<?php
$current_page = 'dashboard';
$page_title = 'Dashboard';
require_once '../includes/header.php';
require_once '../includes/auth.php';

// Check if user is logged in
checkAuth();

$user = getCurrentUser();

// Get user statistics
$stats = [
    'donations' => 0,
    'total_donated' => 0,
    'events_joined' => 0
];

// Get donation count and total
$donation_query = "SELECT COUNT(*) as count, SUM(Quantity_Donated) as total FROM DONATION WHERE Donor_ID = " . $user['id'];
$donation_result = mysqli_query($conn, $donation_query);
if ($donation_result && mysqli_num_rows($donation_result) > 0) {
    $donation_data = mysqli_fetch_assoc($donation_result);
    $stats['donations'] = $donation_data['count'] ?? 0;
    $stats['total_donated'] = $donation_data['total'] ?? 0;
}

// Get recent donations
$recent_query = "SELECT d.*, don.Name as donor_name 
                 FROM DONATION d 
                 LEFT JOIN DONOR don ON d.Donor_ID = don.Donor_ID 
                 WHERE d.Donor_ID = " . $user['id'] . " 
                 ORDER BY d.Date_Donated DESC 
                 LIMIT 5";
$recent_result = mysqli_query($conn, $recent_query);
?>

<style>
.dashboard-container {
    max-width: 1200px;
    margin: 40px auto;
    padding: 0 20px;
}

.dashboard-header {
    background: linear-gradient(135deg, #6b4a8e, #8e6bb4);
    color: white;
    padding: 40px;
    border-radius: 10px;
    margin-bottom: 30px;
}

.dashboard-header h1 {
    font-size: 36px;
    margin-bottom: 10px;
}

.dashboard-header p {
    font-size: 16px;
    opacity: 0.9;
}

.stats-grid {
    display: grid;
    grid-template-columns: repeat(auto-fit, minmax(250px, 1fr));
    gap: 20px;
    margin-bottom: 40px;
}

.stat-card {
    background: white;
    padding: 30px;
    border-radius: 10px;
    box-shadow: 0 2px 10px rgba(0,0,0,0.1);
    transition: transform 0.3s, box-shadow 0.3s;
}

.stat-card:hover {
    transform: translateY(-5px);
    box-shadow: 0 5px 20px rgba(0,0,0,0.15);
}

.stat-card-icon {
    width: 60px;
    height: 60px;
    background: linear-gradient(135deg, #6b4a8e, #8e6bb4);
    border-radius: 50%;
    display: flex;
    align-items: center;
    justify-content: center;
    color: white;
    font-size: 24px;
    margin-bottom: 15px;
}

.stat-card-value {
    font-size: 32px;
    font-weight: 700;
    color: #6b4a8e;
    margin-bottom: 5px;
}

.stat-card-label {
    color: #666;
    font-size: 14px;
}

.dashboard-section {
    background: white;
    padding: 30px;
    border-radius: 10px;
    box-shadow: 0 2px 10px rgba(0,0,0,0.1);
    margin-bottom: 30px;
}

.section-header {
    display: flex;
    justify-content: space-between;
    align-items: center;
    margin-bottom: 25px;
    padding-bottom: 15px;
    border-bottom: 2px solid #f0f0f0;
}

.section-header h2 {
    color: #6b4a8e;
    font-size: 24px;
}

.section-header a {
    color: #6b4a8e;
    text-decoration: none;
    font-weight: 600;
}

.section-header a:hover {
    text-decoration: underline;
}

.quick-actions {
    display: grid;
    grid-template-columns: repeat(auto-fit, minmax(200px, 1fr));
    gap: 15px;
}

.action-btn {
    display: flex;
    align-items: center;
    gap: 10px;
    padding: 15px 20px;
    background: #f8f8f8;
    border-radius: 8px;
    text-decoration: none;
    color: #333;
    font-weight: 600;
    transition: all 0.3s;
}

.action-btn:hover {
    background: #6b4a8e;
    color: white;
    transform: translateX(5px);
}

.action-btn i {
    font-size: 20px;
}

.donations-table {
    width: 100%;
    border-collapse: collapse;
}

.donations-table th {
    background: #f8f8f8;
    padding: 12px;
    text-align: left;
    font-weight: 600;
    color: #6b4a8e;
    border-bottom: 2px solid #e0e0e0;
}

.donations-table td {
    padding: 12px;
    border-bottom: 1px solid #f0f0f0;
}

.donations-table tr:hover {
    background: #f8f8f8;
}

.empty-state {
    text-align: center;
    padding: 40px;
    color: #999;
}

.empty-state i {
    font-size: 48px;
    margin-bottom: 15px;
    opacity: 0.5;
}

.empty-state p {
    font-size: 16px;
    margin-bottom: 20px;
}

.btn-primary {
    display: inline-block;
    padding: 12px 30px;
    background: linear-gradient(135deg, #6b4a8e, #8e6bb4);
    color: white;
    text-decoration: none;
    border-radius: 25px;
    font-weight: 600;
    transition: all 0.3s;
}

.btn-primary:hover {
    transform: translateY(-2px);
    box-shadow: 0 5px 15px rgba(107, 74, 142, 0.3);
}
</style>

<div class="dashboard-container">
    <div class="dashboard-header">
        <h1>Welcome back, <?php echo htmlspecialchars($user['name']); ?>!</h1>
        <p>Here's an overview of your charitable activities</p>
    </div>

    <div class="stats-grid">
        <div class="stat-card">
            <div class="stat-card-icon">
                <i class="fas fa-hand-holding-heart"></i>
            </div>
            <div class="stat-card-value"><?php echo $stats['donations']; ?></div>
            <div class="stat-card-label">Total Donations</div>
        </div>

        <div class="stat-card">
            <div class="stat-card-icon">
                <i class="fas fa-dollar-sign"></i>
            </div>
            <div class="stat-card-value">$<?php echo number_format($stats['total_donated'], 2); ?></div>
            <div class="stat-card-label">Items Donated</div>
        </div>

        <div class="stat-card">
            <div class="stat-card-icon">
                <i class="fas fa-calendar-check"></i>
            </div>
            <div class="stat-card-value"><?php echo $stats['events_joined']; ?></div>
            <div class="stat-card-label">Events Joined</div>
        </div>
    </div>

    <div class="dashboard-section">
        <div class="section-header">
            <h2>Quick Actions</h2>
        </div>
        <div class="quick-actions">
            <a href="<?php echo SITE_URL; ?>/pages/donate.php" class="action-btn">
                <i class="fas fa-hand-holding-heart"></i>
                <span>Make a Donation</span>
            </a>
            <a href="<?php echo SITE_URL; ?>/pages/events.php" class="action-btn">
                <i class="fas fa-calendar-alt"></i>
                <span>View Events</span>
            </a>
            <a href="<?php echo SITE_URL; ?>/pages/volunteers.php" class="action-btn">
                <i class="fas fa-hands-helping"></i>
                <span>Volunteer</span>
            </a>
            <a href="<?php echo SITE_URL; ?>/pages/warehouse.php" class="action-btn">
                <i class="fas fa-warehouse"></i>
                <span>Warehouse</span>
            </a>
        </div>
    </div>

    <div class="dashboard-section">
        <div class="section-header">
            <h2>Recent Donations</h2>
            <a href="<?php echo SITE_URL; ?>/pages/donation.php">View All</a>
        </div>
        
        <?php if ($recent_result && mysqli_num_rows($recent_result) > 0): ?>
            <table class="donations-table">
                <thead>
                    <tr>
                        <th>Date</th>
                        <th>Payment Method</th>
                        <th>Quantity</th>
                        <th>Status</th>
                    </tr>
                </thead>
                <tbody>
                    <?php while ($donation = mysqli_fetch_assoc($recent_result)): ?>
                        <tr>
                            <td><?php echo date('M d, Y', strtotime($donation['Date_Donated'])); ?></td>
                            <td><?php echo htmlspecialchars($donation['Payment_Method'] ?? 'General'); ?></td>
                            <td><?php echo $donation['Quantity_Donated'] ?? 0; ?> items</td>
                            <td><span style="color: #4CAF50; font-weight: 600;">Completed</span></td>
                        </tr>
                    <?php endwhile; ?>
                </tbody>
            </table>
        <?php else: ?>
            <div class="empty-state">
                <i class="fas fa-heart"></i>
                <p>You haven't made any donations yet</p>
                <a href="<?php echo SITE_URL; ?>/pages/donate.php" class="btn-primary">Make Your First Donation</a>
            </div>
        <?php endif; ?>
    </div>
</div>

<?php require_once '../includes/footer.php'; ?>
