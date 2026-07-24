<?php
require_once 'config.php';

class Logger {
    private $con;

    public function __construct() {
        $database = new Database();
        $this->con = $database->getConnection();
    }

    /**
     * Log a user action into the user_logs table.
     *
     * @param int    $userId  The ID of the user performing the action
     * @param string $action  The action type (e.g., 'login', 'logout', 'register', 'change_password')
     * @param string $details Optional details about the action
     * @return bool True on success, false on failure
     */
    public function logUserAction($userId, $action, $details = '') {
        try {
            $sql = "INSERT INTO user_logs (user_id, action, details, log_date) 
                    VALUES (:user_id, :action, :details, NOW())";
            $stmt = $this->con->prepare($sql);
            $stmt->bindParam(':user_id', $userId, PDO::PARAM_INT);
            $stmt->bindParam(':action', $action, PDO::PARAM_STR);
            $stmt->bindParam(':details', $details, PDO::PARAM_STR);
            return $stmt->execute();
        } catch (PDOException $e) {
            error_log("Logger error: " . $e->getMessage());
            return false;
        }
    }
}
?>

