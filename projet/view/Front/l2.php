<?php
// Include necessary files for database connection or validation (if needed)
require_once('../../model/user.php');
require_once('../../controler/userc.php');
$message = "";

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    // Handle Sign Up form submission
    if (isset($_POST['sign_up'])) {
        $username = $_POST['username'];
        $email = $_POST['email'];
        $password = $_POST['password'];
        $confirmPassword = $_POST['confirm_password'];

        // Check if passwords match
        if ($password !== $confirmPassword) {
            $message = "Passwords do not match!";
        } else {
            // Create a UserController instance to add the user to the database
            $controller = new UserController();
            $user = new User($username, $email, $password);
            $controller->addUser($user);
            $message = "User successfully registered!";
        }
    }

    // Handle Sign In form submission
    if (isset($_POST['sign_in'])) {
        $username = $_POST['username'];
        $password = $_POST['password'];

        // Validate user credentials
        $controller = new UserController();
        $user = $controller->verifyCredentials($username, $password);
        if ($user) {
            $message = "Welcome back, " . $user->getUsername() . "!";
            // Redirect or start the session here
            // header("Location: dashboard.php"); // example
            exit();
        } else {
            $message = "Invalid credentials!";
        }
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1.0"/>
  <title>Authentication</title>
  <link rel="stylesheet" href="../assets/css/l2.css" />

  <link href="https://unpkg.com/boxicons@2.1.4/css/boxicons.min.css" rel="stylesheet" />
</head>
<body>
  <div id="container" class="container">
    <div class="row">
      <!-- SIGN UP -->
      <div class="col align-items-center flex-col sign-up">
        <div class="form-wrapper align-items-center">
          <div class="form sign-up">
            <form method="POST" action="">
              <div class="input-group">
                <i class="bx bxs-user"></i>
                <input type="text" name="username" placeholder="Username" required />
              </div>
              <div class="input-group">
                <i class="bx bx-mail-send"></i>
                <input type="email" name="email" placeholder="Email" required />
              </div>
              <div class="input-group">
                <i class="bx bxs-lock-alt"></i>
                <input type="password" name="password" placeholder="Password" required />
              </div>
              <div class="input-group">
                <i class="bx bxs-lock-alt"></i>
                <input type="password" name="confirm_password" placeholder="Confirm Password" required />
              </div>
              <button type="submit" name="sign_up">Sign up</button>
            </form>
            <?php if ($message) echo "<p style='color:red;'>$message</p>"; ?>
            <p>
              <span>Already have an account?</span>
              <b onclick="toggle()" class="pointer">Sign in here</b>
            </p>
          </div>
        </div>
      </div>

      <!-- SIGN IN -->
      <div class="col align-items-center flex-col sign-in">
        <div class="form-wrapper align-items-center">
          <div class="form sign-in">
            <form method="POST" action="">
              <div class="input-group">
                <i class="bx bxs-user"></i>
                <input type="text" name="username" placeholder="Username" required />
              </div>
              <div class="input-group">
                <i class="bx bxs-lock-alt"></i>
                <input type="password" name="password" placeholder="Password" required />
              </div>
              <button type="submit" name="sign_in">Sign in</button>
            </form>
            <?php if ($message) echo "<p style='color:red;'>$message</p>"; ?>
            <!-- Forgot Password Link -->
            <p><a href="forgotpassword.php" style="color: black; text-decoration: none;"><b>Forgot password?</b></a></p>

            <p>
              <span>Don't have an account?</span>
              <b onclick="toggle()" class="pointer">Sign up here</b>
            </p>
          </div>
        </div>
      </div>
    </div>

    <!-- CONTENT SECTION -->
    <div class="row content-row">
      <div class="col align-items-center flex-col">
        <div class="text sign-in">
          <h2>Welcome</h2>
        </div>
        <div class="img sign-in">
          <!-- Image can go here -->
        </div>
      </div>

      <div class="col align-items-center flex-col">
        <div class="img sign-up">
          <!-- Image can go here -->
        </div>
        <div class="text sign-up">
          <h2>Join with us</h2>
        </div>
      </div>
    </div>
  </div>

  <script src="../assets/js/l2.js"></script>
  <script>
    document.getElementById('user-btn').addEventListener('click', function() {
      document.getElementById('organizer-btn').classList.remove('active');
      document.querySelector('.organization-input-container').style.display = 'none';
    });

    document.getElementById('organizer-btn').addEventListener('click', function() {
      this.classList.add('active');
      document.querySelector('.organization-input-container').style.display = 'block';
    });
  </script>
</body>
</html>
