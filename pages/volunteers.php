<?php
$current_page = 'volunteers';
$page_title = 'Volunteers';
require_once '../includes/header.php';
require_once '../includes/auth.php';

// Route based on user role
if (isLoggedIn()) {
    if (isAdmin()) {
        // Admins go to admin volunteers page
        header('Location: ' . SITE_URL . '/pages/admin_volunteers.php');
        exit();
    } else {
        // Regular users go to user volunteers page
        header('Location: ' . SITE_URL . '/pages/user_volunteers.php');
        exit();
    }
}

// Public volunteer registration page for non-logged-in users
// Handle form submission
$success_message = '';
$error_message = '';

if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['add_volunteer'])) {
    $vol_name = mysqli_real_escape_string($conn, $_POST['vol_name']);
    $email = mysqli_real_escape_string($conn, $_POST['email']);
    $phone = mysqli_real_escape_string($conn, $_POST['phone']);
    $gender = mysqli_real_escape_string($conn, $_POST['gender']);
    $area_assigned = mysqli_real_escape_string($conn, $_POST['area_assigned']);
    
    // Validate required fields
    if (empty($vol_name) || empty($email) || empty($phone) || empty($gender) || empty($area_assigned)) {
        $error_message = "All fields are required!";
    } else {
        // Check if email already exists
        $check_email = "SELECT * FROM VOLUNTEERS WHERE Email = '$email'";
        $email_result = mysqli_query($conn, $check_email);
        
        if (mysqli_num_rows($email_result) > 0) {
            $error_message = "A volunteer with this email already exists!";
        } else {
            // Insert new volunteer
            $insert_query = "INSERT INTO VOLUNTEERS (Vol_Name, Email, Phone, Gender, Area_Assigned) 
                            VALUES ('$vol_name', '$email', '$phone', '$gender', '$area_assigned')";
            
            if (mysqli_query($conn, $insert_query)) {
                $success_message = "Volunteer added successfully!";
            } else {
                $error_message = "Error: " . mysqli_error($conn);
            }
        }
    }
}

// Fetch all volunteers
$volunteers_query = "SELECT * FROM VOLUNTEERS ORDER BY Vol_Name";
$volunteers_result = mysqli_query($conn, $volunteers_query);
?>

<div class="hero" style="background: linear-gradient(rgba(107, 74, 142, 0.85), rgba(139, 93, 180, 0.85)), url('<?php echo SITE_URL; ?>/assets/images/volunteer-team.jpg'); background-size: cover; background-position: center;">
    <h1>Our Volunteers</h1>
    <div class="breadcrumb">
        <a href="<?php echo SITE_URL; ?>/index.php"><i class="fas fa-home"></i> Home</a>
        <span>></span>
        <span>Volunteers</span>
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
    
    <!-- Add Volunteer Form -->
    <div style="background: white; border-radius: 15px; box-shadow: 0 5px 25px rgba(0,0,0,0.1); padding: 40px; margin-bottom: 50px;">
        <h2 style="font-size: 28px; color: #6b4a8e; margin-bottom: 10px; text-align: center;">
            <i class="fas fa-user-plus"></i> Join Our Volunteer Team
        </h2>
        <p style="text-align: center; color: #666; margin-bottom: 30px;">Fill out the form below to become a volunteer</p>
        
        <form method="POST" action="">
            <div style="display: grid; grid-template-columns: repeat(auto-fit, minmax(250px, 1fr)); gap: 20px;">
                <div>
                    <label style="display: block; margin-bottom: 8px; color: #333; font-weight: 500;">
                        <i class="fas fa-user"></i> Full Name *
                    </label>
                    <input type="text" name="vol_name" required 
                           style="width: 100%; padding: 12px; border: 2px solid #e0e0e0; border-radius: 8px; font-size: 14px;"
                           placeholder="Enter full name">
                </div>
                
                <div>
                    <label style="display: block; margin-bottom: 8px; color: #333; font-weight: 500;">
                        <i class="fas fa-envelope"></i> Email *
                    </label>
                    <input type="email" name="email" required 
                           style="width: 100%; padding: 12px; border: 2px solid #e0e0e0; border-radius: 8px; font-size: 14px;"
                           placeholder="volunteer@example.com">
                </div>
                
                <div>
                    <label style="display: block; margin-bottom: 8px; color: #333; font-weight: 500;">
                        <i class="fas fa-phone"></i> Phone *
                    </label>
                    <input type="tel" name="phone" required 
                           style="width: 100%; padding: 12px; border: 2px solid #e0e0e0; border-radius: 8px; font-size: 14px;"
                           placeholder="555-0000">
                </div>
                
                <div>
                    <label style="display: block; margin-bottom: 8px; color: #333; font-weight: 500;">
                        <i class="fas fa-venus-mars"></i> Gender *
                    </label>
                    <select name="gender" required 
                            style="width: 100%; padding: 12px; border: 2px solid #e0e0e0; border-radius: 8px; font-size: 14px;">
                        <option value="">Select Gender</option>
                        <option value="Male">Male</option>
                        <option value="Female">Female</option>
                    </select>
                </div>
                
                <div style="grid-column: 1 / -1;">
                    <label style="display: block; margin-bottom: 8px; color: #333; font-weight: 500;">
                        <i class="fas fa-map-marker-alt"></i> Area Assigned *
                    </label>
                    <input type="text" name="area_assigned" required 
                           style="width: 100%; padding: 12px; border: 2px solid #e0e0e0; border-radius: 8px; font-size: 14px;"
                           placeholder="Enter area/location">
                </div>
            </div>
            
            <div style="text-align: center; margin-top: 30px;">
                <button type="submit" name="add_volunteer" 
                        style="background: linear-gradient(135deg, #6b4a8e, #8b5db4); color: white; padding: 15px 50px; border: none; border-radius: 50px; font-size: 16px; font-weight: 600; cursor: pointer; box-shadow: 0 4px 15px rgba(107, 74, 142, 0.3); transition: all 0.3s;">
                    <i class="fas fa-paper-plane"></i> Register as Volunteer
                </button>
            </div>
        </form>
    </div>
    
    <div style="text-align: center; margin-bottom: 50px;">
        <h2 style="font-size: 36px; color: #6b4a8e; margin-bottom: 15px;">Meet Our Amazing Team</h2>
        <p style="font-size: 18px; color: #666;">Dedicated individuals making a difference every day</p>
    </div>
    
    <div style="display: grid; grid-template-columns: repeat(auto-fit, minmax(280px, 1fr)); gap: 30px;">
        <?php while ($volunteer = mysqli_fetch_assoc($volunteers_result)): ?>
        <div style="background: white; border-radius: 10px; box-shadow: 0 4px 20px rgba(0,0,0,0.1); overflow: hidden; text-align: center;">
            <div style="height: 150px; background: linear-gradient(135deg, #6b4a8e, #8b5db4); display: flex; align-items: center; justify-content: center; color: white; font-size: 64px;">
                <i class="fas fa-user-circle"></i>
            </div>
            <div style="padding: 25px;">
                <h3 style="color: #333; margin-bottom: 10px; font-size: 20px;"><?php echo htmlspecialchars($volunteer['Vol_Name']); ?></h3>
                <p style="color: #888; font-size: 14px; margin-bottom: 15px;">
                    <i class="fas fa-venus-mars"></i> <?php echo $volunteer['Gender']; ?>
                </p>
                <div style="text-align: left; background: #f8f9fa; padding: 15px; border-radius: 8px;">
                    <p style="color: #666; font-size: 14px; margin-bottom: 8px;">
                        <i class="fas fa-map-marker-alt"></i> <strong>Area:</strong> <?php echo htmlspecialchars($volunteer['Area_Assigned']); ?>
                    </p>
                    <p style="color: #666; font-size: 14px; margin-bottom: 8px;">
                        <i class="fas fa-envelope"></i> <strong>Email:</strong> <?php echo htmlspecialchars($volunteer['Email']); ?>
                    </p>
                    <p style="color: #666; font-size: 14px;">
                        <i class="fas fa-phone"></i> <strong>Phone:</strong> <?php echo htmlspecialchars($volunteer['Phone']); ?>
                    </p>
                </div>
            </div>
        </div>
        <?php endwhile; ?>
    </div>
    
    <?php if (mysqli_num_rows($volunteers_result) == 0): ?>
    <div style="text-align: center; padding: 60px 20px;">
        <i class="fas fa-users" style="font-size: 72px; color: #ddd; margin-bottom: 20px;"></i>
        <p style="font-size: 18px; color: #888;">No volunteers registered yet.</p>
    </div>
    <?php endif; ?>
</div>

<?php require_once '../includes/footer.php'; ?>
