<?php
// Database Configuration
$host = $_SERVER['HTTP_HOST'] ?? 'localhost';

if ($host === 'localhost' || $host === '127.0.0.1' || strpos($host, '192.168.') !== false) {
    // Local Development
    define('DB_HOST', 'localhost');
    define('DB_USER', 'root');
    define('DB_PASS', '');
    define('DB_NAME', 'diginexai_local');
} else {
    // Production (Hostinger) - UPDATED with provided credentials
    define('DB_HOST', 'localhost');
    define('DB_USER', 'u841045341_diginexai'); 
    define('DB_PASS', 'Guddu@122812');
    define('DB_NAME', 'u841045341_diginexai');
}

// Create connection
function getDBConnection() {
    try {
        $conn = new mysqli(DB_HOST, DB_USER, DB_PASS, DB_NAME);
        
        if ($conn->connect_error) {
            throw new Exception("Connection failed: " . $conn->connect_error);
        }
        
        $conn->set_charset("utf8mb4");
        return $conn;
    } catch (Exception $e) {
        die("Database connection error: " . $e->getMessage());
    }
}

// Close connection
function closeDBConnection($conn) {
    if ($conn) {
        $conn->close();
    }
}
?>
