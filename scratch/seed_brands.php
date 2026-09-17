<?php
require_once __DIR__ . '/../config/db.php';
require_once __DIR__ . '/../config/helpers.php';

$count = (int) db()->query("SELECT COUNT(*) FROM brands")->fetchColumn();
if ($count === 0) {
    $clients = [
        ['name' => 'PRAN-RFL Group', 'logo' => 'images/clients/pran.png', 'website_url' => 'https://www.pranfoods.net', 'brand_type' => 'client', 'sort_order' => 1],
        ['name' => 'Bashundhara Group', 'logo' => 'images/clients/bashundhara-group.png', 'website_url' => 'https://www.bashundharagroup.com', 'brand_type' => 'client', 'sort_order' => 2],
        ['name' => 'Akij Cement', 'logo' => 'images/clients/akij-cement.png', 'website_url' => 'https://www.akijcement.com', 'brand_type' => 'client', 'sort_order' => 3],
        ['name' => 'TEER (City Group)', 'logo' => 'images/clients/teer.png', 'website_url' => 'https://www.citygroupbd.com', 'brand_type' => 'client', 'sort_order' => 4],
        ['name' => 'Bombay Sweets', 'logo' => 'images/clients/bombay-sweets.png', 'website_url' => 'https://www.bombaysweetsbd.com', 'brand_type' => 'client', 'sort_order' => 5],
        ['name' => 'Olympic Industries', 'logo' => 'images/clients/olympic-industries.png', 'website_url' => 'https://www.olympicbd.com', 'brand_type' => 'client', 'sort_order' => 6],
        ['name' => 'Bangladesh Army', 'logo' => 'images/clients/bangladesh-army.png', 'website_url' => 'https://www.army.mil.bd', 'brand_type' => 'client', 'sort_order' => 7],
        ['name' => 'Elite Paint', 'logo' => 'images/clients/elite-paint.png', 'website_url' => 'https://www.elitepaint.com.bd', 'brand_type' => 'client', 'sort_order' => 8],
        ['name' => 'Concord Group', 'logo' => 'images/clients/concord.png', 'website_url' => 'https://www.concordgroupbd.com', 'brand_type' => 'client', 'sort_order' => 9],
        ['name' => 'Fantasy Kingdom', 'logo' => 'images/clients/fantasy-kingdom.png', 'website_url' => 'https://www.fantasykingdom.net', 'brand_type' => 'client', 'sort_order' => 10],
        ['name' => 'a2i - Aspire to Innovate', 'logo' => 'images/clients/a2i.png', 'website_url' => 'https://a2i.gov.bd', 'brand_type' => 'client', 'sort_order' => 11],
        ['name' => 'DGHS Bangladesh', 'logo' => 'images/clients/dghs.png', 'website_url' => 'https://dghs.gov.bd', 'brand_type' => 'client', 'sort_order' => 12],
        ['name' => 'IFAD Group', 'logo' => 'images/clients/ifad.png', 'website_url' => 'https://www.ifadgroup.com', 'brand_type' => 'client', 'sort_order' => 13],
        ['name' => 'Ashok Leyland', 'logo' => 'images/clients/ashok-leyland.png', 'website_url' => 'https://www.ashokleyland.com', 'brand_type' => 'client', 'sort_order' => 14],
        ['name' => 'Coppertech Industries', 'logo' => 'images/clients/coppertech.png', 'website_url' => 'https://www.coppertechbd.com', 'brand_type' => 'client', 'sort_order' => 15],
        ['name' => 'CSRM Steel', 'logo' => 'images/clients/csrm.png', 'website_url' => 'https://www.csrmbd.com', 'brand_type' => 'client', 'sort_order' => 16],
        ['name' => 'Astha Life Insurance', 'logo' => 'images/clients/astha-life.png', 'website_url' => 'https://www.asthalife.com.bd', 'brand_type' => 'client', 'sort_order' => 17],
        ['name' => 'Labib Group', 'logo' => 'images/clients/labib-group.png', 'website_url' => 'https://www.labibgroup.com', 'brand_type' => 'client', 'sort_order' => 18],
        ['name' => 'MK Electronics', 'logo' => 'images/clients/mk-electronics.png', 'website_url' => 'https://www.mke.com.bd', 'brand_type' => 'client', 'sort_order' => 19],
        ['name' => 'Gardenia Wears', 'logo' => 'images/clients/gardenia.png', 'website_url' => 'https://www.gardeniawears.com', 'brand_type' => 'client', 'sort_order' => 20],
        ['name' => 'Surjer Hashi Network', 'logo' => 'images/clients/surjer-hashi-network.png', 'website_url' => 'https://www.surjerhashi.org.bd', 'brand_type' => 'client', 'sort_order' => 21],
        ['name' => 'Canadian University of Bangladesh', 'logo' => 'images/clients/canadian-university-of-bangladesh.png', 'website_url' => 'https://cub.edu.bd', 'brand_type' => 'client', 'sort_order' => 22],
        
        // Partners
        ['name' => 'Chorki OTT', 'logo' => 'images/chorki.png', 'website_url' => 'https://www.chorki.com', 'brand_type' => 'partner', 'sort_order' => 1],
        ['name' => 'Hoichoi TV', 'logo' => 'images/hoichoi.png', 'website_url' => 'https://www.hoichoi.tv', 'brand_type' => 'partner', 'sort_order' => 2],
        ['name' => 'Bioscope Live', 'logo' => 'images/bioscope.png', 'website_url' => 'https://www.bioscopelive.com', 'brand_type' => 'partner', 'sort_order' => 3],
        ['name' => 'Binge OTT', 'logo' => 'images/binge.png', 'website_url' => 'https://binge.buzz', 'brand_type' => 'partner', 'sort_order' => 4],
        ['name' => 'Bongo BD', 'logo' => 'images/bongo.png', 'website_url' => 'https://bongobd.com', 'brand_type' => 'partner', 'sort_order' => 5],
        ['name' => 'iScreen', 'logo' => 'images/iscreen.png', 'website_url' => 'https://iscreen.com.bd', 'brand_type' => 'partner', 'sort_order' => 6],
        ['name' => 'GoodFirms Partner', 'logo' => 'images/partnered-with-goodfirms.webp', 'website_url' => 'https://www.goodfirms.co', 'brand_type' => 'affiliation', 'sort_order' => 1]
    ];

    $stmt = db()->prepare("
        INSERT INTO brands (name, logo, website_url, brand_type, sort_order, status, created_at, updated_at)
        VALUES (?, ?, ?, ?, ?, 'active', NOW(), NOW())
    ");

    foreach ($clients as $c) {
        $stmt->execute([$c['name'], $c['logo'], $c['website_url'], $c['brand_type'], $c['sort_order']]);
    }

    echo "Seeded " . count($clients) . " initial brands/clients/partners records successfully.\n";
} else {
    echo "Brands table already has $count records.\n";
}
