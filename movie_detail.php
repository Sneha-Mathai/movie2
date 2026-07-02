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
    <link rel="stylesheet" href="dashboard1.css">
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
    <script>
        $(document).ready(function() {
            var movieId = new URLSearchParams(window.location.search).get('id');
            //var currentRating = 0;

            // Fetch movie details
            $.ajax({
                url: 'movies.php?action=usergetMovie',
                method: 'GET',
                data: { id: movieId },
                dataType: 'json',
                success: function(movie) {
                    $('#movieTitle').text(movie.title);
                    $('#movieSynopsis').text(movie.synopsis);
                    $('#movieReleaseDate').text(movie.release_date);
                    $('#movieGenre').text(movie.genre);
                    if (movie.vdo_link) {
                        let embedUrl = movie.vdo_link.replace("watch?v=", "embed/");
                        $('#movieVideo').attr('src', embedUrl);
                    }
                    $('#likeCount').text(movie.likes);
                    $('#dislikeCount').text(movie.dislikes);

                    fetchMovieComments(movieId);
                    fetchLikeDislikeCounts(movieId);
                    loadExistingRating(movieId);
                    
                }
            });
        
            // Increment view count when the video starts playing
       /* $('#movieVideo').on('play', function() {
        incrementViewCount(movieId);
        });

        function incrementViewCount(movieId) {
        $.ajax({
            url: 'movies.php?action=incrementViewCount',
            method: 'POST',
            data: { movie_id: movieId },
            success: function(response) {
                console.log("View count incremented.");
            },
            error: function() {
                console.error("Error incrementing view count.");
            }
        });
    }*/

            // Handle star rating
            $('.stars i').click(function() {
               currentRating = rating; // Update the current rating
                highlightStars(rating); 
                var rating = $(this).data('rating');
                submitRating(movieId, rating);
            });

            // Handle like/dislike
            $('#likeBtn').click(function() {
                submitLikeDislike(movieId, 1); // 1 represents a 'Like'
            });
            $('#dislikeBtn').click(function() {
                submitLikeDislike(movieId, 0); // 0 represents a dislike
            });

            // Handle comment submission
            $('#submitComment').click(function() {
                var comment = $('#commentInput').val();
                if (comment.trim()) {
                    submitComment(movieId, comment);
                } else {
                    alert('Comment cannot be empty.');
                }
            });

            
            

            function submitRating(movieId, rating) {
                $.ajax({
                    url: 'ratings.php?action=addRating',
                    method: 'POST',
                    data: { movie_id: movieId, rating: rating },
                    success: function() {
                        //alert('Thank you for rating!');
                        highlightStars(rating) 

                    }
                });
            }

        function loadExistingRating(movieId) {
        // Make an AJAX call to fetch the user's existing rating for the movie
        $.ajax({
            url: 'ratings.php?action=getUserRating',
            method: 'GET',
            data: { movie_id: movieId },
            success: function(response) {
                if (response.rating) {
                    currentRating = response.rating;
                    highlightStars(currentRating);
                }
            },
            dataType: 'json'
        });
    }
    function highlightStars(rating) {
                $('.stars i').each(function() {
                    if ($(this).data('rating') <= rating) {
                        $(this).addClass('active');
                    } else {
                        $(this).removeClass('active');
                    }
                });
            }



            function submitLikeDislike(movieId, likeDislike) {
                $.ajax({
                    url: 'ratings.php?action=addRating',
                    method: 'POST',
                    data: { movie_id: movieId, like_dislike: likeDislike },
                    success: function() {
                        fetchLikeDislikeCounts(movieId);
                    }
                });
            }

            function submitComment(movieId, comment) {
                $.ajax({
                    url: 'ratings.php?action=addRating',
                    method: 'POST',
                    data: { movie_id: movieId, comment: comment },
                    success: function() {
                        //$('#comments').append('<p><strong>You:</strong> ' + comment + '</p>');
                        $('#commentInput').val('');
                        fetchMovieComments(movieId);
                    }
                });
            }
        function fetchMovieComments(movieId) {
            $.ajax({
                url: 'ratings.php?action=getComments',
                method: 'GET',
                data: { movie_id: movieId },
                dataType: 'json',
                success: function(comments) {
                    console.log(comments); // Debugging: Check if comments are being fetc
                    var commentsContainer = $('#comments');
                    commentsContainer.empty(); // Clear existing comments

                    $.each(comments, function(index, comment) {
                        var commentItem = $('<div class="comment-item">').text(comment.user_name + ': ' + comment.comment);
                        commentsContainer.append(commentItem);
                    });
                }
        });
    }
    function fetchLikeDislikeCounts(movieId) {
        $.ajax({
            url: 'ratings.php?action=getMovieLikesDislikes',
            method: 'GET',
            data: { movie_id: movieId },
            dataType: 'json',
            success: function(response) {
                if (response) {
                    $('#likeCount').text(response.likes);
                    $('#dislikeCount').text(response.dislikes);
                } else {
                    console.log('Error fetching like/dislike counts.');
                }
            },
            error: function() {
                console.log('AJAX error.');
            }
        });
    }

    // Fetch counts on page load
    //fetchLikeDislikeCounts();
        });
    </script>
</body>
</html>
