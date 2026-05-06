<?php
header('Content-Type: application/json');
header('Access-Control-Allow-Origin: *');
header('Access-Control-Allow-Methods: GET, POST, OPTIONS');
header('Access-Control-Allow-Headers: Content-Type');

// Handle preflight request
if ($_SERVER['REQUEST_METHOD'] === 'OPTIONS') {
    http_response_code(200);
    exit();
}

require_once __DIR__ . '/config/database.php';

$conn = getDBConnection();
$method = $_SERVER['REQUEST_METHOD'];

// GET - Fetch all contact messages
if ($method === 'GET') {
    $query = "SELECT id, first_name, last_name, email, phone, service, message, created_at 
              FROM contact_us 
              ORDER BY created_at DESC";
    
    $result = $conn->query($query);
    $contacts = [];
    
    if ($result && $result->num_rows > 0) {
        while ($row = $result->fetch_assoc()) {
            $contacts[] = [
                'id' => (int)$row['id'],
                'first_name' => $row['first_name'],
                'last_name' => $row['last_name'],
                'email' => $row['email'],
                'phone' => $row['phone'],
                'service' => $row['service'],
                'message' => $row['message'],
                'created_at' => $row['created_at']
            ];
        }
    }
    
    echo json_encode([
        'success' => true,
        'data' => $contacts,
        'count' => count($contacts)
    ], JSON_PRETTY_PRINT);
}

// POST - Submit new contact message
elseif ($method === 'POST') {
    // Get raw input
    $raw_input = file_get_contents('php://input');
    
    // Try to decode JSON first
    $data = json_decode($raw_input, true);
    
    // If JSON decode failed, try $_POST
    if ($data === null && json_last_error() !== JSON_ERROR_NONE) {
        $data = $_POST;
    }
    
    // If still no data, try to parse as form data
    if (empty($data) && !empty($raw_input)) {
        parse_str($raw_input, $data);
    }
    
    // Extract fields
    $first_name = trim($data['first_name'] ?? '');
    $last_name = trim($data['last_name'] ?? '');
    $email = trim($data['email'] ?? '');
    $phone = trim($data['phone'] ?? '');
    $service = trim($data['service'] ?? '');
    $message = trim($data['message'] ?? '');
    
    // Validation
    if (empty($first_name) || empty($email) || empty($message)) {
        http_response_code(400);
        echo json_encode([
            'success' => false,
            'message' => 'First name, email, and message are required',
            'received' => [
                'first_name' => $first_name,
                'email' => $email,
                'message' => !empty($message) ? 'present' : 'missing'
            ]
        ], JSON_PRETTY_PRINT);
        closeDBConnection($conn);
        exit();
    }
    
    // Insert into database
    $stmt = $conn->prepare("INSERT INTO contact_us (first_name, last_name, email, phone, service, message) VALUES (?, ?, ?, ?, ?, ?)");
    
    if (!$stmt) {
        http_response_code(500);
        echo json_encode([
            'success' => false,
            'message' => 'Database error: ' . $conn->error
        ], JSON_PRETTY_PRINT);
        closeDBConnection($conn);
        exit();
    }
    
    $stmt->bind_param("ssssss", $first_name, $last_name, $email, $phone, $service, $message);
    
    if ($stmt->execute()) {
        http_response_code(200);
        echo json_encode([
            'success' => true,
            'message' => 'Contact message submitted successfully',
            'data' => [
                'id' => (int)$conn->insert_id,
                'first_name' => $first_name,
                'last_name' => $last_name,
                'email' => $email,
                'phone' => $phone,
                'service' => $service,
                'message' => $message
            ]
        ], JSON_PRETTY_PRINT);
    } else {
        http_response_code(500);
        echo json_encode([
            'success' => false,
            'message' => 'Failed to submit contact message: ' . $stmt->error
        ], JSON_PRETTY_PRINT);
    }
    $stmt->close();
}

else {
    http_response_code(405);
    header('Content-Type: application/json');
    echo json_encode([
        'success' => false,
        'message' => 'Method not allowed',
        'allowed_methods' => ['GET', 'POST']
    ], JSON_PRETTY_PRINT);
    closeDBConnection($conn);
    exit();
}

closeDBConnection($conn);
?>

