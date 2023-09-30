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
  $password = password_hash($_POST["password"], PASSWORD_DEFAULT);

  $sql = "INSERT INTO Users (username, password) VALUES ('$username', '$password')";
  if ($conn->query($sql) === TRUE) {
    $_SESSION["username"] = $username;
    header("Location: dashboard.php");
    exit;
  } else {
    $error = "Error: " . $sql . "<br>" . $conn->error;
  }
}
$conn->close();
?>

<!DOCTYPE html>
<html>
<head>
  <title>Payroll Site - Register</title>
  <link rel="stylesheet" type="text/css" href="styles.css"> <!-- Link to the CSS file -->
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
    <div class="register-container">
      <h2>Register</h2>

      <?php if (isset($error)) { ?>
        <p class="error-message"><?php echo $error; ?></p>
      <?php } ?>

      <form method="POST" action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]); ?>">
        <label for="username">Username:</label>
        <input type="text" id="username" name="username" required><br>

        <label for="password">Password:</label>
        <input type="password" id="password" name="password" required><br>

        <button type="submit">Register</button>
      </form>
    </div>
  </main>

  <footer>
    <p>&copy; 2023 Payroll Inc. All rights reserved.</p>
  </footer>
</body>
</html>
