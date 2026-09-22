<?php
$html = file_get_contents(__DIR__ . '/test_index_output.html');
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
