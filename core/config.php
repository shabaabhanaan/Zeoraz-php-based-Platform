<?php
require __DIR__ . '/../vendor/autoload.php';

$dotenv = Dotenv\Dotenv::createImmutable(__DIR__ . '/../');
$dotenv->load();

if (!defined('BASE_URL')) {
    define('BASE_URL', 'http://localhost:8888/');
}