<?php
if (session_status() === PHP_SESSION_NONE) session_start();
$base_url = $base_url ?? '';
?>
<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?= isset($judul) ? htmlspecialchars($judul) : 'Taking Order Cafe'; ?></title>
    <link rel="icon" type="image/png" href="<?= $base_url; ?>assets/logo-brand.png">

    <script src="https://cdn.tailwindcss.com"></script>
    <script>
      tailwind.config = {
        theme: { extend: {
          colors: {
            matcha: {50:'#f4f8ec',100:'#e6efd3',200:'#d2e3b9',300:'#c5d8a4',400:'#a9c47d',500:'#8bb058',600:'#6b8f3e',700:'#4a6a2c',800:'#2a442e',900:'#213b26'},
            cream: {50:'#fbfcf5',100:'#f6f8ee',200:'#eef1e2'},
            espresso: {50:'#f5efeb',100:'#e8dad0',200:'#d1b8a3',500:'#5a3e2b',700:'#4a3528',800:'#3d2a1e',900:'#2c1810'},
          },
          fontFamily: {
            sans: ['Inter','ui-sans-serif','system-ui','sans-serif'],
            display: ['"Playfair Display"','Georgia','serif'],
          },
        }}
      }
    </script>

    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700&family=Playfair+Display:wght@500;600;700&display=swap" rel="stylesheet">
    <script src="https://unpkg.com/lucide@latest/dist/umd/lucide.min.js"></script>
    <link rel="stylesheet" href="<?= $base_url; ?>assets/css/style.css">
</head>
<body class="bg-cream-100 text-espresso-800 font-sans antialiased min-h-screen flex flex-col">