<?php
/**
 * AR Entertainment - Phase 5.2 Services Seeder (Batch 5.2.3: Services 17–24)
 * 
 * Ingests 8 Production, Fixer, Drone, Industrial & Documentary Services:
 * 17. International Film Production Support & Fixer Services (support-for-international-production)
 * 18. Filming Permits, Customs & Ministry Visa Guidance (filming-permits-and-visa-guidance-bangladesh)
 * 19. Cinema Drone & Heavy-Lift Aerial Cinematography (drone-video)
 * 20. RMG & Textile Factory Video Production (corporate-video-for-garment-and-textile-industry-bangladesh)
 * 21. Corporate Event, Summit & Gala Video Production (event-video-production)
 * 22. Real Estate, Architecture & Infrastructure Video Production (real-estate-video)
 * 23. Executive Interview & Thought Leadership Video Production (interviews)
 * 24. Development Project & NGO Video Documentation (video-documentation)
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
echo "🎬 AR ENTERTAINMENT - SEED SERVICES BATCH 5.2.3 (17–24)\n";
echo "========================================================\n\n";

$db = db();

$services_batch3 = [
    // 17. International Film Production Support & Fixer Services
    [
        'title' => 'International Film Production Support & Fixer Services',
        'slug' => 'support-for-international-production',
        'icon' => 'fa-solid fa-earth-americas',
        'short_summary' => 'Comprehensive line production, location management, customs clearance, and bilingual fixer support in Bangladesh for international film crews, broadcasters, and documentary makers.',
        'content' => '<h3>Premier Line Production &amp; Film Fixer Services in Bangladesh</h3>
<p>Filming in Bangladesh offers unparalleled visual richness—from the world’s longest natural sea beach in Cox’s Bazar to the dense mangrove waterways of the Sundarbans and the bustling historic streets of Old Dhaka. <strong>AR Entertainment</strong> acts as the trusted on-ground production partner for foreign broadcasters, Hollywood/European cinema units, streaming networks, and international documentary filmmakers.</p>
<h4>Comprehensive Foreign Production Capabilities</h4>
<ul>
    <li><strong>Dedicated Bilingual Fixers &amp; Production Managers:</strong> Fluent in English and regional dialects, handling real-time logistics, local community liaisons, and cultural mediation.</li>
    <li><strong>Full Line Production &amp; Crewing:</strong> Providing experienced local directors of photography, sound recordists, gaffers, grip technicians, and production assistants.</li>
    <li><strong>Cinema Equipment Rental Packages:</strong> In-house ARRI Alexa Mini LF, RED V-Raptor, Sony FX9 systems, Cooke/Zeiss cine primes, and heavy-duty grip trucks eliminating excessive international freight fees.</li>
    <li><strong>Nationwide Field Logistics &amp; Security:</strong> Secure 4WD transport, chartered speedboats, armed security details where required, and premium hotel accommodations across all 64 districts.</li>
</ul>
<p>From initial concept feasibility assessments to wrapping on location, AR Entertainment delivers flawless execution and peace of mind for international creators.</p>',
        'pricing_note' => 'Line production and fixer day rates tailored to foreign crew size, equipment manifests, and multi-district expedition travel logistics.',
        'faqs' => [
            [
                'q' => 'What international production support services does AR Entertainment provide in Bangladesh?',
                'a' => 'We provide complete turnkey support including government filming permits, customs ATA Carnet clearance, location scouting, local bilingual fixers, transport & security logistics, cinema equipment rental, casting, and line producing.'
            ],
            [
                'q' => 'How do you assist foreign crews with government filming permits and J-visas?',
                'a' => 'We handle the entire application process with the Ministry of Information and Broadcasting, Ministry of Home Affairs, and Bangladesh foreign missions to ensure timely issuance of Journalist (J) visas and official filming permissions.'
            ],
            [
                'q' => 'Can AR Entertainment provide professional camera equipment and local crew on-site?',
                'a' => 'Yes. We maintain high-end cinema camera packages (ARRI Alexa, RED, Sony Venice/FX9), Cooke/Zeiss primes, drone rigs, wireless video systems, and sound kits along with internationally-trained crew.'
            ],
            [
                'q' => 'Which international networks and film productions has your team supported?',
                'a' => 'Our team members and leadership have facilitated filming projects for global broadcasters, international NGOs (UN, BBC, Discovery contributors), commercial agencies, and independent feature film directors.'
            ],
            [
                'q' => 'How do you handle remote transport, security, and accommodation across Bangladesh?',
                'a' => 'We coordinate air-conditioned 4WD vehicles, domestic flights, riverboat charters, dedicated security liaisons, and vetted hotel bookings ensuring foreign crews remain safe, comfortable, and on schedule.'
            ],
            [
                'q' => 'What is the process for hiring AR Entertainment as our local production partner?',
                'a' => 'Send us your production treatment, shoot dates, location wish list, and crew size. We prepare a comprehensive feasibility assessment, location scout deck, and itemized line production budget within 48 to 72 hours.'
            ]
        ],
        'sort_order' => 17,
        'meta_title' => 'International Film Production Support & Fixer Bangladesh | AR Entertainment',
        'meta_description' => 'Professional line production, fixer services, location scouting, and visa assistance for international film crews filming in Bangladesh by AR Entertainment.'
    ],

    // 18. Filming Permits, Customs & Ministry Visa Guidance
    [
        'title' => 'Filming Permits, Customs & Ministry Visa Guidance',
        'slug' => 'filming-permits-and-visa-guidance-bangladesh',
        'icon' => 'fa-solid fa-passport',
        'short_summary' => 'Expert navigation of Bangladesh Ministry of Information filming permits, NBR ATA Carnet customs clearance, civil aviation drone approvals, and journalist visas.',
        'content' => '<h3>Streamlined Regulatory Approvals &amp; Customs Clearances in Bangladesh</h3>
<p>Navigating governmental bureaucracy, import regulations, and security protocols in a foreign country can cause costly production delays. <strong>AR Entertainment</strong> specializes in fast-track regulatory clearance, liaison with relevant ministries, and customs processing for foreign and local film productions across Bangladesh.</p>
<h4>Government Liaison &amp; Regulatory Services</h4>
<ul>
    <li><strong>Ministry of Information Filming Permissions:</strong> Preparing project dossiers and securing official endorsement from the Ministry of Information &amp; Broadcasting and Ministry of Foreign Affairs.</li>
    <li><strong>Journalist (J-Visa) &amp; Work Authorizations:</strong> Assisting foreign crew members with official sponsorship letters and consular visa coordination.</li>
    <li><strong>National Board of Revenue (NBR) Customs &amp; Carnets:</strong> Temporary import clearance and ATA Carnet processing at Hazrat Shahjalal International Airport (DAC) for specialized cinema gear.</li>
    <li><strong>CAAB Drone Flight Clearances:</strong> Securing authorized flight permits from the Civil Aviation Authority of Bangladesh (CAAB) and local law enforcement for aerial filming.</li>
</ul>
<p>Eliminate legal risks and ensure your production operates with 100% official compliance from the moment your crew lands.</p>',
        'pricing_note' => 'Consultation and permit processing packages starting from ৳40,000 BDT depending on government departments, drone flight zones, and equipment manifests.',
        'faqs' => [
            [
                'q' => 'Do foreign filmmakers need a special permit to shoot in Bangladesh?',
                'a' => 'Yes. Foreign film crews, journalists, and documentarians must obtain official filming approval from the Ministry of Information and Broadcasting and enter on appropriate J-category visas.'
            ],
            [
                'q' => 'How does AR Entertainment assist with Ministry of Information and Broadcasting clearances?',
                'a' => 'We act as your officially registered local sponsor, drafting the formal application, script synopses, location itinerary, and equipment manifests, and following up directly with ministry officials for expedited approval.'
            ],
            [
                'q' => 'What is the process for bringing professional cinema cameras through Dhaka customs (ATA Carnet)?',
                'a' => 'We coordinate with the National Board of Revenue (NBR) and customs airport officials with your ATA Carnet or temporary bond documents to ensure swift, hassle-free customs clearance on arrival and departure.'
            ],
            [
                'q' => 'How do you obtain drone flight approvals from CAAB (Civil Aviation Authority of Bangladesh)?',
                'a' => 'We file official flight coordinates, pilot certifications, and security clearances with CAAB and regional security headquarters to secure legal permission for aerial filming.'
            ],
            [
                'q' => 'How far in advance should we apply for filming permits in Bangladesh?',
                'a' => 'We recommend initiating the permit application process at least 3 to 4 weeks prior to your planned arrival date to accommodate inter-ministerial security reviews.'
            ],
            [
                'q' => 'Can AR Entertainment provide on-site government liaison officers during filming?',
                'a' => 'Yes. When required by authorities, we provide dedicated liaison personnel to accompany the crew on location to ensure smooth interactions with local district administrations and law enforcement.'
            ]
        ],
        'sort_order' => 18,
        'meta_title' => 'Filming Permits & Visa Guidance in Bangladesh | AR Entertainment',
        'meta_description' => 'Official Ministry of Information filming permits, CAAB drone approvals, and customs equipment clearance for foreign film crews in Bangladesh by AR Entertainment.'
    ],

    // 19. Cinema Drone & Aerial Cinematography
    [
        'title' => 'Cinema Drone & Heavy-Lift Aerial Cinematography',
        'slug' => 'drone-video',
        'icon' => 'fa-solid fa-helicopter',
        'short_summary' => 'CAAB-certified heavy-lift drone cinematography carrying RED and ARRI cinema packages, delivering sweeping 6K aerials for commercials, feature films, and mega-infrastructure.',
        'content' => '<h3>Breathtaking Aerial Cinematography from Licensed Drone Pilots</h3>
<p>From soaring panoramas of the Padma Bridge and Meghna river confluences to high-speed dynamic tracking shots of industrial manufacturing plants, aerial cinematography elevates production value instantly. <strong>AR Entertainment</strong> operates enterprise-grade cinema drones piloted by CAAB-licensed flight commanders and seasoned camera operators.</p>
<h4>Heavy-Lift Cinema Drone Capabilities</h4>
<ul>
    <li><strong>Heavy-Lift Multi-Rotor Rigs:</strong> Flying DJI Inspire 3 (8K Full-Frame) and custom heavy-lift Octocopters capable of flying RED V-Raptor and ARRI Alexa Mini LF packages.</li>
    <li><strong>Dual-Operator Cinema Control:</strong> Master flight pilot focused on intricate flight paths while a dedicated camera operator controls framing, focus, and tilt with wireless Master Wheels.</li>
    <li><strong>FPV High-Speed Dynamic Flight:</strong> Custom FPV (First Person View) cinewhoop drones flying seamlessly through factory floors, interior spaces, and rapid chase sequences at 100+ km/h.</li>
    <li><strong>Fully Insured &amp; CAAB Compliant:</strong> Comprehensive public liability flight insurance and rigorous pre-flight safety protocols across urban, industrial, and maritime airspace.</li>
</ul>
<p>Capture Bangladesh from perspectives never seen before with unmatched stability, dynamic range, and cinematic composition.</p>',
        'pricing_note' => 'Professional cinema drone packages start from ৳50,000 to ৳350,000 BDT per day including certified pilot, gimbal operator, CAAB flight insurance, and 6K RAW master exports.',
        'faqs' => [
            [
                'q' => 'What cinema drone camera packages does AR Entertainment deploy?',
                'a' => 'We deploy the DJI Inspire 3 with full-frame 8K Zenmuse X9-8K Air cinema camera, DJI Mavic 3 Pro Cine with Apple ProRes, and custom heavy-lift rigs flying RED V-Raptor / ARRI Alexa Mini LF.'
            ],
            [
                'q' => 'Are your drone pilots licensed and certified by the Civil Aviation Authority of Bangladesh (CAAB)?',
                'a' => 'Yes. All our senior flight pilots hold official CAAB certification and commercial flight authorizations with extensive flight hour logs across diverse Bangladeshi terrain.'
            ],
            [
                'q' => 'Can you fly drones over industrial complexes, rivers, and restricted city zones?',
                'a' => 'Yes, subject to official CAAB and security permissions which AR Entertainment secures on behalf of clients prior to filming.'
            ],
            [
                'q' => 'What safety protocols are followed during aerial shoots?',
                'a' => 'We perform satellite GPS calibration, wind speed testing, perimeter security, return-to-home failsafe checks, and maintain visual observers at all times during flight operations.'
            ],
            [
                'q' => 'How does weather and wind affect aerial cinematography in Bangladesh?',
                'a' => 'Our cinema drones operate in wind speeds up to 12 m/s (26 mph). During monsoon season, our flight team monitors real-time weather radar to plan flights between rain bands safely.'
            ],
            [
                'q' => 'Can we live-stream the drone camera feed to client monitors on the ground?',
                'a' => 'Yes. We provide wireless HDMI/SDI ground station video feeds to client and director monitors in real-time, allowing instant review and creative feedback on set.'
            ]
        ],
        'sort_order' => 19,
        'meta_title' => 'Cinema Drone & Aerial Cinematography in Bangladesh | AR Entertainment',
        'meta_description' => 'Heavy-lift cinema drone filming with RED/ARRI cameras by CAAB-certified pilots in Dhaka, Bangladesh. Sweeping 6K aerials by AR Entertainment.'
    ],

    // 20. RMG & Textile Factory Video Production
    [
        'title' => 'RMG & Textile Factory Video Production',
        'slug' => 'corporate-video-for-garment-and-textile-industry-bangladesh',
        'icon' => 'fa-solid fa-shirt',
        'short_summary' => 'Specialized industrial films for ready-made garment (RMG) exporters and spinning mills, showcasing LEED green factories, modern robotics, ethical compliance, and worker welfare.',
        'content' => '<h3>World-Class Industrial Video Production for Bangladesh RMG Exporters</h3>
<p>Bangladesh is the world’s second-largest apparel exporter, housing the highest concentration of LEED-certified green garment factories on earth. <strong>AR Entertainment</strong> creates prestigious industrial corporate films that showcase advanced manufacturing robotics, zero-discharge dyeing units, fair trade compliance, and worker welfare to international fashion buyers across Europe, the US, and Asia.</p>
<h4>Industrial Filmmaking Tailored for Global Brands</h4>
<ul>
    <li><strong>LEED Green Building &amp; ESG Showcases:</strong> Highlighting rooftop solar arrays, biological effluent treatment plants (ETP), rainwater harvesting, and sustainable carbon-neutral operations.</li>
    <li><strong>Automated Production &amp; Robotics:</strong> Filming high-speed automated cutting tables, computerized circular knitting, laser washing machines, and digital printing lines with dynamic slider motion.</li>
    <li><strong>Ethical Compliance &amp; Worker Welfare:</strong> Capturing on-site medical clinics, daycare centers, fair-price grocery shops, and safe ergonomic working environments that satisfy global audit standards.</li>
    <li><strong>International Buyer Pitch Cuts:</strong> 3-minute executive overview videos, 60s trade fair video loops, and technical capability brochures with English, German, French, and Japanese voiceovers.</li>
</ul>
<p>Position your textile and garment enterprise as a world-class manufacturing partner of choice for top global fashion conglomerates.</p>',
        'pricing_note' => 'Industrial RMG corporate video packages range from ৳250,000 to ৳1,400,000 BDT with factory floor lighting rigs, drone fly-throughs, and export buyer pitch cutdowns.',
        'faqs' => [
            [
                'q' => 'Why is a professional corporate video critical for Bangladeshi RMG and textile exporters?',
                'a' => 'International apparel buyers rarely visit every manufacturing floor in person. A high-production corporate film visually proves your technical capacity, automated precision, and ethical compliance standards during supplier tenders.'
            ],
            [
                'q' => 'How do you highlight ESG compliance and LEED Platinum green building certifications?',
                'a' => 'We create structured visual chapters detailing sustainable raw material sourcing, solar energy generation, zero-hazardous-chemical discharge, and worker empowerment initiatives.'
            ],
            [
                'q' => 'Can filming take place on active factory floors without disrupting production lines?',
                'a' => 'Yes. Our production crews operate with minimal physical footprint, coordinating shift schedules with factory floor managers to film without halting sewing or cutting operations.'
            ],
            [
                'q' => 'Do you provide multilingual voiceovers for European, US, and Asian fashion buyers?',
                'a' => 'Yes. We provide native voiceover narration in International English, German, French, Spanish, Japanese, and Mandarin Chinese.'
            ],
            [
                'q' => 'What is the production timeline for a comprehensive RMG factory corporate film?',
                'a' => 'A full RMG industrial film takes 3 to 5 weeks, encompassing script development, 2 to 3 days on-site factory filming, drone sweeps, motion graphics, and multilingual mastering.'
            ],
            [
                'q' => 'How does AR Entertainment ensure technical safety inside spinning, knitting, and dyeing units?',
                'a' => 'Our crew adheres strictly to factory health and safety protocols, wearing appropriate PPE, heat-resistant gear near steam boilers, and utilizing anti-static equipment in fiber-heavy areas.'
            ]
        ],
        'sort_order' => 20,
        'meta_title' => 'RMG & Garment Factory Video Production in Bangladesh | AR Entertainment',
        'meta_description' => 'Showcase LEED green factories, ethical compliance, and automated textile manufacturing to international fashion buyers with AR Entertainment industrial films.'
    ],

    // 21. Corporate Event, Summit & Gala Video Production
    [
        'title' => 'Corporate Event, Summit & Gala Video Production',
        'slug' => 'event-video-production',
        'icon' => 'fa-solid fa-calendar-check',
        'short_summary' => 'Multi-camera broadcast coverage, live 4K switching, same-day highlight reels, and cinematic aftermovies for international summits, corporate expos, and jubilee galas.',
        'content' => '<h3>Cinematic Event Coverage &amp; Multi-Camera Live Broadcast</h3>
<p>From prestigious international trade summits and banking conventions to annual employee galas and brand product launches, milestone corporate events deserve cinema-grade immortalization. <strong>AR Entertainment</strong> provides end-to-end multi-camera production, high-energy recap films, and live 4K broadcast switching.</p>
<h4>High-Impact Event Production Solutions</h4>
<ul>
    <li><strong>Multi-Camera 4K Live Production:</strong> Deploying 3 to 8 broadcast cameras on jibs, wireless roaming gimbals, and fixed telephoto mounts connected to live video production switchers.</li>
    <li><strong>Same-Day Express Highlight Edits:</strong> Delivering a high-energy, polished 3-minute recap film edited on-site to premiere on main stage LED screens during the closing banquet.</li>
    <li><strong>Direct Audio Console Feeds &amp; Master Ambience:</strong> Crystal-clear multi-track digital audio recorded directly from soundboard outputs supplemented with room ambient microphones for roaring applause.</li>
    <li><strong>Multi-Ratio Social Media Reels:</strong> Dynamic 9:16 vertical reels and 1:1 Instagram posts edited within hours of key speeches for real-time press distribution and social media buzz.</li>
</ul>
<p>Transform fleeting corporate gatherings into enduring marketing assets that demonstrate your industry stature and employee community.</p>',
        'pricing_note' => 'Multi-camera event coverage ranges from ৳80,000 to ৳600,000 BDT with live SDI multi-cam switching, wireless roaming gimbals, and 24-hour express highlight edits.',
        'faqs' => [
            [
                'q' => 'What types of corporate events does AR Entertainment cover?',
                'a' => 'We cover international business summits, industry expos, corporate annual general meetings (AGMs), company jubilees, product launches, award galas, and tech hackathons.'
            ],
            [
                'q' => 'Can you provide same-day highlight video edits for gala closing ceremonies?',
                'a' => 'Yes. We deploy an on-site post-production editor who cuts, color grades, and sound-masters a high-impact 2-to-3 minute highlight video to play on the main stage before the event concludes.'
            ],
            [
                'q' => 'How many cameras and operators are deployed for large-scale conferences?',
                'a' => 'Depending on event scale, we deploy from 2 roaming cameras for intimate banquets up to 8 cameras (including crane jibs, wireless steadicams, and fixed stage angles) for multi-thousand attendee summits.'
            ],
            [
                'q' => 'Do you support multi-platform live streaming to YouTube, Facebook, and Zoom?',
                'a' => 'Yes. We provide bonded cellular / optical live streaming systems that broadcast pristine 1080p/4K feeds with custom lower-thirds and branding to multiple online platforms simultaneously.'
            ],
            [
                'q' => 'How do you capture high-quality audio from keynote speakers and panel mics?',
                'a' => 'We interface directly with the venue’s digital audio mixing console via multi-track Dante or XLR feeds, ensuring zero background echo or muffled voice recordings.'
            ],
            [
                'q' => 'What raw footage and edited deliverables are provided after the event?',
                'a' => 'Deliverables include full unedited speech master files, a 3-minute cinematic aftermovie, a 60-second social recap reel, and full high-resolution raw footage archives on hard drive.'
            ]
        ],
        'sort_order' => 21,
        'meta_title' => 'Corporate Event & Summit Video Production in Bangladesh | AR Entertainment',
        'meta_description' => 'Multi-camera 4K event filming, same-day gala highlight reels, and live switching for corporate summits and conferences in Dhaka by AR Entertainment.'
    ],

    // 22. Real Estate, Architecture & Infrastructure Video Production
    [
        'title' => 'Real Estate, Architecture & Infrastructure Video Production',
        'slug' => 'real-estate-video',
        'icon' => 'fa-solid fa-city',
        'short_summary' => 'Ultra-luxurious architectural walkthroughs, twilight cinematography, lifestyle talent staging, and mega-infrastructure progress videos for premier real estate developers.',
        'content' => '<h3>Cinematic Property &amp; Architectural Video Production</h3>
<p>In high-end real estate and architectural development, emotional visual allure drives multi-million taka property acquisitions. <strong>AR Entertainment</strong> crafts breathtaking visual showcases for luxury condominiums, commercial towers, gated townships, and mega-infrastructure developments across Bangladesh.</p>
<h4>Luxury Architectural Filmmaking Services</h4>
<ul>
    <li><strong>Twilight &amp; Golden Hour Cinematography:</strong> Precision scheduled filming capturing warm interior glow and dramatic dusk skies with ultra-wide cinema lenses and motorized camera dollies.</li>
    <li><strong>Lifestyle Staging &amp; Talent Casting:</strong> Incorporating professional actors and lifestyle models to depict aspirational family living, wellness amenities, and rooftop luxury.</li>
    <li><strong>3D CGI Integration &amp; Visual Tracking:</strong> Superimposing 3D architectural CGI renders onto actual drone footage to visualize future phases, skyline views, and infrastructure access roads.</li>
    <li><strong>Multi-Channel Sales Assets:</strong> Full 3-minute investor presentations, 60s TV commercial cuts, and 9:16 vertical walkthroughs optimized for Facebook and Instagram property lead generation.</li>
</ul>
<p>Elevate buyer perception, shorten sales cycles, and showcase architectural elegance with AR Entertainment’s cinema-grade property films.</p>',
        'pricing_note' => 'Architectural and residential video packages range from ৳120,000 to ৳800,000 BDT featuring motorized sliders, twilight drone passes, 3D CGI floorplan integration, and model staging.',
        'faqs' => [
            [
                'q' => 'How do cinematic real estate videos increase property buyer conversions?',
                'a' => 'Cinematic videos create an emotional connection with affluent buyers, allowing them to visualize the lifestyle, spatial flow, and prestige of a property far more effectively than static photographs.'
            ],
            [
                'q' => 'Do you provide lifestyle models and interior staging for luxury apartment shoots?',
                'a' => 'Yes. We provide complete interior styling consultation and professional model casting to bring living rooms, infinity pools, and executive lounges to life with authentic human presence.'
            ],
            [
                'q' => 'Can you integrate 3D architectural renders and motion graphic callouts for under-construction projects?',
                'a' => 'Yes. We match 3D CAD/BIM renders seamlessly with real drone aerials and camera tracks to show prospective investors exactly what the completed project will look like.'
            ],
            [
                'q' => 'What camera techniques are used to make residential interiors look spacious and cinematic?',
                'a' => 'We use ultra-wide zero-distortion cinema primes, motorized sliders, electronic gimbals, and balanced soft-box interior lighting that illuminates spaces naturally without harsh shadows.'
            ],
            [
                'q' => 'How long does a typical luxury property or mega-commercial shoot take?',
                'a' => 'A residential model apartment shoot takes 1 to 2 production days, while comprehensive multi-acre township developments require 3 to 4 days across dawn, midday, and twilight.'
            ],
            [
                'q' => 'Can you deliver vertical video formats optimized for Instagram Reels and Facebook property ads?',
                'a' => 'Yes. Every real estate package includes 9:16 vertical cuts with dynamic price callouts and contact buttons tailored for high-converting social media ad campaigns.'
            ]
        ],
        'sort_order' => 22,
        'meta_title' => 'Real Estate & Architectural Video Production in Bangladesh | AR Entertainment',
        'meta_description' => 'Cinematic property walkthroughs, drone aerials, and luxury apartment video production in Dhaka, Bangladesh by AR Entertainment.'
    ],

    // 23. Executive Interview & Thought Leadership Video Production
    [
        'title' => 'Executive Interview & Thought Leadership Video Production',
        'slug' => 'interviews',
        'icon' => 'fa-solid fa-comments',
        'short_summary' => 'Prestige multi-camera executive interviews, C-suite thought leadership spotlights, founder origin stories, and customer success case studies with broadcast lighting.',
        'content' => '<h3>Authoritative C-Suite Interviews &amp; Thought Leadership Spotlights</h3>
<p>A compelling executive interview positions company leadership at the forefront of their industry, establishing trust with investors, partners, and enterprise clients. <strong>AR Entertainment</strong> produces broadcast-caliber multi-camera interview films that showcase leadership intelligence, corporate vision, and authentic personality.</p>
<h4>Prestige Executive Interview Solutions</h4>
<ul>
    <li><strong>Cinematic 3-Camera Master Setup:</strong> Filming with shallow depth-of-field cinema cameras providing wide establishing, tight emotional focus, and dynamic profile angles.</li>
    <li><strong>Studio Lighting &amp; Soundproofing:</strong> Soft wrap-around key lighting, subtle rim hair lights, and studio-grade wireless lavaliers and boom microphones eliminating room flutter.</li>
    <li><strong>Experienced Interview Facilitation:</strong> Guiding C-suite executives and board directors naturally through conversational prompts, ensuring articulate delivery without sounding rehearsed.</li>
    <li><strong>Executive Personal Branding Snippets:</strong> Extracting 30s to 60s punchy thought leadership soundbites formatted for LinkedIn video, investor pitch decks, and annual report QR codes.</li>
</ul>
<p>Build authoritative market influence and humanize your corporate leadership with prestige video storytelling.</p>',
        'pricing_note' => 'Executive interview packages range from ৳60,000 to ৳350,000 BDT per session including studio acoustic lighting setups, wireless lavaliers, and stylized kinetic subtitle cutdowns.',
        'faqs' => [
            [
                'q' => 'What makes an executive interview video look cinematic and authoritative?',
                'a' => 'We use 3-point cinematic lighting, 4K cinema cameras with shallow depth of field (blurry background), warm acoustic studio audio, and professional multi-camera editing with B-roll cutaways.'
            ],
            [
                'q' => 'How do your interviewers make camera-shy CEOs and stakeholders feel comfortable?',
                'a' => 'We conduct relaxed conversational interviews rather than rigid interrogations. Our directors guide executives through key points naturally, conducting multiple relaxed takes until they feel confident.'
            ],
            [
                'q' => 'Where can interview shoots be conducted?',
                'a' => 'We can transform your corporate boardroom or executive office into a film set, or host the shoot in our dedicated soundproof film studio in Dhaka.'
            ],
            [
                'q' => 'Do you provide teleprompters and question prompt monitors?',
                'a' => 'Yes. We provide high-brightness glass beam teleprompters with professional software operators whenever verbatim script recitation is required.'
            ],
            [
                'q' => 'Can you extract short social media clips and quote cards for LinkedIn from the interview?',
                'a' => 'Yes. We produce vertical 9:16 short clips with stylized karaoke-style animated captions and branded frames designed for maximum engagement on LinkedIn and Twitter.'
            ],
            [
                'q' => 'How quickly can an executive interview video be delivered?',
                'a' => 'Standard delivery is within 5 to 7 business days, with express 48-hour delivery available for urgent shareholder announcements or crisis communications.'
            ]
        ],
        'sort_order' => 23,
        'meta_title' => 'Executive Interview & Thought Leadership Videos | AR Entertainment',
        'meta_description' => 'Professional multi-camera executive interviews, C-suite spotlights, and founder stories in Dhaka, Bangladesh by AR Entertainment.'
    ],

    // 24. Development Project & NGO Video Documentation
    [
        'title' => 'Development Project & NGO Video Documentation',
        'slug' => 'video-documentation',
        'icon' => 'fa-solid fa-hand-holding-heart',
        'short_summary' => 'Empathetic, human-centric documentary storytelling and impact evaluation films for international development agencies, UN missions, climate initiatives, and grassroots NGOs.',
        'content' => '<h3>Empathetic Humanitarian &amp; NGO Project Video Documentation</h3>
<p>For over two decades, the production leadership at <strong>AR Entertainment</strong> has traveled to the deepest corners of Bangladesh to document transformative humanitarian interventions, climate adaptation initiatives, public health campaigns, and women’s economic empowerment programs.</p>
<h4>Ethical, Impact-Driven Field Documentation</h4>
<ul>
    <li><strong>Human-Centric Beneficiary Storytelling:</strong> Capturing honest, dignified, and emotionally resonant stories of real individuals and communities whose lives are changed by development programs.</li>
    <li><strong>Rigorous Consent &amp; Child Protection Protocols:</strong> Strictly adhering to international NGO safeguarding guidelines, informed consent documentation, and ethical filming standards.</li>
    <li><strong>Remote Field Expedition Readiness:</strong> Self-sufficient production units equipped for rugged off-grid filming in riverine char islands, Haor wetlands, refugee settlements, and remote hill tracts.</li>
    <li><strong>Donor-Compliance &amp; Policy Advocacy Masters:</strong> 5-to-10 minute comprehensive impact evaluation documentaries, 2-minute social advocacy films, and donor presentation reels with localized dialect subtitles.</li>
</ul>
<p>Turn project metrics and data into unforgettable human stories that inspire donors, influence policy makers, and mobilize global support.</p>',
        'pricing_note' => 'NGO documentation packages tailored to field travel days, remote district logistics, beneficiary consent protocols, and donor-mandated report standards.',
        'faqs' => [
            [
                'q' => 'What is development project video documentation?',
                'a' => 'It is the structured filming of humanitarian and development initiatives—recording baseline conditions, project interventions, community feedback, and tangible social impact for donors and stakeholders.'
            ],
            [
                'q' => 'How does AR Entertainment ensure ethical storytelling and beneficiary consent?',
                'a' => 'We obtain documented written and video consent from all participants, treat vulnerable populations with utmost dignity, and never stage or misrepresent community realities.'
            ],
            [
                'q' => 'Can your production crews travel to remote rural districts, char islands, and disaster response areas?',
                'a' => 'Yes. Our crews are seasoned expedition filmmakers equipped with portable power generators, drone kits, waterproof pelican cases, and rugged 4WD transport.'
            ],
            [
                'q' => 'Do you adhere to international development agency donor guidelines (UN, USAID, JICA, EU)?',
                'a' => 'Yes. Our team is well-versed in donor branding guidelines, child safeguarding policies, environmental compliance, and high-standard audio-visual deliverables.'
            ],
            [
                'q' => 'Can you produce both long-form documentary reports and short social impact stories?',
                'a' => 'Yes. We deliver full 10-to-15 minute documentary retrospectives accompanied by punchy 90-second social media stories formatted for global advocacy campaigns.'
            ],
            [
                'q' => 'How do you handle multilingual translation and localized dialect subtitles?',
                'a' => 'Our linguistic team transcribes regional dialects (e.g. Sylheti, Chittagonian, Barisal, Rohingya) accurately into English and Standard Bangla subtitles.'
            ]
        ],
        'sort_order' => 24,
        'meta_title' => 'NGO & Development Project Video Documentation | AR Entertainment',
        'meta_description' => 'Impactful NGO documentary films, donor reporting videos, and humanitarian storytelling across Bangladesh by AR Entertainment.'
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
foreach ($services_batch3 as $svc) {
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
echo "🏆 BATCH 5.2.3 COMPLETED SUCCESSFULLY!\n";
echo "   - Total Services Seeded in Batch: {$seeded_count}\n";
echo "   - Current Total in Database: {$total_services}\n";
echo "========================================================\n";
