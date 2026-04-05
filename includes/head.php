<?php
/**
 * includes/head.php
 * Shared <head> block — included by every page.
 *
 * Variables expected (all optional):
 *   $pageTitle       — <title> override
 *   $metaDescription — meta description override
 *   $extraCss        — array of additional CSS file paths relative to assets/css/
 */

$pageTitle       = $pageTitle       ?? APP_NAME;
$metaDescription = $metaDescription ?? 'JSPS Accounting Solutions Pvt. Ltd. — Professional CA firm offering GST, Income Tax, Company Registration, Compliance, and financial advisory services in Mumbai.';
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="description"  content="<?= e($metaDescription) ?>">
    <meta name="robots"       content="index, follow">
    <meta property="og:title" content="<?= e($pageTitle) ?>">
    <meta property="og:description" content="<?= e($metaDescription) ?>">
    <meta property="og:type"  content="website">
    <meta property="og:url"   content="<?= e(APP_URL . $_SERVER['REQUEST_URI']) ?>">

    <title><?= e($pageTitle) ?></title>

    <!-- Bootstrap 5 -->
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css">
    <!-- Bootstrap Icons -->
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.0/font/bootstrap-icons.css">
    <!-- Google Fonts -->
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="stylesheet"  href="https://fonts.googleapis.com/css2?family=Playfair+Display:wght@400;600;700&family=DM+Sans:wght@300;400;500;600&display=swap">
    <!-- Global Stylesheet -->
    <link rel="stylesheet" href="<?= ASSETS_URL ?>/css/style.css">

    <?php if (!empty($extraCss)): ?>
        <?php foreach ($extraCss as $css): ?>
            <link rel="stylesheet" href="<?= ASSETS_URL ?>/css/<?= e($css) ?>">
        <?php endforeach; ?>
    <?php endif; ?>
</head>
<body>
