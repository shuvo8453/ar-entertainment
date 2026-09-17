<?php
require_once __DIR__ . '/../config/db.php';
require_once __DIR__ . '/../config/helpers.php';

// Test 1: Fake JFIF file
$temp_jfif = sys_get_temp_dir() . '/test.jfif';
file_put_contents($temp_jfif, 'fake image data');

$fake_file_jfif = [
    'name' => 'photo.jfif',
    'type' => 'image/jpeg',
    'tmp_name' => $temp_jfif,
    'error' => UPLOAD_ERR_OK,
    'size' => 1024
];

$res1 = upload_image($fake_file_jfif, 'team');
echo "JFIF upload result:\n";
print_r($res1);

@unlink($temp_jfif);
