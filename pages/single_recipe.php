<?php
// Start the session
session_start();
include '../server/database_connect.php';

// Check if the user is logged in
if (!isset($_SESSION['user_id'])) {
    // Redirect to login page if not logged in
    header('Location: login.php');
    exit();
}

// Get user details
$user_id = $_SESSION['user_id'];
$username = $_SESSION['username'];

// Check if a recipe ID is provided
if (!isset($_GET['id'])) {
    header('Location: display_recipes.php');
    exit();
}

$recipe_id = $_GET['id'];

// Fetch recipe details
$sql = "SELECT r.*, u.username as owner_username 
        FROM Recipes r 
        JOIN Users u ON r.user_id = u.user_id 
        WHERE r.recipe_id = ?";
$stmt = $conn->prepare($sql);
$stmt->bind_param("i", $recipe_id);
$stmt->execute();
$result = $stmt->get_result();

if ($result->num_rows == 0) {
    // Recipe not found
    header('Location: display_recipes.php');
    exit();
}

$recipe = $result->fetch_assoc();

// Fetch ingredients
$sql = "SELECT i.name, ri.quantity 
        FROM Recipe_Ingredients ri 
        JOIN Ingredients i ON ri.ingredient_id = i.ingredient_id 
        WHERE ri.recipe_id = ?";
$stmt = $conn->prepare($sql);
$stmt->bind_param("i", $recipe_id);
$stmt->execute();
$ingredients_result = $stmt->get_result();

// Fetch categories
$sql = "SELECT c.name 
        FROM Recipe_Categories rc 
        JOIN Categories c ON rc.category_id = c.category_id 
        WHERE rc.recipe_id = ?";
$stmt = $conn->prepare($sql);
$stmt->bind_param("i", $recipe_id);
$stmt->execute();
$categories_result = $stmt->get_result();
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?php echo htmlspecialchars($recipe['title']); ?> - Community Recipe Sharing</title>
    <link rel="stylesheet" href="../styles/single_recipe.css">
</head>

<body>
    <header>
        <h1>Community Recipe Sharing</h1>
        <nav>
            <a href="./index.php">Home</a>
            <a href="./display_recipes.php">All Recipes</a>
            <a href="./add_recipe.php">Add Recipe</a>
            <a href="../server/logout.php">Logout</a>
        </nav>
    </header>

    <main class="container">
        <div class="recipe-details">
            <h2><?php echo htmlspecialchars($recipe['title']); ?></h2>
            <p>By: <?php echo htmlspecialchars($recipe['owner_username']); ?></p>

            <img src="<?php echo htmlspecialchars($recipe['image_url']); ?>" alt="<?php echo htmlspecialchars($recipe['title']); ?>" class="recipe-image">

            <h3>Instructions:</h3>
            <p><?php echo nl2br(htmlspecialchars($recipe['instructions'])); ?></p>

            <h3>Categories:</h3>
            <ul>
                <?php while ($category = $categories_result->fetch_assoc()): ?>
                    <li><?php echo htmlspecialchars($category['name']); ?></li>
                <?php endwhile; ?>
            </ul>

            <p>Last updated: <?php echo date('F j, Y', strtotime($recipe['last_updated_date'])); ?></p>

            <?php if ($recipe['user_id'] == $user_id): ?>
                <div class="actions">
                    <a href="edit_recipe.php?id=<?php echo $recipe['recipe_id']; ?>" class="button">Edit Recipe</a>
                    <a href="../server/delete_recipe.php?id=<?php echo $recipe['recipe_id']; ?>" class="button delete">Delete Recipe</a>
                </div>
            <?php endif; ?>
        </div>
    </main>

    <footer>
        <p>© Community Recipe Sharing</p>
    </footer>
</body>

</html>
<?php $conn->close(); ?>