<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Zeoraz - Multi-Vendor Market</title>
    <script src="https://unpkg.com/@tailwindcss/browser@4"></script>
    <style>
        body { background-color: #0f172a; color: white; }
        .glass { background: rgba(255, 255, 255, 0.05); backdrop-filter: blur(10px); }
    </style>
</head>
<body class="min-h-screen">
<nav class="glass sticky top-0 z-50 border-b border-white/10 px-6 py-4 flex justify-between items-center">
    <a href="index.php" class="text-2xl font-bold bg-gradient-to-r from-cyan-400 to-blue-500 bg-clip-text text-transparent italic">
        Zeoraz
    </a>
    <div class="space-x-6 flex items-center">
        <a href="index.php" class="hover:text-cyan-400 transition">Market</a>
        <?php if(isset($_SESSION['user_id'])): ?>
            <a href="dashboard.php" class="hover:text-cyan-400 transition">Dashboard</a>
            <a href="auth/logout.php" class="bg-red-500/20 text-red-400 px-4 py-2 rounded-lg border border-red-500/30 hover:bg-red-500/30 transition">Logout</a>
        <?php else: ?>
            <a href="auth/login.php" class="hover:text-cyan-400 transition">Login</a>
            <a href="auth/register.php" class="bg-cyan-500 text-black px-6 py-2 rounded-full font-bold hover:bg-cyan-400 transition">Get Started</a>
        <?php endif; ?>
    </div>
</nav>
<main class="max-w-7xl mx-auto px-6 py-12">
