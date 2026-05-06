<?php
/**
 * Setup Diginex Admin User
 * This will create/update admin user with Diginex credentials
 * Access: http://localhost/public_html/admin/setup_diginex_admin.php
 */

require_once __DIR__ . '/config/database.php';

// Diginex admin credentials
$username = 'diginex';
$email = 'diginex@example.com';
$password = 'diginex@2026';
$full_name = 'Diginex Admin';

?>
<!DOCTYPE html>
<html>
<head>
    <title>Setup Diginex Admin</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            max-width: 800px;
            margin: 50px auto;
            padding: 20px;
            background: #f5f5f5;
        }
        .box {
            background: white;
            padding: 30px;
            border-radius: 8px;
            box-shadow: 0 2px 10px rgba(0,0,0,0.1);
        }
        h1 {
            color: #333;
        }
        .success {
            color: green;
            font-weight: bold;
            padding: 15px;
            background: #d4edda;
            border: 1px solid #c3e6cb;
            border-radius: 5px;
            margin: 15px 0;
        }
        .error {
            color: red;
            font-weight: bold;
            padding: 15px;
            background: #f8d7da;
            border: 1px solid #f5c6cb;
            border-radius: 5px;
            margin: 15px 0;
        }
        .info {
            background: #d1ecf1;
            padding: 15px;
            border-radius: 5px;
            margin: 15px 0;
            border: 1px solid #bee5eb;
        }
        .btn {
            display: inline-block;
            padding: 12px 24px;
            background: #384bff;
            color: white;
            text-decoration: none;
            border-radius: 5px;
            margin: 10px 5px;
            border: none;
            cursor: pointer;
            font-size: 16px;
        }
        .btn:hover {
            background: #2a3dd4;
        }
        .btn-success {
            background: #28a745;
        }
        .btn-success:hover {
            background: #218838;
        }
        .credentials {
            background: #fff3cd;
            padding: 20px;
            border-radius: 5px;
            margin: 20px 0;
            border-left: 4px solid #ffc107;
        }
        .credentials strong {
            color: #856404;
            font-size: 18px;
        }
    </style>
</head>
<body>
    <div class="box">
        <h1>🔧 Setup Diginex Admin User</h1>
        
        <?php
        try {
            $conn = getDBConnection();
            
            // Check if admin already exists
            $check = $conn->prepare("SELECT id FROM admins WHERE username = ? OR email = ?");
            $check->bind_param("ss", $username, $email);
            $check->execute();
            $result = $check->get_result();
            
            if ($result->num_rows > 0) {
                echo "<div class='info'>";
                echo "<h2>Admin user already exists!</h2>";
                echo "<p>Click the button below to update password to: <strong>$password</strong></p>";
                echo "</div>";
                
                // Option to reset password
                if (isset($_POST['reset_password'])) {
                    $hashed_password = password_hash($password, PASSWORD_DEFAULT);
                    $update = $conn->prepare("UPDATE admins SET password = ?, username = ?, email = ?, full_name = ? WHERE username = ? OR email = ?");
                    $update->bind_param("ssssss", $hashed_password, $username, $email, $full_name, $username, $username);
                    
                    if ($update->execute()) {
                        echo "<div class='success'>";
                        echo "<h2>✅ Password Updated Successfully!</h2>";
                        echo "</div>";
                        
                        echo "<div class='credentials'>";
                        echo "<h3>Login Credentials:</h3>";
                        echo "<p><strong>Username:</strong> $username</p>";
                        echo "<p><strong>Password:</strong> $password</p>";
                        echo "<p><strong>Email:</strong> $email</p>";
                        echo "<p><strong>Full Name:</strong> $full_name</p>";
                        echo "</div>";
                        
                        echo "<a href='login.php' class='btn btn-success'>Go to Login Page</a>";
                    } else {
                        echo "<div class='error'>";
                        echo "<p>Error updating: " . $conn->error . "</p>";
                        echo "</div>";
                    }
                    $update->close();
                } else {
                    echo "<form method='POST'>";
                    echo "<button type='submit' name='reset_password' class='btn btn-success'>Update Password to: diginex@2026</button>";
                    echo "</form>";
                }
            } else {
                // Create admin user
                if (isset($_POST['create_admin']) || !isset($_POST)) {
                    $hashed_password = password_hash($password, PASSWORD_DEFAULT);
                    $stmt = $conn->prepare("INSERT INTO admins (username, email, password, full_name) VALUES (?, ?, ?, ?)");
                    $stmt->bind_param("ssss", $username, $email, $hashed_password, $full_name);
                    
                    if ($stmt->execute()) {
                        echo "<div class='success'>";
                        echo "<h2>✅ Diginex Admin User Created Successfully!</h2>";
                        echo "</div>";
                        
                        echo "<div class='credentials'>";
                        echo "<h3>Login Credentials:</h3>";
                        echo "<p><strong>Username:</strong> $username</p>";
                        echo "<p><strong>Password:</strong> $password</p>";
                        echo "<p><strong>Email:</strong> $email</p>";
                        echo "<p><strong>Full Name:</strong> $full_name</p>";
                        echo "</div>";
                        
                        echo "<a href='login.php' class='btn btn-success'>Go to Login Page</a>";
                    } else {
                        echo "<div class='error'>";
                        echo "<h2>❌ Error creating admin user!</h2>";
                        echo "<p>Error: " . $conn->error . "</p>";
                        echo "</div>";
                    }
                    $stmt->close();
                } else {
                    echo "<div class='info'>";
                    echo "<p>Click the button below to create Diginex admin user:</p>";
                    echo "</div>";
                    
                    echo "<form method='POST'>";
                    echo "<button type='submit' name='create_admin' class='btn btn-success'>Create Diginex Admin User</button>";
                    echo "</form>";
                }
            }
            
            $check->close();
            closeDBConnection($conn);
            
        } catch (Exception $e) {
            echo "<div class='error'>";
            echo "<h2>❌ Database Error!</h2>";
            echo "<p>" . htmlspecialchars($e->getMessage()) . "</p>";
            echo "<p>Please check your database configuration in <code>config/database.php</code></p>";
            echo "</div>";
        }
        ?>
    </div>
</body>
</html>


