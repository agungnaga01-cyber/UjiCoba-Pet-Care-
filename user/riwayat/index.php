<?php
require_once '../../config/koneksi.php';
require_once '../../includes/auth.php';
require_role('user');
$id_user=(int)$_SESSION['user']['id_user'];$title='Riwayat Penitipan';
$s=$pdo->prepare(
"SELECT p.*,h.nama_hewan,k.nomor_kandang,l.nama_layanan
 FROM penitipan p
 JOIN hewan h ON h.id_hewan=p.id_hewan
 JOIN kandang k ON k.id_kandang=p.id_kandang
 JOIN layanan l ON l.id_layanan=p.id_layanan
 WHERE h.id_user=? AND p.status IN ('selesai','dibatalkan')
 ORDER BY p.id_titip DESC"
);$s->execute([$id_user]);$data=$s->fetchAll();
include '../../includes/header.php';?>
<div class="page-head"><h1>Riwayat Penitipan</h1></div><div class="card table-wrap"><table><thead><tr><th>ID</th><th>Hewan</th><th>Kandang</th><th>Layanan</th><th>Masuk</th><th>Keluar</th><th>Total</th><th>Status</th></tr></thead><tbody>
<?php foreach($data as $row):?><tr><td>#<?=$row['id_titip']?></td><td><?=e($row['nama_hewan'])?></td><td><?=e($row['nomor_kandang'])?></td><td><?=e($row['nama_layanan'])?></td><td><?=e($row['tanggal_masuk'])?></td><td><?=e($row['tanggal_keluar'])?></td><td>Rp <?=number_format($row['total_biaya'],0,',','.')?></td><td><span class="badge"><?=e($row['status'])?></span></td></tr><?php endforeach;?>
</tbody></table></div>
<?php include '../../includes/footer.php';?>
