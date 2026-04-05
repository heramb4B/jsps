<?php
/**
 * pages/contact.php — Contact Us Page
 */

require_once __DIR__ . '/../bootstrap.php';

$pageTitle       = 'Contact Us | ' . APP_NAME;
$metaDescription = 'Get in touch with JSPS Accounting Solutions. Book a free consultation or send us an enquiry about our CA services.';
$activePage      = 'contact';
$breadcrumbs     = [
    ['label' => 'Home',       'href' => APP_URL . '/'],
    ['label' => 'Contact Us', 'href' => null],
];

$errors  = [];
$success = false;

// ── Handle POST ────────────────────────────────────────────
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $name    = sanitize($_POST['full_name']  ?? '');
    $email   = sanitize($_POST['email']      ?? '');
    $phone   = sanitize($_POST['phone']      ?? '');
    $service = sanitize($_POST['service']    ?? '');
    $message = sanitize($_POST['message']    ?? '');

    // CSRF check (simple token)
    if (!isset($_POST['csrf_token']) || $_POST['csrf_token'] !== ($_SESSION['csrf_token'] ?? '')) {
        $errors[] = 'Invalid form submission. Please refresh and try again.';
    }

    // Validation
    if (empty($name))    $errors[] = 'Full name is required.';
    if (empty($email))   $errors[] = 'Email address is required.';
    elseif (!filter_var($email, FILTER_VALIDATE_EMAIL)) $errors[] = 'Please enter a valid email address.';
    if (empty($message)) $errors[] = 'Message is required.';
    if (strlen($message) < 10) $errors[] = 'Message must be at least 10 characters.';

    if (empty($errors)) {
        $db     = Database::getInstance();
        $userId = isLoggedIn() ? currentUser()['id'] : null;

        $db->insert(
            "INSERT INTO contact_submissions (user_id, full_name, email, phone, service, message)
             VALUES (?, ?, ?, ?, ?, ?)",
            [$userId, $name, $email, $phone ?: null, $service ?: null, $message]
        );

        $success = true;

        // Regenerate CSRF
        $_SESSION['csrf_token'] = bin2hex(random_bytes(32));
    }
}

// Generate CSRF token
if (empty($_SESSION['csrf_token'])) {
    $_SESSION['csrf_token'] = bin2hex(random_bytes(32));
}

$servicesList = [
    'PAN / TAN Card', 'Shop Act Registration', 'Trademark Registration',
    'Income Tax Return & Compliance', 'GST Registration & Compliance',
    'TDS Return & Compliance', 'RERA Registration', 'Food Licence (FSSAI)',
    'Partnership Deed', 'MSME Registration', 'Company Registration',
    'Accounting Services', 'Project Report', 'Digital Signature (DSC)', 'Other',
];

include __DIR__ . '/../includes/head.php';
include __DIR__ . '/../includes/navbar.php';
include __DIR__ . '/../includes/breadcrumb.php';
?>

<section class="section-pad">
    <div class="container-xl">
        <div class="contact-wrap">

            <!-- Contact Info -->
            <div class="contact-info">
                <span class="section-tag">Get In Touch</span>
                <h2>We'd Love to Help<br>Your Business Grow</h2>
                <p>Whether you have a query about our services, need a compliance review, or want to start a new engagement — our team is ready to help.</p>

                <?php
                $details = [
                    ['icon'=>'bi-geo-alt-fill',   'title'=>'Office Address',  'content'=>'JSPS Accounting Solutions Pvt. Ltd.<br>302, Shree Complex, Linking Road,<br>Borivali West, Mumbai – 400092'],
                    ['icon'=>'bi-telephone-fill', 'title'=>'Phone',           'content'=>'<a href="tel:+919876543210">+91 98765 43210</a><br><a href="tel:+912228901234">+91 22 2890 1234</a>'],
                    ['icon'=>'bi-envelope-fill',  'title'=>'Email',           'content'=>'<a href="mailto:info@jspsaccounting.com">info@jspsaccounting.com</a>'],
                    ['icon'=>'bi-clock-fill',     'title'=>'Working Hours',   'content'=>'Mon – Sat: 9:30 AM – 7:00 PM<br>Sunday: Closed'],
                ];
                foreach ($details as $d): ?>
                    <div class="contact-detail">
                        <div class="cd-icon"><i class="bi <?= $d['icon'] ?>"></i></div>
                        <div class="cd-text">
                            <h4><?= $d['title'] ?></h4>
                            <p><?= $d['content'] ?></p>
                        </div>
                    </div>
                <?php endforeach; ?>
            </div>

            <!-- Contact Form -->
            <div class="contact-form-wrap">
                <div class="contact-form">
                    <h3>Send Us a Message</h3>

                    <?php if ($success): ?>
                        <div class="alert alert-success">
                            <i class="bi bi-check-circle-fill me-2"></i>
                            Your message has been sent! We'll get back to you within 24 hours.
                        </div>
                    <?php endif; ?>

                    <?php if (!empty($errors)): ?>
                        <div class="alert alert-danger">
                            <ul class="mb-0">
                                <?php foreach ($errors as $err): ?>
                                    <li><?= e($err) ?></li>
                                <?php endforeach; ?>
                            </ul>
                        </div>
                    <?php endif; ?>

                    <form method="POST" action="" novalidate>
                        <input type="hidden" name="csrf_token" value="<?= e($_SESSION['csrf_token']) ?>">

                        <div class="form-row">
                            <div class="form-group">
                                <label for="full_name">Full Name <span class="required">*</span></label>
                                <input type="text" id="full_name" name="full_name"
                                       value="<?= e($_POST['full_name'] ?? '') ?>"
                                       placeholder="Your full name" required autocomplete="name">
                            </div>
                            <div class="form-group">
                                <label for="email">Email Address <span class="required">*</span></label>
                                <input type="email" id="email" name="email"
                                       value="<?= e($_POST['email'] ?? '') ?>"
                                       placeholder="your@email.com" required autocomplete="email">
                            </div>
                        </div>

                        <div class="form-row">
                            <div class="form-group">
                                <label for="phone">Phone Number</label>
                                <input type="tel" id="phone" name="phone"
                                       value="<?= e($_POST['phone'] ?? '') ?>"
                                       placeholder="+91 XXXXX XXXXX" autocomplete="tel">
                            </div>
                            <div class="form-group">
                                <label for="service">Service Required</label>
                                <select id="service" name="service">
                                    <option value="">Select a service...</option>
                                    <?php foreach ($servicesList as $svc): ?>
                                        <option value="<?= e($svc) ?>"
                                            <?= (($_POST['service'] ?? '') === $svc) ? 'selected' : '' ?>>
                                            <?= e($svc) ?>
                                        </option>
                                    <?php endforeach; ?>
                                </select>
                            </div>
                        </div>

                        <div class="form-group">
                            <label for="message">Message <span class="required">*</span></label>
                            <textarea id="message" name="message" rows="5"
                                      placeholder="Tell us about your requirement..." required><?= e($_POST['message'] ?? '') ?></textarea>
                        </div>

                        <button type="submit" class="btn-submit">
                            Send Message <i class="bi bi-send-fill ms-2"></i>
                        </button>
                    </form>
                </div>
            </div>

        </div>
    </div>
</section>

<?php include __DIR__ . '/../includes/footer.php'; ?>
