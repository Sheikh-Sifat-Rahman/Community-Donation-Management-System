<?php
$current_page = 'shop';
$page_title = 'Shop';
require_once '../includes/header.php';
?>

<div class="hero">
    <h1>Shop</h1>
    <div class="breadcrumb">
        <a href="<?php echo SITE_URL; ?>/index.php"><i class="fas fa-home"></i> Home</a>
        <span>></span>
        <span>Shop</span>
    </div>
</div>

<div class="container">
    <div style="text-align: center; padding: 60px 20px;">
        <i class="fas fa-shopping-bag" style="font-size: 72px; color: #ddd; margin-bottom: 20px;"></i>
        <h2 style="font-size: 36px; color: #333; margin-bottom: 15px;">Shop Coming Soon</h2>
        <p style="font-size: 18px; color: #666; margin-bottom: 30px;">We're working on our shop. Check back soon!</p>
        <a href="<?php echo SITE_URL; ?>/pages/donate.php" class="donate-btn">
            <i class="fas fa-hand-holding-heart"></i> Make a Donation Instead
        </a>
    </div>
</div>

<?php require_once '../includes/footer.php'; ?>
