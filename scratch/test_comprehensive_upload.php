<?php
require_once __DIR__ . '/../config/config.php';
require_once __DIR__ . '/../config/helpers.php';

echo "Testing upload_image comprehensive formats..." . PHP_EOL;

// 1. Create a dummy PNG in memory
$im_png = imagecreatetruecolor(100, 100);
imagealphablending($im_png, false);
imagesavealpha($im_png, true);
$trans = imagecolorallocatealpha($im_png, 0, 0, 0, 127);
imagefill($im_png, 0, 0, $trans);
$red = imagecolorallocate($im_png, 255, 0, 0);
imagefilledrectangle($im_png, 20, 20, 80, 80, $red);
$png_temp = tempnam(sys_get_temp_dir(), 'png_');
imagepng($im_png, $png_temp);
imagedestroy($im_png);

$file_png = [
    'name' => 'test-alpha.png',
    'type' => 'image/png',
    'tmp_name' => $png_temp,
    'error' => UPLOAD_ERR_OK,
    'size' => filesize($png_temp)
];
$res_png = upload_image($file_png, 'test_uploads');
echo "PNG Test: " . ($res_png['success'] ? "PASS ({$res_png['path']})" : "FAIL: {$res_png['error']}") . PHP_EOL;

// 2. Create a dummy JPEG
$im_jpg = imagecreatetruecolor(100, 100);
$blue = imagecolorallocate($im_jpg, 0, 0, 255);
imagefill($im_jpg, 0, 0, $blue);
$jpg_temp = tempnam(sys_get_temp_dir(), 'jpg_');
imagejpeg($im_jpg, $jpg_temp);
imagedestroy($im_jpg);

$file_jpg = [
    'name' => 'test-photo.jpg',
    'type' => 'image/jpeg',
    'tmp_name' => $jpg_temp,
    'error' => UPLOAD_ERR_OK,
    'size' => filesize($jpg_temp)
];
$res_jpg = upload_image($file_jpg, 'test_uploads');
echo "JPG Test: " . ($res_jpg['success'] ? "PASS ({$res_jpg['path']})" : "FAIL: {$res_jpg['error']}") . PHP_EOL;

// 3. Test JFIF file extension with JPEG stream
$jfif_temp = tempnam(sys_get_temp_dir(), 'jfif_');
copy($jpg_temp, $jfif_temp);
$file_jfif = [
    'name' => 'sample-picture.jfif',
    'type' => 'image/jpeg',
    'tmp_name' => $jfif_temp,
    'error' => UPLOAD_ERR_OK,
    'size' => filesize($jfif_temp)
];
$res_jfif = upload_image($file_jfif, 'test_uploads');
echo "JFIF Test: " . ($res_jfif['success'] ? "PASS ({$res_jfif['path']})" : "FAIL: {$res_jfif['error']}") . PHP_EOL;

// 4. Test SVG file upload
$svg_content = '<svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 100 100"><circle cx="50" cy="50" r="40" fill="green"/></svg>';
$svg_temp = tempnam(sys_get_temp_dir(), 'svg_');
file_put_contents($svg_temp, $svg_content);
$file_svg = [
    'name' => 'vector-icon.svg',
    'type' => 'image/svg+xml',
    'tmp_name' => $svg_temp,
    'error' => UPLOAD_ERR_OK,
    'size' => filesize($svg_temp)
];
$res_svg = upload_image($file_svg, 'test_uploads');
echo "SVG Test: " . ($res_svg['success'] ? "PASS ({$res_svg['path']})" : "FAIL: {$res_svg['error']}") . PHP_EOL;
if ($res_svg['success']) {
    echo "  -> SVG extension preserved: " . (str_ends_with($res_svg['path'], '.svg') ? "YES (NO AVIF conversion)" : "NO") . PHP_EOL;
}

// Clean up test files
@unlink($png_temp);
@unlink($jpg_temp);
@unlink($jfif_temp);
@unlink($svg_temp);
