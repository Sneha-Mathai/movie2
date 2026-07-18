  
   
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
               var rating = $(this).data('rating');
              // echo (rating);
               currentRating = rating; // Update the current rating
                highlightStars(rating); 
                //var rating = $(this).data('rating');
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
                // ('here');
                $.ajax({
                    url: 'ratings.php?action=addRating',
                    method: 'POST',
                    data: { movie_id: movieId, rating: rating },
                    success: function() {
                        alert('Thank you for rating!');
                        highlightStars(rating)


                    }
                });
            }

        function loadExistingRating(movieId) {
            console.log('loadexistingrating');
        // Make an AJAX call to fetch the user's existing rating for the movie
        $.ajax({
            url: 'ratings.php?action=getUserRating',
            method: 'GET',
            data: { movie_id: movieId },
            success: function(response) {
                if (response.rating) {
                    currentRating = response.rating;
                    console.log('all goood');
                    highlightStars(currentRating);
                }
            },
            dataType: 'json'
        });
    }
    function highlightStars(rating) {
          console.log("highlightStars called");
    console.log("Rating received:", rating);
                $('.stars i').each(function() {
                       console.log(
            "Star rating:",)
            $(this).data('rating')
                    if ($(this).data('rating') <= rating) {

            console.log("Adding active");

                        $(this).addClass('active');
                    } else {
                         console.log("Removing active");
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
   