</main>
<footer class="glass border-t border-white/10 mt-20 py-24 px-6 relative overflow-hidden">
    <div class="absolute top-0 right-0 w-96 h-96 bg-cyan-500/5 rounded-full blur-[100px] -z-10"></div>
    <div class="max-w-7xl mx-auto grid grid-cols-1 md:grid-cols-4 gap-12">
        <div class="space-y-6">
            <h3 class="text-3xl font-black italic text-cyan-400">Zeoraz</h3>
            <p class="text-white/40 leading-relaxed">Redefining the digital marketplace with cutting-edge technology and seamless vendor integration.</p>
            <div class="flex gap-4">
                <!-- Social Mockups -->
                <div class="w-10 h-10 rounded-xl bg-white/5 border border-white/10 flex items-center justify-center hover:bg-cyan-500/10 hover:border-cyan-500/30 transition cursor-pointer">
                    <svg xmlns="http://www.w3.org/2000/svg" width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M18 2h-3a5 5 0 0 0-5 5v3H7v4h3v8h4v-8h3l1-4h-4V7a1 1 0 0 1 1-1h3z"/></svg>
                </div>
                <div class="w-10 h-10 rounded-xl bg-white/5 border border-white/10 flex items-center justify-center hover:bg-cyan-500/10 hover:border-cyan-500/30 transition cursor-pointer">
                    <svg xmlns="http://www.w3.org/2000/svg" width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><rect width="20" height="20" x="2" y="2" rx="5" ry="5"/><path d="M16 11.37A4 4 0 1 1 12.63 8 4 4 0 0 1 16 11.37z"/><line x1="17.5" x2="17.51" y1="6.5" y2="6.5"/></svg>
                </div>
            </div>
        </div>

        <div class="space-y-6">
            <h4 class="text-xs font-black uppercase tracking-widest text-white/20">Marketplace</h4>
            <ul class="space-y-4 text-sm font-bold text-white/50">
                <li><a href="<?php echo BASE_URL; ?>index.php" class="hover:text-cyan-400 transition">All Products</a></li>
                <li><a href="#" class="hover:text-cyan-400 transition">Latest Drops</a></li>
                <li><a href="#" class="hover:text-cyan-400 transition">Verified Sellers</a></li>
            </ul>
        </div>

        <div class="space-y-6">
            <h4 class="text-xs font-black uppercase tracking-widest text-white/20">Legal</h4>
            <ul class="space-y-4 text-sm font-bold text-white/50">
                <li><a href="<?php echo BASE_URL; ?>pages/privacy-policy.php" class="hover:text-cyan-400 transition">Privacy Policy</a></li>
                <li><a href="<?php echo BASE_URL; ?>pages/terms.php" class="hover:text-cyan-400 transition">Terms of Service</a></li>
                <li><a href="<?php echo BASE_URL; ?>pages/return-policy.php" class="hover:text-cyan-400 transition">Return Policy</a></li>
            </ul>
        </div>

        <div class="space-y-6">
            <h4 class="text-xs font-black uppercase tracking-widest text-white/20">Newsletter</h4>
            <p class="text-sm text-white/40">Subscribe for early access to drops.</p>
            <div class="flex gap-2">
                <input type="email" placeholder="Email address" class="flex-1 bg-white/5 border border-white/10 rounded-xl px-4 py-2 text-sm focus:outline-none focus:border-cyan-500 transition">
                <button class="bg-cyan-500 text-black px-4 py-2 rounded-xl text-xs font-black uppercase hover:bg-cyan-400 transition">Join</button>
            </div>
        </div>
    </div>
    <div class="max-w-7xl mx-auto mt-24 pt-8 border-t border-white/5 flex flex-col md:flex-row justify-between items-center gap-4">
        <p class="text-white/20 text-xs font-medium">
            &copy; <?php echo date('Y'); ?> Zeoraz. Designed for excellence.
        </p>
        <div class="flex gap-6 text-[10px] font-black uppercase tracking-wider text-white/20">
            <span>Powered by Zeoraz Engine</span>
            <span>SECURE PAYMENTS</span>
        </div>
    </div>
</footer>
</body>
</html>
