<?php
session_start();

require_once '../config/koneksi.php';

$username = trim($_POST['username'] ?? '');
$password = $_POST['password'] ?? '';

if ($username === '' || $password === '') {
    $_SESSION['login_error'] = 'Username dan password wajib diisi.';
    header('Location: login.php');
    exit;
}

$stmt = $pdo->prepare("SELECT * FROM users WHERE username = ? LIMIT 1");
$stmt->execute([$username]);

$user = $stmt->fetch();

if (!$user || $password !== $user['password']) {
    $_SESSION['login_error'] = 'Username atau password salah.';
    header('Location: login.php');
    exit;
}

session_regenerate_id(true);

$_SESSION['user'] = [
    'id_user'  => $user['id_user'],
    'nama'     => $user['nama'],
    'username' => $user['username'],
    'role'     => $user['role']
];

if ($user['role'] === 'admin') {
    header('Location: ../admin/dashboard.php');
} else {
    header('Location: ../user/dashboard.php');
}

exit;