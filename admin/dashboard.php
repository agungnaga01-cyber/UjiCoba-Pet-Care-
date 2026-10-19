<?php
require_once '../config/koneksi.php';
require_once '../includes/auth.php';
require_role('admin');

$title = 'Dashboard Admin';

$counts = [];
foreach (['users', 'hewan', 'kandang', 'layanan', 'penitipan', 'pembayaran'] as $table) {
    $counts[$table] = (int) $pdo->query("SELECT COUNT(*) FROM {$table}")->fetchColumn();
}

include '../includes/header.php';
?>
<div class="page-head">
    <div>
        <h1>Dashboard Admin</h1>
        <p class="muted">Kelola seluruh data PetCare.</p>
    </div>
</div>

<div class="stat-grid">
    <div class="stat-card"><span>Users</span><strong><?= $counts['users'] ?></strong></div>
    <div class="stat-card"><span>Hewan</span><strong><?= $counts['hewan'] ?></strong></div>
    <div class="stat-card"><span>Kandang</span><strong><?= $counts['kandang'] ?></strong></div>
    <div class="stat-card"><span>Layanan</span><strong><?= $counts['layanan'] ?></strong></div>
    <div class="stat-card"><span>Penitipan</span><strong><?= $counts['penitipan'] ?></strong></div>
    <div class="stat-card"><span>Pembayaran</span><strong><?= $counts['pembayaran'] ?></strong></div>
</div>

<div class="card">
    <h2>Menu cepat</h2>
    <div class="quick-links">
        <a class="btn btn-primary" href="penitipan/index.php">Kelola Penitipan</a>
        <a class="btn btn-secondary" href="pembayaran/index.php">Kelola Pembayaran</a>
        <a class="btn btn-secondary" href="kandang/index.php">Kelola Kandang</a>
    </div>
</div>
<?php include '../includes/footer.php'; ?>
