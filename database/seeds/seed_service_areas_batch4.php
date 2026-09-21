<?php
/**
 * AR Entertainment - Phase 5.3 District Filming Guides Seeder (Batch 5.3.4: Districts 33–40 — Rajshahi Division)
 * 
 * Ingests 8 District Filming Location Guides for Rajshahi Division:
 * 1. Rajshahi (video-production-company-in-rajshahi)
 * 2. Bogura (video-production-company-in-bogra)
 * 3. Pabna (video-production-company-in-pabna)
 * 4. Natore (video-production-company-in-natore)
 * 5. Naogaon (video-production-company-in-naogaon)
 * 6. Chapai Nawabganj (video-production-company-in-nawabganj)
 * 7. Joypurhat (video-production-company-in-joypurhat)
 * 8. Sirajganj (video-production-company-in-sirajgonj)
 * 
 * Features:
 * - 100% Clean AR Entertainment Branding (0% Legacy Names)
 * - Cinema Logistics & Location Breakdown (Archaeological sites, Mega-bridges, Silk & Mango hubs)
 * - Rich Responsive HTML Guide
 * - Idempotent MySQL Upsert (ON DUPLICATE KEY UPDATE)
 */

declare(strict_types=1);

require_once __DIR__ . '/../../config/config.php';
require_once __DIR__ . '/../../config/db.php';
require_once __DIR__ . '/../../config/helpers.php';

echo "========================================================\n";
echo "🎬 AR ENTERTAINMENT - SEED DISTRICT GUIDES BATCH 5.3.4 (33–40: RAJSHAHI DIVISION)\n";
echo "========================================================\n\n";

$db = db();

$districts_batch4 = [
    // 1. Rajshahi
    [
        'city_name' => 'Rajshahi',
        'slug' => 'video-production-company-in-rajshahi',
        'title' => 'Video Production Company in Rajshahi | AR Entertainment',
        'summary' => 'Silk City documentary cinematography, Padma River bank char lands, Puthia terracotta temple heritage, and commercial TVC filming in Rajshahi by AR Entertainment.',
        'content' => '<h3>Premier Film Production &amp; Cultural Heritage Cinematography in Rajshahi</h3>
<p>Known as the "Silk City" and clean educational capital of Bangladesh, <strong>Rajshahi</strong> offers filmmakers breathtaking Padma River horizons, world-renowned terracotta royal architecture, and rich sericulture heritage.</p>
<h4>Top Filming Hotspots &amp; Scenic Landscapes</h4>
<ul>
    <li><strong>Puthia Royal Temple Complex:</strong> The highest concentration of historic Hindu terracotta temples in Bangladesh, featuring the Shiva Temple, Govinda Temple, and ornate palace courtyards.</li>
    <li><strong>Padma River Banks &amp; T-Groin Embankments:</strong> Dramatic vast river horizons, sunset vistas, and expansive seasonal sandy char island landscapes.</li>
    <li><strong>Rajshahi Silk Factories &amp; Weaving Looms:</strong> Traditional cocoon rearing, spinning mills, and master artisan silk weaving facilities for corporate and documentary shoots.</li>
    <li><strong>Bagha Mosque:</strong> Historic 16th-century Sultanate-era terracotta mosque featured on the national currency.</li>
    <li><strong>Varendra Research Museum &amp; University of Rajshahi Campus:</strong> South Asia\'s premier antiquities museum and Paris Road canopy boulevard.</li>
</ul>
<h4>Local Production Logistics, Permits &amp; Camera Rigs</h4>
<p><strong>AR Entertainment</strong> manages archaeological department permits for Puthia, BGB border clearances along the Padma river corridor, and deploys high-speed RED/ARRI cinema rigs and heavy-lift cinema drones.</p>',
        'sort_order' => 33,
        'meta_title' => 'Video Production Company in Rajshahi | AR Entertainment',
        'meta_description' => 'Top video production company & film fixer in Rajshahi, Bangladesh. Puthia temple shoots, Silk City documentaries, and Padma river drone video by AR Entertainment.'
    ],

    // 2. Bogura (Bogra)
    [
        'city_name' => 'Bogura',
        'slug' => 'video-production-company-in-bogra',
        'title' => 'Video Production Company in Bogura | AR Entertainment',
        'summary' => 'Mahasthangarh 3rd-century BC ancient archaeology filming, industrial agro-processing documentaries, and commercial video production in Bogura by AR Entertainment.',
        'content' => '<h3>Ancient Archaeological Filming &amp; Industrial Production in Bogura</h3>
<p>Celebrated as the gateway to North Bengal and one of South Asia\'s oldest fortified archaeological landscapes, <strong>Bogura</strong> blends 2,500 years of ancient history with modern agro-industrial enterprise.</p>
<h4>Key Filming Locations in Bogura</h4>
<ul>
    <li><strong>Mahasthangarh Ancient Citadel:</strong> Legendary 3rd-century BC Pundranagar capital ruins, massive brick ramparts, and the sacred Karatoa riverbanks.</li>
    <li><strong>Gokul Medh (Behula\'s Basor Ghar):</strong> Terraced monumental stupa platform offering evocative ancient brick textures and elevated cinematography.</li>
    <li><strong>Kherua Mosque (Sherpur):</strong> 1582 Mughal-era architectural masterpiece with distinct curved cornices and red brick relief.</li>
    <li><strong>Bogra Curd (Doi) Heritage Kitchens:</strong> Artisanal clay-pot traditional curd fermenting processes perfect for high-speed macro food cinematography.</li>
    <li><strong>Agro-Machinery &amp; Industrial Estates:</strong> Flourishing agricultural manufacturing, foundry industries, and modern food processing plants.</li>
</ul>
<h4>Archaeological Permitting &amp; Lighting Packages</h4>
<p>We coordinate formal Department of Archaeology authorizations, provide precision cinema lighting packages for ancient interior sites, and manage full North Bengal crew logistics.</p>',
        'sort_order' => 34,
        'meta_title' => 'Video Production Company in Bogura | AR Entertainment',
        'meta_description' => 'Professional video production & film fixers in Bogura, Bangladesh. Mahasthangarh ancient filming, industrial food commercials, and TVCs by AR Entertainment.'
    ],

    // 3. Pabna
    [
        'city_name' => 'Pabna',
        'slug' => 'video-production-company-in-pabna',
        'title' => 'Video Production Company in Pabna | AR Entertainment',
        'summary' => 'Rooppur Nuclear Power Plant mega-infrastructure coverage, Hardinge Bridge heritage cinematography, and industrial video production in Pabna by AR Entertainment.',
        'content' => '<h3>Mega-Infrastructure Documentation &amp; Riverway Cinema in Pabna</h3>
<p>Situated along the confluence of the Padma and Jamuna rivers, <strong>Pabna</strong> is a dynamic filming hub featuring monumental engineering feats, historic British-era railway bridges, and rich pharmaceutical industries.</p>
<h4>Iconic Pabna Filming Locations</h4>
<ul>
    <li><strong>Hardinge Bridge &amp; Lalon Shah Bridge (Paksey):</strong> The monumental 1915 steel truss railway bridge spanning the Padma alongside the modern highway bridge.</li>
    <li><strong>Rooppur Nuclear Mega-Project Zone:</strong> Bangladesh\'s flagship national energy infrastructure project requiring specialized high-security filming permits.</li>
    <li><strong>Paksey Railway Heritage Junction:</strong> British colonial railway bungalows, mature mahogany avenues, and vintage signal infrastructure.</li>
    <li><strong>Edward College &amp; Historic Tarash Rajbari:</strong> Grand colonial neoclassical campus structures and heritage zamindar courtyards.</li>
    <li><strong>Square Pharmaceuticals &amp; Industrial Parks:</strong> High-tech sterile manufacturing facilities and corporate research centers.</li>
</ul>
<h4>Security Protocol &amp; Riverboat Camera Rigs</h4>
<p>AR Entertainment coordinates Ministry and security clearances for national mega-projects, and deploys specialized camera boats on the Padma for majestic golden hour bridge sweeps.</p>',
        'sort_order' => 35,
        'meta_title' => 'Video Production Company in Pabna | AR Entertainment',
        'meta_description' => 'Leading video production company in Pabna, Bangladesh. Hardinge Bridge drone filming, industrial corporate AVs, and Rooppur infrastructure documentation.'
    ],

    // 4. Natore
    [
        'city_name' => 'Natore',
        'slug' => 'video-production-company-in-natore',
        'title' => 'Video Production Company in Natore | AR Entertainment',
        'summary' => 'Uttara Gonobhaban royal palace shoots, Rani Bhabani Rajbari heritage documentaries, and Chalan Beel wetland filming in Natore by AR Entertainment.',
        'content' => '<h3>Royal Palace Heritage &amp; Wetland Cinematography in Natore</h3>
<p>Renowned for its royal zamindar estates and the sweeping waters of Chalan Beel, <strong>Natore</strong> is an essential filming destination for period dramas, architectural documentaries, and tourism commercials.</p>
<h4>Top Filming Hotspots in Natore</h4>
<ul>
    <li><strong>Uttara Gonobhaban (Dighapatia Rajbari):</strong> The official northern state palace of the Prime Minister, featuring Italian marble statues, clock towers, and grand manicured moat gardens.</li>
    <li><strong>Rani Bhabani Rajbari Palace Complex:</strong> Sprawling multi-palace estate of the legendary 18th-century philanthropist queen.</li>
    <li><strong>Chalan Beel Aquatic Expanse:</strong> North Bengal\'s largest freshwater wetland system, featuring picturesque seasonal water horizons and fishing culture.</li>
    <li><strong>Kachagolla Sweet Artisans:</strong> Renowned traditional dairy confection masters creating Natore\'s iconic culinary treasure.</li>
</ul>
<h4>Heritage Access &amp; Drone Flight Clearances</h4>
<p>We handle Cabinet Division and District Administration access protocols for Uttara Gonobhaban, while providing shallow-draft production boats for wetland filming across Chalan Beel.</p>',
        'sort_order' => 36,
        'meta_title' => 'Video Production Company in Natore | AR Entertainment',
        'meta_description' => 'Expert video production & location fixer in Natore, Bangladesh. Uttara Gonobhaban palace shoots, Rani Bhabani documentaries, and Chalan Beel drone video.'
    ],

    // 5. Naogaon
    [
        'city_name' => 'Naogaon',
        'slug' => 'video-production-company-in-naogaon',
        'title' => 'Video Production Company in Naogaon | AR Entertainment',
        'summary' => 'Somapura Mahavihara Paharpur UNESCO World Heritage filming, Kusumba Mosque stone architecture, and granary agricultural shoots in Naogaon by AR Entertainment.',
        'content' => '<h3>UNESCO World Heritage Cinema &amp; Ancient Stone Heritage in Naogaon</h3>
<p>Home to the world-famous 8th-century Buddhist monastery of Somapura Mahavihara, <strong>Naogaon</strong> is an extraordinary archaeological and agricultural treasure in northern Bangladesh.</p>
<h4>Key Filming Locations in Naogaon</h4>
<ul>
    <li><strong>Somapura Mahavihara at Paharpur:</strong> UNESCO World Heritage Site featuring a colossal 72-foot central shrine, 177 monastic cells, and thousands of terracotta plaque reliefs.</li>
    <li><strong>Kusumba Mosque (Manda):</strong> 1558 black basalt stone masterpiece renowned as the "Black Gem of Bengal," surviving severe earthquakes intact.</li>
    <li><strong>Dubalhati &amp; Balihar Rajbari:</strong> Vast atmospheric zamindar ruins with classical colonnades and arched courtyards.</li>
    <li><strong>Paddy Granaries &amp; Chini Gura Rice Fields:</strong> Rolling emerald-to-golden agricultural plains powering Bangladesh\'s premium rice cultivation.</li>
</ul>
<h4>Archaeological Fixer &amp; Gimbal Cine Rigs</h4>
<p>AR Entertainment secures UNESCO and National Archaeology filming clearances, deploying specialized motorized gimbals and cine lenses for intricate stone relief documentation.</p>',
        'sort_order' => 37,
        'meta_title' => 'Video Production Company in Naogaon | AR Entertainment',
        'meta_description' => 'Top video production company in Naogaon, Bangladesh. Paharpur UNESCO World Heritage filming, Kusumba Mosque stone documentation, and cinema drone video.'
    ],

    // 6. Chapai Nawabganj
    [
        'city_name' => 'Chapai Nawabganj',
        'slug' => 'video-production-company-in-nawabganj',
        'title' => 'Video Production Company in Chapai Nawabganj | AR Entertainment',
        'summary' => 'Mango Capital orchard cinematography, Choto Sona Mosque Sultanate heritage, and Mahananda river commercial video production in Chapai Nawabganj by AR Entertainment.',
        'content' => '<h3>Mango Capital Documentaries &amp; Sultanate Architecture in Chapai Nawabganj</h3>
<p>Celebrated as the undisputed "Mango Capital of Bangladesh," <strong>Chapai Nawabganj</strong> combines endless aromatic orchard canopies with historic 15th-century Gaur Sultanate monuments.</p>
<h4>Top Filming Hotspots in Chapai Nawabganj</h4>
<ul>
    <li><strong>Choto Sona Mosque (Gaur Border):</strong> 1493 Sultanate architectural marvel featuring fifteen gilded domes and delicate stone relief carving.</li>
    <li><strong>Darasbari Mosque &amp; Historic Madrasa:</strong> Ancient red-brick academy ruins dating to 1479 amidst peaceful forest glades.</li>
    <li><strong>Kansat Mango Market &amp; Vast Orchards:</strong> Asia\'s largest wholesale mango trading hub and square miles of flourishing Fazli and Khirsapat orchards during harvest.</li>
    <li><strong>Mahananda River Sands &amp; Traditional Ferries:</strong> Picturesque river meanders, shallow sandbanks, and traditional wooden boat crossings.</li>
    <li><strong>Nachole Historical Peasant Movement Heritage:</strong> Significant political history and indigenous Santal tribal communities.</li>
</ul>
<h4>Seasonal Harvest Campaigns &amp; Border Coordination</h4>
<p>We provide full logistical support for seasonal FMCG commercial shoots, including BGB border outpost coordination for monuments adjacent to the zero-line.</p>',
        'sort_order' => 38,
        'meta_title' => 'Video Production Company in Chapai Nawabganj | AR Entertainment',
        'meta_description' => 'Leading video production company & fixer in Chapai Nawabganj. Mango orchard commercials, Choto Sona Mosque filming, and Mahananda river video production.'
    ],

    // 7. Joypurhat
    [
        'city_name' => 'Joypurhat',
        'slug' => 'video-production-company-in-joypurhat',
        'title' => 'Video Production Company in Joypurhat | AR Entertainment',
        'summary' => 'Lokma Rajbari heritage shoots, mineral and limestone industrial documentation, and agro-poultry commercial video production in Joypurhat by AR Entertainment.',
        'content' => '<h3>Industrial Documentation &amp; Heritage Location Filming in Joypurhat</h3>
<p>Positioned along the northern rail corridor, <strong>Joypurhat</strong> is an emerging industrial and agro-farming hub with fascinating colonial estates and natural limestone quarry landscapes.</p>
<h4>Key Filming Locations in Joypurhat</h4>
<ul>
    <li><strong>Lokma Rajbari (Panchbibi):</strong> 18th-century zamindar estate featuring dramatic brick gateways, grand archways, and overgrown courtyards.</li>
    <li><strong>Jamalganj Deep Coal &amp; Limestone Exploration Zones:</strong> Unique subterranean industrial operations and extraction complexes.</li>
    <li><strong>Joypurhat Sugar Mills:</strong> Historic heavy industrial processing complex with towering chimneys and vintage transport systems.</li>
    <li><strong>Pagla Dewan Historical Monument:</strong> Revered ancient sanctuary with deep community folklore.</li>
    <li><strong>Hi-Tech Poultry &amp; Agro Hatcheries:</strong> Advanced agro-biotech facilities for corporate AV and supply-chain documentaries.</li>
</ul>
<h4>Industrial Production &amp; Sound Management</h4>
<p>AR Entertainment deploys industrial-grade filming gear, acoustic baffle kits for noisy processing environments, and complete regional transportation logistics.</p>',
        'sort_order' => 39,
        'meta_title' => 'Video Production Company in Joypurhat | AR Entertainment',
        'meta_description' => 'Professional video production & fixer in Joypurhat, Bangladesh. Lokma Rajbari filming, industrial corporate AVs, and agro-business commercials by AR Entertainment.'
    ],

    // 8. Sirajganj
    [
        'city_name' => 'Sirajganj',
        'slug' => 'video-production-company-in-sirajgonj',
        'title' => 'Video Production Company in Sirajganj | AR Entertainment',
        'summary' => 'Jamuna Bridge cinematic aerials, traditional Handloom Tant weaving documentaries, and Rabindranath Tagore Shahjadpur heritage filming in Sirajganj by AR Entertainment.',
        'content' => '<h3>Jamuna River Mega-Bridges, Handloom Heritage &amp; Literary Cinema in Sirajganj</h3>
<p>As the gateway connecting northern Bangladesh with the capital, <strong>Sirajganj</strong> boasts the mighty Jamuna Multi-purpose Bridge, Bangladesh\'s vibrant handloom capital, and Nobel laureate Rabindranath Tagore\'s historic manor.</p>
<h4>Top Filming Hotspots &amp; Cultural Landscapes</h4>
<ul>
    <li><strong>Bangabandhu Jamuna Multi-Purpose Bridge (Western Bank):</strong> Iconic 4.8-kilometer engineering wonder with expansive river channels, sunrise drone lines, and high-speed railway crossing.</li>
    <li><strong>Belkuchi &amp; Shahjadpur Handloom (Tant) Weaving Hubs:</strong> Thousands of rhythmic wooden looms creating world-renowned Jamdani and cotton sarees.</li>
    <li><strong>Rabindra Kachharibari at Shahjadpur:</strong> The tranquil 19th-century estate where Rabindranath Tagore composed masterpieces like <em>Postmaster</em> and <em>Chitra</em>.</li>
    <li><strong>Navaratna Temple (Hatikumrul):</strong> Magnificent 17th-century nine-pinnacled terracotta temple.</li>
    <li><strong>Jamuna River Chars &amp; Hard Point Embankment:</strong> Dynamic seasonal island communities, boat life, and vast open water expanses.</li>
</ul>
<h4>Bridge Authority Permissions &amp; Sound Recording</h4>
<p>AR Entertainment coordinates Bridge Authority (BBA) permits, provides specialized acoustic microphones for capturing weaving rhythms, and deploys high-wind marine drone packages over the Jamuna.</p>',
        'sort_order' => 40,
        'meta_title' => 'Video Production Company in Sirajganj | AR Entertainment',
        'meta_description' => 'Top video production company & film fixer in Sirajganj, Bangladesh. Jamuna Bridge drone sweeps, Handloom Tant documentaries, and Tagore Kachharibari filming.'
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
foreach ($districts_batch4 as $d) {
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
    echo "   ✅ [{$seeded}/8] Seeded District Guide: {$d['city_name']} ({$d['slug']})\n";
}

$total_active = $db->query("SELECT COUNT(*) FROM service_areas WHERE status='active'")->fetchColumn();

echo "\n========================================================\n";
echo "🏆 BATCH 5.3.4 COMPLETED SUCCESSFULLY!\n";
echo "   - Total Districts Seeded in Batch: {$seeded}\n";
echo "   - Current Total Active in Database: {$total_active}\n";
echo "========================================================\n\n";
