<?php
/**
 * AR Entertainment - Phase 5.3 District Filming Guides Seeder (Batch 5.3.1: Districts 1–10 — Dhaka Division)
 * 
 * Ingests 10 District Filming Location Guides for Dhaka Division:
 * 1.  Dhaka (video-production-company-in-dhaka)
 * 2.  Gazipur (video-production-company-in-gazipur)
 * 3.  Narayanganj (video-production-company-in-narayanganj)
 * 4.  Tangail (video-production-company-in-tangail)
 * 5.  Manikganj (video-production-company-in-manikganj)
 * 6.  Munshiganj (video-production-company-in-munshiganj)
 * 7.  Narsingdi (video-production-company-in-narsingdi)
 * 8.  Faridpur (video-production-company-in-faridpur)
 * 9.  Gopalganj (video-production-company-in-gopalganj)
 * 10. Madaripur (video-production-company-in-madaripur)
 * 
 * Features:
 * - 100% Clean AR Entertainment Branding (0% Legacy Names)
 * - Cinema Logistics & Location Breakdown (Hotspots, Permitting, Transport, Camera Gear)
 * - Rich Responsive HTML Guide
 * - Idempotent MySQL Upsert (ON DUPLICATE KEY UPDATE)
 */

declare(strict_types=1);

require_once __DIR__ . '/../../config/config.php';
require_once __DIR__ . '/../../config/db.php';
require_once __DIR__ . '/../../config/helpers.php';

echo "========================================================\n";
echo "🎬 AR ENTERTAINMENT - SEED DISTRICT GUIDES BATCH 5.3.1 (1–10)\n";
echo "========================================================\n\n";

$db = db();

$districts_batch1 = [
    // 1. Dhaka
    [
        'city_name' => 'Dhaka',
        'slug' => 'video-production-company-in-dhaka',
        'title' => 'Video Production Company in Dhaka | AR Entertainment',
        'summary' => 'Premier film and commercial video production services in Dhaka. Operating cinema camera packages, drone crews, and bilingual fixers across Gulshan, Old Dhaka, Hatirjheel, and Motijheel.',
        'content' => '<h3>Cinema-Grade Video Production &amp; Film Fixer Services in Dhaka</h3>
<p>As Bangladesh\'s capital and vibrant cultural epicenter, <strong>Dhaka</strong> offers an extraordinary tapestry of visual contrasts—from the opulent glass skyscrapers of Gulshan and Banani to the historic Mughal architecture of Lalbagh Fort, the winding heritage alleyways of Shankhari Bazaar, and the modern elevated expressway vistas.</p>
<h4>Top Filming Hotspots &amp; Architectural Locations</h4>
<ul>
    <li><strong>Old Dhaka &amp; Mughal Heritage:</strong> Lalbagh Fort, Ahsan Manzil (Pink Palace), Tara Masjid, and bustling Buriganga riverfront boat life.</li>
    <li><strong>Modern Metropolis &amp; Skyline:</strong> Gulshan, Banani, Hatirjheel waterfront bridge lights, and Dhaka Elevated Expressway sweeps.</li>
    <li><strong>Commercial &amp; Financial Epicenter:</strong> Motijheel Central Business District, Kawran Bazar media hub, and Uttara commercial corridors.</li>
    <li><strong>Academic &amp; Cultural Landmarks:</strong> Curzon Hall (Dhaka University), National Parliament Complex (Louis Kahn architecture), and Shahid Minar.</li>
</ul>
<h4>Local Filming Logistics, Permits &amp; Equipment Rental</h4>
<p><strong>AR Entertainment</strong> maintains full in-house cinema packages in Dhaka, including ARRI Alexa Mini LF, RED V-Raptor, Cooke and Zeiss cinema primes, heavy-lift drones, and specialized lighting trucks. Our location managers secure fast-track DMP police clearances, Ministry of Information permits, and Dhaka City Corporation authorizations.</p>',
        'sort_order' => 1,
        'meta_title' => 'Video Production Company in Dhaka | AR Entertainment',
        'meta_description' => 'Top video production company & film fixer in Dhaka, Bangladesh. Commercials, corporate AVs, documentaries, and cinema drone filming by AR Entertainment.'
    ],

    // 2. Gazipur
    [
        'city_name' => 'Gazipur',
        'slug' => 'video-production-company-in-gazipur',
        'title' => 'Video Production Company in Gazipur | AR Entertainment',
        'summary' => 'Specialized industrial, pharmaceutical, and resort video production in Gazipur. Filming LEED green manufacturing facilities, high-tech parks, and Bhawal forest resorts.',
        'content' => '<h3>Industrial &amp; Nature Film Production in Gazipur</h3>
<p>Located immediately north of the capital, <strong>Gazipur</strong> is Bangladesh’s manufacturing powerhouse, housing the country\'s largest concentration of LEED-certified green garment factories, advanced pharmaceutical manufacturing complexes, and lush Sal forest resort retreats.</p>
<h4>Key Production &amp; Filming Locations in Gazipur</h4>
<ul>
    <li><strong>Industrial &amp; High-Tech Parks:</strong> Bangabandhu Hi-Tech City (Kaliakair), Tongi industrial belt, and export apparel manufacturing complexes.</li>
    <li><strong>Forest Landscapes &amp; Resorts:</strong> Bhawal National Park, luxury eco-resorts, and serene countryside lakes perfect for lifestyle commercial backdrops.</li>
    <li><strong>Agricultural Research Hubs:</strong> BRRI (Bangladesh Rice Research Institute) and BARI modern agricultural testing fields.</li>
</ul>
<h4>Production Logistics &amp; Fast Transit from Dhaka</h4>
<p>Positioned just 30 to 45 minutes from our Dhaka headquarters via the Dhaka-Mymensingh highway, AR Entertainment provides seamless same-day equipment logistics, heavy-lift drone flights, and factory floor lighting setups across Gazipur.</p>',
        'sort_order' => 2,
        'meta_title' => 'Video Production Company in Gazipur | AR Entertainment',
        'meta_description' => 'Professional video production in Gazipur, Bangladesh. Industrial corporate films, factory commercials, and eco-resort drone video by AR Entertainment.'
    ],

    // 3. Narayanganj
    [
        'city_name' => 'Narayanganj',
        'slug' => 'video-production-company-in-narayanganj',
        'title' => 'Video Production Company in Narayanganj | AR Entertainment',
        'summary' => 'Historic Panam City heritage filming, Shitalakshya river logistics, and textile industrial video production in Narayanganj by AR Entertainment.',
        'content' => '<h3>Heritage &amp; Riverine Industrial Video Production in Narayanganj</h3>
<p>Known historically as the "Dundee of Bangladesh," <strong>Narayanganj</strong> combines centuries-old merchant architecture with the nation’s bustling inland container ports and world-renowned Jamdani handloom artisan villages along the Shitalakshya river.</p>
<h4>Iconic Narayanganj Filming Locations</h4>
<ul>
    <li><strong>Panam Nagar (Sonargaon):</strong> The world-famous 19th-century ghost city featuring 52 colonial merchant mansions, ideal for historical period films and music videos.</li>
    <li><strong>Sonargaon Folk Art Museum:</strong> Lush heritage gardens, wooden craftsmanship pavilions, and traditional Bengali village courtyards.</li>
    <li><strong>Shitalakshya &amp; Meghna Riverways:</strong> Massive cargo barges, inland river terminals, and dramatic industrial bridge perspectives.</li>
    <li><strong>Jamdani Weaving Villages (Noapara):</strong> Intricate handloom weaving registered under UNESCO Intangible Cultural Heritage.</li>
</ul>
<h4>Filming Permissions &amp; Heritage Department Approvals</h4>
<p>AR Entertainment coordinates directly with the Department of Archaeology and local maritime police to secure location permits for Panam City and riverboat charters across Narayanganj.</p>',
        'sort_order' => 3,
        'meta_title' => 'Video Production Company in Narayanganj | AR Entertainment',
        'meta_description' => 'Cinematic video production in Narayanganj by AR Entertainment. Panam Nagar heritage filming, Jamdani documentaries, and riverfront industrial corporate AVs.'
    ],

    // 4. Tangail
    [
        'city_name' => 'Tangail',
        'slug' => 'video-production-company-in-tangail',
        'title' => 'Video Production Company in Tangail | AR Entertainment',
        'summary' => 'Cinematic filming in Tangail. Mohera Zamindar Palace, Jamuna Bridge aerials, traditional Tangail sari weaving, and Madhupur pineapple forest expeditions.',
        'content' => '<h3>Visual Storytelling &amp; Heritage Documentaries in Tangail</h3>
<p><strong>Tangail</strong> offers a captivating mix of grand European-influenced Zamindar palaces, sweeping Jamuna River floodplains, the iconic Bangabandhu Jamuna Bridge, and the famous handloom sari weaving communities of Pathrail.</p>
<h4>Top Filming Locations in Tangail</h4>
<ul>
    <li><strong>Mohera Zamindar Bari:</strong> Immaculate 19th-century neo-classical palace complex featuring the Maharaj Lodge, Anand Lodge, and sprawling decorative ponds.</li>
    <li><strong>Jamuna River &amp; Bangabandhu Bridge:</strong> Monumental infrastructure panoramas, high-speed rail tracks, and scenic sandbar char islands.</li>
    <li><strong>Pathrail Handloom Sari Villages:</strong> Rhythmic wooden loom sounds and vibrant colorful dyeing yards showcasing centuries-old Tangail sari heritage.</li>
    <li><strong>Madhupur Sal Forest &amp; Pineapple Orchards:</strong> Dense forest canopies, Garo indigenous communities, and endless green pineapple plantations.</li>
</ul>
<h4>Turnkey Tangail Production Management</h4>
<p>AR Entertainment provides 4WD production transport, heavy-lift drone cinematography over the Jamuna basin, and local district administrative clearances across Tangail.</p>',
        'sort_order' => 4,
        'meta_title' => 'Video Production Company in Tangail | AR Entertainment',
        'meta_description' => 'Prestige video production in Tangail, Bangladesh. Mohera Palace filming, Jamuna Bridge drone sweeps, and handloom documentary production by AR Entertainment.'
    ],

    // 5. Manikganj
    [
        'city_name' => 'Manikganj',
        'slug' => 'video-production-company-in-manikganj',
        'title' => 'Video Production Company in Manikganj | AR Entertainment',
        'summary' => 'Premier film location support in Manikganj. Baliati Palace heritage architecture, mustard flower field panoramas, and Dhaleshwari river cinematography.',
        'content' => '<h3>Scenic Riverine &amp; Royal Palace Filming in Manikganj</h3>
<p>Located just west of Dhaka, <strong>Manikganj</strong> is one of Bangladesh’s most popular film shooting destinations, renowned for the colossal Baliati Palace, tranquil riverine villages along the Dhaleshwari, and vibrant golden yellow mustard blossoms in winter.</p>
<h4>Key Locations for Commercials &amp; Dramas</h4>
<ul>
    <li><strong>Baliati Palace (Saturia):</strong> One of the largest 19th-century royal palaces in South Asia with 7 distinct architectural blocks, 200 rooms, and colonnaded facades.</li>
    <li><strong>Teota Zamindar Bari (Shibalaya):</strong> Overlooking the mighty Jamuna-Padma confluence, offering romantic decaying brick structures and river sunsets.</li>
    <li><strong>Winter Mustard &amp; Tobacco Farmlands:</strong> Endless golden yellow mustard blossom fields attracting top national TVC and fashion shoots.</li>
</ul>
<h4>Fast Production Dispatch from Dhaka</h4>
<p>With Manikganj located only 1.5 hours from our Dhaka studio, AR Entertainment provides rapid film crew dispatch, location recce, mobile vanity vans, and generator trucks.</p>',
        'sort_order' => 5,
        'meta_title' => 'Video Production Company in Manikganj | AR Entertainment',
        'meta_description' => 'Top video production & film fixer in Manikganj, Bangladesh. Baliati Palace filming, Dhaleshwari river drone shots, and commercial shoots by AR Entertainment.'
    ],

    // 6. Munshiganj
    [
        'city_name' => 'Munshiganj',
        'slug' => 'video-production-company-in-munshiganj',
        'title' => 'Video Production Company in Munshiganj | AR Entertainment',
        'summary' => 'Padma Bridge expressway filming, Idrakpur Fort river fortresses, and agricultural agro-processing documentaries in Munshiganj by AR Entertainment.',
        'content' => '<h3>Mega-Infrastructure &amp; Historic Riverway Filming in Munshiganj</h3>
<p><strong>Munshiganj</strong> (historic Bikrampur) sits at the northern gateway of the monumental Padma Multipurpose Bridge and Dhaka-Mawa Expressway, blending ultra-modern transport infrastructure with ancient archaeological forts and fertile river agricultural zones.</p>
<h4>Premier Filming Locations in Munshiganj</h4>
<ul>
    <li><strong>Padma Bridge &amp; Mawa Expressway:</strong> World-class 8-lane expressway cinematography, toll plaza modernism, and sweeping Padma River aerial views.</li>
    <li><strong>Idrakpur Fort:</strong> 17th-century Mughal river fort built by Subahdar Mir Jumla, featuring a unique circular drum platform and historic moat.</li>
    <li><strong>Bikrampur Archaeological Heritage Sites:</strong> Ancient Buddhist monastery ruins of Raghurampur and Baba Adam’s Mosque in Rampal.</li>
    <li><strong>Cold Storage &amp; Potato Harvest Belts:</strong> Massive agricultural export facilities and lively rural market trading hubs.</li>
</ul>
<h4>Expressway Drone Logistics &amp; Marine Filming</h4>
<p>AR Entertainment manages specialized CAAB flight authorizations for Padma Bridge zone aerials, alongside chartered speedboats for dynamic marine tracking shots.</p>',
        'sort_order' => 6,
        'meta_title' => 'Video Production Company in Munshiganj | AR Entertainment',
        'meta_description' => 'Cinematic video production in Munshiganj by AR Entertainment. Padma Bridge aerial filming, Mawa expressway tracking, and Idrakpur Fort documentaries.'
    ],

    // 7. Narsingdi
    [
        'city_name' => 'Narsingdi',
        'slug' => 'video-production-company-in-narsingdi',
        'title' => 'Video Production Company in Narsingdi | AR Entertainment',
        'summary' => 'Archaeological heritage documentaries, textile manufacturing films, and Meghna river cinematography in Narsingdi by AR Entertainment.',
        'content' => '<h3>Ancient Archaeology &amp; Modern Textile Manufacturing in Narsingdi</h3>
<p><strong>Narsingdi</strong> bridges deep historical antiquity with intense industrial productivity. Home to the 2,500-year-old Wari-Bateshwar archaeological civilization and the largest textile wholesale and manufacturing clusters in Bangladesh (Baburhat / Shekherchar).</p>
<h4>Featured Filming Locations in Narsingdi</h4>
<ul>
    <li><strong>Wari-Bateshwar Archaeological Site (Belabo):</strong> Ancient urban fort city dating back to 450 BCE, offering rare historical documentary backdrops.</li>
    <li><strong>Baburhat (Shekherchar) Textile Market:</strong> Asia’s largest traditional fabric trading market, bustling with vibrant commercial energy.</li>
    <li><strong>Meghna River Confluences &amp; Ghats:</strong> Broad water expanses, fishing boat fleets, and sand harvesting barge activities.</li>
    <li><strong>Dream Holiday Park:</strong> Sprawling modern amusement park setting suitable for lifestyle commercials and family product TVCs.</li>
</ul>
<h4>Complete Production Support in Narsingdi</h4>
<p>AR Entertainment provides turnkey film crew hire, local administration coordination, and 4K cinema equipment packages across Narsingdi.</p>',
        'sort_order' => 7,
        'meta_title' => 'Video Production Company in Narsingdi | AR Entertainment',
        'meta_description' => 'Professional video production in Narsingdi, Bangladesh. Wari-Bateshwar documentaries, textile factory corporate AVs, and Meghna drone filming by AR Entertainment.'
    ],

    // 8. Faridpur
    [
        'city_name' => 'Faridpur',
        'slug' => 'video-production-company-in-faridpur',
        'title' => 'Video Production Company in Faridpur | AR Entertainment',
        'summary' => 'Padma river char island documentaries, jute industry films, and rural heritage visual storytelling in Faridpur by AR Entertainment.',
        'content' => '<h3>Riverine Culture, Golden Fiber &amp; Literary Heritage in Faridpur</h3>
<p>Located on the western banks of the Padma River, <strong>Faridpur</strong> is the heartland of Bangladesh’s "Golden Fiber" (raw jute processing), renowned for the literary legacy of Polli Kobi Jasimuddin and expansive riverine char landscapes.</p>
<h4>Distinctive Faridpur Filming Destinations</h4>
<ul>
    <li><strong>Poet Jasimuddin Homestead &amp; Mela Grounds:</strong> Classic rural Bengali village homestead under the shade of ancient banyan trees on the Kumar River.</li>
    <li><strong>Padma River Char Islands:</strong> Vast, shifting sandy islands and river estuaries perfect for dramatic survival films and climate adaptation documentaries.</li>
    <li><strong>Jute Mills &amp; Processing Centers:</strong> Golden fiber soaking, washing, and spinning factories showcasing Bangladesh’s sustainable export heritage.</li>
    <li><strong>Kanaipur Zamindar Bari:</strong> Historic colonial brick architecture surrounded by tranquil rural ponds.</li>
</ul>
<h4>Off-Grid Expedition Filming in Faridpur</h4>
<p>AR Entertainment deploys self-contained field units equipped with portable generators, riverboat charters, and drone packages to capture Faridpur\'s riverine majesty.</p>',
        'sort_order' => 8,
        'meta_title' => 'Video Production Company in Faridpur | AR Entertainment',
        'meta_description' => 'Documentary & commercial video production in Faridpur, Bangladesh. Jute industry corporate films, Padma char drone video, and heritage storytelling by AR Entertainment.'
    ],

    // 9. Gopalganj
    [
        'city_name' => 'Gopalganj',
        'slug' => 'video-production-company-in-gopalganj',
        'title' => 'Video Production Company in Gopalganj | AR Entertainment',
        'summary' => 'National memorial documentary filming, Madhumati riverways, and lush wetland agro-cinematography in Gopalganj by AR Entertainment.',
        'content' => '<h3>National Heritage &amp; Wetland Agro-Storytelling in Gopalganj</h3>
<p><strong>Gopalganj</strong> holds profound historical significance as the founding cradle of the Bengali nation, defined by the serene Madhumati River, lush wetland marshes (Beels), and modern national memorial architecture in Tungipara.</p>
<h4>Key Locations for Documentaries &amp; Institutional Films</h4>
<ul>
    <li><strong>Mausoleum Complex of Bangabandhu (Tungipara):</strong> Iconic white marble architectural complex surrounded by manicured gardens and reflecting pools.</li>
    <li><strong>Madhumati River &amp; Waterway Canals:</strong> Serene country boat journeys, floating market vendors, and traditional fishing nets.</li>
    <li><strong>Chanda Beel Wetland Ecosystem:</strong> Extensive biodiversity wetlands and migratory bird habitats ideal for environmental documentaries.</li>
    <li><strong>Orakandi Holy Shrine (Kashiani):</strong> Historic pilgrimage destination for the Matua community, vibrant with spiritual gatherings.</li>
</ul>
<h4>Protocol-Compliant Production Management</h4>
<p>AR Entertainment coordinates VIP security protocols, high-definition broadcast equipment, and licensed aerial flight authorizations across Gopalganj.</p>',
        'sort_order' => 9,
        'meta_title' => 'Video Production Company in Gopalganj | AR Entertainment',
        'meta_description' => 'Prestige video production in Gopalganj, Bangladesh. Tungipara national heritage filming, Madhumati river drone footage, and NGO documentaries by AR Entertainment.'
    ],

    // 10. Madaripur
    [
        'city_name' => 'Madaripur',
        'slug' => 'video-production-company-in-madaripur',
        'title' => 'Video Production Company in Madaripur | AR Entertainment',
        'summary' => 'Padma southern corridor filming, Arial Khan river cinematography, and traditional date palm Patali Gur documentaries in Madaripur by AR Entertainment.',
        'content' => '<h3>Southern Gateway Infrastructure &amp; Agro-Heritage in Madaripur</h3>
<p>Directly connected to the capital via the Padma Bridge southern link, <strong>Madaripur</strong> is thriving as a regional logistics gateway, famous for Arial Khan river trade routes and the traditional craftsmanship of date palm molasses (Patali Gur).</p>
<h4>Top Filming Locations in Madaripur</h4>
<ul>
    <li><strong>Padma Expressway Southern Corridor:</strong> Seamless high-speed transit filming, modern toll infrastructures, and connecting flyovers.</li>
    <li><strong>Arial Khan &amp; Kumar River Confluences:</strong> Picturesque riverbanks, rural ferry ghats, and river sunset panoramas.</li>
    <li><strong>Date Palm Syrup Harvesting Groves:</strong> Artisanal tree tapping at dawn and open-fire boiling of authentic winter Patali Gur.</li>
    <li><strong>Shakuni Dighi Lake &amp; Raja Ram Temple:</strong> Historic terracotta temple architecture and urban leisure waterfronts.</li>
</ul>
<h4>Complete Local Production Coordination</h4>
<p>AR Entertainment provides end-to-end line production, local fixer support, and cinema camera rentals across Madaripur and neighboring southern districts.</p>',
        'sort_order' => 10,
        'meta_title' => 'Video Production Company in Madaripur | AR Entertainment',
        'meta_description' => 'High-quality video production in Madaripur, Bangladesh. Padma link expressway filming, Arial Khan river aerials, and agro-documentaries by AR Entertainment.'
    ]
];

$stmt_upsert = $db->prepare("
    INSERT INTO service_areas (
        title, slug, city_name, summary, content, sort_order, status, meta_title, meta_description, created_at, updated_at
    ) VALUES (
        :title, :slug, :city_name, :summary, :content, :sort_order, 'active', :meta_title, :meta_description, NOW(), NOW()
    ) ON DUPLICATE KEY UPDATE
        title = VALUES(title),
        city_name = VALUES(city_name),
        summary = VALUES(summary),
        content = VALUES(content),
        sort_order = VALUES(sort_order),
        status = 'active',
        meta_title = VALUES(meta_title),
        meta_description = VALUES(meta_description),
        updated_at = NOW()
");

$seeded_count = 0;
foreach ($districts_batch1 as $dst) {
    $stmt_upsert->execute([
        ':title' => $dst['title'],
        ':slug' => $dst['slug'],
        ':city_name' => $dst['city_name'],
        ':summary' => $dst['summary'],
        ':content' => $dst['content'],
        ':sort_order' => $dst['sort_order'],
        ':meta_title' => $dst['meta_title'],
        ':meta_description' => $dst['meta_description']
    ]);

    echo "   ✅ [" . (++$seeded_count) . "/10] Seeded District Guide: {$dst['city_name']} ({$dst['slug']})\n";
}

$total_districts = (int)$db->query("SELECT COUNT(*) FROM service_areas")->fetchColumn();

echo "\n========================================================\n";
echo "🏆 BATCH 5.3.1 COMPLETED SUCCESSFULLY!\n";
echo "   - Total Districts Seeded in Batch: {$seeded_count}\n";
echo "   - Current Total in Database: {$total_districts}\n";
echo "========================================================\n";
