<?php
session_start();
require 'config.php'; // Include your database connection

// Check if user is logged in
if (!isset($_SESSION['user_id'])) {
    header("Location: login.php");
    exit();
}

// Fetch user data from the database
$userId = $_SESSION['user_id'];

$database = new Database();
$con = $database->getConnection();

$stmt = $con->prepare("SELECT username, email, phone, dob FROM users WHERE id = ?");
$stmt->execute([$userId]);
$user = $stmt->fetch(PDO::FETCH_ASSOC);

if (!$user) {
    echo "User not found.";
    exit();
}
?>

<html>
<head>
    <title>Edit Profile</title>
    <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.5.1/jquery.min.js"></script>
</head>
<body>
    <h2>Edit Profile</h2>
    <form action="" method="post" enctype="multipart/form-data">
        <label for="username">Username:</label>
        <input type="text" id="username" name="username" value="<?php echo htmlspecialchars($user['username']); ?>" required><br>

        <label for="email">Email:</label>
        <input type="email" id="email" name="email" value="<?php echo htmlspecialchars($user['email']); ?>" required><br>

        <label for="phone">Phone:</label>
        <input type="text" id="phone" name="phone" value="<?php echo htmlspecialchars($user['phone']); ?>"><br>

        <label for="dob">Date of Birth:</label>
        <input type="date" id="dob" name="dob" value="<?php echo htmlspecialchars($user['dob']); ?>"><br>

        <!-- If using a photo upload feature -->
        <label for="photo">Profile Photo:</label>
        <input type="file" id="photo" name="photo"><br>

        <input type="submit" value="Update Profile">
    </form>
    <script>
        $(document).ready(function(){
            $document.on('submit',funtion(e){
                e.preventDefault();
                data=$(this).serialize();

                $.ajax(
                    {
                        
                        url:"user.php?action=updateprofile",
                        method:'post',
                        data:data,
                        success:function(response){
                            alert(profile edit success);

                        },
                        error:funtion(){
                            alert(failed);
                        }


                    }
                )


            })
        })        </script>
</body>
</html>