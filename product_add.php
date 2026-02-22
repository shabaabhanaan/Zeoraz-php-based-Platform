<?php
// product_add.php
require_once 'includes/db.php';
require_once 'includes/utils.php';

if (!is_logged_in() || get_user_role() !== 'SELLER') {
    redirect('auth/login.php');
}

$error = '';
$success = '';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $name = $_POST['name'] ?? '';
    $price = $_POST['price'] ?? 0;
    $description = $_POST['description'] ?? '';
    $stock = $_POST['stock'] ?? 0;
    $category = $_POST['category'] ?? '';
    $sellerId = $_SESSION['user_id'];

    if (empty($name) || empty($price)) {
        $error = 'Name and price are required.';
    } else {
        try {
            $id = generate_id();
            $stmt = $pdo->prepare("INSERT INTO products (id, name, description, price, stock, category, sellerId, status) VALUES (?, ?, ?, ?, ?, ?, ?, 'ACTIVE')");
            $stmt->execute([$id, $name, $description, $price, $stock, $category, $sellerId]);
            $success = 'Product added successfully!';
        } catch (PDOException $e) {
            $error = 'Failed to add product: ' . $e->getMessage();
        }
    }
}

require_once 'includes/header.php';
?>

<div class="max-w-2xl mx-auto">
    <a href="dashboard.php" class="text-white/50 hover:text-white mb-6 flex items-center gap-2 transition">
        <svg xmlns="http://www.w3.org/2000/svg" width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="m15 18-6-6 6-6"/></svg>
        Back to Dashboard
    </a>
    
    <div class="glass border border-white/10 p-8 rounded-3xl space-y-8">
        <h1 class="text-3xl font-bold">List New Product</h1>
        
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

        <form method="POST" class="space-y-6">
            <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                <div class="space-y-2">
                    <label class="block text-sm font-medium">Product Name</label>
                    <input type="text" name="name" required class="w-full bg-white/5 border border-white/10 rounded-xl px-4 py-3 focus:outline-none focus:border-cyan-500">
                </div>
                <div class="space-y-2">
                    <label class="block text-sm font-medium">Category</label>
                    <select name="category" class="w-full bg-white/5 border border-white/10 rounded-xl px-4 py-3 focus:outline-none focus:border-cyan-500 text-white">
                        <option value="Electronics" class="bg-slate-900">Electronics</option>
                        <option value="Fashion" class="bg-slate-900">Fashion</option>
                        <option value="Home" class="bg-slate-900">Home</option>
                        <option value="Digital" class="bg-slate-900">Digital</option>
                    </select>
                </div>
            </div>

            <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                <div class="space-y-2">
                    <label class="block text-sm font-medium">Price ($)</label>
                    <input type="number" step="0.01" name="price" required class="w-full bg-white/5 border border-white/10 rounded-xl px-4 py-3 focus:outline-none focus:border-cyan-500">
                </div>
                <div class="space-y-2">
                    <label class="block text-sm font-medium">Stock Quantity</label>
                    <input type="number" name="stock" value="0" class="w-full bg-white/5 border border-white/10 rounded-xl px-4 py-3 focus:outline-none focus:border-cyan-500">
                </div>
            </div>

            <div class="space-y-2">
                <label class="block text-sm font-medium">Description</label>
                <textarea name="description" rows="4" class="w-full bg-white/5 border border-white/10 rounded-xl px-4 py-3 focus:outline-none focus:border-cyan-500"></textarea>
            </div>

            <button type="submit" class="w-full bg-cyan-500 text-black font-bold py-4 rounded-xl hover:bg-cyan-400 transition">
                Create Product
            </button>
        </form>
    </div>
</div>

<?php require_once 'includes/footer.php'; ?>
