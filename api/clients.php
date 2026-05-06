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
    
    // Get single client by ID
    if ($id) {
        $id = intval($id);
        $stmt = $conn->prepare("SELECT * FROM clients WHERE id = ? AND status = 'active'");
        $stmt->bind_param("i", $id);
        $stmt->execute();
        $result = $stmt->get_result();
        
        if ($result->num_rows > 0) {
            $client = $result->fetch_assoc();
            echo json_encode([
                'success' => true,
                'data' => [
                    'id' => (int)$client['id'],
                    'client_name' => $client['client_name'],
                    'website_url' => $client['website_url'],
                    'email' => $client['email'],
                    'phone' => $client['phone'],
                    'logo' => $client['logo'] ? rtrim(UPLOADS_URL, '/') . '/clients/' . $client['logo'] : null,
                    'status' => $client['status'],
                    'created_at' => $client['created_at'],
                    'updated_at' => $client['updated_at']
                ]
            ], JSON_PRETTY_PRINT);
        } else {
            http_response_code(404);
            echo json_encode([
                'success' => false,
                'message' => 'Client not found'
            ], JSON_PRETTY_PRINT);
        }
        $stmt->close();
    }
    // Get all clients
    else {
        $query = "SELECT * FROM clients WHERE status = 'active' ORDER BY created_at DESC";
        $result = $conn->query($query);
        $clients = [];
        
        if ($result && $result->num_rows > 0) {
            while ($row = $result->fetch_assoc()) {
                $clients[] = [
                    'id' => (int)$row['id'],
                    'client_name' => $row['client_name'],
                    'website_url' => $row['website_url'],
                    'email' => $row['email'],
                    'phone' => $row['phone'],
                    'logo' => $row['logo'] ? rtrim(UPLOADS_URL, '/') . '/clients/' . $row['logo'] : null,
                    'status' => $row['status'],
                    'created_at' => $row['created_at'],
                    'updated_at' => $row['updated_at']
                ];
            }
        }
        
        echo json_encode([
            'success' => true,
            'data' => $clients,
            'count' => count($clients)
        ], JSON_PRETTY_PRINT);
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

