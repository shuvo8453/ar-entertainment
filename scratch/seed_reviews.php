<?php
require_once __DIR__ . '/../config/db.php';
require_once __DIR__ . '/../config/helpers.php';

$count = (int) db()->query("SELECT COUNT(*) FROM reviews")->fetchColumn();
if ($count === 0) {
    $seed_reviews = [
        [
            'client_name' => 'Ehtesham Ahmed',
            'client_company' => 'City Group',
            'client_designation' => 'Brand Manager',
            'client_photo' => 'images/ehtesham-ahmed.webp',
            'review_text' => 'Fantastic team. They took ownership of the project. As a result, they gave very good creative inputs to improve the ad. They also tried hard to reduce the project cost. They are definitely into my consideration for future works.',
            'rating' => 5.0,
            'project_name' => 'TEER Brand Commercial (TVC)',
            'source' => 'google',
            'sort_order' => 1
        ],
        [
            'client_name' => 'Md. Jamil Hossain Chowdhury',
            'client_company' => 'PRAN Foods Ltd.',
            'client_designation' => 'Senior Brand Executive',
            'client_photo' => 'images/jamil-hossain.webp',
            'review_text' => 'The absolute best production team out there. They are incredibly fast, reliable, friendly, and their work blew us away by their professionalism and ease of handling our projects from start to finish. If you are considering them, do not think twice.',
            'rating' => 5.0,
            'project_name' => 'PRAN OVC Campaign',
            'source' => 'google',
            'sort_order' => 2
        ],
        [
            'client_name' => 'Riadul Islam',
            'client_company' => 'Akij Group',
            'client_designation' => 'Head of Communications',
            'client_photo' => 'images/riad.webp',
            'review_text' => 'Working with AR Entertainment was a seamless experience. From the initial storyboard discussions to the final color grading and sound mix, everything was delivered on schedule with international production standards.',
            'rating' => 5.0,
            'project_name' => 'Akij Cement Corporate AV',
            'source' => 'goodfirms',
            'sort_order' => 3
        ],
        [
            'client_name' => 'Sarah Jenkins',
            'client_company' => 'BBC StoryWorks Co-Production',
            'client_designation' => 'Line Producer UK',
            'client_photo' => null,
            'review_text' => 'Exceptional fixer and production support in Bangladesh. Azizul and his crew handled our filming permits, gear rental, and logistics across Dhaka and the Sundarbans with supreme efficiency and zero hassle.',
            'rating' => 5.0,
            'project_name' => 'International Documentary Support',
            'source' => 'clutch',
            'sort_order' => 4
        ],
        [
            'client_name' => 'Tanvir Mahbub',
            'client_company' => 'Concord Real Estate',
            'client_designation' => 'Marketing Director',
            'client_photo' => null,
            'review_text' => 'Their creative direction and drone cinematography for our real estate project exceeded our expectations. The video drove incredible engagement on social media.',
            'rating' => 4.8,
            'project_name' => 'Real Estate Showcase Film',
            'source' => 'direct',
            'sort_order' => 5
        ]
    ];

    $stmt = db()->prepare("
        INSERT INTO reviews (
            client_name,
            client_company,
            client_designation,
            client_photo,
            review_text,
            rating,
            project_name,
            source,
            sort_order,
            status,
            created_at,
            updated_at
        ) VALUES (
            :client_name,
            :client_company,
            :client_designation,
            :client_photo,
            :review_text,
            :rating,
            :project_name,
            :source,
            :sort_order,
            'active',
            NOW(),
            NOW()
        )
    ");

    foreach ($seed_reviews as $r) {
        $stmt->execute([
            ':client_name'        => $r['client_name'],
            ':client_company'     => $r['client_company'],
            ':client_designation' => $r['client_designation'],
            ':client_photo'       => $r['client_photo'],
            ':review_text'        => $r['review_text'],
            ':rating'             => $r['rating'],
            ':project_name'       => $r['project_name'],
            ':source'             => $r['source'],
            ':sort_order'         => $r['sort_order']
        ]);
    }

    echo "Seeded " . count($seed_reviews) . " initial client reviews successfully.\n";
} else {
    echo "Reviews table already has $count records.\n";
}
