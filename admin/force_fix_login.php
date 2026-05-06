<?php
/**
 * FORCE FIX LOGIN - This will definitely fix your login
 * Access: http://localhost/public_html/admin/force_fix_login.php
 */

require_once __DIR__ . '/config/database.php';

$username = 'diginex';
$password = 'diginex@2026';
$email = 'diginex@example.com';
$full_name = 'Diginex Admin';

?>
<!DOCTYPE html>
<html>
<head>
    <title>Force Fix Login</title>
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
        h1 { color: #333; }
        .success {
            background: #d4edda;
            color: #155724;
            padding: 15px;
            border-radius: 5px;
            margin: 15px 0;
            border: 1px solid #c3e6cb;
        }
        .error {
            background: #f8d7da;
            color: #721c24;
            padding: 15px;
            border-radius: 5px;
            margin: 15px 0;
            border: 1px solid #f5c6cb;
        }
        .info {
            background: #d1ecf1;
            padding: 15px;
            border-radius: 5px;
            margin: 15px 0;
        }
        .btn {
            display: inline-block;
            padding: 15px 30px;
            background: #28a745;
            color: white;
            text-decoration: none;
            border-radius: 5px;
            border: none;
            cursor: pointer;
            font-size: 18px;
            margin: 10px 5px;
        }
        .btn:hover { background: #218838; }
        .btn-primary { background: #384bff; }
        .btn-primary:hover { background: #2a3dd4; }
        code {
            background: #f4f4f4;
            padding: 10px;
            display: block;
            border-radius: 3px;
            font-family: monospace;
            margin: 10px 0;
            word-break: break-all;
        }
        table {
            width: 100%;
            border-collapse: collapse;
            margin: 15px 0;
        }
        table th, table td {
            padding: 10px;
            border: 1px solid #ddd;
            text-align: left;
        }
        table th { background: #f8f9fa; }
    </style>
</head>
<body>
    <div class="box">
        <h1>🔧 Force Fix Login - Complete Solution</h1>
        
        <?php
        if (isset($_POST['force_fix'])) {
            try {
                $conn = getDBConnection();
                
                echo "<h2>Step 1: Checking Database Connection</h2>";
                echo "<div class='success'>✅ Database connected</div>";
                
                echo "<h2>Step 2: Checking Existing Admin Users</h2>";
                $check_all = $conn->query("SELECT id, username, email, password, full_name FROM admins");
                if ($check_all->num_rows > 0) {
                    echo "<p>Found " . $check_all->num_rows . " admin user(s):</p>";
                    echo "<table>";
                    echo "<tr><th>ID</th><th>Username</th><th>Email</th><th>Password Hash</th></tr>";
                    while ($row = $check_all->fetch_assoc()) {
                        echo "<tr>";
                        echo "<td>" . $row['id'] . "</td>";
                        echo "<td>" . htmlspecialchars($row['username']) . "</td>";
                        echo "<td>" . htmlspecialchars($row['email']) . "</td>";
                        echo "<td><code>" . htmlspecialchars(substr($row['password'], 0, 30)) . "...</code></td>";
                        echo "</tr>";
                    }
                    echo "</table>";
                } else {
                    echo "<div class='info'>No admin users found. Will create new one.</div>";
                }
                
                echo "<h2>Step 3: Generating Fresh Password Hash</h2>";
                $hashed_password = password_hash($password, PASSWORD_DEFAULT);
                echo "<div class='success'>✅ Password hash generated</div>";
                echo "<p>Hash: <code>" . htmlspecialchars(substr($hashed_password, 0, 50)) . "...</code></p>";
                
                // Verify the hash works
                $test_verify = password_verify($password, $hashed_password);
                if ($test_verify) {
                    echo "<div class='success'>✅ Hash verification test: PASSED</div>";
                } else {
                    echo "<div class='error'>❌ Hash verification test: FAILED (This should never happen!)</div>";
                }
                
                echo "<h2>Step 4: Deleting All Existing Admin Users</h2>";
                $delete = $conn->query("DELETE FROM admins WHERE username = 'admin' OR username = 'diginex' OR email = 'admin@example.com' OR email = 'diginex@example.com'");
                $deleted = $conn->affected_rows;
                echo "<div class='info'>Deleted $deleted existing admin user(s)</div>";
                
                echo "<h2>Step 5: Creating New Diginex Admin User</h2>";
                $stmt = $conn->prepare("INSERT INTO admins (username, email, password, full_name) VALUES (?, ?, ?, ?)");
                $stmt->bind_param("ssss", $username, $email, $hashed_password, $full_name);
                
                if ($stmt->execute()) {
                    $new_id = $conn->insert_id;
                    echo "<div class='success'>✅ Admin user created successfully!</div>";
                    echo "<p>New Admin ID: <code>$new_id</code></p>";
                    
                    echo "<h2>Step 6: Verifying Created User</h2>";
                    $verify_stmt = $conn->prepare("SELECT id, username, email, password, full_name FROM admins WHERE id = ?");
                    $verify_stmt->bind_param("i", $new_id);
                    $verify_stmt->execute();
                    $verify_result = $verify_stmt->get_result();
                    $verify_admin = $verify_result->fetch_assoc();
                    
                    if ($verify_admin) {
                        echo "<div class='success'>✅ User verified in database</div>";
                        echo "<table>";
                        echo "<tr><th>Field</th><th>Value</th></tr>";
                        echo "<tr><td>ID</td><td>" . $verify_admin['id'] . "</td></tr>";
                        echo "<tr><td>Username</td><td><code>" . htmlspecialchars($verify_admin['username']) . "</code></td></tr>";
                        echo "<tr><td>Email</td><td><code>" . htmlspecialchars($verify_admin['email']) . "</code></td></tr>";
                        echo "<tr><td>Full Name</td><td>" . htmlspecialchars($verify_admin['full_name']) . "</td></tr>";
                        echo "</table>";
                        
                        echo "<h2>Step 7: Final Password Verification Test</h2>";
                        $final_verify = password_verify($password, $verify_admin['password']);
                        if ($final_verify) {
                            echo "<div class='success'>";
                            echo "<h3>✅✅✅ PASSWORD VERIFICATION SUCCESSFUL! ✅✅✅</h3>";
                            echo "<p><strong>Login will work now!</strong></p>";
                            echo "</div>";
                        } else {
                            echo "<div class='error'>❌ Password verification failed (This is very strange!)</div>";
                        }
                        
                        echo "<h2>Step 8: Testing Login Query</h2>";
                        $test_login_stmt = $conn->prepare("SELECT id, username, email, password, full_name FROM admins WHERE username = ? OR email = ?");
                        $test_login_stmt->bind_param("ss", $username, $username);
                        $test_login_stmt->execute();
                        $test_login_result = $test_login_stmt->get_result();
                        
                        if ($test_login_result->num_rows === 1) {
                            $test_admin = $test_login_result->fetch_assoc();
                            $test_password_verify = password_verify($password, $test_admin['password']);
                            
                            if ($test_password_verify) {
                                echo "<div class='success'>";
                                echo "<h3>✅✅✅ LOGIN TEST PASSED! ✅✅✅</h3>";
                                echo "<p><strong>Everything is working correctly!</strong></p>";
                                echo "<p>Login query finds user: ✅</p>";
                                echo "<p>Password verification: ✅</p>";
                                echo "</div>";
                            } else {
                                echo "<div class='error'>❌ Login test failed - password verification</div>";
                            }
                        } else {
                            echo "<div class='error'>❌ Login test failed - user not found</div>";
                        }
                        $test_login_stmt->close();
                    }
                    $verify_stmt->close();
                    $stmt->close();
                    
                    echo "<div class='success' style='margin-top: 30px; padding: 20px;'>";
                    echo "<h2>🎉 FIX COMPLETE!</h2>";
                    echo "<h3>Login Credentials:</h3>";
                    echo "<p><strong>Username:</strong> <code>$username</code></p>";
                    echo "<p><strong>Password:</strong> <code>$password</code></p>";
                    echo "<p><strong>Email:</strong> <code>$email</code></p>";
                    echo "</div>";
                    
                    echo "<a href='login.php' class='btn btn-primary' style='font-size: 20px; padding: 15px 40px;'>Go to Login Page Now</a>";
                    
                } else {
                    echo "<div class='error'>❌ Error creating admin: " . $conn->error . "</div>";
                }
                
                closeDBConnection($conn);
                
            } catch (Exception $e) {
                echo "<div class='error'>";
                echo "<h2>❌ Error</h2>";
                echo "<p>" . htmlspecialchars($e->getMessage()) . "</p>";
                echo "</div>";
            }
        } else {
            echo "<div class='info'>";
            echo "<h2>What This Will Do:</h2>";
            echo "<ol>";
            echo "<li>Check database connection</li>";
            echo "<li>Show all existing admin users</li>";
            echo "<li>Generate fresh password hash for 'diginex@2026'</li>";
            echo "<li>Delete all old admin users</li>";
            echo "<li>Create new Diginex admin user with correct hash</li>";
            echo "<li>Verify password works</li>";
            echo "<li>Test complete login process</li>";
            echo "</ol>";
            echo "</div>";
            
            echo "<form method='POST'>";
            echo "<button type='submit' name='force_fix' class='btn' style='font-size: 20px; padding: 15px 40px;'>🔧 FORCE FIX LOGIN NOW</button>";
            echo "</form>";
            
            echo "<div class='info' style='margin-top: 20px;'>";
            echo "<h3>After fixing, use these credentials:</h3>";
            echo "<p><strong>Username:</strong> diginex</p>";
            echo "<p><strong>Password:</strong> diginex@2026</p>";
            echo "</div>";
        }
        ?>
    </div>
</body>
</html>


