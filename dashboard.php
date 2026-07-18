<?php
session_start();

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
    <title>Movie Dashboard</title>

    <link rel="stylesheet" href="css/dashboard.css">
    <link rel="stylesheet"
          href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.3/css/all.min.css">
</head>

<body>

<div class="dashboard-container">

    <nav class="navbar">
        <span class="logo">MOVIES</span>

        <ul class="nav-tabs">
            <li><a href="#" class="active" id="browse-tab">Browse</a></li>
            <li><a href="#" id="mylist-tab">My List</a></li>
        </ul>

        <div class="search-box">
            <input type="text" id="search-input" placeholder="Search titles...">
        </div>

        <select id="genre-filter">
            <option value="">All Genres</option>
        </select>

        <div class="nav-right">
            <span style="color:#999;font-size:13px;">
                <?php echo htmlspecialchars($username); ?>
            </span>

            <a href="editform.php">Edit Profile</a>

            <button id="change-password-btn">
                Change Password
            </button>

            <a href="logout.php">Logout</a>
        </div>
    </nav>

    <main class="main-content">

        <section class="trending-movies">
            <h2>Trending</h2>
            <div class="carousel"></div>
        </section>

        <section class="movie-listing">

            <div class="section-heading">
                <h1>Browse</h1>
                <span class="count" id="results-count"></span>
            </div>

            <div class="movies" id="movies-container"></div>

        </section>

        <section class="change-password"
                 id="change-password-section"
                 style="display:none;">

            <h2>Change Password</h2>

            <form id="change-password-form">

                <label for="current_password">
                    Current Password
                </label>

                <input
                    type="password"
                    id="current_password"
                    name="current_password"
                    required>

                <label for="new_password">
                    New Password
                </label>

                <input
                    type="password"
                    id="new_password"
                    name="new_password"
                    required>

                <label for="confirm_new_password">
                    Confirm New Password
                </label>

                <input
                    type="password"
                    id="confirm_new_password"
                    name="confirm_new_password"
                    required>

                <button type="submit">
                    Change Password
                </button>

            </form>

        </section>

    </main>

</div>

<script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>

<script src="js/dashboard.js"></script>

</body>
</html>