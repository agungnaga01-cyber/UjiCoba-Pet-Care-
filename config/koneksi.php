<?php
$host = 'localhost';
$db   = 'penitipan_peliharaan';
$user = 'root';
$pass = '';

define('BASE_URL', 'http://localhost/PetCare');

try {
    $pdo = new PDO(
        "mysql:host={$host};dbname={$db};charset=utf8mb4",
        $user,
        $pass,
        [
            PDO::ATTR_ERRMODE            => PDO::ERRMODE_EXCEPTION,
            PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC,
            PDO::ATTR_EMULATE_PREPARES   => false,
        ]
    );
} catch (PDOException $e) {
    exit('Koneksi database gagal: ' . htmlspecialchars($e->getMessage()));
}
