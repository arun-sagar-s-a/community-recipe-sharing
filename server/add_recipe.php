<?php
include 'database_connect.php';

// Enable error reporting for debugging
error_reporting(E_ALL);
ini_set('display_errors', 1);

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    // Retrieve form data
    $title = mysqli_real_escape_string($conn, $_POST['title']);
    $instructions = mysqli_real_escape_string($conn, $_POST['instructions']);

    // Handle file upload
    $targetDir = "../images/";
    $fileName = basename($_FILES["image"]["name"]);
    $targetFilePath = $targetDir . $fileName;
    $imageUrl = "images/" . $fileName;

    if (move_uploaded_file($_FILES["image"]["tmp_name"], $targetFilePath)) {
        // Insert recipe into the database
        $sql = "INSERT INTO Recipes (user_id, title, instructions, image_url)
                VALUES (1, '$title', '$instructions', '$imageUrl')"; // Assuming user_id=1 for now

        if ($conn->query($sql) === TRUE) {
            echo "Recipe added successfully!";
        } else {
            echo "Error: " . $conn->error;
        }
    } else {
        echo "Failed to upload image.";
    }
}
$conn->close();
?>
