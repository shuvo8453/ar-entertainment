<?php
$css = file_get_contents(__DIR__ . '/../inc/style.css');
preg_match_all('/[^{}]*(?:inner-banner|banner-content)[^{}]*\{[^}]*\}/i', $css, $matches);
foreach ($matches[0] as $rule) {
    echo $rule . "\n-----------------\n";
}
