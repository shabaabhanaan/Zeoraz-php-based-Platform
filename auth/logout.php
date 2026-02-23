<?php
// auth/logout.php
require_once '../core/config.php';
session_start();
session_destroy();
header("Location: " . BASE_URL . "index.php");
exit();
?>
