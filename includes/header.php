
<?php
// Include the configuration file
require_once 'config.php';

// Prevent direct URL access to this file if it's meant to be included
if (!defined('SECURE_ACCESS')) {
    define('SECURE_ACCESS', true);
}

// Get current page for navigation highlighting
$current_page = basename($_SERVER['PHP_SELF']);

// Sanitize any user inputs
function sanitize_input($data) {
    $data = trim($data);
    $data = stripslashes($data);
    $data = htmlspecialchars($data);
    return $data;
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="description" content="Drivin - Professional Driving School in Kelaniya offering comprehensive driving courses">
    <meta name="keywords" content="driving school, driving lessons, learn to drive, Kelaniya, Sri Lanka">
    <title>Drivin - Professional Driving School</title>
    
    <!-- Favicon -->
    <link rel="icon" href="assets/images/favicon.ico" type="image/x-icon">
    <link rel="apple-touch-icon" href="assets/images/apple-touch-icon.png">
    
    <!-- Font Awesome (Using defer for better performance) -->
    <link rel="preconnect" href="https://cdnjs.cloudflare.com">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.4/css/all.min.css" integrity="sha512-1ycn6IcaQQ40/MKBW2W4Rhis/DbILU74C1vSrLJxCq57o941Ym01SwNsOMqvEBFlcgUa6xLiPY/NS5R+E6ztJQ==" crossorigin="anonymous" referrerpolicy="no-referrer">
    
    <!-- Google Fonts (Preconnect for performance) -->
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;500;600;700&display=swap" rel="stylesheet">
    
    <!-- Custom CSS -->
    <link rel="stylesheet" href="assets/css/style.css">
    <link rel="stylesheet" href="assets/css/responsive.css">
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
                    <a href="https://www.facebook.com/profile.php?id=100089544730818" target="_blank" rel="noopener noreferrer" aria-label="Visit our Facebook page"><i class="fab fa-facebook-f" aria-hidden="true"></i></a>
                    <a href="https://x.com/HashaniRav92602" target="_blank" rel="noopener noreferrer" aria-label="Visit our Twitter page"><i class="fab fa-twitter" aria-hidden="true"></i></a>
                    <a href="https://www.linkedin.com/in/raveesha-amarawickrama-1383592a4/" target="_blank" rel="noopener noreferrer" aria-label="Visit our LinkedIn page"><i class="fab fa-linkedin-in" aria-hidden="true"></i></a>
                    <a href="https://www.instagram.com/accounts/emailsignup/" target="_blank" rel="noopener noreferrer" aria-label="Visit our Instagram page"><i class="fab fa-instagram" aria-hidden="true"></i></a>
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
            
            <nav class="main-nav" aria-label="Main navigation">
                <button class="menu-toggle" aria-expanded="false" aria-controls="primary-menu">
                    <span class="sr-only">Toggle menu</span>
                    <span class="hamburger-icon">
                        <span></span>
                        <span></span>
                        <span></span>
                    </span>
                </button>
                
                <ul id="primary-menu" class="menu">
                    <li><a href="index.php" class="<?php echo ($current_page == 'index.php') ? 'active' : ''; ?>">HOME</a></li>
                    <li><a href="about.php" class="<?php echo ($current_page == 'about.php') ? 'active' : ''; ?>">ABOUT</a></li>
                    <li><a href="courses.php" class="<?php echo ($current_page == 'courses.php') ? 'active' : ''; ?>">COURSES</a></li>
                    <li><a href="team.php" class="<?php echo ($current_page == 'team.php') ? 'active' : ''; ?>">OUR TEAM</a></li>
                    <li><a href="testimonial.php" class="<?php echo ($current_page == 'testimonial.php') ? 'active' : ''; ?>">TESTIMONIAL</a></li>
                    <li><a href="contact.php" class="<?php echo ($current_page == 'contact.php') ? 'active' : ''; ?>">CONTACT</a></li>
                </ul>
            </nav>
            
            <div class="header-actions">
                <div class="header-btn">
                    <a href="login.php" class="btn primary-btn">Get Started <i class="fas fa-arrow-right" aria-hidden="true"></i></a>
                </div>
                
                <div class="header-profile">
                    <?php if (isset($_SESSION['user_id'])): ?>
                        <a href="profile.php" class="profile-icon" aria-label="View your profile">
                            <i class="fas fa-user-circle" aria-hidden="true"></i>
                        </a>
                    <?php else: ?>
                        <a href="login.php" class="profile-icon" aria-label="Login to your account">
                            <i class="fas fa-user-circle" aria-hidden="true"></i>
                        </a>
                       
                    <?php endif; ?>
                </div>
            </div>
        </div>
    </header>
    
    <!-- Main Content -->

    <!-- Scripts -->
    <script src="https://cdnjs.cloudflare.com/ajax/libs/jquery/3.6.0/jquery.min.js" integrity="sha512-894YE6QWD5I59HgZOGReFYm4dnWc1Qt5NtvYSaNcOP+u1T9qYdvdihz0PPSiiqn/+/3e7Jo4EaG7TubfWGUrMQ==" crossorigin="anonymous" referrerpolicy="no-referrer" defer></script>
    <script src="assets/js/main.js" defer></script>

    </body>
</html>