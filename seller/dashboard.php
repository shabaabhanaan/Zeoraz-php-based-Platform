<?php
// dashboard.php
require_once '../core/db.php';
require_once '../core/utils.php';

if (!is_logged_in()) {
    redirect(BASE_URL . 'auth/login.php');
}

$role = get_user_role();
$userId = $_SESSION['user_id'];

// Stats calculations (For Admin or General info)
$stats = [
    'users' => $pdo->query("SELECT COUNT(*) FROM users")->fetchColumn(),
    'products' => $pdo->query("SELECT COUNT(*) FROM products")->fetchColumn(),
    'orders' => $pdo->query("SELECT COUNT(*) FROM orders")->fetchColumn(),
    'revenue' => $pdo->query("SELECT SUM(totalAmount) FROM orders WHERE paymentStatus = 'PAID'")->fetchColumn() ?: 0
];

// Fetch orders for Recent Orders section
$recentOrdersStmt = $pdo->prepare("SELECT o.*, u.name as customerName FROM orders o JOIN users u ON o.userId = u.id ORDER BY o.createdAt DESC LIMIT 5");
$recentOrdersStmt->execute();
$recentOrders = $recentOrdersStmt->fetchAll();

require_once '../includes/header.php';
?>

<div class="mb-12">
    <div class="flex flex-col md:flex-row justify-between items-start md:items-center gap-6">
        <div>
            <h1 class="text-4xl font-black">Dashboard</h1>
            <p class="text-white/50 mt-1">Welcome back, <span class="text-cyan-400 font-bold"><?php echo htmlspecialchars($_SESSION['user_name']); ?></span>. Here's what's happening.</p>
        </div>
        
        <div class="flex gap-4">
            <?php if($role === 'SELLER' || $role === 'ADMIN'): ?>
                <a href="<?php echo BASE_URL; ?>seller/product_add.php" class="bg-cyan-500 text-black px-6 py-3 rounded-2xl font-bold hover:bg-cyan-400 transition flex items-center gap-2 shadow-lg shadow-cyan-500/20">
                    <svg xmlns="http://www.w3.org/2000/svg" width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round"><path d="M5 12h14"/><path d="M12 5v14"/></svg>
                    New Product
                </a>
            <?php endif; ?>
        </div>
    </div>
</div>

<!-- Stats Grid -->
<div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-6 mb-12">
    <div class="glass border border-white/10 p-6 rounded-3xl group hover:border-cyan-500/30 transition">
        <p class="text-white/40 text-sm font-bold uppercase tracking-wider mb-2">Total Revenue</p>
        <h3 class="text-3xl font-black text-cyan-400">$<?php echo number_format($stats['revenue'], 2); ?></h3>
    </div>
    <div class="glass border border-white/10 p-6 rounded-3xl group hover:border-blue-500/30 transition">
        <p class="text-white/40 text-sm font-bold uppercase tracking-wider mb-2">Total Orders</p>
        <h3 class="text-3xl font-black text-blue-400"><?php echo $stats['orders']; ?></h3>
    </div>
    <div class="glass border border-white/10 p-6 rounded-3xl group hover:border-purple-500/30 transition">
        <p class="text-white/40 text-sm font-bold uppercase tracking-wider mb-2">Active Products</p>
        <h3 class="text-3xl font-black text-purple-400"><?php echo $stats['products']; ?></h3>
    </div>
    <div class="glass border border-white/10 p-6 rounded-3xl group hover:border-emerald-500/30 transition">
        <p class="text-white/40 text-sm font-bold uppercase tracking-wider mb-2">Total Users</p>
        <h3 class="text-3xl font-black text-emerald-400"><?php echo $stats['users']; ?></h3>
    </div>
</div>

<div class="grid grid-cols-1 lg:grid-cols-3 gap-8">
    <!-- Main Content Area -->
    <div class="lg:col-span-2 space-y-8">
        <?php if($role === 'SELLER' || $role === 'ADMIN'): ?>
            <div class="glass border border-white/10 rounded-3xl overflow-hidden shadow-2xl">
                <div class="px-6 py-5 border-b border-white/5 flex justify-between items-center bg-white/5">
                    <h2 class="text-lg font-bold">Manage Inventory</h2>
                    <span class="text-xs text-white/40 font-mono">Total: <?php echo $stats['products']; ?> items</span>
                </div>
                <div class="overflow-x-auto">
                    <table class="w-full text-left whitespace-nowrap">
                        <thead class="bg-white/[0.02] text-white/40 text-xs uppercase tracking-widest">
                            <tr>
                                <th class="px-6 py-4">Product</th>
                                <th class="px-6 py-4">Price</th>
                                <th class="px-6 py-4">Stock</th>
                                <th class="px-6 py-4">Status</th>
                                <th class="px-6 py-4 text-right">Actions</th>
                            </tr>
                        </thead>
                        <tbody class="divide-y divide-white/5">
                            <?php 
                            $query = $role === 'ADMIN' ? "SELECT * FROM products" : "SELECT * FROM products WHERE sellerId = ?";
                            $pStmt = $pdo->prepare($query . " ORDER BY createdAt DESC");
                            $role === 'ADMIN' ? $pStmt->execute() : $pStmt->execute([$userId]);
                            $products = $pStmt->fetchAll();
                            
                            foreach($products as $p): ?>
                                <tr class="hover:bg-white/[0.03] transition group">
                                    <td class="px-6 py-4">
                                        <div class="flex items-center gap-4">
                                            <div class="w-10 h-10 bg-white/5 rounded-lg border border-white/10 overflow-hidden flex-shrink-0">
                                                <?php if($p['image']): ?>
                                                    <img src="<?php echo htmlspecialchars($p['image']); ?>" class="w-full h-full object-cover">
                                                <?php endif; ?>
                                            </div>
                                            <span class="font-bold truncate max-w-[150px]"><?php echo htmlspecialchars($p['name']); ?></span>
                                        </div>
                                    </td>
                                    <td class="px-6 py-4 text-cyan-400 font-mono font-bold">$<?php echo number_format($p['price'], 2); ?></td>
                                    <td class="px-6 py-4">
                                        <div class="flex items-center gap-2">
                                            <span class="w-2 h-2 rounded-full <?php echo $p['stock'] > 10 ? 'bg-green-500' : 'bg-red-500'; ?> animate-pulse"></span>
                                            <span class="text-white/60"><?php echo $p['stock']; ?></span>
                                        </div>
                                    </td>
                                    <td class="px-6 py-4 text-xs font-black uppercase tracking-tighter">
                                        <span class="<?php echo $p['status'] === 'ACTIVE' ? 'text-emerald-400' : 'text-amber-400'; ?>">
                                            <?php echo $p['status']; ?>
                                        </span>
                                    </td>
                                    <td class="px-6 py-4 text-right space-x-3">
                                        <a href="<?php echo BASE_URL; ?>seller/product_manager.php?id=<?php echo $p['id']; ?>" class="text-white/20 hover:text-white transition inline-block">
                                            <svg xmlns="http://www.w3.org/2000/svg" width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M17 3a2.828 2.828 0 1 1 4 4L7.5 20.5 2 22l1.5-5.5L17 3z"/></svg>
                                        </a>
                                        <a href="<?php echo BASE_URL; ?>seller/product_delete.php?id=<?php echo $p['id']; ?>" onclick="return confirm('Delete this product?')" class="text-white/10 hover:text-red-500 transition inline-block">
                                            <svg xmlns="http://www.w3.org/2000/svg" width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M3 6h18"/><path d="M19 6v14c0 1-1 2-2 2H7c-1 0-2-1-2-2V6"/><path d="M8 6V4c0-1 1-2 2-2h4c1 0 2 1 2 2v2"/></svg>
                                        </a>
                                    </td>
                                </tr>
                            <?php endforeach; ?>
                        </tbody>
                    </table>
                </div>
            </div>
        <?php else: ?>
            <div class="glass border border-white/10 p-8 rounded-3xl border-dashed flex flex-col items-center justify-center text-center space-y-4">
                <div class="w-16 h-16 bg-cyan-500/10 rounded-2xl flex items-center justify-center text-cyan-400">
                    <svg xmlns="http://www.w3.org/2000/svg" width="32" height="32" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M6 2 3 6v14a2 2 0 0 0 2 2h14a2 2 0 0 0 2-2V6l-3-4Z"/><path d="M3 6h18"/><path d="M16 10a4 4 0 0 1-8 0"/></svg>
                </div>
                <h3 class="text-xl font-bold">Start Selling on Zeoraz</h3>
                <p class="text-white/40 max-w-sm">Join our network of professional vendors and reach customers worldwide.</p>
                <button class="bg-white text-black px-8 py-3 rounded-2xl font-bold hover:scale-105 transition">Update to Seller Account</button>
            </div>
        <?php endif; ?>
    </div>

    <!-- Sidebar / Activity Area -->
    <div class="space-y-8">
        <div class="glass border border-white/10 p-6 rounded-3xl">
            <h3 class="font-bold mb-4 flex items-center gap-2">
                <span class="w-2 h-2 bg-cyan-500 rounded-full"></span>
                Recent Orders
            </h3>
            <div class="space-y-4">
                <?php foreach($recentOrders as $order): ?>
                    <div class="flex justify-between items-center text-sm border-b border-white/5 pb-4 last:border-0 last:pb-0">
                        <div>
                            <p class="font-bold"><?php echo htmlspecialchars($order['customerName']); ?></p>
                            <p class="text-white/40 text-xs"><?php echo date('M d, H:i', strtotime($order['createdAt'])); ?></p>
                        </div>
                        <div class="text-right flex flex-col items-end gap-2">
                            <p class="text-cyan-400 font-mono font-bold">$<?php echo number_format($order['totalAmount'], 2); ?></p>
                            <?php if($role === 'ADMIN' || $role === 'SELLER'): ?>
                                <select onchange="updateOrderStatus('<?php echo $order['id']; ?>', this.value)" 
                                    class="bg-white/5 border border-white/10 rounded-lg px-2 py-1 text-[10px] uppercase font-black focus:outline-none focus:border-cyan-500 transition">
                                    <option value="PENDING" <?php echo $order['status'] === 'PENDING' ? 'selected' : ''; ?>>Pending</option>
                                    <option value="PROCESSING" <?php echo $order['status'] === 'PROCESSING' ? 'selected' : ''; ?>>Processing</option>
                                    <option value="SHIPPED" <?php echo $order['status'] === 'SHIPPED' ? 'selected' : ''; ?>>Shipped</option>
                                    <option value="COMPLETED" <?php echo $order['status'] === 'COMPLETED' ? 'selected' : ''; ?>>Completed</option>
                                    <option value="CANCELLED" <?php echo $order['status'] === 'CANCELLED' ? 'selected' : ''; ?>>Cancelled</option>
                                </select>
                            <?php else: ?>
                                <p class="text-[10px] uppercase font-black <?php echo $order['status'] === 'COMPLETED' ? 'text-green-500' : 'text-amber-500'; ?> transition-colors duration-500" id="status-<?php echo $order['id']; ?>"><?php echo $order['status']; ?></p>
                            <?php endif; ?>
                        </div>
                    </div>
                <?php endforeach; ?>
                <?php if(empty($recentOrders)): ?>
                    <p class="text-white/20 text-center py-4 italic">No recent activity</p>
                <?php endif; ?>
            </div>
        </div>

        <div class="glass border border-white/10 p-6 rounded-3xl bg-gradient-to-br from-blue-600/10 to-purple-600/10">
            <h3 class="font-bold mb-2">Account Profile</h3>
            <div class="flex items-center gap-4 mb-4">
                <div class="w-12 h-12 bg-white/10 rounded-full border border-white/10 flex items-center justify-center font-black text-xl">
                    <?php echo strtoupper(substr($_SESSION['user_name'], 0, 1)); ?>
                </div>
                <div>
                    <p class="font-bold leading-tight"><?php echo htmlspecialchars($_SESSION['user_name']); ?></p>
                    <p class="text-xs text-white/40 capitalize"><?php echo $role; ?></p>
                </div>
            </div>
            <a href="<?php echo BASE_URL; ?>pages/profile.php" class="block w-full text-center bg-white/5 border border-white/10 py-3 rounded-xl text-sm font-bold hover:bg-white/10 transition">Manage Settings</a>
        </div>
    </div>
</div>


<script>
async function updateOrderStatus(orderId, status) {
    const formData = new FormData();
    formData.append('order_id', orderId);
    formData.append('status', status);

    try {
        const res = await fetch('<?php echo BASE_URL; ?>api/update_order_status.php', {
            method: 'POST',
            body: formData
        });
        const data = await res.json();
        if (data.success) {
            // Optional: Add a toast notification here
            console.log('Status updated and email sent');
        } else {
            alert('Failed to update status: ' + data.message);
        }
    } catch (e) {
        console.error('Error updating status', e);
    }
}
</script>

<?php require_once '../includes/footer.php'; ?>
