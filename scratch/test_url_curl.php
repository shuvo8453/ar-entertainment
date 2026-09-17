<?php
$ch = curl_init('http://localhost/ar-entertainment/about-us/meet-the-team/meet-the-team/6635_azizul-hoque-shiplu');
curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
curl_setopt($ch, CURLOPT_HEADER, true);
curl_setopt($ch, CURLOPT_FOLLOWLOCATION, false);
$res = curl_exec($ch);
$info = curl_getinfo($ch);
echo "HTTP CODE: " . $info['http_code'] . "\n";
echo "REDIRECT: " . ($info['redirect_url'] ?? '') . "\n";
echo substr($res, 0, 500) . "\n";
