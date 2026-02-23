<?php
require_once 'includes/db.php';
require_once 'includes/header.php';

// Fetch products
try {
    $stmt = $pdo->query("SELECT * FROM products WHERE status = 'ACTIVE' ORDER BY createdAt DESC LIMIT 12");
    $products = $stmt->fetchAll();
} catch (PDOException $e) {
    $products = [];
}
?>

<div class="text-center space-y-6 mb-20">
    <h1 class="text-6xl font-black bg-gradient-to-b from-white to-white/40 bg-clip-text text-transparent">
        Everything you need,<br><span class="text-cyan-400">Simplified.</span>
    </h1>
    <p class="text-white/60 text-xl max-w-2xl mx-auto">
        Join the most advanced multi-vendor marketplace built for the next generation of commerce.
    </p>
    <div class="flex justify-center gap-4 pt-4">
        <a href="auth/register.php" class="bg-white text-black px-8 py-3 rounded-full font-bold text-lg hover:scale-105 transition">Explore Now</a>
        <a href="#" class="border border-white/10 px-8 py-3 rounded-full font-bold text-lg hover:bg-white/5 transition">Learn More</a>
    </div>
</div>

<div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-8">
    <?php foreach ($products as $product): ?>
        <div class="glass border border-white/10 rounded-3xl p-4 group hover:border-cyan-500/50 transition duration-500 flex flex-col">
            <a href="product_details.php?id=<?php echo $product['id']; ?>" class="block relative aspect-square bg-white/5 rounded-2xl mb-4 overflow-hidden">
                <?php if($product['image']): ?>
                    <img src="<?php echo htmlspecialchars($product['image']); ?>" class="w-full h-full object-cover group-hover:scale-110 transition duration-700">
                <?php else: ?>
                    <div class="w-full h-full flex items-center justify-center text-white/5 font-black text-4xl uppercase tracking-tighter">Zeoraz</div>
                <?php endif; ?>
                <div class="absolute top-3 right-3 px-3 py-1 bg-black/60 backdrop-blur-md rounded-full text-[10px] font-black uppercase tracking-widest border border-white/10">
                    <?php echo htmlspecialchars($product['category'] ?? 'General'); ?>
                </div>
            </a>
            
            <div class="flex-1 space-y-1">
                <h3 class="font-bold text-lg leading-tight truncate"><?php echo htmlspecialchars($product['name']); ?></h3>
                <p class="text-white/40 text-xs line-clamp-2 italic h-8"><?php echo htmlspecialchars($product['description']); ?></p>
            </div>

            <div class="mt-6 flex justify-between items-center bg-white/5 p-2 rounded-2xl border border-white/5 group-hover:border-cyan-500/20 transition">
                <span class="pl-2 font-black text-xl text-cyan-400 font-mono">$<?php echo number_format($product['price'], 2); ?></span>
                <button onclick="addToCart('<?php echo $product['id']; ?>', this)" 
                    class="bg-cyan-500 hover:bg-cyan-400 p-3 rounded-xl text-black transition shadow-lg shadow-cyan-500/10 active:scale-90">
                    <svg xmlns="http://www.w3.org/2000/svg" width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round"><circle cx="8" cy="21" r="1"/><circle cx="19" cy="21" r="1"/><path d="M2.05 2.05h2l2.66 12.42a2 2 0 0 0 2 1.58h9.78a2 2 0 0 0 1.95-1.57l1.65-7.43H5.12"/></svg>
                </button>
            </div>
        </div>
    <?php endforeach; ?>
    
    <?php if(empty($products)): ?>
        <div class="col-span-full text-center py-20 border-2 border-dashed border-white/5 rounded-[40px] bg-white/[0.01]">
            <p class="text-white/20 text-xl font-bold italic tracking-tighter">The marketplace is quiet... be the first to sell something.</p>
        </div>
    <?php endif; ?>
</div>

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
        const data = await res.json();
        
        if (data.success) {
            btn.innerHTML = '<svg xmlns="http://www.w3.org/2000/svg" width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="black" stroke-width="3" stroke-linecap="round" stroke-linejoin="round"><polyline points="20 6 9 17 4 12"></polyline></svg>';
            btn.classList.replace('bg-cyan-500', 'bg-emerald-500');
            setTimeout(() => {
                btn.innerHTML = originalContent;
                btn.classList.replace('bg-emerald-500', 'bg-cyan-500');
                btn.disabled = false;
            }, 1500);
        }
    } catch (e) {
        btn.innerHTML = originalContent;
        btn.disabled = false;
    }
}
</script>


<a href="#" id="chatbot-button" class="fixed bottom-6 right-6 w-16 h-16 bg-cyan-500 rounded-full flex items-center justify-center shadow-lg hover:bg-cyan-400 transition z-50">
    <svg xmlns="http://www.w3.org/2000/svg" class="w-8 h-8 text-white" fill="none" viewBox="0 0 24 24" stroke="currentColor">
        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 10h.01M12 10h.01M16 10h.01M21 12c0 4.418-4.03 8-9 8s-9-3.582-9-8 4.03-8 9-8 9 3.582 9 8z"/>
    </svg>
</a>

<div id="chat-popup" class="fixed bottom-24 right-6 w-96 bg-gray-900 rounded-2xl shadow-2xl hidden flex-col overflow-hidden z-50 border border-white/10">

    <div class="bg-cyan-500 text-black px-4 py-3 flex justify-between items-center font-bold">
        Zeoraz AI Assistant
        <button id="close-chat">&times;</button>
    </div>

    <div id="chat-messages" class="p-4 h-80 overflow-y-auto space-y-3 text-sm"></div>

    <div class="p-3 border-t border-white/10 flex gap-2">
        <input type="text" id="chat-input"
            class="flex-1 bg-gray-800 text-white rounded-lg px-3 py-2 focus:outline-none"
            placeholder="Ask something..." />
        <button id="send-chat"
            class="bg-cyan-500 text-black px-4 rounded-lg font-semibold">
            Send
        </button>
    </div>
</div>

<script>
const chatBtn = document.getElementById('chatbot-button');
const chatPopup = document.getElementById('chat-popup');
const closeChat = document.getElementById('close-chat');
const sendBtn = document.getElementById('send-chat');
const chatInput = document.getElementById('chat-input');
const chatMessages = document.getElementById('chat-messages');

let welcomeSent = false;
chatBtn.addEventListener('click', (e) => {
    e.preventDefault();
    chatPopup.classList.toggle('hidden');
    if (!chatPopup.classList.contains('hidden')) {
        chatInput.focus();
        if (!welcomeSent) {
            addMessage('Hello! I am Zeoraz AI. How can I help you today?', 'bot');
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

    // Add typing indicator
    const typingId = addMessage('...', 'bot', true);

    try {
        const response = await fetch('api/chat_ai.php', {
            method: 'POST',
            headers: { 'Content-Type': 'application/json' },
            body: JSON.stringify({ message: msg })
        });

        const data = await response.json();
        removeMessage(typingId);
        addMessage(data.reply || 'Sorry, I keep having trouble connecting.', 'bot');
    } catch (error) {
        removeMessage(typingId);
        addMessage('Sorry, something went wrong. Please try again.', 'bot');
    }
}

function addMessage(text, sender, isTyping = false) {
    const id = 'msg-' + Date.now();
    const div = document.createElement('div');
    div.id = id;
    div.className = sender === 'user'
        ? 'bg-cyan-500 text-black p-3 rounded-2xl rounded-tr-none self-end max-w-[80%] shadow-sm'
        : 'bg-white/5 text-white p-3 rounded-2xl rounded-tl-none self-start max-w-[80%] border border-white/5 shadow-sm';

    if (isTyping) {
        div.classList.add('animate-pulse');
    }

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
