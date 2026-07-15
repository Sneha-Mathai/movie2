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
            //fputcsv($output, ['movie_id', 'movie_title', 'total_likes', 'total_dislikes', 'total_comments','average_rating']);
            fputcsv($output, ['movie_id', 'movie_title', 'total_views', 'total_ratings', 'average_rating', 'total_likes', 'total_dislikes', 'like_ratio_pct', 'total_comments']);

            // Query to get overall statistics
            $sql = "SELECT 
            movies.id AS movie_id,
            movies.title AS movie_title,
            movies.view_count AS total_views,
            COUNT(ratings.rating) AS total_ratings,
            ROUND(AVG(ratings.rating), 2) AS average_rating,
            SUM(CASE WHEN ratings.like_dislike = 1 THEN 1 ELSE 0 END) AS total_likes,
            SUM(CASE WHEN ratings.like_dislike = 0 THEN 1 ELSE 0 END) AS total_dislikes,
            ROUND(
                SUM(CASE WHEN ratings.like_dislike = 1 THEN 1 ELSE 0 END) /
                NULLIF(SUM(CASE WHEN ratings.like_dislike IN (0,1) THEN 1 ELSE 0 END), 0) * 100, 1
            ) AS like_ratio_pct,
            COUNT(CASE WHEN ratings.comment IS NOT NULL AND ratings.comment != '' THEN 1 END) AS total_comments
        FROM movies
        LEFT JOIN ratings ON movies.id = ratings.movie_id
        WHERE ratings.created_at <= :timestamp OR ratings.created_at IS NULL
        GROUP BY movies.id
        ORDER BY average_rating DESC";
        

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
/*if ($_SERVER['REQUEST_METHOD'] === 'GET') {
    $timestamp = $_GET['timestamp']; // Get timestamp from form input
    $report = new Report();
    $report->generateOverallStatisticsReport($timestamp);
}*/

// Example usage
/*if ($_SERVER['REQUEST_METHOD'] === 'GET') {
    $timestamp = $_GET['report_date']; // Get timestamp from form input
    $report = new Report();
    $report->generateOverallStatisticsReport($timestamp);
}*/


if ($_SERVER['REQUEST_METHOD'] === 'GET' && isset($_GET['report_date'])) {
    $reportDate = $_GET['report_date'];
    $timestamp = $reportDate . ' 23:59:59'; // include the whole selected day
    $reportType = isset($_GET['report_type']) ? $_GET['report_type'] : 'movies';

    $report = new Report();

    if ($reportType === 'users') {
        $report->generateUserActivityReport($timestamp);
    } else {
        $report->generateOverallStatisticsReport($timestamp);
    }
}
?>
