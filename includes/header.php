<?php 
// Use absolute path for config
$config_path = $_SERVER['DOCUMENT_ROOT'] . '/Community-Donation-Management-System/config/config.php';
if (file_exists($config_path)) {
    require_once $config_path;
} else {
    // Fallback for different directory structures
    require_once dirname(dirname(__FILE__)) . '/config/config.php';
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?php echo isset($page_title) ? $page_title . ' - ' : ''; ?><?php echo SITE_NAME; ?></title>
    <link rel="stylesheet" href="<?php echo SITE_URL; ?>/assets/css/style.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
</head>
<body>
    <!-- Top Bar -->
    <div class="top-bar">
        <div class="container">
            <div class="top-bar-left">
                <span><i class="fas fa-envelope"></i> <?php echo SITE_EMAIL; ?></span>
                <span><i class="fas fa-map-marker-alt"></i> Dhaka, Bangladesh</span>
            </div>
            <div class="top-bar-right">
                <a href="#"><i class="fab fa-facebook-f"></i></a>
                <a href="#"><i class="fab fa-twitter"></i></a>
                <a href="#"><i class="fab fa-linkedin-in"></i></a>
                <a href="#"><i class="fab fa-youtube"></i></a>
            </div>
        </div>
    </div>

    <!-- Header -->
    <header>
        <div class="header-content">
            <a href="<?php echo SITE_URL; ?>/index.php" class="logo">
                <svg width="50" height="50" viewBox="0 0 50 50" fill="none" xmlns="http://www.w3.org/2000/svg">
                    <!-- Warm gradient background circle -->
                    <circle cx="25" cy="25" r="22" fill="url(#warmGradient)" opacity="0.2"/>
                    <!-- Two hands forming heart shape -->
                    <path d="M 25 18 Q 20 12, 13 18 Q 10 20, 13 25 L 25 35" fill="url(#warmGradient)"/>
                    <path d="M 25 18 Q 30 12, 37 18 Q 40 20, 37 25 L 25 35" fill="url(#warmGradient)"/>
                    <!-- Heart in center -->
                    <circle cx="25" cy="22" r="3" fill="#ff6b6b"/>
                    <defs>
                        <linearGradient id="warmGradient" x1="0%" y1="0%" x2="100%" y2="100%">
                            <stop offset="0%" style="stop-color:#ff6b6b;stop-opacity:1" />
                            <stop offset="100%" style="stop-color:#ff8e53;stop-opacity:1" />
                        </linearGradient>
                    </defs>
                </svg>
                <div class="logo-text">
                    <h1><?php echo SITE_NAME; ?></h1>
                    <p>Charity & Donation</p>
                </div>
            </a>

            <nav>
                <ul>
                    <li><a href="<?php echo SITE_URL; ?>/index.php" class="<?php echo ($current_page == 'home') ? 'active' : ''; ?>">Home</a></li>
                    <li><a href="<?php echo SITE_URL; ?>/pages/about.php" class="<?php echo ($current_page == 'about') ? 'active' : ''; ?>">About Us</a></li>
                    <?php if (isset($_SESSION['user_id'])): ?>
                        <li><a href="<?php echo SITE_URL; ?>/pages/dashboard.php" class="<?php echo ($current_page == 'dashboard') ? 'active' : ''; ?>">Dashboard</a></li>
                    <?php endif; ?>
                    <?php if (isset($_SESSION['user_id']) && $_SESSION['user_role'] === 'admin'): ?>
                        <li><a href="<?php echo SITE_URL; ?>/pages/pages.php" class="<?php echo ($current_page == 'pages') ? 'active' : ''; ?>">Admin Dashboard</a></li>
                    <?php endif; ?>
                    <li><a href="<?php echo SITE_URL; ?>/pages/events.php" class="<?php echo ($current_page == 'events') ? 'active' : ''; ?>">Events</a></li>
                    <?php if (isset($_SESSION['user_id'])): ?>
                        <li class="user-menu">
                            <a href="#"><i class="fas fa-user"></i> <?php echo htmlspecialchars($_SESSION['user_name']); ?></a>
                            <div class="dropdown">
                                <a href="<?php echo SITE_URL; ?>/pages/profile.php">Profile</a>
                                <a href="<?php echo SITE_URL; ?>/pages/logout.php">Logout</a>
                            </div>
                        </li>
                    <?php else: ?>
                        <li><a href="<?php echo SITE_URL; ?>/pages/login.php" class="login-btn"><i class="fas fa-sign-in-alt"></i> Login</a></li>
                        <li><a href="<?php echo SITE_URL; ?>/pages/register.php" class="register-btn"><i class="fas fa-user-plus"></i> Register</a></li>
                    <?php endif; ?>
                </ul>
            </nav>
        </div>
    </header>
