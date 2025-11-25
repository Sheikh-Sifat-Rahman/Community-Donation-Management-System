<?php
$current_page = 'warehouse';
$page_title = 'Warehouse Inventory';
require_once '../includes/header.php';

// Fetch all warehouse items
$warehouse_query = "SELECT * FROM WAREHOUSE ORDER BY Item_Name";
$warehouse_result = mysqli_query($conn, $warehouse_query);
?>

<div class="hero">
    <h1>Warehouse Inventory</h1>
    <div class="breadcrumb">
        <a href="<?php echo SITE_URL; ?>/index.php"><i class="fas fa-home"></i> Home</a>
        <span>></span>
        <span>Warehouse</span>
    </div>
</div>

<div class="container">
    <div style="text-align: center; margin-bottom: 50px;">
        <h2 style="font-size: 36px; color: #6b4a8e; margin-bottom: 15px;">Available Items</h2>
        <p style="font-size: 18px; color: #666;">Current inventory of donation items</p>
    </div>
    
    <div style="display: grid; grid-template-columns: repeat(auto-fit, minmax(250px, 1fr)); gap: 30px;">
        <?php while ($item = mysqli_fetch_assoc($warehouse_result)): ?>
        <div style="background: white; border-radius: 10px; box-shadow: 0 4px 20px rgba(0,0,0,0.1); padding: 30px; text-align: center;">
            <div style="width: 80px; height: 80px; margin: 0 auto 20px; background: linear-gradient(135deg, #6b4a8e, #8b5db4); border-radius: 50%; display: flex; align-items: center; justify-content: center; color: white; font-size: 36px;">
                <i class="fas fa-box"></i>
            </div>
            <h3 style="color: #333; margin-bottom: 15px; font-size: 20px;"><?php echo htmlspecialchars($item['Item_Name']); ?></h3>
            <div style="background: #f8f9fa; padding: 15px; border-radius: 8px;">
                <p style="color: #666; font-size: 14px; margin-bottom: 5px;">Available Quantity</p>
                <p style="color: #6b4a8e; font-size: 32px; font-weight: 700;"><?php echo $item['quantity']; ?></p>
                <p style="color: #888; font-size: 12px;">units</p>
            </div>
        </div>
        <?php endwhile; ?>
    </div>
</div>

<?php require_once '../includes/footer.php'; ?>
