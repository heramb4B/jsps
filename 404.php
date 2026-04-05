<?php
/**
 * 404.php — Custom Error Page
 */

require_once __DIR__ . '/bootstrap.php';

http_response_code(404);

$pageTitle       = '404 – Page Not Found | ' . APP_NAME;
$metaDescription = 'The page you are looking for could not be found.';
$activePage      = '';

include __DIR__ . '/includes/head.php';
include __DIR__ . '/includes/navbar.php';
?>

<section class="section-pad" style="min-height: 60vh; display:flex; align-items:center;">
    <div class="container-xl text-center">
        <div style="font-size:5rem; font-family:'Playfair Display',serif; font-weight:700; color:var(--border); line-height:1;">
            404
        </div>
        <h1 style="font-family:'Playfair Display',serif; font-size:2rem; color:var(--primary); margin:20px 0 14px;">
            Page Not Found
        </h1>
        <p style="font-size:16px; color:var(--text-muted); max-width:460px; margin:0 auto 32px; line-height:1.75;">
            The page you are looking for may have been moved, deleted, or does not exist.
        </p>
        <div style="display:flex; gap:12px; justify-content:center; flex-wrap:wrap;">
            <a href="<?= APP_URL ?>/" class="btn-primary-cta">
                <i class="bi bi-house-fill me-1"></i> Back to Home
            </a>
            <a href="<?= APP_URL ?>/pages/contact.php" class="btn-outline-cta">
                Contact Us
            </a>
        </div>
    </div>
</section>

<?php include __DIR__ . '/includes/footer.php'; ?>
