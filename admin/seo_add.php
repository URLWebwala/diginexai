<?php
require_once __DIR__ . '/config/database.php';
require_once __DIR__ . '/config/auth.php';
requireLogin();

$page_title = 'Add SEO Data';
$is_edit = false;
$seo = null;
$seo_id = isset($_GET['id']) ? intval($_GET['id']) : 0;

// If editing, fetch SEO data
if ($seo_id > 0) {
    $conn = getDBConnection();
    $stmt = $conn->prepare("SELECT * FROM seo_data WHERE id = ?");
    $stmt->bind_param("i", $seo_id);
    $stmt->execute();
    $result = $stmt->get_result();
    if ($result->num_rows > 0) {
        $seo = $result->fetch_assoc();
        $is_edit = true;
        $page_title = 'Edit SEO Data';
    }
    $stmt->close();
    closeDBConnection($conn);
}

require_once 'includes/header.php';
?>

<div class="page-header">
    <div>
        <h2><?php echo $is_edit ? 'Edit SEO Data' : 'Add SEO Data'; ?></h2>
        <p><?php echo $is_edit ? 'Update SEO data for this page' : 'Add SEO data for a new page'; ?></p>
    </div>
    <a href="seo.php" class="btn btn-secondary">
        <i class="fas fa-arrow-left"></i> Back to SEO
    </a>
</div>

<?php if (isset($_GET['error'])): ?>
<div class="alert alert-error">
    <i class="fas fa-exclamation-circle"></i> <?php echo htmlspecialchars($_GET['error']); ?>
</div>
<?php endif; ?>

<form id="seoForm" action="actions/seo_action.php" method="POST" class="seo-form">
    <input type="hidden" name="id" value="<?php echo $seo_id; ?>">
    <input type="hidden" name="action" value="<?php echo $is_edit ? 'edit' : 'add'; ?>">
    
    <div class="form-row">
        <div class="form-group">
            <label for="page_name">Page Name <span class="required">*</span></label>
            <input type="text" id="page_name" name="page_name" 
                   placeholder="e.g., Home, About Us, Contact, Services, Blog" 
                   value="<?php echo htmlspecialchars($seo['page_name'] ?? ''); ?>" required>
            <small>Enter the name of the page (e.g., Home, About Us, Contact)</small>
        </div>
        
        <div class="form-group">
            <label for="seo_url">SEO URL (Slug) <span class="required">*</span></label>
            <input type="text" id="seo_url" name="seo_url" 
                   placeholder="e.g., home, about-us, contact" 
                   value="<?php echo htmlspecialchars($seo['seo_url'] ?? ''); ?>" required>
            <small>SEO-friendly URL slug (lowercase, use hyphens)</small>
        </div>
    </div>
    
    <div class="form-group">
        <label for="title">Title <span class="required">*</span></label>
        <input type="text" id="title" name="title" 
               placeholder="Enter page title (appears in browser tab)" 
               value="<?php echo htmlspecialchars($seo['title'] ?? ''); ?>" required>
        <small>This appears in the browser tab and search results</small>
    </div>
    
    <div class="form-group">
        <label for="page_title">Page Title (Optional)</label>
        <input type="text" id="page_title" name="page_title" 
               placeholder="Enter page title (if different from main title)" 
               value="<?php echo htmlspecialchars($seo['page_title'] ?? ''); ?>">
        <small>Optional: Additional page title if needed</small>
    </div>
    
    <div class="form-group">
        <label for="description">Meta Description</label>
        <textarea id="description" name="description" rows="4" 
                  placeholder="Enter meta description (recommended: 150-160 characters)"><?php echo htmlspecialchars($seo['description'] ?? ''); ?></textarea>
        <small>This appears in search engine results. Keep it between 150-160 characters for best results.</small>
        <div class="char-counter">
            <span id="descCharCount">0</span> / 160 characters
        </div>
    </div>
    
    <div class="form-row">
        <div class="form-group">
            <label for="keywords">Keywords</label>
            <input type="text" id="keywords" name="keywords" 
                   placeholder="Enter keywords (comma separated)" 
                   value="<?php echo htmlspecialchars($seo['keywords'] ?? ''); ?>">
            <small>Comma-separated keywords (e.g., digital marketing, SEO, branding)</small>
        </div>
        
        <div class="form-group">
            <label for="author_name">Author Name</label>
            <input type="text" id="author_name" name="author_name" 
                   placeholder="Enter author name" 
                   value="<?php echo htmlspecialchars($seo['author_name'] ?? ''); ?>">
            <small>Name of the page author</small>
        </div>
    </div>
    
    <div class="form-row">
        <div class="form-group">
            <label for="canonical_url">Canonical URL</label>
            <input type="url" id="canonical_url" name="canonical_url" 
                   placeholder="https://www.diginexai.com/page-name" 
                   value="<?php echo htmlspecialchars($seo['canonical_url'] ?? ''); ?>">
            <small>The preferred URL for this page (prevents duplicate content issues)</small>
        </div>
        
        <div class="form-group">
            <label for="robots">Robots Meta Tag</label>
            <select id="robots" name="robots">
                <option value="index, follow" <?php echo (isset($seo['robots']) && $seo['robots'] == 'index, follow') ? 'selected' : ''; ?>>index, follow</option>
                <option value="noindex, follow" <?php echo (isset($seo['robots']) && $seo['robots'] == 'noindex, follow') ? 'selected' : ''; ?>>noindex, follow</option>
                <option value="index, nofollow" <?php echo (isset($seo['robots']) && $seo['robots'] == 'index, nofollow') ? 'selected' : ''; ?>>index, nofollow</option>
                <option value="noindex, nofollow" <?php echo (isset($seo['robots']) && $seo['robots'] == 'noindex, nofollow') ? 'selected' : ''; ?>>noindex, nofollow</option>
            </select>
            <small>Controls how search engines index this page</small>
        </div>
    </div>
    
    <div class="form-actions">
        <a href="seo.php" class="btn btn-secondary">Cancel</a>
        <button type="submit" class="btn btn-primary">
            <i class="fas fa-save"></i> <?php echo $is_edit ? 'Update' : 'Save'; ?> SEO Data
        </button>
    </div>
</form>

<style>
.seo-form {
    max-width: 1000px;
    margin: 0 auto;
}

.form-row {
    display: grid;
    grid-template-columns: repeat(auto-fit, minmax(300px, 1fr));
    gap: 20px;
    margin-bottom: 20px;
}

.form-group {
    margin-bottom: 20px;
}

.form-group label {
    display: block;
    margin-bottom: 8px;
    font-weight: 600;
    color: #333;
}

.form-group input[type="text"],
.form-group input[type="url"],
.form-group select,
.form-group textarea {
    width: 100%;
    padding: 12px;
    border: 1px solid #ddd;
    border-radius: 6px;
    font-size: 14px;
    transition: border-color 0.3s;
}

.form-group input[type="text"]:focus,
.form-group input[type="url"]:focus,
.form-group select:focus,
.form-group textarea:focus {
    outline: none;
    border-color: #007bff;
}

.form-group small {
    display: block;
    margin-top: 6px;
    color: #666;
    font-size: 12px;
}

.form-group textarea {
    resize: vertical;
    min-height: 100px;
}

.char-counter {
    margin-top: 8px;
    font-size: 12px;
    color: #666;
    text-align: right;
}

.char-counter.warning {
    color: #ff9800;
}

.char-counter.error {
    color: #f44336;
}

.form-actions {
    display: flex;
    justify-content: flex-end;
    gap: 15px;
    margin-top: 30px;
    padding-top: 20px;
    border-top: 1px solid #e0e0e0;
}

.required {
    color: #dc3545;
}

@media (max-width: 768px) {
    .form-row {
        grid-template-columns: 1fr;
    }
}
</style>

<script>
// Character counter for description
const descriptionTextarea = document.getElementById('description');
const descCharCount = document.getElementById('descCharCount');

function updateCharCount() {
    const length = descriptionTextarea.value.length;
    descCharCount.textContent = length;
    
    // Update color based on length
    descCharCount.parentElement.classList.remove('warning', 'error');
    if (length > 160) {
        descCharCount.parentElement.classList.add('error');
    } else if (length > 150) {
        descCharCount.parentElement.classList.add('warning');
    }
}

descriptionTextarea.addEventListener('input', updateCharCount);
updateCharCount(); // Initial count

// Auto-generate SEO URL from page name
document.getElementById('page_name')?.addEventListener('input', function() {
    const seoUrl = document.getElementById('seo_url');
    if (seoUrl && !seoUrl.value) {
        seoUrl.value = this.value.toLowerCase()
            .replace(/[^a-z0-9]+/g, '-')
            .replace(/(^-|-$)/g, '');
    }
});

// Form validation
document.getElementById('seoForm').addEventListener('submit', function(e) {
    const pageName = document.getElementById('page_name').value.trim();
    const title = document.getElementById('title').value.trim();
    const seoUrl = document.getElementById('seo_url').value.trim();
    
    if (!pageName || !title || !seoUrl) {
        e.preventDefault();
        alert('Please fill all required fields.');
        return false;
    }
    
    // Validate SEO URL format
    if (!/^[a-z0-9-]+$/.test(seoUrl)) {
        e.preventDefault();
        alert('SEO URL should only contain lowercase letters, numbers, and hyphens.');
        return false;
    }
});
</script>

<?php require_once 'includes/footer.php'; ?>

