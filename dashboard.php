<?php
// dashboard.php
require_once 'includes/db.php';
require_once 'includes/utils.php';

if (!is_logged_in()) {
    redirect('auth/login.php');
}

$role = get_user_role();
$userId = $_SESSION['user_id'];

require_once 'includes/header.php';
?>

<div class="flex flex-col md:flex-row justify-between items-start md:items-center gap-4 mb-12">
    <div>
        <h1 class="text-4xl font-bold">Welcome, <span class="text-cyan-400"><?php echo htmlspecialchars($_SESSION['user_name']); ?></span></h1>
        <p class="text-white/50 mt-1">Manage your account and activities here.</p>
    </div>
    
    <?php if($role === 'SELLER'): ?>
        <a href="product_add.php" class="bg-cyan-500 text-black px-6 py-3 rounded-xl font-bold hover:bg-cyan-400 transition flex items-center gap-2">
            <svg xmlns="http://www.w3.org/2000/svg" width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M5 12h14"/><path d="M12 5v14"/></svg>
            Add New Product
        </a>
    <?php endif; ?>
</div>

<?php if($role === 'SELLER'): ?>
    <?php
    // Fetch seller products
    $stmt = $pdo->prepare("SELECT * FROM products WHERE sellerId = ? ORDER BY createdAt DESC");
    $stmt->execute([$userId]);
    $myProducts = $stmt->fetchAll();
    ?>
    
    <div class="space-y-6">
        <h2 class="text-2xl font-bold border-l-4 border-cyan-500 pl-4">Your Products</h2>
        
        <div class="glass border border-white/10 rounded-3xl overflow-hidden">
            <table class="w-full text-left">
                <thead class="bg-white/5 border-b border-white/10">
                    <tr>
                        <th class="px-6 py-4 font-bold">Product</th>
                        <th class="px-6 py-4 font-bold">Price</th>
                        <th class="px-6 py-4 font-bold">Stock</th>
                        <th class="px-6 py-4 font-bold">Status</th>
                        <th class="px-6 py-4 font-bold">Actions</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-white/5">
                    <?php foreach($myProducts as $p): ?>
                        <tr class="hover:bg-white/5 transition">
                            <td class="px-6 py-4 flex items-center gap-4">
                                <div class="w-12 h-12 bg-white/5 rounded-lg flex-shrink-0">
                                    <?php if($p['image']): ?>
                                        <img src="<?php echo htmlspecialchars($p['image']); ?>" class="w-full h-full object-cover rounded-lg">
                                    <?php endif; ?>
                                </div>
                                <span class="font-medium"><?php echo htmlspecialchars($p['name']); ?></span>
                            </td>
                            <td class="px-6 py-4 text-cyan-400 font-bold">$<?php echo number_format($p['price'], 2); ?></td>
                            <td class="px-6 py-4 text-white/60"><?php echo $p['stock']; ?> units</td>
                            <td class="px-6 py-4">
                                <span class="px-3 py-1 rounded-full text-xs font-bold <?php echo $p['status'] === 'ACTIVE' ? 'bg-green-500/20 text-green-400' : 'bg-yellow-500/20 text-yellow-400'; ?>">
                                    <?php echo $p['status']; ?>
                                </span>
                            </td>
                            <td class="px-6 py-4">
                                <button class="text-white/40 hover:text-white transition">Edit</button>
                            </td>
                        </tr>
                    <?php endforeach; ?>
                    
                    <?php if(empty($myProducts)): ?>
                        <tr>
                            <td colspan="5" class="px-6 py-20 text-center text-white/30 italic">
                                You haven't added any products yet.
                            </td>
                        </tr>
                    <?php endif; ?>
                </tbody>
            </table>
        </div>
    </div>
<?php else: ?>
    <div class="grid grid-cols-1 md:grid-cols-3 gap-8">
        <div class="glass border border-white/10 p-8 rounded-3xl">
            <h3 class="text-xl font-bold mb-2">My Orders</h3>
            <p class="text-white/50 text-sm mb-6">Track your recent purchases.</p>
            <p class="text-white/20 italic">No orders found.</p>
        </div>
        <div class="glass border border-white/10 p-8 rounded-3xl">
            <h3 class="text-xl font-bold mb-2">Settings</h3>
            <p class="text-white/50 text-sm mb-6">Update your profile info.</p>
            <a href="#" class="text-cyan-400 hover:underline">Edit Profile</a>
        </div>
        <div class="glass border border-white/10 p-8 rounded-3xl border-dashed">
            <h3 class="text-xl font-bold mb-2">Become a Seller</h3>
            <p class="text-white/50 text-sm mb-6">Start selling your products on Zeoraz today.</p>
            <button class="bg-white/10 px-6 py-2 rounded-xl font-bold hover:bg-white/20 transition">Apply Now</button>
        </div>
    </div>
<?php endif; ?>

<?php require_once 'includes/footer.php'; ?>
