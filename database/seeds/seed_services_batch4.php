<?php
/**
 * AR Entertainment - Phase 5.2 Services Seeder (Batch 5.2.4: Services 25–32)
 * 
 * Ingests 8 Creative Development, Scripting, Strategy & Corporate Media Services:
 * 25. Promo Video & Product DVC Production (promo-video)
 * 26. Corporate Training & Instructional Video Production (training-video)
 * 27. Milestone Celebration & Jubilee Video Production (milestone-celebration-video)
 * 28. Social Media Video & Viral Content Production (social-media-video)
 * 29. Creative Concept Development & Campaign Ideation (concept-development)
 * 30. Screenplay & Commercial Scriptwriting Services (script-development)
 * 31. Cinematic Storyboard & Visual Animatics Development (storyboard-development)
 * 32. Video Marketing Strategy & Media Planning (strategic-planning)
 * 
 * Features:
 * - 100% Clean AR Entertainment Branding (0% Legacy Names)
 * - Structured JSON FAQs (6 per service)
 * - High-Impact Rich Body HTML
 * - Idempotent MySQL Upsert (ON DUPLICATE KEY UPDATE)
 */

declare(strict_types=1);

require_once __DIR__ . '/../../config/config.php';
require_once __DIR__ . '/../../config/db.php';
require_once __DIR__ . '/../../config/helpers.php';

echo "========================================================\n";
echo "🎬 AR ENTERTAINMENT - SEED SERVICES BATCH 5.2.4 (25–32)\n";
echo "========================================================\n\n";

$db = db();

$services_batch4 = [
    // 25. Promo Video & Product DVC Production
    [
        'title' => 'Promo Video & Product DVC Production',
        'slug' => 'promo-video',
        'icon' => 'fa-solid fa-bullhorn',
        'short_summary' => 'High-conversion digital video commercials, product launch promos, and promotional teasers engineered for eCommerce, retail promotions, and app user acquisition.',
        'content' => '<h3>High-Impact Promotional Videos That Drive Rapid Conversions</h3>
<p>In retail, eCommerce, and tech startups, launching a new product requires immediate consumer desire and clear benefit demonstration. <strong>AR Entertainment</strong> produces high-energy promotional videos and Digital Video Commercials (DVCs) that showcase product ergonomics, build unboxing excitement, and drive measurable sales action.</p>
<h4>Engineered for High Consumer Excitement &amp; Sales</h4>
<ul>
    <li><strong>Macro Optics &amp; Slow-Motion Tabletop Filming:</strong> High-frame-rate 120fps/240fps macro shots capturing liquid splashes, tactile textures, metallic finishes, and premium packaging details.</li>
    <li><strong>Benefit-Driven Narrative Hooks:</strong> Clear visual demonstration of product problem-solving capabilities within the first 5 seconds to minimize bounce rates.</li>
    <li><strong>Motion Graphic Callouts &amp; Offers:</strong> Dynamic on-screen pricing badges, feature highlights, and animated call-to-action buttons optimized for mobile viewing.</li>
    <li><strong>Multi-Platform Ad Suites:</strong> Full 30s hero promos delivered alongside 15s Instagram Story teasers, 6s YouTube bumper ads, and 1:1 square marketplace listing videos.</li>
</ul>
<p>From consumer electronics and beauty cosmetics to fintech mobile applications, our promo videos turn casual viewers into enthusiastic brand buyers.</p>',
        'pricing_note' => 'Promo video packages range from ৳75,000 to ৳450,000 BDT based on duration, motion graphics complexity, tabletop macro filming, and social cutdowns.',
        'faqs' => [
            [
                'q' => 'What is the primary difference between a promo video and a general corporate film?',
                'a' => 'A corporate film focuses on long-term institutional prestige and company history. A promo video is an energetic, direct-response sales asset engineered around a specific product launch, limited-time discount, or feature benefit.'
            ],
            [
                'q' => 'How long should a product promotional video be?',
                'a' => 'For digital ad campaigns and social media, ideal durations range between 15 and 45 seconds, while comprehensive eCommerce product page explainers can run 60 to 90 seconds.'
            ],
            [
                'q' => 'Can you film physical products in studio tabletop environments?',
                'a' => 'Yes. We operate specialized studio tabletop setups equipped with motorized turntables, robotic sliders, high-speed cinema cameras, and color-calibrated spotlighting.'
            ],
            [
                'q' => 'Do you cast lifestyle models or actors demonstrating the product?',
                'a' => 'Yes. We provide complete casting services, pairing your product with relatable actors and lifestyle influencers to demonstrate authentic real-world usage.'
            ],
            [
                'q' => 'What is the turnaround time for a product promo video?',
                'a' => 'Standard promo video projects take 7 to 14 business days from concept approval to final multi-ratio export.'
            ],
            [
                'q' => 'Can AR Entertainment optimize promo videos for Facebook and TikTok ad testing?',
                'a' => 'Yes. We deliver A/B test variations with multiple opening hook scenes (first 3 seconds) so your digital media team can maximize return on ad spend (ROAS).'
            ]
        ],
        'sort_order' => 25,
        'meta_title' => 'Promo Video & Product DVC Production in Bangladesh | AR Entertainment',
        'meta_description' => 'High-converting promo videos and product DVC production in Dhaka, Bangladesh by AR Entertainment. Boost sales, app downloads, and product launches.'
    ],

    // 26. Corporate Training & Instructional Video Production
    [
        'title' => 'Corporate Training & Instructional Video Production',
        'slug' => 'training-video',
        'icon' => 'fa-solid fa-graduation-cap',
        'short_summary' => 'Comprehensive employee onboarding videos, standard operating procedure (SOP) demonstrations, workplace safety compliance, and software instructional courses.',
        'content' => '<h3>Standardize Workplace Excellence Through Engaging Training Videos</h3>
<p>Inconsistent employee training leads to compliance risks, operational inefficiencies, and wasted executive time. <strong>AR Entertainment</strong> produces clear, highly engaging corporate training and instructional video suites that ensure every team member across your organization masters essential operating standards.</p>
<h4>Enterprise Training &amp; Instructional Modules</h4>
<ul>
    <li><strong>Standard Operating Procedure (SOP) Step-by-Steps:</strong> Filming high-clarity demonstrations of complex manufacturing protocols, laboratory workflows, and service guidelines.</li>
    <li><strong>Workplace Health &amp; Safety (EHS) Compliance:</strong> Interactive dramatizations of fire safety, hazard prevention, emergency evacuation, and hygiene compliance for factory and office staff.</li>
    <li><strong>Software &amp; Digital Tool Tutorials:</strong> High-definition screencasting integrated with animated mouse callouts, zoom highlights, and professional voiceover instructions.</li>
    <li><strong>LMS &amp; SCORM Video Architecture:</strong> Modular chaptered video packages with knowledge-check graphic overlays ready for seamless LMS and HR portal integration.</li>
</ul>
<p>Empower your workforce with consistent, scalable, and on-demand video learning that reduces training overhead by up to 70%.</p>',
        'pricing_note' => 'Training video packages range from ৳60,000 to ৳350,000 BDT per module, including chapter markers, interactive LMS quizzing screens, and multi-language voiceovers.',
        'faqs' => [
            [
                'q' => 'Why should our company invest in video training over written employee manuals?',
                'a' => 'Studies prove employees retain 95% of a message when watched in video compared to only 10% when reading text. Video ensures standardized training across distributed branch networks.'
            ],
            [
                'q' => 'Can training videos be localized in both Bangla and English?',
                'a' => 'Yes. We provide dual-language master files with synchronized voiceovers and on-screen graphic subtitles tailored for management and factory-floor personnel.'
            ],
            [
                'q' => 'How do you keep instructional videos engaging and prevent viewer fatigue?',
                'a' => 'We structure content into bite-sized 3-to-5 minute micro-learning chapters using dynamic motion graphics, real-world case scenarios, and lively presenter delivery.'
            ],
            [
                'q' => 'Can you film on-site at our corporate branches, warehouses, or hospitals?',
                'a' => 'Yes. Our production teams travel nationwide with compact cinema lighting and sound kits to film authentic procedures directly on your premises.'
            ],
            [
                'q' => 'Are deliverables compatible with corporate Learning Management Systems (LMS)?',
                'a' => 'Yes. We export SCORM-compatible video packages, MP4 (H.264), and interactive web modules formatted for systems like Moodle, SAP SuccessFactors, and custom portals.'
            ],
            [
                'q' => 'How do we update training videos when company policies change?',
                'a' => 'Our modular video architecture allows specific chapter segments or voiceover lines to be edited and re-rendered rapidly without remaking the entire video.'
            ]
        ],
        'sort_order' => 26,
        'meta_title' => 'Corporate Training & Instructional Video Production | AR Entertainment',
        'meta_description' => 'Scalable employee training, compliance, and SOP video production in Dhaka, Bangladesh by AR Entertainment. Standardize corporate knowledge across your workforce.'
    ],

    // 27. Milestone Celebration & Jubilee Video Production
    [
        'title' => 'Milestone Celebration & Jubilee Video Production',
        'slug' => 'milestone-celebration-video',
        'icon' => 'fa-solid fa-trophy',
        'short_summary' => 'Commemorative anniversary films, silver/golden jubilee retrospectives, and corporate milestone documentaries celebrating institutional endurance, leadership vision, and heritage.',
        'content' => '<h3>Immortalize Corporate Legacy with Prestige Milestone Films</h3>
<p>Reaching a major corporate milestone—a 10th anniversary, silver jubilee, golden jubilee, or historic company expansion—is a testament to endurance, leadership wisdom, and stakeholder trust. <strong>AR Entertainment</strong> crafts cinematic milestone documentary films that celebrate your founding struggles, transformative achievements, and bold future horizons.</p>
<h4>Commemorative Milestone Storytelling</h4>
<ul>
    <li><strong>Archival Restoration &amp; 3D Photo Animation:</strong> Digitizing, restoring, and animating vintage founding photographs, blueprints, and historic press clippings with 2.5D parallax depth.</li>
    <li><strong>Founder &amp; Veteran Leadership Testimonials:</strong> Capturing intimate, heartfelt reflections from founders, early employees, and long-standing clients with flattering cinematic lighting.</li>
    <li><strong>3D Timeline Graphics &amp; Growth Milestones:</strong> Elegantly visualizing key corporate turning points, revenue milestones, factory expansions, and community impacts.</li>
    <li><strong>Gala Premiere &amp; Commemorative Video Keepsakes:</strong> Master 10-to-15 minute retrospective films tailored for anniversary banquet screens, accompanied by commemorative executive boxed USB editions.</li>
</ul>
<p>Honour your founding pioneers, inspire current employees, and solidify your legacy as an enduring national institution.</p>',
        'pricing_note' => 'Milestone jubilee films range from ৳150,000 to ৳900,000 BDT encompassing historical photo restoration, founder interviews, 3D timeline graphics, and gala premiere cuts.',
        'faqs' => [
            [
                'q' => 'When should an organization begin producing an anniversary milestone video?',
                'a' => 'We recommend starting 2 to 3 months prior to your celebration gala to allow ample time for archival photo collection, multi-executive interviews, and 3D visual effects development.'
            ],
            [
                'q' => 'Can you work with low-resolution historic photos from our founding years?',
                'a' => 'Yes. Our digital artists use AI neural enhancement and digital painting to restore damaged vintage photos and animate them with 3D camera depth.'
            ],
            [
                'q' => 'How do you structure the storytelling of a milestone documentary?',
                'a' => 'We divide the film into three emotional acts: The Founding Spark (vision and early struggles), The Growth Engine (major breakthroughs and national impact), and The Future Horizon (technology and next-generation leadership).'
            ],
            [
                'q' => 'Can you film retired founders or international board members in other locations?',
                'a' => 'Yes. We conduct filming across all 64 districts of Bangladesh and can arrange remote high-definition satellite/studio interviews for overseas directors.'
            ],
            [
                'q' => 'Do you provide short cutdowns for social media and television broadcast?',
                'a' => 'Yes. Alongside the main gala retrospective, we produce 60-second and 30-second celebratory television commercials and social media highlight reels.'
            ],
            [
                'q' => 'What audio scoring is used for milestone celebration films?',
                'a' => 'We compose bespoke orchestral scores with emotional string sections and traditional Bangladeshi instruments that build to an inspiring, celebratory climax.'
            ]
        ],
        'sort_order' => 27,
        'meta_title' => 'Milestone Celebration & Corporate Jubilee Videos | AR Entertainment',
        'meta_description' => 'Commemorate corporate anniversaries, founding history, and institutional milestones with prestigious celebration films in Bangladesh by AR Entertainment.'
    ],

    // 28. Social Media Video & Viral Content Production
    [
        'title' => 'Social Media Video & Viral Content Production',
        'slug' => 'social-media-video',
        'icon' => 'fa-solid fa-hashtag',
        'short_summary' => 'Agile, trend-aligned short-form vertical video packages for TikTok, Instagram Reels, and Facebook Shorts designed to capture Gen-Z attention and drive viral engagement.',
        'content' => '<h3>High-Retention Vertical Content Engineered for Social Algorithms</h3>
<p>Modern social media algorithms reward high-frequency, authentic, and fast-paced vertical video content. <strong>AR Entertainment</strong> operates dedicated agile social content units that produce batch vertical videos (9:16) for TikTok, Instagram Reels, YouTube Shorts, and Facebook, keeping your brand perpetually relevant and engaging.</p>
<h4>Social-First Video Production Architecture</h4>
<ul>
    <li><strong>Viral Trend &amp; Audio Hijacking:</strong> Monitoring trending sounds, cultural memes, and viral challenges to produce brand-safe, culturally witty social content within 48 hours.</li>
    <li><strong>Dynamic Kinetic Captions:</strong> High-impact animated subtitles, highlighted keywords, and sound-effect pops optimized for the 85% of mobile users who watch on mute.</li>
    <li><strong>High-Volume Batch Shoot Days:</strong> Efficient single-day studio shoots producing 10 to 20 distinct vertical video assets, providing a full month of daily content calendar deliverables.</li>
    <li><strong>Creator &amp; Influencer Collaborations:</strong> Seamless integration of micro-influencers and relatable digital creators matching your brand demographic.</li>
</ul>
<p>Dominate the mobile feed, boost organic follower growth, and generate genuine consumer buzz with AR Entertainment’s social video engine.</p>',
        'pricing_note' => 'Monthly social video retainer packages range from ৳80,000 to ৳400,000 BDT (delivering 8 to 24 edited vertical reels, animated captions, and trend hooks per month).',
        'faqs' => [
            [
                'q' => 'What makes social media video production different from traditional video production?',
                'a' => 'Social videos require a vertical 9:16 framing, immediate visual hooks within 2 seconds, punchy fast-cut pacing, kinetic subtitles, and native understanding of platform trends and algorithms.'
            ],
            [
                'q' => 'How many social media videos can you produce per month?',
                'a' => 'Our monthly retainer packages typically deliver 8, 16, or 24 edited vertical reels per month based on 1 to 2 scheduled batch production shoot days.'
            ],
            [
                'q' => 'Do you provide creative concepts and scripts for social reels?',
                'a' => 'Yes. Our digital content strategists brainstorm monthly content calendars featuring comedy sketches, product hacks, customer FAQs, employee behind-the-scenes, and trend challenges.'
            ],
            [
                'q' => 'Can we include our own staff or brand ambassadors in the videos?',
                'a' => 'Absolutely. We coach your team on set to appear confident and natural, or we can provide charismatic digital creators to host the videos.'
            ],
            [
                'q' => 'Are all videos delivered with licensed commercial background music?',
                'a' => 'Yes. We provide 100% commercially licensed audio tracks or match trending platform-native sounds that are safe from copyright muting.'
            ],
            [
                'q' => 'How quickly can you turn around trending or breaking news video topics?',
                'a' => 'For fast-breaking cultural moments, our rapid-response team can script, shoot, edit, and deliver ready-to-post vertical videos within 24 to 48 hours.'
            ]
        ],
        'sort_order' => 28,
        'meta_title' => 'Social Media Video & Viral Content Production | AR Entertainment',
        'meta_description' => 'High-energy vertical video production for TikTok, Instagram Reels, and Facebook in Dhaka, Bangladesh by AR Entertainment. Drive organic engagement and brand reach.'
    ],

    // 29. Creative Concept Development & Campaign Ideation
    [
        'title' => 'Creative Concept Development & Campaign Ideation',
        'slug' => 'concept-development',
        'icon' => 'fa-solid fa-lightbulb',
        'short_summary' => 'Ogilvy-grade strategic campaign ideation, narrative hooks, big-idea brainstorming, and cultural insight discovery that turn brand briefs into award-winning audio-visual campaigns.',
        'content' => '<h3>Transforming Brand Objectives into Unforgettable Creative Big Ideas</h3>
<p>Before a camera rolls or a storyboard is drawn, the success of any commercial campaign hinges on the strength of its core creative idea. At <strong>AR Entertainment</strong>, our creative directors draw upon 20+ years of top-tier agency heritage (including Ogilvy &amp; Mather alumni leadership) to discover deep consumer insights and engineer breakthrough creative concepts.</p>
<h4>Strategic Creative Ideation Framework</h4>
<ul>
    <li><strong>Consumer Insight Mining:</strong> Analyzing target audience demographics, cultural habits, unspoken desires, and competitive whitespace across the Bangladeshi market.</li>
    <li><strong>The "Big Idea" Architecture:</strong> Formulating multi-platform creative umbrellas that can seamlessly scale across TVCs, digital OVCs, print, outdoor billboards, and experiential activations.</li>
    <li><strong>Multi-Route Creative Decks:</strong> Presenting 3 distinct creative routes per brief—from emotionally profound human stories to witty comedic satire and visually surreal spectacle.</li>
    <li><strong>Visual Mood Boards &amp; Tone Styling:</strong> Curating cinematic references, color palettes, lighting mood guides, and music pacing to establish a unified creative vision early.</li>
</ul>
<p>Stop producing forgettable ads. Partner with AR Entertainment to craft concepts that capture national imagination and inspire cultural conversations.</p>',
        'pricing_note' => 'Creative ideation packages range from ৳50,000 to ৳250,000 BDT including 3 distinct campaign thematic routes, narrative treatments, mood boards, and presentation decks.',
        'faqs' => [
            [
                'q' => 'What is creative concept development in advertising?',
                'a' => 'It is the strategic and artistic process of translating a marketing objective into an overarching creative "Big Idea" and narrative hook that connects emotionally with consumers and drives brand action.'
            ],
            [
                'q' => 'Why choose AR Entertainment for creative concepting rather than a generic ad agency?',
                'a' => 'Unlike traditional agencies whose concepts often prove impractical or over-budget on set, our directors understand both agency-grade brand strategy and real-world physical film production, ensuring feasible yet breathtaking ideas.'
            ],
            [
                'q' => 'What deliverables are included in a creative concept package?',
                'a' => 'You receive a comprehensive Campaign Concept Deck featuring 3 creative routes, narrative synopses, character profiles, visual mood boards, sample dialogue hooks, and platform rollout plans.'
            ],
            [
                'q' => 'How long does the concept development process take?',
                'a' => 'Initial concept presentation decks are delivered within 5 to 7 business days following the formal client creative brief.'
            ],
            [
                'q' => 'Can we hire AR Entertainment solely for concept development without production?',
                'a' => 'Yes. We offer standalone creative consultancy and ideation services for brands and international agencies seeking local cultural strategy in Bangladesh.'
            ],
            [
                'q' => 'How many revision rounds are included in concept development?',
                'a' => 'We provide up to 3 structured feedback and refinement rounds on the selected concept route to ensure total stakeholder alignment.'
            ]
        ],
        'sort_order' => 29,
        'meta_title' => 'Creative Concept Development & Ideation Bangladesh | AR Entertainment',
        'meta_description' => 'Strategic advertising concepts, big ideas, and campaign ideation in Dhaka by AR Entertainment. Ogilvy-trained creative direction that drives consumer action.'
    ],

    // 30. Screenplay & Commercial Scriptwriting Services
    [
        'title' => 'Screenplay & Commercial Scriptwriting Services',
        'slug' => 'script-development',
        'icon' => 'fa-solid fa-scroll',
        'short_summary' => 'Masterful dialogue writing, narrative pacing, timed commercial audio-video split scripts, and documentary treatments in standard Bangla and international English.',
        'content' => '<h3>Words That Evoke Emotion, Spark Action &amp; Command Attention</h3>
<p>A mediocre script results in a forgettable film, no matter how expensive the camera rig. <strong>AR Entertainment</strong> houses seasoned screenwriters, advertising copywriters, and playwrights who craft compelling scripts with airtight narrative structure, authentic human dialogue, and precise visual pacing.</p>
<h4>Masterful Audio-Visual Scriptwriting Disciplines</h4>
<ul>
    <li><strong>Two-Column Broadcast AV Scripts:</strong> Professional industry-standard formatting aligning exact visual actions with timed voiceovers, character lines, music cues, and sound effects.</li>
    <li><strong>Authentic Dialogue &amp; Cultural Nuance:</strong> Writing natural conversational dialogue in Standard Bengali, urban youth slang, regional dialects, and polished corporate English.</li>
    <li><strong>Documentary Treatments &amp; Narrative Arcs:</strong> Structuring non-fiction documentary outlines, interview question guides, and voice of god (VOG) narrations.</li>
    <li><strong>Seconds-Accurate Pacing:</strong> Precision timed scripts calibrated strictly for 15-second TVC pods, 30-second commercial spots, or 90-second digital stories.</li>
</ul>
<p>Transform raw marketing points into captivating audio-visual prose that engages audiences from the very first spoken word.</p>',
        'pricing_note' => 'Professional scriptwriting packages range from ৳30,000 to ৳180,000 BDT per script with 2-column AV formatting, character dialogue bible, and voiceover pacing timing.',
        'faqs' => [
            [
                'q' => 'What is a two-column AV script?',
                'a' => 'It is the standard industry format for commercial video where the left column details visual scenes and camera actions, and the right column details synchronized dialogue, voiceovers, and sound effects.'
            ],
            [
                'q' => 'Can you write scripts in both Bengali and English?',
                'a' => 'Yes. We provide native bilingual writing services—crafting culturally vibrant Bengali scripts as well as sophisticated English scripts for multinational audiences.'
            ],
            [
                'q' => 'How do you ensure a 30-second commercial script fits the exact broadcast time limit?',
                'a' => 'Our writers conduct timed read-throughs with professional pacing cadences (approx. 60-70 words per 30 seconds), accounting for visual breathing room and dramatic pauses.'
            ],
            [
                'q' => 'Do you provide character backstories and director notes with the script?',
                'a' => 'Yes. Every finalized script includes character bios, emotional subtext notes, and visual tone cues to guide actors and cinematographers.'
            ],
            [
                'q' => 'What is the turnaround time for a commercial script?',
                'a' => 'First drafts are typically delivered within 3 to 5 business days, with rapid 48-hour turnarounds available for urgent campaigns.'
            ],
            [
                'q' => 'Who owns the copyright to the script once completed?',
                'a' => 'Full exclusive copyright and worldwide publishing/broadcast rights are transferred to the client upon final payment.'
            ]
        ],
        'sort_order' => 30,
        'meta_title' => 'Commercial Scriptwriting & Screenplay Development | AR Entertainment',
        'meta_description' => 'Professional scriptwriting for TV commercials, OVCs, corporate AVs, and documentaries in Dhaka, Bangladesh by AR Entertainment. Engaging dialogue and storytelling.'
    ],

    // 31. Cinematic Storyboard & Visual Animatics Development
    [
        'title' => 'Cinematic Storyboard & Visual Animatics Development',
        'slug' => 'storyboard-development',
        'icon' => 'fa-solid fa-palette',
        'short_summary' => 'Frame-by-frame hand-drawn storyboards, camera angle framing, 3D visual previz, and timed 2D animatics with temp voiceover and sound effects before shoot day.',
        'content' => '<h3>Visualizing Every Frame with Surgical Precision Before Shoot Day</h3>
<p>Filming without a storyboard is like building a skyscraper without a blueprint. <strong>AR Entertainment</strong> creates comprehensive, frame-by-frame illustrated storyboards and dynamic video animatics that eliminate on-set guesswork, align client expectations, and streamline production efficiency.</p>
<h4>Pre-Visualization &amp; Storyboarding Solutions</h4>
<ul>
    <li><strong>Hand-Drawn &amp; Digital Illustrated Frames:</strong> Clear sketches showing camera focal lengths (wide, medium, close-up), subject positioning, lighting direction, and shot transitions.</li>
    <li><strong>Camera Movement &amp; Arrow Choreography:</strong> Visual indicators detailing pans, tilts, crane sweeps, tracking dollies, and zoom speeds for the cinematography crew.</li>
    <li><strong>Timed 2D Video Animatics:</strong> Sequencing storyboard panels into a timeline synchronized with scratch voiceovers, background music, and foley to test pacing before filming.</li>
    <li><strong>3D Previz &amp; Virtual Set Planning:</strong> Digital 3D spatial simulations for complex VFX sequences, car stunts, and drone flight paths inside Unreal Engine.</li>
</ul>
<p>Save substantial production budget, secure executive sign-off with ease, and guarantee a flawless shoot day with AR Entertainment’s visual pre-production pipeline.</p>',
        'pricing_note' => 'Storyboarding packages range from ৳25,000 to ৳120,000 BDT based on frame count (12 to 48 frames), color shading, and dynamic video animatic editing.',
        'faqs' => [
            [
                'q' => 'Why is a storyboard essential before starting film production?',
                'a' => 'A storyboard allows clients and directors to visualize camera angles, pacing, and visual transitions before spending money on cameras, actors, and location rentals, preventing costly re-shoots.'
            ],
            [
                'q' => 'What is the difference between a static storyboard and an animatic?',
                'a' => 'A static storyboard is a printed or PDF series of illustration panels. An animatic is a video mockup that places those illustrations in sequence with timed voiceover and music to experience real-time pacing.'
            ],
            [
                'q' => 'How many frames are typically needed for a 30-second TVC?',
                'a' => 'A standard 30-second TV commercial typically requires between 12 and 24 illustrated storyboard frames depending on visual cut speed and scene complexity.'
            ],
            [
                'q' => 'Do you offer colored storyboards or black-and-white line art?',
                'a' => 'We offer both: rapid black-and-white concept thumbnails for structural layout, and full-color shaded storyboards for executive board and client presentations.'
            ],
            [
                'q' => 'How long does it take to illustrate a complete storyboard?',
                'a' => 'A full 18-to-24 frame commercial storyboard is typically illustrated and delivered within 3 to 4 business days.'
            ],
            [
                'q' => 'Can we make changes to shot compositions on the storyboard before filming?',
                'a' => 'Yes. The primary purpose of storyboarding is to test and refine ideas. We provide revision rounds to adjust camera angles, actor blocking, and scene transitions until everyone is 100% satisfied.'
            ]
        ],
        'sort_order' => 31,
        'meta_title' => 'Cinematic Storyboarding & Visual Animatics | AR Entertainment',
        'meta_description' => 'Frame-by-frame storyboarding, shot framing, and 2D/3D video animatics in Dhaka, Bangladesh by AR Entertainment. Eliminate guesswork before production.'
    ],

    // 32. Video Marketing Strategy & Media Planning
    [
        'title' => 'Video Marketing Strategy & Media Planning',
        'slug' => 'strategic-planning',
        'icon' => 'fa-solid fa-chart-line',
        'short_summary' => 'End-to-end audio-visual distribution blueprints, audience persona mapping, multi-platform media budgeting, YouTube SEO optimization, and conversion funnel architecture.',
        'content' => '<h3>Maximizing Audio-Visual ROI with Data-Driven Distribution Strategy</h3>
<p>Producing a stunning video is only half the battle; ensuring it reaches the right target audience at the optimal time is what generates real business revenue. <strong>AR Entertainment</strong> designs data-backed video marketing strategies and media deployment plans that maximize reach, view-through rates, and customer conversions.</p>
<h4>Full-Funnel Video Marketing Architecture</h4>
<ul>
    <li><strong>Audience Persona &amp; Funnel Mapping:</strong> Aligning specific video assets with Top-of-Funnel (brand awareness), Middle-of-Funnel (consideration &amp; demo), and Bottom-of-Funnel (conversion) customer journeys.</li>
    <li><strong>Cross-Platform Distribution Planning:</strong> Tailoring video bitrates, aspect ratios, and thumbnail hooks for YouTube, Facebook, LinkedIn, TikTok, OTT platforms, and national television.</li>
    <li><strong>YouTube &amp; Video SEO Optimization:</strong> Keyword research, chaptering, rich metadata tagging, and schema markup ensuring long-term organic search discoverability.</li>
    <li><strong>Performance Analytics &amp; A/B Optimization:</strong> Tracking completion rates, engagement drop-offs, click-through rates (CTR), and optimizing ad spend allocation across target demographics.</li>
</ul>
<p>Turn video from an artistic cost center into a predictable, revenue-generating growth engine for your enterprise.</p>',
        'pricing_note' => 'Strategic media planning packages range from ৳45,000 to ৳220,000 BDT including channel distribution schedules, digital ad spend optimization, and KPI analytics dashboards.',
        'faqs' => [
            [
                'q' => 'What is a video marketing strategy and why does our brand need one?',
                'a' => 'A video marketing strategy is a comprehensive roadmap that defines target audiences, distribution channels, messaging goals, and budget allocation to ensure your video achieves measurable business results.'
            ],
            [
                'q' => 'How do you measure the success of a video marketing campaign?',
                'a' => 'We measure key performance indicators (KPIs) including 3-second hook retention, average percentage viewed (APV), click-through rate (CTR), cost-per-view (CPV), and direct lead conversions.'
            ],
            [
                'q' => 'Can AR Entertainment help manage digital ad spend on Facebook and YouTube?',
                'a' => 'Yes. We provide end-to-end media planning, audience demographic targeting, pixel conversion tracking, and campaign optimization across Google/YouTube and Meta ad networks.'
            ],
            [
                'q' => 'What is Video SEO and how does it help organic discoverability?',
                'a' => 'Video SEO involves optimizing titles, descriptions, custom thumbnails, video transcripts, and schema tags so your videos rank at the top of Google and YouTube search results organically.'
            ],
            [
                'q' => 'Do you provide full-funnel video marketing plans for product launches?',
                'a' => 'Yes. We architect 3-phase launch roadmaps: Phase 1 Teaser (hype building), Phase 2 Hero Launch (commercial reveal), and Phase 3 Social Proof &amp; Explainer (driving conversions).'
            ],
            [
                'q' => 'How often should our brand review and adjust its video strategy?',
                'a' => 'We recommend quarterly video performance audits to analyze retention data, test emerging platform trends, and re-allocate media budgets to highest-converting creative assets.'
            ]
        ],
        'sort_order' => 32,
        'meta_title' => 'Video Marketing Strategy & Media Planning Bangladesh | AR Entertainment',
        'meta_description' => 'Data-driven video distribution strategy, audience targeting, and multi-channel campaign planning in Dhaka, Bangladesh by AR Entertainment. Maximize ROI.'
    ]
];

$stmt_upsert = $db->prepare("
    INSERT INTO services (
        title, slug, icon, short_summary, content, pricing_note, faqs_json, sort_order, status, meta_title, meta_description, created_at, updated_at
    ) VALUES (
        :title, :slug, :icon, :short_summary, :content, :pricing_note, :faqs_json, :sort_order, 'active', :meta_title, :meta_description, NOW(), NOW()
    ) ON DUPLICATE KEY UPDATE
        title = VALUES(title),
        icon = VALUES(icon),
        short_summary = VALUES(short_summary),
        content = VALUES(content),
        pricing_note = VALUES(pricing_note),
        faqs_json = VALUES(faqs_json),
        sort_order = VALUES(sort_order),
        status = 'active',
        meta_title = VALUES(meta_title),
        meta_description = VALUES(meta_description),
        updated_at = NOW()
");

$seeded_count = 0;
foreach ($services_batch4 as $svc) {
    $faqs_json = json_encode($svc['faqs'], JSON_UNESCAPED_UNICODE | JSON_PRETTY_PRINT);

    $stmt_upsert->execute([
        ':title' => $svc['title'],
        ':slug' => $svc['slug'],
        ':icon' => $svc['icon'],
        ':short_summary' => $svc['short_summary'],
        ':content' => $svc['content'],
        ':pricing_note' => $svc['pricing_note'],
        ':faqs_json' => $faqs_json,
        ':sort_order' => $svc['sort_order'],
        ':meta_title' => $svc['meta_title'],
        ':meta_description' => $svc['meta_description']
    ]);

    $faq_count = count($svc['faqs']);
    echo "   ✅ [" . (++$seeded_count) . "/8] Seeded Service: {$svc['title']} ({$svc['slug']}) - {$faq_count} FAQs\n";
}

$total_services = (int)$db->query("SELECT COUNT(*) FROM services")->fetchColumn();

echo "\n========================================================\n";
echo "🏆 BATCH 5.2.4 COMPLETED SUCCESSFULLY!\n";
echo "   - Total Services Seeded in Batch: {$seeded_count}\n";
echo "   - Current Total in Database: {$total_services}\n";
echo "========================================================\n";
