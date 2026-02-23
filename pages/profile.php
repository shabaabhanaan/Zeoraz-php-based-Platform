<?php
// profile.php
require_once '../core/db.php';
require_once '../core/utils.php';

if (!is_logged_in()) {
    redirect('auth/login.php');
}

$userId = $_SESSION['user_id'];
$error = '';
$success = '';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $newName = $_POST['name'] ?? '';
    $newEmail = $_POST['email'] ?? '';
    
    if (empty($newName) || empty($newEmail)) {
        $error = 'Name and email are required.';
    } else {
        try {
            // Get old data for comparison
            $stmt = $pdo->prepare("SELECT name, email FROM users WHERE id = ?");
            $stmt->execute([$userId]);
            $oldUser = $stmt->fetch();

            if ($oldUser['email'] !== $newEmail || $oldUser['name'] !== $newName) {
                $stmt = $pdo->prepare("UPDATE users SET name = ?, email = ? WHERE id = ?");
                $stmt->execute([$newName, $newEmail, $userId]);
                
                $_SESSION['user_name'] = $newName;
                
                // Security Notification
                $subject = "Security Alert: Profile Updated - Zeoraz";
                $body = "<h2>Hello, " . htmlspecialchars($newName) . "</h2>
                         <p>Your profile information was recently updated.</p>
                         <p>If you did not make this change, please contact support or reset your password immediately.</p>";
                sendMail($newEmail, $subject, $body);
                if ($oldUser['email'] !== $newEmail) {
                    sendMail($oldUser['email'], $subject, $body);
                }
                
                $success = 'Profile updated successfully! A security notification has been sent.';
            }
        } catch (PDOException $e) {
            $error = 'Failed to update profile.';
        }
    }
}

$stmt = $pdo->prepare("SELECT * FROM users WHERE id = ?");
$stmt->execute([$userId]);
$user = $stmt->fetch();

require_once '../includes/header.php';
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

        <?php if($error): ?>
            <div class="bg-red-500/20 text-red-400 p-4 rounded-xl border border-red-500/30 text-sm">
                <?php echo htmlspecialchars($error); ?>
            </div>
        <?php endif; ?>

        <?php if($success): ?>
            <div class="bg-green-500/20 text-green-400 p-4 rounded-xl border border-green-500/30 text-sm">
                <?php echo htmlspecialchars($success); ?>
            </div>
        <?php endif; ?>

        <form method="POST" class="space-y-6">
            <div class="space-y-2">
                <label class="block text-sm font-medium text-white/50">Full Name</label>
                <input type="text" name="name" value="<?php echo htmlspecialchars($user['name']); ?>" required
                    class="w-full bg-white/5 border border-white/10 rounded-xl px-4 py-3 focus:outline-none focus:border-cyan-500 transition">
            </div>

            <div class="space-y-2">
                <label class="block text-sm font-medium text-white/50">Email Address</label>
                <input type="email" name="email" value="<?php echo htmlspecialchars($user['email']); ?>" required
                    class="w-full bg-white/5 border border-white/10 rounded-xl px-4 py-3 focus:outline-none focus:border-cyan-500 transition">
            </div>

            <div class="grid grid-cols-2 gap-4 pt-4">
                <div class="p-4 bg-white/5 border border-white/5 rounded-2xl">
                    <p class="text-xs text-white/30 uppercase tracking-widest font-bold mb-1">Account Type</p>
                    <p class="text-cyan-400 font-bold"><?php echo $user['role']; ?></p>
                </div>
                <div class="p-4 bg-white/5 border border-white/5 rounded-2xl">
                    <p class="text-xs text-white/30 uppercase tracking-widest font-bold mb-1">Joined</p>
                    <p class="text-white/70 font-bold"><?php echo date('M Y', strtotime($user['createdAt'])); ?></p>
                </div>
            </div>

            <button type="submit" class="w-full bg-cyan-500 text-black font-black py-4 rounded-2xl hover:bg-cyan-400 transition shadow-lg shadow-cyan-500/20">
                Update Security Settings
            </button>
        </form>

        <div class="pt-6 border-t border-white/5 flex justify-between items-center text-sm">
            <a href="<?php echo BASE_URL; ?>seller/dashboard.php" class="text-white/40 hover:text-white transition">← Back to Dashboard</a>
            <a href="<?php echo BASE_URL; ?>auth/forgot-password.php" class="text-cyan-400 hover:underline">Reset Password?</a>
        </div>
    </div>
</div>

<?php require_once '../includes/footer.php'; ?>
