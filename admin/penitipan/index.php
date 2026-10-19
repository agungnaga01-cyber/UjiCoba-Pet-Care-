<?php
require_once '../../config/koneksi.php';
require_once '../../includes/auth.php';
require_role('admin');
$title='Data Penitipan';
$data=$pdo->query(
"SELECT p.*, h.nama_hewan, u.nama AS pemilik, k.nomor_kandang, l.nama_layanan
 FROM penitipan p
 JOIN hewan h ON h.id_hewan=p.id_hewan
 JOIN users u ON u.id_user=h.id_user
 JOIN kandang k ON k.id_kandang=p.id_kandang
 JOIN layanan l ON l.id_layanan=p.id_layanan
 ORDER BY p.id_titip DESC"
)->fetchAll();
include '../../includes/header.php';?>
<div class="page-head"><h1>Data Penitipan</h1></div>
<div class="card table-wrap"><table><thead><tr><th>ID</th><th>Pemilik</th><th>Hewan</th><th>Kandang</th><th>Layanan</th><th>Masuk</th><th>Keluar</th><th>Total</th><th>Status</th><th>Aksi</th></tr></thead><tbody>
<?php foreach($data as $row):?><tr>
<td><?=$row['id_titip']?></td><td><?=e($row['pemilik'])?></td><td><?=e($row['nama_hewan'])?></td><td><?=e($row['nomor_kandang'])?></td><td><?=e($row['nama_layanan'])?></td>
<td><?=e($row['tanggal_masuk'])?></td><td><?=e($row['tanggal_keluar'])?></td><td>Rp <?=number_format($row['total_biaya'],0,',','.')?></td>
<td><span class="badge"><?=e($row['status'])?></span></td>
<td class="actions"><a class="btn btn-sm btn-secondary" href="detail.php?id=<?=$row['id_titip']?>">Detail</a><a class="btn btn-sm btn-secondary" href="edit.php?id=<?=$row['id_titip']?>">Edit</a>
<form action="hapus.php" method="post" class="inline-form" onsubmit="return confirm('Hapus transaksi ini?');"><input type="hidden" name="id" value="<?=$row['id_titip']?>"><button class="btn btn-sm btn-danger">Hapus</button></form></td>
</tr><?php endforeach;?></tbody></table></div>
<?php include '../../includes/footer.php';?>
