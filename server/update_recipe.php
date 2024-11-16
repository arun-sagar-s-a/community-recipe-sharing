<?php
include 'database_connect.php';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $recipe_id = intval($_POST['recipe_id']);
    $title = $_POST['title'];
    $instructions = $_POST['instructions'];
    $image_url = $_POST['image_url'];

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
