<?php
header('Content-Type: application/json');
header('Access-Control-Allow-Origin: *');
header('Access-Control-Allow-Methods: GET, OPTIONS');
header('Access-Control-Allow-Headers: Content-Type');

// Handle preflight request
if ($_SERVER['REQUEST_METHOD'] === 'OPTIONS') {
    http_response_code(200);
    exit();
}

require_once __DIR__ . '/config/config.php';
require_once __DIR__ . '/config/database.php';

$conn = getDBConnection();
$method = $_SERVER['REQUEST_METHOD'];

if ($method === 'GET') {
    $id = $_GET['id'] ?? null;
    
    // Get single testimonial by ID
    if ($id) {
        $id = intval($id);
        $stmt = $conn->prepare("SELECT * FROM testimonials WHERE id = ? AND status = 'active'");
        $stmt->bind_param("i", $id);
        $stmt->execute();
        $result = $stmt->get_result();
        
        if ($result->num_rows > 0) {
            $testimonial = $result->fetch_assoc();
            echo json_encode([
                'success' => true,
                'data' => [
                    'id' => (int)$testimonial['id'],
                    'client_name' => $testimonial['client_name'],
                    'description' => $testimonial['description'],
                    'rating' => (int)$testimonial['rating'],
                    'image' => $testimonial['image'] ? UPLOADS_URL . 'testimonials/' . $testimonial['image'] : null,
                    'status' => $testimonial['status'],
                    'created_at' => $testimonial['created_at'],
                    'updated_at' => $testimonial['updated_at']
                ]
            ], JSON_PRETTY_PRINT);
        } else {
            http_response_code(404);
            echo json_encode([
                'success' => false,
                'message' => 'Testimonial not found'
            ], JSON_PRETTY_PRINT);
        }
        $stmt->close();
    }
    // Get all testimonials
    else {
        $limit = isset($_GET['limit']) ? intval($_GET['limit']) : null;
        
        $query = "SELECT * FROM testimonials WHERE status = 'active' ORDER BY created_at DESC";
        
        if ($limit) {
            $query .= " LIMIT ?";
            $stmt = $conn->prepare($query);
            $stmt->bind_param("i", $limit);
        } else {
            $stmt = $conn->prepare($query);
        }
        
        $stmt->execute();
        $result = $stmt->get_result();
        $testimonials = [];
        
        if ($result && $result->num_rows > 0) {
            while ($row = $result->fetch_assoc()) {
                $testimonials[] = [
                    'id' => (int)$row['id'],
                    'client_name' => $row['client_name'],
                    'description' => $row['description'],
                    'rating' => (int)$row['rating'],
                    'image' => $row['image'] ? UPLOADS_URL . 'testimonials/' . $row['image'] : null,
                    'status' => $row['status'],
                    'created_at' => $row['created_at'],
                    'updated_at' => $row['updated_at']
                ];
            }
        }
        
        echo json_encode([
            'success' => true,
            'data' => $testimonials,
            'count' => count($testimonials)
        ], JSON_PRETTY_PRINT);
        
        $stmt->close();
    }
} else {
    http_response_code(405);
    echo json_encode([
        'success' => false,
        'message' => 'Method not allowed'
    ], JSON_PRETTY_PRINT);
}

closeDBConnection($conn);
?>

