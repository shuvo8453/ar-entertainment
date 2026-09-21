<?php
/**
 * AR Entertainment - Phase 5.3 District Filming Guides Seeder (Batch 5.3.6: Districts 49–58 — Khulna Division)
 * 
 * Ingests 10 District Filming Location Guides for Khulna Division:
 * 1.  Khulna (video-production-company-in-khulna)
 * 2.  Bagerhat (video-production-company-in-bagerhat)
 * 3.  Satkhira (video-production-company-in-satkhira)
 * 4.  Jashore (video-production-company-in-jessore)
 * 5.  Jhenaidah (video-production-company-in-jhenaidah)
 * 6.  Magura (video-production-company-in-magura)
 * 7.  Narail (video-production-company-in-narail)
 * 8.  Kushtia (video-production-company-in-kushtia)
 * 9.  Chuadanga (video-production-company-in-chuadanga)
 * 10. Meherpur (video-production-company-in-meherpur)
 * 
 * Features:
 * - 100% Clean AR Entertainment Branding (0% Legacy Names)
 * - Cinema Logistics & Location Breakdown (Sundarbans Mangroves, Shait Gombuj UNESCO, Lalon Shah Akhra, Flower Capital)
 * - Rich Responsive HTML Guide
 * - Idempotent MySQL Upsert (ON DUPLICATE KEY UPDATE)
 */

declare(strict_types=1);

require_once __DIR__ . '/../../config/config.php';
require_once __DIR__ . '/../../config/db.php';
require_once __DIR__ . '/../../config/helpers.php';

echo "========================================================\n";
echo "🎬 AR ENTERTAINMENT - SEED DISTRICT GUIDES BATCH 5.3.6 (49–58: KHULNA DIVISION)\n";
echo "========================================================\n\n";

$db = db();

$districts_batch6 = [
    // 1. Khulna
    [
        'city_name' => 'Khulna',
        'slug' => 'video-production-company-in-khulna',
        'title' => 'Video Production Company in Khulna | AR Entertainment',
        'summary' => 'Sundarbans mangrove expedition filming, Rupsha riverway cinematography, shipbuilding and seafood industrial video production in Khulna by AR Entertainment.',
        'content' => '<h3>Sundarbans Gateway Cinema &amp; Industrial Production in Khulna</h3>
<p>As the principal industrial metropolis of southwest Bangladesh and the primary launchpad for the world\'s largest mangrove forest, <strong>Khulna</strong> provides a dynamic mix of riverine shipping channels, modern export seafood processing plants, and deep wilderness access.</p>
<h4>Top Filming Hotspots &amp; Industrial Landscapes</h4>
<ul>
    <li><strong>Rupsha River &amp; Khan Jahan Ali Bridge:</strong> The iconic gateway bridge offering sweeping sunset horizons, industrial cargo barge crossings, and golden hour aerial sweeps.</li>
    <li><strong>Khulna Shipyard &amp; Maritime Docks:</strong> Heavy industrial slipways, naval vessel construction, and cinematic high-contrast metal welding aesthetics.</li>
    <li><strong>Seafood Processing &amp; Frozen Shrimp Export Hubs:</strong> Modern automated cold-storage facilities for global export corporate AV and commercial shoots.</li>
    <li><strong>Sundarbans Launch Terminal (Mongla Proximity):</strong> Luxury expedition vessel departures navigating the dynamic brackish water delta.</li>
    <li><strong>Gollamari &amp; Khulna University Canopy Boulevards:</strong> Modern academic institutions and historical monuments framed by tranquil coastal flora.</li>
</ul>
<h4>Marine Logistics, Forest Passes &amp; Weather-Sealed Gear</h4>
<p><strong>AR Entertainment</strong> provisions motorized marine production vessels, Forest Department expedition clearances, and weather-sealed cinema packages for humid tidal conditions.</p>',
        'sort_order' => 49,
        'meta_title' => 'Video Production Company in Khulna | AR Entertainment',
        'meta_description' => 'Top video production company & film fixer in Khulna, Bangladesh. Sundarbans mangrove expeditions, Rupsha river drone video, and industrial corporate AVs by AR Entertainment.'
    ],

    // 2. Bagerhat
    [
        'city_name' => 'Bagerhat',
        'slug' => 'video-production-company-in-bagerhat',
        'title' => 'Video Production Company in Bagerhat | AR Entertainment',
        'summary' => 'Sixty Dome Mosque UNESCO World Heritage filming, Khan Jahan Ali historic city cinematography, and Kotka wildlife documentaries in Bagerhat by AR Entertainment.',
        'content' => '<h3>UNESCO World Heritage Mosque City &amp; Coastal Wilderness in Bagerhat</h3>
<p>Home to the UNESCO World Heritage "Historic Mosque City of Bagerhat," this district combines 15th-century terracotta brick Sultanate architecture with pristine Sundarbans coastal wildlife reserves.</p>
<h4>Key Filming Locations in Bagerhat</h4>
<ul>
    <li><strong>Sixty Dome Mosque (Shait Gombuj Masjid):</strong> Monumental 1459 Sultanate brick architectural wonder featuring 77 low domes, 60 slender stone pillars, and massive curved cornices.</li>
    <li><strong>Mausoleum of Khan Jahan Ali &amp; Sacred Crocodile Dighi:</strong> Historic domed shrine overlooking an ancient reservoir inhabited by legendary marsh crocodiles.</li>
    <li><strong>Nine Dome Mosque &amp; Singar Mosque:</strong> Elegant single and multi-domed terracotta structures nestled within lush rural coconut palm groves.</li>
    <li><strong>Kotka &amp; Kochikhali Wildlife Sanctuaries:</strong> Deep Sundarbans coastal zones featuring spotted deer herds, saltwater crocodiles, and pristine sandy beaches.</li>
    <li><strong>Sundarbans Honey Hunters (Mawali) Base:</strong> Traditional forest honey collection expeditions in seasonal mangrove swamps.</li>
</ul>
<h4>Archaeological &amp; Forest Department Authorizations</h4>
<p>We coordinate joint UNESCO heritage permits and Sundarbans East Wildlife Division approvals, while providing specialized macro lenses and long-range wildlife zoom packages.</p>',
        'sort_order' => 50,
        'meta_title' => 'Video Production Company in Bagerhat | AR Entertainment',
        'meta_description' => 'Leading video production company & fixer in Bagerhat, Bangladesh. Sixty Dome Mosque UNESCO filming, Khan Jahan Ali heritage shoots, and Sundarbans wildlife video.'
    ],

    // 3. Satkhira
    [
        'city_name' => 'Satkhira',
        'slug' => 'video-production-company-in-satkhira',
        'title' => 'Video Production Company in Satkhira | AR Entertainment',
        'summary' => 'Western Sundarbans Royal Bengal Tiger habitat filming, coastal brackish aquaculture cinematography, and heritage Jesuit ruins in Satkhira by AR Entertainment.',
        'content' => '<h3>Deep Mangrove Tiger Wilderness &amp; Coastal Aquaculture in Satkhira</h3>
<p>Bordering the western Sundarbans, <strong>Satkhira</strong> is the world\'s prime gateway for wild Royal Bengal Tiger documentaries, vast coastal shrimp enclosures (ghers), and fascinating 16th-century colonial ruins.</p>
<h4>Iconic Satkhira Filming Locations</h4>
<ul>
    <li><strong>Munshiganj &amp; Burigoalini Mangrove Trails:</strong> Deep western Sundarbans waterways offering access to dense Sundari tree canopies and tiger tracking zones.</li>
    <li><strong>Coastal Shrimp &amp; Crab Farming Enclosures (Ghers):</strong> Endless mirror-like water landscapes stretching to the horizon, ideal for sunrise drone tracking.</li>
    <li><strong>Ishwaripur Jesuit Church &amp; Chanda Bhairab Temple:</strong> 1599 ruins of Bangladesh\'s first Christian church and ancient triangular terracotta temple.</li>
    <li><strong>Ichamati River International Borderline:</strong> Serene border river separating Bangladesh and West Bengal, with traditional boat life.</li>
</ul>
<h4>Tiger Tracking Fixers, Marine Craft &amp; Telephoto Rigs</h4>
<p>AR Entertainment charters armed Forest Guard escorts, silent electric tracking boats, and ultra-telephoto 400mm-800mm cinema lenses for safe, ethical wildlife filmmaking.</p>',
        'sort_order' => 51,
        'meta_title' => 'Video Production Company in Satkhira | AR Entertainment',
        'meta_description' => 'Professional video production & wildlife film fixer in Satkhira, Bangladesh. Sundarbans tiger tracking, coastal aquaculture commercials, and Ichamati river drone video.'
    ],

    // 4. Jashore (Jessore)
    [
        'city_name' => 'Jashore',
        'slug' => 'video-production-company-in-jessore',
        'title' => 'Video Production Company in Jashore | AR Entertainment',
        'summary' => 'Gadkhali Flower Capital bloom cinematography, Software Technology Park hi-tech shoots, and Michael Madhusudan Dutt Sagardari heritage in Jashore by AR Entertainment.',
        'content' => '<h3>Flower Capital Blooms, Hi-Tech Innovation &amp; Literary Heritage in Jashore</h3>
<p>As Bangladesh\'s premier flower-growing hub and first digital tech center in the southwest, <strong>Jashore</strong> blends multi-colored agricultural blossoms with cutting-edge IT infrastructure and romantic literary heritage.</p>
<h4>Top Filming Hotspots in Jashore</h4>
<ul>
    <li><strong>Gadkhali Flower Fields (Flower Capital):</strong> Over 1,500 hectares of vibrant rose, gerbera, gladiolus, and marigold plantations blooming year-round.</li>
    <li><strong>Sheikh Hasina Software Technology Park:</strong> 15-story sleek glass-facade IT park with high-tech workspaces, auditoriums, and corporate media hubs.</li>
    <li><strong>Sagardari (Michael Madhusudan Dutt Memorial):</strong> The tranquil riverside estate on the Kapotaksha River celebrating the father of Bengali blank verse poetry.</li>
    <li><strong>Jessore Cantonment &amp; Historic Airport:</strong> Modern logistics and aviation corridor connecting southwest Bangladesh.</li>
    <li><strong>Chanchra Rajbari &amp; 108 Shiva Temples (Abhaynagar):</strong> Historic terracotta royal ruins and sacred heritage tanks.</li>
</ul>
<h4>Agricultural Commercial Production &amp; Gimbal Cine Rigs</h4>
<p>We deploy high-speed macro lenses for floral beauty commercials, smooth dolly setups for modern corporate parks, and complete on-ground fixer teams across Jashore.</p>',
        'sort_order' => 52,
        'meta_title' => 'Video Production Company in Jashore | AR Entertainment',
        'meta_description' => 'Top video production company & fixer in Jashore, Bangladesh. Gadkhali flower field commercials, Software Tech Park corporate AVs, and Sagardari heritage video.'
    ],

    // 5. Jhenaidah
    [
        'city_name' => 'Jhenaidah',
        'slug' => 'video-production-company-in-jhenaidah',
        'title' => 'Video Production Company in Jhenaidah | AR Entertainment',
        'summary' => 'Naldanga royal terracotta palace shoots, Shailkupa Mughal Shahi Mosque filming, and lush agro-banana plantation cinematography in Jhenaidah by AR Entertainment.',
        'content' => '<h3>Terracotta Temple Palaces &amp; Agricultural Innovation in Jhenaidah</h3>
<p>Famed for its royal Naldanga terracotta temples and fertile agro-farming belts along the Kumar and Nabaganga rivers, <strong>Jhenaidah</strong> offers extraordinary historical and rural filming backdrops.</p>
<h4>Key Filming Locations in Jhenaidah</h4>
<ul>
    <li><strong>Naldanga Royal Palace Temple Complex:</strong> Magnificent 17th-century terracotta temples, including the Siddheswari and Kali temples surrounded by royal moats.</li>
    <li><strong>Shailkupa Shahi Mosque:</strong> 15th-century Sultanate and Mughal historic mosque standing gracefully on the banks of the Kumar River.</li>
    <li><strong>Commercial Banana &amp; Dragon Fruit Plantations:</strong> Expansive modern fruit farms producing high-yield harvests for agricultural documentaries.</li>
    <li><strong>Dhol Samudra Historic Lake:</strong> Vast legendary medieval reservoir steeped in regional folklore and water vistas.</li>
    <li><strong>Lalon Shah\'s Birthplace (Harishpur):</strong> Cultural memorial dedicated to the mystic spiritual philosopher.</li>
</ul>
<h4>Heritage Authorizations &amp; Rural Drone Clearances</h4>
<p>AR Entertainment coordinates temple heritage access, provides high-resolution aerial mapping drones, and handles full lighting equipment for historical interiors.</p>',
        'sort_order' => 53,
        'meta_title' => 'Video Production Company in Jhenaidah | AR Entertainment',
        'meta_description' => 'Leading video production company in Jhenaidah, Bangladesh. Naldanga royal palace shoots, Shailkupa mosque filming, and agro-documentaries by AR Entertainment.'
    ],

    // 6. Magura
    [
        'city_name' => 'Magura',
        'slug' => 'video-production-company-in-magura',
        'title' => 'Video Production Company in Magura | AR Entertainment',
        'summary' => 'Katyayani Puja street carnival cinematography, Sreepur Rajbari heritage filming, and Nabaganga river commercial video production in Magura by AR Entertainment.',
        'content' => '<h3>Spectacular Festival Carnivals &amp; Zamindar Heritage in Magura</h3>
<p>Celebrated nationwide for hosting Bangladesh\'s grandest month-long Katyayani Puja festival, <strong>Magura</strong> transforms into an extraordinary world of illuminated street architecture, idol artistry, and cultural fervor.</p>
<h4>Top Filming Hotspots in Magura</h4>
<ul>
    <li><strong>Katyayani Festival Street Sets:</strong> Monumental temporary palace facades, dazzling LED lighting arches, and vibrant cultural processions.</li>
    <li><strong>Sreepur Rajbari &amp; Ichhakhada Heritage Sites:</strong> Historic 18th-century zamindar estates with ancient brick archways and moss-covered courtyards.</li>
    <li><strong>Nabaganga &amp; Gorai River Confluence:</strong> Serene river waterways, wooden country boat life, and sunset fisherman silhouettes.</li>
    <li><strong>Modern Agro-Seed Production Facilities:</strong> High-yield seed testing and packaging centers for corporate agricultural AVs.</li>
</ul>
<h4>Night-Cine Lighting &amp; Crowd Management Fixers</h4>
<p>We provide low-noise, high-ISO cinema camera packages for night festival filming, crowd control liaison with local administration, and wireless multi-channel audio kits.</p>',
        'sort_order' => 54,
        'meta_title' => 'Video Production Company in Magura | AR Entertainment',
        'meta_description' => 'Expert video production & fixer in Magura, Bangladesh. Katyayani festival filming, Sreepur Rajbari shoots, and Nabaganga river drone cinematography.'
    ],

    // 7. Narail
    [
        'city_name' => 'Narail',
        'slug' => 'video-production-company-in-narail',
        'title' => 'Video Production Company in Narail | AR Entertainment',
        'summary' => 'SM Sultan artistic heritage & museum filming, traditional Otter Fishing on the Chitra River, and wetland cinematography in Narail by AR Entertainment.',
        'content' => '<h3>Artistic Legacy, Riverboat Otter Fishing &amp; Wetland Cinema in Narail</h3>
<p>Home of the legendary master painter SM Sultan and Bangladesh\'s only surviving ancient practice of otter fishing, <strong>Narail</strong> is one of the most culturally unique documentary destinations in South Asia.</p>
<h4>Iconic Narail Filming Locations</h4>
<ul>
    <li><strong>Traditional Otter Fishing on the Chitra River:</strong> Ancient generational fishermen using trained smooth-coated otters to drive fish into nets from wooden boats.</li>
    <li><strong>SM Sultan Memorial Complex &amp; Shishuswargo:</strong> The legendary painter\'s art gallery, massive floating studio barge (Nao), and riverside garden estate.</li>
    <li><strong>Chitra &amp; Nabaganga River Waterways:</strong> Tranquil meandering waterways framed by water lilies and weeping willows.</li>
    <li><strong>Victoria College &amp; Historic Zamindar Estuaries:</strong> Colonial educational landmarks and heritage brick structures.</li>
</ul>
<h4>Wildlife Video Fixers &amp; Water-Level Gimbals</h4>
<p>AR Entertainment provisions specialized low-angle splash-resistant gimbals for otter fishing action shots, local boatman crews, and cultural artist foundation passes.</p>',
        'sort_order' => 55,
        'meta_title' => 'Video Production Company in Narail | AR Entertainment',
        'meta_description' => 'Top video production company & fixer in Narail, Bangladesh. Traditional otter fishing documentaries, SM Sultan art museum filming, and Chitra river drone video.'
    ],

    // 8. Kushtia
    [
        'city_name' => 'Kushtia',
        'slug' => 'video-production-company-in-kushtia',
        'title' => 'Video Production Company in Kushtia | AR Entertainment',
        'summary' => 'Lalon Shah Mazar Baul spiritual music filming, Rabindranath Tagore Shilaidaha Kuthibari heritage, and Gorai bridge cinematography in Kushtia by AR Entertainment.',
        'content' => '<h3>Spiritual Baul Music Cinema &amp; Tagore Literary Palaces in Kushtia</h3>
<p>Revered as the cultural and spiritual heart of Bengal, <strong>Kushtia</strong> is the home of mystic philosopher Fakir Lalon Shah and Nobel laureate Rabindranath Tagore\'s idyllic countryside residence.</p>
<h4>Top Filming Hotspots &amp; Cultural Landmarks</h4>
<ul>
    <li><strong>Lalon Shah Akhra &amp; Mazar (Chheuriya):</strong> Global center of Baul philosophy, featuring acoustic Ektara and Dotara performances and thousands of spiritual devotees during Dol Purnima.</li>
    <li><strong>Rabindranath Tagore\'s Shilaidaha Kuthibari:</strong> 3-story pyramidal tiled heritage manor where Tagore penned major portions of <em>Gitanjali</em> (Song Offerings).</li>
    <li><strong>Gorai Railway Bridge &amp; Padma Riverbanks:</strong> Dramatic steel railway truss bridges spanning the wide Gorai River with scenic riverboat life.</li>
    <li><strong>Historic Mohini Mills:</strong> Pioneer British-era textile industrial heritage site with towering brick chimneys and vintage loom structures.</li>
    <li><strong>Mir Mosharraf Hossain Memorial (Lahoripara):</strong> Historic estate of the celebrated 19th-century novelist.</li>
</ul>
<h4>High-Fidelity Acoustic Audio &amp; Heritage Passes</h4>
<p>We deploy 32-bit float multi-track field audio recorders to capture acoustic Baul music with zero distortion, alongside Department of Archaeology access passes for Kuthibari.</p>',
        'sort_order' => 56,
        'meta_title' => 'Video Production Company in Kushtia | AR Entertainment',
        'meta_description' => 'Leading video production company & fixer in Kushtia, Bangladesh. Lalon Shah spiritual music filming, Tagore Kuthibari shoots, and Gorai river drone video.'
    ],

    // 9. Chuadanga
    [
        'city_name' => 'Chuadanga',
        'slug' => 'video-production-company-in-chuadanga',
        'title' => 'Video Production Company in Chuadanga | AR Entertainment',
        'summary' => 'Historic first capital heritage filming, Carew & Co. industrial distillery documentation, and Mathabhanga river cinematography in Chuadanga by AR Entertainment.',
        'content' => '<h3>Liberation History &amp; Century-Old Agro-Industrial Heritage in Chuadanga</h3>
<p>Holding a proud place in history as the first temporary capital of independent Bangladesh in 1971, <strong>Chuadanga</strong> combines historic railway transit with the country\'s oldest commercial agro-distillery.</p>
<h4>Key Filming Locations in Chuadanga</h4>
<ul>
    <li><strong>Carew &amp; Co. (Bangladesh) Ltd. (Darsana):</strong> 1938 British-era sugar mill and distillery featuring vintage steam turbines, massive copper distillation columns, and industrial rail sidings.</li>
    <li><strong>Mathabhanga River Sandbars &amp; Riverways:</strong> Meandering river channels flanked by lush green agricultural banks and rural ferry crossings.</li>
    <li><strong>First Capital Memorial &amp; 1971 Transit Sites:</strong> Historic locations commemorating the early administrative foundations of the Bangladesh Liberation War.</li>
    <li><strong>Darsana International Railway &amp; Land Port:</strong> Major freight and passenger transit corridor with vintage British railway infrastructure.</li>
</ul>
<h4>Industrial Facility Safety Clearances &amp; Production Crew</h4>
<p>AR Entertainment coordinates Ministry of Industries permissions for Carew &amp; Co., providing specialized intrinsically safe lighting rigs and full borderland production support.</p>',
        'sort_order' => 57,
        'meta_title' => 'Video Production Company in Chuadanga | AR Entertainment',
        'meta_description' => 'Professional video production company in Chuadanga, Bangladesh. Carew & Co. industrial shoots, Mathabhanga river filming, and Liberation War documentaries.'
    ],

    // 10. Meherpur
    [
        'city_name' => 'Meherpur',
        'slug' => 'video-production-company-in-meherpur',
        'title' => 'Video Production Company in Meherpur | AR Entertainment',
        'summary' => 'Mujibnagar National Monument historical oath-taking memorial filming, British Amjhopi Indigo Nilkuthi shoots, and Bhairab river video production in Meherpur by AR Entertainment.',
        'content' => '<h3>National Birthplace Monument Cinema &amp; Indigo Heritage in Meherpur</h3>
<p>As the hallowed soil where the first government of independent Bangladesh took oath on April 17, 1971, <strong>Meherpur</strong> is an iconic national heritage site complemented by 19th-century colonial indigo estates.</p>
<h4>Top Filming Hotspots in Meherpur</h4>
<ul>
    <li><strong>Mujibnagar National Memorial &amp; Complex:</strong> Monumental 23-pillar monument symbolizing 23 years of Pakistani rule, massive 3D topographical map of Bangladesh, and historical mural galleries.</li>
    <li><strong>Amjhopi Historic Indigo Factory (Nilkuthi):</strong> 1800s British East India Company colonial manor featuring sprawling lawns, arched verandas, and historic punishment chambers.</li>
    <li><strong>Bhairab River Corridor &amp; Mango Groves:</strong> Picturesque quiet riverbanks surrounded by sweet mango and jujube (kul) orchards during seasonal harvests.</li>
    <li><strong>Bhabpara Historic Protestant Church:</strong> 19th-century heritage church structure surrounded by tranquil rural communities.</li>
</ul>
<h4>National Monument Authorizations &amp; Drone Clearances</h4>
<p>We handle Ministry of Liberation War Affairs and District Administration filming protocols for Mujibnagar, deploying high-wind 4K cinema drones for majestic architectural sweeps.</p>',
        'sort_order' => 58,
        'meta_title' => 'Video Production Company in Meherpur | AR Entertainment',
        'meta_description' => 'Top video production company & film fixer in Meherpur, Bangladesh. Mujibnagar National Monument filming, Amjhopi Nilkuthi shoots, and drone cinematography.'
    ]
];

$stmt = $db->prepare("
    INSERT INTO service_areas (city_name, slug, title, summary, content, sort_order, status, meta_title, meta_description, created_at, updated_at)
    VALUES (?, ?, ?, ?, ?, ?, 'active', ?, ?, NOW(), NOW())
    ON DUPLICATE KEY UPDATE 
        city_name=VALUES(city_name),
        title=VALUES(title),
        summary=VALUES(summary),
        content=VALUES(content),
        sort_order=VALUES(sort_order),
        status='active',
        meta_title=VALUES(meta_title),
        meta_description=VALUES(meta_description),
        updated_at=NOW()
");

$seeded = 0;
foreach ($districts_batch6 as $d) {
    $stmt->execute([
        $d['city_name'],
        $d['slug'],
        $d['title'],
        $d['summary'],
        $d['content'],
        $d['sort_order'],
        $d['meta_title'],
        $d['meta_description']
    ]);
    $seeded++;
    echo "   ✅ [{$seeded}/10] Seeded District Guide: {$d['city_name']} ({$d['slug']})\n";
}

$total_active = $db->query("SELECT COUNT(*) FROM service_areas WHERE status='active'")->fetchColumn();

echo "\n========================================================\n";
echo "🏆 BATCH 5.3.6 COMPLETED SUCCESSFULLY!\n";
echo "   - Total Districts Seeded in Batch: {$seeded}\n";
echo "   - Current Total Active in Database: {$total_active}\n";
echo "========================================================\n\n";
