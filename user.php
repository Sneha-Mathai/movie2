<?php
include 'config.php';

class User {
    private $con;

    public function __construct() {
        $database = new Database();
        $this->con = $database->getConnection();
    }
    
    public function addUser($name,$hashed_password,$email,$phone,$gender,$dob,$age,$photo_path,$password)
    {
        try{
            $sql="insert into users(username,password,email,phone,dob,age,gender,og_pass,image_url) 
values(:name,:pass,:email,:phone,:dob,:age,:gender,:og_pass,:image)";
$stmt=$this->con->prepare($sql);
$stmt->bindParam(':name',$name);
$stmt->bindParam(':pass',$hashed_password);
$stmt->bindParam(':email',$email);
$stmt->bindParam(':phone',$phone);
$stmt->bindParam(':gender',$gender);
$stmt->bindParam(':dob',$dob);
$stmt->bindParam(':age',$age);
$stmt->bindParam(':og_pass',$password);
$stmt->bindParam(':image',$photo_path);

return $stmt->execute();
} catch (Exception $e) {
    echo "Register Error: " . $e->getMessage();
            return false;
        }
    }



// Check if user exists and verify password
public function login($email, $password) {
    try {
        // Prepare SQL query to select user by email
        $sql = "SELECT * FROM users WHERE email = :email LIMIT 1";
        $stmt = $this->con->prepare($sql);
        $stmt->bindParam(':email', $email);
        $stmt->execute();

        // Fetch user details
        $user = $stmt->fetch(PDO::FETCH_ASSOC);

        if ($user) {
            // Debugging: Check if user was found and password hash
            echo "User found: " . print_r($user, true);

            // Verify password
            if (password_verify($password, $user['password'])) {
                // Start a session and store user information
                session_start();
                $_SESSION['user_id'] = $user['id'];
                $_SESSION['username'] = $user['username'];
                $_SESSION['role'] = $user['role'];
                //setcookie('user_session', session_id(), time() + (86400 * 30), "/", "", true, true);

                

                // Redirect based on user role
                if ($user['role'] == 'Admin') {
                    header("Location: admin_dashboard.php");
                } else {
                    header("Location: user_dashboard.php");
                }
                exit();
            } else {
                echo "<script>
                        alert('Invalid credentials, please try again.');
                        window.location.href = 'login.php';
                    </script>";
                exit();
            }
        } else {
            echo "<script>
                    alert('User not found.');
                    window.location.href = 'login.php';
                </script>";
            exit();
        }
    } catch (PDOException $e) {
        return "Error: " . $e->getMessage();
    }
}
/*public function getImageUrl($userId) {
    try {
        $sql = "SELECT image_url FROM users WHERE id = :id";
        $stmt = $this->con->prepare($sql);
        $stmt->bindParam(':id', $userId);
        $stmt->execute();

        $user = $stmt->fetch(PDO::FETCH_ASSOC);
        return $user['image_url'] ?? 'default-profile-pic.jpg'; // Fallback to a default image if no URL is set
    } catch (PDOException $e) {
        // Handle error, e.g., log it or return a default value
        error_log($e->getMessage());
        return 'default-profile-pic.jpg'; // Fallback in case of an error
    }
}
*/
public function update_user(){
    $sql="update users set username=.username,email=:email,";
    $stmt=$this->con->prepare($sql);
$stmt->bindParam(':username',$username);
$stmt->execute();
}



}


?>
