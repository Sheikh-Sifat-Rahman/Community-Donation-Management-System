<?php
$current_page = 'about';
$page_title = 'About Us';
require_once '../includes/header.php';
?>

<div class="hero">
    <h1>About Us</h1>
    <div class="breadcrumb">
        <a href="<?php echo SITE_URL; ?>/index.php"><i class="fas fa-home"></i> Home</a>
        <span>></span>
        <span>About Us</span>
    </div>
</div>

<div class="container">
    <div style="max-width: 900px; margin: 0 auto;">
        <div style="display: grid; grid-template-columns: 1fr 1fr; gap: 40px; align-items: center; margin-bottom: 50px;">
            <div>
                <h2 style="font-size: 36px; color: #6b4a8e; margin-bottom: 20px;">Our Mission</h2>
                <p style="font-size: 18px; line-height: 1.8; color: #666; margin-bottom: 30px;">
                    Clean Hearts is a community-driven organization dedicated to helping those in need through organized 
                    donation drives and volunteer support. We believe that by working together, we can make a significant 
                    impact on the lives of individuals and families facing difficult circumstances.
                </p>
            </div>
            <div>
                <img src="<?php echo SITE_URL; ?>/assets/images/about-mission.jpg" alt="Our Mission" style="width: 100%; border-radius: 15px; box-shadow: 0 8px 20px rgba(0,0,0,0.15);">
            </div>
        </div>
        
        <h2 style="font-size: 36px; color: #6b4a8e; margin-bottom: 20px;">What We Do</h2>
        <div style="display: grid; grid-template-columns: repeat(auto-fit, minmax(250px, 1fr)); gap: 30px; margin-bottom: 40px;">
            <div style="background: white; padding: 30px; border-radius: 10px; box-shadow: 0 4px 15px rgba(0,0,0,0.1);">
                <i class="fas fa-hands-helping" style="font-size: 48px; color: #6b4a8e; margin-bottom: 15px;"></i>
                <h3 style="color: #333; margin-bottom: 10px;">Food Distribution</h3>
                <p style="color: #666;">Providing nutritious meals and food items to families in need.</p>
            </div>
            
            <div style="background: white; padding: 30px; border-radius: 10px; box-shadow: 0 4px 15px rgba(0,0,0,0.1);">
                <i class="fas fa-tshirt" style="font-size: 48px; color: #6b4a8e; margin-bottom: 15px;"></i>
                <h3 style="color: #333; margin-bottom: 10px;">Clothing Drives</h3>
                <p style="color: #666;">Collecting and distributing clothing to those who need them most.</p>
            </div>
            
            <div style="background: white; padding: 30px; border-radius: 10px; box-shadow: 0 4px 15px rgba(0,0,0,0.1);">
                <i class="fas fa-medkit" style="font-size: 48px; color: #6b4a8e; margin-bottom: 15px;"></i>
                <h3 style="color: #333; margin-bottom: 10px;">Medical Support</h3>
                <p style="color: #666;">Providing essential medical supplies and healthcare assistance.</p>
            </div>
        </div>
        
        <h2 style="font-size: 36px; color: #6b4a8e; margin-bottom: 20px;">Our Impact</h2>
        <div style="display: grid; grid-template-columns: 1fr 1fr; gap: 40px; align-items: center; margin-bottom: 50px;">
            <div>
                <img src="<?php echo SITE_URL; ?>/assets/images/volunteer-team.jpg" alt="Our Team" style="width: 100%; border-radius: 15px; box-shadow: 0 8px 20px rgba(0,0,0,0.15);">
            </div>
            <div>
                <p style="font-size: 18px; line-height: 1.8; color: #666; margin-bottom: 30px;">
                    Since our inception, we have helped thousands of families and individuals through our various programs. 
                    Every donation, no matter how small, contributes to making a real difference in someone's life. 
                    Together with our volunteers and generous donors, we continue to expand our reach and impact.
                </p>
            </div>
        </div>
    </div>
</div>

<?php require_once '../includes/footer.php'; ?>
