<?php
// Handle delete and status toggle BEFORE any output
require_once __DIR__ . '/config/database.php';

if (isset($_GET['delete'])) {
    $conn = getDBConnection();
    $id = intval($_GET['delete']);
    $stmt = $conn->prepare("DELETE FROM clients WHERE id = ?");
    $stmt->bind_param("i", $id);
    $stmt->execute();
    $stmt->close();
    header('HTTP/1.1 303 See Other');
    header('Location: clients.php?deleted=1');
    exit();
}

if (isset($_GET['toggle_status'])) {
    $conn = getDBConnection();
    $id = intval($_GET['toggle_status']);
    $stmt = $conn->prepare("UPDATE clients SET status = IF(status = 'active', 'inactive', 'active') WHERE id = ?");
    $stmt->bind_param("i", $id);
    $stmt->execute();
    $stmt->close();
    header('HTTP/1.1 303 See Other');
    header('Location: clients.php');
    exit();
}

$page_title = 'Our Clients';
require_once 'includes/header.php';
require_once __DIR__ . '/config/database.php';

$conn = getDBConnection();
$search = $_GET['search'] ?? '';

// Get clients with search (no pagination - show all)
$where = '';
$search_param = '';
if (!empty($search)) {
    $search_param = "%$search%";
    $where = "WHERE client_name LIKE ? OR email LIKE ?";
    $query = "SELECT * FROM clients $where ORDER BY created_at DESC";
    $stmt = $conn->prepare($query);
    $stmt->bind_param("ss", $search_param, $search_param);
    $stmt->execute();
    $clients = $stmt->get_result();
} else {
    $query = "SELECT * FROM clients ORDER BY created_at DESC";
    $clients = $conn->query($query);
}

closeDBConnection($conn);
?>

<div class="page-header">
    <div>
        <h2>Our Clients</h2>
        <p>Manage your list of clients.</p>
    </div>
    <button class="btn btn-primary" onclick="openModal('addClientModal')">
        <i class="fas fa-plus"></i> Add Client
    </button>
</div>

<?php if (isset($_GET['deleted'])): ?>
<div class="alert alert-success">
    <i class="fas fa-check-circle"></i> Client deleted successfully!
</div>
<?php endif; ?>

<div class="search-bar">
    <input 
        type="text" 
        id="searchInput" 
        placeholder="Search by client name or email..." 
        value="<?php echo htmlspecialchars($search); ?>"
        onkeyup="handleSearch(event)"
    >
</div>

<div class="card">
    <div class="table-responsive">
        <table class="data-table">
            <thead>
                <tr>
                    <th>Logo</th>
                    <th>Client Name</th>
                    <th>Email</th>
                    <th>Website</th>
                    <th>Status</th>
                    <th>Actions</th>
                </tr>
            </thead>
            <tbody>
                <?php while ($client = $clients->fetch_assoc()): ?>
                <tr>
                    <td>
                        <?php if ($client['logo']): ?>
                        <img src="<?php echo UPLOADS_URL; ?>clients/<?php echo htmlspecialchars($client['logo']); ?>" 
                             alt="Client Logo" class="table-image">
                        <?php else: ?>
                        <div class="table-image-placeholder">
                            <i class="fas fa-building"></i>
                        </div>
                        <?php endif; ?>
                    </td>
                    <td><?php echo htmlspecialchars($client['client_name']); ?></td>
                    <td><?php echo htmlspecialchars($client['email']); ?></td>
                    <td>
                        <?php if ($client['website_url']): ?>
                        <a href="<?php echo htmlspecialchars($client['website_url']); ?>" target="_blank">
                            <?php echo htmlspecialchars($client['website_url']); ?>
                        </a>
                        <?php else: ?>
                        N/A
                        <?php endif; ?>
                    </td>
                    <td>
                        <span class="badge badge-<?php echo $client['status'] == 'active' ? 'success' : 'danger'; ?>">
                            <?php echo ucfirst($client['status']); ?>
                        </span>
                    </td>
                    <td>
                        <div class="action-buttons">
                            <button class="action-icon-btn edit-btn" 
                                    onclick="confirmEditClient(<?php echo htmlspecialchars(json_encode($client)); ?>)"
                                    title="Edit Client">
                                <i class="fas fa-edit"></i>
                            </button>
                            <button class="action-icon-btn delete-btn" 
                                    onclick="confirmDeleteClient(<?php echo $client['id']; ?>)"
                                    title="Delete Client">
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

<!-- Add/Edit Client Modal -->
<div id="addClientModal" class="modal">
    <div class="modal-content">
        <div class="modal-header">
            <h3 id="modalTitle">Add Client</h3>
            <button class="modal-close" onclick="closeModal('addClientModal')">
                <i class="fas fa-times"></i>
            </button>
        </div>
        <form id="clientForm" action="actions/client_action.php" method="POST" enctype="multipart/form-data">
            <input type="hidden" name="id" id="client_id">
            <input type="hidden" name="action" id="client_action" value="add">
            
            <div class="form-group">
                <label for="client_name">Client Name</label>
                <input type="text" id="client_name" name="client_name" placeholder="Enter client name" required>
            </div>
            
            <div class="form-group">
                <label for="website_url">Website URL</label>
                <input type="url" id="website_url" name="website_url" placeholder="https://example.com">
            </div>
            
            <div class="form-group">
                <label for="email">Email</label>
                <input type="email" id="email" name="email" placeholder="Enter email address">
            </div>
            
            <div class="form-group">
                <label for="phone">Phone</label>
                <input type="tel" id="phone" name="phone" placeholder="Enter phone number">
            </div>
            
            <div class="form-group">
                <label for="logo">Client Logo</label>
                <div class="file-upload">
                    <input type="file" id="logo" name="logo" accept="image/*" onchange="previewImage(this, 'logoPreview')">
                    <label for="logo" class="file-label">
                        <i class="fas fa-upload"></i> Choose File
                    </label>
                    <span class="file-name" id="logoFileName">No file chosen</span>
                </div>
                <div id="logoPreview" class="image-preview"></div>
                <div id="currentLogo" class="current-image"></div>
            </div>
            
            <div class="form-group">
                <label for="status">Status</label>
                <select id="status" name="status" required>
                    <option value="active">Active</option>
                    <option value="inactive">Inactive</option>
                </select>
            </div>
            
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" onclick="closeModal('addClientModal')">Cancel</button>
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
        window.location.href = 'clients.php?search=' + encodeURIComponent(search);
    }
}

function confirmEditClient(client) {
    showConfirm(
        'Are you sure you want to edit this client?',
        function() {
            editClient(client);
        },
        'edit'
    );
}

function confirmDeleteClient(id) {
    showConfirm(
        'Are you sure you want to delete this client? This action cannot be undone.',
        function() {
            window.location.href = 'clients.php?delete=' + id;
        },
        'delete'
    );
}

function editClient(client) {
    document.getElementById('modalTitle').textContent = 'Edit Client';
    document.getElementById('client_id').value = client.id;
    document.getElementById('client_action').value = 'edit';
    document.getElementById('client_name').value = client.client_name;
    document.getElementById('website_url').value = client.website_url || '';
    document.getElementById('email').value = client.email || '';
    document.getElementById('phone').value = client.phone || '';
    document.getElementById('status').value = client.status;
    
    if (client.logo) {
        document.getElementById('currentLogo').innerHTML = 
            '<img src="<?php echo UPLOADS_URL; ?>clients/' + client.logo + '" alt="Current Logo" style="max-width: 200px; margin-top: 10px;">';
    }
    
    openModal('addClientModal');
}

function previewImage(input, previewId) {
    const preview = document.getElementById(previewId);
    const fileName = document.getElementById('logoFileName');
    
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

