<?php
$current_page = 'dashboard';
$page_title = 'My Dashboard';
require_once '../includes/header.php';
require_once '../includes/auth.php';

// Ensure user is logged in
checkAuth();

// Route admins to admin dashboard
if (isAdmin()) {
    header('Location: ' . SITE_URL . '/pages/pages.php');
    exit();
}

$user_id = $_SESSION['user_id'];

// Fetch user's donation history from view
$donations_query = "SELECT * FROM user_donation_history WHERE user_id = '$user_id'";
$donations = mysqli_query($conn, $donations_query);
$total_donations = mysqli_num_rows($donations);
$total_quantity = 0;
while ($don = mysqli_fetch_assoc($donations)) {
    $total_quantity += $don['Quantity_Donated'];
}
mysqli_data_seek($donations, 0);

// Fetch user's volunteer activities from view
$volunteer_query = "SELECT * FROM user_volunteer_activities WHERE user_id = '$user_id'";
$volunteer_activities = mysqli_query($conn, $volunteer_query);
$total_tasks = mysqli_num_rows($volunteer_activities);
$completed_tasks = 0;
while ($task = mysqli_fetch_assoc($volunteer_activities)) {
    if ($task['Status'] == 'Completed') $completed_tasks++;
}
mysqli_data_seek($volunteer_activities, 0);

// Fetch user's aid requests from view
$aid_query = "SELECT * FROM user_aid_requests WHERE user_id = '$user_id'";
$aid_requests = mysqli_query($conn, $aid_query);
$total_aid_requests = mysqli_num_rows($aid_requests);
?>

<div class="hero" style="background: linear-gradient(135deg, #ff6b6b, #ff8e53);">
    <h1><i class="fas fa-tachometer-alt"></i> My Dashboard</h1>
    <p style="font-size: 18px; margin-top: 15px;">Welcome back, <?php echo htmlspecialchars($_SESSION['user_name']); ?>!</p>
</div>

<div class="container">
    <!-- Stats Cards -->
    <div style="display: grid; grid-template-columns: repeat(auto-fit, minmax(250px, 1fr)); gap: 25px; margin-bottom: 50px;">
        <div style="background: linear-gradient(135deg, #ff6b6b, #ff8e53); color: white; padding: 30px; border-radius: 15px; box-shadow: 0 4px 20px rgba(0,0,0,0.15);">
            <div style="display: flex; align-items: center; justify-content: space-between;">
                <div>
                    <p style="font-size: 14px; opacity: 0.9; margin-bottom: 10px;">Total Donations</p>
                    <h2 style="font-size: 36px; margin: 0;"><?php echo $total_donations; ?></h2>
                    <p style="font-size: 12px; opacity: 0.8; margin-top: 5px;"><?php echo $total_quantity; ?> items donated</p>
                </div>
                <i class="fas fa-hand-holding-heart" style="font-size: 48px; opacity: 0.3;"></i>
            </div>
        </div>
        
        <div style="background: linear-gradient(135deg, #ff8e53, #ffa07a); color: white; padding: 30px; border-radius: 15px; box-shadow: 0 4px 20px rgba(0,0,0,0.15);">
            <div style="display: flex; align-items: center; justify-content: space-between;">
                <div>
                    <p style="font-size: 14px; opacity: 0.9; margin-bottom: 10px;">Volunteer Tasks</p>
                    <h2 style="font-size: 36px; margin: 0;"><?php echo $total_tasks; ?></h2>
                    <p style="font-size: 12px; opacity: 0.8; margin-top: 5px;"><?php echo $completed_tasks; ?> completed</p>
                </div>
                <i class="fas fa-hands-helping" style="font-size: 48px; opacity: 0.3;"></i>
            </div>
        </div>
        
        <div style="background: linear-gradient(135deg, #d9534f, #c9302c); color: white; padding: 30px; border-radius: 15px; box-shadow: 0 4px 20px rgba(0,0,0,0.15);">
            <div style="display: flex; align-items: center; justify-content: space-between;">
                <div>
                    <p style="font-size: 14px; opacity: 0.9; margin-bottom: 10px;">Aid Requests</p>
                    <h2 style="font-size: 36px; margin: 0;"><?php echo $total_aid_requests; ?></h2>
                    <p style="font-size: 12px; opacity: 0.8; margin-top: 5px;">Application status</p>
                </div>
                <i class="fas fa-life-ring" style="font-size: 48px; opacity: 0.3;"></i>
            </div>
        </div>
    </div>

    <!-- Tabs Navigation -->
    <div style="margin-bottom: 30px; border-bottom: 3px solid #ff6b6b;">
        <div style="display: flex; gap: 10px;">
            <button onclick="showTab('donations')" id="donations-tab" class="tab-btn active-tab" style="padding: 15px 30px; background: #ff6b6b; color: white; border: none; cursor: pointer; font-size: 16px; font-weight: 600; border-radius: 8px 8px 0 0;">
                <i class="fas fa-gift"></i> My Donations
            </button>
            <button onclick="showTab('volunteer')" id="volunteer-tab" class="tab-btn" style="padding: 15px 30px; background: #e0e0e0; color: #666; border: none; cursor: pointer; font-size: 16px; font-weight: 600; border-radius: 8px 8px 0 0;">
                <i class="fas fa-user-check"></i> My Volunteer Work
            </button>
            <button onclick="showTab('aid')" id="aid-tab" class="tab-btn" style="padding: 15px 30px; background: #e0e0e0; color: #666; border: none; cursor: pointer; font-size: 16px; font-weight: 600; border-radius: 8px 8px 0 0;">
                <i class="fas fa-hands"></i> My Aid Requests
            </button>
        </div>
    </div>

    <!-- Donations Tab -->
    <div id="donations-content" class="tab-content" style="display: block;">
        <h2 style="color: #ff6b6b; margin-bottom: 25px;"><i class="fas fa-history"></i> Donation History</h2>
        
        <?php if ($total_donations > 0): ?>
        <div style="background: white; border-radius: 10px; box-shadow: 0 4px 15px rgba(0,0,0,0.1); overflow: hidden;">
            <table style="width: 100%; border-collapse: collapse;">
                <thead style="background: linear-gradient(135deg, #ff6b6b, #ff8e53); color: white;">
                    <tr>
                        <th style="padding: 15px; text-align: left;">Date</th>
                        <th style="padding: 15px; text-align: left;">Item</th>
                        <th style="padding: 15px; text-align: left;">Quantity</th>
                        <th style="padding: 15px; text-align: left;">Payment Method</th>
                        <th style="padding: 15px; text-align: left;">Status</th>
                    </tr>
                </thead>
                <tbody>
                    <?php while ($donation = mysqli_fetch_assoc($donations)): ?>
                    <tr style="border-bottom: 1px solid #eee;">
                        <td style="padding: 15px;"><?php echo date('M d, Y', strtotime($donation['Date_Donated'])); ?></td>
                        <td style="padding: 15px; font-weight: 600;"><?php echo htmlspecialchars($donation['Item_Name']); ?></td>
                        <td style="padding: 15px;"><?php echo $donation['Quantity_Donated']; ?> units</td>
                        <td style="padding: 15px;">
                            <span style="padding: 5px 12px; border-radius: 20px; font-size: 12px; font-weight: 600; background: <?php echo $donation['Payment_Method'] == 'Pickup from Home' ? '#fff3cd' : '#d1ecf1'; ?>; color: <?php echo $donation['Payment_Method'] == 'Pickup from Home' ? '#856404' : '#0c5460'; ?>;">
                                <?php echo $donation['Payment_Method']; ?>
                            </span>
                        </td>
                        <td style="padding: 15px;">
                            <span style="background: #28a745; color: white; padding: 5px 12px; border-radius: 20px; font-size: 12px; font-weight: 600;">
                                <i class="fas fa-check"></i> Completed
                            </span>
                        </td>
                    </tr>
                    <?php endwhile; ?>
                </tbody>
            </table>
        </div>
        <?php else: ?>
        <div style="text-align: center; padding: 60px; background: #f8f9fa; border-radius: 15px;">
            <i class="fas fa-box-open" style="font-size: 64px; color: #ddd; margin-bottom: 20px;"></i>
            <p style="font-size: 20px; color: #666; margin-bottom: 20px;">You haven't made any donations yet</p>
            <a href="<?php echo SITE_URL; ?>/pages/donate.php" class="btn" style="display: inline-block; background: linear-gradient(135deg, #ff6b6b, #ff8e53); color: white; padding: 15px 35px; border-radius: 30px; text-decoration: none; font-weight: 600;">
                <i class="fas fa-heart"></i> Make Your First Donation
            </a>
        </div>
        <?php endif; ?>
    </div>

    <!-- Volunteer Work Tab -->
    <div id="volunteer-content" class="tab-content" style="display: none;">
        <h2 style="color: #ff6b6b; margin-bottom: 25px;"><i class="fas fa-tasks"></i> My Volunteer Activities</h2>
        
        <?php if ($total_tasks > 0): ?>
        <div style="display: grid; grid-template-columns: repeat(auto-fit, minmax(350px, 1fr)); gap: 25px;">
            <?php while ($task = mysqli_fetch_assoc($volunteer_activities)): ?>
            <div style="background: white; border-radius: 15px; padding: 25px; box-shadow: 0 4px 20px rgba(0,0,0,0.1); border-left: 5px solid <?php echo $task['Status'] == 'Completed' ? '#28a745' : '#ffc107'; ?>;">
                <div style="display: flex; justify-content: space-between; align-items: center; margin-bottom: 15px;">
                    <h3 style="color: #333; margin: 0;"><?php echo htmlspecialchars($task['Item_Name']); ?></h3>
                    <span style="background: <?php echo $task['Status'] == 'Completed' ? '#28a745' : '#ffc107'; ?>; color: white; padding: 5px 12px; border-radius: 20px; font-size: 12px; font-weight: 600;">
                        <?php echo $task['Status']; ?>
                    </span>
                </div>
                
                <div style="background: #f8f9fa; padding: 15px; border-radius: 10px; margin-bottom: 15px;">
                    <p style="color: #666; margin-bottom: 8px;"><strong><i class="fas fa-map-marker-alt"></i> Pickup:</strong> <?php echo htmlspecialchars($task['Pickup_Address']); ?></p>
                    <p style="color: #666; margin-bottom: 8px;"><strong><i class="fas fa-phone"></i> Contact:</strong> <?php echo htmlspecialchars($task['Donor_Phone']); ?></p>
                    <p style="color: #666; margin-bottom: 0;"><strong><i class="fas fa-box"></i> Quantity:</strong> <?php echo $task['Quantity_Donated']; ?> units</p>
                </div>
                
                <div style="display: grid; grid-template-columns: 1fr 1fr; gap: 10px; font-size: 13px; color: #666;">
                    <div>
                        <i class="fas fa-calendar-plus"></i> <strong>Assigned:</strong><br>
                        <?php echo date('M d, Y', strtotime($task['Assigned_Date'])); ?>
                    </div>
                    <?php if ($task['Completed_Date']): ?>
                    <div>
                        <i class="fas fa-calendar-check"></i> <strong>Completed:</strong><br>
                        <?php echo date('M d, Y', strtotime($task['Completed_Date'])); ?>
                    </div>
                    <?php endif; ?>
                </div>
                
                <?php if ($task['Notes']): ?>
                <div style="margin-top: 15px; padding: 12px; background: #fff3cd; border-radius: 8px;">
                    <p style="color: #856404; margin: 0; font-size: 13px;"><strong><i class="fas fa-sticky-note"></i> Notes:</strong> <?php echo htmlspecialchars($task['Notes']); ?></p>
                </div>
                <?php endif; ?>
            </div>
            <?php endwhile; ?>
        </div>
        <?php else: ?>
        <div style="text-align: center; padding: 60px; background: #f8f9fa; border-radius: 15px;">
            <i class="fas fa-user-friends" style="font-size: 64px; color: #ddd; margin-bottom: 20px;"></i>
            <p style="font-size: 20px; color: #666; margin-bottom: 20px;">You haven't volunteered for any tasks yet</p>
            <a href="<?php echo SITE_URL; ?>/pages/user_volunteers.php" class="btn" style="display: inline-block; background: linear-gradient(135deg, #ff8e53, #ffa07a); color: white; padding: 15px 35px; border-radius: 30px; text-decoration: none; font-weight: 600;">
                <i class="fas fa-hands-helping"></i> Browse Available Tasks
            </a>
        </div>
        <?php endif; ?>
    </div>

    <!-- Aid Requests Tab -->
    <div id="aid-content" class="tab-content" style="display: none;">
        <h2 style="color: #ff6b6b; margin-bottom: 25px;"><i class="fas fa-file-alt"></i> My Aid Applications</h2>
        
        <?php if ($total_aid_requests > 0): ?>
        <div style="display: grid; grid-template-columns: repeat(auto-fit, minmax(400px, 1fr)); gap: 25px;">
            <?php while ($aid = mysqli_fetch_assoc($aid_requests)): ?>
            <div style="background: white; border-radius: 15px; padding: 25px; box-shadow: 0 4px 20px rgba(0,0,0,0.1); 
                border-left: 5px solid <?php echo $aid['Status'] == 'Approved' ? '#28a745' : ($aid['Status'] == 'Rejected' ? '#dc3545' : '#ffc107'); ?>;">
                
                <div style="display: flex; justify-content: space-between; align-items: center; margin-bottom: 20px;">
                    <h3 style="color: #333; margin: 0;"><?php echo htmlspecialchars($aid['Seeker_Name']); ?></h3>
                    <span style="background: <?php echo $aid['Status'] == 'Approved' ? '#28a745' : ($aid['Status'] == 'Rejected' ? '#dc3545' : '#ffc107'); ?>; 
                        color: white; padding: 5px 15px; border-radius: 20px; font-size: 13px; font-weight: 600;">
                        <?php echo $aid['Status']; ?>
                    </span>
                </div>
                
                <div style="background: #f8f9fa; padding: 15px; border-radius: 10px; margin-bottom: 15px;">
                    <p style="color: #666; margin-bottom: 8px;"><strong><i class="fas fa-id-card"></i> NID:</strong> <?php echo htmlspecialchars($aid['National_ID']); ?></p>
                    <p style="color: #666; margin-bottom: 8px;"><strong><i class="fas fa-phone"></i> Phone:</strong> <?php echo htmlspecialchars($aid['Phone']); ?></p>
                    <p style="color: #666; margin-bottom: 8px;"><strong><i class="fas fa-money-bill-wave"></i> Income:</strong> ৳<?php echo number_format($aid['Monthly_Income']); ?>/month</p>
                    <p style="color: #666; margin-bottom: 0;"><strong><i class="fas fa-users"></i> Family:</strong> <?php echo $aid['Family_Members']; ?> members</p>
                </div>
                
                <?php if ($aid['Reason']): ?>
                <div style="background: #e7f3ff; padding: 12px; border-radius: 8px; margin-bottom: 15px;">
                    <p style="color: #004085; margin: 0; font-size: 13px;"><strong><i class="fas fa-comment-alt"></i> Reason:</strong> <?php echo htmlspecialchars($aid['Reason']); ?></p>
                </div>
                <?php endif; ?>
                
                <div style="padding: 12px; background: #f1f1f1; border-radius: 8px; font-size: 13px; color: #666;">
                    <strong><i class="fas fa-map-marked-alt"></i> Address:</strong><br>
                    <?php echo htmlspecialchars($aid['House']) . ', ' . htmlspecialchars($aid['Street']) . ', ' . htmlspecialchars($aid['Village']) . ', ' . htmlspecialchars($aid['Thana']) . ', ' . htmlspecialchars($aid['District']); ?>
                </div>
                
                <p style="text-align: center; margin-top: 15px; font-size: 12px; color: #999;">
                    <i class="fas fa-calendar"></i> Applied on <?php echo date('F d, Y', strtotime($aid['Created_At'])); ?>
                </p>
            </div>
            <?php endwhile; ?>
        </div>
        <?php else: ?>
        <div style="text-align: center; padding: 60px; background: #f8f9fa; border-radius: 15px;">
            <i class="fas fa-hand-holding-heart" style="font-size: 64px; color: #ddd; margin-bottom: 20px;"></i>
            <p style="font-size: 20px; color: #666; margin-bottom: 20px;">You haven't submitted any aid requests</p>
            <a href="<?php echo SITE_URL; ?>/pages/aid_seekers.php" class="btn" style="display: inline-block; background: linear-gradient(135deg, #d9534f, #c9302c); color: white; padding: 15px 35px; border-radius: 30px; text-decoration: none; font-weight: 600;">
                <i class="fas fa-plus-circle"></i> Apply for Aid
            </a>
        </div>
        <?php endif; ?>
    </div>
</div>

<script>
function showTab(tabName) {
    // Hide all tabs
    document.querySelectorAll('.tab-content').forEach(content => {
        content.style.display = 'none';
    });
    
    // Reset all tab buttons
    document.querySelectorAll('.tab-btn').forEach(btn => {
        btn.style.background = '#e0e0e0';
        btn.style.color = '#666';
        btn.classList.remove('active-tab');
    });
    
    // Show selected tab
    document.getElementById(tabName + '-content').style.display = 'block';
    const activeBtn = document.getElementById(tabName + '-tab');
    activeBtn.style.background = '#ff6b6b';
    activeBtn.style.color = 'white';
    activeBtn.classList.add('active-tab');
}
</script>

<?php require_once '../includes/footer.php'; ?>
