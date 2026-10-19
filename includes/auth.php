<?php
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

function e(?string $value): string
{
    return htmlspecialchars($value ?? '', ENT_QUOTES, 'UTF-8');
}

function require_login(): void
{
    if (!isset($_SESSION['user'])) {
        header('Location: ' . BASE_URL . '/auth/login.php');
        exit;
    }
}

function require_role(string $role): void
{
    require_login();

    if ($_SESSION['user']['role'] !== $role) {
        header('Location: ' . BASE_URL . '/index.php');
        exit;
    }
}

function current_user(): array
{
    return $_SESSION['user'] ?? [];
}
