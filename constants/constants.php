<?php
// Company Information
define('COMPANY_TITLT', 'Top Digital Marketing & Branding Agency UK | DiginexAI');
define('COMPNY_NAME', 'DiginexAI');

// API Configuration - Dynamically detect environment
$protocol = (!empty($_SERVER['HTTPS']) && $_SERVER['HTTPS'] !== 'off') ? 'https' : 'http';
$host = $_SERVER['HTTP_HOST'] ?? 'localhost';

if ($host === 'localhost' || $host === '127.0.0.1' || strpos($host, '192.168.') !== false) {
    define('API_BASE_URL', $protocol . '://' . $host . '/diginexai/api');
} else {
    // Production URL for Hostinger
    define('API_BASE_URL', 'https://api.diginexai.com');
}
?>
