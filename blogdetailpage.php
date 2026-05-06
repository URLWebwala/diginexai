<?php
require_once('./constants/constants.php');
// Fix malformed URLs and validate slug
$request_uri = $_SERVER['REQUEST_URI'] ?? '';
$hasFilePath = strpos($request_uri, '/C:/') !== false ||
    strpos($request_uri, '/xampp/') !== false ||
    strpos($request_uri, '/htdocs/') !== false ||
    strpos($request_uri, '/public_html/public_html/') !== false;

if ($hasFilePath) {
    $slug = $_GET['slug'] ?? '';
    if (empty($slug) && preg_match('/[?&]slug=([^&]+)/', $request_uri, $matches)) {
        $slug = urldecode($matches[1]);
    }

    if (!empty($slug) && $slug !== '0') {
        header('Location: /public_html/blog/' . urlencode($slug), true, 301);
    } else {
        header('Location: /public_html/blog', true, 301);
    }
    exit();
}

$slug = $_GET['slug'] ?? '';
$blog_id = $_GET['id'] ?? null;

// If slug is missing but ID is provided, fetch slug from database
if ((empty($slug) || $slug === '0') && $blog_id) {
    require_once __DIR__ . '/admin/config/database.php';

    // Helper function to generate slug from title
    function generateSlugFromTitle($title)
    {
        if (empty($title))
            return '';
        $slug = strtolower(trim($title));
        $slug = preg_replace('/[^a-z0-9]+/', '-', $slug);
        $slug = preg_replace('/(^-|-$)/', '', $slug);
        return substr($slug, 0, 100);
    }

    $conn = getDBConnection();
    $stmt = $conn->prepare("SELECT slug, title FROM blogs WHERE id = ? AND status = 'active'");
    $blog_id_int = intval($blog_id);
    $stmt->bind_param("i", $blog_id_int);
    $stmt->execute();
    $result = $stmt->get_result();

    if ($result->num_rows > 0) {
        $blog = $result->fetch_assoc();
        $slug = $blog['slug'];

        // If slug is '0' or empty, generate from title
        if (empty($slug) || $slug === '0') {
            if (!empty($blog['title'])) {
                $slug = generateSlugFromTitle($blog['title']);
                // Update the slug in database for future use
                $update_stmt = $conn->prepare("UPDATE blogs SET slug = ? WHERE id = ?");
                $update_stmt->bind_param("si", $slug, $blog_id_int);
                $update_stmt->execute();
                $update_stmt->close();
            } else {
                // No title, redirect to blog listing
                header('Location: /public_html/blog', true, 301);
                exit();
            }
        }

        // Redirect to clean URL with slug
        header('Location: /public_html/blog/' . urlencode($slug), true, 301);
        exit();
    } else {
        // Blog not found, redirect to blog listing
        header('Location: /public_html/blog', true, 301);
        exit();
    }
    $stmt->close();
    closeDBConnection($conn);
}

if (empty($slug) || $slug === '0') {
    header('Location: /public_html/blog', true, 301);
    exit();
}

$pageTitle = 'Blog Detail';
$description = 'Read our latest blog post';
include('header.php');
?>

<main class="dx-home">
    <section class="dx-about-hero">
        <div class="container">
            <div class="dx-about-hero-grid" style="min-height: 400px;">
                <div class="dx-about-hero-copy">
                    <span class="dx-eyebrow"><i class="fa-regular fa-sparkles"></i> Article Details</span>
                    <h1 id="blog-title" style="margin: 15px 0;">Loading...</h1>
                    <nav aria-label="breadcrumb" class="mt-4">
                        <ol class="breadcrumb mb-0" style="background: transparent; padding: 0;">
                            <li class="breadcrumb-item"><a href="index.php"
                                    style="color: rgba(255,255,255,0.7); text-decoration: none;">Home</a></li>
                            <li class="breadcrumb-item"><a href="blog"
                                    style="color: rgba(255,255,255,0.7); text-decoration: none;">Blog</a></li>
                            <li class="breadcrumb-item active" id="breadcrumb-title" aria-current="page"
                                style="color: #fff; font-weight: 700;">Detail</li>
                        </ol>
                    </nav>
                </div>
                <div class="dx-about-hero-media">
                    <img src="assets/img/hero/hero-2.jpg" alt="Our services overview">
                    <div class="dx-about-stat">
                        <strong>100%</strong>
                        <span>Performance driven execution for every project.</span>
                    </div>
                </div>
            </div>
        </div>
    </section>

    <section class="blog-detail-section fix section-padding" style="background: #f5f5f5; padding: 60px 0;">
        <div class="container">
            <div class="row g-4">
                <div class="col-lg-8">
                    <div id="blog-content" style="min-height: 400px;">
                        <div style="text-align: center; padding: 60px 20px;">
                            <div class="spinner-border text-primary" role="status"></div>
                            <p style="margin-top: 20px;">Loading blog post...</p>
                        </div>
                    </div>
                </div>
                <div class="col-lg-4">
                    <div class="blog-sidebar">
                        <div class="sidebar-widget" id="related-posts-widget" style="display: none;">
                            <h3 class="sidebar-title">Related Posts</h3>
                            <div id="related-posts">
                                <p>Loading...</p>
                            </div>
                        </div>
                        <div class="sidebar-widget help-widget">
                            <div class="help-box">
                                <h3>Need Help?</h3>
                                <p>Contact us for any queries or project discussions.</p>
                                <a href="contact.php" class="help-btn">
                                    Get In Touch
                                    <i class="fa-solid fa-arrow-right"></i>
                                </a>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>

</main>
<?php include('footer.php'); ?>

<script>
    (function () {
        var url = window.location.href;
        if (url.indexOf('/C:/') !== -1 || url.indexOf('/xampp/') !== -1 || url.indexOf('/htdocs/') !== -1 || url.indexOf('/public_html/public_html/') !== -1) {
            var slugMatch = url.match(/[?&]slug=([^&]+)/);
            var slug = slugMatch ? decodeURIComponent(slugMatch[1]) : '';
            // Detect if we're in local development or production
            const isLocal = window.location.hostname === 'localhost' ||
                window.location.hostname === '127.0.0.1' ||
                window.location.hostname.includes('xampp');
            const basePath = isLocal ? '/public_html' : '';

            if (slug && slug !== '0' && slug !== '') {
                window.location.replace(basePath + '/blog/' + encodeURIComponent(slug));
            } else {
                window.location.replace(basePath + '/blog');
            }
            return;
        }
    })();

    document.addEventListener('DOMContentLoaded', function () {
        const slug = '<?php echo htmlspecialchars($slug, ENT_QUOTES, 'UTF-8'); ?>';
        const blogId = '<?php echo isset($_GET['id']) ? intval($_GET['id']) : ''; ?>';

        // If slug is missing but ID exists, we should have been redirected by PHP
        // But as a backup, redirect here too
        if ((!slug || slug === '0') && blogId) {
            // Fetch slug from API using ID
            fetch('<?php echo defined("API_BASE_URL") ? API_BASE_URL : "https://api.diginexai.com"; ?>/blogs.php?id=' + blogId)
                .then(response => response.json())
                .then(data => {
                    // Detect if we're in local development or production
                    const isLocal = window.location.hostname === 'localhost' ||
                        window.location.hostname === '127.0.0.1' ||
                        window.location.hostname.includes('xampp');
                    const basePath = isLocal ? '/public_html' : '';

                    if (data.success && data.data && data.data.slug) {
                        window.location.replace(basePath + '/blog/' + encodeURIComponent(data.data.slug));
                    } else {
                        window.location.replace(basePath + '/blog');
                    }
                })
                .catch(() => {
                    const isLocal = window.location.hostname === 'localhost' ||
                        window.location.hostname === '127.0.0.1' ||
                        window.location.hostname.includes('xampp');
                    const basePath = isLocal ? '/public_html' : '';
                    window.location.replace(basePath + '/blog');
                });
            return;
        }

        if (!slug || slug === '0') {
            // Detect if we're in local development or production
            const isLocal = window.location.hostname === 'localhost' ||
                window.location.hostname === '127.0.0.1' ||
                window.location.hostname.includes('xampp');
            const basePath = isLocal ? '/public_html' : '';
            window.location.href = basePath + '/blog';
            return;
        }

        function getApiUrl(path) {
            const apiBaseUrl = '<?php echo defined("API_BASE_URL") ? API_BASE_URL : "https://api.diginexai.com"; ?>';
            const cleanPath = path.startsWith('/') ? path.substring(1) : path;
            return apiBaseUrl.endsWith('/') ? apiBaseUrl + cleanPath : apiBaseUrl + '/' + cleanPath;
        }

        function getBlogUrl(slug) {
            // Detect if we're in local development or production
            const isLocal = window.location.hostname === 'localhost' ||
                window.location.hostname === '127.0.0.1' ||
                window.location.hostname.includes('xampp');
            const basePath = isLocal ? '/public_html' : '';
            return basePath + '/blog/' + encodeURIComponent(slug);
        }

        function getCurrentUrl() {
            return window.location.origin + window.location.pathname;
        }

        function showError(message) {
            document.getElementById('blog-content').innerHTML = `
            <div class="blog-detail-card">
                <div style="text-align: center; padding: 60px 20px;">
                    <h2>${message}</h2>
                    <p>Unable to load the blog post. Please try again later.</p>
                    <a href="blog" class="theme-btn" style="margin-top: 20px; display: inline-block;">Back to Blogs</a>
                </div>
            </div>
        `;
        }

        fetch(getApiUrl('blogs.php?slug=' + encodeURIComponent(slug)))
            .then(response => {
                if (!response.ok) throw new Error('Network response was not ok: ' + response.status);
                const contentType = response.headers.get('content-type');
                if (!contentType || !contentType.includes('application/json')) {
                    return response.text().then(() => {
                        throw new Error('Server returned non-JSON response');
                    });
                }
                return response.json();
            })
            .then(data => {
                if (data.success && data.data) {
                    const blog = data.data;
                    const date = new Date(blog.created_at);
                    const formattedDate = date.toLocaleDateString('en-US', { year: 'numeric', month: 'long', day: 'numeric' });
                    const shortDate = date.toLocaleDateString('en-US', { month: 'short', day: 'numeric', year: 'numeric' });
                    const blogImage = blog.blog_image || 'assets/img/blog/blogThumb1_1.jpg';
                    const categoryName = blog.category_name || 'Uncategorized';
                    const currentUrl = getCurrentUrl();

                    document.title = blog.title + ' | DiginexAI';
                    document.getElementById('blog-title').textContent = blog.title;
                    document.getElementById('breadcrumb-title').textContent = blog.title;

                    document.getElementById('blog-content').innerHTML = `
                    <div class="blog-detail-card">
                        <div class="blog-header-image">
                            <img src="${blogImage}" alt="${blog.title.replace(/"/g, '&quot;')}" onerror="this.src='assets/img/blog/blogThumb1_1.jpg'">
                        </div>
                        <div class="blog-meta-bar">
                            <span class="blog-category-badge">${categoryName}</span>
                            <span class="blog-meta-item">
                                <i class="fa-regular fa-calendar-days"></i>
                                ${formattedDate}
                            </span>
                            <span class="blog-meta-item">
                                <i class="fa-regular fa-user"></i>
                                ${blog.author_name || 'Admin'}
                            </span>
                            <span class="blog-meta-item">
                                <i class="fa-regular fa-eye"></i>
                                <span id="blog-views">0</span> Views
                            </span>
                        </div>
                        <h1 class="blog-detail-title">${blog.title}</h1>
                        <div class="blog-detail-body">
                            ${blog.content}
                        </div>
                        <div class="blog-share-section">
                            <hr style="margin: 40px 0 20px 0; border: none; border-top: 1px solid #e0e0e0;">
                            <p style="text-align: center; font-weight: 600; margin-bottom: 20px; color: #333;">Share this post:</p>
                            <div class="social-share-buttons">
                                <a href="https://www.facebook.com/sharer/sharer.php?u=${encodeURIComponent(currentUrl)}" target="_blank" class="share-btn facebook" title="Share on Facebook">
                                    <i class="fab fa-facebook-f"></i>
                                </a>
                                <a href="https://twitter.com/intent/tweet?url=${encodeURIComponent(currentUrl)}&text=${encodeURIComponent(blog.title)}" target="_blank" class="share-btn twitter" title="Share on Twitter">
                                    <i class="fab fa-twitter"></i>
                                </a>
                                <a href="https://www.linkedin.com/sharing/share-offsite/?url=${encodeURIComponent(currentUrl)}" target="_blank" class="share-btn linkedin" title="Share on LinkedIn">
                                    <i class="fab fa-linkedin-in"></i>
                                </a>
                                <a href="https://wa.me/?text=${encodeURIComponent(blog.title + ' ' + currentUrl)}" target="_blank" class="share-btn whatsapp" title="Share on WhatsApp">
                                    <i class="fab fa-whatsapp"></i>
                                </a>
                            </div>
                        </div>
                    </div>
                `;

                    loadRelatedPosts(blog.category_id, blog.id);
                } else {
                    showError('Blog Not Found');
                }
            })
            .catch(error => {
                console.error('Error fetching blog:', error);
                showError('Error Loading Blog');
            });

        function loadRelatedPosts(categoryId, currentBlogId) {
            fetch(getApiUrl('blogs.php?limit=3'))
                .then(response => response.json())
                .then(data => {
                    const container = document.getElementById('related-posts');
                    const widget = document.getElementById('related-posts-widget');

                    if (data.success && data.data && data.data.length > 0) {
                        let relatedBlogs = data.data.filter(blog => blog.id !== currentBlogId);
                        if (categoryId) {
                            relatedBlogs = relatedBlogs.filter(blog => blog.category_id === categoryId);
                        }
                        relatedBlogs = relatedBlogs.slice(0, 3);

                        if (relatedBlogs.length > 0) {
                            let html = '';
                            relatedBlogs.forEach(blog => {
                                const date = new Date(blog.created_at);
                                const formattedDate = date.toLocaleDateString('en-US', { month: 'short', day: 'numeric', year: 'numeric' });
                                const blogImage = blog.blog_image || 'assets/img/blog/blogThumb1_1.jpg';
                                html += `
                                <div class="related-post-item">
                                    <div class="related-post-thumb">
                                        <img src="${blogImage}" alt="${blog.title.replace(/"/g, '&quot;')}" onerror="this.src='assets/img/blog/blogThumb1_1.jpg'">
                                    </div>
                                    <div class="related-post-content">
                                        <span class="related-post-date">${formattedDate}</span>
                                        <h4><a href="${getBlogUrl(blog.slug)}">${blog.title}</a></h4>
                                    </div>
                                </div>
                            `;
                            });
                            container.innerHTML = html;
                            widget.style.display = 'block';
                        } else {
                            widget.style.display = 'none';
                        }
                    } else {
                        widget.style.display = 'none';
                    }
                })
                .catch(error => {
                    console.error('Error fetching related posts:', error);
                    document.getElementById('related-posts-widget').style.display = 'none';
                });
        }
    });
</script>

<style>
    .blog-detail-section {
        background: #f8fbff;
        padding: 80px 0;
    }

    .blog-detail-card {
        background: #fff;
        border-radius: 20px;
        box-shadow: 0 20px 50px rgba(0, 0, 0, 0.05);
        overflow: hidden;
        border: 1px solid #eef2ff;
    }

    .blog-header-image {
        width: 100%;
        height: 500px;
        overflow: hidden;
        background: #f0f0f0;
    }

    .blog-header-image img {
        width: 100%;
        height: 100%;
        object-fit: cover;
    }

    .blog-meta-bar {
        display: flex;
        align-items: center;
        flex-wrap: wrap;
        gap: 25px;
        padding: 25px 40px;
        border-bottom: 1px solid #f1f5f9;
        background: #fff;
    }

    .blog-category-badge {
        background: #2563eb;
        color: #fff;
        padding: 8px 20px;
        border-radius: 50px;
        font-size: 12px;
        font-weight: 800;
        text-transform: uppercase;
        letter-spacing: 1px;
    }

    .blog-meta-item {
        display: flex;
        align-items: center;
        gap: 10px;
        color: #64748b;
        font-size: 14px;
        font-weight: 600;
    }

    .blog-meta-item i {
        color: #10b981;
        font-size: 16px;
    }

    .blog-detail-title {
        font-size: clamp(32px, 4vw, 48px);
        font-weight: 800;
        color: #1e293b;
        margin: 40px 40px 24px;
        line-height: 1.2;
    }

    .blog-detail-body {
        padding: 0 40px 40px;
        font-size: 18px;
        line-height: 1.8;
        color: #475569;
    }

    .blog-detail-body h2,
    .blog-detail-body h3 {
        color: #1e293b;
        margin-top: 40px;
        margin-bottom: 20px;
        font-weight: 700;
    }

    .blog-detail-body p {
        margin-bottom: 24px;
    }

    .blog-detail-body blockquote {
        border-left: 5px solid #2563eb;
        padding: 20px 30px;
        background: #f8faff;
        margin: 30px 0;
        font-style: italic;
        border-radius: 0 10px 10px 0;
    }

    .blog-share-section {
        padding: 0 40px 40px;
    }

    .social-share-buttons {
        display: flex;
        gap: 12px;
    }

    .share-btn {
        width: 44px;
        height: 44px;
        border-radius: 12px;
        display: flex;
        align-items: center;
        justify-content: center;
        color: #fff;
        font-size: 18px;
        text-decoration: none;
        transition: all 0.3s ease;
    }

    .share-btn:hover {
        transform: translateY(-4px);
        box-shadow: 0 8px 20px rgba(0, 0, 0, 0.1);
        color: #fff;
    }

    .share-btn.facebook {
        background: #1877f2;
    }

    .share-btn.twitter {
        background: #1da1f2;
    }

    .share-btn.linkedin {
        background: #0077b5;
    }

    .share-btn.whatsapp {
        background: #25d366;
    }

    .blog-sidebar {
        position: sticky;
        top: 100px;
    }

    .sidebar-widget {
        background: #fff;
        border-radius: 20px;
        box-shadow: 0 10px 30px rgba(0, 0, 0, 0.03);
        padding: 30px;
        margin-bottom: 30px;
        border: 1px solid #f1f5f9;
    }

    .sidebar-title {
        font-size: 20px;
        font-weight: 800;
        color: #1e293b;
        margin-bottom: 25px;
        padding-bottom: 15px;
        border-bottom: 2px solid #eef2ff;
        position: relative;
    }

    .sidebar-title::after {
        content: '';
        position: absolute;
        bottom: -2px;
        left: 0;
        width: 50px;
        height: 2px;
        background: #2563eb;
    }

    .related-post-item {
        display: flex;
        gap: 15px;
        padding: 15px 0;
        border-bottom: 1px solid #f1f5f9;
    }

    .related-post-item:last-child {
        border-bottom: none;
    }

    .related-post-thumb {
        width: 90px;
        height: 70px;
        flex-shrink: 0;
        border-radius: 10px;
        overflow: hidden;
    }

    .related-post-thumb img {
        width: 100%;
        height: 100%;
        object-fit: cover;
    }

    .related-post-content h4 {
        font-size: 15px;
        font-weight: 700;
        margin: 0;
        line-height: 1.4;
    }

    .related-post-content h4 a {
        color: #1e293b;
        text-decoration: none;
        transition: color 0.3s ease;
    }

    .related-post-content h4 a:hover {
        color: #2563eb;
    }

    .help-widget {
        background: #123f8c;
        color: #fff;
    }

    .help-box h3 {
        color: #fff;
        font-size: 24px;
        font-weight: 800;
    }

    .help-btn {
        background: #fff;
        color: #123f8c;
        padding: 14px 28px;
        border-radius: 10px;
        font-weight: 700;
        display: inline-flex;
        align-items: center;
        gap: 10px;
        text-decoration: none;
        margin-top: 10px;
    }

    .help-btn:hover {
        background: #10b981;
        color: #fff;
        transform: translateX(5px);
    }

    @media (max-width: 991px) {
        .blog-header-image {
            height: 350px;
        }

        .blog-detail-title {
            margin: 30px 25px 20px;
        }

        .blog-detail-body {
            padding: 0 25px 30px;
        }

        .blog-meta-bar {
            padding: 20px 25px;
        }
    }
</style>