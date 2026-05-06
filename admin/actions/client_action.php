<?php
require_once __DIR__ . '/../config/database.php';
require_once __DIR__ . '/../config/auth.php';
requireLogin();

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $action = $_POST['action'] ?? '';
    $id = intval($_POST['id'] ?? 0);
    $client_name = trim($_POST['client_name'] ?? '');
    $website_url = trim($_POST['website_url'] ?? '');
    $email = trim($_POST['email'] ?? '');
    $phone = trim($_POST['phone'] ?? '');
    $status = $_POST['status'] ?? 'active';
    
    // Validate
    if (empty($client_name)) {
        header('Location: ../clients.php?error=Please enter client name');
        exit();
    }
    
    $conn = getDBConnection();
    
    // Handle file upload
    $logo = null;
    if (isset($_FILES['logo']) && $_FILES['logo']['error'] == 0) {
        $upload_dir = '../../uploads/clients/';
        if (!is_dir($upload_dir)) {
            mkdir($upload_dir, 0777, true);
        }
        
        $file_ext = strtolower(pathinfo($_FILES['logo']['name'], PATHINFO_EXTENSION));
        $allowed_ext = ['jpg', 'jpeg', 'png', 'gif', 'webp', 'svg'];
        
        if (in_array($file_ext, $allowed_ext)) {
            $logo = uniqid() . '_' . time() . '.' . $file_ext;
            $upload_path = $upload_dir . $logo;
            
            if (move_uploaded_file($_FILES['logo']['tmp_name'], $upload_path)) {
                // If editing, delete old logo
                if ($action == 'edit' && $id > 0) {
                    $stmt = $conn->prepare("SELECT logo FROM clients WHERE id = ?");
                    $stmt->bind_param("i", $id);
                    $stmt->execute();
                    $result = $stmt->get_result();
                    if ($old = $result->fetch_assoc()) {
                        if ($old['logo'] && file_exists($upload_dir . $old['logo'])) {
                            unlink($upload_dir . $old['logo']);
                        }
                    }
                    $stmt->close();
                }
            } else {
                $logo = null;
            }
        }
    }
    
    if ($action == 'edit' && $id > 0) {
        // Update existing
        if ($logo) {
            $stmt = $conn->prepare("UPDATE clients SET client_name = ?, website_url = ?, email = ?, phone = ?, logo = ?, status = ? WHERE id = ?");
            $stmt->bind_param("ssssssi", $client_name, $website_url, $email, $phone, $logo, $status, $id);
        } else {
            $stmt = $conn->prepare("UPDATE clients SET client_name = ?, website_url = ?, email = ?, phone = ?, status = ? WHERE id = ?");
            $stmt->bind_param("sssssi", $client_name, $website_url, $email, $phone, $status, $id);
        }
    } else {
        // Insert new
        if (!$logo) {
            $logo = null;
        }
        $stmt = $conn->prepare("INSERT INTO clients (client_name, website_url, email, phone, logo, status) VALUES (?, ?, ?, ?, ?, ?)");
        $stmt->bind_param("ssssss", $client_name, $website_url, $email, $phone, $logo, $status);
    }
    
    if ($stmt->execute()) {
        header('Location: ../clients.php?success=1');
    } else {
        header('Location: ../clients.php?error=Database error occurred');
    }
    
    $stmt->close();
    closeDBConnection($conn);
} else {
    header('Location: ../clients.php');
}
exit();
?>

