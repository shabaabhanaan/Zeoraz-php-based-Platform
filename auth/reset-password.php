<?php
// auth/reset-password.php
require_once '../includes/db.php';
require_once '../includes/utils.php';

$error = '';
$success = '';
$token = $_GET['token'] ?? '';

if (!$token) {
    redirect('login.php');
}

// Verify token
$stmt = $pdo->prepare("SELECT id FROM users WHERE reset_token = ? AND reset_expires > NOW()");
$stmt->execute([$token]);
$user = $stmt->fetch();

if (!$user) {
    die("Invalid or expired token. <a href='forgot-password.php'>Request a new link</a>");
}

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $password = $_POST['password'] ?? '';
    $confirmPassword = $_POST['confirm_password'] ?? '';

    if (empty($password) || strlen($password) < 6) {
        $error = 'Password must be at least 6 characters.';
    } elseif ($password !== $confirmPassword) {
        $error = 'Passwords do not match.';
    } else {
        try {
            $hashedPassword = password_hash($password, PASSWORD_DEFAULT);
            $stmt = $pdo->prepare("UPDATE users SET password = ?, reset_token = NULL, reset_expires = NULL WHERE id = ?");
            $stmt->execute([$hashedPassword, $user['id']]);
            $success = 'Password reset successfully! You can now login.';
        } catch (PDOException $e) {
            $error = 'Failed to reset password.';
        }
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Reset Password - Zeoraz</title>
    <script src="https://unpkg.com/@tailwindcss/browser@4"></script>
    <style>
        body { background-color: #0f172a; color: white; }
        .glass { background: rgba(255, 255, 255, 0.05); backdrop-filter: blur(10px); }
    </style>
</head>
<body class="flex items-center justify-center min-h-screen p-6">
    <div class="glass border border-white/10 p-8 rounded-3xl w-full max-w-md space-y-8">
        <div class="text-center">
            <h1 class="text-3xl font-bold italic text-cyan-400">Zeoraz</h1>
            <h2 class="text-xl font-bold mt-4 text-white">Reset Password</h2>
            <p class="text-white/50 mt-2">Enter your new password below.</p>
        </div>

        <?php if($error): ?>
            <div class="bg-red-500/20 text-red-400 p-4 rounded-xl border border-red-500/30 text-sm">
                <?php echo htmlspecialchars($error); ?>
            </div>
        <?php endif; ?>

        <?php if($success): ?>
            <div class="bg-green-500/20 text-green-400 p-4 rounded-xl border border-green-500/30 text-sm">
                <?php echo htmlspecialchars($success); ?>
                <div class="mt-4">
                    <a href="login.php" class="inline-block bg-cyan-500 text-black px-6 py-2 rounded-lg font-bold">Login Now</a>
                </div>
            </div>
        <?php else: ?>
            <form method="POST" class="space-y-4">
                <div>
                    <label class="block text-sm font-medium mb-1">New Password</label>
                    <input type="password" name="password" required
                        class="w-full bg-white/5 border border-white/10 rounded-xl px-4 py-3 focus:outline-none focus:border-cyan-500">
                </div>
                <div>
                    <label class="block text-sm font-medium mb-1">Confirm New Password</label>
                    <input type="password" name="confirm_password" required
                        class="w-full bg-white/5 border border-white/10 rounded-xl px-4 py-3 focus:outline-none focus:border-cyan-500">
                </div>
                <button type="submit" class="w-full bg-cyan-500 text-black font-bold py-3 rounded-xl hover:bg-cyan-400 transition">
                    Update Password
                </button>
            </form>
        <?php endif; ?>
    </div>
</body>
</html>
