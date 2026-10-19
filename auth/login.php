<?php
session_start();

if (isset($_SESSION['user'])) {
    header('Location: ' . (
        $_SESSION['user']['role'] === 'admin'
        ? '../admin/dashboard.php'
        : '../user/dashboard.php'
    ));
    exit;
}

$error = $_SESSION['login_error'] ?? '';
unset($_SESSION['login_error']);
?>
<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Login - PetCare</title>
    <link rel="stylesheet" href="../assets/css/style.css">
    <link rel="stylesheet" href="../assets/css/login.css">
</head>
<body class="login-page">
<div class="login-card">
    <div class="login-logo">🐾</div>
    <h1>PetCare</h1>
    <p class="muted">Sistem Penitipan Hewan</p>

    <?php if ($error): ?>
        <div class="alert alert-danger"><?= htmlspecialchars($error) ?></div>
    <?php endif; ?>

    <form action="proses_login.php" method="post">
        <label>Username</label>
        <input type="text" name="username" required autocomplete="username">

        <label>Password</label>
        <input type="password" name="password" required autocomplete="current-password">

        <button class="btn btn-primary btn-full" type="submit">Login</button>
    </form>

    <div class="demo-box">
    <strong>Demo Login:</strong><br>
    Admin: <code>admin</code> / <code>admin123</code><br>
    User 1: <code>budi</code> / <code>budi123</code><br>
    User 2: <code>andi</code> / <code>andi123</code>
    </div>
</div>
</body>
</html>
