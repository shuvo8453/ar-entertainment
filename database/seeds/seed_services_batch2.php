<?php
/**
 * AR Entertainment - Phase 5.2 Services Seeder (Batch 5.2.2: Services 9–16)
 * 
 * Ingests 8 AI, Audio, Music & Visual Storytelling Services:
 * 9.  AI Video Content Creation & Generative Media (ai-video-content-creation)
 * 10. AI Video Localisation & Multilingual Voice Dubbing (ai-video-localisation-dubbing)
 * 11. AI Training & Avatar Video Production (ai-training-avatar-video-production)
 * 12. AI Music, Jingle & Brand Anthem Development (ai-music-jingle-brand-anthem-development)
 * 13. Commercial Jingle & Radio Audio Production (jingle-production)
 * 14. Brand Songwriting & Commercial Lyrics Development (lyrics-development)
 * 15. Brand Anthem Video & Corporate Theme Song Production (brand-anthem-video)
 * 16. Animated Music Video & Visual Storytelling (animated-music-video)
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
echo "🎬 AR ENTERTAINMENT - SEED SERVICES BATCH 5.2.2 (9–16)\n";
echo "========================================================\n\n";

$db = db();

$services_batch2 = [
    // 9. AI Video Content Creation
    [
        'title' => 'AI Video Content Creation & Generative Media',
        'slug' => 'ai-video-content-creation',
        'icon' => 'fa-solid fa-robot',
        'short_summary' => 'Next-generation generative AI video production combining Midjourney, Runway Gen-3, Kling AI, and Sora workflows with seasoned human cinematic storytelling in Bangladesh.',
        'content' => '<h3>Pioneering Generative AI Video Production in Bangladesh</h3>
<p>At <strong>AR Entertainment</strong>, artificial intelligence is not a shortcut—it is a groundbreaking creative superpower. We fuse state-of-the-art generative video models with twenty years of human cinematic direction, providing brands, agencies, and enterprises with hyper-realistic visuals, photorealistic digital worlds, and conceptual storytelling previously impossible on conventional budgets.</p>
<h4>End-to-End Generative AI Production Pipeline</h4>
<ul>
    <li><strong>Neural Concepting &amp; Style Framing:</strong> Custom diffusion modeling using Midjourney v6 and Stable Diffusion XL to craft photorealistic concept frames and brand-aligned visual aesthetics.</li>
    <li><strong>Motion Synthesis &amp; Camera Simulation:</strong> Generating temporal motion passes via Runway Gen-3 Alpha, Kling AI, Luma Dream Machine, and OpenAI Sora with precise virtual camera moves and physics.</li>
    <li><strong>Character Consistency &amp; LoRA Training:</strong> Training bespoke LoRA neural checkpoints on brand talent, corporate ambassadors, and product packaging for absolute cross-scene visual fidelity.</li>
    <li><strong>Cinematic Finishing &amp; 4K Upscaling:</strong> Topaz Video AI neural upscaling, optical flow interpolation, DaVinci Resolve color grading, and multi-track audio foley for broadcast compliance.</li>
</ul>
<p>Whether creating surreal fantasy landscapes for FMCG commercials, visualizing complex architectural futures, or generating agile social ad variations, AR Entertainment leads Bangladesh in ethical, broadcast-grade AI video production.</p>',
        'pricing_note' => 'AI video packages range from ৳35,000 to ৳350,000 BDT based on generative shot count, custom LoRA training, and 4K neural finishing.',
        'faqs' => [
            [
                'q' => 'What is AI video content creation?',
                'a' => 'AI video content creation utilizes advanced deep learning generative models (such as Runway Gen-3, Kling, Midjourney, and Sora) to generate photorealistic video sequences, characters, environments, and visual effects from structured text prompts and image references.'
            ],
            [
                'q' => 'How much does AI video production cost in Bangladesh?',
                'a' => 'At AR Entertainment, AI video production packages start from ৳35,000 BDT for agile social media clips and scale up to ৳350,000+ BDT for comprehensive multi-scene commercial campaigns featuring custom-trained character models.'
            ],
            [
                'q' => 'How does AR Entertainment blend AI technology with traditional film direction?',
                'a' => 'We treat AI as an advanced optical tool. Every AI-generated shot is framed with professional cinematic composition, directed under the guidance of Ogilvy-trained director Azizul Hoque Shiplu, and finished in DaVinci Resolve with custom sound design.'
            ],
            [
                'q' => 'What AI video models and software does AR Entertainment utilize?',
                'a' => 'Our proprietary production pipeline leverages Midjourney v6, Runway Gen-3 Alpha, Kling AI, Stable Video Diffusion, ElevenLabs voice synthesis, and Topaz Video AI neural enhancement suites.'
            ],
            [
                'q' => 'Can AI videos replace traditional commercial shoots?',
                'a' => 'AI video excels at visualizing impossible worlds, sci-fi concepts, international locations, and rapid campaign testing. For tactile product interactions or local celebrity appearances, we frequently produce hybrid films combining live-action filming with AI generative backdrops.'
            ],
            [
                'q' => 'What is the typical turnaround time for an AI video campaign?',
                'a' => 'Standard AI video projects are completed within 5 to 10 business days. For rapid digital launches or social newsjacking, express deliveries within 48 to 72 hours can be accommodated.'
            ]
        ],
        'sort_order' => 9,
        'meta_title' => 'AI Video Content Creation in Bangladesh | AR Entertainment',
        'meta_description' => 'Cutting-edge AI video production in Dhaka, Bangladesh by AR Entertainment. Generative AI commercials, digital humans, and VFX starting from ৳35,000 BDT.'
    ],

    // 10. AI Video Localisation & Multilingual Dubbing
    [
        'title' => 'AI Video Localisation & Multilingual Voice Dubbing',
        'slug' => 'ai-video-localisation-dubbing',
        'icon' => 'fa-solid fa-language',
        'short_summary' => 'Precision neural voice cloning, automated phoneme-level lip synchronization, and on-screen visual translation across Bangla, English, Arabic, and 30+ global languages.',
        'content' => '<h3>Break Language Barriers with Neural Voice Cloning &amp; Lip-Sync Dubbing</h3>
<p>In an interconnected global economy, your brand’s video content must speak directly to regional and international audiences. <strong>AR Entertainment</strong> delivers AI-powered video localisation that goes far beyond traditional subtitles. We replicate the original speaker’s exact vocal timbre, emotional cadence, and speaking rhythm in over 30 global languages while synchronizing on-screen mouth movements in real time.</p>
<h4>Enterprise Multilingual Localisation Capabilities</h4>
<ul>
    <li><strong>Acoustic Voice Cloning:</strong> Exact neural replication of your original presenter or executive’s voice tone, pitch, and accent across foreign languages.</li>
    <li><strong>Phoneme-Accurate Lip Synchronization:</strong> AI video generative warping that adjusts the speaker’s mouth and jaw movements to match the translated spoken phonemes naturally.</li>
    <li><strong>Cultural Localization &amp; Idiom Adaptation:</strong> Native human linguistic supervision ensuring localized idioms, terminology, and cultural nuances resonate authentically in each target market.</li>
    <li><strong>Visual Screen Replacement:</strong> Automated translation and graphical replacement of on-screen titles, lower-thirds, documents, and product packaging labels.</li>
</ul>
<p>From multinational NGOs training field workers across South Asia to export manufacturers pitching European buyers, AR Entertainment scales your video reach across the globe effortlessly.</p>',
        'pricing_note' => 'Localisation packages range from ৳25,000 to ৳180,000 BDT per project based on minute length, voice matching fidelity, and visual screen replacement.',
        'faqs' => [
            [
                'q' => 'What is AI video localisation and dubbing?',
                'a' => 'AI video localisation is the process of translating video speech into other languages using voice cloning technology, while simultaneously adjusting the on-screen speaker\'s lip movements to match the new language seamlessly.'
            ],
            [
                'q' => 'How realistic is AI voice cloning and lip-syncing?',
                'a' => 'Our neural dubbing models achieve broadcast-grade realism, preserving the original speaker\'s pitch, emotional inflection, and natural breathing rhythm so audiences experience the content as if originally filmed in that language.'
            ],
            [
                'q' => 'Which languages and regional dialects are supported?',
                'a' => 'We support over 30 languages including Standard Bangla, Sylheti/Chittagonian dialects, English (US/UK/Australian), Arabic, Hindi, Mandarin, Spanish, French, German, and Japanese.'
            ],
            [
                'q' => 'Can you replace on-screen text and graphics in the video during localisation?',
                'a' => 'Yes. Our post-production team extracts on-screen text layers, translates lower-thirds, charts, and captions, and re-composites them seamlessly into the localized video masters.'
            ],
            [
                'q' => 'How long does it take to localize a video into multiple languages?',
                'a' => 'A standard 3-minute corporate or training video can be translated, dubbed, lip-synced, and mastered into 3 to 5 languages within 3 to 5 business days.'
            ],
            [
                'q' => 'Why choose AR Entertainment for AI video translation over generic software?',
                'a' => 'Generic software produces robotic, unnatural cadences. AR Entertainment combines advanced neural engines with human linguistic editors and professional studio audio mixing to ensure flawless cultural and commercial delivery.'
            ]
        ],
        'sort_order' => 10,
        'meta_title' => 'AI Video Localisation & Dubbing in Bangladesh | AR Entertainment',
        'meta_description' => 'Multilingual AI voice cloning and lip-sync video localisation in Dhaka by AR Entertainment. Expand your brand globally in Bangla, English, Arabic, and 30+ languages.'
    ],

    // 11. AI Training & Avatar Video Production
    [
        'title' => 'AI Training & Avatar Video Production',
        'slug' => 'ai-training-avatar-video-production',
        'icon' => 'fa-solid fa-user-tie',
        'short_summary' => 'Photorealistic custom digital avatars for corporate LMS training, employee onboarding, product demonstrations, and automated multilingual executive communications.',
        'content' => '<h3>Scalable Corporate Learning &amp; Executive Digital Twin Avatars</h3>
<p>Modern enterprises lose hundreds of hours coordinating studio time, teleprompters, and executive schedules for recurring training and onboarding videos. <strong>AR Entertainment</strong> solves this by creating photorealistic digital avatars and automated video pipelines that transform raw text scripts into studio-grade training modules in minutes.</p>
<h4>The Enterprise Digital Avatar Advantage</h4>
<ul>
    <li><strong>Custom Executive Cloning:</strong> Filming a single 15-minute studio calibration session to build an exclusive digital twin of your CEO, trainer, or brand ambassador.</li>
    <li><strong>Infinite Script Updates:</strong> Update compliance policies, HR handbooks, or software tutorials simply by editing text—eliminating costly re-shoots.</li>
    <li><strong>LMS &amp; SCORM Integration:</strong> Video modules exported in SCORM-compliant 1080p/4K formats tailored for corporate Learning Management Systems.</li>
    <li><strong>Multi-Presenter Diversity:</strong> Access a library of over 100 photorealistic digital presenters spanning various ethnicities, age groups, and professional attire.</li>
</ul>
<p>Trusted by financial institutions, RMG conglomerates, and healthcare organizations across Bangladesh to scale institutional knowledge seamlessly.</p>',
        'pricing_note' => 'Enterprise avatar packages range from ৳45,000 to ৳400,000 BDT including custom executive twin cloning, studio green screen training, and automated script-to-video pipelines.',
        'faqs' => [
            [
                'q' => 'What is an AI training avatar video?',
                'a' => 'An AI training avatar video is an educational or corporate presentation video featuring a lifelike digital human presenter driven by AI that speaks text scripts naturally with realistic facial expressions and body language.'
            ],
            [
                'q' => 'Can we clone our company\'s CEO or brand spokesperson as an AI avatar?',
                'a' => 'Yes. We film a brief high-resolution studio calibration session with your spokesperson and generate an exclusive digital twin that can deliver future company announcements and training modules on demand.'
            ],
            [
                'q' => 'How does AI avatar video production reduce training and onboarding costs?',
                'a' => 'It eliminates the recurring costs of booking studio stages, camera crews, lighting setups, and executive travel. Whenever company policies change, you update the script and export fresh videos in minutes.'
            ],
            [
                'q' => 'What formats and resolutions are delivered for corporate LMS systems?',
                'a' => 'We deliver MP4 (H.264/H.265), ProRes masters, and interactive SCORM-compatible video packages formatted for popular LMS platforms like Moodle, TalentLMS, and enterprise SAP suites.'
            ],
            [
                'q' => 'Can we update the voiceover or script later without re-filming?',
                'a' => 'Absolutely. That is the core benefit. Any module can be updated with new text, statistics, or branding at a fraction of the original production timeline.'
            ],
            [
                'q' => 'How secure is the proprietary data and likeness of our company executives?',
                'a' => 'We enforce strict legal NDAs, biometric data encryption, and exclusive access protocols. Your executive avatars and proprietary training scripts are never shared or used outside your enterprise account.'
            ]
        ],
        'sort_order' => 11,
        'meta_title' => 'AI Training & Avatar Video Production Bangladesh | AR Entertainment',
        'meta_description' => 'Scalable AI avatar video production for corporate training, compliance, and onboarding in Dhaka, Bangladesh by AR Entertainment. Custom digital twins & LMS videos.'
    ],

    // 12. AI Music, Jingle & Brand Anthem Development
    [
        'title' => 'AI Music, Jingle & Brand Anthem Development',
        'slug' => 'ai-music-jingle-brand-anthem-development',
        'icon' => 'fa-solid fa-wand-magic-sparkles',
        'short_summary' => 'Neural audio synthesis and generative musical ideation mastered with live studio instrumentation, creating unforgettable sonic branding and commercial jingles.',
        'content' => '<h3>Intelligent Sonic Branding &amp; AI-Powered Music Composition</h3>
<p>Sonic identity is the most memorable sensory touchpoint of modern branding. <strong>AR Entertainment</strong> merges neural audio synthesis algorithms with master studio musicianship to develop iconic commercial jingles, brand sound logos, and corporate anthems at unprecedented speed and artistic precision.</p>
<h4>The Hybrid AI Music Production Workflow</h4>
<ul>
    <li><strong>Neural Melody Exploration:</strong> Generating hundreds of unique harmonic progressions, melodic hooks, and rhythmic structures tuned to your brand personality.</li>
    <li><strong>Live Studio Enhancement:</strong> Overdubbing synthetic tracks with live acoustic guitars, brass sections, traditional Bengali instruments (Dotara, Flute, Tabla), and pro vocalists.</li>
    <li><strong>Sonic Logo &amp; Audio UI Kits:</strong> Developing 2-to-5 second sonic watermarks, app notification sounds, and brand mnemonic signatures.</li>
    <li><strong>Dolby Atmos &amp; Stereo Mastering:</strong> Broadcast-grade mixing conforming to EBU R128 loudness standards for pristine playback across TV, radio, Spotify, and cinema halls.</li>
</ul>
<p>Accelerate your musical campaign development without ever compromising on acoustic warmth, emotional depth, or commercial copyright protection.</p>',
        'pricing_note' => 'AI-assisted sonic branding packages range from ৳30,000 to ৳220,000 BDT based on vocal synthesis layers, live instrumental tracking, and full broadcast stem delivery.',
        'faqs' => [
            [
                'q' => 'How does AI music and jingle development work?',
                'a' => 'We use neural music models to explore hundreds of melody candidates and chords matching your brief. Our sound engineers then arrange, track live instruments over the best melodies, record professional vocalists, and master the tracks in our recording studio.'
            ],
            [
                'q' => 'Are AI-generated jingles and songs free from copyright issues for commercial advertising?',
                'a' => 'Yes. Every track produced by AR Entertainment undergoes strict copyright screening and is delivered with 100% full commercial ownership rights and master ownership transfer.'
            ],
            [
                'q' => 'What is the benefit of using AI in sonic branding versus traditional composition?',
                'a' => 'Speed and creative exploration. Instead of waiting weeks for a single musical demo, AI enables us to present 5 to 10 distinct musical directions in 48 hours before entering the studio for final recording.'
            ],
            [
                'q' => 'Do you incorporate live musicians and studio instruments with AI tracks?',
                'a' => 'Yes, always. We believe pure AI music lacks organic soul. Our hybrid approach ensures that live instruments (guitars, flute, percussions) and real vocalists give every track acoustic warmth and emotional power.'
            ],
            [
                'q' => 'What audio stems and deliverables are provided upon project completion?',
                'a' => 'We provide full WAV master tracks, 30s TV cuts, 15s radio cuts, 6s sonic mnemonics, instrumental backing tracks, isolated vocal stems, and loopable background cues.'
            ],
            [
                'q' => 'Can AR Entertainment create regional Bangladeshi folk or fusion music styles with AI?',
                'a' => 'Yes. We specialize in blending contemporary global pop/cinematic production with authentic Bangladeshi folk traditions, Baul rhythms, and modern electronic fusion.'
            ]
        ],
        'sort_order' => 12,
        'meta_title' => 'AI Music & Jingle Development Bangladesh | AR Entertainment',
        'meta_description' => 'AI-powered commercial jingle, sonic branding, and anthem development in Dhaka by AR Entertainment. Rapid musical composition mastered with live studio acoustics.'
    ],

    // 13. Commercial Jingle & Radio Audio Production
    [
        'title' => 'Commercial Jingle & Radio Audio Production',
        'slug' => 'jingle-production',
        'icon' => 'fa-solid fa-microphone-lines',
        'short_summary' => 'Catchy, culturally resonant 10-to-30-second musical hooks and broadcast audio spots composed by acclaimed music directors for television, radio, and digital channels.',
        'content' => '<h3>Broadcast-Quality Commercial Jingles &amp; Audio Advertising</h3>
<p>A great advertising jingle becomes an indelible part of national culture. <strong>AR Entertainment</strong> has composed and produced memorable broadcast jingles that have resonated in millions of Bangladeshi households across decades of commercial advertising.</p>
<h4>Full-Service Audio Commercial Production</h4>
<ul>
    <li><strong>Creative Melody Architecture:</strong> Composing sticky, memorable melodic hooks designed for instantaneous recall and long-term brand equity.</li>
    <li><strong>Celebrity &amp; Session Vocalists:</strong> Casting renowned playback artists, energetic commercial voice talents, and versatile choir harmonies.</li>
    <li><strong>Acoustic &amp; Electronic Instrumentation:</strong> Recorded in top-tier acoustic tracking rooms featuring vintage analog preamps, Neumann microphones, and live rhythm sections.</li>
    <li><strong>Multi-Format Broadcast Mastering:</strong> Delivered in 10s, 15s, 30s, and 60s cuts calibrated for FM radio networks, TV commercial pods, podcast sponsorships, and digital video bumpers.</li>
</ul>
<p>From lively FMCG product anthems to sophisticated financial institution mnemonics, our jingles drive high consumer recall and brand affinity.</p>',
        'pricing_note' => 'Radio and commercial jingle packages range from ৳60,000 to ৳450,000 BDT with celebrity vocalists, live tracking, and Dolby-calibrated stereo master tracks.',
        'faqs' => [
            [
                'q' => 'What makes a successful commercial jingle in Bangladesh?',
                'a' => 'A successful jingle combines a simple, hummable melodic hook, rhythmic alignment with the brand name, emotional cultural resonance, and clear vocal articulation that cuts through background noise.'
            ],
            [
                'q' => 'How long should an advertising jingle be?',
                'a' => 'Standard commercial jingles are composed in 15-second and 30-second lengths, with 5-second to 10-second abbreviated hooks tailored for radio bumpers and digital pre-roll ads.'
            ],
            [
                'q' => 'Does AR Entertainment handle lyric writing, vocal casting, and final broadcast mixing?',
                'a' => 'Yes. We provide complete end-to-end production including lyrical concept, musical composition, singer auditions, studio tracking, sound effects foley, and broadcast mastering.'
            ],
            [
                'q' => 'How do you ensure our jingle stands out across TV, radio, and digital channels?',
                'a' => 'Our mastering engineers calibrate dynamic range and frequency curves specifically for consumer phone speakers, car radios, and television soundbars, ensuring crystal-clear punch across all devices.'
            ],
            [
                'q' => 'Who owns the copyright and broadcasting rights to the completed jingle?',
                'a' => 'Upon project completion and final payment, 100% of the commercial copyright and synchronization rights are legally assigned to your company in perpetuity.'
            ],
            [
                'q' => 'What is the turnaround time for a complete commercial jingle production?',
                'a' => 'A complete commercial jingle project typically takes 7 to 14 business days from initial brief and melody demo presentation to final master stem delivery.'
            ]
        ],
        'sort_order' => 13,
        'meta_title' => 'Commercial Jingle & Radio Audio Production Bangladesh | AR Entertainment',
        'meta_description' => 'Unforgettable advertising jingles and radio commercial production in Dhaka, Bangladesh by AR Entertainment. Catchy melodies, star vocalists, and studio mastering.'
    ],

    // 14. Brand Songwriting & Commercial Lyrics Development
    [
        'title' => 'Brand Songwriting & Commercial Lyrics Development',
        'slug' => 'lyrics-development',
        'icon' => 'fa-solid fa-pen-nib',
        'short_summary' => 'Culturally rich, emotionally gripping Bangla and English lyric writing crafted by veteran lyricists for corporate anthems, advertising jingles, and brand campaigns.',
        'content' => '<h3>Evocative Lyric Writing Tailored for Brands &amp; Cinematic Music</h3>
<p>Behind every iconic brand song or advertising anthem lies powerful lyrical storytelling. At <strong>AR Entertainment</strong>, our team of seasoned poets, copywriters, and commercial songwriters craft lyrics that touch hearts, evoke patriotic pride, and cement brand taglines in the collective consciousness.</p>
<h4>Strategic Songwriting &amp; Metrical Craft</h4>
<ul>
    <li><strong>Rhythmic &amp; Metrical Architecture:</strong> Structuring verses, pre-choruses, and punchy hooks engineered specifically for musical rhythm and effortless singing.</li>
    <li><strong>Cultural &amp; Dialectical Mastery:</strong> Fluent in Standard Colloquial Bangla (Chalito), poetic Shadhubhasha, dynamic youth street vernacular, and international English.</li>
    <li><strong>Brand Narrative Integration:</strong> Seamlessly embedding corporate values, campaign mottos, and product benefits into emotional lyrical poetry without sounding clinical.</li>
    <li><strong>Vocal Phrasing Consultation:</strong> Direct collaboration with music composers and vocal directors to ensure natural vowel elongation and maximum acoustic clarity.</li>
</ul>
<p>Empower your brand with words that inspire movements, build consumer loyalty, and resonate across generations.</p>',
        'pricing_note' => 'Professional lyrical development packages range from ৳25,000 to ৳150,000 BDT per composition with rhythmic metre structuring and full copyright assignment.',
        'faqs' => [
            [
                'q' => 'Why is professional lyric development critical for a brand campaign?',
                'a' => 'Standard advertising copy does not fit musical metre. Professional lyricists understand poetic rhythm, syllable counts, vowel resonance, and emotional hooks that make words singable and unforgettable.'
            ],
            [
                'q' => 'Can you write lyrics in both formal standard Bangla and colloquial youth dialects?',
                'a' => 'Yes. We tailor our linguistic tone to your target demographic—from formal corporate anthems celebrating national heritage to vibrant, rhythmic urban slang for youth-targeted energy drinks or telco brands.'
            ],
            [
                'q' => 'How does AR Entertainment align lyrics with our corporate brand values?',
                'a' => 'We start with a thorough brand brief, distilling your core values, audience demographics, and campaign objectives into central emotional metaphors before drafting lyric options.'
            ],
            [
                'q' => 'Do your lyricists collaborate with music composers during the writing process?',
                'a' => 'Yes. Our lyricists work hand-in-hand with our in-house music directors to ensure that words and melodic chord progressions enhance each other seamlessly.'
            ],
            [
                'q' => 'What is the typical process for revising and approving song lyrics?',
                'a' => 'We present 2 to 3 distinct lyrical concepts with meter guides. Clients provide feedback and we refine the selected version through up to 3 rounds of revisions until perfect.'
            ],
            [
                'q' => 'Are the lyrical rights transferred 100% exclusively to our company?',
                'a' => 'Yes. All intellectual property, publishing rights, and lyric ownership are transferred exclusively to the client upon final project sign-off.'
            ]
        ],
        'sort_order' => 14,
        'meta_title' => 'Brand Songwriting & Lyrics Development Bangladesh | AR Entertainment',
        'meta_description' => 'Expert Bangla and English lyrical songwriting for advertising campaigns, brand anthems, and jingles in Dhaka, Bangladesh by AR Entertainment.'
    ],

    // 15. Brand Anthem Video & Corporate Theme Song
    [
        'title' => 'Brand Anthem Video & Corporate Theme Song Production',
        'slug' => 'brand-anthem-video',
        'icon' => 'fa-solid fa-flag',
        'short_summary' => 'Monumental corporate anthems and cinematic brand music videos celebrating organizational milestones, heritage, workforce solidarity, and national prestige.',
        'content' => '<h3>Cinematic Brand Anthems That Celebrate Corporate Heritage &amp; Vision</h3>
<p>When an organization celebrates a milestone—whether a 25th jubilee, an IPO listing, or a national expansion—a standard corporate video is not enough. <strong>AR Entertainment</strong> produces monumental Brand Anthem Videos that unite original orchestral compositions, celebrated vocalists, and sweeping nationwide cinematography into an emotional masterpiece.</p>
<h4>Monumental Multi-Location Cinematic Production</h4>
<ul>
    <li><strong>Original Anthem Scoring &amp; Production:</strong> Bespoke musical composition featuring live orchestral arrangements, traditional Bangladeshi instrumentation, and choir harmonies.</li>
    <li><strong>Nationwide Field Cinematography:</strong> 4K cinema cameras, aerial heavy-lift drone cinematography, and stylized lighting capturing factories, farmlands, corporate headquarters, and smiling communities across Bangladesh.</li>
    <li><strong>Emotional Workforce Storytelling:</strong> Highlighting real employees, leadership vision, and customer lives to showcase the authentic human heartbeat behind your enterprise.</li>
    <li><strong>Multi-Channel Broadcast Masters:</strong> Full 4-minute cinematic music video masters accompanied by 60s, 30s, and 15s TV and social media cutdowns for annual galas, AGMs, and national advertising.</li>
</ul>
<p>Create a legacy audio-visual monument that instills immense pride across your workforce and solidifies your stature as an industry titan.</p>',
        'pricing_note' => 'Brand anthem video packages range from ৳200,000 to ৳1,500,000 BDT including original musical composition, star vocalists, nationwide cinematography, and drone sweeps.',
        'faqs' => [
            [
                'q' => 'What is a brand anthem video and when should a company produce one?',
                'a' => 'A brand anthem video is a high-production music video and thematic film that articulates a company\'s mission, values, and emotional impact. It is ideal for major anniversaries, rebranding campaigns, IPO launches, and corporate galas.'
            ],
            [
                'q' => 'How does a brand anthem differ from a standard corporate AV?',
                'a' => 'A standard corporate AV is informational and documentary in nature. A brand anthem is an emotional, music-driven cinematic experience designed to evoke inspiration, unity, and deep brand pride.'
            ],
            [
                'q' => 'Can you film our employees and manufacturing facilities across multiple districts?',
                'a' => 'Yes. Our production crews operate across all 64 districts of Bangladesh, equipped with cinema camera packages, specialized lighting trucks, and licensed drone pilots.'
            ],
            [
                'q' => 'Do you provide celebrity playback singers or orchestral arrangements for the music track?',
                'a' => 'Yes. We partner with Bangladesh’s leading playback singers, session musicians, and symphonic arrangers to deliver world-class musical recordings.'
            ],
            [
                'q' => 'How long does a full brand anthem music video project take?',
                'a' => 'A complete brand anthem production takes 4 to 8 weeks, encompassing lyrical development, studio music recording, multi-district filming, and cinema-grade editing/color grading.'
            ],
            [
                'q' => 'How can our organization utilize the brand anthem across internal and external marketing?',
                'a' => 'Brand anthems can be premiered at AGMs and corporate galas, integrated into employee onboarding, featured on television during national holidays, and shared across social media and YouTube.'
            ]
        ],
        'sort_order' => 15,
        'meta_title' => 'Brand Anthem & Corporate Theme Video Production | AR Entertainment',
        'meta_description' => 'Cinematic brand anthem video production in Bangladesh by AR Entertainment. Celebrate corporate milestones with custom musical compositions and epic nationwide visuals.'
    ],

    // 16. Animated Music Video & Visual Storytelling
    [
        'title' => 'Animated Music Video & Visual Storytelling',
        'slug' => 'animated-music-video',
        'icon' => 'fa-solid fa-compact-disc',
        'short_summary' => 'Stylized 2D/3D animated music videos, anime aesthetics, and surreal visual narratives created for music artists, record labels, and innovative brand collaborations.',
        'content' => '<h3>Visualizing Sound Through Groundbreaking Animation &amp; CGI</h3>
<p>Animation frees storytelling from the constraints of physical sets, gravity, and conventional reality. <strong>AR Entertainment</strong> crafts visually stunning Animated Music Videos for recording artists, record labels, and progressive brands seeking to merge captivating musical rhythms with limitless artistic imagination.</p>
<h4>Limitless Animation Artistry &amp; Visual FX</h4>
<ul>
    <li><strong>Diverse Animation Disciplines:</strong> Hand-drawn frame-by-frame 2D animation, dynamic Japanese anime aesthetics, stylized vector motion, and photorealistic 3D CGI environments in Unreal Engine and Blender.</li>
    <li><strong>World-Building &amp; Concept Art:</strong> Bespoke character design, surreal fantasy landscapes, cyberpunk neon cities, and detailed color script development synced to every musical beat.</li>
    <li><strong>Audio-Visual Rhythm Synchronization:</strong> Kinetic camera motions, lighting pulses, and visual transitions tightly choreographed to the song’s rhythm, bass drops, and lyrical poetry.</li>
    <li><strong>4K HDR Master Delivery:</strong> High-bitrate 4K exports optimized for YouTube 4K, Apple Music, Vevo, and theatrical screening standards.</li>
</ul>
<p>Break through the noise of standard live-action videos with imaginative worlds that captivate audiences and inspire viral fandom.</p>',
        'pricing_note' => 'Animated music video packages range from ৳180,000 to ৳950,000 BDT based on animation style (hand-drawn 2D vs. 3D Blender/Unreal Engine) and complexity.',
        'faqs' => [
            [
                'q' => 'What animation styles do you offer for music videos?',
                'a' => 'We offer 2D traditional hand-drawn animation, anime/manga styles, 3D CGI character animation, mixed-media rotoscoping, pixel art, and futuristic cyberpunk aesthetics.'
            ],
            [
                'q' => 'How long does it take to produce a full 3-to-4-minute animated music video?',
                'a' => 'Production timelines typically range from 4 to 8 weeks depending on visual complexity, character rigging, frame rate (12fps vs 24fps), and stylistic detail.'
            ],
            [
                'q' => 'Can you create animated music videos based on a rough concept or storyboard?',
                'a' => 'Yes. Whether you come with a detailed treatment or just an audio track and an emotional mood, our concept artists develop complete storyboards, character sheets, and visual animatics.'
            ],
            [
                'q' => 'What is the advantage of an animated music video over a live-action shoot?',
                'a' => 'Animation has zero physical limitations—you can create sci-fi galaxies, mythological creatures, time travel, and surreal metamorphoses at a predictable cost without actors\' scheduling hurdles.'
            ],
            [
                'q' => 'Do you handle character concept design, world-building, and color scripting?',
                'a' => 'Yes. Our art directors design original characters, turnaround model sheets, environmental concept art, and emotional color scripts aligned with the track.'
            ],
            [
                'q' => 'Who retains the intellectual property rights to the custom characters and animation assets?',
                'a' => 'Upon completion and final delivery, all custom character designs, background artwork, and animation master files are 100% owned by the client or artist.'
            ]
        ],
        'sort_order' => 16,
        'meta_title' => 'Animated Music Video Production in Bangladesh | AR Entertainment',
        'meta_description' => 'Stylized 2D & 3D animated music video production in Dhaka, Bangladesh by AR Entertainment. Creative storytelling, anime visuals, and high-impact motion graphics.'
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
foreach ($services_batch2 as $svc) {
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
echo "🏆 BATCH 5.2.2 COMPLETED SUCCESSFULLY!\n";
echo "   - Total Services Seeded in Batch: {$seeded_count}\n";
echo "   - Current Total in Database: {$total_services}\n";
echo "========================================================\n";
