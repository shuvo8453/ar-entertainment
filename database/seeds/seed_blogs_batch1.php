<?php
/**
 * AR Entertainment - Phase 5.4 Blog Articles Seeder (Batch 5.4.1: Articles 1–5)
 * 
 * Ingests and rewrites 5 core production & strategy articles:
 * 1. Mastering Mobile Video Production (slug: mastering-mobile-video-production)
 * 2. The Rising Phenomenon of OTT Platforms (slug: phenomenon-of-ott-platforms)
 * 3. Budget Optimization in Social Media Video (slug: budget-optimisation-in-social-media-video-production)
 * 4. TV Commercial Production Process (slug: tv-commercial-production-process)
 * 5. How To Make A Documentary (slug: how-to-make-a-documentary)
 * 
 * Features:
 * - 100% Clean AR Entertainment Branding (0% Legacy Names)
 * - Author: Azizul Hoque Shiplu (Founder & Lead Director)
 * - Rich Responsive HTML Article Structure with FAQs & Actionable Guides
 * - Dynamic Category Resolution via slug
 * - Idempotent MySQL Upsert (ON DUPLICATE KEY UPDATE)
 */

declare(strict_types=1);

require_once __DIR__ . '/../../config/config.php';
require_once __DIR__ . '/../../config/db.php';
require_once __DIR__ . '/../../config/helpers.php';

echo "========================================================\n";
echo "✍️ AR ENTERTAINMENT - SEED BLOG ARTICLES BATCH 5.4.1 (1–5)\n";
echo "========================================================\n\n";

$db = db();

$articles_batch1 = [
    // 1. Mobile Video Production
    [
        'title' => 'Tips for High-Quality Mobile Video Creation in Bangladesh',
        'slug' => 'mastering-mobile-video-production',
        'category_slug' => 'ovc-digital-ads',
        'author_name' => 'Azizul Hoque Shiplu',
        'is_featured' => 1,
        'views' => 1420,
        'published_at' => '2024-06-10 10:00:00',
        'summary' => 'A complete, practical field guide on shooting cinematic smartphone videos in Bangladesh using smart framing, lighting setups, mobile gimbals, external audio, and editing apps.',
        'content' => '<div class="article-lead mb-4">
    <p class="lead" style="color: #cbd5e1; font-size: 1.15rem; line-height: 1.8;">Mobile video production has transformed from an amateur hobby into a formidable corporate and commercial content channel. Modern flagship smartphones boast high-resolution sensors, 10-bit log color profiles, and advanced optical image stabilization that enable creators, startups, and established enterprises in Bangladesh to produce broadcast-caliber video content without cumbersome multi-ton equipment trucks.</p>
</div>

<h2 class="text-warning font-weight-bold mt-4 mb-3">1. Choosing the Right Smartphone & Sensor Setup</h2>
<p>Sensor capability and lens versatility form the backbone of mobile videography. When selecting a smartphone for commercial or documentary creation:</p>
<ul style="line-height: 1.8; color: #cbd5e1;">
    <li><strong>Primary Wide Sensor:</strong> Prioritize devices featuring large 1-inch or 1/1.3-inch sensors with bright aperture (f/1.8 or wider) for authentic optical depth-of-field and low-light performance.</li>
    <li><strong>Manual Camera Controls:</strong> Use specialized camera applications such as Blackmagic Camera or FilmiC Pro to lock ISO, shutter angle (strictly 180 degrees rule: 1/50s for 25fps), white balance, and focus.</li>
    <li><strong>Stabilization Options:</strong> Combine built-in Sensor-Shift Optical Image Stabilization (OIS) with 3-axis motorized smartphone gimbals (e.g., DJI Osmo Mobile) to eliminate micro-jitter during tracking shots.</li>
</ul>

<h2 class="text-warning font-weight-bold mt-4 mb-3">2. Audio Engineering: The 50% Rule in Video Quality</h2>
<p>Audiences will forgive slight visual imperfections, but poor or distorted audio will cause viewers to bounce within 3 seconds. Built-in smartphone microphones capture excessive ambient noise and reverberation.</p>
<ul style="line-height: 1.8; color: #cbd5e1;">
    <li><strong>Wireless Lavalier Systems:</strong> Deploy dual-channel 2.4GHz wireless lavaliers (e.g., DJI Mic 2 or Rode Wireless PRO) clipped directly to the talent’s collar.</li>
    <li><strong>Compact Shotgun Microphones:</strong> Mount directional condenser mics with furry deadcats when filming run-and-gun street scenes across Dhaka or Chittagong.</li>
    <li><strong>Acoustic Space Management:</strong> Choose quiet filming environments and avoid bare-walled rooms with heavy acoustic echo.</li>
</ul>

<h2 class="text-warning font-weight-bold mt-4 mb-3">3. Lighting: Shaping Depth with Three-Point Illumination</h2>
<p>Because smartphone sensors possess smaller dynamic ranges than full-frame cinema cameras, lighting control is critical:</p>
<div class="row my-4">
    <div class="col-md-4 mb-3">
        <div class="p-3 rounded border" style="background: #1e293b; border-color: #334155 !important;">
            <h5 class="text-warning font-weight-bold">Key Light</h5>
            <p class="text-light small mb-0">Diffuse soft LED panel positioned at a 45-degree angle to provide flattering facial illumination.</p>
        </div>
    </div>
    <div class="col-md-4 mb-3">
        <div class="p-3 rounded border" style="background: #1e293b; border-color: #334155 !important;">
            <h5 class="text-warning font-weight-bold">Fill Light / Reflector</h5>
            <p class="text-light small mb-0">Soften harsh facial shadows on the opposite side using a silver or white bounce card.</p>
        </div>
    </div>
    <div class="col-md-4 mb-3">
        <div class="p-3 rounded border" style="background: #1e293b; border-color: #334155 !important;">
            <h5 class="text-warning font-weight-bold">Back / Rim Light</h5>
            <p class="text-light small mb-0">Illuminates the subject’s shoulders and hair to carve them cleanly off dark backgrounds.</p>
        </div>
    </div>
</div>

<h2 class="text-warning font-weight-bold mt-4 mb-3">4. Post-Production & Vertical Formatting</h2>
<p>Export in high-bitrate codecs (ProRes or HEVC at 50-80 Mbps) to maintain clean gradients when uploading to Facebook Reels, YouTube Shorts, and TikTok. Mobile editing suites such as CapCut Pro, DaVinci Resolve iPad, and VN Editor provide desktop-grade color grading curves and multi-track audio mastering.</p>

<div class="p-4 rounded my-4" style="background: linear-gradient(135deg, #1e293b, #0f172a); border-left: 4px solid #f59e0b;">
    <h4 class="text-white font-weight-bold mb-2">Need Professional Commercial Video Production?</h4>
    <p class="text-light mb-3">While mobile video is fantastic for agile social content, flagship TVCs, corporate documentaries, and brand films demand cinema camera systems, master directors, and broadcast colorists. Contact AR Entertainment for high-impact commercial production.</p>
    <a href="contact-us.php" class="btn btn-warning font-weight-bold px-4 py-2" style="background: #f59e0b; color: #0f172a;">Consult Our Directors</a>
</div>',
        'tags' => 'Mobile Videography, Smartphone Filmmaking, Video Marketing, Social Media Video, Dhaka Video Production',
        'meta_title' => 'Tips for High-Quality Mobile Video Creation in Bangladesh | AR Entertainment',
        'meta_description' => 'Master smartphone video production in Bangladesh. Learn professional camera settings, lighting, audio gear, and editing tips from AR Entertainment.',
        'meta_keywords' => 'mobile video production bangladesh, smartphone filmmaking dhaka, mobile video tips, video creation mobile phone'
    ],

    // 2. OTT Platforms
    [
        'title' => 'The Rising Phenomenon of OTT Platforms in Bangladesh',
        'slug' => 'phenomenon-of-ott-platforms',
        'category_slug' => 'video-marketing-seo',
        'author_name' => 'Azizul Hoque Shiplu',
        'is_featured' => 1,
        'views' => 2180,
        'published_at' => '2024-06-26 14:30:00',
        'summary' => 'An in-depth analysis of Bangladesh\'s OTT streaming revolution (Chorki, Bioscope, Bongo, Binge, Hoichoi) and how brand storytelling is evolving towards high-end narrative content.',
        'content' => '<div class="article-lead mb-4">
    <p class="lead" style="color: #cbd5e1; font-size: 1.15rem; line-height: 1.8;">Bangladesh is witnessing an unprecedented digital entertainment revolution. The traditional living room television schedule has been eclipsed by on-demand OTT streaming platforms accessible across 4G and 5G smartphone networks. For brands, filmmakers, and audiences, this transition represents a seismic shift from passive 30-second commercial interruptions to deeply engaging, long-form narrative storytelling.</p>
</div>

<h2 class="text-warning font-weight-bold mt-4 mb-3">Key Forces Driving the OTT Explosion in Bangladesh</h2>
<ul style="line-height: 1.8; color: #cbd5e1;">
    <li><strong>Widespread High-Speed Mobile Data:</strong> Affordable 4G broadband and fiber connections have brought streaming within reach of over 80 million mobile internet subscribers.</li>
    <li><strong>Appetite for Grounded Local Stories:</strong> Bangladeshi audiences have embraced complex thrillers, rural folk narratives, psychological dramas, and modern urban series that broadcast television historically overlooked.</li>
    <li><strong>Seamless Micro-Payment Gateways:</strong> Integration with bKash, Nagad, and telecom direct operator billing (DOB) allows hassle-free single-click monthly subscriptions.</li>
</ul>

<h2 class="text-warning font-weight-bold mt-4 mb-3">The Leading OTT Players in the Bangladeshi Market</h2>
<div class="row my-4">
    <div class="col-md-6 mb-3">
        <div class="p-3 rounded border h-100" style="background: #1e293b; border-color: #334155 !important;">
            <h5 class="text-warning font-weight-bold">Chorki (Film-First Narrative)</h5>
            <p class="text-light small mb-0">Pioneered original Bangladeshi cinematic thrillers, anthology films, and web series with auteur directors and cinema-grade color grading.</p>
        </div>
    </div>
    <div class="col-md-6 mb-3">
        <div class="p-3 rounded border h-100" style="background: #1e293b; border-color: #334155 !important;">
            <h5 class="text-warning font-weight-bold">Bioscope (Mass Telecom Reach)</h5>
            <p class="text-light small mb-0">Backed by Grameenphone, offering live television, blockbuster Bangla cinema, and high-engagement cricket sports streaming.</p>
        </div>
    </div>
    <div class="col-md-6 mb-3">
        <div class="p-3 rounded border h-100" style="background: #1e293b; border-color: #334155 !important;">
            <h5 class="text-warning font-weight-bold">Bongo (Digital Content Pioneer)</h5>
            <p class="text-light small mb-0">Boasts one of the largest libraries of Bengali drama, dubbed international series, comedy sketches, and original web releases.</p>
        </div>
    </div>
    <div class="col-md-6 mb-3">
        <div class="p-3 rounded border h-100" style="background: #1e293b; border-color: #334155 !important;">
            <h5 class="text-warning font-weight-bold">Binge &amp; Regional Platforms</h5>
            <p class="text-light small mb-0">Provides hybrid multi-screen entertainment connecting mobile devices with smart TV streaming dongles.</p>
        </div>
    </div>
</div>

<h2 class="text-warning font-weight-bold mt-4 mb-3">What OTT Means for Brand Advertisers &amp; Storytellers</h2>
<p>Modern consumers actively avoid banner ads and skip pre-roll videos. On OTT platforms, progressive brands are partnering with production houses to co-create <em>branded web series</em>, <em>documentary specials</em>, and <em>integrated story placements</em> that build immense brand equity.</p>

<div class="p-4 rounded my-4" style="background: linear-gradient(135deg, #1e293b, #0f172a); border-left: 4px solid #f59e0b;">
    <h4 class="text-white font-weight-bold mb-2">Develop Your OTT Original Content with AR Entertainment</h4>
    <p class="text-light mb-3">From scriptwriting and casting to 4K RED/ARRI cinematography and post-production, AR Entertainment produces high-concept web series and narrative films for local and global streaming platforms.</p>
    <a href="contact-us.php" class="btn btn-warning font-weight-bold px-4 py-2" style="background: #f59e0b; color: #0f172a;">Pitch Your OTT Project</a>
</div>',
        'tags' => 'OTT Bangladesh, Chorki, Streaming Platforms, Web Series Production, Digital Entertainment',
        'meta_title' => 'The Rising Phenomenon of OTT Platforms in Bangladesh | AR Entertainment',
        'meta_description' => 'Explore the booming OTT streaming landscape in Bangladesh (Chorki, Bioscope, Bongo). Learn what it means for filmmakers and brands by AR Entertainment.',
        'meta_keywords' => 'ott platforms bangladesh, chorki, bioscope, bongo, streaming video production, web series bangladesh'
    ],

    // 3. Budget Optimization in Social Media Video
    [
        'title' => 'Budget Optimization in Social Media Video Production in Dhaka',
        'slug' => 'budget-optimisation-in-social-media-video-production',
        'category_slug' => 'ovc-digital-ads',
        'author_name' => 'Azizul Hoque Shiplu',
        'is_featured' => 0,
        'views' => 980,
        'published_at' => '2024-07-15 11:20:00',
        'summary' => 'Proven strategies for Bangladeshi brands and agencies to optimize budgets, batch-shoot multi-platform content, and achieve maximum ROI in social video campaigns.',
        'content' => '<div class="article-lead mb-4">
    <p class="lead" style="color: #cbd5e1; font-size: 1.15rem; line-height: 1.8;">In the fast-paced digital marketplace of Dhaka, brands face constant pressure to publish high-volume video content across Facebook, YouTube, TikTok, and Instagram. However, producing dozens of standalone video ads can quickly drain marketing budgets if not managed with rigorous production planning and creative optimization.</p>
</div>

<h2 class="text-warning font-weight-bold mt-4 mb-3">1. The Power of Batch Shooting &amp; Content Multiplication</h2>
<p>The single greatest budget drain in video production is repeated setup and teardown costs (crew day rates, location rentals, lighting truck logistics, and makeup teams). By adopting a <strong>Batch Production Model</strong>, you can shoot 5 to 10 distinct video deliverables in a single 12-hour production day:</p>
<ul style="line-height: 1.8; color: #cbd5e1;">
    <li><strong>Unified Studio Environment:</strong> Utilize a single versatile studio with modular set dressings and lighting cues to transition between product demos, testimonials, and founder soundbites.</li>
    <li><strong>Multi-Format Framing:</strong> Frame principal cinematography in open-gate 4K/6K sensor modes with framing guides for 16:9 (YouTube), 1:1 (Facebook feed), and 9:16 (Reels/TikTok) simultaneously.</li>
    <li><strong>A/B Hook Variations:</strong> Film 4 to 5 alternative opening hooks (first 3 seconds) for the same core message, allowing performance marketers to split-test ad creatives cheaply.</li>
</ul>

<h2 class="text-warning font-weight-bold mt-4 mb-3">2. Pre-Production Rigor: Preventing Costly Reshoots</h2>
<p>Over 70% of budget overruns happen because creative decisions were left unresolved before cameras started rolling. Essential pre-production checkpoints include:</p>
<ul style="line-height: 1.8; color: #cbd5e1;">
    <li><strong>Locked Two-Column Script:</strong> Match every line of spoken dialogue or voiceover with exact visual actions, on-screen text graphics, and asset callouts.</li>
    <li><strong>Illustrated Storyboards:</strong> Ensure the client, agency, and director agree on camera angles and framing before lighting the set.</li>
    <li><strong>Detailed Wardrobe &amp; Prop Lists:</strong> Eliminate on-set delays by pre-approving all costumes and hero products 48 hours prior to call time.</li>
</ul>

<h2 class="text-warning font-weight-bold mt-4 mb-3">3. Strategic Gear Allocation: Match Rig to Objective</h2>
<p>Not every social media ad requires an 8K cinema camera package. Optimize your equipment budget by choosing the right tool for the job:</p>
<div class="table-responsive my-4">
    <table class="table table-dark table-bordered" style="background: #1e293b; border-color: #334155;">
        <thead>
            <tr style="background: #0f172a; color: #f59e0b;">
                <th>Production Tier</th>
                <th>Recommended Camera Rig</th>
                <th>Ideal Project Type</th>
            </tr>
        </thead>
        <tbody>
            <tr>
                <td><strong>Tier 1: Agile Social</strong></td>
                <td>Sony FX3 / A7S III + Gimbal + Wireless Mics</td>
                <td>Reels, TikToks, Behind the Scenes, Event Highlights</td>
            </tr>
            <tr>
                <td><strong>Tier 2: Premium OVC</strong></td>
                <td>RED Komodo-X / Sony FX6 + Prime Lenses + Grip</td>
                <td>YouTube Commercials, Product DVCs, Brand Stories</td>
            </tr>
            <tr>
                <td><strong>Tier 3: Flagship TVC</strong></td>
                <td>ARRI Alexa Mini LF / RED V-Raptor + Anamorphic</td>
                <td>National TV Broadcast, Cinema Theaters, Hero Campaigns</td>
            </tr>
        </tbody>
    </table>
</div>

<div class="p-4 rounded my-4" style="background: linear-gradient(135deg, #1e293b, #0f172a); border-left: 4px solid #f59e0b;">
    <h4 class="text-white font-weight-bold mb-2">Maximize Your Campaign ROI with AR Entertainment</h4>
    <p class="text-light mb-3">We engineer high-efficiency social media video production packages tailored for brands in Bangladesh. Get premium cinematic quality with predictable, optimized production budgets.</p>
    <a href="contact-us.php" class="btn btn-warning font-weight-bold px-4 py-2" style="background: #f59e0b; color: #0f172a;">Request a Custom Package</a>
</div>',
        'tags' => 'Budget Video Production, Social Media Video, Video Marketing Dhaka, OVC Production, Content Batching',
        'meta_title' => 'Budget Optimization in Social Media Video Production in Dhaka | AR Entertainment',
        'meta_description' => 'Learn how to optimize video production budgets in Dhaka. Proven strategies for batch shooting, multi-platform formatting, and high-ROI social video ads.',
        'meta_keywords' => 'video production budget dhaka, social media video optimization, ovc cost reduction bangladesh, batch shooting videos'
    ],

    // 4. TV Commercial Production Process
    [
        'title' => 'TV Commercial Production Process in Bangladesh: A Complete Step-by-Step Guide',
        'slug' => 'tv-commercial-production-process',
        'category_slug' => 'tvc-commercials',
        'author_name' => 'Azizul Hoque Shiplu',
        'is_featured' => 1,
        'views' => 3450,
        'published_at' => '2024-05-18 09:00:00',
        'summary' => 'The complete 7-stage TVC production process in Bangladesh — from creative briefing, scriptwriting, and casting to shooting, visual effects, sound design, and broadcast mastering.',
        'content' => '<div class="article-lead mb-4">
    <p class="lead" style="color: #cbd5e1; font-size: 1.15rem; line-height: 1.8;">Television commercials (TVCs) remain the gold standard for mass brand awareness, consumer trust, and emotional resonance in Bangladesh. In an intensely competitive broadcast and digital landscape, producing a memorable 30-second or 60-second commercial demands meticulous synchronization between creative directors, cinematographers, production designers, and post-production specialists.</p>
</div>

<h2 class="text-warning font-weight-bold mt-4 mb-3">The 7 Stages of TVC Production</h2>

<div class="timeline-box my-4">
    <div class="p-3 mb-3 rounded border" style="background: #1e293b; border-color: #334155 !important;">
        <h4 class="text-warning font-weight-bold mb-1">Phase 1: Creative Brief &amp; Strategic Positioning</h4>
        <p class="text-light small mb-0">Aligning with the brand’s marketing team on core consumer insights, unique selling proposition (USP), target demographics, tone of voice, and primary call-to-action.</p>
    </div>
    <div class="p-3 mb-3 rounded border" style="background: #1e293b; border-color: #334155 !important;">
        <h4 class="text-warning font-weight-bold mb-1">Phase 2: Scriptwriting &amp; Screenplay Crafting</h4>
        <p class="text-light small mb-0">Condensing the brand narrative into a razor-sharp 30-second or 45-second script. Every second must evoke emotion, build intrigue, or deliver memorable visual satisfaction.</p>
    </div>
    <div class="p-3 mb-3 rounded border" style="background: #1e293b; border-color: #334155 !important;">
        <h4 class="text-warning font-weight-bold mb-1">Phase 3: Visual Storyboarding &amp; Director’s Treatment</h4>
        <p class="text-light small mb-0">Drawing frame-by-frame visual storyboards alongside a detailed Director’s Treatment document covering color palettes, lighting schemes, lens focal lengths, and camera movement.</p>
    </div>
    <div class="p-3 mb-3 rounded border" style="background: #1e293b; border-color: #334155 !important;">
        <h4 class="text-warning font-weight-bold mb-1">Phase 4: Pre-Production (PPM Meeting, Casting &amp; Set Construction)</h4>
        <p class="text-light small mb-0">Conducting the formal Pre-Production Meeting (PPM) with brand stakeholders to lock in cast auditions, location permits across Dhaka/Bangladesh, custom set designs, and technical crew rosters.</p>
    </div>
    <div class="p-3 mb-3 rounded border" style="background: #1e293b; border-color: #334155 !important;">
        <h4 class="text-warning font-weight-bold mb-1">Phase 5: Principal Photography (The Shoot)</h4>
        <p class="text-light small mb-0">Executing the shoot with high-end cinema camera systems (ARRI/RED), professional gaffer lighting rigs, dolly tracks, camera cranes, and wireless client monitoring.</p>
    </div>
    <div class="p-3 mb-3 rounded border" style="background: #1e293b; border-color: #334155 !important;">
        <h4 class="text-warning font-weight-bold mb-1">Phase 6: Post-Production (Offline Edit, VFX, Color Grading)</h4>
        <p class="text-light small mb-0">Assembly editing, pacing refinement, 3D CGI product integration, rotoscoping, clean-ups, and cinematic color grading in DaVinci Resolve to establish a world-class visual identity.</p>
    </div>
    <div class="p-3 mb-3 rounded border" style="background: #1e293b; border-color: #334155 !important;">
        <h4 class="text-warning font-weight-bold mb-1">Phase 7: Jingle &amp; Audio Mastering, Broadcast Deliverables</h4>
        <p class="text-light small mb-0">Original musical score composition, voiceover recording in broadcast studios, sound design (Foley), final 5.1/stereo audio mixing, and broadcast-ready multi-format master exports.</p>
    </div>
</div>

<div class="p-4 rounded my-4" style="background: linear-gradient(135deg, #1e293b, #0f172a); border-left: 4px solid #f59e0b;">
    <h4 class="text-white font-weight-bold mb-2">Produce Your Next TV Commercial with AR Entertainment</h4>
    <p class="text-light mb-3">With an extensive portfolio of broadcast TVCs for national and international brands in Bangladesh, AR Entertainment delivers end-to-end commercial filmmaking from initial concept to master delivery.</p>
    <a href="contact-us.php" class="btn btn-warning font-weight-bold px-4 py-2" style="background: #f59e0b; color: #0f172a;">Schedule a TVC Briefing</a>
</div>',
        'tags' => 'TV Commercial, TVC Production, Film Production House, Advertising Bangladesh, Commercial Filmmaking',
        'meta_title' => 'TV Commercial Production Process in Bangladesh: A Complete Guide | AR Entertainment',
        'meta_description' => 'Learn the complete step-by-step TV commercial production process in Bangladesh. From script and PPM to shooting and color grading by AR Entertainment.',
        'meta_keywords' => 'tvc production process bangladesh, tv commercial making, film production house dhaka, advertising film guide'
    ],

    // 5. How To Make A Documentary
    [
        'title' => 'How To Make A Documentary in Bangladesh: The Ultimate Filmmaker\'s Guide',
        'slug' => 'how-to-make-a-documentary',
        'category_slug' => 'film-fixer-filming-in-bd',
        'author_name' => 'Azizul Hoque Shiplu',
        'is_featured' => 1,
        'views' => 2890,
        'published_at' => '2024-04-12 16:00:00',
        'summary' => 'A master guide to documentary filmmaking in Bangladesh covering story concept development, field research, NGO/international fixer logistics, ethical interviews, and cinematic editing.',
        'content' => '<div class="article-lead mb-4">
    <p class="lead" style="color: #cbd5e1; font-size: 1.15rem; line-height: 1.8;">Documentary filmmaking possesses the extraordinary power to humanize complex global realities, celebrate cultural heritage, and catalyze social transformation. In a country as visually rich and ecologically dynamic as Bangladesh, documentary storytellers have the privilege and responsibility to capture authentic human experiences across rivers, tea plantations, coastal estuaries, and bustling urban sprawls.</p>
</div>

<h2 class="text-warning font-weight-bold mt-4 mb-3">Step 1: Concept Development — Moving from Topic to Tension</h2>
<p>Every compelling documentary begins not with a dry generic subject, but with a specific human story driven by tension, change, or inquiry. <em>"Climate change in Bangladesh"</em> is a broad academic topic. <em>"A multigenerational family of fisherman adapting to shifting tidal river channels in Bhola"</em> is an unforgettable documentary concept.</p>
<ul style="line-height: 1.8; color: #cbd5e1;">
    <li><strong>The Central Dramatic Question:</strong> What core truth or journey is the audience following?</li>
    <li><strong>Authentic Character Focus:</strong> Identify charismatic, genuine subjects whose lived reality embodies the broader story.</li>
    <li><strong>Unique Access:</strong> What perspective or intimacy can your production team provide that has never been documented before?</li>
</ul>

<h2 class="text-warning font-weight-bold mt-4 mb-3">Step 2: Field Research &amp; Fixer Logistics Across Bangladesh</h2>
<p>In-depth pre-production research is the foundation of credible non-fiction storytelling. In Bangladesh, on-the-ground line production and fixer support are crucial for navigating regional nuances:</p>
<ul style="line-height: 1.8; color: #cbd5e1;">
    <li><strong>Government &amp; Ministry Clearances:</strong> Securing filming permissions from the Ministry of Information, Forest Department (Sundarbans), and local district administrations.</li>
    <li><strong>Remote Travel Logistics:</strong> Managing four-wheel-drive field transport, shallow-draft riverboats, and portable power generator stations in off-grid char areas.</li>
    <li><strong>Community Trust &amp; Cultural Protocols:</strong> Engaging with local elders, indigenous communities, and grassroots organizations with empathy and ethical integrity.</li>
</ul>

<h2 class="text-warning font-weight-bold mt-4 mb-3">Step 3: Cinematography &amp; Interview Mastery</h2>
<p>Documentary visual language demands adaptability, patience, and technical excellence:</p>
<div class="row my-4">
    <div class="col-md-6 mb-3">
        <div class="p-3 rounded border h-100" style="background: #1e293b; border-color: #334155 !important;">
            <h5 class="text-warning font-weight-bold">Observational / Cinéma Vérité</h5>
            <p class="text-light small mb-0">Handheld or shoulder-mounted cinema rigs (using natural light and wide lenses) following spontaneous, unscripted moments without disrupting reality.</p>
        </div>
    </div>
    <div class="col-md-6 mb-3">
        <div class="p-3 rounded border h-100" style="background: #1e293b; border-color: #334155 !important;">
            <h5 class="text-warning font-weight-bold">In-Depth Subject Interviews</h5>
            <p class="text-light small mb-0">Two-camera interview setups with shallow depth-of-field, continuous eye-contact, and soft key lighting to create intimate, trustworthy conversations.</p>
        </div>
    </div>
</div>

<h2 class="text-warning font-weight-bold mt-4 mb-3">Step 4: Post-Production &amp; Narrative Sculpting</h2>
<p>In documentary film, the true script is written in the editing room. Transcribe all audio interviews, build a comprehensive paper edit, assemble the thematic storyline, and weave atmospheric sound design (local birdcalls, river currents, urban ambiance) and original musical score to guide the emotional journey of the audience.</p>

<div class="p-4 rounded my-4" style="background: linear-gradient(135deg, #1e293b, #0f172a); border-left: 4px solid #f59e0b;">
    <h4 class="text-white font-weight-bold mb-2">Partner with Bangladesh\'s Premier Documentary Production Team</h4>
    <p class="text-light mb-3">AR Entertainment has produced award-winning documentaries for international broadcast networks, NGOs, development partners, and global corporate foundations across all 64 districts of Bangladesh.</p>
    <a href="contact-us.php" class="btn btn-warning font-weight-bold px-4 py-2" style="background: #f59e0b; color: #0f172a;">Discuss Your Documentary</a>
</div>',
        'tags' => 'Documentary Filmmaking, Film Fixer Bangladesh, NGO Video Production, Cinematography, Storytelling',
        'meta_title' => 'How To Make A Documentary in Bangladesh: The Ultimate Guide | AR Entertainment',
        'meta_description' => 'Comprehensive guide to documentary filmmaking in Bangladesh. Research, field line production, fixer logistics, and cinematic editing by AR Entertainment.',
        'meta_keywords' => 'how to make a documentary bangladesh, documentary filmmaking guide, film fixer bangladesh, ngo video production dhaka'
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
foreach ($articles_batch1 as $idx => $art) {
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
echo "🏆 BATCH 5.4.1 COMPLETED SUCCESSFULLY!\n";
echo "   - Total Articles Seeded in Batch: {$seeded}\n";
echo "   - Current Total Published Blogs in Database: {$total_blogs}\n";
echo "========================================================\n\n";
