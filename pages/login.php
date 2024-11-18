<!DOCTYPE html>
<html lang="en">
  <head>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />
    <title>Login - Community Recipe Sharing</title>
    <link rel="stylesheet" href="../styles/common.css" />
    <script src="../scripts/loginvalidation.js" defer></script>
  </head>
  <body>
    <header>
      <h1>Community Recipe Sharing</h1>
      <nav>
        <a href="./dashboard.php">Home</a>
        <a href="./index.php">Register</a>
      </nav>
    </header>

    <main class="container">
      <section class="form-container">
        <h2>Login</h2>
        <form
          id="login-form"
          method="post"
          action="../server/login.php"
          onsubmit="return validate()"
        >
          <div class="textfield form-group">
            <label for="login">Username</label>
            <input
              type="text"
              name="login"
              id="login"
              placeholder="Enter your username"
              oninput="validateLogin()"
            />
            <p class="error-message" id="loginError"></p>
          </div>

          <div class="textfield form-group">
            <label for="pass">Password</label>
            <input
              type="password"
              name="pass"
              id="pass"
              placeholder="Enter your password"
              oninput="validatePassword()"
            />
            <p class="error-message" id="passError"></p>
          </div>

          <!-- Display login error if exists -->
          <?php
            session_start();
            if (isset($_SESSION['login_error'])) {
                echo "<p style='color: red;'>" . $_SESSION['login_error'] . "</p>";
                unset($_SESSION['login_error']);
            }
          ?>

          <div class="button-container">
            <button type="submit">Login</button>
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
