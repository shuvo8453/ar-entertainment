<?php

/**
 * AR Entertainment - Universal Contact & Production Inquiry Page
 * 
 * High-conversion contact page with dynamic contact details, real-time database lead capture,
 * CSRF & honeypot spam protection, responsive layout, Google Map embed, and FAQ section.
 */

require_once __DIR__ . '/config/config.php';
require_once __DIR__ . '/config/helpers.php';

$page_title       = 'Contact Us';
$page_description = 'Get in touch with AR Entertainment for TV commercials, online video commercials (OVC), corporate brand films, documentaries, and international film fixer services in Bangladesh.';
$page_keywords    = 'Contact AR Entertainment, Video Production Dhaka, Film Fixer Bangladesh, TVC Production House, Video Making Agency Dhaka, Corporate AV Maker BD';
$current_page     = 'contact-us';

$contact_phone    = get_setting('contact_phone', CONTACT_PHONE);
$contact_email    = get_setting('contact_email', CONTACT_EMAIL);
$contact_address  = get_setting('contact_address', CONTACT_ADDRESS);
$clean_phone      = preg_replace('/[^\d+]/', '', $contact_phone);

// -----------------------------------------------------------------------------
// Form Submission Processing
// -----------------------------------------------------------------------------
$form_success = false;
$form_errors  = [];

$form_name     = '';
$form_email    = '';
$form_phone    = '';
$form_company  = '';
$form_service  = '';
$form_budget   = '';
$form_message  = '';

if (($_SERVER['REQUEST_METHOD'] ?? '') === 'POST') {
    // 1. Honeypot check (anti-bot)
    if (!empty($_POST['website_hp'])) {
        // Silent rejection for bots
        $form_success = true;
    } else {
        // 2. CSRF Token verification
        if (!verify_csrf()) {
            $form_errors[] = 'Security verification failed (CSRF token expired). Please refresh and try again.';
        } else {
            $form_name    = trim($_POST['name'] ?? '');
            $form_email   = trim($_POST['email'] ?? '');
            $form_phone   = trim($_POST['phone'] ?? '');
            $form_company = trim($_POST['company'] ?? '');
            $form_service = trim($_POST['service_type'] ?? 'General Inquiry');
            $form_budget  = trim($_POST['budget'] ?? '');
            $form_message = trim($_POST['message'] ?? '');

            // Validation
            if (empty($form_name)) {
                $form_errors[] = 'Please enter your full name.';
            }

            if (empty($form_email) || !filter_var($form_email, FILTER_VALIDATE_EMAIL)) {
                $form_errors[] = 'Please enter a valid email address.';
            }

            if (empty($form_phone)) {
                $form_errors[] = 'Please enter your phone or WhatsApp number.';
            }

            if (empty($form_message)) {
                $form_errors[] = 'Please describe your project or inquiry.';
            }

            // Save to database inquiries table
            if (empty($form_errors)) {
                try {
                    $extra_data = [
                        'company' => $form_company,
                        'budget'  => $form_budget,
                        'service' => $form_service,
                        'source'  => 'contact_page'
                    ];

                    $sql = "
                        INSERT INTO inquiries (
                            form_type, name, email, phone, subject,
                            message, extra_data_json, is_read, ip_address, user_agent, created_at
                        ) VALUES (
                            'contact', :name, :email, :phone, :subject,
                            :message, :extra_data_json, 0, :ip_address, :user_agent, NOW()
                        )
                    ";

                    $stmt = db()->prepare($sql);
                    $stmt->execute([
                        ':name'            => $form_name,
                        ':email'           => $form_email,
                        ':phone'           => $form_phone,
                        ':subject'         => $form_service,
                        ':message'         => $form_message,
                        ':extra_data_json' => json_encode($extra_data, JSON_UNESCAPED_SLASHES | JSON_UNESCAPED_UNICODE),
                        ':ip_address'      => $_SERVER['REMOTE_ADDR'] ?? null,
                        ':user_agent'      => mb_substr($_SERVER['HTTP_USER_AGENT'] ?? '', 0, 255)
                    ]);

                    $form_success = true;
                    // Reset form fields
                    $form_name = $form_email = $form_phone = $form_company = $form_service = $form_budget = $form_message = '';
                } catch (PDOException $e) {
                    $form_errors[] = 'Failed to submit inquiry. Please call us directly at ' . htmlspecialchars($contact_phone);
                }
            }
        }
    }
}

// Page schema definition
$page_schema = [
    '@type' => 'ContactPage',
    '@id'   => site_url('contact-us') . '#contactpage',
    'url'   => site_url('contact-us'),
    'name'  => 'Contact AR Entertainment | Video Production & Film Fixer in Bangladesh',
    'description' => $page_description
];

require_once __DIR__ . '/includes/header.php';
require_once __DIR__ . '/includes/navbar.php';
?>

<!-- Inner Banner / Hero Header -->
<div class="inner-banner-area">
    <div class="inner-banner" style="background-image: linear-gradient(rgba(15, 16, 22, 0.85), rgba(15, 16, 22, 0.95)), url('<?= asset_url('../images/banner/contact-us.jpg') ?>'); background-size: cover; background-position: center;">
        <div class="container">
            <div class="banner-content text-center py-5">
                <nav aria-label="breadcrumb">
                    <ul class="d-inline-flex list-unstyled gap-2 align-items-center justify-content-center flex-wrap mb-2" style="font-size: 14px;">
                        <li><a href="<?= site_url() ?>" style="color: #e50914; text-decoration: none;"><i class="fa fa-home"></i> Home</a></li>
                        <li style="color: #6b7280;">/</li>
                        <li style="color: #6b7280;"><a href="<?= site_url('about-us') ?>" style="color: #9ca3af; text-decoration: none;">About Us</a></li>
                        <li style="color: #6b7280;">/</li>
                        <li style="color: #f3f4f6; font-weight: 600;">Contact Us</li>
                    </ul>
                </nav>
                <h1 class="display-4 font-weight-bold text-white mb-3" style="letter-spacing: -0.5px;">Get In Touch With <span style="color: #e50914;">AR Entertainment</span></h1>
                <p class="lead mx-auto mb-0" style="max-width: 720px; color: #9ca3af; font-size: 16px;">
                    Have an upcoming TVC, OVC, corporate video, documentary, or international shoot in Bangladesh? Reach out today for creative briefs, technical quotes, or fixer assistance.
                </p>
            </div>
        </div>
    </div>
</div>

<!-- Main Contact Section -->
<section class="contact-main-section py-5" style="background-color: #0f1016; color: #f1f2f6;">
    <div class="container py-lg-4">
        <div class="row g-4 justify-content-between">
            
            <!-- Left Column: Contact Cards & Studio Info -->
            <div class="col-12 col-lg-5 mb-5 mb-lg-0">
                <div class="contact-info-wrapper">
                    <div class="section-badge mb-3 text-uppercase font-weight-bold" style="color: #e50914; font-size: 13px; letter-spacing: 1.5px;">
                        <i class="fa-solid fa-headset mr-2"></i> Connect Direct
                    </div>
                    <h2 class="h2 font-weight-bold text-white mb-4">Let's Bring Your Production Vision to Life</h2>
                    <p class="text-muted mb-4" style="line-height: 1.7;">
                        Whether you are an international broadcaster requiring fixer logistics or a local enterprise launching a flagship commercial, our team is available 24/7 to support your production goals.
                    </p>

                    <!-- Contact Details Cards -->
                    <div class="d-flex flex-column gap-3">
                        <!-- Phone & WhatsApp Card -->
                        <div class="p-3 rounded-lg mb-3 d-flex align-items-start" style="background: rgba(255, 255, 255, 0.03); border: 1px solid rgba(255, 255, 255, 0.08); border-radius: 12px;">
                            <div class="icon-box mr-3 d-flex align-items-center justify-content-center" style="width: 48px; height: 48px; background: rgba(229, 9, 20, 0.15); color: #e50914; border-radius: 10px; font-size: 20px; flex-shrink: 0;">
                                <i class="fa-solid fa-phone-volume"></i>
                            </div>
                            <div>
                                <div class="text-muted small text-uppercase font-weight-bold" style="letter-spacing: 0.5px;">Direct Phone &amp; WhatsApp</div>
                                <div class="mt-1">
                                    <a href="tel:<?= htmlspecialchars($clean_phone) ?>" class="text-white font-weight-bold" style="text-decoration: none; font-size: 16px;">
                                        <?= htmlspecialchars($contact_phone) ?>
                                    </a>
                                </div>
                                <small class="text-muted">Available 24/7 for urgent production support</small>
                            </div>
                        </div>

                        <!-- Email Card -->
                        <div class="p-3 rounded-lg mb-3 d-flex align-items-start" style="background: rgba(255, 255, 255, 0.03); border: 1px solid rgba(255, 255, 255, 0.08); border-radius: 12px;">
                            <div class="icon-box mr-3 d-flex align-items-center justify-content-center" style="width: 48px; height: 48px; background: rgba(24, 144, 255, 0.15); color: #1890ff; border-radius: 10px; font-size: 20px; flex-shrink: 0;">
                                <i class="fa-solid fa-envelope"></i>
                            </div>
                            <div>
                                <div class="text-muted small text-uppercase font-weight-bold" style="letter-spacing: 0.5px;">Official Inquiries Email</div>
                                <div class="mt-1">
                                    <a href="mailto:<?= htmlspecialchars($contact_email) ?>" class="text-white font-weight-bold" style="text-decoration: none; font-size: 16px;">
                                        <?= htmlspecialchars($contact_email) ?>
                                    </a>
                                </div>
                                <small class="text-muted">Expected response within 2-4 business hours</small>
                            </div>
                        </div>

                        <!-- Office Address Card -->
                        <div class="p-3 rounded-lg mb-3 d-flex align-items-start" style="background: rgba(255, 255, 255, 0.03); border: 1px solid rgba(255, 255, 255, 0.08); border-radius: 12px;">
                            <div class="icon-box mr-3 d-flex align-items-center justify-content-center" style="width: 48px; height: 48px; background: rgba(46, 213, 115, 0.15); color: #2ed573; border-radius: 10px; font-size: 20px; flex-shrink: 0;">
                                <i class="fa-solid fa-location-dot"></i>
                            </div>
                            <div>
                                <div class="text-muted small text-uppercase font-weight-bold" style="letter-spacing: 0.5px;">Studio &amp; Headquarters</div>
                                <div class="mt-1 text-white font-weight-bold" style="font-size: 15px; line-height: 1.5;">
                                    <?= nl2br(htmlspecialchars($contact_address)) ?>
                                </div>
                                <small class="text-muted">Gulshan 1, Dhaka 1212, Bangladesh</small>
                            </div>
                        </div>

                        <!-- Working Hours Card -->
                        <div class="p-3 rounded-lg mb-4 d-flex align-items-start" style="background: rgba(255, 255, 255, 0.03); border: 1px solid rgba(255, 255, 255, 0.08); border-radius: 12px;">
                            <div class="icon-box mr-3 d-flex align-items-center justify-content-center" style="width: 48px; height: 48px; background: rgba(255, 159, 26, 0.15); color: #ff9f1a; border-radius: 10px; font-size: 20px; flex-shrink: 0;">
                                <i class="fa-solid fa-clock"></i>
                            </div>
                            <div>
                                <div class="text-muted small text-uppercase font-weight-bold" style="letter-spacing: 0.5px;">Office Hours</div>
                                <div class="mt-1 text-white font-weight-bold" style="font-size: 15px;">
                                    Saturday &ndash; Thursday: 10:00 AM &ndash; 8:00 PM
                                </div>
                                <small class="text-muted">Friday: Closed / On-Location Shoots Only</small>
                            </div>
                        </div>
                    </div>

                    <!-- WhatsApp Quick Trigger -->
                    <div class="mt-2">
                        <a href="https://wa.me/<?= preg_replace('/[^\d]/', '', $contact_phone) ?>?text=Hello%20AR%20Entertainment,%20I%20would%20like%20to%20inquire%20about%20a%20video%20production%20project." target="_blank" rel="noopener noreferrer" class="btn btn-lg w-100 font-weight-bold d-flex align-items-center justify-content-center" style="background-color: #25D366; color: #fff; border-radius: 10px; padding: 12px 24px; text-decoration: none;">
                            <i class="fa-brands fa-whatsapp mr-2" style="font-size: 22px;"></i> Chat on WhatsApp Now
                        </a>
                    </div>
                </div>
            </div>

            <!-- Right Column: Interactive Inquiry Form -->
            <div class="col-12 col-lg-7">
                <div class="p-4 p-md-5 rounded-lg shadow-lg" style="background: #14151f; border: 1px solid rgba(255, 255, 255, 0.08); border-radius: 16px;">
                    <div class="mb-4">
                        <h3 class="h3 font-weight-bold text-white mb-2">Request a Quote &amp; Production Proposal</h3>
                        <p class="text-muted small mb-0">Fill in your requirements below and our producer will respond with estimated budgets and timelines.</p>
                    </div>

                    <!-- Success Message Alert -->
                    <?php if ($form_success): ?>
                        <div class="alert alert-success d-flex align-items-center mb-4" role="alert" style="background: rgba(46, 213, 115, 0.15); border: 1px solid #2ed573; color: #2ed573; border-radius: 10px; padding: 16px;">
                            <i class="fa-solid fa-circle-check mr-3" style="font-size: 24px;"></i>
                            <div>
                                <strong class="d-block">Inquiry Received Successfully!</strong>
                                Thank you for contacting AR Entertainment. Our executive production team has received your brief and will contact you shortly.
                            </div>
                        </div>
                    <?php endif; ?>

                    <!-- Error Alert -->
                    <?php if (!empty($form_errors)): ?>
                        <div class="alert alert-danger mb-4" role="alert" style="background: rgba(229, 9, 20, 0.15); border: 1px solid #e50914; color: #ff6b6b; border-radius: 10px;">
                            <div class="font-weight-bold mb-1"><i class="fa-solid fa-triangle-exclamation mr-1"></i> Please fix the following:</div>
                            <ul class="mb-0 pl-3">
                                <?php foreach ($form_errors as $err): ?>
                                    <li><?= htmlspecialchars($err) ?></li>
                                <?php endforeach; ?>
                            </ul>
                        </div>
                    <?php endif; ?>

                    <form method="POST" action="<?= site_url('contact-us') ?>" id="contactForm">
                        <?= csrf_field() ?>
                        <!-- Invisible Honeypot Anti-Spam Field -->
                        <div style="display: none !important;">
                            <input type="text" name="website_hp" tabindex="-1" autocomplete="off">
                        </div>

                        <div class="row g-3">
                            <!-- Full Name -->
                            <div class="col-12 col-md-6 mb-3">
                                <label for="formName" class="form-label text-white small font-weight-bold">Your Full Name <span class="text-danger">*</span></label>
                                <input type="text" name="name" id="formName" class="form-control" placeholder="e.g. Tanvir Ahmed" value="<?= htmlspecialchars($form_name) ?>" required style="background: #0f1016; border: 1px solid #282a3c; color: #fff; border-radius: 8px; padding: 10px 14px;">
                            </div>

                            <!-- Email Address -->
                            <div class="col-12 col-md-6 mb-3">
                                <label for="formEmail" class="form-label text-white small font-weight-bold">Email Address <span class="text-danger">*</span></label>
                                <input type="email" name="email" id="formEmail" class="form-control" placeholder="e.g. tanvir@company.com" value="<?= htmlspecialchars($form_email) ?>" required style="background: #0f1016; border: 1px solid #282a3c; color: #fff; border-radius: 8px; padding: 10px 14px;">
                            </div>

                            <!-- Phone Number -->
                            <div class="col-12 col-md-6 mb-3">
                                <label for="formPhone" class="form-label text-white small font-weight-bold">Phone / WhatsApp Number <span class="text-danger">*</span></label>
                                <input type="text" name="phone" id="formPhone" class="form-control" placeholder="e.g. +880 1711 000000" value="<?= htmlspecialchars($form_phone) ?>" required style="background: #0f1016; border: 1px solid #282a3c; color: #fff; border-radius: 8px; padding: 10px 14px;">
                            </div>

                            <!-- Company / Organization -->
                            <div class="col-12 col-md-6 mb-3">
                                <label for="formCompany" class="form-label text-white small font-weight-bold">Company / Brand Name <span class="text-muted">(Optional)</span></label>
                                <input type="text" name="company" id="formCompany" class="form-control" placeholder="e.g. Apex Footwear Ltd." value="<?= htmlspecialchars($form_company) ?>" style="background: #0f1016; border: 1px solid #282a3c; color: #fff; border-radius: 8px; padding: 10px 14px;">
                            </div>

                            <!-- Service / Project Category -->
                            <div class="col-12 col-md-6 mb-3">
                                <label for="formService" class="form-label text-white small font-weight-bold">Project Category <span class="text-danger">*</span></label>
                                <select name="service_type" id="formService" class="form-control" required style="background: #0f1016; border: 1px solid #282a3c; color: #fff; border-radius: 8px; padding: 10px 14px; height: 45px;">
                                    <option value="" disabled <?= empty($form_service) ? 'selected' : '' ?>>Select Production Type</option>
                                    <option value="TV Commercial (TVC)" <?= ($form_service === 'TV Commercial (TVC)') ? 'selected' : '' ?>>Television Commercial (TVC)</option>
                                    <option value="Online Video Commercial (OVC)" <?= ($form_service === 'Online Video Commercial (OVC)') ? 'selected' : '' ?>>Online Video Commercial (OVC / Digital Ad)</option>
                                    <option value="Corporate AV & Brand Film" <?= ($form_service === 'Corporate AV & Brand Film') ? 'selected' : '' ?>>Corporate AV &amp; Factory / Brand Film</option>
                                    <option value="Film Fixer in Bangladesh" <?= ($form_service === 'Film Fixer in Bangladesh') ? 'selected' : '' ?>>Film Fixer &amp; International Production Support</option>
                                    <option value="AI Video Production & Dubbing" <?= ($form_service === 'AI Video Production & Dubbing') ? 'selected' : '' ?>>AI Video Production &amp; AI Dubbing</option>
                                    <option value="Documentary & NGO Film" <?= ($form_service === 'Documentary & NGO Film') ? 'selected' : '' ?>>Documentary &amp; NGO Social Impact Film</option>
                                    <option value="Music Video & Theme Song" <?= ($form_service === 'Music Video & Theme Song') ? 'selected' : '' ?>>Music Video, Jingle &amp; Brand Theme Song</option>
                                    <option value="2D / 3D Animation & VFX" <?= ($form_service === '2D / 3D Animation & VFX') ? 'selected' : '' ?>>2D &amp; 3D Animation / Motion Graphics</option>
                                    <option value="Other Production Inquiry" <?= ($form_service === 'Other Production Inquiry') ? 'selected' : '' ?>>Other / General Inquiry</option>
                                </select>
                            </div>

                            <!-- Estimated Budget Range -->
                            <div class="col-12 col-md-6 mb-3">
                                <label for="formBudget" class="form-label text-white small font-weight-bold">Estimated Budget Range</label>
                                <select name="budget" id="formBudget" class="form-control" style="background: #0f1016; border: 1px solid #282a3c; color: #fff; border-radius: 8px; padding: 10px 14px; height: 45px;">
                                    <option value="" selected>Flexible / Need Recommendation</option>
                                    <option value="Under 2 Lac BDT ($1,500 - $2,500)">Under 2 Lac BDT (Starter Digital OVC)</option>
                                    <option value="2 to 5 Lac BDT ($2,500 - $5,000)">2 &ndash; 5 Lac BDT (Mid-tier Production)</option>
                                    <option value="5 to 15 Lac BDT ($5,000 - $15,000)">5 &ndash; 15 Lac BDT (Standard Commercial / Fixer)</option>
                                    <option value="15+ Lac BDT ($15,000+)">15+ Lac BDT (High-end TVC / International)</option>
                                </select>
                            </div>

                            <!-- Project Brief / Message -->
                            <div class="col-12 mb-4">
                                <label for="formMessage" class="form-label text-white small font-weight-bold">Project Scope / Message <span class="text-danger">*</span></label>
                                <textarea name="message" id="formMessage" rows="5" class="form-control" placeholder="Describe your video requirements, target audience, preferred shooting locations, or deadlines..." required style="background: #0f1016; border: 1px solid #282a3c; color: #fff; border-radius: 8px; padding: 12px 14px;"><?= htmlspecialchars($form_message) ?></textarea>
                            </div>

                            <!-- Submit Button -->
                            <div class="col-12">
                                <button type="submit" class="btn btn-lg w-100 font-weight-bold text-white shadow-sm" style="background: linear-gradient(135deg, #e50914 0%, #b80710 100%); border: none; border-radius: 10px; padding: 14px 28px; font-size: 16px;">
                                    <i class="fa-solid fa-paper-plane mr-2"></i> Submit Production Inquiry
                                </button>
                                <div class="text-center text-muted small mt-2">
                                    <i class="fa-solid fa-lock mr-1"></i> Your information is kept strictly confidential. No spam guaranteed.
                                </div>
                            </div>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</section>

<!-- Studio Location Interactive Map Section -->
<section class="studio-map-section" style="background-color: #0b0c10;">
    <div class="container-fluid p-0">
        <div class="ratio ratio-21x9" style="width: 100%; height: 420px; position: relative;">
            <iframe 
                src="https://www.google.com/maps/embed?pb=!1m18!1m12!1m3!1d3651.584347719602!2d90.41434757602334!3d23.779998088237976!2m3!1f0!2f0!3f0!3m2!1i1024!2i768!4f13.1!3m3!1m2!1s0x3755c77728f32279%3A0x74112e5f385c9603!2sNiketan%2C%20Gulshan%201%2C%20Dhaka%201212!5e0!3m2!1sen!2sbd!4v1710000000000!5m2!1sen!2sbd" 
                width="100%" 
                height="420" 
                style="border:0; filter: grayscale(80%) invert(90%) contrast(85%);" 
                allowfullscreen="" 
                loading="lazy" 
                referrerpolicy="no-referrer-when-downgrade"
                title="AR Entertainment Office Location Map">
            </iframe>
        </div>
    </div>
</section>

<!-- FAQ Accordion Component Inclusion -->
<section class="py-5" style="background-color: #14151f; border-top: 1px solid rgba(255, 255, 255, 0.05);">
    <div class="container py-lg-4">
        <div class="text-center mb-5">
            <div class="text-uppercase font-weight-bold mb-2" style="color: #e50914; font-size: 13px; letter-spacing: 1px;">Frequently Asked Questions</div>
            <h2 class="h2 font-weight-bold text-white">Working With AR Entertainment</h2>
        </div>

        <div class="row justify-content-center">
            <div class="col-12 col-lg-9">
                <div class="accordion" id="contactFaq">
                    <!-- Q1 -->
                    <div class="card mb-3" style="background: #0f1016; border: 1px solid rgba(255, 255, 255, 0.08); border-radius: 10px; overflow: hidden;">
                        <div class="card-header p-3" id="faqHeading1" style="background: transparent; border: none; cursor: pointer;" data-toggle="collapse" data-target="#faqCollapse1" aria-expanded="true">
                            <h5 class="mb-0 text-white font-weight-bold d-flex justify-content-between align-items-center" style="font-size: 16px;">
                                <span>How quickly can AR Entertainment mobilize a production crew in Bangladesh?</span>
                                <i class="fa-solid fa-chevron-down text-danger"></i>
                            </h5>
                        </div>
                        <div id="faqCollapse1" class="collapse show" data-parent="#contactFaq">
                            <div class="card-body text-muted pt-0" style="line-height: 1.7;">
                                For standard digital commercials (OVC) and documentary shoots, we can deploy director, DoP, and lighting crew within <strong>24 to 48 hours</strong> across Dhaka and major divisional cities. For large-scale TVCs and international productions requiring complex set builds or foreign gear clearance, typical pre-production turnaround is 5 to 10 working days.
                            </div>
                        </div>
                    </div>

                    <!-- Q2 -->
                    <div class="card mb-3" style="background: #0f1016; border: 1px solid rgba(255, 255, 255, 0.08); border-radius: 10px; overflow: hidden;">
                        <div class="card-header p-3" id="faqHeading2" style="background: transparent; border: none; cursor: pointer;" data-toggle="collapse" data-target="#faqCollapse2">
                            <h5 class="mb-0 text-white font-weight-bold d-flex justify-content-between align-items-center" style="font-size: 16px;">
                                <span>Do you provide full film fixing, drone permits, and government clearance in Bangladesh?</span>
                                <i class="fa-solid fa-chevron-down text-danger"></i>
                            </h5>
                        </div>
                        <div id="faqCollapse2" class="collapse" data-parent="#contactFaq">
                            <div class="card-body text-muted pt-0" style="line-height: 1.7;">
                                Yes. AR Entertainment is a licensed film fixer and production support partner for international broadcasters, NGOs, and global creative agencies. We handle Ministry of Information filming permits, FF Visa invitations, CAAB drone flight approvals, airport customs clearance (ATA Carnet), local security, and nationwide location scouting.
                            </div>
                        </div>
                    </div>

                    <!-- Q3 -->
                    <div class="card mb-3" style="background: #0f1016; border: 1px solid rgba(255, 255, 255, 0.08); border-radius: 10px; overflow: hidden;">
                        <div class="card-header p-3" id="faqHeading3" style="background: transparent; border: none; cursor: pointer;" data-toggle="collapse" data-target="#faqCollapse3">
                            <h5 class="mb-0 text-white font-weight-bold d-flex justify-content-between align-items-center" style="font-size: 16px;">
                                <span>Can we visit your office in Niketan, Gulshan 1 to discuss a project in person?</span>
                                <i class="fa-solid fa-chevron-down text-danger"></i>
                            </h5>
                        </div>
                        <div id="faqCollapse3" class="collapse" data-parent="#contactFaq">
                            <div class="card-body text-muted pt-0" style="line-height: 1.7;">
                                Absolutely! You are always welcome to visit our studio at <strong>Apt 4-S, House 62, Road 14/1, Block G, Niketan, Gulshan 1, Dhaka 1212</strong>. We recommend scheduling an appointment by calling <strong><?= htmlspecialchars($contact_phone) ?></strong> so our director and production team can dedicate uninterrupted time to your brief.
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</section>

<?php require_once __DIR__ . '/includes/footer.php'; ?>
