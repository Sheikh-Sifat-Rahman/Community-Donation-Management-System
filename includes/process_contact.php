<?php
require_once '../config/config.php';

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $name = mysqli_real_escape_string($conn, trim($_POST['name']));
    $email = mysqli_real_escape_string($conn, trim($_POST['email']));
    $subject = mysqli_real_escape_string($conn, trim($_POST['subject']));
    $message = mysqli_real_escape_string($conn, trim($_POST['message']));
    
    // In a real application, you would save this to a contact messages table
    // and/or send an email
    
    $_SESSION['success_message'] = "Thank you for contacting us! We'll get back to you soon.";
    header('Location: ' . SITE_URL . '/pages/contact.php');
    exit();
} else {
    header('Location: ' . SITE_URL . '/pages/contact.php');
    exit();
}
?>
