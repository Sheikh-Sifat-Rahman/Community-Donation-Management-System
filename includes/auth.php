<?php
// Authentication check function
function checkAuth() {
    if (!isset($_SESSION['user_id'])) {
        header('Location: ' . SITE_URL . '/pages/login.php');
        exit();
    }
}

// Check if user is logged in
function isLoggedIn() {
    return isset($_SESSION['user_id']);
}

// Get current user info
function getCurrentUser() {
    if (isLoggedIn()) {
        return [
            'id' => $_SESSION['user_id'],
            'name' => $_SESSION['user_name'],
            'email' => $_SESSION['user_email'],
            'role' => $_SESSION['user_role']
        ];
    }
    return null;
}

// Check if user is admin
function isAdmin() {
    return isLoggedIn() && $_SESSION['user_role'] === 'admin';
}
?>
