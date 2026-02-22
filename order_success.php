<?php
// order_success.php
require_once 'includes/db.php';
require_once 'includes/utils.php';

$orderId = $_GET['id'] ?? '';
require_once 'includes/header.php';
?>

<div class="max-w-2xl mx-auto py-20 text-center space-y-8">
    <div class="flex justify-center">
        <div class="w-24 h-24 bg-green-500/20 rounded-full flex items-center justify-center border-4 border-green-500/30">
            <svg xmlns="http://www.w3.org/2000/svg" width="48" height="48" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="3" stroke-linecap="round" stroke-linejoin="round" class="text-green-400"><path d="M20 6 9 17l-5-5"/></svg>
        </div>
    </div>
    
    <div class="space-y-4">
        <h1 class="text-5xl font-black">Order <span class="text-cyan-400">Confirmed!</span></h1>
        <p class="text-white/50 text-xl font-medium">Thank you for your purchase. Your order is now being processed.</p>
    </div>

    <div class="glass border border-white/10 p-6 rounded-2xl inline-block">
        <p class="text-sm text-white/30 uppercase tracking-widest italic mb-1">Order ID</p>
        <p class="font-mono text-cyan-400 font-bold"><?php echo htmlspecialchars($orderId); ?></p>
    </div>

    <div class="pt-8 flex flex-col sm:flex-row justify-center gap-4">
        <a href="dashboard.php" class="bg-white text-black px-10 py-4 rounded-xl font-black hover:scale-105 transition">View My Orders</a>
        <a href="index.php" class="border border-white/10 px-10 py-4 rounded-xl font-black text-white/60 hover:bg-white/5 transition">Continue Shopping</a>
    </div>
</div>

<?php require_once 'includes/footer.php'; ?>
