<?php
/**
 * API Root - DiginexAI API Endpoints
 * Access: https://api.diginexai.com/
 */

header('Content-Type: application/json');
header('Access-Control-Allow-Origin: *');
header('Access-Control-Allow-Methods: GET, POST, OPTIONS');
header('Access-Control-Allow-Headers: Content-Type, Authorization');

// Handle preflight request
if ($_SERVER['REQUEST_METHOD'] === 'OPTIONS') {
    http_response_code(200);
    exit();
}

$protocol = (!empty($_SERVER['HTTPS']) && $_SERVER['HTTPS'] !== 'off') ? 'https' : 'http';
$host = $_SERVER['HTTP_HOST'] ?? 'api.diginexai.com';
$baseUrl = $protocol . '://' . $host;

$endpoints = [
    'blogs' => [
        'url' => $baseUrl . '/blogs.php',
        'methods' => ['GET'],
        'description' => 'Get all blog posts or single blog by ID or slug',
        'examples' => [
            'All blogs' => $baseUrl . '/blogs.php',
            'Single blog by ID' => $baseUrl . '/blogs.php?id=1',
            'Single blog by slug' => $baseUrl . '/blogs.php?slug=blog-slug'
        ]
    ],
    'seo' => [
        'url' => $baseUrl . '/seo.php',
        'methods' => ['GET'],
        'description' => 'Get SEO data by page name or SEO URL',
        'examples' => [
            'All SEO data' => $baseUrl . '/seo.php',
            'By page name' => $baseUrl . '/seo.php?page_name=Home',
            'By SEO URL' => $baseUrl . '/seo.php?seo_url=home'
        ]
    ],
    'testimonials' => [
        'url' => $baseUrl . '/testimonials.php',
        'methods' => ['GET'],
        'description' => 'Get all active testimonials or single testimonial by ID',
        'examples' => [
            'All testimonials' => $baseUrl . '/testimonials.php',
            'Single testimonial' => $baseUrl . '/testimonials.php?id=1'
        ]
    ],
    'clients' => [
        'url' => $baseUrl . '/clients.php',
        'methods' => ['GET'],
        'description' => 'Get all active clients or single client by ID',
        'examples' => [
            'All clients' => $baseUrl . '/clients.php',
            'Single client' => $baseUrl . '/clients.php?id=1'
        ]
    ],
    'contact' => [
        'url' => $baseUrl . '/contact.php',
        'methods' => ['GET', 'POST'],
        'description' => 'Get all contact messages (GET) or submit new contact form (POST)',
        'examples' => [
            'All messages' => $baseUrl . '/contact.php',
            'Submit form' => $baseUrl . '/contact.php (POST)'
        ]
    ]
];

echo json_encode([
    'success' => true,
    'message' => 'DiginexAI API - Available Endpoints',
    'version' => '1.0.0',
    'base_url' => $baseUrl,
    'endpoints' => $endpoints,
    'documentation' => [
        'All endpoints return JSON format',
        'CORS is enabled for all origins',
        'All endpoints support OPTIONS preflight requests'
    ]
], JSON_PRETTY_PRINT);
?>

