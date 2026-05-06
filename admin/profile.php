<?php
$page_title = 'Admin Profile';
require_once 'includes/header.php';
require_once __DIR__ . '/config/database.php';

$conn = getDBConnection();
$admin_id = getAdminId();
$error = '';
$success = '';

// Get admin data
$stmt = $conn->prepare("SELECT * FROM admins WHERE id = ?");
$stmt->bind_param("i", $admin_id);
$stmt->execute();
$admin = $stmt->get_result()->fetch_assoc();
$stmt->close();

// Handle profile update
if ($_SERVER['REQUEST_METHOD'] == 'POST' && isset($_POST['update_profile'])) {
    $full_name = trim($_POST['full_name'] ?? '');
    $email = trim($_POST['email'] ?? '');
    $username = trim($_POST['username'] ?? '');
    
    if (empty($full_name) || empty($email) || empty($username)) {
        $error = 'Please fill all required fields';
    } else {
        // Check if email exists for another user
        $check_stmt = $conn->prepare("SELECT id FROM admins WHERE email = ? AND id != ?");
        $check_stmt->bind_param("si", $email, $admin_id);
        $check_stmt->execute();
        if ($check_stmt->get_result()->num_rows > 0) {
            $error = 'Email already exists';
        } else {
            // Check if username exists for another user
            $check_stmt = $conn->prepare("SELECT id FROM admins WHERE username = ? AND id != ?");
            $check_stmt->bind_param("si", $username, $admin_id);
            $check_stmt->execute();
            if ($check_stmt->get_result()->num_rows > 0) {
                $error = 'Username already exists';
            } else {
                // Handle profile image upload
                $profile_image = $admin['profile_image'];
                if (isset($_FILES['profile_image']) && $_FILES['profile_image']['error'] == 0) {
                    $upload_dir = __DIR__ . '/../uploads/profile/';
                    if (!is_dir($upload_dir)) {
                        mkdir($upload_dir, 0777, true);
                    }
                    
                    $file_ext = strtolower(pathinfo($_FILES['profile_image']['name'], PATHINFO_EXTENSION));
                    $allowed_ext = ['jpg', 'jpeg', 'png', 'gif', 'webp'];
                    
                    if (in_array($file_ext, $allowed_ext)) {
                        $profile_image = uniqid() . '_' . time() . '.' . $file_ext;
                        $upload_path = $upload_dir . $profile_image;
                        
                        if (move_uploaded_file($_FILES['profile_image']['tmp_name'], $upload_path)) {
                            // Delete old image
                            if ($admin['profile_image'] && file_exists($upload_dir . $admin['profile_image'])) {
                                unlink($upload_dir . $admin['profile_image']);
                            }
                        } else {
                            $profile_image = $admin['profile_image'];
                        }
                    }
                }
                
                $stmt = $conn->prepare("UPDATE admins SET full_name = ?, email = ?, username = ?, profile_image = ? WHERE id = ?");
                $stmt->bind_param("ssssi", $full_name, $email, $username, $profile_image, $admin_id);
                
                if ($stmt->execute()) {
                    $_SESSION['admin_name'] = $full_name;
                    $_SESSION['admin_email'] = $email;
                    $_SESSION['admin_username'] = $username;
                    $success = 'Profile updated successfully';
                    $admin = $conn->query("SELECT * FROM admins WHERE id = $admin_id")->fetch_assoc();
                } else {
                    $error = 'Failed to update profile';
                }
                $stmt->close();
            }
        }
        $check_stmt->close();
    }
}

// Handle password change
if ($_SERVER['REQUEST_METHOD'] == 'POST' && isset($_POST['change_password'])) {
    $current_password = $_POST['current_password'] ?? '';
    $new_password = $_POST['new_password'] ?? '';
    $confirm_password = $_POST['confirm_password'] ?? '';
    
    if (empty($current_password) || empty($new_password) || empty($confirm_password)) {
        $error = 'Please fill all password fields';
    } elseif ($new_password !== $confirm_password) {
        $error = 'New passwords do not match';
    } elseif (strlen($new_password) < 6) {
        $error = 'Password must be at least 6 characters';
    } else {
        // Verify current password
        if (password_verify($current_password, $admin['password'])) {
            $hashed_password = password_hash($new_password, PASSWORD_DEFAULT);
            $stmt = $conn->prepare("UPDATE admins SET password = ? WHERE id = ?");
            $stmt->bind_param("si", $hashed_password, $admin_id);
            
            if ($stmt->execute()) {
                $success = 'Password changed successfully';
            } else {
                $error = 'Failed to change password';
            }
            $stmt->close();
        } else {
            $error = 'Current password is incorrect';
        }
    }
}

closeDBConnection($conn);
?>

<div class="profile-page">
    <div class="profile-header">
        <div class="profile-avatar-large">
            <?php if ($admin['profile_image']): ?>
            <img src="<?php echo UPLOADS_URL; ?>profile/<?php echo htmlspecialchars($admin['profile_image']); ?>" alt="Profile">
            <?php else: ?>
            <div class="avatar-placeholder">
                <?php echo strtoupper(substr($admin['full_name'], 0, 2)); ?>
            </div>
            <?php endif; ?>
        </div>
        <div class="profile-info">
            <h2><?php echo htmlspecialchars($admin['full_name']); ?></h2>
            <p><?php echo htmlspecialchars($admin['email']); ?></p>
        </div>
    </div>
    
    <?php if ($error): ?>
    <div class="alert alert-error">
        <i class="fas fa-exclamation-circle"></i> <?php echo htmlspecialchars($error); ?>
    </div>
    <?php endif; ?>
    
    <?php if ($success): ?>
    <div class="alert alert-success">
        <i class="fas fa-check-circle"></i> <?php echo htmlspecialchars($success); ?>
    </div>
    <?php endif; ?>
    
    <div class="profile-tabs">
        <button class="tab-btn active" onclick="switchTab('profile', this)">Profile Information</button>
        <button class="tab-btn" onclick="switchTab('password', this)">Change Password</button>
    </div>
    
    <div class="tab-content active" id="profileTab">
        <div class="card">
            <form method="POST" enctype="multipart/form-data">
                <input type="hidden" name="update_profile" value="1">
                
                <div class="form-group">
                    <label for="full_name">Full Name</label>
                    <input type="text" id="full_name" name="full_name" 
                           value="<?php echo htmlspecialchars($admin['full_name']); ?>" required>
                </div>
                
                <div class="form-group">
                    <label for="username">Username</label>
                    <input type="text" id="username" name="username" 
                           value="<?php echo htmlspecialchars($admin['username']); ?>" required>
                </div>
                
                <div class="form-group">
                    <label for="email">Email</label>
                    <input type="email" id="email" name="email" 
                           value="<?php echo htmlspecialchars($admin['email']); ?>" required>
                </div>
                
                <div class="form-group">
                    <label for="profile_image">Profile Image</label>
                    <div class="file-upload">
                        <input type="file" id="profile_image" name="profile_image" accept="image/*" 
                               onchange="previewImage(this, 'profileImagePreview')">
                        <label for="profile_image" class="file-label">
                            <i class="fas fa-upload"></i> Choose File
                        </label>
                        <span class="file-name" id="profileImageFileName">No file chosen</span>
                    </div>
                    <div id="profileImagePreview" class="image-preview"></div>
                </div>
                
                <button type="submit" class="btn btn-primary">
                    <i class="fas fa-save"></i> Update Profile
                </button>
            </form>
        </div>
    </div>
    
    <div class="tab-content" id="passwordTab">
        <div class="card">
            <form method="POST">
                <input type="hidden" name="change_password" value="1">
                
                <div class="form-group">
                    <label for="current_password">Current Password</label>
                    <input type="password" id="current_password" name="current_password" required>
                </div>
                
                <div class="form-group">
                    <label for="new_password">New Password</label>
                    <input type="password" id="new_password" name="new_password" required>
                </div>
                
                <div class="form-group">
                    <label for="confirm_password">Confirm New Password</label>
                    <input type="password" id="confirm_password" name="confirm_password" required>
                </div>
                
                <button type="submit" class="btn btn-primary">
                    <i class="fas fa-key"></i> Change Password
                </button>
            </form>
        </div>
    </div>
</div>

<script>
function switchTab(tab, button) {
    document.querySelectorAll('.tab-content').forEach(t => t.classList.remove('active'));
    document.querySelectorAll('.tab-btn').forEach(b => b.classList.remove('active'));
    
    document.getElementById(tab + 'Tab').classList.add('active');
    button.classList.add('active');
}

function previewImage(input, previewId) {
    const preview = document.getElementById(previewId);
    const fileName = document.getElementById('profileImageFileName');
    
    if (input.files && input.files[0]) {
        const reader = new FileReader();
        reader.onload = function(e) {
            preview.innerHTML = '<img src="' + e.target.result + '" alt="Preview" style="max-width: 200px; margin-top: 10px; border-radius: 8px;">';
        };
        reader.readAsDataURL(input.files[0]);
        fileName.textContent = input.files[0].name;
    }
}
</script>

<?php require_once 'includes/footer.php'; ?>

