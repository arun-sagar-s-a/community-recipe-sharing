<!DOCTYPE html>
<html lang="en">
  <head>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />
    <title>Registration Form - Community Recipe Sharing</title>
    <link rel="stylesheet" href="../styles/common.css" />
    <script src="../scripts/validation.js" defer></script>
  </head>
  <body>
    <header>
      <h1>Community Recipe Sharing</h1>
      <nav>
        <a href="index.php">Home</a>
        <a href="./login.php">Login</a>
      </nav>
    </header>

    <main class="container">
      <section class="form-container">
        <h2>Registration Form</h2>

        <?php
        // Start session to access error messages
        session_start();
        if (isset($_SESSION['errors'])) {
          echo '<div class="error-messages">';
          foreach ($_SESSION['errors'] as $error) {
            echo '<p class="error-message">' . htmlspecialchars($error) . '</p>';
          }
          echo '</div>';
          // Clear errors after displaying them
          unset($_SESSION['errors']);
        }
        ?>

        <form
          id="registration-form"
          method="post"
          action="../server/register.php"
          onsubmit="return validate()"
        >
          <div class="textfield form-group">
            <label for="login">User name</label>
            <input
              type="text"
              name="login"
              id="login"
              placeholder="please enter a unique username"
              oninput="validateLogin()"
            />
            <p class="error-message" id="loginError"></p>
          </div>

          <div class="textfield form-group">
            <label for="email">Email Address</label>
            <input
              type="text"
              name="email"
              id="email"
              placeholder="please enter your email"
              oninput="validateEmail()"
            />
            <p class="error-message" id="emailError"></p>
          </div>

          <div class="textfield form-group">
            <label for="pass">Password</label>
            <input
              type="password"
              name="pass"
              id="pass"
              placeholder="Password"
              oninput="enableConfirmPassword(); validatePassword()"
            />
            <p class="error-message" id="passError"></p>
          </div>

          <div class="textfield form-group">
            <label for="pass2">Re-type Password</label>
            <input
              type="password"
              id="pass2"
              placeholder="Password"
              disabled
              oninput="validateRePassword()"
            />
            <p class="error-message" id="pass2Error"></p>
          </div>

          <div class="button-container">
            <button type="submit">Sign-Up</button>
            <button type="reset" onclick="clearErrors()">Reset</button>
          </div>
        </form>
      </section>
    </main>

    <footer>
      <p>© Community Recipe Sharing</p>
    </footer>
  </body>
</html>
