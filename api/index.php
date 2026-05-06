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

echo json_encode([
    'success' => true,
    'message' => 'Welcome to DiginexAI API Service.',
    'status' => 'Active',
    'version' => '1.0.0'
], JSON_PRETTY_PRINT);
?>

