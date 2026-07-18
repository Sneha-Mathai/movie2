<?php
include 'config.php';
//require 'phpSpreadsheet/vendor/autoload.php'; // Path to PhpSpreadsheet library

use PhpOffice\PhpSpreadsheet\IOFactory;
use PhpOffice\PhpSpreadsheet\Spreadsheet;

class Movie {
    private $con;

    public function __construct() {
        $database = new Database();
        $this->con = $database->getConnection();

    }
    public function searchMovies() {
    try {
        $q = isset($_GET['q']) ? trim($_GET['q']) : '';
        $genre = isset($_GET['genre']) ? trim($_GET['genre']) : '';

        $sql = "SELECT m.id, m.title, m.genre, m.release_date, m.poster_url, m.vdo_link,
                       AVG(CASE WHEN r.rating > 0 THEN r.rating END) AS average_rating
                FROM movies m
                LEFT JOIN ratings r ON m.id = r.movie_id
                WHERE 1=1";

        $params = [];

        if (!empty($q)) {
            $sql .= " AND m.title LIKE :q";
            $params[':q'] = "%$q%";
        }

        if (!empty($genre)) {
            $sql .= " AND m.genre = :genre";
            $params[':genre'] = $genre;
        }

        $sql .= " GROUP BY m.id";

        $stmt = $this->con->prepare($sql);
        $stmt->execute($params);
        $movies = $stmt->fetchAll(PDO::FETCH_ASSOC);

        echo json_encode($movies);
    } catch (PDOException $e) {
        echo json_encode(["error" => $e->getMessage()]);
    }
}
//     public function searchMovies() {
//     try {
//         $q = isset($_GET['q']) ? trim($_GET['q']) : '';
//         $genre = isset($_GET['genre']) ? trim($_GET['genre']) : '';

//         $sql = "SELECT id, title, genre, release_date, poster_url, vdo_link
//                 FROM movies
//                 WHERE 1=1";

//         $params = [];

//         // Search by title
//         if (!empty($q)) {
//             $sql .= " AND title LIKE :q";
//             $params[':q'] = "%$q%";
//         }

//         // Filter by genre
//         if (!empty($genre)) {
//             $sql .= " AND genre = :genre";
//             $params[':genre'] = $genre;
//         }

//         $stmt = $this->con->prepare($sql);
//        $stmt->execute($params);
//        $movies = $stmt->fetchAll(PDO::FETCH_ASSOC);
//        // Return ONLY the movie array
//         echo json_encode($movies);


// //

// // echo json_encode([
// //     "sql" => $sql,
// //     "params" => $params,
// //     "movies" => $movies
// // ]);

//     } catch (PDOException $e) {
//         echo json_encode(["error" => $e->getMessage()]);
//     }
// }

public function fetchMovies() {
    try {
        $sql = "SELECT m.id, m.title, m.genre, m.release_date, m.poster_url, m.vdo_link,
                       AVG(CASE WHEN r.rating > 0 THEN r.rating END) AS average_rating
                FROM movies m
                LEFT JOIN ratings r ON m.id = r.movie_id
                GROUP BY m.id";
        $stmt = $this->con->prepare($sql);
        $stmt->execute();
        $movies = $stmt->fetchAll(PDO::FETCH_ASSOC);

        echo json_encode($movies);
    } catch (PDOException $e) {
        echo json_encode(["error" => $e->getMessage()]);
    }
}
public function addmovie($title,$genre,$release_date,$synopsis,$poster,$video_url){
    try{
        $sql="insert into movies(title,genre,release_date,synopsis,poster_url,vdo_link)values(:title,:genre,:date,:synopsis,:poster,:video_url)";
        $stmt=$this->con->prepare($sql);
        $stmt->bindParam(':title',$title);
        $stmt->bindParam(':genre',$genre);
        $stmt->bindParam('date',$release_date);
        $stmt->bindParam('synopsis',$synopsis);
        $stmt->bindParam('poster',$poster);
        $stmt->bindParam('video_url',$video_url);

       return $stmt->execute();


    }
    catch(Exception $e){
        echo"error".$e->getMessage();
    }
}
//     public function fetchMovies() {
//         try {
//             $sql = "SELECT id,title,genre,release_date, poster_url,vdo_link FROM movies";
//             $stmt = $this->con->prepare($sql);
//             $stmt->execute();
//             $movies = $stmt->fetchAll(PDO::FETCH_ASSOC);
            
//             echo json_encode($movies); // Return the JSON-encoded data
//         } catch (PDOException $e) {
//             echo json_encode(["error" => $e->getMessage()]);
//         }

// }
public function fetchTrendingMovies() {
    $query = "SELECT movies.id, movies.title, movies.poster_url, 
       AVG(ratings.rating) as average_rating, 
       COUNT(ratings.rating) as total_ratings
FROM movies
JOIN ratings ON movies.id = ratings.movie_id
GROUP BY movies.id
ORDER BY total_ratings DESC, average_rating DESC
LIMIT 5";
// Adjust the limit as needed
    $stmt = $this->con->prepare($query);
    $stmt->execute();
    return $stmt->fetchAll(PDO::FETCH_ASSOC);
}


    public function getMovie($id){
        try{
            $sql="SELECT id, title, genre, release_date, synopsis, poster_url,vdo_link FROM movies WHERE id = :id";
            $stmt=$this->con->prepare($sql);
            $stmt->bindParam(':id', $id, PDO::PARAM_INT);
            $stmt->execute();
            $movie=$stmt->fetchAll(PDO::FETCH_ASSOC);
            echo json_encode($movie);


        }catch (PDOException $e) {
            echo json_encode(["error" => $e->getMessage()]);
        }
    }
    public function updateMovie($id, $title, $genre, $release_date, $synopsis, $poster = null,$video_url) {
        try {
            $sql = "UPDATE movies SET title = :title, genre = :genre, release_date = :release_date, synopsis = :synopsis, vdo_link=:video_url";

            if ($poster) {
                $sql .= ", poster_url = :poster";
            }

            $sql .= " WHERE id = :id";
            
            $stmt = $this->con->prepare($sql);
            $stmt->bindParam(':title', $title);
            $stmt->bindParam(':genre', $genre);
            $stmt->bindParam(':release_date', $release_date);
            $stmt->bindParam(':synopsis', $synopsis);
            $stmt->bindParam('video_url',$video_url);
            if ($poster) {
                $stmt->bindParam(':poster', $poster);
            }
            $stmt->bindParam(':id', $id, PDO::PARAM_INT);
            
            return $stmt->execute();
        } catch (PDOException $e) {
            echo json_encode(["error" => $e->getMessage()]);
        }
    }
    public function watchMovies() {
        try {
            $sql = "SELECT  title, poster_url FROM movies";
            $stmt = $this->con->prepare($sql);
            $stmt->execute();
            $movies = $stmt->fetchAll(PDO::FETCH_ASSOC);
            
            echo json_encode($movies); // Return the JSON-encoded data
        } catch (PDOException $e) {
            echo json_encode(["error" => $e->getMessage()]);
        }
    }
    public function usergetMovie($id){
        try {
            
            $sql="SELECT id, title, genre, release_date, synopsis, vdo_link FROM movies WHERE id = :id";
            $stmt=$this->con->prepare($sql);
            $stmt->bindParam(':id', $id, PDO::PARAM_INT);
            $stmt->execute();
            $movie=$stmt->fetch(PDO::FETCH_ASSOC);
            echo json_encode($movie);
        } catch (PDOException $e) {
            echo json_encode(["error" => $e->getMessage()]);
        }
    }
    public function incrementViewCount($movieId) {
        // Prepare the SQL query to increment view count
        $query = "UPDATE movies SET view_count = view_count + 1 WHERE id = :id";
        $stmt = $this->con->prepare($query);
        $stmt->bindParam(':id', $movieId, PDO::PARAM_INT);

        // Execute the query
        if ($stmt->execute()) {
            return true;
        } else {
            return false;
        }
    }
    public function deleteMovie($id) {
        // Connect to database
        // Assume $pdo is your PDO connection
        $query="DELETE FROM movies WHERE id = ?";
        $stmt = $this->con->prepare($query);
        return $stmt->execute([$id]);
    }
    /*function insertDataFromExcel($filePath, $conn) {
        // Load the Excel file using PhpSpreadsheet
        $spreadsheet = IOFactory::load($filePath);
        $sheetData = $spreadsheet->getActiveSheet()->toArray();
    
        // Skip the header row and insert data into the database
        for ($i = 1; $i < count($sheetData); $i++) {
            $id = $sheetData[$i][0];
            $title = $sheetData[$i][1];
            $genre = $sheetData[$i][2];
            $release_date = $sheetData[$i][3];
            $synopsis = $sheetData[$i][4];
            //$poster_url = $sheetData[$i][5];
           // $view_count = $sheetData[$i][6];
            $vdo_link = $sheetData[$i][7];
    
            $sql = "INSERT INTO movies (id, title, genre, release_date, synopsis, poster_url, view_count, vdo_link)
                    VALUES ('$id', '$title', '$genre', '$release_date', '$synopsis', '$poster_url', '$view_count', '$vdo_link')";
    
            if ($conn->query($sql) === TRUE) {
                echo "New record created successfully<br>";
            } else {
                echo "Error: " . $sql . "<br>" . $conn->error;
            }
        }
    }*/
    

    
}

$movie = new Movie();


if (isset($_GET['action'])) {
    if ($_GET['action'] == 'fetchMovies') {
        $movie->fetchMovies();
    } elseif ($_GET['action'] == 'getMovie' && isset($_GET['id'])) {
        $movie->getMovie($_GET['id']);
        
    } elseif ($_GET['action'] == 'updateMovie') {
        $movieId = $_POST['id'];
        $title = $_POST['title'];
        $genre = $_POST['genre'];
        $release_date = $_POST['release_date'];
        $synopsis = $_POST['synopsis'];
        $video_url = isset($_POST['video_url']) ? $_POST['video_url'] : null;
        if (!empty($_FILES['poster']['name'])) {
            $uploadDir = 'uploads/'; // Directory where the file will be saved
            $uploadFile = $uploadDir . basename($_FILES['poster']['name']);
        
            // Move the uploaded file to the specified directory
            if (move_uploaded_file($_FILES['poster']['tmp_name'], $uploadFile)) {
                $poster = $uploadFile;
            } else {
                $poster = null; // Handle upload error
            }
        } else {
            $poster = null;
        }
          // Handle the file upload here
        
        $movie->updateMovie($movieId, $title, $genre, $release_date, $synopsis, $poster,$video_url);
    }elseif ($_GET['action'] == 'watchMovies') {
        $movie->watchMovies();
    }elseif ($_GET['action'] == 'usergetMovie') {
        $id = $_GET['id'];
        $movie->usergetMovie($id);
    }elseif ($_GET['action'] == 'incrementViewCount') {
        $movieId = $_POST['movie_id'];
        $movie->incrementViewCount($movieId);
    }elseif($_GET['action'] == 'fetchTrendingMovies') {
        $trendingMovies = $movie->fetchTrendingMovies();
        echo json_encode($trendingMovies);
    }elseif ($_GET['action'] == 'searchMovies') {

    $movie->searchMovies();

}elseif ($_GET['action'] == 'deleteMovie') {
        $movieId = $_POST['id'];
    
        // Assuming you have a Movie class with a deleteMovie method
        //$movie = new Movie();
        if ($movie->deleteMovie($movieId)) {
           // echo 'Movie deleted successfully';
        } else {
            echo 'Failed to delete movie';
        }
    }
    
    
}
