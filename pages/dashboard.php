<?php
// Start the session
session_start();
include '../server/database_connect.php'; // Ensure the database connection is included
?>
<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Community Recipe Sharing</title>
  <link rel="stylesheet" href="../styles/dashboard.css">
  <script src="../scripts/initial.js" defer></script>
</head>
<body>

  <header>
    <h1 style="color:aliceblue">Welcome to Community Recipe Sharing</h1>
    <nav>
      <?php if (isset($_SESSION['user_id'])): ?>
        <a href="./display_recipes.php">View Recipes</a>  
        <a href="../server/logout.php">Logout</a>
      <?php else: ?>
        <a href="./index.php">Register</a>  
        <a href="./login.php">Login</a>
      <?php endif; ?>
    </nav>
  </header>

  <main>
    <section id="featured-recipes">
      <h2>
        <?php if (isset($_SESSION['user_id'])): ?>
          Welcome back, <?php echo htmlspecialchars($_SESSION['username']); ?>! Here are your featured recipes:
        <?php else: ?>
          Featured Recipes
        <?php endif; ?>
      </h2>
      <div id="recipes-container">
        <?php
        if (isset($_SESSION['user_id'])) {
            $user_id = $_SESSION['user_id'];
            $query = "SELECT * FROM Recipes WHERE user_id = ?";
            $stmt = $conn->prepare($query);
            $stmt->bind_param("i", $user_id);
            $stmt->execute();
            $result = $stmt->get_result();

            if ($result->num_rows > 0) {
                while ($recipe = $result->fetch_assoc()) {
                    echo "<div class='recipe'>
                            <h2>". 'Your Recipes' ." <h2/>
                            <h3>" . htmlspecialchars($recipe['title']) . "</h3>
                          </div>";
                }
            } else {
                echo "<p>You haven't added any recipes yet!</p>";
            }
        } else {
            echo "<p>Discover amazing recipes from our community!</p>";
        }
        ?>
      </div>
    </section>
  </main>

  <footer>
    <p>© Community Recipe Sharing</p>
  </footer>

</body>
</html>
