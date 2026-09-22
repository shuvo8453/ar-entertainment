<?php
$files = [
    'team-of-multimedia-content-creators-talking.jpg',
    'corporate-video-production-office.webp',
    'storyboard-development.webp',
    'scriptwriting.webp',
    'script-writing-process.webp',
    'corporate-av-vs-video-vs-documentary.webp',
    'corporate-av-vs-corporate-video.webp',
    'drone-video-services-in-bangladesh.webp',
    'drone-video-og.jpg',
    'corporate-av-vs-video-documentary-og.webp',
    'movie-camera-behind-the-video-camera.jpg',
    'the-process-of-recording-video-content.jpg',
    'male-videographer-using-computer-for-editing-video.jpg'
];
foreach ($files as $f) {
    $p = __DIR__ . '/../images/' . $f;
    echo $f . ': ' . (file_exists($p) ? filesize($p) . ' bytes' : 'NOT FOUND') . PHP_EOL;
}
