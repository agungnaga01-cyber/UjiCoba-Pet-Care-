<?php
require_once '../../config/koneksi.php';
require_once '../../includes/auth.php';
require_role('admin');

$title = 'Tambah User';
$error = '';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $nama = trim($_POST['nama'] ?? '');
    $username = trim($_POST['username'] ?? '');
    $password = $_POST['password'] ?? '';
    $role = $_POST['role'] ?? 'user';
    $no_telp = trim($_POST['no_telp'] ?? '');
    $alamat = trim($_POST['alamat'] ?? '');

    if ($nama === '' || $username === '' || $password === '') {
        $error = 'Nama, username, dan password wajib diisi.';
    } elseif (!in_array($role, ['admin', 'user'], true)) {
        $error = 'Role tidak valid.';
    } else {
        try {
            $stmt = $pdo->prepare(
                "INSERT INTO users (nama, username, password, role, no_telp, alamat)
                 VALUES (?, ?, ?, ?, ?, ?)"
            );
            $stmt->execute([
                $nama, $username, password_hash($password, PASSWORD_DEFAULT),
                $role, $no_telp, $alamat
            ]);
            header('Location: index.php');
            exit;
        } catch (PDOException $e) {
            $error = $e->getCode() === '23000'
                ? 'Username sudah digunakan.'
                : 'Gagal menyimpan data.';
        }
    }
}

include '../../includes/header.php';
?>
<div class="page-head"><div><h1>Tambah User</h1></div></div>
<div class="card form-card">
<?php if ($error): ?><div class="alert alert-danger"><?= e($error) ?></div><?php endif; ?>
<form method="post">
    <label>Nama</label><input type="text" name="nama" required>
    <label>Username</label><input type="text" name="username" required>
    <label>Password</label><input type="password" name="password" required>
    <label>Role</label>
    <select name="role" required><option value="user">User</option><option value="admin">Admin</option></select>
    <label>No. Telepon</label><input type="text" name="no_telp">
    <label>Alamat</label><textarea name="alamat"></textarea>
    <div class="form-actions"><button class="btn btn-primary">Simpan</button><a class="btn btn-secondary" href="index.php">Batal</a></div>
</form>
</div>
<?php include '../../includes/footer.php'; ?>
