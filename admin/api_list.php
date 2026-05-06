<?php
$page_title = 'API List';
require_once 'includes/header.php';

$protocol = (!empty($_SERVER['HTTPS']) && $_SERVER['HTTPS'] !== 'off') ? 'https' : 'http';
$host = $_SERVER['HTTP_HOST'] ?? 'localhost';
$api_base_url = $protocol . '://' . $host . '/public_html/api/';

$apis = [
    [
        'name' => 'Contact Us API',
        'endpoint' => $api_base_url . 'contact.php',
        'methods' => ['GET', 'POST'],
        'description' => 'Get all contact messages or submit a new contact message',
        'get_example' => [
            'url' => $api_base_url . 'contact.php',
            'method' => 'GET',
            'response' => [
                'success' => true,
                'data' => [
                    [
                        'id' => 1,
                        'first_name' => 'John',
                        'last_name' => 'Doe',
                        'email' => 'john@example.com',
                        'phone' => '+1234567890',
                        'service' => 'Web Development',
                        'message' => 'I need help with my website',
                        'created_at' => '2024-01-15 10:30:00'
                    ]
                ],
                'count' => 1
            ]
        ],
        'post_example' => [
            'url' => $api_base_url . 'contact.php',
            'method' => 'POST',
            'body' => [
                'first_name' => 'John',
                'last_name' => 'Doe',
                'email' => 'john@example.com',
                'phone' => '+1234567890',
                'service' => 'Web Development',
                'message' => 'I need help with my website'
            ],
            'response' => [
                'success' => true,
                'message' => 'Contact message submitted successfully',
                'data' => [
                    'id' => 1,
                    'first_name' => 'John',
                    'last_name' => 'Doe',
                    'email' => 'john@example.com',
                    'phone' => '+1234567890',
                    'service' => 'Web Development',
                    'message' => 'I need help with my website'
                ]
            ]
        ]
    ],
    [
        'name' => 'Blogs API',
        'endpoint' => $api_base_url . 'blogs.php',
        'methods' => ['GET'],
        'description' => 'Get all blogs or a single blog by ID',
        'get_all_example' => [
            'url' => $api_base_url . 'blogs.php',
            'method' => 'GET',
            'query_params' => [
                'category' => 'web-development (optional)',
                'limit' => '10 (optional)'
            ],
            'response' => [
                'success' => true,
                'data' => [
                    [
                        'id' => 1,
                        'title' => 'How to Build a Modern Website',
                        'slug' => 'how-to-build-modern-website',
                        'excerpt' => 'Learn the best practices for building modern websites...',
                        'author_name' => 'John Doe',
                        'category_id' => 1,
                        'category_name' => 'Web Development',
                        'blog_image' => '../uploads/blogs/image.jpg',
                        'created_at' => '2024-01-15 10:30:00',
                        'updated_at' => '2024-01-15 10:30:00'
                    ]
                ],
                'count' => 1
            ]
        ],
        'get_single_example' => [
            'url' => $api_base_url . 'blogs.php?id=1',
            'method' => 'GET',
            'response' => [
                'success' => true,
                'data' => [
                    'id' => 1,
                    'title' => 'How to Build a Modern Website',
                    'slug' => 'how-to-build-modern-website',
                    'content' => '<p>Full blog content here...</p>',
                    'author_name' => 'John Doe',
                    'category_id' => 1,
                    'category_name' => 'Web Development',
                    'blog_image' => '../uploads/blogs/image.jpg',
                    'canonical_url' => 'https://example.com/blog/1',
                    'seo_url' => 'https://example.com/blog/1',
                    'robots' => 'index, follow',
                    'status' => 'active',
                    'created_at' => '2024-01-15 10:30:00',
                    'updated_at' => '2024-01-15 10:30:00'
                ]
            ]
        ]
    ],
    [
        'name' => 'Clients API',
        'endpoint' => $api_base_url . 'clients.php',
        'methods' => ['GET'],
        'description' => 'Get all clients or a single client by ID',
        'get_all_example' => [
            'url' => $api_base_url . 'clients.php',
            'method' => 'GET',
            'response' => [
                'success' => true,
                'data' => [
                    [
                        'id' => 1,
                        'client_name' => 'ABC Company',
                        'website_url' => 'https://abc.com',
                        'email' => 'contact@abc.com',
                        'phone' => '+1234567890',
                        'logo' => '../uploads/clients/logo.png',
                        'status' => 'active',
                        'created_at' => '2024-01-15 10:30:00',
                        'updated_at' => '2024-01-15 10:30:00'
                    ]
                ],
                'count' => 1
            ]
        ],
        'get_single_example' => [
            'url' => $api_base_url . 'clients.php?id=1',
            'method' => 'GET',
            'response' => [
                'success' => true,
                'data' => [
                    'id' => 1,
                    'client_name' => 'ABC Company',
                    'website_url' => 'https://abc.com',
                    'email' => 'contact@abc.com',
                    'phone' => '+1234567890',
                    'logo' => '../uploads/clients/logo.png',
                    'status' => 'active',
                    'created_at' => '2024-01-15 10:30:00',
                    'updated_at' => '2024-01-15 10:30:00'
                ]
            ]
        ]
    ],
    [
        'name' => 'Testimonials API',
        'endpoint' => $api_base_url . 'testimonials.php',
        'methods' => ['GET'],
        'description' => 'Get all testimonials or a single testimonial by ID',
        'get_all_example' => [
            'url' => $api_base_url . 'testimonials.php',
            'method' => 'GET',
            'query_params' => [
                'limit' => '5 (optional)'
            ],
            'response' => [
                'success' => true,
                'data' => [
                    [
                        'id' => 1,
                        'client_name' => 'John Doe',
                        'description' => 'Great service! Very professional team.',
                        'rating' => 5,
                        'image' => '../uploads/testimonials/image.jpg',
                        'status' => 'active',
                        'created_at' => '2024-01-15 10:30:00',
                        'updated_at' => '2024-01-15 10:30:00'
                    ]
                ],
                'count' => 1
            ]
        ],
        'get_single_example' => [
            'url' => $api_base_url . 'testimonials.php?id=1',
            'method' => 'GET',
            'response' => [
                'success' => true,
                'data' => [
                    'id' => 1,
                    'client_name' => 'John Doe',
                    'description' => 'Great service! Very professional team.',
                    'rating' => 5,
                    'image' => '../uploads/testimonials/image.jpg',
                    'status' => 'active',
                    'created_at' => '2024-01-15 10:30:00',
                    'updated_at' => '2024-01-15 10:30:00'
                ]
            ]
        ]
    ]
];
?>

<div class="page-header">
    <div>
        <h2><i class="fas fa-code"></i> API Documentation</h2>
        <p>Complete list of available APIs with example responses</p>
    </div>
</div>

<div class="api-list-container">
    <?php foreach ($apis as $index => $api): ?>
    <div class="api-card">
        <div class="api-header">
            <div class="api-title-section">
                <h3><?php echo htmlspecialchars($api['name']); ?></h3>
                <div class="api-methods">
                    <?php foreach ($api['methods'] as $method): ?>
                    <span class="method-badge method-<?php echo strtolower($method); ?>"><?php echo $method; ?></span>
                    <?php endforeach; ?>
                </div>
            </div>
            <div class="api-endpoint">
                <code><?php echo htmlspecialchars($api['endpoint']); ?></code>
                <button class="copy-btn" onclick="copyToClipboard('<?php echo htmlspecialchars($api['endpoint']); ?>', this)">
                    <i class="fas fa-copy"></i>
                </button>
            </div>
        </div>
        
        <p class="api-description"><?php echo htmlspecialchars($api['description']); ?></p>
        
        <div class="api-examples">
            <?php if (isset($api['get_example'])): ?>
            <div class="example-section">
                <h4><i class="fas fa-arrow-right"></i> GET Request Example</h4>
                <div class="example-url">
                    <strong>URL:</strong>
                    <code><?php echo htmlspecialchars($api['get_example']['url']); ?></code>
                    <button class="copy-btn-small" onclick="copyToClipboard('<?php echo htmlspecialchars($api['get_example']['url']); ?>', this)">
                        <i class="fas fa-copy"></i>
                    </button>
                </div>
                <?php if (isset($api['get_example']['query_params'])): ?>
                <div class="query-params">
                    <strong>Query Parameters:</strong>
                    <ul>
                        <?php foreach ($api['get_example']['query_params'] as $param => $desc): ?>
                        <li><code><?php echo htmlspecialchars($param); ?></code> - <?php echo htmlspecialchars($desc); ?></li>
                        <?php endforeach; ?>
                    </ul>
                </div>
                <?php endif; ?>
                <div class="response-section">
                    <strong>Response:</strong>
                    <pre><code><?php echo htmlspecialchars(json_encode($api['get_example']['response'], JSON_PRETTY_PRINT)); ?></code></pre>
                    <button class="copy-btn-small" onclick="copyToClipboard(<?php echo htmlspecialchars(json_encode(json_encode($api['get_example']['response'], JSON_PRETTY_PRINT))); ?>, this)">
                        <i class="fas fa-copy"></i> Copy Response
                    </button>
                </div>
            </div>
            <?php endif; ?>
            
            <?php if (isset($api['get_all_example'])): ?>
            <div class="example-section">
                <h4><i class="fas fa-arrow-right"></i> GET All Request Example</h4>
                <div class="example-url">
                    <strong>URL:</strong>
                    <code><?php echo htmlspecialchars($api['get_all_example']['url']); ?></code>
                    <button class="copy-btn-small" onclick="copyToClipboard('<?php echo htmlspecialchars($api['get_all_example']['url']); ?>', this)">
                        <i class="fas fa-copy"></i>
                    </button>
                </div>
                <?php if (isset($api['get_all_example']['query_params'])): ?>
                <div class="query-params">
                    <strong>Query Parameters:</strong>
                    <ul>
                        <?php foreach ($api['get_all_example']['query_params'] as $param => $desc): ?>
                        <li><code><?php echo htmlspecialchars($param); ?></code> - <?php echo htmlspecialchars($desc); ?></li>
                        <?php endforeach; ?>
                    </ul>
                </div>
                <?php endif; ?>
                <div class="response-section">
                    <strong>Response:</strong>
                    <pre><code><?php echo htmlspecialchars(json_encode($api['get_all_example']['response'], JSON_PRETTY_PRINT)); ?></code></pre>
                    <button class="copy-btn-small" onclick="copyToClipboard(<?php echo htmlspecialchars(json_encode(json_encode($api['get_all_example']['response'], JSON_PRETTY_PRINT))); ?>, this)">
                        <i class="fas fa-copy"></i> Copy Response
                    </button>
                </div>
            </div>
            <?php endif; ?>
            
            <?php if (isset($api['get_single_example'])): ?>
            <div class="example-section">
                <h4><i class="fas fa-arrow-right"></i> GET Single Item Example</h4>
                <div class="example-url">
                    <strong>URL:</strong>
                    <code><?php echo htmlspecialchars($api['get_single_example']['url']); ?></code>
                    <button class="copy-btn-small" onclick="copyToClipboard('<?php echo htmlspecialchars($api['get_single_example']['url']); ?>', this)">
                        <i class="fas fa-copy"></i>
                    </button>
                </div>
                <div class="response-section">
                    <strong>Response:</strong>
                    <pre><code><?php echo htmlspecialchars(json_encode($api['get_single_example']['response'], JSON_PRETTY_PRINT)); ?></code></pre>
                    <button class="copy-btn-small" onclick="copyToClipboard(<?php echo htmlspecialchars(json_encode(json_encode($api['get_single_example']['response'], JSON_PRETTY_PRINT))); ?>, this)">
                        <i class="fas fa-copy"></i> Copy Response
                    </button>
                </div>
            </div>
            <?php endif; ?>
            
            <?php if (isset($api['post_example'])): ?>
            <div class="example-section">
                <h4><i class="fas fa-arrow-right"></i> POST Request Example</h4>
                <div class="example-url">
                    <strong>URL:</strong>
                    <code><?php echo htmlspecialchars($api['post_example']['url']); ?></code>
                    <button class="copy-btn-small" onclick="copyToClipboard('<?php echo htmlspecialchars($api['post_example']['url']); ?>', this)">
                        <i class="fas fa-copy"></i>
                    </button>
                </div>
                <div class="request-body">
                    <strong>Request Body:</strong>
                    <pre><code><?php echo htmlspecialchars(json_encode($api['post_example']['body'], JSON_PRETTY_PRINT)); ?></code></pre>
                    <button class="copy-btn-small" onclick="copyToClipboard(<?php echo htmlspecialchars(json_encode(json_encode($api['post_example']['body'], JSON_PRETTY_PRINT))); ?>, this)">
                        <i class="fas fa-copy"></i> Copy Body
                    </button>
                </div>
                <div class="response-section">
                    <strong>Response:</strong>
                    <pre><code><?php echo htmlspecialchars(json_encode($api['post_example']['response'], JSON_PRETTY_PRINT)); ?></code></pre>
                    <button class="copy-btn-small" onclick="copyToClipboard(<?php echo htmlspecialchars(json_encode(json_encode($api['post_example']['response'], JSON_PRETTY_PRINT))); ?>, this)">
                        <i class="fas fa-copy"></i> Copy Response
                    </button>
                </div>
            </div>
            <?php endif; ?>
        </div>
    </div>
    <?php endforeach; ?>
</div>

<script>
function copyToClipboard(text, button) {
    navigator.clipboard.writeText(text).then(function() {
        const icon = button.querySelector('i');
        const originalClass = icon.className;
        icon.className = 'fas fa-check';
        button.style.background = 'var(--success-color)';
        
        setTimeout(function() {
            icon.className = originalClass;
            button.style.background = '';
        }, 2000);
    });
}
</script>

<style>
.api-list-container {
    display: flex;
    flex-direction: column;
    gap: 30px;
}

.api-card {
    background: white;
    border-radius: 20px;
    padding: 35px;
    box-shadow: var(--shadow-md);
    border: 1px solid var(--border-color);
}

.api-header {
    display: flex;
    justify-content: space-between;
    align-items: flex-start;
    margin-bottom: 20px;
    padding-bottom: 20px;
    border-bottom: 2px solid var(--border-color);
    flex-wrap: wrap;
    gap: 20px;
}

.api-title-section {
    flex: 1;
}

.api-title-section h3 {
    font-size: 24px;
    font-weight: 800;
    color: var(--dark-text);
    margin-bottom: 12px;
    letter-spacing: -0.5px;
}

.api-methods {
    display: flex;
    gap: 8px;
    flex-wrap: wrap;
}

.method-badge {
    padding: 6px 14px;
    border-radius: 8px;
    font-size: 12px;
    font-weight: 700;
    letter-spacing: 0.5px;
    text-transform: uppercase;
}

.method-get {
    background: var(--gradient-success);
    color: white;
}

.method-post {
    background: var(--gradient-primary);
    color: white;
}

.method-put {
    background: var(--warning-color);
    color: white;
}

.method-delete {
    background: var(--gradient-danger);
    color: white;
}

.api-endpoint {
    display: flex;
    align-items: center;
    gap: 10px;
    background: var(--light-bg);
    padding: 12px 18px;
    border-radius: 10px;
    flex-wrap: wrap;
}

.api-endpoint code {
    font-size: 14px;
    color: var(--primary-color);
    font-weight: 600;
    font-family: 'Courier New', monospace;
}

.copy-btn, .copy-btn-small {
    background: var(--primary-color);
    color: white;
    border: none;
    padding: 8px 12px;
    border-radius: 8px;
    cursor: pointer;
    font-size: 12px;
    display: inline-flex;
    align-items: center;
    gap: 6px;
}

.copy-btn-small {
    padding: 6px 10px;
    font-size: 11px;
    margin-top: 8px;
}

.copy-btn:hover, .copy-btn-small:hover {
    background: var(--primary-dark);
}

.api-description {
    font-size: 15px;
    color: var(--secondary-color);
    margin-bottom: 25px;
    line-height: 1.6;
}

.api-examples {
    display: flex;
    flex-direction: column;
    gap: 30px;
}

.example-section {
    background: var(--light-bg);
    padding: 25px;
    border-radius: 12px;
    border: 1px solid var(--border-color);
}

.example-section h4 {
    font-size: 18px;
    font-weight: 700;
    color: var(--dark-text);
    margin-bottom: 18px;
    display: flex;
    align-items: center;
    gap: 10px;
}

.example-section h4 i {
    color: var(--primary-color);
}

.example-url {
    margin-bottom: 18px;
    display: flex;
    align-items: center;
    gap: 12px;
    flex-wrap: wrap;
}

.example-url strong {
    color: var(--dark-text);
    font-size: 14px;
}

.example-url code {
    background: white;
    padding: 8px 14px;
    border-radius: 8px;
    font-family: 'Courier New', monospace;
    font-size: 13px;
    color: var(--primary-color);
    border: 1px solid var(--border-color);
    flex: 1;
    min-width: 200px;
}

.query-params {
    margin-bottom: 18px;
    background: white;
    padding: 15px;
    border-radius: 8px;
}

.query-params strong {
    display: block;
    margin-bottom: 10px;
    color: var(--dark-text);
    font-size: 14px;
}

.query-params ul {
    list-style: none;
    padding: 0;
    margin: 0;
}

.query-params li {
    padding: 6px 0;
    font-size: 13px;
    color: var(--secondary-color);
}

.query-params code {
    background: var(--light-bg);
    padding: 2px 8px;
    border-radius: 4px;
    font-family: 'Courier New', monospace;
    color: var(--primary-color);
    font-weight: 600;
}

.request-body, .response-section {
    margin-top: 18px;
}

.request-body strong, .response-section strong {
    display: block;
    margin-bottom: 10px;
    color: var(--dark-text);
    font-size: 14px;
}

.request-body pre, .response-section pre {
    background: #1e293b;
    color: #e2e8f0;
    padding: 20px;
    border-radius: 10px;
    overflow-x: auto;
    margin: 0;
    position: relative;
}

.request-body pre code, .response-section pre code {
    font-family: 'Courier New', monospace;
    font-size: 13px;
    line-height: 1.6;
    color: #e2e8f0;
}

@media (max-width: 768px) {
    .api-card {
        padding: 25px 20px;
    }
    
    .api-header {
        flex-direction: column;
    }
    
    .api-endpoint {
        width: 100%;
    }
    
    .example-url {
        flex-direction: column;
        align-items: flex-start;
    }
    
    .example-url code {
        width: 100%;
    }
}
</style>

<?php require_once 'includes/footer.php'; ?>

