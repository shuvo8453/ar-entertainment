<?php

/**
 * AR Entertainment - Universal Call-To-Action (CTA) Component
 * 
 * High-converting banner customizable per view or included globally.
 */

require_once dirname(__DIR__) . '/config/config.php';
require_once dirname(__DIR__) . '/config/helpers.php';

$cta_heading      = $cta_title ?? 'Ready to Bring Your Film & Video Vision to Life?';
$cta_description  = $cta_subtitle ?? 'From international film fixing and broadcast TV commercials to high-impact corporate AVs and cutting-edge AI video production, AR Entertainment delivers world-class execution across Bangladesh.';
$cta_btn_label    = $cta_primary_btn_text ?? 'Request a Free Quote';
$cta_btn_link     = $cta_primary_btn_url ?? site_url('contact-us');
$cta_phone        = get_setting('contact_phone', CONTACT_PHONE);
$clean_cta_phone  = preg_replace('/[^\d+]/', '', $cta_phone);
?>
<section class="ar-cta-section py-5 my-5" style="background: linear-gradient(135deg, #13141f 0%, #1f0b0f 50%, #13141f 100%); border-top: 1px solid #2d1519; border-bottom: 1px solid #2d1519; position: relative; overflow: hidden;">
    <div class="container py-4">
        <div class="row align-items-center justify-content-between">
            <div class="col-lg-8 text-center text-lg-left mb-4 mb-lg-0">
                <div class="d-inline-flex align-items-center mb-3 px-3 py-1 rounded-pill" style="background: rgba(229, 9, 20, 0.15); border: 1px solid rgba(229, 9, 20, 0.4); color: #ff4d58; font-size: 13px; font-weight: 700; letter-spacing: 1px;">
                    <i class="fa-solid fa-film mr-2"></i> LET'S PRODUCE TOGETHER
                </div>
                <h2 class="h1 text-white font-weight-bold mb-3" style="font-family: 'Plus Jakarta Sans', sans-serif; letter-spacing: -0.5px;">
                    <?= htmlspecialchars($cta_heading) ?>
                </h2>
                <p class="lead text-muted mb-0" style="color: #cbd5e1 !important; font-size: 16px; max-width: 680px;">
                    <?= htmlspecialchars($cta_description) ?>
                </p>
            </div>
            <div class="col-lg-4 text-center text-lg-right">
                <div class="d-flex flex-column flex-sm-row justify-content-center justify-content-lg-end align-items-center gap-3">
                    <a href="<?= htmlspecialchars($cta_btn_link) ?>" class="btn btn-danger btn-lg px-4 py-3 font-weight-bold text-uppercase shadow-lg mb-3 mb-sm-0" style="background: #e50914; border: none; border-radius: 6px; letter-spacing: 1px; font-size: 14px;">
                        <?= htmlspecialchars($cta_btn_label) ?> <i class="fa-solid fa-arrow-right ml-2"></i>
                    </a>
                    <a href="tel:<?= htmlspecialchars($clean_cta_phone) ?>" class="btn btn-outline-light btn-lg px-3 py-3 font-weight-bold ml-sm-3" style="border-radius: 6px; font-size: 14px; border-color: #4b5563;">
                        <i class="fa-solid fa-phone mr-2" style="color: #e50914;"></i> Call Us
                    </a>
                </div>
            </div>
        </div>
    </div>
</section>
