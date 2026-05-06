<?php
session_start();

// Load config if not already loaded
if (!defined('BASE_URL')) {
    // Check if we're in config directory or parent directory
    $config_path = file_exists(__DIR__ . '/config.php') ? __DIR__ . '/config.php' : __DIR__ . '/../config/config.php';
    if (file_exists($config_path)) {
        require_once $config_path;
    }
}

// Check if user is logged in
function isLoggedIn() {
    return isset($_SESSION['admin_id']) && isset($_SESSION['admin_username']);
}

// Require login
function requireLogin() {
    if (!isLoggedIn()) {
        // Use BASE_URL to prevent file paths
        if (defined('BASE_URL')) {
            header('Location: ' . BASE_URL . 'login.php');
        } else {
            header('Location: login.php');
        }
        exit();
    }
}

// Get current admin ID
function getAdminId() {
    return isset($_SESSION['admin_id']) ? $_SESSION['admin_id'] : null;
}

// Get current admin username
function getAdminUsername() {
    return isset($_SESSION['admin_username']) ? $_SESSION['admin_username'] : null;
}

// Logout function
function logout() {
    session_unset();
    session_destroy();
    // Use BASE_URL to prevent file paths
    if (defined('BASE_URL')) {
        header('Location: ' . BASE_URL . 'login.php');
    } else {
        header('Location: login.php');
    }
    exit();
}
?>

