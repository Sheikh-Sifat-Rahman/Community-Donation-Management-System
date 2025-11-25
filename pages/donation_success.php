<?php
$current_page = 'donation';
$page_title = 'Donation Success';
require_once '../includes/header.php';
?>

<div class="hero">
    <h1>Thank You!</h1>
</div>

<div class="container" style="text-align: center; max-width: 700px; margin: 80px auto;">
    <?php if (isset($_SESSION['success_message'])): ?>
        <div style="background-color: #d4edda; border: 1px solid #c3e6cb; color: #155724; padding: 20px; border-radius: 10px; margin-bottom: 30px;">
            <i class="fas fa-check-circle" style="font-size: 48px; color: #28a745; margin-bottom: 15px;"></i>
            <h2 style="color: #155724; margin-bottom: 15px;">Donation Successful!</h2>
            <p style="font-size: 18px;"><?php echo $_SESSION['success_message']; unset($_SESSION['success_message']); ?></p>
        </div>
    <?php endif; ?>
    
    <p style="font-size: 18px; color: #666; margin-bottom: 30px;">
        Your generosity makes a real difference in people's lives. We'll send you a confirmation email shortly.
    </p>
    
    <div style="display: flex; gap: 15px; justify-content: center; flex-wrap: wrap;">
        <a href="<?php echo SITE_URL; ?>/index.php" class="donate-btn">
            <i class="fas fa-home"></i> Back to Home
        </a>
        <a href="<?php echo SITE_URL; ?>/pages/donate.php" class="donate-btn">
            <i class="fas fa-hand-holding-heart"></i> Donate Again
        </a>
    </div>
</div>

<?php require_once '../includes/footer.php'; ?>
