<?php
require_once __DIR__ . '/../core/utils.php';
$role = get_user_role(); 
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="description" content="Zeoraz - The next generation multi-vendor marketplace for premium goods. Secure, fast, and elegant commerce experience.">
    <meta name="keywords" content="ecommerce, marketplace, premium goods, Zeoraz, verified vendors">
    <title>Zeoraz | Premium Multi-Vendor Ecosystem</title>
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Outfit:wght@100;300;400;500;700;900&display=swap" rel="stylesheet">
    <script src="https://unpkg.com/@tailwindcss/browser@4"></script>
    <style>
        :root {
            --cyan: #06b6d4;
            --blue: #2563eb;
            --purple: #7c3aed;
            --bg-primary: #f8fafc;
            --text-primary: #0f172a;
            --text-secondary: #64748b;
        }
        body { 
            background-color: var(--bg-primary); 
            color: var(--text-primary); 
            font-family: 'Outfit', system-ui, sans-serif;
            overflow-x: hidden;
        }
        /* Premium Pearl Background */
        .bg-mesh {
            position: fixed;
            top: 0; left: 0; width: 100vw; height: 100vh;
            z-index: -1;
            background: 
                radial-gradient(circle at 0% 0%, rgba(6, 182, 212, 0.05) 0%, transparent 50%),
                radial-gradient(circle at 100% 100%, rgba(124, 58, 237, 0.05) 0%, transparent 50%),
                radial-gradient(circle at 50% 50%, rgba(37, 99, 235, 0.03) 0%, transparent 50%);
            filter: blur(80px);
        }
        .bg-grid {
            position: fixed;
            top: 0; left: 0; width: 100vw; height: 100vh;
            z-index: -1;
            background-image: 
                linear-gradient(to right, rgba(15, 23, 42, 0.02) 1px, transparent 1px),
                linear-gradient(to bottom, rgba(15, 23, 42, 0.02) 1px, transparent 1px);
            background-size: 50px 50px;
            mask-image: radial-gradient(circle at 50% 50%, black 30%, transparent 90%);
        }
        .glass { 
            background: rgba(255, 255, 255, 0.7); 
            backdrop-filter: blur(16px); 
            border: 1px solid rgba(15, 23, 42, 0.05); 
            box-shadow: 0 4px 30px rgba(0, 0, 0, 0.03);
        }
        .text-glow { text-shadow: 0 0 20px rgba(6, 182, 212, 0.2); }
        
        /* Typography Scale Up */
        h1, h2, h3 { color: #0f172a; }
        p { color: #475569; }
    </style>
</head>
<body class="min-h-screen">
<div class="bg-mesh"></div>
<div class="bg-grid"></div>
<nav class="glass sticky top-0 z-50 border-b border-black/5 px-6 py-4 flex justify-between items-center">
    <a href="<?php echo BASE_URL; ?>index.php" class="text-2xl font-black bg-gradient-to-r from-cyan-600 to-blue-700 bg-clip-text text-transparent italic">
        Zeoraz
    </a>
    <div class="space-x-8 flex items-center font-bold text-sm">
        <a href="<?php echo BASE_URL; ?>index.php" class="text-slate-600 hover:text-cyan-600 transition">Marketplace</a>
        <?php if(isset($_SESSION['user_id'])): ?>
            <?php if($role === 'SELLER' || $role === 'ADMIN'): ?>
                <a href="<?php echo BASE_URL; ?>pages/analytics.php" class="text-slate-600 hover:text-cyan-600 transition">Analytics</a>
            <?php endif; ?>
            <a href="<?php echo BASE_URL; ?>seller/dashboard.php" class="text-slate-600 hover:text-cyan-600 transition">Dashboard</a>
            <a href="<?php echo BASE_URL; ?>auth/logout.php" class="bg-red-50 text-red-600 px-4 py-2 rounded-xl border border-red-100 hover:bg-red-100 transition">Logout</a>
        <?php else: ?>
            <a href="<?php echo BASE_URL; ?>auth/login.php" class="text-slate-600 hover:text-cyan-600 transition">Sign In</a>
            <a href="<?php echo BASE_URL; ?>auth/register.php" class="bg-cyan-600 text-white px-6 py-2.5 rounded-xl font-black shadow-lg shadow-cyan-600/20 hover:bg-cyan-500 hover:-translate-y-0.5 transition duration-300">
                Get Started
            </a>
        <?php endif; ?>
    </div>
</nav>
<main class="max-w-7xl mx-auto px-6 py-12">
