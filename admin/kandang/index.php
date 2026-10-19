<?php
require_once '../../config/koneksi.php';
require_once '../../includes/auth.php';
require_role('admin');
$title='Data Kandang';
$data=$pdo->query("SELECT * FROM kandang ORDER BY id_kandang DESC")->fetchAll();
include '../../includes/header.php';
?>
<div class="page-head"><div><h1>Data Kandang / Wadah</h1><p class="muted">Kelola kandang, aviary, terrarium, dan akuarium sesuai kategori hewan.</p></div><a class="btn btn-primary" href="tambah.php">+ Tambah Kandang / Wadah</a></div>
<div class="card table-wrap">
<table><thead><tr><th>ID</th><th>Nomor</th><th>Kategori Hewan</th><th>Ukuran / Kapasitas</th><th>Harga/Hari</th><th>Status</th><th>Aksi</th></tr></thead>
<tbody><?php foreach($data as $row): ?>
<tr><td><?= $row['id_kandang'] ?></td><td><?= e($row['nomor_kandang']) ?></td><td><?= e($row['jenis']) ?></td><td><?= e($row['ukuran']) ?></td><td>Rp <?= number_format($row['harga_per_hari'],0,',','.') ?></td><td><span class="badge"><?= e($row['status']) ?></span></td>
<td class="actions"><a class="btn btn-sm btn-secondary" href="edit.php?id=<?= $row['id_kandang'] ?>">Edit</a>
<form action="hapus.php" method="post" class="inline-form" onsubmit="return confirm('Hapus kandang ini?');"><input type="hidden" name="id" value="<?= $row['id_kandang'] ?>"><button class="btn btn-sm btn-danger">Hapus</button></form></td></tr>
<?php endforeach; ?></tbody></table></div>
<?php include '../../includes/footer.php'; ?>
