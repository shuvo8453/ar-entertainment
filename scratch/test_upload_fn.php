<?php

function upload_image_optimized(
    array $file,
    string $folder = 'blogs',
    array $allowed_types = ['image/jpeg', 'image/png', 'image/webp'],
    int $max_size = 5242880,
    int $max_dimension = 1200,
    int $quality = 82
): array {
    if (empty($file['tmp_name']) || !is_uploaded_file($file['tmp_name'])) {
        return ['success' => false, 'path' => '', 'filename' => '', 'error' => 'No file was uploaded or upload failed.'];
    }

    if ($file['error'] !== UPLOAD_ERR_OK) {
        return ['success' => false, 'path' => '', 'filename' => '', 'error' => 'File upload error code: ' . $file['error']];
    }

    if ($file['size'] > $max_size) {
        return ['success' => false, 'path' => '', 'filename' => '', 'error' => 'File size exceeds maximum limit (' . round($max_size / 1048576, 1) . 'MB).'];
    }

    $finfo = finfo_open(FILEINFO_MIME_TYPE);
    $mime = finfo_file($finfo, $file['tmp_name']);
    finfo_close($finfo);

    if (!in_array($mime, $allowed_types, true)) {
        return ['success' => false, 'path' => '', 'filename' => '', 'error' => 'Invalid image format. Allowed: JPG, JPEG, PNG, WebP.'];
    }

    $target_dir = __DIR__ . '/uploads/' . trim($folder, '/\\');
    if (!is_dir($target_dir)) {
        mkdir($target_dir, 0755, true);
    }

    $clean_orig_name = preg_replace('~[^a-zA-Z0-9_-]+~', '-', pathinfo($file['name'], PATHINFO_FILENAME));
    $clean_orig_name = trim($clean_orig_name, '-');
    if (empty($clean_orig_name)) {
        $clean_orig_name = 'image';
    }

    // If SVG, save directly as vector code
    if ($mime === 'image/svg+xml') {
        $filename = $clean_orig_name . '-' . uniqid() . '.svg';
        $target_file = $target_dir . DIRECTORY_SEPARATOR . $filename;
        if (move_uploaded_file($file['tmp_name'], $target_file)) {
            return ['success' => true, 'path' => $folder . '/' . $filename, 'filename' => $filename, 'error' => ''];
        }
        return ['success' => false, 'path' => '', 'filename' => '', 'error' => 'Failed to save SVG file.'];
    }

    // Check if GD image processing is available
    if (extension_loaded('gd') && function_exists('imagecreatefromstring')) {
        $raw_data = file_get_contents($file['tmp_name']);
        $src_img = @imagecreatefromstring($raw_data);

        if ($src_img !== false) {
            $orig_width = imagesx($src_img);
            $orig_height = imagesy($src_img);

            // Scale down proportionally if oversized
            $out_img = $src_img;
            if ($orig_width > $max_dimension || $orig_height > $max_dimension) {
                if ($orig_width >= $orig_height) {
                    $new_width = $max_dimension;
                    $new_height = (int) round(($orig_height / $orig_width) * $max_dimension);
                } else {
                    $new_height = $max_dimension;
                    $new_width = (int) round(($orig_width / $orig_height) * $max_dimension);
                }

                $scaled = imagescale($src_img, $new_width, $new_height, IMG_BILINEAR_FIXED);
                if ($scaled !== false) {
                    $out_img = $scaled;
                    if ($src_img !== $out_img) {
                        imagedestroy($src_img);
                    }
                }
            }

            // Preserve alpha transparency for PNG / WebP / AVIF
            imagealphablending($out_img, false);
            imagesavealpha($out_img, true);

            // Determine optimal output format (AVIF -> WebP -> JPG/PNG)
            $saved = false;
            $ext = 'jpg';

            if (function_exists('imageavif')) {
                $ext = 'avif';
                $filename = $clean_orig_name . '-' . uniqid() . '.' . $ext;
                $target_file = $target_dir . DIRECTORY_SEPARATOR . $filename;
                $saved = @imageavif($out_img, $target_file, $quality);
            } elseif (function_exists('imagewebp')) {
                $ext = 'webp';
                $filename = $clean_orig_name . '-' . uniqid() . '.' . $ext;
                $target_file = $target_dir . DIRECTORY_SEPARATOR . $filename;
                $saved = @imagewebp($out_img, $target_file, $quality);
            } elseif ($mime === 'image/png' && function_exists('imagepng')) {
                $ext = 'png';
                $filename = $clean_orig_name . '-' . uniqid() . '.' . $ext;
                $target_file = $target_dir . DIRECTORY_SEPARATOR . $filename;
                $saved = @imagepng($out_img, $target_file, 8);
            } elseif (function_exists('imagejpeg')) {
                $ext = 'jpg';
                $filename = $clean_orig_name . '-' . uniqid() . '.' . $ext;
                $target_file = $target_dir . DIRECTORY_SEPARATOR . $filename;
                $saved = @imagejpeg($out_img, $target_file, $quality);
            }

            imagedestroy($out_img);

            if ($saved && file_exists($target_file)) {
                return ['success' => true, 'path' => $folder . '/' . $filename, 'filename' => $filename, 'error' => ''];
            }
        }
    }

    // Direct fallback if GD is not loaded or processing was not possible
    $ext_map = [
        'image/jpeg' => 'jpg',
        'image/png'  => 'png',
        'image/webp' => 'webp',
        'image/gif'  => 'gif',
        'image/svg+xml' => 'svg'
    ];
    $ext = $ext_map[$mime] ?? pathinfo($file['name'], PATHINFO_EXTENSION);
    $ext = strtolower($ext);

    $filename = $clean_orig_name . '-' . uniqid() . '.' . $ext;
    $target_file = $target_dir . DIRECTORY_SEPARATOR . $filename;

    if (move_uploaded_file($file['tmp_name'], $target_file)) {
        return ['success' => true, 'path' => $folder . '/' . $filename, 'filename' => $filename, 'error' => ''];
    }

    return ['success' => false, 'path' => '', 'filename' => '', 'error' => 'Failed to save uploaded file to storage directory.'];
}

echo "Function parsed successfully.\n";
