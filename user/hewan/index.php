<?php
require_once '../../config/koneksi.php';
require_once '../../includes/auth.php';
require_role('user');

$id_user = (int)$_SESSION['user']['id_user'];
$title = 'Hewan Saya';
$stmt = $pdo->prepare("SELECT * FROM hewan WHERE id_user=? ORDER BY id_hewan DESC");
$stmt->execute([$id_user]);
$data = $stmt->fetchAll();

include '../../includes/header.php';
?>
<div class="page-head"><div><h1>Hewan Saya</h1><p class="muted">Daftarkan anjing, kucing, burung, reptil, ikan, dan hewan peliharaan lainnya.</p></div><a class="btn btn-primary" href="tambah.php">+ Tambah Hewan</a></div>
<div class="card table-wrap">
<table><thead><tr><th>ID</th><th>Nama</th><th>Kategori</th><th>Ras / Spesies</th><th>Umur</th><th>Kelamin</th><th>Aksi</th></tr></thead><tbody>
<?php if (!$data): ?>
<tr><td colspan="7" class="empty-state">Kamu belum punya hewan. Klik Tambah Hewan untuk mendaftarkan hewan peliharaan.</td></tr>
<?php else: foreach ($data as $row): ?>
<tr>
<td><?= (int)$row['id_hewan'] ?></td><td><?= e($row['nama_hewan']) ?></td><td><?= e($row['jenis']) ?></td><td><?= e($row['ras']) ?></td><td><?= e((string)$row['umur']) ?></td><td><?= e($row['jenis_kelamin']) ?></td>
<td class="actions"><a class="btn btn-sm btn-secondary" href="edit.php?id=<?= (int)$row['id_hewan'] ?>">Edit</a>
<form action="hapus.php" method="post" class="inline-form" onsubmit="return confirm('Hapus hewan ini?');"><input type="hidden" name="id" value="<?= (int)$row['id_hewan'] ?>"><button class="btn btn-sm btn-danger" type="submit">Hapus</button></form></td>
</tr>
<?php endforeach; endif; ?>
</tbody></table></div>
<?php include '../../includes/footer.php'; ?>
