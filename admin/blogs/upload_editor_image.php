<?php

/**
 * AR Entertainment - TinyMCE Editor Image Upload Handler
 * 
 * Secure asynchronous endpoint for uploading inline article images.
 */

require_once dirname(__DIR__) . '/auth_check.php';

header('Content-Type: application/json; charset=utf-8');

if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    http_response_code(405);
    echo json_encode(['error' => 'Method not allowed']);
    exit;
}

if (!verify_csrf()) {
    http_response_code(403);
    echo json_encode(['error' => 'CSRF verification failed']);
    exit;
}

if (empty($_FILES['file'])) {
    http_response_code(400);
    echo json_encode(['error' => 'No image file uploaded']);
    exit;
}

$upload_result = upload_image($_FILES['file'], 'blogs', ['image/jpeg', 'image/png', 'image/webp', 'image/gif'], 5242880);

if (!$upload_result['success']) {
    http_response_code(400);
    echo json_encode(['error' => $upload_result['error']]);
    exit;
}

// Return absolute URL for TinyMCE
$location = upload_url($upload_result['path']);

echo json_encode([
    'location' => $location
]);
exit;
