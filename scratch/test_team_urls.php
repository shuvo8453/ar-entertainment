<?php
$urls = [
    'http://localhost/ar-entertainment/about-us/meet-the-team',
    'http://localhost/ar-entertainment/about-us/meet-the-team/',
    'http://localhost/ar-entertainment/about-us/meet-the-team/6635_azizul-hoque-shiplu',
    'http://localhost/ar-entertainment/about-us/meet-the-team/6635_azizul-hoque-shiplu.html',
    'http://localhost/ar-entertainment/about-us/meet-the-team/meet-the-team/6635_azizul-hoque-shiplu'
];

foreach ($urls as $url) {
    $ch = curl_init($url);
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
    curl_setopt($ch, CURLOPT_HEADER, true);
    curl_setopt($ch, CURLOPT_FOLLOWLOCATION, false);
    $res = curl_exec($ch);
    $info = curl_getinfo($ch);
    echo "URL: $url\n";
    echo "  -> CODE: " . $info['http_code'] . "\n";
    if (!empty($info['redirect_url'])) {
        echo "  -> REDIRECT: " . $info['redirect_url'] . "\n";
    }
}
