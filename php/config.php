<?php
// ============================================================
//  BUSH MINDS — DATABASE CONNECTION
// ============================================================

define('DB_HOST', 'localhost');
define('DB_USER', 'root');       // XAMPP default
define('DB_PASS', '');           // XAMPP default (empty)
define('DB_NAME', 'bushminds_db');

function getConnection() {
    $conn = new mysqli(DB_HOST, DB_USER, DB_PASS, DB_NAME);
    if ($conn->connect_error) {
        die(json_encode([
            'success' => false,
            'message' => 'Database connection failed. Please try again later.'
        ]));
    }
    $conn->set_charset('utf8mb4');
    return $conn;
}
?>
