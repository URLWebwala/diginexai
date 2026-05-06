<?php
/**
 * Admin Panel Configuration
 * Base URL and common constants
 */

// Detect protocol
$protocol = (!empty($_SERVER['HTTPS']) && $_SERVER['HTTPS'] !== 'off') ? 'https' : 'http';

// Detect host
$host = $_SERVER['HTTP_HOST'] ?? 'localhost';

// Production Check
if (strpos($host, 'admin.diginexai.com') !== false) {
    // Production subdomain
    define('BASE_URL', 'https://admin.diginexai.com/');
    define('SITE_URL', 'https://www.diginexai.com/');
} else {
    // Development
    define('BASE_URL', $protocol . '://' . $host . '/diginexai/admin/');
    define('SITE_URL', $protocol . '://' . $host . '/diginexai/');
}

// Admin assets URL
define('ADMIN_ASSETS_URL', BASE_URL . 'assets/');

// Uploads URL - always use main domain for uploads
define('UPLOADS_URL', SITE_URL . 'uploads/');

// Prevent direct access
if (!defined('BASE_URL')) {
    die('Direct access not allowed');
}
?>
