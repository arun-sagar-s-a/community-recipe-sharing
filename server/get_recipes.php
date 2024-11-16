<?php
include 'database_connect.php';

header('Content-Type: application/json');

$sql = "SELECT recipe_id, title, image_url FROM Recipes ORDER BY creation_date DESC LIMIT 3";
$result = $conn->query($sql);

$recipes = [];
if ($result->num_rows > 0) {
  while ($row = $result->fetch_assoc()) {
    $recipes[] = $row;
  }
}

echo json_encode($recipes);
$conn->close();
?>
