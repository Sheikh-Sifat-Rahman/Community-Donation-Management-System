<?php
$current_page = 'donation';
$page_title = 'Donate Now';
require_once '../includes/header.php';

// Fetch items from warehouse
$items_query = "SELECT * FROM WAREHOUSE ORDER BY Item_Name";
$items_result = mysqli_query($conn, $items_query);
?>

<!-- Hero Section -->
<div class="hero">
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
        <strong>Notice:</strong> Test Mode Is Enabled. While In Test Mode No Live Donations Are Processed.
    </div>

    <div class="donation-section">
        <!-- Donation Form -->
        <div class="donation-form">
            <form id="donationForm" method="POST" action="<?php echo SITE_URL; ?>/includes/process_donation.php">
                <!-- Amount Slider -->
                <div class="amount-slider">
                    <span style="font-size: 36px;">৳</span>
                    <input type="number" id="donationAmount" name="amount" value="5000" min="1" required>
                </div>

                <!-- Amount Buttons -->
                <div class="amount-buttons">
                    <button type="button" class="amount-btn" data-amount="1000">৳1,000</button>
                    <button type="button" class="amount-btn" data-amount="5000">৳5,000</button>
                    <button type="button" class="amount-btn" data-amount="10000">৳10,000</button>
                    <button type="button" class="amount-btn active" data-amount="50000">৳50,000</button>
                    <button type="button" class="amount-btn" id="customBtn">Custom Amount</button>
                </div>

                <!-- Select Item -->
                <div class="form-group">
                    <label for="item_id"><strong>Select Item to Donate</strong></label>
                    <select name="item_id" id="item_id" required>
                        <option value="">-- Select Item --</option>
                        <?php while($item = mysqli_fetch_assoc($items_result)): ?>
                            <option value="<?php echo $item['Item_ID']; ?>">
                                <?php echo $item['Item_Name']; ?> (Available: <?php echo $item['quantity']; ?>)
                            </option>
                        <?php endwhile; ?>
                    </select>
                </div>

                <!-- Payment Method -->
                <div class="payment-method">
                    <h3>Select Payment Method</h3>
                    <div class="payment-options">
                        <div class="payment-option">
                            <input type="radio" id="testDonation" name="payment_method" value="Test Donation" checked>
                            <label for="testDonation">Test Donation</label>
                        </div>
                        <div class="payment-option">
                            <input type="radio" id="offlineDonation" name="payment_method" value="Offline Donation">
                            <label for="offlineDonation">Offline Donation</label>
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

        <!-- Campaign Card & Organizer -->
        <div>
            <!-- Campaign Card -->
            <div class="campaign-card">
                <div class="campaign-image">
                    <img src="<?php echo SITE_URL; ?>/assets/images/campaign.jpg" alt="Campaign">
                    <span class="campaign-badge">FOOD</span>
                </div>
                <div class="campaign-progress">
                    <div class="progress-info">
                        <span><strong>Raised $30,050</strong></span>
                        <span><strong>Goal $50,000</strong></span>
                    </div>
                    <div class="progress-bar">
                        <div class="progress-fill" style="width: 60%"></div>
                    </div>
                    <h3 class="campaign-title">Raise Funds For Clean & Healthy Food</h3>
                    <button class="details-btn">
                        <i class="fas fa-arrow-right"></i> Donation Details
                    </button>
                </div>
            </div>

            <!-- Organizer Card -->
            <div class="organizer-card">
                <h3>Organizer</h3>
                <div class="organizer-info">
                    <div class="organizer-avatar">
                        <img src="<?php echo SITE_URL; ?>/assets/images/organizer.jpg" alt="Eluse A. Phillips">
                    </div>
                    <div class="organizer-details">
                        <h4>Eluse A. Phillips</h4>
                        <p>Watkins Ridge</p>
                    </div>
                </div>
                <div class="organizer-address">
                    <i class="fas fa-map-marker-alt"></i>
                    <span><strong>Address:</strong> 350 5th Avenue York</span>
                </div>
            </div>
        </div>
    </div>
</div>

<?php require_once '../includes/footer.php'; ?>
