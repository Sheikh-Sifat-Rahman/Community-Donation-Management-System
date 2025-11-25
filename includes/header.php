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
                <span><i class="fas fa-map-marker-alt"></i> 18 Jaclin Harbour Roadstown, PA 19020</span>
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
                <svg width="50" height="50" viewBox="0 0 50 50" fill="none">
                    <circle cx="25" cy="25" r="20" fill="#6b4a8e" opacity="0.2"/>
                    <path d="M25 10 C 15 15, 15 35, 25 40 C 35 35, 35 15, 25 10 Z" fill="#6b4a8e"/>
                    <circle cx="25" cy="25" r="8" fill="white"/>
                    <path d="M 22 25 L 25 28 L 30 20" stroke="#6b4a8e" stroke-width="2" fill="none"/>
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
                    <li><a href="<?php echo SITE_URL; ?>/pages/donation.php" class="<?php echo ($current_page == 'donation') ? 'active' : ''; ?>">Donation</a></li>
                    <?php if (isset($_SESSION['user_id'])): ?>
                        <li><a href="<?php echo SITE_URL; ?>/pages/dashboard.php" class="<?php echo ($current_page == 'dashboard') ? 'active' : ''; ?>">Dashboard</a></li>
                    <?php endif; ?>
                    <?php if (isset($_SESSION['user_id']) && $_SESSION['user_role'] === 'admin'): ?>
                        <li><a href="<?php echo SITE_URL; ?>/pages/pages.php" class="<?php echo ($current_page == 'pages') ? 'active' : ''; ?>">Admin Dashboard</a></li>
                    <?php endif; ?>
                    <li><a href="<?php echo SITE_URL; ?>/pages/events.php" class="<?php echo ($current_page == 'events') ? 'active' : ''; ?>">Events</a></li>
                    <li><a href="<?php echo SITE_URL; ?>/pages/volunteers.php" class="<?php echo ($current_page == 'volunteers') ? 'active' : ''; ?>">Volunteers</a></li>
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
                    <li><a href="<?php echo SITE_URL; ?>/pages/donate.php" class="donate-btn"><i class="fas fa-hand-holding-heart"></i> Donate Now</a></li>
                </ul>
            </nav>
        </div>
    </header>
