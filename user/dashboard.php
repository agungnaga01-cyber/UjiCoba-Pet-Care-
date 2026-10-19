<?php
require_once '../config/koneksi.php';
require_once '../includes/auth.php';
require_role('user');

$title='Dashboard User';
$id=(int)$_SESSION['user']['id_user'];

$s=$pdo->prepare("SELECT COUNT(*) FROM hewan WHERE id_user=?");$s->execute([$id]);$jumlah_hewan=(int)$s->fetchColumn();
$s=$pdo->prepare("SELECT COUNT(*) FROM penitipan p JOIN hewan h ON h.id_hewan=p.id_hewan WHERE h.id_user=?");$s->execute([$id]);$jumlah_booking=(int)$s->fetchColumn();
$s=$pdo->prepare("SELECT COUNT(*) FROM pembayaran pb JOIN penitipan p ON p.id_titip=pb.id_titip JOIN hewan h ON h.id_hewan=p.id_hewan WHERE h.id_user=? AND pb.status='pending'");$s->execute([$id]);$jumlah_pending=(int)$s->fetchColumn();

include '../includes/header.php';?>
<div class="page-head"><div><h1>Selamat Datang, <?=e($_SESSION['user']['nama'])?>!</h1><p class="muted">Kelola hewan dan penitipanmu di sini.</p></div></div>
<div class="stat-grid">
<div class="stat-card"><span>Hewan Saya</span><strong><?=$jumlah_hewan?></strong></div>
<div class="stat-card"><span>Total Booking</span><strong><?=$jumlah_booking?></strong></div>
<div class="stat-card"><span>Pembayaran Pending</span><strong><?=$jumlah_pending?></strong></div>
</div>
<div class="card"><h2>Menu cepat</h2><div class="quick-links"><a class="btn btn-primary" href="booking/tambah.php">+ Buat Booking</a><a class="btn btn-secondary" href="hewan/tambah.php">+ Tambah Hewan</a><a class="btn btn-secondary" href="pembayaran/index.php">Lihat Pembayaran</a></div></div>
<?php include '../includes/footer.php';?>
