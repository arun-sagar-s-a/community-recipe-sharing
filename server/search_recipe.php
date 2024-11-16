<?php
include '../server/database_connect.php';

$search = isset($_GET['search']) ? $_GET['search'] : '';

// Default query to fetch all recipes
$sql = "SELECT recipe_id, title, image_url FROM Recipes";

// Add search filter if search term is provided
if ($search) {
    $sql .= " WHERE title LIKE '%" . $conn->real_escape_string($search) . "%'";
}

$sql .= " ORDER BY creation_date DESC";
$result = $conn->query($sql);
?>
