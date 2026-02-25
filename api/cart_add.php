<?php
// cart_add.php
require_once '../core/db.php';
require_once '../core/utils.php';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $productId = $_POST['product_id'] ?? '';
    $quantity = (int)($_POST['quantity'] ?? 1);

    if (!$productId) {
        redirect(BASE_URL . 'index.php');
    }

    // Initialize cart if not exists
    if (!isset($_SESSION['cart'])) {
        $_SESSION['cart'] = [];
    }

    // Add or update quantity
    if (isset($_SESSION['cart'][$productId])) {
        $_SESSION['cart'][$productId] += $quantity;
    } else {
        $_SESSION['cart'][$productId] = $quantity;
    }

    redirect(BASE_URL . 'pages/cart.php');
} else {
    redirect(BASE_URL . 'index.php');
}
?>
