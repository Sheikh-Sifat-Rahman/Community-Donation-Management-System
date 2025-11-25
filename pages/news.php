<?php
$current_page = 'news';
$page_title = 'News';
require_once '../includes/header.php';
?>

<div class="hero">
    <h1>News & Updates</h1>
    <div class="breadcrumb">
        <a href="<?php echo SITE_URL; ?>/index.php"><i class="fas fa-home"></i> Home</a>
        <span>></span>
        <span>News</span>
    </div>
</div>

<div class="container">
    <div style="text-align: center; padding: 60px 20px;">
        <i class="fas fa-newspaper" style="font-size: 72px; color: #ddd; margin-bottom: 20px;"></i>
        <h2 style="font-size: 36px; color: #333; margin-bottom: 15px;">News Section Coming Soon</h2>
        <p style="font-size: 18px; color: #666; margin-bottom: 30px;">Stay tuned for updates and news about our activities!</p>
        <a href="<?php echo SITE_URL; ?>/index.php" class="donate-btn">
            <i class="fas fa-home"></i> Back to Home
        </a>
    </div>
</div>

<?php require_once '../includes/footer.php'; ?>
