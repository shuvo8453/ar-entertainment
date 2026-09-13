-- =====================================================================
-- AR Entertainment Database Seed Data
-- =====================================================================

-- 1. Default Superadmin User
-- Login Email: admin@arentertainment.bd
-- Default Password: Admin@AREnt2026!
INSERT INTO `users` (`name`, `email`, `password_hash`, `role`, `status`, `created_at`)
VALUES (
    'AR Admin',
    'admin@arentertainment.bd',
    '$2y$10$7bmt77dyv5XlgbypJkt7J.kPINbYGlkoRGGgN.kFy3/loT.cBn7Jq',
    'admin',
    'active',
    NOW()
)
ON DUPLICATE KEY UPDATE `name` = VALUES(`name`);

-- 2. Core Site Settings (AR Entertainment)
INSERT INTO `site_settings` (`setting_key`, `setting_value`, `setting_group`) VALUES
('site_name', 'AR Entertainment', 'general'),
('legal_name', 'AR Entertainment Ltd.', 'general'),
('site_title', 'AR Entertainment | Video Production & Film Fixer Services in Bangladesh', 'seo'),
('site_tagline', 'Video Production & Film Fixer Services in Bangladesh', 'general'),
('site_description', 'AR Entertainment is a premier Dhaka-based video production house and film fixer for local and international productions. TVC, OVC, corporate AV, documentary, AI video content and full production support across Bangladesh.', 'seo'),
('site_keywords', 'Video Production Bangladesh, TVC, Film Fixer in Bangladesh, AI Video Production Bangladesh, Corporate AV Production, OVC Production, Music Video Production, Jingle Production, Film Production House Bangladesh', 'seo'),
('site_domain', 'arentertainment.bd', 'general'),
('site_url', 'https://www.arentertainment.bd', 'general'),
('contact_email', 'info@arentertainment.bd', 'contact'),
('contact_phone', '+8801988777444', 'contact'),
('contact_address', 'Apt 4-S, House 62, Road 14/1, Block G, Niketan, Gulshan 1, Dhaka 1212, Bangladesh', 'contact'),
('founder_name', 'Azizul Hoque Shiplu', 'general'),
('founder_title', 'Founder and Film Director', 'general'),
('founding_year', '2018', 'general'),
('ga4_id', 'G-LYXSSGVBJF', 'analytics'),
('meta_pixel_id', '969321647479793', 'analytics'),
('google_ads_id', 'AW-11262226603', 'analytics'),
('social_facebook', 'https://www.facebook.com/arentertainment.bd', 'social'),
('social_linkedin', 'https://www.linkedin.com/company/ar-entertainment-bd', 'social'),
('social_instagram', 'https://www.instagram.com/arentertainment.bd', 'social'),
('social_youtube', 'https://www.youtube.com/@AREntertainmentBD', 'social'),
('social_twitter', 'https://x.com/AREntertainBD', 'social'),
('header_scripts', '', 'custom_code'),
('footer_scripts', '', 'custom_code')
ON DUPLICATE KEY UPDATE `setting_value` = VALUES(`setting_value`);

-- 3. Categories (Blog Categories)
INSERT INTO `categories` (`name`, `slug`, `type`, `description`, `sort_order`) VALUES
('AI Video Production', 'ai-video-production', 'blog', 'AI video dubbing, avatar training, and AI commercials', 1),
('Corporate AV & Video', 'corporate-av-video', 'blog', 'Corporate video production and industrial films', 2),
('Film Fixer & Filming in BD', 'film-fixer-filming-in-bd', 'blog', 'Film fixing, permits, and international production guide in Bangladesh', 3),
('TVC & Commercials', 'tvc-commercials', 'blog', 'Television commercial production insights and guides', 4),
('OVC & Digital Ads', 'ovc-digital-ads', 'blog', 'Online video commercials, social media ads, and YouTube content', 5),
('Video Marketing & SEO', 'video-marketing-seo', 'blog', 'Video content marketing, optimization, and audience engagement', 6)
ON DUPLICATE KEY UPDATE `name` = VALUES(`name`);

-- 4. Categories (Portfolio Categories)
INSERT INTO `categories` (`name`, `slug`, `type`, `description`, `sort_order`) VALUES
('TV Commercial', 'tvc', 'portfolio', 'Television commercials & high-end broadcast spots', 1),
('Online Video Commercial (OVC)', 'ovc', 'portfolio', 'Digital first video ads & social media campaigns', 2),
('Corporate AV', 'corporate-av', 'portfolio', 'Corporate profile videos, factory tours & investor reels', 3),
('AI Video Content', 'ai-video', 'portfolio', 'AI avatars, localization, synthetic media & smart edits', 4),
('Documentary', 'documentary', 'portfolio', 'NGO documentaries, social impact films & reality reels', 5),
('Music Video & Jingle', 'music-video-jingle', 'portfolio', 'Original music scoring, jingles & brand theme songs', 6)
ON DUPLICATE KEY UPDATE `name` = VALUES(`name`);

