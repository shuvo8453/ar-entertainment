<?php
/**
 * AR Entertainment - Phase 5.2 Services Seeder (Batch 5.2.5: Services 33–40)
 * 
 * Ingests 8 Specialized Tutorials, Marketing, Quality Assurance & Technical 3D Services:
 * 33. Video Tutorials & How-To Explainer Production (video-tutorials)
 * 34. Digital Video Marketing & Multi-Channel Campaigns (video-marketing)
 * 35. Video SEO, Metadata & Description Optimization (video-description-service-bangladesh)
 * 36. Dhaka Corporate AV & Executive Media Production (corporate-av-production-company-in-dhaka)
 * 37. Nationwide Corporate AV & Industrial Filming Services (corporate-av-production-in-bangladesh)
 * 38. Production Quality Assurance & Service Excellence (service-excellence)
 * 39. Custom Audio-Visual & Bespoke Cinema Solutions (additional-services)
 * 40. 3D Technical & Isometric Explainer Video Production (animated-explainer-video)
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
echo "🎬 AR ENTERTAINMENT - SEED SERVICES BATCH 5.2.5 (33–40)\n";
echo "========================================================\n\n";

$db = db();

$services_batch5 = [
    // 33. Video Tutorials & How-To Explainer Production
    [
        'title' => 'Video Tutorials & How-To Explainer Production',
        'slug' => 'video-tutorials',
        'icon' => 'fa-solid fa-chalkboard-user',
        'short_summary' => 'Clear, step-by-step customer education tutorials, software screencasts, physical product unboxing guides, and user onboarding walkthroughs with animated graphics.',
        'content' => '<h3>Empower Users and Cut Support Costs with Engaging Video Tutorials</h3>
<p>Complex software platforms, consumer electronics, and financial apps often lose users during onboarding due to confusing user journeys. <strong>AR Entertainment</strong> produces crisp, highly intuitive Video Tutorials and How-To Guides that walk users through setup, features, and troubleshooting with crystal clarity.</p>
<h4>Intuitive Educational Video Solutions</h4>
<ul>
    <li><strong>High-Definition Screencasting &amp; UI Zoom:</strong> Crisp digital screen recording paired with smooth cursor tracking, highlighted button clicks, and animated zoom insets.</li>
    <li><strong>Studio Presenter &amp; Hands-On Unboxing:</strong> Filming charismatic on-camera instructors and hands-on tabletop unboxings demonstrating tactile hardware interactions.</li>
    <li><strong>Micro-Chapter Video Navigation:</strong> Segmented into bite-sized 60-to-120 second modular topics allowing users to search and resolve specific problems instantly.</li>
    <li><strong>Multilingual Voiceover &amp; Subtitles:</strong> Clear, articulate narration in Standard Bangla and International English tailored to diverse customer skill levels.</li>
</ul>
<p>Boost user activation, reduce customer support tickets by up to 60%, and turn first-time buyers into loyal power users.</p>',
        'pricing_note' => 'Video tutorial packages range from ৳50,000 to ৳280,000 BDT per module based on screen UI animation, studio presenter capture, and bilingual voiceovers.',
        'faqs' => [
            [
                'q' => 'What is the ideal length for a video tutorial?',
                'a' => 'The most effective tutorials run between 90 seconds and 3 minutes per specific topic. Breaking complex workflows into a series of short modular videos ensures higher viewer retention.'
            ],
            [
                'q' => 'Can you produce software tutorials without our team needing to appear on camera?',
                'a' => 'Yes. We can produce 100% animated screencast tutorials featuring dynamic UI animations, animated pointer graphics, background music, and professional voiceover.'
            ],
            [
                'q' => 'How do you handle software UI updates down the line?',
                'a' => 'Our modular editing templates allow specific screen sequences or voiceover sentences to be swapped and re-rendered rapidly whenever your software receives an update.'
            ],
            [
                'q' => 'Can you film physical product tutorials and unboxings?',
                'a' => 'Yes. We film in professional studio environments with macro overhead lighting rigs, showcasing physical assembly, button controls, and maintenance steps clearly.'
            ],
            [
                'q' => 'What deliverables are included for video tutorials?',
                'a' => 'You receive web-optimized 1080p/4K MP4 files, custom clickable video thumbnails, timed SRT subtitle files, and chapter timestamp metadata for YouTube and helpdesk portals.'
            ],
            [
                'q' => 'How fast can a 5-part video tutorial series be completed?',
                'a' => 'A standard 5-part tutorial series takes 7 to 12 business days from script approval to final delivery.'
            ]
        ],
        'sort_order' => 33,
        'meta_title' => 'Video Tutorials & How-To Video Production | AR Entertainment',
        'meta_description' => 'Professional video tutorials, software walkthroughs, and how-to guides in Dhaka, Bangladesh by AR Entertainment. Boost customer onboarding and cut support costs.'
    ],

    // 34. Digital Video Marketing & Multi-Channel Campaigns
    [
        'title' => 'Digital Video Marketing & Multi-Channel Campaigns',
        'slug' => 'video-marketing',
        'icon' => 'fa-solid fa-bullseye',
        'short_summary' => 'Full-funnel video advertising management, YouTube TrueView ad campaigns, Meta video ads, conversion retargeting, and performance optimization across Bangladesh.',
        'content' => '<h3>Performance-Driven Video Marketing That Generates Measurable Sales</h3>
<p>Even the most brilliant cinematic video fails if it does not reach paying customers. <strong>AR Entertainment</strong> provides end-to-end digital video marketing services—combining cinema-grade creative assets with rigorous performance advertising, precision audience targeting, and continuous conversion rate optimization.</p>
<h4>Full-Funnel Digital Video Advertising</h4>
<ul>
    <li><strong>YouTube In-Stream &amp; Discovery Ads:</strong> Architecting YouTube skippable and non-skippable campaigns targeting relevant search keywords, competitor channels, and in-market buyers.</li>
    <li><strong>Meta (Facebook/Instagram) Paid Video Funnels:</strong> Running dynamic multi-ratio video ads optimized for link clicks, lead generation forms, and WhatsApp direct messaging.</li>
    <li><strong>Dynamic Retargeting Sequences:</strong> Serving secondary testimonial and explainer videos specifically to prospects who watched more than 50% of your initial hero ad.</li>
    <li><strong>A/B Creative Hook Testing:</strong> Continuously testing multiple visual hooks, opening 3-second headlines, and call-to-actions to lower cost-per-acquisition (CPA).</li>
</ul>
<p>Bridge the gap between creative storytelling and real-world commercial revenue with AR Entertainment’s performance marketing engine.</p>',
        'pricing_note' => 'Video marketing campaign management ranges from ৳60,000 to ৳300,000 BDT/month with pixel setup, audience segmentation, ad testing, and real-time ROAS dashboards.',
        'faqs' => [
            [
                'q' => 'How does video marketing differ from traditional static image ads?',
                'a' => 'Video ads generate up to 3x higher engagement, longer dwell time, and significantly higher emotional trust, resulting in lower customer acquisition costs across Meta and Google ad networks.'
            ],
            [
                'q' => 'Do you manage our ad spend budget on Google and Facebook?',
                'a' => 'Yes. Our certified media buyers handle campaign setup, audience segmentation, daily ad spend bidding, and budget scaling across Meta Ads Manager and Google Ads.'
            ],
            [
                'q' => 'What is video retargeting and how does it increase sales?',
                'a' => 'Retargeting tracks users who watched your initial brand video and automatically serves them product-focused explainers, discounts, or client reviews to close the sale.'
            ],
            [
                'q' => 'What reporting and analytics do you provide during campaigns?',
                'a' => 'We provide real-time dashboard reports tracking 3-second view rates, completion rates, cost-per-view (CPV), click-through rates (CTR), and direct revenue return on ad spend (ROAS).'
            ],
            [
                'q' => 'What is the recommended minimum monthly ad spend budget?',
                'a' => 'For meaningful audience reach and statistical A/B testing in Bangladesh, we recommend a minimum media spend of ৳50,000 to ৳200,000 BDT per month.'
            ],
            [
                'q' => 'Can you optimize existing videos that our company already filmed?',
                'a' => 'Yes. We can re-edit, re-color, add dynamic kinetic subtitles, and re-format existing footage into high-converting social ad formats.'
            ]
        ],
        'sort_order' => 34,
        'meta_title' => 'Digital Video Marketing Agency in Bangladesh | AR Entertainment',
        'meta_description' => 'High-ROI digital video marketing and multi-channel ad campaigns in Dhaka by AR Entertainment. Scale conversions on YouTube, Facebook, and Instagram.'
    ],

    // 35. Video SEO, Metadata & Description Optimization
    [
        'title' => 'Video SEO, Metadata & Description Optimization',
        'slug' => 'video-description-service-bangladesh',
        'icon' => 'fa-solid fa-magnifying-glass-chart',
        'short_summary' => 'Strategic YouTube and search engine video optimization, keyword-dense video descriptions, custom thumbnail design, timestamp chapters, and schema markup.',
        'content' => '<h3>Dominate Google &amp; YouTube Search Rankings with Expert Video SEO</h3>
<p>YouTube is the world’s second-largest search engine. Without proper metadata, keyword research, and schema markup, your videos remain invisible to millions of active searchers. <strong>AR Entertainment</strong> optimizes every aspect of your video metadata to ensure your content ranks at the top of Google and YouTube search results organically.</p>
<h4>Comprehensive Video SEO Architecture</h4>
<ul>
    <li><strong>Search Intent &amp; Keyword Research:</strong> Discovering high-volume, low-competition search queries in both Bangla (Latin &amp; Bengali script) and English.</li>
    <li><strong>Conversion-Focused Video Descriptions:</strong> Crafting rich, keyword-optimized 500+ word video descriptions with lead capture links, social handles, and brand info.</li>
    <li><strong>Custom High-CTR Thumbnails:</strong> Designing eye-catching, high-contrast thumbnails with emotive facial expressions and bold typography that maximize click-through rates.</li>
    <li><strong>Timestamp Chapters &amp; JSON-LD Schema:</strong> Structuring timestamp chapters for Google Rich Snippets and embedding Schema.org VideoObject code into your website.</li>
</ul>
<p>Generate thousands of organic views, establish perennial authority in your industry, and turn YouTube into a 24/7 evergreen lead generation machine.</p>',
        'pricing_note' => 'Video SEO and channel optimization packages start from ৳25,000 to ৳120,000 BDT per channel or video batch with ranking reports and CTR enhancements.',
        'faqs' => [
            [
                'q' => 'What is Video SEO?',
                'a' => 'Video SEO is the process of optimizing video titles, tags, descriptions, transcripts, custom thumbnails, and website schema markup so videos rank higher in Google and YouTube search results.'
            ],
            [
                'q' => 'How does a custom thumbnail impact video rankings?',
                'a' => 'Thumbnails directly determine Click-Through Rate (CTR). High CTR signals to the YouTube algorithm that viewers want to watch your content, triggering broader algorithmic promotion.'
            ],
            [
                'q' => 'Can you optimize our company\'s existing YouTube channel and past videos?',
                'a' => 'Yes. We conduct complete channel audits, revising outdated titles, descriptions, playlists, and tags on past videos to revive dormant organic traffic.'
            ],
            [
                'q' => 'How do timestamp chapters improve Google search visibility?',
                'a' => 'Timestamp chapters allow Google to index specific moments in your video, displaying "Key Moments" directly in Google Search results for targeted user queries.'
            ],
            [
                'q' => 'How long does it take for video SEO optimizations to show results?',
                'a' => 'While algorithmic re-indexing begins within 48 hours, meaningful organic search ranking improvements and sustained view growth typically materialize within 2 to 6 weeks.'
            ],
            [
                'q' => 'Do you provide Schema.org code for embedding videos onto our corporate website?',
                'a' => 'Yes. We generate Google-compliant VideoObject JSON-LD structured data code that enhances your website pages with rich video snippets in Google Search.'
            ]
        ],
        'sort_order' => 35,
        'meta_title' => 'Video SEO & YouTube Channel Optimization | AR Entertainment',
        'meta_description' => 'Professional Video SEO, YouTube metadata optimization, and high-CTR thumbnail design in Dhaka, Bangladesh by AR Entertainment. Rank #1 on YouTube & Google.'
    ],

    // 36. Dhaka Corporate AV & Executive Media Production
    [
        'title' => 'Dhaka Corporate AV & Executive Media Production',
        'slug' => 'corporate-av-production-company-in-dhaka',
        'icon' => 'fa-solid fa-building-columns',
        'short_summary' => 'Premier corporate audio-visual production tailored for multinational corporations, financial institutions, and conglomerates located in Gulshan, Banani, Motijheel, and Uttara.',
        'content' => '<h3>High-Caliber Corporate Media Production in the Heart of Dhaka</h3>
<p>As Bangladesh’s commercial and financial epicenter, Dhaka demands corporate communications of international distinction. Headquartered in Dhaka, <strong>AR Entertainment</strong> caters to premier financial conglomerates, multinational banks, IT software exporters, and enterprise institutions across Gulshan, Banani, Motijheel, and Kawran Bazar.</p>
<h4>Bespoke Dhaka Corporate Media Services</h4>
<ul>
    <li><strong>Executive Boardroom &amp; HQ Filming:</strong> Rapid deployment of cinema-grade lighting and silent camera rigs in active corporate headquarters without disrupting business operations.</li>
    <li><strong>Corporate Governance &amp; AGM Videos:</strong> Producing polished annual performance overviews, sustainability reports, and shareholder presentations for Annual General Meetings.</li>
    <li><strong>Dhaka Skyline Cinema Aerials:</strong> Sweeping drone sweeps capturing prime corporate towers, Hatirjheel vistas, and bustling commercial districts under legal CAAB clearance.</li>
    <li><strong>Executive Media Training:</strong> Directing and coaching board directors and CEOs for natural, authoritative on-camera presence during high-stakes corporate announcements.</li>
</ul>
<p>Partner with Dhaka’s most respected corporate video production house to convey your enterprise stature with elegance, authority, and cinematic prestige.</p>',
        'pricing_note' => 'Dhaka corporate AV packages range from ৳200,000 to ৳1,200,000 BDT with on-site boardroom filming, 4K executive interviews, and drone footage of Dhaka commercial skylines.',
        'faqs' => [
            [
                'q' => 'Why choose AR Entertainment for corporate AV production in Dhaka?',
                'a' => 'We combine 20+ years of Ogilvy-grade advertising heritage with in-house cinema equipment (ARRI/RED), ensuring enterprise-grade polish at direct production house efficiency.'
            ],
            [
                'q' => 'Can you shoot inside our corporate office during regular working hours?',
                'a' => 'Yes. Our production crews use compact cinema LED lighting and whisper-quiet camera rigs, planning shooting schedules to ensure zero disruption to office workflows.'
            ],
            [
                'q' => 'Do you film corporate events and AGMs in Dhaka five-star hotels?',
                'a' => 'Yes. We regularly film at Radisson Blu, InterContinental, Westin, Sheraton, and BICC, providing multi-camera live switching and same-day highlight reels.'
            ],
            [
                'q' => 'How do you handle confidential financial figures and corporate disclosures?',
                'a' => 'We enforce strict legal non-disclosure agreements (NDAs) and encrypted data handling protocols to protect all sensitive corporate information.'
            ],
            [
                'q' => 'What is the production timeline for a prestige corporate AV in Dhaka?',
                'a' => 'A full corporate film typically takes 3 to 4 weeks, with expedited 10-day turnarounds available for urgent AGM or investor deadlines.'
            ],
            [
                'q' => 'Can you deliver videos in multiple aspect ratios for web, TV, and LinkedIn?',
                'a' => 'Yes. We provide 16:9 widescreen masters for website and gala screens, alongside 9:16 vertical and 1:1 square cutdowns tailored for LinkedIn and social media.'
            ]
        ],
        'sort_order' => 36,
        'meta_title' => 'Dhaka Corporate AV Production Company | AR Entertainment',
        'meta_description' => 'Prestige corporate AV and brand film production in Gulshan, Banani, and Dhaka by AR Entertainment. Trusted by leading Bangladeshi corporations & financial institutions.'
    ],

    // 37. Nationwide Corporate AV & Industrial Filming Services
    [
        'title' => 'Nationwide Corporate AV & Industrial Filming Services',
        'slug' => 'corporate-av-production-in-bangladesh',
        'icon' => 'fa-solid fa-map-location-dot',
        'short_summary' => 'Multi-district industrial filming covering economic export zones (EPZs), power plants, bridge infrastructure, and agribusiness supply chains across all 64 districts.',
        'content' => '<h3>Nationwide Industrial Filming Across Bangladesh’s Economic Corridors</h3>
<p>Bangladesh’s economic growth is driven by heavy manufacturing, coastal shipyards, pharmaceutical hubs, power generation plants, and agricultural supply chains spanning from Chattogram to Sylhet and Khulna. <strong>AR Entertainment</strong> deploys self-sufficient cinema units capable of filming across all 64 districts of Bangladesh.</p>
<h4>Comprehensive Nationwide Production Capabilities</h4>
<ul>
    <li><strong>Special Economic Zones (BEPZA &amp; BEZA):</strong> Documenting export processing zones, heavy industrial manufacturing, automated robotics, and foreign joint ventures.</li>
    <li><strong>Infrastructure &amp; Mega-Project Filming:</strong> Capturing bridges, deep-sea ports, railway networks, and energy installations with 6K drone cinematography and time-lapse rigs.</li>
    <li><strong>Agribusiness &amp; Tea Estate Expeditions:</strong> Beautiful panoramic filming across rural agricultural processing hubs, tea gardens in Sreemangal, and fisheries in Khulna.</li>
    <li><strong>Self-Sustaining Expedition Units:</strong> Equipped with mobile power generators, 4WD transport, heavy-lift drones, waterproof Pelican gear, and bilingual field fixers.</li>
</ul>
<p>Showcase the full geographic scale, industrial power, and supply chain strength of your national enterprise with AR Entertainment.</p>',
        'pricing_note' => 'Nationwide corporate video expeditions range from ৳350,000 to ৳2,000,000 BDT including multi-city logistics, heavy-lift aerials, and 4K cinema master suites.',
        'faqs' => [
            [
                'q' => 'Can AR Entertainment film across multiple divisions and districts in a single project?',
                'a' => 'Yes. We routinely execute multi-city production tours (e.g., Dhaka, Chattogram, Sylhet, Khulna, Bogura) with structured travel itineraries and dedicated transport trucks.'
            ],
            [
                'q' => 'How do you handle health and safety protocols in hazardous industrial environments?',
                'a' => 'Our crew carries certified PPE (helmets, high-vis vests, steel-toe boots) and completes site safety orientations before filming inside heavy industrial or chemical plants.'
            ],
            [
                'q' => 'Do you capture long-term time-lapse footage for infrastructure construction?',
                'a' => 'Yes. We deploy weatherproof solar-powered time-lapse camera systems that capture construction progress over weeks, months, or years.'
            ],
            [
                'q' => 'How do you manage government and regional security permissions across districts?',
                'a' => 'We handle all district administration liaison, police notifications, and specialized area clearances on behalf of clients prior to the shoot.'
            ],
            [
                'q' => 'What is the turnaround time for a multi-location nationwide corporate film?',
                'a' => 'A nationwide project typically takes 4 to 6 weeks from initial scouting to final multi-language master delivery.'
            ],
            [
                'q' => 'Can you supply foreign language voiceovers for international joint venture partners?',
                'a' => 'Yes. We provide native voiceover artists in English, Japanese, Chinese, Korean, German, and Arabic to communicate your national capabilities to global partners.'
            ]
        ],
        'sort_order' => 37,
        'meta_title' => 'Nationwide Corporate AV Production in Bangladesh | AR Entertainment',
        'meta_description' => 'Industrial corporate video production across all 64 districts of Bangladesh by AR Entertainment. Filming EPZs, manufacturing, infrastructure & supply chains.'
    ],

    // 38. Production Quality Assurance & Service Excellence
    [
        'title' => 'Production Quality Assurance & Service Excellence',
        'slug' => 'service-excellence',
        'icon' => 'fa-solid fa-award',
        'short_summary' => 'Strict Hollywood and European broadcast quality assurance standards, DaVinci ACES color calibration, EBU R128 audio mastering, and on-time delivery guarantees.',
        'content' => '<h3>Uncompromising Quality Standards &amp; Client Service Excellence</h3>
<p>At <strong>AR Entertainment</strong>, technical precision and creative integrity are non-negotiable. Every project—from a 15-second social reel to a nationwide television commercial—passes through rigorous Quality Control (QC) checkpoints to ensure flawless broadcast compliance and visual perfection.</p>
<h4>The AR Entertainment Quality Assurance Framework</h4>
<ul>
    <li><strong>DaVinci Resolve ACES Color Calibration:</strong> Color grading performed on calibrated OLED reference monitors within the Academy Color Encoding System (ACES) for perfect color consistency across all screens.</li>
    <li><strong>EBU R128 &amp; ITU-R BS.1770 Audio Mastering:</strong> Multi-track sound design calibrated strictly to international loudness standards, eliminating clipping or distorted broadcast audio.</li>
    <li><strong>Multi-Tiered Creative Reviews:</strong> Every edit is reviewed by executive director Azizul Hoque Shiplu before client presentation, guaranteeing Ogilvy-grade aesthetic standards.</li>
    <li><strong>100% On-Time Delivery Guarantee:</strong> Strict project management milestones, automated daily backups, and redundant cloud storage ensuring zero missed deadlines or data loss.</li>
</ul>
<p>Experience the confidence of working with a production partner that values your brand reputation as much as you do.</p>',
        'pricing_note' => 'Dedicated Quality Control (QC) and technical finishing suites included across all AR Entertainment production packages.',
        'faqs' => [
            [
                'q' => 'What quality control standards does AR Entertainment adhere to?',
                'a' => 'We adhere to international broadcast standards including DaVinci ACES color management, EBU R128 audio loudness normalization, and ProRes 422 HQ broadcast master file specifications.'
            ],
            [
                'q' => 'How do you ensure data security and backup of our raw footage?',
                'a' => 'We employ the 3-2-1 backup rule: 3 copies of all raw footage across 2 different media types (RAID storage and encrypted cloud off-site archives).'
            ],
            [
                'q' => 'What if we require revisions to the final video edit?',
                'a' => 'Every production package includes up to 3 structured revision rounds. We utilize frame-accurate collaborative review platforms so clients can leave exact timestamped notes.'
            ],
            [
                'q' => 'How does AR Entertainment guarantee on-time project delivery?',
                'a' => 'We assign a dedicated Production Coordinator to each client, establishing clear milestone schedules for script, shoot, rough cut, and final master sign-off.'
            ],
            [
                'q' => 'Are your video deliverables calibrated for different viewing devices?',
                'a' => 'Yes. We test master files across OLED televisions, Apple Retina displays, Android mobile screens, and standard PC monitors to ensure color and audio fidelity everywhere.'
            ],
            [
                'q' => 'Who oversees the final sign-off of our project before delivery?',
                'a' => 'Founding Director Azizul Hoque Shiplu personally reviews the final color grade, audio mix, and narrative pacing before final release.'
            ]
        ],
        'sort_order' => 38,
        'meta_title' => 'Production Quality Assurance & Service Excellence | AR Entertainment',
        'meta_description' => 'Uncompromising production quality, DaVinci ACES color grading, EBU R128 audio mastering, and on-time delivery guarantees by AR Entertainment.'
    ],

    // 39. Custom Audio-Visual & Bespoke Cinema Solutions
    [
        'title' => 'Custom Audio-Visual & Bespoke Cinema Solutions',
        'slug' => 'additional-services',
        'icon' => 'fa-solid fa-sliders',
        'short_summary' => 'Specialized film equipment rental, teleprompter services, high-speed Phantom cameras, gimbal stabilizers, set construction, and specialized casting solutions.',
        'content' => '<h3>Bespoke Cinema Solutions for Unique Creative Requirements</h3>
<p>Not every production fits neatly into standard categories. When your project demands specialized high-speed Phantom cameras, custom underwater filming housings, heavy-duty camera cranes, or bespoke studio set construction, <strong>AR Entertainment</strong> provides custom cinematic solutions tailored to your exact creative vision.</p>
<h4>Specialized Cinema &amp; Technical Add-Ons</h4>
<ul>
    <li><strong>Specialized Camera Packages:</strong> High-speed 1000fps Phantom Flex cameras, underwater housings, probe macro lenses, and anamorphic prime sets.</li>
    <li><strong>Grip &amp; Heavy-Duty Movement Rigs:</strong> 24-foot camera cranes, motorized techno-jibs, car pursuit camera mounts, and electronic 3-axis Ronin rigs.</li>
    <li><strong>Bespoke Studio Set Design &amp; Construction:</strong> In-house production designers constructing realistic living rooms, laboratories, futuristic sets, or rustic village environments.</li>
    <li><strong>Teleprompter &amp; Confidence Monitor Packages:</strong> High-brightness 19-inch optical teleprompters with experienced operators for live broadcasts and CEO speeches.</li>
</ul>
<p>No creative vision is too ambitious. Tell us your technical requirements and AR Entertainment will build the custom production solution to bring it to life.</p>',
        'pricing_note' => 'Custom bespoke cinema add-on packages tailored to unique technical requirements, specialized lens packages, and bespoke art direction.',
        'faqs' => [
            [
                'q' => 'What additional cinema equipment can AR Entertainment supply on set?',
                'a' => 'We supply high-speed Phantom cameras, Cooke/Zeiss anamorphic lenses, 24-foot crane jibs, wireless Teradek video links, wireless Master Wheels, and studio teleprompter rigs.'
            ],
            [
                'q' => 'Can you construct custom sets inside a studio in Dhaka?',
                'a' => 'Yes. Our art department designs and constructs bespoke physical sets—including living rooms, offices, futuristic sci-fi rooms, and historical period settings.'
            ],
            [
                'q' => 'Do you provide professional teleprompter services for live speeches and video shoots?',
                'a' => 'Yes. We provide high-contrast glass teleprompters with dedicated software operators that adjust scroll speed in real time to the presenter\'s speaking tempo.'
            ],
            [
                'q' => 'Can you accommodate specialized underwater or marine filming in Bangladesh?',
                'a' => 'Yes. We provide waterproof camera housings and experienced dive operators for riverine, marine, and underwater pool filming.'
            ],
            [
                'q' => 'Do you handle specialized casting for foreign models or child actors?',
                'a' => 'Yes. Our casting directors maintain extensive talent databases spanning international models, trained child actors, stunt performers, and dancers.'
            ],
            [
                'q' => 'How do we request a custom equipment or technical package quote?',
                'a' => 'Contact our production management team with your equipment wish list and shoot dates for a comprehensive custom package quote within 24 hours.'
            ]
        ],
        'sort_order' => 39,
        'meta_title' => 'Custom Cinema & Specialized Film Solutions | AR Entertainment',
        'meta_description' => 'Specialized film equipment rental, teleprompters, Phantom high-speed cameras, custom set construction, and bespoke cinema solutions in Dhaka by AR Entertainment.'
    ],

    // 40. 3D Technical & Isometric Explainer Video Production
    [
        'title' => '3D Technical & Isometric Explainer Video Production',
        'slug' => 'animated-explainer-video',
        'icon' => 'fa-solid fa-cubes-stacked',
        'short_summary' => 'Photorealistic 3D technical animations, isometric architectural cuts, exploded product views, and industrial simulation videos for engineering and tech brands.',
        'content' => '<h3>Visualize Complex Engineering &amp; Technology with 3D Explainer Animations</h3>
<p>When physical filming cannot capture microscopic internal components, complex chemical processes, or under-construction architectural engineering, 3D technical animation is the ultimate storytelling medium. <strong>AR Entertainment</strong> creates photorealistic 3D animations, exploded product views, and isometric explainers that make complex concepts instantly understandable.</p>
<h4>Advanced 3D Technical &amp; Isometric Capabilities</h4>
<ul>
    <li><strong>Exploded Mechanical &amp; Product Views:</strong> Animating internal gears, electronic microchips, engine cylinders, and architectural HVAC systems in 3D space.</li>
    <li><strong>Isometric 3D World Animation:</strong> Stylized isometric cityscapes, factory floor cross-sections, and logistics supply chain simulations rendered with vibrant lighting.</li>
    <li><strong>CAD / BIM 3D Asset Import:</strong> Directly converting SolidWorks, AutoCAD, and Revit engineering models into cinema-grade 3D textured assets in Blender and Unreal Engine.</li>
    <li><strong>Microscopic &amp; Scientific Simulations:</strong> Visualizing pharmaceutical drug delivery mechanisms, water filtration membranes, and chemical molecular interactions.</li>
</ul>
<p>Explain the unfilmable, demonstrate technological superiority, and captivate technical stakeholders with AR Entertainment’s 3D animation studio.</p>',
        'pricing_note' => '3D technical explainer packages range from ৳120,000 to ৳650,000 BDT based on CAD model conversion, simulation physics, and 4K rendering passes.',
        'faqs' => [
            [
                'q' => 'What is a 3D technical explainer video?',
                'a' => 'It is a computer-generated 3D animation that visually demonstrates how a complex machine, software architecture, architectural infrastructure, or scientific process works internally.'
            ],
            [
                'q' => 'Can you work directly with our engineering CAD or SolidWorks 3D files?',
                'a' => 'Yes. We import native CAD, STEP, OBJ, and Revit models, clean the topology, and apply photorealistic textures and lighting to match real-world physical materials.'
            ],
            [
                'q' => 'What is an "exploded view" animation in 3D?',
                'a' => 'An exploded view separates the individual components of a complex product outwards in 3D space, showing how parts assemble and interact internally.'
            ],
            [
                'q' => 'How long does a 60-second 3D technical animation take to produce?',
                'a' => 'A full 60-second 3D technical video typically takes 3 to 5 weeks, encompassing 3D modeling, texturing, camera choreography, simulation physics, and 4K GPU rendering.'
            ],
            [
                'q' => 'What industries benefit most from 3D technical explainer videos?',
                'a' => 'Engineering manufacturing, pharmaceuticals, medical technology, real estate architecture, consumer electronics, and renewable energy infrastructure.'
            ],
            [
                'q' => 'Can we use the rendered 3D product models in static marketing brochures as well?',
                'a' => 'Yes. We can render ultra-high-resolution 8K static print stills from the 3D scene files for use on billboards, packaging, and digital brochures.'
            ]
        ],
        'sort_order' => 40,
        'meta_title' => '3D Technical & Isometric Explainer Videos | AR Entertainment',
        'meta_description' => 'Photorealistic 3D technical explainer animations, exploded product views, and isometric animations in Dhaka, Bangladesh by AR Entertainment. Visualize the unfilmable.'
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
foreach ($services_batch5 as $svc) {
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
echo "🏆 BATCH 5.2.5 COMPLETED SUCCESSFULLY!\n";
echo "   - Total Services Seeded in Batch: {$seeded_count}\n";
echo "   - Current Total in Database: {$total_services}\n";
echo "========================================================\n";
