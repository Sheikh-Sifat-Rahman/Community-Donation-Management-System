<?php
$current_page = 'volunteers';
$page_title = 'Volunteer - Available Tasks';
require_once '../includes/header.php';
require_once '../includes/auth.php';

// Ensure user is logged in and NOT admin
checkAuth();
if (isAdmin()) {
    header('Location: ' . SITE_URL . '/pages/admin_volunteers.php');
    exit();
}

$user_id = $_SESSION['user_id'];
$success_message = '';
$error_message = '';

// Check if user has pending volunteer task
$check_pending_query = "
    SELECT va.Assignment_ID, va.Status, w.Item_Name, d.Date_Donated
    FROM volunteer_assignments va
    JOIN DONATION d ON va.Donation_ID = d.Donation_ID
    JOIN WAREHOUSE w ON d.Item_ID = w.Item_ID
    JOIN VOLUNTEERS v ON va.Volunteer_ID = v.Volunteer_ID
    JOIN users u ON v.Email = u.email
    WHERE u.id = '$user_id' AND va.Status = 'Pending'
";
$pending_result = mysqli_query($conn, $check_pending_query);
$has_pending_task = mysqli_num_rows($pending_result) > 0;
$pending_task = $has_pending_task ? mysqli_fetch_assoc($pending_result) : null;

// Handle self-assignment
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['self_assign'])) {
    if ($has_pending_task) {
        $error_message = "You already have a pending task. Please complete it before taking another one.";
    } else {
        $donation_id = mysqli_real_escape_string($conn, $_POST['donation_id']);
        
        // Get or create volunteer record for this user
        $get_volunteer_query = "SELECT Volunteer_ID FROM VOLUNTEERS WHERE Email = '{$_SESSION['user_email']}'";
        $vol_result = mysqli_query($conn, $get_volunteer_query);
        
        if (mysqli_num_rows($vol_result) > 0) {
            $volunteer = mysqli_fetch_assoc($vol_result);
            $volunteer_id = $volunteer['Volunteer_ID'];
        } else {
            // Create volunteer record if doesn't exist
            $create_vol_query = "INSERT INTO VOLUNTEERS (Vol_Name, Email, Phone) 
                                 VALUES ('{$_SESSION['user_name']}', '{$_SESSION['user_email']}', '')";
            mysqli_query($conn, $create_vol_query);
            $volunteer_id = mysqli_insert_id($conn);
        }
        
        // Assign task
        $assign_query = "INSERT INTO volunteer_assignments (Donation_ID, Volunteer_ID, Notes) 
                         VALUES ('$donation_id', '$volunteer_id', 'Self-assigned by user')";
        
        if (mysqli_query($conn, $assign_query)) {
            $success_message = "Task assigned successfully! You can view it in your dashboard.";
            $has_pending_task = true;
            
            // Refresh pending task info
            $pending_result = mysqli_query($conn, $check_pending_query);
            $pending_task = mysqli_fetch_assoc($pending_result);
        } else {
            $error_message = "Error assigning task: " . mysqli_error($conn);
        }
    }
}

// Handle task completion
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['complete_task'])) {
    $assignment_id = mysqli_real_escape_string($conn, $_POST['assignment_id']);
    
    $complete_query = "UPDATE volunteer_assignments 
                       SET Status = 'Completed', Completed_Date = NOW() 
                       WHERE Assignment_ID = '$assignment_id'";
    
    if (mysqli_query($conn, $complete_query)) {
        $success_message = "Great job! Task marked as completed.";
        $has_pending_task = false;
        $pending_task = null;
    } else {
        $error_message = "Error completing task: " . mysqli_error($conn);
    }
}

// Fetch available pickup tasks from view
$available_tasks_query = "SELECT * FROM available_pickup_tasks ORDER BY Date_Donated ASC LIMIT 20";
$available_tasks = mysqli_query($conn, $available_tasks_query);
?>

<div class="hero" style="background: linear-gradient(rgba(245, 87, 108, 0.85), rgba(240, 147, 251, 0.85)), url('<?php echo SITE_URL; ?>/assets/images/volunteer-team.jpg'); background-size: cover; background-position: center;">
    <h1><i class="fas fa-hands-helping"></i> Volunteer Tasks</h1>
    <p style="font-size: 18px; margin-top: 15px;">Help our community by collecting donations from homes</p>
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

    <?php if ($has_pending_task): ?>
    <!-- Current Task Alert -->
    <div style="background: linear-gradient(135deg, #f093fb 0%, #f5576c 100%); color: white; padding: 30px; border-radius: 15px; margin-bottom: 40px; box-shadow: 0 4px 20px rgba(240, 147, 251, 0.4);">
        <h2 style="color: white; margin-bottom: 15px;"><i class="fas fa-tasks"></i> Your Current Task</h2>
        <div style="background: rgba(255,255,255,0.2); padding: 20px; border-radius: 10px; backdrop-filter: blur(10px);">
            <h3 style="color: white; margin-bottom: 10px;"><?php echo htmlspecialchars($pending_task['Item_Name']); ?></h3>
            <p style="color: white; opacity: 0.9; margin-bottom: 15px;">
                <i class="fas fa-calendar"></i> Donation Date: <?php echo date('F d, Y', strtotime($pending_task['Date_Donated'])); ?>
            </p>
            <div style="display: flex; gap: 10px; align-items: center;">
                <span style="background: #ffc107; color: #000; padding: 8px 16px; border-radius: 20px; font-weight: 600; font-size: 14px;">
                    <i class="fas fa-hourglass-half"></i> <?php echo $pending_task['Status']; ?>
                </span>
                <form method="POST" style="margin: 0;">
                    <input type="hidden" name="assignment_id" value="<?php echo $pending_task['Assignment_ID']; ?>">
                    <button type="submit" name="complete_task" style="background: #28a745; color: white; border: none; padding: 8px 20px; border-radius: 20px; cursor: pointer; font-weight: 600;">
                        <i class="fas fa-check-double"></i> Mark as Completed
                    </button>
                </form>
            </div>
        </div>
        <p style="margin-top: 15px; opacity: 0.9; font-size: 14px;">
            <i class="fas fa-info-circle"></i> You can only have one active task at a time. Complete this task to take on another one.
        </p>
    </div>
    <?php endif; ?>

    <!-- Available Tasks -->
    <div style="margin-bottom: 30px;">
        <h2 style="color: #6b4a8e; margin-bottom: 10px;"><i class="fas fa-list"></i> Available Pickup Tasks</h2>
        <p style="color: #666; margin-bottom: 25px;">These donors need someone to collect their donations from their homes. Take a task and help the community!</p>
        
        <?php if (mysqli_num_rows($available_tasks) > 0): ?>
        <div style="display: grid; grid-template-columns: repeat(auto-fit, minmax(350px, 1fr)); gap: 25px;">
            <?php while ($task = mysqli_fetch_assoc($available_tasks)): ?>
            <div style="background: white; border-radius: 15px; padding: 25px; box-shadow: 0 4px 20px rgba(0,0,0,0.1); border-top: 4px solid #6b4a8e; transition: transform 0.3s;">
                <div style="margin-bottom: 15px;">
                    <h3 style="color: #333; margin-bottom: 5px;"><?php echo htmlspecialchars($task['Item_Name']); ?></h3>
                    <p style="color: #999; font-size: 14px;">
                        <i class="fas fa-clock"></i> Posted <?php echo date('M d, Y', strtotime($task['Date_Donated'])); ?>
                    </p>
                </div>
                
                <div style="background: #f8f9fa; padding: 15px; border-radius: 10px; margin-bottom: 15px;">
                    <p style="color: #666; margin-bottom: 10px;">
                        <i class="fas fa-box" style="color: #6b4a8e; width: 20px;"></i> 
                        <strong>Quantity:</strong> <?php echo $task['Quantity_Donated']; ?> units
                    </p>
                    <p style="color: #666; margin-bottom: 10px;">
                        <i class="fas fa-phone" style="color: #6b4a8e; width: 20px;"></i> 
                        <strong>Contact:</strong> <?php echo htmlspecialchars($task['Contact_Phone']); ?>
                    </p>
                    <p style="color: #666; margin-bottom: 0;">
                        <i class="fas fa-map-marker-alt" style="color: #6b4a8e; width: 20px;"></i> 
                        <strong>Location:</strong> <?php echo htmlspecialchars($task['Location_Info']); ?>
                    </p>
                </div>
                
                <?php if ($has_pending_task): ?>
                <button disabled style="width: 100%; background: #ccc; color: #666; border: none; padding: 12px; border-radius: 8px; font-weight: 600; cursor: not-allowed;">
                    <i class="fas fa-lock"></i> Complete Your Current Task First
                </button>
                <?php else: ?>
                <form method="POST" style="margin: 0;">
                    <input type="hidden" name="donation_id" value="<?php echo $task['Donation_ID']; ?>">
                    <button type="submit" name="self_assign" style="width: 100%; background: linear-gradient(135deg, #6b4a8e, #8b5db4); color: white; border: none; padding: 12px; border-radius: 8px; font-weight: 600; cursor: pointer; transition: transform 0.2s;">
                        <i class="fas fa-hand-paper"></i> Take This Task
                    </button>
                </form>
                <?php endif; ?>
            </div>
            <?php endwhile; ?>
        </div>
        <?php else: ?>
        <div style="text-align: center; padding: 60px; background: #f8f9fa; border-radius: 15px;">
            <i class="fas fa-check-circle" style="font-size: 64px; color: #28a745; margin-bottom: 20px;"></i>
            <p style="font-size: 20px; color: #666; margin-bottom: 10px;">All pickup tasks are assigned!</p>
            <p style="color: #999;">Check back later for new volunteer opportunities</p>
        </div>
        <?php endif; ?>
    </div>

    <!-- Info Section -->
    <div style="background: linear-gradient(135deg, #667eea 0%, #764ba2 100%); color: white; padding: 40px; border-radius: 15px; margin-top: 50px;">
        <h2 style="color: white; margin-bottom: 20px;"><i class="fas fa-question-circle"></i> How It Works</h2>
        <div style="display: grid; grid-template-columns: repeat(auto-fit, minmax(250px, 1fr)); gap: 30px;">
            <div>
                <div style="background: rgba(255,255,255,0.2); width: 50px; height: 50px; border-radius: 50%; display: flex; align-items: center; justify-content: center; margin-bottom: 15px; font-size: 24px; font-weight: bold;">1</div>
                <h3 style="color: white; margin-bottom: 10px;">Browse Tasks</h3>
                <p style="opacity: 0.9;">View available pickup tasks with location and contact details</p>
            </div>
            <div>
                <div style="background: rgba(255,255,255,0.2); width: 50px; height: 50px; border-radius: 50%; display: flex; align-items: center; justify-content: center; margin-bottom: 15px; font-size: 24px; font-weight: bold;">2</div>
                <h3 style="color: white; margin-bottom: 10px;">Take a Task</h3>
                <p style="opacity: 0.9;">Click "Take This Task" to assign it to yourself (one at a time)</p>
            </div>
            <div>
                <div style="background: rgba(255,255,255,0.2); width: 50px; height: 50px; border-radius: 50%; display: flex; align-items: center; justify-content: center; margin-bottom: 15px; font-size: 24px; font-weight: bold;">3</div>
                <h3 style="color: white; margin-bottom: 10px;">Complete Pickup</h3>
                <p style="opacity: 0.9;">Contact the donor, collect the items, and deliver to warehouse</p>
            </div>
            <div>
                <div style="background: rgba(255,255,255,0.2); width: 50px; height: 50px; border-radius: 50%; display: flex; align-items: center; justify-content: center; margin-bottom: 15px; font-size: 24px; font-weight: bold;">4</div>
                <h3 style="color: white; margin-bottom: 10px;">Mark Complete</h3>
                <p style="opacity: 0.9;">Once done, mark the task as completed to take another one</p>
            </div>
        </div>
    </div>
</div>

<?php require_once '../includes/footer.php'; ?>
