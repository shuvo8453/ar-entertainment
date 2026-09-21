<?php
/**
 * AR Entertainment - Phase 5.4 Blog Articles Seeder (Batch 5.4.4: Articles 16–20)
 * 
 * Ingests and rewrites 5 core AI, production evaluation, real estate, international shoot, and story craft articles:
 * 16. How to Develop Quality AI Content & Earn Money From Home (slug: how-to-earn-money-from-home-using-ai-bangladesh)
 * 17. Top Production Houses in Bangladesh: What to Look for Before Hiring (slug: best-production-houses-in-bangladesh)
 * 18. Real Estate Video Production in Bangladesh: 2026 Marketing Guide (slug: real-estate-video-production-bangladesh)
 * 19. International Shoot in Bangladesh: 2026 Filming Guide & Logistics (slug: international-shoot-in-bangladesh)
 * 20. How to Develop Your Visual Story with AI: Creative Guide (slug: how-to-develop-your-story-with-ai)
 * 
 * Features:
 * - 100% Clean AR Entertainment Branding (0% Legacy Names)
 * - Author: Azizul Hoque Shiplu (Founder & Lead Director)
 * - Rich Responsive HTML Article Structure with Practical Frameworks, Pricing Guides & Callouts
 * - Dynamic Category Resolution via slug
 * - Idempotent MySQL Upsert (ON DUPLICATE KEY UPDATE)
 */

declare(strict_types=1);

require_once __DIR__ . '/../../config/config.php';
require_once __DIR__ . '/../../config/db.php';
require_once __DIR__ . '/../../config/helpers.php';

echo "========================================================\n";
echo "✍️ AR ENTERTAINMENT - SEED BLOG ARTICLES BATCH 5.4.4 (16–20)\n";
echo "========================================================\n\n";

$db = db();

$articles_batch4 = [
    // 16. How to Earn Money From Home Using AI in Bangladesh
    [
        'title' => 'How to Develop Quality AI Content & Earn Money From Home (2026 Guide)',
        'slug' => 'how-to-earn-money-from-home-using-ai-bangladesh',
        'category_slug' => 'ai-video-production',
        'author_name' => 'Azizul Hoque Shiplu',
        'is_featured' => 1,
        'views' => 3420,
        'published_at' => '2024-01-08 14:00:00',
        'summary' => 'A comprehensive guide for Bangladeshi creators, freelancers, and marketers on building high-income AI video, scriptwriting, localization, and automation services from home.',
        'content' => '<div class="article-lead mb-4">
    <p class="lead" style="color: #cbd5e1; font-size: 1.15rem; line-height: 1.8;">The rapid maturity of generative artificial intelligence tools in 2026 has created unprecedented remote freelancing and agency opportunities across Bangladesh. Earning substantial income with AI is no longer about generating generic text; it requires mastering prompt engineering, ethical content workflows, and hybrid human-in-the-loop post-production to deliver commercial-grade video, audio, and visual assets to global clients.</p>
</div>

<h2 class="text-warning font-weight-bold mt-4 mb-3">1. High-Income AI Content Niches for Bangladeshi Creators</h2>
<div class="row my-4">
    <div class="col-md-6 mb-3">
        <div class="p-3 rounded border h-100" style="background: #1e293b; border-color: #334155 !important;">
            <h5 class="text-warning font-weight-bold">AI Video &amp; Avatar Commercials</h5>
            <p class="text-light small mb-0">Creating high-converting 9:16 vertical TikTok/Reels ads and SaaS software explainers using Runway Gen-3, Midjourney, and ElevenLabs.</p>
        </div>
    </div>
    <div class="col-md-6 mb-3">
        <div class="p-3 rounded border h-100" style="background: #1e293b; border-color: #334155 !important;">
            <h5 class="text-warning font-weight-bold">Multilingual Voice Dubbing &amp; Localisation</h5>
            <p class="text-light small mb-0">Translating, synthesizing, and lipsyncing YouTube channels and corporate tutorials across Bangla, English, Arabic, and Hindi.</p>
        </div>
    </div>
    <div class="col-md-6 mb-3">
        <div class="p-3 rounded border h-100" style="background: #1e293b; border-color: #334155 !important;">
            <h5 class="text-warning font-weight-bold">B2B Copywriting &amp; Video Scripting</h5>
            <p class="text-light small mb-0">Structuring professional two-column scripts, email sequences, and SEO long-form authority articles for international corporate clients.</p>
        </div>
    </div>
    <div class="col-md-6 mb-3">
        <div class="p-3 rounded border h-100" style="background: #1e293b; border-color: #334155 !important;">
            <h5 class="text-warning font-weight-bold">Brand Visual Asset Generation</h5>
            <p class="text-light small mb-0">Developing photorealistic e-commerce product mockups, 3D architectural renders, and digital advertising banners with Flux and Stable Diffusion.</p>
        </div>
    </div>
</div>

<h2 class="text-warning font-weight-bold mt-4 mb-3">2. The 4-Step Client Delivery Framework</h2>
<p>To win repeat contracts on Upwork, Fiverr, and direct LinkedIn outreach, adopt this structured pipeline:</p>
<ul style="line-height: 1.8; color: #cbd5e1;">
    <li><strong>Step 1 (Deep Briefing):</strong> Extract target demographic, brand guidelines, reference styles, and aspect ratio requirements.</li>
    <li><strong>Step 2 (AI Generation):</strong> Execute precision iterative prompting to generate multiple stylistic variations.</li>
    <li><strong>Step 3 (Human Polish):</strong> Remove digital artifacts, refine audio frequencies, and color grade in DaVinci Resolve or Premiere Pro.</li>
    <li><strong>Step 4 (Format Mastering):</strong> Export multi-platform packages (4K UHD, 1080p vertical 9:16, square 1:1) with SRT subtitles.</li>
</ul>

<div class="p-4 rounded my-4" style="background: linear-gradient(135deg, #1e293b, #0f172a); border-left: 4px solid #f59e0b;">
    <h4 class="text-white font-weight-bold mb-2">Scale Your AI Creative Capabilities with AR Entertainment</h4>
    <p class="text-light mb-3">We partner with forward-thinking creators and enterprise brands to build cutting-edge generative AI video pipelines.</p>
    <a href="ai.html" class="btn btn-warning font-weight-bold px-4 py-2" style="background: #f59e0b; color: #0f172a;">Discover AI Production Solutions</a>
</div>',
        'tags' => 'Earn Money with AI, AI Freelancing Bangladesh, AI Video Creation, Remote Income Dhaka, Prompt Engineering',
        'meta_title' => 'How to Develop Quality AI Content & Earn Money From Home | AR Entertainment',
        'meta_description' => 'Learn how to create high-income AI content and earn money from home in Bangladesh. Complete 2026 guide to AI video, audio, and scripting by AR Entertainment.',
        'meta_keywords' => 'earn money with ai bangladesh, ai video freelancing dhaka, prompt engineering income, ai content creation bangladesh'
    ],

    // 17. Top Production Houses in Bangladesh
    [
        'title' => 'Top Production Houses in Bangladesh: What to Look for Before Hiring (2026 Guide)',
        'slug' => 'best-production-houses-in-bangladesh',
        'category_slug' => 'corporate-av-video',
        'author_name' => 'Azizul Hoque Shiplu',
        'is_featured' => 1,
        'views' => 2850,
        'published_at' => '2024-01-20 11:30:00',
        'summary' => 'A comprehensive decision-maker guide to evaluating and hiring the best video production houses in Dhaka for TVCs, corporate AVs, documentaries, and digital ad campaigns.',
        'content' => '<div class="article-lead mb-4">
    <p class="lead" style="color: #cbd5e1; font-size: 1.15rem; line-height: 1.8;">Selecting the right production house in Bangladesh is one of the most critical commercial decisions for brand managers, corporate CMOs, and agency creative directors. A top-tier production partner elevates brand equity, ensures flawless broadcast compliance, and delivers measurable ROI, while an inexperienced team can burn budgets and tarnish reputation.</p>
</div>

<h2 class="text-warning font-weight-bold mt-4 mb-3">5 Critical Criteria for Evaluating a Production House</h2>

<div class="row my-4">
    <div class="col-md-6 mb-3">
        <div class="p-3 rounded border h-100" style="background: #1e293b; border-color: #334155 !important;">
            <h5 class="text-warning font-weight-bold">1. Directorial Vision &amp; Storytelling Depth</h5>
            <p class="text-light small mb-0">Review past showreels for emotional resonance, visual rhythm, and authentic Bengali cultural storytelling rather than superficial camera tricks.</p>
        </div>
    </div>
    <div class="col-md-6 mb-3">
        <div class="p-3 rounded border h-100" style="background: #1e293b; border-color: #334155 !important;">
            <h5 class="text-warning font-weight-bold">2. Owned Cinema Gear &amp; Technical Assets</h5>
            <p class="text-light small mb-0">Check whether the company deploys modern cinema camera packages (RED, ARRI, Sony FX), Ronin 2 stabilizers, cinema prime lenses, and dedicated studio lighting.</p>
        </div>
    </div>
    <div class="col-md-6 mb-3">
        <div class="p-3 rounded border h-100" style="background: #1e293b; border-color: #334155 !important;">
            <h5 class="text-warning font-weight-bold">3. Transparent Budgeting &amp; Scope Controls</h5>
            <p class="text-light small mb-0">Look for line-item transparency across pre-production, shooting days, location clearances, talent fees, and post-production revision rounds.</p>
        </div>
    </div>
    <div class="col-md-6 mb-3">
        <div class="p-3 rounded border h-100" style="background: #1e293b; border-color: #334155 !important;">
            <h5 class="text-warning font-weight-bold">4. In-House Post-Production &amp; Color Grading</h5>
            <p class="text-light small mb-0">Verify in-house DaVinci Resolve color grading suites, sound design recording studios, and 2D/3D motion design capabilities for rapid iteration.</p>
        </div>
    </div>
</div>

<div class="p-3 rounded border mb-4" style="background: #1e293b; border-color: #334155 !important;">
    <h5 class="text-warning font-weight-bold">5. Regulatory Compliance &amp; Nationwide Logistics</h5>
    <p class="text-light small mb-0">Ensure the production house holds official government filming permits, CAAB drone certifications, and nationwide logistical coverage across all 64 districts of Bangladesh.</p>
</div>

<div class="p-4 rounded my-4" style="background: linear-gradient(135deg, #1e293b, #0f172a); border-left: 4px solid #f59e0b;">
    <h4 class="text-white font-weight-bold mb-2">Partner with AR Entertainment</h4>
    <p class="text-light mb-3">Led by veteran director Azizul Hoque Shiplu, AR Entertainment is recognized among Bangladesh’s top production houses, trusted by national brands, MNCs, and international broadcasters.</p>
    <a href="about-us" class="btn btn-warning font-weight-bold px-4 py-2" style="background: #f59e0b; color: #0f172a;">Learn More About AR Entertainment</a>
</div>',
        'tags' => 'Production Houses Bangladesh, Video Production Dhaka, Best Production Company, TVC House Dhaka, Filmmaking Bangladesh',
        'meta_title' => 'Top Production Houses in Bangladesh: What to Look for Before Hiring | AR Entertainment',
        'meta_description' => 'How to choose the best production house in Bangladesh for TVCs, corporate videos, and documentaries. Complete 2026 hiring guide by AR Entertainment.',
        'meta_keywords' => 'best production houses bangladesh, video production house dhaka, top film production company bangladesh, hire tvc director dhaka'
    ],

    // 18. Real Estate Video Production in Bangladesh
    [
        'title' => 'Real Estate Video Production in Bangladesh: The Complete 2026 Marketing Guide',
        'slug' => 'real-estate-video-production-bangladesh',
        'category_slug' => 'corporate-av-video',
        'author_name' => 'Azizul Hoque Shiplu',
        'is_featured' => 1,
        'views' => 2340,
        'published_at' => '2024-01-15 15:45:00',
        'summary' => 'How real estate developers and property marketing agencies in Dhaka use cinematic architectural walkthroughs, licensed drone aerials, and CGI tours to sell luxury properties to domestic and NRB buyers.',
        'content' => '<div class="article-lead mb-4">
    <p class="lead" style="color: #cbd5e1; font-size: 1.15rem; line-height: 1.8;">The real estate sector in Bangladesh is experiencing a sophisticated digital revolution. With Non-Resident Bangladeshi (NRB) buyers in the UK, USA, Middle East, and Canada accounting for over 30% of luxury residential sales in Dhaka and Chattogram, cinematic real estate video production has become the primary driver of property sales velocity and brand prestige.</p>
</div>

<h2 class="text-warning font-weight-bold mt-4 mb-3">1. High-Converting Real Estate Video Formats</h2>
<ul style="line-height: 1.8; color: #cbd5e1;">
    <li><strong>Cinematic Architectural Walkthroughs:</strong> Smooth gimbal and slider shots highlighting interior marble finishes, smart home integrations, modular kitchens, and ambient lighting.</li>
    <li><strong>Licensed Drone Aerial Showcase:</strong> Sweeping 4K aerial shots establishing property location, rooftop infinity pools, connectivity to major Dhaka flyovers, and green surrounding vistas.</li>
    <li><strong>Lifestyle &amp; Community Films:</strong> Featuring professional actors modeling luxury living—enjoying community gyms, children play parks, and rooftop lounge evenings.</li>
    <li><strong>3D Pre-Construction CGI Renders:</strong> Photorealistic 3D virtual walkthroughs allowing buyers to experience off-plan apartment layouts before ground breaking.</li>
</ul>

<h2 class="text-warning font-weight-bold mt-4 mb-3">2. Essential Production Techniques for Property Films</h2>
<div class="row my-4">
    <div class="col-md-4 mb-3">
        <div class="p-3 rounded border h-100" style="background: #1e293b; border-color: #334155 !important;">
            <h5 class="text-warning font-weight-bold">Ultra-Wide Distortion Control</h5>
            <p class="text-light small mb-0">Deploying 16–24mm full-frame cinema lenses with zero fish-eye barrel distortion to represent true room dimensions.</p>
        </div>
    </div>
    <div class="col-md-4 mb-3">
        <div class="p-3 rounded border h-100" style="background: #1e293b; border-color: #334155 !important;">
            <h5 class="text-warning font-weight-bold">Golden Hour Lighting</h5>
            <p class="text-light small mb-0">Scheduling exterior shoots during sunset golden hour to create warm, inviting emotional tones across glass facades.</p>
        </div>
    </div>
    <div class="col-md-4 mb-3">
        <div class="p-3 rounded border h-100" style="background: #1e293b; border-color: #334155 !important;">
            <h5 class="text-warning font-weight-bold">Motion Graphic Supers</h5>
            <p class="text-light small mb-0">Displaying animated square footage badges, floor plans, and nearby school/hospital distance markers on screen.</p>
        </div>
    </div>
</div>

<div class="p-4 rounded my-4" style="background: linear-gradient(135deg, #1e293b, #0f172a); border-left: 4px solid #f59e0b;">
    <h4 class="text-white font-weight-bold mb-2">Sell Properties Faster with AR Entertainment</h4>
    <p class="text-light mb-3">We produce premier real estate promo films, gated community overviews, and commercial property videos that captivate domestic and international investors.</p>
    <a href="services/real-estate-video" class="btn btn-warning font-weight-bold px-4 py-2" style="background: #f59e0b; color: #0f172a;">Explore Real Estate Video Services</a>
</div>',
        'tags' => 'Real Estate Video, Property Marketing Dhaka, Architectural Videography, Real Estate Drone Bangladesh, NRB Property Marketing',
        'meta_title' => 'Real Estate Video Production in Bangladesh (2026) | AR Entertainment',
        'meta_description' => 'Master real estate video production in Bangladesh. Learn how cinematic walkthroughs, drone footage, and lifestyle films sell luxury properties by AR Entertainment.',
        'meta_keywords' => 'real estate video bangladesh, property video dhaka, real estate videography bangladesh, architectural video production'
    ],

    // 19. International Shoot in Bangladesh
    [
        'title' => 'International Shoot in Bangladesh: 2026 Filming Guide & Foreign Crew Logistics',
        'slug' => 'international-shoot-in-bangladesh',
        'category_slug' => 'film-fixer-filming-in-bd',
        'author_name' => 'Azizul Hoque Shiplu',
        'is_featured' => 1,
        'views' => 3910,
        'published_at' => '2024-01-02 10:00:00',
        'summary' => 'The complete executive filming guide for foreign production houses, news networks, and documentary filmmakers planning shoots anywhere in Bangladesh.',
        'content' => '<div class="article-lead mb-4">
    <p class="lead" style="color: #cbd5e1; font-size: 1.15rem; line-height: 1.8;">From the ancient terracotta temples of Puthia and the world heritage Sundarbans mangrove jungle to the world’s largest ship-breaking yards in Sitakunda and the vibrant bustling heart of Dhaka city, Bangladesh presents some of the most visually compelling filming locations on earth. Successfully executing an international film shoot requires comprehensive preparation, governmental permits, and an experienced on-ground fixer.</p>
</div>

<h2 class="text-warning font-weight-bold mt-4 mb-3">1. Legal Permissions &amp; Visa Timeline</h2>
<p>Planning an international production in Bangladesh requires <strong>6 to 8 weeks lead time</strong>:</p>
<ul style="line-height: 1.8; color: #cbd5e1;">
    <li><strong>Ministry of Information Permit:</strong> 10 to 21 working days for national script and crew clearance.</li>
    <li><strong>Media / Journalist Visa (J-Visa):</strong> 2 to 4 weeks for official consular invitation endorsement.</li>
    <li><strong>CAAB Drone Clearances:</strong> 7 to 10 working days with registered drone pilot documentation.</li>
    <li><strong>Special Restricted Area Access:</strong> Additional security permits for the Chittagong Hill Tracts (CHT) and Rohingya humanitarian camps in Cox’s Bazar.</li>
</ul>

<h2 class="text-warning font-weight-bold mt-4 mb-3">2. Gear Customs, Carnet &amp; Local Rentals</h2>
<p>Bringing high-value cinema camera equipment into Hazrat Shahjalal International Airport (DAC) requires either an ATA Carnet or customs bank guarantee. To eliminate airport delays, international crews frequently opt to rent top-tier camera packages (RED V-Raptor, ARRI Alexa, Sony FX9) and lighting gear locally in Dhaka through AR Entertainment.</p>

<div class="row my-4">
    <div class="col-md-6 mb-3">
        <div class="p-3 rounded border h-100" style="background: #1e293b; border-color: #334155 !important;">
            <h5 class="text-warning font-weight-bold">Turnkey Logistics</h5>
            <p class="text-light small mb-0">Air-conditioned 4WD vehicle fleet, domestic flight bookings, police liaison escorts, and bilingual location managers.</p>
        </div>
    </div>
    <div class="col-md-6 mb-3">
        <div class="p-3 rounded border h-100" style="background: #1e293b; border-color: #334155 !important;">
            <h5 class="text-warning font-weight-bold">Bilingual Crew Support</h5>
            <p class="text-light small mb-0">Local assistant directors, line producers, location scouts, drone operators, and sound recordists.</p>
        </div>
    </div>
</div>

<div class="p-4 rounded my-4" style="background: linear-gradient(135deg, #1e293b, #0f172a); border-left: 4px solid #f59e0b;">
    <h4 class="text-white font-weight-bold mb-2">Plan Your International Shoot with AR Entertainment</h4>
    <p class="text-light mb-3">We have facilitated high-profile documentaries, broadcast news features, and international commercials across Bangladesh with 100% safety and regulatory compliance.</p>
    <a href="services/additional-services/professional-fixer-services-in-bangladesh" class="btn btn-warning font-weight-bold px-4 py-2" style="background: #f59e0b; color: #0f172a;">Contact Our International Fixer Desk</a>
</div>',
        'tags' => 'International Shoot Bangladesh, Film Fixer Dhaka, Filming in Bangladesh, Foreign Film Crew Support, Documentary Production Bangladesh',
        'meta_title' => 'International Shoot in Bangladesh: 2026 Filming Guide | AR Entertainment',
        'meta_description' => 'Complete guide for international filmmakers shooting in Bangladesh. Permits, media visas, local crew, gear rental, and fixer services by AR Entertainment.',
        'meta_keywords' => 'international shoot bangladesh, filming in bangladesh guide, film fixer dhaka, foreign documentary filming bangladesh'
    ],

    // 20. How to Develop Your Story With AI
    [
        'title' => 'How to Develop Your Visual Story with AI: The 2026 Filmmaker & Creator Guide',
        'slug' => 'how-to-develop-your-story-with-ai',
        'category_slug' => 'ai-video-production',
        'author_name' => 'Azizul Hoque Shiplu',
        'is_featured' => 0,
        'views' => 2670,
        'published_at' => '2023-12-28 16:00:00',
        'summary' => 'How filmmakers, scriptwriters, and commercial directors use generative AI for character development, visual storyboarding, three-act pacing, and treatment drafting.',
        'content' => '<div class="article-lead mb-4">
    <p class="lead" style="color: #cbd5e1; font-size: 1.15rem; line-height: 1.8;">Storytelling remains the fundamental currency of cinema and advertising. In 2026, artificial intelligence tools are not replacing human emotional wisdom—they are serving as an ultra-responsive creative sparring partner that accelerates concept exploration, eliminates creative block, and brings visual treatments to life before a single camera rolls.</p>
</div>

<h2 class="text-warning font-weight-bold mt-4 mb-3">1. Overcoming the Blank Page with Thematic Exploration</h2>
<p>Directors and screenwriters can use Large Language Models to rapidly pressure-test premise ideas:</p>
<ul style="line-height: 1.8; color: #cbd5e1;">
    <li><strong>Alternative Narrative Perspectives:</strong> Rewriting a commercial treatment from the perspective of secondary characters to uncover unexpected emotional angles.</li>
    <li><strong>Conflict Escalation Mapping:</strong> Brainstorming high-stakes turning points and dramatic plot obstacles for documentary subjects.</li>
    <li><strong>Cultural Idiom &amp; Dialogue Testing:</strong> Adapting character dialogue for specific regional Bengali dialects or urban colloquialisms.</li>
</ul>

<h2 class="text-warning font-weight-bold mt-4 mb-3">2. Rapid AI Storyboarding &amp; Visual Style Frames</h2>
<div class="row my-4">
    <div class="col-md-6 mb-3">
        <div class="p-3 rounded border h-100" style="background: #1e293b; border-color: #334155 !important;">
            <h5 class="text-warning font-weight-bold">Visual Pitch Decks</h5>
            <p class="text-light small mb-0">Generating photorealistic style frames with Midjourney to communicate lighting mood, anamorphic lens flares, and color timing to corporate clients.</p>
        </div>
    </div>
    <div class="col-md-6 mb-3">
        <div class="p-3 rounded border h-100" style="background: #1e293b; border-color: #334155 !important;">
            <h5 class="text-warning font-weight-bold">Animated Animatics</h5>
            <p class="text-light small mb-0">Transforming 2D storyboard panels into motion animatics with Runway Gen-3 to test editing pacing and audio timing prior to production.</p>
        </div>
    </div>
</div>

<h2 class="text-warning font-weight-bold mt-4 mb-3">3. The Irreplaceable Value of Human Direction</h2>
<p>While AI calculates statistical probabilities of story structures, only a human director understands genuine human heartbreak, nuanced Bengali humor, and the raw visual poetry of Bangladesh’s landscape. At AR Entertainment, we combine cutting-edge AI ideation with authentic directorial craftsmanship.</p>

<div class="p-4 rounded my-4" style="background: linear-gradient(135deg, #1e293b, #0f172a); border-left: 4px solid #f59e0b;">
    <h4 class="text-white font-weight-bold mb-2">Pioneer the Future of Filmmaking with AR Entertainment</h4>
    <p class="text-light mb-3">From AI-assisted commercial treatments to world-class cinematic production, AR Entertainment brings your brand stories to life.</p>
    <a href="contact-us.php" class="btn btn-warning font-weight-bold px-4 py-2" style="background: #f59e0b; color: #0f172a;">Start a Creative Collaboration</a>
</div>',
        'tags' => 'AI Storytelling, Generative AI Filmmaking, Scriptwriting AI, AI Storyboards, Commercial Treatment Development',
        'meta_title' => 'How to Develop Your Visual Story with AI | AR Entertainment',
        'meta_description' => 'Learn how filmmakers and creators use AI for story development, scriptwriting, character design, and visual storyboarding. By AR Entertainment Bangladesh.',
        'meta_keywords' => 'develop story with ai, ai filmmaking bangladesh, ai video scriptwriting, generative ai storyboards'
    ]
];

$cat_stmt = $db->prepare("SELECT id FROM categories WHERE slug = ? AND type = 'blog' LIMIT 1");

$insert_stmt = $db->prepare("
    INSERT INTO blogs (
        title,
        slug,
        summary,
        content,
        category_id,
        author_name,
        tags,
        views,
        status,
        is_featured,
        meta_title,
        meta_description,
        meta_keywords,
        published_at,
        created_at,
        updated_at
    ) VALUES (
        :title,
        :slug,
        :summary,
        :content,
        :category_id,
        :author_name,
        :tags,
        :views,
        'published',
        :is_featured,
        :meta_title,
        :meta_description,
        :meta_keywords,
        :published_at,
        NOW(),
        NOW()
    )
    ON DUPLICATE KEY UPDATE
        title = VALUES(title),
        summary = VALUES(summary),
        content = VALUES(content),
        category_id = VALUES(category_id),
        author_name = VALUES(author_name),
        tags = VALUES(tags),
        views = VALUES(views),
        status = 'published',
        is_featured = VALUES(is_featured),
        meta_title = VALUES(meta_title),
        meta_description = VALUES(meta_description),
        meta_keywords = VALUES(meta_keywords),
        published_at = VALUES(published_at),
        updated_at = NOW()
");

$seeded = 0;
foreach ($articles_batch4 as $idx => $art) {
    // Resolve Category ID
    $cat_stmt->execute([$art['category_slug']]);
    $cat_id = $cat_stmt->fetchColumn() ?: null;

    $insert_stmt->execute([
        ':title'            => $art['title'],
        ':slug'             => $art['slug'],
        ':summary'          => $art['summary'],
        ':content'          => $art['content'],
        ':category_id'      => $cat_id,
        ':author_name'      => $art['author_name'],
        ':tags'             => $art['tags'],
        ':views'            => $art['views'],
        ':is_featured'      => $art['is_featured'],
        ':meta_title'       => $art['meta_title'],
        ':meta_description' => $art['meta_description'],
        ':meta_keywords'    => $art['meta_keywords'],
        ':published_at'     => $art['published_at']
    ]);

    $seeded++;
    echo "   ✅ [" . ($idx + 1) . "/5] Seeded Blog Article: {$art['title']} (slug: {$art['slug']})\n";
}

$total_blogs = $db->query("SELECT COUNT(*) FROM blogs WHERE status = 'published'")->fetchColumn();

echo "\n========================================================\n";
echo "🏆 BATCH 5.4.4 SEEDING COMPLETED SUCCESSFULLY!\n";
echo "   - Total Articles Seeded in Batch: {$seeded}\n";
echo "   - Current Total Published Blogs in Database: {$total_blogs}\n";
echo "========================================================\n\n";
