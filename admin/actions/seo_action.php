<?php
require_once __DIR__ . '/../config/database.php';
require_once __DIR__ . '/../config/auth.php';
requireLogin();

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $action = $_POST['action'] ?? '';
    $id = intval($_POST['id'] ?? 0);
    $page_name = trim($_POST['page_name'] ?? '');
    $title = trim($_POST['title'] ?? '');
    $page_title = trim($_POST['page_title'] ?? '');
    $description = trim($_POST['description'] ?? '');
    $author_name = trim($_POST['author_name'] ?? '');
    $keywords = trim($_POST['keywords'] ?? '');
    $seo_url = trim($_POST['seo_url'] ?? '');
    $canonical_url = trim($_POST['canonical_url'] ?? '');
    $robots = trim($_POST['robots'] ?? '');
    
    // Validate
    if (empty($page_name) || empty($title) || empty($seo_url)) {
        header('Location: ../seo.php?error=Please fill all required fields');
        exit();
    }
    
    $conn = getDBConnection();
    
    // Check if page_name exists (for new entries)
    if ($action == 'add') {
        $check_stmt = $conn->prepare("SELECT id FROM seo_data WHERE page_name = ?");
        $check_stmt->bind_param("s", $page_name);
        $check_stmt->execute();
        if ($check_stmt->get_result()->num_rows > 0) {
            header('Location: ../seo.php?error=Page name already exists');
            exit();
        }
        $check_stmt->close();
    }
    
    // Check if seo_url exists
    $check_stmt = $conn->prepare("SELECT id FROM seo_data WHERE seo_url = ? AND id != ?");
    $check_stmt->bind_param("si", $seo_url, $id);
    $check_stmt->execute();
    if ($check_stmt->get_result()->num_rows > 0) {
        header('Location: ../seo.php?error=SEO URL already exists');
        exit();
    }
    $check_stmt->close();
    
    if ($action == 'edit' && $id > 0) {
        // Update existing
        $stmt = $conn->prepare("UPDATE seo_data SET page_name = ?, title = ?, page_title = ?, description = ?, author_name = ?, keywords = ?, seo_url = ?, canonical_url = ?, robots = ? WHERE id = ?");
        $stmt->bind_param("sssssssssi", $page_name, $title, $page_title, $description, $author_name, $keywords, $seo_url, $canonical_url, $robots, $id);
    } else {
        // Insert new
        $stmt = $conn->prepare("INSERT INTO seo_data (page_name, title, page_title, description, author_name, keywords, seo_url, canonical_url, robots) VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?)");
        $stmt->bind_param("sssssssss", $page_name, $title, $page_title, $description, $author_name, $keywords, $seo_url, $canonical_url, $robots);
    }
    
    if ($stmt->execute()) {
        header('Location: ../seo.php?success=1');
    } else {
        header('Location: ../seo.php?error=Database error occurred');
    }
    
    $stmt->close();
    closeDBConnection($conn);
} else {
    header('Location: ../seo.php');
}
exit();
?>

