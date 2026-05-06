<?php
/**
 * Frontend Database Configuration
 * Update these values for production
 */
define('DB_HOST', 'localhost'); // Your production database host
define('DB_USER', 'root');
define('DB_PASS', '');
define('DB_NAME', 'diginexai_local');

/**
 * Get Database Connection
 * @return mysqli Database connection object
 */
function getDBConnection() {
    try {
        $conn = new mysqli(DB_HOST, DB_USER, DB_PASS, DB_NAME);
        
        if ($conn->connect_error) {
            throw new Exception("Connection failed: " . $conn->connect_error);
        }
        
        $conn->set_charset("utf8mb4");
        return $conn;
    } catch (Exception $e) {
        // Log error in production (don't expose details)
        error_log("Database connection error: " . $e->getMessage());
        die("Database connection error. Please try again later.");
    }
}

/**
 * Close Database Connection
 * @param mysqli $conn Database connection object
 */
function closeDBConnection($conn) {
    if ($conn) {
        $conn->close();
    }
}
?>

