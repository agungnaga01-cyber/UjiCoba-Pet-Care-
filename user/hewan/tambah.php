<?php
require_once '../../config/koneksi.php';
require_once '../../includes/auth.php';
require_role('user');

$title = 'Tambah Hewan';
$jenisOptions = ['Anjing', 'Kucing', 'Burung', 'Reptil', 'Hewan Air', 'Kelinci', 'Hamster', 'Hewan Lainnya'];
$error = '';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $id_user = (int)$_SESSION['user']['id_user'];
    $nama = trim($_POST['nama_hewan'] ?? '');
    $jenis = trim($_POST['jenis'] ?? '');
    $ras = trim($_POST['ras'] ?? '');
    $umur = (int)($_POST['umur'] ?? 0);
    $kelamin = $_POST['jenis_kelamin'] ?? '';
    $keterangan = trim($_POST['keterangan'] ?? '');

    if ($nama === '' || !in_array($jenis, $jenisOptions, true)) {
        $error = 'Nama dan jenis hewan wajib diisi.';
    } elseif ($umur < 0) {
        $error = 'Umur tidak boleh negatif.';
    } elseif ($kelamin !== '' && !in_array($kelamin, ['Jantan', 'Betina'], true)) {
        $error = 'Jenis kelamin tidak valid.';
    } else {
        $stmt = $pdo->prepare(
            "INSERT INTO hewan (id_user, nama_hewan, jenis, ras, umur, jenis_kelamin, keterangan)
             VALUES (?, ?, ?, ?, ?, ?, ?)"
        );
        $stmt->execute([$id_user, $nama, $jenis, $ras, $umur, $kelamin ?: null, $keterangan]);
        header('Location: index.php');
        exit;
    }
}

include '../../includes/header.php';
?>
<div class="page-head"><h1>Tambah Hewan</h1></div>
<div class="card form-card">
<?php if ($error): ?><div class="alert alert-danger"><?= e($error) ?></div><?php endif; ?>
<form method="post">
    <label for="nama_hewan">Nama Hewan</label><input id="nama_hewan" name="nama_hewan" value="<?= e($_POST['nama_hewan'] ?? '') ?>" required>
    <label for="jenis">Jenis/Kategori Hewan</label>
    <select id="jenis" name="jenis" required>
        <option value="">-- Pilih Jenis Hewan --</option>
        <?php foreach ($jenisOptions as $option): ?>
            <option value="<?= e($option) ?>" <?= ($_POST['jenis'] ?? '') === $option ? 'selected' : '' ?>><?= e($option) ?></option>
        <?php endforeach; ?>
    </select>
    <small class="muted">Kategori: anjing, kucing, burung, reptil, hewan air, kelinci, hamster, atau lainnya.</small>
    <label for="ras">Ras / Spesies</label><input id="ras" name="ras" value="<?= e($_POST['ras'] ?? '') ?>" placeholder="Contoh: Lovebird, Iguana, Ikan Cupang">
    <label for="umur">Umur (tahun)</label><input id="umur" type="number" name="umur" min="0" value="<?= e($_POST['umur'] ?? '0') ?>">
    <label for="jenis_kelamin">Jenis Kelamin</label>
    <select id="jenis_kelamin" name="jenis_kelamin"><option value="">-- Tidak diketahui / tidak diisi --</option><option value="Jantan" <?= ($_POST['jenis_kelamin'] ?? '') === 'Jantan' ? 'selected' : '' ?>>Jantan</option><option value="Betina" <?= ($_POST['jenis_kelamin'] ?? '') === 'Betina' ? 'selected' : '' ?>>Betina</option></select>
    <label for="keterangan">Keterangan / Perawatan Khusus</label><textarea id="keterangan" name="keterangan" placeholder="Contoh: perlu lampu penghangat atau filter air"><?= e($_POST['keterangan'] ?? '') ?></textarea>
    <div class="form-actions"><button class="btn btn-primary" type="submit">Simpan</button><a class="btn btn-secondary" href="index.php">Batal</a></div>
</form>
</div>
<?php include '../../includes/footer.php'; ?>
