<?php

/**
 * AR Entertainment - Universal Frontend Footer Partial
 * 
 * Renders company footer, social channels, dynamic contact info,
 * copyright, scripts, return-to-top widget, and custom footer scripts.
 */

require_once dirname(__DIR__) . '/config/config.php';
require_once dirname(__DIR__) . '/config/helpers.php';

$site_name       = get_setting('site_name', SITE_NAME);
$legal_name      = get_setting('legal_name', SITE_LEGAL_NAME);
$contact_phone   = get_setting('contact_phone', CONTACT_PHONE);
$contact_email   = get_setting('contact_email', CONTACT_EMAIL);
$contact_address = get_setting('contact_address', CONTACT_ADDRESS);
$clean_phone     = preg_replace('/[^\d+]/', '', $contact_phone);

$social_linkedin = get_setting('social_linkedin', 'https://www.linkedin.com/company/ar-entertainment-bd');
$social_facebook = get_setting('social_facebook', 'https://www.facebook.com/arentertainment.bd');
$social_instagram= get_setting('social_instagram', 'https://www.instagram.com/arentertainment.bd');
$social_twitter  = get_setting('social_twitter', 'https://x.com/AREntertainBD');
$social_youtube  = get_setting('social_youtube', 'https://www.youtube.com/@AREntertainmentBD');

$footer_scripts  = get_setting('footer_scripts', '');

$logo_dark_path  = get_setting('site_logo_dark');
$logo_path       = get_setting('site_logo');
$footer_logo_url = !empty($logo_dark_path)
    ? upload_url($logo_dark_path)
    : (!empty($logo_path) ? upload_url($logo_path) : site_url('images/arentertainment-logo.svg'));
?>
    </main>
    <!-- Main Content Area Ends -->

    <!-- Global Footer -->
    <footer class="footer order-12">
        <div class="footer-upper">
            <div class="container-fluid px-3 px-lg-4">
                <div class="row justify-content-between">
                    <!-- Brand & Summary Column -->
                    <div class="col-12 col-lg-3 mb-5 mb-lg-0">
                        <div class="footer-widget text-center text-lg-left pl-lg-3">
                            <a href="<?= site_url() ?>" class="d-inline-block mb-3">
                                <img src="<?= htmlspecialchars($footer_logo_url) ?>" alt="<?= htmlspecialchars($site_name) ?> Logo" width="220" height="58" style="height: auto; max-height: 56px; width: auto; object-fit: contain;">
                            </a>
                            <p class="footer-summary" style="color: #9ca3af; font-size: 14px; line-height: 1.6;">
                                Premier video production house &amp; international film fixer in Bangladesh. High-end TVC, OVC, corporate films, documentaries, and AI-driven content.
                            </p>
                        </div>
                    </div>

                    <!-- Social Media Links -->
                    <div class="col-12 col-md-5 col-lg-3 mb-5 mb-md-0 d-flex flex-column align-items-center align-items-lg-start">
                        <div class="footer-widget links-widget">
                            <div class="widget-title text-center text-lg-left" style="color: #ffffff; font-weight: 700; font-size: 18px; margin-bottom: 20px;">Connect With Us</div>
                            <div class="linkIcons d-flex flex-wrap align-items-center">
                                <?php if (!empty($social_linkedin)): ?>
                                    <div id="linkedin">
                                        <a href="<?= htmlspecialchars($social_linkedin) ?>" title="LinkedIn" target="_blank" rel="noopener noreferrer">
                                            <i class="fa-brands fa-linkedin" style="font-size: 28px; color: #0077b5;"></i>
                                        </a>
                                    </div>
                                <?php endif; ?>

                                <?php if (!empty($social_facebook)): ?>
                                    <div id="facebook" class="ml-3">
                                        <a href="<?= htmlspecialchars($social_facebook) ?>" title="Facebook" target="_blank" rel="noopener noreferrer">
                                            <i class="fa-brands fa-square-facebook" style="font-size: 28px; color: #1877f2;"></i>
                                        </a>
                                    </div>
                                <?php endif; ?>

                                <?php if (!empty($social_instagram)): ?>
                                    <div id="instagram" class="ml-3">
                                        <a href="<?= htmlspecialchars($social_instagram) ?>" title="Instagram" target="_blank" rel="noopener noreferrer">
                                            <i class="fa-brands fa-square-instagram" style="font-size: 28px; color: #e4405f;"></i>
                                        </a>
                                    </div>
                                <?php endif; ?>

                                <?php if (!empty($social_twitter)): ?>
                                    <div id="x" class="ml-3">
                                        <a href="<?= htmlspecialchars($social_twitter) ?>" title="X / Twitter" target="_blank" rel="noopener noreferrer">
                                            <i class="fa-brands fa-square-x-twitter" style="font-size: 28px; color: #ffffff;"></i>
                                        </a>
                                    </div>
                                <?php endif; ?>

                                <?php if (!empty($social_youtube)): ?>
                                    <div id="youtube" class="ml-3">
                                        <a href="<?= htmlspecialchars($social_youtube) ?>" title="YouTube" target="_blank" rel="noopener noreferrer">
                                            <i class="fa-brands fa-youtube" style="font-size: 28px; color: #ff0000;"></i>
                                        </a>
                                    </div>
                                <?php endif; ?>
                            </div>
                        </div>
                    </div>

                    <!-- Office Address -->
                    <div class="col-12 col-md-5 col-lg-3 mb-5 d-flex justify-content-center justify-content-lg-start">
                        <div class="footer-widget links-widget">
                            <div class="widget-title text-center text-lg-left" style="color: #ffffff; font-weight: 700; font-size: 18px; margin-bottom: 20px;">Office Address</div>
                            <address style="color: #9ca3af; font-size: 14px; line-height: 1.6; font-style: normal;">
                                <a href="https://maps.google.com/?q=<?= urlencode($contact_address) ?>" target="_blank" rel="noopener noreferrer" style="color: inherit; text-decoration: none;">
                                    <i class="fa fa-map-marker mr-2" style="color: #e50914;"></i>
                                    <?= nl2br(htmlspecialchars($contact_address)) ?>
                                </a>
                            </address>
                        </div>
                    </div>

                    <!-- Contact & Call Us CTA -->
                    <div class="col-12 col-lg-3 mb-5 d-flex flex-column align-items-center align-items-lg-end">
                        <div class="footer-widget links-widget pr-lg-3 text-center text-lg-right">
                            <div class="widget-title text-center text-lg-right" style="color: #ffffff; font-weight: 700; font-size: 18px; margin-bottom: 20px;">Direct Inquiries</div>
                            <a class="cta-box d-inline-flex align-items-center" href="tel:<?= htmlspecialchars($clean_phone) ?>" style="background: rgba(229, 9, 20, 0.1); border: 1px solid rgba(229, 9, 20, 0.4); padding: 10px 18px; border-radius: 8px; color: #fff; text-decoration: none;">
                                <div class="mr-3">
                                    <i class="fa fa-phone-square" style="font-size: 24px; color: #e50914;"></i>
                                </div>
                                <div class="text-left">
                                    <p class="mb-0" style="font-weight: 700; font-size: 15px;"><?= htmlspecialchars($contact_phone) ?></p>
                                    <small style="color: #9ca3af;"><?= htmlspecialchars($contact_email) ?></small>
                                </div>
                            </a>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Footer Bottom Bar -->
        <div class="footer-bottom">
            <div class="container">
                <div class="row">
                    <div class="col text-center links-widget">
                        <p class="mb-2">
                            <a href="<?= site_url('about-us') ?>">About Us</a> |
                            <a href="<?= site_url('services') ?>">Services</a> |
                            <a href="<?= site_url('portfolio') ?>">Portfolio</a> |
                            <a href="<?= site_url('privacy') ?>">Privacy Policy</a> |
                            <a href="<?= site_url('contact-us') ?>">Contact Us</a> |
                            <a href="<?= site_url('sitemap.xml') ?>">Sitemap</a>
                        </p>
                        <p class="mb-0" style="font-size: 13px; color: #6b7280;">
                            Copyright &copy; <?= date('Y') ?> <strong><?= htmlspecialchars($legal_name) ?></strong> | All rights reserved.
                        </p>
                    </div>
                </div>
            </div>
        </div>
    </footer>

    <!-- Return to Top Floating Button -->
    <div class="scroll-to-top">
        <span id="return-to-top" style="display: none; cursor: pointer;">
            <i class="fa-solid fa-chevron-up" style="font-size: 18px; line-height: 44px; color: #fff;"></i>
        </span>
    </div>
</div>

<!-- Essential Vendor JavaScript Libraries -->
<script src="https://cdnjs.cloudflare.com/ajax/libs/twitter-bootstrap/4.6.0/js/bootstrap.bundle.min.js" defer></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/OwlCarousel2/2.3.4/owl.carousel.min.js" defer></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/lightbox2/2.11.3/js/lightbox.min.js" defer></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/lazysizes/5.2.2/lazysizes.min.js" defer></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/fancybox/3.5.7/jquery.fancybox.min.js" defer></script>

<!-- Project Main Script -->
<script src="<?= asset_url('script/script.js') ?>" defer></script>

<!-- Custom Footer Scripts from Admin Settings -->
<?php if (!empty($footer_scripts)): ?>
    <?= $footer_scripts ?>
<?php endif; ?>

<!-- Extra Footer Injections from Specific Pages -->
<?php if (!empty($extra_footer)): ?>
    <?= $extra_footer ?>
<?php endif; ?>

</body>
</html>
