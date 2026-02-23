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
    
    // --- EMAIL NOTIFICATIONS ---
    try {
        // 5. Notify Buyer
        $buyerEmail = $_SESSION['user_email'] ?? '';
        if (!$buyerEmail) {
            $uStmt = $pdo->prepare("SELECT email FROM users WHERE id = ?");
            $uStmt->execute([$userId]);
            if ($u = $uStmt->fetch()) $buyerEmail = $u['email'];
        }

        if ($buyerEmail) {
            $subject = "Order Confirmed - #" . substr($orderId, 0, 8);
            $body = "<h2>Thank you for your order!</h2>
                     <p>Your order <strong>#$orderId</strong> has been placed successfully.</p>
                     <p>Total Amount: <strong>$" . number_format($totalAmount, 2) . "</strong></p>
                     <p>We'll notify you once your items are shipped.</p>";
            sendMail($buyerEmail, $subject, $body);
        }

        // 6. Notify Sellers and Check Low Stock
        $sellerData = [];
        foreach ($products as $p) {
            if (!isset($sellerData[$p['sellerId']])) {
                $sStmt = $pdo->prepare("SELECT name, email FROM users WHERE id = ?");
                $sStmt->execute([$p['sellerId']]);
                $sellerData[$p['sellerId']] = $sStmt->fetch();
            }
            
            $seller = $sellerData[$p['sellerId']];
            if ($seller) {
                // New Order Notification
                $sSubject = "New Order Received - Zeoraz";
                $sBody = "<h3>Hello " . htmlspecialchars($seller['name']) . ",</h3>
                          <p>You have received a new order for <strong>" . htmlspecialchars($p['name']) . "</strong>.</p>
                          <p>Quantity: " . $cart[$p['id']] . "</p>
                          <p>Please check your dashboard for details.</p>";
                sendMail($seller['email'], $sSubject, $sBody);

                // Low Stock Alert
                $newStock = $p['stock'] - $cart[$p['id']];
                if ($newStock < 5) {
                    $lsSubject = "Low Stock Alert: " . htmlspecialchars($p['name']);
                    $lsBody = "<h3>Stock Warning</h3>
                               <p>Your product <strong>" . htmlspecialchars($p['name']) . "</strong> is running low on stock.</p>
                               <p>Current stock: <strong>$newStock</strong></p>
                               <p>Please restock soon to avoid missing out on sales.</p>";
                    sendMail($seller['email'], $lsSubject, $lsBody);
                }
            }
        }
    } catch (Exception $mailEx) {
        // Mailer fail should not block the order success page
    }

    // Redirect to success page
    $_SESSION['last_order_id'] = $orderId;
    redirect('order_success.php');

} catch (Exception $e) {
    $pdo->rollBack();
    die("Order failed: " . $e->getMessage() . " <a href='cart.php'>Go back to cart</a>");
}
?>
