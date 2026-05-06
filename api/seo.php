<?php
header('Content-Type: application/json');
header('Access-Control-Allow-Origin: *');
header('Access-Control-Allow-Methods: GET, OPTIONS');
header('Access-Control-Allow-Headers: Content-Type');

// Handle preflight request
if ($_SERVER['REQUEST_METHOD'] === 'OPTIONS') {
    http_response_code(200);
    exit();
}

require_once __DIR__ . '/config/database.php';

$protocol = (!empty($_SERVER['HTTPS']) && $_SERVER['HTTPS'] !== 'off') ? 'https' : 'http';
$host = $_SERVER['HTTP_HOST'] ?? 'localhost';

$conn = getDBConnection();
$method = $_SERVER['REQUEST_METHOD'];

if ($method === 'GET') {
    $page_name = $_GET['page_name'] ?? null;
    $seo_url = $_GET['seo_url'] ?? null;
    
    // Get SEO data by page name
    if ($page_name) {
        $stmt = $conn->prepare("SELECT * FROM seo_data WHERE page_name = ? LIMIT 1");
        $stmt->bind_param("s", $page_name);
        $stmt->execute();
        $result = $stmt->get_result();
        
        if ($result->num_rows > 0) {
            $seo = $result->fetch_assoc();
            echo json_encode([
                'success' => true,
                'data' => [
                    'id' => (int)$seo['id'],
                    'page_name' => $seo['page_name'],
                    'title' => $seo['title'],
                    'page_title' => $seo['page_title'],
                    'description' => $seo['description'],
                    'keywords' => $seo['keywords'],
                    'author_name' => $seo['author_name'],
                    'seo_url' => $seo['seo_url'],
                    'canonical_url' => $seo['canonical_url'],
                    'robots' => $seo['robots'],
                    'created_at' => $seo['created_at'],
                    'updated_at' => $seo['updated_at']
                ]
            ], JSON_PRETTY_PRINT);
        } else {
            http_response_code(404);
            echo json_encode([
                'success' => false,
                'message' => 'SEO data not found for this page'
            ], JSON_PRETTY_PRINT);
        }
        $stmt->close();
    }
    // Get SEO data by SEO URL
    elseif ($seo_url) {
        $stmt = $conn->prepare("SELECT * FROM seo_data WHERE seo_url = ? LIMIT 1");
        $stmt->bind_param("s", $seo_url);
        $stmt->execute();
        $result = $stmt->get_result();
        
        if ($result->num_rows > 0) {
            $seo = $result->fetch_assoc();
            echo json_encode([
                'success' => true,
                'data' => [
                    'id' => (int)$seo['id'],
                    'page_name' => $seo['page_name'],
                    'title' => $seo['title'],
                    'page_title' => $seo['page_title'],
                    'description' => $seo['description'],
                    'keywords' => $seo['keywords'],
                    'author_name' => $seo['author_name'],
                    'seo_url' => $seo['seo_url'],
                    'canonical_url' => $seo['canonical_url'],
                    'robots' => $seo['robots'],
                    'created_at' => $seo['created_at'],
                    'updated_at' => $seo['updated_at']
                ]
            ], JSON_PRETTY_PRINT);
        } else {
            http_response_code(404);
            echo json_encode([
                'success' => false,
                'message' => 'SEO data not found'
            ], JSON_PRETTY_PRINT);
        }
        $stmt->close();
    }
    // Get all SEO data
    else {
        $stmt = $conn->prepare("SELECT * FROM seo_data ORDER BY page_name ASC");
        $stmt->execute();
        $result = $stmt->get_result();
        $seo_list = [];
        
        if ($result && $result->num_rows > 0) {
            while ($row = $result->fetch_assoc()) {
                $seo_list[] = [
                    'id' => (int)$row['id'],
                    'page_name' => $row['page_name'],
                    'title' => $row['title'],
                    'page_title' => $row['page_title'],
                    'description' => $row['description'],
                    'keywords' => $row['keywords'],
                    'author_name' => $row['author_name'],
                    'seo_url' => $row['seo_url'],
                    'canonical_url' => $row['canonical_url'],
                    'robots' => $row['robots'],
                    'created_at' => $row['created_at'],
                    'updated_at' => $row['updated_at']
                ];
            }
        }
        
        echo json_encode([
            'success' => true,
            'data' => $seo_list,
            'count' => count($seo_list)
        ], JSON_PRETTY_PRINT);
        
        $stmt->close();
    }
} else {
    http_response_code(405);
    echo json_encode([
        'success' => false,
        'message' => 'Method not allowed'
    ], JSON_PRETTY_PRINT);
}

closeDBConnection($conn);
?>

