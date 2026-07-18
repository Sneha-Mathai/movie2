<?php
include 'config.php';

class Ratings {
    private $con;
    private $userId;

    public function __construct() {
        // Start the session to access session variables
        if (session_status() === PHP_SESSION_NONE) {
            session_start();
        }

        // Initialize database connection
        $database = new Database();
        $this->con = $database->getConnection();

        // Check if user_id is available in session
        if (isset($_SESSION['user_id'])) {
            $this->userId = $_SESSION['user_id'];
        } else {
            // Handle the case where user_id is not available
            throw new Exception("User is not logged in.");
        }
    }

    public function addRating() {
        echo ('here2');
        if ($_SERVER['REQUEST_METHOD'] == 'POST') {
            // Validate and sanitize input
            $movieId = $_POST['movie_id'];
            $rating = isset($_POST['rating']) ? $_POST['rating'] : null;
            $likeDislike = isset($_POST['like_dislike']) ? $_POST['like_dislike'] : null;
            $comment = isset($_POST['comment']) ? $_POST['comment'] : null;
            //$existingRating = $checkStmt->fetch(PDO::FETCH_ASSOC);

            if ($rating !== null) {
                // Handle rating
                $this->handleRating($movieId, $rating);
            } elseif ($likeDislike !== null) {
                // Handle like/dislike
                $this->handleLikeDislike($movieId, $likeDislike);
            } elseif ($comment !== null) {
                // Handle comment
                $this->handleComment($movieId, $comment);
            } else {
                echo 'Invalid input';
            }
        }
    }

    private function handleRating($movieId, $rating) {
        echo('here3');

        $query = "SELECT * FROM ratings WHERE movie_id = :movie_id AND user_id = :user_id";
    $stmt = $this->con->prepare($query);
    $stmt->bindParam(':movie_id', $movieId, PDO::PARAM_INT);
    $stmt->bindParam(':user_id', $this->userId, PDO::PARAM_INT);
    $stmt->execute();
    echo($movieId);

    if ($stmt->rowCount() > 0) {
        print('if');
        // If a rating exists, update it
        $updateQuery = "UPDATE ratings SET rating = :rating WHERE movie_id = :movie_id AND user_id = :user_id";
        $updateStmt = $this->con->prepare($updateQuery);
        $updateStmt->bindParam(':movie_id', $movieId, PDO::PARAM_INT);
        $updateStmt->bindParam(':user_id', $this->userId, PDO::PARAM_INT);
        $updateStmt->bindParam(':rating', $rating, PDO::PARAM_INT);
        $updateStmt->execute();
        echo($rating);
        echo('sneha');
        
    } else {
        print('else');

       
        // Prepare and execute SQL query for rating
        $query = "INSERT INTO ratings (movie_id, user_id, rating) VALUES (:movie_id, :user_id, :rating)";
        $stmt = $this->con->prepare($query);
        $stmt->bindParam(':movie_id', $movieId, PDO::PARAM_INT);
        $stmt->bindParam(':user_id', $this->userId, PDO::PARAM_INT);
        $stmt->bindParam(':rating', $rating, PDO::PARAM_INT);
        
        $stmt->execute() ;
        echo($rating);
        file_put_contents('ratings.txt',print_r($stmt,true).PHP_EOL,FILE_APPEND);
    }
}

    private function handleLikeDislike($movieId, $likeDislike) {
        // Prepare and execute SQL query for like/dislike
       // $query = "INSERT INTO ratings (movie_id, user_id, like_dislike) VALUES (:movie_id, :user_id, :like_dislike)";
       $query = "SELECT like_dislike FROM ratings WHERE movie_id = :movie_id AND user_id = :user_id";
        $stmt = $this->con->prepare($query);
        $stmt->bindParam(':movie_id', $movieId, PDO::PARAM_INT);
        $stmt->bindParam(':user_id', $this->userId, PDO::PARAM_INT);
       // $stmt->bindParam(':like_dislike', $likeDislike, PDO::PARAM_INT); // assuming like_dislike is an integer
       $stmt->execute();
       $existingRating = $stmt->fetch(PDO::FETCH_ASSOC);
       if ($existingRating) {
        // If the existing like_dislike is different from the new one, update it
        if ($existingRating['like_dislike'] != $likeDislike) {
            $query = "UPDATE ratings SET like_dislike = :like_dislike WHERE movie_id = :movie_id AND user_id = :user_id";
            $stmt = $this->con->prepare($query);
            $stmt->bindParam(':like_dislike', $likeDislike, PDO::PARAM_INT);
        } else {
            // If the user tries to set the same like/dislike, return an error or do nothing
            echo json_encode(['error' => 'Already ' . ($likeDislike == 1 ? 'liked' : 'disliked')]);
            return;
        }
    } else {
        // Insert a new like/dislike
        $query = "INSERT INTO ratings (movie_id, user_id, like_dislike) VALUES (:movie_id, :user_id, :like_dislike)";
        $stmt = $this->con->prepare($query);
        $stmt->bindParam(':like_dislike', $likeDislike, PDO::PARAM_INT);
    }
    $stmt->bindParam(':movie_id', $movieId, PDO::PARAM_INT);
    $stmt->bindParam(':user_id', $this->userId, PDO::PARAM_INT);

    
   

        if ($stmt->execute()) {
           $counts = $this->getMovieLikesDislikes($movieId);
        echo json_encode($counts); // Return updated counts in JSON format
    } else {
        echo json_encode(['error' => 'Error adding like/dislike.']);
    }
    }

    private function handleComment($movieId, $comment) {
        // Prepare and execute SQL query for comment
        echo "hello";
        echo($movieId);
        echo($comment);
        $query = "INSERT INTO ratings (movie_id, user_id, comment) VALUES (:movie_id, :user_id, :comment)";
        $stmt = $this->con->prepare($query);
        $stmt->bindParam(':movie_id', $movieId, PDO::PARAM_INT);
        $stmt->bindParam(':user_id', $this->userId, PDO::PARAM_INT);
        $stmt->bindParam(':comment', $comment, PDO::PARAM_STR);
        if ($stmt->execute()) {
            echo 'Comment added successfully!';
        } else {
            echo 'Error adding comment.';
        }
    }
    public function getComments($movieId) {
        try{
        $query = "SELECT u.username as user_name, r.comment 
                    FROM ratings r
                    JOIN users u ON r.user_id = u.id
                    WHERE r.movie_id = :movie_id AND r.comment IS NOT NULL AND r.comment != ''
                    ";
        $stmt = $this->con->prepare($query);
        $stmt->bindParam(':movie_id', $movieId, PDO::PARAM_INT);
        $stmt->execute();
        $comments= $stmt->fetchAll(PDO::FETCH_ASSOC);
        echo json_encode($comments); 
    }catch (PDOException $e) {
         // Log the error message
         error_log("Error in getComments: " . $e->getMessage());
         echo json_encode([]); // Return an empty array on errors
    }
}

public function getMovieLikesDislikes($movieId) {
    try {
        $sql = "SELECT 
                    SUM(CASE WHEN like_dislike = 1 THEN 1 ELSE 0 END) AS likes,
                    SUM(CASE WHEN like_dislike = 0 THEN 1 ELSE 0 END) AS dislikes
                FROM ratings 
                WHERE movie_id = :movie_id";
        $stmt = $this->con->prepare($sql);
        $stmt->bindParam(':movie_id', $movieId, PDO::PARAM_INT);
        $stmt->execute();
        
        $result = $stmt->fetch(PDO::FETCH_ASSOC);
        echo json_encode($result);
    } catch (PDOException $e) {
        error_log("Error in getMovieLikesDislikes: " . $e->getMessage());
        echo json_encode(['likes' => 0, 'dislikes' => 0]); // Return default values on error
    }
}

public function getUserRating($movieId) {
    echo('here4');
    $query = "SELECT rating FROM ratings WHERE movie_id = :movie_id AND user_id = :user_id";
    $stmt = $this->con->prepare($query);
    $stmt->bindParam(':movie_id', $movieId, PDO::PARAM_INT);
    $stmt->bindParam(':user_id', $this->userId, PDO::PARAM_INT);
    $stmt->execute();
    echo ($movieId);
    return $stmt->fetch(PDO::FETCH_ASSOC);
}

/*
    public function getAverageRating($movieId) {
        try {
            $sql = "SELECT AVG(rating) as average_rating FROM ratings WHERE movie_id = :movie_id";
            $stmt = $this->con->prepare($sql);
            $stmt->bindParam(':movie_id', $movieId, PDO::PARAM_INT);
            $stmt->execute();
            return $stmt->fetch(PDO::FETCH_ASSOC)['average_rating'];
        } catch (PDOException $e) {
            // Log the error message
            error_log("Error in getAverageRating: " . $e->getMessage());
            return false;
        }
    }*/
    
}

// Ensure to include this only once at the start of the file where needed
if (isset($_GET['action'])) {
    if ($_GET['action'] == 'addRating') {
        $rating = new Ratings();
        $rating->addRating();
    }elseif($_GET['action'] == 'getComments') {
        $movie_id = $_GET['movie_id'];
        $rating = new Ratings();
        $rating->getComments($movie_id);
        //echo json_encode($comments); 
    }elseif($_GET['action']=='getMovieLikesDislikes'){
        $movie_id = $_GET['movie_id'];
        $rating = new Ratings();
        $rating->getMovieLikesDislikes($movie_id);
    }
    elseif($_GET['action']=='getUserRating'){
        $movie_id = $_GET['movie_id'];
        $rating = new Ratings();
        
        $result =$rating->getUserRating($movie_id);
        echo json_encode($result);

    }


}

?>
