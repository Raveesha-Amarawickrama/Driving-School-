<?php
// Include the configuration file
require_once 'includes/config.php';

// Prevent direct URL access to this file if it's meant to be included
if (!defined('SECURE_ACCESS')) {
    define('SECURE_ACCESS', true);
}

// Initialize session
if (session_status() == PHP_SESSION_NONE) {
    session_start();
}

// Check if user is already logged in as admin
if (isset($_SESSION['admin_id']) && $_SESSION['is_admin'] === true) {
    header("Location: admin_dashboard.php");
    exit;
}

// Define variables and initialize with empty values
$username = $password = "";
$username_err = $password_err = $login_err = "";

// Processing form data when form is submitted
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    
    // Validate username
    if (empty(trim($_POST["username"]))) {
        $username_err = "Please enter username.";
    } else {
        $username = trim($_POST["username"]);
    }
    
    // Validate password
    if (empty(trim($_POST["password"]))) {
        $password_err = "Please enter your password.";
    } else {
        $password = trim($_POST["password"]);
    }
    
    // Validate credentials
    if (empty($username_err) && empty($password_err)) {
        // For demonstration purposes - in production, use database validation
        $admin_username = "admin"; // Change to your preferred admin username
        $admin_password = "admin123"; // Change to your preferred admin password
        
        if ($username === $admin_username && $password === $admin_password) {
            // Password is correct, start a new session
            session_start();
            
            // Store data in session variables
            $_SESSION["loggedin"] = true;
            $_SESSION["admin_id"] = 1;
            $_SESSION["username"] = $username;
            $_SESSION["is_admin"] = true;
            
            // Redirect user to admin dashboard
            header("location: admin_dashboard.php");
            exit;
        } else {
            // Username or password is incorrect
            $login_err = "Invalid username or password.";
        }
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="description" content="Drivin - Admin Login">
    <title>Admin Login - Drivin Driving School</title>
    
    <!-- Favicon -->
    <link rel="icon" href="assets/images/favicon.ico" type="image/x-icon">
    <link rel="apple-touch-icon" href="assets/images/apple-touch-icon.png">
    
    <!-- Font Awesome -->
    <link rel="preconnect" href="https://cdnjs.cloudflare.com">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.4/css/all.min.css" integrity="sha512-1ycn6IcaQQ40/MKBW2W4Rhis/DbILU74C1vSrLJxCq57o941Ym01SwNsOMqvEBFlcgUa6xLiPY/NS5R+E6ztJQ==" crossorigin="anonymous" referrerpolicy="no-referrer">
    
    <!-- Google Fonts -->
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;500;600;700&display=swap" rel="stylesheet">
    
    <!-- Custom CSS -->
    <link rel="stylesheet" href="assets/css/style.css">
    <link rel="stylesheet" href="assets/css/responsive.css">
    <link rel="stylesheet" href="assets/css/admin.css">
</head>
<body>
    <!-- Top Bar -->
    <div class="top-bar">
        <div class="container">
            <div class="top-bar-left">
                <address>
                    <span><i class="fas fa-map-marker-alt" aria-hidden="true"></i> 123 Street, Dalugama, Kelaniya</span>
                    <span><i class="far fa-clock" aria-hidden="true"></i> Mon - Fri: 09:00 AM - 09:00 PM</span>
                </address>
            </div>
            <div class="top-bar-right">
                <span><i class="fas fa-phone-alt" aria-hidden="true"></i> <a href="tel:+94762219168" aria-label="Call our office">+94 76 2219168</a></span>
                <div class="social-links" aria-label="Social media links">
                    <a href="https://www.facebook.com/" target="_blank" rel="noopener noreferrer" aria-label="Visit our Facebook page"><i class="fab fa-facebook-f" aria-hidden="true"></i></a>
                    <a href="https://twitter.com/" target="_blank" rel="noopener noreferrer" aria-label="Visit our Twitter page"><i class="fab fa-twitter" aria-hidden="true"></i></a>
                    <a href="https://www.linkedin.com/" target="_blank" rel="noopener noreferrer" aria-label="Visit our LinkedIn page"><i class="fab fa-linkedin-in" aria-hidden="true"></i></a>
                    <a href="https://www.instagram.com/" target="_blank" rel="noopener noreferrer" aria-label="Visit our Instagram page"><i class="fab fa-instagram" aria-hidden="true"></i></a>
                </div>
            </div>
        </div>
    </div>

    <!-- Header -->
    <header class="header">
        <div class="container">
            <div class="logo">
                <a href="index.php" aria-label="Drivin homepage">
                    <h1><i class="fas fa-car" aria-hidden="true"></i> Drivin</h1>
                </a>
            </div>
        </div>
    </header>

    <!-- Main Content -->
    <main id="main-content" class="admin-login-section">
        <div class="container">
            <div class="admin-login-box">
                <h2><i class="fas fa-user-shield"></i> Admin Login</h2>
                
                <?php if(!empty($login_err)): ?>
                    <div class="alert alert-danger"><?php echo $login_err; ?></div>
                <?php endif; ?>
                
                <form action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]); ?>" method="post">
                    <div class="form-group">
                        <label for="username">Username</label>
                        <input type="text" name="username" id="username" class="form-control <?php echo (!empty($username_err)) ? 'is-invalid' : ''; ?>" value="<?php echo $username; ?>">
                        <span class="invalid-feedback"><?php echo $username_err; ?></span>
                    </div>
                    
                    <div class="form-group">
                        <label for="password">Password</label>
                        <input type="password" name="password" id="password" class="form-control <?php echo (!empty($password_err)) ? 'is-invalid' : ''; ?>">
                        <span class="invalid-feedback"><?php echo $password_err; ?></span>
                    </div>
                    
                    <div class="form-group">
                        <input type="submit" class="btn primary-btn" value="Login">
                    </div>
                    
                    <p class="back-link"><a href="index.php"><i class="fas fa-arrow-left"></i> Back to Website</a></p>
                </form>
            </div>
        </div>
    </main>

    <!-- Footer -->
    <footer class="footer">
        <div class="container">
            <div class="footer-bottom">
                <p>&copy; <?php echo date("Y"); ?> Drivin Driving School. All Rights Reserved.</p>
            </div>
        </div>
    </footer>

    <!-- Scripts -->
    <script src="https://cdnjs.cloudflare.com/ajax/libs/jquery/3.6.0/jquery.min.js" integrity="sha512-894YE6QWD5I59HgZOGReFYm4dnWc1Qt5NtvYSaNcOP+u1T9qYdvdihz0PPSiiqn/+/3e7Jo4EaG7TubfWGUrMQ==" crossorigin="anonymous" referrerpolicy="no-referrer" defer></script>
    <script src="assets/js/main.js" defer></script>
</body>
</html>