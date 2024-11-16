<?php
include '../server/database_connect.php'; // Fetch recipe details from the database.
$recipe_id = intval($_GET['id']);
$sql = "SELECT title, instructions, image_url FROM Recipes WHERE recipe_id = ?";
$stmt = $conn->prepare($sql);
$stmt->bind_param('i', $recipe_id);
$stmt->execute();
$recipe = $stmt->get_result()->fetch_assoc();
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Edit Recipe</title>
</head>
<body>
    <h1>Edit Recipe</h1>
    <form action="../server/update_recipe.php" method="POST">
        <input type="hidden" name="recipe_id" value="<?php echo $recipe_id; ?>">
        <label for="title">Recipe Title:</label>
        <input type="text" id="title" name="title" value="<?php echo htmlspecialchars($recipe['title']); ?>" required><br>
        <label for="instructions">Instructions:</label>
        <textarea id="instructions" name="instructions" rows="5" required><?php echo htmlspecialchars($recipe['instructions']); ?></textarea><br>
        <label for="image_url">Image URL:</label>
        <input type="text" id="image_url" name="image_url" placeholder="<?php echo htmlspecialchars($recipe['image_url']); ?>" required><br>
        <button type="submit">Update Recipe</button>
    </form>
</body>
</html>
