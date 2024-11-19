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

// Fetch categories for the dropdown menu
$category_sql = "SELECT category_id, name FROM Categories";
$category_result = $conn->query($category_sql);
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Add Recipe - Community Recipe Sharing</title>
    <link rel="stylesheet" href="../styles/add_recipe.css">
</head>

<body>
    <header>
        <h1>Community Recipe Sharing</h1>
        <nav>
            <a href="./index.php">Home</a>
            <a href="./display_recipes.php">All Recipes</a>
            <a href="../server/logout.php">Logout</a>
        </nav>
    </header>

    <main>


        <section class="form-container">
            <div class="heading">
                <h2>Add a New Recipe</h2>
                <p>Welcome, <?php echo htmlspecialchars($username); ?>!</p>
            </div>
            <form id="add-recipe-form" action="../server/add_recipe.php" method="POST" enctype="multipart/form-data">
                <div class="form-group">
                    <label for="title">Recipe Title:</label>
                    <input type="text" id="title" name="title" required>
                </div>

                <div class="form-group">
                    <label for="instructions">Instructions:</label>
                    <textarea id="instructions" name="instructions" rows="5" required></textarea>
                </div>

                <div class="form-group">
                    <label for="image">Recipe Image:</label>
                    <input type="file" id="image" name="image" accept="image/*" required>
                </div>

                <div class="form-group">
                    <label for="category">Category:</label>
                    <select id="category" name="category" required>
                        <option value="">Select a category</option>
                        <?php while ($category = $category_result->fetch_assoc()): ?>
                            <option value="<?php echo $category['category_id']; ?>"><?php echo htmlspecialchars($category['name']); ?></option>
                        <?php endwhile; ?>
                    </select>
                </div>

                <div class="button-container">
                    <button type="submit">Add Recipe</button>
                    <button type="reset">Reset</button>
                </div>
            </form>
        </section>
    </main>

    <footer>
      <p>© Community Recipe Sharing. By Arun, with <span>♡</span></p>
     
    </footer>
</body>

</html>
<?php $conn->close(); ?>