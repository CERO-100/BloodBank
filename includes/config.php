<?php
// config.php

// Database credentials
define('DB_HOST', 'localhost');      // Usually 'localhost'
define('DB_USER', 'root');           // Your DB username
define('DB_PASS', '');               // Your DB password (default for XAMPP is empty)
define('DB_NAME', 'blood');          // Your database name

// Establish database connection
$conn = new mysqli(DB_HOST, DB_USER, DB_PASS, DB_NAME);

// Check connection
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

// Start session if not already started
if (session_status() == PHP_SESSION_NONE) {
    session_start();
}

// Optional: define global constants for roles
define('ROLE_USER', 'user');
define('ROLE_HOSPITAL', 'hospital');
define('ROLE_ADMIN', 'admin');

// Optional: site base URL
define('BASE_URL', 'http://localhost/BloodBank/');  // Change if hosted elsewhere

?>
