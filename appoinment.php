<?php
session_start();

// show all errors during development
ini_set('display_errors', 1);
error_reporting(E_ALL);

// Database connection details
$servername = "localhost";
$username   = "root";
$password   = "";            // XAMPP default
$dbname     = "drivin_db";   // your database

// Create connection
$conn = new mysqli($servername, $username, $password, $dbname);
if ($conn->connect_error) {
    $_SESSION['appointment_error'] = "Connection failed: " . $conn->connect_error;
    header('Location: index.php');
    exit;
}

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    // sanitize inputs
    $name        = strip_tags(trim($_POST['name']         ?? ''));
    $email       = filter_var(trim($_POST['email']       ?? ''), FILTER_SANITIZE_EMAIL);
    $courseType  = strip_tags(trim($_POST['course_type'] ?? ''));
    $carType     = strip_tags(trim($_POST['car_type']    ?? ''));
    $message     = strip_tags(trim($_POST['message']     ?? ''));
    $dateCreated = date('Y-m-d H:i:s');

    // validate
    if ($name === '' || $email === '' || $courseType === '') {
        $_SESSION['appointment_error'] = 'Please fill in all required fields (name, email, course type).';
        header('Location: index.php');
        exit;
    }
    if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
        $_SESSION['appointment_error'] = 'Please enter a valid email address.';
        header('Location: index.php');
        exit;
    }

    // insert
    $stmt = $conn->prepare(
      "INSERT INTO appointments
         (name, email, course_type, car_type, message, date_created)
       VALUES (?, ?, ?, ?, ?, ?)"
    );
    $stmt->bind_param("ssssss", $name, $email, $courseType, $carType, $message, $dateCreated);

    if ($stmt->execute()) {
        $_SESSION['appointment_success'] = 'Your appointment request has been submitted successfully.';
    } else {
        $_SESSION['appointment_error'] = 'Database error: ' . $stmt->error;
    }

    $stmt->close();
    $conn->close();

    // redirect back to home/landing page
    header('Location: index.php');
    exit;
}

// if not POST, just go home
header('Location: index.php');
exit;
?>
