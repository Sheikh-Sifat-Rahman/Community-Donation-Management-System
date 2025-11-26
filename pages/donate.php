<?php
$current_page = 'donation';
$page_title = 'Donate Now';
require_once '../includes/header.php';

// Fetch items from warehouse
$items_query = "SELECT * FROM WAREHOUSE ORDER BY Item_Name";
$items_result = mysqli_query($conn, $items_query);
?>

<!-- Hero Section -->
<div class="hero" style="background: linear-gradient(rgba(255, 107, 107, 0.85), rgba(255, 142, 83, 0.85)), url('<?php echo SITE_URL; ?>/assets/images/donation-help.jpg'); background-size: cover; background-position: center;">
    <h1>Donate Now</h1>
    <div class="breadcrumb">
        <a href="<?php echo SITE_URL; ?>/index.php"><i class="fas fa-home"></i> Home</a>
        <span>></span>
        <span>Donate Now</span>
    </div>
</div>

<!-- Main Content -->
<div class="container">
    <!-- Notice Alert -->
    <div class="notice-alert">
        <strong>Notice:</strong> You can donate items from your home or bring them directly to our warehouse.
    </div>

    <div class="donation-section">
        <!-- Donation Form -->
        <div class="donation-form">
            <form id="donationForm" method="POST" action="<?php echo SITE_URL; ?>/includes/process_donation.php">
                <!-- Select Item -->
                <div class="form-group">
                    <label for="item_id"><strong>Select Item to Donate</strong></label>
                    <select name="item_id" id="item_id" required>
                        <option value="">-- Select Item --</option>
                        <?php while($item = mysqli_fetch_assoc($items_result)): ?>
                            <option value="<?php echo $item['Item_ID']; ?>">
                                <?php echo $item['Item_Name']; ?>
                            </option>
                        <?php endwhile; ?>
                    </select>
                </div>
                
                <!-- Quantity -->
                <div class="form-group">
                    <label for="quantity"><strong>Quantity</strong></label>
                    <input type="number" name="quantity" id="quantity" placeholder="Enter quantity" min="1" required style="padding: 12px; border: 2px solid #e0e0e0; border-radius: 8px; font-size: 14px;">
                </div>

                <!-- Donation Location -->
                <div class="payment-method">
                    <h3>Select Donation Location</h3>
                    <div class="payment-options">
                        <div class="payment-option">
                            <input type="radio" id="fromHome" name="payment_method" value="Pickup from Home" checked>
                            <label for="fromHome">
                                <i class="fas fa-home"></i> Pickup from Home
                            </label>
                        </div>
                        <div class="payment-option">
                            <input type="radio" id="toWarehouse" name="payment_method" value="Deliver to Warehouse">
                            <label for="toWarehouse">
                                <i class="fas fa-warehouse"></i> Deliver to Warehouse
                            </label>
                        </div>
                    </div>
                </div>

                <!-- Personal Info -->
                <div class="personal-info">
                    <h3>Personal Info</h3>
                    <div class="form-row">
                        <div class="form-group">
                            <input type="text" name="first_name" placeholder="First Name" required>
                        </div>
                        <div class="form-group">
                            <input type="text" name="last_name" placeholder="Last Name" required>
                        </div>
                    </div>
                    <div class="form-group">
                        <input type="email" name="email" placeholder="Email Address" required>
                    </div>
                    <div class="form-group">
                        <input type="tel" name="phone" placeholder="Phone Number" required>
                    </div>
                    <div class="form-group">
                        <select name="donor_type" required>
                            <option value="">-- Select Donor Type --</option>
                            <option value="Individual">Individual</option>
                            <option value="Organization">Organization</option>
                            <option value="Anonymous">Anonymous</option>
                        </select>
                    </div>
                    <div class="form-group">
                        <textarea name="address" rows="3" placeholder="Address (Optional)"></textarea>
                    </div>
                </div>

                <button type="submit" class="submit-btn">
                    <i class="fas fa-hand-holding-heart"></i> Donate Now
                </button>
            </form>
        </div>
    </div>
</div>

<?php require_once '../includes/footer.php'; ?>
