<?php
session_start();

// redirect if not logged in
if (!isset($_SESSION['user_id'])) {
    header("Location: login.php");
    exit;
}

// include your DB credentials
require_once 'includes/config.php';   // defines DB_HOST, DB_USER, DB_PASS, DB_NAME

// connect
$conn = new mysqli(DB_HOST, DB_USER, DB_PASS, DB_NAME);
if ($conn->connect_error) {
    die("Database connection failed: " . $conn->connect_error);
}

// fetch the user’s data
$userId = intval($_SESSION['user_id']);
$stmt = $conn->prepare("SELECT first_name, last_name, email, phone, country, registration_date 
                        FROM users 
                        WHERE id = ?");
$stmt->bind_param("i", $userId);
$stmt->execute();
$result = $stmt->get_result();
$user = $result->fetch_assoc();
$stmt->close();
$conn->close();

// if somehow no user found, log out
if (!$user) {
    session_destroy();
    header("Location: login.php");
    exit;
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <title>Your Profile</title>
  <link rel="stylesheet" href="assets/css/style.css">
  <style>
    /* profile‑specific tweaks */
    .profile-container {
      max-width: 600px;
      margin: 60px auto;
      background: #fff;
      padding: 30px;
      border: 1px solid #e9e9e9;
      box-shadow: 0 5px 15px rgba(0,0,0,0.1);
      border-radius: 8px;
    }
    .profile-container h2 {
      color: var(--secondary-color);
      margin-bottom: 20px;
    }
    .profile-info p {
      margin: 10px 0;
      font-size: 1rem;
    }
    .profile-info strong {
      width: 140px;
      display: inline-block;
      color: var(--dark-blue);
    }
    .logout-btn {
      display: inline-block;
      margin-top: 20px;
      padding: 10px 20px;
      background: var(--primary-color);
      color: var(--dark-blue);
      border: none;
      border-radius: 4px;
      text-decoration: none;
      font-weight: 600;
    }
    .logout-btn:hover {
      background: var(--dark-blue);
      color: var(--white);
    }
  </style>
</head>
<body>

  <div class="profile-container">
    <h2>Welcome, <?= htmlspecialchars($user['first_name']) ?>!</h2>

    <div class="profile-info">
      <p><strong>Full Name:</strong>
         <?= htmlspecialchars($user['first_name'] . ' ' . $user['last_name']) ?></p>
      <p><strong>Email:</strong>
         <?= htmlspecialchars($user['email']) ?></p>
      <p><strong>Phone:</strong>
         <?= htmlspecialchars($user['phone']) ?></p>
      <p><strong>Country:</strong>
         <?= htmlspecialchars($user['country']) ?></p>
      <p><strong>Registered on:</strong>
         <?= date("F j, Y", strtotime($user['registration_date'])) ?></p>
    </div>

    <a href="logout.php" class="logout-btn">Logout</a>
  </div>

</body>
</html>
