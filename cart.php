<?php
// cart.php
require_once 'includes/db.php';
require_once 'includes/utils.php';

$cart = $_SESSION['cart'] ?? [];
$cartProducts = [];
$total = 0;

if (!empty($cart)) {
    $ids = array_keys($cart);
    $placeholders = str_repeat('?,', count($ids) - 1) . '?';
    $stmt = $pdo->prepare("SELECT * FROM products WHERE id IN ($placeholders)");
    $stmt->execute($ids);
    $cartProducts = $stmt->fetchAll();
    
    foreach ($cartProducts as &$p) {
        $p['quantity'] = $cart[$p['id']];
        $p['subtotal'] = $p['price'] * $p['quantity'];
        $total += $p['subtotal'];
    }
}

require_once 'includes/header.php';
?>

<div class="max-w-4xl mx-auto">
    <h1 class="text-4xl font-black mb-12">Your <span class="text-cyan-400">Shopping Cart</span></h1>

    <?php if(empty($cartProducts)): ?>
        <div class="glass border border-white/10 rounded-3xl p-20 text-center space-y-6">
            <div class="flex justify-center">
                <svg xmlns="http://www.w3.org/2000/svg" width="80" height="80" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1" stroke-linecap="round" stroke-linejoin="round" class="text-white/10"><circle cx="8" cy="21" r="1"/><circle cx="19" cy="21" r="1"/><path d="M2.05 2.05h2l2.66 12.42a2 2 0 0 0 2 1.58h9.78a2 2 0 0 0 1.95-1.57l1.65-7.43H5.12"/></svg>
            </div>
            <p class="text-white/40 text-xl font-medium italic">Your cart is as empty as space.</p>
            <a href="index.php" class="inline-block bg-white text-black px-8 py-3 rounded-full font-bold hover:scale-105 transition">Start Shopping</a>
        </div>
    <?php else: ?>
        <div class="space-y-6">
            <div class="glass border border-white/10 rounded-3xl overflow-hidden">
                <table class="w-full text-left">
                    <thead class="bg-white/5 border-b border-white/10">
                        <tr>
                            <th class="px-8 py-4 font-bold text-white/50 text-sm italic uppercase tracking-widest">Item</th>
                            <th class="px-8 py-4 font-bold text-white/50 text-sm italic uppercase tracking-widest">Quantity</th>
                            <th class="px-8 py-4 font-bold text-white/50 text-sm italic uppercase tracking-widest text-right">Subtotal</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-white/5">
                        <?php foreach($cartProducts as $p): ?>
                            <tr>
                                <td class="px-8 py-6 flex items-center gap-6">
                                    <div class="w-20 h-20 bg-white/5 rounded-2xl flex-shrink-0 overflow-hidden">
                                        <?php if($p['image']): ?>
                                            <img src="<?php echo htmlspecialchars($p['image']); ?>" class="w-full h-full object-cover">
                                        <?php endif; ?>
                                    </div>
                                    <div>
                                        <h3 class="font-bold text-lg"><?php echo htmlspecialchars($p['name']); ?></h3>
                                        <p class="text-cyan-400 font-bold">$<?php echo number_format($p['price'], 2); ?></p>
                                    </div>
                                </td>
                                <td class="px-8 py-6">
                                    <div class="flex items-center gap-3">
                                        <span class="text-xl font-black"><?php echo $p['quantity']; ?></span>
                                        <a href="cart_remove.php?id=<?php echo $p['id']; ?>" class="text-red-500/50 hover:text-red-500 transition text-sm underline">Remove</a>
                                    </div>
                                </td>
                                <td class="px-8 py-6 text-right font-black text-xl">
                                    $<?php echo number_format($p['subtotal'], 2); ?>
                                </td>
                            </tr>
                        <?php endforeach; ?>
                    </tbody>
                </table>
            </div>

            <div class="flex flex-col md:flex-row justify-between items-center gap-8 pt-8 outline-none">
                <a href="index.php" class="text-white/40 hover:text-white transition flex items-center gap-2">
                    <svg xmlns="http://www.w3.org/2000/svg" width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="m15 18-6-6 6-6"/></svg>
                    Continue Shopping
                </a>
                
                <div class="flex items-center gap-12">
                    <div class="text-right">
                        <p class="text-white/40 text-sm italic">Grand Total</p>
                        <p class="text-4xl font-black text-cyan-400">$<?php echo number_format($total, 2); ?></p>
                    </div>
                    <a href="checkout.php" class="bg-cyan-500 text-black px-12 py-4 rounded-full font-black text-lg hover:bg-cyan-400 hover:scale-105 transition shadow-lg shadow-cyan-500/20 active:scale-95">
                        Checkout Now
                    </a>
                </div>
            </div>
        </div>
    <?php endif; ?>
</div>

<?php require_once 'includes/footer.php'; ?>
