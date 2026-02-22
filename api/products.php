<?php
// api/products.php
require_once '../includes/db.php';
require_once '../includes/utils.php';

header('Content-Type: application/json');

$method = $_SERVER['REQUEST_METHOD'];

if ($method === 'GET') {
    try {
        $stmt = $pdo->query("SELECT * FROM products WHERE status = 'ACTIVE' ORDER BY createdAt DESC");
        $products = $stmt->fetchAll();
        echo json_encode(['success' => true, 'data' => $products]);
    } catch (PDOException $e) {
        http_response_code(500);
        echo json_encode(['success' => false, 'error' => $e->getMessage()]);
    }
} elseif ($method === 'POST') {
    if (!is_logged_in() || get_user_role() !== 'SELLER') {
        http_response_code(403);
        echo json_encode(['success' => false, 'error' => 'Unauthorized']);
        exit;
    }

    $data = json_decode(file_get_contents('php://input'), true);
    
    $name = $data['name'] ?? '';
    $price = $data['price'] ?? 0;
    $description = $data['description'] ?? '';
    $stock = $data['stock'] ?? 0;
    $category = $data['category'] ?? '';
    $image = $data['image'] ?? '';
    $sellerId = $_SESSION['user_id'];

    if (empty($name) || empty($price)) {
        http_response_code(400);
        echo json_encode(['success' => false, 'error' => 'Name and price are required']);
        exit;
    }

    try {
        $id = generate_id();
        $stmt = $pdo->prepare("INSERT INTO products (id, name, description, price, stock, image, category, sellerId, status) VALUES (?, ?, ?, ?, ?, ?, ?, ?, 'ACTIVE')");
        $stmt->execute([$id, $name, $description, $price, $stock, $image, $category, $sellerId]);
        
        echo json_encode(['success' => true, 'message' => 'Product created', 'id' => $id]);
    } catch (PDOException $e) {
        http_response_code(500);
        echo json_encode(['success' => false, 'error' => $e->getMessage()]);
    }
} else {
    http_response_code(405);
    echo json_encode(['success' => false, 'error' => 'Method not allowed']);
}
?>
