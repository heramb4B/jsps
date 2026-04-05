<?php
/**
 * includes/navbar.php
 * Main public navigation — included by every public page.
 *
 * Variable expected:
 *   $activePage  — string matching a nav key: 'home','about','services','blog','contact'
 */

$activePage = $activePage ?? '';

$navLinks = [
    'home'     => ['label' => 'Home',     'href' => APP_URL . '/'],
    'about'    => ['label' => 'About Us', 'href' => APP_URL . '/pages/about.php'],
    'services' => ['label' => 'Services', 'href' => APP_URL . '/pages/services.php'],
    'blog'     => ['label' => 'Blog',     'href' => APP_URL . '/pages/blog.php'],
    'contact'  => ['label' => 'Contact',  'href' => APP_URL . '/pages/contact.php'],
];
?>

<!-- WhatsApp Float -->
<a class="wa-float" href="https://wa.me/919876543210" target="_blank" rel="noopener" aria-label="Chat on WhatsApp">
    <i class="bi bi-whatsapp"></i>
</a>

<!-- Flash Message -->
<?php $flash = getFlash(); if ($flash): ?>
<div class="toast-container position-fixed bottom-0 end-0 p-3" style="z-index:9999">
    <div class="toast show align-items-center text-bg-<?= $flash['type'] === 'success' ? 'success' : 'danger' ?> border-0" role="alert">
        <div class="d-flex">
            <div class="toast-body"><?= e($flash['message']) ?></div>
            <button type="button" class="btn-close btn-close-white me-2 m-auto" data-bs-dismiss="toast"></button>
        </div>
    </div>
</div>
<?php endif; ?>

<nav class="site-navbar" id="siteNavbar">
    <div class="nav-container">
        <!-- Logo -->
        <a class="navbar-brand" href="<?= APP_URL ?>/">
            <div class="logo-box" aria-hidden="true">JS<br>PS</div>
            <div class="logo-text">
                <span class="brand-name"><?= APP_NAME ?></span>
                <span class="brand-tagline"><?= APP_TAGLINE ?></span>
            </div>
        </a>

        <!-- Desktop Links -->
        <div class="nav-links d-none d-lg-flex">
            <?php foreach ($navLinks as $key => $link): ?>
                <a href="<?= $link['href'] ?>"
                   class="nav-link<?= $activePage === $key ? ' active' : '' ?>">
                    <?= $link['label'] ?>
                </a>
            <?php endforeach; ?>

            <?php if (isLoggedIn()):
                $user = currentUser();
            ?>
                <a href="<?= APP_URL . (isAdmin() ? '/admin/dashboard.php' : '/user/dashboard.php') ?>"
                   class="nav-link nav-cta">
                    <i class="bi bi-person-circle me-1"></i><?= e($user['first_name']) ?>
                </a>
            <?php else: ?>
                <a href="<?= APP_URL ?>/login.php" class="nav-link nav-cta">Login</a>
            <?php endif; ?>
        </div>

        <!-- Hamburger -->
        <button class="hamburger d-lg-none" id="hamburgerBtn" aria-label="Toggle navigation">
            <i class="bi bi-list"></i>
        </button>
    </div>

    <!-- Mobile Menu -->
    <div class="mobile-menu" id="mobileMenu">
        <?php foreach ($navLinks as $key => $link): ?>
            <a href="<?= $link['href'] ?>"
               class="<?= $activePage === $key ? 'active' : '' ?>">
                <?= $link['label'] ?>
            </a>
        <?php endforeach; ?>
        <?php if (isLoggedIn()): ?>
            <a href="<?= APP_URL . (isAdmin() ? '/admin/dashboard.php' : '/user/dashboard.php') ?>">Dashboard</a>
        <?php else: ?>
            <a href="<?= APP_URL ?>/login.php">Login / Register</a>
        <?php endif; ?>
    </div>
</nav>
