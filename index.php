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
        <div class="glass border border-white/10 rounded-2xl p-4 group hover:border-cyan-500/50 transition">
            <div class="aspect-square bg-white/5 rounded-xl mb-4 overflow-hidden">
                <?php if($product['image']): ?>
                    <img src="<?php echo htmlspecialchars($product['image']); ?>" class="w-full h-full object-cover group-hover:scale-110 transition duration-500">
                <?php else: ?>
                    <div class="w-full h-full flex items-center justify-center text-white/10">No Image</div>
                <?php endif; ?>
            </div>
            <h3 class="font-bold text-lg"><?php echo htmlspecialchars($product['name']); ?></h3>
            <p class="text-white/50 text-sm line-clamp-2 mt-1"><?php echo htmlspecialchars($product['description']); ?></p>
            <div class="mt-4 flex justify-between items-center">
                <span class="text-cyan-400 font-black text-xl">$<?php echo number_format($product['price'], 2); ?></span>
                <button class="bg-cyan-500 hover:bg-cyan-400 p-2 rounded-lg text-black transition">
                    <svg xmlns="http://www.w3.org/2000/svg" width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><circle cx="8" cy="21" r="1"/><circle cx="19" cy="21" r="1"/><path d="M2.05 2.05h2l2.66 12.42a2 2 0 0 0 2 1.58h9.78a2 2 0 0 0 1.95-1.57l1.65-7.43H5.12"/></svg>
                </button>
            </div>
        </div>
    <?php endforeach; ?>
    
    <?php if(empty($products)): ?>
        <div class="col-span-full text-center py-20 border border-dashed border-white/10 rounded-3xl">
            <p class="text-white/40 italic">No products available yet. Be the first to list one!</p>
        </div>
    <?php endif; ?>
</div>

<a href="#" id="chatbot-button" class="fixed bottom-6 right-6 w-16 h-16 bg-cyan-500 rounded-full flex items-center justify-center shadow-lg hover:bg-cyan-400 transition z-50">
    <svg xmlns="http://www.w3.org/2000/svg" class="w-8 h-8 text-white" fill="none" viewBox="0 0 24 24" stroke="currentColor">
        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 10h.01M12 10h.01M16 10h.01M21 12c0 4.418-4.03 8-9 8s-9-3.582-9-8 4.03-8 9-8 9 3.582 9 8z"/>
    </svg>
</a>

<div id="chat-popup" class="fixed bottom-24 right-6 w-80 bg-white rounded-xl shadow-lg hidden flex-col overflow-hidden z-50">
    <div class="bg-cyan-500 text-white px-4 py-2 flex justify-between items-center">
        <span>Chat with us</span>
        <button id="close-chat" class="text-white">&times;</button>
    </div>
    <div id="chat-messages" class="p-4 h-64 overflow-y-auto flex flex-col gap-2"></div>
    <div class="p-2 border-t flex gap-2">
        <input type="text" id="chat-input" class="flex-1 border rounded px-2 py-1" placeholder="Type a message..." />
        <button id="send-chat" class="bg-cyan-500 text-white px-3 py-1 rounded">Send</button>
    </div>
</div>

<script>
const chatbotBtn = document.getElementById('chatbot-button');
const chatPopup = document.getElementById('chat-popup');
const closeBtn = document.getElementById('close-chat');
const sendBtn = document.getElementById('send-chat');
const chatInput = document.getElementById('chat-input');
const chatMessages = document.getElementById('chat-messages');

// Open/close chat popup
chatbotBtn.addEventListener('click', () => {
    chatPopup.classList.toggle('hidden');
});

closeBtn.addEventListener('click', () => {
    chatPopup.classList.add('hidden');
});

// Send message
sendBtn.addEventListener('click', () => {
    const msg = chatInput.value.trim();
    if(msg){
        // Display message in chat
        const msgDiv = document.createElement('div');
        msgDiv.className = 'bg-cyan-100 text-black p-2 rounded self-end';
        msgDiv.textContent = msg;
        chatMessages.appendChild(msgDiv);

        // Scroll to bottom
        chatMessages.scrollTop = chatMessages.scrollHeight;

        chatInput.value = '';

        // OPTIONAL: Send to backend via AJAX
        fetch('chat_send.php', {
            method: 'POST',
            headers: {'Content-Type': 'application/json'},
            body: JSON.stringify({ message: msg })
        });
    }
});

// Press Enter to send
chatInput.addEventListener('keypress', (e) => {
    if(e.key === 'Enter') sendBtn.click();
});
</script>

<?php require_once 'includes/footer.php'; ?>
