<?php

require_once '../core/db.php';
require_once '../core/utils.php';
require_once '../google-config.php';

// Create Google login URL
$google_login_url = $client->createAuthUrl();

// If user is already logged in, redirect to homepage
if (is_logged_in()) {
    // Redirect based on role
    if ($_SESSION['user_role'] === 'seller') {
        redirect(BASE_URL . 'seller/dashboard.php');
    } else {
        redirect(BASE_URL . 'index.php'); // customer homepage
    }
}

// Handle email/password login
$error = '';
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $email = $_POST['email'] ?? '';
    $password = $_POST['password'] ?? '';
    $role   = $_POST['role'] ?? ''; // added role selection

    if (empty($email) || empty($password) || empty($role)) {
        $error = 'Please enter email, password, and select your role.';
    } else {
        try {
            $stmt = $pdo->prepare("SELECT * FROM users WHERE email = ? AND role = ?");
            $stmt->execute([$email, $role]);
            $user = $stmt->fetch();

            if ($user && password_verify($password, $user['password'])) {
                $_SESSION['user_id'] = $user['id'];
                $_SESSION['user_role'] = $user['role'];
                $_SESSION['user_name'] = $user['name'];

                // Redirect based on role
                if ($user['role'] === 'seller') {
                    redirect(BASE_URL . 'seller/dashboard.php');
                } else {
                    redirect(BASE_URL . 'index.php'); // customer homepage
                }

            } else {
                $error = 'Invalid email, password, or role.';
            }
        } catch (PDOException $e) {
            $error = 'Login failed: ' . $e->getMessage();
        }
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Login - Zeoraz</title>
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
            <p class="text-white/50 mt-2">Sign in to your account</p>
        </div>

        <?php if($error): ?>
            <div class="bg-red-500/20 text-red-400 p-4 rounded-xl border border-red-500/30 text-sm">
                <?php echo htmlspecialchars($error); ?>
            </div>
        <?php endif; ?>

        <!-- Email & Password Login Form -->
        <form method="POST" class="space-y-4">
            <div>
                <label class="block text-sm font-medium mb-1">Email Address</label>
                <input type="email" name="email" required class="w-full bg-white/5 border border-white/10 rounded-xl px-4 py-3 focus:outline-none focus:border-cyan-500">
            </div>
            <div>
                <label class="block text-sm font-medium mb-1">Password</label>
                <input type="password" name="password" required class="w-full bg-white/5 border border-white/10 rounded-xl px-4 py-3 focus:outline-none focus:border-cyan-500">
            </div>
           <div>
    <label class="block text-sm font-medium mb-1 text-cyan-400">Login as</label>
    <select name="role" required
        class="w-full bg-cyan-800 text-cyan-100 border border-cyan-600 rounded-xl px-4 py-3 focus:outline-none focus:ring-2 focus:ring-cyan-400 focus:border-cyan-400">
        <option value="" class="text-cyan-300">-- Select Role --</option>
        <option value="customer">Customer</option>
        <option value="seller">Seller</option>
    </select>
</div>
            <button type="submit" class="w-full bg-cyan-500 text-black font-bold py-3 rounded-xl hover:bg-cyan-400 transition">
                Sign In
            </button>
        </form>

        <!-- Google Sign-In Button -->
        <div class="text-center mt-4">
            <a href="<?= $google_login_url ?>" 
               class="w-full inline-block bg-white text-black font-semibold py-3 rounded-xl hover:bg-gray-200 transition">
                Sign in with Google
            </a>
        </div>

        <p class="text-center text-white/50 text-sm mt-4">
            Don't have an account? <a href="register.php" class="text-cyan-400 hover:underline">Create Account</a>
        </p>
    </div>
</body>
</html>