<?php
// checkout.php
require_once 'includes/db.php';
require_once 'includes/utils.php';

if (!is_logged_in()) {
    redirect('auth/login.php');
}

$cart = $_SESSION['cart'] ?? [];
if (empty($cart)) {
    redirect('index.php');
}

$cartProducts = [];
$total = 0;

$ids = array_keys($cart);
$placeholders = str_repeat('?,', count($ids) - 1) . '?';
$stmt = $pdo->prepare("SELECT * FROM products WHERE id IN ($placeholders)");
$stmt->execute($ids);
$cartProducts = $stmt->fetchAll();

foreach ($cartProducts as $p) {
    $total += $p['price'] * $cart[$p['id']];
}

require_once 'includes/header.php';
?>

<div class="max-w-6xl mx-auto">
    <h1 class="text-4xl font-black mb-12">Final <span class="text-cyan-400">Checkout</span></h1>

    <form action="order_process.php" method="POST" class="grid grid-cols-1 lg:grid-cols-3 gap-12">
        <!-- Shipping Form -->
        <div class="lg:col-span-2 space-y-8">
            <div class="glass border border-white/10 p-8 rounded-3xl space-y-6">
                <h2 class="text-2xl font-bold flex items-center gap-3">
                    <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="text-cyan-400"><path d="M20 10c0 6-8 12-8 12s-8-6-8-12a8 8 0 0 1 16 0Z"/><circle cx="12" cy="10" r="3"/></svg>
                    Shipping Information
                </h2>
                
                <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                    <div class="space-y-2">
                        <label class="text-sm text-white/50">Full Name</label>
                        <input type="text" value="<?php echo htmlspecialchars($_SESSION['user_name']); ?>" readonly class="w-full bg-white/5 border border-white/10 rounded-xl px-4 py-3 focus:outline-none text-white/50">
                    </div>
                    <div class="space-y-2">
                        <label class="text-sm text-white/50">Phone Number</label>
                        <input type="text" name="phone" required class="w-full bg-white/5 border border-white/10 rounded-xl px-4 py-3 focus:outline-none focus:border-cyan-500">
                    </div>
                </div>

                <div class="space-y-2">
                    <label class="text-sm text-white/50">Shipping Address</label>
                    <textarea name="address" rows="4" required placeholder="Enter your full street address, city, and zip code" class="w-full bg-white/5 border border-white/10 rounded-xl px-4 py-3 focus:outline-none focus:border-cyan-500"></textarea>
                </div>
            </div>

            <div class="glass border border-white/10 p-8 rounded-3xl space-y-6">
                <h2 class="text-2xl font-bold flex items-center gap-3">
                    <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="text-cyan-400"><rect width="20" height="14" x="2" y="5" rx="2"/><line x1="2" x2="22" y1="10" y2="10"/></svg>
                    Payment Method
                </h2>
                <div class="flex items-center gap-4 p-4 border border-cyan-500/30 bg-cyan-500/5 rounded-2xl">
                    <div class="w-4 h-4 rounded-full bg-cyan-500 shadow-[0_0_10px_rgba(6,182,212,0.5)]"></div>
                    <span class="font-bold">Cash on Delivery</span>
                    <span class="ml-auto text-xs text-white/30 uppercase tracking-widest italic">Default</span>
                </div>
                <p class="text-white/40 text-sm italic">Online payments are currently disabled for maintenance. Please pay upon arrival.</p>
            </div>
        </div>

        <!-- Order Summary -->
        <div class="space-y-6">
            <div class="glass border border-white/10 p-8 rounded-3xl sticky top-28">
                <h2 class="text-xl font-bold mb-6">Order Summary</h2>
                
                <div class="space-y-4 max-h-60 overflow-y-auto pr-4 mb-6 custom-scrollbar">
                    <?php foreach($cartProducts as $p): ?>
                        <div class="flex justify-between items-center text-sm">
                            <span class="text-white/60 line-clamp-1 flex-1"><?php echo htmlspecialchars($p['name']); ?> <span class="text-cyan-400 font-bold ml-1">x<?php echo $cart[$p['id']]; ?></span></span>
                            <span class="font-bold ml-4">$<?php echo number_format($p['price'] * $cart[$p['id']], 2); ?></span>
                        </div>
                    <?php endforeach; ?>
                </div>

                <div class="border-t border-white/10 pt-6 space-y-3">
                    <div class="flex justify-between text-white/50 italic">
                        <span>Subtotal</span>
                        <span>$<?php echo number_format($total, 2); ?></span>
                    </div>
                    <div class="flex justify-between text-white/50 italic">
                        <span>Shipping</span>
                        <span class="text-green-400 font-bold uppercase text-xs">Free</span>
                    </div>
                    <div class="flex justify-between items-center text-xl font-black pt-2">
                        <span>Total</span>
                        <span class="text-cyan-400">$<?php echo number_format($total, 2); ?></span>
                    </div>
                </div>

                <button type="submit" class="w-full bg-white text-black font-black py-4 rounded-xl mt-8 hover:scale-[1.02] transition active:scale-95 shadow-xl shadow-white/5">
                    Confirm Order
                </button>
            </div>
        </div>
    </form>
</div>

<style>
.custom-scrollbar::-webkit-scrollbar { width: 4px; }
.custom-scrollbar::-webkit-scrollbar-track { background: transparent; }
.custom-scrollbar::-webkit-scrollbar-thumb { background: rgba(255,255,255,0.1); border-radius: 10px; }
</style>

<?php require_once 'includes/footer.php'; ?>
