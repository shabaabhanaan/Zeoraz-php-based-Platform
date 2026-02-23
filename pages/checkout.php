<?php
// checkout.php - Complete Order Flow
require_once '../core/db.php';
require_once '../core/utils.php';

if (!is_logged_in()) {
    redirect('auth/login.php');
}

$cart = $_SESSION['cart'] ?? [];
if (empty($cart)) {
    redirect(BASE_URL . 'pages/cart.php');
}

$products = [];
$total = 0;
$ids = array_keys($cart);
$placeholders = str_repeat('?,', count($ids) - 1) . '?';
$stmt = $pdo->prepare("SELECT * FROM products WHERE id IN ($placeholders)");
$stmt->execute($ids);
$products = $stmt->fetchAll();

foreach ($products as $p) {
    $total += $p['price'] * $cart[$p['id']];
}

require_once '../includes/header.php';
?>

<div class="max-w-6xl mx-auto">
    <div class="flex flex-col lg:flex-row gap-12">
        <!-- Billing Form -->
        <div class="flex-1 space-y-8">
            <h1 class="text-3xl font-black">Billing <span class="text-cyan-400">Information</span></h1>
            
            <form action="<?php echo BASE_URL; ?>api/order_process.php" method="POST" class="space-y-6">
                <div class="glass border border-white/10 p-8 rounded-3xl space-y-6">
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                        <div class="space-y-2">
                            <label class="block text-sm font-medium text-white/50">Full Name</label>
                            <input type="text" name="name" required value="<?php echo htmlspecialchars($_SESSION['user_name']); ?>" 
                                class="w-full bg-white/5 border border-white/10 rounded-xl px-4 py-3 focus:outline-none focus:border-cyan-500 transition">
                        </div>
                        <div class="space-y-2">
                            <label class="block text-sm font-medium text-white/50">Email Address</label>
                            <input type="email" name="email" required value="<?php echo htmlspecialchars($_SESSION['user_email'] ?? ''); ?>" 
                                class="w-full bg-white/5 border border-white/10 rounded-xl px-4 py-3 focus:outline-none focus:border-cyan-500 transition">
                        </div>
                    </div>

                    <div class="space-y-2">
                        <label class="block text-sm font-medium text-white/50">Shipping Address</label>
                        <textarea name="address" rows="3" required placeholder="Street address, City, State, ZIP"
                            class="w-full bg-white/5 border border-white/10 rounded-xl px-4 py-3 focus:outline-none focus:border-cyan-500 transition"></textarea>
                    </div>

                    <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                        <div class="space-y-2">
                            <label class="block text-sm font-medium text-white/50">Phone Number</label>
                            <input type="text" name="phone" required
                                class="w-full bg-white/5 border border-white/10 rounded-xl px-4 py-3 focus:outline-none focus:border-cyan-500 transition">
                        </div>
                        <div class="space-y-2">
                            <label class="block text-sm font-medium text-white/50">Payment Method</label>
                            <select name="payment_method" class="w-full bg-white/5 border border-white/10 rounded-xl px-4 py-3 focus:outline-none focus:border-cyan-500 text-white transition appearance-none">
                                <option value="CASH" class="bg-slate-900">Cash on Delivery</option>
                                <option value="CARD" class="bg-slate-900">Credit / Debit Card</option>
                            </select>
                        </div>
                    </div>
                </div>

                <div class="flex items-center gap-4 text-white/40 text-sm italic">
                    <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><rect width="18" height="11" x="3" y="11" rx="2" ry="2"/><path d="M7 11V7a5 5 0 0 1 10 0v4"/></svg>
                    Your personal data will be used to process your order and support your experience throughout this website.
                </div>

                <button type="submit" class="w-full bg-white text-black font-black py-4 rounded-2xl hover:bg-cyan-400 transition shadow-xl shadow-white/5 active:scale-[0.98]">
                    Place Order & Pay $<?php echo number_format($total, 2); ?>
                </button>
            </form>
        </div>

        <!-- Sidebar / Order Summary -->
        <div class="w-full lg:w-96 space-y-6">
            <h2 class="text-xl font-bold">In Your <span class="text-white/40">Cart</span></h2>
            <div class="glass border border-white/10 p-6 rounded-3xl space-y-4">
                <?php foreach ($products as $p): ?>
                    <div class="flex justify-between items-center text-sm">
                        <div class="flex items-center gap-3">
                            <span class="text-white/40 font-mono"><?php echo $cart[$p['id']]; ?> x</span>
                            <span class="font-bold truncate max-w-[120px]"><?php echo htmlspecialchars($p['name']); ?></span>
                        </div>
                        <span class="font-mono text-cyan-400 font-bold">$<?php echo number_format($p['price'] * $cart[$p['id']], 2); ?></span>
                    </div>
                <?php endforeach; ?>
                
                <div class="border-t border-white/5 pt-4 space-y-2">
                    <div class="flex justify-between text-xs text-white/40 uppercase tracking-widest">
                        <span>Original Price</span>
                        <span>$<?php echo number_format($total, 2); ?></span>
                    </div>
                    <div class="flex justify-between text-xs text-white/40 uppercase tracking-widest">
                        <span>Shipping Fee</span>
                        <span class="text-green-400">FREE</span>
                    </div>
                </div>

                <div class="border-t border-white/10 pt-4 flex justify-between items-end">
                    <span class="text-sm font-bold uppercase tracking-tighter">Grand Total</span>
                    <span class="text-2xl font-black text-cyan-400 font-mono">$<?php echo number_format($total, 2); ?></span>
                </div>
            </div>

            <div class="p-4 bg-cyan-500/5 rounded-2xl border border-cyan-500/20 text-center">
                <p class="text-[10px] text-cyan-400 font-black uppercase tracking-widest mb-1">Zeoraz Guarantee</p>
                <p class="text-xs text-white/60 italic">Free 30-day returns on all electronics and fashion items.</p>
            </div>
        </div>
    </div>
</div>

<?php require_once '../includes/footer.php'; ?>
