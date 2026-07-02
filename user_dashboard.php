<?php session_start();

// Check if the user is logged in
if (!isset($_SESSION['user_id'])|| $_SESSION['role']=='Admin') {
    // If not logged in, redirect to login page
    header("Location: login.php");
    exit();
}

// Optional: Retrieve username if stored in session or database
$username = isset($_SESSION['username']) ? $_SESSION['username'] : 'User';
//$imageUrl = $user['image_url']; // Fallback to a default image if no URL is set


?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>User Dashboard</title>
    <link rel="stylesheet" href="dashboard.css"> <!-- Link to your CSS file -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.3/css/all.min.css"> <!-- Icons -->
</head>
<body>
    <div class="dashboard-container">
        <!-- Sidebar -->
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

        <!-- Main Content -->
        <main class="main-content">
            <!-- Trending Movies Carousel -->
            <section class="trending-movies">
                <h2>Trending Movies</h2>
                <div class="carousel">
                    <!-- Carousel items -->
                    <div class="carousel-item">
                        <img src="movie1.jpg" alt="Movie 1">
                        <h3>Movie Title 1</h3>
                    </div>
                    <div class="carousel-item">
                        <img src="movie2.jpg" alt="Movie 2">
                        <h3>Movie Title 2</h3>
                    </div>
                    <!-- Add more items as needed -->
                </div>
            </section>

            <!-- Movie Listing -->
            <section class="movie-listing">
                <h2>Available Movies</h2>
                <div class="movies" id="movies-container">
                    <div class="movie-item">
                        <img src="movie1.jpg" alt="Movie 1">
                        <h3>Movie Title 1</h3>
                    </div>
                    <div class="movie-item">
                        <img src="movie2.jpg" alt="Movie 2">
                        <h3>Movie Title 2</h3>
                    </div>
                    <!-- Add more movie items as needed -->
                </div>
            </section>
            <section class="change-password" id="change-password-section">
                <h2>Change Password</h2>
                <form id="change-password-form">
                    <label for="current_password">Current Password:</label>
                    <input type="password" id="current_password" name="current_password" required>

                    <label for="new_password">New Password:</label>
                    <input type="password" id="new_password" name="new_password" required>

                    <label for="confirm_new_password">Confirm New Password:</label>
                    <input type="password" id="confirm_new_password" name="confirm_new_password" required>

                    <button type="submit">Change Password</button>
                </form>
            </section>
        </main>
    </div>
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
<script>
    $(document).ready(function() {
        $('#change-password-section').hide();
        $.ajax({
            url: 'movies.php?action=fetchMovies', // Ensure the action matches your PHP script
            method: 'GET',
            dataType: 'json',
            success: function(response) {
                var moviesContainer = $('#movies-container');
                moviesContainer.empty(); // Clear any existing content
    
                $.each(response, function(index, movie) {
                    var movieItem = $('<div class="movie-item">').append(
                        $('<img>').attr('src', movie.poster_url).attr('alt', movie.title),
                        $('<h3>').text(movie.title)
                    ).click(function() {
                    // Send the movie ID as data in the AJAX request
                    window.location.href = 'movie_detail.php?id=' + movie.id;
                });

    
                    moviesContainer.append(movieItem);
                });
            }
        });
    
    $.ajax({
        url: 'movies.php?action=fetchTrendingMovies', // Ensure the action matches your PHP script
        method: 'GET',
        dataType: 'json',
        success: function(response) {
            var carouselContainer = $('.carousel');
            carouselContainer.empty(); // Clear any existing content
    
            $.each(response, function(index, movie) {
                var carouselItem = $('<div class="carousel-item">').append(
                    $('<img>').attr('src', movie.poster_url).attr('alt', movie.title),
                    $('<h3>').text(movie.title)
                ).click(function() {
                    // Send the movie ID as data in the AJAX request
                    window.location.href = 'movie_detail.php?id=' + movie.id;
                });


                carouselContainer.append(carouselItem);
            });
        }
    });
    $('#change-password-btn').click(function() {
        //e.preventDefault();
        $('.movie-listing').hide();
        $('.trending-movies').hide();
               // $('#movies-container').hide()

                $('#change-password-section').show();
            });

            // Handle form submission with AJAX (optional)
            $('#change-password-form').submit(function(event) {
                event.preventDefault(); // Prevent default form submission

                $.ajax({
                    url: 'change_password.php',
                    method: 'POST',
                    data: $(this).serialize(),
                    success: function(response) {
                        alert('Password changed successfully!');
                        $('#change-password-section').hide();
                        $('.movie-listing').show();
                        $('.trending-movies').show();
                    },
                    error: function() {
                        alert('An error occurred while changing the password.');
                    }
                });
            });
            /*$('#edit-form').click(function(){
                $.ajax({
                    url:'user.php?action=edituser',
                    type:'get',
                    success:funtion(){

                    }

                })
                


            }
       // var userID=

        )*/
});
    </script>
    
</body>
</html>
