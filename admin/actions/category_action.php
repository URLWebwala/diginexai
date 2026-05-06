<?php
require_once __DIR__ . '/../config/database.php';
require_once __DIR__ . '/../config/auth.php';
requireLogin();

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $action = $_POST['action'] ?? '';
    $id = intval($_POST['id'] ?? 0);
    $name = trim($_POST['name'] ?? '');
    $slug = trim($_POST['slug'] ?? '');
    
    // Validate
    if (empty($name)) {
        header('Location: ../categories.php?error=Please enter category name');
        exit();
    }
    
    // Generate slug if empty
    if (empty($slug)) {
        $slug = strtolower(trim(preg_replace('/[^A-Za-z0-9-]+/', '-', $name)));
    }
    
    $conn = getDBConnection();
    
    // Check if name exists
    $check_stmt = $conn->prepare("SELECT id FROM blog_categories WHERE name = ? AND id != ?");
    $check_stmt->bind_param("si", $name, $id);
    $check_stmt->execute();
    if ($check_stmt->get_result()->num_rows > 0) {
        header('Location: ../categories.php?error=Category name already exists');
        exit();
    }
    $check_stmt->close();
    
    // Check if slug exists
    $check_stmt = $conn->prepare("SELECT id FROM blog_categories WHERE slug = ? AND id != ?");
    $check_stmt->bind_param("si", $slug, $id);
    $check_stmt->execute();
    if ($check_stmt->get_result()->num_rows > 0) {
        $slug = $slug . '-' . time();
    }
    $check_stmt->close();
    
    if ($action == 'edit' && $id > 0) {
        // Update existing
        $stmt = $conn->prepare("UPDATE blog_categories SET name = ?, slug = ? WHERE id = ?");
        $stmt->bind_param("ssi", $name, $slug, $id);
    } else {
        // Insert new
        $stmt = $conn->prepare("INSERT INTO blog_categories (name, slug) VALUES (?, ?)");
        $stmt->bind_param("ss", $name, $slug);
    }
    
    if ($stmt->execute()) {
        header('Location: ../categories.php?success=1');
    } else {
        header('Location: ../categories.php?error=Database error occurred');
    }
    
    $stmt->close();
    closeDBConnection($conn);
} else {
    header('Location: ../categories.php');
}
exit();
?>

