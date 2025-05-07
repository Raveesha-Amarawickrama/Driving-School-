<?php
session_start();

$errors = [];

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $email = trim($_POST['email'] ?? '');
    $passwordInput = $_POST['password'] ?? '';

    if (empty($email) || empty($passwordInput)) {
        $errors[] = "Please enter both email and password.";
    } else {
        try {
            // Database credentials
            $servername = "localhost";
            $dbUsername = "root";
            $dbPassword = ""; // Default in XAMPP
            $dbname = "drivin_db"; // Your DB name

            // PDO Connection
            $conn = new PDO("mysql:host=$servername;dbname=$dbname", $dbUsername, $dbPassword);
            $conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

            // Prepare and execute query
            $stmt = $conn->prepare("SELECT * FROM users WHERE email = :email");
            $stmt->bindParam(':email', $email);
            $stmt->execute();

            $user = $stmt->fetch(PDO::FETCH_ASSOC);

            if ($user && password_verify($passwordInput, $user['password'])) {
                $_SESSION['user_id'] = $user['id'];
                $_SESSION['user_name'] = $user['first_name'];
                header("Location: index.php");
                exit;
            } else {
                $errors[] = "Invalid email or password.";
            }
        } catch (PDOException $e) {
            $errors[] = "Database error: " . $e->getMessage();
        }
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <title>Login</title>
  <link rel="stylesheet" href="assets/css/style.css">
</head>
<body>
  <div class="registration-section">
    <div class="registration-form">
      <h2>User Login</h2>

      <?php if (!empty($errors)): ?>
        <div class="error-message">
          <?php foreach ($errors as $error): ?>
            <p><?= htmlspecialchars($error) ?></p>
          <?php endforeach; ?>
        </div>
      <?php endif; ?>

      <form method="POST" action="login.php">
        <div class="form-group">
          <label for="email">Email Address</label>
          <input type="email" id="email" name="email" required value="<?= htmlspecialchars($email ?? '') ?>">
        </div>

        <div class="form-group">
          <label for="password">Password</label>
          <input type="password" id="password" name="password" required>
        </div>

        <div class="form-group">
          <button type="submit" class="btn">Login</button>
        </div>

        <div style="text-align:center;">
          <p>Don't have an account? <a href="register.php">Register here</a></p>
        </div>
      </form>
    </div>
  </div>
</body>
</html>
