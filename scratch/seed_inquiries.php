<?php
/**
 * AR Entertainment - Inquiries Seed Data Generator
 * Populates realistic customer leads for testing Phase 4.7.
 */
require_once __DIR__ . '/../config/db.php';
require_once __DIR__ . '/../config/helpers.php';

$leads = [
    [
        'form_type'       => 'quote',
        'name'            => 'Tanvir Ahmed',
        'email'           => 'tanvir.ahmed@apexfootwear.com',
        'phone'           => '+880 1711-234567',
        'subject'         => 'TV Commercial & Digital Campaign Production for Eid 2027',
        'message'         => "Hello AR Entertainment Team,\n\nWe are looking to produce a 60-second national TV commercial and 3 digital cutdowns for our upcoming Eid footwear collection. We need end-to-end production support including creative scripting, multi-location shooting (Dhaka & Sylhet), high-end post-production, color grading, and original sound design.\n\nPlease share your availability and estimated quotation.",
        'extra_data_json' => json_encode([
            'company_name'    => 'Apex Footwear Ltd.',
            'budget_range'    => '৳ 1,500,000 - ৳ 2,500,000 BDT',
            'target_timeline' => 'Within 45 Days',
            'services_needed' => ['TV Commercial (TVC)', 'Digital OVC', 'Color Grading', 'Sound Design'],
            'shoot_location'  => 'Dhaka & Sylhet',
        ], JSON_PRETTY_PRINT),
        'is_read'         => 0,
        'ip_address'      => '103.205.71.42',
        'user_agent'      => 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/128.0.0.0 Safari/537.36',
        'created_at'      => date('Y-m-d H:i:s', strtotime('-2 hours'))
    ],
    [
        'form_type'       => 'contact',
        'name'            => 'Sarah Jenkins',
        'email'           => 's.jenkins@bbclondon.co.uk',
        'phone'           => '+44 20 7946 0912',
        'subject'         => 'Line Production & Fixer Services in Chittagong Hill Tracts',
        'message'         => "Hi,\n\nWe are an international documentary film crew traveling from the UK in November for a nature and heritage series. We require local line production, equipment hire (RED V-Raptor / Sony FX9 lenses), bilingual fixing crew, and government filming permits in Bandarban and Rangamati.\n\nCould we schedule a discovery Zoom call this week?",
        'extra_data_json' => json_encode([
            'organization'    => 'BBC Media Action / Freelance Doc Unit',
            'country'         => 'United Kingdom',
            'crew_size'       => '6 Persons',
            'equipment_needs' => 'RED V-Raptor package, Cinema Primes, Ronin 2 Rig',
            'tentative_dates' => 'Nov 12 - Nov 28, 2026'
        ], JSON_PRETTY_PRINT),
        'is_read'         => 0,
        'ip_address'      => '82.165.197.1',
        'user_agent'      => 'Mozilla/5.0 (Macintosh; Intel Mac OS X 10_15_7) AppleWebKit/605.1.15 (KHTML, like Gecko) Version/17.5 Safari/605.1.15',
        'created_at'      => date('Y-m-d H:i:s', strtotime('-1 day'))
    ],
    [
        'form_type'       => 'careers',
        'name'            => 'Mahmudul Hasan Rafi',
        'email'           => 'rafi.cinematography@gmail.com',
        'phone'           => '+880 1822-987654',
        'subject'         => 'Application for Senior Video Editor & Colorist Role',
        'message'         => "Dear Hiring Team at AR Entertainment,\n\nI have been following AR Entertainment’s commercial and music video projects for over 3 years. I have 5+ years of experience in DaVinci Resolve Studio color grading (ACES workflow) and Adobe Premiere Pro editing for top FMCG brands in Bangladesh.\n\nPlease find my showreel and resume attached in the link below. I would love the opportunity to contribute to your creative team.",
        'extra_data_json' => json_encode([
            'portfolio_url'   => 'https://vimeo.com/showreel/rafi-colorist-2026',
            'linkedin_url'    => 'https://linkedin.com/in/rafi-hasan-edit',
            'experience_yrs'  => '5+ Years',
            'primary_tools'   => ['DaVinci Resolve Studio', 'Adobe Premiere Pro', 'After Effects'],
            'expected_salary' => '৳ 60,000 - ৳ 80,000 / month'
        ], JSON_PRETTY_PRINT),
        'is_read'         => 0,
        'ip_address'      => '118.179.88.210',
        'user_agent'      => 'Mozilla/5.0 (Windows NT 10.0; Win64; x64; rv:129.0) Gecko/20100101 Firefox/129.0',
        'created_at'      => date('Y-m-d H:i:s', strtotime('-2 days'))
    ],
    [
        'form_type'       => 'quote',
        'name'            => 'Kazi Naimur Rahman',
        'email'           => 'marketing@pran-rfl.com',
        'phone'           => '+880 1912-345678',
        'subject'         => 'Corporate Documentary & Factory Drone Cinematic Shoot',
        'message'         => "Greetings,\n\nPRAN-RFL Group is planning an extensive 15-minute corporate documentary and short brand highlights showcasing our new green industrial park in Habiganj. We require 4K 10-bit cinema camera recording, FPV drone cinematography, and bilingual voiceover (English & Bengali).\n\nPlease send us your corporate profile and sample portfolio.",
        'extra_data_json' => json_encode([
            'company_name'    => 'PRAN-RFL Group',
            'budget_range'    => '৳ 800,000 - ৳ 1,200,000 BDT',
            'target_timeline' => 'Within 30 Days',
            'location'        => 'Habiganj Industrial Park'
        ], JSON_PRETTY_PRINT),
        'is_read'         => 1,
        'ip_address'      => '203.112.218.10',
        'user_agent'      => 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/127.0.0.0 Safari/537.36',
        'created_at'      => date('Y-m-d H:i:s', strtotime('-4 days'))
    ],
    [
        'form_type'       => 'survey',
        'name'            => 'Nusrat Jahan',
        'email'           => 'nusrat.j@daraz.com.bd',
        'phone'           => '+880 1611-456789',
        'subject'         => 'Post-Production Project Feedback & Client Satisfaction',
        'message'         => "We recently completed the 11.11 Mega Sale commercial campaign with AR Entertainment. The turnaround speed, VFX motion graphics, and audio mixing quality exceeded our expectations. Looking forward to working together again for upcoming campaigns.",
        'extra_data_json' => json_encode([
            'overall_rating'  => '5 / 5 (Excellent)',
            'quality_score'   => '10 / 10',
            'communication'   => '10 / 10',
            'would_recommend' => 'Yes, Absolutely',
            'preferred_next'  => ['3D Product Animation', 'OVC Series']
        ], JSON_PRETTY_PRINT),
        'is_read'         => 1,
        'ip_address'      => '103.108.144.5',
        'user_agent'      => 'Mozilla/5.0 (iPhone; CPU iPhone OS 17_5_1 like Mac OS X) AppleWebKit/605.1.15 (KHTML, like Gecko) Version/17.5 Mobile/15E148 Safari/604.1',
        'created_at'      => date('Y-m-d H:i:s', strtotime('-6 days'))
    ],
    [
        'form_type'       => 'contact',
        'name'            => 'Rezaul Karim',
        'email'           => 'rezaul@bengalgroup.com',
        'phone'           => '+880 1713-998877',
        'subject'         => 'Inquiry about Studio & Cinema Equipment Rental',
        'message'         => "Dear AR Entertainment,\n\nWe need to rent a 3-point ARRI Skypanel lighting kit, wireless video transmitters (Teradek), and a heavy-duty dolly track for 3 days starting next Monday at BFDC Dhaka. Please let us know your standard rental catalog and deposit policies.",
        'extra_data_json' => json_encode([
            'rental_days'     => '3 Days',
            'shoot_location'  => 'BFDC, Tejgaon, Dhaka'
        ], JSON_PRETTY_PRINT),
        'is_read'         => 0,
        'ip_address'      => '103.242.23.18',
        'user_agent'      => 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/128.0.0.0 Safari/537.36',
        'created_at'      => date('Y-m-d H:i:s', strtotime('-3 hours'))
    ]
];

$stmt = db()->prepare("
    INSERT INTO inquiries (form_type, name, email, phone, subject, message, extra_data_json, is_read, ip_address, user_agent, created_at)
    VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?)
");

$inserted = 0;
foreach ($leads as $lead) {
    $stmt->execute([
        $lead['form_type'],
        $lead['name'],
        $lead['email'],
        $lead['phone'],
        $lead['subject'],
        $lead['message'],
        $lead['extra_data_json'],
        $lead['is_read'],
        $lead['ip_address'],
        $lead['user_agent'],
        $lead['created_at']
    ]);
    $inserted++;
}

echo "Successfully seeded {$inserted} sample inquiries into 'inquiries' table!\n";
