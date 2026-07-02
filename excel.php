<?php
// Include the movies.php file to use its functions and database connection
include 'movies.php';
?>

<html>
    <form action="" method="post" enctype="multipart/form-data">
        Upload Excel: <input type="file" name="excel">
        <input type="submit" name="submit">
    </form>
</html>
<?php
if (isset($_FILES['excel']['name']) && $_FILES['excel']['name'] != '') {
    // Remove the inner 'if' condition as it's redundant
    $file_path = strtolower(basename($_FILES['excel']['name'])); // Use 'basename' to get the filename
    if (move_uploaded_file($_FILES['excel']['tmp_name'], 'uploads/' . $file_path)) {
        $message = 'Congratulations! Your file was accepted.';
        echo $message;
        insertDataFromExcel($upload_dir . $file_path, $conn);
    } else {
        echo 'Sorry, there was an error uploading your file.';
    }
}
?>
