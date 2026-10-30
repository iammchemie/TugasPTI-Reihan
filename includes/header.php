<?php
/**
 * Header umum: memuat session, bahasa, navigasi, language switcher, dan flash message.
 * Variabel opsional: $pageTitle (kunci terjemahan).
 */

require_once __DIR__ . '/auth.php';
require_once __DIR__ . '/lang.php';

$flashes = get_flashes();
$current = basename($_SERVER['SCRIPT_NAME'] ?? '');
$isHome  = $current === 'index.php';
$isAdd   = $current === 'create.php';
?>
<!DOCTYPE html>
<html lang="<?= e($_SESSION['lang']) ?>">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title><?= e(t($pageTitle ?? 'app_name')) ?> - <?= e(t('app_name')) ?></title>
    <link rel="stylesheet" href="assets/style.css">
</head>
<body>
<header class="topbar">
    <div class="topbar-inner">
        <a class="brand" href="<?= is_logged_in() ? 'index.php' : 'login.php' ?>">
            <span class="brand-title"><?= e(t('app_name')) ?></span>
            <span class="brand-tagline"><?= e(t('app_tagline')) ?></span>
        </a>

        <nav class="nav">
            <?php if (is_logged_in()): ?>
                <a href="index.php" class="<?= $isHome ? 'active' : '' ?>"><?= e(t('nav_books')) ?></a>
                <a href="create.php" class="<?= $isAdd ? 'active' : '' ?>"><?= e(t('nav_add')) ?></a>
                <span class="nav-user">
                    <?= e(t('logged_in_as')) ?>: <strong><?= e($_SESSION['username'] ?? '') ?></strong>
                </span>
                <a href="logout.php" class="btn btn-outline btn-sm"><?= e(t('logout')) ?></a>
            <?php endif; ?>

            <span class="lang-switch" title="<?= e(t('language')) ?>">
                <a href="set_lang.php?lang=id" class="<?= $_SESSION['lang'] === 'id' ? 'active' : '' ?>">ID</a>
                <span class="sep">|</span>
                <a href="set_lang.php?lang=en" class="<?= $_SESSION['lang'] === 'en' ? 'active' : '' ?>">EN</a>
            </span>
        </nav>
    </div>
</header>

<main class="container">
<?php foreach ($flashes as $flash): ?>
    <div class="flash flash-<?= e($flash['type']) ?>" role="alert"><?= e(t($flash['message'])) ?></div>
<?php endforeach; ?>
