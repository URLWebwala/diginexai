<?php
// 404 Error Handler for API - Always return JSON
header('Content-Type: application/json');
header('Access-Control-Allow-Origin: *');
http_response_code(404);

echo json_encode([
    'success' => false,
    'message' => 'API endpoint not found',
    'error' => '404 Not Found',
    'requested_url' => $_SERVER['REQUEST_URI'] ?? 'unknown',
    'available_endpoints' => [
        'GET /api/contact.php - Get all contact messages',
        'POST /api/contact.php - Submit contact message',
        'GET /api/blogs.php - Get all blogs',
        'GET /api/blogs.php?id={id} - Get single blog',
        'GET /api/clients.php - Get all clients',
        'GET /api/clients.php?id={id} - Get single client',
        'GET /api/testimonials.php - Get all testimonials',
        'GET /api/testimonials.php?id={id} - Get single testimonial'
    ]
], JSON_PRETTY_PRINT);
exit();
?>

