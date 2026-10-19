<?php
require_once '../../config/koneksi.php';
require_once '../../includes/auth.php';
require_role('admin');

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $id = (int)($_POST['id'] ?? 0);
    $stmt = $pdo->prepare("DELETE FROM hewan WHERE id_hewan=?");
    $stmt->execute([$id]);
}

header('Location: index.php');
exit;
