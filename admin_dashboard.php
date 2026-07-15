<?php 
session_start();
//echo  $_SESSION['user_id'];

// Check if the user is logged in
if (!isset($_SESSION['user_id']) || $_SESSION['role']=='User') {

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
    <link rel="stylesheet" href="admin_dashboard.css"> <!-- Link to your CSS file -->
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


    <script>
    $(document).ready(function() {
        // Handle click on "Edit Movies" link

       // $('#movies-table').DataTable();
    // Handle the submission of the Add Movie form
    $('#add-movie-link').on('click', function(event) {
        
            
        $('.admin-section').hide();
        $('#edit-movie').hide();
        $('#add-movie').show();
    });



        $('#edit-movie-link').on('click', function() {
            $.ajax({
                url: 'movies.php?action=fetchMovies',
                method: 'GET',
                dataType: 'json',
                success: function(data) {
                    let moviesTable= `<h2>Edit Movie</h2>
                        <table  width="100%" border="1" id="movies-table">
                            <thead>
                                <tr>
                                    <th>Poster</th>
                                    <th>Title</th>
                                    <th>Year</th>
                                    <th>Video</th>
                                    <th>Action</th>
                                </tr>
                            </thead>
                            <tbody>
                    `;

                   // print_r(moviesTable);
                    
                    $.each(data, function(index, movie) {
                        moviesTable += `
                            <tr>
                                <td height="100"><img src="${movie.poster_url}" alt="${movie.title}" class="thumbnail" /></td>
                                <td>${movie.title}</td>
                                <td>${movie.release_date}</td>
                                <td>${movie.video_url}</td>
                                <td><button class="edit-btn" data-id="${movie.id}">Edit</button>
                                <button class="delete-btn" data-id="${movie.id}">Delete</button></td>
                                
                            </tr>
                        `;});
                        moviesTable += `
                            </tbody>
                        </table>
                    `;
                   // console.log(moviesTable);
                    $('#edit-movie').html(moviesTable);
                    // Initialize DataTables after the table has been inserted into the DOM
                    $('#movies-table').DataTable();
                },
                error: function() {
                    alert('Error loading movies.');
                }
            });
            // Hide other sections and show the edit movie section
            $('.admin-section').hide();
            $('#edit-movie').show();
        });
        $(document).on('click', '.edit-btn', function() {
            event.preventDefault();

        const movieId = $(this).data('id');

        // Fetch the movie details for the selected movie (you can also get these details directly from the table)
        $.ajax({
            url: 'movies.php?action=getMovie',
            method: 'GET',
            data: { id: movieId },
            dataType: 'json',
            success: function(data) {
                let movie = data[0];
                console.log(movie);
                // Populate the edit form with movie details
                let editForm = `
                    <h2>Edit Movie</h2>
                    <form id="edit-movie-form" enctype="multipart/form-data">
                        <input type="hidden" name="id" value="${movie.id}" />
                        <label for="title">Title:</label>
                        <input type="text" id="title" name="title" value="${movie.title}" required><br><br>

                        <label for="genre">Genre:</label>
                        <input type="text" id="genre" name="genre" value="${movie.genre}" required><br><br>

                        <label for="release_date">Release Date:</label>
                        <input type="date" id="release_date" name="release_date" value="${movie.release_date}" required><br><br>

                        <label for="synopsis">Synopsis:</label>
                        <textarea id="synopsis" name="synopsis" rows="4" required>${movie.synopsis}</textarea><br><br>

                        <label for="poster">Poster:</label>
                        <input type="file" id="poster" name="poster" accept="image/*"><br><br>

                        <label for="video_url">Video URL:</label>
                        <input type="url" id="video_url" name="video_url" ><br><br>

                        <button type="submit">Update Movie</button>
                    </form>
                `;
                console.log(editForm);
                $('#edit-movie-form').attr('display','block');
                $('#ss').html(editForm);
               // $('.edit-movie').hide();
                $('.admin-section').hide(); // Hide other sections
            },
            error: function() {
                alert('Error loading movie details.');
            }
        });
    });

    $(document).on('click', '.delete-btn', function() {
    if (confirm('Are you sure you want to delete this movie?')) {
        const movieId = $(this).data('id');

        $.ajax({
            url: 'movies.php?action=deleteMovie',
            method: 'POST',
            data: { id: movieId },
            success: function(response) {
                alert('Movie deleted successfully!');
                $('#edit-movie-link').click(); // Reload the movie list
            },
            error: function() {
                alert('Error deleting movie.');
            }
        });
    }
});


    // Handle the submission of the edit movie form
    $(document).on('submit', '#edit-movie-form', function(event) {
        event.preventDefault();

        // Prepare form data
        const formData = new FormData(this);
        // Send the form data via AJAX to update the movie
        $.ajax({
            url: 'movies.php?action=updateMovie',
            method: 'POST',
            data: formData,
            contentType: false,
            processData: false,
            success: function(response) {
                alert('Movie updated successfully!');
                $('#edit-movie').hide(); // Hide the edit form
                $('#edit-movie-link').click(); // Reload the movie list
            },
            error: function() {
                alert('Error updating movie.');
            }
        });
    });
    $('#generate-report-link').on('click', function() {
        // Hide other sections and show the generate report section
        $('.admin-section').hide();
        $('#generate-report').show();
    });

    $('#generate-report form').on('submit', function(event) {
        event.preventDefault();

        // Prepare form data
        const formData = $(this).serialize();

        // Redirect to generate the report
        window.location.href = 'report.php?' + formData;
    });


    });
        </script>
    <!-- Link to your JavaScript file -->
    <div id="poster" style="background-repeat: no-repeat; background-position: center; background-image: url('poster.jpg'); background-size: cover;"></div>

</body>

</html>
