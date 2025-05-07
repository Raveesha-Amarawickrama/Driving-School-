<?php
$servername = "localhost";
$username = "root";
$password = ""; // Empty by default in XAMPP
$dbname = "drivin_db"; // Or whatever your actual DB name is


session_start(); // Needed for error storage

$response = array('success' => false, 'message' => '', 'errors' => array());

function sanitize_input($data) {
    return htmlspecialchars(stripslashes(trim($data)));
}

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $firstName = isset($_POST['firstName']) ? sanitize_input($_POST['firstName']) : '';
    $lastName = isset($_POST['lastName']) ? sanitize_input($_POST['lastName']) : '';
    $email = isset($_POST['email']) ? sanitize_input($_POST['email']) : '';
    $password = $_POST['password'] ?? '';
    $confirmPassword = $_POST['confirmPassword'] ?? '';
    $phone = isset($_POST['phone']) ? sanitize_input($_POST['phone']) : '';
    $country = isset($_POST['country']) ? sanitize_input($_POST['country']) : '';

    $errors = [];

    if (empty($firstName)) $errors[] = "First name is required";
    if (empty($lastName)) $errors[] = "Last name is required";
    if (empty($email)) $errors[] = "Email is required";
    elseif (!filter_var($email, FILTER_VALIDATE_EMAIL)) $errors[] = "Invalid email format";
    if (empty($password)) $errors[] = "Password is required";
    elseif (strlen($password) < 8) $errors[] = "Password must be at least 8 characters long";
    if ($password !== $confirmPassword) $errors[] = "Passwords do not match";

    if (empty($errors)) {
        try {
            $conn = new PDO("mysql:host=$servername;dbname=$dbname", $username, $dbPassword);
            $conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

            $hashed_password = password_hash($password, PASSWORD_DEFAULT);

            $stmt = $conn->prepare("SELECT COUNT(*) FROM users WHERE email = :email");
            $stmt->bindParam(':email', $email);
            $stmt->execute();

            if ($stmt->fetchColumn() > 0) {
                $response['message'] = "Email already exists. Please use a different email.";
            } else {
                $sql = "INSERT INTO users (first_name, last_name, email, password, phone, country, registration_date) 
                        VALUES (:firstName, :lastName, :email, :password, :phone, :country, NOW())";
                $stmt = $conn->prepare($sql);
                $stmt->bindParam(':firstName', $firstName);
                $stmt->bindParam(':lastName', $lastName);
                $stmt->bindParam(':email', $email);
                $stmt->bindParam(':password', $hashed_password);
                $stmt->bindParam(':phone', $phone);
                $stmt->bindParam(':country', $country);
                $stmt->execute();

                $response['success'] = true;
                $response['message'] = "Registration successful!";
            }
        } catch (PDOException $e) {
            $response['message'] = "Database error: " . $e->getMessage();
        }
        $conn = null;
    } else {
        $response['message'] = "Please correct the following errors:";
        $response['errors'] = $errors;
    }

    // Store errors in session if not successful
    if (!$response['success']) {
        $_SESSION['registration_error'] = $response['message'];
        $_SESSION['validation_errors'] = $response['errors'];
        $_SESSION['form_data'] = $_POST;
        header("Location: register.php"); // Redirect back to form
        exit;
    } else {
        // Clear session data and redirect
        unset($_SESSION['registration_error'], $_SESSION['form_data'], $_SESSION['validation_errors']);
        header("Location: index.php");
        exit;
    }
} else {
    $response['message'] = "Invalid request method";
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <title>User Registration</title>
  <link rel="stylesheet" href="assets/css/style.css">
</head>
<body>
  <div class="registration-section">
    <div class="registration-form">
      <h2>User Registration</h2>

      <?php
      if (session_status() === PHP_SESSION_NONE) {
        session_start();
    }
    
      if (isset($_SESSION['registration_error'])) {
          echo '<div class="error-message">' . $_SESSION['registration_error'] . '</div>';
          if (!empty($_SESSION['validation_errors'])) {
              echo '<ul class="error-list">';
              foreach ($_SESSION['validation_errors'] as $err) {
                  echo '<li>' . htmlspecialchars($err) . '</li>';
              }
              echo '</ul>';
          }
          unset($_SESSION['registration_error'], $_SESSION['validation_errors']);
      }

      $formData = $_SESSION['form_data'] ?? [];
      unset($_SESSION['form_data']);
      ?>

      <form id="registrationForm" action="register.php" method="POST">
        <div class="form-group">
          <label for="firstName">First Name</label>
          <input type="text" id="firstName" name="firstName" required value="<?= htmlspecialchars($formData['firstName'] ?? '') ?>">
        </div>

        <div class="form-group">
          <label for="lastName">Last Name</label>
          <input type="text" id="lastName" name="lastName" required value="<?= htmlspecialchars($formData['lastName'] ?? '') ?>">
        </div>

        <div class="form-group">
          <label for="email">Email Address</label>
          <input type="email" id="email" name="email" required value="<?= htmlspecialchars($formData['email'] ?? '') ?>">
          <div id="emailError" class="error"></div>
        </div>

        <div class="form-group">
          <label for="password">Password</label>
          <input type="password" id="password" name="password" required>
          <div id="passwordError" class="error"></div>
        </div>

        <div class="form-group">
          <label for="confirmPassword">Confirm Password</label>
          <input type="password" id="confirmPassword" name="confirmPassword" required>
          <div id="confirmPasswordError" class="error"></div>
        </div>

        <div class="form-group">
          <label for="phone">Phone Number</label>
          <input type="text" id="phone" name="phone" value="<?= htmlspecialchars($formData['phone'] ?? '') ?>">
        </div>

        <div class="form-group">
          <label for="country">Country</label>
          <select id="country" name="country">
            <option value="">Select a country</option>
            <?php
              $countries = ["USA", "UK", "Canada", "Australia", "India", "Germany", "France", "Other"];
              foreach ($countries as $c) {
                  $selected = (isset($formData['country']) && $formData['country'] == $c) ? 'selected' : '';
                  echo "<option value=\"$c\" $selected>$c</option>";
              }
            ?>
          </select>
        </div>

        <div class="form-group">
          <button type="submit" class="btn">Register</button>
        </div>
      </form>
    </div>
  </div>

  <script>
    document.getElementById('registrationForm').addEventListener('submit', function(e) {
      let valid = true;
      const password = document.getElementById('password').value;
      const confirmPassword = document.getElementById('confirmPassword').value;
      const email = document.getElementById('email').value;

      document.getElementById('emailError').textContent = '';
      document.getElementById('passwordError').textContent = '';
      document.getElementById('confirmPasswordError').textContent = '';

      if (!validateEmail(email)) {
        document.getElementById('emailError').textContent = 'Please enter a valid email address.';
        valid = false;
      }

      if (password.length < 8) {
        document.getElementById('passwordError').textContent = 'Password must be at least 8 characters long.';
        valid = false;
      }

      if (password !== confirmPassword) {
        document.getElementById('confirmPasswordError').textContent = 'Passwords do not match.';
        valid = false;
      }

      if (!valid) {
        e.preventDefault();
      }
    });

    function validateEmail(email) {
      const re = /^[^\s@]+@[^\s@]+\.[^\s@]+$/;
      return re.test(String(email).toLowerCase());
    }
  </script>
</body>
</html>
