<?php
// product_details.php
require_once 'includes/db.php';
require_once 'includes/utils.php';

$id = $_GET['id'] ?? '';

if (!$id) {
    redirect('index.php');
}

try {
    $stmt = $pdo->prepare("SELECT p.*, u.name as sellerName FROM products p JOIN users u ON p.sellerId = u.id WHERE p.id = ?");
    $stmt->execute([$id]);
    $product = $stmt->fetch();

    if (!$product) {
        die("Product not found.");
    }
} catch (PDOException $e) {
    die("Error: " . $e->getMessage());
}

require_once 'includes/header.php';
?>

<div class="grid grid-cols-1 lg:grid-cols-2 gap-12">
    <!-- Image Section -->
    <div class="glass border border-white/10 rounded-3xl overflow-hidden aspect-square">
        <?php if($product['image']): ?>
            <img src="<?php echo htmlspecialchars($product['image']); ?>" class="w-full h-full object-cover">
        <?php else: ?>
            <div class="w-full h-full flex items-center justify-center text-white/5">
                <svg xmlns="http://www.w3.org/2000/svg" width="100" height="100" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1" stroke-linecap="round" stroke-linejoin="round"><rect width="18" height="18" x="3" y="3" rx="2" ry="2"/><circle cx="9" cy="9" r="2"/><path d="m21 15-3.086-3.086a2 2 0 0 0-2.828 0L6 21"/></svg>
            </div>
        <?php endif; ?>
    </div>

    <!-- Info Section -->
    <div class="space-y-8">
        <div>
            <span class="text-cyan-400 font-bold tracking-widest uppercase text-sm"><?php echo htmlspecialchars($product['category'] ?? 'General'); ?></span>
            <h1 class="text-5xl font-black mt-2"><?php echo htmlspecialchars($product['name']); ?></h1>
            <p class="text-white/40 mt-4 flex items-center gap-2">
                Sold by <span class="text-white font-medium italic underline underline-offset-4 decoration-cyan-500/50"><?php echo htmlspecialchars($product['sellerName']); ?></span>
            </p>
        </div>

        <div class="text-4xl font-black text-cyan-400">
            $<?php echo number_format($product['price'], 2); ?>
        </div>

        <div class="prose prose-invert max-w-none text-white/70 text-lg leading-relaxed">
            <?php echo nl2br(htmlspecialchars($product['description'])); ?>
        </div>

        <div class="space-y-4 pt-6">
            <div class="flex items-center gap-4">
                <span class="text-white/50">Availability:</span>
                <span class="font-bold <?php echo $product['stock'] > 0 ? 'text-green-400' : 'text-red-400'; ?>">
                    <?php echo $product['stock'] > 0 ? $product['stock'] . ' in stock' : 'Out of stock'; ?>
                </span>
            </div>

            <?php if($product['stock'] > 0): ?>
                <form action="cart_add.php" method="POST" class="flex gap-4">
                    <input type="hidden" name="product_id" value="<?php echo $product['id']; ?>">
                    <input type="number" name="quantity" value="1" min="1" max="<?php echo $product['stock']; ?>" class="w-20 bg-white/5 border border-white/10 rounded-xl px-4 py-3 focus:outline-none focus:border-cyan-500">
                    <button type="submit" class="flex-1 bg-cyan-500 text-black font-black py-4 rounded-xl hover:bg-cyan-400 hover:scale-[1.02] transition active:scale-95">
                        Add to Cart
                    </button>
                </form>
            <?php else: ?>
                <button disabled class="w-full bg-white/5 text-white/20 font-bold py-4 rounded-xl border border-white/10 cursor-not-allowed">
                    Currently Unavailable
                </button>
            <?php endif; ?>
        </div>

        <div class="grid grid-cols-3 gap-4 pt-10 border-t border-white/10">
            <div class="text-center">
                <div class="text-cyan-400 font-bold">Secure</div>
                <div class="text-xs text-white/30">Payment</div>
            </div>
            <div class="text-center border-x border-white/10">
                <div class="text-cyan-400 font-bold">Original</div>
                <div class="text-xs text-white/30">Guarantee</div>
            </div>
            <div class="text-center">
                <div class="text-cyan-400 font-bold">Fast</div>
                <div class="text-xs text-white/30">Delivery</div>
            </div>
        </div>
    </div>
</div>

<?php require_once 'includes/footer.php'; ?>
