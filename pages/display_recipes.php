<?php
include '../server/database_connect.php';

// Check if there's a search term in the URL
$search = isset($_GET['search']) ? $_GET['search'] : '';

// Prepare the SQL query to search recipes by title
$sql = "SELECT recipe_id, title, image_url FROM Recipes WHERE title LIKE ?";
$stmt = $conn->prepare($sql);
$search_term = "%" . $search . "%";
$stmt->bind_param("s", $search_term);
$stmt->execute();
$result = $stmt->get_result();

// Get categories for the filter dropdown
$category_sql = "SELECT category_id, name FROM Categories";
$category_result = $conn->query($category_sql);

// Check if there's a selected category in the URL
$category_filter = isset($_GET['category']) ? $_GET['category'] : '';

// Prepare the SQL query to filter recipes by category
if ($category_filter) {
    // Join Recipes, Recipe_Categories, and Categories tables to filter by selected category
    $sql = "
        SELECT r.recipe_id, r.title, r.image_url
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
    $sql = "SELECT recipe_id, title, image_url FROM Recipes ORDER BY creation_date DESC";
    $result = $conn->query($sql);
}

?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Recipes</title>
    <style>
        .recipe {
            border: 1px solid #ddd;
            margin: 10px;
            padding: 10px;
            width: 300px;
            display: inline-block;
            vertical-align: top;
        }
        .recipe img {
            width: 100%;
            height: auto;
        }
        .actions {
            margin-top: 10px;
        }
    </style>
</head>
<body>
    <h1>All Recipes</h1>

    <!-- Search Form -->
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

    <div id="recipes-container">
        <?php if ($result->num_rows > 0): ?>
            <?php while ($recipe = $result->fetch_assoc()): ?>
                <div class="recipe">
                    <img src="../<?php echo htmlspecialchars($recipe['image_url']); ?>" alt="<?php echo htmlspecialchars($recipe['title']); ?>">
                    <h3><?php echo htmlspecialchars($recipe['title']); ?></h3>
                    <div class="actions">
                        <a href="edit_recipe.php?id=<?php echo $recipe['recipe_id']; ?>">Edit</a>
                        <a href="../server/delete_recipe.php?id=<?php echo $recipe['recipe_id']; ?>">Delete</a>
                    </div>
                </div>
            <?php endwhile; ?>
        <?php else: ?>
            <p>No recipes found!</p>
        <?php endif; ?>
    </div>

    <!-- JavaScript for Reset Button -->
    <script>
        // Reset button click event
        document.getElementById("reset").addEventListener("click", function() {
            document.getElementById("search").value = '';  // Clear search input
            document.getElementById("searchForm").submit();  // Submit the form to reset the search
        });
    </script>
</body>
</html>

<?php $conn->close(); ?>
