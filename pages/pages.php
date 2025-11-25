<?php
$current_page = 'pages';
$page_title = 'Pages';
require_once '../includes/header.php';
?>

<div class="hero">
    <h1>Our Pages</h1>
    <div class="breadcrumb">
        <a href="<?php echo SITE_URL; ?>/index.php"><i class="fas fa-home"></i> Home</a>
        <span>></span>
        <span>Pages</span>
    </div>
</div>

<div class="container">
    <div style="display: grid; grid-template-columns: repeat(auto-fit, minmax(300px, 1fr)); gap: 30px;">
        <a href="<?php echo SITE_URL; ?>/pages/about.php" style="text-decoration: none;">
            <div style="background: white; padding: 40px; border-radius: 10px; box-shadow: 0 4px 20px rgba(0,0,0,0.1); text-align: center; transition: transform 0.3s;">
                <i class="fas fa-info-circle" style="font-size: 64px; color: #6b4a8e; margin-bottom: 20px;"></i>
                <h3 style="color: #333; margin-bottom: 10px;">About Us</h3>
                <p style="color: #666;">Learn more about our mission and values</p>
            </div>
        </a>
        
        <a href="<?php echo SITE_URL; ?>/pages/donation.php" style="text-decoration: none;">
            <div style="background: white; padding: 40px; border-radius: 10px; box-shadow: 0 4px 20px rgba(0,0,0,0.1); text-align: center; transition: transform 0.3s;">
                <i class="fas fa-list" style="font-size: 64px; color: #6b4a8e; margin-bottom: 20px;"></i>
                <h3 style="color: #333; margin-bottom: 10px;">Donation History</h3>
                <p style="color: #666;">View all donations received</p>
            </div>
        </a>
        
        <a href="<?php echo SITE_URL; ?>/pages/donate.php" style="text-decoration: none;">
            <div style="background: white; padding: 40px; border-radius: 10px; box-shadow: 0 4px 20px rgba(0,0,0,0.1); text-align: center; transition: transform 0.3s;">
                <i class="fas fa-hand-holding-heart" style="font-size: 64px; color: #6b4a8e; margin-bottom: 20px;"></i>
                <h3 style="color: #333; margin-bottom: 10px;">Donate Now</h3>
                <p style="color: #666;">Make a donation to help others</p>
            </div>
        </a>
        
        <a href="<?php echo SITE_URL; ?>/pages/volunteers.php" style="text-decoration: none;">
            <div style="background: white; padding: 40px; border-radius: 10px; box-shadow: 0 4px 20px rgba(0,0,0,0.1); text-align: center; transition: transform 0.3s;">
                <i class="fas fa-users" style="font-size: 64px; color: #6b4a8e; margin-bottom: 20px;"></i>
                <h3 style="color: #333; margin-bottom: 10px;">Volunteers</h3>
                <p style="color: #666;">Meet our dedicated volunteers</p>
            </div>
        </a>
        
        <a href="<?php echo SITE_URL; ?>/pages/warehouse.php" style="text-decoration: none;">
            <div style="background: white; padding: 40px; border-radius: 10px; box-shadow: 0 4px 20px rgba(0,0,0,0.1); text-align: center; transition: transform 0.3s;">
                <i class="fas fa-warehouse" style="font-size: 64px; color: #6b4a8e; margin-bottom: 20px;"></i>
                <h3 style="color: #333; margin-bottom: 10px;">Warehouse</h3>
                <p style="color: #666;">View available items inventory</p>
            </div>
        </a>
        
        <a href="<?php echo SITE_URL; ?>/pages/contact.php" style="text-decoration: none;">
            <div style="background: white; padding: 40px; border-radius: 10px; box-shadow: 0 4px 20px rgba(0,0,0,0.1); text-align: center; transition: transform 0.3s;">
                <i class="fas fa-envelope" style="font-size: 64px; color: #6b4a8e; margin-bottom: 20px;"></i>
                <h3 style="color: #333; margin-bottom: 10px;">Contact Us</h3>
                <p style="color: #666;">Get in touch with our team</p>
            </div>
        </a>
    </div>
</div>

<?php require_once '../includes/footer.php'; ?>
