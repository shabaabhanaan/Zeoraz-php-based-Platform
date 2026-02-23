<?php
// product_manager.php - Unified Add/Edit
require_once 'includes/db.php';
require_once 'includes/utils.php';

if (!is_logged_in() || (get_user_role() !== 'SELLER' && get_user_role() !== 'ADMIN')) {
    redirect('auth/login.php');
}

$error = '';
$success = '';
$productId = $_GET['id'] ?? null;
$product = null;

if ($productId) {
    $stmt = $pdo->prepare("SELECT * FROM products WHERE id = ?");
    $stmt->execute([$productId]);
    $product = $stmt->fetch();
    
    // Check ownership if not admin
    if ($product && get_user_role() !== 'ADMIN' && $product['sellerId'] !== $_SESSION['user_id']) {
        redirect('dashboard.php');
    }
}

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $name = $_POST['name'] ?? '';
    $price = $_POST['price'] ?? 0;
    $description = $_POST['description'] ?? '';
    $stock = $_POST['stock'] ?? 0;
    $category = $_POST['category'] ?? '';
    $status = $_POST['status'] ?? 'ACTIVE';
    $sellerId = $product ? $product['sellerId'] : $_SESSION['user_id'];
    
    $imageUrl = $product['image'] ?? '';

    // Image Upload Logic
    if (isset($_FILES['image']) && $_FILES['image']['error'] === UPLOAD_ERR_OK) {
        $uploadDir = 'uploads/products/';
        if (!is_dir($uploadDir)) mkdir($uploadDir, 0777, true);
        
        $ext = pathinfo($_FILES['image']['name'], PATHINFO_EXTENSION);
        $fileName = generate_id() . '.' . $ext;
        $uploadFile = $uploadDir . $fileName;
        
        if (move_uploaded_file($_FILES['image']['tmp_name'], $uploadFile)) {
            $imageUrl = $uploadFile;
        } else {
            $error = "Failed to upload image.";
        }
    }

    if (!$error) {
        if (empty($name) || empty($price)) {
            $error = 'Name and price are required.';
        } else {
            try {
                if ($product) {
                    // Update
                    $stmt = $pdo->prepare("UPDATE products SET name = ?, description = ?, price = ?, stock = ?, category = ?, image = ?, status = ? WHERE id = ?");
                    $stmt->execute([$name, $description, $price, $stock, $category, $imageUrl, $status, $productId]);
                    $success = 'Product updated successfully!';
                    // Refresh product data
                    $stmt = $pdo->prepare("SELECT * FROM products WHERE id = ?");
                    $stmt->execute([$productId]);
                    $product = $stmt->fetch();
                } else {
                    // Add
                    $id = generate_id();
                    $stmt = $pdo->prepare("INSERT INTO products (id, name, description, price, stock, category, sellerId, image, status) VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?)");
                    $stmt->execute([$id, $name, $description, $price, $stock, $category, $sellerId, $imageUrl, $status]);
                    $success = 'Product added successfully!';
                    
                    // If added, redirect to edit page with success or back to dashboard
                    // redirect('dashboard.php');
                }
            } catch (PDOException $e) {
                $error = 'Database error: ' . $e->getMessage();
            }
        }
    }
}

require_once 'includes/header.php';
?>

<div class="max-w-4xl mx-auto">
    <div class="flex items-center justify-between mb-8">
        <a href="dashboard.php" class="text-white/50 hover:text-white flex items-center gap-2 transition">
            <svg xmlns="http://www.w3.org/2000/svg" width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="m15 18-6-6 6-6"/></svg>
            Back to Dashboard
        </a>
        <h1 class="text-3xl font-bold"><?php echo $product ? 'Edit Product' : 'Add New Product'; ?></h1>
    </div>
    
    <div class="glass border border-white/10 p-8 rounded-3xl space-y-8">
        <?php if($error): ?>
            <div class="bg-red-500/20 text-red-400 p-4 rounded-xl border border-red-500/30 text-sm">
                <?php echo htmlspecialchars($error); ?>
            </div>
        <?php endif; ?>

        <?php if($success): ?>
            <div class="bg-green-500/20 text-green-400 p-4 rounded-xl border border-green-500/30 text-sm">
                <?php echo htmlspecialchars($success); ?>
            </div>
        <?php endif; ?>

        <form method="POST" enctype="multipart/form-data" class="space-y-6">
            <div class="grid grid-cols-1 md:grid-cols-2 gap-8">
                <!-- Left Column: Details -->
                <div class="space-y-6">
                    <div class="space-y-2">
                        <label class="block text-sm font-medium text-white/70">Product Name</label>
                        <input type="text" name="name" required value="<?php echo htmlspecialchars($product['name'] ?? ''); ?>" 
                            class="w-full bg-white/5 border border-white/10 rounded-xl px-4 py-3 focus:outline-none focus:border-cyan-500 transition">
                    </div>

                    <div class="grid grid-cols-2 gap-4">
                        <div class="space-y-2">
                            <label class="block text-sm font-medium text-white/70">Price ($)</label>
                            <input type="number" step="0.01" name="price" required value="<?php echo htmlspecialchars($product['price'] ?? ''); ?>" 
                                class="w-full bg-white/5 border border-white/10 rounded-xl px-4 py-3 focus:outline-none focus:border-cyan-500 transition">
                        </div>
                        <div class="space-y-2">
                            <label class="block text-sm font-medium text-white/70">Stock</label>
                            <input type="number" name="stock" required value="<?php echo htmlspecialchars($product['stock'] ?? '0'); ?>" 
                                class="w-full bg-white/5 border border-white/10 rounded-xl px-4 py-3 focus:outline-none focus:border-cyan-500 transition">
                        </div>
                    </div>

                    <div class="space-y-2">
                        <label class="block text-sm font-medium text-white/70">Category</label>
                        <select name="category" class="w-full bg-white/5 border border-white/10 rounded-xl px-4 py-3 focus:outline-none focus:border-cyan-500 text-white transition appearance-none">
                            <?php 
                            $categories = ['Electronics', 'Fashion', 'Home', 'Digital', 'Other'];
                            foreach($categories as $cat): 
                                $selected = (isset($product['category']) && $product['category'] === $cat) ? 'selected' : '';
                            ?>
                                <option value="<?php echo $cat; ?>" <?php echo $selected; ?> class="bg-slate-900"><?php echo $cat; ?></option>
                            <?php endforeach; ?>
                        </select>
                    </div>

                    <div class="space-y-2">
                        <label class="block text-sm font-medium text-white/70">Status</label>
                        <select name="status" class="w-full bg-white/5 border border-white/10 rounded-xl px-4 py-3 focus:outline-none focus:border-cyan-500 text-white transition appearance-none">
                            <option value="ACTIVE" <?php echo (isset($product['status']) && $product['status'] === 'ACTIVE') ? 'selected' : ''; ?> class="bg-slate-900">Active (In Stock)</option>
                            <option value="DRAFT" <?php echo (isset($product['status']) && $product['status'] === 'DRAFT') ? 'selected' : ''; ?> class="bg-slate-900">Draft (Private)</option>
                            <option value="OUT_OF_STOCK" <?php echo (isset($product['status']) && $product['status'] === 'OUT_OF_STOCK') ? 'selected' : ''; ?> class="bg-slate-900">Out of Stock</option>
                        </select>
                    </div>
                </div>

                <!-- Right Column: Image & Description -->
                <div class="space-y-6">
                    <div class="space-y-2">
                        <label class="block text-sm font-medium text-white/70">Product Image</label>
                        <div class="relative group">
                            <div class="aspect-square bg-white/5 border-2 border-dashed border-white/10 rounded-2xl flex items-center justify-center overflow-hidden transition group-hover:border-cyan-500/50">
                                <?php if($product && $product['image']): ?>
                                    <img src="<?php echo htmlspecialchars($product['image']); ?>" class="w-full h-full object-cover">
                                <?php else: ?>
                                    <div class="text-center space-y-2">
                                        <svg xmlns="http://www.w3.org/2000/svg" width="40" height="40" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round" class="mx-auto text-white/20"><rect width="18" height="18" x="3" y="3" rx="2" ry="2"/><circle cx="9" cy="9" r="2"/><path d="m21 15-3.086-3.086a2 2 0 0 0-2.828 0L6 21"/></svg>
                                        <span class="text-xs text-white/30">Upload JPEG/PNG</span>
                                    </div>
                                <?php endif; ?>
                            </div>
                            <input type="file" name="image" class="absolute inset-0 opacity-0 cursor-pointer">
                        </div>
                        <p class="text-[10px] text-white/40 italic mt-1">Leave empty to keep current image</p>
                    </div>

                    <div class="space-y-2">
                        <label class="block text-sm font-medium text-white/70">Description</label>
                        <textarea name="description" rows="5" class="w-full bg-white/5 border border-white/10 rounded-xl px-4 py-3 focus:outline-none focus:border-cyan-500 transition"><?php echo htmlspecialchars($product['description'] ?? ''); ?></textarea>
                    </div>
                </div>
            </div>

            <button type="submit" class="w-full bg-cyan-500 text-black font-black py-4 rounded-2xl hover:bg-cyan-400 transition shadow-lg shadow-cyan-500/20 active:scale-[0.98]">
                <?php echo $product ? 'Update Product Information' : 'List Product on Market'; ?>
            </button>
        </form>
    </div>
</div>

<?php require_once 'includes/footer.php'; ?>
