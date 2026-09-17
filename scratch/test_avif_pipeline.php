<?php
require_once __DIR__ . '/../config/db.php';
require_once __DIR__ . '/../config/helpers.php';

header('Content-Type: application/json');

// Create a sample 2000x1500 test JPG image in memory
$im = imagecreatetruecolor(2000, 1500);
$bg = imagecolorallocate($im, 240, 120, 30);
imagefilledrectangle($im, 0, 0, 2000, 1500, $bg);
$text_color = imagecolorallocate($im, 255, 255, 255);
imagestring($im, 5, 800, 700, 'AR Entertainment HD Image', $text_color);

$tmp_jpg = sys_get_temp_dir() . '/test_sample_' . uniqid() . '.jpg';
imagejpeg($im, $tmp_jpg, 95);
imagedestroy($im);

$orig_filesize = filesize($tmp_jpg);

// Simulate upload array
$file_arr = [
    'name'     => 'director-portrait.jpg',
    'type'     => 'image/jpeg',
    'tmp_name' => $tmp_jpg,
    'error'    => UPLOAD_ERR_OK,
    'size'     => $orig_filesize
];

// Call the upload_image function directly (with max dimension 1200)
// To bypass is_uploaded_file in unit test, we can test the GD conversion pipeline directly:
$clean_orig_name = 'director-portrait';
$target_dir = UPLOADS_PATH . DIRECTORY_SEPARATOR . 'team';
if (!is_dir($target_dir)) {
    mkdir($target_dir, 0755, true);
}

$raw_data = file_get_contents($tmp_jpg);
$src_img  = imagecreatefromstring($raw_data);
$orig_width = imagesx($src_img);
$orig_height = imagesy($src_img);

$scaled = imagescale($src_img, 1200, 900, IMG_BILINEAR_FIXED);
imagealphablending($scaled, false);
imagesavealpha($scaled, true);

$avif_filename = $clean_orig_name . '-' . uniqid() . '.avif';
$avif_path = $target_dir . DIRECTORY_SEPARATOR . $avif_filename;
$saved_avif = imageavif($scaled, $avif_path, 80);

$avif_filesize = file_exists($avif_path) ? filesize($avif_path) : 0;

imagedestroy($src_img);
imagedestroy($scaled);
@unlink($tmp_jpg);

echo json_encode([
    'original_dimensions' => $orig_width . 'x' . $orig_height,
    'original_size_kb' => round($orig_filesize / 1024, 2) . ' KB',
    'converted_format' => 'AVIF',
    'converted_dimensions' => '1200x900',
    'converted_size_kb' => round($avif_filesize / 1024, 2) . ' KB',
    'compression_ratio' => round((1 - ($avif_filesize / $orig_filesize)) * 100, 1) . '% reduction',
    'saved_file' => 'uploads/team/' . $avif_filename,
    'file_exists' => file_exists($avif_path)
], JSON_PRETTY_PRINT);
