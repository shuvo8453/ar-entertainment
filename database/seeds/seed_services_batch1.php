<?php
/**
 * AR Entertainment - Phase 5.2 Services Seeder (Batch 5.2.1: Services 1–8)
 * 
 * Ingests 8 Flagship Production Services:
 * 1. Television Commercial (TVC) Production
 * 2. Online Video Commercial (OVC) Production
 * 3. Corporate AV & Brand Film Production
 * 4. Documentary Film & Line Fixing Production
 * 5. Theme Song & Brand Anthem Production
 * 6. Music Video Production
 * 7. 2D & 3D Animation & Motion Graphics
 * 8. Animated Explainer & Product Video Production
 * 
 * Features:
 * - 100% Clean AR Entertainment Branding (0% Legacy Names)
 * - Structured JSON FAQs (5-7 per service)
 * - High-Impact Rich Body HTML
 * - Idempotent MySQL Upsert (ON DUPLICATE KEY UPDATE)
 */

declare(strict_types=1);

require_once __DIR__ . '/../../config/config.php';
require_once __DIR__ . '/../../config/db.php';
require_once __DIR__ . '/../../config/helpers.php';

echo "========================================================\n";
echo "🎬 AR ENTERTAINMENT - SEED SERVICES BATCH 5.2.1 (1–8)\n";
echo "========================================================\n\n";

$db = db();

$services_batch1 = [
    // 1. TV Commercial (TVC)
    [
        'title' => 'Television Commercial (TVC) Production',
        'slug' => 'tv-commercial',
        'icon' => 'fa-solid fa-tv',
        'short_summary' => 'Broadcast-grade 30-to-60-second television commercials crafted with Ogilvy-trained advertising strategy, cinematic lighting, and DaVinci ACES color grading.',
        'content' => '<h3>High-Impact Television Commercial Production in Bangladesh</h3>
<p>At <strong>AR Entertainment</strong>, TV commercial production is where strategic brand psychology meets cinema-grade craftsmanship. Led by founding director <strong>Azizul Hoque Shiplu</strong>—an Ogilvy &amp; Mather alumnus and graduate of the Zahir Raihan Film Institute who has helmed over 200 broadcast TVCs—our team treats every 30-second commercial as an emotional cinematic experience.</p>
<h4>End-to-End Broadcast Workflow</h4>
<ul>
    <li><strong>Strategic Conceptualization &amp; Scriptwriting:</strong> Creative ideation, narrative hooks, and broadcast storyboarding tailored to target audience demographics.</li>
    <li><strong>Pre-Production &amp; Casting:</strong> Professional casting directors sourcing celebrated actors and fresh faces, location scouting across Bangladesh, and bespoke set design.</li>
    <li><strong>Cinema-Grade Production:</strong> Filmed on ARRI Alexa Mini LF and RED V-Raptor camera systems with Cooke and Zeiss cinema primes, operated by experienced broadcast cinematographers.</li>
    <li><strong>Post-Production &amp; Finishing:</strong> Multi-track sound design, original foley, orchestral scoring, visual effects compositing, and DaVinci Resolve Studio color grading optimized for broadcast television.</li>
</ul>
<p>From FMCG giants and financial institutions to leading real estate developers, AR Entertainment delivers commercials that capture national attention and generate measurable ROI.</p>',
        'pricing_note' => 'Custom packages typically range from ৳350,000 to ৳2,500,000 BDT depending on shoot days, cast, locations, and VFX scope.',
        'faqs' => [
            [
                'q' => 'What is TV Commercial (TVC) production?',
                'a' => 'TV Commercial production is the end-to-end process of creating a television advertisement—from concept development, scriptwriting, casting, and location scouting through to filming, editing, sound design, colour grading, and broadcast-ready delivery conforming to national broadcast standards.'
            ],
            [
                'q' => 'Why is TV Commercial production essential for modern brands?',
                'a' => 'Television commercials provide unmatched mass reach and household credibility in Bangladesh. A well-crafted TVC builds long-term brand equity, deep emotional trust, and consumer recall that digital-only ads often struggle to match.'
            ],
            [
                'q' => 'How long does it take to complete a TV Commercial with AR Entertainment?',
                'a' => 'A standard TVC production takes 3 to 6 weeks from initial creative brief to final broadcast master delivery. Rush timelines (10 to 14 days) can also be accommodated depending on set construction and casting requirements.'
            ],
            [
                'q' => 'What makes AR Entertainment different from other TVC production agencies?',
                'a' => 'AR Entertainment combines 19+ years of top-tier advertising agency strategy (Ogilvy heritage) with in-house cinema camera packages (ARRI/RED) and DaVinci ACES color grading, delivering agency-grade creative vision at direct production house efficiency.'
            ],
            [
                'q' => 'What industries does AR Entertainment produce TVCs for?',
                'a' => 'We produce TV commercials across FMCG, real estate, pharmaceuticals, banking & financial services, building materials, telecommunications, lifestyle retail, and government/NGO public campaigns.'
            ],
            [
                'q' => 'Can AR Entertainment handle broadcast delivery formats for Bangladeshi TV channels?',
                'a' => 'Yes. We deliver broadcast-ready ProRes 422 HQ and XDCAM HD master files strictly calibrated to the technical audio-visual standards required by BTRC and major Bangladeshi satellite television channels.'
            ]
        ],
        'sort_order' => 1,
        'meta_title' => 'TV Commercial (TVC) Production Agency in Bangladesh | AR Entertainment',
        'meta_description' => 'Broadcast-grade TV commercial production in Dhaka, Bangladesh by AR Entertainment. Ogilvy-trained director Azizul Hoque Shiplu, ARRI/RED cameras, and DaVinci color grading.'
    ],

    // 2. Online Video Commercial (OVC)
    [
        'title' => 'Online Video Commercial (OVC) Production',
        'slug' => 'online-video-commercial',
        'icon' => 'fa-solid fa-play',
        'short_summary' => 'High-conversion digital video commercials engineered with 3-second thumb-stopping hooks, multi-aspect ratio cutdowns, and social-first visual storytelling.',
        'content' => '<h3>Platform-Native Online Video Commercials for Maximum Digital Engagement</h3>
<p>In today’s hyper-competitive digital landscape, capturing viewer attention within the first 3 seconds is everything. <strong>AR Entertainment</strong> engineers high-energy, scroll-stopping Online Video Commercials (OVCs) tailored specifically for YouTube, Facebook, Instagram Reels, and TikTok.</p>
<h4>Engineered for High Retention &amp; Conversion</h4>
<ul>
    <li><strong>Audience Hook Architecture:</strong> Immediate narrative hooks, unexpected visual shifts, and clear brand placement designed to conquer short attention spans.</li>
    <li><strong>Multi-Format Deliverables:</strong> Master deliveries in 16:9 (YouTube &amp; Desktop), 9:16 (Stories &amp; Reels), 1:1 (Feed), and 4:5 optimized for mobile feeds.</li>
    <li><strong>Sound-Off Optimization:</strong> Dynamic kinetic typography, on-screen text callouts, and expressive visual acting that communicate value even without audio.</li>
    <li><strong>Viral Cultural Relevance:</strong> Creative treatments infused with contemporary Bangladeshi youth trends, humor, emotion, and relatable lifestyle scenarios.</li>
</ul>
<p>Whether launching a new consumer product, driving eCommerce sales, or elevating brand awareness across social media, our digital commercials deliver measurable click-through rates and high shareability.</p>',
        'pricing_note' => 'Packages range from ৳150,000 to ৳850,000 BDT based on campaign duration, talent casting, and social cutdown variations.',
        'faqs' => [
            [
                'q' => 'What is an online video commercial (OVC)?',
                'a' => 'An OVC (Online Video Commercial) is a video advertisement crafted specifically for digital and social media channels like Facebook, YouTube, Instagram, and TikTok, featuring fast hooks, engaging pacing, and mobile-friendly vertical or square formats.'
            ],
            [
                'q' => 'How does an OVC differ from a traditional TVC?',
                'a' => 'A TVC is designed for broadcast television with fixed 30 or 60-second lengths and a lean-back viewing experience. An OVC is designed for lean-forward mobile users, demanding an immediate hook in the first 3 seconds, flexible durations (15s to 90s), and multi-ratio aspect options.'
            ],
            [
                'q' => 'How long does OVC production take in Bangladesh?',
                'a' => 'A standard OVC takes 10 to 20 business days from brief to final export. For agile social campaigns or rapid product launches, expedited delivery within 7 days is possible.'
            ],
            [
                'q' => 'What makes AR Entertainment different from other OVC production houses?',
                'a' => 'We combine cinema-level optics and professional lighting with rigorous digital analytics knowledge. We do not just make pretty videos—we build high-retention commercial assets designed to convert viewers into paying customers.'
            ],
            [
                'q' => 'Do you provide cutdowns and vertical formats for TikTok and Instagram Reels?',
                'a' => 'Yes. Every OVC production package includes 9:16 vertical cuts, 1:1 square versions, 6-second bumper ads, and 15-second story cutdowns with stylized captions.'
            ]
        ],
        'sort_order' => 2,
        'meta_title' => 'Online Video Commercial (OVC) Production in Bangladesh | AR Entertainment',
        'meta_description' => 'High-conversion OVC production in Dhaka, Bangladesh. Fast hooks, vertical Reels/TikTok cutdowns, and cinematic visuals by AR Entertainment.'
    ],

    // 3. Corporate AV & Brand Film
    [
        'title' => 'Corporate AV & Brand Film Production',
        'slug' => 'corporate-av',
        'icon' => 'fa-solid fa-building',
        'short_summary' => 'Sophisticated corporate audiovisual profiles, investor presentations, factory showcases, and ESG sustainability films that elevate enterprise prestige.',
        'content' => '<h3>Prestigious Corporate Audiovisuals for Industry Leaders</h3>
<p>A corporate audiovisual (AV) is the definitive visual statement of your organization’s stature, manufacturing prowess, human capital, and future vision. <strong>AR Entertainment</strong> crafts cinematic corporate brand films for Bangladesh’s leading conglomerates, multinational corporations, industrial exporters, and institutions.</p>
<h4>Comprehensive Enterprise Storytelling</h4>
<ul>
    <li><strong>Executive Interviews &amp; Thought Leadership:</strong> Warm, authoritative lighting and teleprompter-assisted executive interviews that project credibility and visionary leadership.</li>
    <li><strong>Industrial &amp; Factory Cinematography:</strong> Specialized heavy-industry filming in manufacturing plants, high-speed automated production lines, pharmaceutical cleanrooms, and automated logistics hubs.</li>
    <li><strong>Licensed Drone &amp; Aerial Filming:</strong> Ultra-high-definition aerial surveys showcasing campus scale, factory infrastructure, and environmental stewardship.</li>
    <li><strong>3D Motion Graphics &amp; Financial Infographics:</strong> Sleek kinetic infographics illustrating annual financial milestones, supply chain networks, and CSR initiatives.</li>
</ul>
<p>From annual general meetings (AGMs) and international export trade delegations to investor roadshows, our corporate films command respect and reinforce enterprise reputation.</p>',
        'pricing_note' => 'Corporate AV packages typically range from ৳250,000 to ৳1,800,000 BDT depending on factory locations, drone clearances, and 3D graphics.',
        'faqs' => [
            [
                'q' => 'What is Corporate AV Production?',
                'a' => 'Corporate AV (Audiovisual) production is the creation of a comprehensive video profile representing an entire company, group of industries, or brand. It showcases facilities, team expertise, manufacturing capacity, and corporate culture to investors, B2B partners, and clients.'
            ],
            [
                'q' => 'Why is Corporate AV production important for Bangladeshi enterprises?',
                'a' => 'A cinema-grade corporate AV builds instant international trust with global buyers, export partners, and institutional investors. It visually validates operational scale, quality certifications, and ethical compliance better than any brochure.'
            ],
            [
                'q' => 'How long does the Corporate AV production process take?',
                'a' => 'Most corporate films take 3 to 5 weeks, accounting for factory scheduling, executive availability, script approvals, aerial filming permits, and multi-lingual voiceover recording.'
            ],
            [
                'q' => 'What makes AR Entertainment the leading corporate video production agency?',
                'a' => 'Our crew has filmed inside over 100 industrial plants across Bangladesh (textiles, pharmaceuticals, heavy steel, FMCG). We understand industrial safety protocols, cleanroom regulations, and how to make complex machinery look visually breathtaking.'
            ],
            [
                'q' => 'Can you produce corporate videos in multiple languages (English, Bengali, French)?',
                'a' => 'Yes. We provide native international voiceover artists, professional script translation, and synchronized multilingual subtitles for international export delegations.'
            ]
        ],
        'sort_order' => 3,
        'meta_title' => 'Corporate AV & Brand Film Production Company in Dhaka | AR Entertainment',
        'meta_description' => 'World-class corporate AV production in Bangladesh. Factory profiles, investor brand films, and industrial cinematography by AR Entertainment.'
    ],

    // 4. Documentary Film & Line Fixing
    [
        'title' => 'Documentary Film Production & Line Fixing',
        'slug' => 'documentary',
        'icon' => 'fa-solid fa-globe',
        'short_summary' => 'Authentic social impact documentaries and full-service line production fixing for international networks filming across Bangladesh.',
        'content' => '<h3>Cinema-Grade Documentaries &amp; Turnkey Film Fixing in Bangladesh</h3>
<p>Bangladesh is a land of dramatic geographic diversity, resilient communities, and vibrant cultural heritage. <strong>AR Entertainment</strong> produces award-winning independent documentaries, NGO social impact reports, and provides complete international film fixing services for global television networks including the BBC, National Geographic, and international production houses.</p>
<h4>Complete Line Production &amp; Fixing Infrastructure</h4>
<ul>
    <li><strong>Government Filming Permits &amp; Clearances:</strong> Rapid processing of Ministry of Information (MoI), Civil Aviation (CAAB) drone clearances, and local district administrative permits.</li>
    <li><strong>Local Line Production Crew &amp; Bilingual Fixers:</strong> Seasoned production managers, local location scouts, translators, safety officers, and professional cinema camera operators.</li>
    <li><strong>Equipment Rental &amp; Logistics:</strong> Access to RED V-Raptor, Sony FX9/FX6 packages, Zeiss/Cooke cinema glass, wireless follow-focus, sound packages, and rugged off-road transport across remote terrain.</li>
    <li><strong>Deep Access:</strong> Unrivaled access to Old Dhaka shipyards, Sundarbans mangrove forests, Chattogram hill tracts, tea gardens, and garment manufacturing clusters.</li>
</ul>
<p>We handle every logistical, bureaucratic, and creative challenge so your production unit can focus entirely on uncovering powerful human stories.</p>',
        'pricing_note' => 'Documentary packages and line fixing day rates are tailored to crew size, remote travel logistics, and equipment requirements.',
        'faqs' => [
            [
                'q' => 'What is documentary film production?',
                'a' => 'Documentary film production involves researching, investigating, and documenting real events, human journeys, cultural heritage, or socio-economic issues using cinematic storytelling without scripted dramatization.'
            ],
            [
                'q' => 'What fixer services does AR Entertainment provide for foreign crews?',
                'a' => 'We offer end-to-end fixer support: government filming permissions, visa invitation letters, CAAB drone clearances, bilingual fixer personnel, hotel & transportation logistics, cinema equipment rental, and location security.'
            ],
            [
                'q' => 'How much time does an independent documentary take to produce?',
                'a' => 'Depending on depth and scope, short documentaries take 3 to 6 weeks, while feature-length or multi-location investigative films may span several months of research and field shooting.'
            ],
            [
                'q' => 'Can AR Entertainment fly camera drones legally in Bangladesh?',
                'a' => 'Yes. We handle legal Civil Aviation Authority of Bangladesh (CAAB) drone permit applications and employ certified drone operators complying with national airspace safety laws.'
            ],
            [
                'q' => 'What sets AR Entertainment apart in documentary storytelling?',
                'a' => 'Our leadership team includes seasoned professionals with extensive field experience working with BBC Media Action, UNICEF, and international documentary networks. We bring cultural sensitivity, journalistic ethics, and poetic visuals to every narrative.'
            ]
        ],
        'sort_order' => 4,
        'meta_title' => 'Documentary Production & Film Fixer in Bangladesh | AR Entertainment',
        'meta_description' => 'Leading documentary production house & film fixer in Bangladesh. Government permits, drone clearances, RED/Sony equipment hire, and line production by AR Entertainment.'
    ],

    // 5. Theme Song & Brand Anthem
    [
        'title' => 'Theme Song & Brand Anthem Production',
        'slug' => 'theme-song',
        'icon' => 'fa-solid fa-music',
        'short_summary' => 'Original musical compositions, corporate theme songs, brand anthems, and lyrical sonic branding that forge instant emotional connections.',
        'content' => '<h3>Sonic Branding &amp; Corporate Anthem Production</h3>
<p>Music reaches places where words alone cannot. A bespoke brand anthem or theme song embeds your brand into the cultural subconscious of your audience. <strong>AR Entertainment</strong> provides complete sonic branding and theme song production—from lyrical poetry and melody composition to professional studio recording, orchestral arrangement, and music video visualization.</p>
<h4>End-to-End Sonic Engineering</h4>
<ul>
    <li><strong>Lyrical Concept Development:</strong> Renowned lyricists weaving brand ethos, patriotic spirit, institutional heritage, and consumer inspiration into memorable poetry.</li>
    <li><strong>Original Melody Composition:</strong> Versatile music composers crafting catchy hooks and powerful orchestral choruses spanning fusion, rock, orchestral, and traditional folk genres.</li>
    <li><strong>Elite Recording &amp; Voice Talents:</strong> Collaborations with Bangladesh’s leading vocalists, background choruses, and master acoustic instrumentalists.</li>
    <li><strong>Studio Mastering &amp; Spatial Audio:</strong> 5.1 surround sound and Dolby Atmos compliant audio mastering for broadcast, stadium ceremonies, and streaming platforms.</li>
</ul>
<p>Whether for a corporate anniversary, national sports tournament, government celebration, or product launch, our brand anthems inspire mass sing-alongs and brand love.</p>',
        'pricing_note' => 'Theme song audio packages start from ৳180,000 BDT; full audio-visual anthem packages with cinematic music video start from ৳500,000 BDT.',
        'faqs' => [
            [
                'q' => 'What is theme song and brand anthem production?',
                'a' => 'Theme song production is the creation of a proprietary, brand-tailored musical piece—combining concept development, lyric writing, vocal recording, musical arrangement, and audio mastering to establish a distinctive sonic identity.'
            ],
            [
                'q' => 'Why is a theme song essential for corporate branding?',
                'a' => 'A signature melody triggers emotional memory far quicker than visual logos alone. Theme songs unify employees, electrify live events, and give advertising campaigns a cohesive auditory signature that endures for years.'
            ],
            [
                'q' => 'How long does the theme song production process take?',
                'a' => 'Audio composition, lyrical review, studio vocal recording, and final mastering typically take 2 to 4 weeks. Producing an accompanying cinematic music video takes an additional 2 to 3 weeks.'
            ],
            [
                'q' => 'Who owns the intellectual property and copyright of the music?',
                'a' => 'Upon project completion and final payment, 100% of the commercial copyright, master recording rights, and publishing rights are transferred entirely to your company.'
            ],
            [
                'q' => 'Can AR Entertainment produce a music video for the theme song as well?',
                'a' => 'Yes. As a full-service film production house, we regularly direct and produce lavish music videos to accompany theme songs, creating a total audio-visual experience.'
            ]
        ],
        'sort_order' => 5,
        'meta_title' => 'Theme Song & Brand Anthem Production in Bangladesh | AR Entertainment',
        'meta_description' => 'Original corporate theme songs, brand anthems, and sonic branding in Bangladesh. Lyrics, audio composition, master recording, and music videos by AR Entertainment.'
    ],

    // 6. Music Video Production
    [
        'title' => 'Music Video Production',
        'slug' => 'music-video',
        'icon' => 'fa-solid fa-compact-disc',
        'short_summary' => 'Cinematic, visually dazzling music videos featuring creative choreography, stylized studio sets, neon aesthetics, and DaVinci color grading.',
        'content' => '<h3>Visionary Music Video Direction for Artists &amp; Labels</h3>
<p>Music videos are the ultimate playground of visual imagination. <strong>AR Entertainment</strong> collaborates with leading musicians, record labels, OTT platforms, and indie artists to produce visually arresting music videos that captivate audiences and rack up millions of views across YouTube and social platforms.</p>
<h4>Uncompromising Artistic Execution</h4>
<ul>
    <li><strong>Creative Visual Treatments:</strong> Non-linear narratives, surreal visual metaphors, romantic period dramas, and high-octane hip-hop/urban choreography.</li>
    <li><strong>Lighting Design &amp; Atmosphere:</strong> Atmospheric haze, dramatic neon color contrast, moving light rigs, and shadow play engineered by master gaffers.</li>
    <li><strong>Advanced Camera Movement:</strong> Steadicam operators, motorized jib cranes, high-speed tracking vehicles, and high-frame-rate slow motion up to 240fps.</li>
    <li><strong>Stylized Color Grading:</strong> Bespoke film-stock emulation, rich contrast, and vibrant color palettes dialed in DaVinci Resolve Studio.</li>
</ul>
<p>We transform your sound into an unforgettable cinematic visual masterpiece that elevates the artist’s persona and resonates with global music lovers.</p>',
        'pricing_note' => 'Music video packages range from ৳200,000 to ৳1,200,000 BDT based on set construction, dancers, location permits, and VFX requirements.',
        'faqs' => [
            [
                'q' => 'What is music video production?',
                'a' => 'Music video production is the visual interpretation of a musical track through cinematic storytelling, performance capture, artistic choreography, and stylized visual editing to engage fans and promote the artist.'
            ],
            [
                'q' => 'How much does professional music video production cost in Bangladesh?',
                'a' => 'Budgets vary widely based on creative treatment. An indie performance video can be produced starting from ৳200,000 BDT, while lavish narrative shoots with custom studio sets, choreography, and top-tier cast range from ৳500,000 to ৳1,200,000 BDT.'
            ],
            [
                'q' => 'How long does it take to produce a music video from brief to delivery?',
                'a' => 'Typically 2 to 4 weeks, including 1 to 2 weeks of pre-production (storyboarding, styling, rehearsals), 1 to 2 shoot days, and 1 to 2 weeks of editorial, color grading, and visual effects finishing.'
            ],
            [
                'q' => 'Do you provide concept development and styling support?',
                'a' => 'Yes. Our creative directors and costume stylists develop complete visual mood boards, wardrobe treatments, and performance rehearsals aligned with your musical genre and persona.'
            ],
            [
                'q' => 'Can AR Entertainment help with digital music video promotion and distribution?',
                'a' => 'We provide YouTube metadata optimization, thumb-stopping poster design, teaser cutdowns for Instagram Reels and TikTok, and strategic release planning.'
            ]
        ],
        'sort_order' => 6,
        'meta_title' => 'Music Video Production Company in Bangladesh | AR Entertainment',
        'meta_description' => 'Cinematic music video production in Dhaka, Bangladesh. Creative direction, Steadicam, stylized lighting, and DaVinci color grading by AR Entertainment.'
    ],

    // 7. 2D & 3D Animation & VFX
    [
        'title' => '2D & 3D Animation & Visual Effects',
        'slug' => '2d-and-3d-animation',
        'icon' => 'fa-solid fa-cubes',
        'short_summary' => 'Photorealistic 3D product visualizations, fluid 2D motion graphics, character animation, and Hollywood-grade visual effects compositing.',
        'content' => '<h3>Limitless Visual Storytelling Through 2D &amp; 3D Animation</h3>
<p>When live-action cameras cannot capture what the mind envisions, animation breaks every physical barrier. <strong>AR Entertainment</strong> houses a world-class animation and visual effects unit delivering photorealistic 3D CGI, stylized 2D character narratives, dynamic motion graphics, and seamless visual effects (VFX) compositing.</p>
<h4>Comprehensive Animation Capabilities</h4>
<ul>
    <li><strong>3D Product Renders &amp; Exploded Views:</strong> Ultra-precise CAD modeling, ray-traced lighting, and mechanical exploded views ideal for electronics, FMCG packaging, and engineering products.</li>
    <li><strong>2D Character Animation:</strong> Hand-crafted and rigged 2D character storytelling with expressive acting, charming art direction, and warm relatable storylines.</li>
    <li><strong>Motion Graphics &amp; Broadcast Packaging:</strong> Kinetic typography, channel IDs, sleek title sequences, and UI/UX product walkthroughs for software applications.</li>
    <li><strong>VFX Compositing &amp; Clean-Up:</strong> Green-screen chroma keying, CGI set extensions, wire removal, sky replacements, and fluid dynamic simulations (fire, smoke, water).</li>
</ul>
<p>We blend technical rendering precision with cinematic emotion, producing animations that simplify complexity and enchant viewers of all ages.</p>',
        'pricing_note' => 'Animation rates depend on asset complexity and duration, starting from ৳80,000 BDT for 2D motion graphics to ৳450,000+ BDT for photorealistic 3D CGI.',
        'faqs' => [
            [
                'q' => 'What types of animation services does AR Entertainment provide?',
                'a' => 'We offer full-spectrum animation services including 2D vector animation, 3D photorealistic modeling and rendering, character animation, motion graphics, architectural walkthroughs, and visual effects (VFX) compositing.'
            ],
            [
                'q' => 'Which industries benefit most from animated video production?',
                'a' => 'Tech startups, fintech apps, healthcare/pharmaceuticals, heavy engineering, consumer FMCG brands, and educational institutions heavily leverage animation to explain abstract concepts clearly.'
            ],
            [
                'q' => 'How long does it take to produce a 60-second animated video?',
                'a' => 'A 60-second 2D motion graphics video typically takes 2 to 3 weeks. A complex 3D CGI character or photorealistic product simulation takes 3 to 5 weeks from script to final render.'
            ],
            [
                'q' => 'Can animated videos be broadcast on national television channels?',
                'a' => 'Yes. All our animation projects are rendered in full HD or 4K with broadcast-calibrated color spaces and audio mastering ready for TV commercials and billboard displays.'
            ],
            [
                'q' => 'What is the standard production process for an animated video?',
                'a' => 'Our process follows 5 structured steps: 1) Script & voiceover recording, 2) Visual style frame design, 3) Storyboarding & animatic review, 4) Full animation production, 5) Sound design, foley, and final render.'
            ]
        ],
        'sort_order' => 7,
        'meta_title' => '2D & 3D Animated Video Production Company in Bangladesh | AR Entertainment',
        'meta_description' => 'Top 2D & 3D animation, motion graphics, and VFX studio in Dhaka, Bangladesh. Photorealistic CGI, product visualization, and character animation by AR Entertainment.'
    ],

    // 8. Explainer Video & Product DVC
    [
        'title' => 'Animated Explainer & Product Video Production',
        'slug' => 'explainer-video',
        'icon' => 'fa-solid fa-lightbulb',
        'short_summary' => 'Crystal-clear animated explainer videos and digital product demos that transform complex technical offerings into intuitive, engaging customer journeys.',
        'content' => '<h3>Transform Complex Ideas into High-Converting Explainer Videos</h3>
<p>If your customer does not understand your product within 60 seconds, you lose them. <strong>AR Entertainment</strong> specializes in crafting razor-sharp animated explainer videos and digital product showcases that break down software apps, financial services, healthcare platforms, and innovative business models into engaging, easy-to-digest narratives.</p>
<h4>The Proven Explainer Framework</h4>
<ul>
    <li><strong>The Relatable Problem:</strong> Opening with the precise pain point your customer experiences every day to forge immediate emotional empathy.</li>
    <li><strong>The Seamless Solution:</strong> Introducing your product or service as the elegant, friction-free answer with clear UI walkthroughs and visual demonstrations.</li>
    <li><strong>How It Works (Step-by-Step):</strong> A 3-step intuitive breakdown showing how effortless it is to sign up, download, or purchase.</li>
    <li><strong>Unmissable Call to Action (CTA):</strong> A compelling closing prompt directing viewers to download the app, visit your website, or request a quote.</li>
</ul>
<p>Boost your website conversions, reduce customer support calls, and empower your sales team with an explainer video that works 24/7 to pitch your business flawlessly.</p>',
        'pricing_note' => 'Explainer video packages start from ৳70,000 to ৳300,000 BDT based on animation style, script duration (60s vs 90s), and custom voiceover.',
        'faqs' => [
            [
                'q' => 'What is an explainer video?',
                'a' => 'An explainer video is a concise (typically 60 to 90 seconds) animated or live-action video designed to quickly explain a company’s product, service, or business model in an engaging, easy-to-understand format.'
            ],
            [
                'q' => 'Why should businesses invest in explainer videos?',
                'a' => 'Explainer videos drastically improve landing page conversion rates (often by 20% to 80%), improve SEO dwell time, simplify complex customer onboarding, and serve as versatile sales assets for pitch meetings.'
            ],
            [
                'q' => 'What is the ideal duration for an explainer video?',
                'a' => 'Research shows the sweet spot is between 60 and 90 seconds (approximately 130 to 180 spoken words). This provides enough time to establish the problem, explain the solution, and prompt action without losing viewer attention.'
            ],
            [
                'q' => 'How much does an explainer video cost in Bangladesh?',
                'a' => 'Professional 60-second explainer videos with bespoke scriptwriting, professional voiceover, and custom illustration range from ৳70,000 to ৳250,000 BDT at AR Entertainment.'
            ],
            [
                'q' => 'Do you provide professional voiceover artists for explainer videos?',
                'a' => 'Yes. We provide native Bengali (both standard Shuddho and conversational) and native English (American, British, Neutral Accent) voiceover artists with crystal-clear studio recording.'
            ]
        ],
        'sort_order' => 8,
        'meta_title' => 'Explainer Video Production Services in Bangladesh | AR Entertainment',
        'meta_description' => 'High-converting animated explainer videos in Dhaka, Bangladesh. Clear scripts, professional voiceovers, and custom animation by AR Entertainment.'
    ]
];

$stmt = $db->prepare("
    INSERT INTO services (title, slug, icon, short_summary, content, pricing_note, faqs_json, sort_order, status, meta_title, meta_description, created_at, updated_at)
    VALUES (?, ?, ?, ?, ?, ?, ?, ?, 'active', ?, ?, NOW(), NOW())
    ON DUPLICATE KEY UPDATE 
        title=VALUES(title),
        icon=VALUES(icon),
        short_summary=VALUES(short_summary),
        content=VALUES(content),
        pricing_note=VALUES(pricing_note),
        faqs_json=VALUES(faqs_json),
        sort_order=VALUES(sort_order),
        status='active',
        meta_title=VALUES(meta_title),
        meta_description=VALUES(meta_description),
        updated_at=NOW()
");

$success_count = 0;
foreach ($services_batch1 as $svc) {
    $faqs_json = json_encode($svc['faqs'], JSON_UNESCAPED_UNICODE | JSON_PRETTY_PRINT);
    $stmt->execute([
        $svc['title'],
        $svc['slug'],
        $svc['icon'],
        $svc['short_summary'],
        $svc['content'],
        $svc['pricing_note'],
        $faqs_json,
        $svc['sort_order'],
        $svc['meta_title'],
        $svc['meta_description']
    ]);
    $success_count++;
    echo "   ✅ [{$svc['sort_order']}/8] Seeded Service: {$svc['title']} ({$svc['slug']}) - " . count($svc['faqs']) . " FAQs\n";
}

echo "\n========================================================\n";
echo "🏆 BATCH 5.2.1 COMPLETED SUCCESSFULLY!\n";
echo "   - Total Services Seeded in Batch: {$success_count}\n";
echo "   - Current Total in Database: " . $db->query("SELECT count(*) FROM services")->fetchColumn() . "\n";
echo "========================================================\n";
