<?php
// Prevent form resubmission on refresh - must be before any output
header('Cache-Control: no-cache, no-store, must-revalidate');
header('Pragma: no-cache');
header('Expires: 0');

// Clean URL if file path detected
$request_uri = $_SERVER['REQUEST_URI'] ?? '';
if (preg_match('#/C:/|/xampp/|/htdocs/#i', $request_uri) || preg_match('#/public_html/public_html/#', $request_uri)) {
    $protocol = (!empty($_SERVER['HTTPS']) && $_SERVER['HTTPS'] !== 'off') ? 'https' : 'http';
    $host = $_SERVER['HTTP_HOST'] ?? 'localhost';
    $current_file = basename($_SERVER['SCRIPT_NAME'] ?? 'index.php');
    $clean_url = $protocol . '://' . $host . '/public_html/admin/' . $current_file;
    if (isset($_SERVER['QUERY_STRING']) && !empty($_SERVER['QUERY_STRING'])) {
        $clean_url .= '?' . $_SERVER['QUERY_STRING'];
    }
    header('HTTP/1.1 301 Moved Permanently');
    header('Location: ' . $clean_url);
    exit();
}

require_once __DIR__ . '/../config/config.php';
require_once __DIR__ . '/../config/database.php';
require_once __DIR__ . '/../config/auth.php';
requireLogin();

$current_page = basename($_SERVER['PHP_SELF']);
$admin_name = getAdminUsername();
$admin_full_name = $_SESSION['admin_name'] ?? $admin_name;
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?php echo isset($page_title) ? $page_title . ' - ' : ''; ?>Admin Panel</title>
    <!-- Base URL - DO NOT use file paths, always use web URL -->
    <base href="<?php echo BASE_URL; ?>">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <link rel="stylesheet" href="assets/css/admin.css">
    <script>
        // Set base URL for JavaScript to prevent file paths
        window.ADMIN_BASE_URL = '<?php echo BASE_URL; ?>';
        
        // Fix URL if it contains file paths (run immediately on page load)
        (function() {
            const currentUrl = window.location.href;
            // Check if URL contains file paths
            if (currentUrl.includes('/C:/') || currentUrl.includes('/xampp/') || currentUrl.includes('/htdocs/') || currentUrl.includes('/public_html/public_html/')) {
                // Extract the page name
                let pageName = 'index.php';
                const pathMatch = currentUrl.match(/admin\/([^\/\?]+)/);
                if (pathMatch) {
                    pageName = pathMatch[1];
                    // Ensure .php extension
                    if (!pageName.endsWith('.php')) {
                        pageName += '.php';
                    }
                }
                // Get query string
                const queryString = window.location.search;
                // Redirect to clean URL
                window.location.replace(window.ADMIN_BASE_URL + pageName + queryString);
            }
        })();
    </script>
</head>
<body>
    <div class="admin-wrapper">
        <!-- Sidebar -->
        <aside class="sidebar">
            <div class="sidebar-header">
                <a href="<?php echo BASE_URL; ?>index.php" class="sidebar-logo-link">

                    <img src="assets/images/logowhite.png" alt="Admin Panel Logo" class="sidebar-logo" />
                </a>
            </div>
            
            <nav class="sidebar-nav">
                <ul>
                    <li class="<?php echo $current_page == 'index.php' ? 'active' : ''; ?>">
                        <a href="<?php echo BASE_URL; ?>index.php">
                            <i class="fas fa-home"></i>
                            <span>Dashboard</span>
                        </a>
                    </li>
                    
                    <li class="nav-section">
                        <span class="nav-label">Content Management</span>
                    </li>
                    
                    <li class="<?php echo $current_page == 'blogs.php' ? 'active' : ''; ?>">
                        <a href="<?php echo BASE_URL; ?>blogs.php">
                            <i class="fas fa-blog"></i>
                            <span>Blogs</span>
                        </a>
                    </li>
                    
                    <li class="<?php echo $current_page == 'services.php' ? 'active' : ''; ?>">
                        <a href="<?php echo BASE_URL; ?>services.php">
                            <i class="fas fa-briefcase"></i>
                            <span>Services</span>
                        </a>
                    </li>
                    
                    <li class="<?php echo $current_page == 'testimonials.php' ? 'active' : ''; ?>">
                        <a href="<?php echo BASE_URL; ?>testimonials.php">
                            <i class="fas fa-comments"></i>
                            <span>Testimonials</span>
                        </a>
                    </li>
                    
                    <li class="<?php echo $current_page == 'categories.php' ? 'active' : ''; ?>">
                        <a href="<?php echo BASE_URL; ?>categories.php">
                            <i class="fas fa-th-large"></i>
                            <span>Categories</span>
                        </a>
                    </li>
                    
                    <li class="nav-section">
                        <span class="nav-label">Site Settings</span>
                    </li>
                    
                    <li class="<?php echo $current_page == 'seo.php' ? 'active' : ''; ?>">
                        <a href="<?php echo BASE_URL; ?>seo.php">
                            <i class="fas fa-search"></i>
                            <span>SEO</span>
                        </a>
                    </li>
                    
                    <li class="<?php echo $current_page == 'clients.php' ? 'active' : ''; ?>">
                        <a href="<?php echo BASE_URL; ?>clients.php">
                            <i class="fas fa-users"></i>
                            <span>Clients</span>
                        </a>
                    </li>
                    
                    <li class="<?php echo $current_page == 'contact.php' ? 'active' : ''; ?>">
                        <a href="<?php echo BASE_URL; ?>contact.php">
                            <i class="fas fa-envelope"></i>
                            <span>Contact Us</span>
                        </a>
                    </li>
                    
                    <li class="nav-section">
                        <span class="nav-label">API Management</span>
                    </li>
                    
                    <li class="<?php echo $current_page == 'api_list.php' ? 'active' : ''; ?>">
                        <a href="<?php echo BASE_URL; ?>api_list.php">
                            <i class="fas fa-code"></i>
                            <span>API List</span>
                        </a>
                    </li>
                </ul>
            </nav>
            
            <div class="sidebar-footer">
                <div class="admin-profile">
                    <div class="profile-avatar">
                        <?php echo strtoupper(substr($admin_full_name, 0, 2)); ?>
                    </div>
                    <div class="profile-info">
                        <span class="profile-name"><?php echo htmlspecialchars($admin_full_name); ?></span>
                        <span class="profile-email"><?php echo htmlspecialchars($_SESSION['admin_email'] ?? ''); ?></span>
                    </div>
                    <div class="profile-dropdown">
                        <a href="<?php echo BASE_URL; ?>profile.php" class="dropdown-item">
                            <i class="fas fa-user"></i> Profile
                        </a>
                        <a href="<?php echo BASE_URL; ?>logout.php" class="dropdown-item">
                            <i class="fas fa-sign-out-alt"></i> Logout
                        </a>
                    </div>
                </div>
            </div>
        </aside>
        
        <!-- Main Content -->
        <main class="main-content">
            <!-- Top Header -->
            <header class="top-header">
                <div class="header-left">
                    <button class="sidebar-toggle" id="sidebarToggle">
                        <i class="fas fa-bars"></i>
                    </button>
                    <h1 class="page-title"><?php echo isset($page_title) ? $page_title : 'Dashboard'; ?></h1>
                </div>
                <div class="header-right">
                    <a href="<?php echo SITE_URL; ?>index.php" target="_blank" class="btn btn-outline">
                        <i class="fas fa-external-link-alt"></i> View Website
                    </a>
                    <a href="<?php echo BASE_URL; ?>logout.php" class="btn btn-outline">
                        <i class="fas fa-sign-out-alt"></i> Logout
                    </a>
                </div>
            </header>
            
            <div class="content-wrapper">

