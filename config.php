<?php
class Database {
    private $host = 'localhost';
    private $db_name = 'project';
    private $username = 'root';
    private $password = '';
    public $con;

    public function getConnection() {
        $this->con = null;

        try {
            $this->con = new PDO("mysql:host=" . $this->host . ";dbname=" . $this->db_name, $this->username, $this->password);
            $this->con->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
        } catch(PDOException $exception) {
            echo "Connection error: " . $exception->getMessage();
        }

        return $this->con;
    }
}
?>
