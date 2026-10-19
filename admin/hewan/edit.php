<?php
require_once '../../config/koneksi.php';
require_once '../../includes/auth.php';
require_role('admin');

$id = (int)($_GET['id'] ?? 0);
$stmt = $pdo->prepare("SELECT * FROM hewan WHERE id_hewan = ?");
$stmt->execute([$id]);
$data = $stmt->fetch();
if (!$data) exit('Hewan tidak ditemukan.');

$users = $pdo->query("SELECT id_user, nama FROM users WHERE role='user' ORDER BY nama")->fetchAll();
$jenisOptions = ['Anjing', 'Kucing', 'Burung', 'Reptil', 'Hewan Air', 'Kelinci', 'Hamster', 'Hewan Lainnya'];
// Pertahankan jenis lama jika sebelumnya tersimpan di database tetapi belum ada di daftar opsi.
if (!in_array($data['jenis'], $jenisOptions, true)) $jenisOptions[] = $data['jenis'];
$title = 'Edit Hewan';
$error = '';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $id_user = (int)($_POST['id_user'] ?? 0);
    $nama = trim($_POST['nama_hewan'] ?? '');
    $jenis = trim($_POST['jenis'] ?? '');
    $ras = trim($_POST['ras'] ?? '');
    $umur = (int)($_POST['umur'] ?? 0);
    $kelamin = $_POST['jenis_kelamin'] ?? '';
    $keterangan = trim($_POST['keterangan'] ?? '');

    if ($id_user <= 0 || $nama === '' || !in_array($jenis, $jenisOptions, true)) {
        $error = 'Pemilik, nama hewan, dan jenis hewan wajib dipilih.';
    } elseif ($umur < 0) {
        $error = 'Umur tidak boleh negatif.';
    } elseif ($kelamin !== '' && !in_array($kelamin, ['Jantan', 'Betina'], true)) {
        $error = 'Jenis kelamin tidak valid.';
    } else {
        $stmt = $pdo->prepare(
            "UPDATE hewan SET id_user=?, nama_hewan=?, jenis=?, ras=?, umur=?, jenis_kelamin=?, keterangan=? WHERE id_hewan=?"
        );
        $stmt->execute([$id_user, $nama, $jenis, $ras, $umur, $kelamin ?: null, $keterangan, $id]);
        header('Location: index.php');
        exit;
    }
}

include '../../includes/header.php';
?>
<div class="page-head"><h1>Edit Hewan</h1></div>
<div class="card form-card">
<?php if ($error): ?><div class="alert alert-danger"><?= e($error) ?></div><?php endif; ?>
<form method="post">
    <label for="id_user">Pemilik</label>
    <select id="id_user" name="id_user" required>
        <?php foreach ($users as $u): ?>
            <option value="<?= (int)$u['id_user'] ?>" <?= (int)($_POST['id_user'] ?? $data['id_user']) === (int)$u['id_user'] ? 'selected' : '' ?>><?= e($u['nama']) ?></option>
        <?php endforeach; ?>
    </select>
    <label for="nama_hewan">Nama Hewan</label><input id="nama_hewan" type="text" name="nama_hewan" value="<?= e($_POST['nama_hewan'] ?? $data['nama_hewan']) ?>" required>
    <label for="jenis">Jenis/Kategori Hewan</label>
    <select id="jenis" name="jenis" required>
        <?php foreach ($jenisOptions as $option): ?>
            <option value="<?= e($option) ?>" <?= ($_POST['jenis'] ?? $data['jenis']) === $option ? 'selected' : '' ?>><?= e($option) ?></option>
        <?php endforeach; ?>
    </select>
    <small class="muted">Contoh spesies dapat ditulis pada kolom Ras/Spesies.</small>
    <label for="ras">Ras / Spesies</label><input id="ras" type="text" name="ras" value="<?= e($_POST['ras'] ?? $data['ras']) ?>" placeholder="Contoh: Lovebird, Iguana, Ikan Cupang">
    <label for="umur">Umur (tahun)</label><input id="umur" type="number" name="umur" min="0" value="<?= e($_POST['umur'] ?? (string)$data['umur']) ?>">
    <label for="jenis_kelamin">Jenis Kelamin</label>
    <select id="jenis_kelamin" name="jenis_kelamin">
        <option value="">-- Tidak diketahui / tidak diisi --</option>
        <option value="Jantan" <?= ($_POST['jenis_kelamin'] ?? $data['jenis_kelamin']) === 'Jantan' ? 'selected' : '' ?>>Jantan</option>
        <option value="Betina" <?= ($_POST['jenis_kelamin'] ?? $data['jenis_kelamin']) === 'Betina' ? 'selected' : '' ?>>Betina</option>
    </select>
    <label for="keterangan">Keterangan / Perawatan Khusus</label><textarea id="keterangan" name="keterangan"><?= e($_POST['keterangan'] ?? $data['keterangan']) ?></textarea>
    <div class="form-actions"><button class="btn btn-primary" type="submit">Update</button><a class="btn btn-secondary" href="index.php">Batal</a></div>
</form>
</div>
<?php include '../../includes/footer.php'; ?>
