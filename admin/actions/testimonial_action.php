<?php
require_once __DIR__ . '/../config/database.php';
require_once __DIR__ . '/../config/auth.php';
requireLogin();

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $action = $_POST['action'] ?? '';
    $id = intval($_POST['id'] ?? 0);
    $client_name = trim($_POST['client_name'] ?? '');
    $description = trim($_POST['description'] ?? '');
    $rating = floatval($_POST['rating'] ?? 5.0);
    $status = $_POST['status'] ?? 'active';
    
    // Validate
    if (empty($client_name) || empty($description)) {
        header('Location: ../testimonials.php?error=Please fill all required fields');
        exit();
    }
    
    // Validate rating
    if ($rating < 1 || $rating > 5) {
        $rating = 5.0;
    }
    
    $conn = getDBConnection();
    
    // Handle file upload
    $image = null;
    if (isset($_FILES['image']) && $_FILES['image']['error'] == 0) {
        $upload_dir = '../../uploads/testimonials/';
        if (!is_dir($upload_dir)) {
            mkdir($upload_dir, 0777, true);
        }
        
        $file_ext = strtolower(pathinfo($_FILES['image']['name'], PATHINFO_EXTENSION));
        $allowed_ext = ['jpg', 'jpeg', 'png', 'gif', 'webp'];
        
        if (in_array($file_ext, $allowed_ext)) {
            $image = uniqid() . '_' . time() . '.' . $file_ext;
            $upload_path = $upload_dir . $image;
            
            if (move_uploaded_file($_FILES['image']['tmp_name'], $upload_path)) {
                // If editing, delete old image
                if ($action == 'edit' && $id > 0) {
                    $stmt = $conn->prepare("SELECT image FROM testimonials WHERE id = ?");
                    $stmt->bind_param("i", $id);
                    $stmt->execute();
                    $result = $stmt->get_result();
                    if ($old = $result->fetch_assoc()) {
                        if ($old['image'] && file_exists($upload_dir . $old['image'])) {
                            unlink($upload_dir . $old['image']);
                        }
                    }
                    $stmt->close();
                }
            } else {
                $image = null;
            }
        }
    }
    
    if ($action == 'edit' && $id > 0) {
        // Update existing
        if ($image) {
            $stmt = $conn->prepare("UPDATE testimonials SET client_name = ?, description = ?, rating = ?, image = ?, status = ? WHERE id = ?");
            $stmt->bind_param("ssdssi", $client_name, $description, $rating, $image, $status, $id);
        } else {
            $stmt = $conn->prepare("UPDATE testimonials SET client_name = ?, description = ?, rating = ?, status = ? WHERE id = ?");
            $stmt->bind_param("ssdsi", $client_name, $description, $rating, $status, $id);
        }
    } else {
        // Insert new
        if (!$image) {
            $image = null;
        }
        $stmt = $conn->prepare("INSERT INTO testimonials (client_name, description, rating, image, status) VALUES (?, ?, ?, ?, ?)");
        $stmt->bind_param("ssdss", $client_name, $description, $rating, $image, $status);
    }
    
    if ($stmt->execute()) {
        header('Location: ../testimonials.php?success=1');
    } else {
        header('Location: ../testimonials.php?error=Database error occurred');
    }
    
    $stmt->close();
    closeDBConnection($conn);
} else {
    header('Location: ../testimonials.php');
}
exit();
?>

