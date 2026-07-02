<?php

include 'config.php';

class Report {
    private $con;

    public function __construct() {
        // Initialize database connection
        
        $database = new Database();
        $this->con = $database->getConnection();

        
    }

    public function generateOverallStatisticsReport($timestamp) {
        try {
            header('Content-Type: text/csv');
            header('Content-Disposition: attachment;filename=overall_statistics_report.csv');

            $output = fopen('php://output', 'w');

            // Add CSV column headers
            fputcsv($output, ['movie_id', 'movie_title', 'total_likes', 'total_dislikes', 'total_comments','average_rating']);

            // Query to get overall statistics
            $sql = "SELECT 
    movies.id AS movie_id,
    movies.title AS movie_title,
    COUNT(CASE WHEN ratings.like_dislike = 1 THEN 1 ELSE NULL END) AS total_likes,
    COUNT(CASE WHEN ratings.like_dislike = 0 THEN 1 ELSE NULL END) AS total_dislikes,
    COUNT(CASE WHEN ratings.comment IS NOT NULL AND ratings.comment != '' THEN 1 ELSE NULL END) AS total_comments,
    AVG(ratings.rating) AS average_rating
FROM 
    movies
LEFT JOIN 
    ratings ON movies.id = ratings.movie_id
WHERE 
    ratings.created_at <= :timestamp
GROUP BY 
    movies.id
ORDER BY 
    movies.title
";
        

            $stmt = $this->con->prepare($sql);
            $stmt->bindParam(':timestamp', $timestamp);
            $stmt->execute();
            error_log("Attempting to execute SQL: " . $sql);


            // Fetch and write data to CSV
            while($row = $stmt->fetch(PDO::FETCH_ASSOC)) 
            {
                fputcsv($output, $row);
            }
            

            fclose($output);
        } catch (PDOException $e) {
            // Log the error message
            error_log("Error in generateOverallStatisticsReport: " . $e->getMessage());
            echo "Error generating report.";
        }
    }
}

// Example usage
if ($_SERVER['REQUEST_METHOD'] === 'GET') {
    $timestamp = $_GET['timestamp']; // Get timestamp from form input
    $report = new Report();
    $report->generateOverallStatisticsReport($timestamp);
}
?>
