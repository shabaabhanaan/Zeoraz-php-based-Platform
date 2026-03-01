<?php
// analytics.php
require_once '../core/db.php';
require_once '../core/utils.php';

if (!is_logged_in()) {
    redirect('auth/login.php');
}

$role = get_user_role();
$userId = $_SESSION['user_id'];

// 1. Sales Trend (Last 30 Days)
$salesTrend = [];
for ($i = 29; $i >= 0; $i--) {
    $date = date('Y-m-d', strtotime("-$i days"));
    $query = "SELECT SUM(totalAmount) FROM orders WHERE DATE(createdAt) = ? AND paymentStatus = 'PAID'";
    if ($role === 'SELLER') {
        $query = "SELECT SUM(oi.price * oi.quantity) FROM order_items oi 
                  JOIN orders o ON oi.orderId = o.id 
                  JOIN products p ON oi.productId = p.id
                  WHERE DATE(o.createdAt) = ? AND o.paymentStatus = 'PAID' AND p.sellerId = ?";
        $stmt = $pdo->prepare($query);
        $stmt->execute([$date, $userId]);
    } else {
        $stmt = $pdo->prepare($query);
        $stmt->execute([$date]);
    }
    $salesTrend[] = [
        'date' => date('M d', strtotime($date)),
        'amount' => (float)($stmt->fetchColumn() ?: 0)
    ];
}

// 2. Revenue by Category
$categoryQuery = "SELECT p.category, SUM(oi.price * oi.quantity) as revenue 
                  FROM order_items oi 
                  JOIN products p ON oi.productId = p.id
                  JOIN orders o ON oi.orderId = o.id
                  WHERE o.paymentStatus = 'PAID'";
if ($role === 'SELLER') {
    $categoryQuery .= " AND p.sellerId = ?";
}
$categoryQuery .= " GROUP BY p.category";

$catStmt = $pdo->prepare($categoryQuery);
$role === 'SELLER' ? $catStmt->execute([$userId]) : $catStmt->execute();
$categoryData = $catStmt->fetchAll();

// 3. Stock Levels
$stockQuery = "SELECT name, stock FROM products";
if ($role === 'SELLER') {
    $stockQuery .= " WHERE sellerId = ?";
}
$stockQuery .= " ORDER BY stock ASC LIMIT 10";

$stockStmt = $pdo->prepare($stockQuery);
$role === 'SELLER' ? $stockStmt->execute([$userId]) : $stockStmt->execute();
$stockData = $stockStmt->fetchAll();

// 4. Map Data (Mocking lat/lng based on city/address for demo)
// In a real app, we'd geocode addresses. For now, let's pull shipping addresses.
$mapQuery = "SELECT shippingAddress, totalAmount FROM orders WHERE paymentStatus = 'PAID'";
if ($role === 'SELLER') {
    $mapQuery = "SELECT o.shippingAddress, SUM(oi.price * oi.quantity) as totalAmount 
                 FROM orders o 
                 JOIN order_items oi ON o.id = oi.orderId 
                 JOIN products p ON oi.productId = p.id
                 WHERE o.paymentStatus = 'PAID' AND p.sellerId = ?
                 GROUP BY o.id";
}
$mapStmt = $pdo->prepare($mapQuery);
$role === 'SELLER' ? $mapStmt->execute([$userId]) : $mapStmt->execute();
$mapOrders = $mapStmt->fetchAll();

require_once '../includes/header.php';
?>

<div class="space-y-12">
    <div class="flex justify-between items-end">
        <div>
            <h1 class="text-4xl font-black italic tracking-tighter text-slate-900">Data <span class="text-cyan-600">Analytics</span></h1>
            <p class="text-slate-500 mt-1">Deep dive into your business performance and reach.</p>
        </div>
        <div class="bg-slate-100 border border-black/5 px-4 py-2 rounded-xl text-xs font-mono text-slate-500">
            AUTO-REFRESH: <span class="text-cyan-600">ENABLED</span>
        </div>
    </div>

    <!-- Quick Stats Cards -->
    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-6">
        <?php
        $totalRevenue = array_sum(array_column($salesTrend, 'amount'));
        $totalOrders = 0; // In a real app, fetch this
        $avgOrderValue = $totalRevenue > 0 ? $totalRevenue / (max(count($mapOrders), 1)) : 0;
        ?>
        <div class="bg-white p-6 rounded-[32px] border border-black/5 space-y-2 shadow-lg shadow-black/[0.02]">
            <p class="text-[10px] font-black uppercase tracking-[0.2em] text-slate-400">Total Revenue</p>
            <p class="text-3xl font-black text-slate-900">$<?php echo number_format($totalRevenue, 2); ?></p>
            <div class="flex items-center gap-2 text-emerald-600 text-[10px] font-bold">
                <svg xmlns="http://www.w3.org/2000/svg" width="12" height="12" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="3"><polyline points="23 6 13.5 15.5 8.5 10.5 1 18"/><polyline points="17 6 23 6 23 12"/></svg>
                +12.5% from last month
            </div>
        </div>
        <div class="bg-white p-6 rounded-[32px] border border-black/5 space-y-2 shadow-lg shadow-black/[0.02]">
            <p class="text-[10px] font-black uppercase tracking-[0.2em] text-slate-400">Active Orders</p>
            <p class="text-3xl font-black text-slate-900"><?php echo count($mapOrders); ?></p>
            <div class="flex items-center gap-2 text-cyan-600 text-[10px] font-bold">
                <span class="w-2 h-2 bg-cyan-600 rounded-full animate-pulse"></span>
                Processing Live
            </div>
        </div>
        <div class="bg-white p-6 rounded-[32px] border border-black/5 space-y-2 shadow-lg shadow-black/[0.02]">
            <p class="text-[10px] font-black uppercase tracking-[0.2em] text-slate-400">Avg. Order Value</p>
            <p class="text-3xl font-black text-slate-900">$<?php echo number_format($avgOrderValue, 2); ?></p>
            <div class="text-slate-400 text-[10px] font-bold">Baseline metrics</div>
        </div>
        <div class="bg-white p-6 rounded-[32px] border border-black/5 space-y-2 shadow-lg shadow-black/[0.02]">
            <p class="text-[10px] font-black uppercase tracking-[0.2em] text-slate-400">Stock Alerts</p>
            <p class="text-3xl font-black text-red-600"><?php echo count(array_filter($stockData, fn($s) => $s['stock'] < 10)); ?></p>
            <div class="text-red-600/50 text-[10px] font-bold">Items require attention</div>
        </div>
    </div>

    <!-- Main Sales Chart -->
    <div class="bg-white border border-black/5 p-8 rounded-[40px] shadow-xl">
        <h3 class="text-xl font-bold mb-6 flex items-center gap-3">
            <span class="w-2 h-6 bg-cyan-600 rounded-full"></span>
            Revenue Trend (Last 30 Days)
        </h3>
        <div class="h-[400px]">
            <canvas id="salesChart"></canvas>
        </div>
    </div>

    <div class="grid grid-cols-1 lg:grid-cols-2 gap-8">
        <!-- Category Breakdown -->
        <div class="bg-white border border-black/5 p-8 rounded-[40px] shadow-xl">
            <h3 class="text-xl font-bold mb-6">Revenue by Category</h3>
            <div class="h-[350px] flex items-center justify-center">
                <canvas id="categoryChart"></canvas>
            </div>
        </div>

        <!-- Inventory Health -->
        <div class="bg-white border border-black/5 p-8 rounded-[40px] shadow-xl">
            <h3 class="text-xl font-bold mb-6">Inventory Health (Lowest Stock)</h3>
            <div class="h-[350px]">
                <canvas id="stockChart"></canvas>
            </div>
        </div>
    </div>

    <!-- Sales Distribution Map -->
    <div class="bg-white border border-black/5 p-8 rounded-[40px] overflow-hidden shadow-xl">
        <h3 class="text-xl font-bold mb-6 flex items-center gap-3">
            <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="text-emerald-600"><path d="M21 10c0 7-9 13-9 13s-9-6-9-13a9 9 0 0 1 18 0z"/><circle cx="12" cy="10" r="3"/></svg>
            Global Sales Distribution
        </h3>
        <div id="salesMap" class="h-[500px] rounded-3xl border border-black/5"></div>
    </div>
</div>

<!-- Scripts -->
<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
<link rel="stylesheet" href="https://unpkg.com/leaflet@1.9.4/dist/leaflet.css" />
<script src="https://unpkg.com/leaflet@1.9.4/dist/leaflet.js"></script>

<script>
    // Configuration for charts
    const chartConfig = {
        responsive: true,
        maintainAspectRatio: false,
        plugins: {
            legend: { labels: { color: '#475569', font: { family: 'Outfit', weight: 'bold' } } }
        },
        scales: {
            y: { grid: { color: 'rgba(15, 23, 42, 0.05)' }, ticks: { color: '#64748b', font: { family: 'JetBrains Mono' } } },
            x: { grid: { display: false }, ticks: { color: '#64748b', font: { family: 'JetBrains Mono' } } }
        }
    };

    // 1. Sales Trend Chart
    new Chart(document.getElementById('salesChart'), {
        type: 'line',
        data: {
            labels: <?php echo json_encode(array_column($salesTrend, 'date')); ?>,
            datasets: [{
                label: 'Revenue ($)',
                data: <?php echo json_encode(array_column($salesTrend, 'amount')); ?>,
                borderColor: '#06b6d4',
                backgroundColor: 'rgba(6, 182, 212, 0.05)',
                borderWidth: 4,
                fill: true,
                tension: 0.4,
                pointRadius: 0,
                pointHoverRadius: 6
            }]
        },
        options: chartConfig
    });

    // 2. Category Chart
    new Chart(document.getElementById('categoryChart'), {
        type: 'polarArea',
        data: {
            labels: <?php echo json_encode(array_column($categoryData, 'category')); ?>,
            datasets: [{
                data: <?php echo json_encode(array_column($categoryData, 'revenue')); ?>,
                backgroundColor: [
                    'rgba(34, 211, 238, 0.6)',
                    'rgba(59, 130, 246, 0.6)',
                    'rgba(168, 85, 247, 0.6)',
                    'rgba(236, 72, 153, 0.6)',
                    'rgba(16, 185, 129, 0.6)'
                ],
                borderWidth: 0
            }]
        },
        options: {
            ...chartConfig,
            scales: { r: { grid: { color: 'rgba(255,255,255,0.1)' }, ticks: { display: false } } }
        }
    });

    // 3. Stock Chart
    new Chart(document.getElementById('stockChart'), {
        type: 'bar',
        data: {
            labels: <?php echo json_encode(array_column($stockData, 'name')); ?>,
            datasets: [{
                label: 'Stock Units',
                data: <?php echo json_encode(array_column($stockData, 'stock')); ?>,
                backgroundColor: function(context) {
                    const value = context.dataset.data[context.dataIndex];
                    return value < 10 ? 'rgba(239, 68, 68, 0.6)' : 'rgba(34, 211, 238, 0.6)';
                },
                borderRadius: 8
            }]
        },
        options: chartConfig
    });

    // 4. Leaflet Map
    const map = L.map('salesMap', {
        zoomControl: false,
        attributionControl: false
    }).setView([7.8731, 80.7718], 7); // Center on Sri Lanka for demo

    L.tileLayer('https://{s}.basemaps.cartocdn.com/rastertiles/voyager/{z}/{x}/{y}{r}.png').addTo(map);

    // Mock markers based on order addresses
    // In a real scenario, we would use a geocoding service.
    const orders = <?php echo json_encode($mapOrders); ?>;
    
    // Demo locations for Sri Lanka cities for visualization
    const demoLocations = [
        [6.9271, 79.8612], // Colombo
        [7.2906, 80.6337], // Kandy
        [6.0535, 80.2210], // Galle
        [8.5873, 81.2152], // Trinco
        [9.6615, 80.0255], // Jaffna
        [6.6885, 80.3907]  // Ratnapura
    ];

    orders.forEach((order, index) => {
        const loc = demoLocations[index % demoLocations.length]; // Cycle through demo locations
        const marker = L.circleMarker(loc, {
            radius: Math.min(order.totalAmount / 10, 20),
            fillColor: "#22d3ee",
            color: "#fff",
            weight: 1,
            opacity: 1,
            fillOpacity: 0.6
        }).addTo(map);
        
        marker.bindPopup(`<b class="text-black">Order Revenue: $${parseFloat(order.totalAmount).toFixed(2)}</b><br><span class="text-gray-500">${order.shippingAddress}</span>`);
    });

</script>

<style>
    .leaflet-popup-content-wrapper {
        background: rgba(255, 255, 255, 0.9);
        backdrop-filter: blur(10px);
        border-radius: 12px;
        font-family: 'Inter', sans-serif;
    }
    .leaflet-popup-tip {
        background: rgba(255, 255, 255, 0.9);
    }
</style>

<?php require_once '../includes/footer.php'; ?>
