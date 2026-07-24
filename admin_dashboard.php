<?php 
session_start();
//echo  $_SESSION['user_id'];

// Check if the user is logged in
if (!isset($_SESSION['role']) || $_SESSION['role'] != 'Admin') {

    // If not logged in, redirect to login page
    
    header("Location: login.php");
    exit();

}  
// Sample genres array. Replace this with a database query result in a real application.
$genres = ['Action', 'Comedy', 'Drama', 'Horror', 'Sci-Fi', 'Romance', 'Documentary'];

?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Admin Dashboard</title>
    <link rel="stylesheet" href="css/admin_dashboard.css"> <!-- Link to your CSS file -->
</head>
<body>
    <div class="admin-dashboard-container">
        <!-- Sidebar -->
        <aside class="sidebar">
            <h2>Admin Dashboard</h2>
            <ul>
                <li><a href="#" id="add-movie-link">Add Movies</a></li>
                <li><a href="#" id="edit-movie-link">Edit Movies</a></li>
                <li><a href="#" id="view-logs-link">View User Logs</a></li>
                <li><a href="#" id="generate-report-link">Generate Report</a></li>
                <li><a href="logout.php" id="log-out">Logout</a></li>
            </ul>
        </aside>

        <!-- Main Content -->
        <main class="main-content">
            <!-- Add Movie Form -->
            <section id="add-movie" class="admin-section">
                <h2>Add Movie</h2>
                <form method="POST" action="add_movie.php" enctype="multipart/form-data">
                    <label for="title">Title:</label>
                    <input type="text" id="title" name="title" required><br><br>

                    <label for="genre">Genre:</label>
                    <select id="genre" name="genre" required>
            <option value="" disabled selected>Select a genre</option>
            <?php foreach ($genres as $genre): ?>
                <option value="<?php echo htmlspecialchars($genre); ?>"><?php echo htmlspecialchars($genre); ?></option>
            <?php endforeach; ?>
        </select>

                    <label for="release_date">Release Date:</label>
                    <input type="date" id="release_date" name="release_date" required><br><br>

                    <label for="synopsis">Synopsis:</label>
                    <textarea id="synopsis" name="synopsis" rows="4" required></textarea><br><br>

                    <label for="poster">Poster:</label>
                    <input type="file" id="poster" name="poster" accept="image/*" required><br><br>

                    <label for="video_url">Video URL:</label>
                    <input type="url" id="video_url" name="video_url" required><br><br>

                    <button type="submit">Add Movie</button>
                </form>
            </section>
            <p id='ss'></p>

            <!-- Edit Movie Form -->
            <section id="edit-movie" class="admin-section" style="display: none;" >
               
            <section id="edit-movie-form" class="admin-section" style="display: none;" >
               
                   
</section>
                <!-- Form for editing movie will be dynamically generated -->
            </section>

            <!-- User Logs -->
            <section id="view-logs" class="admin-section" style="display: none;">
                <h2>User Logs</h2>
                <table>
                    <thead>
                        <tr>
                            <th>ID</th>
                            <th>User ID</th>
                            <th>Action</th>
                            <th>Details</th>
                            <th>Date</th>
                        </tr>
                    </thead>
                    <tbody>
                        <!-- User logs will be dynamically populated here -->
                    </tbody>
                </table>
            </section>

            <!-- Report Generation -->
            <section id="generate-report" class="admin-section" style="display: none;">
           <h2>Generate Report</h2>
    <form method="GET" action="report.php">
        <label for="timestamp">Select Date</label>
        <input type="date" id="report_date" name="report_date" required><br><br>

        <button type="submit">Generate Report</button>
    </form>
</section>

        </main>
    </div>

   

    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>


    <!-- DataTables CSS -->
    <link rel="stylesheet" href="https://cdn.datatables.net/1.13.1/css/jquery.dataTables.min.css">

    <!-- DataTables JS -->
    <script src="https://cdn.datatables.net/1.13.1/js/jquery.dataTables.min.js"></script>
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
<script src="js/admin_dashboard.js"></script>


   
    <!-- Link to your JavaScript file -->
    <div id="poster" style="background-repeat: no-repeat; background-position: center; background-image: url('poster.jpg'); background-size: cover;"></div>

</body>

</html>
