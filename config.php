<?php
// Prevent direct access
if (!defined('DB_ACCESS')) {
    define('DB_ACCESS', true);
}

// Database configuration
define('DB_SERVER', 'localhost');
define('DB_USERNAME', 'root');
define('DB_PASSWORD', '');
define('DB_NAME', 'hotel_reservation');

// Create connection with error handling
$conn = @mysqli_connect(DB_SERVER, DB_USERNAME, DB_PASSWORD, DB_NAME);

// Check connection
if ($conn === false) {
    error_log("Database Connection Error: " . mysqli_connect_error());
    die("ERROR: Could not connect to database. Please contact administrator.");
}

// Set charset to UTF-8 to prevent encoding issues
if (!mysqli_set_charset($conn, "utf8")) {
    error_log("Error loading character set utf8: " . mysqli_error($conn));
    die("ERROR: Character set configuration failed.");
}

// Set timezone (adjust to your timezone)
date_default_timezone_set('Asia/Jakarta');

// Function to safely close connection
function closeConnection() {
    global $conn;
    if ($conn) {
        mysqli_close($conn);
    }
}

// Register shutdown function
register_shutdown_function('closeConnection');
?>
