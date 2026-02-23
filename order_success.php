<?php
// order_success.php - Invoice View
require_once 'includes/db.php';
require_once 'includes/utils.php';

if (!is_logged_in()) redirect('auth/login.php');

$orderId = $_SESSION['last_order_id'] ?? null;
if (!$orderId) redirect('index.php');

// Fetch Order Details
$stmt = $pdo->prepare("SELECT o.*, u.name as customerName, u.email as customerEmail FROM orders o JOIN users u ON o.userId = u.id WHERE o.id = ?");
$stmt->execute([$orderId]);
$order = $stmt->fetch();

// Fetch Items
$stmt = $pdo->prepare("SELECT oi.*, p.name FROM order_items oi JOIN products p ON oi.productId = p.id WHERE oi.orderId = ?");
$stmt->execute([$orderId]);
$items = $stmt->fetchAll();

require_once 'includes/header.php';
?>

<div class="max-w-3xl mx-auto space-y-12">
    <div class="text-center space-y-4">
        <div class="w-20 h-20 bg-emerald-500/20 text-emerald-400 rounded-full flex items-center justify-center mx-auto mb-6 border border-emerald-500/30">
            <svg xmlns="http://www.w3.org/2000/svg" width="40" height="40" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="3" stroke-linecap="round" stroke-linejoin="round"><path d="M20 6 9 17l-5-5"/></svg>
        </div>
        <h1 class="text-5xl font-black">Order <span class="text-cyan-400">Confirmed.</span></h1>
        <p class="text-white/40 text-lg italic">Thank you for your purchase. Your order ID is <span class="text-white font-mono font-bold"><?php echo substr($orderId, 0, 8); ?>...</span></p>
    </div>

    <div class="glass border border-white/10 rounded-3xl overflow-hidden shadow-2xl">
        <div class="bg-white/5 px-8 py-6 border-b border-white/10 flex justify-between items-center">
            <h2 class="font-mono text-xs font-black uppercase tracking-widest text-white/40">Invoice Summary</h2>
            <span class="text-cyan-400 font-bold"><?php echo date('M d, Y', strtotime($order['createdAt'])); ?></span>
        </div>
        
        <div class="p-8 space-y-8">
            <div class="grid grid-cols-2 gap-8 text-sm">
                <div>
                    <h3 class="text-white/40 font-bold uppercase text-[10px] mb-2 tracking-widest">Billed To</h3>
                    <p class="font-black text-lg"><?php echo htmlspecialchars($order['customerName']); ?></p>
                    <p class="text-white/60"><?php echo htmlspecialchars($order['customerEmail']); ?></p>
                    <p class="text-white/40 mt-2 italic"><?php echo nl2br(htmlspecialchars($order['shippingAddress'])); ?></p>
                </div>
                <div class="text-right">
                    <h3 class="text-white/40 font-bold uppercase text-[10px] mb-2 tracking-widest">Order Status</h3>
                    <span class="px-4 py-1.5 bg-cyan-500/20 text-cyan-400 rounded-full text-xs font-black"><?php echo $order['status']; ?></span>
                    <p class="mt-4 text-white/40 italic">Payment: <span class="text-white/70"><?php echo $order['paymentStatus']; ?></span></p>
                </div>
            </div>

            <table class="w-full text-left">
                <thead class="text-[10px] uppercase font-bold text-white/20 border-b border-white/5">
                    <tr>
                        <th class="py-4">Description</th>
                        <th class="py-4 text-center">Qty</th>
                        <th class="py-4 text-right">Amount</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-white/[0.03]">
                    <?php foreach ($items as $item): ?>
                        <tr>
                            <td class="py-4 font-bold"><?php echo htmlspecialchars($item['name']); ?></td>
                            <td class="py-4 text-center text-white/40 font-mono"><?php echo $item['quantity']; ?></td>
                            <td class="py-4 text-right font-mono">$<?php echo number_format($item['price'] * $item['quantity'], 2); ?></td>
                        </tr>
                    <?php endforeach; ?>
                </tbody>
                <tfoot>
                    <tr>
                        <td colspan="2" class="pt-8 text-right text-white/40 font-bold">Grand Total</td>
                        <td class="pt-8 text-right text-2xl font-black text-cyan-400 font-mono">$<?php echo number_format($order['totalAmount'], 2); ?></td>
                    </tr>
                </tfoot>
            </table>
        </div>
    </div>

    <div class="flex justify-center gap-6">
        <a href="index.php" class="bg-white/5 border border-white/10 px-8 py-3 rounded-2xl font-bold hover:bg-white/10 transition">Continue Shopping</a>
        <button onclick="window.print()" class="bg-cyan-500 text-black px-8 py-3 rounded-2xl font-bold hover:bg-cyan-400 transition shadow-lg shadow-cyan-500/20">Print Invoice</button>
    </div>
</div>

<?php require_once 'includes/footer.php'; ?>
