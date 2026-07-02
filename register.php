
<?php
include 'user.php';

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    try {
$name=$_POST['username'];
$email=$_POST['email'];
$password=$_POST['password'];
$confirm_password=$_POST['confirm_password'];
$phone=$_POST['phone'];
$gender=$_POST['gender'];
$dob=$_POST['dob'];
$age=$_POST['age'];
//$photo=$_POST['photo'];
$captcha = $_POST['captcha'];

        // Validate CAPTCHA
        if ($captcha !== '1234') {
            throw new Exception("Invalid CAPTCHA code.");
        }

        // Validate password and confirm password match
        if ($password !== $confirm_password) {
            throw new Exception("Passwords do not match.");
        }

        // Hash the password
        $hashed_password = password_hash($password, PASSWORD_DEFAULT);
        $photo_path = null;

       if (isset($_FILES['photo'])) {
            if ($_FILES['photo']['error'] == 0) {
                $photo_name = $_FILES['photo']['name'];
                $photo_tmp_name = $_FILES['photo']['tmp_name'];
                $photo_dir = 'uploads/';
                $photo_path = $photo_dir . basename($photo_name);
                
                if (!move_uploaded_file($photo_tmp_name, $photo_path)) 
                 
                    throw new Exception("Error uploading photo.");
                
            } else {
                echo "Upload Error: " . $_FILES['photo']['error'];
            }
        } else {
            throw new Exception("Photo upload failed.");
        }
        $user = new User();
        if ($user->addUser($name,$hashed_password,$email, $phone, $gender, $dob, $age, $photo_path,$password)) {
            header('Location: login.html'); // Redirect to login page on successful registration
        } else {
            echo "Registration failed.";
        }
    } catch (Exception $e) {
        echo "Error: " . $e->getMessage();
    }
        
    }

?>