<?php
require_once '../config/config.php';

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $email = mysqli_real_escape_string($conn, trim($_POST['email']));
    
    // In a real application, you would save this to a subscribers table
    // For now, we'll just show a success message
    
    $_SESSION['success_message'] = "Thank you for subscribing! You'll receive our updates at $email";
    header('Location: ' . $_SERVER['HTTP_REFERER']);
    exit();
} else {
    header('Location: ' . SITE_URL . '/index.php');
    exit();
}
?>
