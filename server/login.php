<?php
// Include database connection file
include 'database_connect.php';

// Check if form is submitted
if ($_SERVER["REQUEST_METHOD"] === "POST") {
  $username = trim($_POST['login']);
  $password = trim($_POST['pass']);

  // Initialize errors array
  $errors = [];

  // Validate username and password
  if (empty($username)) {
    $errors[] = "Username is required.";
  }
  if (empty($password)) {
    $errors[] = "Password is required.";
  }

  // Check if username and password match database records
  if (empty($errors)) {
    $sql_check = "SELECT * FROM Users WHERE username = ?";
    $stmt_check = $conn->prepare($sql_check);
    $stmt_check->bind_param("s", $username);
    $stmt_check->execute();
    $result = $stmt_check->get_result();

    if ($result->num_rows > 0) {
      $user = $result->fetch_assoc();
      if (password_verify($password, $user['password'])) {
        // Login successful, start session and redirect
        session_start();
        $_SESSION['user_id'] = $user['user_id'];
        $_SESSION['username'] = $user['username'];
        header('Location: ../pages/dashboard.php');
        exit;
      } else {
        $errors[] = "Wrong password.";
      }
    } else {
      $errors[] = "Invalid username.";
    }
  }

  // Store errors in session variables
  session_start();
  $_SESSION['login_error'] = implode("<br>", $errors);
  
  // Redirect to login page
  header('Location: ../pages/login.php');
  exit;
}
?>
