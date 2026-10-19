<?php
require_once '../../config/koneksi.php';
require_once '../../includes/auth.php';
require_role('admin');
$id=(int)($_GET['id']??0);
$s=$pdo->prepare(
"SELECT p.*, h.nama_hewan, h.jenis, u.nama AS pemilik, u.no_telp, k.nomor_kandang, k.jenis AS jenis_kandang, l.nama_layanan
 FROM penitipan p
 JOIN hewan h ON h.id_hewan=p.id_hewan
 JOIN users u ON u.id_user=h.id_user
 JOIN kandang k ON k.id_kandang=p.id_kandang
 JOIN layanan l ON l.id_layanan=p.id_layanan
 WHERE p.id_titip=?"
);$s->execute([$id]);$data=$s->fetch();if(!$data)exit('Data penitipan tidak ditemukan.');
$title='Detail Penitipan';
include '../../includes/header.php';?>
<div class="page-head"><h1>Detail Penitipan #<?=$data['id_titip']?></h1></div>
<div class="card detail-grid">
<div><strong>Pemilik</strong><span><?=e($data['pemilik'])?></span></div>
<div><strong>No. Telp</strong><span><?=e($data['no_telp'])?></span></div>
<div><strong>Hewan</strong><span><?=e($data['nama_hewan'])?></span></div>
<div><strong>Jenis</strong><span><?=e($data['jenis'])?></span></div>
<div><strong>Kandang</strong><span><?=e($data['nomor_kandang'])?></span></div>
<div><strong>Layanan</strong><span><?=e($data['nama_layanan'])?></span></div>
<div><strong>Tanggal Masuk</strong><span><?=e($data['tanggal_masuk'])?></span></div>
<div><strong>Tanggal Keluar</strong><span><?=e($data['tanggal_keluar'])?></span></div>
<div><strong>Lama Menginap</strong><span><?=e((string)$data['lama_menginap'])?> hari</span></div>
<div><strong>Total</strong><span>Rp <?=number_format($data['total_biaya'],0,',','.')?></span></div>
<div><strong>Status</strong><span><?=e($data['status'])?></span></div>
<div><strong>Catatan</strong><span><?=nl2br(e($data['catatan']))?></span></div>
</div>
<div class="form-actions"><a class="btn btn-primary" href="edit.php?id=<?=$data['id_titip']?>">Edit Status</a><a class="btn btn-secondary" href="index.php">Kembali</a></div>
<?php include '../../includes/footer.php';?>
