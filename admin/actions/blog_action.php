<?php
require_once __DIR__ . '/../config/database.php';
require_once __DIR__ . '/../config/auth.php';
requireLogin();

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $action = $_POST['action'] ?? '';
    $id = intval($_POST['id'] ?? 0);
    $title = trim($_POST['title'] ?? '');
    $category_id = intval($_POST['category_id'] ?? 0);
    $content = $_POST['content'] ?? '';
    $author_name = trim($_POST['author_name'] ?? '');
    $slug = trim($_POST['slug'] ?? '');
    $canonical_url = trim($_POST['canonical_url'] ?? '');
    $seo_url = trim($_POST['seo_url'] ?? '');
    $robots = trim($_POST['robots'] ?? '');
    $status = $_POST['status'] ?? 'active';
    
    // Validate
    if (empty($title) || empty($content) || empty($author_name) || $category_id == 0) {
        header('Location: ../blogs.php?error=' . urlencode('Please fill all required fields'));
        header('HTTP/1.1 303 See Other');
        exit();
    }
    
    // Generate slug if empty
    if (empty($slug)) {
        $slug = strtolower(trim(preg_replace('/[^A-Za-z0-9-]+/', '-', $title)));
        $slug = preg_replace('/-+/', '-', $slug);
        $slug = trim($slug, '-');
    }
    
    // Ensure slug is not empty even after sanitization
    if (empty($slug)) {
        $slug = 'blog-' . time();
    }
    
    if (empty($seo_url)) {
        $seo_url = $slug;
    }
    
    $conn = getDBConnection();
    
    // Check if slug exists
    $check_stmt = $conn->prepare("SELECT id FROM blogs WHERE slug = ? AND id != ?");
    $check_stmt->bind_param("si", $slug, $id);
    $check_stmt->execute();
    if ($check_stmt->get_result()->num_rows > 0) {
        $slug = $slug . '-' . time();
        $seo_url = $slug;
    }
    $check_stmt->close();
    
    // Handle file upload
    $blog_image = null;
    if (isset($_FILES['blog_image']) && $_FILES['blog_image']['error'] == 0) {
        $upload_dir = '../../uploads/blogs/';
        if (!is_dir($upload_dir)) {
            mkdir($upload_dir, 0777, true);
        }
        
        $file_ext = strtolower(pathinfo($_FILES['blog_image']['name'], PATHINFO_EXTENSION));
        $allowed_ext = ['jpg', 'jpeg', 'png', 'gif', 'webp'];
        
        if (in_array($file_ext, $allowed_ext)) {
            $blog_image = uniqid() . '_' . time() . '.' . $file_ext;
            $upload_path = $upload_dir . $blog_image;
            
            if (move_uploaded_file($_FILES['blog_image']['tmp_name'], $upload_path)) {
                // If editing, delete old image
                if ($action == 'edit' && $id > 0) {
                    $stmt = $conn->prepare("SELECT blog_image FROM blogs WHERE id = ?");
                    $stmt->bind_param("i", $id);
                    $stmt->execute();
                    $result = $stmt->get_result();
                    if ($old = $result->fetch_assoc()) {
                        if ($old['blog_image'] && file_exists($upload_dir . $old['blog_image'])) {
                            unlink($upload_dir . $old['blog_image']);
                        }
                    }
                    $stmt->close();
                }
            } else {
                $blog_image = null;
            }
        }
    }
    
    if ($action == 'edit' && $id > 0) {
        // Update existing
        if ($blog_image) {
            $stmt = $conn->prepare("UPDATE blogs SET title = ?, category_id = ?, content = ?, author_name = ?, slug = ?, blog_image = ?, canonical_url = ?, seo_url = ?, robots = ?, status = ? WHERE id = ?");
            $stmt->bind_param("sississsssi", $title, $category_id, $content, $author_name, $slug, $blog_image, $canonical_url, $seo_url, $robots, $status, $id);
        } else {
            $stmt = $conn->prepare("UPDATE blogs SET title = ?, category_id = ?, content = ?, author_name = ?, slug = ?, canonical_url = ?, seo_url = ?, robots = ?, status = ? WHERE id = ?");
            $stmt->bind_param("sississssi", $title, $category_id, $content, $author_name, $slug, $canonical_url, $seo_url, $robots, $status, $id);
        }
    } else {
        // Insert new
        if (!$blog_image) {
            $blog_image = null;
        }
        $stmt = $conn->prepare("INSERT INTO blogs (title, category_id, content, author_name, slug, blog_image, canonical_url, seo_url, robots, status) VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?)");
        $stmt->bind_param("sississsss", $title, $category_id, $content, $author_name, $slug, $blog_image, $canonical_url, $seo_url, $robots, $status);
    }
    
    if ($stmt->execute()) {
        header('Location: ../blogs.php?success=1');
        header('HTTP/1.1 303 See Other');
    } else {
        header('Location: ../blogs.php?error=' . urlencode('Database error occurred'));
        header('HTTP/1.1 303 See Other');
    }
    
    $stmt->close();
    closeDBConnection($conn);
} else {
    header('Location: ../blogs.php');
    header('HTTP/1.1 303 See Other');
}
exit();
?>

