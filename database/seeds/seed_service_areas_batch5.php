<?php
/**
 * AR Entertainment - Phase 5.3 District Filming Guides Seeder (Batch 5.3.5: Districts 41–48 — Rangpur Division)
 * 
 * Ingests 8 District Filming Location Guides for Rangpur Division:
 * 1. Rangpur (video-production-company-in-rangpur)
 * 2. Dinajpur (video-production-company-in-dinajpur)
 * 3. Kurigram (video-production-company-in-kurigram)
 * 4. Lalmonirhat (video-production-company-in-lalmonirhat)
 * 5. Nilphamari (video-production-company-in-nilphamari)
 * 6. Gaibandha (video-production-company-in-gaibandha)
 * 7. Panchagarh (video-production-company-in-panchagarh)
 * 8. Thakurgaon (video-production-company-in-thakurgaon)
 * 
 * Features:
 * - 100% Clean AR Entertainment Branding (0% Legacy Names)
 * - Cinema Logistics & Location Breakdown (Himalayan Vistas, Kantajew Terracotta, Teesta Barrage, Tea Plains)
 * - Rich Responsive HTML Guide
 * - Idempotent MySQL Upsert (ON DUPLICATE KEY UPDATE)
 */

declare(strict_types=1);

require_once __DIR__ . '/../../config/config.php';
require_once __DIR__ . '/../../config/db.php';
require_once __DIR__ . '/../../config/helpers.php';

echo "========================================================\n";
echo "🎬 AR ENTERTAINMENT - SEED DISTRICT GUIDES BATCH 5.3.5 (41–48: RANGPUR DIVISION)\n";
echo "========================================================\n\n";

$db = db();

$districts_batch5 = [
    // 1. Rangpur
    [
        'city_name' => 'Rangpur',
        'slug' => 'video-production-company-in-rangpur',
        'title' => 'Video Production Company in Rangpur | AR Entertainment',
        'summary' => 'Tajhat Palace colonial cinema shoots, Carmichael College heritage architecture, and commercial TVC production in Rangpur by AR Entertainment.',
        'content' => '<h3>Palace Heritage Cinematography &amp; Commercial Production in Rangpur</h3>
<p>As the historic divisional center of North Bengal, <strong>Rangpur</strong> is home to magnificent neoclassical zamindar palaces, heritage university campuses, and flourishing agro-commercial enterprises.</p>
<h4>Top Filming Hotspots &amp; Architectural Landmarks</h4>
<ul>
    <li><strong>Tajhat Palace (Rangpur Museum):</strong> Grand 20th-century Greco-Roman royal palace featuring an imposing white marble central staircase, U-shaped facade, and manicured ornamental gardens.</li>
    <li><strong>Carmichael College Campus:</strong> Sprawling 1916 heritage campus adorned with colonial Indo-Saracenic red brick buildings and sweeping banyan boulevards.</li>
    <li><strong>Chikli Beel &amp; Water Park:</strong> Picturesque urban waterbody offering serene reflections, paddle-boat tracking shots, and recreational family TVC settings.</li>
    <li><strong>Pairabandh (Begum Rokeya Memorial):</strong> Cultural heritage center and museum celebrating Bangladesh\'s pioneering feminist thinker.</li>
    <li><strong>Teesta Floodplain Agricultural Belts:</strong> Vast rural riverine landscapes showcasing agro-farming innovations and green field vistas.</li>
</ul>
<h4>Local Production Logistics, Heritage Passes &amp; Camera Rigs</h4>
<p><strong>AR Entertainment</strong> coordinates formal Department of Archaeology filming authorizations for Tajhat Palace, deploys RED/ARRI cine packages, and manages North Bengal mobile production units.</p>',
        'sort_order' => 41,
        'meta_title' => 'Video Production Company in Rangpur | AR Entertainment',
        'meta_description' => 'Top video production company & film fixer in Rangpur, Bangladesh. Tajhat Palace filming, Carmichael College shoots, and commercial TVC production by AR Entertainment.'
    ],

    // 2. Dinajpur
    [
        'city_name' => 'Dinajpur',
        'slug' => 'video-production-company-in-dinajpur',
        'title' => 'Video Production Company in Dinajpur | AR Entertainment',
        'summary' => 'Kantajew Temple terracotta masterpiece cinematography, Ramsagar historic lake filming, and premium Kataribhog rice commercial video production in Dinajpur by AR Entertainment.',
        'content' => '<h3>Terracotta Architectural Masterpieces &amp; Ancient Waters in Dinajpur</h3>
<p>Renowned for the world\'s finest terracotta temple architecture and fragrant Kataribhog rice, <strong>Dinajpur</strong> offers extraordinary visual textures for historical documentaries and commercial productions.</p>
<h4>Key Filming Locations in Dinajpur</h4>
<ul>
    <li><strong>Kantajew (Kantanagar) Temple:</strong> 1752 Navaratna Hindu temple featuring hundreds of square meters of intricate terracotta panel reliefs depicting epic mythology and Mughal court life.</li>
    <li><strong>Ramsagar Dighi:</strong> Bangladesh\'s largest historical man-made reservoir (4.37 lakh sq meters) surrounded by mature forest mounds, ideal for sunset drone sweeps.</li>
    <li><strong>Dinajpur Rajbari Palace:</strong> Historic ruins of the Maharaja\'s royal seat, including the Krishna Temple and ornate Aina Mahal palace gates.</li>
    <li><strong>Nayabad Mosque:</strong> 18th-century three-domed brick mosque built by the master masons of Kantajew Temple.</li>
    <li><strong>Aromatic Kataribhog &amp; Litchi Orchards:</strong> Vast lush litchi orchards (Madraji &amp; Bedana varieties) during springtime blossom and harvest.</li>
</ul>
<h4>Archaeological Fixer &amp; Low-Light Macro Cine Kits</h4>
<p>We provide specialized macro cinema lenses for capturing millimeter-level terracotta craftsmanship, high-CRI soft light arrays for delicate relief preservation, and district administration clearances.</p>',
        'sort_order' => 42,
        'meta_title' => 'Video Production Company in Dinajpur | AR Entertainment',
        'meta_description' => 'Leading video production company & fixer in Dinajpur, Bangladesh. Kantajew Temple terracotta filming, Ramsagar lake drone video, and Kataribhog commercial shoots.'
    ],

    // 3. Kurigram
    [
        'city_name' => 'Kurigram',
        'slug' => 'video-production-company-in-kurigram',
        'title' => 'Video Production Company in Kurigram | AR Entertainment',
        'summary' => 'Brahmaputra & Dharla river char community documentaries, Chilmari river port folk heritage, and borderlands cinematography in Kurigram by AR Entertainment.',
        'content' => '<h3>Riverine Island (Char) Cinema &amp; Grassroots Documentaries in Kurigram</h3>
<p>Where sixteen transboundary rivers, including the mighty Brahmaputra and Dharla, enter Bangladesh, <strong>Kurigram</strong> provides profound river delta landscapes, dynamic char islands, and poignant climate resilience stories.</p>
<h4>Iconic Kurigram Filming Locations</h4>
<ul>
    <li><strong>Dharla River &amp; Sheikh Hasina Dharla Bridge:</strong> Expansive sweeping water horizons, seasonal golden sandy char expanses, and sunset fishermen silhouettes.</li>
    <li><strong>Chilmari Historic River Port:</strong> Fabled river terminal immortalized in timeless Bhawaiya folk songs, bustling with wooden country cargo fleets.</li>
    <li><strong>Brahmaputra Remote Char Communities:</strong> Nomadic island settlements offering authentic human-interest and NGO developmental documentary settings.</li>
    <li><strong>Shahi Mosque of Shingimari:</strong> Ancient historic place of worship dating back several centuries in the northern border corridor.</li>
</ul>
<h4>Char Island Logistics &amp; Solar Cine Power Bases</h4>
<p>AR Entertainment deploys shallow-draft motorized catamarans, off-grid solar and generator battery charging hubs, and bilingual local community facilitators for remote char productions.</p>',
        'sort_order' => 43,
        'meta_title' => 'Video Production Company in Kurigram | AR Entertainment',
        'meta_description' => 'Professional video production & fixer in Kurigram, Bangladesh. Brahmaputra char documentaries, Dharla river drone sweeps, and NGO filming by AR Entertainment.'
    ],

    // 4. Lalmonirhat
    [
        'city_name' => 'Lalmonirhat',
        'slug' => 'video-production-company-in-lalmonirhat',
        'title' => 'Video Production Company in Lalmonirhat | AR Entertainment',
        'summary' => 'Teesta Barrage mega-waterway filming, historic Tin Bigha border corridor access, and aviation heritage cinematography in Lalmonirhat by AR Entertainment.',
        'content' => '<h3>Teesta Barrage Infrastructure &amp; Historic Borderland Filming in Lalmonirhat</h3>
<p>Bordering India along the scenic northern frontier, <strong>Lalmonirhat</strong> features monumental hydraulic barrage structures, historic wartime airfields, and picturesque riverbed channels.</p>
<h4>Top Filming Hotspots in Lalmonirhat</h4>
<ul>
    <li><strong>Teesta Barrage (Dalia):</strong> Bangladesh\'s largest irrigation barrage spanning 615 meters with 44 radial sluice gates, creating dramatic foaming water discharges and vast reservoir aerials.</li>
    <li><strong>Tin Bigha Corridor &amp; Dahagram-Angarpota:</strong> Unique political geography enclave featuring historical border checkpoint access and tranquil rural enclaves.</li>
    <li><strong>Historic WWII Lalmonirhat Airport:</strong> Massive decommissioned 1940s Allied military airfield runways amidst open green rural plains.</li>
    <li><strong>Mogolhat Border &amp; Dharla River Basin:</strong> Serene river meanders, railway transit junctions, and seasonal white kashful blossom fields.</li>
</ul>
<h4>Border Security Clearances &amp; Waterway Drone Rigs</h4>
<p>We manage Border Guard Bangladesh (BGB) and Water Development Board filming authorizations, providing high-wind resistant drone platforms for panoramic Teesta Barrage sweeps.</p>',
        'sort_order' => 44,
        'meta_title' => 'Video Production Company in Lalmonirhat | AR Entertainment',
        'meta_description' => 'Expert video production company in Lalmonirhat, Bangladesh. Teesta Barrage filming, Tin Bigha corridor documentaries, and borderland drone video by AR Entertainment.'
    ],

    // 5. Nilphamari
    [
        'city_name' => 'Nilphamari',
        'slug' => 'video-production-company-in-nilphamari',
        'title' => 'Video Production Company in Nilphamari | AR Entertainment',
        'summary' => 'Saidpur British Railway Workshop heritage, Chini Mosque porcelain china mosaic filming, and Uttara EPZ industrial AV production in Nilphamari by AR Entertainment.',
        'content' => '<h3>Industrial Engineering Heritage &amp; Porcelain Mosaic Cinema in Nilphamari</h3>
<p>Boasting Bangladesh\'s oldest and largest railway engineering complex alongside modern export processing zones, <strong>Nilphamari</strong> blends Victorian industrial legacy with modern manufacturing power.</p>
<h4>Key Filming Locations in Nilphamari</h4>
<ul>
    <li><strong>Saidpur Railway Carriage Workshop (1870):</strong> Sprawling 110-acre historic railway works featuring active vintage heavy machinery, steam locomotive bays, and industrial textures.</li>
    <li><strong>Chini Mosque (Saidpur):</strong> 1863 architectural marvel adorned with over 25,000 pieces of authentic Victorian porcelain china plate mosaics and glass crystals.</li>
    <li><strong>Uttara Export Processing Zone (EPZ):</strong> Modern high-tech textile, wig, and apparel export manufacturing lines for corporate video productions.</li>
    <li><strong>Nilsagar Historic Lake (Dhobadhoba Dighi):</strong> 54-acre serene sanctuary lake attracting thousands of migratory waterfowl in winter.</li>
    <li><strong>Dimla Teesta Canal Network:</strong> Sweeping irrigation waterways framed by rolling green embankments.</li>
</ul>
<h4>Industrial Facility Safety Passes &amp; Heritage Permits</h4>
<p>AR Entertainment coordinates Ministry of Railways permits for Saidpur workshops, BEPZA clearances for Uttara EPZ, and deploys high-speed LED studio lights for detailed interior filming.</p>',
        'sort_order' => 45,
        'meta_title' => 'Video Production Company in Nilphamari | AR Entertainment',
        'meta_description' => 'Top video production company in Nilphamari & Saidpur. Saidpur railway workshop filming, Chini Mosque shoots, and Uttara EPZ corporate videos by AR Entertainment.'
    ],

    // 6. Gaibandha
    [
        'city_name' => 'Gaibandha',
        'slug' => 'video-production-company-in-gaibandha',
        'title' => 'Video Production Company in Gaibandha | AR Entertainment',
        'summary' => 'Balasi Ghat Jamuna riverboat cinematography, rural theatre & folk drama documentaries, and agricultural supply-chain video production in Gaibandha by AR Entertainment.',
        'content' => '<h3>Jamuna Riverway Port Cinematography &amp; Folk Culture in Gaibandha</h3>
<p>Nestled along the western bank of the mighty Jamuna River, <strong>Gaibandha</strong> is celebrated for its bustling ferry ghats, rich folk performance culture, and flourishing agro-maize farming belts.</p>
<h4>Top Filming Hotspots in Gaibandha</h4>
<ul>
    <li><strong>Balasi Ghat (Jamuna River Port):</strong> Picturesque historic railway ferry terminal with passenger boat fleets, dramatic river sunsets, and char transport life.</li>
    <li><strong>Teesta-Jamuna River Confluence:</strong> Monumental merging of two colossal river systems creating dynamic sand dunes, whirlpools, and dramatic horizons.</li>
    <li><strong>Grassroots Theatre &amp; Gono Natok Cultural Groups:</strong> Renowned folk theatre performers preserving traditional North Bengal storytelling and music.</li>
    <li><strong>Maize, Chilli &amp; Vegetable Char Belts:</strong> Vast emerald-to-crimson agricultural drying fields on seasonal river chars.</li>
    <li><strong>Bhabaniganj &amp; Bardhankuthi Ancient Ruins:</strong> Historical zamindar ponds, terracotta temples, and heritage banyan groves.</li>
</ul>
<h4>Riverboat Camera Mounts &amp; Audio Field Rigs</h4>
<p>We provide specialized gyro-stabilized gimbal setups for moving boat cinematography, directional microphones for capturing authentic folk music, and turnkey crew logistics.</p>',
        'sort_order' => 46,
        'meta_title' => 'Video Production Company in Gaibandha | AR Entertainment',
        'meta_description' => 'Leading video production company & fixer in Gaibandha, Bangladesh. Balasi Ghat river filming, Jamuna char documentaries, and agricultural commercial shoots.'
    ],

    // 7. Panchagarh
    [
        'city_name' => 'Panchagarh',
        'slug' => 'video-production-company-in-panchagarh',
        'title' => 'Video Production Company in Panchagarh | AR Entertainment',
        'summary' => 'Himalayan Kanchenjunga peak mountain cinematography, Tetulia flatland organic tea estates, and Banglabandha Zero-Point filming in Panchagarh by AR Entertainment.',
        'content' => '<h3>Himalayan Mountain Vistas &amp; Flatland Tea Cinema in Panchagarh</h3>
<p>As the northernmost tip of Bangladesh, <strong>Panchagarh</strong> is a premier destination offering breathtaking views of the snow-capped Himalayan peak Kanchenjunga, manicured flatland tea gardens, and crystal-clear stone rivers.</p>
<h4>Iconic Panchagarh Filming Locations</h4>
<ul>
    <li><strong>Tetulia Kanchenjunga Viewpoints (Mahananda Riverbank):</strong> Spectacular panoramic sightings of Mt. Kanchenjunga (8,586m) glowing crimson and gold during autumn and winter dawns.</li>
    <li><strong>Flatland Organic Tea Gardens:</strong> Sprawling organic tea plantations stretching across the plains up to the Indian border fence.</li>
    <li><strong>Banglabandha Land Port &amp; Zero Point:</strong> Strategic quadri-national transit point (Bangladesh-India-Nepal-Bhutan) featuring modern trade logistics and flag-lowering ceremonies.</li>
    <li><strong>Mahananda River Stone Extraction:</strong> Crystal clear flowing mountain streams where workers harvest natural river stones against dramatic hill backdrops.</li>
    <li><strong>Mirzapur Shahi Mosque &amp; Bodeshwar Temple:</strong> 17th-century terracotta mosque and ancient archaeological temple site.</li>
</ul>
<h4>Telephoto Cine Glass &amp; Border Clearances</h4>
<p>AR Entertainment deploys ultra-sharp 400mm-800mm telephoto cinema lenses for pristine mountain captures, cold-weather battery management, and BGB zero-line authorizations.</p>',
        'sort_order' => 47,
        'meta_title' => 'Video Production Company in Panchagarh | AR Entertainment',
        'meta_description' => 'Top video production company & fixer in Panchagarh & Tetulia. Kanchenjunga mountain filming, flatland tea commercials, and Banglabandha border video production.'
    ],

    // 8. Thakurgaon
    [
        'city_name' => 'Thakurgaon',
        'slug' => 'video-production-company-in-thakurgaon',
        'title' => 'Video Production Company in Thakurgaon | AR Entertainment',
        'summary' => 'Ancient Baliadangi Surya Puri giant mango tree documentaries, Jamalpur Shahi Mosque terracotta filming, and Tangon river cinematography in Thakurgaon by AR Entertainment.',
        'content' => '<h3>Ancient Botanical Wonders &amp; Mughal Heritage in Thakurgaon</h3>
<p>Located in the pristine northwest, <strong>Thakurgaon</strong> features South Asia\'s largest spreading historic mango tree, Mughal terracotta mosques, and tranquil agricultural riverways.</p>
<h4>Top Filming Hotspots &amp; Natural Landmarks</h4>
<ul>
    <li><strong>Baliadangi Ancient Surya Puri Mango Tree:</strong> Renowned as Asia\'s largest historic mango tree (over 200 years old), spanning more than 2 bighas with dozens of massive horizontal boughs touching the ground.</li>
    <li><strong>Jamalpur Shahi Mosque:</strong> 1867 terracotta-adorned Mughal-style mosque featuring intricate minarets and floral brick relief panels.</li>
    <li><strong>Haripur Rajbari Palace:</strong> Grand colonial zamindar palace ruins with imposing arched facades and colonnaded verandas.</li>
    <li><strong>Tangon &amp; Kulik River Basins:</strong> Peaceful winding northern rivers flanked by lush bamboo groves and mustard blossom fields in winter.</li>
    <li><strong>Sugarcane &amp; Agro Farming Landscapes:</strong> Vast green plantation belts supporting local agro-industrial sugar mills.</li>
</ul>
<h4>Drone Canopy Sweeps &amp; Heritage Fixer Support</h4>
<p>We provide dual-operator cinema drone teams for sweeping 360-degree canopy flights around the Baliadangi tree, alongside local community access and district permits.</p>',
        'sort_order' => 48,
        'meta_title' => 'Video Production Company in Thakurgaon | AR Entertainment',
        'meta_description' => 'Professional video production & fixer in Thakurgaon, Bangladesh. Baliadangi giant mango tree filming, Jamalpur Shahi Mosque shoots, and drone cinematography.'
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
foreach ($districts_batch5 as $d) {
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
echo "🏆 BATCH 5.3.5 COMPLETED SUCCESSFULLY!\n";
echo "   - Total Districts Seeded in Batch: {$seeded}\n";
echo "   - Current Total Active in Database: {$total_active}\n";
echo "========================================================\n\n";
