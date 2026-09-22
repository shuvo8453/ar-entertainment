<?php
if (is_dir(__DIR__ . '/../uploads/services')) {
    echo "uploads/services: " . implode(', ', scandir(__DIR__ . '/../uploads/services')) . "\n";
}
if (is_dir(__DIR__ . '/../images/featured')) {
    echo "images/featured: " . implode(', ', scandir(__DIR__ . '/../images/featured')) . "\n";
}
