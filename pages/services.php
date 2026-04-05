<?php
/**
 * pages/services.php — Services Page
 */

require_once __DIR__ . '/../bootstrap.php';

$pageTitle       = 'Our Services | ' . APP_NAME;
$metaDescription = 'Explore the full range of CA services offered by JSPS Accounting Solutions — GST, Income Tax, Company Registration, TDS, MSME, and more.';
$activePage      = 'services';
$breadcrumbs     = [
    ['label' => 'Home',     'href' => APP_URL . '/'],
    ['label' => 'Services', 'href' => null],
];

$services = require __DIR__ . '/../config/services.php';

include __DIR__ . '/../includes/head.php';
include __DIR__ . '/../includes/navbar.php';
include __DIR__ . '/../includes/breadcrumb.php';
?>

<!-- ══ PAGE HERO ════════════════════════════════════════ -->
<section class="page-hero">
    <div class="container-xl text-center">
        <span class="section-tag">What We Offer</span>
        <h1>Our Services</h1>
        <p>Comprehensive CA, legal, and financial services tailored for every business need</p>
    </div>
</section>

<!-- ══ SERVICES GRID ════════════════════════════════════ -->
<section class="section-pad" aria-labelledby="servicesHeading">
    <div class="container-xl">
        <div class="row g-4" id="servicesHeading">
            <?php foreach ($services as $srv): ?>
                <div class="col-sm-6 col-md-4 col-lg-3">
                    <div class="service-detail-card">
                        <div class="sdc-icon" aria-hidden="true"><?= $srv['emoji'] ?></div>
                        <h3><?= e($srv['title']) ?></h3>
                        <p><?= e($srv['desc']) ?></p>
                        <a href="<?= APP_URL ?>/pages/contact.php" class="learn-more">
                            Get Started <i class="bi bi-arrow-right"></i>
                        </a>
                    </div>
                </div>
            <?php endforeach; ?>
        </div>
    </div>
</section>

<!-- ══ PROCESS ══════════════════════════════════════════ -->
<section class="section-pad section-alt" aria-labelledby="processHeading">
    <div class="container-xl">
        <div class="section-header">
            <span class="section-tag">How It Works</span>
            <h2 id="processHeading">Our Simple 4-Step Process</h2>
        </div>
        <div class="process-grid">
            <?php
            $steps = [
                ['num'=>'01', 'icon'=>'bi-chat-dots-fill',   'title'=>'Consultation',       'desc'=>'Free initial consultation to understand your requirements and recommend the right services.'],
                ['num'=>'02', 'icon'=>'bi-file-earmark-text-fill', 'title'=>'Documentation', 'desc'=>'We guide you through collecting the necessary documents and information.'],
                ['num'=>'03', 'icon'=>'bi-gear-fill',        'title'=>'Processing',          'desc'=>'Our experts handle all filings, registrations, and compliance work on your behalf.'],
                ['num'=>'04', 'icon'=>'bi-check-circle-fill','title'=>'Delivery',            'desc'=>'Timely delivery with complete transparency and post-service support.'],
            ];
            foreach ($steps as $step): ?>
                <div class="process-step">
                    <div class="process-num"><?= $step['num'] ?></div>
                    <div class="process-icon"><i class="bi <?= $step['icon'] ?>"></i></div>
                    <h4><?= e($step['title']) ?></h4>
                    <p><?= e($step['desc']) ?></p>
                </div>
            <?php endforeach; ?>
        </div>
    </div>
</section>

<!-- ══ CTA ════════════════════════════════════════════════ -->
<section class="cta-strip">
    <div class="container-xl text-center">
        <h2>Not Sure Which Service You Need?</h2>
        <p>Our experts will assess your requirements and recommend the best solution.</p>
        <a href="<?= APP_URL ?>/pages/contact.php" class="btn-primary-cta">Get Free Advice</a>
    </div>
</section>

<?php include __DIR__ . '/../includes/footer.php'; ?>
