<?php
session_start();
$hostname = "localhost";
$username = "root";
$password = "";
$database = "natehub";

$conn = new mysqli($hostname, $username, $password, $database);
if ($conn->connect_error) {
  die("Connection failed: " . $conn->connect_error);
}

if ($_SERVER["REQUEST_METHOD"] === "POST") {
  $username = $_POST["username"];
  $password = $_POST["password"];

  $sql = "SELECT * FROM Users WHERE username = '$username'";
  $result = $conn->query($sql);

  if ($result->num_rows == 1) {
    $row = $result->fetch_assoc();
    if (password_verify($password, $row["password"])) {
      $_SESSION["username"] = $username;
      header("Location: dashboard.php");
      exit;
    } else {
      $error = "Invalid username or password.";
    }
  } else {
    $error = "Invalid username or password.";
  }
}
$conn->close();
?>

<!DOCTYPE html>
<html>
<head>
  <title>Payroll Site - Login</title>
  <link rel="stylesheet" type="text/css" href="styles.css">
</head>
<body>
  <header>
    <h1>Welcome to Payroll Inc.</h1>
    <nav>
      <ul>
        <li><a href="#">Home</a></li>
        <li><a href="#">About</a></li>
        <li><a href="#">Services</a></li>
        <li><a href="#">Contact</a></li>
      </ul>
      <div class="user-buttons">
        <a href="login.php" class="login-button">Login</a>
        <a href="register.php" class="register-button">Register</a>
      </div>
    </nav>
  </header>

  <main>
    <div class="login-container">
      <h2>Login</h2>

      <?php if (isset($error)) { ?>
        <p class="error-message"><?php echo $error; ?></p>
      <?php } ?>

      <form method="POST" action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]); ?>">
        <label for="username">Username:</label>
        <input type="text" id="username" name="username" required><br>

        <label for="password">Password:</label>
        <input type="password" id="password" name="password" required><br>

        <button type="submit">Login</button>
      </form>
    </div>
  </main>

  <footer>
    <p>&copy; 2023 Payroll Inc. All rights reserved.</p>
  </footer>
</body>
</html>
