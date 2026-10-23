<?php
$current = basename($_SERVER['PHP_SELF']);
$items = [
    ['file'=>'dashboard.php','icon'=>'layout-dashboard','label'=>'Dashboard'],
    ['file'=>'menu.php','icon'=>'coffee','label'=>'Kelola Menu'],
    ['file'=>'order.php','icon'=>'receipt','label'=>'Data Order'],
    ['file'=>'laporan.php','icon'=>'file-text','label'=>'Laporan'],
];
?>
<div class="min-h-screen flex bg-cream-100 w-full">
    <aside class="w-64 bg-matcha-900 text-cream-100 flex flex-col border-r border-matcha-800 shrink-0">
        <div class="px-5 py-5 border-b border-matcha-800">
            <a href="dashboard.php" class="flex items-center gap-2.5">
                <img src="../assets/logo-brand.png" alt="Logo" class="w-9 h-9 object-contain">
                <div class="leading-tight">
                    <p class="font-display font-semibold text-sm text-cream-50">Taking Order</p>
                    <p class="text-[10px] uppercase tracking-[0.18em] text-matcha-300">Admin Panel</p>
                </div>
            </a>
        </div>
        <nav class="flex-1 px-3 py-4 space-y-1">
            <?php foreach ($items as $item): $active = $current===$item['file']; ?>
                <a href="<?= $item['file']; ?>" class="flex items-center gap-3 px-3 py-2.5 rounded-xl text-sm font-medium transition <?= $active ? 'bg-matcha-200 text-matcha-900' : 'text-cream-200 hover:bg-matcha-800'; ?>">
                    <i data-lucide="<?= $item['icon']; ?>" class="w-4 h-4"></i>
                    <span class="flex-1"><?= $item['label']; ?></span>
                    <?php if ($item['file']==='order.php'): ?>
                        <span id="notifPending" class="hidden bg-espresso-900 text-cream-50 text-[10px] font-bold rounded-full min-w-[18px] h-[18px] flex items-center justify-center px-1"></span>
                    <?php endif; ?>
                </a>
            <?php endforeach; ?>
        </nav>
        <div class="px-3 py-4 border-t border-matcha-800">
            <div class="px-3 py-2 text-xs text-matcha-300 mb-2 flex items-center gap-2">
                <i data-lucide="user" class="w-3.5 h-3.5"></i>
                <?= htmlspecialchars($_SESSION['admin_nama']); ?>
            </div>
            <a href="logout.php" class="flex items-center gap-2 px-3 py-2 rounded-xl text-sm text-cream-200 hover:bg-matcha-800 transition">
                <i data-lucide="log-out" class="w-4 h-4"></i> Logout
            </a>
        </div>
    </aside>
    <main class="flex-1 overflow-x-hidden">