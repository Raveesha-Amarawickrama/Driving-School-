<?php
// Include the configuration file
require_once 'includes/config.php';

// Initialize session
if (session_status() == PHP_SESSION_NONE) {
    session_start();
}

// Initialize the approved appointments array if it doesn't exist
if (!isset($_SESSION['approved_appointments'])) {
    $_SESSION['approved_appointments'] = [];
}

// Check if the user is logged in as admin, if not then redirect to login page
if(!isset($_SESSION["loggedin"]) || $_SESSION["loggedin"] !== true || !isset($_SESSION["is_admin"]) || $_SESSION["is_admin"] !== true) {
    header("location: admin_login.php");
    exit;
}

// Process appointment actions
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    if (isset($_POST['action']) && isset($_POST['appointment_id'])) {
        $appointment_id = $_POST['appointment_id'];
        
        // Database connection
        $conn = new mysqli(DB_HOST, DB_USER, DB_PASS, DB_NAME);
        
        // Check connection
        if ($conn->connect_error) {
            die("Connection failed: " . $conn->connect_error);
        }
        
        if ($_POST['action'] === 'approve') {
            // Since there's no status column, we'll use session to track approved appointments
            $_SESSION['approved_appointments'][$appointment_id] = true;
            $success_message = "Appointment approved successfully!";
            
            // If you want to implement status tracking properly, you would need to:
            // 1. Add a status column to your appointments table:
            // ALTER TABLE appointments ADD COLUMN status VARCHAR(20) DEFAULT 'pending';
            
            // 2. Then you could uncomment this code:
            /*
            $sql = "UPDATE appointments SET status = 'approved' WHERE id = ?";
            
            if ($stmt = $conn->prepare($sql)) {
                $stmt->bind_param("i", $appointment_id);
                
                if ($stmt->execute()) {
                    // Success message
                    $success_message = "Appointment approved successfully!";
                } else {
                    $error_message = "Oops! Something went wrong. Please try again later.";
                }
                
                // Close statement
                $stmt->close();
            }
            */
        } elseif ($_POST['action'] === 'delete') {
            // Delete appointment
            $sql = "DELETE FROM appointments WHERE id = ?";
            
            if ($stmt = $conn->prepare($sql)) {
                $stmt->bind_param("i", $appointment_id);
                
                if ($stmt->execute()) {
                    // Success message
                    $success_message = "Appointment deleted successfully!";
                } else {
                    $error_message = "Oops! Something went wrong. Please try again later.";
                }
                
                // Close statement
                $stmt->close();
            }
        }
        
        // Close connection
        $conn->close();
    }
}

// Function to get all appointments
function getAppointments() {
    // Create database connection
    $conn = new mysqli(DB_HOST, DB_USER, DB_PASS, DB_NAME);
    
    // Check connection
    if ($conn->connect_error) {
        die("Connection failed: " . $conn->connect_error);
    }
    
    // Modified query to match your database structure
    // Order by date_created instead of appointment_date
    $sql = "SELECT * FROM appointments ORDER BY date_created DESC";
    
    $result = $conn->query($sql);
    
    $appointments = [];
    
    if ($result->num_rows > 0) {
        // Output data of each row
        while($row = $result->fetch_assoc()) {
            $appointments[] = $row;
        }
    }
    
    $conn->close();
    
    return $appointments;
}

// Get all appointments
$appointments = getAppointments();
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="description" content="Drivin - Admin Dashboard">
    <title>Admin Dashboard - Drivin Driving School</title>
    
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
    <style>
        .badge {
            padding: 5px 10px;
            border-radius: 4px;
            font-size: 12px;
            font-weight: 600;
        }
        .badge-warning {
            background-color: #ffc107;
            color: #212529;
        }
        .badge-success {
            background-color: #28a745;
            color: #fff;
        }
        .badge-danger {
            background-color: #dc3545;
            color: #fff;
        }
        .approved-row {
            background-color: rgba(40, 167, 69, 0.1);
        }
        .btn-sm {
            padding: 0.25rem 0.5rem;
            font-size: 0.875rem;
            line-height: 1.5;
            border-radius: 0.2rem;
        }
        .btn-success {
            color: #fff;
            background-color: #28a745;
            border-color: #28a745;
        }
        .btn-danger {
            color: #fff;
            background-color: #dc3545;
            border-color: #dc3545;
        }
        .d-inline-block {
            display: inline-block;
        }
    </style>
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

    <!-- Admin Header -->
    <header class="admin-header">
        <div class="container">
            <div class="admin-header-content">
                <div class="logo">
                    <a href="index.php" aria-label="Drivin homepage">
                        <h1><i class="fas fa-car" aria-hidden="true"></i> Drivin</h1>
                    </a>
                </div>
                <div class="admin-user">
                    <span>Welcome, Admin</span>
                    <a href="admin_login.php" class="logout-btn"><i class="fas fa-sign-out-alt"></i> Logout</a>
                </div>
            </div>
        </div>
    </header>

    <!-- Main Content -->
    <main id="main-content" class="admin-dashboard">
        <div class="container">
            <div class="admin-dashboard-header">
                <h2><i class="fas fa-tachometer-alt"></i> Admin Dashboard</h2>
                <span class="current-date"><?php echo date("F j, Y"); ?></span>
            </div>
            
            <!-- Alert Messages -->
            <?php if(isset($success_message)): ?>
                <div class="alert alert-success">
                    <?php echo $success_message; ?>
                </div>
            <?php endif; ?>
            
            <?php if(isset($error_message)): ?>
                <div class="alert alert-danger">
                    <?php echo $error_message; ?>
                </div>
            <?php endif; ?>
            
            <div class="dashboard-content">
                <div class="dashboard-card">
                    <div class="card-header">
                        <h3><i class="far fa-calendar-check"></i> Manage Appointments</h3>
                    </div>
                    <div class="card-body">
                        <?php if(count($appointments) > 0): ?>
                            <div class="table-responsive">
                                <table class="table">
                                    <thead>
                                        <tr>
                                            <th>ID</th>
                                            <th>Name</th>
                                            <th>Email</th>
                                            <th>Course</th>
                                            <th>Car Type</th>
                                            <th>Message</th>
                                            <th>Date Created</th>
                                            <th>Status</th>
                                            <th>Actions</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        <?php foreach($appointments as $appointment): ?>
                                            <tr <?php echo isset($_SESSION['approved_appointments'][$appointment['id']]) ? 'class="approved-row"' : ''; ?>>
                                                <td><?php echo $appointment['id']; ?></td>
                                                <td><?php echo $appointment['name']; ?></td>
                                                <td><?php echo $appointment['email']; ?></td>
                                                <td><?php echo $appointment['course_type']; ?></td>
                                                <td><?php echo $appointment['car_type']; ?></td>
                                                <td><?php echo $appointment['message']; ?></td>
                                                <td><?php echo date('M d, Y H:i', strtotime($appointment['date_created'])); ?></td>
                                                <td>
                                                    <?php if(isset($_SESSION['approved_appointments'][$appointment['id']])): ?>
                                                        <span class="badge badge-success">Approved</span>
                                                    <?php else: ?>
                                                        <span class="badge badge-warning">Pending</span>
                                                    <?php endif; ?>
                                                </td>
                                                <td class="actions">
                                                    <?php if(!isset($_SESSION['approved_appointments'][$appointment['id']])): ?>
                                                        <form method="post" class="d-inline-block">
                                                            <input type="hidden" name="appointment_id" value="<?php echo $appointment['id']; ?>">
                                                            <input type="hidden" name="action" value="approve">
                                                            <button type="submit" class="btn btn-sm btn-success" title="Approve Appointment">
                                                                <i class="fas fa-check"></i>
                                                            </button>
                                                        </form>
                                                    <?php endif; ?>
                                                    <form method="post" class="d-inline-block">
                                                        <input type="hidden" name="appointment_id" value="<?php echo $appointment['id']; ?>">
                                                        <input type="hidden" name="action" value="delete">
                                                        <button type="submit" class="btn btn-sm btn-danger" title="Delete Appointment" onclick="return confirm('Are you sure you want to delete this appointment?');">
                                                            <i class="fas fa-trash"></i>
                                                        </button>
                                                    </form>
                                                </td>
                                            </tr>
                                        <?php endforeach; ?>
                                    </tbody>
                                </table>
                            </div>
                        <?php else: ?>
                            <div class="no-appointments">
                                <i class="far fa-calendar-times"></i>
                                <p>No appointments found.</p>
                            </div>
                        <?php endif; ?>
                    </div>
                </div>
            </div>
        </div>
    </main>

    <!-- Footer -->
    <footer class="admin-footer">
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