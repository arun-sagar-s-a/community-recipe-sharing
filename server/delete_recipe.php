<?php
include 'database_connect.php';

if (isset($_GET['id']) && is_numeric($_GET['id'])) {
    $recipe_id = $_GET['id'];

    // Prepare DELETE query
    $sql = "DELETE FROM Recipes WHERE recipe_id = ?";

    if ($stmt = $conn->prepare($sql)) {
        // Bind parameter
        $stmt->bind_param('i', $recipe_id);

        // Execute statement
        if ($stmt->execute()) {
            // Redirect to the recipes list after successful deletion
            header('Location: ../pages/display_recipes.php');
            exit();
        } else {
            echo "Error: Could not delete the recipe.";
        }

        // Close statement
        $stmt->close();
    } else {
        echo "Error: Could not prepare the DELETE query.";
    }
} else {
    echo "Invalid recipe ID.";
}

$conn->close();
?>
