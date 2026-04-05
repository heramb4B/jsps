<?php
/**
 * admin/includes/sidebar.php
 * Admin panel left navigation.
 *
 * Variable expected:
 *   $activeAdminPage  — string key matching one of the nav items
 */

$activeAdminPage = $activeAdminPage ?? '';
$user            = currentUser();
$initials        = strtoupper(substr($user['first_name'], 0, 1) . substr($user['last_name'], 0, 1));

$navItems = [
    'dashboard'  => ['icon' => 'bi-speedometer2',     'label' => 'Dashboard',             'href' => APP_URL . '/admin/dashboard.php'],
    'contacts'   => ['icon' => 'bi-inbox-fill',        'label' => 'Contact Submissions',   'href' => APP_URL . '/admin/contacts.php'],
    'articles'   => ['icon' => 'bi-newspaper',         'label' => 'All Articles',           'href' => APP_URL . '/admin/articles.php'],
    'users'      => ['icon' => 'bi-people-fill',       'label' => 'Manage Users',           'href' => APP_URL . '/admin/users.php'],
];
?>
<aside class="panel-sidebar" id="adminSidebar" role="navigation" aria-label="Admin navigation">

    <!-- Brand -->
    <div class="sidebar-header">
        <a href="<?= APP_URL ?>/" class="sidebar-brand">
            <div class="logo-box sidebar-logo-box">JS<br>PS</div>
            <div>
                <div class="sidebar-brand-name">JSPS Accounting</div>
                <div class="sidebar-brand-sub">Admin Panel</div>
            </div>
        </a>
    </div>

    <!-- User card -->
    <div class="sidebar-user">
        <div class="sidebar-avatar"><?= e($initials) ?></div>
        <div class="sidebar-user-info">
            <div class="sidebar-user-name"><?= e($user['first_name'] . ' ' . $user['last_name']) ?></div>
            <span class="role-badge">ADMIN</span>
        </div>
    </div>

    <!-- Nav -->
    <nav class="sidebar-nav">
        <?php foreach ($navItems as $key => $item): ?>
            <a href="<?= $item['href'] ?>"
               class="sidebar-nav-link<?= $activeAdminPage === $key ? ' active' : '' ?>">
                <i class="bi <?= $item['icon'] ?>"></i>
                <span><?= $item['label'] ?></span>
            </a>
        <?php endforeach; ?>
    </nav>

    <!-- Logout -->
    <div class="sidebar-footer">
        <a href="<?= APP_URL ?>/logout.php" class="sidebar-nav-link logout-link">
            <i class="bi bi-box-arrow-left"></i>
            <span>Logout</span>
        </a>
    </div>

</aside>

<!-- Mobile overlay -->
<div class="sidebar-overlay" id="sidebarOverlay" onclick="closeMobileSidebar()"></div>
