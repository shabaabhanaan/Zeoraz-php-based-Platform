<?php
// cart.php - Upgraded Shopping Cart
require_once '../core/db.php';
require_once '../includes/header.php';

$cart = $_SESSION['cart'] ?? [];
$products = [];
$total = 0;

if (!empty($cart)) {
    $ids = array_keys($cart);
    $placeholders = str_repeat('?,', count($ids) - 1) . '?';
    $stmt = $pdo->prepare("SELECT * FROM products WHERE id IN ($placeholders)");
    $stmt->execute($ids);
    $products = $stmt->fetchAll();
}
?>

<div class="max-w-4xl mx-auto">
    <h1 class="text-4xl font-black mb-12">Your <span class="text-cyan-400">Cart</span></h1>

    <?php if (empty($products)): ?>
        <div class="glass border border-dashed border-white/10 rounded-3xl p-20 text-center space-y-6">
            <div class="w-20 h-20 bg-white/5 rounded-full flex items-center justify-center mx-auto">
                <svg xmlns="http://www.w3.org/2000/svg" width="40" height="40" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="text-white/20"><circle cx="8" cy="21" r="1"/><circle cx="19" cy="21" r="1"/><path d="M2.05 2.05h2l2.66 12.42a2 2 0 0 0 2 1.58h9.78a2 2 0 0 0 1.95-1.57l1.65-7.43H5.12"/></svg>
            </div>
            <p class="text-white/40 text-xl italic">Your cart is feeling a bit lonely...</p>
            <a href="<?php echo BASE_URL; ?>index.php" class="inline-block bg-white text-black px-8 py-3 rounded-2xl font-bold hover:scale-105 transition">Start Shopping</a>
        </div>
    <?php else: ?>
        <div class="grid grid-cols-1 lg:grid-cols-3 gap-8">
            <!-- Cart Items -->
            <div class="lg:col-span-2 space-y-4">
                <?php foreach ($products as $p): 
                    $qty = $cart[$p['id']];
                    $subtotal = $p['price'] * $qty;
                    $total += $subtotal;
                ?>
                    <div class="glass border border-white/10 p-4 rounded-3xl flex items-center gap-6 group hover:border-cyan-500/30 transition shadow-xl" id="cart-item-<?php echo $p['id']; ?>">
                        <div class="w-24 h-24 bg-white/5 rounded-2xl border border-white/10 overflow-hidden flex-shrink-0">
                            <?php if($p['image']): ?>
                                <img src="<?php echo htmlspecialchars($p['image']); ?>" class="w-full h-full object-cover">
                            <?php endif; ?>
                        </div>
                        
                        <div class="flex-1 min-w-0">
                            <h3 class="font-bold text-lg truncate"><?php echo htmlspecialchars($p['name']); ?></h3>
                            <p class="text-white/40 text-sm mb-3"><?php echo htmlspecialchars($p['category']); ?></p>
                            
                            <div class="flex items-center gap-4">
                                <div class="flex items-center bg-white/5 rounded-xl border border-white/10 px-2 py-1">
                                    <button onclick="updateQty('<?php echo $p['id']; ?>', -1)" class="w-8 h-8 flex items-center justify-center hover:text-cyan-400 transition">
                                        <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="3" stroke-linecap="round" stroke-linejoin="round"><path d="M5 12h14"/></svg>
                                    </button>
                                    <span class="w-8 text-center font-mono font-bold" id="qty-<?php echo $p['id']; ?>"><?php echo $qty; ?></span>
                                    <button onclick="updateQty('<?php echo $p['id']; ?>', 1)" class="w-8 h-8 flex items-center justify-center hover:text-cyan-400 transition">
                                        <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="3" stroke-linecap="round" stroke-linejoin="round"><path d="M5 12h14"/><path d="M12 5v14"/></svg>
                                    </button>
                                </div>
                                <button onclick="removeItem('<?php echo $p['id']; ?>')" class="text-white/20 hover:text-red-500 transition">
                                    <svg xmlns="http://www.w3.org/2000/svg" width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M3 6h18"/><path d="M19 6v14c0 1-1 2-2 2H7c-1 0-2-1-2-2V6"/><path d="M8 6V4c0-1 1-2 2-2h4c1 0 2 1 2 2v2"/></svg>
                                </button>
                            </div>
                        </div>

                        <div class="text-right">
                            <p class="text-xl font-black text-cyan-400 font-mono">$<?php echo number_format($subtotal, 2); ?></p>
                        </div>
                    </div>
                <?php endforeach; ?>
            </div>

            <!-- Summary -->
            <div class="space-y-6">
                <div class="glass border border-white/10 p-8 rounded-3xl sticky top-28 shadow-2xl bg-gradient-to-b from-white/[0.02] to-transparent">
                    <h2 class="text-xl font-bold mb-6">Order Summary</h2>
                    
                    <div class="space-y-4 text-white/60 text-sm">
                        <div class="flex justify-between">
                            <span>Subtotal</span>
                            <span class="font-mono">$<?php echo number_format($total, 2); ?></span>
                        </div>
                        <div class="flex justify-between">
                            <span>Shipping</span>
                            <span class="text-green-400 font-bold">FREE</span>
                        </div>
                        <div class="border-t border-white/10 pt-4 flex justify-between text-white text-lg font-black">
                            <span>Total</span>
                            <span class="text-cyan-400 font-mono">$<?php echo number_format($total, 2); ?></span>
                        </div>
                    </div>

                    <a href="<?php echo BASE_URL; ?>pages/checkout.php" class="block w-full text-center bg-cyan-500 text-black font-black py-4 rounded-2xl hover:bg-cyan-400 transition mt-8 shadow-lg shadow-cyan-500/20 active:scale-[0.98]">
                        Proceed to Checkout
                    </a>
                    
                    <p class="text-[10px] text-white/30 text-center mt-4 uppercase tracking-widest font-bold">Secure Checkout Powered by Zeoraz</p>
                </div>

                <a href="<?php echo BASE_URL; ?>index.php" class="block w-full text-center py-4 text-white/40 hover:text-white transition font-bold text-sm">
                    Continue Shopping
                </a>
            </div>
        </div>
    <?php endif; ?>
</div>

<script>
async function updateQty(id, delta) {
    const qtyEl = document.getElementById('qty-' + id);
    let newQty = parseInt(qtyEl.innerText) + delta;
    if (newQty < 1) return;

    const formData = new FormData();
    formData.append('action', 'update');
    formData.append('product_id', id);
    formData.append('quantity', newQty);

    try {
        const res = await fetch('<?php echo BASE_URL; ?>api/cart_handler.php', { method: 'POST', body: formData });
        const data = await res.json();
        if (data.success) {
            location.reload(); // Simple way to refresh totals
        }
    } catch (e) { console.error(e); }
}

async function removeItem(id) {
    if(!confirm('Remove this item?')) return;
    
    const formData = new FormData();
    formData.append('action', 'remove');
    formData.append('product_id', id);

    try {
        const res = await fetch('<?php echo BASE_URL; ?>api/cart_handler.php', { method: 'POST', body: formData });
        const data = await res.json();
        if (data.success) {
            location.reload();
        }
    } catch (e) { console.error(e); }
}
</script>

<?php require_once '../includes/footer.php'; ?>
