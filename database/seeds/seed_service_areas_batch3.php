<?php
/**
 * AR Entertainment - Phase 5.3 District Filming Guides Seeder (Batch 5.3.3: Districts 22–32 — Sylhet, Mymensingh & Regional Hubs)
 * 
 * Ingests 11 District Filming Location Guides:
 * 1.  Sylhet (video-production-company-in-sylhet)
 * 2.  Moulvibazar (video-production-company-in-maulvibazar)
 * 3.  Sunamganj (video-production-company-in-sunamganj)
 * 4.  Habiganj (video-production-company-in-habiganj)
 * 5.  Mymensingh (video-production-company-in-mymensingh)
 * 6.  Jamalpur (video-production-company-in-jamalpur)
 * 7.  Netrokona (video-production-company-in-netrokona)
 * 8.  Sherpur (video-production-company-in-sherpur)
 * 9.  Kishoreganj (video-production-company-in-kishoreganj)
 * 10. Rajbari (video-production-company-in-rajbari)
 * 11. Shariatpur (video-production-company-in-shariatpur)
 * 
 * Features:
 * - 100% Clean AR Entertainment Branding (0% Legacy Names)
 * - Cinema Logistics & Location Breakdown (Haor Boat Rigs, Rainforest Expeditions, Border Permissions)
 * - Rich Responsive HTML Guide
 * - Idempotent MySQL Upsert (ON DUPLICATE KEY UPDATE)
 */

declare(strict_types=1);

require_once __DIR__ . '/../../config/config.php';
require_once __DIR__ . '/../../config/db.php';
require_once __DIR__ . '/../../config/helpers.php';

echo "========================================================\n";
echo "🎬 AR ENTERTAINMENT - SEED DISTRICT GUIDES BATCH 5.3.3 (22–32: SYLHET & MYMENSINGH)\n";
echo "========================================================\n\n";

$db = db();

$districts_batch3 = [
    // 1. Sylhet
    [
        'city_name' => 'Sylhet',
        'slug' => 'video-production-company-in-sylhet',
        'title' => 'Video Production Company in Sylhet | AR Entertainment',
        'summary' => 'High-end tea garden cinematography, Ratargul freshwater swamp filming, Jaflong stone beds, and Lalakhal river video production in Sylhet by AR Entertainment.',
        'content' => '<h3>Premier Film Production &amp; Nature Cinematography in Sylhet</h3>
<p>Surrounded by emerald green tea estates, pristine mountain streams, and the mystical freshwater swamp forest of Ratargul, <strong>Sylhet</strong> is one of Bangladesh\'s most iconic film and commercial shooting destinations.</p>
<h4>Top Filming Hotspots &amp; Scenic Landscapes</h4>
<ul>
    <li><strong>Ratargul Freshwater Swamp Forest:</strong> The only swamp forest in Bangladesh, featuring submerged Koroch and Murta trees navigated via silent wooden canoes.</li>
    <li><strong>Jaflong &amp; Piyain River:</strong> Dramatic rolling hills on the Indian border, crystal clear flowing riverbeds, and traditional stone collectors.</li>
    <li><strong>Lalakhal (Blue River):</strong> Captivating emerald-green river flowing from the Meghalaya hills, perfect for sunrise drone sweeps and luxury resort shoots.</li>
    <li><strong>Malnicherra &amp; Lakkatura Tea Estates:</strong> Historic 19th-century sprawling tea plantations with picturesque undulating canopy vistas.</li>
    <li><strong>Shah Jalal &amp; Shah Paran Shrines:</strong> Spiritual heritage courtyards with centuries-old pigeons, lamps, and devotional atmosphere.</li>
</ul>
<h4>Local Production Logistics, Permits &amp; Camera Rigs</h4>
<p><strong>AR Entertainment</strong> deploys weather-sealed RED &amp; ARRI camera packages, specialized shallow-water camera boats for Ratargul, and manages border security (BGB) clearances across Jaflong and Tamabil.</p>',
        'sort_order' => 22,
        'meta_title' => 'Video Production Company in Sylhet | AR Entertainment',
        'meta_description' => 'Top video production company & film fixer in Sylhet, Bangladesh. Tea garden commercials, Ratargul swamp filming, and cinema drone video by AR Entertainment.'
    ],

    // 2. Moulvibazar (Maulvibazar / Sreemangal)
    [
        'city_name' => 'Moulvibazar',
        'slug' => 'video-production-company-in-maulvibazar',
        'title' => 'Video Production Company in Moulvibazar | AR Entertainment',
        'summary' => 'Tea Capital Sreemangal filming, Lawachara rainforest expeditions, Madhabkunda waterfall shoots, and luxury eco-resort commercials by AR Entertainment.',
        'content' => '<h3>Tea Capital Cinematography &amp; Rainforest Documentaries in Moulvibazar</h3>
<p>Celebrated as the "Tea Capital of Bangladesh," <strong>Moulvibazar</strong> (including Sreemangal) offers endless manicured tea gardens, the biodiversity-rich Lawachara National Park rainforest, and dramatic hill cascades.</p>
<h4>Key Filming Locations in Moulvibazar</h4>
<ul>
    <li><strong>Sreemangal Tea Valleys:</strong> The world\'s highest density of tea plantations, seven-layer tea stalls, and winding scenic cycling tracks.</li>
    <li><strong>Lawachara National Park:</strong> Dense evergreen rainforest home to the endangered Western Hoolock Gibbon, historic railway tracks, and Khasia tribal villages.</li>
    <li><strong>Madhabkunda &amp; Hum Hum Waterfalls:</strong> Spectacular natural rock waterfalls plunging into rocky forest pools.</li>
    <li><strong>Baikka Beel Wetland Sanctuary:</strong> Vast lotus lakes teeming with thousands of migratory birds and elevated watchtowers.</li>
</ul>
<h4>Rainforest Filming Permits &amp; Sound Recording</h4>
<p>We provide Forest Department filming authorizations, specialized wildlife telephoto lenses, and directional acoustic microphones for pristine ambient rainforest sound capture.</p>',
        'sort_order' => 23,
        'meta_title' => 'Video Production Company in Moulvibazar | AR Entertainment',
        'meta_description' => 'Professional video production & film fixers in Moulvibazar & Sreemangal. Lawachara rainforest shoots, tea garden TVCs, and eco-resort films by AR Entertainment.'
    ],

    // 3. Sunamganj
    [
        'city_name' => 'Sunamganj',
        'slug' => 'video-production-company-in-sunamganj',
        'title' => 'Video Production Company in Sunamganj | AR Entertainment',
        'summary' => 'Tanguar Haor aquatic sea vistas, Shimul Bagan red silk cotton blossom aerials, and Jadukata River crystal shoots in Sunamganj by AR Entertainment.',
        'content' => '<h3>Haor Wetland Cinema &amp; Blossom Landscape Filming in Sunamganj</h3>
<p>Home to the globally renowned <strong>Tanguar Haor</strong> (UNESCO Ramsar wetland), <strong>Sunamganj</strong> transforms into a shimmering inland freshwater sea during the monsoon, and a vibrant bird sanctuary in the winter.</p>
<h4>Iconic Sunamganj Filming Locations</h4>
<ul>
    <li><strong>Tanguar Haor:</strong> A colossal 100-square-kilometer wetland expanse with watchtowers, floating houseboat communities, and endless water horizons.</li>
    <li><strong>Shimul Bagan (Tahirpur):</strong> Asia\'s largest red silk cotton (Shimul) tree garden, creating a mesmerizing crimson canopy blossom in spring.</li>
    <li><strong>Jadukata River &amp; Barek Tila:</strong> Turquoise mountain water winding along the base of lush green hill ridges along the international border.</li>
    <li><strong>Hason Raja Heritage Museum:</strong> The historic estate of the legendary mystic philosopher poet overlooking the Surma River.</li>
</ul>
<h4>Houseboat Production Base &amp; Marine Logistics</h4>
<p>AR Entertainment charters luxury production houseboats equipped with onboard generators, Starlink satellite comms, and marine drone launch pads for multi-day Haor shoots.</p>',
        'sort_order' => 24,
        'meta_title' => 'Video Production Company in Sunamganj | AR Entertainment',
        'meta_description' => 'Cinematic video production in Sunamganj, Bangladesh. Tanguar Haor houseboat filming, Shimul Bagan aerials, and Jadukata river commercials by AR Entertainment.'
    ],

    // 4. Habiganj
    [
        'city_name' => 'Habiganj',
        'slug' => 'video-production-company-in-habiganj',
        'title' => 'Video Production Company in Habiganj | AR Entertainment',
        'summary' => 'Satchari National Park wildlife filming, Rema-Kalenga deep forest expeditions, rubber estate visuals, and natural gas plant corporate video production.',
        'content' => '<h3>Wildlife Sanctuaries &amp; Energy Sector Filming in Habiganj</h3>
<p>Nestled in the Surma valley, <strong>Habiganj</strong> combines pristine protected jungle reserves (Satchari &amp; Rema-Kalenga), sprawling rubber estates, and modern ceramic manufacturing hubs.</p>
<h4>Top Filming Hotspots in Habiganj</h4>
<ul>
    <li><strong>Satchari National Park:</strong> Deciduous evergreen forest featuring canopy walking bridges, Tipra indigenous hamlets, and diverse primate species.</li>
    <li><strong>Rema-Kalenga Wildlife Sanctuary:</strong> The second-largest natural forest reserve in Bangladesh, ideal for deep-jungle documentary expeditions.</li>
    <li><strong>Bahubal &amp; Chunarughat Tea Estates:</strong> Rolling green plantations with colonial-era manager bungalows and organic tea nurseries.</li>
    <li><strong>Shahjibazar Power Plant &amp; Industrial EPZs:</strong> Modern natural gas turbine energy complexes and large-scale industrial plants.</li>
</ul>
<h4>Forest Logistics &amp; Industrial Clearances</h4>
<p>AR Entertainment secures Forest Department permits, provides off-road 4x4 vehicles, and supplies industrial safety gear for energy sector corporate video shoots.</p>',
        'sort_order' => 25,
        'meta_title' => 'Video Production Company in Habiganj | AR Entertainment',
        'meta_description' => 'Video production & documentary fixers in Habiganj, Bangladesh. Satchari forest filming, tea estate commercials, and industrial AVs by AR Entertainment.'
    ],

    // 5. Mymensingh
    [
        'city_name' => 'Mymensingh',
        'slug' => 'video-production-company-in-mymensingh',
        'title' => 'Video Production Company in Mymensingh | AR Entertainment',
        'summary' => 'Old Brahmaputra riverfront filming, BAU university agricultural campus, Shashi Lodge zamindar palace, and fisheries corporate video production in Mymensingh.',
        'content' => '<h3>Academic Heritage, Riverfront Cinema &amp; Agro-Tech Filming in Mymensingh</h3>
<p>Situated on the banks of the Old Brahmaputra river, <strong>Mymensingh</strong> is a major regional education and agricultural hub, celebrated for classical Victorian mansions and Bangladesh Agricultural University.</p>
<h4>Top Filming Hotspots in Mymensingh</h4>
<ul>
    <li><strong>Shashi Lodge &amp; Alexander Castle:</strong> 19th-century royal zamindar palaces featuring Greek marble statues, wooden spiral staircases, and ornate ballrooms.</li>
    <li><strong>Bangladesh Agricultural University (BAU) Campus:</strong> A colossal 1,200-acre green campus with experimental farms, botanical gardens, and research canals.</li>
    <li><strong>Old Brahmaputra River Promenade:</strong> Broad sandy riverbanks, traditional wooden country boats, and scenic sunset reflections.</li>
    <li><strong>Muktagacha Zamindar Bari:</strong> Renowned for historical royal courtyards and the birthplace of authentic Muktagacha Monda sweets.</li>
</ul>
<h4>Turnkey Production Crew &amp; Heritage Permits</h4>
<p>AR Entertainment coordinates Department of Archaeology approvals for Shashi Lodge, manages campus filming permissions at BAU, and deploys high-speed drone units for river sweeps.</p>',
        'sort_order' => 26,
        'meta_title' => 'Video Production Company in Mymensingh | AR Entertainment',
        'meta_description' => 'Top video production company in Mymensingh, Bangladesh. Shashi Lodge heritage shoots, BAU campus documentaries, and agricultural commercials by AR Entertainment.'
    ],

    // 6. Jamalpur
    [
        'city_name' => 'Jamalpur',
        'slug' => 'video-production-company-in-jamalpur',
        'title' => 'Video Production Company in Jamalpur | AR Entertainment',
        'summary' => 'Nakshi Kantha handcraft artisan filming, Brahmaputra riverine char documentaries, and jute agro-industry video production in Jamalpur by AR Entertainment.',
        'content' => '<h3>Artisan Crafts, River Chars &amp; Rural Livelihood Filming in Jamalpur</h3>
<p>Famed across Bengal for authentic hand-embroidered <strong>Nakshi Kantha</strong> quilts, <strong>Jamalpur</strong> offers picturesque riverine char life along the Brahmaputra and traditional cottage craft communities.</p>
<h4>Prime Filming Locations in Jamalpur</h4>
<ul>
    <li><strong>Nakshi Kantha Artisan Villages:</strong> Women artisans hand-stitching intricate traditional Bengali embroidered quilts and folklore designs.</li>
    <li><strong>Brahmaputra River Chars &amp; Sand Dunes:</strong> Vast sweeping sandbanks with seasonal mustard blossoms, peanut fields, and pastoral cattle herds.</li>
    <li><strong>Lojjar Pahar &amp; Garo Border Footpaths:</strong> Rolling border terrain with scenic bamboo forests and rustic trails.</li>
    <li><strong>Jamalpur Jute &amp; Agro Mills:</strong> Processing facilities showcasing golden fibre jute extraction and sorting lines.</li>
</ul>
<h4>Artisan Community Access &amp; Off-Grid Filming</h4>
<p>We provide localized cultural liaisons, respectful artisan community access, portable power generation for remote char locations, and high-CRI lighting kits.</p>',
        'sort_order' => 27,
        'meta_title' => 'Video Production Company in Jamalpur | AR Entertainment',
        'meta_description' => 'Professional video production in Jamalpur, Bangladesh. Nakshi Kantha artisan documentaries, river char drone footage, and agro commercials by AR Entertainment.'
    ],

    // 7. Netrokona
    [
        'city_name' => 'Netrokona',
        'slug' => 'video-production-company-in-netrokona',
        'title' => 'Video Production Company in Netrokona | AR Entertainment',
        'summary' => 'Birisiri white ceramic hill filming, crystal clear Shomeshwari River boat shoots, and Garo/Hajong indigenous cultural documentaries in Netrokona.',
        'content' => '<h3>Ceramic Hill Landscapes &amp; Indigenous Culture in Netrokona</h3>
<p>Nestled against the Meghalaya border, <strong>Netrokona</strong> (Durgapur / Birisiri) is famous for its breathtaking white porcelain clay hills, vibrant turquoise quarry lakes, and rich Garo and Hajong indigenous heritage.</p>
<h4>Top Filming Hotspots in Netrokona</h4>
<ul>
    <li><strong>Birisiri White Ceramic Clay Hills:</strong> Brilliant white, pink, and pale yellow clay formations surrounding a deep blue-green lake basin.</li>
    <li><strong>Shomeshwari River:</strong> Translucent mountain stream flowing over smooth pebbles where coal extractors and boatmen work against mountain backdrops.</li>
    <li><strong>Tribal Cultural Academy &amp; Garo Villages:</strong> Traditional Wangala harvest festival dances, bamboo architecture, and handloom weaving.</li>
    <li><strong>Ranikhong Catholic Church &amp; Hilltop:</strong> Century-old stone church perched majestically atop a high ridge overlooking the Shomeshwari river.</li>
</ul>
<h4>Border Clearances &amp; Expedition Rigs</h4>
<p>AR Entertainment coordinates border security (BGB) clearances for Durgapur, provides 4x4 transport across riverbeds, and operates lightweight cinema rigs for hill ascents.</p>',
        'sort_order' => 28,
        'meta_title' => 'Video Production Company in Netrokona | AR Entertainment',
        'meta_description' => 'Video production & documentary fixers in Netrokona (Birisiri/Durgapur). Ceramic hills aerials, Shomeshwari river shoots, and indigenous films by AR Entertainment.'
    ],

    // 8. Sherpur
    [
        'city_name' => 'Sherpur',
        'slug' => 'video-production-company-in-sherpur',
        'title' => 'Video Production Company in Sherpur | AR Entertainment',
        'summary' => 'Garo Hill border ranges, Madhutila Eco Park, Gazni Abakash forest retreats, and wild elephant trail documentaries in Sherpur by AR Entertainment.',
        'content' => '<h3>Border Hill Ranges &amp; Wildlife Habitat Filming in Sherpur</h3>
<p>Positioned along the northern frontier of Bangladesh, <strong>Sherpur</strong> features lush undulating Garo hill ranges, natural pine and sal forests, and protected eco-tourism corridors.</p>
<h4>Key Filming Locations in Sherpur</h4>
<ul>
    <li><strong>Gajni Abakash (Jhenaigati):</strong> Scenic hill resort complex featuring natural lakes, suspended walking bridges, watchtowers, and pine forest knolls.</li>
    <li><strong>Madhutila Eco Park (Nalitabari):</strong> Expansive green undulating hills on the international border with winding stone trails and medicinal plant gardens.</li>
    <li><strong>Border Wild Elephant Trails:</strong> Natural seasonal migratory paths for Asian wild elephants moving between Meghalaya and northern Bangladesh.</li>
    <li><strong>Baromari St. Leo Church &amp; Pilgrim Center:</strong> Historic Christian shrine set within peaceful forest hills.</li>
</ul>
<h4>Eco-Tourism &amp; Border Filming Fixers</h4>
<p>We manage local district administration passes, security escorts for border filming zones, and deploy heavy-duty drone systems for vast canopy panoramas.</p>',
        'sort_order' => 29,
        'meta_title' => 'Video Production Company in Sherpur | AR Entertainment',
        'meta_description' => 'Cinematic video production & film fixers in Sherpur, Bangladesh. Garo hill drone footage, Madhutila eco-park commercials, and forest documentaries by AR Entertainment.'
    ],

    // 9. Kishoreganj
    [
        'city_name' => 'Kishoreganj',
        'slug' => 'video-production-company-in-kishoreganj',
        'title' => 'Video Production Company in Kishoreganj | AR Entertainment',
        'summary' => 'Nikli Haor water highway filming, historic Jangalbari Fort of Isha Khan, and traditional boat transport documentaries in Kishoreganj by AR Entertainment.',
        'content' => '<h3>Haor Waterways &amp; Mughal Historical Cinema in Kishoreganj</h3>
<p>Home to the awe-inspiring <strong>Nikli Haor</strong> and the historic seat of Bengal\'s legendary ruler Isha Khan, <strong>Kishoreganj</strong> blends colossal seasonal water expanses with rich architectural antiquity.</p>
<h4>Prime Filming Locations in Kishoreganj</h4>
<ul>
    <li><strong>Nikli Haor &amp; All-Weather Highway:</strong> A magnificent elevated road cutting directly across endless freshwater seas, popular for music videos and automotive commercials.</li>
    <li><strong>Jangalbari Fort (Isha Khan Estate):</strong> 16th-century Mughal fort ruins, ancient mosques, and royal residential quarters.</li>
    <li><strong>Gurudayal Govt. College &amp; Narsunda Riverwalk:</strong> Urban promenade bridges, manicured waterfronts, and cultural open-air theaters.</li>
    <li><strong>Austagram Historic 16th-Century Mosque:</strong> Five-domed Mughal architectural jewel surrounded by haor floodplains.</li>
</ul>
<h4>Waterborne Camera Rigs &amp; High-Speed Drone Operations</h4>
<p>AR Entertainment deploys specialized speedboats with stabilized camera gimbals for high-speed tracking along the Nikli all-weather road and across open waters.</p>',
        'sort_order' => 30,
        'meta_title' => 'Video Production Company in Kishoreganj | AR Entertainment',
        'meta_description' => 'Top video production company in Kishoreganj, Bangladesh. Nikli Haor aerials, all-weather road shoots, and historical documentaries by AR Entertainment.'
    ],

    // 10. Rajbari
    [
        'city_name' => 'Rajbari',
        'slug' => 'video-production-company-in-rajbari',
        'title' => 'Video Production Company in Rajbari | AR Entertainment',
        'summary' => 'Goalando Ghat river port filming, historic railway transit hubs, Padma River Hilsa fisheries, and zamindar heritage documentaries in Rajbari.',
        'content' => '<h3>River Ports, Railway Heritage &amp; Riverine Livelihoods in Rajbari</h3>
<p>Strategically situated on the southern banks of the mighty Padma river, <strong>Rajbari</strong> is steeped in 19th-century railway heritage, river trade, and the legendary river port of Goalando Ghat.</p>
<h4>Top Filming Hotspots in Rajbari</h4>
<ul>
    <li><strong>Goalando Ghat &amp; Daulatdia Ferry Terminal:</strong> Major transport confluence featuring bustling cargo barges, passenger steamers, and river life.</li>
    <li><strong>Kalyan Dighi &amp; Royal Zamindar Estates:</strong> 300-year-old historic water reservoirs and zamindar palace arches.</li>
    <li><strong>Padma Riverbank Char Formations:</strong> Expansive agricultural fields, mustard crops, and artisanal freshwater Hilsa fishermen.</li>
    <li><strong>Historic Rajbari Railway Junction:</strong> British-era railway architecture, vintage locomotives, and historic signal cabins.</li>
</ul>
<h4>River Police Clearances &amp; Commercial Equipment</h4>
<p>AR Entertainment provides authorized river police permits, camera chase boats for ferry operations, and drone flights across the Padma riverbanks.</p>',
        'sort_order' => 31,
        'meta_title' => 'Video Production Company in Rajbari | AR Entertainment',
        'meta_description' => 'Professional video production in Rajbari, Bangladesh. Goalando Ghat ferry filming, Padma river drone shots, and railway heritage documentaries by AR Entertainment.'
    ],

    // 11. Shariatpur
    [
        'city_name' => 'Shariatpur',
        'slug' => 'video-production-company-in-shariatpur',
        'title' => 'Video Production Company in Shariatpur | AR Entertainment',
        'summary' => 'Padma Bridge southern expressway connection, Burirhat char lands, riverbank agro stories, and modern infrastructure video production in Shariatpur.',
        'content' => '<h3>Mega-Infrastructure &amp; Riverine Delta Documentaries in Shariatpur</h3>
<p>Connected directly to the national capital via the monumental Padma Bridge link, <strong>Shariatpur</strong> is a dynamic riverine delta district combining rapid infrastructure development with fertile agro-chars.</p>
<h4>Key Filming Locations in Shariatpur</h4>
<ul>
    <li><strong>Padma Bridge South Expressway Corridor:</strong> State-of-the-art multi-lane elevated highways, toll plazas, and modern transit perspectives.</li>
    <li><strong>Burirhat Char &amp; Kirtinasha Riverbanks:</strong> Winding river streams, floating duck farms, and rich deltaic harvests.</li>
    <li><strong>Fatehjungpur Historic Fort &amp; Zamindar Bari:</strong> Centuries-old heritage brick monuments and traditional rural pond enclosures.</li>
    <li><strong>Modern Agro &amp; Dairy Farming Belts:</strong> Large-scale automated livestock farms and organic agricultural fields.</li>
</ul>
<h4>Fast Dispatch from Dhaka &amp; Drone Flight Clearances</h4>
<p>Located just 1 hour from Dhaka via the expressway, AR Entertainment provides same-day crew dispatch, mobile camera trucks, and certified drone flights across the Padma bridge approach roads.</p>',
        'sort_order' => 32,
        'meta_title' => 'Video Production Company in Shariatpur | AR Entertainment',
        'meta_description' => 'Video production services in Shariatpur, Bangladesh. Padma Bridge southern expressway shoots, agro corporate films, and delta documentaries by AR Entertainment.'
    ]
];

$stmt = $db->prepare("
    INSERT INTO service_areas (city_name, slug, title, summary, content, sort_order, meta_title, meta_description, status, created_at, updated_at)
    VALUES (:city_name, :slug, :title, :summary, :content, :sort_order, :meta_title, :meta_description, 'active', NOW(), NOW())
    ON DUPLICATE KEY UPDATE
        city_name = VALUES(city_name),
        title = VALUES(title),
        summary = VALUES(summary),
        content = VALUES(content),
        sort_order = VALUES(sort_order),
        meta_title = VALUES(meta_title),
        meta_description = VALUES(meta_description),
        status = 'active',
        updated_at = NOW()
");

$seeded_count = 0;
foreach ($districts_batch3 as $d) {
    $stmt->execute([
        ':city_name' => $d['city_name'],
        ':slug' => $d['slug'],
        ':title' => $d['title'],
        ':summary' => $d['summary'],
        ':content' => $d['content'],
        ':sort_order' => $d['sort_order'],
        ':meta_title' => $d['meta_title'],
        ':meta_description' => $d['meta_description']
    ]);
    $seeded_count++;
    echo "   ✅ [{$seeded_count}/11] Seeded District Guide: {$d['city_name']} ({$d['slug']})\n";
}

$total_in_db = (int)$db->query("SELECT COUNT(*) FROM service_areas WHERE status = 'active'")->fetchColumn();

echo "\n========================================================\n";
echo "🏆 BATCH 5.3.3 COMPLETED SUCCESSFULLY!\n";
echo "   - Total Districts Seeded in Batch: {$seeded_count}\n";
echo "   - Current Total Active in Database: {$total_in_db}\n";
echo "========================================================\n\n";
