<?php

/**
 * AR Entertainment - Core Configuration
 * 
 * Central site configuration, environment management, database credentials,
 * security keys, and global paths.
 */

// Prevent direct script access if needed
defined('APP_INIT') or define('APP_INIT', true);

// Set default timezone (Asia/Dhaka)
date_default_timezone_set('Asia/Dhaka');

// -----------------------------------------------------------------------------
// 1. Environment Detection & Error Reporting
// -----------------------------------------------------------------------------
$is_cli = (php_sapi_name() === 'cli' || defined('STDIN'));
$http_host = $_SERVER['HTTP_HOST'] ?? 'localhost';

if ($http_host === 'localhost' || str_ends_with($http_host, '.test') || str_ends_with($http_host, '.local') || $is_cli) {
    define('ENVIRONMENT', 'development');
} else {
    define('ENVIRONMENT', 'production');
}

if (ENVIRONMENT === 'development') {
    ini_set('display_errors', '1');
    ini_set('display_startup_errors', '1');
    error_reporting(E_ALL);
} else {
    ini_set('display_errors', '0');
    ini_set('display_startup_errors', '0');
    error_reporting(0);
}

// -----------------------------------------------------------------------------
// 2. Base URL & Path Constants
// -----------------------------------------------------------------------------
define('ROOT_PATH', dirname(__DIR__));
define('CONFIG_PATH', ROOT_PATH . DIRECTORY_SEPARATOR . 'config');
define('INCLUDES_PATH', ROOT_PATH . DIRECTORY_SEPARATOR . 'includes');
define('UPLOADS_PATH', ROOT_PATH . DIRECTORY_SEPARATOR . 'uploads');
define('DATABASE_PATH', ROOT_PATH . DIRECTORY_SEPARATOR . 'database');
define('ADMIN_PATH', ROOT_PATH . DIRECTORY_SEPARATOR . 'admin');

// Calculate dynamic base URL
if ($is_cli) {
    define('BASE_URL', 'http://localhost/ar-entertainment');
} else {
    $protocol = (!empty($_SERVER['HTTPS']) && $_SERVER['HTTPS'] !== 'off' || ($_SERVER['SERVER_PORT'] ?? 80) == 443) ? 'https://' : 'http://';
    $script_dir = str_replace('\\', '/', dirname($_SERVER['SCRIPT_NAME'] ?? ''));

    // If in root or vhost
    if ($http_host === 'ar-entertainment.test') {
        define('BASE_URL', $protocol . $http_host);
    } elseif (strpos($script_dir, '/ar-entertainment') === 0) {
        define('BASE_URL', $protocol . $http_host . '/ar-entertainment');
    } elseif ($script_dir === '/' || $script_dir === '') {
        define('BASE_URL', $protocol . $http_host);
    } else {
        // Fallback calculation
        $base = rtrim($protocol . $http_host . $script_dir, '/');
        // If script is inside admin or subfolders, strip them
        $base = preg_replace('/\/admin(\/.*)?$/', '', $base);
        $base = preg_replace('/\/database(\/.*)?$/', '', $base);
        define('BASE_URL', $base);
    }
}

define('ADMIN_URL', BASE_URL . '/admin');
define('UPLOADS_URL', BASE_URL . '/uploads');
define('ASSETS_URL', BASE_URL . '/inc');

// -----------------------------------------------------------------------------
// 3. Database Credentials
// -----------------------------------------------------------------------------
define('DB_HOST', 'localhost');
define('DB_PORT', '3306');
define('DB_NAME', 'ar-entertainment');
define('DB_USER', 'root');
define('DB_PASS', '');
define('DB_CHARSET', 'utf8mb4');

// -----------------------------------------------------------------------------
// 4. Brand & Identity Constants (AR Entertainment)
// -----------------------------------------------------------------------------
define('SITE_NAME', 'AR Entertainment');
define('SITE_LEGAL_NAME', 'AR Entertainment Ltd.');
define('SITE_TAGLINE', 'Video Production & Film Fixer Services in Bangladesh');
define('SITE_DOMAIN', 'arentertainment.bd');
define('PRODUCTION_URL', 'https://www.arentertainment.bd');
define('ADMIN_EMAIL', 'admin@arentertainment.bd');
define('CONTACT_EMAIL', 'info@arentertainment.bd');
define('CONTACT_PHONE', '+8801988777444');
define('CONTACT_ADDRESS', 'Apt 4-S, House 62, Road 14/1, Block G, Niketan, Gulshan 1, Dhaka 1212, Bangladesh');

// -----------------------------------------------------------------------------
// 5. Session Configuration
// -----------------------------------------------------------------------------
if (session_status() === PHP_SESSION_NONE && !$is_cli) {
    ini_set('session.cookie_httponly', '1');
    ini_set('session.use_only_cookies', '1');
    if (!empty($_SERVER['HTTPS']) && $_SERVER['HTTPS'] !== 'off') {
        ini_set('session.cookie_secure', '1');
    }
    session_start();
}
