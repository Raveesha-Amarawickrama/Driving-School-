<?php
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

// Site details
define('SITE_NAME', 'Drivin');
define('SITE_URL', 'http://localhost/drivin');
define('ADMIN_EMAIL', 'admin@example.com');

// Database Configuration
define('DB_HOST', 'localhost');
define('DB_USER', 'root');
define('DB_PASS', '');
define('DB_NAME', 'drivin_db');

// Error Reporting
error_reporting(E_ALL);
ini_set('display_errors', 1);



