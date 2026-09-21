<?php
/**
 * AR Entertainment - Phase 5.4 Blog Articles Seeder (Batch 5.4.5: Articles 21–25)
 * 
 * Ingests and rewrites 5 core pricing, filming cost, NGO documentary, and AI generation articles:
 * 21. AI Image Generation Guide & Best Tools 2026 (slug: ai-image-generation-guide-2026)
 * 22. Filming Costs in Bangladesh: Budget Guide (slug: filming-cost-bangladesh-2026)
 * 23. NGO & Documentary Filming in Bangladesh (slug: ngo-documentary-filming-bangladesh)
 * 24. Corporate AV Production Cost in Bangladesh (slug: corporate-av-production-cost-in-bangladesh)
 * 25. OVC Cost in Bangladesh: Complete Pricing Guide (slug: ovc-cost-in-bangladesh-2026)
 * 
 * Features:
 * - 100% Clean AR Entertainment Branding (0% Legacy Names)
 * - Author: Azizul Hoque Shiplu (Founder & Lead Director)
 * - Rich Responsive HTML Article Structure with Practical Pricing Tables, Checklists & Callouts
 * - Dynamic Category Resolution via slug
 * - Idempotent MySQL Upsert (ON DUPLICATE KEY UPDATE)
 */

declare(strict_types=1);

require_once __DIR__ . '/../../config/config.php';
require_once __DIR__ . '/../../config/db.php';
require_once __DIR__ . '/../../config/helpers.php';

echo "========================================================\n";
echo "✍️ AR ENTERTAINMENT - SEED BLOG ARTICLES BATCH 5.4.5 (21–25)\n";
echo "========================================================\n\n";

$db = db();

$articles_batch5 = [
    // 21. AI Image Generation Guide
    [
        'title' => 'AI Image Generation Guide & Best Tools (2026 Edition)',
        'slug' => 'ai-image-generation-guide-2026',
        'category_slug' => 'ai-video-production',
        'author_name' => 'Azizul Hoque Shiplu',
        'is_featured' => 0,
        'views' => 2190,
        'published_at' => '2023-12-20 12:00:00',
        'summary' => 'The ultimate 2026 practical guide to AI image generation for advertising agencies, filmmakers, and digital marketers — tool comparisons, prompt engineering frameworks, and commercial ethics.',
        'content' => '<div class="article-lead mb-4">
    <p class="lead" style="color: #cbd5e1; font-size: 1.15rem; line-height: 1.8;">Generative AI image synthesis has evolved from experimental novelty into a mission-critical pre-production asset for modern advertising agencies and filmmakers in Bangladesh. By combining advanced prompt structuring with photorealistic text-to-image engines, creative directors can generate cinematic storyboards, client pitch style frames, and high-converting marketing imagery in seconds.</p>
</div>

<h2 class="text-warning font-weight-bold mt-4 mb-3">1. Top AI Image Generation Models in 2026</h2>
<div class="row my-4">
    <div class="col-md-6 mb-3">
        <div class="p-3 rounded border h-100" style="background: #1e293b; border-color: #334155 !important;">
            <h5 class="text-warning font-weight-bold">Midjourney v6+</h5>
            <p class="text-light small mb-0">Unmatched cinematic lighting, textural nuance, photorealistic skin tones, and artistic mood generation for director treatments.</p>
        </div>
    </div>
    <div class="col-md-6 mb-3">
        <div class="p-3 rounded border h-100" style="background: #1e293b; border-color: #334155 !important;">
            <h5 class="text-warning font-weight-bold">Flux.1 &amp; Stable Diffusion 3</h5>
            <p class="text-light small mb-0">Exceptional typography integration, precise spatial composition control (ControlNet), and commercial open-source licensing.</p>
        </div>
    </div>
</div>

<h2 class="text-warning font-weight-bold mt-4 mb-3">2. Prompt Engineering Formula for Commercial Visuals</h2>
<p>To produce consistent, cinema-grade results, structure your image prompts using this 4-part framework:</p>
<ul style="line-height: 1.8; color: #cbd5e1;">
    <li><strong>Subject &amp; Wardrobe:</strong> Detailed character description (e.g., "30-year-old Bangladeshi textile factory director wearing tailored navy suit").</li>
    <li><strong>Environment &amp; Composition:</strong> Spatial setting and camera framing (e.g., "modern automated spinning mill floor in Gazipur, wide 35mm cinematic angle, leading lines").</li>
    <li><strong>Lighting &amp; Color Grading:</strong> Specific optical style (e.g., "golden hour rim lighting, soft diffuse LED key light, teal and amber DaVinci Resolve color timing").</li>
    <li><strong>Camera Equipment Simulation:</strong> Optical metadata (e.g., "shot on ARRI Alexa LF, 50mm Master Prime, shallow depth of field, subtle film grain --ar 16:9").</li>
</ul>

<div class="p-4 rounded my-4" style="background: linear-gradient(135deg, #1e293b, #0f172a); border-left: 4px solid #f59e0b;">
    <h4 class="text-white font-weight-bold mb-2">Explore Next-Gen AI Production with AR Entertainment</h4>
    <p class="text-light mb-3">We integrate cutting-edge AI concept art, rapid storyboarding, and virtual pre-visualization into all commercial productions in Bangladesh.</p>
    <a href="ai.html" class="btn btn-warning font-weight-bold px-4 py-2" style="background: #f59e0b; color: #0f172a;">Discover AI Production Services</a>
</div>',
        'tags' => 'AI Image Generation, Generative AI 2026, Midjourney Dhaka, AI Prompt Engineering, Visual Storyboarding',
        'meta_title' => 'AI Image Generation Guide & Best Tools (2026) | AR Entertainment',
        'meta_description' => 'The ultimate guide to AI image generation in 2026. Learn prompt frameworks, tool comparisons, and commercial workflows by AR Entertainment Bangladesh.',
        'meta_keywords' => 'ai image generation bangladesh, midjourney guide dhaka, ai prompt engineering, generative ai visual art'
    ],

    // 22. Filming Costs in Bangladesh
    [
        'title' => 'Filming Costs in Bangladesh: Complete 2026 Budget Guide for Productions',
        'slug' => 'filming-cost-bangladesh-2026',
        'category_slug' => 'film-fixer-filming-in-bd',
        'author_name' => 'Azizul Hoque Shiplu',
        'is_featured' => 1,
        'views' => 4120,
        'published_at' => '2024-01-12 10:30:00',
        'summary' => 'A transparent, comprehensive breakdown of filming costs in Bangladesh — film fixer day rates, bilingual crew fees, camera gear rentals, transport, permits, and sample production budgets.',
        'content' => '<div class="article-lead mb-4">
    <p class="lead" style="color: #cbd5e1; font-size: 1.15rem; line-height: 1.8;">Bangladesh offers one of the most cost-competitive production ecosystems in South Asia for international broadcasters (BBC, Netflix, Discovery), documentary filmmakers, and commercial agencies. Local crew rates, equipment rentals, and travel logistics cost 30% to 50% less than regional hubs like Singapore, Bangkok, or Mumbai, while delivering international-grade production quality.</p>
</div>

<h2 class="text-warning font-weight-bold mt-4 mb-3">1. Typical Production Day Rates in Bangladesh (USD Breakdown)</h2>

<div class="table-responsive my-4">
    <table class="table table-dark table-bordered" style="background: #1e293b; border-color: #334155;">
        <thead style="background: #0f172a; color: #f59e0b;">
            <tr>
                <th>Production Item</th>
                <th>Estimated Day Rate (USD)</th>
                <th>Notes &amp; Inclusions</th>
            </tr>
        </thead>
        <tbody style="color: #cbd5e1;">
            <tr>
                <td><strong>Lead Film Fixer / Line Producer</strong></td>
                <td>$200 – $400 / day</td>
                <td>Bilingual coordination, authority liaison, location management</td>
            </tr>
            <tr>
                <td><strong>Director of Photography (DP)</strong></td>
                <td>$250 – $600 / day</td>
                <td>Experienced cinema camera operator with lighting setup direction</td>
            </tr>
            <tr>
                <td><strong>Sound Recordist with Kit</strong></td>
                <td>$120 – $220 / day</td>
                <td>32-bit float audio, multi-channel wireless lavaliers, boom mic</td>
            </tr>
            <tr>
                <td><strong>Cinema Camera Package (Sony FX6/FX9 / RED)</strong></td>
                <td>$150 – $350 / day</td>
                <td>Body, cinema primes/zooms, monitor, V-mount batteries, tripod</td>
            </tr>
            <tr>
                <td><strong>4WD Transport with Driver &amp; Fuel</strong></td>
                <td>$90 – $160 / day</td>
                <td>Spacious Toyota HiAce or Prado suited for highway and rough terrain</td>
            </tr>
            <tr>
                <td><strong>CAAB Certified Drone Team</strong></td>
                <td>$200 – $450 / day</td>
                <td>4K drone package, licensed pilot, flight permit filing</td>
            </tr>
        </tbody>
    </table>
</div>

<h2 class="text-warning font-weight-bold mt-4 mb-3">2. Government Filming Permits &amp; Visa Fees</h2>
<ul style="line-height: 1.8; color: #cbd5e1;">
    <li><strong>Ministry of Information (MOI) Permit:</strong> Free official government processing fee, but requires local registered sponsor coordination ($300 – $600 fixer documentation fee).</li>
    <li><strong>Restricted Zone Permissions (Sundarbans / CHT):</strong> $150 – $400 depending on forest department boat hire and district administration security escort.</li>
</ul>

<div class="p-4 rounded my-4" style="background: linear-gradient(135deg, #1e293b, #0f172a); border-left: 4px solid #f59e0b;">
    <h4 class="text-white font-weight-bold mb-2">Request an Itemized Bangladesh Production Budget</h4>
    <p class="text-light mb-3">AR Entertainment provides rapid turnaround, transparent line-item budget quotes for foreign productions shooting anywhere across Bangladesh.</p>
    <a href="services/additional-services/professional-fixer-services-in-bangladesh" class="btn btn-warning font-weight-bold px-4 py-2" style="background: #f59e0b; color: #0f172a;">Get a Custom Production Estimate</a>
</div>',
        'tags' => 'Filming Cost Bangladesh, Film Fixer Rates Dhaka, Production Budget Bangladesh, Gear Rental Cost Dhaka, Crew Rates Bangladesh',
        'meta_title' => 'Filming Costs in Bangladesh: Complete 2026 Budget Guide | AR Entertainment',
        'meta_description' => 'Complete guide to filming costs in Bangladesh. Transparent daily rates for film fixers, crew, equipment rentals, permits, and transport by AR Entertainment.',
        'meta_keywords' => 'filming cost bangladesh, film fixer day rates dhaka, production budget bangladesh, camera rental cost bangladesh'
    ],

    // 23. NGO & Documentary Filming in Bangladesh
    [
        'title' => 'NGO & Documentary Filming in Bangladesh: Complete Guide for International Teams',
        'slug' => 'ngo-documentary-filming-bangladesh',
        'category_slug' => 'film-fixer-filming-in-bd',
        'author_name' => 'Azizul Hoque Shiplu',
        'is_featured' => 1,
        'views' => 3180,
        'published_at' => '2024-01-18 11:00:00',
        'summary' => 'Essential protocols, ethical consent guidelines, security measures, and fixer logistics for foreign INGO communications teams and humanitarian documentary crews in Bangladesh.',
        'content' => '<div class="article-lead mb-4">
    <p class="lead" style="color: #cbd5e1; font-size: 1.15rem; line-height: 1.8;">Bangladesh is at the forefront of global humanitarian, climate resilience, and public health innovation. International NGOs (UNICEF, UNDP, BRAC, Oxfam, Save the Children) regularly commission human-centric documentaries across the country. Capturing these stories ethically and safely requires a local production partner who understands community trust, cultural sensitivity, and regulatory clearance.</p>
</div>

<h2 class="text-warning font-weight-bold mt-4 mb-3">1. Ethical Informed Consent &amp; Child Protection</h2>
<p>Documenting vulnerable populations (climate-displaced families, flood victims, women empowerment initiatives) requires strict adherence to international safeguarding protocols:</p>
<ul style="line-height: 1.8; color: #cbd5e1;">
    <li><strong>Verbal &amp; Written Consent in Bengali:</strong> Ensuring subjects fully understand where the video will be distributed (global TV, social media, donor fundraising).</li>
    <li><strong>Child Safeguarding Compliance:</strong> Mandatory parental or legal guardian signatures prior to filming children under 18, with zero exploitation.</li>
    <li><strong>Dignity-Centered Storytelling:</strong> Framing subjects as empowered agents of change rather than passive victims of circumstance.</li>
</ul>

<h2 class="text-warning font-weight-bold mt-4 mb-3">2. Critical Filming Regions for Development Work</h2>
<div class="row my-4">
    <div class="col-md-4 mb-3">
        <div class="p-3 rounded border h-100" style="background: #1e293b; border-color: #334155 !important;">
            <h5 class="text-warning font-weight-bold">Climate Vulnerable Coast</h5>
            <p class="text-light small mb-0">Satkhira, Khulna, and Bhola: Documenting tidal surges, cyclone shelters, solar water desalination, and mangrove reforestation.</p>
        </div>
    </div>
    <div class="col-md-4 mb-3">
        <div class="p-3 rounded border h-100" style="background: #1e293b; border-color: #334155 !important;">
            <h5 class="text-warning font-weight-bold">Humanitarian Camps</h5>
            <p class="text-light small mb-0">Cox’s Bazar &amp; Bhasan Char: Requires specialized RRRC and Home Ministry permits for health and educational program documentation.</p>
        </div>
    </div>
    <div class="col-md-4 mb-3">
        <div class="p-3 rounded border h-100" style="background: #1e293b; border-color: #334155 !important;">
            <h5 class="text-warning font-weight-bold">Rural Char Islands</h5>
            <p class="text-light small mb-0">Kurigram &amp; Gaibandha: Riverine island communities pioneering floating solar schools and agricultural micro-insurance.</p>
        </div>
    </div>
</div>

<div class="p-4 rounded my-4" style="background: linear-gradient(135deg, #1e293b, #0f172a); border-left: 4px solid #f59e0b;">
    <h4 class="text-white font-weight-bold mb-2">Partner with Bangladesh’s Trusted NGO Production House</h4>
    <p class="text-light mb-3">AR Entertainment has directed and filmed high-impact documentaries for leading international NGOs across all 64 districts of Bangladesh.</p>
    <a href="services/documentary" class="btn btn-warning font-weight-bold px-4 py-2" style="background: #f59e0b; color: #0f172a;">Explore Documentary Production Services</a>
</div>',
        'tags' => 'NGO Video Production, Documentary Filming Bangladesh, INGO Video Dhaka, Ethical Storytelling, Humanitarian Filming Bangladesh',
        'meta_title' => 'NGO & Documentary Filming in Bangladesh | AR Entertainment',
        'meta_description' => 'Comprehensive guide for NGOs and documentary filmmakers in Bangladesh. Ethical consent, permits, remote logistics, and film fixing by AR Entertainment.',
        'meta_keywords' => 'ngo filming bangladesh, documentary filmmaking dhaka, ingo video production bangladesh, film fixer ngo dhaka'
    ],

    // 24. Corporate AV Production Cost in Bangladesh
    [
        'title' => 'Corporate AV Production Cost in Bangladesh (2026 Pricing Guide)',
        'slug' => 'corporate-av-production-cost-in-bangladesh',
        'category_slug' => 'corporate-av-video',
        'author_name' => 'Azizul Hoque Shiplu',
        'is_featured' => 1,
        'views' => 3650,
        'published_at' => '2024-01-22 13:30:00',
        'summary' => 'Transparent budget breakdown of Corporate AV (Audio-Visual) production in Dhaka — cost tiers, scripting fees, factory shoot days, motion graphics, and ROI metrics.',
        'content' => '<div class="article-lead mb-4">
    <p class="lead" style="color: #cbd5e1; font-size: 1.15rem; line-height: 1.8;">Commissioning a Corporate Audio-Visual (AV) profile is a major strategic investment for corporations, banks, and manufacturing exporters in Bangladesh. A Corporate AV communicates executive vision, industrial capability, financial stability, and sustainability credentials to global buyers and investors. Understanding what drives production costs helps organizations budget accurately.</p>
</div>

<h2 class="text-warning font-weight-bold mt-4 mb-3">1. Corporate AV Budget Tiers in Bangladesh (2026)</h2>

<div class="row my-4">
    <div class="col-md-4 mb-3">
        <div class="p-3 rounded border h-100" style="background: #1e293b; border-color: #334155 !important;">
            <h5 class="text-warning font-weight-bold">Essential Tier</h5>
            <p class="text-white font-weight-bold" style="font-size: 1.25rem;">৳1,50,000 – ৳3,00,000</p>
            <p class="text-light small mb-0">1 shoot day in Dhaka, single 4K camera setup, executive interview, clean corporate b-roll, 2D lower thirds, and licensed music.</p>
        </div>
    </div>
    <div class="col-md-4 mb-3">
        <div class="p-3 rounded border h-100" style="background: #1e293b; border-color: #334155 !important;">
            <h5 class="text-warning font-weight-bold">Professional Tier</h5>
            <p class="text-white font-weight-bold" style="font-size: 1.25rem;">৳3,50,000 – ৳7,50,000</p>
            <p class="text-light small mb-0">2–3 shoot days across Dhaka &amp; industrial belts, multi-camera cinema package (Sony FX/RED), drone aerials, professional voiceover, and color grading.</p>
        </div>
    </div>
    <div class="col-md-4 mb-3">
        <div class="p-3 rounded border h-100" style="background: #1e293b; border-color: #334155 !important;">
            <h5 class="text-warning font-weight-bold">Enterprise / Broadcast Tier</h5>
            <p class="text-white font-weight-bold" style="font-size: 1.25rem;">৳8,00,000 – ৳18,00,000+</p>
            <p class="text-light small mb-0">4+ shoot days nationwide, ARRI/RED cinema packages, 3D architectural/machinery CGI, international bilingual voiceovers, and orchestral score.</p>
        </div>
    </div>
</div>

<h2 class="text-warning font-weight-bold mt-4 mb-3">2. Core Cost Factors Behind Corporate AV Production</h2>
<ul style="line-height: 1.8; color: #cbd5e1;">
    <li><strong>Scriptwriting &amp; Creative Treatment:</strong> Engaging professional corporate screenwriters to articulate complex financial or engineering milestones.</li>
    <li><strong>Multi-Location Factory Coverage:</strong> Logistics for filming expansive industrial plants in Gazipur, Narayanganj, Comilla EPZ, and Chattogram.</li>
    <li><strong>Advanced Visual Effects &amp; CGI:</strong> 3D product exploded views, animated data charts, and LEED green building architectural overlays.</li>
</ul>

<div class="p-4 rounded my-4" style="background: linear-gradient(135deg, #1e293b, #0f172a); border-left: 4px solid #f59e0b;">
    <h4 class="text-white font-weight-bold mb-2">Request an Itemized Corporate AV Proposal</h4>
    <p class="text-light mb-3">AR Entertainment crafts landmark corporate AV profiles engineered for enterprise credibility and international buyer confidence.</p>
    <a href="services/corporate-av" class="btn btn-warning font-weight-bold px-4 py-2" style="background: #f59e0b; color: #0f172a;">Request Corporate AV Quote</a>
</div>',
        'tags' => 'Corporate AV Cost, Corporate Video Pricing Dhaka, Corporate Film Budget Bangladesh, RMG Corporate AV Cost, Business Video Cost Dhaka',
        'meta_title' => 'Corporate AV Production Cost in Bangladesh (2026 Guide) | AR Entertainment',
        'meta_description' => 'Understand Corporate AV production costs in Bangladesh. Budget tiers, scripting fees, equipment packages, and factory filming costs by AR Entertainment.',
        'meta_keywords' => 'corporate av cost bangladesh, corporate video pricing dhaka, corporate film budget bangladesh, factory video cost dhaka'
    ],

    // 25. OVC Cost in Bangladesh
    [
        'title' => 'OVC Cost in Bangladesh: Complete Pricing & Budget Guide (2026)',
        'slug' => 'ovc-cost-in-bangladesh-2026',
        'category_slug' => 'ovc-digital-ads',
        'author_name' => 'Azizul Hoque Shiplu',
        'is_featured' => 1,
        'views' => 4290,
        'published_at' => '2024-01-26 15:00:00',
        'summary' => 'The comprehensive 2026 pricing and budgeting guide for Online Video Commercials (OVCs) in Bangladesh — budget tiers, cast & location fees, multi-format social exports, and digital ROI.',
        'content' => '<div class="article-lead mb-4">
    <p class="lead" style="color: #cbd5e1; font-size: 1.15rem; line-height: 1.8;">Online Video Commercials (OVCs) represent the fastest-growing advertising category in Bangladesh. Forward-thinking brands in FMCG, fintech, e-commerce, and telecommunications invest heavily in OVCs due to their precise digital targeting, measurable click-through conversions, and cost efficiency compared to legacy television broadcasts.</p>
</div>

<h2 class="text-warning font-weight-bold mt-4 mb-3">1. OVC Budget Tiers in Bangladesh (2026 Breakdown)</h2>

<div class="row my-4">
    <div class="col-md-4 mb-3">
        <div class="p-3 rounded border h-100" style="background: #1e293b; border-color: #334155 !important;">
            <h5 class="text-warning font-weight-bold">Social Performance OVC</h5>
            <p class="text-white font-weight-bold" style="font-size: 1.25rem;">৳80,000 – ৳1,80,000</p>
            <p class="text-light small mb-0">Fast-paced 15–30s digital ad, single studio/office location, micro-influencer or model, kinetic subtitles, optimized for Facebook &amp; TikTok.</p>
        </div>
    </div>
    <div class="col-md-4 mb-3">
        <div class="p-3 rounded border h-100" style="background: #1e293b; border-color: #334155 !important;">
            <h5 class="text-warning font-weight-bold">Brand Storytelling OVC</h5>
            <p class="text-white font-weight-bold" style="font-size: 1.25rem;">৳2,20,000 – ৳5,00,000</p>
            <p class="text-light small mb-0">60–90s emotional digital commercial, 2 outdoor locations, professional actors, custom background music score, and 4K cinema cameras.</p>
        </div>
    </div>
    <div class="col-md-4 mb-3">
        <div class="p-3 rounded border h-100" style="background: #1e293b; border-color: #334155 !important;">
            <h5 class="text-warning font-weight-bold">High-Impact Campaign OVC</h5>
            <p class="text-white font-weight-bold" style="font-size: 1.25rem;">৳6,00,000 – ৳15,00,000+</p>
            <p class="text-light small mb-0">Full-scale commercial set build, celebrity / top-tier talent casting, RED/ARRI cinema optics, custom musical jingle, and multi-cut ad variants.</p>
        </div>
    </div>
</div>

<h2 class="text-warning font-weight-bold mt-4 mb-3">2. What Drives OVC Costs in Bangladesh?</h2>
<ul style="line-height: 1.8; color: #cbd5e1;">
    <li><strong>Talent &amp; Model Casting:</strong> Emerging digital influencers vs. prominent national actors.</li>
    <li><strong>Set Design &amp; Art Direction:</strong> Physical set construction in Dhaka studios vs. natural real-world locations.</li>
    <li><strong>Multi-Format Deliverables:</strong> Producing 16:9 (YouTube), 1:1 (Facebook/Instagram), and 9:16 (TikTok/Reels) with custom framing and dynamic motion typography.</li>
</ul>

<div class="p-4 rounded my-4" style="background: linear-gradient(135deg, #1e293b, #0f172a); border-left: 4px solid #f59e0b;">
    <h4 class="text-white font-weight-bold mb-2">Launch High-Converting OVCs with AR Entertainment</h4>
    <p class="text-light mb-3">AR Entertainment crafts performance-engineered Online Video Commercials that stop thumbs and multiply return on ad spend (ROAS).</p>
    <a href="services/online-video-commercial" class="btn btn-warning font-weight-bold px-4 py-2" style="background: #f59e0b; color: #0f172a;">Explore OVC Production Services</a>
</div>',
        'tags' => 'OVC Cost Bangladesh, Online Video Commercial Pricing, Video Ad Cost Dhaka, Facebook Video Ad Budget, Digital Commercial Production',
        'meta_title' => 'OVC Cost in Bangladesh: Complete Pricing & Budget Guide (2026) | AR Entertainment',
        'meta_description' => 'Complete guide to OVC costs and pricing in Bangladesh. Learn budget tiers, talent fees, format deliverables, and ROI strategies by AR Entertainment.',
        'meta_keywords' => 'ovc cost bangladesh, online video commercial pricing dhaka, video ad cost bangladesh, facebook ad video production cost'
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
foreach ($articles_batch5 as $idx => $art) {
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
echo "🏆 BATCH 5.4.5 SEEDING COMPLETED SUCCESSFULLY!\n";
echo "   - Total Articles Seeded in Batch: {$seeded}\n";
echo "   - Current Total Published Blogs in Database: {$total_blogs}\n";
echo "========================================================\n\n";
