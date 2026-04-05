<?php
/**
 * setup.php — One-time database installer
 *
 * USAGE:  http://localhost/jsps2/setup.php
 *
 * ⚠  DELETE THIS FILE after the setup completes successfully.
 */

// ── Standalone config (no bootstrap, no DB connection yet) ─
define('APP_NAME',   'JSPS Accounting Solutions Pvt. Ltd.');
define('DB_HOST',    'localhost');
define('DB_NAME',    'jsps_db');
define('DB_USER',    'root');
define('DB_PASS',    '');          // ← change if your MySQL has a password
define('DB_CHARSET', 'utf8mb4');

// Auto-detect URL — works with any folder name on localhost or production
(function () {
    $protocol  = (!empty($_SERVER['HTTPS']) && $_SERVER['HTTPS'] !== 'off') ? 'https' : 'http';
    $host      = $_SERVER['HTTP_HOST'] ?? 'localhost';
    $docRoot   = rtrim(str_replace('\\', '/', $_SERVER['DOCUMENT_ROOT'] ?? ''), '/');
    $scriptDir = rtrim(str_replace('\\', '/', dirname(__FILE__)), '/');
    $subFolder = str_replace($docRoot, '', $scriptDir);
    define('APP_URL', $protocol . '://' . $host . $subFolder);
})();

$steps  = [];
$errors = [];

function step(string $msg): void  { global $steps;  $steps[]  = $msg; }
function fail(string $msg): void  { global $errors; $errors[] = $msg; }

// ── Connect without selecting a database first ────────────
try {
    $pdo = new PDO(
        'mysql:host=' . DB_HOST . ';charset=' . DB_CHARSET,
        DB_USER, DB_PASS,
        [PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION]
    );
    step('Connected to MySQL server.');
} catch (PDOException $e) {
    fail('Cannot connect to MySQL: ' . $e->getMessage());
    $pdo = null;
}

if ($pdo) {

    // 1. Create database
    $pdo->exec("CREATE DATABASE IF NOT EXISTS `" . DB_NAME . "` CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci");
    $pdo->exec("USE `" . DB_NAME . "`");
    step('Database `' . DB_NAME . '` ready.');

    // 2. Drop existing tables (clean slate)
    $pdo->exec("SET FOREIGN_KEY_CHECKS = 0");
    foreach (['contact_submissions', 'articles', 'users'] as $tbl) {
        $pdo->exec("DROP TABLE IF EXISTS `$tbl`");
    }
    $pdo->exec("SET FOREIGN_KEY_CHECKS = 1");
    step('Old tables dropped.');

    // 3. Create users table
    $pdo->exec("
        CREATE TABLE users (
            id            INT UNSIGNED     NOT NULL AUTO_INCREMENT,
            first_name    VARCHAR(80)      NOT NULL,
            last_name     VARCHAR(80)      NOT NULL,
            email         VARCHAR(180)     NOT NULL UNIQUE,
            password_hash VARCHAR(255)     NOT NULL,
            role          ENUM('admin','user') NOT NULL DEFAULT 'user',
            is_active     TINYINT(1)       NOT NULL DEFAULT 1,
            created_at    DATETIME         NOT NULL DEFAULT CURRENT_TIMESTAMP,
            updated_at    DATETIME         NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
            PRIMARY KEY (id),
            INDEX idx_email (email),
            INDEX idx_role  (role)
        ) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4
    ");
    step('Table `users` created.');

    // 4. Create articles table
    $pdo->exec("
        CREATE TABLE articles (
            id            INT UNSIGNED     NOT NULL AUTO_INCREMENT,
            user_id       INT UNSIGNED     NOT NULL,
            title         VARCHAR(255)     NOT NULL,
            slug          VARCHAR(280)     NOT NULL UNIQUE,
            category      VARCHAR(100)     NOT NULL,
            excerpt       TEXT             NOT NULL,
            content       LONGTEXT         NOT NULL,
            tags          VARCHAR(500)     DEFAULT NULL,
            emoji         VARCHAR(10)      DEFAULT '📰',
            status        ENUM('draft','published') NOT NULL DEFAULT 'published',
            created_at    DATETIME         NOT NULL DEFAULT CURRENT_TIMESTAMP,
            updated_at    DATETIME         NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
            PRIMARY KEY (id),
            INDEX idx_user     (user_id),
            INDEX idx_status   (status),
            INDEX idx_category (category),
            FULLTEXT INDEX ft_search (title, content),
            CONSTRAINT fk_article_user FOREIGN KEY (user_id)
                REFERENCES users (id) ON DELETE CASCADE
        ) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4
    ");
    step('Table `articles` created.');

    // 5. Create contact_submissions table
    $pdo->exec("
        CREATE TABLE contact_submissions (
            id         INT UNSIGNED  NOT NULL AUTO_INCREMENT,
            user_id    INT UNSIGNED  DEFAULT NULL,
            full_name  VARCHAR(160)  NOT NULL,
            email      VARCHAR(180)  NOT NULL,
            phone      VARCHAR(20)   DEFAULT NULL,
            service    VARCHAR(120)  DEFAULT NULL,
            message    TEXT          NOT NULL,
            is_read    TINYINT(1)    NOT NULL DEFAULT 0,
            created_at DATETIME      NOT NULL DEFAULT CURRENT_TIMESTAMP,
            PRIMARY KEY (id),
            INDEX idx_user    (user_id),
            INDEX idx_is_read (is_read),
            CONSTRAINT fk_contact_user FOREIGN KEY (user_id)
                REFERENCES users (id) ON DELETE SET NULL
        ) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4
    ");
    step('Table `contact_submissions` created.');

    // 6. Seed admin user
    $adminHash = password_hash('Admin@123', PASSWORD_BCRYPT, ['cost' => 12]);
    $stmt = $pdo->prepare(
        "INSERT INTO users (first_name, last_name, email, password_hash, role)
         VALUES (?, ?, ?, ?, 'admin')"
    );
    $stmt->execute(['Admin', 'JSPS', 'admin@jspsaccounting.com', $adminHash]);
    $adminId = $pdo->lastInsertId();
    step('Admin user created — admin@jspsaccounting.com / Admin@123');

    // 7. Seed demo user
    $userHash = password_hash('User@123', PASSWORD_BCRYPT, ['cost' => 12]);
    $stmt->execute(['Raj', 'Kumar', 'raj@example.com', $userHash]);
    $userId = $pdo->lastInsertId();
    step('Demo user created — raj@example.com / User@123');

    // 8. Seed sample articles
    $articles = [
        [
            'title'    => 'Understanding GST Annual Return: A Complete Guide',
            'slug'     => 'understanding-gst-annual-return-complete-guide',
            'category' => 'GST & Indirect Tax',
            'emoji'    => '📊',
            'excerpt'  => 'GSTR-9 filing can be complex for businesses. Here is everything you need to know about deadlines, documents, and common mistakes.',
            'content'  => '<h2>What is GSTR-9?</h2><p>GSTR-9 is an annual return to be filed by all regular registered taxpayers under GST. It consolidates all monthly/quarterly returns filed during the financial year.</p><blockquote><strong>Key Deadline:</strong> GSTR-9 must be filed by 31st December following the end of the financial year.</blockquote><h2>Who Needs to File?</h2><p>All regular taxpayers with annual aggregate turnover exceeding ₹2 crores must file GSTR-9.</p><h2>Documents Required</h2><ul><li>All filed GSTR-1 and GSTR-3B returns</li><li>Purchase registers with HSN-wise details</li><li>Input Tax Credit (ITC) records</li></ul>',
            'tags'     => 'GST,compliance,annual return,GSTR-9',
        ],
        [
            'title'    => 'New Tax Regime vs Old Tax Regime: FY 2024-25',
            'slug'     => 'new-tax-regime-vs-old-tax-regime-fy-2024-25',
            'category' => 'Income Tax',
            'emoji'    => '💰',
            'excerpt'  => 'Budget 2024 brought significant changes to the new tax regime. We compare both regimes to help you decide which suits your situation best.',
            'content'  => '<h2>Overview</h2><p>The new regime offers lower slab rates with minimal deductions. The old regime allows HRA, 80C, 80D, and home loan interest exemptions.</p><blockquote>Standard deduction of ₹75,000 is now available under the new regime.</blockquote><h2>When Old Regime is Better</h2><ul><li>Total deductions above ₹3.75 lakh → Old Regime</li><li>HRA claim possible → Old Regime advantage</li><li>Limited investments → New Regime likely better</li></ul>',
            'tags'     => 'income tax,tax planning,budget 2024,ITR',
        ],
        [
            'title'    => 'MSME Registration: Benefits, Process & Documents',
            'slug'     => 'msme-udyam-registration-benefits-process-documents',
            'category' => 'MSME & Startups',
            'emoji'    => '🏢',
            'excerpt'  => 'Udyam Registration opens doors to collateral-free loans, government subsidies, and priority lending. Here is your complete step-by-step guide.',
            'content'  => '<h2>What is MSME Registration?</h2><p>Udyam Registration gives businesses access to collateral-free loans, subsidies, and priority sector status from the government.</p><h2>Key Benefits</h2><ul><li>Collateral-free bank loans under CGTMSE</li><li>Protection against delayed payments (MSMED Act)</li><li>Preference in government tender procurement</li><li>Electricity bill concessions in many states</li></ul><blockquote>Udyam Registration is completely FREE and paperless — done via Aadhaar-based self-declaration.</blockquote>',
            'tags'     => 'MSME,Udyam,registration,startup',
        ],
    ];

    $artStmt = $pdo->prepare(
        "INSERT INTO articles (user_id, title, slug, category, emoji, excerpt, content, tags, status)
         VALUES (?, ?, ?, ?, ?, ?, ?, ?, 'published')"
    );
    foreach ($articles as $a) {
        $artStmt->execute([$adminId, $a['title'], $a['slug'], $a['category'], $a['emoji'], $a['excerpt'], $a['content'], $a['tags']]);
    }
    step('3 sample articles inserted.');

    // 9. Seed sample contact submission
    $pdo->prepare(
        "INSERT INTO contact_submissions (full_name, email, phone, service, message)
         VALUES (?, ?, ?, ?, ?)"
    )->execute(['Vijay Tiwari', 'vijay@example.com', '9876512345', 'GST Registration & Compliance', 'We need help with GST registration for our new retail business.']);
    step('Sample contact submission inserted.');
}

?>
<!DOCTYPE html>
<html lang="en">
<head>
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1.0">
<title>JSPS — Database Setup</title>
<style>
  * { box-sizing: border-box; margin: 0; padding: 0; }
  body { font-family: -apple-system, 'Segoe UI', sans-serif; background: #F4F7FB; display: flex; justify-content: center; padding: 40px 16px; min-height: 100vh; }
  .card { background: #fff; border: 1px solid #DDE4EE; border-radius: 16px; padding: 40px; max-width: 640px; width: 100%; box-shadow: 0 4px 24px rgba(11,61,107,0.08); }
  .logo { width: 56px; height: 56px; background: #0B3D6B; border-radius: 10px; display: flex; align-items: center; justify-content: center; font-size: 16px; font-weight: 700; color: #fff; line-height: 1.2; text-align: center; margin-bottom: 20px; }
  h1 { font-size: 1.6rem; font-weight: 700; color: #0B3D6B; margin-bottom: 6px; }
  .subtitle { font-size: 14px; color: #5C6B7A; margin-bottom: 28px; }
  .step { display: flex; align-items: flex-start; gap: 10px; padding: 10px 14px; background: #EAF7EE; border-radius: 8px; margin-bottom: 8px; font-size: 14px; color: #1A6B38; border: 1px solid rgba(43,168,74,0.2); }
  .step::before { content: '✔'; font-weight: 700; flex-shrink: 0; }
  .err  { display: flex; align-items: flex-start; gap: 10px; padding: 10px 14px; background: #FEE2E2; border-radius: 8px; margin-bottom: 8px; font-size: 14px; color: #991B1B; border: 1px solid rgba(239,68,68,0.25); }
  .err::before { content: '✖'; font-weight: 700; flex-shrink: 0; }
  .creds { background: #F4F7FB; border: 1px solid #DDE4EE; border-radius: 10px; padding: 20px; margin: 24px 0; }
  .creds h3 { font-size: 14px; font-weight: 700; color: #0B3D6B; margin-bottom: 14px; text-transform: uppercase; letter-spacing: 0.5px; }
  .cred-row { display: flex; justify-content: space-between; font-size: 14px; padding: 6px 0; border-bottom: 1px solid #EEF2F8; }
  .cred-row:last-child { border-bottom: none; }
  .cred-label { color: #5C6B7A; font-weight: 500; }
  .cred-value { font-weight: 600; color: #0D1B2A; font-family: monospace; }
  .warning { background: #FEF9EC; border: 1px solid rgba(201,152,42,0.3); border-radius: 10px; padding: 14px 18px; font-size: 13px; color: #7A5A0A; margin: 20px 0; line-height: 1.6; }
  .btn { display: inline-block; background: #0B3D6B; color: #fff; padding: 12px 28px; border-radius: 8px; font-size: 15px; font-weight: 600; text-decoration: none; margin-top: 8px; transition: background 0.2s; }
  .btn:hover { background: #1A5C9B; }
  .btn-outline { background: transparent; color: #0B3D6B; border: 1.5px solid #DDE4EE; margin-left: 10px; }
  .btn-outline:hover { background: #F4F7FB; }
  hr { border: none; border-top: 1px solid #EEF2F8; margin: 24px 0; }
</style>
</head>
<body>
<div class="card">
  <div class="logo">JS<br>PS</div>
  <h1>Database Setup</h1>
  <p class="subtitle">JSPS Accounting Solutions Pvt. Ltd. — One-time installer</p>

  <?php foreach ($steps as $s): ?>
    <div class="step"><?= htmlspecialchars($s) ?></div>
  <?php endforeach; ?>
  <?php foreach ($errors as $e): ?>
    <div class="err"><?= htmlspecialchars($e) ?></div>
  <?php endforeach; ?>

  <?php if (empty($errors)): ?>
    <div class="creds">
      <h3>Login Credentials</h3>
      <div class="cred-row"><span class="cred-label">Admin Email</span><span class="cred-value">admin@jspsaccounting.com</span></div>
      <div class="cred-row"><span class="cred-label">Admin Password</span><span class="cred-value">Admin@123</span></div>
      <hr style="margin:10px 0">
      <div class="cred-row"><span class="cred-label">User Email</span><span class="cred-value">raj@example.com</span></div>
      <div class="cred-row"><span class="cred-label">User Password</span><span class="cred-value">User@123</span></div>
    </div>

    <div class="warning">
      ⚠ <strong>Security:</strong> Delete or rename <code>setup.php</code> immediately after setup.
      This file gives anyone full database access if left accessible.
    </div>

    <a href="<?= APP_URL ?>/" class="btn">Open Website →</a>
    <a href="<?= APP_URL ?>/login.php" class="btn btn-outline">Login</a>

  <?php else: ?>
    <div class="warning">
      ⚠ Setup did not complete. Check the errors above. Common fixes:<br><br>
      • Open <code>setup.php</code> and set the correct <code>DB_PASS</code><br>
      • Make sure MySQL is running in XAMPP Control Panel<br>
      • Ensure the MySQL user has CREATE DATABASE privilege
    </div>
  <?php endif; ?>
</div>
</body>
</html>
