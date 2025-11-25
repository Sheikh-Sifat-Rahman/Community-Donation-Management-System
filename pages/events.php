<?php
$current_page = 'events';
$page_title = 'Events';
require_once '../includes/header.php';
?>

<div class="hero">
    <h1>Our Events</h1>
    <div class="breadcrumb">
        <a href="<?php echo SITE_URL; ?>/index.php"><i class="fas fa-home"></i> Home</a>
        <span>></span>
        <span>Events</span>
    </div>
</div>

<div class="container">
    <div style="text-align: center; margin-bottom: 50px;">
        <h2 style="font-size: 36px; color: #6b4a8e; margin-bottom: 15px;">Upcoming Events</h2>
        <p style="font-size: 18px; color: #666;">Join us in making a difference in our community</p>
    </div>
    
    <div style="display: grid; grid-template-columns: repeat(auto-fit, minmax(350px, 1fr)); gap: 30px;">
        <div style="background: white; border-radius: 10px; box-shadow: 0 4px 20px rgba(0,0,0,0.1); overflow: hidden;">
            <div style="height: 200px; background: linear-gradient(135deg, #6b4a8e, #8b5db4); display: flex; align-items: center; justify-content: center; color: white; font-size: 72px;">
                <i class="fas fa-utensils"></i>
            </div>
            <div style="padding: 25px;">
                <div style="color: #f4c54d; font-size: 14px; font-weight: 600; margin-bottom: 10px;">
                    <i class="far fa-calendar"></i> December 15, 2025
                </div>
                <h3 style="color: #333; margin-bottom: 15px; font-size: 22px;">Community Food Drive</h3>
                <p style="color: #666; margin-bottom: 20px;">Join us for our annual food drive to help families in need during the holiday season.</p>
                <a href="#" style="color: #6b4a8e; font-weight: 600; text-decoration: none;">Learn More →</a>
            </div>
        </div>
        
        <div style="background: white; border-radius: 10px; box-shadow: 0 4px 20px rgba(0,0,0,0.1); overflow: hidden;">
            <div style="height: 200px; background: linear-gradient(135deg, #f4c54d, #e6b547); display: flex; align-items: center; justify-content: center; color: white; font-size: 72px;">
                <i class="fas fa-tshirt"></i>
            </div>
            <div style="padding: 25px;">
                <div style="color: #f4c54d; font-size: 14px; font-weight: 600; margin-bottom: 10px;">
                    <i class="far fa-calendar"></i> December 20, 2025
                </div>
                <h3 style="color: #333; margin-bottom: 15px; font-size: 22px;">Winter Clothing Distribution</h3>
                <p style="color: #666; margin-bottom: 20px;">Help us distribute warm clothing to those who need it most this winter.</p>
                <a href="#" style="color: #6b4a8e; font-weight: 600; text-decoration: none;">Learn More →</a>
            </div>
        </div>
        
        <div style="background: white; border-radius: 10px; box-shadow: 0 4px 20px rgba(0,0,0,0.1); overflow: hidden;">
            <div style="height: 200px; background: linear-gradient(135deg, #6b4a8e, #8b5db4); display: flex; align-items: center; justify-content: center; color: white; font-size: 72px;">
                <i class="fas fa-hands-helping"></i>
            </div>
            <div style="padding: 25px;">
                <div style="color: #f4c54d; font-size: 14px; font-weight: 600; margin-bottom: 10px;">
                    <i class="far fa-calendar"></i> January 5, 2026
                </div>
                <h3 style="color: #333; margin-bottom: 15px; font-size: 22px;">Volunteer Orientation</h3>
                <p style="color: #666; margin-bottom: 20px;">Interested in volunteering? Join our orientation session to learn how you can help.</p>
                <a href="#" style="color: #6b4a8e; font-weight: 600; text-decoration: none;">Learn More →</a>
            </div>
        </div>
    </div>
</div>

<?php require_once '../includes/footer.php'; ?>
