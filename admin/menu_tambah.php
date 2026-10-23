<?php
require_once '../config/koneksi.php';
if (!isset($_SESSION['admin_id'])) { header("Location: login.php"); exit; }
$judul = "Tambah Menu"; $base_url = "../";
$error = '';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $nama = mysqli_real_escape_string($koneksi, $_POST['nama_menu']);
    $kat = mysqli_real_escape_string($koneksi, $_POST['kategori']);
    $harga = (float)$_POST['harga'];
    $desc = mysqli_real_escape_string($koneksi, $_POST['deskripsi']);
    $stok = max(0, (int)$_POST['stok']);
    $status = in_array($_POST['status'], ['tersedia','habis']) ? $_POST['status'] : 'tersedia';
    $gambar = uploadGambarMenu($_FILES['gambar'] ?? null);
    $gambar_sql = $gambar ? "'$gambar'" : "NULL";

    $sql = "INSERT INTO menu (nama_menu,kategori,harga,deskripsi,stok,status,gambar) VALUES ('$nama','$kat',$harga,'$desc',$stok,'$status',$gambar_sql)";
    if (mysqli_query($koneksi, $sql)) { $_SESSION['pesan'] = "Menu berhasil ditambahkan."; header("Location: menu.php"); exit; }
    $error = "Gagal menyimpan menu.";
}

include '../includes/header.php';
include 'includes_sidebar.php';
?>
<div class="p-6 lg:p-10">
    <a href="menu.php" class="inline-flex items-center gap-1 text-sm text-espresso-500 hover:text-matcha-800 mb-4"><i data-lucide="arrow-left" class="w-4 h-4"></i> Kembali ke daftar menu</a>
    <div class="border-b border-espresso-100 pb-5 mb-6">
        <p class="text-xs uppercase tracking-[0.18em] text-espresso-500 mb-1">Manajemen</p>
        <h1 class="font-display text-3xl font-semibold text-matcha-900">Tambah Menu Baru</h1>
    </div>

    <?php if ($error): ?><div class="mb-4 bg-espresso-50 border border-espresso-200 text-espresso-700 rounded-xl px-4 py-3 text-sm"><?= $error; ?></div><?php endif; ?>

    <form method="POST" enctype="multipart/form-data" class="bg-white rounded-2xl border border-espresso-100/60 p-6 space-y-5 max-w-3xl">
        <div class="grid grid-cols-1 md:grid-cols-2 gap-5">
            <div>
                <label class="block text-xs font-semibold text-espresso-700 mb-1.5 uppercase tracking-wide">Nama Menu</label>
                <input type="text" name="nama_menu" required class="w-full px-3.5 py-2.5 rounded-xl border border-espresso-100 bg-white text-sm focus:outline-none focus:ring-2 focus:ring-matcha-500">
            </div>
            <div>
                <label class="block text-xs font-semibold text-espresso-700 mb-1.5 uppercase tracking-wide">Kategori</label>
                <input type="text" name="kategori" required placeholder="Kopi / Non-Kopi / Makanan / Minuman" class="w-full px-3.5 py-2.5 rounded-xl border border-espresso-100 bg-white text-sm focus:outline-none focus:ring-2 focus:ring-matcha-500">
            </div>
            <div>
                <label class="block text-xs font-semibold text-espresso-700 mb-1.5 uppercase tracking-wide">Harga (Rp)</label>
                <input type="number" name="harga" required min="0" step="100" class="w-full px-3.5 py-2.5 rounded-xl border border-espresso-100 bg-white text-sm focus:outline-none focus:ring-2 focus:ring-matcha-500">
            </div>
            <div>
                <label class="block text-xs font-semibold text-espresso-700 mb-1.5 uppercase tracking-wide">Stok</label>
                <input type="number" name="stok" value="0" required min="0" class="w-full px-3.5 py-2.5 rounded-xl border border-espresso-100 bg-white text-sm focus:outline-none focus:ring-2 focus:ring-matcha-500">
            </div>
            <div>
                <label class="block text-xs font-semibold text-espresso-700 mb-1.5 uppercase tracking-wide">Status</label>
                <select name="status" class="w-full px-3.5 py-2.5 rounded-xl border border-espresso-100 bg-white text-sm focus:outline-none focus:ring-2 focus:ring-matcha-500">
                    <option value="tersedia">Tersedia</option><option value="habis">Habis</option>
                </select>
            </div>
            <div>
                <label class="block text-xs font-semibold text-espresso-700 mb-1.5 uppercase tracking-wide">Gambar Produk</label>
                <input type="file" name="gambar" accept="image/jpeg,image/png,image/webp" class="w-full px-3 py-2 rounded-xl border border-espresso-100 bg-white text-xs file:mr-3 file:py-1.5 file:px-3 file:rounded-lg file:border-0 file:bg-matcha-100 file:text-matcha-800 file:font-semibold">
                <p class="text-[11px] text-espresso-500 mt-1">JPG/PNG/WEBP, maks 3MB.</p>
            </div>
            <div class="md:col-span-2">
                <label class="block text-xs font-semibold text-espresso-700 mb-1.5 uppercase tracking-wide">Deskripsi</label>
                <textarea name="deskripsi" rows="3" class="w-full px-3.5 py-2.5 rounded-xl border border-espresso-100 bg-white text-sm focus:outline-none focus:ring-2 focus:ring-matcha-500"></textarea>
            </div>
        </div>
        <div class="flex gap-3 pt-2">
            <button type="submit" class="inline-flex items-center gap-1.5 bg-matcha-900 hover:bg-matcha-800 text-cream-100 font-semibold px-5 py-2.5 rounded-xl transition text-sm"><i data-lucide="save" class="w-4 h-4"></i> Simpan</button>
            <a href="menu.php" class="inline-flex items-center gap-1.5 bg-cream-100 hover:bg-cream-200 text-espresso-700 font-semibold px-5 py-2.5 rounded-xl transition text-sm border border-espresso-100">Batal</a>
        </div>
    </form>
</div>
<?php include 'includes_footer_admin.php'; ?>