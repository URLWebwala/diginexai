<?php
// Clean URL if file path detected or malformed URL
$request_uri = $_SERVER['REQUEST_URI'] ?? '';
$script_name = $_SERVER['SCRIPT_NAME'] ?? '';
$request_path = parse_url($request_uri, PHP_URL_PATH);

// Check if URL contains file paths or is malformed
$needs_redirect = false;
if (preg_match('#/C:/|/xampp/|/htdocs/#i', $request_uri) || 
    preg_match('#/public_html/public_html/#', $request_uri) ||
    (preg_match('#/admin/services/?$#', $request_path) && !preg_match('#\.php$#', $request_path) && basename($script_name) !== 'services.php')) {
    $needs_redirect = true;
}

if ($needs_redirect) {
    $protocol = (!empty($_SERVER['HTTPS']) && $_SERVER['HTTPS'] !== 'off') ? 'https' : 'http';
    $host = $_SERVER['HTTP_HOST'] ?? 'localhost';
    $query_string = isset($_SERVER['QUERY_STRING']) && !empty($_SERVER['QUERY_STRING']) ? '?' . $_SERVER['QUERY_STRING'] : '';
    $clean_url = $protocol . '://' . $host . '/public_html/admin/services.php' . $query_string;
    header('HTTP/1.1 301 Moved Permanently');
    header('Location: ' . $clean_url);
    exit();
}

$page_title = 'Services';
require_once 'includes/header.php';
?>

<div class="page-header">
    <div>
        <h2>Services</h2>
        <p>Manage your services. (Coming Soon)</p>
    </div>
</div>

<div class="card">
    <div class="empty-state">
        <i class="fas fa-briefcase" style="font-size: 48px; color: #384bff; margin-bottom: 20px;"></i>
        <h3>Services Management</h3>
        <p>This feature will be available soon. You can manage your services here.</p>
    </div>
</div>

<?php require_once 'includes/footer.php'; ?>

