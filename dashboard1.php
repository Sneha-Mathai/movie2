<?php
session_start();

// Check if the user is logged in
if (!isset($_SESSION['user_id']) || $_SESSION['role'] == 'Admin') {
    header("Location: login.php");
    exit();
}

$username = $_SESSION['username'] ?? 'User';
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>User Dashboard</title>

    <link rel="stylesheet" href="css/dashboard.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.3/css/all.min.css">
</head>

<body>

<div class="dashboard-container">

    <aside class="sidebar">
        <div class="profile-section">
            <h3><?php echo htmlspecialchars($username); ?></h3>

            <ul>
                <li><a href="editform.php" id="edit-form">Edit Profile</a></li>
                <li><a href="#" id="change-password-btn">Change Password</a></li>
                <li><a href="logout.php">Logout</a></li>
            </ul>
        </div>
    </aside>

    <main class="main-content">

        <section class="trending-movies">
            <h2>Trending Movies</h2>
            <div class="carousel"></div>
        </section>

        <section class="movie-listing">
            <h2>Available Movies</h2>
            <div class="movies" id="movies-container"></div>
        </section>

        <section class="change-password" id="change-password-section">
            <h2>Change Password</h2>

            <form id="change-password-form">

                <label>Current Password</label>
                <input type="password" id="current_password" name="current_password" required>

                <label>New Password</label>
                <input type="password" id="new_password" name="new_password" required>

                <label>Confirm Password</label>
                <input type="password" id="confirm_new_password" name="confirm_new_password" required>

                <button type="submit">Change Password</button>

            </form>

        </section>

    </main>

</div>

<script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
<script src="js/dashboard.js"></script>

</body>
</html>