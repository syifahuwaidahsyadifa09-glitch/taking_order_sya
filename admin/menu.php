<?php
require_once '../config/koneksi.php';
if (!isset($_SESSION['admin_id'])) { header("Location: login.php"); exit; }
$judul = "Kelola Menu"; $base_url = "../";
include '../includes/header.php';
include 'includes_sidebar.php';
$q = mysqli_query($koneksi, "SELECT * FROM menu ORDER BY kategori, nama_menu");
?>
<div class="p-6 lg:p-10 space-y-6">
    <div class="flex items-end justify-between border-b border-espresso-100 pb-5">
        <div>
            <p class="text-xs uppercase tracking-[0.18em] text-espresso-500 mb-1">Manajemen</p>
            <h1 class="font-display text-3xl font-semibold text-matcha-900">Kelola Menu</h1>
        </div>
        <a href="menu_tambah.php" class="inline-flex items-center gap-1.5 bg-matcha-900 hover:bg-matcha-800 text-cream-100 font-semibold px-4 py-2.5 rounded-xl transition text-sm">
            <i data-lucide="plus" class="w-4 h-4"></i> Tambah Menu
        </a>
    </div>

    <?php if (isset($_SESSION['pesan'])): ?>
        <div class="bg-matcha-50 border border-matcha-200 text-matcha-800 rounded-xl px-4 py-3 text-sm"><?= $_SESSION['pesan']; unset($_SESSION['pesan']); ?></div>
    <?php endif; ?>

    <div class="bg-white rounded-2xl border border-espresso-100/60 overflow-hidden">
        <div class="overflow-x-auto">
            <table class="table-clean">
                <thead><tr><th>Gambar</th><th>Nama</th><th>Kategori</th><th class="!text-right">Harga</th><th class="!text-center">Stok</th><th class="!text-center">Status</th><th class="!text-center">Aksi</th></tr></thead>
                <tbody>
                    <?php while ($m = mysqli_fetch_assoc($q)): $img = gambarMenuUrl($m['gambar']); ?>
                    <tr>
                        <td>
                            <?php if ($img): ?>
                                <img src="<?= htmlspecialchars($img); ?>" alt="" class="w-12 h-12 object-cover rounded-lg border border-espresso-100">
                            <?php else: ?>
                                <div class="w-12 h-12 rounded-lg bg-cream-100 border border-espresso-100 flex items-center justify-center text-espresso-300"><i data-lucide="image" class="w-5 h-5"></i></div>
                            <?php endif; ?>
                        </td>
                        <td class="font-medium text-espresso-800"><?= htmlspecialchars($m['nama_menu']); ?></td>
                        <td><span class="text-xs text-espresso-600 bg-cream-100 px-2.5 py-1 rounded-md border border-espresso-100"><?= htmlspecialchars($m['kategori']); ?></span></td>
                        <td class="text-right text-espresso-700"><?= rupiah($m['harga']); ?></td>
                        <td class="text-center text-espresso-700"><?= $m['stok']; ?></td>
                        <td class="text-center"><span class="badge badge-<?= $m['status']; ?>"><?= ucfirst($m['status']); ?></span></td>
                        <td class="text-center">
                            <div class="inline-flex gap-1">
                                <a href="menu_edit.php?id=<?= $m['id']; ?>" class="text-espresso-500 hover:text-matcha-800 p-1"><i data-lucide="pencil" class="w-4 h-4"></i></a>
                                <a href="menu_hapus.php?id=<?= $m['id']; ?>" onclick="return confirm('Hapus menu ini?')" class="text-espresso-500 hover:text-espresso-900 p-1"><i data-lucide="trash-2" class="w-4 h-4"></i></a>
                            </div>
                        </td>
                    </tr>
                    <?php endwhile; ?>
                </tbody>
            </table>
        </div>
    </div>
</div>
<?php include 'includes_footer_admin.php'; ?>