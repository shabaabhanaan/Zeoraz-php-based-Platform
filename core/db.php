<?php
// includes/db.php
require_once __DIR__ . '/config.php';

$host = '127.0.0.1';
$dbname = 'multi_vendor_market';
$user = 'root';
$pass = '1234';
$port = '3307';

try {
    $dsn = "mysql:host=$host;port=$port;dbname=$dbname;charset=utf8mb4";
    $pdo = new PDO($dsn, $user, $pass);
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
    $pdo->setAttribute(PDO::ATTR_DEFAULT_FETCH_MODE, PDO::FETCH_ASSOC);
} catch (PDOException $e) {
    // If connection fails, we catch it and print error
    error_log("DB Connection Error: " . $e->getMessage());
    die("Database connection failed: " . $e->getMessage());
}

if (session_status() === PHP_SESSION_NONE) {
    session_start();
}
?>
