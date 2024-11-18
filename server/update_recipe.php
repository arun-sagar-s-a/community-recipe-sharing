<?php
include 'database_connect.php';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    // Get form data
    $recipe_id = intval($_POST['recipe_id']);
    $title = $_POST['title'];
    $instructions = $_POST['instructions'];
    
    // Get current image_url from the database
    $sql = "SELECT image_url FROM Recipes WHERE recipe_id = ?";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param('i', $recipe_id);
    $stmt->execute();
    $result = $stmt->get_result();
    $recipe = $result->fetch_assoc();
    $current_image_url = $recipe['image_url'];  // Current image URL
    
    // Check if a new image is uploaded
    if (isset($_FILES['image']) && $_FILES['image']['error'] == UPLOAD_ERR_OK) {
        // Process the image upload
        $image_name = basename($_FILES['image']['name']);
        $image_path = '../images/' . $image_name;
        
        if (move_uploaded_file($_FILES['image']['tmp_name'], $image_path)) {
            // If new image uploaded, update image_url
            $image_url ='../images/'. $image_name;
        } else {
            echo "Error uploading the image.";
            exit();
        }
    } else {
        // If no new image, keep the current image URL
        $image_url = $current_image_url;
    }

    // Update recipe in the database
    $sql = "UPDATE Recipes SET title = ?, instructions = ?, image_url = ? WHERE recipe_id = ?";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param('sssi', $title, $instructions, $image_url, $recipe_id);

    if ($stmt->execute()) {
        header('Location: ../pages/display_recipes.php'); // Redirect to recipe list
        exit;
    } else {
        echo "Failed to update recipe!";
    }
}
?>
