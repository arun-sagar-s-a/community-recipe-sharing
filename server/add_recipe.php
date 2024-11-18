<?php
session_start();
include 'database_connect.php';

// Check if the user is logged in
if (!isset($_SESSION['user_id'])) {
    // Redirect to login page if not logged in
    header('Location: ../login.php');
    exit();
}

$user_id = $_SESSION['user_id'];

// Check if form is submitted
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $title = $_POST['title'];
    $instructions = $_POST['instructions'];
    $category_id = $_POST['category'];

    // Handle file upload
    $image = $_FILES['image']['name'];
    $image_tmp = $_FILES['image']['tmp_name'];
    $image_path = "../images/" . basename($image);
    
    if (move_uploaded_file($image_tmp, $image_path)) {
        // Insert the new recipe into the Recipes table
        $sql = "INSERT INTO Recipes (user_id, title, instructions, image_url) VALUES (?, ?, ?, ?)";
        $stmt = $conn->prepare($sql);
        $stmt->bind_param("ssss", $user_id, $title, $instructions, $image_path);
        if ($stmt->execute()) {
            // Get the inserted recipe ID
            $recipe_id = $stmt->insert_id;

            // Insert the category-tagging relationship into Recipe_Categories
            $category_sql = "INSERT INTO Recipe_Categories (recipe_id, category_id) VALUES (?, ?)";
            $category_stmt = $conn->prepare($category_sql);
            $category_stmt->bind_param("ii", $recipe_id, $category_id);
            $category_stmt->execute();

            // Redirect to the display recipes page
            header('Location: ../pages/display_recipes.php');
        } else {
            echo "Error adding recipe.";
        }
    } else {
        echo "Failed to upload image.";
    }
}
?>
