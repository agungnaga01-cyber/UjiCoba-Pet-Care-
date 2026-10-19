<?php
require_once '../../config/koneksi.php';
require_once '../../includes/auth.php';
require_role('user');

$id_user = (int)$_SESSION['user']['id_user'];
$title = 'Booking Baru';
$error = '';

$hewanStmt = $pdo->prepare("SELECT * FROM hewan WHERE id_user=? ORDER BY nama_hewan");
$hewanStmt->execute([$id_user]);
$hewanList = $hewanStmt->fetchAll();

$kandang = $pdo->query("SELECT * FROM kandang WHERE status='tersedia' ORDER BY jenis, nomor_kandang")->fetchAll();
$layanan = $pdo->query("SELECT * FROM layanan WHERE status='aktif' ORDER BY nama_layanan")->fetchAll();

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $id_hewan = (int)($_POST['id_hewan'] ?? 0);
    $id_kandang = (int)($_POST['id_kandang'] ?? 0);
    $id_layanan = (int)($_POST['id_layanan'] ?? 0);
    $masuk = $_POST['tanggal_masuk'] ?? '';
    $keluar = $_POST['tanggal_keluar'] ?? '';
    $catatan = trim($_POST['catatan'] ?? '');

    $stmt = $pdo->prepare("SELECT * FROM hewan WHERE id_hewan=? AND id_user=?");
    $stmt->execute([$id_hewan, $id_user]);
    $pet = $stmt->fetch();

    $stmt = $pdo->prepare("SELECT * FROM kandang WHERE id_kandang=? AND status='tersedia'");
    $stmt->execute([$id_kandang]);
    $cage = $stmt->fetch();

    $stmt = $pdo->prepare("SELECT * FROM layanan WHERE id_layanan=? AND status='aktif'");
    $stmt->execute([$id_layanan]);
    $service = $stmt->fetch();

    try {
        $d1 = new DateTime($masuk);
        $d2 = new DateTime($keluar);
        $days = (int)$d1->diff($d2)->days;
    } catch (Exception $e) {
        $days = 0;
    }

    if (!$pet || !$cage || !$service) {
        $error = 'Pilihan hewan, kandang/wadah, atau layanan tidak valid.';
    } elseif (strtolower(trim($pet['jenis'])) !== strtolower(trim($cage['jenis']))) {
        $error = 'Kategori kandang/wadah harus sesuai dengan kategori hewan yang dipilih.';
    } elseif ($masuk === '' || $keluar === '' || $days < 1 || $keluar <= $masuk) {
        $error = 'Tanggal keluar harus lebih besar dari tanggal masuk.';
    } else {
        $total = ($days * (float)$cage['harga_per_hari']) + (float)$service['harga'];
        try {
            $pdo->beginTransaction();
            $stmt = $pdo->prepare(
                "INSERT INTO penitipan
                (id_hewan, id_kandang, id_layanan, tanggal_masuk, tanggal_keluar, lama_menginap, total_biaya, status, catatan)
                VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?)"
            );
            $stmt->execute([$id_hewan, $id_kandang, $id_layanan, $masuk, $keluar, $days, $total, 'menunggu', $catatan]);
            $id_titip = (int)$pdo->lastInsertId();

            $stmt = $pdo->prepare(
                "INSERT INTO pembayaran (id_titip, tanggal_bayar, jumlah, metode, status)
                 VALUES (?, ?, ?, ?, ?)"
            );
            $stmt->execute([$id_titip, null, $total, 'transfer', 'pending']);
            $pdo->commit();
            header('Location: index.php');
            exit;
        } catch (Throwable $e) {
            if ($pdo->inTransaction()) $pdo->rollBack();
            $error = 'Booking gagal disimpan. Pastikan kandang/wadah sesuai dengan jenis hewan dan coba lagi.';
        }
    }
}

include '../../includes/header.php';
?>
<div class="page-head"><div><h1>Booking Penitipan</h1><p class="muted">Sistem akan menampilkan kandang/wadah yang sesuai dengan kategori hewan.</p></div></div>
<div class="card form-card">
<?php if ($error): ?><div class="alert alert-danger"><?= e($error) ?></div><?php endif; ?>
<?php if (!$hewanList): ?><div class="alert alert-warning">Kamu belum punya hewan. Tambahkan hewan dulu.</div><?php endif; ?>
<form method="post">
    <label for="id_hewan">Pilih Hewan</label>
    <select id="id_hewan" name="id_hewan" required>
        <option value="">-- Pilih Hewan --</option>
        <?php foreach ($hewanList as $h): ?>
            <option value="<?= (int)$h['id_hewan'] ?>" data-jenis="<?= e(strtolower(trim($h['jenis']))) ?>" <?= (int)($_POST['id_hewan'] ?? 0) === (int)$h['id_hewan'] ? 'selected' : '' ?>><?= e($h['nama_hewan']) ?> (<?= e($h['jenis']) ?><?= $h['ras'] ? ' - ' . e($h['ras']) : '' ?>)</option>
        <?php endforeach; ?>
    </select>

    <label for="id_kandang">Pilih Kandang / Wadah</label>
    <select id="id_kandang" name="id_kandang" required>
        <option value="">-- Pilih hewan dulu --</option>
        <?php foreach ($kandang as $k): ?>
            <option value="<?= (int)$k['id_kandang'] ?>" data-jenis="<?= e(strtolower(trim($k['jenis']))) ?>" <?= (int)($_POST['id_kandang'] ?? 0) === (int)$k['id_kandang'] ? 'selected' : '' ?>><?= e($k['nomor_kandang']) ?> - <?= e($k['jenis']) ?> - <?= e($k['ukuran']) ?> - Rp <?= number_format((float)$k['harga_per_hari'], 0, ',', '.') ?>/hari</option>
        <?php endforeach; ?>
    </select>
    <small class="muted" id="info-kandang">Pilih hewan untuk melihat kandang/wadah yang sesuai.</small>

    <label for="id_layanan">Pilih Layanan</label>
    <select id="id_layanan" name="id_layanan" required><option value="">-- Pilih Layanan --</option><?php foreach ($layanan as $l): ?><option value="<?= (int)$l['id_layanan'] ?>" <?= (int)($_POST['id_layanan'] ?? 0) === (int)$l['id_layanan'] ? 'selected' : '' ?>><?= e($l['nama_layanan']) ?> - Rp <?= number_format((float)$l['harga'], 0, ',', '.') ?></option><?php endforeach; ?></select>

    <label for="tanggal_masuk">Tanggal Masuk</label><input id="tanggal_masuk" type="date" name="tanggal_masuk" value="<?= e($_POST['tanggal_masuk'] ?? '') ?>" required>
    <label for="tanggal_keluar">Tanggal Keluar</label><input id="tanggal_keluar" type="date" name="tanggal_keluar" value="<?= e($_POST['tanggal_keluar'] ?? '') ?>" required>
    <label for="catatan">Catatan</label><textarea id="catatan" name="catatan" placeholder="Contoh: pakan khusus, lampu penghangat, filter air, atau perawatan lainnya"><?= e($_POST['catatan'] ?? '') ?></textarea>
    <div class="form-actions"><button class="btn btn-primary" type="submit" <?= $hewanList ? '' : 'disabled' ?>>Buat Booking</button><a class="btn btn-secondary" href="index.php">Batal</a></div>
</form>
</div>
<script>
document.addEventListener('DOMContentLoaded', function () {
    const animalSelect = document.getElementById('id_hewan');
    const cageSelect = document.getElementById('id_kandang');
    const info = document.getElementById('info-kandang');
    const allOptions = Array.from(cageSelect.querySelectorAll('option')).filter(option => option.value !== '');
    const placeholder = cageSelect.querySelector('option[value=""]');

    function filterCages() {
        const currentlySelected = cageSelect.value;
        const selectedAnimal = animalSelect.options[animalSelect.selectedIndex];
        const type = selectedAnimal ? selectedAnimal.dataset.jenis || '' : '';
        let visibleCount = 0;
        let retainedSelection = false;

        allOptions.forEach(option => {
            const matches = type !== '' && option.dataset.jenis === type;
            option.hidden = !matches;
            option.disabled = !matches;
            if (matches) {
                visibleCount++;
                if (option.value === currentlySelected) retainedSelection = true;
            }
        });

        placeholder.textContent = type ? '-- Pilih Kandang / Wadah --' : '-- Pilih hewan dulu --';
        if (!retainedSelection || !type) cageSelect.value = '';
        info.textContent = !type
            ? 'Pilih hewan untuk melihat kandang/wadah yang sesuai.'
            : (visibleCount ? visibleCount + ' kandang/wadah tersedia untuk kategori ini.' : 'Belum ada kandang/wadah tersedia untuk kategori ini. Minta admin menambahkannya.');
    }

    animalSelect.addEventListener('change', filterCages);
    filterCages();
});
</script>
<?php include '../../includes/footer.php'; ?>
