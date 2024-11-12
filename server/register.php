<?php
include 'database_connect.php';

if ($_SERVER["REQUEST_METHOD"] === "POST") {
    $login = trim($_POST['login']);
    $email = filter_var(trim($_POST['email']), FILTER_SANITIZE_EMAIL);
    $pass = trim($_POST['pass']);

    // Initialize errors array
    $errors = [];

    // Username Validation
    if (empty($login)) {
        $errors[] = "Username is required.";
    } elseif (strlen($login) < 3 || strlen($login) > 50) {
        $errors[] = "Username must be between 3 and 50 characters.";
    }

    // Email Validation
    if (empty($email)) {
        $errors[] = "Email is required.";
    } elseif (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
        $errors[] = "Invalid email format.";
    }

    // Password Validation
    $passwordPattern = "/^(?=.*[a-z])(?=.*[A-Z])(?=.*\d)(?=.*[!@#$%^&*])[A-Za-z\d!@#$%^&*]{8,}$/";
    if (empty($pass)) {
        $errors[] = "Password is required.";
    } elseif (!preg_match($passwordPattern, $pass)) {
        $errors[] = "Password must contain at least 8 characters, including 1 uppercase, 1 lowercase, 1 number, and 1 special character.";
    }

    // Check if username or email already exists in the database
    $sql_check = "SELECT * FROM Users WHERE username = ? OR email = ?";
    $stmt_check = $conn->prepare($sql_check);
    $stmt_check->bind_param("ss", $login, $email);
    $stmt_check->execute();
    $result = $stmt_check->get_result();

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

    // Display errors or proceed with registration
    if (!empty($errors)) {
        foreach ($errors as $error) {
            echo "<p style='color: red;'>$error</p>";
        }
    } else {
        // Hash the password and insert the user into the database
        $passwordHash = password_hash($pass, PASSWORD_DEFAULT);
        $sql_insert = "INSERT INTO Users (username, email, password) VALUES (?, ?, ?)";
        $stmt_insert = $conn->prepare($sql_insert);
        $stmt_insert->bind_param("sss", $login, $email, $passwordHash);

        if ($stmt_insert->execute()) {
            echo "<p style='color: green;'>Registration successful!</p>";
        } else {
            echo "<p style='color: red;'>Registration failed. Please try again.</p>";
        }
        $stmt_insert->close();
    }
} else {
    echo "<p style='color: red;'>Invalid request method.</p>";
}

$conn->close();
?>
