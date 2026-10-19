<?php
require_once '../../config/koneksi.php';
require_once '../../includes/auth.php';
require_role('user');
$id=(int)($_GET['id']??0);$id_user=(int)$_SESSION['user']['id_user'];
$s=$pdo->prepare(
"SELECT pb.*,p.id_titip,h.nama_hewan
 FROM pembayaran pb
 JOIN penitipan p ON p.id_titip=pb.id_titip
 JOIN hewan h ON h.id_hewan=p.id_hewan
 WHERE pb.id_bayar=? AND h.id_user=?"
);$s->execute([$id,$id_user]);$data=$s->fetch();if(!$data)exit('Pembayaran tidak ditemukan.');
$title='Detail Pembayaran';
include '../../includes/header.php';?>
<div class="page-head"><h1>Pembayaran #<?=$data['id_bayar']?></h1></div><div class="card detail-grid">
<div><strong>Penitipan</strong><span>#<?=$data['id_titip']?></span></div><div><strong>Hewan</strong><span><?=e($data['nama_hewan'])?></span></div>
<div><strong>Tanggal</strong><span><?=e($data['tanggal_bayar'])?></span></div><div><strong>Jumlah</strong><span>Rp <?=number_format($data['jumlah'],0,',','.')?></span></div>
<div><strong>Metode</strong><span><?=e($data['metode'])?></span></div><div><strong>Status</strong><span><?=e($data['status'])?></span></div>
</div><div class="form-actions"><a class="btn btn-secondary" href="index.php">Kembali</a></div>
<?php include '../../includes/footer.php';?>
