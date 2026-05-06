<?php
/**
 * Generate Password Hash
 * Access: http://localhost/public_html/admin/generate_hash.php?password=diginex@2026
 */

$password = $_GET['password'] ?? 'diginex@2026';

// Generate hash
$hash = password_hash($password, PASSWORD_DEFAULT);

?>
<!DOCTYPE html>
<html>
<head>
    <title>Generate Password Hash</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            max-width: 900px;
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
        code {
            background: #f4f4f4;
            padding: 15px;
            display: block;
            border-radius: 5px;
            font-family: 'Courier New', monospace;
            margin: 15px 0;
            word-break: break-all;
            border: 1px solid #ddd;
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
        .info {
            background: #d1ecf1;
            padding: 15px;
            border-radius: 5px;
            margin: 15px 0;
            border: 1px solid #bee5eb;
        }
    </style>
</head>
<body>
    <div class="box">
        <h1>🔐 Password Hash Generator</h1>
        
        <div class="success">
            <strong>Password:</strong> <?php echo htmlspecialchars($password); ?>
        </div>
        
        <h2>Generated Hash:</h2>
        <code><?php echo $hash; ?></code>
        
        <h2>SQL Query to Insert/Update Admin:</h2>
        <code>
USE admin_panel;

DELETE FROM admins WHERE username = 'diginex' OR email = 'diginex@example.com';

INSERT INTO admins (username, email, password, full_name) VALUES 
('diginex', 'diginex@example.com', '<?php echo $hash; ?>', 'Diginex Admin');
        </code>
        
        <div class="info">
            <h3>Instructions:</h3>
            <ol>
                <li>Copy the SQL query above</li>
                <li>Go to phpMyAdmin</li>
                <li>Select <code>admin_panel</code> database</li>
                <li>Click "SQL" tab</li>
                <li>Paste the query and click "Go"</li>
                <li>Then login with:
                    <ul>
                        <li><strong>Username:</strong> diginex</li>
                        <li><strong>Password:</strong> <?php echo htmlspecialchars($password); ?></li>
                    </ul>
                </li>
            </ol>
        </div>
        
        <h3>Generate Hash for Different Password:</h3>
        <p>Add <code>?password=YOUR_PASSWORD</code> to URL</p>
        <p>Example: <code>generate_hash.php?password=diginex@2026</code></p>
    </div>
</body>
</html>


