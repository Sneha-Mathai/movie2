<?php
session_start();
include 'config.php';

class Watchlist {
    private $con;
    private $userId;

    public function __construct() {
        $database = new Database();
        $this->con = $database->getConnection();

        if (!isset($_SESSION['user_id'])) {
            echo json_encode(['error' => 'Not logged in']);
            exit();
        }
        $this->userId = $_SESSION['user_id'];
    }

    public function add($movieId) {
        try {
            $sql = "INSERT IGNORE INTO watchlist (user_id, movie_id) VALUES (:user_id, :movie_id)";
            $stmt = $this->con->prepare($sql);
            $stmt->bindParam(':user_id', $this->userId, PDO::PARAM_INT);
            $stmt->bindParam(':movie_id', $movieId, PDO::PARAM_INT);
            $stmt->execute();
            echo json_encode(['success' => true, 'action' => 'added']);
        } catch (PDOException $e) {
            error_log("Watchlist add error: " . $e->getMessage());
            echo json_encode(['success' => false, 'error' => $e->getMessage()]);
        }
    }

    public function remove($movieId) {
        try {
            $sql = "DELETE FROM watchlist WHERE user_id = :user_id AND movie_id = :movie_id";
            $stmt = $this->con->prepare($sql);
            $stmt->bindParam(':user_id', $this->userId, PDO::PARAM_INT);
            $stmt->bindParam(':movie_id', $movieId, PDO::PARAM_INT);
            $stmt->execute();
            echo json_encode(['success' => true, 'action' => 'removed']);
        } catch (PDOException $e) {
            error_log("Watchlist remove error: " . $e->getMessage());
            echo json_encode(['success' => false, 'error' => $e->getMessage()]);
        }
    }

    public function list() {
        try {
            $sql = "SELECT m.id, m.title, m.genre, m.release_date, m.poster_url, m.vdo_link,
                           AVG(CASE WHEN r.rating > 0 THEN r.rating END) AS average_rating
                    FROM watchlist w
                    JOIN movies m ON w.movie_id = m.id
                    LEFT JOIN ratings r ON m.id = r.movie_id
                    WHERE w.user_id = :user_id
                    GROUP BY m.id
                    ORDER BY w.added_at DESC";
            $stmt = $this->con->prepare($sql);
            $stmt->bindParam(':user_id', $this->userId, PDO::PARAM_INT);
            $stmt->execute();
            echo json_encode($stmt->fetchAll(PDO::FETCH_ASSOC));
        } catch (PDOException $e) {
            error_log("Watchlist list error: " . $e->getMessage());
            echo json_encode(['error' => $e->getMessage()]);
        }
    }

    public function ids() {
        try {
            $sql = "SELECT movie_id FROM watchlist WHERE user_id = :user_id";
            $stmt = $this->con->prepare($sql);
            $stmt->bindParam(':user_id', $this->userId, PDO::PARAM_INT);
            $stmt->execute();
            echo json_encode($stmt->fetchAll(PDO::FETCH_COLUMN));
        } catch (PDOException $e) {
            error_log("Watchlist ids error: " . $e->getMessage());
            echo json_encode([]);
        }
    }
}

$watchlist = new Watchlist();

if (isset($_GET['action'])) {
    if ($_GET['action'] == 'add') {
        $movieId = $_POST['movie_id'];
        $watchlist->add($movieId);
    } elseif ($_GET['action'] == 'remove') {
        $movieId = $_POST['movie_id'];
        $watchlist->remove($movieId);
    } elseif ($_GET['action'] == 'list') {
        $watchlist->list();
    } elseif ($_GET['action'] == 'ids') {
        $watchlist->ids();
    }
}
