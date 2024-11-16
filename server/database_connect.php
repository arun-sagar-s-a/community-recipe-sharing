<?php
// Database configuration
$host = "localhost";
$username = "root"; // Default XAMPP username
$password = ''; // Default XAMPP password (leave empty)
// $database = "community_recipe_sharing";
$database = "community_recipe_sharing";

// This point those you changed to port 3307 might encounter error.
// Nice vidoe to fix it: https://www.youtube.com/watch?v=YkLfRVGRLjE
// optionally you can connect at 3307 for port
// connection object
$conn = new mysqli($host, $username, $password, $database,3307);

// Check connection
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}
?>
