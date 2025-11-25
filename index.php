<?php
$current_page = 'home';
$page_title = 'Home';
require_once 'includes/header.php';
require_once 'includes/auth.php';

// Check if user is logged in, if yes redirect to dashboard, else redirect to login
if (isLoggedIn()) {
    header('Location: ' . SITE_URL . '/pages/dashboard.php');
    exit();
} else {
    header('Location: ' . SITE_URL . '/pages/login.php');
    exit();
}
?>

<!-- Hero Section -->
<div class="hero" style="background-image: linear-gradient(rgba(45, 27, 78, 0.75), rgba(107, 74, 142, 0.75)), url('assets/images/hero-bg.jpg'); background-size: cover; background-position: center; background-attachment: fixed;">
    <div class="container" style="text-align: center; padding: 120px 20px;">
        <h1 style="font-size: 56px; margin-bottom: 20px; color: white; text-shadow: 2px 2px 4px rgba(0,0,0,0.3);">Welcome to <?php echo SITE_NAME; ?></h1>
        <p style="font-size: 20px; margin-bottom: 30px; color: white; text-shadow: 1px 1px 2px rgba(0,0,0,0.3);">Making a difference in people's lives through community support</p>
        <a href="<?php echo SITE_URL; ?>/pages/donate.php" class="donate-btn" style="display: inline-flex; margin-top: 20px; font-size: 18px; padding: 15px 40px;">
            <i class="fas fa-hand-holding-heart"></i> Donate Now
        </a>
    </div>
</div>

<!-- About Section -->
<div class="container">
    <div style="text-align: center; max-width: 800px; margin: 0 auto;">
        <h2 style="font-size: 42px; color: #6b4a8e; margin-bottom: 20px;">About Our Mission</h2>
        <p style="font-size: 18px; line-height: 1.8; color: #666;">
            We are dedicated to helping those in need through organized donation drives and community support. 
            Our platform connects generous donors with individuals and families who need assistance, 
            ensuring that help reaches those who need it most.
        </p>
    </div>
</div>

<!-- Stats Section -->
<div class="container">
    <div style="display: grid; grid-template-columns: repeat(auto-fit, minmax(250px, 1fr)); gap: 30px; text-align: center;">
        <?php
        // Get statistics
        $total_donations = mysqli_fetch_assoc(mysqli_query($conn, "SELECT COUNT(*) as count FROM DONATION"))['count'];
        $total_donors = mysqli_fetch_assoc(mysqli_query($conn, "SELECT COUNT(*) as count FROM DONOR"))['count'];
        $total_volunteers = mysqli_fetch_assoc(mysqli_query($conn, "SELECT COUNT(*) as count FROM VOLUNTEERS"))['count'];
        $total_distributions = mysqli_fetch_assoc(mysqli_query($conn, "SELECT COUNT(*) as count FROM DISTRIBUTION"))['count'];
        ?>
        
        <div style="background: linear-gradient(135deg, #6b4a8e, #8b5db4); padding: 40px; border-radius: 15px; color: white;">
            <i class="fas fa-hand-holding-heart" style="font-size: 48px; margin-bottom: 15px;"></i>
            <h3 style="font-size: 36px; margin-bottom: 10px;"><?php echo $total_donations; ?></h3>
            <p style="font-size: 18px;">Total Donations</p>
        </div>
        
        <div style="background: linear-gradient(135deg, #f4c54d, #e6b547); padding: 40px; border-radius: 15px; color: #333;">
            <i class="fas fa-users" style="font-size: 48px; margin-bottom: 15px;"></i>
            <h3 style="font-size: 36px; margin-bottom: 10px;"><?php echo $total_donors; ?></h3>
            <p style="font-size: 18px;">Generous Donors</p>
        </div>
        
        <div style="background: linear-gradient(135deg, #6b4a8e, #8b5db4); padding: 40px; border-radius: 15px; color: white;">
            <i class="fas fa-hands-helping" style="font-size: 48px; margin-bottom: 15px;"></i>
            <h3 style="font-size: 36px; margin-bottom: 10px;"><?php echo $total_volunteers; ?></h3>
            <p style="font-size: 18px;">Active Volunteers</p>
        </div>
        
        <div style="background: linear-gradient(135deg, #f4c54d, #e6b547); padding: 40px; border-radius: 15px; color: #333;">
            <i class="fas fa-box-open" style="font-size: 48px; margin-bottom: 15px;"></i>
            <h3 style="font-size: 36px; margin-bottom: 10px;"><?php echo $total_distributions; ?></h3>
            <p style="font-size: 18px;">Items Distributed</p>
        </div>
    </div>
</div>

<!-- Recent Donations -->
<div class="container">
    <h2 style="text-align: center; font-size: 42px; color: #6b4a8e; margin-bottom: 40px;">Recent Donations</h2>
    
    <div style="display: grid; grid-template-columns: repeat(auto-fit, minmax(300px, 1fr)); gap: 30px;">
        <?php
        $recent_donations = mysqli_query($conn, "
            SELECT d.*, don.Name, don.Donor_Type, w.Item_Name 
            FROM DONATION d 
            JOIN DONOR don ON d.Donor_ID = don.Donor_ID 
            JOIN WAREHOUSE w ON d.Item_ID = w.Item_ID 
            ORDER BY d.Date_Donated DESC 
            LIMIT 6
        ");
        
        while ($donation = mysqli_fetch_assoc($recent_donations)):
        ?>
        <div style="background: white; padding: 25px; border-radius: 10px; box-shadow: 0 4px 15px rgba(0,0,0,0.1);">
            <div style="display: flex; align-items: center; gap: 15px; margin-bottom: 15px;">
                <div style="width: 50px; height: 50px; background: linear-gradient(135deg, #6b4a8e, #8b5db4); border-radius: 50%; display: flex; align-items: center; justify-content: center; color: white; font-size: 24px;">
                    <i class="fas fa-user"></i>
                </div>
                <div>
                    <h4 style="color: #333; margin-bottom: 5px;"><?php echo htmlspecialchars($donation['Name']); ?></h4>
                    <p style="color: #888; font-size: 14px;"><?php echo $donation['Donor_Type']; ?></p>
                </div>
            </div>
            <p style="color: #666; margin-bottom: 10px;">
                <strong>Donated:</strong> <?php echo $donation['Quantity_Donated']; ?> units of <?php echo htmlspecialchars($donation['Item_Name']); ?>
            </p>
            <p style="color: #888; font-size: 14px;">
                <i class="far fa-calendar"></i> <?php echo date('F j, Y', strtotime($donation['Date_Donated'])); ?>
            </p>
        </div>
        <?php endwhile; ?>
    </div>
</div>

<!-- Call to Action -->
<div style="background: linear-gradient(rgba(107, 74, 142, 0.9), rgba(139, 93, 180, 0.9)), url('assets/images/community.jpg'); background-size: cover; background-position: center; background-attachment: fixed; padding: 80px 20px; text-align: center; color: white;">
    <div class="container">
        <h2 style="font-size: 42px; margin-bottom: 20px; text-shadow: 2px 2px 4px rgba(0,0,0,0.3);">Make a Difference Today</h2>
        <p style="font-size: 20px; margin-bottom: 30px; max-width: 700px; margin-left: auto; margin-right: auto; text-shadow: 1px 1px 2px rgba(0,0,0,0.3);">
            Your contribution can change lives. Join us in our mission to help those in need.
        </p>
        <a href="<?php echo SITE_URL; ?>/pages/donate.php" class="donate-btn" style="display: inline-flex; font-size: 18px; padding: 15px 40px;">
            <i class="fas fa-hand-holding-heart"></i> Start Donating
        </a>
    </div>
</div>

<?php require_once 'includes/footer.php'; ?>
