<?php
$current_page = 'donation';
$page_title = 'Donation List';
require_once '../includes/header.php';

// Fetch all donations with donor and item details
$donations_query = "
    SELECT d.*, don.Name as Donor_Name, don.Email, don.Donor_Type, w.Item_Name 
    FROM DONATION d 
    JOIN DONOR don ON d.Donor_ID = don.Donor_ID 
    JOIN WAREHOUSE w ON d.Item_ID = w.Item_ID 
    ORDER BY d.Date_Donated DESC
";
$donations_result = mysqli_query($conn, $donations_query);
?>

<div class="hero">
    <h1>Donation History</h1>
    <div class="breadcrumb">
        <a href="<?php echo SITE_URL; ?>/index.php"><i class="fas fa-home"></i> Home</a>
        <span>></span>
        <span>Donation</span>
    </div>
</div>

<div class="container">
    <div style="display: flex; justify-content: space-between; align-items: center; margin-bottom: 30px;">
        <h2 style="color: #ff6b6b;">All Donations</h2>
        <a href="<?php echo SITE_URL; ?>/pages/donate.php" class="donate-btn">
            <i class="fas fa-plus"></i> New Donation
        </a>
    </div>
    
    <div style="background: white; border-radius: 10px; box-shadow: 0 4px 20px rgba(0,0,0,0.1); overflow: hidden;">
        <table style="width: 100%; border-collapse: collapse;">
            <thead style="background: linear-gradient(135deg, #ff6b6b, #ff8e53); color: white;">
                <tr>
                    <th style="padding: 15px; text-align: left;">ID</th>
                    <th style="padding: 15px; text-align: left;">Donor Name</th>
                    <th style="padding: 15px; text-align: left;">Type</th>
                    <th style="padding: 15px; text-align: left;">Item</th>
                    <th style="padding: 15px; text-align: left;">Quantity</th>
                    <th style="padding: 15px; text-align: left;">Payment Method</th>
                    <th style="padding: 15px; text-align: left;">Date</th>
                </tr>
            </thead>
            <tbody>
                <?php while ($donation = mysqli_fetch_assoc($donations_result)): ?>
                <tr style="border-bottom: 1px solid #e0e0e0;">
                    <td style="padding: 15px;">#<?php echo $donation['Donation_ID']; ?></td>
                    <td style="padding: 15px;"><?php echo htmlspecialchars($donation['Donor_Name']); ?></td>
                    <td style="padding: 15px;">
                        <span style="background: #e8f4fd; color: #0066cc; padding: 5px 10px; border-radius: 12px; font-size: 12px;">
                            <?php echo $donation['Donor_Type']; ?>
                        </span>
                    </td>
                    <td style="padding: 15px;"><?php echo htmlspecialchars($donation['Item_Name']); ?></td>
                    <td style="padding: 15px;"><?php echo $donation['Quantity_Donated']; ?></td>
                    <td style="padding: 15px;"><?php echo $donation['Payment_Method']; ?></td>
                    <td style="padding: 15px;"><?php echo date('M j, Y', strtotime($donation['Date_Donated'])); ?></td>
                </tr>
                <?php endwhile; ?>
            </tbody>
        </table>
    </div>
</div>

<?php require_once '../includes/footer.php'; ?>
