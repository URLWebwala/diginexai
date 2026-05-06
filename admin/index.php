<?php
// Clean URL if file path detected
if (isset($_SERVER['REQUEST_URI']) && preg_match('#/C:/|/xampp/|/htdocs/#i', $_SERVER['REQUEST_URI'])) {
    require_once __DIR__ . '/config/config.php';
    header('Location: ' . BASE_URL . 'index.php');
    exit();
}

$page_title = 'Dashboard';
require_once __DIR__ . '/config/config.php';
require_once 'includes/header.php';
require_once __DIR__ . '/config/database.php';

$conn = getDBConnection();

// Get statistics
$stats = [];

// Total Blogs
$result = $conn->query("SELECT COUNT(*) as count FROM blogs");
$stats['blogs'] = $result->fetch_assoc()['count'];

// Total Testimonials
$result = $conn->query("SELECT COUNT(*) as count FROM testimonials");
$stats['testimonials'] = $result->fetch_assoc()['count'];

// Total Clients
$result = $conn->query("SELECT COUNT(*) as count FROM clients");
$stats['clients'] = $result->fetch_assoc()['count'];

// Total Contact Messages
$result = $conn->query("SELECT COUNT(*) as count FROM contact_us");
$stats['contacts'] = $result->fetch_assoc()['count'];

// Active Blogs
$result = $conn->query("SELECT COUNT(*) as count FROM blogs WHERE status = 'active'");
$stats['active_blogs'] = $result->fetch_assoc()['count'];

// Active Testimonials
$result = $conn->query("SELECT COUNT(*) as count FROM testimonials WHERE status = 'active'");
$stats['active_testimonials'] = $result->fetch_assoc()['count'];

// Recent Contact Messages
$recent_contacts = $conn->query("SELECT * FROM contact_us ORDER BY created_at DESC LIMIT 5");

// Recent Blogs
$recent_blogs = $conn->query("SELECT b.*, c.name as category_name FROM blogs b LEFT JOIN blog_categories c ON b.category_id = c.id ORDER BY b.created_at DESC LIMIT 5");

closeDBConnection($conn);
?>

<div class="dashboard">
    <!-- Statistics Cards -->
    <div class="stats-grid">
        <div class="stat-card">
            <div class="stat-icon" style="background: linear-gradient(135deg, #384bff 0%, #5a6cff 100%);">
                <i class="fas fa-blog"></i>
            </div>
            <div class="stat-content">
                <h3><?php echo $stats['blogs']; ?></h3>
                <p>Total Blogs</p>
                <span class="stat-badge"><?php echo $stats['active_blogs']; ?> Active</span>
            </div>
        </div>
        
        <div class="stat-card">
            <div class="stat-icon" style="background: linear-gradient(135deg, #384bff 0%, #5a6cff 100%);">
                <i class="fas fa-comments"></i>
            </div>
            <div class="stat-content">
                <h3><?php echo $stats['testimonials']; ?></h3>
                <p>Testimonials</p>
                <span class="stat-badge"><?php echo $stats['active_testimonials']; ?> Active</span>
            </div>
        </div>
        
        <div class="stat-card">
            <div class="stat-icon" style="background: linear-gradient(135deg, #384bff 0%, #5a6cff 100%);">
                <i class="fas fa-users"></i>
            </div>
            <div class="stat-content">
                <h3><?php echo $stats['clients']; ?></h3>
                <p>Clients</p>
            </div>
        </div>
        
        <div class="stat-card">
            <div class="stat-icon" style="background: linear-gradient(135deg, #384bff 0%, #5a6cff 100%);">
                <i class="fas fa-envelope"></i>
            </div>
            <div class="stat-content">
                <h3><?php echo $stats['contacts']; ?></h3>
                <p>Contact Messages</p>
            </div>
        </div>
    </div>
    
    <!-- Recent Activity -->
    <div class="dashboard-grid">
        <div class="dashboard-card">
            <div class="card-header">
                <h3><i class="fas fa-envelope"></i> Recent Contact Messages</h3>
                <a href="<?php echo BASE_URL; ?>contact.php" class="btn-link">View All</a>
            </div>
            <div class="card-body">
                <?php if ($recent_contacts->num_rows > 0): ?>
                <table class="data-table">
                    <thead>
                        <tr>
                            <th>Name</th>
                            <th>Email</th>
                            <th>Service</th>
                            <th>Date</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php while ($contact = $recent_contacts->fetch_assoc()): ?>
                        <tr>
                            <td><?php echo htmlspecialchars($contact['first_name'] . ' ' . $contact['last_name']); ?></td>
                            <td><?php echo htmlspecialchars($contact['email']); ?></td>
                            <td><?php echo htmlspecialchars($contact['service'] ?? 'N/A'); ?></td>
                            <td><?php echo date('d M Y', strtotime($contact['created_at'])); ?></td>
                        </tr>
                        <?php endwhile; ?>
                    </tbody>
                </table>
                <?php else: ?>
                <p class="empty-state">No contact messages yet</p>
                <?php endif; ?>
            </div>
        </div>
        
        <div class="dashboard-card">
            <div class="card-header">
                <h3><i class="fas fa-blog"></i> Recent Blog Posts</h3>
                <a href="<?php echo BASE_URL; ?>blogs.php" class="btn-link">View All</a>
            </div>
            <div class="card-body">
                <?php if ($recent_blogs->num_rows > 0): ?>
                <table class="data-table">
                    <thead>
                        <tr>
                            <th>Title</th>
                            <th>Category</th>
                            <th>Author</th>
                            <th>Status</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php while ($blog = $recent_blogs->fetch_assoc()): ?>
                        <tr>
                            <td><?php echo htmlspecialchars(substr($blog['title'], 0, 40)) . '...'; ?></td>
                            <td><?php echo htmlspecialchars($blog['category_name'] ?? 'N/A'); ?></td>
                            <td><?php echo htmlspecialchars($blog['author_name']); ?></td>
                            <td>
                                <span class="badge badge-<?php echo $blog['status'] == 'active' ? 'success' : 'danger'; ?>">
                                    <?php echo ucfirst($blog['status']); ?>
                                </span>
                            </td>
                        </tr>
                        <?php endwhile; ?>
                    </tbody>
                </table>
                <?php else: ?>
                <p class="empty-state">No blog posts yet</p>
                <?php endif; ?>
            </div>
        </div>
    </div>
</div>

<?php require_once 'includes/footer.php'; ?>

