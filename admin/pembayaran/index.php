<?php
require_once '../../config/koneksi.php';
require_once '../../includes/auth.php';
require_role('admin');
$title='Data Pembayaran';
$data=$pdo->query(
"SELECT pb.*, p.id_titip, h.nama_hewan, u.nama AS pemilik
 FROM pembayaran pb
 JOIN penitipan p ON p.id_titip=pb.id_titip
 JOIN hewan h ON h.id_hewan=p.id_hewan
 JOIN users u ON u.id_user=h.id_user
 ORDER BY pb.id_bayar DESC"
)->fetchAll();
include '../../includes/header.php';?>
<div class="page-head"><h1>Data Pembayaran</h1></div>
<div class="card table-wrap"><table><thead><tr><th>ID</th><th>Penitipan</th><th>Pemilik</th><th>Hewan</th><th>Tanggal</th><th>Jumlah</th><th>Metode</th><th>Status</th><th>Aksi</th></tr></thead><tbody>
<?php foreach($data as $row):?><tr><td><?=$row['id_bayar']?></td><td>#<?=$row['id_titip']?></td><td><?=e($row['pemilik'])?></td><td><?=e($row['nama_hewan'])?></td><td><?=e($row['tanggal_bayar'])?></td><td>Rp <?=number_format($row['jumlah'],0,',','.')?></td><td><?=e($row['metode'])?></td><td><span class="badge"><?=e($row['status'])?></span></td>
<td class="actions"><a class="btn btn-sm btn-secondary" href="detail.php?id=<?=$row['id_bayar']?>">Detail</a><a class="btn btn-sm btn-secondary" href="edit.php?id=<?=$row['id_bayar']?>">Edit</a><form action="hapus.php" method="post" class="inline-form" onsubmit="return confirm('Hapus pembayaran ini?');"><input type="hidden" name="id" value="<?=$row['id_bayar']?>"><button class="btn btn-sm btn-danger">Hapus</button></form></td></tr><?php endforeach;?></tbody></table></div>
<?php include '../../includes/footer.php';?>
