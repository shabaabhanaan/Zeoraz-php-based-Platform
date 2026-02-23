<?php
// cart_handler.php
session_start();
require_once 'includes/db.php';

header('Content-Type: application/json');

$action = $_POST['action'] ?? '';
$productId = $_POST['product_id'] ?? '';
$quantity = intval($_POST['quantity'] ?? 1);

if (!isset($_SESSION['cart'])) {
    $_SESSION['cart'] = [];
}

try {
    switch ($action) {
        case 'add':
            // Check if product exists and is in stock
            $stmt = $pdo->prepare("SELECT * FROM products WHERE id = ? AND status = 'ACTIVE'");
            $stmt->execute([$productId]);
            $product = $stmt->fetch();
            
            if ($product) {
                if (isset($_SESSION['cart'][$productId])) {
                    $_SESSION['cart'][$productId] += $quantity;
                } else {
                    $_SESSION['cart'][$productId] = $quantity;
                }
                echo json_encode(['success' => true, 'message' => 'Product added to cart', 'cart_count' => count($_SESSION['cart'])]);
            } else {
                echo json_encode(['success' => false, 'message' => 'Product not found or out of stock']);
            }
            break;

        case 'update':
            if (isset($_SESSION['cart'][$productId])) {
                if ($quantity <= 0) {
                    unset($_SESSION['cart'][$productId]);
                } else {
                    $_SESSION['cart'][$productId] = $quantity;
                }
                echo json_encode(['success' => true, 'message' => 'Cart updated']);
            }
            break;

        case 'remove':
            if (isset($_SESSION['cart'][$productId])) {
                unset($_SESSION['cart'][$productId]);
                echo json_encode(['success' => true, 'message' => 'Item removed']);
            }
            break;

        case 'clear':
            $_SESSION['cart'] = [];
            echo json_encode(['success' => true, 'message' => 'Cart cleared']);
            break;

        default:
            echo json_encode(['success' => false, 'message' => 'Invalid action']);
    }
} catch (Exception $e) {
    echo json_encode(['success' => false, 'message' => $e->getMessage()]);
}
?>
