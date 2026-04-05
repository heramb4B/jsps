<?php
/**
 * pages/terms.php — Terms of Service
 */

require_once __DIR__ . '/../bootstrap.php';

$pageTitle   = 'Terms of Service | ' . APP_NAME;
$activePage  = '';
$breadcrumbs = [
    ['label' => 'Home',             'href' => APP_URL . '/'],
    ['label' => 'Terms of Service', 'href' => null],
];

include __DIR__ . '/../includes/head.php';
include __DIR__ . '/../includes/navbar.php';
include __DIR__ . '/../includes/breadcrumb.php';
?>

<section class="section-pad">
    <div class="container-xl" style="max-width:860px">
        <h1 style="font-family:'Playfair Display',serif;font-size:2.2rem;color:var(--primary);margin-bottom:8px;">Terms of Service</h1>
        <p style="color:var(--text-muted);font-size:14px;margin-bottom:40px;">Last updated: <?= date('d F Y') ?></p>

        <div style="font-size:16px;color:#374151;line-height:1.85;">
            <h2 style="font-family:'Playfair Display',serif;font-size:1.4rem;color:var(--primary);margin:28px 0 12px;">Acceptance of Terms</h2>
            <p>By accessing or using the JSPS Accounting Solutions website, you agree to be bound by these Terms of Service. If you disagree with any part of these terms, you may not access the website.</p>

            <h2 style="font-family:'Playfair Display',serif;font-size:1.4rem;color:var(--primary);margin:28px 0 12px;">Services</h2>
            <p>JSPS Accounting Solutions provides chartered accountancy, taxation, and compliance advisory services. Content on this website is for general informational purposes only and does not constitute professional legal or financial advice.</p>

            <h2 style="font-family:'Playfair Display',serif;font-size:1.4rem;color:var(--primary);margin:28px 0 12px;">User Accounts</h2>
            <p>When you create an account, you are responsible for maintaining the security of your credentials and for all activities that occur under your account. You must notify us immediately of any unauthorised use of your account.</p>

            <h2 style="font-family:'Playfair Display',serif;font-size:1.4rem;color:var(--primary);margin:28px 0 12px;">Intellectual Property</h2>
            <p>All content on this website, including text, graphics, logos, and software, is the property of JSPS Accounting Solutions Pvt. Ltd. and is protected by applicable intellectual property laws.</p>

            <h2 style="font-family:'Playfair Display',serif;font-size:1.4rem;color:var(--primary);margin:28px 0 12px;">Contact</h2>
            <p>For any questions about these terms, contact us at <a href="mailto:info@jspsaccounting.com" style="color:var(--primary)">info@jspsaccounting.com</a>.</p>
        </div>
    </div>
</section>

<?php include __DIR__ . '/../includes/footer.php'; ?>
