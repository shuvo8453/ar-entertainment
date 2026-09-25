<?php

/**
 * AR Entertainment - Universal Frontend Header Partial
 * 
 * Handles dynamic SEO meta tags, OpenGraph, Twitter Cards, Schema.org JSON-LD,
 * Google Analytics 4, Meta Pixel, Google Ads, fonts, stylesheets, and custom header scripts.
 */

require_once dirname(__DIR__) . '/config/config.php';
require_once dirname(__DIR__) . '/config/helpers.php';

// -----------------------------------------------------------------------------
// Dynamic Page Variables with Fallbacks
// -----------------------------------------------------------------------------
$site_name        = get_setting('site_name', SITE_NAME);
$legal_name       = get_setting('legal_name', SITE_LEGAL_NAME);
$default_title    = get_setting('site_title', SITE_TAGLINE . ' | ' . SITE_NAME);
$site_desc        = get_setting('site_description', 'AR Entertainment is a premier Dhaka-based video production house and film fixer for local and international productions.');
$site_keywords    = get_setting('site_keywords', 'Video Production Bangladesh, TVC, Film Fixer in Bangladesh, AI Video Production Bangladesh, Corporate AV Production, Film Production House Bangladesh');
$contact_phone    = get_setting('contact_phone', CONTACT_PHONE);
$contact_email    = get_setting('contact_email', CONTACT_EMAIL);
$contact_address  = get_setting('contact_address', CONTACT_ADDRESS);
$founder_name     = get_setting('founder_name', 'Azizul Hoque Shiplu');
$founder_title    = get_setting('founder_title', 'Founder and Film Director');
$founding_year    = get_setting('founding_year', '2018');

// Current Page Specific Overrides
$meta_title       = !empty($page_title) ? $page_title . ' | ' . $site_name : $default_title;
$meta_desc        = !empty($page_description) ? $page_description : $site_desc;
$meta_keywords    = !empty($page_keywords) ? $page_keywords : $site_keywords;
$meta_canonical   = !empty($canonical_url) ? $canonical_url : (function_exists('site_url') ? site_url($_SERVER['REQUEST_URI'] ?? '') : '');
$meta_og_type     = $og_type ?? 'website';
$meta_og_image    = !empty($og_image) ? upload_url($og_image) : site_url('images/og.webp');

$logo_path        = get_setting('site_logo');
if (empty($logo_path) || $logo_path === 'images/arentertainment-logo.svg') {
    $site_logo_url = site_url('images/arentertainment-brand-logo.svg');
} else {
    $site_logo_url = upload_url($logo_path);
}

// Tracking IDs
$ga4_id           = get_setting('ga4_id', 'G-LYXSSGVBJF');
$meta_pixel_id    = get_setting('meta_pixel_id', '969321647479793');
$google_ads_id    = get_setting('google_ads_id', 'AW-11262226603');
$header_scripts   = get_setting('header_scripts', '');

// Social Links for Schema
$social_links = array_filter([
    get_setting('social_facebook', 'https://www.facebook.com/arentertainment.bd'),
    get_setting('social_linkedin', 'https://www.linkedin.com/company/ar-entertainment-bd'),
    get_setting('social_instagram', 'https://www.instagram.com/arentertainment.bd'),
    get_setting('social_youtube', 'https://www.youtube.com/@AREntertainmentBD'),
    get_setting('social_twitter', 'https://x.com/AREntertainBD'),
]);
?>
<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0, maximum-scale=5.0">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="theme-color" content="#0f1016">

    <!-- SEO Title & Meta Description -->
    <title><?= htmlspecialchars($meta_title) ?></title>
    <meta name="description" content="<?= htmlspecialchars($meta_desc) ?>">
    <meta name="keywords" content="<?= htmlspecialchars($meta_keywords) ?>">
    <meta name="robots" content="index, follow, max-snippet:-1, max-image-preview:large, max-video-preview:-1">
    <meta name="author" content="<?= htmlspecialchars($founder_name) ?>">

    <!-- Canonical URL -->
    <?php if (!empty($meta_canonical)): ?>
        <link rel="canonical" href="<?= htmlspecialchars($meta_canonical) ?>">
    <?php endif; ?>

    <!-- Favicons -->
    <?php 
    $fav_path = get_setting('site_favicon');
    $frontend_favicon = !empty($fav_path) ? upload_url($fav_path) : site_url('images/favicon.ico');
    $fav_ext = strtolower(pathinfo($fav_path, PATHINFO_EXTENSION));
    $fav_mime = ($fav_ext === 'avif') ? 'image/avif' : (($fav_ext === 'svg') ? 'image/svg+xml' : (($fav_ext === 'png') ? 'image/png' : 'image/x-icon'));
    ?>
    <link rel="icon" type="<?= $fav_mime ?>" href="<?= htmlspecialchars($frontend_favicon) ?>">
    <link rel="shortcut icon" href="<?= htmlspecialchars($frontend_favicon) ?>">

    <!-- Open Graph (Facebook / LinkedIn) -->
    <meta property="og:title" content="<?= htmlspecialchars($meta_title) ?>">
    <meta property="og:description" content="<?= htmlspecialchars($meta_desc) ?>">
    <meta property="og:url" content="<?= htmlspecialchars($meta_canonical) ?>">
    <meta property="og:image" content="<?= htmlspecialchars($meta_og_image) ?>">
    <meta property="og:image:width" content="1200">
    <meta property="og:image:height" content="630">
    <meta property="og:image:alt" content="<?= htmlspecialchars($site_name) ?> - Video Production & Film Fixer in Bangladesh">
    <meta property="og:type" content="<?= htmlspecialchars($meta_og_type) ?>">
    <meta property="og:site_name" content="<?= htmlspecialchars($site_name) ?>">
    <meta property="og:locale" content="en_US">

    <!-- Twitter Cards -->
    <meta name="twitter:card" content="summary_large_image">
    <meta name="twitter:title" content="<?= htmlspecialchars($meta_title) ?>">
    <meta name="twitter:description" content="<?= htmlspecialchars($meta_desc) ?>">
    <meta name="twitter:image" content="<?= htmlspecialchars($meta_og_image) ?>">
    <meta name="twitter:site" content="@AREntertainBD">
    <meta name="twitter:creator" content="@AREntertainBD">

    <!-- Performance: Google Fonts Preconnect -->
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link rel="stylesheet" href="https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@400;500;600;700;800;900&family=Raleway:wght@300;400;500;600;700;800;900&family=Roboto:wght@300;400;500;700;900&family=Train+One&display=swap">

    <!-- FontAwesome 6 -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.1/css/all.min.css">

    <!-- Main Project Stylesheet -->
    <link rel="stylesheet" href="<?= asset_url('style.css') ?>?v=<?= file_exists(ROOT_PATH . '/inc/style.css') ? filemtime(ROOT_PATH . '/inc/style.css') : '2.0' ?>" type="text/css">

    <!-- jQuery (Deferred) -->
    <script src="https://cdnjs.cloudflare.com/ajax/libs/jquery/3.6.0/jquery.min.js" defer></script>

    <!-- Google Ads Tag -->
    <?php if (!empty($google_ads_id)): ?>
        <script async src="https://www.googletagmanager.com/gtag/js?id=<?= htmlspecialchars($google_ads_id) ?>"></script>
        <script>
            window.dataLayer = window.dataLayer || [];
            function gtag(){dataLayer.push(arguments);}
            gtag('js', new Date());
            gtag('config', '<?= htmlspecialchars($google_ads_id) ?>');
        </script>
    <?php endif; ?>

    <!-- Google Analytics 4 (GA4) -->
    <?php if (!empty($ga4_id)): ?>
        <script async src="https://www.googletagmanager.com/gtag/js?id=<?= htmlspecialchars($ga4_id) ?>"></script>
        <script>
            window.dataLayer = window.dataLayer || [];
            function gtag(){dataLayer.push(arguments);}
            gtag('js', new Date());
            gtag('config', '<?= htmlspecialchars($ga4_id) ?>', { send_page_view: true });
        </script>
    <?php endif; ?>

    <!-- Meta Pixel Code -->
    <?php if (!empty($meta_pixel_id)): ?>
        <script>
            !function(f,b,e,v,n,t,s){
                if(f.fbq)return;n=f.fbq=function(){
                    n.callMethod?n.callMethod.apply(n,arguments):n.queue.push(arguments)
                };
                if(!f._fbq)f._fbq=n;n.push=n;n.loaded=!0;n.version='2.0';
                n.queue=[];t=b.createElement(e);t.async=!0;
                t.src=v;s=b.getElementsByTagName(e)[0];
                s.parentNode.insertBefore(t,s)
            }(window,document,'script','https://connect.facebook.net/en_US/fbevents.js');
            fbq('init', '<?= htmlspecialchars($meta_pixel_id) ?>');
            fbq('track', 'PageView');
        </script>
        <noscript>
            <img height="1" width="1" style="display:none" src="https://www.facebook.com/tr?id=<?= htmlspecialchars($meta_pixel_id) ?>&ev=PageView&noscript=1" alt="Meta Pixel" />
        </noscript>
    <?php endif; ?>

    <!-- GA4 Native Call & Contact Tracking -->
    <script>
        document.addEventListener('DOMContentLoaded', function () {
            document.querySelectorAll("a[href^='tel:']").forEach(function(link) {
                link.addEventListener('click', function () {
                    const phoneNumber = this.getAttribute('href').replace('tel:', '');
                    if (typeof gtag === 'function') {
                        gtag('event', 'call_click', {
                            phone_number: phoneNumber,
                            page_location: window.location.href
                        });
                    }
                });
            });
        });
    </script>

    <!-- Schema.org Structured Data (JSON-LD) -->
    <script type="application/ld+json">
    {
        "@context": "https://schema.org",
        "@graph": [
            {
                "@type": "Organization",
                "@id": "<?= site_url() ?>#organization",
                "name": "<?= htmlspecialchars($site_name) ?>",
                "legalName": "<?= htmlspecialchars($legal_name) ?>",
                "url": "<?= site_url() ?>",
                "foundingDate": "<?= htmlspecialchars($founding_year) ?>",
                "logo": {
                    "@type": "ImageObject",
                    "url": "<?= htmlspecialchars($site_logo_url) ?>",
                    "width": 320,
                    "height": 80
                },
                "telephone": "<?= htmlspecialchars($contact_phone) ?>",
                "email": "<?= htmlspecialchars($contact_email) ?>",
                "description": "<?= htmlspecialchars($site_desc) ?>",
                "address": {
                    "@type": "PostalAddress",
                    "streetAddress": "Apt 4-S, House 62, Road 14/1, Block G, Niketan, Gulshan 1",
                    "addressLocality": "Dhaka",
                    "postalCode": "1212",
                    "addressCountry": "BD"
                },
                "founder": {
                    "@type": "Person",
                    "name": "<?= htmlspecialchars($founder_name) ?>",
                    "jobTitle": "<?= htmlspecialchars($founder_title) ?>"
                },
                "sameAs": <?= json_encode(array_values($social_links), JSON_UNESCAPED_SLASHES | JSON_PRETTY_PRINT) ?>
            },
            {
                "@type": "WebSite",
                "@id": "<?= site_url() ?>#website",
                "url": "<?= site_url() ?>",
                "name": "<?= htmlspecialchars($site_name) ?>",
                "publisher": { "@id": "<?= site_url() ?>#organization" },
                "inLanguage": "en"
            }
            <?php if (!empty($page_schema)): ?>
            , <?= is_array($page_schema) ? json_encode($page_schema, JSON_UNESCAPED_SLASHES | JSON_PRETTY_PRINT) : $page_schema ?>
            <?php endif; ?>
        ]
    }
    </script>

    <!-- Custom Header Scripts from Admin Settings -->
    <?php if (!empty($header_scripts)): ?>
        <?= $header_scripts ?>
    <?php endif; ?>

    <!-- Extra Head Content from Specific Views -->
    <?php if (!empty($extra_head)): ?>
        <?= $extra_head ?>
    <?php endif; ?>
</head>

<body>
