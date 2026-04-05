<?php
/**
 * index.php — Home Page
 */

require_once __DIR__ . '/bootstrap.php';

$pageTitle  = 'Home | ' . APP_NAME;
$activePage = 'home';

// Fetch latest 3 articles for preview
$db       = Database::getInstance();
$articles = $db->fetchAll(
    "SELECT a.*, u.first_name, u.last_name
     FROM articles a
     JOIN users u ON a.user_id = u.id
     WHERE a.status = 'published'
     ORDER BY a.created_at DESC
     LIMIT 3"
);

// Services data (static — can be moved to DB later)
$services = require __DIR__ . '/config/services.php';

include __DIR__ . '/includes/head.php';
include __DIR__ . '/includes/navbar.php';
?>

<!-- ══ HERO ══════════════════════════════════════════════ -->
<section class="hero" aria-label="Hero">
    <div class="hero-content container-xl">
        <div class="row align-items-center">
            <div class="col-lg-7">
                <div class="hero-badge">
                    <i class="bi bi-award-fill"></i>
                    Trusted CA Firm Since 2005
                </div>
                <h1 class="hero-heading">
                    Professional <span class="hero-accent">Accounting</span><br>
                    &amp; Tax Solutions
                </h1>
                <p class="hero-subtext">
                    JSPS Accounting Solutions delivers end-to-end financial, taxation, and
                    compliance services to businesses of all sizes. We simplify complex
                    regulations so you can grow.
                </p>
                <div class="hero-actions">
                    <a href="<?= APP_URL ?>/pages/contact.php" class="btn-primary-cta">
                        Get Free Consultation
                    </a>
                    <a href="<?= APP_URL ?>/pages/services.php" class="btn-outline-cta">
                        Our Services
                    </a>
                </div>
                <div class="hero-stats">
                    <div class="hero-stat"><span class="stat-num">2000+</span><span class="stat-lbl">Happy Clients</span></div>
                    <div class="hero-stat"><span class="stat-num">18+</span><span class="stat-lbl">Years Experience</span></div>
                    <div class="hero-stat"><span class="stat-num">14</span><span class="stat-lbl">Service Areas</span></div>
                </div>
            </div>
            <div class="col-lg-5 d-none d-lg-flex justify-content-end">
                <div class="hero-float-cards">
                    <div class="float-card">
                        <div class="float-icon"><i class="bi bi-shield-check"></i></div>
                        <h4>GST Compliance</h4>
                        <p>End-to-end GST filing &amp; advisory</p>
                    </div>
                    <div class="float-card">
                        <div class="float-icon"><i class="bi bi-building"></i></div>
                        <h4>Company Registration</h4>
                        <p>Pvt Ltd, LLP, OPC &amp; more</p>
                    </div>
                    <div class="float-card">
                        <div class="float-icon"><i class="bi bi-graph-up-arrow"></i></div>
                        <h4>Tax Planning</h4>
                        <p>Strategic income tax solutions</p>
                    </div>
                </div>
            </div>
        </div>
    </div>
</section>

<!-- ══ WHY CHOOSE US ════════════════════════════════════ -->
<section class="section-pad" aria-labelledby="whyHeading">
    <div class="container-xl">
        <div class="section-header">
            <span class="section-tag">Why Choose Us</span>
            <h2 id="whyHeading">Your Trusted Financial Partners</h2>
            <p>We bring expertise, integrity, and dedication to every client relationship — from startups to established enterprises.</p>
        </div>
        <div class="why-grid">
            <?php
            $whyItems = [
                ['icon'=>'bi-people-fill',     'title'=>'Expert Team',           'desc'=>'Qualified Chartered Accountants and tax professionals with decades of combined experience.'],
                ['icon'=>'bi-clock-history',   'title'=>'Timely Compliance',     'desc'=>'All filings and returns submitted on time — no penalties, no notices.'],
                ['icon'=>'bi-headset',         'title'=>'Dedicated Support',     'desc'=>'Round-the-clock support with a dedicated relationship manager for every client.'],
                ['icon'=>'bi-lock-fill',       'title'=>'100% Confidential',     'desc'=>'Strict data privacy protocols. Your financial information is always safe with us.'],
                ['icon'=>'bi-currency-rupee',  'title'=>'Transparent Pricing',   'desc'=>'Clear, upfront pricing with no hidden charges. Quality CA services at affordable rates.'],
                ['icon'=>'bi-buildings-fill',  'title'=>'All Business Types',    'desc'=>'Serving sole proprietors, partnerships, LLPs, private limited companies, and corporates.'],
            ];
            foreach ($whyItems as $item): ?>
                <div class="why-card">
                    <div class="why-icon"><i class="bi <?= $item['icon'] ?>"></i></div>
                    <h3><?= e($item['title']) ?></h3>
                    <p><?= e($item['desc']) ?></p>
                </div>
            <?php endforeach; ?>
        </div>
    </div>
</section>

<!-- ══ SERVICES OVERVIEW ════════════════════════════════ -->
<section class="section-pad section-alt" aria-labelledby="servicesHeading">
    <div class="container-xl">
        <div class="section-header">
            <span class="section-tag">Our Services</span>
            <h2 id="servicesHeading">Comprehensive CA Services</h2>
            <p>From registration to compliance — everything your business needs under one roof.</p>
        </div>
        <div class="services-grid">
            <?php foreach ($services as $srv): ?>
                <a href="<?= APP_URL ?>/pages/services.php" class="service-card">
                    <div class="srv-icon" aria-hidden="true"><?= $srv['emoji'] ?></div>
                    <h4><?= e($srv['title']) ?></h4>
                </a>
            <?php endforeach; ?>
        </div>
        <div class="text-center mt-5">
            <a href="<?= APP_URL ?>/pages/services.php" class="btn-primary-cta">
                View All Services <i class="bi bi-arrow-right ms-1"></i>
            </a>
        </div>
    </div>
</section>

<!-- ══ TESTIMONIALS ══════════════════════════════════════ -->
<section class="section-pad" aria-labelledby="testiHeading">
    <div class="container-xl">
        <div class="section-header">
            <span class="section-tag">Testimonials</span>
            <h2 id="testiHeading">What Our Clients Say</h2>
        </div>
        <div class="testi-grid">
            <?php
            $testimonials = [
                ['initials'=>'RK','name'=>'Rahul Kapoor',  'role'=>'Director, TechVentures Pvt. Ltd.',  'stars'=>5, 'text'=>'"JSPS handled our company registration and GST setup flawlessly. Their team is incredibly knowledgeable and responsive. Highly recommended for any startup!"'],
                ['initials'=>'PM','name'=>'Priya Mehta',   'role'=>'Small Business Owner',              'stars'=>5, 'text'=>'"The income tax filing process was seamless. They identified deductions I wasn\'t aware of and saved me a significant amount. Professional and trustworthy."'],
                ['initials'=>'AS','name'=>'Arjun Shah',    'role'=>'MD, Shah Exports',                  'stars'=>5, 'text'=>'"We\'ve been with JSPS for 5 years for annual audits and compliance. Their attention to detail and proactive communication is what sets them apart."'],
            ];
            foreach ($testimonials as $t): ?>
                <div class="testi-card" itemscope itemtype="https://schema.org/Review">
                    <div class="testi-stars" aria-label="<?= $t['stars'] ?> out of 5 stars">
                        <?= str_repeat('★', $t['stars']) ?>
                    </div>
                    <p class="testi-text" itemprop="reviewBody"><?= e($t['text']) ?></p>
                    <div class="testi-author">
                        <div class="testi-avatar" aria-hidden="true"><?= $t['initials'] ?></div>
                        <div class="testi-info">
                            <h4 itemprop="author"><?= e($t['name']) ?></h4>
                            <p><?= e($t['role']) ?></p>
                        </div>
                    </div>
                </div>
            <?php endforeach; ?>
        </div>
    </div>
</section>

<!-- ══ BLOG PREVIEW ══════════════════════════════════════ -->
<?php if (!empty($articles)): ?>
<section class="section-pad section-alt" aria-labelledby="blogHeading">
    <div class="container-xl">
        <div class="section-header">
            <span class="section-tag">Latest Insights</span>
            <h2 id="blogHeading">From Our Blog</h2>
            <p>Stay updated with the latest in taxation, compliance, and financial planning.</p>
        </div>
        <div class="blog-grid">
            <?php foreach ($articles as $article): ?>
                <?php include __DIR__ . '/includes/blog_card.php'; ?>
            <?php endforeach; ?>
        </div>
        <div class="text-center mt-5">
            <a href="<?= APP_URL ?>/pages/blog.php" class="btn-primary-cta">
                View All Articles <i class="bi bi-arrow-right ms-1"></i>
            </a>
        </div>
    </div>
</section>
<?php endif; ?>

<!-- ══ CTA STRIP ════════════════════════════════════════ -->
<section class="cta-strip" aria-label="Call to action">
    <div class="container-xl text-center">
        <h2>Ready to Get Started?</h2>
        <p>Book a free 30-minute consultation with our CA experts today.</p>
        <a href="<?= APP_URL ?>/pages/contact.php" class="btn-primary-cta">
            Schedule Consultation <i class="bi bi-calendar-check ms-1"></i>
        </a>
    </div>
</section>

<?php include __DIR__ . '/includes/footer.php'; ?>
