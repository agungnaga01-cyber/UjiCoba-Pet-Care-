<header class="topbar">
    <div class="brand">
        <a href="<?= BASE_URL ?>/index.php">🐾 PetCare</a>
    </div>

    <div class="topbar-user">
        <?php if (isset($_SESSION['user'])): ?>
            <span><?= e($_SESSION['user']['nama']) ?></span>
            <span class="role-badge"><?= e(ucfirst($_SESSION['user']['role'])) ?></span>
            <a class="btn btn-danger btn-sm" href="<?= BASE_URL ?>/auth/logout.php">Logout</a>
        <?php endif; ?>
    </div>
</header>
