<?php
ob_start();
require __DIR__ . '/../index.php';
$html = ob_get_clean();
file_put_contents(__DIR__ . '/test_index_utf8.html', $html);

$checks = [
    'Title'               => 'Video Production and Film Fixer Services in Bangladesh',
    'Bashundhara City'    => 'Bashundhara City',
    'TEER Aromatic Rice'  => 'TEER Aromatic Rice',
    'Ehtesham Ahmed'      => 'Ehtesham Ahmed',
    'PRAN-RFL Group'      => 'PRAN-RFL Group',
    'Latest Articles'     => 'Latest Articles',
    'Azizul Hoque Shiplu' => 'Azizul Hoque Shiplu',
    'FAQ Section'         => 'How much does commercial video production cost in Bangladesh?'
];
foreach ($checks as $name => $needle) {
    echo $name . ': ' . (strpos($html, $needle) !== false ? 'OK' : 'MISSING') . "\n";
}
