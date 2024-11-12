<?php
// Include database connection file
include 'database_connect.php';

// Check if form is submitted
if ($_SERVER["REQUEST_METHOD"] === "POST") {
  $username = trim($_POST['username']);
  $password = trim($_POST['password']);

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
        header('Location: index.php');
        exit;
      } else {
        $errors[] = "Invalid password.";
      }
    } else {
      $errors[] = "Invalid username.";
    }
  }

  // Assign errors to variables
  $usernameError = '';
  $passwordError = '';
  $generalError = '';

  foreach ($errors as $error) {
    if (strpos($error, 'username') !== false) {
      $usernameError = $error;
    } elseif (strpos($error, 'password') !== false) {
      $passwordError = $error;
    } else {
      $generalError = $error;
    }
  }
}

// Store errors in session variables
session_start();
$_SESSION['usernameError'] = $usernameError;
$_SESSION['passwordError'] = $passwordError;
$_SESSION['generalError'] = $generalError;

// Redirect to login.html
header('Location: ../pages/login.html');
exit;
?>