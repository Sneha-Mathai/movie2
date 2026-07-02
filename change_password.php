<?php
session_start();
require 'config.php'; // Include your database connection
//public $con;

// Instantiate the Database class
$database = new Database();
$pdo = $database->getConnection(); // Get the PDO connection

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $userId = $_SESSION['user_id'];
    $currentPassword = $_POST['current_password'];
    $newPassword = $_POST['new_password'];
    $confirmNewPassword = $_POST['confirm_new_password'];

    // Validate inputs
    if (empty($currentPassword) || empty($newPassword) || empty($confirmNewPassword)) {
        echo 'All fields are required.';
        exit();
    }

    if ($newPassword !== $confirmNewPassword) {
        echo 'New passwords do not match.';
        exit();
    }

    // Fetch the current hashed password from the database
    $stmt = $pdo->prepare('SELECT password FROM users WHERE id = ?');
    $stmt->execute([$userId]);
    $user = $stmt->fetch();

    if (!$user || !password_verify($currentPassword, $user['password'])) {
        echo 'Current password is incorrect.';
        exit();
    }

    // Hash the new password
    $hashedPassword = password_hash($newPassword, PASSWORD_DEFAULT);

    // Update the password in the database
    $stmt = $pdo->prepare('UPDATE users SET password = ? WHERE id = ?');
    if ($stmt->execute([$hashedPassword, $userId])) {
        echo 'Password changed successfully.';
    } else {
        echo 'Failed to update password.';
    }
}
?>
