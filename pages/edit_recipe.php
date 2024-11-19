<?php
session_start();  // Start the session to track logged-in user
include '../server/database_connect.php'; // Fetch recipe details from the database

// Check if the user is logged in
if (!isset($_SESSION['user_id'])) {
    // Redirect to login page if not logged in
    header('Location: login.php');
    exit();
}

// Get user details
$user_id = $_SESSION['user_id'];
$username = $_SESSION['username'];

// Fetch the recipe to be edited
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
    <link rel="stylesheet" href="../styles/common.css"> <!-- Ensure styles are consistent -->
</head>
<body>
    <!-- Header with consistent navigation -->
    <header>
        <h1>Community Recipe Sharing</h1>
        <nav>
            <a href="./index.php">Home</a>
            <a href="./display_recipes.php">All Recipes</a>
            <a href="../server/logout.php">Logout</a>
        </nav>
    </header>

    <main>
        <!-- Welcome message with username -->
        <div class="page-header">
            <h2>Edit Recipe</h2>
            <p>Welcome, <?php echo htmlspecialchars($username); ?>!</p>
        </div>

        <!-- Edit Recipe Form -->
        <section class="form-container">
            <form action="../server/update_recipe.php" method="POST" enctype="multipart/form-data">
                <input type="hidden" name="recipe_id" value="<?php echo $recipe_id; ?>">
                
                <div class="form-group">
                    <label for="title">Recipe Title:</label>
                    <input type="text" id="title" name="title" value="<?php echo htmlspecialchars($recipe['title']); ?>" required>
                </div>

                <div class="form-group">
                    <label for="instructions">Instructions:</label>
                    <textarea id="instructions" name="instructions" rows="5" required><?php echo htmlspecialchars($recipe['instructions']); ?></textarea>
                </div>

                <!-- Display current image -->
                <div class="form-group">
                    <label>Current Image:</label><br>
                    <?php if ($recipe['image_url']) : ?>
                        <img src="../images/<?php echo htmlspecialchars($recipe['image_url']); ?>" alt="Recipe Image" width="150"><br>
                    <?php else : ?>
                        <p>No image available</p>
                    <?php endif; ?>
                </div>

                <!-- Optional image upload -->
                <div class="form-group">
                    <label for="image">Update Recipe Image (Optional):</label>
                    <input type="file" id="image" name="image" accept="image/*">
                </div>

                <div class="form-group">
                    <button type="submit">Update Recipe</button>
                </div>
            </form>
        </section>
    </main>

    <!-- Footer with consistent footer across pages -->
    <footer>
        <p>© Community Recipe Sharing</p>
    </footer>
</body>
</html>
<?php $conn->close(); ?>
