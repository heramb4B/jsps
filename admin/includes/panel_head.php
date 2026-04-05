<?php
/**
 * admin/includes/panel_head.php
 * Renders the <head> block + sidebar + topbar for admin pages.
 *
 * Variables expected:
 *   $pageTitle        — browser tab title
 *   $activeAdminPage  — sidebar highlight key
 *   $topbarTitle      — heading shown in topbar
 *   $extraCss         — array of extra CSS file names (optional)
 */

$topbarTitle = $topbarTitle ?? 'Dashboard';
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="robots" content="noindex, nofollow">
    <title><?= e($pageTitle ?? 'Admin | ' . APP_NAME) ?></title>

    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.0/font/bootstrap-icons.css">
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="stylesheet" href="https://fonts.googleapis.com/css2?family=Playfair+Display:wght@400;600;700&family=DM+Sans:wght@300;400;500;600&display=swap">
    <link rel="stylesheet" href="<?= ASSETS_URL ?>/css/style.css">
    <link rel="stylesheet" href="<?= ASSETS_URL ?>/css/panel.css">

    <?php if (!empty($extraCss)): ?>
        <?php foreach ($extraCss as $css): ?>
            <link rel="stylesheet" href="<?= ASSETS_URL ?>/css/<?= e($css) ?>">
        <?php endforeach; ?>
    <?php endif; ?>
</head>
<body class="panel-body">

<div class="panel-layout">

    <?php include __DIR__ . '/sidebar.php'; ?>

    <div class="panel-main-wrap">

        <!-- Topbar -->
        <header class="panel-topbar">
            <div class="topbar-left">
                <button class="mobile-sidebar-toggle" onclick="toggleMobileSidebar()" aria-label="Toggle sidebar">
                    <i class="bi bi-list"></i>
                </button>
                <h1 class="topbar-title"><?= e($topbarTitle) ?></h1>
            </div>
            <div class="topbar-right">
                <a href="<?= APP_URL ?>/" target="_blank" class="topbar-btn" title="View Site">
                    <i class="bi bi-box-arrow-up-right"></i> View Site
                </a>
                <a href="<?= APP_URL ?>/logout.php" class="topbar-btn topbar-logout">
                    <i class="bi bi-power"></i> Logout
                </a>
            </div>
        </header>

        <!-- Flash Message -->
        <?php $flash = getFlash(); if ($flash): ?>
            <div class="panel-flash alert alert-<?= $flash['type'] === 'success' ? 'success' : 'danger' ?> alert-dismissible fade show">
                <?= e($flash['message']) ?>
                <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
            </div>
        <?php endif; ?>

        <!-- Page content is injected after this include -->
        <div class="panel-content">
