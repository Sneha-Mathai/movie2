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
       $photo_path = 'uploads/default-avatar.png'; // set BEFORE the check, not after

if (isset($_FILES['photo']) && $_FILES['photo']['error'] == 0) {
    $photo_name = $_FILES['photo']['name'];
    $photo_tmp_name = $_FILES['photo']['tmp_name'];
    $photo_dir = 'uploads/';
    $photo_path = $photo_dir . basename($photo_name);

    if (!move_uploaded_file($photo_tmp_name, $photo_path)) {
        throw new Exception("Error uploading photo.");
    }
}
        $user = new User();
        if ($user->addUser($name,$hashed_password,$email, $phone, $gender, $dob, $age, $photo_path,$password)) {
            // Log the registration
            $logger = new Logger();
            $logger->logUserAction(0, 'register', 'New user registered: ' . $email);

            // Clear any existing session so the new user starts fresh
            if (session_status() === PHP_SESSION_ACTIVE) {
                $_SESSION = [];
                session_unset();
                session_destroy();
            }
            // Remove the session cookie as well
            if (ini_get('session.use_cookies')) {
                $params = session_get_cookie_params();
                setcookie(session_name(), '', time() - 42000,
                    $params['path'], $params['domain'],
                    $params['secure'], $params['httponly']
                );
            }

            header('Location: login.php'); // Redirect to login page on successful registration
        } else {
            echo "Registration failed.";
        }
    } catch (Exception $e) {
        echo "Error: " . $e->getMessage();
    }
        
    }

?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>User Registration</title>
    <link rel="stylesheet" href="register.css"> <!-- Link to your CSS file -->
</head>
<body>
    <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.5.1/jquery.min.js"></script>
    
    <form method="POST" action="register.php" enctype="multipart/form-data">
        <label for="username">Username:</label>
        <input type="text" id="username" name="username" required><br><br>

        <label for="password">Password:</label>
        <input type="password" id="password" name="password" required><br><br>

        <label for="confirm_password">Confirm Password:</label>
        <input type="password" id="confirm_password" name="confirm_password" required><br><br>

        <label for="email">Email:</label>
        <input type="email" id="email" name="email" required><br><br>
        
        <label for="phone">Phone:</label>
        <input type="text" id="phone" name="phone" required><br><br>

        <label for="dob">Date of Birth:</label>
        <input type="date" id="dob" name="dob" required><br><br>

        <label for="gender">Gender:</label>
        <select id="gender" name="gender" required>
            <option value="male">Male</option>
            <option value="female">Female</option>
            <option value="other">Other</option>
        </select><br><br>

        <label for="age">Age:</label>
        <input type="text" id="age" name="age" required><br><br>


        <label for="photo">Profile Photo:</label>
        <input type="file" id="photo" name="photo" accept="image/*"><br><br>

        
        <label for="captcha">Enter the code: 1234</label>
        <input type="text" id="captcha" name="captcha" required><br><br>

        <button type="submit">Register</button>
    </form>
    

    <script>
    $(document).ready(function() {
        $('form').on('submit', function(event) {
            let isValid = true;
    
            // Check if all required fields are filled
            $('input[required]').each(function() {
                if ($(this).val().trim() === '') {
                    alert($(this).prev('label').text() + ' is required.');
                    isValid = false;
                    return false; // Break loop
                }
            });
    
            // Check if email is valid
            const emailPattern = /^[a-zA-Z0-9._-]+@[a-zA-Z0-9.-]+\.[a-zA-Z]{2,6}$/;
            if (!emailPattern.test($('#email').val())) {
                alert('Please enter a valid email address.');
                isValid = false;
            }
    
            // Check if phone is valid (simple example)
            const phonePattern = /^[0-9]{10}$/; // Assuming 10-digit phone numbers
            if (!phonePattern.test($('#phone').val())) {
                alert('Please enter a valid phone number (10 digits).');
                isValid = false;
            }
    
            // Check if passwords match
            if ($('#password').val() !== $('#confirm_password').val()) {
                alert('Passwords do not match.');
                isValid = false;
            }
    
            // Check if age is at least 18
            const dob = new Date($('#dob').val());
            const today = new Date();
            const age = today.getFullYear() - dob.getFullYear();
            if (age < 18) {
                alert('You must be at least 18 years old to register.');
                isValid = false;
            }
    
            // Check if CAPTCHA is correct
            if ($('#captcha').val() !== '1234') {
                alert('Incorrect CAPTCHA.');
                isValid = false;
            }
    
            // If form is not valid, prevent submission
            if (!isValid) {
                event.preventDefault();
            }
        });
    });
    </script>
    
</body>
</html>