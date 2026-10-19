<?php
require_once '../../config/koneksi.php';
require_once '../../includes/auth.php';
require_role('admin');

$id = (int)($_GET['id'] ?? 0);
$stmt = $pdo->prepare("SELECT * FROM kandang WHERE id_kandang = ?");
$stmt->execute([$id]);
$data = $stmt->fetch();
if (!$data) exit('Kandang tidak ditemukan.');

$jenisOptions = ['Anjing', 'Kucing', 'Burung', 'Reptil', 'Hewan Air', 'Kelinci', 'Hamster', 'Hewan Lainnya'];
if (!in_array($data['jenis'], $jenisOptions, true)) $jenisOptions[] = $data['jenis'];
$title = 'Edit Kandang / Wadah';
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
            $stmt = $pdo->prepare("UPDATE kandang SET nomor_kandang=?, jenis=?, ukuran=?, harga_per_hari=?, status=? WHERE id_kandang=?");
            $stmt->execute([$nomor, $jenis, $ukuran, $harga, $status, $id]);
            header('Location: index.php');
            exit;
        } catch (PDOException $e) {
            $error = $e->getCode() === '23000' ? 'Nomor kandang/wadah sudah digunakan.' : 'Gagal mengubah data kandang.';
        }
    }
}

include '../../includes/header.php';
?>
<div class="page-head"><h1>Edit Kandang / Wadah</h1></div>
<div class="card form-card">
<?php if ($error): ?><div class="alert alert-danger"><?= e($error) ?></div><?php endif; ?>
<form method="post">
    <label for="nomor_kandang">Nomor Kandang / Wadah</label><input id="nomor_kandang" name="nomor_kandang" value="<?= e($_POST['nomor_kandang'] ?? $data['nomor_kandang']) ?>" required>
    <label for="jenis">Kategori Hewan</label><select id="jenis" name="jenis" required><?php foreach ($jenisOptions as $option): ?><option value="<?= e($option) ?>" <?= ($_POST['jenis'] ?? $data['jenis']) === $option ? 'selected' : '' ?>><?= e($option) ?></option><?php endforeach; ?></select>
    <label for="ukuran">Ukuran / Kapasitas</label><input id="ukuran" name="ukuran" value="<?= e($_POST['ukuran'] ?? $data['ukuran']) ?>" placeholder="Contoh: Large, 2 ekor, Akuarium 20 L">
    <label for="harga_per_hari">Harga per Hari (Rp)</label><input id="harga_per_hari" type="number" name="harga_per_hari" min="1" step="0.01" value="<?= e($_POST['harga_per_hari'] ?? (string)$data['harga_per_hari']) ?>" required>
    <label for="status">Status</label><select id="status" name="status"><?php foreach (['tersedia' => 'Tersedia', 'terisi' => 'Terisi', 'maintenance' => 'Maintenance'] as $value => $label): ?><option value="<?= e($value) ?>" <?= ($_POST['status'] ?? $data['status']) === $value ? 'selected' : '' ?>><?= e($label) ?></option><?php endforeach; ?></select>
    <div class="form-actions"><button class="btn btn-primary" type="submit">Update</button><a class="btn btn-secondary" href="index.php">Batal</a></div>
</form>
</div>
<?php include '../../includes/footer.php'; ?>
