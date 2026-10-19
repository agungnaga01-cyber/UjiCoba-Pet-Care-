<?php
require_once '../../config/koneksi.php';
require_once '../../includes/auth.php';
require_role('user');
$id=(int)($_GET['id']??0);$id_user=(int)$_SESSION['user']['id_user'];
$s=$pdo->prepare(
"SELECT p.*,h.nama_hewan,h.jenis,k.nomor_kandang,l.nama_layanan,pb.status AS pembayaran_status,pb.metode,pb.tanggal_bayar
 FROM penitipan p
 JOIN hewan h ON h.id_hewan=p.id_hewan
 JOIN kandang k ON k.id_kandang=p.id_kandang
 JOIN layanan l ON l.id_layanan=p.id_layanan
 LEFT JOIN pembayaran pb ON pb.id_titip=p.id_titip
 WHERE p.id_titip=? AND h.id_user=?"
);$s->execute([$id,$id_user]);$data=$s->fetch();if(!$data)exit('Booking tidak ditemukan.');
$title='Detail Booking';
include '../../includes/header.php';?>
<div class="page-head"><h1>Booking #<?=$data['id_titip']?></h1></div><div class="card detail-grid">
<div><strong>Hewan</strong><span><?=e($data['nama_hewan'])?></span></div><div><strong>Jenis</strong><span><?=e($data['jenis'])?></span></div>
<div><strong>Kandang</strong><span><?=e($data['nomor_kandang'])?></span></div><div><strong>Layanan</strong><span><?=e($data['nama_layanan'])?></span></div>
<div><strong>Masuk</strong><span><?=e($data['tanggal_masuk'])?></span></div><div><strong>Keluar</strong><span><?=e($data['tanggal_keluar'])?></span></div>
<div><strong>Lama</strong><span><?=e((string)$data['lama_menginap'])?> hari</span></div><div><strong>Total</strong><span>Rp <?=number_format($data['total_biaya'],0,',','.')?></span></div>
<div><strong>Status Booking</strong><span><?=e($data['status'])?></span></div><div><strong>Status Pembayaran</strong><span><?=e($data['pembayaran_status'])?></span></div>
<div><strong>Metode</strong><span><?=e($data['metode'])?></span></div><div><strong>Catatan</strong><span><?=nl2br(e($data['catatan']))?></span></div>
</div><div class="form-actions"><a class="btn btn-secondary" href="index.php">Kembali</a></div>
<?php include '../../includes/footer.php';?>
