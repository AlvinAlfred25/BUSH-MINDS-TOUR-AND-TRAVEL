<?php
// ── DATABASE CONNECTION ──
// Bush Minds Tours & Travel
// Make sure XAMPP MySQL is running before testing

$host     = 'localhost';
$dbname   = 'bushminds_db';
$username = 'root';       // default XAMPP username
$password = '';           // default XAMPP password (empty)

try {
    $pdo = new PDO("mysql:host=$host;dbname=$dbname;charset=utf8", $username, $password);
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
    $pdo->setAttribute(PDO::ATTR_DEFAULT_FETCH_MODE, PDO::FETCH_ASSOC);
} catch (PDOException $e) {
    die(json_encode([
        'status'  => 'error',
        'message' => 'Database connection failed: ' . $e->getMessage()
    ]));
}
?>
