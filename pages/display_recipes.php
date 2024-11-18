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

// Get the search term from the query string (URL)
$search = isset($_GET['search']) ? $_GET['search'] : '';

// Only search if the search term is not empty
if (!empty($search)) {
    // Prepare the SQL query to search recipes by title (with LIKE for partial matches)
    $sql = "SELECT recipe_id, user_id, title, image_url FROM Recipes WHERE title LIKE ? ORDER BY creation_date DESC";
    $stmt = $conn->prepare($sql);
    $search_term = "%" . $search . "%"; // Adding the "%" for partial matches
    $stmt->bind_param("s", $search_term);
    $stmt->execute();
    $result = $stmt->get_result();
} else {
    // If no search term, display all recipes
    $sql = "SELECT recipe_id, user_id, title, image_url FROM Recipes ORDER BY creation_date DESC";
    $result = $conn->query($sql);
}

// Get categories for the filter dropdown
$category_sql = "SELECT category_id, name FROM Categories";
$category_result = $conn->query($category_sql);

// Check if there's a selected category in the URL
$category_filter = isset($_GET['category']) ? $_GET['category'] : '';

// Prepare the SQL query to filter recipes by category if a category is selected
if ($category_filter) {
    $sql = "
        SELECT r.recipe_id, r.user_id, r.title, r.image_url
        FROM Recipes r
        INNER JOIN Recipe_Categories rc ON r.recipe_id = rc.recipe_id
        INNER JOIN Categories c ON rc.category_id = c.category_id
        WHERE c.category_id = ? 
        ORDER BY r.creation_date DESC
    ";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("i", $category_filter);
    $stmt->execute();
    $result = $stmt->get_result();
} else {
    // If no category is selected, show all recipes
    $sql = "SELECT recipe_id, user_id, title, image_url FROM Recipes ORDER BY creation_date DESC";
    $result = $conn->query($sql);
}

?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Recipes</title>
    <link rel="stylesheet" href="../styles/view_recipe.css">
</head>

<body>
    <header>
        <h1>Community Recipe Sharing</h1>
        <nav>
            <a href="./dashboard.php">Home</a>
            <a href="./add_recipe.php">Add Recipe</a>
            <a href="../server/logout.php">Logout</a>
        </nav>
    </header>

    <main class="container">
        <h2>All Recipes</h2>
        <p>Welcome back, <?php echo htmlspecialchars($username); ?>!</p>

        <section class="search-filter">
            <form id="searchForm" method="GET" action="display_recipes.php">
                <input type="text" id="search" name="search" placeholder="Search recipes..." value="<?php echo isset($_GET['search']) ? htmlspecialchars($_GET['search']) : ''; ?>" />
                <button type="submit">Search</button>
                <button type="button" id="reset">Reset</button>
            </form>
            <form id="filterForm" method="GET" action="display_recipes.php">
                <select id="category" name="category">
                    <option value="">Select Category</option>
                    <?php while ($category = $category_result->fetch_assoc()): ?>
                        <option value="<?php echo $category['category_id']; ?>"
                            <?php echo ($category['category_id'] == $category_filter) ? 'selected' : ''; ?>>
                            <?php echo htmlspecialchars($category['name']); ?>
                        </option>
                    <?php endwhile; ?>
                </select>
                <button type="submit">Filter</button>
            </form>
        </section>

        <section id="recipes-container">
            <?php if ($result->num_rows > 0): ?>
                <?php while ($recipe = $result->fetch_assoc()): ?>
                    <a href="single_recipe.php?id=<?php echo $recipe['recipe_id']; ?>" class="recipe-card">
                        <div class="recipe">
                            <img src="<?php echo htmlspecialchars($recipe['image_url']); ?>" alt="<?php echo htmlspecialchars($recipe['title']); ?>">
                            <h3><?php echo htmlspecialchars($recipe['title']); ?></h3>

                            <div class="actions">
                                <?php if ($recipe['user_id'] == $user_id): ?>
                                    <a href="edit_recipe.php?id=<?php echo $recipe['recipe_id']; ?>">Edit</a>
                                    <a href="../server/delete_recipe.php?id=<?php echo $recipe['recipe_id']; ?>">Delete</a>
                                <?php endif; ?>
                            </div>
                        </div>
                    </a>
                <?php endwhile; ?>
            <?php else: ?>
                <p>No recipes found!</p>
            <?php endif; ?>
        </section>
    </main>

    <footer>
        <p>© Community Recipe Sharing</p>
    </footer>

    <script>
        document.getElementById("reset").addEventListener("click", function() {
            document.getElementById("search").value = '';
            document.getElementById("searchForm").submit(); // Submit to reset the search
        });
    </script>
</body>

</html>
<?php $conn->close(); ?>