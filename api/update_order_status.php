<?php
// api/update_order_status.php
require_once '../core/db.php';
require_once '../core/utils.php';

if (!is_logged_in() || (get_user_role() !== 'SELLER' && get_user_role() !== 'ADMIN')) {
    echo json_encode(['success' => false, 'message' => 'Unauthorized']);
    exit;
}

$orderId = $_POST['order_id'] ?? null;
$newStatus = $_POST['status'] ?? null;

if (!$orderId || !$newStatus) {
    echo json_encode(['success' => false, 'message' => 'Missing data']);
    exit;
}

try {
    // Update status
    $stmt = $pdo->prepare("UPDATE orders SET status = ? WHERE id = ?");
    $stmt->execute([$newStatus, $orderId]);

    // Fetch buyer info to notify
    $stmt = $pdo->prepare("SELECT u.email, u.name, o.id FROM orders o JOIN users u ON o.userId = u.id WHERE o.id = ?");
    $stmt->execute([$orderId]);
    $order = $stmt->fetch();

    if ($order) {
        $subject = "Order Status Update - Zeoraz";
        $body = "<h3>Hello " . htmlspecialchars($order['name']) . ",</h3>
                 <p>Your order <strong>#" . substr($order['id'], 0, 8) . "...</strong> status has been updated to:</p>
                 <h2 style='color:#22d3ee;'>" . htmlspecialchars($newStatus) . "</h2>
                 <p>Log in to your dashboard for more details.</p>";
        sendMail($order['email'], $subject, $body);
    }

    echo json_encode(['success' => true]);
} catch (PDOException $e) {
    echo json_encode(['success' => false, 'message' => 'Database error']);
}
?>
