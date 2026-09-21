<?php
/**
 * AR Entertainment - Phase 5.3 District Filming Guides Seeder (Batch 5.3.7: Districts 59–64 — Barishal Division)
 * 
 * Ingests the final 6 District Filming Location Guides completing all 64 Districts of Bangladesh:
 * 1. Barishal (video-production-company-in-barisal)
 * 2. Barguna (video-production-company-in-barguna)
 * 3. Bhola (video-production-company-in-bhola)
 * 4. Jhalokati (video-production-company-in-jhalokati)
 * 5. Patuakhali (video-production-company-in-patuakhali)
 * 6. Pirojpur (video-production-company-in-pirojpur)
 * 
 * Features:
 * - 100% Clean AR Entertainment Branding (0% Legacy Names)
 * - Venice of Bengal & Coastal Delta Cinema Logistics (Floating Markets, Kuakata Beach, Jacob Tower, Payra Port, Monpura Island)
 * - Rich Responsive HTML Guide Structure
 * - Idempotent MySQL Upsert (ON DUPLICATE KEY UPDATE)
 */

declare(strict_types=1);

require_once __DIR__ . '/../../config/config.php';
require_once __DIR__ . '/../../config/db.php';
require_once __DIR__ . '/../../config/helpers.php';

echo "========================================================\n";
echo "🎬 AR ENTERTAINMENT - SEED DISTRICT GUIDES BATCH 5.3.7 (59–64: BARISHAL DIVISION)\n";
echo "========================================================\n\n";

$db = db();

$districts_batch7 = [
    // 1. Barishal
    [
        'city_name' => 'Barishal',
        'slug' => 'video-production-company-in-barisal',
        'title' => 'Video Production Company in Barishal | AR Entertainment',
        'summary' => 'Floating guava market river cinematography, Kirtankhola port operations, heritage churches, and southern commercial film production in Barishal by AR Entertainment.',
        'content' => '<h3>Venice of Bengal Riverine Cinema &amp; Commercial Production in Barishal</h3>
<p>Known as the "Venice of Bengal", <strong>Barishal</strong> is the pulsating cultural and maritime hub of southern Bangladesh. Defined by its crisscrossing rivers, bustling launch terminals, and lush aquatic ecosystems, Barishal provides filmmakers with extraordinary dynamic water reflections, floating commerce, and grand architectural landmarks.</p>
<h4>Top Filming Hotspots &amp; Natural Backdrops</h4>
<ul>
    <li><strong>Bhimruli &amp; Banaripara Floating Guava Markets:</strong> Hundreds of wooden boats laden with green guavas colliding in narrow tidal canals—world-class visual appeal for documentaries and commercial travel films.</li>
    <li><strong>Kirtankhola River &amp; Barishal Launch Terminal:</strong> Multi-deck passenger steamers navigating swirling currents at dawn and dusk, creating iconic river transport imagery.</li>
    <li><strong>Oxford Mission Church (Epiphany Church):</strong> One of Asia’s largest and most stunning red-brick Gothic churches featuring towering bell towers and serene palm reflections.</li>
    <li><strong>Baitul Aman Jame Masjid Complex (Guthia Mosque):</strong> Majestic Islamic architecture with 20 sprawling domes, towering 193-foot minaret, and shimmering ornamental lakes.</li>
    <li><strong>Bell\'s Park (Bangabandhu Udyan) &amp; Riverfront Walkway:</strong> Vibrant urban lifestyle gatherings framed by expansive riverine horizon views.</li>
    <li><strong>Durgasagar Dighi (Madhabpasha):</strong> Historic 18th-century reservoir island teeming with migratory waterfowl and tranquil lotus ponds.</li>
</ul>
<h4>Waterway Filming Logistics, Shallow Boats &amp; Drone Permissions</h4>
<p><strong>AR Entertainment</strong> provisions custom motorized filming crafts, water-stabilized gimbal setups, BIWTA launch filming permits, and skilled local river pilots for seamless waterway productions.</p>',
        'sort_order' => 59,
        'meta_title' => 'Video Production Company in Barishal | AR Entertainment',
        'meta_description' => 'Top video production company & film fixer in Barishal, Bangladesh. Floating guava markets, Kirtankhola river aerials, and documentary line production by AR Entertainment.'
    ],

    // 2. Barguna
    [
        'city_name' => 'Barguna',
        'slug' => 'video-production-company-in-barguna',
        'title' => 'Video Production Company in Barguna | AR Entertainment',
        'summary' => 'Payra estuary cinematography, Shuvoshondha sea beach sunrises, Taltali Rakhine cultural films, and coastal wildlife production in Barguna by AR Entertainment.',
        'content' => '<h3>Coastal Delta Wilderness &amp; Indigenous Heritage Filming in Barguna</h3>
<p>Bordering the Bay of Bengal and flanked by the mighty Payra and Bishkhali rivers, <strong>Barguna</strong> offers rugged coastal landscapes, untouched sandy beaches, ancient Rakhine settlements, and protected coastal mangrove reserves.</p>
<h4>Top Filming Hotspots &amp; Coastal Backdrops</h4>
<ul>
    <li><strong>Shuvoshondha Sea Beach (Taltali):</strong> An expansive, pristine coastal beach stretching for 4 kilometers where the Payra River converges with the turquoise waves of the Bay of Bengal.</li>
    <li><strong>Tengragiri Wildlife Sanctuary (Fatrar Bon):</strong> Second-largest mangrove ecosystem in Bangladesh after the Sundarbans, home to coastal deer, wild boars, and canopy tidal creeks.</li>
    <li><strong>Taltali Rakhine Indigenous Communities:</strong> Traditional wooden handloom weaving hubs, historic pagoda monasteries, and vibrant cultural ceremonies.</li>
    <li><strong>Bibi Chini Mosque (Betagi):</strong> Historic 17th-century Mughal brick mosque perched atop an elevated riverside mound with authentic rural village charm.</li>
    <li><strong>Patharghata Fish Harbor &amp; Dry Fish Yards:</strong> Dynamic high-energy fishing trawler unloads and coastal seafood processing scenes.</li>
</ul>
<h4>Tidal Scheduling, Salt Protection &amp; Coastal Line Production</h4>
<p><strong>AR Entertainment</strong> manages tidal logistics, marine vessel chartering, anti-corrosive cinema gear rigging, and cultural liaison with Rakhine community leaders for respectful, cinematic storytelling.</p>',
        'sort_order' => 60,
        'meta_title' => 'Video Production Company in Barguna | AR Entertainment',
        'meta_description' => 'Professional video production company & fixer in Barguna, Bangladesh. Shuvoshondha beach, Tengragiri mangrove, and Rakhine culture documentaries by AR Entertainment.'
    ],

    // 3. Bhola
    [
        'city_name' => 'Bhola',
        'slug' => 'video-production-company-in-bhola',
        'title' => 'Video Production Company in Bhola | AR Entertainment',
        'summary' => 'Island delta cinematography, Monpura island horizons, Jacob Tower aerials, and water buffalo pastoral documentary production in Bhola by AR Entertainment.',
        'content' => '<h3>Island Delta Expeditions &amp; Oceanic Horizon Cinematography in Bhola</h3>
<p>As the largest offshore island district in Bangladesh, <strong>Bhola</strong> is an enchanting world enclosed by the Meghna River and the Bay of Bengal. Bhola presents filmmakers with infinite sky-water horizons, modern architectural landmarks, and centuries-old island pastoral lifestyles.</p>
<h4>Top Filming Hotspots &amp; Island Landscapes</h4>
<ul>
    <li><strong>Monpura Island:</strong> Idyllic green island paradise with unspoiled cycling trails, coastal mangrove canopies, and mesmerizing sunsets over the roaring Meghna estuary.</li>
    <li><strong>Jacob Tower (Char Fasson):</strong> 225-foot iconic glass-and-steel observation tower—the highest in South Asia—offering breathtaking 360-degree aerial views across the riverine delta.</li>
    <li><strong>Tarua Sea Beach &amp; Coastal Sandbars:</strong> Golden sandspits with virgin mangrove trees rising from the ocean tide, ideal for music videos and commercial fashion shoots.</li>
    <li><strong>Pastoral Buffalo Herds &amp; Traditional Dairy Farms:</strong> Massive herds of water buffalo swimming across tidal rivers at sunrise, providing world-class agricultural imagery.</li>
    <li><strong>Char Kukri-Mukri Wildlife Reserve:</strong> Coastal mangrove delta sanctuary populated by spotted deer, migratory winter birds, and pristine tidal waterways.</li>
</ul>
<h4>Speedboat Transport, Island Logistics &amp; High-Wind Aerial Permits</h4>
<p><strong>AR Entertainment</strong> operates dedicated speedboat logistics, coastal safety personnel, mobile charging generators, and CAAB drone approvals for windy offshore island flights.</p>',
        'sort_order' => 61,
        'meta_title' => 'Video Production Company in Bhola | AR Entertainment',
        'meta_description' => 'Leading video production company & film fixer in Bhola, Bangladesh. Monpura Island, Jacob Tower aerials, and Char Kukri-Mukri wildlife shoots by AR Entertainment.'
    ],

    // 4. Jhalokati
    [
        'city_name' => 'Jhalokati',
        'slug' => 'video-production-company-in-jhalokati',
        'title' => 'Video Production Company in Jhalokati | AR Entertainment',
        'summary' => 'Sugandha river heritage, Bhimruli floating market cinematography, Gabkhan bridge engineering, and timber craft corporate video in Jhalokati by AR Entertainment.',
        'content' => '<h3>Riverine Heartland &amp; Floating Market Cinematography in Jhalokati</h3>
<p>Nestled along the serene banks of the Sugandha and Dhanshiri rivers, <strong>Jhalokati</strong> is the true commercial epicenter of Bangladesh\'s legendary floating fruit and vegetable markets, traditional river boatbuilding, and rich Bengali literary heritage.</p>
<h4>Top Filming Hotspots &amp; Waterway Landscapes</h4>
<ul>
    <li><strong>Bhimruli Canal &amp; Floating Market:</strong> The world-renowned intersection where hundreds of traditional wooden dinghies trade guavas, hog plums, and fresh produce along emerald green canals.</li>
    <li><strong>Gabkhan Bridge &amp; Shipping Channel:</strong> The dramatic 115-foot high road bridge spanning the Gabkhan Channel (the "Suez Canal of Bengal"), accommodating oceanic vessels and barge traffic.</li>
    <li><strong>Kirtipasha Zamindar Palace &amp; Temple Complex:</strong> Historic 18th-century terracotta ruins and moss-covered courtyards rich in vintage colonial atmosphere.</li>
    <li><strong>Dhanshiri River &amp; Poet Jibanananda Das Heritage:</strong> Romantic riverway banks made immortal in modern Bengali poetry, surrounded by lush paddy fields and lotus marshes.</li>
    <li><strong>Timber Trading Hubs &amp; Woodcraft Boat Docks:</strong> Skilled artisans handcrafting wooden dinghies and river vessels along the busy river banks.</li>
</ul>
<h4>Canal Film Rigs, Drone Passes &amp; Floating Camera Platforms</h4>
<p><strong>AR Entertainment</strong> equips film crews with low-draft camera boats, overhead cable-cam and drone systems, local river guides, and high-speed transit connections from Barishal airport.</p>',
        'sort_order' => 62,
        'meta_title' => 'Video Production Company in Jhalokati | AR Entertainment',
        'meta_description' => 'Expert video production company in Jhalokati, Bangladesh. Bhimruli floating market cinematography, Gabkhan channel drone videos, and documentaries by AR Entertainment.'
    ],

    // 5. Patuakhali
    [
        'city_name' => 'Patuakhali',
        'slug' => 'video-production-company-in-patuakhali',
        'title' => 'Video Production Company in Patuakhali | AR Entertainment',
        'summary' => 'Kuakata Daughter of the Sea cinematography, Payra deep sea port mega-infrastructure, Rakhine temples, and marine video production in Patuakhali by AR Entertainment.',
        'content' => '<h3>Oceanic Horizon &amp; Megaproject Industrial Cinematography in Patuakhali</h3>
<p>Celebrated for the world-famous <strong>Kuakata Beach</strong> ("Sagar Kannya" / Daughter of the Sea) and the massive <strong>Payra Deep Sea Port</strong>, <strong>Patuakhali</strong> offers an extraordinary juxtaposition of panoramic ocean horizons and nation-building mega-infrastructure.</p>
<h4>Top Filming Hotspots &amp; Industrial Landscapes</h4>
<ul>
    <li><strong>Kuakata Sea Beach:</strong> Rare 18-kilometer unbroken sandy shoreline offering panoramic views of both sunrise and sunset emerging from and dipping into the Bay of Bengal waters.</li>
    <li><strong>Payra Deep Sea Port &amp; Thermal Power Hub:</strong> Sprawling multi-billion-dollar maritime container terminals, coal-fired power facilities, and cutting-edge industrial construction.</li>
    <li><strong>Fatrar Char Mangrove Forest:</strong> Untamed coastal forest reserve accessible by speedboat, featuring tidal estuaries, mudflats, and red crab colonies.</li>
    <li><strong>Misripara &amp; Keranipara Rakhine Villages:</strong> Ancient Buddhist temples housing one of South Asia’s largest seated metal Gautama Buddha statues and historic wishing wells.</li>
    <li><strong>Lebur Char &amp; Sunset Sandspits:</strong> Romantic driftwood-strewn beaches with dramatic marine golden hour lighting for high-end commercials and music videos.</li>
</ul>
<h4>Beach Track Rigs, Port Permits &amp; Coastal Aerial Line Production</h4>
<p><strong>AR Entertainment</strong> delivers full-scale filming line production in Patuakhali, including Payra Port authority security clearances, 4x4 beach transport, and salt-mist proof camera packages.</p>',
        'sort_order' => 63,
        'meta_title' => 'Video Production Company in Patuakhali | AR Entertainment',
        'meta_description' => 'Top video production company & line producer in Patuakhali, Bangladesh. Kuakata beach sunrise/sunset shoots, Payra Deep Sea Port corporate AVs by AR Entertainment.'
    ],

    // 6. Pirojpur
    [
        'city_name' => 'Pirojpur',
        'slug' => 'video-production-company-in-pirojpur',
        'title' => 'Video Production Company in Pirojpur | AR Entertainment',
        'summary' => 'Boleshwar river estuaries, Atghar-Kuriana floating market filming, Rayerkathi palace heritage, and eco-mangrove video production in Pirojpur by AR Entertainment.',
        'content' => '<h3>Riverine Estuaries, Betel Gardens &amp; Heritage Cinematography in Pirojpur</h3>
<p>Surrounded by the Boleshwar, Damodar, and Sandhya rivers on the eastern edge of the Sundarbans mangrove fringe, <strong>Pirojpur</strong> is celebrated for its floating betel leaf and guava markets, historic zamindar estates, and iconic bridge architecture.</p>
<h4>Top Filming Hotspots &amp; Natural Backdrops</h4>
<ul>
    <li><strong>Atghar-Kuriana Floating Betel Leaf &amp; Guava Market:</strong> Dense network of lush canals where floating boats trade betel leaves, coconuts, and guavas amid pristine greenery.</li>
    <li><strong>Bekutia Bridge (8th Bangladesh-China Friendship Bridge):</strong> Modern 1.5-kilometer suspension engineering masterpiece spanning the wide Sandhya River with dramatic lighting.</li>
    <li><strong>Rayerkathi Zamindar Palace &amp; Temple Complex:</strong> 300-year-old royal zamindar palaces, Shiva temples, and intricate ancient carvings steeped in Bengali history.</li>
    <li><strong>Boleshwar Riverfront &amp; Mangrove Channels:</strong> Serene tidal estuaries connecting directly to the Sundarbans, providing tranquil eco-travel and wildlife documentary settings.</li>
    <li><strong>Bhandaria &amp; Mathbaria Coconut Plantations:</strong> Expansive coconut groves and traditional river jetty lifestyles ideal for authentic rural storytelling.</li>
</ul>
<h4>Waterway Line Production, Canal Drones &amp; Local Coordination</h4>
<p><strong>AR Entertainment</strong> manages waterway filming logistics, shallow boat navigation, historical site shoot clearances, and custom audio recording in lush natural environments.</p>',
        'sort_order' => 64,
        'meta_title' => 'Video Production Company in Pirojpur | AR Entertainment',
        'meta_description' => 'Professional video production company & fixer in Pirojpur, Bangladesh. Atghar-Kuriana floating market, Bekutia Bridge, and Rayerkathi palace filming by AR Entertainment.'
    ]
];

$stmt = $db->prepare("
    INSERT INTO service_areas (
        city_name,
        slug,
        title,
        summary,
        content,
        sort_order,
        meta_title,
        meta_description,
        status,
        created_at,
        updated_at
    ) VALUES (
        :city_name,
        :slug,
        :title,
        :summary,
        :content,
        :sort_order,
        :meta_title,
        :meta_description,
        'active',
        NOW(),
        NOW()
    )
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
foreach ($districts_batch7 as $idx => $d) {
    $stmt->execute([
        ':city_name'        => $d['city_name'],
        ':slug'             => $d['slug'],
        ':title'            => $d['title'],
        ':summary'          => $d['summary'],
        ':content'          => $d['content'],
        ':sort_order'       => $d['sort_order'],
        ':meta_title'       => $d['meta_title'],
        ':meta_description' => $d['meta_description']
    ]);

    $seeded_count++;
    echo "   ✅ [" . ($idx + 1) . "/6] Seeded District Guide: {$d['city_name']} ({$d['slug']})\n";
}

$total_active = $db->query("SELECT COUNT(*) FROM service_areas WHERE status = 'active'")->fetchColumn();

echo "\n========================================================\n";
echo "🏆 BATCH 5.3.7 COMPLETED SUCCESSFULLY!\n";
echo "   - Total Districts Seeded in Batch: {$seeded_count}\n";
echo "   - Current Total Active in Database: {$total_active} / 64\n";
echo "========================================================\n\n";
