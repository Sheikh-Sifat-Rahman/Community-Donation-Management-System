<?php
$current_page = 'cart';
$page_title = 'Shopping Cart';
require_once '../includes/header.php';
?>

<div class="hero">
    <h1>Shopping Cart</h1>
    <div class="breadcrumb">
        <a href="<?php echo SITE_URL; ?>/index.php"><i class="fas fa-home"></i> Home</a>
        <span>></span>
        <span>Cart</span>
    </div>
</div>

<div class="container">
    <div style="text-align: center; padding: 60px 20px;">
        <i class="fas fa-shopping-cart" style="font-size: 72px; color: #ddd; margin-bottom: 20px;"></i>
        <h2 style="font-size: 36px; color: #333; margin-bottom: 15px;">Your Cart is Empty</h2>
        <p style="font-size: 18px; color: #666; margin-bottom: 30px;">Start shopping or make a donation to help others!</p>
        <div style="display: flex; gap: 15px; justify-content: center; flex-wrap: wrap;">
            <a href="<?php echo SITE_URL; ?>/pages/shop.php" class="donate-btn">
                <i class="fas fa-shopping-bag"></i> Go to Shop
            </a>
            <a href="<?php echo SITE_URL; ?>/pages/donate.php" class="donate-btn">
                <i class="fas fa-hand-holding-heart"></i> Make a Donation
            </a>
        </div>
    </div>
</div>

<?php require_once '../includes/footer.php'; ?>
