<?php
// Database Configuration
define('DB_HOST', 'localhost');
define('DB_USER', 'root');
define('DB_PASS', '');
define('DB_NAME', 'community_donation');

// Site Configuration
define('SITE_NAME', 'Clean Hearts');
define('SITE_URL', 'http://localhost/Community Donation');
define('SITE_EMAIL', 'info.cleanhearts@gmail.com');
define('SITE_PHONE', '(456) 555-0100');
define('SITE_ADDRESS', '1901 N Pitt Str., Suite 170 Alexandria, USA');

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
