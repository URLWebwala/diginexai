<?php
// Clean URL if file path detected or malformed URL
$request_uri = $_SERVER['REQUEST_URI'] ?? '';
$script_name = $_SERVER['SCRIPT_NAME'] ?? '';
$request_path = parse_url($request_uri, PHP_URL_PATH);

// Check if URL contains file paths or is malformed
$needs_redirect = false;
if (preg_match('#/C:/|/xampp/|/htdocs/#i', $request_uri) || 
    preg_match('#/public_html/public_html/#', $request_uri) ||
    (preg_match('#/admin/blogs/?$#', $request_path) && !preg_match('#\.php$#', $request_path) && basename($script_name) !== 'blogs.php')) {
    $needs_redirect = true;
}

if ($needs_redirect) {
    $protocol = (!empty($_SERVER['HTTPS']) && $_SERVER['HTTPS'] !== 'off') ? 'https' : 'http';
    $host = $_SERVER['HTTP_HOST'] ?? 'localhost';
    $query_string = isset($_SERVER['QUERY_STRING']) && !empty($_SERVER['QUERY_STRING']) ? '?' . $_SERVER['QUERY_STRING'] : '';
    $clean_url = $protocol . '://' . $host . '/public_html/admin/blogs.php' . $query_string;
    header('HTTP/1.1 301 Moved Permanently');
    header('Location: ' . $clean_url);
    exit();
}

// Handle delete and status toggle BEFORE any output
require_once __DIR__ . '/config/database.php';

if (isset($_GET['delete'])) {
    $conn = getDBConnection();
    $id = intval($_GET['delete']);
    
    // Get blog image filename before deleting
    $stmt = $conn->prepare("SELECT blog_image FROM blogs WHERE id = ?");
    $stmt->bind_param("i", $id);
    $stmt->execute();
    $result = $stmt->get_result();
    $blog = $result->fetch_assoc();
    $stmt->close();
    
    // Delete the blog record
    $stmt = $conn->prepare("DELETE FROM blogs WHERE id = ?");
    $stmt->bind_param("i", $id);
    $stmt->execute();
    $stmt->close();
    
    // Delete associated image file if it exists
    if ($blog && !empty($blog['blog_image'])) {
        $upload_dir = __DIR__ . '/../uploads/blogs/';
        $image_path = $upload_dir . $blog['blog_image'];
        if (file_exists($image_path)) {
            unlink($image_path);
        }
    }
    
    closeDBConnection($conn);
    header('HTTP/1.1 303 See Other');
    header('Location: blogs.php?deleted=1');
    exit();
}

if (isset($_GET['toggle_status'])) {
    $conn = getDBConnection();
    $id = intval($_GET['toggle_status']);
    $stmt = $conn->prepare("UPDATE blogs SET status = IF(status = 'active', 'inactive', 'active') WHERE id = ?");
    $stmt->bind_param("i", $id);
    $stmt->execute();
    $stmt->close();
    closeDBConnection($conn);
    header('HTTP/1.1 303 See Other');
    header('Location: blogs.php');
    exit();
}

$page_title = 'Manage Blogs';
require_once 'includes/header.php';
require_once __DIR__ . '/config/database.php';

$conn = getDBConnection();
$search = $_GET['search'] ?? '';

// Get blogs with search (no pagination - show all)
$where = '';
$search_param = '';
if (!empty($search)) {
    $search_param = "%$search%";
    $where = "WHERE b.title LIKE ?";
    $query = "SELECT b.*, c.name as category_name FROM blogs b 
              LEFT JOIN blog_categories c ON b.category_id = c.id 
              $where ORDER BY b.created_at DESC";
    $stmt = $conn->prepare($query);
    $stmt->bind_param("s", $search_param);
    $stmt->execute();
    $blogs = $stmt->get_result();
} else {
    $query = "SELECT b.*, c.name as category_name FROM blogs b 
              LEFT JOIN blog_categories c ON b.category_id = c.id 
              ORDER BY b.created_at DESC";
    $blogs = $conn->query($query);
}

// Get categories for dropdown
$categories = $conn->query("SELECT * FROM blog_categories ORDER BY name");

closeDBConnection($conn);
?>

<div class="page-header">
    <div>
        <h2>Manage Blogs</h2>
        <p>Create, edit, and manage all your blog posts.</p>
    </div>
    <a href="blog_add.php" class="btn btn-primary">
        <i class="fas fa-plus"></i> Add New Post
    </a>
</div>

<?php if (isset($_GET['deleted'])): ?>
<div class="alert alert-success">
    <i class="fas fa-check-circle"></i> Blog deleted successfully!
</div>
<?php endif; ?>

<?php if (isset($_GET['success'])): ?>
<div class="alert alert-success">
    <i class="fas fa-check-circle"></i> Blog saved successfully!
</div>
<?php endif; ?>

<?php if (isset($_GET['error'])): ?>
<div class="alert alert-error">
    <i class="fas fa-exclamation-circle"></i> <?php echo htmlspecialchars($_GET['error']); ?>
</div>
<?php endif; ?>

<div class="search-bar">
    <input 
        type="text" 
        id="searchInput" 
        placeholder="Search by title..." 
        value="<?php echo htmlspecialchars($search); ?>"
        onkeyup="handleSearch(event)"
    >
</div>

<div class="card">
    <div class="table-responsive">
        <table class="data-table">
            <thead>
                <tr>
                    <th>Image</th>
                    <th>Title</th>
                    <th>Category</th>
                    <th>Author</th>
                    <th>Status</th>
                    <th>Actions</th>
                </tr>
            </thead>
            <tbody>
                <?php while ($blog = $blogs->fetch_assoc()): ?>
                <tr>
                    <td>
                        <?php if ($blog['blog_image']): ?>
                        <img src="<?php echo UPLOADS_URL; ?>blogs/<?php echo htmlspecialchars($blog['blog_image']); ?>" 
                             alt="Blog" class="table-image">
                        <?php else: ?>
                        <div class="table-image-placeholder">
                            <i class="fas fa-image"></i>
                        </div>
                        <?php endif; ?>
                    </td>
                    <td><?php echo htmlspecialchars($blog['title']); ?></td>
                    <td><?php echo htmlspecialchars($blog['category_name'] ?? 'N/A'); ?></td>
                    <td><?php echo htmlspecialchars($blog['author_name']); ?></td>
                    <td>
                        <span class="badge badge-<?php echo $blog['status'] == 'active' ? 'success' : 'danger'; ?>">
                            <?php echo ucfirst($blog['status']); ?>
                        </span>
                    </td>
                    <td>
                        <div class="action-buttons">
                            <a href="blog_add.php?id=<?php echo $blog['id']; ?>" 
                               class="action-icon-btn edit-btn" 
                               title="Edit Blog">
                                <i class="fas fa-edit"></i>
                            </a>
                            <button class="action-icon-btn delete-btn" 
                                    onclick="confirmDelete(<?php echo $blog['id']; ?>)"
                                    title="Delete Blog">
                                <i class="fas fa-trash"></i>
                            </button>
                        </div>
                    </td>
                </tr>
                <?php endwhile; ?>
            </tbody>
        </table>
    </div>
</div>

<!-- Add/Edit Blog Modal -->
<div id="addBlogModal" class="modal">
    <div class="modal-content modal-large">
        <div class="modal-header">
            <h3 id="modalTitle">Add Blog</h3>
            <button class="modal-close" onclick="closeModal('addBlogModal')">
                <i class="fas fa-times"></i>
            </button>
        </div>
        <form id="blogForm" action="actions/blog_action.php" method="POST" enctype="multipart/form-data">
            <input type="hidden" name="id" id="blog_id">
            <input type="hidden" name="action" id="blog_action" value="add">
            
            <div class="form-group">
                <label for="title">Title</label>
                <input type="text" id="title" name="title" placeholder="Enter blog title" required>
            </div>
            
            <div class="form-group">
                <label for="category_id">Category</label>
                <select id="category_id" name="category_id" required>
                    <option value="">Select Category</option>
                    <?php while ($cat = $categories->fetch_assoc()): ?>
                    <option value="<?php echo $cat['id']; ?>"><?php echo htmlspecialchars($cat['name']); ?></option>
                    <?php endwhile; ?>
                </select>
            </div>
            
            <div class="form-group">
                <label for="content">Content</label>
                <textarea id="content" name="content" rows="10" placeholder="Start writing..." required></textarea>
            </div>
            
            <div class="form-group">
                <label for="author_name">Author Name</label>
                <input type="text" id="author_name" name="author_name" placeholder="Enter author name" required>
            </div>
            
            <div class="form-group">
                <label for="slug">SEO URL (Slug)</label>
                <input type="text" id="slug" name="slug" placeholder="Enter SEO-friendly URL">
                <small>Leave empty to auto-generate from title</small>
            </div>
            
            <div class="form-group">
                <label for="blog_image">Blog Image</label>
                <div class="file-upload">
                    <input type="file" id="blog_image" name="blog_image" accept="image/*" onchange="previewImage(this, 'blogImagePreview')">
                    <label for="blog_image" class="file-label">
                        <i class="fas fa-upload"></i> Choose File
                    </label>
                    <span class="file-name" id="blogImageFileName">No file chosen</span>
                </div>
                <div id="blogImagePreview" class="image-preview"></div>
                <div id="currentBlogImage" class="current-image"></div>
            </div>
            
            <div class="form-group">
                <label for="canonical_url">Canonical URL</label>
                <input type="url" id="canonical_url" name="canonical_url" placeholder="Enter canonical URL">
            </div>
            
            <div class="form-group">
                <label for="seo_url">SEO URL (Slug)</label>
                <input type="text" id="seo_url" name="seo_url" placeholder="Enter SEO URL">
            </div>
            
            <div class="form-group">
                <label for="robots">Robots</label>
                <input type="text" id="robots" name="robots" placeholder="e.g., index, follow">
            </div>
            
            <div class="form-group">
                <label for="status">Status</label>
                <select id="status" name="status" required>
                    <option value="active">Active</option>
                    <option value="inactive">Inactive</option>
                </select>
            </div>
            
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" onclick="closeModal('addBlogModal')">Cancel</button>
                <button type="submit" class="btn btn-primary">
                    <i class="fas fa-save"></i> Save Changes
                </button>
            </div>
        </form>
    </div>
</div>

<script>
let contentEditor = null;

function handleSearch(e) {
    if (e.key === 'Enter') {
        const search = document.getElementById('searchInput').value;
        // Use simple relative path
        window.location.href = 'blogs.php?search=' + encodeURIComponent(search);
    }
}

function confirmEdit(blog) {
    // Redirect to edit page instead of opening modal
    window.location.href = 'blog_add.php?id=' + blog.id;
}

// Make confirmDelete available globally
window.confirmDelete = function(id) {
    // Ensure showConfirm is available (from admin.js)
    if (typeof showConfirm === 'function') {
        showConfirm(
            'Are you sure you want to delete this blog? This action cannot be undone.',
            function() {
                window.location.href = 'blogs.php?delete=' + id;
            },
            'delete'
        );
    } else {
        // Wait for admin.js to load
        const checkShowConfirm = setInterval(function() {
            if (typeof showConfirm === 'function') {
                clearInterval(checkShowConfirm);
                showConfirm(
                    'Are you sure you want to delete this blog? This action cannot be undone.',
                    function() {
                        window.location.href = 'blogs.php?delete=' + id;
                    },
                    'delete'
                );
            }
        }, 50);
        
        // Fallback after 2 seconds if showConfirm still not available
        setTimeout(function() {
            clearInterval(checkShowConfirm);
            if (typeof showConfirm !== 'function') {
                // Use native confirm as fallback
                if (confirm('Are you sure you want to delete this blog? This action cannot be undone.')) {
                    window.location.href = 'blogs.php?delete=' + id;
                }
            }
        }, 2000);
    }
};

function editBlog(blog) {
    document.getElementById('modalTitle').textContent = 'Edit Blog';
    document.getElementById('blog_id').value = blog.id;
    document.getElementById('blog_action').value = 'edit';
    document.getElementById('title').value = blog.title;
    document.getElementById('category_id').value = blog.category_id;
    document.getElementById('author_name').value = blog.author_name;
    document.getElementById('slug').value = blog.slug;
    document.getElementById('canonical_url').value = blog.canonical_url || '';
    document.getElementById('seo_url').value = blog.seo_url || '';
    document.getElementById('robots').value = blog.robots || '';
    document.getElementById('status').value = blog.status;
    
    if (blog.blog_image) {
        document.getElementById('currentBlogImage').innerHTML = 
            '<img src="<?php echo UPLOADS_URL; ?>blogs/' + blog.blog_image + '" alt="Current Image" style="max-width: 200px; margin-top: 10px;">';
    }
    
    openModal('addBlogModal');
    
    // Set content in editor after modal opens
    setTimeout(function() {
        if (contentEditor) {
            contentEditor.value = blog.content || '';
        } else {
            // Initialize editor if not already initialized
            initJoditEditor();
            setTimeout(function() {
                if (contentEditor) {
                    contentEditor.value = blog.content || '';
                }
            }, 300);
        }
    }, 200);
}

// Initialize Jodit Editor
function initJoditEditor() {
    const textarea = document.getElementById('content');
    if (textarea && typeof Jodit !== 'undefined') {
        // Destroy existing editor if any
        if (contentEditor) {
            contentEditor.destruct();
            contentEditor = null;
        }
        
        contentEditor = new Jodit(textarea, {
            height: 500,
            placeholder: 'Start writing...',
            toolbar: true,
            toolbarButtonSize: 'large',
            buttons: [
                'bold', 'italic', 'underline', 'strikethrough',
                '|',
                'ul', 'ol',
                '|',
                'outdent', 'indent',
                '|',
                'font', 'fontsize', 'paragraph',
                '|',
                'image', 'link', 'table',
                '|',
                'align',
                '|',
                'undo', 'redo',
                '|',
                'hr', 'eraser', 'copyformat',
                '|',
                'fullsize', 'selectall', 'print',
                '|',
                'source', 'preview'
            ],
            showCharsCounter: true,
            showWordsCounter: true,
            showXPathInStatusbar: true,
            askBeforePasteHTML: true,
            askBeforePasteFromWord: true,
            defaultActionOnPaste: 'insert_as_html',
            uploader: {
                insertImageAsBase64URI: true
            },
            style: {
                background: '#ffffff',
                color: '#1e293b'
            }
        });
    }
}

// Override openModal for blog modal to initialize editor (after admin.js loads)
document.addEventListener('DOMContentLoaded', function() {
    // Wait for admin.js to load
    function waitForAdminJS(callback) {
        if (typeof window.openModal === 'function') {
            callback();
        } else {
            setTimeout(function() {
                waitForAdminJS(callback);
            }, 50);
        }
    }
    
    waitForAdminJS(function() {
        const originalOpenModal = window.openModal;
        window.openModal = function(modalId) {
            originalOpenModal(modalId);
            if (modalId === 'addBlogModal') {
                // Wait a bit for modal to fully render
                setTimeout(function() {
                    if (typeof Jodit !== 'undefined') {
                        initJoditEditor();
                    } else {
                        // Wait for Jodit to load
                        const checkJodit = setInterval(function() {
                            if (typeof Jodit !== 'undefined') {
                                clearInterval(checkJodit);
                                initJoditEditor();
                            }
                        }, 100);
                        // Stop checking after 5 seconds
                        setTimeout(function() {
                            clearInterval(checkJodit);
                        }, 5000);
                    }
                }, 200);
            }
        };

        // Override closeModal to destroy editor
        const originalCloseModal = window.closeModal;
        window.closeModal = function(modalId) {
            if (modalId === 'addBlogModal' && contentEditor) {
                contentEditor.destruct();
                contentEditor = null;
            }
            originalCloseModal(modalId);
        };
    });
});

// Sync editor content to textarea before form submit
document.addEventListener('DOMContentLoaded', function() {
    const blogForm = document.getElementById('blogForm');
    if (blogForm) {
        blogForm.addEventListener('submit', function(e) {
            if (contentEditor) {
                // Sync editor content to textarea
                const textarea = document.getElementById('content');
                if (textarea) {
                    textarea.value = contentEditor.value;
                }
            }
        });
    }
});

// Auto-generate slug from title
document.getElementById('title')?.addEventListener('input', function() {
    const slug = document.getElementById('slug');
    if (slug && !slug.value) {
        slug.value = this.value.toLowerCase()
            .replace(/[^a-z0-9]+/g, '-')
            .replace(/(^-|-$)/g, '');
    }
});

function previewImage(input, previewId) {
    const preview = document.getElementById(previewId);
    const fileName = input.id + 'FileName';
    const fileLabel = document.getElementById(fileName);
    
    if (input.files && input.files[0]) {
        const reader = new FileReader();
        reader.onload = function(e) {
            preview.innerHTML = '<img src="' + e.target.result + '" alt="Preview" style="max-width: 200px; margin-top: 10px; border-radius: 8px;">';
        };
        reader.readAsDataURL(input.files[0]);
        fileLabel.textContent = input.files[0].name;
    }
}
</script>

<?php require_once 'includes/footer.php'; ?>

