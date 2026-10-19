<?php
require_once '../../config/koneksi.php';
require_once '../../includes/auth.php';
require_role('admin');
$title='Data Layanan';
$data=$pdo->query("SELECT * FROM layanan ORDER BY id_layanan DESC")->fetchAll();
include '../../includes/header.php';
?>
<div class="page-head"><h1>Data Layanan</h1><a class="btn btn-primary" href="tambah.php">+ Tambah Layanan</a></div>
<div class="card table-wrap"><table><thead><tr><th>ID</th><th>Nama</th><th>Harga</th><th>Deskripsi</th><th>Status</th><th>Aksi</th></tr></thead><tbody>
<?php foreach($data as $row):?><tr><td><?=$row['id_layanan']?></td><td><?=e($row['nama_layanan'])?></td><td>Rp <?=number_format($row['harga'],0,',','.')?></td><td><?=e($row['deskripsi'])?></td><td><span class="badge"><?=e($row['status'])?></span></td><td class="actions"><a class="btn btn-sm btn-secondary" href="edit.php?id=<?=$row['id_layanan']?>">Edit</a><form action="hapus.php" method="post" class="inline-form" onsubmit="return confirm('Hapus layanan ini?');"><input type="hidden" name="id" value="<?=$row['id_layanan']?>"><button class="btn btn-sm btn-danger">Hapus</button></form></td></tr><?php endforeach;?>
</tbody></table></div>
<?php include '../../includes/footer.php';?>
