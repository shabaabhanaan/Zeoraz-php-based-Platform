<?php
require_once __DIR__ . '/../core/utils.php';
$role = get_user_role(); 
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Zeoraz - Multi-Vendor Market</title>
    <script src="https://unpkg.com/@tailwindcss/browser@4"></script>
    <style>
        :root {
            --cyan: #22d3ee;
            --blue: #3b82f6;
            --purple: #a855f7;
        }
        body { 
            background-color: #020617; 
            color: white; 
            font-family: 'Inter', system-ui, sans-serif;
            overflow-x: hidden;
        }
        /* Premium Mesh Background */
        .bg-mesh {
            position: fixed;
            top: 0; left: 0; width: 100vw; height: 100vh;
            z-index: -1;
            background: 
                radial-gradient(circle at 0% 0%, rgba(34, 211, 238, 0.08) 0%, transparent 50%),
                radial-gradient(circle at 100% 100%, rgba(168, 85, 247, 0.08) 0%, transparent 50%),
                radial-gradient(circle at 50% 50%, rgba(59, 130, 246, 0.05) 0%, transparent 50%);
            filter: blur(80px);
        }
        .bg-grid {
            position: fixed;
            top: 0; left: 0; width: 100vw; height: 100vh;
            z-index: -1;
            background-image: 
                linear-gradient(to right, rgba(255,255,255,0.02) 1px, transparent 1px),
                linear-gradient(to bottom, rgba(255,255,255,0.02) 1px, transparent 1px);
            background-size: 50px 50px;
            mask-image: radial-gradient(circle at 50% 50%, black 30%, transparent 80%);
        }
        .glass { background: rgba(255, 255, 255, 0.03); backdrop-filter: blur(12px); border: 1px solid rgba(255,255,255,0.08); }
        .text-glow { text-shadow: 0 0 20px rgba(34, 211, 238, 0.4); }
    </style>
</head>
<body class="min-h-screen">
<div class="bg-mesh"></div>
<div class="bg-grid"></div>
<nav class="glass sticky top-0 z-50 border-b border-white/10 px-6 py-4 flex justify-between items-center">
    <a href="<?php echo BASE_URL; ?>index.php" class="text-2xl font-bold bg-gradient-to-r from-cyan-400 to-blue-500 bg-clip-text text-transparent italic">
        Zeoraz
    </a>
    <div class="space-x-6 flex items-center">
        <a href="<?php echo BASE_URL; ?>index.php" class="hover:text-cyan-400 transition">Home</a>
        <?php if(isset($_SESSION['user_id'])): ?>
            <?php if($role === 'SELLER' || $role === 'ADMIN'): ?>
                <a href="<?php echo BASE_URL; ?>pages/analytics.php" class="hover:text-cyan-400 transition">Analytics</a>
            <?php endif; ?>
            <a href="<?php echo BASE_URL; ?>seller/dashboard.php" class="hover:text-cyan-400 transition">Dashboard</a>
            <a href="<?php echo BASE_URL; ?>auth/logout.php" class="bg-red-500/20 text-red-400 px-4 py-2 rounded-lg border border-red-500/30 hover:bg-red-500/30 transition">Logout</a>
        <?php else: ?>
            <a href="<?php echo BASE_URL; ?>auth/login.php" class="hover:text-cyan-400 transition">Login</a>
            <a href="<?php echo BASE_URL; ?>auth/register.php" class="bg-cyan-500 text-black px-6 py-2 rounded-full font-bold hover:bg-cyan-400 transition">Get Started</a>
        <?php endif; ?>
    </div>
</nav>
<main class="max-w-7xl mx-auto px-6 py-12">
