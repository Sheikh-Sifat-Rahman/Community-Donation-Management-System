<?php
// Database Configuration
define('DB_HOST', 'localhost');
define('DB_USER', 'root');
define('DB_PASS', '');
define('DB_NAME', 'community_donation');

// Site Configuration
define('SITE_NAME', 'Warm Hands');
define('SITE_URL', 'http://localhost:8000');
define('SITE_EMAIL', 'info.warmhands@gmail.com');
define('SITE_PHONE', '(555) 123-4567');
define('SITE_ADDRESS', '123 Hope Street, Community Center, USA');

// Create database connection
$conn = mysqli_connect(DB_HOST, DB_USER, DB_PASS, DB_NAME);

// Check connection
if (!$conn) {
    die("Connection failed: " . mysqli_connect_error());
}

// Set charset to utf8
mysqli_set_charset($conn, "utf8");

// Session start
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}
?>
