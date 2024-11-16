<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Add Recipe</title>
    <link rel="stylesheet" href="../styles/main.css">
</head>
<body>
    <h1>Add a New Recipe</h1>
    <form id="add-recipe-form" action="../server/add_recipe.php" method="POST" enctype="multipart/form-data">
        <label for="title">Recipe Title:</label>
        <input type="text" id="title" name="title" required>

        <label for="instructions">Instructions:</label>
        <textarea id="instructions" name="instructions" rows="5" required></textarea>

        <label for="image">Recipe Image:</label>
        <input type="file" id="image" name="image" accept="image/*" required>

        <button type="submit">Add Recipe</button>
    </form>
</body>
</html>
