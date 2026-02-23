<?php
// privacy-policy.php
require_once '../core/db.php';
require_once '../core/utils.php';
require_once '../includes/header.php';
?>

<div class="max-w-4xl mx-auto py-12 px-6">
    <div class="glass border border-white/10 p-10 md:p-16 rounded-[40px] space-y-12">
        <div class="text-center space-y-4">
            <h1 class="text-5xl font-black italic tracking-tighter">Privacy <span class="text-cyan-400">Policy</span></h1>
            <p class="text-white/40 text-lg italic">How we protect and handle your digital footprint.</p>
        </div>

        <div class="space-y-8 text-white/80 leading-relaxed">
            <section class="space-y-4">
                <h2 class="text-2xl font-bold text-white flex items-center gap-3">
                    <span class="w-8 h-8 bg-blue-500/20 text-blue-400 rounded-lg flex items-center justify-center text-sm">01</span>
                    Information Collection
                </h2>
                <p>We collect personal information that you provide to us when you register on the website, place an order, or subscribe to our newsletter. This information may include your name, email address, mailing address, and phone number.</p>
            </section>

            <section class="space-y-4">
                <h2 class="text-2xl font-bold text-white flex items-center gap-3">
                    <span class="w-8 h-8 bg-blue-500/20 text-blue-400 rounded-lg flex items-center justify-center text-sm">02</span>
                    How We Use Your Data
                </h2>
                <p>Your personal data is used solely to process orders, manage your account, and communicate with you regarding your transactions or support requests. We may also use your information to improve our website experience and customer service.</p>
            </section>

            <section class="space-y-4 p-6 bg-blue-500/5 border border-blue-500/20 rounded-2xl">
                <h2 class="text-xl font-bold text-blue-400 flex items-center gap-2">
                    <svg xmlns="http://www.w3.org/2000/svg" width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round"><path d="M12 22s8-4 8-10V5l-8-3-8 3v7c0 6 8 10 8 10"/></svg>
                    Payment Security (PayHere)
                </h2>
                <p class="text-sm">All payment transactions are handled securely through the **PayHere** payment gateway. **Zeoraz does not store any credit card or debit card data on our servers.** All sensitive cardholder information is transmitted directly to PayHere’s secure environment, ensuring maximum protection for your financial data.</p>
            </section>

            <section class="space-y-4">
                <h2 class="text-2xl font-bold text-white flex items-center gap-3">
                    <span class="w-8 h-8 bg-blue-500/20 text-blue-400 rounded-lg flex items-center justify-center text-sm">03</span>
                    Data Protection
                </h2>
                <p>We implement a variety of security measures to maintain the safety of your personal information. We use advanced encryption and secure socket layer (SSL) technology to protect your data during transmission.</p>
            </section>

            <section class="space-y-4">
                <h2 class="text-2xl font-bold text-white flex items-center gap-3">
                    <span class="w-8 h-8 bg-blue-500/20 text-blue-400 rounded-lg flex items-center justify-center text-sm">04</span>
                    Third-Party Disclosure
                </h2>
                <p>We do not sell, trade, or otherwise transfer your personally identifiable information to outside parties except to provide products or services you have requested, or when required by law.</p>
            </section>

            <section class="space-y-4 pt-8 border-t border-white/5 text-center">
                <p class="text-white/40 italic">Last updated: February 23, 2026</p>
                <p>Questions about our Privacy Policy? Email <a href="mailto:privacy@zeoraz.com" class="text-cyan-400 hover:underline">privacy@zeoraz.com</a></p>
            </section>
        </div>
    </div>
</div>

<?php require_once '../includes/footer.php'; ?>
