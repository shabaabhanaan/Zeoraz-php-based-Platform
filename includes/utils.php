<?php
// includes/utils.php

function generate_id() {
    return bin2hex(random_bytes(16)); // Simple 32-char hex ID
}

function redirect($url) {
    header("Location: $url");
    exit();
}

function is_logged_in() {
    return isset($_SESSION['user_id']);
}

function get_user_role() {
    return $_SESSION['user_role'] ?? 'USER';
}
?>
