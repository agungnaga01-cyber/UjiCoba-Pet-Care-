<?php
require_once '../../config/koneksi.php';
require_once '../../includes/auth.php';
require_role('admin');

if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    header('Location: index.php');
    exit;
}

$id = (int)($_POST['id'] ?? 0);

if ($id === (int)$_SESSION['user']['id_user']) {
    exit('Akun yang sedang dipakai tidak boleh dihapus.');
}

$stmt = $pdo->prepare("DELETE FROM users WHERE id_user = ?");
$stmt->execute([$id]);

header('Location: index.php');
exit;
