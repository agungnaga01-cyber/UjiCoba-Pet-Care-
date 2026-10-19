<?php
require_once '../../config/koneksi.php';
require_once '../../includes/auth.php';
require_role('user');
$id_user=(int)$_SESSION['user']['id_user'];$title='Pembayaran';
$s=$pdo->prepare(
"SELECT pb.*,p.id_titip,h.nama_hewan
 FROM pembayaran pb
 JOIN penitipan p ON p.id_titip=pb.id_titip
 JOIN hewan h ON h.id_hewan=p.id_hewan
 WHERE h.id_user=? ORDER BY pb.id_bayar DESC"
);$s->execute([$id_user]);$data=$s->fetchAll();
include '../../includes/header.php';?>
<div class="page-head"><h1>Pembayaran</h1></div><div class="card table-wrap"><table><thead><tr><th>ID</th><th>Penitipan</th><th>Hewan</th><th>Tanggal</th><th>Jumlah</th><th>Metode</th><th>Status</th><th>Aksi</th></tr></thead><tbody>
<?php foreach($data as $row):?><tr><td><?=$row['id_bayar']?></td><td>#<?=$row['id_titip']?></td><td><?=e($row['nama_hewan'])?></td><td><?=e($row['tanggal_bayar'])?></td><td>Rp <?=number_format($row['jumlah'],0,',','.')?></td><td><?=e($row['metode'])?></td><td><span class="badge"><?=e($row['status'])?></span></td><td><a class="btn btn-sm btn-secondary" href="detail.php?id=<?=$row['id_bayar']?>">Detail</a></td></tr><?php endforeach;?>
</tbody></table></div>
<?php include '../../includes/footer.php';?>
