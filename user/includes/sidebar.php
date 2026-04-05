<?php
/**
 * user/includes/sidebar.php
 * User panel left navigation.
 */

$activeUserPage = $activeUserPage ?? '';
$user           = currentUser();
$initials       = strtoupper(substr($user['first_name'],0,1) . substr($user['last_name'],0,1));

$navItems = [
    'dashboard' => ['icon' => 'bi-speedometer2', 'label' => 'Dashboard',          'href' => APP_URL . '/user/dashboard.php'],
    'contacts'  => ['icon' => 'bi-inbox-fill',   'label' => 'My Submissions',      'href' => APP_URL . '/user/contacts.php'],
    'articles'  => ['icon' => 'bi-newspaper',    'label' => 'My Articles',          'href' => APP_URL . '/user/articles.php'],
];
?>
<aside class="panel-sidebar" id="userSidebar" role="navigation" aria-label="User navigation">

    <div class="sidebar-header">
        <a href="<?= APP_URL ?>/" class="sidebar-brand">
            <div class="logo-box sidebar-logo-box">JS<br>PS</div>
            <div>
                <div class="sidebar-brand-name">JSPS Accounting</div>
                <div class="sidebar-brand-sub">User Panel</div>
            </div>
        </a>
    </div>

    <div class="sidebar-user">
        <div class="sidebar-avatar"><?= e($initials) ?></div>
        <div class="sidebar-user-info">
            <div class="sidebar-user-name"><?= e($user['first_name'] . ' ' . $user['last_name']) ?></div>
            <span class="role-badge role-user">USER</span>
        </div>
    </div>

    <nav class="sidebar-nav">
        <?php foreach ($navItems as $key => $item): ?>
            <a href="<?= $item['href'] ?>"
               class="sidebar-nav-link<?= $activeUserPage === $key ? ' active' : '' ?>">
                <i class="bi <?= $item['icon'] ?>"></i>
                <span><?= $item['label'] ?></span>
            </a>
        <?php endforeach; ?>
    </nav>

    <div class="sidebar-footer">
        <a href="<?= APP_URL ?>/logout.php" class="sidebar-nav-link logout-link">
            <i class="bi bi-box-arrow-left"></i>
            <span>Logout</span>
        </a>
    </div>

</aside>
<div class="sidebar-overlay" id="sidebarOverlay" onclick="closeMobileSidebar()"></div>
