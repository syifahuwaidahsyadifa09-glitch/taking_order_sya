<?php
/**
 * Partial: Brand identity (logo + wordmark).
 * Variabel opsional: $brandSize ('sm'|'md'|'lg'), $brandVariant ('light'|'dark')
 */
$brandSize    = $brandSize    ?? 'md';
$brandVariant = $brandVariant ?? 'light';

$sizes = [
    'sm' => ['box' => 'w-8 h-8',  'text' => 'text-sm'],
    'md' => ['box' => 'w-10 h-10','text' => 'text-base'],
    'lg' => ['box' => 'w-12 h-12','text' => 'text-lg'],
];
$sz = $sizes[$brandSize] ?? $sizes['md'];

$textClass   = $brandVariant === 'light' ? 'text-cream-50' : 'text-matcha-900';
$subClass    = $brandVariant === 'light' ? 'text-matcha-300' : 'text-espresso-500';
?>
<a href="<?= BASE_URL; ?>/index.php" class="flex items-center gap-3 group">
    <span class="brand-mark <?= $sz['box']; ?> shrink-0 bg-cream-50 ring-1 ring-espresso-900/5 group-hover:ring-matcha-500 transition">
        <img src="<?= LOGO_URL; ?>" alt="Taking Order Cafe"
             onerror="this.parentNode.innerHTML='<span>TC</span>';">
    </span>
    <span class="flex flex-col leading-tight">
        <span class="font-display font-bold <?= $sz['text']; ?> <?= $textClass; ?> tracking-wide">
            Taking Order
        </span>
        <span class="text-[10px] uppercase tracking-[0.18em] <?= $subClass; ?>">
            Cafe · Est. 2025
        </span>
    </span>
</a>