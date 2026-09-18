<?php
/**
 * AR Entertainment - Master Database Seeder for All Modules
 * Seeds Categories, Blogs, Services, Service Areas, Portfolio, Team Members.
 */

require_once __DIR__ . '/../config/config.php';
require_once __DIR__ . '/../config/db.php';
require_once __DIR__ . '/../config/helpers.php';

echo "=== 🚀 SEEDING ALL DATABASE MODULES ===\n\n";

$db = db();

// 1. Seed Blog & Portfolio Categories
$categories = [
    ['name' => 'Commercials & Ads', 'slug' => 'commercials-ads', 'type' => 'portfolio', 'description' => 'High-end TVCs and digital commercials.'],
    ['name' => 'Music Videos', 'slug' => 'music-videos', 'type' => 'portfolio', 'description' => 'Creative music video productions.'],
    ['name' => 'Documentaries & Fixer', 'slug' => 'documentaries-fixer', 'type' => 'portfolio', 'description' => 'International and local line production and documentary filming.'],
    ['name' => 'AI Video & VFX', 'slug' => 'ai-video-vfx', 'type' => 'portfolio', 'description' => 'Cutting edge AI generated video and visual effects.'],
    ['name' => 'Corporate & Brand Films', 'slug' => 'corporate-brand-films', 'type' => 'portfolio', 'description' => 'High impact corporate communications.'],
    
    ['name' => 'Film Industry Insights', 'slug' => 'film-industry-insights', 'type' => 'blog', 'description' => 'Trends, analytics and insights in Bangladeshi media.'],
    ['name' => 'Production Guides', 'slug' => 'production-guides', 'type' => 'blog', 'description' => 'Practical guides for line production, fixing and shooting.'],
    ['name' => 'Behind The Scenes', 'slug' => 'behind-the-scenes', 'type' => 'blog', 'description' => 'Exclusive look into AR Entertainment shoots.'],
    ['name' => 'AI in Cinema', 'slug' => 'ai-in-cinema', 'type' => 'blog', 'description' => 'How generative AI is reshaping visual storytelling.']
];

foreach ($categories as $i => $cat) {
    $stmt = $db->prepare("
        INSERT INTO categories (name, slug, type, description, sort_order, created_at)
        VALUES (?, ?, ?, ?, ?, NOW())
        ON DUPLICATE KEY UPDATE name=VALUES(name), description=VALUES(description)
    ");
    $stmt->execute([$cat['name'], $cat['slug'], $cat['type'], $cat['description'], $i + 1]);
}
echo "✅ Categories seeded.\n";

// 2. Seed Blogs
$blogs = [
    [
        'title' => 'Top 10 Video Production Trends in Bangladesh for 2026',
        'slug' => 'top-10-video-production-trends-bangladesh-2026',
        'summary' => 'Discover how AI workflows, hyper-realistic VFX, and cinematic storytelling are transforming commercial film production in Dhaka.',
        'content' => '<p>The audio-visual landscape in Bangladesh has undergone a seismic shift. In this deep dive, AR Entertainment explores the top innovations defining 2026 video production standards.</p><h3>1. AI-Assisted Pre-visualization</h3><p>Storyboarding and visual treatment are now faster and richer than ever before.</p><h3>2. International Line Production Standards</h3><p>Foreign film crews and OTT platforms are increasingly choosing Dhaka and remote picturesque locations across Bangladesh for global productions.</p>',
        'author_name' => 'Azizul Hoque Shiplu',
        'tags' => 'Video Production, Dhaka, TVC, AI Cinema',
        'status' => 'published',
        'is_featured' => 1,
        'meta_title' => 'Top 10 Video Production Trends in Bangladesh 2026 | AR Entertainment',
        'meta_description' => 'Explore the leading video production trends shaping advertising and filmmaking in Bangladesh by AR Entertainment.',
        'meta_keywords' => 'video production bangladesh, tv commercial dhaka, film fixer'
    ],
    [
        'title' => 'The Complete Guide to Line Production & Film Fixing in Bangladesh',
        'slug' => 'complete-guide-line-production-film-fixing-bangladesh',
        'summary' => 'Everything international documentary and cinema crews need to know about permits, equipment rental, drone laws, and local logistics in Bangladesh.',
        'content' => '<p>Filming in Bangladesh offers unparalleled cultural richness and dramatic geography. From the bustling streets of Old Dhaka to the tea hills of Sylhet and the shipbreaking yards of Chattogram, here is how AR Entertainment facilitates seamless line production.</p>',
        'author_name' => 'Azizul Hoque Shiplu',
        'tags' => 'Fixer Bangladesh, Line Production, Film Permits',
        'status' => 'published',
        'is_featured' => 1,
        'meta_title' => 'Film Fixer & Line Production Guide Bangladesh | AR Entertainment',
        'meta_description' => 'Complete guide for international film crews needing line production, location permits and fixer services in Bangladesh.',
        'meta_keywords' => 'film fixer bangladesh, line production dhaka, documentary fixing'
    ]
];

foreach ($blogs as $b) {
    $stmt = $db->prepare("
        INSERT INTO blogs (title, slug, summary, content, category_id, author_name, tags, status, is_featured, meta_title, meta_description, meta_keywords, published_at, created_at, updated_at)
        VALUES (?, ?, ?, ?, (SELECT id FROM categories WHERE type='blog' LIMIT 1), ?, ?, ?, ?, ?, ?, ?, NOW(), NOW(), NOW())
        ON DUPLICATE KEY UPDATE title=VALUES(title), summary=VALUES(summary)
    ");
    $stmt->execute([
        $b['title'], $b['slug'], $b['summary'], $b['content'],
        $b['author_name'], $b['tags'], $b['status'], $b['is_featured'],
        $b['meta_title'], $b['meta_description'], $b['meta_keywords']
    ]);
}
echo "✅ Blogs seeded.\n";

// 3. Seed Services
$services = [
    [
        'title' => 'TV Commercial & OVC Production',
        'slug' => 'tv-commercial-ovc-production',
        'icon' => 'fa-solid fa-film',
        'short_summary' => 'High-concept television commercials and online video advertisements crafted for maximum brand recall and conversion.',
        'content' => '<p>From conceptual ideation and scriptwriting to cinematic shooting and high-end color grading, we craft commercials that captivate audiences across broadcast and digital channels.</p>',
        'pricing_note' => 'Custom quotes based on scale and production scope',
        'faqs_json' => json_encode([
            ['q' => 'What is the standard turnaround time for a TVC?', 'a' => 'Typically 2 to 4 weeks from concept approval to final broadcast master delivery.'],
            ['q' => 'Do you handle celebrity casting and endorsements?', 'a' => 'Yes, our talent management team negotiates and coordinates top actors and models.']
        ]),
        'sort_order' => 1,
        'status' => 'active',
        'meta_title' => 'TV Commercial & OVC Production Services Dhaka | AR Entertainment',
        'meta_description' => 'Premium TV commercial and OVC video production in Bangladesh by award-winning director Azizul Hoque Shiplu.'
    ],
    [
        'title' => 'International Line Production & Film Fixing',
        'slug' => 'line-production-film-fixing',
        'icon' => 'fa-solid fa-globe',
        'short_summary' => 'End-to-end local production management, location permits, customs clearance, and bilingual fixer support for global media crews.',
        'content' => '<p>We have supported major international broadcasters, documentary filmmakers, and foreign production houses across all 64 districts of Bangladesh.</p>',
        'pricing_note' => 'Day-rate or comprehensive package tailored to project duration',
        'faqs_json' => json_encode([
            ['q' => 'Can you arrange drone permits in Bangladesh?', 'a' => 'Yes, we handle CAAB drone filming permissions and security clearances.'],
            ['q' => 'Do you provide RED and ARRI camera packages locally?', 'a' => 'Yes, we have top cinema cameras, Cooke/Zeiss primes, and grip gear in Dhaka.']
        ]),
        'sort_order' => 2,
        'status' => 'active',
        'meta_title' => 'Line Production & Film Fixer in Bangladesh | AR Entertainment',
        'meta_description' => 'Experienced film fixer and line producer in Bangladesh for international documentaries, features, and commercials.'
    ]
];

foreach ($services as $s) {
    $stmt = $db->prepare("
        INSERT INTO services (title, slug, icon, short_summary, content, pricing_note, faqs_json, sort_order, status, meta_title, meta_description, created_at, updated_at)
        VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, NOW(), NOW())
        ON DUPLICATE KEY UPDATE title=VALUES(title), short_summary=VALUES(short_summary)
    ");
    $stmt->execute([
        $s['title'], $s['slug'], $s['icon'], $s['short_summary'], $s['content'],
        $s['pricing_note'], $s['faqs_json'], $s['sort_order'], $s['status'],
        $s['meta_title'], $s['meta_description']
    ]);
}
echo "✅ Services seeded.\n";

// 4. Seed Service Areas
$service_areas = [
    [
        'title' => 'Video Production & Film Fixing in Dhaka',
        'slug' => 'video-production-dhaka',
        'city_name' => 'Dhaka',
        'summary' => 'Full-service commercial, corporate, and documentary video production in the capital city of Bangladesh.',
        'content' => '<p>Dhaka is the cultural and commercial epicenter of Bangladesh. AR Entertainment operates fully equipped studios and location units throughout Dhaka.</p>',
        'sort_order' => 1,
        'status' => 'active',
        'meta_title' => 'Video Production Services in Dhaka | AR Entertainment',
        'meta_description' => 'Leading video production company in Dhaka specializing in TVCs, OVCs, corporate videos and film fixing.'
    ],
    [
        'title' => 'Line Production & Location Filming in Chattogram',
        'slug' => 'video-production-chattogram',
        'city_name' => 'Chattogram',
        'summary' => 'Filming support for port city, maritime, shipbreaking, and coastal documentary productions in Chattogram.',
        'content' => '<p>Chattogram offers scenic coastal landscapes, hills, and industrial ports. AR Entertainment provides comprehensive fixing and crew support.</p>',
        'sort_order' => 2,
        'status' => 'active',
        'meta_title' => 'Film Production & Fixer in Chattogram | AR Entertainment',
        'meta_description' => 'Professional line production and fixer services in Chattogram and coastal areas of Bangladesh.'
    ]
];

foreach ($service_areas as $sa) {
    $stmt = $db->prepare("
        INSERT INTO service_areas (title, slug, city_name, summary, content, sort_order, status, meta_title, meta_description, created_at, updated_at)
        VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, NOW(), NOW())
        ON DUPLICATE KEY UPDATE title=VALUES(title), city_name=VALUES(city_name)
    ");
    $stmt->execute([
        $sa['title'], $sa['slug'], $sa['city_name'], $sa['summary'], $sa['content'],
        $sa['sort_order'], $sa['status'], $sa['meta_title'], $sa['meta_description']
    ]);
}
echo "✅ Service Areas seeded.\n";

// 5. Seed Portfolio
$portfolio = [
    [
        'title' => 'Apex Footwear - Eid Campaign TVC',
        'slug' => 'apex-footwear-eid-campaign-tvc',
        'category_name' => 'TV Commercial',
        'video_url' => 'https://www.youtube.com/watch?v=dQw4w9WgXcQ',
        'client_name' => 'Apex Footwear Ltd.',
        'year' => '2025',
        'description' => 'A visually lavish festive TV commercial highlighting the modern lifestyle collection.',
        'is_featured' => 1,
        'sort_order' => 1,
        'status' => 'active'
    ],
    [
        'title' => 'BBC World Service - Dhaka Mega City Documentary',
        'slug' => 'bbc-world-service-dhaka-documentary',
        'category_name' => 'Documentary & Fixer',
        'video_url' => 'https://www.youtube.com/watch?v=dQw4w9WgXcQ',
        'client_name' => 'BBC World Service',
        'year' => '2025',
        'description' => 'Full line production, location permits, drone operation and fixer services for international crew.',
        'is_featured' => 1,
        'sort_order' => 2,
        'status' => 'active'
    ]
];

foreach ($portfolio as $p) {
    $stmt = $db->prepare("
        INSERT INTO portfolio (title, slug, category_id, category_name, video_url, client_name, year, description, is_featured, sort_order, status, created_at, updated_at)
        VALUES (?, ?, (SELECT id FROM categories WHERE type='portfolio' LIMIT 1), ?, ?, ?, ?, ?, ?, ?, ?, NOW(), NOW())
        ON DUPLICATE KEY UPDATE title=VALUES(title), client_name=VALUES(client_name)
    ");
    $stmt->execute([
        $p['title'], $p['slug'], $p['category_name'], $p['video_url'],
        $p['client_name'], $p['year'], $p['description'], $p['is_featured'],
        $p['sort_order'], $p['status']
    ]);
}
echo "✅ Portfolio seeded.\n";

// 6. Seed Team Members
$team = [
    [
        'name' => 'Azizul Hoque Shiplu',
        'slug' => 'azizul-hoque-shiplu',
        'role_title' => 'Founder & Executive Director',
        'bio' => 'Celebrated Bangladeshi filmmaker, commercial director, and media producer with over 15 years of industry excellence.',
        'email' => 'shiplu@arentertainment.bd',
        'phone' => '+880 1711 000000',
        'social_links_json' => json_encode([
            'facebook' => 'https://facebook.com/arentertainment.bd',
            'linkedin' => 'https://linkedin.com',
            'imdb' => 'https://imdb.com'
        ]),
        'sort_order' => 1,
        'status' => 'active'
    ],
    [
        'name' => 'Rahim Chowdhury',
        'slug' => 'rahim-chowdhury',
        'role_title' => 'Head of Line Production & Fixing',
        'bio' => 'Logistics master coordinating location access, permits, and equipment for global crews across Bangladesh.',
        'email' => 'production@arentertainment.bd',
        'phone' => '+880 1811 000000',
        'social_links_json' => json_encode([
            'linkedin' => 'https://linkedin.com'
        ]),
        'sort_order' => 2,
        'status' => 'active'
    ]
];

foreach ($team as $tm) {
    $stmt = $db->prepare("
        INSERT INTO team_members (name, slug, role_title, bio, email, phone, social_links_json, sort_order, status, created_at, updated_at)
        VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, NOW(), NOW())
        ON DUPLICATE KEY UPDATE name=VALUES(name), role_title=VALUES(role_title)
    ");
    $stmt->execute([
        $tm['name'], $tm['slug'], $tm['role_title'], $tm['bio'],
        $tm['email'], $tm['phone'], $tm['social_links_json'],
        $tm['sort_order'], $tm['status']
    ]);
}
echo "✅ Team members seeded.\n";

echo "\n🏆 ALL MODULES SEEDED SUCCESSFULLY!\n";
