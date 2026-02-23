<?php
// product_delete.php
require_once 'includes/db.php';
require_once 'includes/utils.php';

if (!is_logged_in() || (get_user_role() !== 'SELLER' && get_user_role() !== 'ADMIN')) {
    die("Unauthorized");
}

$id = $_GET['id'] ?? null;

if ($id) {
    try {
        // Fetch to check ownership and get image path
        $stmt = $pdo->prepare("SELECT sellerId, image FROM products WHERE id = ?");
        $stmt->execute([$id]);
        $product = $stmt->fetch();

        if ($product) {
            if (get_user_role() !== 'ADMIN' && $product['sellerId'] !== $_SESSION['user_id']) {
                die("Unauthorized: You do not own this product.");
            }

            // Delete image file if exists
            if ($product['image'] && file_exists($product['image'])) {
                unlink($product['image']);
            }

            // Delete database record
            $stmt = $pdo->prepare("DELETE FROM products WHERE id = ?");
            $stmt->execute([$id]);
        }
    } catch (PDOException $e) {
        // Log error silently or display
    }
}

redirect('dashboard.php');
?>
