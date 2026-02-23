<?php
// return-policy.php
require_once '../core/db.php';
require_once '../core/utils.php';
require_once '../includes/header.php';
?>

<div class="max-w-4xl mx-auto py-12 px-6">
    <div class="glass border border-white/10 p-10 md:p-16 rounded-[40px] space-y-12">
        <div class="text-center space-y-4">
            <h1 class="text-5xl font-black italic tracking-tighter">Return & <span class="text-cyan-400">Refund</span> Policy</h1>
            <p class="text-white/40 text-lg italic">Our commitment to your satisfaction and trust.</p>
        </div>

        <div class="space-y-8 text-white/80 leading-relaxed">
            <section class="space-y-4">
                <h2 class="text-2xl font-bold text-white flex items-center gap-3">
                    <span class="w-8 h-8 bg-cyan-500/20 text-cyan-400 rounded-lg flex items-center justify-center text-sm">01</span>
                    Introduction
                </h2>
                <p>At Zeoraz, we strive to ensure that our customers are completely satisfied with their purchases. If you are not entirely happy with your purchase, we're here to help. This policy outlines the conditions under which returns and refunds are processed.</p>
            </section>

            <section class="space-y-4">
                <h2 class="text-2xl font-bold text-white flex items-center gap-3">
                    <span class="w-8 h-8 bg-cyan-500/20 text-cyan-400 rounded-lg flex items-center justify-center text-sm">02</span>
                    Return Period
                </h2>
                <p>You have <strong>7 calendar days</strong> to return an item from the date you received it. To be eligible for a return, your item must be unused and in the same condition that you received it. Your item must be in the original packaging.</p>
            </section>

            <section class="space-y-4">
                <h2 class="text-2xl font-bold text-white flex items-center gap-3">
                    <span class="w-8 h-8 bg-cyan-500/20 text-cyan-400 rounded-lg flex items-center justify-center text-sm">03</span>
                    Refund Process
                </h2>
                <p>Once we receive your item, we will inspect it and notify you that we have received your returned item. We will immediately notify you on the status of your refund after inspecting the item.</p>
                <p>If your return is approved, we will initiate a refund to your original method of payment (Credit Card, Debit Card, or Bank Transfer).</p>
            </section>

            <section class="space-y-4 p-6 bg-cyan-500/5 border border-cyan-500/20 rounded-2xl">
                <h2 class="text-xl font-bold text-cyan-400">PayHere Payment Rules</h2>
                <p class="text-sm italic">Please note that all refunds for payments processed through the **PayHere** gateway will follow PayHere’s standard payment rules and timelines. Depending on your bank's policies, it may take a few days for the credit to appear on your statement.</p>
            </section>

            <section class="space-y-4">
                <h2 class="text-2xl font-bold text-white flex items-center gap-3">
                    <span class="w-8 h-8 bg-cyan-500/20 text-cyan-400 rounded-lg flex items-center justify-center text-sm">04</span>
                    Shipping Costs
                </h2>
                <p>You will be responsible for paying for your own shipping costs for returning your item. Shipping costs are non-refundable. If you receive a refund, the cost of return shipping will be deducted from your refund.</p>
            </section>

            <section class="space-y-4 pt-8 border-t border-white/5">
                <h2 class="text-2xl font-bold text-white">Contact Us</h2>
                <p>If you have any questions on how to return your item to us, contact us at:</p>
                <div class="flex flex-col gap-2">
                    <a href="mailto:support@zeoraz.com" class="text-cyan-400 hover:underline font-bold">support@zeoraz.com</a>
                    <span class="text-white/40">+94 11 234 5678</span>
                </div>
            </section>
        </div>
    </div>
</div>

<?php require_once '../includes/footer.php'; ?>
