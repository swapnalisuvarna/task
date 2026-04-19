<?php
// ============================================
// config/db.php - Database Connection
// ============================================

// Database credentials - change these to match your setup
define('DB_HOST', 'localhost');    // Usually 'localhost' for XAMPP/WAMP
define('DB_USER', 'root');         // Your MySQL username (default: root)
define('DB_PASS', '');             // Your MySQL password (default: empty for XAMPP)
define('DB_NAME', 'task_manage'); // Database name we created

// Create connection using mysqli
$conn = mysqli_connect(DB_HOST, DB_USER, DB_PASS, DB_NAME);

// Check if connection was successful
if (!$conn) {
    // If connection fails, stop and show error
    die("<div style='font-family:sans-serif;padding:20px;color:red;'>
        <h2>❌ Database Connection Failed</h2>
        <p>" . mysqli_connect_error() . "</p>
        <p>Please check your database credentials in <strong>config/db.php</strong></p>
    </div>");
}


mysqli_set_charset($conn, "utf8");
?>
