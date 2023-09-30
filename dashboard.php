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

$username = $_SESSION["username"];

// Check if the user belongs to an organization
$sql = "SELECT * FROM Organizations WHERE name = '$username'";
$result = $conn->query($sql);

if ($result->num_rows == 0) {
  // User does not belong to an organization
  $showGetStarted = true;
} else {
  // User belongs to an organization, retrieve organization details
  $row = $result->fetch_assoc();
  $organizationName = $row["name"];
  $showGetStarted = false;
}

// Process organization creation form submission
if ($_SERVER["REQUEST_METHOD"] === "POST") {
  $newOrgName = $_POST["org_name"];

  $sql = "INSERT INTO Organizations (name) VALUES ('$newOrgName')";
  if ($conn->query($sql) === TRUE) {
    $organizationName = $newOrgName;
    $showGetStarted = false;
  } else {
    $error = "Error: " . $sql . "<br>" . $conn->error;
  }
}

// Sample employee payroll data
$employees = array(
  array("name" => "John Doe", "pay" => 2000),
  array("name" => "Jane Smith", "pay" => 1800),
  array("name" => "David Johnson", "pay" => 1500),
  array("name" => "Emily Davis", "pay" => 2200),
);

$conn->close();
?>

<!DOCTYPE html>
<html>
<head>
  <title>Payroll Site - Dashboard</title>
  <link rel="stylesheet" type="text/css" href="styles.css"> <!-- Link to the CSS file -->
  <script src="https://cdn.jsdelivr.net/npm/apexcharts@3.26.3"></script> <!-- ApexCharts library -->
  <style>
    .sample-bar-graph {
      width: 600px;
      height: 400px;
      margin: 20px;
    }

    .sample-payroll {
      margin: 20px;
    }

    .employee-table {
      margin-top: 20px;
    }

    .employee-table th,
    .employee-table td {
      padding: 10px;
    }

    .submit-payroll-button {
      padding: 5px 10px;
      background-color: #4CAF50;
      color: white;
      border: none;
      cursor: pointer;
    }

    .submit-payroll-button:hover {
      background-color: #45a049;
    }

    .user-buttons {
      position: absolute;
      top: 0;
      right: 0;
      margin: 10px;
    }

    .user-buttons a {
      margin-left: 10px;
    }
  </style>
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
    <?php if ($showGetStarted) { ?>
      <div class="get-started-container">
        <h2>Hey! Let me show you how to get started.</h2>
        <a href="#organization-form" class="next-button">Next</a>
      </div>
    <?php } else { ?>
      <div class="organization-details">
        <h2>Organization Details</h2>
        <p>Organization Name: <?php echo $organizationName; ?></p>
      </div>

      <div id="sample-bar-graph" class="sample-bar-graph"></div>

      <div class="sample-payroll">
        <h2>Sample Payroll</h2>
        <table class="employee-table">
          <thead>
            <tr>
              <th>Name</th>
              <th>Pay</th>
              <th>Action</th>
            </tr>
          </thead>
          <tbody>
            <?php foreach ($employees as $employee) { ?>
              <tr>
                <td><?php echo $employee['name']; ?></td>
                <td><?php echo $employee['pay']; ?></td>
                <td>
                  <button class="submit-payroll-button">Submit Payroll</button>
                </td>
              </tr>
            <?php } ?>
          </tbody>
        </table>
      </div>
    <?php } ?>

    <?php if ($showGetStarted) { ?>
      <div id="organization-form" class="organization-form">
        <h2>Enter Organization Details</h2>

        <?php if (isset($error)) { ?>
          <p class="error-message"><?php echo $error; ?></p>
        <?php } ?>

        <form method="POST" action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]); ?>">
          <label for="org_name">Organization Name:</label>
          <input type="text" id="org_name" name="org_name" required><br>

          <button type="submit">Create Organization</button>
        </form>
      </div>
    <?php } ?>
  </main>

  <footer>
    <p>&copy; 2023 Payroll Inc. All rights reserved.</p>
  </footer>

  <?php if (!$showGetStarted) { ?>
    <script>
      // Sample data for the bar graph
      var sampleData = {
        categories: ['Jan', 'Feb', 'Mar', 'Apr', 'May', 'Jun'],
        series: [
          {
            name: 'Sales',
            data: [1200, 1800, 1500, 2000, 2200, 1900]
          }
        ]
      };

      // Initialize ApexCharts
      var options = {
        chart: {
          type: 'bar',
          height: 350
        },
        series: sampleData.series,
        xaxis: {
          categories: sampleData.categories
        }
      };

      var chart = new ApexCharts(document.querySelector("#sample-bar-graph"), options);
      chart.render();
    </script>
  <?php } ?>
</body>
</html>
