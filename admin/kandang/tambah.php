<?php
require_once '../../config/koneksi.php';
require_once '../../includes/auth.php';
require_role('admin');

$title = 'Tambah Kandang / Wadah';
$jenisOptions = ['Anjing', 'Kucing', 'Burung', 'Reptil', 'Hewan Air', 'Kelinci', 'Hamster', 'Hewan Lainnya'];
$error = '';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $nomor = trim($_POST['nomor_kandang'] ?? '');
    $jenis = trim($_POST['jenis'] ?? '');
    $ukuran = trim($_POST['ukuran'] ?? '');
    $harga = (float)($_POST['harga_per_hari'] ?? 0);
    $status = $_POST['status'] ?? 'tersedia';

    if ($nomor === '' || !in_array($jenis, $jenisOptions, true) || $harga <= 0) {
        $error = 'Nomor, jenis hewan, dan harga per hari wajib diisi dengan benar.';
    } elseif (!in_array($status, ['tersedia', 'terisi', 'maintenance'], true)) {
        $error = 'Status kandang tidak valid.';
    } else {
        try {
            $stmt = $pdo->prepare("INSERT INTO kandang (nomor_kandang, jenis, ukuran, harga_per_hari, status) VALUES (?, ?, ?, ?, ?)");
            $stmt->execute([$nomor, $jenis, $ukuran, $harga, $status]);
            header('Location: index.php');
            exit;
        } catch (PDOException $e) {
            $error = $e->getCode() === '23000' ? 'Nomor kandang/wadah sudah digunakan.' : 'Gagal menyimpan data kandang.';
        }
    }
}

include '../../includes/header.php';
?>
<div class="page-head"><div><h1>Tambah Kandang / Wadah</h1><p class="muted">Pilih kategori yang sesuai dengan hewan yang akan dititipkan.</p></div></div>
<div class="card form-card">
<?php if ($error): ?><div class="alert alert-danger"><?= e($error) ?></div><?php endif; ?>
<form method="post">
    <label for="nomor_kandang">Nomor Kandang / Wadah</label><input id="nomor_kandang" name="nomor_kandang" value="<?= e($_POST['nomor_kandang'] ?? '') ?>" required>
    <label for="jenis">Kategori Hewan</label><select id="jenis" name="jenis" required><option value="">-- Pilih Kategori --</option><?php foreach ($jenisOptions as $option): ?><option value="<?= e($option) ?>" <?= ($_POST['jenis'] ?? '') === $option ? 'selected' : '' ?>><?= e($option) ?></option><?php endforeach; ?></select>
    <label for="ukuran">Ukuran / Kapasitas</label><input id="ukuran" name="ukuran" value="<?= e($_POST['ukuran'] ?? '') ?>" placeholder="Contoh: Large, 2 ekor, Akuarium 20 L">
    <label for="harga_per_hari">Harga per Hari (Rp)</label><input id="harga_per_hari" type="number" name="harga_per_hari" min="1" step="0.01" value="<?= e($_POST['harga_per_hari'] ?? '') ?>" required>
    <label for="status">Status</label><select id="status" name="status"><option value="tersedia">Tersedia</option><option value="terisi" <?= ($_POST['status'] ?? '') === 'terisi' ? 'selected' : '' ?>>Terisi</option><option value="maintenance" <?= ($_POST['status'] ?? '') === 'maintenance' ? 'selected' : '' ?>>Maintenance</option></select>
    <div class="form-actions"><button class="btn btn-primary" type="submit">Simpan</button><a class="btn btn-secondary" href="index.php">Batal</a></div>
</form>
</div>
<?php include '../../includes/footer.php'; ?>
