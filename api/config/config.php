<?php
/**
 * API Configuration
 * Base URL and common constants
 */

// Detect protocol
$protocol = (!empty($_SERVER['HTTPS']) && $_SERVER['HTTPS'] !== 'off') ? 'https' : 'http';

// Detect host
$host = $_SERVER['HTTP_HOST'] ?? 'localhost';

// Check if we're on production server
$isProduction = (
    $host === 'api.diginexai.com' || 
    $host === 'www.diginexai.com' ||
    $host === 'diginexai.com'
);

if ($isProduction) {
    // PRODUCTION - Final Domains
    define('API_BASE_URL', 'https://api.diginexai.com/');
    define('SITE_URL', 'https://www.diginexai.com/');
    define('UPLOADS_URL', 'https://www.diginexai.com/uploads/');
} else {
    // DEVELOPMENT
    define('API_BASE_URL', $protocol . '://' . $host . '/diginexai/api/');
    define('SITE_URL', $protocol . '://' . $host . '/diginexai/');
    define('UPLOADS_URL', SITE_URL . 'uploads/');
}

// Prevent direct access
if (!defined('API_BASE_URL')) {
    die('Direct access not allowed');
}
?>
