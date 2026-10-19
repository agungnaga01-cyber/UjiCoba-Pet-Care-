<?php
if (!isset($title)) {
    $title = 'PetCare';
}
?>
<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?= e($title) ?> - PetCare</title>
    <link rel="stylesheet" href="<?= BASE_URL ?>/assets/css/style.css">
    <link rel="stylesheet" href="<?= BASE_URL ?>/assets/css/dashboard.css">
</head>
<body>
<?php include __DIR__ . '/navbar.php'; ?>
<div class="app-shell">
<?php include __DIR__ . '/sidebar.php'; ?>
<main class="content">
