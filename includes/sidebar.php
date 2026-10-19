<?php
$role = $_SESSION['user']['role'] ?? null;

/* Halaman yang sedang dibuka */
$current_page = parse_url($_SERVER['REQUEST_URI'], PHP_URL_PATH);

/* Cek menu aktif */
function isActive($path)
{
    global $current_page;

    if (strpos($current_page, $path) !== false) {
        return 'active';
    }

    return '';
}
?>

<style>
    /* ================================
       SIDEBAR MENU
    ================================= */

    .sidebar a.active {
        background: #2563eb !important;
        color: #ffffff !important;
        border-radius: 9px !important;
    }

    .sidebar a.active:hover {
        background: #2563eb !important;
        color: #ffffff !important;
    }

    .sidebar a:hover {
        background: #1f2937;
        color: #ffffff;
    }
</style>

<aside class="sidebar">

    <?php if ($role === 'admin'): ?>

        <h3>Menu Admin</h3>

        <a
            href="<?= BASE_URL ?>/admin/dashboard.php"
            class="<?= isActive('/admin/dashboard.php') ?>">
            Dashboard
        </a>

        <a
            href="<?= BASE_URL ?>/admin/users/index.php"
            class="<?= isActive('/admin/users/') ?>">
            Users
        </a>

        <a
            href="<?= BASE_URL ?>/admin/hewan/index.php"
            class="<?= isActive('/admin/hewan/') ?>">
            Hewan
        </a>

        <a
            href="<?= BASE_URL ?>/admin/kandang/index.php"
            class="<?= isActive('/admin/kandang/') ?>">
            Kandang
        </a>

        <a
            href="<?= BASE_URL ?>/admin/layanan/index.php"
            class="<?= isActive('/admin/layanan/') ?>">
            Layanan
        </a>

        <a
            href="<?= BASE_URL ?>/admin/penitipan/index.php"
            class="<?= isActive('/admin/penitipan/') ?>">
            Penitipan
        </a>

        <a
            href="<?= BASE_URL ?>/admin/pembayaran/index.php"
            class="<?= isActive('/admin/pembayaran/index.php') || (isActive('/admin/pembayaran/') && !isActive('/admin/pembayaran/rekap.php')) ? 'active' : '' ?>">
            Pembayaran
        </a>

        <a
            href="<?= BASE_URL ?>/admin/pembayaran/rekap.php"
            class="<?= isActive('/admin/pembayaran/rekap.php') ?>">
            Rekap Bulanan
        </a>

    <?php elseif ($role === 'user'): ?>

        <h3>Menu User</h3>

        <a
            href="<?= BASE_URL ?>/user/dashboard.php"
            class="<?= isActive('/user/dashboard.php') ?>">
            Dashboard
        </a>

        <a
            href="<?= BASE_URL ?>/user/profil.php"
            class="<?= isActive('/user/profil.php') ?>">
            Profil Saya
        </a>

        <a
            href="<?= BASE_URL ?>/user/hewan/index.php"
            class="<?= isActive('/user/hewan/') ?>">
            Hewan Saya
        </a>

        <a
            href="<?= BASE_URL ?>/user/booking/index.php"
            class="<?= isActive('/user/booking/') ?>">
            Booking
        </a>

        <a
            href="<?= BASE_URL ?>/user/riwayat/index.php"
            class="<?= isActive('/user/riwayat/') ?>">
            Riwayat
        </a>

        <a
            href="<?= BASE_URL ?>/user/pembayaran/index.php"
            class="<?= isActive('/user/pembayaran/') ?>">
            Pembayaran
        </a>

    <?php endif; ?>

</aside>