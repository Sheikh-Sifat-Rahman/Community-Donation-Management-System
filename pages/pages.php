<?php
$current_page = 'pages';
$page_title = 'Admin Dashboard';
require_once '../includes/header.php';
require_once '../includes/auth.php';

// Check if user is logged in and is admin
checkAuth();
if (!isAdmin()) {
    header('Location: ' . SITE_URL . '/pages/dashboard.php');
    exit();
}

// Get admin statistics
$stats = [];

// Total users
$users_query = "SELECT COUNT(*) as count FROM users";
$users_result = mysqli_query($conn, $users_query);
$stats['users'] = mysqli_fetch_assoc($users_result)['count'] ?? 0;

// Total donors
$donors_query = "SELECT COUNT(*) as count FROM DONOR";
$donors_result = mysqli_query($conn, $donors_query);
$stats['donors'] = mysqli_fetch_assoc($donors_result)['count'] ?? 0;

// Total donations
$donations_query = "SELECT COUNT(*) as count, SUM(Quantity_Donated) as total FROM DONATION";
$donations_result = mysqli_query($conn, $donations_query);
$donation_data = mysqli_fetch_assoc($donations_result);
$stats['donations'] = $donation_data['count'] ?? 0;
$stats['total_items'] = $donation_data['total'] ?? 0;

// Total volunteers
$volunteers_query = "SELECT COUNT(*) as count FROM VOLUNTEERS";
$volunteers_result = mysqli_query($conn, $volunteers_query);
$stats['volunteers'] = mysqli_fetch_assoc($volunteers_result)['count'] ?? 0;

// Total warehouse items
$warehouse_query = "SELECT COUNT(*) as count, SUM(quantity) as total FROM WAREHOUSE";
$warehouse_result = mysqli_query($conn, $warehouse_query);
$warehouse_data = mysqli_fetch_assoc($warehouse_result);
$stats['warehouse_items'] = $warehouse_data['count'] ?? 0;
$stats['warehouse_quantity'] = $warehouse_data['total'] ?? 0;

// Total aid seekers
$seekers_query = "SELECT COUNT(*) as count FROM Aid_Seeker";
$seekers_result = mysqli_query($conn, $seekers_query);
$stats['aid_seekers'] = mysqli_fetch_assoc($seekers_result)['count'] ?? 0;

// Total distributions
$distributions_query = "SELECT COUNT(*) as count FROM DISTRIBUTION";
$distributions_result = mysqli_query($conn, $distributions_query);
$stats['distributions'] = mysqli_fetch_assoc($distributions_result)['count'] ?? 0;
?>

<style>
.admin-dashboard {
    max-width: 1400px;
    margin: 40px auto;
    padding: 0 20px;
}

.admin-header {
    background: linear-gradient(135deg, #6b4a8e, #8e6bb4);
    color: white;
    padding: 40px;
    border-radius: 10px;
    margin-bottom: 30px;
    display: flex;
    justify-content: space-between;
    align-items: center;
}

.admin-header h1 {
    font-size: 36px;
    margin-bottom: 10px;
}

.admin-header p {
    font-size: 16px;
    opacity: 0.9;
}

.stats-grid {
    display: grid;
    grid-template-columns: repeat(auto-fit, minmax(220px, 1fr));
    gap: 20px;
    margin-bottom: 40px;
}

.admin-stat-card {
    background: white;
    padding: 25px;
    border-radius: 10px;
    box-shadow: 0 2px 10px rgba(0,0,0,0.1);
    transition: transform 0.3s, box-shadow 0.3s;
}

.admin-stat-card:hover {
    transform: translateY(-5px);
    box-shadow: 0 5px 20px rgba(0,0,0,0.15);
}

.admin-stat-card .icon {
    width: 50px;
    height: 50px;
    background: linear-gradient(135deg, #6b4a8e, #8e6bb4);
    border-radius: 50%;
    display: flex;
    align-items: center;
    justify-content: center;
    color: white;
    font-size: 20px;
    margin-bottom: 15px;
}

.admin-stat-card .value {
    font-size: 32px;
    font-weight: 700;
    color: #6b4a8e;
    margin-bottom: 5px;
}

.admin-stat-card .label {
    color: #666;
    font-size: 14px;
}

.management-grid {
    display: grid;
    grid-template-columns: repeat(auto-fit, minmax(280px, 1fr));
    gap: 25px;
    margin-bottom: 40px;
}

.management-card {
    background: white;
    padding: 30px;
    border-radius: 10px;
    box-shadow: 0 2px 10px rgba(0,0,0,0.1);
    text-decoration: none;
    color: inherit;
    transition: all 0.3s;
    display: block;
}

.management-card:hover {
    transform: translateY(-5px);
    box-shadow: 0 8px 25px rgba(0,0,0,0.15);
}

.management-card .card-icon {
    font-size: 48px;
    color: #6b4a8e;
    margin-bottom: 20px;
}

.management-card h3 {
    color: #333;
    font-size: 24px;
    margin-bottom: 10px;
}

.management-card p {
    color: #666;
    line-height: 1.6;
    margin-bottom: 15px;
}

.management-card .action-btn {
    display: inline-block;
    padding: 10px 20px;
    background: linear-gradient(135deg, #6b4a8e, #8e6bb4);
    color: white;
    border-radius: 5px;
    font-weight: 600;
    transition: all 0.3s;
}

.management-card:hover .action-btn {
    background: linear-gradient(135deg, #8e6bb4, #6b4a8e);
}

.recent-activity {
    background: white;
    padding: 30px;
    border-radius: 10px;
    box-shadow: 0 2px 10px rgba(0,0,0,0.1);
}

.recent-activity h2 {
    color: #6b4a8e;
    font-size: 24px;
    margin-bottom: 20px;
    padding-bottom: 15px;
    border-bottom: 2px solid #f0f0f0;
}

.activity-list {
    list-style: none;
}

.activity-item {
    padding: 15px;
    border-bottom: 1px solid #f0f0f0;
    display: flex;
    align-items: center;
    gap: 15px;
}

.activity-item:last-child {
    border-bottom: none;
}

.activity-icon {
    width: 40px;
    height: 40px;
    background: #f0f0f0;
    border-radius: 50%;
    display: flex;
    align-items: center;
    justify-content: center;
    color: #6b4a8e;
}

.activity-content {
    flex: 1;
}

.activity-content strong {
    color: #333;
}

.activity-content span {
    color: #999;
    font-size: 13px;
}
</style>

<div class="admin-dashboard">
    <div class="admin-header">
        <div>
            <h1>Admin Dashboard</h1>
            <p>Manage your donation management system</p>
        </div>
    </div>

    <div class="stats-grid">
        <div class="admin-stat-card">
            <div class="icon">
                <i class="fas fa-users"></i>
            </div>
            <div class="value"><?php echo $stats['users']; ?></div>
            <div class="label">Registered Users</div>
        </div>

        <div class="admin-stat-card">
            <div class="icon">
                <i class="fas fa-hand-holding-heart"></i>
            </div>
            <div class="value"><?php echo $stats['donors']; ?></div>
            <div class="label">Total Donors</div>
        </div>

        <div class="admin-stat-card">
            <div class="icon">
                <i class="fas fa-gift"></i>
            </div>
            <div class="value"><?php echo $stats['donations']; ?></div>
            <div class="label">Total Donations</div>
        </div>

        <div class="admin-stat-card">
            <div class="icon">
                <i class="fas fa-hands-helping"></i>
            </div>
            <div class="value"><?php echo $stats['volunteers']; ?></div>
            <div class="label">Volunteers</div>
        </div>

        <div class="admin-stat-card">
            <div class="icon">
                <i class="fas fa-warehouse"></i>
            </div>
            <div class="value"><?php echo $stats['warehouse_items']; ?></div>
            <div class="label">Warehouse Items</div>
        </div>

        <div class="admin-stat-card">
            <div class="icon">
                <i class="fas fa-people-carry"></i>
            </div>
            <div class="value"><?php echo $stats['aid_seekers']; ?></div>
            <div class="label">Aid Seekers</div>
        </div>

        <div class="admin-stat-card">
            <div class="icon">
                <i class="fas fa-box-open"></i>
            </div>
            <div class="value"><?php echo $stats['distributions']; ?></div>
            <div class="label">Distributions</div>
        </div>

        <div class="admin-stat-card">
            <div class="icon">
                <i class="fas fa-cubes"></i>
            </div>
            <div class="value"><?php echo $stats['warehouse_quantity']; ?></div>
            <div class="label">Total Items in Stock</div>
        </div>
    </div>

    <div class="management-grid">
        <a href="#" class="management-card">
            <div class="card-icon">
                <i class="fas fa-users-cog"></i>
            </div>
            <h3>Manage Users</h3>
            <p>View, edit, and manage registered users and their permissions</p>
            <span class="action-btn">Manage Users</span>
        </a>

        <a href="<?php echo SITE_URL; ?>/pages/donation.php" class="management-card">
            <div class="card-icon">
                <i class="fas fa-list"></i>
            </div>
            <h3>Donation History</h3>
            <p>View all donations received and track donation records</p>
            <span class="action-btn">View Donations</span>
        </a>

        <a href="<?php echo SITE_URL; ?>/pages/volunteers.php" class="management-card">
            <div class="card-icon">
                <i class="fas fa-users"></i>
            </div>
            <h3>Volunteers</h3>
            <p>Manage volunteer registrations and assignments</p>
            <span class="action-btn">Manage Volunteers</span>
        </a>

        <a href="<?php echo SITE_URL; ?>/pages/warehouse.php" class="management-card">
            <div class="card-icon">
                <i class="fas fa-warehouse"></i>
            </div>
            <h3>Warehouse</h3>
            <p>Monitor inventory and manage warehouse stock</p>
            <span class="action-btn">View Warehouse</span>
        </a>

        <a href="#" class="management-card">
            <div class="card-icon">
                <i class="fas fa-people-carry"></i>
            </div>
            <h3>Aid Seekers</h3>
            <p>Review and manage aid seeker applications</p>
            <span class="action-btn">Manage Seekers</span>
        </a>

        <a href="#" class="management-card">
            <div class="card-icon">
                <i class="fas fa-box-open"></i>
            </div>
            <h3>Distributions</h3>
            <p>Track and manage aid distribution records</p>
            <span class="action-btn">View Distributions</span>
        </a>

        <a href="<?php echo SITE_URL; ?>/pages/events.php" class="management-card">
            <div class="card-icon">
                <i class="fas fa-calendar-alt"></i>
            </div>
            <h3>Events</h3>
            <p>Create and manage charity events</p>
            <span class="action-btn">Manage Events</span>
        </a>

        <a href="#" class="management-card">
            <div class="card-icon">
                <i class="fas fa-chart-line"></i>
            </div>
            <h3>Reports</h3>
            <p>Generate reports and analytics</p>
            <span class="action-btn">View Reports</span>
        </a>
    </div>

    <div class="recent-activity">
        <h2>Recent Activity</h2>
        <ul class="activity-list">
            <?php
            // Get recent donations
            $recent_query = "SELECT d.*, don.Name, d.Date_Donated 
                           FROM DONATION d 
                           JOIN DONOR don ON d.Donor_ID = don.Donor_ID 
                           ORDER BY d.Date_Donated DESC 
                           LIMIT 5";
            $recent_result = mysqli_query($conn, $recent_query);
            
            if ($recent_result && mysqli_num_rows($recent_result) > 0):
                while ($activity = mysqli_fetch_assoc($recent_result)):
            ?>
                <li class="activity-item">
                    <div class="activity-icon">
                        <i class="fas fa-gift"></i>
                    </div>
                    <div class="activity-content">
                        <div><strong><?php echo htmlspecialchars($activity['Name']); ?></strong> made a donation</div>
                        <span><?php echo date('M d, Y g:i A', strtotime($activity['Date_Donated'])); ?></span>
                    </div>
                </li>
            <?php 
                endwhile;
            else:
            ?>
                <li class="activity-item">
                    <div class="activity-content">
                        <p style="color: #999; text-align: center; padding: 20px;">No recent activity</p>
                    </div>
                </li>
            <?php endif; ?>
        </ul>
    </div>
</div>

<?php require_once '../includes/footer.php'; ?>

