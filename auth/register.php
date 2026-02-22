<?php
require_once '../includes/db.php';
require_once '../includes/utils.php';

if (is_logged_in()) {
    redirect('../index.php');
}

$error = '';
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $name = $_POST['name'] ?? '';
    $email = $_POST['email'] ?? '';
    $password = $_POST['password'] ?? '';
    $role = $_POST['role'] ?? 'USER';

    if (empty($name) || empty($email) || empty($password)) {
        $error = 'All fields are required.';
    } else {
        try {
            // Check if email exists
            $stmt = $pdo->prepare("SELECT id FROM users WHERE email = ?");
            $stmt->execute([$email]);
            if ($stmt->fetch()) {
                $error = 'Email already registered.';
            } else {
                $id = generate_id();
                $hashedPassword = password_hash($password, PASSWORD_DEFAULT);
                
                $stmt = $pdo->prepare("INSERT INTO users (id, name, email, password, role) VALUES (?, ?, ?, ?, ?)");
                $stmt->execute([$id, $name, $email, $hashedPassword, $role]);
                
                $_SESSION['user_id'] = $id;
                $_SESSION['user_role'] = $role;
                $_SESSION['user_name'] = $name;
                
                redirect('../index.php');
            }
        } catch (PDOException $e) {
            $error = 'Registration failed: ' . $e->getMessage();
        }
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Register - Zeoraz</title>
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
            <p class="text-white/50 mt-2">Create your account to start trading</p>
        </div>

        <?php if($error): ?>
            <div class="bg-red-500/20 text-red-400 p-4 rounded-xl border border-red-500/30 text-sm">
                <?php echo htmlspecialchars($error); ?>
            </div>
        <?php endif; ?>

        <form method="POST" class="space-y-4">
            <div>
                <label class="block text-sm font-medium mb-1">Full Name</label>
                <input type="text" name="name" required class="w-full bg-white/5 border border-white/10 rounded-xl px-4 py-3 focus:outline-none focus:border-cyan-500">
            </div>
            <div>
                <label class="block text-sm font-medium mb-1">Email Address</label>
                <input type="email" name="email" required class="w-full bg-white/5 border border-white/10 rounded-xl px-4 py-3 focus:outline-none focus:border-cyan-500">
            </div>
            <div>
                <label class="block text-sm font-medium mb-1">Password</label>
                <input type="password" name="password" required class="w-full bg-white/5 border border-white/10 rounded-xl px-4 py-3 focus:outline-none focus:border-cyan-500">
            </div>
            <div>
                <label class="block text-sm font-medium mb-1">Account Type</label>
                <select name="role" class="w-full bg-white/10 border border-white/10 rounded-xl px-4 py-3 focus:outline-none focus:border-cyan-500 text-white">
                    <option value="USER" class="bg-slate-900">Customer</option>
                    <option value="SELLER" class="bg-slate-900">Seller</option>
                </select>
            </div>
            <button type="submit" class="w-full bg-cyan-500 text-black font-bold py-3 rounded-xl hover:bg-cyan-400 transition">
                Create Account
            </button>
        </form>

        <p class="text-center text-white/50 text-sm">
            Already have an account? <a href="login.php" class="text-cyan-400 hover:underline">Sign In</a>
        </p>
    </div>
</body>
</html>
