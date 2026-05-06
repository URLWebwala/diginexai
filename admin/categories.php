<?php
// Handle delete BEFORE any output
require_once __DIR__ . '/config/database.php';

if (isset($_GET['delete'])) {
    $conn = getDBConnection();
    $id = intval($_GET['delete']);
    // Check if category is used in blogs
    $check = $conn->prepare("SELECT COUNT(*) as count FROM blogs WHERE category_id = ?");
    $check->bind_param("i", $id);
    $check->execute();
    $result = $check->get_result()->fetch_assoc();
    
    if ($result['count'] > 0) {
        header('HTTP/1.1 303 See Other');
        header('Location: categories.php?error=' . urlencode('Category is being used in blogs and cannot be deleted'));
        exit();
    }
    
    $stmt = $conn->prepare("DELETE FROM blog_categories WHERE id = ?");
    $stmt->bind_param("i", $id);
    $stmt->execute();
    $stmt->close();
    header('HTTP/1.1 303 See Other');
    header('Location: categories.php?deleted=1');
    exit();
}

$page_title = 'Blog Categories';
require_once 'includes/header.php';
require_once __DIR__ . '/config/database.php';

$conn = getDBConnection();
$search = $_GET['search'] ?? '';

// Get categories with search (no pagination - show all)
$where = '';
$search_param = '';
if (!empty($search)) {
    $search_param = "%$search%";
    $where = "WHERE c.name LIKE ?";
    $query = "SELECT c.*, COUNT(b.id) as blog_count FROM blog_categories c 
              LEFT JOIN blogs b ON c.id = b.category_id 
              $where GROUP BY c.id ORDER BY c.name";
    $stmt = $conn->prepare($query);
    $stmt->bind_param("s", $search_param);
    $stmt->execute();
    $categories = $stmt->get_result();
} else {
    $query = "SELECT c.*, COUNT(b.id) as blog_count FROM blog_categories c 
              LEFT JOIN blogs b ON c.id = b.category_id 
              GROUP BY c.id ORDER BY c.name";
    $categories = $conn->query($query);
}

closeDBConnection($conn);
?>

<div class="page-header">
    <div>
        <h2>Blog Categories</h2>
        <p>Manage blog post categories.</p>
    </div>
    <button class="btn btn-primary" onclick="openModal('addCategoryModal')">
        <i class="fas fa-plus"></i> Add Category
    </button>
</div>

<?php if (isset($_GET['deleted'])): ?>
<div class="alert alert-success">
    <i class="fas fa-check-circle"></i> Category deleted successfully!
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
        placeholder="Search categories..." 
        value="<?php echo htmlspecialchars($search); ?>"
        onkeyup="handleSearch(event)"
    >
</div>

<div class="card">
    <div class="table-responsive">
        <table class="data-table">
            <thead>
                <tr>
                    <th>Sr No</th>
                    <th>Name</th>
                    <th>Slug</th>
                    <th>Blog Count</th>
                    <th>Actions</th>
                </tr>
            </thead>
            <tbody>
                <?php 
                $sr = 1;
                while ($category = $categories->fetch_assoc()): 
                ?>
                <tr>
                    <td><?php echo $sr++; ?></td>
                    <td><?php echo htmlspecialchars($category['name']); ?></td>
                    <td><?php echo htmlspecialchars($category['slug']); ?></td>
                    <td><?php echo $category['blog_count']; ?></td>
                    <td>
                        <div class="action-buttons">
                            <button class="action-icon-btn edit-btn" 
                                    onclick="confirmEditCategory(<?php echo htmlspecialchars(json_encode($category)); ?>)"
                                    title="Edit Category">
                                <i class="fas fa-edit"></i>
                            </button>
                            <button class="action-icon-btn delete-btn" 
                                    onclick="confirmDeleteCategory(<?php echo $category['id']; ?>)"
                                    title="Delete Category">
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

<!-- Add/Edit Category Modal -->
<div id="addCategoryModal" class="modal">
    <div class="modal-content">
        <div class="modal-header">
            <h3 id="modalTitle">Add Category</h3>
            <button class="modal-close" onclick="closeModal('addCategoryModal')">
                <i class="fas fa-times"></i>
            </button>
        </div>
        <form id="categoryForm" action="actions/category_action.php" method="POST">
            <input type="hidden" name="id" id="category_id">
            <input type="hidden" name="action" id="category_action" value="add">
            
            <div class="form-group">
                <label for="name">Category Name</label>
                <input type="text" id="name" name="name" placeholder="Enter category name" required>
            </div>
            
            <div class="form-group">
                <label for="slug">Slug</label>
                <input type="text" id="slug" name="slug" placeholder="Enter slug (auto-generated if empty)">
                <small>Leave empty to auto-generate from name</small>
            </div>
            
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" onclick="closeModal('addCategoryModal')">Cancel</button>
                <button type="submit" class="btn btn-primary">
                    <i class="fas fa-save"></i> Save Changes
                </button>
            </div>
        </form>
    </div>
</div>

<script>
function handleSearch(e) {
    if (e.key === 'Enter') {
        const search = document.getElementById('searchInput').value;
        window.location.href = 'categories.php?search=' + encodeURIComponent(search);
    }
}

function confirmEditCategory(category) {
    showConfirm(
        'Are you sure you want to edit this category?',
        function() {
            editCategory(category);
        },
        'edit'
    );
}

function confirmDeleteCategory(id) {
    showConfirm(
        'Are you sure you want to delete this category? This action cannot be undone.',
        function() {
            window.location.href = 'categories.php?delete=' + id;
        },
        'delete'
    );
}

function editCategory(category) {
    document.getElementById('modalTitle').textContent = 'Edit Category';
    document.getElementById('category_id').value = category.id;
    document.getElementById('category_action').value = 'edit';
    document.getElementById('name').value = category.name;
    document.getElementById('slug').value = category.slug;
    
    openModal('addCategoryModal');
}

// Auto-generate slug from name
document.getElementById('name')?.addEventListener('input', function() {
    const slug = document.getElementById('slug');
    if (slug && !slug.value) {
        slug.value = this.value.toLowerCase()
            .replace(/[^a-z0-9]+/g, '-')
            .replace(/(^-|-$)/g, '');
    }
});
</script>

<?php require_once 'includes/footer.php'; ?>

