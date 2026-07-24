<?php
session_start();

// Log the logout action before destroying session
if (isset($_SESSION['user_id'])) {
    include 'config.php';
    require_once __DIR__ . '/logger.php';
    
    $logger = new Logger();
    $username = $_SESSION['username'] ?? 'Unknown';
    $logger->logUserAction($_SESSION['user_id'], 'logout', 'User logged out: ' . $username);
}

// Destroy all session data
session_unset();
session_destroy();

// Redirect to login page or homepage
header("Location: login.php");
exit;
?>
