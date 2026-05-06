<?php
// Handle delete BEFORE any output
require_once __DIR__ . '/config/database.php';

if (isset($_GET['delete'])) {
    $conn = getDBConnection();
    $id = intval($_GET['delete']);
    $stmt = $conn->prepare("DELETE FROM seo_data WHERE id = ?");
    $stmt->bind_param("i", $id);
    $stmt->execute();
    $stmt->close();
    header('HTTP/1.1 303 See Other');
    header('Location: seo.php?deleted=1');
    exit();
}

$page_title = 'SEO Management';
require_once 'includes/header.php';
require_once __DIR__ . '/config/database.php';

$conn = getDBConnection();
$search = $_GET['search'] ?? '';

// Get SEO data with search (no pagination - show all)
$where = '';
$search_param = '';
if (!empty($search)) {
    $search_param = "%$search%";
    $where = "WHERE page_name LIKE ? OR title LIKE ?";
    $query = "SELECT * FROM seo_data $where ORDER BY created_at DESC";
    $stmt = $conn->prepare($query);
    $stmt->bind_param("ss", $search_param, $search_param);
    $stmt->execute();
    $seo_list = $stmt->get_result();
} else {
    $query = "SELECT * FROM seo_data ORDER BY created_at DESC";
    $seo_list = $conn->query($query);
}

closeDBConnection($conn);
?>

<div class="page-header">
    <div>
        <h2>SEO Management</h2>
        <p>Manage SEO for all pages.</p>
    </div>
    <a href="seo_add.php" class="btn btn-primary">
        <i class="fas fa-plus"></i> Add SEO Data
    </a>
</div>

<?php if (isset($_GET['deleted'])): ?>
<div class="alert alert-success">
    <i class="fas fa-check-circle"></i> SEO data deleted successfully!
</div>
<?php endif; ?>

<?php if (isset($_GET['success'])): ?>
<div class="alert alert-success">
    <i class="fas fa-check-circle"></i> SEO data saved successfully!
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
                    <th>Sr No</th>
                    <th>Page Name</th>
                    <th>Title</th>
                    <th>SEO URL</th>
                    <th>Created At</th>
                    <th>Actions</th>
                </tr>
            </thead>
            <tbody>
                <?php 
                $sr = 1;
                while ($seo = $seo_list->fetch_assoc()): 
                ?>
                <tr>
                    <td><?php echo $sr++; ?></td>
                    <td><?php echo htmlspecialchars($seo['page_name']); ?></td>
                    <td><?php echo htmlspecialchars(substr($seo['title'], 0, 60)) . '...'; ?></td>
                    <td><?php echo htmlspecialchars($seo['seo_url']); ?></td>
                    <td><?php echo date('d-m-Y h:i:s A', strtotime($seo['created_at'])); ?></td>
                    <td>
                        <div class="action-buttons">
                            <a href="seo_add.php?id=<?php echo $seo['id']; ?>" 
                               class="action-icon-btn edit-btn" 
                               title="Edit SEO">
                                <i class="fas fa-edit"></i>
                            </a>
                            <button class="action-icon-btn delete-btn" 
                                    onclick="confirmDeleteSEO(<?php echo $seo['id']; ?>)"
                                    title="Delete SEO">
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


<script>
function handleSearch(e) {
    if (e.key === 'Enter') {
        const search = document.getElementById('searchInput').value;
        window.location.href = 'seo.php?search=' + encodeURIComponent(search);
    }
}

function confirmDeleteSEO(id) {
    showConfirm(
        'Are you sure you want to delete this SEO data? This action cannot be undone.',
        function() {
            window.location.href = 'seo.php?delete=' + id;
        },
        'delete'
    );
}
</script>

<?php require_once 'includes/footer.php'; ?>

