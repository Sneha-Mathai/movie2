<?php
include 'movies.php';

if($_SERVER['REQUEST_METHOD'] == 'POST'){
    try {
        $title = $_POST['title'];
        $genre = $_POST['genre'];
        $release_date = $_POST['release_date'];
        $synopsis = $_POST['synopsis'];
        $video_url = $_POST['video_url'];

        // Check if the poster file was uploaded
        if (isset($_FILES['poster'])) {
            if ($_FILES['poster']['error'] == 0) {
                $poster_name = $_FILES['poster']['name'];
                $poster_tmp_name = $_FILES['poster']['tmp_name'];
                $dir = 'uploads/';
                $poster_path = $dir . basename($poster_name);

                // Move the uploaded file to the uploads directory
                if (!move_uploaded_file($poster_tmp_name, $poster_path)) {
                    throw new Exception("Error uploading poster.");
                }
            } else {
                throw new Exception("Upload Error: " . $_FILES['poster']['error']);
            }
        } else {
            throw new Exception("Poster upload failed.");
        }

        // Instantiate the Movie class and add the movie
        $movie = new Movie();
        if($movie->addMovie($title, $genre, $release_date, $synopsis, $poster_path,$video_url)) {
            
                    header("Location:admin_dashboard.php");
                    
        } else {
            echo "Failed to add movie.";
        }
    } catch(Exception $e) {
        echo "Error: " . $e->getMessage();
    }
}
?>
