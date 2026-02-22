<?php
// profile.php
require_once 'includes/db.php';
require_once 'includes/utils.php';

if (!is_logged_in()) {
    redirect('auth/login.php');
}

$userId = $_SESSION['user_id'];
$stmt = $pdo->prepare("SELECT * FROM users WHERE id = ?");
$stmt->execute([$userId]);
$user = $stmt->fetch();

require_once 'includes/header.php';
?>

<div class="max-w-xl mx-auto">
    <div class="glass border border-white/10 p-10 rounded-3xl space-y-8">
        <div class="flex items-center gap-6">
            <div class="w-20 h-20 bg-cyan-500 rounded-full flex items-center justify-center text-3xl font-black text-black">
                <?php echo strtoupper(substr($user['name'], 0, 1)); ?>
            </div>
            <div>
                <h1 class="text-3xl font-black"><?php echo htmlspecialchars($user['name']); ?></h1>
                <p class="text-white/40 italic"><?php echo htmlspecialchars($user['email']); ?></p>
            </div>
        </div>

        <div class="grid grid-cols-2 gap-4">
            <div class="p-4 bg-white/5 border border-white/5 rounded-2xl">
                <p class="text-xs text-white/30 uppercase tracking-widest font-bold mb-1">Account Type</p>
                <p class="text-cyan-400 font-bold"><?php echo $user['role']; ?></p>
            </div>
            <div class="p-4 bg-white/5 border border-white/5 rounded-2xl">
                <p class="text-xs text-white/30 uppercase tracking-widest font-bold mb-1">Joined</p>
                <p class="text-white/70 font-bold"><?php echo date('M Y', strtotime($user['createdAt'])); ?></p>
            </div>
        </div>

        <div class="pt-6">
            <a href="dashboard.php" class="block text-center border border-white/10 py-3 rounded-xl hover:bg-white/5 transition font-bold">Manage My Activities</a>
        </div>
    </div>
</div>

<?php require_once 'includes/footer.php'; ?>
