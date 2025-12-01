<?php
require_once '../config/config.php';

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    // Sanitize and validate input
    $first_name = mysqli_real_escape_string($conn, trim($_POST['first_name']));
    $last_name = mysqli_real_escape_string($conn, trim($_POST['last_name']));
    $email = mysqli_real_escape_string($conn, trim($_POST['email']));
    $phone = mysqli_real_escape_string($conn, trim($_POST['phone']));
    $donor_type = mysqli_real_escape_string($conn, $_POST['donor_type']);
    $address = mysqli_real_escape_string($conn, trim($_POST['address']));
    $quantity = intval($_POST['quantity']);
    $item_id = intval($_POST['item_id']);
    $payment_method = mysqli_real_escape_string($conn, $_POST['payment_method']);
    
    $full_name = $first_name . ' ' . $last_name;
    $date_donated = date('Y-m-d');
    
    // Start transaction
    mysqli_begin_transaction($conn);
    
    try {
        // Check if donor exists
        $check_donor = "SELECT Donor_ID FROM DONOR WHERE Email = '$email'";
        $result = mysqli_query($conn, $check_donor);
        
        if (mysqli_num_rows($result) > 0) {
            // Existing donor
            $donor = mysqli_fetch_assoc($result);
            $donor_id = $donor['Donor_ID'];
            
            // Update donor info
            $update_donor = "UPDATE DONOR SET 
                            Name = '$full_name',
                            Donor_Type = '$donor_type',
                            Phone = '$phone',
                            Address = '$address'
                            WHERE Donor_ID = $donor_id";
            mysqli_query($conn, $update_donor);
        } else {
            // New donor
            $insert_donor = "INSERT INTO DONOR (Name, Email, Donor_Type, Phone, Address) 
                            VALUES ('$full_name', '$email', '$donor_type', '$phone', '$address')";
            mysqli_query($conn, $insert_donor);
            $donor_id = mysqli_insert_id($conn);
        }
        
        // Use the quantity provided by the user
        $quantity_donated = $quantity;
        
        // Insert donation record
        $insert_donation = "INSERT INTO DONATION (Donor_ID, Item_ID, Date_Donated, Quantity_Donated, Payment_Method) 
                           VALUES ($donor_id, $item_id, '$date_donated', $quantity_donated, '$payment_method')";
        mysqli_query($conn, $insert_donation);
        
        // Update warehouse quantity only if delivered to warehouse
        // For "Pickup from Home", warehouse will be updated when volunteer completes the pickup
        if ($payment_method !== 'Pickup from Home') {
            $update_warehouse = "UPDATE WAREHOUSE SET quantity = quantity + $quantity_donated WHERE Item_ID = $item_id";
            mysqli_query($conn, $update_warehouse);
        }
        
        // Commit transaction
        mysqli_commit($conn);
        
        // Set success message
        $_SESSION['success_message'] = "Thank you for your donation of $quantity_donated items! Your contribution has been recorded.";
        header('Location: ' . SITE_URL . '/pages/donation_success.php');
        exit();
        
    } catch (Exception $e) {
        // Rollback on error
        mysqli_rollback($conn);
        $_SESSION['error_message'] = "An error occurred while processing your donation. Please try again.";
        header('Location: ' . SITE_URL . '/pages/donate.php');
        exit();
    }
} else {
    // Redirect if accessed directly
    header('Location: ' . SITE_URL . '/pages/donate.php');
    exit();
}
?>
