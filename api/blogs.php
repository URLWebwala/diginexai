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

require_once __DIR__ . '/config/config.php';
require_once __DIR__ . '/config/database.php';

$conn = getDBConnection();
$method = $_SERVER['REQUEST_METHOD'];

if ($method === 'GET') {
    $id = $_GET['id'] ?? null;
    $slug = $_GET['slug'] ?? null;
    
    // Get single blog by slug
    if ($slug) {
        $stmt = $conn->prepare("SELECT b.*, c.name as category_name 
                                FROM blogs b 
                                LEFT JOIN blog_categories c ON b.category_id = c.id 
                                WHERE b.slug = ? AND b.status = 'active'");
        $stmt->bind_param("s", $slug);
        $stmt->execute();
        $result = $stmt->get_result();
        
        if ($result->num_rows > 0) {
            $blog = $result->fetch_assoc();
            echo json_encode([
                'success' => true,
                'data' => [
                    'id' => (int)$blog['id'],
                    'title' => $blog['title'],
                    'slug' => $blog['slug'],
                    'content' => $blog['content'],
                    'author_name' => $blog['author_name'],
                    'category_id' => (int)$blog['category_id'],
                    'category_name' => $blog['category_name'],
                    'blog_image' => $blog['blog_image'] ? UPLOADS_URL . 'blogs/' . $blog['blog_image'] : null,
                    'canonical_url' => $blog['canonical_url'],
                    'seo_url' => $blog['seo_url'],
                    'robots' => $blog['robots'],
                    'status' => $blog['status'],
                    'created_at' => $blog['created_at'],
                    'updated_at' => $blog['updated_at']
                ]
            ], JSON_PRETTY_PRINT);
        } else {
            // Slug not found - try to find by matching title (for generated slugs)
            // Generate slug from potential title match
            $stmt->close();
            
            // Try to find blog with slug='0' or empty, and match by generated slug from title
            $stmt = $conn->prepare("SELECT b.*, c.name as category_name 
                                    FROM blogs b 
                                    LEFT JOIN blog_categories c ON b.category_id = c.id 
                                    WHERE (b.slug = '0' OR b.slug = '' OR b.slug IS NULL) 
                                    AND b.status = 'active'");
            $stmt->execute();
            $result = $stmt->get_result();
            
            $found = false;
            while ($row = $result->fetch_assoc()) {
                // Generate slug from title and compare
                $titleSlug = strtolower(trim($row['title']));
                $titleSlug = preg_replace('/[^a-z0-9]+/', '-', $titleSlug);
                $titleSlug = preg_replace('/(^-|-$)/', '', $titleSlug);
                $titleSlug = substr($titleSlug, 0, 100);
                
                if ($titleSlug === $slug) {
                    // Found matching blog - update slug in database
                    $update_stmt = $conn->prepare("UPDATE blogs SET slug = ? WHERE id = ?");
                    $update_stmt->bind_param("si", $slug, $row['id']);
                    $update_stmt->execute();
                    $update_stmt->close();
                    
                    // Return the blog data
                    echo json_encode([
                        'success' => true,
                        'data' => [
                            'id' => (int)$row['id'],
                            'title' => $row['title'],
                            'slug' => $slug,
                            'content' => $row['content'],
                            'author_name' => $row['author_name'],
                            'category_id' => (int)$row['category_id'],
                            'category_name' => $row['category_name'],
                            'blog_image' => $row['blog_image'] ? UPLOADS_URL . 'blogs/' . $row['blog_image'] : null,
                            'canonical_url' => $row['canonical_url'],
                            'seo_url' => $row['seo_url'],
                            'robots' => $row['robots'],
                            'status' => $row['status'],
                            'created_at' => $row['created_at'],
                            'updated_at' => $row['updated_at']
                        ]
                    ], JSON_PRETTY_PRINT);
                    $found = true;
                    break;
                }
            }
            $stmt->close();
            
            if (!$found) {
                http_response_code(404);
                echo json_encode([
                    'success' => false,
                    'message' => 'Blog not found'
                ], JSON_PRETTY_PRINT);
            }
        }
    }
    // Get single blog by ID
    elseif ($id) {
        $id = intval($id);
        $stmt = $conn->prepare("SELECT b.*, c.name as category_name 
                                FROM blogs b 
                                LEFT JOIN blog_categories c ON b.category_id = c.id 
                                WHERE b.id = ? AND b.status = 'active'");
        $stmt->bind_param("i", $id);
        $stmt->execute();
        $result = $stmt->get_result();
        
        if ($result->num_rows > 0) {
            $blog = $result->fetch_assoc();
            echo json_encode([
                'success' => true,
                'data' => [
                    'id' => (int)$blog['id'],
                    'title' => $blog['title'],
                    'slug' => $blog['slug'],
                    'content' => $blog['content'],
                    'author_name' => $blog['author_name'],
                    'category_id' => (int)$blog['category_id'],
                    'category_name' => $blog['category_name'],
                    'blog_image' => $blog['blog_image'] ? UPLOADS_URL . 'blogs/' . $blog['blog_image'] : null,
                    'canonical_url' => $blog['canonical_url'],
                    'seo_url' => $blog['seo_url'],
                    'robots' => $blog['robots'],
                    'status' => $blog['status'],
                    'created_at' => $blog['created_at'],
                    'updated_at' => $blog['updated_at']
                ]
            ], JSON_PRETTY_PRINT);
        } else {
            http_response_code(404);
            echo json_encode([
                'success' => false,
                'message' => 'Blog not found'
            ], JSON_PRETTY_PRINT);
        }
        $stmt->close();
    }
    // Get all blogs
    else {
        $category = $_GET['category'] ?? null;
        $limit = isset($_GET['limit']) ? intval($_GET['limit']) : null;
        
        $query = "SELECT b.*, c.name as category_name 
                  FROM blogs b 
                  LEFT JOIN blog_categories c ON b.category_id = c.id 
                  WHERE b.status = 'active'";
        
        if ($category) {
            $query .= " AND c.slug = ?";
        }
        
        $query .= " ORDER BY b.created_at DESC";
        
        if ($limit) {
            $query .= " LIMIT ?";
        }
        
        if ($category && $limit) {
            $stmt = $conn->prepare($query);
            $stmt->bind_param("si", $category, $limit);
        } elseif ($category) {
            $stmt = $conn->prepare($query);
            $stmt->bind_param("s", $category);
        } elseif ($limit) {
            $stmt = $conn->prepare($query);
            $stmt->bind_param("i", $limit);
        } else {
            $stmt = $conn->prepare($query);
        }
        
        try {
            $stmt->execute();
            $result = $stmt->get_result();
            $blogs = [];
            
            if ($result && $result->num_rows > 0) {
            while ($row = $result->fetch_assoc()) {
                $blogs[] = [
                    'id' => (int)$row['id'],
                    'title' => $row['title'],
                    'slug' => $row['slug'],
                    'excerpt' => substr(strip_tags($row['content']), 0, 200) . '...',
                    'author_name' => $row['author_name'],
                    'category_id' => (int)$row['category_id'],
                    'category_name' => $row['category_name'],
                    'blog_image' => $row['blog_image'] ? UPLOADS_URL . 'blogs/' . $row['blog_image'] : null,
                    'created_at' => $row['created_at'],
                    'updated_at' => $row['updated_at']
                ];
            }
        }
        
            echo json_encode([
                'success' => true,
                'data' => $blogs,
                'count' => count($blogs)
            ], JSON_PRETTY_PRINT);
        } catch (Exception $e) {
            http_response_code(500);
            echo json_encode([
                'success' => false,
                'message' => 'Database error: ' . $e->getMessage()
            ], JSON_PRETTY_PRINT);
        }
        
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

