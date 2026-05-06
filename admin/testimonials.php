<?php
// Handle delete and status toggle BEFORE any output
require_once __DIR__ . '/config/database.php';

if (isset($_GET['delete'])) {
    $conn = getDBConnection();
    $id = intval($_GET['delete']);
    $stmt = $conn->prepare("DELETE FROM testimonials WHERE id = ?");
    $stmt->bind_param("i", $id);
    $stmt->execute();
    $stmt->close();
    header('HTTP/1.1 303 See Other');
    header('Location: testimonials.php?deleted=1');
    exit();
}

if (isset($_GET['toggle_status'])) {
    $conn = getDBConnection();
    $id = intval($_GET['toggle_status']);
    $stmt = $conn->prepare("UPDATE testimonials SET status = IF(status = 'active', 'inactive', 'active') WHERE id = ?");
    $stmt->bind_param("i", $id);
    $stmt->execute();
    $stmt->close();
    header('HTTP/1.1 303 See Other');
    header('Location: testimonials.php');
    exit();
}

$page_title = 'Testimonials';
require_once 'includes/header.php';
require_once __DIR__ . '/config/database.php';

$conn = getDBConnection();
$search = $_GET['search'] ?? '';

// Get testimonials with search (no pagination - show all)
$where = '';
$search_param = '';
if (!empty($search)) {
    $search_param = "%$search%";
    $where = "WHERE client_name LIKE ?";
    $query = "SELECT * FROM testimonials $where ORDER BY created_at DESC";
    $stmt = $conn->prepare($query);
    $stmt->bind_param("s", $search_param);
    $stmt->execute();
    $testimonials = $stmt->get_result();
} else {
    $query = "SELECT * FROM testimonials ORDER BY created_at DESC";
    $testimonials = $conn->query($query);
}

closeDBConnection($conn);
?>

<div class="page-header">
    <div>
        <h2>Testimonials</h2>
        <p>Manage customer testimonials.</p>
    </div>
    <button class="btn btn-primary" onclick="openModal('addTestimonialModal')">
        <i class="fas fa-plus"></i> Add Testimonial
    </button>
</div>

<?php if (isset($_GET['deleted'])): ?>
<div class="alert alert-success">
    <i class="fas fa-check-circle"></i> Testimonial deleted successfully!
</div>
<?php endif; ?>

<div class="search-bar">
    <input 
        type="text" 
        id="searchInput" 
        placeholder="Search by client name..." 
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
                    <th>Image</th>
                    <th>Client Name</th>
                    <th>Rating</th>
                    <th>Date & Time</th>
                    <th>Status</th>
                    <th>Actions</th>
                </tr>
            </thead>
            <tbody>
                <?php 
                $sr = 1;
                while ($testimonial = $testimonials->fetch_assoc()): 
                ?>
                <tr>
                    <td><?php echo $sr++; ?></td>
                    <td>
                        <?php if ($testimonial['image']): ?>
                        <img src="<?php echo UPLOADS_URL; ?>testimonials/<?php echo htmlspecialchars($testimonial['image']); ?>" 
                             alt="Client" class="table-image">
                        <?php else: ?>
                        <div class="table-image-placeholder">
                            <i class="fas fa-user"></i>
                        </div>
                        <?php endif; ?>
                    </td>
                    <td><?php echo htmlspecialchars($testimonial['client_name']); ?></td>
                    <td>
                        <div class="rating">
                            <?php for ($i = 1; $i <= 5; $i++): ?>
                            <i class="fas fa-star <?php echo $i <= $testimonial['rating'] ? 'active' : ''; ?>"></i>
                            <?php endfor; ?>
                            <span class="rating-value"><?php echo $testimonial['rating']; ?></span>
                        </div>
                    </td>
                    <td><?php echo date('d-m-Y h:i:s A', strtotime($testimonial['created_at'])); ?></td>
                    <td>
                        <span class="badge badge-<?php echo $testimonial['status'] == 'active' ? 'success' : 'danger'; ?>">
                            <?php echo ucfirst($testimonial['status']); ?>
                        </span>
                    </td>
                    <td>
                        <div class="action-buttons">
                            <button class="action-icon-btn edit-btn" 
                                    onclick="confirmEditTestimonial(<?php echo htmlspecialchars(json_encode($testimonial)); ?>)"
                                    title="Edit Testimonial">
                                <i class="fas fa-edit"></i>
                            </button>
                            <button class="action-icon-btn delete-btn" 
                                    onclick="confirmDeleteTestimonial(<?php echo $testimonial['id']; ?>)"
                                    title="Delete Testimonial">
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

<!-- Add/Edit Testimonial Modal -->
<div id="addTestimonialModal" class="modal">
    <div class="modal-content">
        <div class="modal-header">
            <h3 id="modalTitle">Add Testimonial</h3>
            <button class="modal-close" onclick="closeModal('addTestimonialModal')">
                <i class="fas fa-times"></i>
            </button>
        </div>
        <form id="testimonialForm" action="actions/testimonial_action.php" method="POST" enctype="multipart/form-data">
            <input type="hidden" name="id" id="testimonial_id">
            <input type="hidden" name="action" id="testimonial_action" value="add">
            
            <div class="form-group">
                <label for="client_name">Client Name</label>
                <input type="text" id="client_name" name="client_name" placeholder="Enter the client name" required>
            </div>
            
            <div class="form-group">
                <label for="description">Description</label>
                <textarea id="description" name="description" rows="4" placeholder="Enter the description" required></textarea>
            </div>
            
            <div class="form-group">
                <label for="rating">Rating</label>
                <input type="number" id="rating" name="rating" min="1" max="5" step="0.1" value="5" required>
            </div>
            
            <div class="form-group">
                <label for="image">Image</label>
                <div class="file-upload">
                    <input type="file" id="image" name="image" accept="image/*" onchange="previewImage(this, 'imagePreview')">
                    <label for="image" class="file-label">
                        <i class="fas fa-upload"></i> Choose File
                    </label>
                    <span class="file-name" id="imageFileName">No file chosen</span>
                </div>
                <div id="imagePreview" class="image-preview"></div>
                <div id="currentImage" class="current-image"></div>
            </div>
            
            <div class="form-group">
                <label for="status">Status</label>
                <select id="status" name="status" required>
                    <option value="active">Active</option>
                    <option value="inactive">Inactive</option>
                </select>
            </div>
            
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" onclick="closeModal('addTestimonialModal')">Cancel</button>
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
        window.location.href = 'testimonials.php?search=' + encodeURIComponent(search);
    }
}

function confirmEditTestimonial(testimonial) {
    showConfirm(
        'Are you sure you want to edit this testimonial?',
        function() {
            editTestimonial(testimonial);
        },
        'edit'
    );
}

function confirmDeleteTestimonial(id) {
    showConfirm(
        'Are you sure you want to delete this testimonial? This action cannot be undone.',
        function() {
            window.location.href = 'testimonials.php?delete=' + id;
        },
        'delete'
    );
}

function editTestimonial(testimonial) {
    document.getElementById('modalTitle').textContent = 'Edit Testimonial';
    document.getElementById('testimonial_id').value = testimonial.id;
    document.getElementById('testimonial_action').value = 'edit';
    document.getElementById('client_name').value = testimonial.client_name;
    document.getElementById('description').value = testimonial.description;
    document.getElementById('rating').value = testimonial.rating;
    document.getElementById('status').value = testimonial.status;
    
    if (testimonial.image) {
        document.getElementById('currentImage').innerHTML = 
            '<img src="<?php echo UPLOADS_URL; ?>testimonials/' + testimonial.image + '" alt="Current Image" style="max-width: 200px; margin-top: 10px;">';
    }
    
    openModal('addTestimonialModal');
}

function previewImage(input, previewId) {
    const preview = document.getElementById(previewId);
    const fileName = document.getElementById('imageFileName');
    
    if (input.files && input.files[0]) {
        const reader = new FileReader();
        reader.onload = function(e) {
            preview.innerHTML = '<img src="' + e.target.result + '" alt="Preview" style="max-width: 200px; margin-top: 10px; border-radius: 8px;">';
        };
        reader.readAsDataURL(input.files[0]);
        fileName.textContent = input.files[0].name;
    }
}
</script>

<?php require_once 'includes/footer.php'; ?>

