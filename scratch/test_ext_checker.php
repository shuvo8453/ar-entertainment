<?php
$file_name = 'myphoto.jfif';
$orig_ext = strtolower(pathinfo($file_name, PATHINFO_EXTENSION));
$valid_exts = ['jpg', 'jpeg', 'png', 'webp'];

echo "File: $file_name -> Ext: $orig_ext\n";
echo "Is Valid: " . (in_array($orig_ext, $valid_exts, true) ? 'YES' : 'NO (BLOCKED)') . "\n";

$test_files = ['avatar.jpg', 'team.png', 'director.jpeg', 'cinematographer.webp', 'photo.jfif', 'banner.gif', 'hack.php'];
foreach ($test_files as $f) {
    $ext = strtolower(pathinfo($f, PATHINFO_EXTENSION));
    $status = in_array($ext, $valid_exts, true) ? 'ALLOWED' : 'BLOCKED';
    echo "  - $f -> $status\n";
}
