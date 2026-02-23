<?php
// order_process.php - Transaction Logic
require_once 'includes/db.php';
require_once 'includes/utils.php';

if (!is_logged_in() || $_SERVER['REQUEST_METHOD'] !== 'POST') {
    redirect('index.php');
}

$cart = $_SESSION['cart'] ?? [];
if (empty($cart)) redirect('cart.php');

$userId = $_SESSION['user_id'];
$address = $_POST['address'] ?? '';
$paymentMethod = $_POST['payment_method'] ?? 'CASH';

try {
    $pdo->beginTransaction();

    // 1. Calculate Total and Verify Stock
    $totalAmount = 0;
    $ids = array_keys($cart);
    $placeholders = str_repeat('?,', count($ids) - 1) . '?';
    $stmt = $pdo->prepare("SELECT * FROM products WHERE id IN ($placeholders) FOR UPDATE");
    $stmt->execute($ids);
    $products = $stmt->fetchAll();

    foreach ($products as $p) {
        $qty = $cart[$p['id']];
        if ($p['stock'] < $qty) {
            throw new Exception("Product '{$p['name']}' is out of stock.");
        }
        $totalAmount += $p['price'] * $qty;
    }

    // 2. Create Order
    $orderId = generate_id();
    $orderStmt = $pdo->prepare("INSERT INTO orders (id, userId, totalAmount, status, paymentStatus, shippingAddress) VALUES (?, ?, ?, 'PENDING', ?, ?)");
    $paymentStatus = ($paymentMethod === 'CARD') ? 'PAID' : 'PENDING';
    $orderStmt->execute([$orderId, $userId, $totalAmount, $paymentStatus, $address]);

    // 3. Create Order Items and Deduct Stock
    $itemStmt = $pdo->prepare("INSERT INTO order_items (id, orderId, productId, quantity, price) VALUES (?, ?, ?, ?, ?)");
    $stockStmt = $pdo->prepare("UPDATE products SET stock = stock - ? WHERE id = ?");

    foreach ($products as $p) {
        $qty = $cart[$p['id']];
        $itemStmt->execute([generate_id(), $orderId, $p['id'], $qty, $p['price']]);
        $stockStmt->execute([$qty, $p['id']]);
    }

    // 4. Commit and Clear Cart
    $pdo->commit();
    $_SESSION['cart'] = [];
    
    // Redirect to success page
    $_SESSION['last_order_id'] = $orderId;
    redirect('order_success.php');

} catch (Exception $e) {
    $pdo->rollBack();
    die("Order failed: " . $e->getMessage() . " <a href='cart.php'>Go back to cart</a>");
}
?>
