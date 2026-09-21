<?php
/**
 * AR Entertainment - Phase 5.4 Blog Articles Seeder (Batch 5.4.2: Articles 6–10)
 * 
 * Ingests and rewrites 5 core AI, SEO, OVC, and video craft articles:
 * 6.  Harnessing the Power of ChatGPT for Content Creation (slug: harnessing-the-power-of-chatgpt)
 * 7.  7 Step Guide for Creating & Optimizing Video (slug: 7-step-guide-for-creating-optimizing-video)
 * 8.  Unleashing the Power of Online Video Commercials (slug: unleashing-power-of-online-video)
 * 9.  The Complete Video Production Process (slug: video-production-process)
 * 10. How to Create Quality Video Ads (slug: how-to-create-quality-video-ads)
 * 
 * Features:
 * - 100% Clean AR Entertainment Branding (0% Legacy Names)
 * - Author: Azizul Hoque Shiplu (Founder & Lead Director)
 * - Rich Responsive HTML Article Structure with Practical Frameworks & Callouts
 * - Dynamic Category Resolution via slug
 * - Idempotent MySQL Upsert (ON DUPLICATE KEY UPDATE)
 */

declare(strict_types=1);

require_once __DIR__ . '/../../config/config.php';
require_once __DIR__ . '/../../config/db.php';
require_once __DIR__ . '/../../config/helpers.php';

echo "========================================================\n";
echo "✍️ AR ENTERTAINMENT - SEED BLOG ARTICLES BATCH 5.4.2 (6–10)\n";
echo "========================================================\n\n";

$db = db();

$articles_batch2 = [
    // 6. Harnessing the Power of ChatGPT
    [
        'title' => 'Harnessing the Power of ChatGPT & AI Scripting for Video Production',
        'slug' => 'harnessing-the-power-of-chatgpt',
        'category_slug' => 'ai-video-production',
        'author_name' => 'Azizul Hoque Shiplu',
        'is_featured' => 1,
        'views' => 1890,
        'published_at' => '2024-03-20 11:00:00',
        'summary' => 'Discover how generative AI and ChatGPT prompt engineering are transforming video scripting, commercial brainstorming, treatment drafting, and creative workflows in Bangladesh.',
        'content' => '<div class="article-lead mb-4">
    <p class="lead" style="color: #cbd5e1; font-size: 1.15rem; line-height: 1.8;">Large Language Models (LLMs) like OpenAI’s ChatGPT, Google Gemini, and Anthropic Claude have revolutionized creative workflows across the global film and advertising industries. In Bangladesh, video production teams, creative agencies, and corporate marketing departments are leveraging generative AI not to replace human directorial vision, but to multiply creative productivity by 10x during pre-production.</p>
</div>

<h2 class="text-warning font-weight-bold mt-4 mb-3">1. Accelerating Creative Brainstorming &amp; Concept Variations</h2>
<p>The earliest phase of any video commercial or documentary is conceptual exploration. Instead of staring at a blank page, creative directors can use structured prompt frameworks to generate dozens of distinct thematic angles within minutes:</p>
<ul style="line-height: 1.8; color: #cbd5e1;">
    <li><strong>Audience Persona Exploration:</strong> Prompting AI to simulate distinct demographic reactions (e.g., Gen-Z mobile gamers vs. middle-aged suburban homemakers in Dhaka) to test emotional appeal.</li>
    <li><strong>Metaphor &amp; Visual Hook Generation:</strong> Brainstorming high-contrast visual metaphors to represent abstract corporate values like data security or fintech trust.</li>
    <li><strong>Competitor Contrast Mapping:</strong> Identifying storytelling clichés in the telecom or FMCG sectors to ensure your concept stands apart from broadcast noise.</li>
</ul>

<h2 class="text-warning font-weight-bold mt-4 mb-3">2. Rapid Two-Column Script Drafting &amp; Dialogue Polish</h2>
<p>Transforming an abstract creative concept into an actionable shootable screenplay requires strict formatting. ChatGPT excels at formatting two-column audiovisual scripts:</p>
<div class="row my-4">
    <div class="col-md-6 mb-3">
        <div class="p-3 rounded border h-100" style="background: #1e293b; border-color: #334155 !important;">
            <h5 class="text-warning font-weight-bold">Visual Description Column</h5>
            <p class="text-light small mb-0">Specifying camera focal lengths (e.g., 35mm close-up), lighting changes, talent blocking, and on-screen graphical supers (lower thirds).</p>
        </div>
    </div>
    <div class="col-md-6 mb-3">
        <div class="p-3 rounded border h-100" style="background: #1e293b; border-color: #334155 !important;">
            <h5 class="text-warning font-weight-bold">Audio &amp; Voiceover Column</h5>
            <p class="text-light small mb-0">Calculating syllable count and timing pacing (strictly 120–140 words per minute for Bangla or English narration) to prevent rushed voice tracks.</p>
        </div>
    </div>
</div>

<h2 class="text-warning font-weight-bold mt-4 mb-3">3. Developing Comprehensive Director’s Treatments</h2>
<p>Pitching a commercial concept to enterprise clients requires an extensive Director’s Treatment covering narrative tone, color palette palettes, wardrobe direction, and lighting aesthetics. Generative AI allows directors to articulate complex visual metaphors in polished, persuasive language.</p>

<h2 class="text-warning font-weight-bold mt-4 mb-3">4. The Critical Role of Human-in-the-Loop Direction</h2>
<p>AI generates linguistic patterns, but only seasoned human directors understand cultural nuance, Bengali emotional idioms, and the physical realities of location lighting in Dhaka. At AR Entertainment, we combine cutting-edge AI ideation tools with battle-tested cinematic craftsmanship.</p>

<div class="p-4 rounded my-4" style="background: linear-gradient(135deg, #1e293b, #0f172a); border-left: 4px solid #f59e0b;">
    <h4 class="text-white font-weight-bold mb-2">Explore AI-Powered Commercial Production</h4>
    <p class="text-light mb-3">AR Entertainment pioneers hybrid AI workflows, from rapid script prototyping and voice dubbing to full AI brand video generation for startups and multinational corporations.</p>
    <a href="ai.html" class="btn btn-warning font-weight-bold px-4 py-2" style="background: #f59e0b; color: #0f172a;">Discover AI Production Solutions</a>
</div>',
        'tags' => 'ChatGPT, AI Video Scripting, Generative AI, Video Pre-Production, AI Content Creation',
        'meta_title' => 'Harnessing ChatGPT for Video Production & Scripting | AR Entertainment',
        'meta_description' => 'Learn how ChatGPT and generative AI are transforming video scripting, treatments, and creative workflows in Bangladesh. By AR Entertainment.',
        'meta_keywords' => 'chatgpt video production, ai video scripting bangladesh, generative ai filmmaking, scriptwriting chatgpt dhaka'
    ],

    // 7. 7 Step Guide for Creating & Optimizing Video
    [
        'title' => '7 Step Guide for Creating & Optimizing High-Ranking Video Content',
        'slug' => '7-step-guide-for-creating-optimizing-video',
        'category_slug' => 'video-marketing-seo',
        'author_name' => 'Azizul Hoque Shiplu',
        'is_featured' => 0,
        'views' => 1430,
        'published_at' => '2024-02-14 15:00:00',
        'summary' => 'A proven 7-step optimization framework for YouTube, Google Video Search, and Facebook to maximize organic rankings, click-through rates (CTR), and viewer retention.',
        'content' => '<div class="article-lead mb-4">
    <p class="lead" style="color: #cbd5e1; font-size: 1.15rem; line-height: 1.8;">Creating exceptional video content is only half the battle. If your target audience cannot find your videos on YouTube search results, Google Video Carousels, or Facebook watch feeds, your production investment is severely underleveraged. Video Search Engine Optimization (Video SEO) is the systematic methodology of optimizing your video assets for maximum discoverability and algorithmic recommendation.</p>
</div>

<h2 class="text-warning font-weight-bold mt-4 mb-3">The 7-Step Video Optimization Lifecycle</h2>

<div class="row my-4">
    <div class="col-md-6 mb-3">
        <div class="p-3 rounded border h-100" style="background: #1e293b; border-color: #334155 !important;">
            <h5 class="text-warning font-weight-bold">Step 1: Search Intent &amp; Keyword Research</h5>
            <p class="text-light small mb-0">Identify high-volume, low-competition search queries using tools like Ahrefs, SEMrush, and YouTube autocomplete before writing the script.</p>
        </div>
    </div>
    <div class="col-md-6 mb-3">
        <div class="p-3 rounded border h-100" style="background: #1e293b; border-color: #334155 !important;">
            <h5 class="text-warning font-weight-bold">Step 2: Scripting for 3-Second Retention</h5>
            <p class="text-light small mb-0">Place the core value proposition in the first 5 seconds. Hook viewers immediately before they scroll away.</p>
        </div>
    </div>
    <div class="col-md-6 mb-3">
        <div class="p-3 rounded border h-100" style="background: #1e293b; border-color: #334155 !important;">
            <h5 class="text-warning font-weight-bold">Step 3: High-Contrast Custom Thumbnails</h5>
            <p class="text-light small mb-0">Design 16:9 custom thumbnails featuring bold typography (max 4 words), high-contrast subject cutouts, and clear facial expressions.</p>
        </div>
    </div>
    <div class="col-md-6 mb-3">
        <div class="p-3 rounded border h-100" style="background: #1e293b; border-color: #334155 !important;">
            <h5 class="text-warning font-weight-bold">Step 4: SEO-Driven Metadata &amp; Chapters</h5>
            <p class="text-light small mb-0">Include primary keywords in the video title, first 2 lines of the description, and configure YouTube timestamp chapters for Google key moments.</p>
        </div>
    </div>
    <div class="col-md-6 mb-3">
        <div class="p-3 rounded border h-100" style="background: #1e293b; border-color: #334155 !important;">
            <h5 class="text-warning font-weight-bold">Step 5: Full Captions &amp; SRT Transcripts</h5>
            <p class="text-light small mb-0">Upload accurate bilingual SRT subtitle files (Bangla and English) to allow search engine crawlers to index every spoken word.</p>
        </div>
    </div>
    <div class="col-md-6 mb-3">
        <div class="p-3 rounded border h-100" style="background: #1e293b; border-color: #334155 !important;">
            <h5 class="text-warning font-weight-bold">Step 6: Schema.org VideoObject Markup</h5>
            <p class="text-light small mb-0">Embed structured JSON-LD Schema markup on your web landing pages to qualify for Google rich video search snippets.</p>
        </div>
    </div>
</div>

<div class="p-3 rounded border mb-4" style="background: #1e293b; border-color: #334155 !important;">
    <h5 class="text-warning font-weight-bold">Step 7: Strategic Multi-Platform Distribution &amp; Backlinking</h5>
    <p class="text-light small mb-0">Cross-promote across social media channels, embed in authoritative blog articles, and syndicate to industry publications to generate quality external signals.</p>
</div>

<div class="p-4 rounded my-4" style="background: linear-gradient(135deg, #1e293b, #0f172a); border-left: 4px solid #f59e0b;">
    <h4 class="text-white font-weight-bold mb-2">Dominate Video Search with AR Entertainment</h4>
    <p class="text-light mb-3">We combine cinematic production with technical Video SEO to ensure your corporate AVs, tutorials, and commercials rank at the very top of Google and YouTube search results.</p>
    <a href="contact-us.php" class="btn btn-warning font-weight-bold px-4 py-2" style="background: #f59e0b; color: #0f172a;">Plan a Video SEO Campaign</a>
</div>',
        'tags' => 'Video SEO, YouTube Optimization, Video Marketing, Google Video Search, Content Ranking',
        'meta_title' => '7 Step Guide to Video SEO & Content Optimization | AR Entertainment',
        'meta_description' => 'Master Video SEO with this 7-step guide. Learn how to optimize video content for YouTube and Google search rankings in Bangladesh by AR Entertainment.',
        'meta_keywords' => 'video seo bangladesh, youtube video optimization, video marketing guide dhaka, rank video on google'
    ],

    // 8. Unleashing Power of Online Video
    [
        'title' => 'Unleashing the Power of Online Video Commercials (OVCs) in Bangladesh',
        'slug' => 'unleashing-power-of-online-video',
        'category_slug' => 'ovc-digital-ads',
        'author_name' => 'Azizul Hoque Shiplu',
        'is_featured' => 1,
        'views' => 2750,
        'published_at' => '2024-01-18 10:30:00',
        'summary' => 'Why digital-first Online Video Commercials (OVCs) are outperforming traditional broadcast TV in Bangladesh, and how leading brands achieve measurable conversion growth.',
        'content' => '<div class="article-lead mb-4">
    <p class="lead" style="color: #cbd5e1; font-size: 1.15rem; line-height: 1.8;">The media landscape in Bangladesh has crossed a critical threshold. With over 130 million active mobile internet subscribers and social media penetration at an all-time high, <strong>Online Video Commercials (OVCs)</strong> have overtaken traditional broadcast television as the primary revenue and engagement engine for forward-thinking brands.</p>
</div>

<h2 class="text-warning font-weight-bold mt-4 mb-3">Why OVCs Outperform Traditional TV Commercials</h2>
<ul style="line-height: 1.8; color: #cbd5e1;">
    <li><strong>Laser-Targeted Audience Segmentation:</strong> Unlike television broadcast which broadcasts broadly, digital OVC campaigns can target exact demographics, interests, geolocations (Dhaka, Chittagong, Sylhet), and buying behaviors.</li>
    <li><strong>Immediate Clickable Conversion:</strong> OVCs allow direct in-video and overlay CTA buttons ("Order Now", "Sign Up", "Download App"), turning brand storytelling directly into tracked revenue.</li>
    <li><strong>Real-Time Performance Metrics:</strong> Instant access to Click-Through Rates (CTR), Video Completion Rates (VCR), Cost Per View (CPV), and Return on Ad Spend (ROAS).</li>
    <li><strong>Agile Creative Iteration:</strong> If an ad variant underperforms, marketers can swap creative hooks, titles, or voiceovers within hours without re-booking expensive broadcast slots.</li>
</ul>

<h2 class="text-warning font-weight-bold mt-4 mb-3">Key OVC Formats for Bangladeshi Brands</h2>
<div class="row my-4">
    <div class="col-md-4 mb-3">
        <div class="p-3 rounded border h-100" style="background: #1e293b; border-color: #334155 !important;">
            <h5 class="text-warning font-weight-bold">15s Non-Skippable Ads</h5>
            <p class="text-light small mb-0">Punchy, high-energy product launch spots optimized for YouTube pre-roll and Facebook in-stream placements.</p>
        </div>
    </div>
    <div class="col-md-4 mb-3">
        <div class="p-3 rounded border h-100" style="background: #1e293b; border-color: #334155 !important;">
            <h5 class="text-warning font-weight-bold">60s–90s Emotional DVCs</h5>
            <p class="text-light small mb-0">Story-driven brand films celebrating cultural moments (Eid, Pohela Boishakh, Mother’s Day) that spark massive organic shares.</p>
        </div>
    </div>
    <div class="col-md-4 mb-3">
        <div class="p-3 rounded border h-100" style="background: #1e293b; border-color: #334155 !important;">
            <h5 class="text-warning font-weight-bold">9:16 Vertical Video Ads</h5>
            <p class="text-light small mb-0">Immersive full-screen video ads built specifically for TikTok, Facebook Reels, and Instagram Stories.</p>
        </div>
    </div>
</div>

<div class="p-4 rounded my-4" style="background: linear-gradient(135deg, #1e293b, #0f172a); border-left: 4px solid #f59e0b;">
    <h4 class="text-white font-weight-bold mb-2">Launch Your Next High-Impact OVC Campaign</h4>
    <p class="text-light mb-3">AR Entertainment crafts performance-focused Online Video Commercials engineered for maximum digital conversion across Bangladesh.</p>
    <a href="services/online-video-commercial" class="btn btn-warning font-weight-bold px-4 py-2" style="background: #f59e0b; color: #0f172a;">Explore OVC Production Services</a>
</div>',
        'tags' => 'OVC Production, Online Video Commercial, Video Advertising, Digital Marketing Bangladesh, Video Ads',
        'meta_title' => 'Unleashing the Power of OVCs in Bangladesh | AR Entertainment',
        'meta_description' => 'Discover how Online Video Commercials (OVCs) drive brand growth and sales in Bangladesh. Strategy, formats, and best practices by AR Entertainment.',
        'meta_keywords' => 'ovc production bangladesh, online video commercial dhaka, video advertising bangladesh, social media video ads'
    ],

    // 9. The Complete Video Production Process
    [
        'title' => 'The Complete Video Production Process: From Concept to Final Delivery',
        'slug' => 'video-production-process',
        'category_slug' => 'corporate-av-video',
        'author_name' => 'Azizul Hoque Shiplu',
        'is_featured' => 0,
        'views' => 3120,
        'published_at' => '2023-11-28 14:00:00',
        'summary' => 'A comprehensive guide to modern video production workflows in Dhaka — pre-production planning, studio & location shooting, DaVinci Resolve color grading, and broadcast mastering.',
        'content' => '<div class="article-lead mb-4">
    <p class="lead" style="color: #cbd5e1; font-size: 1.15rem; line-height: 1.8;">Behind every captivating corporate film, television commercial, or brand documentary lies a rigorous, multi-stage production framework. Whether producing a multimillion-taka broadcast spot or an agile corporate overview in Dhaka, adhering to a disciplined production pipeline ensures on-time delivery, creative excellence, and total budget control.</p>
</div>

<h2 class="text-warning font-weight-bold mt-4 mb-3">Phase 1: Pre-Production (The Foundation)</h2>
<p>Pre-production represents 70% of a project’s eventual success. Skipping steps here invariably leads to chaotic shoot days and costly delays:</p>
<ul style="line-height: 1.8; color: #cbd5e1;">
    <li><strong>Creative Strategy &amp; Brief:</strong> Identifying core objectives, key messages, target demographic, and brand guidelines.</li>
    <li><strong>Scriptwriting &amp; Storyboarding:</strong> Crafting the screenplay and frame-by-frame illustrated storyboards.</li>
    <li><strong>Location Scouting &amp; Permissions:</strong> Securing shoot clearances for industrial plants, heritage sites, or urban streets across Bangladesh.</li>
    <li><strong>Casting &amp; Crew Assembling:</strong> Auditioning talent, hiring lead cinematographers, gaffers, and sound engineers.</li>
</ul>

<h2 class="text-warning font-weight-bold mt-4 mb-3">Phase 2: Production (Principal Photography)</h2>
<p>This is where the creative vision is captured onto digital cinema sensors. Key on-set operational standards include:</p>
<ul style="line-height: 1.8; color: #cbd5e1;">
    <li><strong>Camera Department:</strong> Deploying cinema packages (RED, ARRI, Sony FX) with prime cinema lenses for cinematic texture.</li>
    <li><strong>Lighting &amp; Grip:</strong> Shaping high-contrast cinematic lighting with modern LED fixtures, softboxes, and bounce flags.</li>
    <li><strong>Sound Recording:</strong> Multi-track 32-bit float audio capture with boom microphones and wireless lavaliers to ensure zero clipping.</li>
</ul>

<h2 class="text-warning font-weight-bold mt-4 mb-3">Phase 3: Post-Production (The Polish)</h2>
<div class="row my-4">
    <div class="col-md-4 mb-3">
        <div class="p-3 rounded border h-100" style="background: #1e293b; border-color: #334155 !important;">
            <h5 class="text-warning font-weight-bold">1. Editorial Assembly</h5>
            <p class="text-light small mb-0">Rough cut assembly, pacing refinement, and director/client review passes.</p>
        </div>
    </div>
    <div class="col-md-4 mb-3">
        <div class="p-3 rounded border h-100" style="background: #1e293b; border-color: #334155 !important;">
            <h5 class="text-warning font-weight-bold">2. Color Grading</h5>
            <p class="text-light small mb-0">Precision color timing in DaVinci Resolve to establish cinematic mood and skin-tone perfection.</p>
        </div>
    </div>
    <div class="col-md-4 mb-3">
        <div class="p-3 rounded border h-100" style="background: #1e293b; border-color: #334155 !important;">
            <h5 class="text-warning font-weight-bold">3. Sound Design &amp; Mix</h5>
            <p class="text-light small mb-0">Foley sound effects, custom musical score, voiceover cleanup, and final stereo/5.1 mix.</p>
        </div>
    </div>
</div>

<div class="p-4 rounded my-4" style="background: linear-gradient(135deg, #1e293b, #0f172a); border-left: 4px solid #f59e0b;">
    <h4 class="text-white font-weight-bold mb-2">Experience Seamless Video Production with AR Entertainment</h4>
    <p class="text-light mb-3">From initial brainstorming to broadcast-ready masters, AR Entertainment delivers world-class video production services across Bangladesh.</p>
    <a href="contact-us.php" class="btn btn-warning font-weight-bold px-4 py-2" style="background: #f59e0b; color: #0f172a;">Start Your Production</a>
</div>',
        'tags' => 'Video Production Process, Filmmaking Pipeline, Pre-Production, Color Grading, Dhaka Production House',
        'meta_title' => 'The Complete Video Production Process Guide | AR Entertainment',
        'meta_description' => 'Understand the complete video production process from concept and shooting to color grading and sound design. By AR Entertainment Bangladesh.',
        'meta_keywords' => 'video production process bangladesh, filmmaking pipeline dhaka, video post production, commercial shoot workflow'
    ],

    // 10. How to Create Quality Video Ads
    [
        'title' => 'How to Create High-Converting Video Ads in Bangladesh (2026 Guide)',
        'slug' => 'how-to-create-quality-video-ads',
        'category_slug' => 'ovc-digital-ads',
        'author_name' => 'Azizul Hoque Shiplu',
        'is_featured' => 1,
        'views' => 2410,
        'published_at' => '2024-01-05 12:00:00',
        'summary' => 'The anatomy of high-performance video advertisements: crafting irresistible 3-second hooks, pacing emotional storytelling, platform aspect ratio optimization, and conversion CTAs.',
        'content' => '<div class="article-lead mb-4">
    <p class="lead" style="color: #cbd5e1; font-size: 1.15rem; line-height: 1.8;">In the modern attention economy, consumers scroll through hundreds of meters of social media feeds every single day. A successful video advertisement has less than 3 seconds to halt the user’s thumb, spark emotional curiosity, and deliver a compelling solution. Creating high-converting video ads in Bangladesh requires a scientific blend of psychology, visual craft, and platform-native pacing.</p>
</div>

<h2 class="text-warning font-weight-bold mt-4 mb-3">1. The 3-Second Hook: The Difference Between Viral &amp; Ignored</h2>
<p>Over 65% of viewers drop off before the 10-second mark if the opening hook fails. Proven hook frameworks include:</p>
<ul style="line-height: 1.8; color: #cbd5e1;">
    <li><strong>The Problem-Agitation Hook:</strong> Immediately present a relatable pain point (e.g., "Tired of slow internet buffering during live cricket matches?").</li>
    <li><strong>The Pattern-Interrupt Visual:</strong> A startling visual action, dramatic close-up, or unexpected statement that breaks feed monotony.</li>
    <li><strong>The Curiosity Gap:</strong> Tease an astonishing outcome or revelation that compels viewers to watch until the climax.</li>
</ul>

<h2 class="text-warning font-weight-bold mt-4 mb-3">2. Pacing &amp; Visual Dynamic Retention</h2>
<p>Keep visual interest constantly refreshed:</p>
<ul style="line-height: 1.8; color: #cbd5e1;">
    <li><strong>Cut Every 2 to 3 Seconds:</strong> Avoid static shots. Introduce camera movement, B-roll cutaways, punch-in zooms, and motion text supers.</li>
    <li><strong>Sound-Off Optimization:</strong> Over 70% of mobile users watch social videos with sound muted in public transit or offices. Always embed dynamic kinetic subtitles and on-screen graphic overlays.</li>
    <li><strong>Platform-Specific Aspect Ratios:</strong> Produce custom exports in 9:16 (vertical for Reels/TikTok), 1:1 (square for Instagram/Facebook feed), and 16:9 (widescreen for YouTube).</li>
</ul>

<h2 class="text-warning font-weight-bold mt-4 mb-3">3. Clear, Frictionless Call-to-Action (CTA)</h2>
<p>Never conclude a video ad without instructing the viewer on the exact next step. Whether it’s clicking a link, using a promo code, or dialing a hotline, the CTA must be visually prominent for the final 5 seconds of the video.</p>

<div class="p-4 rounded my-4" style="background: linear-gradient(135deg, #1e293b, #0f172a); border-left: 4px solid #f59e0b;">
    <h4 class="text-white font-weight-bold mb-2">Create High-Performing Video Ads with AR Entertainment</h4>
    <p class="text-light mb-3">Our team of commercial directors, copywriters, and motion designers create conversion-engineered video ads that drive real measurable business growth.</p>
    <a href="contact-us.php" class="btn btn-warning font-weight-bold px-4 py-2" style="background: #f59e0b; color: #0f172a;">Request a Video Ad Proposal</a>
</div>',
        'tags' => 'Video Ads, High-Converting Ads, Digital Advertising, Facebook Video Ads, TikTok Video Ads Bangladesh',
        'meta_title' => 'How to Create High-Converting Video Ads in Bangladesh | AR Entertainment',
        'meta_description' => 'Learn how to create high-converting video ads in Bangladesh. Master 3-second hooks, pacing, kinetic subtitles, and strong CTAs by AR Entertainment.',
        'meta_keywords' => 'video ads bangladesh, high converting video ads, facebook video ads dhaka, tiktok video advertising'
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
foreach ($articles_batch2 as $idx => $art) {
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
echo "🏆 BATCH 5.4.2 COMPLETED SUCCESSFULLY!\n";
echo "   - Total Articles Seeded in Batch: {$seeded}\n";
echo "   - Current Total Published Blogs in Database: {$total_blogs}\n";
echo "========================================================\n\n";
