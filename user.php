<?php
include 'config.php';
require_once __DIR__ . '/logger.php';

class User {
    private $con;

    public function __construct() {
        $database = new Database();
        $this->con = $database->getConnection();
    }

    public function addUser($name, $hashed_password, $email, $phone, $gender, $dob, $age, $photo_path, $password) {
        try {
            $sql = "insert into users(username,password,email,phone,dob,age,gender,og_pass,image_url)
                    values(:name,:pass,:email,:phone,:dob,:age,:gender,:og_pass,:image)";
            $stmt = $this->con->prepare($sql);
            $stmt->bindParam(':name', $name);
            $stmt->bindParam(':pass', $hashed_password);
            $stmt->bindParam(':email', $email);
            $stmt->bindParam(':phone', $phone);
            $stmt->bindParam(':gender', $gender);
            $stmt->bindParam(':dob', $dob);
            $stmt->bindParam(':age', $age);
            $stmt->bindParam(':og_pass', $password);
            $stmt->bindParam(':image', $photo_path);

            return $stmt->execute();
        } catch (Exception $e) {
            echo "Register Error: " . $e->getMessage();
            return false;
        }
    }

    // Caller must already have session_start() called before this runs.
    public function login($email, $password) {
        try {
            $sql = "SELECT * FROM users WHERE email = :email LIMIT 1";
            $stmt = $this->con->prepare($sql);
            $stmt->bindParam(':email', $email);
            $stmt->execute();

            $user = $stmt->fetch(PDO::FETCH_ASSOC);

            if (!$user || !password_verify($password, $user['password'])) {
                echo "<script>
                        alert('Invalid credentials, please try again.');
                        window.location.href = 'login.php';
                    </script>";
                exit();
            }

            // Regenerate session ID to prevent session fixation attacks
            session_regenerate_id(true);

            $_SESSION['user_id']  = $user['id'];
            $_SESSION['username'] = $user['username'];
            $_SESSION['role']     = $user['role'];

            $logger = new Logger();
            $logger->logUserAction($user['id'], 'login', 'User logged in');

            if ($user['role'] == 'Admin') {
                header("Location: admin_dashboard.php");
            } else {
                header("Location: dashboard.php");
            }
            exit();

        } catch (PDOException $e) {
            error_log("Login error: " . $e->getMessage());
            echo "<script>
                    alert('Something went wrong. Please try again.');
                    window.location.href = 'login.php';
                </script>";
            exit();
        }
    }

    public function update_user($userId, $username, $email, $phone, $dob) {
        try {
            $sql = "UPDATE users SET username = :username, email = :email, phone = :phone, dob = :dob WHERE id = :id";
            $stmt = $this->con->prepare($sql);
            $stmt->bindParam(':username', $username);
            $stmt->bindParam(':email', $email);
            $stmt->bindParam(':phone', $phone);
            $stmt->bindParam(':dob', $dob);
            $stmt->bindParam(':id', $userId, PDO::PARAM_INT);
            return $stmt->execute();
        } catch (PDOException $e) {
            error_log("update_user error: " . $e->getMessage());
            return false;
        }
    }
}

// ---- Dispatcher (this was completely missing) ----
if (isset($_GET['action']) && $_GET['action'] == 'updateprofile') {
    session_start();
    if (!isset($_SESSION['user_id'])) {
        echo json_encode(['error' => 'Not logged in']);
        exit();
    }

    $user = new User();
    $ok = $user->update_user(
        $_SESSION['user_id'],
        $_POST['username'],
        $_POST['email'],
        $_POST['phone'],
        $_POST['dob']
    );

    if ($ok) {
        // Keep session username in sync with what was just saved
        $_SESSION['username'] = $_POST['username'];
        echo json_encode(['success' => true]);
    } else {
        echo json_encode(['success' => false, 'error' => 'Update failed']);
    }
}
?>
