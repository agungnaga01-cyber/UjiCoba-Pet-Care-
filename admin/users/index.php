<?php
require_once '../../config/koneksi.php';
require_once '../../includes/auth.php';
require_role('admin');

$title = 'Data Users';
$data = $pdo->query("SELECT * FROM users ORDER BY id_user DESC")->fetchAll();

include '../../includes/header.php';
?>
<div class="page-head">
    <div><h1>Data Users</h1><p class="muted">Kelola akun admin dan user.</p></div>
    <a class="btn btn-primary" href="tambah.php">+ Tambah User</a>
</div>

<div class="card table-wrap">
<table>
<thead><tr>
    <th>ID</th><th>Nama</th><th>Username</th><th>Role</th><th>No. Telp</th><th>Aksi</th>
</tr></thead>
<tbody>
<?php foreach ($data as $row): ?>
<tr>
    <td><?= e((string)$row['id_user']) ?></td>
    <td><?= e($row['nama']) ?></td>
    <td><?= e($row['username']) ?></td>
    <td><span class="badge"><?= e($row['role']) ?></span></td>
    <td><?= e($row['no_telp']) ?></td>
    <td class="actions">
        <a class="btn btn-sm btn-secondary" href="edit.php?id=<?= $row['id_user'] ?>">Edit</a>
        <form action="hapus.php" method="post" class="inline-form" onsubmit="return confirm('Hapus user ini?');">
            <input type="hidden" name="id" value="<?= $row['id_user'] ?>">
            <button class="btn btn-sm btn-danger" type="submit">Hapus</button>
        </form>
    </td>
</tr>
<?php endforeach; ?>
</tbody>
</table>
</div>
<?php include '../../includes/footer.php'; ?>
