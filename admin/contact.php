<?php
$page_title = 'Contact Us';
require_once 'includes/header.php';
require_once __DIR__ . '/config/database.php';

$conn = getDBConnection();
$search = $_GET['search'] ?? '';

// Get contact messages with search (no pagination - show all)
$where = '';
$search_param = '';
if (!empty($search)) {
    $search_param = "%$search%";
    $where = "WHERE first_name LIKE ? OR last_name LIKE ? OR email LIKE ? OR service LIKE ?";
    $query = "SELECT * FROM contact_us $where ORDER BY created_at DESC";
    $stmt = $conn->prepare($query);
    $stmt->bind_param("ssss", $search_param, $search_param, $search_param, $search_param);
    $stmt->execute();
    $contacts = $stmt->get_result();
} else {
    $query = "SELECT * FROM contact_us ORDER BY created_at DESC";
    $contacts = $conn->query($query);
}

closeDBConnection($conn);
?>

<div class="page-header">
    <div>
        <h2>Recent Contact Us</h2>
        <p>View and manage contact form submissions.</p>
    </div>
</div>

<div class="search-bar">
    <input 
        type="text" 
        id="searchInput" 
        placeholder="Search by name, email, or service..." 
        value="<?php echo htmlspecialchars($search); ?>"
        onkeyup="handleSearch(event)"
    >
</div>

<div class="card">
    <div class="table-responsive">
        <table class="data-table">
            <thead>
                <tr>
                    <th>First Name</th>
                    <th>Last Name</th>
                    <th>Email</th>
                    <th>Phone</th>
                    <th>Service</th>
                    <th>Message</th>
                    <th>Date</th>
                </tr>
            </thead>
            <tbody>
                <?php while ($contact = $contacts->fetch_assoc()): ?>
                <tr>
                    <td><?php echo htmlspecialchars($contact['first_name']); ?></td>
                    <td><?php echo htmlspecialchars($contact['last_name']); ?></td>
                    <td>
                        <a href="mailto:<?php echo htmlspecialchars($contact['email']); ?>">
                            <?php echo htmlspecialchars($contact['email']); ?>
                        </a>
                    </td>
                    <td>
                        <a href="tel:<?php echo htmlspecialchars($contact['phone']); ?>">
                            <?php echo htmlspecialchars($contact['phone']); ?>
                        </a>
                    </td>
                    <td><?php echo htmlspecialchars($contact['service'] ?? 'N/A'); ?></td>
                    <td>
                        <div class="message-preview" title="<?php echo htmlspecialchars($contact['message']); ?>">
                            <?php echo htmlspecialchars(substr($contact['message'], 0, 50)) . '...'; ?>
                        </div>
                    </td>
                    <td><?php echo date('d M Y, h:i A', strtotime($contact['created_at'])); ?></td>
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
        window.location.href = 'contact.php?search=' + encodeURIComponent(search);
    }
}
</script>

<?php require_once 'includes/footer.php'; ?>

