<?php
$current_page = 'volunteers';
$page_title = 'Volunteers';
require_once '../includes/header.php';

// Fetch all volunteers
$volunteers_query = "SELECT * FROM VOLUNTEERS ORDER BY Vol_Name";
$volunteers_result = mysqli_query($conn, $volunteers_query);
?>

<div class="hero">
    <h1>Our Volunteers</h1>
    <div class="breadcrumb">
        <a href="<?php echo SITE_URL; ?>/index.php"><i class="fas fa-home"></i> Home</a>
        <span>></span>
        <span>Volunteers</span>
    </div>
</div>

<div class="container">
    <div style="text-align: center; margin-bottom: 50px;">
        <h2 style="font-size: 36px; color: #6b4a8e; margin-bottom: 15px;">Meet Our Amazing Team</h2>
        <p style="font-size: 18px; color: #666;">Dedicated individuals making a difference every day</p>
    </div>
    
    <div style="display: grid; grid-template-columns: repeat(auto-fit, minmax(280px, 1fr)); gap: 30px;">
        <?php while ($volunteer = mysqli_fetch_assoc($volunteers_result)): ?>
        <div style="background: white; border-radius: 10px; box-shadow: 0 4px 20px rgba(0,0,0,0.1); overflow: hidden; text-align: center;">
            <div style="height: 150px; background: linear-gradient(135deg, #6b4a8e, #8b5db4); display: flex; align-items: center; justify-content: center; color: white; font-size: 64px;">
                <i class="fas fa-user-circle"></i>
            </div>
            <div style="padding: 25px;">
                <h3 style="color: #333; margin-bottom: 10px; font-size: 20px;"><?php echo htmlspecialchars($volunteer['Vol_Name']); ?></h3>
                <p style="color: #888; font-size: 14px; margin-bottom: 15px;">
                    <i class="fas fa-venus-mars"></i> <?php echo $volunteer['Gender']; ?>
                </p>
                <div style="text-align: left; background: #f8f9fa; padding: 15px; border-radius: 8px;">
                    <p style="color: #666; font-size: 14px; margin-bottom: 8px;">
                        <i class="fas fa-map-marker-alt"></i> <strong>Area:</strong> <?php echo htmlspecialchars($volunteer['Area_Assigned']); ?>
                    </p>
                    <p style="color: #666; font-size: 14px; margin-bottom: 8px;">
                        <i class="fas fa-envelope"></i> <strong>Email:</strong> <?php echo htmlspecialchars($volunteer['Email']); ?>
                    </p>
                    <p style="color: #666; font-size: 14px;">
                        <i class="fas fa-phone"></i> <strong>Phone:</strong> <?php echo htmlspecialchars($volunteer['Phone']); ?>
                    </p>
                </div>
            </div>
        </div>
        <?php endwhile; ?>
    </div>
    
    <?php if (mysqli_num_rows($volunteers_result) == 0): ?>
    <div style="text-align: center; padding: 60px 20px;">
        <i class="fas fa-users" style="font-size: 72px; color: #ddd; margin-bottom: 20px;"></i>
        <p style="font-size: 18px; color: #888;">No volunteers registered yet.</p>
    </div>
    <?php endif; ?>
</div>

<?php require_once '../includes/footer.php'; ?>
