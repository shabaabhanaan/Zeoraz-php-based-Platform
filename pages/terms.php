<?php
// terms.php
require_once '../core/db.php';
require_once '../core/utils.php';
require_once '../includes/header.php';
?>

<div class="max-w-4xl mx-auto py-12 px-6">
    <div class="glass border border-white/10 p-10 md:p-16 rounded-[40px] space-y-12">
        <div class="text-center space-y-4">
            <h1 class="text-5xl font-black italic tracking-tighter">Terms & <span class="text-cyan-400">Conditions</span></h1>
            <p class="text-white/40 text-lg italic">The legal framework for our professional relationship.</p>
        </div>

        <div class="space-y-8 text-white/80 leading-relaxed">
            <section class="space-y-4">
                <h2 class="text-2xl font-bold text-white flex items-center gap-3">
                    <span class="w-8 h-8 bg-purple-500/20 text-purple-400 rounded-lg flex items-center justify-center text-sm">01</span>
                    Agreement to Terms
                </h2>
                <p>Welcome to Zeoraz. These Terms and Conditions govern your use of our website and services. By accessing or using our platform, you agree to be bound by these terms. If you disagree with any part of the terms, you may not access the service.</p>
            </section>

            <section class="space-y-4">
                <h2 class="text-2xl font-bold text-white flex items-center gap-3">
                    <span class="w-8 h-8 bg-purple-500/20 text-purple-400 rounded-lg flex items-center justify-center text-sm">02</span>
                    Purchases and Payments
                </h2>
                <p>If you wish to purchase any product or service made available through the platform, you may be asked to supply certain information relevant to your purchase. All payments are processed through the **PayHere** payment gateway.</p>
                <p>You agree to provide current, complete, and accurate purchase and account information for all purchases made at our store.</p>
            </section>

            <section class="space-y-4">
                <h2 class="text-2xl font-bold text-white flex items-center gap-3">
                    <span class="w-8 h-8 bg-purple-500/20 text-purple-400 rounded-lg flex items-center justify-center text-sm">03</span>
                    Intellectual Property
                </h2>
                <p>The service and its original content, features, and functionality are and will remain the exclusive property of Zeoraz and its licensors. Our trademarks and trade dress may not be used in connection with any product or service without the prior written consent of Zeoraz.</p>
            </section>

            <section class="space-y-4">
                <h2 class="text-2xl font-bold text-white flex items-center gap-3">
                    <span class="w-8 h-8 bg-purple-500/20 text-purple-400 rounded-lg flex items-center justify-center text-sm">04</span>
                    User Responsibilities
                </h2>
                <p>Users are responsible for maintaining the confidentiality of their account and password and for restricting access to their computer. You agree to accept responsibility for all activities that occur under your account.</p>
            </section>

            <section class="space-y-4">
                <h2 class="text-2xl font-bold text-white flex items-center gap-3">
                    <span class="w-8 h-8 bg-purple-500/20 text-purple-400 rounded-lg flex items-center justify-center text-sm">05</span>
                    Changes to Terms
                </h2>
                <p>We reserve the right, at our sole discretion, to modify or replace these Terms at any time. We will provide at least 30 days' notice prior to any new terms taking effect. What constitutes a material change will be determined at our sole discretion.</p>
            </section>

            <section class="space-y-4 pt-8 border-t border-white/5 text-center">
                <p class="text-white/40 italic text-sm">By continuing to access or use our Service after those revisions become effective, you agree to be bound by the revised terms.</p>
                <p class="mt-4 font-bold text-white">Contact: <a href="mailto:legal@zeoraz.com" class="text-cyan-400 hover:underline">legal@zeoraz.com</a></p>
            </section>
        </div>
    </div>
</div>

<?php require_once '../includes/footer.php'; ?>
