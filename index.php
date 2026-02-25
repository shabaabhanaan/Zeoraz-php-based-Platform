<?php
// index.php
require_once 'core/db.php';
require_once 'includes/header.php';

// Fetch products
try {
    $stmt = $pdo->query("SELECT * FROM products WHERE status = 'ACTIVE' ORDER BY createdAt DESC LIMIT 12");
    $products = $stmt->fetchAll();
} catch (PDOException $e) {
    $products = [];
}

// Fetch some vendor locations for the map
try {
    $vendorStmt = $pdo->query("SELECT storeName, description FROM vendor_profiles WHERE status = 'ACTIVE' LIMIT 10");
    $vendors = $vendorStmt->fetchAll();
} catch (PDOException $e) {
    $vendors = [];
}
?>

<!-- Hero Section -->
<div class="relative py-24 sm:py-32 overflow-hidden mb-24">
    <div class="absolute inset-0 bg-[url('https://images.unsplash.com/photo-1639762681485-074b7f938ba0?q=80&w=2832&auto=format&fit=crop')] bg-cover bg-center opacity-10"></div>
    <div class="absolute inset-0 bg-gradient-to-b from-transparent via-[#0f172a]/80 to-[#0f172a]"></div>
    
    <div class="relative text-center space-y-8 max-w-4xl mx-auto px-6">
        <div class="inline-flex items-center gap-2 px-4 py-2 rounded-full bg-cyan-500/10 border border-cyan-500/20 text-cyan-400 text-xs font-black uppercase tracking-widest animate-pulse">
            <span class="w-2 h-2 bg-cyan-400 rounded-full"></span>
            Next Gen Commerce is Here
        </div>
        <h1 class="text-7xl md:text-8xl font-black bg-gradient-to-b from-white to-white/40 bg-clip-text text-transparent tracking-tighter leading-tight">
            Everything you need,<br><span class="text-cyan-400 italic">Simplified.</span>
        </h1>
        <p class="text-white/50 text-xl md:text-2xl max-w-2xl mx-auto font-medium leading-relaxed">
            Experience the future of digital retail. A premium multi-vendor ecosystem designed for high-performance commerce.
        </p>
        <div class="flex flex-col sm:flex-row justify-center gap-6 pt-6">
            <a href="auth/register.php" class="bg-white text-black px-10 py-4 rounded-2xl font-black text-lg hover:scale-105 transition shadow-2xl shadow-white/10 active:scale-95">Explore Marketplace</a>
            <a href="#network" class="glass border border-white/10 px-10 py-4 rounded-2xl font-black text-lg hover:bg-white/5 transition active:scale-95">Our Network</a>
        </div>
    </div>
</div>

<!-- Service Highlights -->
<div class="grid grid-cols-1 md:grid-cols-3 gap-8 mb-32">
    <div class="glass p-8 rounded-[40px] border border-white/10 space-y-4 hover:border-cyan-500/30 transition duration-500">
        <div class="w-12 h-12 bg-cyan-500/20 rounded-2xl flex items-center justify-center text-cyan-400">
            <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round"><path d="M12 22s8-4 8-10V5l-8-3-8 3v7c0 6 8 10 8 10"/></svg>
        </div>
        <h3 class="text-xl font-bold">Secure Transactions</h3>
        <p class="text-white/40 text-sm leading-relaxed">Encrypted payments powered by PayHere Level 1 PCI DSS security standards.</p>
    </div>
    <div class="glass p-8 rounded-[40px] border border-white/10 space-y-4 hover:border-blue-500/30 transition duration-500 text-center">
        <div class="mx-auto w-12 h-12 bg-blue-500/20 rounded-2xl flex items-center justify-center text-blue-400">
            <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round"><circle cx="12" cy="12" r="10"/><polyline points="12 6 12 12 16 14"/></svg>
        </div>
        <h3 class="text-xl font-bold">24/7 Deployment</h3>
        <p class="text-white/40 text-sm leading-relaxed">Instant order processing and automated logistics for a seamless shopping experience.</p>
    </div>
    <div class="glass p-8 rounded-[40px] border border-white/10 space-y-4 hover:border-purple-500/30 transition duration-500 text-right">
        <div class="ml-auto w-12 h-12 bg-purple-500/20 rounded-2xl flex items-center justify-center text-purple-400">
            <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round"><path d="m16 6 4 14-8-4-8 4 4-14"/><path d="M12 2v2.9"/><path d="M12 11.1V13"/></svg>
        </div>
        <h3 class="text-xl font-bold">Verified Vendors</h3>
        <p class="text-white/40 text-sm leading-relaxed">Every seller is vetted and verified to ensure the highest quality of service and products.</p>
    </div>
</div>

<!-- Section Header -->
<div class="flex flex-col md:flex-row justify-between items-end gap-6 mb-12">
    <div>
        <h2 class="text-4xl font-black italic tracking-tighter">Featured <span class="text-cyan-400">Drops</span></h2>
        <p class="text-white/40 mt-1">Hand-picked collection of the week's most trending items.</p>
    </div>
    <a href="#" class="text-sm font-black uppercase tracking-widest text-white/30 hover:text-cyan-400 transition">View All Products &rarr;</a>
</div>

<!-- Product Grid -->
<div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-8 mb-32">
    <?php foreach ($products as $product): ?>
        <div class="glass border border-white/10 rounded-[32px] p-5 group hover:border-cyan-500/50 transition duration-700 flex flex-col shadow-xl hover:shadow-cyan-500/5">
            <a href="product_details.php?id=<?php echo $product['id']; ?>" class="block relative aspect-square bg-white/[0.03] rounded-3xl mb-5 overflow-hidden border border-white/5">
                <?php if($product['image']): ?>
                    <img src="<?php echo htmlspecialchars($product['image']); ?>" class="w-full h-full object-cover group-hover:scale-110 transition duration-1000">
                <?php else: ?>
                    <div class="w-full h-full flex items-center justify-center text-white/5 font-black text-6xl uppercase tracking-tighter opacity-20">Zeoraz</div>
                <?php endif; ?>
                <div class="absolute top-4 right-4 px-3 py-1.5 bg-black/60 backdrop-blur-xl rounded-full text-[10px] font-black uppercase tracking-widest border border-white/10 text-cyan-400">
                    <?php echo htmlspecialchars($product['category'] ?? 'General'); ?>
                </div>
            </a>
            
            <div class="flex-1 space-y-2 px-1">
                <h3 class="font-bold text-xl leading-tight truncate group-hover:text-cyan-400 transition"><?php echo htmlspecialchars($product['name']); ?></h3>
                <p class="text-white/30 text-xs line-clamp-2 italic h-8 leading-relaxed"><?php echo htmlspecialchars($product['description']); ?></p>
            </div>

            <div class="mt-8 flex justify-between items-center bg-white/[0.03] p-2.5 rounded-2xl border border-white/5 group-hover:border-cyan-500/20 transition duration-500">
                <div class="pl-3">
                    <span class="text-[10px] font-black uppercase tracking-widest text-white/20 block mb-0.5">Price (USD)</span>
                    <span class="font-black text-2xl text-white font-mono">$<?php echo number_format($product['price'], 2); ?></span>
                </div>
                <button onclick="addToCart('<?php echo $product['id']; ?>', this)" 
                    class="bg-cyan-500 hover:bg-cyan-400 p-4 rounded-xl text-black transition shadow-lg shadow-cyan-500/20 active:scale-90 flex items-center justify-center">
                    <svg xmlns="http://www.w3.org/2000/svg" width="22" height="22" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="3" stroke-linecap="round" stroke-linejoin="round"><circle cx="8" cy="21" r="1"/><circle cx="19" cy="21" r="1"/><path d="M2.05 2.05h2l2.66 12.42a2 2 0 0 0 2 1.58h9.78a2 2 0 0 0 1.95-1.57l1.65-7.43H5.12"/></svg>
                </button>
            </div>
        </div>
    <?php endforeach; ?>
    
    <?php if(empty($products)): ?>
        <div class="col-span-full text-center py-32 border-2 border-dashed border-white/5 rounded-[50px] bg-white/[0.01]">
            <div class="w-16 h-16 bg-white/5 rounded-2xl flex items-center justify-center mx-auto mb-6 text-white/20">
                 <svg xmlns="http://www.w3.org/2000/svg" width="32" height="32" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M6 2 3 6v14a2 2 0 0 0 2 2h14a2 2 0 0 0 2-2V6l-3-4Z"/><path d="M3 6h18"/><path d="M16 10a4 4 0 0 1-8 0"/></svg>
            </div>
            <p class="text-white/20 text-2xl font-bold italic tracking-tighter">The marketplace is currently resetting... check back soon.</p>
        </div>
    <?php endif; ?>
</div>

<!-- Vendor Network Map -->
<div id="network" class="space-y-12 mb-32">
    <div class="text-center">
        <h2 class="text-5xl font-black italic tracking-tighter">Our Global <span class="text-emerald-400">Network</span></h2>
        <p class="text-white/40 mt-2 text-lg">Connecting thousands of verified vendors with customers across the globe.</p>
    </div>
    <div class="glass border border-white/10 p-4 rounded-[40px] overflow-hidden shadow-2xl relative">
        <div class="absolute top-10 left-10 z-[10] flex flex-col gap-2">
            <div class="bg-black/60 backdrop-blur-xl px-4 py-2 rounded-xl border border-white/10 flex items-center gap-3">
                <span class="w-2 h-2 bg-emerald-400 rounded-full animate-ping"></span>
                <span class="text-xs font-black uppercase tracking-widest text-emerald-400">Live Vendor Access</span>
            </div>
        </div>
    <div id="vendorMap" class="h-[600px] rounded-[32px] border border-white/10"></div>
    </div>
</div>

<!-- About the Builder Section -->
<!-- About the Builder Section -->
<div class="mb-32 max-w-6xl mx-auto px-6">
    <div class="glass border border-white/10 p-8 md:p-12 rounded-[40px] overflow-hidden relative group">
        <!-- Background Glow -->
        <div class="absolute -top-24 -right-24 w-80 h-80 bg-cyan-500/10 rounded-full blur-[80px] group-hover:bg-cyan-500/15 transition-all duration-1000"></div>
        <div class="absolute -bottom-24 -left-24 w-80 h-80 bg-blue-500/10 rounded-full blur-[80px] group-hover:bg-blue-500/15 transition-all duration-1000"></div>

        <div class="relative grid grid-cols-1 lg:grid-cols-2 gap-12 items-center">
            <!-- Image / Visual Side -->
            <div class="relative max-w-sm mx-auto lg:mx-0">
                <div class="aspect-square rounded-[32px] bg-gradient-to-br from-cyan-500/10 via-blue-500/10 to-purple-500/10 border border-white/10 flex items-center justify-center overflow-hidden shadow-xl relative">
                    <img src="assets/img/builder_profile.jpg" alt="Shabaab Hanaan" class="w-full h-full object-cover group-hover:scale-105 transition duration-1000">
                    <div class="absolute inset-0 bg-grid-small-white/[0.05] [mask-image:radial-gradient(white,transparent)]"></div>
                </div>
            </div>

            <!-- Content Side -->
            <div class="space-y-6">
                <div class="space-y-3">
                    <h3 class="text-xs font-black uppercase tracking-[0.3em] text-cyan-400">The Architect</h3>
                    <h2 class="text-4xl md:text-5xl font-black italic tracking-tighter leading-none">Visionary behind <span class="bg-gradient-to-r from-white to-white/40 bg-clip-text text-transparent">Zeoraz.</span></h2>
                    <h4 class="text-xl font-bold text-white mt-1">Shabaab Hanaan</h4>
                </div>
                
                <p class="text-white/50 text-lg leading-relaxed font-medium">
                    "My mission was to bridge the gap between high-end digital architecture and seamless vendor accessibility. Zeoraz isn't just a marketplace; it's a testament to the power of simplified, high-performance commerce."
                </p>

                <div class="grid grid-cols-2 gap-6 py-5 border-y border-white/5">
                    <div>
                        <span class="text-[9px] font-black uppercase tracking-widest text-white/20 block mb-1.5">Primary Stack</span>
                        <div class="flex flex-wrap gap-2">
                            <span class="px-2.5 py-0.5 bg-white/5 border border-white/10 rounded-md text-[11px] font-bold">PHP 8.2</span>
                            <span class="px-2.5 py-0.5 bg-white/5 border border-white/10 rounded-md text-[11px] font-bold">Tailwind</span>
                            <span class="px-2.5 py-0.5 bg-white/5 border border-white/10 rounded-md text-[11px] font-bold">MySQL</span>
                        </div>
                    </div>
                    <div>
                        <span class="text-[9px] font-black uppercase tracking-widest text-white/20 block mb-1.5">Specialization</span>
                        <p class="text-[11px] font-black text-white/70 italic uppercase tracking-wider">Modular System Architecture</p>
                    </div>
                </div>

                <div class="flex items-center gap-5">
                    <div class="flex -space-x-3">
                        <div class="w-10 h-10 rounded-full border-2 border-[#090e1a] bg-cyan-500/10 flex items-center justify-center font-black text-xs">JS</div>
                        <div class="w-10 h-10 rounded-full border-2 border-[#090e1a] bg-blue-500/10 flex items-center justify-center font-black text-xs">PY</div>
                        <div class="w-10 h-10 rounded-full border-2 border-[#090e1a] bg-purple-500/10 flex items-center justify-center font-black text-xs">SQL</div>
                    </div>
                    <div>
                        <p class="text-[11px] text-white/30 font-medium">Building resilient digital ecosystems</p>
                    </div>
                </div>

                <div class="pt-4">
                    <a href="https://www.linkedin.com/in/hanaan-fasni" target="_blank" class="inline-flex items-center gap-2.5 bg-[#0a66c2]/10 border border-[#0a66c2]/20 px-5 py-2.5 rounded-lg text-[#0a66c2] hover:bg-[#0a66c2]/20 transition text-sm font-bold group/link">
                        <svg xmlns="http://www.w3.org/2000/svg" width="18" height="18" viewBox="0 0 24 24" fill="currentColor"><path d="M19 0h-14c-2.761 0-5 2.239-5 5v14c0 2.761 2.239 5 5 5h14c2.762 0 5-2.239 5-5v-14c0-2.761-2.238-5-5-5zm-11 19h-3v-11h3v11zm-1.5-12.268c-.966 0-1.75-.79-1.75-1.764s.784-1.764 1.75-1.764 1.75.79 1.75 1.764-.783 1.764-1.75 1.764zm13.5 12.268h-3v-5.604c0-3.368-4-3.113-4 0v5.604h-3v-11h3v1.765c1.396-2.586 7-2.777 7 2.476v6.759z"/></svg>
                        LinkedIn Profile
                        <svg xmlns="http://www.w3.org/2000/svg" width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="translate-x-0 group-hover/link:translate-x-1 transition"><path d="M5 12h14"/><path d="m12 5 7 7-7 7"/></svg>
                    </a>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Map Scripts -->
<link rel="stylesheet" href="https://unpkg.com/leaflet@1.9.4/dist/leaflet.css" />
<script src="https://unpkg.com/leaflet@1.9.4/dist/leaflet.js"></script>

<script>
    // Initialize Map
    const map = L.map('vendorMap', {
        zoomControl: false,
        attributionControl: false
    }).setView([7.8731, 80.7718], 8); // Sri Lanka

    L.tileLayer('https://{s}.basemaps.cartocdn.com/dark_all/{z}/{x}/{y}{r}.png').addTo(map);

    // Vendor Markers
    const vendorData = <?php echo json_encode($vendors); ?>;
    const demoLocations = [
        [6.9271, 79.8612], [7.2906, 80.6337], [6.0535, 80.2210], 
        [8.5873, 81.2152], [9.6615, 80.0255], [6.6885, 80.3907],
        [7.0840, 80.0098], [7.2111, 79.8386], [6.8402, 79.9984]
    ];

    vendorData.forEach((v, i) => {
        const loc = demoLocations[i % demoLocations.length];
        const marker = L.circleMarker(loc, {
            radius: 8,
            fillColor: "#10b981",
            color: "#fff",
            weight: 2,
            opacity: 1,
            fillOpacity: 0.8
        }).addTo(map);

        marker.bindPopup(`
            <div class="p-2 space-y-1">
                <h4 class="font-black text-lg text-emerald-600 m-0">${v.storeName}</h4>
                <p class="text-gray-500 text-xs italic m-0">${v.description.substring(0, 60)}...</p>
                <div class="pt-2">
                   <span class="text-[10px] bg-emerald-100 text-emerald-800 px-2 py-0.5 rounded-full font-bold uppercase">Verified Seller</span>
                </div>
            </div>
        `);
    });

    // Custom Scroll Animation for Map
    document.querySelectorAll('a[href^="#"]').forEach(anchor => {
        anchor.addEventListener('click', function (e) {
            e.preventDefault();
            document.querySelector(this.getAttribute('href')).scrollIntoView({
                behavior: 'smooth'
            });
        });
    });
</script>

<style>
    .leaflet-popup-content-wrapper {
        background: rgba(255, 255, 255, 0.95);
        backdrop-filter: blur(10px);
        border-radius: 20px;
        padding: 4px;
        border: 1px solid rgba(0,0,0,0.1);
    }
    .leaflet-popup-tip-container { display: none; }
</style>

<script>
async function addToCart(id, btn) {
    const originalContent = btn.innerHTML;
    btn.innerHTML = '<svg class="animate-spin h-5 w-5 text-black" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24"><circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle><path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path></svg>';
    btn.disabled = true;

    const formData = new FormData();
    formData.append('action', 'add');
    formData.append('product_id', id);
    formData.append('quantity', 1);

    try {
        const res = await fetch('cart_handler.php', { method: 'POST', body: formData });
        if(!res.ok) throw new Error('Network error');
        const data = await res.json();
        
        if (data.success) {
            btn.innerHTML = '<svg xmlns="http://www.w3.org/2000/svg" width="22" height="22" viewBox="0 0 24 24" fill="none" stroke="black" stroke-width="4" stroke-linecap="round" stroke-linejoin="round"><polyline points="20 6 9 17 4 12"></polyline></svg>';
            btn.classList.replace('bg-cyan-500', 'bg-emerald-500');
            setTimeout(() => {
                btn.innerHTML = originalContent;
                btn.classList.replace('bg-emerald-500', 'bg-cyan-500');
                btn.disabled = false;
            }, 1000);
        } else {
            alert(data.message || 'Error adding to cart');
            btn.innerHTML = originalContent;
            btn.disabled = false;
        }
    } catch (e) {
        btn.innerHTML = originalContent;
        btn.disabled = false;
    }
}
</script>

<!-- Keep AI Chatbot from original file -->
<a href="#" id="chatbot-button" class="fixed bottom-10 right-10 w-14 h-14 bg-cyan-500 rounded-[18px] flex items-center justify-center shadow-2xl hover:bg-cyan-400 transition transform hover:-rotate-12 duration-500 z-50 group">
    <div class="absolute -top-3 -left-3 bg-white text-black text-[10px] font-black px-2 py-1 rounded-lg opacity-0 group-hover:opacity-100 transition duration-500 uppercase">AI AI</div>
    <svg xmlns="http://www.w3.org/2000/svg" class="w-7 h-7 text-black" fill="none" viewBox="0 0 24 24" stroke="currentColor">
        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M8 10h.01M12 10h.01M16 10h.01M21 12c0 4.418-4.03 8-9 8s-9-3.582-9-8 4.03-8 9-8 9 3.582 9 8z"/>
    </svg>
</a>

<div id="chat-popup" class="fixed bottom-28 right-10 w-80 bg-[#0f172a] rounded-[30px] shadow-2xl hidden flex-col overflow-hidden z-50 border border-white/10 glass">
    <div class="bg-cyan-500 text-black px-6 py-4 flex justify-between items-center font-black text-lg italic tracking-tighter">
        ZEORAZ AI
        <button id="close-chat" class="text-2xl">&times;</button>
    </div>
    <div id="chat-messages" class="p-6 h-80 overflow-y-auto space-y-4 text-xs"></div>
    <!-- Voice HUD -->
    <div id="voice-hud" class="hidden h-14 flex items-center justify-center gap-1.5 bg-cyan-500/10 border-t border-white/10">
        <style>
            @keyframes bounce { 0%, 100% { height: 8px; } 50% { height: 24px; } }
            .voice-bar { width: 3px; background: #22d3ee; border-radius: 4px; animation: bounce 1s infinite ease-in-out; }
            .bar-1 { animation-delay: 0.0s; } .bar-2 { animation-delay: 0.2s; } .bar-3 { animation-delay: 0.4s; }
            .bar-4 { animation-delay: 0.2s; } .bar-5 { animation-delay: 0.0s; }
        </style>
        <div class="voice-bar bar-1"></div>
        <div class="voice-bar bar-2"></div>
        <div class="voice-bar bar-3"></div>
        <div class="voice-bar bar-4"></div>
        <div class="voice-bar bar-5"></div>
        <span class="ml-3 text-[10px] font-black uppercase tracking-[0.2em] text-cyan-400 text-glow">Listening Intelligence</span>
    </div>

    <div class="p-4 border-t border-white/5 flex gap-2 bg-white/[0.02]">
        <button id="voice-trigger" class="bg-white/5 border border-white/10 text-cyan-400 p-3 rounded-xl hover:bg-white/10 transition active:scale-95 group relative">
             <svg xmlns="http://www.w3.org/2000/svg" width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round" class="group-active:animate-ping"><path d="M12 2a3 3 0 0 0-3 3v7a3 3 0 0 0 6 0V5a3 3 0 0 0-3-3Z"/><path d="M19 10v2a7 7 0 0 1-14 0v-2"/><line x1="12" x2="12" y1="19" y2="22"/></svg>
        </button>
        <input type="text" id="chat-input"
            class="flex-1 bg-white/5 border border-white/10 text-white rounded-xl px-4 py-3 focus:outline-none focus:border-cyan-500 transition font-medium text-xs"
            placeholder="How can we help?" />
        <button id="send-chat"
            class="bg-cyan-500 text-black px-4 rounded-xl font-black uppercase text-[10px] tracking-widest hover:bg-cyan-400 transition">
            Send
        </button>
    </div>
</div>

<script>
// Re-linking existing chatbot logic with updated selectors if needed
const chatBtn = document.getElementById('chatbot-button');
const chatPopup = document.getElementById('chat-popup');
const closeChat = document.getElementById('close-chat');
const sendBtn = document.getElementById('send-chat');
const chatInput = document.getElementById('chat-input');
const chatMessages = document.getElementById('chat-messages');
const voiceTrigger = document.getElementById('voice-trigger');
const voiceHud = document.getElementById('voice-hud');

// AI Voice Support Logic
const SpeechRecognition = window.SpeechRecognition || window.webkitSpeechRecognition;
const synthesis = window.speechSynthesis;
let recognition;

if (SpeechRecognition) {
    recognition = new SpeechRecognition();
    recognition.continuous = false;
    recognition.lang = 'en-US';

    recognition.onstart = () => {
        voiceHud.classList.remove('hidden');
        voiceTrigger.classList.add('bg-cyan-500/20', 'border-cyan-500/50');
    };

    recognition.onspeechend = () => {
        recognition.stop();
        voiceHud.classList.add('hidden');
        voiceTrigger.classList.remove('bg-cyan-500/20', 'border-cyan-500/50');
    };

    recognition.onresult = (event) => {
        const transcript = event.results[0][0].transcript;
        chatInput.value = transcript;
        handleVoiceIntent(transcript);
        sendMessage();
    };
}

voiceTrigger.addEventListener('click', () => {
    if (recognition) recognition.start();
    else alert('Voice recognition not supported in this browser.');
});

function handleVoiceIntent(text) {
    const lowerText = text.toLowerCase();
    
    // Intent Recognition Logic
    if (lowerText.includes('analytics') || lowerText.includes('show report')) {
        speakResponse("Opening your business analytics now.");
        setTimeout(() => window.location.href = 'pages/analytics.php', 1500);
    } else if (lowerText.includes('market') || lowerText.includes('home')) {
        speakResponse("Navigating back to the marketplace.");
        setTimeout(() => window.location.href = 'index.php', 1500);
    } else if (lowerText.includes('terms') || lowerText.includes('policy')) {
        speakResponse("I am showing you the legal and return policies.");
        setTimeout(() => window.location.href = 'pages/terms.php', 1500);
    }
}

function speakResponse(text) {
    if (!synthesis) return;
    synthesis.cancel(); // Stop current speaking
    const utterance = new SpeechSynthesisUtterance(text);
    // Find a premium voice
    const voices = synthesis.getVoices();
    utterance.voice = voices.find(v => v.name.includes('Google') || v.name.includes('Premium')) || voices[0];
    utterance.pitch = 1.1;
    utterance.rate = 1.0;
    synthesis.speak(utterance);
}

let welcomeSent = false;
chatBtn.addEventListener('click', (e) => {
    e.preventDefault();
    chatPopup.classList.toggle('hidden');
    if (!chatPopup.classList.contains('hidden')) {
        chatInput.focus();
        if (!welcomeSent) {
            addMessage('Welcome to Zeoraz. I am your AI assistant. How can I facilitate your experience today?', 'bot');
            welcomeSent = true;
        }
    }
});

closeChat.addEventListener('click', () => {
    chatPopup.classList.add('hidden');
});

sendBtn.addEventListener('click', sendMessage);
chatInput.addEventListener('keypress', (e) => {
    if (e.key === 'Enter') sendMessage();
});

async function sendMessage() {
    const msg = chatInput.value.trim();
    if (!msg) return;
    addMessage(msg, 'user');
    chatInput.value = '';
    const typingId = addMessage('Thinking...', 'bot', true);
    try {
        const response = await fetch('api/chat_ai.php', {
            method: 'POST',
            headers: { 'Content-Type': 'application/json' },
            body: JSON.stringify({ message: msg })
        });
        const data = await response.json();
        removeMessage(typingId);
        const reply = data.reply || 'Our AI systems are currently under high load. Please try again.';
        addMessage(reply, 'bot');
        speakResponse(reply); // Automate voice response
    } catch (error) {
        removeMessage(typingId);
        addMessage('Critical connection error. Please refresh.', 'bot');
    }
}

function addMessage(text, sender, isTyping = false) {
    const id = 'msg-' + Date.now();
    const div = document.createElement('div');
    div.id = id;
    div.className = sender === 'user'
        ? 'bg-cyan-500 text-black p-4 rounded-3xl rounded-tr-none self-end max-w-[85%] shadow-xl font-bold'
        : 'glass text-white p-4 rounded-3xl rounded-tl-none self-start max-w-[85%] border border-white/10 shadow-xl leading-relaxed';
    if (isTyping) div.classList.add('animate-pulse');
    div.textContent = text;
    chatMessages.appendChild(div);
    chatMessages.scrollTop = chatMessages.scrollHeight;
    return id;
}

function removeMessage(id) {
    const el = document.getElementById(id);
    if (el) el.remove();
}
</script>

<?php require_once 'includes/footer.php'; ?>
