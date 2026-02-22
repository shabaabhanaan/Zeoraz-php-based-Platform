<?php
// order_process.php
require_once 'includes/db.php';
require_once 'includes/utils.php';

if (!is_logged_in() || $_SERVER['REQUEST_METHOD'] !== 'POST') {
    redirect('index.php');
}

$userId = $_SESSION['user_id'];
$address = $_POST['address'] ?? '';
$phone = $_POST['phone'] ?? '';
$cart = $_SESSION['cart'] ?? [];

if (empty($cart) || empty($address)) {
    redirect('cart.php');
}

try {
    $pdo->beginTransaction();

    // 1. Calculate total and verify products
    $ids = array_keys($cart);
    $placeholders = str_repeat('?,', count($ids) - 1) . '?';
    $stmt = $pdo->prepare("SELECT * FROM products WHERE id IN ($placeholders)");
    $stmt->execute($ids);
    $products = $stmt->fetchAll();

    $total = 0;
    foreach ($products as $p) {
        $total += $p['price'] * $cart[$p['id']];
    }

    // 2. Create Order
    $orderId = generate_id();
    $stmt = $pdo->prepare("INSERT INTO orders (id, userId, totalAmount, status, paymentStatus, shippingAddress) VALUES (?, ?, ?, 'PENDING', 'PENDING', ?)");
    $stmt->execute([$orderId, $userId, $total, $address . " | Phone: " . $phone]);

    // 3. Create Order Items
    $stmtItem = $pdo->prepare("INSERT INTO order_items (id, orderId, productId, quantity, price) VALUES (?, ?, ?, ?, ?)");
    foreach ($products as $p) {
        $stmtItem->execute([
            generate_id(),
            $orderId,
            $p['id'],
            $cart[$p['id']],
            $p['price']
        ]);
        
        // 4. Update Stock
        $updateStock = $pdo->prepare("UPDATE products SET stock = stock - ? WHERE id = ?");
        $updateStock->execute([$cart[$p['id']], $p['id']]);
    }

    $pdo->commit();
    
    // Clear cart
    unset($_SESSION['cart']);
    
    redirect("order_success.php?id=$orderId");

} catch (Exception $e) {
    $pdo->rollBack();
    die("Order failed: " . $e->getMessage());
}
?>
