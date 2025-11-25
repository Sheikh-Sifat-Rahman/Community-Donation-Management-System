<?php
$current_page = 'contact';
$page_title = 'Contact Us';
require_once '../includes/header.php';
?>

<div class="hero">
    <h1>Contact Us</h1>
    <div class="breadcrumb">
        <a href="<?php echo SITE_URL; ?>/index.php"><i class="fas fa-home"></i> Home</a>
        <span>></span>
        <span>Contact Us</span>
    </div>
</div>

<div class="container">
    <div style="display: grid; grid-template-columns: 1fr 1fr; gap: 40px; align-items: start;">
        <div>
            <h2 style="font-size: 36px; color: #6b4a8e; margin-bottom: 20px;">Get In Touch</h2>
            <p style="font-size: 16px; color: #666; margin-bottom: 30px;">
                Have questions or want to learn more about our work? We'd love to hear from you!
            </p>
            
            <div style="margin-bottom: 25px;">
                <h4 style="color: #333; margin-bottom: 10px;"><i class="fas fa-envelope"></i> Email</h4>
                <p style="color: #666;"><?php echo SITE_EMAIL; ?></p>
            </div>
            
            <div style="margin-bottom: 25px;">
                <h4 style="color: #333; margin-bottom: 10px;"><i class="fas fa-phone"></i> Phone</h4>
                <p style="color: #666;"><?php echo SITE_PHONE; ?></p>
            </div>
            
            <div style="margin-bottom: 25px;">
                <h4 style="color: #333; margin-bottom: 10px;"><i class="fas fa-map-marker-alt"></i> Address</h4>
                <p style="color: #666;"><?php echo SITE_ADDRESS; ?></p>
            </div>
        </div>
        
        <div style="background: white; padding: 40px; border-radius: 10px; box-shadow: 0 4px 20px rgba(0,0,0,0.1);">
            <form method="POST" action="<?php echo SITE_URL; ?>/includes/process_contact.php">
                <div class="form-group">
                    <input type="text" name="name" placeholder="Your Name" required>
                </div>
                <div class="form-group">
                    <input type="email" name="email" placeholder="Your Email" required>
                </div>
                <div class="form-group">
                    <input type="text" name="subject" placeholder="Subject" required>
                </div>
                <div class="form-group">
                    <textarea name="message" rows="5" placeholder="Your Message" required></textarea>
                </div>
                <button type="submit" class="submit-btn">
                    <i class="fas fa-paper-plane"></i> Send Message
                </button>
            </form>
        </div>
    </div>
</div>

<?php require_once '../includes/footer.php'; ?>
