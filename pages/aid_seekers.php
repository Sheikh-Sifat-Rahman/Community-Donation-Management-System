<?php
$current_page = 'aid_seekers';
$page_title = 'Aid Seekers';
require_once '../includes/header.php';

// Handle form submission
$success_message = '';
$error_message = '';

if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['add_seeker'])) {
    $seeker_name = mysqli_real_escape_string($conn, $_POST['seeker_name']);
    $national_id = mysqli_real_escape_string($conn, $_POST['national_id']);
    $monthly_income = mysqli_real_escape_string($conn, $_POST['monthly_income']);
    $family_members = mysqli_real_escape_string($conn, $_POST['family_members']);
    $phone = mysqli_real_escape_string($conn, $_POST['phone']);
    $email = mysqli_real_escape_string($conn, $_POST['email']);
    $reason = mysqli_real_escape_string($conn, $_POST['reason']);
    $district = mysqli_real_escape_string($conn, $_POST['district']);
    $thana = mysqli_real_escape_string($conn, $_POST['thana']);
    $post_no = mysqli_real_escape_string($conn, $_POST['post_no']);
    $village = mysqli_real_escape_string($conn, $_POST['village']);
    $street = mysqli_real_escape_string($conn, $_POST['street']);
    $house = mysqli_real_escape_string($conn, $_POST['house']);
    
    // Validate required fields
    if (empty($seeker_name) || empty($national_id) || empty($monthly_income) || empty($family_members) || 
        empty($phone) || empty($district) || empty($thana) || empty($post_no)) {
        $error_message = "Please fill all required fields!";
    } else {
        // Check if national ID already exists
        $check_nid = "SELECT * FROM Aid_Seeker WHERE National_ID = '$national_id'";
        $nid_result = mysqli_query($conn, $check_nid);
        
        if (mysqli_num_rows($nid_result) > 0) {
            $error_message = "This National ID is already registered!";
        } else {
            // Insert new aid seeker
            $insert_query = "INSERT INTO Aid_Seeker (Seeker_Name, National_ID, Monthly_Income, Family_Members, 
                            Phone, Email, Reason, District, Thana, Post_No, Village, Street, House) 
                            VALUES ('$seeker_name', '$national_id', '$monthly_income', '$family_members', 
                            '$phone', '$email', '$reason', '$district', '$thana', '$post_no', '$village', '$street', '$house')";
            
            if (mysqli_query($conn, $insert_query)) {
                $success_message = "Aid seeker registered successfully!";
            } else {
                $error_message = "Error: " . mysqli_error($conn);
            }
        }
    }
}

// Fetch all aid seekers
$seekers_query = "SELECT * FROM Aid_Seeker ORDER BY Created_At DESC";
$seekers_result = mysqli_query($conn, $seekers_query);

// Get statistics
$stats_query = "SELECT COUNT(*) as total FROM Aid_Seeker";
$stats_result = mysqli_query($conn, $stats_query);
$stats = mysqli_fetch_assoc($stats_result);
?>

<div class="hero" style="background: linear-gradient(rgba(107, 74, 142, 0.85), rgba(139, 93, 180, 0.85)), url('<?php echo SITE_URL; ?>/assets/images/community-support.jpg'); background-size: cover; background-position: center;">
    <h1>Aid Seekers</h1>
    <div class="breadcrumb">
        <a href="<?php echo SITE_URL; ?>/index.php"><i class="fas fa-home"></i> Home</a>
        <span>></span>
        <span>Aid Seekers</span>
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
    
    <!-- Statistics Card -->
    <div style="background: linear-gradient(135deg, #6b4a8e, #8b5db4); color: white; border-radius: 15px; padding: 30px; margin-bottom: 40px; box-shadow: 0 5px 25px rgba(0,0,0,0.15);">
        <div style="display: flex; justify-content: space-around; align-items: center; flex-wrap: wrap; gap: 20px;">
            <div style="text-align: center;">
                <i class="fas fa-users" style="font-size: 48px; margin-bottom: 10px;"></i>
                <h3 style="font-size: 36px; margin-bottom: 5px;"><?php echo $stats['total']; ?></h3>
                <p style="font-size: 16px; opacity: 0.9;">Total Aid Seekers</p>
            </div>
        </div>
    </div>
    
    <!-- Add Aid Seeker Form -->
    <div style="background: white; border-radius: 15px; box-shadow: 0 5px 25px rgba(0,0,0,0.1); padding: 40px; margin-bottom: 50px;">
        <h2 style="font-size: 28px; color: #6b4a8e; margin-bottom: 10px; text-align: center;">
            <i class="fas fa-hand-holding-heart"></i> Register as Aid Seeker
        </h2>
        <p style="text-align: center; color: #666; margin-bottom: 30px;">Fill out the form below to request assistance</p>
        
        <form method="POST" action="">
            <h3 style="color: #6b4a8e; margin-bottom: 20px; border-bottom: 2px solid #6b4a8e; padding-bottom: 10px;">
                <i class="fas fa-user"></i> Personal Information
            </h3>
            <div style="display: grid; grid-template-columns: repeat(auto-fit, minmax(250px, 1fr)); gap: 20px; margin-bottom: 30px;">
                <div>
                    <label style="display: block; margin-bottom: 8px; color: #333; font-weight: 500;">
                        <i class="fas fa-user"></i> Full Name *
                    </label>
                    <input type="text" name="seeker_name" required 
                           style="width: 100%; padding: 12px; border: 2px solid #e0e0e0; border-radius: 8px; font-size: 14px;"
                           placeholder="Enter full name">
                </div>
                
                <div>
                    <label style="display: block; margin-bottom: 8px; color: #333; font-weight: 500;">
                        <i class="fas fa-id-card"></i> National ID *
                    </label>
                    <input type="text" name="national_id" required 
                           style="width: 100%; padding: 12px; border: 2px solid #e0e0e0; border-radius: 8px; font-size: 14px;"
                           placeholder="Enter NID number">
                </div>
                
                <div>
                    <label style="display: block; margin-bottom: 8px; color: #333; font-weight: 500;">
                        <i class="fas fa-phone"></i> Phone *
                    </label>
                    <input type="tel" name="phone" required 
                           style="width: 100%; padding: 12px; border: 2px solid #e0e0e0; border-radius: 8px; font-size: 14px;"
                           placeholder="01XXXXXXXXX">
                </div>
                
                <div>
                    <label style="display: block; margin-bottom: 8px; color: #333; font-weight: 500;">
                        <i class="fas fa-envelope"></i> Email
                    </label>
                    <input type="email" name="email" 
                           style="width: 100%; padding: 12px; border: 2px solid #e0e0e0; border-radius: 8px; font-size: 14px;"
                           placeholder="email@example.com">
                </div>
                
                <div>
                    <label style="display: block; margin-bottom: 8px; color: #333; font-weight: 500;">
                        <i class="fas fa-money-bill-wave"></i> Monthly Income (৳) *
                    </label>
                    <input type="number" name="monthly_income" required min="0" 
                           style="width: 100%; padding: 12px; border: 2px solid #e0e0e0; border-radius: 8px; font-size: 14px;"
                           placeholder="Enter monthly income">
                </div>
                
                <div>
                    <label style="display: block; margin-bottom: 8px; color: #333; font-weight: 500;">
                        <i class="fas fa-users"></i> Family Members *
                    </label>
                    <input type="number" name="family_members" required min="1" 
                           style="width: 100%; padding: 12px; border: 2px solid #e0e0e0; border-radius: 8px; font-size: 14px;"
                           placeholder="Number of family members">
                </div>
            </div>
            
            <div style="margin-bottom: 30px;">
                <label style="display: block; margin-bottom: 8px; color: #333; font-weight: 500;">
                    <i class="fas fa-comment-dots"></i> Reason for Request *
                </label>
                <textarea name="reason" required rows="4"
                          style="width: 100%; padding: 12px; border: 2px solid #e0e0e0; border-radius: 8px; font-size: 14px; resize: vertical;"
                          placeholder="Please explain why you need assistance and what kind of aid you're seeking..."></textarea>
            </div>
            
            <h3 style="color: #6b4a8e; margin-bottom: 20px; border-bottom: 2px solid #6b4a8e; padding-bottom: 10px;">
                <i class="fas fa-map-marker-alt"></i> Address Information
            </h3>
            <div style="display: grid; grid-template-columns: repeat(auto-fit, minmax(250px, 1fr)); gap: 20px;">
                <div>
                    <label style="display: block; margin-bottom: 8px; color: #333; font-weight: 500;">
                        <i class="fas fa-city"></i> District *
                    </label>
                    <input type="text" name="district" required 
                           style="width: 100%; padding: 12px; border: 2px solid #e0e0e0; border-radius: 8px; font-size: 14px;"
                           placeholder="Enter district">
                </div>
                
                <div>
                    <label style="display: block; margin-bottom: 8px; color: #333; font-weight: 500;">
                        <i class="fas fa-map"></i> Thana *
                    </label>
                    <input type="text" name="thana" required 
                           style="width: 100%; padding: 12px; border: 2px solid #e0e0e0; border-radius: 8px; font-size: 14px;"
                           placeholder="Enter thana">
                </div>
                
                <div>
                    <label style="display: block; margin-bottom: 8px; color: #333; font-weight: 500;">
                        <i class="fas fa-mail-bulk"></i> Post Office No *
                    </label>
                    <input type="text" name="post_no" required 
                           style="width: 100%; padding: 12px; border: 2px solid #e0e0e0; border-radius: 8px; font-size: 14px;"
                           placeholder="Enter post office number">
                </div>
                
                <div>
                    <label style="display: block; margin-bottom: 8px; color: #333; font-weight: 500;">
                        <i class="fas fa-tree"></i> Village
                    </label>
                    <input type="text" name="village" 
                           style="width: 100%; padding: 12px; border: 2px solid #e0e0e0; border-radius: 8px; font-size: 14px;"
                           placeholder="Enter village">
                </div>
                
                <div>
                    <label style="display: block; margin-bottom: 8px; color: #333; font-weight: 500;">
                        <i class="fas fa-road"></i> Street
                    </label>
                    <input type="text" name="street" 
                           style="width: 100%; padding: 12px; border: 2px solid #e0e0e0; border-radius: 8px; font-size: 14px;"
                           placeholder="Enter street">
                </div>
                
                <div>
                    <label style="display: block; margin-bottom: 8px; color: #333; font-weight: 500;">
                        <i class="fas fa-home"></i> House
                    </label>
                    <input type="text" name="house" 
                           style="width: 100%; padding: 12px; border: 2px solid #e0e0e0; border-radius: 8px; font-size: 14px;"
                           placeholder="Enter house number">
                </div>
            </div>
            
            <div style="text-align: center; margin-top: 30px;">
                <button type="submit" name="add_seeker" 
                        style="background: linear-gradient(135deg, #6b4a8e, #8b5db4); color: white; padding: 15px 50px; border: none; border-radius: 50px; font-size: 16px; font-weight: 600; cursor: pointer; box-shadow: 0 4px 15px rgba(107, 74, 142, 0.3); transition: all 0.3s;">
                    <i class="fas fa-paper-plane"></i> Submit Registration
                </button>
            </div>
        </form>
    </div>
    
    <!-- Aid Seekers List -->
    <div style="text-align: center; margin-bottom: 30px;">
        <h2 style="font-size: 36px; color: #6b4a8e; margin-bottom: 15px;">Registered Aid Seekers</h2>
        <p style="font-size: 18px; color: #666;">People who need our support</p>
    </div>
    
    <div style="display: grid; grid-template-columns: repeat(auto-fill, minmax(350px, 1fr)); gap: 25px;">
        <?php while ($seeker = mysqli_fetch_assoc($seekers_result)): ?>
        <div style="background: white; border-radius: 12px; box-shadow: 0 4px 20px rgba(0,0,0,0.1); overflow: hidden; transition: transform 0.3s;">
            <div style="background: linear-gradient(135deg, #6b4a8e, #8b5db4); color: white; padding: 20px;">
                <h3 style="font-size: 20px; margin-bottom: 5px;">
                    <i class="fas fa-user-circle"></i> <?php echo htmlspecialchars($seeker['Seeker_Name']); ?>
                </h3>
                <p style="font-size: 14px; opacity: 0.9;">
                    <i class="fas fa-id-card"></i> NID: <?php echo htmlspecialchars($seeker['National_ID']); ?>
                </p>
            </div>
            <div style="padding: 20px;">
                <div style="display: grid; gap: 12px;">
                    <div style="display: flex; align-items: center; gap: 10px; padding: 10px; background: #f8f9fa; border-radius: 6px;">
                        <i class="fas fa-phone" style="color: #6b4a8e; width: 20px;"></i>
                        <span style="color: #666; font-size: 14px;"><?php echo htmlspecialchars($seeker['Phone']); ?></span>
                    </div>
                    
                    <?php if ($seeker['Email']): ?>
                    <div style="display: flex; align-items: center; gap: 10px; padding: 10px; background: #f8f9fa; border-radius: 6px;">
                        <i class="fas fa-envelope" style="color: #6b4a8e; width: 20px;"></i>
                        <span style="color: #666; font-size: 14px;"><?php echo htmlspecialchars($seeker['Email']); ?></span>
                    </div>
                    <?php endif; ?>
                    
                    <div style="display: flex; align-items: center; gap: 10px; padding: 10px; background: #f8f9fa; border-radius: 6px;">
                        <i class="fas fa-money-bill-wave" style="color: #6b4a8e; width: 20px;"></i>
                        <span style="color: #666; font-size: 14px;">Income: ৳<?php echo number_format($seeker['Monthly_Income']); ?>/month</span>
                    </div>
                    
                    <div style="display: flex; align-items: center; gap: 10px; padding: 10px; background: #f8f9fa; border-radius: 6px;">
                        <i class="fas fa-users" style="color: #6b4a8e; width: 20px;"></i>
                        <span style="color: #666; font-size: 14px;">Family: <?php echo $seeker['Family_Members']; ?> members</span>
                    </div>
                    
                    <?php if ($seeker['Reason']): ?>
                    <div style="padding: 10px; background: #fff3cd; border-radius: 6px; border-left: 4px solid #6b4a8e;">
                        <div style="color: #6b4a8e; font-weight: 600; font-size: 13px; margin-bottom: 5px;">
                            <i class="fas fa-comment-dots"></i> Reason for Request:
                        </div>
                        <span style="color: #666; font-size: 13px; line-height: 1.5;"><?php echo nl2br(htmlspecialchars($seeker['Reason'])); ?></span>
                    </div>
                    <?php endif; ?>
                    
                    <div style="display: flex; align-items: center; gap: 10px; padding: 10px; background: #f8f9fa; border-radius: 6px;">
                        <i class="fas fa-map-marker-alt" style="color: #6b4a8e; width: 20px;"></i>
                        <span style="color: #666; font-size: 14px;">
                            <?php 
                            $address = [];
                            if ($seeker['House']) $address[] = $seeker['House'];
                            if ($seeker['Street']) $address[] = $seeker['Street'];
                            if ($seeker['Village']) $address[] = $seeker['Village'];
                            $address[] = $seeker['Thana'];
                            $address[] = $seeker['District'];
                            echo htmlspecialchars(implode(', ', $address));
                            ?>
                        </span>
                    </div>
                </div>
            </div>
        </div>
        <?php endwhile; ?>
    </div>
    
    <?php if (mysqli_num_rows($seekers_result) == 0): ?>
    <div style="text-align: center; padding: 60px 20px;">
        <i class="fas fa-users" style="font-size: 72px; color: #ddd; margin-bottom: 20px;"></i>
        <p style="font-size: 18px; color: #888;">No aid seekers registered yet.</p>
    </div>
    <?php endif; ?>
</div>

<?php require_once '../includes/footer.php'; ?>
