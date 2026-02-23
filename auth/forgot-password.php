<?php
// auth/forgot-password.php
require_once '../includes/db.php';
require_once '../includes/utils.php';

$error = '';
$success = '';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $email = $_POST['email'] ?? '';

    if (empty($email)) {
        $error = 'Email is required.';
    } else {
        $stmt = $pdo->prepare("SELECT id, name FROM users WHERE email = ?");
        $stmt->execute([$email]);
        $user = $stmt->fetch();

        if ($user) {
            $token = bin2hex(random_bytes(32));
            $expires = date('Y-m-d H:i:s', strtotime('+1 hour'));

            $stmt = $pdo->prepare("UPDATE users SET reset_token = ?, reset_expires = ? WHERE id = ?");
            $stmt->execute([$token, $expires, $user['id']]);

            $resetLink = "http://" . $_SERVER['HTTP_HOST'] . dirname($_SERVER['PHP_SELF']) . "/reset-password.php?token=$token";
            
            $subject = "Password Reset Request - Zeoraz";
            $body = "<h3>Hello " . htmlspecialchars($user['name']) . ",</h3>
                     <p>You requested a password reset. Click the link below to set a new password:</p>
                     <p><a href='$resetLink' style='background:#22d3ee; color:black; padding:10px 20px; border-radius:8px; text-decoration:none; font-weight:bold;'>Reset Password</a></p>
                     <p>This link will expire in 1 hour.</p>
                     <p>If you didn't request this, please ignore this email.</p>";
            
            if (sendMail($email, $subject, $body)) {
                $success = 'If that email is registered, a reset link has been sent.';
            } else {
                $error = 'Failed to send email. Please try again later.';
            }
        } else {
            // For security, show the same success message
            $success = 'If that email is registered, a reset link has been sent.';
        }
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Forgot Password - Zeoraz</title>
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
            <h2 class="text-xl font-bold mt-4 text-white">Forgot Password?</h2>
            <p class="text-white/50 mt-2">Enter your email and we'll send you a link to reset your password.</p>
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

        <form method="POST" class="space-y-4">
            <div>
                <label class="block text-sm font-medium mb-1">Email Address</label>
                <input type="email" name="email" required placeholder="your@email.com"
                    class="w-full bg-white/5 border border-white/10 rounded-xl px-4 py-3 focus:outline-none focus:border-cyan-500">
            </div>
            <button type="submit" class="w-full bg-cyan-500 text-black font-bold py-3 rounded-xl hover:bg-cyan-400 transition">
                Send Reset Link
            </button>
        </form>

        <p class="text-center text-white/50 text-sm">
            Remembered your password? <a href="login.php" class="text-cyan-400 hover:underline">Back to Login</a>
        </p>
    </div>
</body>
</html>
