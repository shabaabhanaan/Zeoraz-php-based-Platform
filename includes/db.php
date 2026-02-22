<?php
// includes/db.php
$host = '127.0.0.1'; // Use literal IP for better compatibility with ports
$dbname = 'multi_vendor_market';
$user = 'root';
$pass = '1234'; // Password found in config.inc.php
$port = '3307'; // Port found in config.inc.php

try {
    $dsn = "mysql:host=$host;port=$port;dbname=$dbname;charset=utf8mb4";
    $pdo = new PDO($dsn, $user, $pass);
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
    $pdo->setAttribute(PDO::ATTR_DEFAULT_FETCH_MODE, PDO::FETCH_ASSOC);
} catch (PDOException $e) {
    die("Database connection failed: " . $e->getMessage());
}

// Start session if not already started
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}
?>
