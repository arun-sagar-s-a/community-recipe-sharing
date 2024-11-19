<?php
// Include database connection file
include 'database_connect.php';

// Check if the request method is POST
if ($_SERVER["REQUEST_METHOD"] === "POST") {
    // Sanitize and trim user inputs
    $login = trim($_POST['login']);
    $email = filter_var(trim($_POST['email']), FILTER_SANITIZE_EMAIL);
    $pass = trim($_POST['pass']);
    $errors = []; // Initialize errors array

    // === Username Validation ===
    if (empty($login)) {
        $errors[] = "Username is required.";
    } elseif (strlen($login) < 3 || strlen($login) > 50) {
        $errors[] = "Username must be between 3 and 50 characters.";
    }

    // === Email Validation ===
    if (empty($email)) {
        $errors[] = "Email is required.";
    } elseif (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
        $errors[] = "Invalid email format.";
    }

    // === Password Validation ===
    $passwordPattern = "/^(?=.*[a-z])(?=.*[A-Z])(?=.*\d)(?=.*[!@#$%^&*])[A-Za-z\d!@#$%^&*]{8,}$/";
    if (empty($pass)) {
        $errors[] = "Password is required.";
    } elseif (!preg_match($passwordPattern, $pass)) {
        $errors[] = "Password must contain at least 8 characters, including 1 uppercase, 1 lowercase, 1 number, and 1 special character.";
    }

    // === Check for Existing Username or Email in the Database ===
    $sql_check = "SELECT * FROM Users WHERE username = ? OR email = ?";
    $stmt_check = $conn->prepare($sql_check);
    $stmt_check->bind_param("ss", $login, $email);
    $stmt_check->execute();
    $result = $stmt_check->get_result();

    // If username or email already exists, add errors
    if ($result->num_rows > 0) {
        $existingUser = $result->fetch_assoc();
        if ($existingUser['username'] === $login) {
            $errors[] = "Username already exists.";
        }
        if ($existingUser['email'] === $email) {
            $errors[] = "Email already registered.";
        }
    }
    $stmt_check->close();

    // === Handle Errors ===
    if (!empty($errors)) {
        // Start session to pass errors to the registration form
        session_start();
        $_SESSION['errors'] = $errors;

        // Redirect back to the registration form
        header('Location: ../pages/register.php');
        exit;
    }

    // === Insert New User into the Database ===
    // Hash the password before saving it
    $passwordHash = password_hash($pass, PASSWORD_DEFAULT);

    $sql_insert = "INSERT INTO Users (username, email, password) VALUES (?, ?, ?)";
    $stmt_insert = $conn->prepare($sql_insert);
    $stmt_insert->bind_param("sss", $login, $email, $passwordHash);

    if ($stmt_insert->execute()) {
        // Redirect to the login page with a success message
        header('Location: ../pages/login.php?message=Registration Successful. Please log in.');
        exit;
    } else {
        // Log the error for debugging and show a generic error message
        error_log("Database Insert Error: " . $stmt_insert->error);
        echo "<p style='color: red;'>An unexpected error occurred. Please try again later.</p>";
    }
    $stmt_insert->close();
} else {
    // Display an error if the request method is not POST
    echo "<p style='color: red;'>Invalid request method.</p>";
}

// Close the database connection
$conn->close();
?>
