<?php
/**
 * pages/privacy.php — Privacy Policy
 */

require_once __DIR__ . '/../bootstrap.php';

$pageTitle   = 'Privacy Policy | ' . APP_NAME;
$activePage  = '';
$breadcrumbs = [
    ['label' => 'Home',           'href' => APP_URL . '/'],
    ['label' => 'Privacy Policy', 'href' => null],
];

include __DIR__ . '/../includes/head.php';
include __DIR__ . '/../includes/navbar.php';
include __DIR__ . '/../includes/breadcrumb.php';
?>

<section class="section-pad">
    <div class="container-xl" style="max-width:860px">
        <h1 style="font-family:'Playfair Display',serif;font-size:2.2rem;color:var(--primary);margin-bottom:8px;">Privacy Policy</h1>
        <p style="color:var(--text-muted);font-size:14px;margin-bottom:40px;">Last updated: <?= date('d F Y') ?></p>

        <div style="font-size:16px;color:#374151;line-height:1.85;">
            <h2 style="font-family:'Playfair Display',serif;font-size:1.4rem;color:var(--primary);margin:28px 0 12px;">Information We Collect</h2>
            <p>JSPS Accounting Solutions Pvt. Ltd. collects information you voluntarily provide through our contact forms, including your name, email address, phone number, and the nature of your enquiry. We do not collect any financial data through this website.</p>

            <h2 style="font-family:'Playfair Display',serif;font-size:1.4rem;color:var(--primary);margin:28px 0 12px;">How We Use Your Information</h2>
            <p>Information collected is used solely to respond to your enquiries, provide the services requested, and send relevant communications about our services. We do not sell, rent, or share your personal information with third parties for marketing purposes.</p>

            <h2 style="font-family:'Playfair Display',serif;font-size:1.4rem;color:var(--primary);margin:28px 0 12px;">Data Security</h2>
            <p>We implement industry-standard security measures to protect your personal data. All data is stored on secure servers with restricted access. Our website uses HTTPS encryption to protect data in transit.</p>

            <h2 style="font-family:'Playfair Display',serif;font-size:1.4rem;color:var(--primary);margin:28px 0 12px;">Contact Us</h2>
            <p>For any privacy-related queries, please contact us at <a href="mailto:info@jspsaccounting.com" style="color:var(--primary)">info@jspsaccounting.com</a> or call <a href="tel:+919876543210" style="color:var(--primary)">+91 98765 43210</a>.</p>
        </div>
    </div>
</section>

<?php include __DIR__ . '/../includes/footer.php'; ?>
