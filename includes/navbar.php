<?php

/**
 * AR Entertainment - Universal Frontend Navigation Partial
 * 
 * Provides both the desktop navigation bar and the responsive mobile sliding drawer.
 * Supports active menu highlighting and dynamic settings.
 */

require_once dirname(__DIR__) . '/config/config.php';
require_once dirname(__DIR__) . '/config/helpers.php';

$current_nav_page = $current_page ?? '';
$contact_phone    = get_setting('contact_phone', CONTACT_PHONE);
$clean_phone      = preg_replace('/[^\d+]/', '', $contact_phone);
?>
<div class="dvLayout d-flex flex-column">
    <!-- Main Header Area -->
    <header class="header-area">
        <div class="container-fluid">
            <div class="d-flex justify-content-between align-items-center">
                <!-- Site Brand Logo -->
                <div class="site-logo">
                    <a href="<?= site_url() ?>" aria-label="AR Entertainment Home">
                        <img src="<?= site_url('images/arentertainment-logo.svg') ?>" alt="AR Entertainment Logo" width="180" height="48" style="height: auto; max-height: 52px; width: auto; object-fit: contain;">
                    </a>
                </div>

                <!-- Desktop Top Navigation Menu -->
                <div class="top-navigation d-none d-xl-block">
                    <div class="custom-menu">
                        <nav class="navbar navbar-expand-lg navbar-light">
                            <div class="collapse navbar-collapse" id="navbarSupportedContent">
                                <ul class="navbar-nav ml-auto">
                                    <li class="<?= ($current_nav_page === 'home' || empty($current_nav_page)) ? 'active' : '' ?>">
                                        <a href="<?= site_url() ?>">Home</a>
                                    </li>

                                    <li class="dropdown hasChild <?= (str_starts_with($current_nav_page, 'services')) ? 'active' : '' ?>">
                                        <a href="<?= site_url('services') ?>">Services</a>
                                        <ul class="dropdown-menu">
                                            <li><a href="<?= site_url('services/tv-commercial') ?>">TV Commercial (TVC)</a></li>
                                            <li class="dropdown hasChild">
                                                <a href="<?= site_url('services/online-video-commercial') ?>">Online Video Commercial (OVC)</a>
                                                <ul class="sub-sub-navs dropdown-menu">
                                                    <li><a href="<?= site_url('services/online-video-commercial/facebook-youtube-ovc') ?>">Facebook & YouTube Video Ads</a></li>
                                                    <li><a href="<?= site_url('services/online-video-commercial/ovc-cost-in-bangladesh') ?>">OVC Cost in Bangladesh</a></li>
                                                    <li><a href="<?= site_url('services/online-video-commercial/ovc-for-brands-and-fmcg') ?>">OVC for Brands & FMCG</a></li>
                                                    <li><a href="<?= site_url('services/online-video-commercial/ovc-for-banks-and-financial-services') ?>">OVC for Banks & FinTech</a></li>
                                                    <li><a href="<?= site_url('services/online-video-commercial/tiktok-video-production-bangladesh') ?>">TikTok Video Production</a></li>
                                                </ul>
                                            </li>
                                            <li><a href="<?= site_url('services/corporate-av') ?>">Corporate AV & Brand Films</a></li>
                                            <li><a href="<?= site_url('services/documentary') ?>">Documentary & NGO Films</a></li>
                                            <li><a href="<?= site_url('services/support-for-international-production') ?>">Film Fixer in Bangladesh</a></li>
                                            <li><a href="<?= site_url('services/theme-song') ?>">Theme Song & Brand Anthems</a></li>
                                            <li><a href="<?= site_url('services/music-video') ?>">Music Video Production</a></li>
                                            <li><a href="<?= site_url('services/2d-and-3d-animation') ?>">2D & 3D Animation</a></li>
                                            <li><a href="<?= site_url('services/ai-video-content-creation') ?>">AI Video Content Creation</a></li>
                                            <li><a href="<?= site_url('services/ai-video-localisation-dubbing') ?>">AI Dubbing & Localisation</a></li>
                                            <li><a href="<?= site_url('services/corporate-video-for-garment-and-textile-industry-bangladesh') ?>">RMG & Textile Corporate Video</a></li>
                                        </ul>
                                    </li>

                                    <li class="dropdown hasChild <?= (str_starts_with($current_nav_page, 'ai')) ? 'active' : '' ?>">
                                        <a href="<?= site_url('ai') ?>">AI Solutions</a>
                                        <ul class="dropdown-menu">
                                            <li><a href="<?= site_url('ai/ai-vs-traditional-video-production') ?>">AI vs Traditional Production</a></li>
                                            <li><a href="<?= site_url('ai/how-ai-content-production-works') ?>">How AI Production Works</a></li>
                                            <li><a href="<?= site_url('ai/ai-content-governance-compliance') ?>">AI Governance & Compliance</a></li>
                                            <li><a href="<?= site_url('ai/human-in-the-loop-ai-production') ?>">Human-in-the-Loop AI</a></li>
                                            <li><a href="<?= site_url('ai/ai-video-dubbing-localisation') ?>">AI Dubbing & Localisation</a></li>
                                            <li><a href="<?= site_url('ai/ai-video-production-cost') ?>">AI Video Production Cost</a></li>
                                            <li class="dropdown hasChild">
                                                <a href="<?= site_url('ai') ?>">Global Production Hubs</a>
                                                <ul class="sub-sub-navs dropdown-menu">
                                                    <li><a href="<?= site_url('ai/usa') ?>">United States (USA)</a></li>
                                                    <li><a href="<?= site_url('ai/uk') ?>">United Kingdom (UK)</a></li>
                                                    <li><a href="<?= site_url('ai/uae') ?>">United Arab Emirates (UAE)</a></li>
                                                    <li><a href="<?= site_url('ai/canada') ?>">Canada</a></li>
                                                    <li><a href="<?= site_url('ai/australia') ?>">Australia</a></li>
                                                    <li><a href="<?= site_url('ai/germany') ?>">Germany</a></li>
                                                </ul>
                                            </li>
                                        </ul>
                                    </li>

                                    <li class="dropdown hasChild <?= (str_starts_with($current_nav_page, 'portfolio')) ? 'active' : '' ?>">
                                        <a href="<?= site_url('portfolio') ?>">Portfolio</a>
                                        <ul class="dropdown-menu">
                                            <li><a href="<?= site_url('portfolio?category=tvc') ?>">Television Commercial (TVC)</a></li>
                                            <li><a href="<?= site_url('portfolio?category=ovc') ?>">Online Video Commercial (OVC)</a></li>
                                            <li><a href="<?= site_url('portfolio?category=corporate-av') ?>">Corporate AV</a></li>
                                            <li><a href="<?= site_url('portfolio?category=theme-song') ?>">Theme Song</a></li>
                                            <li><a href="<?= site_url('portfolio?category=documentary') ?>">Documentary</a></li>
                                            <li><a href="<?= site_url('portfolio?category=ai-video') ?>">AI Brand Video</a></li>
                                        </ul>
                                    </li>

                                    <li class="<?= ($current_nav_page === 'blog' || str_starts_with($current_nav_page, 'blog')) ? 'active' : '' ?>">
                                        <a href="<?= site_url('blog') ?>">Blog</a>
                                    </li>

                                    <li class="<?= ($current_nav_page === 'brands') ? 'active' : '' ?>">
                                        <a href="<?= site_url('brands') ?>">Clients</a>
                                    </li>

                                    <li class="dropdown hasChild <?= (str_starts_with($current_nav_page, 'about')) ? 'active' : '' ?>">
                                        <a href="<?= site_url('about-us') ?>">About Us</a>
                                        <ul class="dropdown-menu">
                                            <li><a href="<?= site_url('meet-the-team') ?>">The Creative Minds</a></li>
                                            <li><a href="<?= site_url('about-us/why-choose-us') ?>">Why AR Entertainment?</a></li>
                                            <li><a href="<?= site_url('reviews') ?>">Client Reviews</a></li>
                                            <li><a href="<?= site_url('about-us/careers') ?>">Careers</a></li>
                                            <li><a href="<?= site_url('about-us/partners') ?>">Partners & Affiliations</a></li>
                                            <li><a href="<?= site_url('contact-us') ?>">Contact Us</a></li>
                                        </ul>
                                    </li>
                                </ul>
                            </div>
                        </nav>
                    </div>
                </div>

                <!-- Right Side Actions & Contact CTA -->
                <div class="right-side d-flex align-items-center justify-content-end">
                    <div class="head-cta d-flex align-items-center">
                        <div class="top-btn d-none d-sm-block ml-3">
                            <a href="<?= site_url('contact-us') ?>">
                                ENQUIRE <i class="fa-solid fa-arrow-up-right-from-square ml-1"></i>
                            </a>
                        </div>
                        <div class="con-btn d-flex flex-column ml-3 ml-md-4">
                            <span class="d-flex text-uppercase" style="font-size: 11px; letter-spacing: 1px; color: #a0a0a0;">Call Us</span>
                            <a href="tel:<?= htmlspecialchars($clean_phone) ?>" class="d-flex align-items-center" style="font-weight: 700; color: #fff;">
                                <i class="fa fa-phone-square mr-2" style="color: #e50914;"></i>
                                <?= htmlspecialchars($contact_phone) ?>
                            </a>
                        </div>
                    </div>

                    <!-- Mobile Hamburger Toggle -->
                    <div class="hamburger menu-icon d-xl-none ml-4" role="button" aria-label="Toggle navigation menu">
                        <div class="menu-icon-in">
                            <span></span>
                            <span></span>
                            <span></span>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </header>

    <!-- Mobile Navigation Drawer -->
    <aside class="dvLeft" aria-label="Mobile Navigation Menu">
        <nav class="navigation" id="menubar">
            <ul class="nav flex-column flex-nowrap" id="ulmenu">
                <li class="nav-item">
                    <a class="nav-link" href="<?= site_url() ?>">
                        <i class="fa fa-angle-right" aria-hidden="true"></i> Home
                    </a>
                </li>

                <!-- Services Mobile Dropdown -->
                <li class="nav-item mainDropdown">
                    <a class="nav-link collapsed" href="<?= site_url('services') ?>">Services</a>
                    <span role="button" data-toggle="collapse" data-target_="#mob-services" class="fa fa-plus sub" aria-expanded="false"></span>
                    <div class="collapse" id="mob-services">
                        <ul class="flex-column pl-2 nav">
                            <li class="nav-item"><a class="nav-link" href="<?= site_url('services/tv-commercial') ?>">TV Commercial (TVC)</a></li>
                            <li class="nav-item"><a class="nav-link" href="<?= site_url('services/online-video-commercial') ?>">Online Video Commercial (OVC)</a></li>
                            <li class="nav-item"><a class="nav-link" href="<?= site_url('services/corporate-av') ?>">Corporate AV & Brand Films</a></li>
                            <li class="nav-item"><a class="nav-link" href="<?= site_url('services/documentary') ?>">Documentary & NGO Films</a></li>
                            <li class="nav-item"><a class="nav-link" href="<?= site_url('services/support-for-international-production') ?>">Film Fixer in Bangladesh</a></li>
                            <li class="nav-item"><a class="nav-link" href="<?= site_url('services/theme-song') ?>">Theme Song</a></li>
                            <li class="nav-item"><a class="nav-link" href="<?= site_url('services/music-video') ?>">Music Video Production</a></li>
                            <li class="nav-item"><a class="nav-link" href="<?= site_url('services/2d-and-3d-animation') ?>">2D & 3D Animation</a></li>
                            <li class="nav-item"><a class="nav-link" href="<?= site_url('services/ai-video-content-creation') ?>">AI Video Content Creation</a></li>
                            <li class="nav-item"><a class="nav-link" href="<?= site_url('services/ai-video-localisation-dubbing') ?>">AI Dubbing & Localisation</a></li>
                            <li class="nav-item"><a class="nav-link" href="<?= site_url('services/corporate-video-for-garment-and-textile-industry-bangladesh') ?>">RMG & Textile Corporate Video</a></li>
                        </ul>
                    </div>
                </li>

                <!-- AI Mobile Dropdown -->
                <li class="nav-item mainDropdown">
                    <a class="nav-link collapsed" href="<?= site_url('ai') ?>">AI Solutions</a>
                    <span role="button" data-toggle="collapse" data-target_="#mob-ai" class="fa fa-plus sub" aria-expanded="false"></span>
                    <div class="collapse" id="mob-ai">
                        <ul class="flex-column pl-2 nav">
                            <li class="nav-item"><a class="nav-link" href="<?= site_url('ai/ai-vs-traditional-video-production') ?>">AI vs Traditional</a></li>
                            <li class="nav-item"><a class="nav-link" href="<?= site_url('ai/how-ai-content-production-works') ?>">How AI Production Works</a></li>
                            <li class="nav-item"><a class="nav-link" href="<?= site_url('ai/ai-content-governance-compliance') ?>">AI Governance & Compliance</a></li>
                            <li class="nav-item"><a class="nav-link" href="<?= site_url('ai/human-in-the-loop-ai-production') ?>">Human-in-the-Loop AI</a></li>
                            <li class="nav-item"><a class="nav-link" href="<?= site_url('ai/ai-video-dubbing-localisation') ?>">AI Video Dubbing</a></li>
                            <li class="nav-item"><a class="nav-link" href="<?= site_url('ai/ai-video-production-cost') ?>">AI Production Cost</a></li>
                        </ul>
                    </div>
                </li>

                <!-- Portfolio Mobile Dropdown -->
                <li class="nav-item mainDropdown">
                    <a class="nav-link collapsed" href="<?= site_url('portfolio') ?>">Portfolio</a>
                    <span role="button" data-toggle="collapse" data-target_="#mob-portfolio" class="fa fa-plus sub" aria-expanded="false"></span>
                    <div class="collapse" id="mob-portfolio">
                        <ul class="flex-column pl-2 nav">
                            <li class="nav-item"><a class="nav-link" href="<?= site_url('portfolio?category=tvc') ?>">TV Commercials</a></li>
                            <li class="nav-item"><a class="nav-link" href="<?= site_url('portfolio?category=ovc') ?>">Online Video Ads</a></li>
                            <li class="nav-item"><a class="nav-link" href="<?= site_url('portfolio?category=corporate-av') ?>">Corporate AV</a></li>
                            <li class="nav-item"><a class="nav-link" href="<?= site_url('portfolio?category=theme-song') ?>">Theme Songs</a></li>
                            <li class="nav-item"><a class="nav-link" href="<?= site_url('portfolio?category=documentary') ?>">Documentaries</a></li>
                            <li class="nav-item"><a class="nav-link" href="<?= site_url('portfolio?category=ai-video') ?>">AI Brand Videos</a></li>
                        </ul>
                    </div>
                </li>

                <!-- Blog Link -->
                <li class="nav-item">
                    <a class="nav-link" href="<?= site_url('blog') ?>">
                        <i class="fa fa-angle-right" aria-hidden="true"></i> Blog
                    </a>
                </li>

                <!-- Clients Link -->
                <li class="nav-item">
                    <a class="nav-link" href="<?= site_url('brands') ?>">
                        <i class="fa fa-angle-right" aria-hidden="true"></i> Clients
                    </a>
                </li>

                <!-- About Us Mobile Dropdown -->
                <li class="nav-item mainDropdown">
                    <a class="nav-link collapsed" href="<?= site_url('about-us') ?>">About Us</a>
                    <span role="button" data-toggle="collapse" data-target_="#mob-about" class="fa fa-plus sub" aria-expanded="false"></span>
                    <div class="collapse" id="mob-about">
                        <ul class="flex-column pl-2 nav">
                            <li class="nav-item"><a class="nav-link" href="<?= site_url('meet-the-team') ?>">The Creative Minds</a></li>
                            <li class="nav-item"><a class="nav-link" href="<?= site_url('about-us/why-choose-us') ?>">Why AR Entertainment?</a></li>
                            <li class="nav-item"><a class="nav-link" href="<?= site_url('reviews') ?>">Client Reviews</a></li>
                            <li class="nav-item"><a class="nav-link" href="<?= site_url('about-us/careers') ?>">Careers</a></li>
                            <li class="nav-item"><a class="nav-link" href="<?= site_url('about-us/partners') ?>">Partners & Affiliations</a></li>
                            <li class="nav-item"><a class="nav-link" href="<?= site_url('contact-us') ?>">Contact Us</a></li>
                        </ul>
                    </div>
                </li>
            </ul>
        </nav>
    </aside>

    <!-- Main Content Area Starts -->
    <main class="MainSiteContent">
