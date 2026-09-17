<?php
$profiles = [
    'http://localhost/ar-entertainment/about-us/meet-the-team/6635_azizul-hoque-shiplu',
    'http://localhost/ar-entertainment/about-us/meet-the-team/6636_tanveer-ahmed',
    'http://localhost/ar-entertainment/about-us/meet-the-team/6637_monirul-islam-masum',
    'http://localhost/ar-entertainment/about-us/meet-the-team/6638_prosenjit-mitra',
];

foreach ($profiles as $url) {
    $ch = curl_init($url);
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
    curl_setopt($ch, CURLOPT_HEADER, true);
    curl_setopt($ch, CURLOPT_FOLLOWLOCATION, true);
    $res = curl_exec($ch);
    $info = curl_getinfo($ch);
    echo "$url -> HTTP " . $info['http_code'] . " (Effective: " . $info['url'] . ")\n";
}
