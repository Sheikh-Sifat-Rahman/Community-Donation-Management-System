<?php
$current_page = 'volunteers';
$page_title = 'Admin - Volunteer Management';
require_once '../includes/header.php';
require_once '../includes/auth.php';

// Check if user is admin
if (!isAdmin()) {
    header('Location: ' . SITE_URL . '/pages/dashboard.php');
    exit();
}

$success_message = '';
$error_message = '';

// Handle volunteer assignment to pickup
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['assign_volunteer'])) {
    $donation_id = mysqli_real_escape_string($conn, $_POST['donation_id']);
    $volunteer_id = mysqli_real_escape_string($conn, $_POST['volunteer_id']);
    $notes = mysqli_real_escape_string($conn, $_POST['notes']);
    
    $assign_query = "INSERT INTO volunteer_assignments (Donation_ID, Volunteer_ID, Notes) 
                     VALUES ('$donation_id', '$volunteer_id', '$notes')";
    
    if (mysqli_query($conn, $assign_query)) {
        $success_message = "Volunteer assigned successfully!";
    } else {
        $error_message = "Error assigning volunteer: " . mysqli_error($conn);
    }
}

// Handle aid seeker approval/rejection
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['update_seeker_status'])) {
    $seeker_id = mysqli_real_escape_string($conn, $_POST['seeker_id']);
    $status = mysqli_real_escape_string($conn, $_POST['status']);
    
    $update_query = "UPDATE Aid_Seeker SET Status = '$status' WHERE Seeker_ID = '$seeker_id'";
    
    if (mysqli_query($conn, $update_query)) {
        $success_message = "Aid seeker status updated to $status!";
    } else {
        $error_message = "Error updating status: " . mysqli_error($conn);
    }
}

// Fetch pending home pickups (not yet assigned)
$pending_pickups_query = "
    SELECT d.Donation_ID, d.Date_Donated, d.Quantity_Donated, 
           w.Item_Name, don.Name as Donor_Name, don.Phone, don.Address
    FROM DONATION d
    JOIN WAREHOUSE w ON d.Item_ID = w.Item_ID
    JOIN DONOR don ON d.Donor_ID = don.Donor_ID
    LEFT JOIN volunteer_assignments va ON d.Donation_ID = va.Donation_ID
    WHERE d.Payment_Method = 'Pickup from Home' 
    AND va.Assignment_ID IS NULL
    ORDER BY d.Date_Donated DESC
";
$pending_pickups = mysqli_query($conn, $pending_pickups_query);

// Fetch assigned pickups
$assigned_pickups_query = "
    SELECT d.Donation_ID, d.Date_Donated, d.Quantity_Donated,
           w.Item_Name, don.Name as Donor_Name, don.Phone, don.Address,
           v.Vol_Name, v.Phone as Vol_Phone, va.Status as Assignment_Status,
           va.Assignment_ID, va.Notes, va.Assigned_Date
    FROM DONATION d
    JOIN WAREHOUSE w ON d.Item_ID = w.Item_ID
    JOIN DONOR don ON d.Donor_ID = don.Donor_ID
    JOIN volunteer_assignments va ON d.Donation_ID = va.Donation_ID
    JOIN VOLUNTEERS v ON va.Volunteer_ID = v.Volunteer_ID
    WHERE d.Payment_Method = 'Pickup from Home'
    ORDER BY va.Assigned_Date DESC
";
$assigned_pickups = mysqli_query($conn, $assigned_pickups_query);

// Fetch all volunteers (prioritize those linked to registered users)
$volunteers_query = "
    SELECT v.*, 
           CASE WHEN u.id IS NOT NULL THEN 1 ELSE 0 END as is_registered_user,
           u.name as user_name
    FROM VOLUNTEERS v
    LEFT JOIN users u ON v.Email = u.email
    ORDER BY is_registered_user DESC, Vol_Name
";
$volunteers = mysqli_query($conn, $volunteers_query);

// Fetch pending aid seekers
$pending_seekers_query = "SELECT * FROM Aid_Seeker WHERE Status = 'Pending' ORDER BY Created_At DESC";
$pending_seekers = mysqli_query($conn, $pending_seekers_query);

// Fetch approved/rejected aid seekers
$processed_seekers_query = "SELECT * FROM Aid_Seeker WHERE Status IN ('Approved', 'Rejected') ORDER BY Created_At DESC LIMIT 20";
$processed_seekers = mysqli_query($conn, $processed_seekers_query);
?>

<div class="hero" style="background: linear-gradient(rgba(255, 107, 107, 0.85), rgba(255, 142, 83, 0.85)), url('<?php echo SITE_URL; ?>/assets/images/volunteer-team.jpg'); background-size: cover; background-position: center;">
    <h1><i class="fas fa-users-cog"></i> Volunteer Management</h1>
    <div class="breadcrumb">
        <a href="<?php echo SITE_URL; ?>/index.php"><i class="fas fa-home"></i> Home</a>
        <span>></span>
        <a href="<?php echo SITE_URL; ?>/pages/pages.php">Admin</a>
        <span>></span>
        <span>Volunteer Management</span>
    </div>
</div>

<div class="container">
    <?php if ($success_message): ?>
    <div style="background: #d4edda; color: #155724; padding: 15px; border-radius: 8px; margin-bottom: 30px; border: 1px solid #c3e6cb;">
        <i class="fas fa-check-circle"></i> <?php echo $success_message; ?>
    </div>
    <?php endif; ?>
    
    <?php if ($error_message): ?>
    <div style="background: #f8d7da; color: #721c24; padding: 15px; border-radius: 8px; margin-bottom: 30px; border: 1px solid #f5c6cb;">
        <i class="fas fa-exclamation-circle"></i> <?php echo $error_message; ?>
    </div>
    <?php endif; ?>

    <!-- Tabs Navigation -->
    <div style="margin-bottom: 30px; border-bottom: 3px solid #ff6b6b;">
        <div style="display: flex; gap: 10px;">
            <button onclick="showTab('pickups')" id="pickups-tab" class="tab-btn active-tab" style="padding: 15px 30px; background: #ff6b6b; color: white; border: none; cursor: pointer; font-size: 16px; font-weight: 600; border-radius: 8px 8px 0 0;">
                <i class="fas fa-truck-pickup"></i> Pickup Assignments (<?php echo mysqli_num_rows($pending_pickups); ?>)
            </button>
            <button onclick="showTab('seekers')" id="seekers-tab" class="tab-btn" style="padding: 15px 30px; background: #e0e0e0; color: #666; border: none; cursor: pointer; font-size: 16px; font-weight: 600; border-radius: 8px 8px 0 0;">
                <i class="fas fa-hands-helping"></i> Aid Seeker Approvals (<?php echo mysqli_num_rows($pending_seekers); ?>)
            </button>
        </div>
    </div>

    <!-- Pickup Assignments Tab -->
    <div id="pickups-content" class="tab-content" style="display: block;">
        <!-- Pending Pickups -->
        <div style="margin-bottom: 50px;">
            <h2 style="color: #ff6b6b; margin-bottom: 20px;"><i class="fas fa-clock"></i> Pending Home Pickups</h2>
            
            <?php if (mysqli_num_rows($pending_pickups) > 0): ?>
            <div style="background: white; border-radius: 10px; box-shadow: 0 4px 15px rgba(0,0,0,0.1); overflow: hidden;">
                <table style="width: 100%; border-collapse: collapse;">
                    <thead style="background: linear-gradient(135deg, #ff6b6b, #ff8e53); color: white;">
                        <tr>
                            <th style="padding: 15px; text-align: left;">Date</th>
                            <th style="padding: 15px; text-align: left;">Donor</th>
                            <th style="padding: 15px; text-align: left;">Item</th>
                            <th style="padding: 15px; text-align: left;">Quantity</th>
                            <th style="padding: 15px; text-align: left;">Contact</th>
                            <th style="padding: 15px; text-align: left;">Address</th>
                            <th style="padding: 15px; text-align: left;">Action</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php while ($pickup = mysqli_fetch_assoc($pending_pickups)): ?>
                        <tr style="border-bottom: 1px solid #eee;">
                            <td style="padding: 15px;"><?php echo date('M d, Y', strtotime($pickup['Date_Donated'])); ?></td>
                            <td style="padding: 15px; font-weight: 600;"><?php echo htmlspecialchars($pickup['Donor_Name']); ?></td>
                            <td style="padding: 15px;"><?php echo htmlspecialchars($pickup['Item_Name']); ?></td>
                            <td style="padding: 15px;"><?php echo $pickup['Quantity_Donated']; ?> units</td>
                            <td style="padding: 15px;"><i class="fas fa-phone"></i> <?php echo htmlspecialchars($pickup['Phone']); ?></td>
                            <td style="padding: 15px; max-width: 200px;"><?php echo htmlspecialchars($pickup['Address'] ?? 'N/A'); ?></td>
                            <td style="padding: 15px;">
                                <button onclick="showAssignModal(<?php echo $pickup['Donation_ID']; ?>)" style="background: #28a745; color: white; border: none; padding: 8px 16px; border-radius: 5px; cursor: pointer;">
                                    <i class="fas fa-user-plus"></i> Assign
                                </button>
                            </td>
                        </tr>
                        <?php endwhile; ?>
                    </tbody>
                </table>
            </div>
            <?php else: ?>
            <div style="text-align: center; padding: 40px; background: #f8f9fa; border-radius: 10px;">
                <i class="fas fa-check-circle" style="font-size: 48px; color: #28a745; margin-bottom: 15px;"></i>
                <p style="font-size: 18px; color: #666;">No pending pickups at the moment</p>
            </div>
            <?php endif; ?>
        </div>

        <!-- Assigned Pickups -->
        <div>
            <h2 style="color: #ff6b6b; margin-bottom: 20px;"><i class="fas fa-check-double"></i> Assigned Pickups</h2>
            
            <?php if (mysqli_num_rows($assigned_pickups) > 0): ?>
            <div style="display: grid; grid-template-columns: repeat(auto-fit, minmax(350px, 1fr)); gap: 20px;">
                <?php while ($assigned = mysqli_fetch_assoc($assigned_pickups)): ?>
                <div style="background: white; border-radius: 10px; padding: 20px; box-shadow: 0 4px 15px rgba(0,0,0,0.1);">
                    <div style="display: flex; justify-content: space-between; align-items: center; margin-bottom: 15px;">
                        <h3 style="color: #333; margin: 0;"><?php echo htmlspecialchars($assigned['Item_Name']); ?></h3>
                        <span style="background: <?php echo $assigned['Assignment_Status'] == 'Completed' ? '#28a745' : '#ffc107'; ?>; color: white; padding: 5px 12px; border-radius: 20px; font-size: 12px; font-weight: 600;">
                            <?php echo $assigned['Assignment_Status']; ?>
                        </span>
                    </div>
                    <p style="color: #666; margin-bottom: 10px;"><strong><i class="fas fa-user"></i> Donor:</strong> <?php echo htmlspecialchars($assigned['Donor_Name']); ?></p>
                    <p style="color: #666; margin-bottom: 10px;"><strong><i class="fas fa-box"></i> Quantity:</strong> <?php echo $assigned['Quantity_Donated']; ?> units</p>
                    <p style="color: #666; margin-bottom: 10px;"><strong><i class="fas fa-user-check"></i> Volunteer:</strong> <?php echo htmlspecialchars($assigned['Vol_Name']); ?></p>
                    <p style="color: #666; margin-bottom: 10px;"><strong><i class="fas fa-phone"></i> Contact:</strong> <?php echo htmlspecialchars($assigned['Vol_Phone']); ?></p>
                    <p style="color: #666; margin-bottom: 10px;"><strong><i class="fas fa-calendar"></i> Assigned:</strong> <?php echo date('M d, Y', strtotime($assigned['Assigned_Date'])); ?></p>
                    <?php if ($assigned['Notes']): ?>
                    <p style="color: #666; margin-top: 10px; padding: 10px; background: #f8f9fa; border-radius: 5px;"><strong><i class="fas fa-sticky-note"></i> Notes:</strong> <?php echo htmlspecialchars($assigned['Notes']); ?></p>
                    <?php endif; ?>
                </div>
                <?php endwhile; ?>
            </div>
            <?php else: ?>
            <div style="text-align: center; padding: 40px; background: #f8f9fa; border-radius: 10px;">
                <i class="fas fa-tasks" style="font-size: 48px; color: #ddd; margin-bottom: 15px;"></i>
                <p style="font-size: 18px; color: #666;">No assigned pickups yet</p>
            </div>
            <?php endif; ?>
        </div>
    </div>

    <!-- Aid Seeker Approvals Tab -->
    <div id="seekers-content" class="tab-content" style="display: none;">
        <!-- Pending Seekers -->
        <div style="margin-bottom: 50px;">
            <h2 style="color: #ff6b6b; margin-bottom: 20px;"><i class="fas fa-hourglass-half"></i> Pending Approvals</h2>
            
            <?php if (mysqli_num_rows($pending_seekers) > 0): ?>
            <div style="display: grid; grid-template-columns: repeat(auto-fit, minmax(400px, 1fr)); gap: 25px;">
                <?php while ($seeker = mysqli_fetch_assoc($pending_seekers)): ?>
                <div style="background: white; border-radius: 15px; padding: 25px; box-shadow: 0 4px 20px rgba(0,0,0,0.1); border-left: 5px solid #ffc107;">
                    <h3 style="color: #333; margin-bottom: 15px;"><i class="fas fa-user"></i> <?php echo htmlspecialchars($seeker['Seeker_Name']); ?></h3>
                    
                    <div style="display: grid; grid-template-columns: 1fr 1fr; gap: 10px; margin-bottom: 15px;">
                        <p style="color: #666;"><strong>NID:</strong> <?php echo htmlspecialchars($seeker['National_ID']); ?></p>
                        <p style="color: #666;"><strong>Phone:</strong> <?php echo htmlspecialchars($seeker['Phone']); ?></p>
                        <p style="color: #666;"><strong>Income:</strong> ৳<?php echo number_format($seeker['Monthly_Income']); ?></p>
                        <p style="color: #666;"><strong>Family:</strong> <?php echo $seeker['Family_Members']; ?> members</p>
                    </div>
                    
                    <?php if ($seeker['Reason']): ?>
                    <div style="background: #fff3cd; padding: 12px; border-radius: 8px; margin-bottom: 15px;">
                        <p style="color: #856404; margin: 0;"><strong><i class="fas fa-info-circle"></i> Reason:</strong> <?php echo htmlspecialchars($seeker['Reason']); ?></p>
                    </div>
                    <?php endif; ?>
                    
                    <p style="color: #666; margin-bottom: 15px;"><strong><i class="fas fa-map-marker-alt"></i> Address:</strong> 
                        <?php echo htmlspecialchars($seeker['House']) . ', ' . htmlspecialchars($seeker['Street']) . ', ' . htmlspecialchars($seeker['Village']) . ', ' . htmlspecialchars($seeker['Thana']) . ', ' . htmlspecialchars($seeker['District']); ?>
                    </p>
                    
                    <div style="display: flex; gap: 10px;">
                        <form method="POST" style="flex: 1;">
                            <input type="hidden" name="seeker_id" value="<?php echo $seeker['Seeker_ID']; ?>">
                            <input type="hidden" name="status" value="Approved">
                            <button type="submit" name="update_seeker_status" style="width: 100%; background: #28a745; color: white; border: none; padding: 12px; border-radius: 8px; font-weight: 600; cursor: pointer;">
                                <i class="fas fa-check"></i> Approve
                            </button>
                        </form>
                        <form method="POST" style="flex: 1;">
                            <input type="hidden" name="seeker_id" value="<?php echo $seeker['Seeker_ID']; ?>">
                            <input type="hidden" name="status" value="Rejected">
                            <button type="submit" name="update_seeker_status" style="width: 100%; background: #dc3545; color: white; border: none; padding: 12px; border-radius: 8px; font-weight: 600; cursor: pointer;">
                                <i class="fas fa-times"></i> Reject
                            </button>
                        </form>
                    </div>
                </div>
                <?php endwhile; ?>
            </div>
            <?php else: ?>
            <div style="text-align: center; padding: 40px; background: #f8f9fa; border-radius: 10px;">
                <i class="fas fa-check-circle" style="font-size: 48px; color: #28a745; margin-bottom: 15px;"></i>
                <p style="font-size: 18px; color: #666;">No pending aid seeker applications</p>
            </div>
            <?php endif; ?>
        </div>

        <!-- Processed Seekers -->
        <div>
            <h2 style="color: #ff6b6b; margin-bottom: 20px;"><i class="fas fa-history"></i> Recent Decisions</h2>
            
            <?php if (mysqli_num_rows($processed_seekers) > 0): ?>
            <div style="background: white; border-radius: 10px; box-shadow: 0 4px 15px rgba(0,0,0,0.1); overflow: hidden;">
                <table style="width: 100%; border-collapse: collapse;">
                    <thead style="background: linear-gradient(135deg, #ff6b6b, #ff8e53); color: white;">
                        <tr>
                            <th style="padding: 15px; text-align: left;">Name</th>
                            <th style="padding: 15px; text-align: left;">NID</th>
                            <th style="padding: 15px; text-align: left;">Phone</th>
                            <th style="padding: 15px; text-align: left;">Income</th>
                            <th style="padding: 15px; text-align: left;">Date Applied</th>
                            <th style="padding: 15px; text-align: left;">Status</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php while ($processed = mysqli_fetch_assoc($processed_seekers)): ?>
                        <tr style="border-bottom: 1px solid #eee;">
                            <td style="padding: 15px; font-weight: 600;"><?php echo htmlspecialchars($processed['Seeker_Name']); ?></td>
                            <td style="padding: 15px;"><?php echo htmlspecialchars($processed['National_ID']); ?></td>
                            <td style="padding: 15px;"><?php echo htmlspecialchars($processed['Phone']); ?></td>
                            <td style="padding: 15px;">৳<?php echo number_format($processed['Monthly_Income']); ?></td>
                            <td style="padding: 15px;"><?php echo date('M d, Y', strtotime($processed['Created_At'])); ?></td>
                            <td style="padding: 15px;">
                                <span style="background: <?php echo $processed['Status'] == 'Approved' ? '#28a745' : '#dc3545'; ?>; color: white; padding: 5px 12px; border-radius: 20px; font-size: 12px; font-weight: 600;">
                                    <?php echo $processed['Status']; ?>
                                </span>
                            </td>
                        </tr>
                        <?php endwhile; ?>
                    </tbody>
                </table>
            </div>
            <?php endif; ?>
        </div>
    </div>
</div>

<!-- Assign Volunteer Modal -->
<div id="assignModal" style="display: none; position: fixed; top: 0; left: 0; width: 100%; height: 100%; background: rgba(0,0,0,0.7); z-index: 1000; justify-content: center; align-items: center;">
    <div style="background: white; padding: 40px; border-radius: 15px; max-width: 500px; width: 90%;">
        <h2 style="color: #ff6b6b; margin-bottom: 20px;"><i class="fas fa-user-plus"></i> Assign Volunteer</h2>
        <form method="POST">
            <input type="hidden" name="donation_id" id="modal_donation_id">
            
            <div style="margin-bottom: 20px;">
                <label style="display: block; margin-bottom: 8px; color: #333; font-weight: 600;">Select Volunteer *</label>
                <select name="volunteer_id" required style="width: 100%; padding: 12px; border: 2px solid #e0e0e0; border-radius: 8px; font-size: 14px;">
                    <option value="">-- Choose a volunteer --</option>
                    <?php mysqli_data_seek($volunteers, 0); while ($vol = mysqli_fetch_assoc($volunteers)): ?>
                        <option value="<?php echo $vol['Volunteer_ID']; ?>">
                            <?php echo htmlspecialchars($vol['Vol_Name']); ?> - <?php echo htmlspecialchars($vol['Area_Assigned']); ?>
                            <?php echo $vol['is_registered_user'] ? ' (Registered User)' : ''; ?>
                        </option>
                    <?php endwhile; ?>
                </select>
            </div>
            
            <div style="margin-bottom: 20px;">
                <label style="display: block; margin-bottom: 8px; color: #333; font-weight: 600;">Notes (Optional)</label>
                <textarea name="notes" rows="3" style="width: 100%; padding: 12px; border: 2px solid #e0e0e0; border-radius: 8px; font-size: 14px;" placeholder="Add any special instructions..."></textarea>
            </div>
            
            <div style="display: flex; gap: 10px;">
                <button type="button" onclick="closeAssignModal()" style="flex: 1; background: #6c757d; color: white; border: none; padding: 12px; border-radius: 8px; font-weight: 600; cursor: pointer;">
                    Cancel
                </button>
                <button type="submit" name="assign_volunteer" style="flex: 1; background: #28a745; color: white; border: none; padding: 12px; border-radius: 8px; font-weight: 600; cursor: pointer;">
                    <i class="fas fa-check"></i> Assign
                </button>
            </div>
        </form>
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

function showAssignModal(donationId) {
    document.getElementById('modal_donation_id').value = donationId;
    document.getElementById('assignModal').style.display = 'flex';
}

function closeAssignModal() {
    document.getElementById('assignModal').style.display = 'none';
}

// Close modal when clicking outside
document.getElementById('assignModal').addEventListener('click', function(e) {
    if (e.target === this) {
        closeAssignModal();
    }
});
</script>

<?php require_once '../includes/footer.php'; ?>
