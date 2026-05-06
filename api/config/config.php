<?php
/**
 * API Configuration
 * Base URL and common constants
 */

// Detect protocol
$protocol = (!empty($_SERVER['HTTPS']) && $_SERVER['HTTPS'] !== 'off') ? 'https' : 'http';

// Detect host
$host = $_SERVER['HTTP_HOST'] ?? 'localhost';

// PRODUCTION URLs - HARDCODED to prevent /public_html/ in URLs
// Production server: api.diginexai.com
// NEVER include /public_html/ in production URLs - it's an internal directory

// Check if we're on production server
$isProduction = (
    $host === 'api.diginexai.com' || 
    $host === 'diginexai.com' ||
    (strpos($host, 'diginexai.com') !== false && $host !== 'localhost')
);

if ($isProduction) {
    // PRODUCTION - HARDCODED clean URLs (NO /public_html/)
    define('API_BASE_URL', 'https://api.diginexai.com/');
    define('SITE_URL', 'https://diginexai.com/');
    // Uploads URL - main domain, ABSOLUTELY NO /public_html/ in URL
    // Remove /public_html/ if it somehow gets in there (failsafe)
    $uploadsUrl = 'https://diginexai.com/uploads/';
    $uploadsUrl = str_replace('/public_html/', '/', $uploadsUrl);
    define('UPLOADS_URL', $uploadsUrl);
} else {
    // DEVELOPMENT - Only use /public_html/ for localhost/XAMPP
    define('API_BASE_URL', $protocol . '://' . $host . '/public_html/api/');
    define('SITE_URL', $protocol . '://' . $host . '/public_html/');
    // Uploads URL - development only
    define('UPLOADS_URL', SITE_URL . 'uploads/');
}

// Prevent direct access
if (!defined('API_BASE_URL')) {
    die('Direct access not allowed');
}
?>


