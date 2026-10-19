<?php
require_once '../../config/koneksi.php';
require_once '../../includes/auth.php';
require_role('admin');

$id = (int)($_GET['id'] ?? 0);
$stmt = $pdo->prepare("SELECT * FROM users WHERE id_user = ?");
$stmt->execute([$id]);
$data = $stmt->fetch();

if (!$data) {
    exit('User tidak ditemukan.');
}

$title = 'Edit User';
$error = '';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $nama = trim($_POST['nama'] ?? '');
    $username = trim($_POST['username'] ?? '');
    $role = $_POST['role'] ?? 'user';
    $no_telp = trim($_POST['no_telp'] ?? '');
    $alamat = trim($_POST['alamat'] ?? '');
    $password = $_POST['password'] ?? '';

    if ($nama === '' || $username === '') {
        $error = 'Nama dan username wajib diisi.';
    } elseif (!in_array($role, ['admin', 'user'], true)) {
        $error = 'Role tidak valid.';
    } else {
        try {
            if ($password !== '') {
                $stmt = $pdo->prepare(
                    "UPDATE users SET nama=?, username=?, password=?, role=?, no_telp=?, alamat=? WHERE id_user=?"
                );
                $stmt->execute([
                    $nama, $username, password_hash($password, PASSWORD_DEFAULT),
                    $role, $no_telp, $alamat, $id
                ]);
            } else {
                $stmt = $pdo->prepare(
                    "UPDATE users SET nama=?, username=?, role=?, no_telp=?, alamat=? WHERE id_user=?"
                );
                $stmt->execute([$nama, $username, $role, $no_telp, $alamat, $id]);
            }

            if ($_SESSION['user']['id_user'] === $id) {
                $_SESSION['user']['nama'] = $nama;
                $_SESSION['user']['username'] = $username;
                $_SESSION['user']['role'] = $role;
            }

            header('Location: index.php');
            exit;
        } catch (PDOException $e) {
            $error = $e->getCode() === '23000'
                ? 'Username sudah digunakan.'
                : 'Gagal mengubah data.';
        }
    }
}

include '../../includes/header.php';
?>
<div class="page-head"><div><h1>Edit User</h1></div></div>
<div class="card form-card">
<?php if ($error): ?><div class="alert alert-danger"><?= e($error) ?></div><?php endif; ?>
<form method="post">
    <label>Nama</label><input type="text" name="nama" value="<?= e($data['nama']) ?>" required>
    <label>Username</label><input type="text" name="username" value="<?= e($data['username']) ?>" required>
    <label>Password Baru <small>(kosongkan jika tidak diubah)</small></label><input type="password" name="password">
    <label>Role</label>
    <select name="role" required>
        <option value="user" <?= $data['role']==='user'?'selected':'' ?>>User</option>
        <option value="admin" <?= $data['role']==='admin'?'selected':'' ?>>Admin</option>
    </select>
    <label>No. Telepon</label><input type="text" name="no_telp" value="<?= e($data['no_telp']) ?>">
    <label>Alamat</label><textarea name="alamat"><?= e($data['alamat']) ?></textarea>
    <div class="form-actions"><button class="btn btn-primary">Update</button><a class="btn btn-secondary" href="index.php">Batal</a></div>
</form>
</div>
<?php include '../../includes/footer.php'; ?>
