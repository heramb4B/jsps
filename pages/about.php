<?php
/**
 * pages/about.php — About Us Page
 */

require_once __DIR__ . '/../bootstrap.php';

$pageTitle       = 'About Us | ' . APP_NAME;
$metaDescription = 'Learn about JSPS Accounting Solutions — our story, mission, values, and the expert team behind our services.';
$activePage      = 'about';
$breadcrumbs     = [
    ['label' => 'Home',     'href' => APP_URL . '/'],
    ['label' => 'About Us', 'href' => null],
];

include __DIR__ . '/../includes/head.php';
include __DIR__ . '/../includes/navbar.php';
include __DIR__ . '/../includes/breadcrumb.php';
?>

<!-- ══ ABOUT HERO ════════════════════════════════════════ -->
<section class="about-hero">
    <div class="container-xl">
        <div class="about-hero-grid">
            <div>
                <span class="about-badge">Est. 2005 · Mumbai</span>
                <h1>Grow Together<br>With JSPS</h1>
                <p>JSPS Accounting Solutions Pvt. Ltd. is a full-service Chartered Accountant firm headquartered in Mumbai. We have been empowering businesses with trusted financial guidance, tax compliance, and regulatory support for over 18 years.</p>
                <p>Our team of qualified CAs, tax consultants, and legal experts work as an extension of your business — ensuring you remain compliant, profitable, and future-ready.</p>
                <a href="<?= APP_URL ?>/pages/contact.php" class="btn-primary-cta mt-3 d-inline-block">
                    Talk to Our Experts
                </a>
            </div>
            <div class="about-visual">
                <div class="av-item">
                    <div class="av-num">2000+</div>
                    <div class="av-text"><h4>Clients Served</h4><p>Across diverse industries</p></div>
                </div>
                <div class="av-item">
                    <div class="av-num">18+</div>
                    <div class="av-text"><h4>Years of Excellence</h4><p>Trusted expertise since 2005</p></div>
                </div>
                <div class="av-item">
                    <div class="av-num">12</div>
                    <div class="av-text"><h4>Expert Professionals</h4><p>CAs, CS, and legal advisors</p></div>
                </div>
                <div class="av-item">
                    <div class="av-num">98%</div>
                    <div class="av-text"><h4>Client Retention Rate</h4><p>Long-term trusted relationships</p></div>
                </div>
            </div>
        </div>
    </div>
</section>

<!-- ══ MISSION / VISION / VALUES ══════════════════════════ -->
<section class="section-pad" aria-labelledby="mvvHeading">
    <div class="container-xl">
        <div class="section-header">
            <span class="section-tag">Our Foundation</span>
            <h2 id="mvvHeading">What Drives Us Every Day</h2>
        </div>
        <div class="why-grid">
            <div class="why-card">
                <div class="why-icon"><i class="bi bi-eye-fill"></i></div>
                <h3>Our Vision</h3>
                <p>To be the most trusted CA firm for businesses across India — bridging the gap between complex regulations and business growth with clarity and integrity.</p>
            </div>
            <div class="why-card">
                <div class="why-icon"><i class="bi bi-bullseye"></i></div>
                <h3>Our Mission</h3>
                <p>To deliver world-class accounting, tax, and compliance services with a client-first approach, ensuring every business thrives in a compliant, financially healthy environment.</p>
            </div>
            <div class="why-card">
                <div class="why-icon"><i class="bi bi-gem"></i></div>
                <h3>Our Values</h3>
                <p>Integrity, transparency, and accuracy form the foundation of everything we do. We build relationships on trust and deliver results with excellence.</p>
            </div>
        </div>
    </div>
</section>

<!-- ══ TEAM ═══════════════════════════════════════════════ -->
<section class="section-pad section-alt" aria-labelledby="teamHeading">
    <div class="container-xl">
        <div class="section-header">
            <span class="section-tag">Our Team</span>
            <h2 id="teamHeading">Meet the Experts Behind JSPS</h2>
            <p>A team of qualified professionals committed to your financial success.</p>
        </div>
        <div class="team-grid">
            <?php
            $team = [
                ['initials'=>'JS', 'name'=>'CA Jayesh Shah',   'role'=>'Founder & Managing Partner',     'qual'=>'B.Com, FCA · 22 years experience'],
                ['initials'=>'PS', 'name'=>'CA Priya Sharma',  'role'=>'Head – Tax & Compliance',         'qual'=>'B.Com, ACA, DISA · 14 years'],
                ['initials'=>'RM', 'name'=>'CS Rohan Mehta',   'role'=>'Company Secretary',               'qual'=>'LLB, ACS · 10 years experience'],
                ['initials'=>'NP', 'name'=>'CA Neha Patel',    'role'=>'GST & Indirect Tax Specialist',   'qual'=>'B.Com, ACA · 8 years experience'],
            ];
            foreach ($team as $member): ?>
                <div class="team-card">
                    <div class="team-photo">
                        <div class="team-initials"><?= e($member['initials']) ?></div>
                    </div>
                    <div class="team-info">
                        <h3><?= e($member['name']) ?></h3>
                        <p class="team-role"><?= e($member['role']) ?></p>
                        <span class="team-qual"><?= e($member['qual']) ?></span>
                    </div>
                </div>
            <?php endforeach; ?>
        </div>
    </div>
</section>

<!-- ══ CTA ════════════════════════════════════════════════ -->
<section class="cta-strip">
    <div class="container-xl text-center">
        <h2>Work with Our Expert Team</h2>
        <p>Schedule a free consultation and discover how JSPS can help your business grow.</p>
        <a href="<?= APP_URL ?>/pages/contact.php" class="btn-primary-cta">
            Get In Touch <i class="bi bi-arrow-right ms-1"></i>
        </a>
    </div>
</section>

<?php include __DIR__ . '/../includes/footer.php'; ?>
