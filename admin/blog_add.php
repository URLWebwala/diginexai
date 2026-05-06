<?php
require_once __DIR__ . '/config/database.php';
require_once __DIR__ . '/config/auth.php';
requireLogin();

$page_title = 'Add New Blog Post';
$is_edit = false;
$blog = null;
$blog_id = isset($_GET['id']) ? intval($_GET['id']) : 0;

// If editing, fetch blog data
if ($blog_id > 0) {
    $conn = getDBConnection();
    $stmt = $conn->prepare("SELECT * FROM blogs WHERE id = ?");
    $stmt->bind_param("i", $blog_id);
    $stmt->execute();
    $result = $stmt->get_result();
    if ($result->num_rows > 0) {
        $blog = $result->fetch_assoc();
        $is_edit = true;
        $page_title = 'Edit Blog Post';
    }
    $stmt->close();
    closeDBConnection($conn);
}

// Get categories
$conn = getDBConnection();
$categories = $conn->query("SELECT * FROM blog_categories ORDER BY name");
closeDBConnection($conn);

require_once 'includes/header.php';
?>

<div class="page-header">
    <div>
        <h2><?php echo $is_edit ? 'Edit Blog Post' : 'Add New Blog Post'; ?></h2>
        <p><?php echo $is_edit ? 'Update your blog post details' : 'Create a new blog post'; ?></p>
    </div>
    <a href="blogs.php" class="btn btn-secondary">
        <i class="fas fa-arrow-left"></i> Back to Blogs
    </a>
</div>

<?php if (isset($_GET['error'])): ?>
<div class="alert alert-error">
    <i class="fas fa-exclamation-circle"></i> <?php echo htmlspecialchars($_GET['error']); ?>
</div>
<?php endif; ?>

<form id="blogForm" action="actions/blog_action.php" method="POST" enctype="multipart/form-data" class="blog-form">
    <input type="hidden" name="id" value="<?php echo $blog_id; ?>">
    <input type="hidden" name="action" value="<?php echo $is_edit ? 'edit' : 'add'; ?>">
    
    <div class="form-row">
        <div class="form-group form-group-large">
            <label for="title">Title <span class="required">*</span></label>
            <input type="text" id="title" name="title" placeholder="Enter blog title" 
                   value="<?php echo htmlspecialchars($blog['title'] ?? ''); ?>" required>
        </div>
        
        <div class="form-group">
            <label for="category_id">Category <span class="required">*</span></label>
            <select id="category_id" name="category_id" required>
                <option value="">Select Category</option>
                <?php 
                $categories->data_seek(0);
                while ($cat = $categories->fetch_assoc()): 
                ?>
                <option value="<?php echo $cat['id']; ?>" 
                        <?php echo (isset($blog['category_id']) && $blog['category_id'] == $cat['id']) ? 'selected' : ''; ?>>
                    <?php echo htmlspecialchars($cat['name']); ?>
                </option>
                <?php endwhile; ?>
            </select>
        </div>
    </div>
    
    <div class="form-group">
        <label for="content">Content <span class="required">*</span></label>
        <div id="editor-container">
            <div id="editor-toolbar">
                <button type="button" class="toolbar-btn" data-command="bold" title="Bold">
                    <strong>B</strong>
                </button>
                <button type="button" class="toolbar-btn" data-command="italic" title="Italic">
                    <em>I</em>
                </button>
                <button type="button" class="toolbar-btn" data-command="underline" title="Underline">
                    <u>U</u>
                </button>
                <div class="toolbar-divider"></div>
                <button type="button" class="toolbar-btn" data-command="insertUnorderedList" title="Bullet List">
                    <i class="fas fa-list-ul"></i>
                </button>
                <button type="button" class="toolbar-btn" data-command="insertOrderedList" title="Numbered List">
                    <i class="fas fa-list-ol"></i>
                </button>
                <div class="toolbar-divider"></div>
                <button type="button" class="toolbar-btn" data-command="formatBlock" data-value="h1" title="Heading 1">
                    H H1
                </button>
                <button type="button" class="toolbar-btn" data-command="formatBlock" data-value="h2" title="Heading 2">
                    H H2
                </button>
                <button type="button" class="toolbar-btn" data-command="formatBlock" data-value="h3" title="Heading 3">
                    H H3
                </button>
                <div class="toolbar-divider"></div>
                <button type="button" class="toolbar-btn" data-command="createLink" title="Insert Link">
                    <i class="fas fa-link"></i>
                </button>
                <button type="button" class="toolbar-btn" data-command="insertHorizontalRule" title="Horizontal Line">
                    <i class="fas fa-minus"></i>
                </button>
                <div class="toolbar-divider"></div>
                <button type="button" class="toolbar-btn" data-command="undo" title="Undo">
                    <i class="fas fa-undo"></i>
                </button>
                <button type="button" class="toolbar-btn" data-command="redo" title="Redo">
                    <i class="fas fa-redo"></i>
                </button>
                <div class="toolbar-divider"></div>
                <button type="button" class="toolbar-btn" id="templateBtn" title="Template">
                    <i class="fas fa-file-alt"></i> Template
                </button>
                <button type="button" class="toolbar-btn" id="previewBtn" title="Preview">
                    <i class="fas fa-eye"></i> Preview
                </button>
            </div>
            <div id="editor-content" contenteditable="true" class="editor-content">
                <?php echo htmlspecialchars($blog['content'] ?? ''); ?>
            </div>
            <div class="editor-footer">
                <div class="editor-stats">
                    <span id="wordCount">0 words</span>
                    <span id="charCount">0 characters</span>
                </div>
                <div class="editor-actions">
                    <button type="button" class="btn-clear" id="clearBtn">
                        <i class="fas fa-trash"></i> Clear
                    </button>
                    <button type="button" class="btn-format" id="formatBtn">
                        <i class="fas fa-check"></i> Format
                    </button>
                </div>
            </div>
        </div>
        <textarea id="content" name="content" style="display: none;" required><?php echo htmlspecialchars($blog['content'] ?? ''); ?></textarea>
    </div>
    
    <div class="form-row">
        <div class="form-group">
            <label for="author_name">Author Name <span class="required">*</span></label>
            <input type="text" id="author_name" name="author_name" placeholder="Enter author name" 
                   value="<?php echo htmlspecialchars($blog['author_name'] ?? ''); ?>" required>
        </div>
        
        <div class="form-group">
            <label for="slug">SEO URL (Slug)</label>
            <input type="text" id="slug" name="slug" placeholder="Enter SEO-friendly URL" 
                   value="<?php echo htmlspecialchars($blog['slug'] ?? ''); ?>">
            <small>Leave empty to auto-generate from title</small>
        </div>
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
        <?php if (isset($blog['blog_image']) && $blog['blog_image']): ?>
        <div id="currentBlogImage" class="current-image">
            <p>Current Image:</p>
            <img src="<?php echo UPLOADS_URL; ?>blogs/<?php echo htmlspecialchars($blog['blog_image']); ?>" alt="Current Image" style="max-width: 200px; margin-top: 10px; border-radius: 8px;">
        </div>
        <?php endif; ?>
    </div>
    
    <div class="form-row">
        <div class="form-group">
            <label for="canonical_url">Canonical URL</label>
            <input type="url" id="canonical_url" name="canonical_url" placeholder="Enter canonical URL" 
                   value="<?php echo htmlspecialchars($blog['canonical_url'] ?? ''); ?>">
        </div>
        
        <div class="form-group">
            <label for="seo_url">SEO URL</label>
            <input type="text" id="seo_url" name="seo_url" placeholder="Enter SEO URL" 
                   value="<?php echo htmlspecialchars($blog['seo_url'] ?? ''); ?>">
        </div>
        
        <div class="form-group">
            <label for="robots">Robots</label>
            <input type="text" id="robots" name="robots" placeholder="e.g., index, follow" 
                   value="<?php echo htmlspecialchars($blog['robots'] ?? ''); ?>">
        </div>
        
        <div class="form-group">
            <label for="status">Status <span class="required">*</span></label>
            <select id="status" name="status" required>
                <option value="active" <?php echo (isset($blog['status']) && $blog['status'] == 'active') ? 'selected' : ''; ?>>Active</option>
                <option value="inactive" <?php echo (isset($blog['status']) && $blog['status'] == 'inactive') ? 'selected' : ''; ?>>Inactive</option>
            </select>
        </div>
    </div>
    
    <div class="form-actions">
        <a href="blogs.php" class="btn btn-secondary">Cancel</a>
        <button type="submit" class="btn btn-primary">
            <i class="fas fa-save"></i> <?php echo $is_edit ? 'Update' : 'Publish'; ?> Post
        </button>
    </div>
</form>

<!-- Preview Modal -->
<div id="previewModal" class="modal">
    <div class="modal-content modal-large">
        <div class="modal-header">
            <h3>Preview</h3>
            <button class="modal-close" onclick="closeModal('previewModal')">
                <i class="fas fa-times"></i>
            </button>
        </div>
        <div class="modal-body">
            <div id="previewContent" class="preview-content"></div>
        </div>
    </div>
</div>

<style>
.blog-form {
    max-width: 1200px;
    margin: 0 auto;
}

.form-row {
    display: grid;
    grid-template-columns: repeat(auto-fit, minmax(250px, 1fr));
    gap: 20px;
    margin-bottom: 20px;
}

.form-group-large {
    grid-column: 1 / -1;
}

#editor-container {
    border: 1px solid #e0e0e0;
    border-radius: 8px;
    background: #fff;
    overflow: hidden;
}

#editor-toolbar {
    display: flex;
    align-items: center;
    gap: 2px;
    padding: 12px 15px;
    background: #f8f9fa;
    border-bottom: 1px solid #e0e0e0;
    flex-wrap: wrap;
}

.toolbar-btn {
    background: #ffffff;
    border: 1px solid #e0e0e0;
    padding: 6px 10px;
    cursor: pointer;
    border-radius: 3px;
    font-size: 13px;
    color: #333;
    transition: all 0.2s;
    min-width: 32px;
    display: inline-flex;
    align-items: center;
    justify-content: center;
}

.toolbar-btn:hover {
    background: #f0f0f0;
    border-color: #d0d0d0;
}

.toolbar-btn.active {
    background: #e8e8e8;
    border-color: #c0c0c0;
}

.toolbar-divider {
    width: 1px;
    height: 20px;
    background: #d0d0d0;
    margin: 0 5px;
}

.editor-content {
    min-height: 450px;
    padding: 25px;
    outline: none;
    font-size: 15px;
    line-height: 1.7;
    color: #1e293b;
    background: #ffffff;
}

.editor-content:empty:before {
    content: "Start writing...";
    color: #9ca3af;
    font-style: italic;
}

.editor-content h1 {
    font-size: 2em;
    font-weight: bold;
    margin: 0.67em 0;
}

.editor-content h2 {
    font-size: 1.5em;
    font-weight: bold;
    margin: 0.75em 0;
}

.editor-content h3 {
    font-size: 1.17em;
    font-weight: bold;
    margin: 0.83em 0;
}

.editor-content p {
    margin: 1em 0;
}

.editor-content ul, .editor-content ol {
    margin: 1em 0;
    padding-left: 2em;
}

.editor-footer {
    display: flex;
    justify-content: space-between;
    align-items: center;
    padding: 12px 15px;
    background: #f8f9fa;
    border-top: 1px solid #e0e0e0;
}

.editor-stats {
    display: flex;
    gap: 15px;
    font-size: 13px;
    color: #666;
}

.editor-actions {
    display: flex;
    gap: 10px;
}

.btn-clear {
    background: #f8f9fa;
    border: 1px solid #d0d0d0;
    padding: 8px 16px;
    border-radius: 4px;
    cursor: pointer;
    font-size: 13px;
    color: #495057;
    display: flex;
    align-items: center;
    gap: 6px;
    transition: all 0.2s;
}

.btn-clear:hover {
    background: #e9ecef;
    border-color: #adb5bd;
}

.btn-format {
    background: #28a745;
    border: none;
    padding: 8px 16px;
    border-radius: 4px;
    cursor: pointer;
    font-size: 13px;
    color: #fff;
    display: flex;
    align-items: center;
    gap: 6px;
    transition: all 0.2s;
}

.btn-format:hover {
    background: #218838;
}

.form-actions {
    display: flex;
    justify-content: flex-end;
    gap: 15px;
    margin-top: 30px;
    padding-top: 20px;
    border-top: 1px solid #e0e0e0;
}

.preview-content {
    padding: 20px;
    max-height: 70vh;
    overflow-y: auto;
}

.required {
    color: #dc3545;
}
</style>

<script>
// Rich Text Editor Functions
const editorContent = document.getElementById('editor-content');
const contentTextarea = document.getElementById('content');

// Update word and character count
function updateStats() {
    const text = editorContent.innerText || editorContent.textContent || '';
    const words = text.trim() ? text.trim().split(/\s+/).length : 0;
    const chars = text.length;
    document.getElementById('wordCount').textContent = words + ' words';
    document.getElementById('charCount').textContent = chars + ' characters';
}

// Sync editor content to textarea
function syncContent() {
    contentTextarea.value = editorContent.innerHTML;
    updateStats();
}

// Toolbar button handlers
document.querySelectorAll('.toolbar-btn').forEach(btn => {
    btn.addEventListener('click', function(e) {
        e.preventDefault();
        const command = this.dataset.command;
        const value = this.dataset.value;
        
        if (command === 'createLink') {
            const url = prompt('Enter URL:');
            if (url) {
                document.execCommand(command, false, url);
            }
        } else if (command === 'formatBlock' && value) {
            document.execCommand(command, false, value);
        } else {
            document.execCommand(command, false, null);
        }
        
        syncContent();
    });
});

// Editor content change
editorContent.addEventListener('input', syncContent);
editorContent.addEventListener('paste', function(e) {
    e.preventDefault();
    const text = (e.clipboardData || window.clipboardData).getData('text/plain');
    document.execCommand('insertText', false, text);
    syncContent();
});

// Clear button
document.getElementById('clearBtn').addEventListener('click', function() {
    if (confirm('Are you sure you want to clear all content?')) {
        editorContent.innerHTML = '';
        syncContent();
    }
});

// Format button
document.getElementById('formatBtn').addEventListener('click', function() {
    // Clean up formatting
    const text = editorContent.innerText;
    editorContent.innerHTML = '<p>' + text.replace(/\n/g, '</p><p>') + '</p>';
    syncContent();
});

// Preview button
document.getElementById('previewBtn').addEventListener('click', function() {
    document.getElementById('previewContent').innerHTML = editorContent.innerHTML;
    openModal('previewModal');
});

// Template button
document.getElementById('templateBtn').addEventListener('click', function() {
    const template = '<h1>Blog Post Title</h1><p>Start writing your blog post here...</p>';
    editorContent.innerHTML = template;
    syncContent();
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

// Form submit - sync content
document.getElementById('blogForm').addEventListener('submit', function(e) {
    syncContent();
    if (!contentTextarea.value.trim()) {
        e.preventDefault();
        alert('Please enter some content for your blog post.');
        return false;
    }
});

// Image preview
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

// Initialize stats
updateStats();
</script>

<?php require_once 'includes/footer.php'; ?>

