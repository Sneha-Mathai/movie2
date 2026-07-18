<?php session_start();

// Check if the user is logged in
if (!isset($_SESSION['user_id'])) {
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
    <title>Movie Details</title>
    <link rel="stylesheet" href="css/dashboard.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.3/css/all.min.css">
</head>
<body>
    <div class="movie-details-container">
        <!-- Video Player -->
        <div class="movie-player">
            <iframe id="movieVideo" width="100%" height="315" 
                src="" 
                frameborder="0" 
                allow="accelerometer; autoplay; clipboard-write; encrypted-media; gyroscope; picture-in-picture" 
                allowfullscreen>
            </iframe>
        </div>

        <!-- Movie Details -->
        <div class="movie-info-container">
            <div class="movie-info">
                <h1 id="movieTitle"></h1>
                <p id="movieSynopsis"></p>
                <p><strong>Release Date:</strong> <span id="movieReleaseDate"></span></p>
                <p><strong>Genre:</strong> <span id="movieGenre"></span></p>
            </div>
        </div>

        <!-- Rating Section -->
        <div class="rating">
            <h3>Rate this Movie:</h3>
            <div class="stars">
                <!-- Add star icons here -->
                <i class="fas fa-star" data-rating="1"></i>
                <i class="fas fa-star" data-rating="2"></i>
                <i class="fas fa-star" data-rating="3"></i>
                <i class="fas fa-star" data-rating="4"></i>
                <i class="fas fa-star" data-rating="5"></i>
            </div>
        </div>

        <!-- Like/Dislike Section -->
        <div class="like-dislike">
            <button id="likeBtn"><i class="fas fa-thumbs-up"></i> Like <span id="likeCount">0</span></button>
            <button id="dislikeBtn"><i class="fas fa-thumbs-down"></i> Dislike <span id="dislikeCount">0</span></button>
        </div>

        <!-- Comment Section -->
        <div class="comment-section">
            <h3>Comments:</h3>
            <div id="comments"></div>
            <textarea id="commentInput" placeholder="Add a comment..."></textarea>
            <button id="submitComment">Submit</button>
        </div>
    
    </div>

  <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
<script src="js/movie_detail.js"></script>
</body>
</html>
