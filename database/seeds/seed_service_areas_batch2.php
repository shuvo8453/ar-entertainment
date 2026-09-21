<?php
/**
 * AR Entertainment - Phase 5.3 District Filming Guides Seeder (Batch 5.3.2: Districts 11–21 — Chattogram Division)
 * 
 * Ingests 11 District Filming Location Guides for Chattogram Division:
 * 1.  Chattogram (video-production-company-in-chittagong)
 * 2.  Cox's Bazar (video-production-company-in-coxsbazar)
 * 3.  Bandarban (video-production-company-in-bandarban)
 * 4.  Rangamati (video-production-company-in-rangamati)
 * 5.  Khagrachari (video-production-company-in-khagrachari)
 * 6.  Feni (video-production-company-in-feni)
 * 7.  Noakhali (video-production-company-in-noakhali)
 * 8.  Lakshmipur (video-production-company-in-lakshmipur)
 * 9.  Chandpur (video-production-company-in-chandpur)
 * 10. Cumilla (video-production-company-in-cumilla)
 * 11. Brahmanbaria (video-production-company-in-brahmanbaria)
 * 
 * Features:
 * - 100% Clean AR Entertainment Branding (0% Legacy Names)
 * - Cinema Logistics & Location Breakdown (Hotspots, Permitting, Mountain/Marine Transport, Camera Gear)
 * - Rich Responsive HTML Guide
 * - Idempotent MySQL Upsert (ON DUPLICATE KEY UPDATE)
 */

declare(strict_types=1);

require_once __DIR__ . '/../../config/config.php';
require_once __DIR__ . '/../../config/db.php';
require_once __DIR__ . '/../../config/helpers.php';

echo "========================================================\n";
echo "🎬 AR ENTERTAINMENT - SEED DISTRICT GUIDES BATCH 5.3.2 (11–21: CHATTOGRAM DIVISION)\n";
echo "========================================================\n\n";

$db = db();

$districts_batch2 = [
    // 1. Chattogram (Chittagong)
    [
        'city_name' => 'Chattogram',
        'slug' => 'video-production-company-in-chittagong',
        'title' => 'Video Production Company in Chattogram | AR Entertainment',
        'summary' => 'Commercial, marine, and industrial video production in Chattogram. Operating RED & ARRI cinema packages across Patenga Beach, Karnaphuli Port, Foy\'s Lake, and CEPZ.',
        'content' => '<h3>Premier Cinema Video Production &amp; Commercial Filming in Chattogram</h3>
<p>As Bangladesh\'s premier seaport and commercial gateway, <strong>Chattogram</strong> offers filmmakers an unparalleled mix of deep-water maritime horizons, rolling green coastal hills, mega-infrastructure bridges, and bustling international container terminals.</p>
<h4>Top Filming Hotspots &amp; Industrial Landscapes</h4>
<ul>
    <li><strong>Karnaphuli River &amp; Seaport:</strong> The iconic Bangabandhu Tunnel entrance, massive container ships, and naval harbor sweeps.</li>
    <li><strong>Patenga Beach &amp; Coastal Marine Drive:</strong> Expansive sunset seascapes, breakwater wave splashes, and golden hour coastal drone cinematography.</li>
    <li><strong>Foy\'s Lake &amp; Batali Hill:</strong> Serene inland freshwater lakes surrounded by lush forested hills and bird\'s-eye cityscape panoramas.</li>
    <li><strong>Industrial &amp; Heavy Manufacturing Zones:</strong> Chittagong Export Processing Zone (CEPZ), steel re-rolling mills, shipyards, and oil refineries.</li>
</ul>
<h4>Local Production Logistics, Permits &amp; Gear</h4>
<p><strong>AR Entertainment</strong> provides on-ground production coordination, marine boat charters, and specialized drone flight authorizations from the Civil Aviation Authority of Bangladesh (CAAB) and Chattogram Port Authority (CPA).</p>',
        'sort_order' => 11,
        'meta_title' => 'Video Production Company in Chattogram | AR Entertainment',
        'meta_description' => 'Top video production company & film fixer in Chattogram, Bangladesh. Port commercials, industrial documentaries, and marine drone cinematography by AR Entertainment.'
    ],

    // 2. Cox\'s Bazar
    [
        'city_name' => 'Cox\'s Bazar',
        'slug' => 'video-production-company-in-coxsbazar',
        'title' => 'Video Production Company in Cox\'s Bazar | AR Entertainment',
        'summary' => 'World\'s longest sea beach filming, Marine Drive road shoots, luxury resort commercials, and humanitarian documentary fixers in Cox\'s Bazar by AR Entertainment.',
        'content' => '<h3>Beachfront Cinema, Commercials &amp; Documentary Filming in Cox\'s Bazar</h3>
<p>Boasting the world\'s longest uninterrupted natural sand beach (120 km), <strong>Cox\'s Bazar</strong> is the premier destination for high-end fashion shoots, music videos, automotive commercials on Marine Drive, and international NGO documentaries.</p>
<h4>Top Filming Hotspots &amp; Scenic Locations</h4>
<ul>
    <li><strong>Marine Drive (Cox\'s Bazar to Teknaf):</strong> 80 kilometers of breathtaking coastal highway running between dramatic green hills and the crashing Bay of Bengal waves.</li>
    <li><strong>Inani &amp; Himchari Coral Beaches:</strong> Prehistoric coral stones, crashing surf, and tranquil waterfalls overlooking the ocean.</li>
    <li><strong>Moheshkhali Island &amp; Adinath Temple:</strong> Ancient hilltop temples, mangrove waterways, and traditional salt pans.</li>
    <li><strong>Fishing Ports &amp; Dry Fish Yards (Nazirartek):</strong> Thousands of traditional wooden moon-boats (Sampans) and vibrant coastal livelihoods.</li>
</ul>
<h4>Specialized Coastal Production Services</h4>
<p>We deploy weather-sealed cinema cameras, high-wind certified drone platforms (DJI Inspire 3), gyro-stabilized vehicle camera rigs for Marine Drive tracking shots, and complete bilingual fixer support for foreign broadcast crews.</p>',
        'sort_order' => 12,
        'meta_title' => 'Video Production Company in Cox\'s Bazar | AR Entertainment',
        'meta_description' => 'Professional video production & film fixers in Cox\'s Bazar. Marine Drive car commercials, luxury resort videos, and NGO documentaries by AR Entertainment.'
    ],

    // 3. Bandarban
    [
        'city_name' => 'Bandarban',
        'slug' => 'video-production-company-in-bandarban',
        'title' => 'Video Production Company in Bandarban | AR Entertainment',
        'summary' => 'High-altitude mountain cinematography, cloud valley vistas, and indigenous cultural documentary fixers across Nilgiri, Keokradong, and Sangu River in Bandarban.',
        'content' => '<h3>Highland Cinema &amp; Mountain Expedition Filming in Bandarban</h3>
<p>Nestled deep within the Chittagong Hill Tracts, <strong>Bandarban</strong> offers the highest mountain peaks in Bangladesh, dramatic sea-of-clouds phenomena, roaring waterfalls, and rich indigenous cultural heritage (Marma, Bawm, Murong, Chakma).</p>
<h4>Top Filming Hotspots &amp; Highland Vistas</h4>
<ul>
    <li><strong>Nilgiri &amp; Thanchi Cloud Valleys:</strong> Spectacular high-altitude ridgelines where mist and clouds swirl around mountain resort peaks.</li>
    <li><strong>Nafakhum &amp; Amiakhum Waterfalls:</strong> Pristine, roaring jungle cascades accessible via wooden boat navigations through the Sangu River canyon.</li>
    <li><strong>Buddha Dhatu Jadi (Golden Temple):</strong> Magnificent Theravada Buddhist architecture nestled atop scenic hilltops.</li>
    <li><strong>Chimbuk &amp; Keokradong Trails:</strong> Rugged winding roads, tribal bamboo homesteads, and panoramic valley overlooks.</li>
</ul>
<h4>Hill Tracts Permitting &amp; Expedition Logistics</h4>
<p>Filming in Bandarban requires specialized local administration (DC Office) and security clearances. <strong>AR Entertainment</strong> manages all Hill Tracts paperwork, 4x4 off-road transport, portable solar power generators, and indigenous bilingual translators.</p>',
        'sort_order' => 13,
        'meta_title' => 'Video Production Company in Bandarban | AR Entertainment',
        'meta_description' => 'Cinematic video production & mountain film fixers in Bandarban, Bangladesh. Nilgiri cloudscapes, waterfall expeditions, and cultural films by AR Entertainment.'
    ],

    // 4. Rangamati
    [
        'city_name' => 'Rangamati',
        'slug' => 'video-production-company-in-rangamati',
        'title' => 'Video Production Company in Rangamati | AR Entertainment',
        'summary' => 'Kaptai Lake aerials, hanging bridge visuals, indigenous floating market documentaries, and island resort filming in Rangamati by AR Entertainment.',
        'content' => '<h3>Lake Cinema &amp; Island Video Production in Rangamati</h3>
<p>Surrounding the shimmering expanse of Kaptai Lake—the largest artificial reservoir in South Asia—<strong>Rangamati</strong> combines serene turquoise waterways, emerald green mountain islands, and the distinctive culture of the Chakma kingdom.</p>
<h4>Iconic Rangamati Filming Locations</h4>
<ul>
    <li><strong>Kaptai Lake &amp; Gushing Waterways:</strong> Endless labyrinth of scenic water channels perfect for boat-to-boat tracking shots and aerial drone sweeps.</li>
    <li><strong>Rangamati Hanging Bridge (Jhulanto Shetu):</strong> Landmark architectural suspension bridge spanning across scenic mountain coves.</li>
    <li><strong>Rajban Vihara &amp; Chakma Royal Palace:</strong> Sacred Buddhist monastic grounds, meditation halls, and royal cultural archives.</li>
    <li><strong>Shuvalong Waterfall &amp; Gorge:</strong> Dramatic rocky mountain gorge where fresh waterfall streams plummet directly into the lake.</li>
</ul>
<h4>Marine &amp; Island Filming Logistics</h4>
<p>AR Entertainment maintains dedicated motorized camera speedboats, underwater housing kits, and drone crews experienced in over-water navigation and island generator deployment across Kaptai Lake.</p>',
        'sort_order' => 14,
        'meta_title' => 'Video Production Company in Rangamati | AR Entertainment',
        'meta_description' => 'Lake & hill video production in Rangamati, Bangladesh. Kaptai Lake boat shoots, tribal cultural documentaries, and aerial cinematography by AR Entertainment.'
    ],

    // 5. Khagrachari
    [
        'city_name' => 'Khagrachari',
        'slug' => 'video-production-company-in-khagrachari',
        'title' => 'Video Production Company in Khagrachari | AR Entertainment',
        'summary' => 'Sajek Valley cloudscapes, Alutila mysterious cave expeditions, and indigenous eco-tourism commercial production in Khagrachari by AR Entertainment.',
        'content' => '<h3>Cloud Valley Cinematography &amp; Eco-Adventure Filming in Khagrachari</h3>
<p>Famous as the gateway to the breathtaking Sajek Valley ("The Kingdom of Clouds"), <strong>Khagrachari</strong> features lush undulating hill landscapes, mysterious subterranean caves, and cascades surrounded by pine and bamboo forests.</p>
<h4>Prime Filming Locations in Khagrachari</h4>
<ul>
    <li><strong>Sajek Valley &amp; Konglak Para:</strong> Picturesque hilltop cottage resorts floating above dense white clouds at sunrise and sunset.</li>
    <li><strong>Alutila Mysterious Cave:</strong> A dark 100-meter ancient limestone cave tunnel with underground cold water springs for adventure filming.</li>
    <li><strong>Richhang Waterfall:</strong> Natural stepped rock waterfall hidden inside lush green deciduous forest canopies.</li>
    <li><strong>Undulating Tea Gardens &amp; Fruit Orchards:</strong> Sprawling rubber plantations, pineapple gardens, and mango orchards.</li>
</ul>
<h4>Sajek Escort Logistics &amp; Fast Clearances</h4>
<p>We coordinate official military escort convoys for Sajek Valley transit, manage equipment logistics for high-altitude resort sets, and provide specialized low-light camera packages for cave exploration scenes.</p>',
        'sort_order' => 15,
        'meta_title' => 'Video Production Company in Khagrachari | AR Entertainment',
        'meta_description' => 'Sajek Valley film fixers & video production in Khagrachari, Bangladesh. Cloudscapes, cave exploration, and eco-tourism commercials by AR Entertainment.'
    ],

    // 6. Feni
    [
        'city_name' => 'Feni',
        'slug' => 'video-production-company-in-feni',
        'title' => 'Video Production Company in Feni | AR Entertainment',
        'summary' => 'Muhuri Project water barrage filming, highway transit commercials, and coastal agriculture video production in Feni by AR Entertainment.',
        'content' => '<h3>River Barrage &amp; Cross-Regional Production Services in Feni</h3>
<p>Strategically positioned as the vital transit corridor connecting Dhaka and Chattogram, <strong>Feni</strong> is home to the colossal Muhuri Irrigation Project, scenic delta fisheries, and historic water reservoirs.</p>
<h4>Top Filming Hotspots in Feni</h4>
<ul>
    <li><strong>Muhuri Irrigation Project:</strong> Massive multi-vent water barrage at the confluence of Feni and Kalidas Pahalia rivers with vast scenic wetlands and migratory bird habitats.</li>
    <li><strong>Raja\'s Pond (Bijoy Singh Dighi):</strong> A colossal 700-year-old historic water tank surrounded by lush mahogany trees and heritage steps.</li>
    <li><strong>Dhaka-Chattogram 8-Lane Expressway Corridors:</strong> Modern wide-angle highway perspectives ideal for transit TVCs and logistics corporate videos.</li>
    <li><strong>Silua Historic Archaeological Site:</strong> Ancient stone monuments and archaeological ruins reflecting regional antiquity.</li>
</ul>
<h4>Transit &amp; Rapid Deployment Logistics</h4>
<p>AR Entertainment offers rapid-deployment camera units along the Dhaka-Chittagong transit highway, with rapid permits for drone flights over the Muhuri wetlands and agricultural belts.</p>',
        'sort_order' => 16,
        'meta_title' => 'Video Production Company in Feni | AR Entertainment',
        'meta_description' => 'Video production services in Feni, Bangladesh. Muhuri barrage aerials, highway commercial filming, and corporate documentaries by AR Entertainment.'
    ],

    // 7. Noakhali
    [
        'city_name' => 'Noakhali',
        'slug' => 'video-production-company-in-noakhali',
        'title' => 'Video Production Company in Noakhali | AR Entertainment',
        'summary' => 'Nijhum Dwip spotted deer wildlife filming, Hatia Island estuaries, and coastal climate change documentary production in Noakhali by AR Entertainment.',
        'content' => '<h3>Coastal Wildlife, Island Estuaries &amp; Climate Documentaries in Noakhali</h3>
<p>Bordering the dynamic Bay of Bengal estuary, <strong>Noakhali</strong> is globally renowned for Nijhum Dwip (Silent Island), vast mangrove afforestation zones, migrating deer herds, and coastal resilience stories.</p>
<h4>Key Filming Locations in Noakhali</h4>
<ul>
    <li><strong>Nijhum Dwip National Park:</strong> Pristine offshore island sanctuary hosting thousands of wild spotted deer, intertidal mudflats, and winter waterfowl.</li>
    <li><strong>Hatia Island &amp; Meghna River Estuary:</strong> Massive deltaic islands, roaring river transitions, and traditional river trawler boatbuilders.</li>
    <li><strong>Gandhi Ashram Trust (Jayag):</strong> Historic peace sanctuary visited by Mahatma Gandhi in 1946, featuring rustic museum halls and cottage craft workshops.</li>
    <li><strong>Maijdee Town &amp; Agricultural Polders:</strong> Extensive saline-tolerant crop fields, shrimp hatcheries, and cyclone shelter infrastructure.</li>
</ul>
<h4>Offshore Marine Expeditions &amp; Trawler Charters</h4>
<p>Filming in Nijhum Dwip requires sea-worthy trawler logistics, satellite communications, and tides-aligned planning. AR Entertainment manages complete offshore island safety, power backups, and wildlife telephoto lens packages.</p>',
        'sort_order' => 17,
        'meta_title' => 'Video Production Company in Noakhali | AR Entertainment',
        'meta_description' => 'Documentary & video production in Noakhali, Bangladesh. Nijhum Dwip wildlife filming, Hatia island shoots, and climate stories by AR Entertainment.'
    ],

    // 8. Lakshmipur
    [
        'city_name' => 'Lakshmipur',
        'slug' => 'video-production-company-in-lakshmipur',
        'title' => 'Video Production Company in Lakshmipur | AR Entertainment',
        'summary' => 'Meghna riverine char filming, coconut/betel nut grove visuals, and rural agriculture corporate video production in Lakshmipur by AR Entertainment.',
        'content' => '<h3>Riverine Agro-Livelihood &amp; Heritage Filming in Lakshmipur</h3>
<p>Situated on the eastern banks of the mighty lower Meghna river, <strong>Lakshmipur</strong> is celebrated for its endless coconut and betel nut forests, fertile riverine chars, and traditional soybean farming.</p>
<h4>Top Filming Locations in Lakshmipur</h4>
<ul>
    <li><strong>Lower Meghna Riverbanks &amp; Char Islands:</strong> Dramatic wide horizons where the sky meets the immense river expanse, ideal for sunset silhouettes and boat tracking.</li>
    <li><strong>Soybean &amp; Betel Nut Plantations:</strong> Sprawling green canopy groves and massive agricultural harvests representing Bangladesh\'s largest soybean hub.</li>
    <li><strong>Dalal Bazar Zamindar Bari &amp; Khoa Sagor Dighi:</strong> 400-year-old historic merchant estate, brick arches, and serene royal water tanks.</li>
    <li><strong>Maju Chaudhury Hat River Port:</strong> Critical ferry and cargo terminal connecting the south-western divisions with Chattogram.</li>
</ul>
<h4>Local Production &amp; River Logistics</h4>
<p>We provide localized camera crew support, drone coverage across agricultural fields, and authorized ferry/watercraft filming charters across Lakshmipur.</p>',
        'sort_order' => 18,
        'meta_title' => 'Video Production Company in Lakshmipur | AR Entertainment',
        'meta_description' => 'Professional video production in Lakshmipur, Bangladesh. Agro corporate films, Meghna river drone footage, and heritage documentaries by AR Entertainment.'
    ],

    // 9. Chandpur
    [
        'city_name' => 'Chandpur',
        'slug' => 'video-production-company-in-chandpur',
        'title' => 'Video Production Company in Chandpur | AR Entertainment',
        'summary' => 'City of Hilsa, Three-River Confluence (Molla Head), river cruise launch terminal, and fish harbor video production in Chandpur by AR Entertainment.',
        'content' => '<h3>River Confluence, Fish Harbor &amp; Maritime Commercials in Chandpur</h3>
<p>Known as the legendary "City of Hilsa," <strong>Chandpur</strong> sits directly at the magnificent triple-confluence of the Padma, Meghna, and Dakatia rivers, making it Bangladesh\'s primary riverine trade and passenger hub.</p>
<h4>Iconic Chandpur Filming Locations</h4>
<ul>
    <li><strong>Molla Head (Boro Station Confluence):</strong> The spectacular meeting point of three major rivers with strong currents, churning waters, and sweeping horizon vistas.</li>
    <li><strong>Chandpur Fish Landing Ghat:</strong> The bustling morning auction harbor where millions of silver Hilsa fish are unloaded from wooden trawlers.</li>
    <li><strong>Passenger Launch Terminal &amp; Steamers:</strong> Multi-deck passenger vessels, historic rocket paddle steamers, and vibrant river transit life.</li>
    <li><strong>Rupsha Zamindar Bari (Faridganj):</strong> Elegant historic palatial architecture with classical columns and pond courtyards.</li>
</ul>
<h4>Fast Launch Charters &amp; High-Speed River Cinematography</h4>
<p>AR Entertainment coordinates river police permissions, high-speed chase boats for launch-tracking shots, and water-repellent camera protection rigs for river shoots.</p>',
        'sort_order' => 19,
        'meta_title' => 'Video Production Company in Chandpur | AR Entertainment',
        'meta_description' => 'Cinematic video production & river film fixers in Chandpur, Bangladesh. Three-river confluence aerials, Hilsa fish harbor shoots, and launch TVCs by AR Entertainment.'
    ],

    // 10. Cumilla
    [
        'city_name' => 'Cumilla',
        'slug' => 'video-production-company-in-cumilla',
        'title' => 'Video Production Company in Cumilla | AR Entertainment',
        'summary' => 'Ancient Shalban Vihara Buddhist archaeological filming, Mainamati war cemetery, Khadi textile craftsmanship, and BARD documentaries in Cumilla.',
        'content' => '<h3>Archaeological Heritage, Khadi Textiles &amp; Educational Filming in Cumilla</h3>
<p>Rich with over 1,200 years of civilization, <strong>Cumilla</strong> is home to the world-renowned 8th-century Buddhist monastic ruins of Mainamati, the sacred Lalmai Hills, and the birthplace of hand-spun Khadi cloth.</p>
<h4>Top Filming Hotspots &amp; Archaeological Wonders</h4>
<ul>
    <li><strong>Shalban Vihara (Mainamati):</strong> Massive 8th-century Buddhist terracotta monastery ruins featuring central sanctums and monk cells.</li>
    <li><strong>Mainamati War Cemetery:</strong> Immaculate Commonwealth World War II memorial garden surrounded by peaceful pine groves.</li>
    <li><strong>Dharmasagar Dighi &amp; City Heritage:</strong> Historic 15th-century royal lake with tranquil promenade walkways and century-old rain trees.</li>
    <li><strong>BARD (Bangladesh Academy for Rural Development):</strong> Serene institutional campus surrounded by terraced hill gardens and water features.</li>
    <li><strong>Chandina Khadi Weaving Cottages:</strong> Traditional spinning wheel (Charkha) artisans producing authentic organic Khadi fabrics.</li>
</ul>
<h4>Department of Archaeology Filming Permits</h4>
<p>AR Entertainment coordinates fast-track national filming clearances with the Ministry of Cultural Affairs and Department of Archaeology for Shalban Vihara and Mainamati heritage sites.</p>',
        'sort_order' => 20,
        'meta_title' => 'Video Production Company in Cumilla | AR Entertainment',
        'meta_description' => 'Top video production company in Cumilla, Bangladesh. Shalban Vihara heritage documentaries, Khadi textile commercials, and aerial cinematography by AR Entertainment.'
    ],

    // 11. Brahmanbaria
    [
        'city_name' => 'Brahmanbaria',
        'slug' => 'video-production-company-in-brahmanbaria',
        'title' => 'Video Production Company in Brahmanbaria | AR Entertainment',
        'summary' => 'Titas River literary heritage, natural gas industrial plants, Sarail hound cultural documentaries, and handloom textile video production in Brahmanbaria.',
        'content' => '<h3>Literary Riverine Culture &amp; Energy Sector Filming in Brahmanbaria</h3>
<p>Celebrated as the cultural capital of classical music (Ustad Alauddin Khan) and the iconic backdrop of the novel <em>Titas Ekti Nadir Naam</em>, <strong>Brahmanbaria</strong> blends poetic river life with massive national natural gas infrastructure.</p>
<h4>Prime Filming Locations in Brahmanbaria</h4>
<ul>
    <li><strong>Titas River &amp; Gokarna Ghat:</strong> Serene river meanders, traditional rowing competitions, and fishing boat communities immortalized in Bengali literature.</li>
    <li><strong>Ashuganj Power Hub &amp; Fertilizer Mega-Complex:</strong> Massive thermal power plants, national grain silos, and Meghna river cargo transit ports.</li>
    <li><strong>Titas Gas Transmission Fields:</strong> Clean industrial energy installations and high-tech pipeline monitoring stations.</li>
    <li><strong>Sarail Heritage &amp; Hatirpool:</strong> Historic Mughal brick elephant bridges, Dewan Bari mansions, and legendary Sarail greyhound breeders.</li>
    <li><strong>Ustad Alauddin Khan Music Academy:</strong> Classical music heritage archives, sitar instruments, and cultural performance halls.</li>
</ul>
<h4>Industrial &amp; Cultural Production Fixers</h4>
<p>AR Entertainment delivers turnkey production crew solutions, security clearances for energy plants in Ashuganj, and high-fidelity multi-mic audio recording for classical musical shoots.</p>',
        'sort_order' => 21,
        'meta_title' => 'Video Production Company in Brahmanbaria | AR Entertainment',
        'meta_description' => 'Professional video production in Brahmanbaria, Bangladesh. Titas River aerials, energy industrial corporate films, and cultural documentaries by AR Entertainment.'
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
foreach ($districts_batch2 as $d) {
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
echo "🏆 BATCH 5.3.2 COMPLETED SUCCESSFULLY!\n";
echo "   - Total Districts Seeded in Batch: {$seeded_count}\n";
echo "   - Current Total Active in Database: {$total_in_db}\n";
echo "========================================================\n\n";
