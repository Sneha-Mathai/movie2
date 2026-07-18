<?php session_start();

// Check if user is already logged in
if (isset($_SESSION['user_id'])) {
    // Redirect to the user dashboard if already logged in
    header("Location: dashboard.php");
    exit();
}
?>