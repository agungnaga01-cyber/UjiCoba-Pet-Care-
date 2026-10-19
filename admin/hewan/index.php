<?php
require_once '../../config/koneksi.php';
require_once '../../includes/auth.php';
require_role('admin');

$title = 'Data Hewan';
$stmt = $pdo->query(
    "SELECT h.*, u.nama AS pemilik
     FROM hewan h
     JOIN users u ON u.id_user = h.id_user
     ORDER BY h.id_hewan DESC"
);
$data = $stmt->fetchAll();

include '../../includes/header.php';
?>
<div class="page-head">
    <div><h1>Data Hewan</h1><p class="muted">Kelola hewan dari seluruh user.</p></div>
    <a class="btn btn-primary" href="tambah.php">+ Tambah Hewan</a>
</div>
<div class="card table-wrap">
    <table>
        <thead><tr><th>ID</th><th>Nama Hewan</th><th>Kategori</th><th>Ras / Spesies</th><th>Umur</th><th>Kelamin</th><th>Pemilik</th><th>Aksi</th></tr></thead>
        <tbody>
        <?php if (!$data): ?>
            <tr><td colspan="8" class="empty-state">Belum ada data hewan.</td></tr>
        <?php else: foreach ($data as $row): ?>
            <tr>
                <td><?= (int)$row['id_hewan'] ?></td>
                <td><?= e($row['nama_hewan']) ?></td>
                <td><?= e($row['jenis']) ?></td>
                <td><?= e($row['ras']) ?></td>
                <td><?= e((string)$row['umur']) ?></td>
                <td><?= e($row['jenis_kelamin']) ?></td>
                <td><?= e($row['pemilik']) ?></td>
                <td class="actions">
                    <a class="btn btn-sm btn-secondary" href="edit.php?id=<?= (int)$row['id_hewan'] ?>">Edit</a>
                    <form action="hapus.php" method="post" class="inline-form" onsubmit="return confirm('Hapus data hewan ini?');">
                        <input type="hidden" name="id" value="<?= (int)$row['id_hewan'] ?>">
                        <button class="btn btn-sm btn-danger" type="submit">Hapus</button>
                    </form>
                </td>
            </tr>
        <?php endforeach; endif; ?>
        </tbody>
    </table>
</div>
<?php include '../../includes/footer.php'; ?>
