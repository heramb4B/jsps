<?php
/**
 * Database Seeder
 * Run once: php database/seed.php
 */

require_once __DIR__ . '/../config/config.php';
require_once __DIR__ . '/Database.php';

$db = Database::getInstance();

echo "Seeding database...\n";

// ── Admin user ────────────────────────────────────────────
$adminHash = password_hash('Admin@123', PASSWORD_BCRYPT, ['cost' => 12]);
$db->execute(
    "UPDATE users SET password_hash = ? WHERE email = ?",
    [$adminHash, 'admin@jspsaccounting.com']
);
echo "✔ Admin password set.\n";

// ── Sample regular user ───────────────────────────────────
$userHash = password_hash('User@123', PASSWORD_BCRYPT, ['cost' => 12]);
$db->insert(
    "INSERT IGNORE INTO users (first_name, last_name, email, password_hash, role)
     VALUES (?, ?, ?, ?, 'user')",
    ['Raj', 'Kumar', 'raj@example.com', $userHash]
);
echo "✔ Sample user inserted.\n";

// ── Sample articles ───────────────────────────────────────
$adminId = $db->fetchOne("SELECT id FROM users WHERE email = ?", ['admin@jspsaccounting.com'])['id'];

$articles = [
    [
        'title'    => 'Understanding GST Annual Return: A Complete Guide',
        'slug'     => 'understanding-gst-annual-return-complete-guide',
        'category' => 'GST & Indirect Tax',
        'emoji'    => '📊',
        'excerpt'  => 'GSTR-9 filing can be complex for businesses. Here is everything you need to know about deadlines, documents, and common mistakes.',
        'content'  => '<h2>What is GSTR-9?</h2><p>GSTR-9 is an annual return to be filed by all regular registered taxpayers under GST. It consolidates all monthly/quarterly returns filed during the financial year.</p><blockquote><strong>Key Deadline:</strong> GSTR-9 must be filed by 31st December following the end of the financial year.</blockquote><h2>Who Needs to File?</h2><p>All regular taxpayers with annual aggregate turnover exceeding ₹2 crores must file GSTR-9. Composition dealers, ISDs, and non-resident taxable persons are exempt.</p><h2>Documents Required</h2><ul><li>All filed GSTR-1 and GSTR-3B returns</li><li>Purchase registers with HSN-wise details</li><li>Input Tax Credit (ITC) records</li></ul><h2>Common Mistakes to Avoid</h2><p>Errors in reconciling ITC between GSTR-2A/2B and books of accounts are the most common pitfall. Always reconcile before filing.</p>',
        'tags'     => 'GST,compliance,annual return,GSTR-9',
    ],
    [
        'title'    => 'New Tax Regime vs Old Tax Regime: FY 2024-25',
        'slug'     => 'new-tax-regime-vs-old-tax-regime-fy-2024-25',
        'category' => 'Income Tax',
        'emoji'    => '💰',
        'excerpt'  => 'Budget 2024 brought significant changes to the new tax regime. We compare both regimes to help you decide which suits you best.',
        'content'  => '<h2>Overview of Both Regimes</h2><p>The new regime offers lower slab rates with minimal deductions. The old regime allows HRA, 80C, 80D, and home loan interest exemptions.</p><h2>New Tax Regime Slabs (FY 2024-25)</h2><p>Standard deduction of ₹75,000 is now available under the new regime — an increase from ₹50,000.</p><h2>When Old Regime is Better</h2><p>If your total eligible deductions exceed ₹3.75 lakh, the old regime likely results in lower tax outgo.</p><ul><li>HRA claim possible → Old Regime advantage</li><li>Home loan interest ₹2L → Old Regime advantage</li><li>Limited investments → New Regime likely better</li></ul>',
        'tags'     => 'income tax,tax planning,budget 2024,ITR',
    ],
    [
        'title'    => 'MSME Registration: Benefits, Process & Documents',
        'slug'     => 'msme-udyam-registration-benefits-process-documents',
        'category' => 'MSME & Startups',
        'emoji'    => '🏢',
        'excerpt'  => 'Udyam Registration opens doors to collateral-free loans, government subsidies, and priority lending. Here is your complete guide.',
        'content'  => '<h2>What is MSME Registration?</h2><p>Udyam Registration is a government certification that gives businesses access to collateral-free loans, subsidies, and priority sector status.</p><h2>Key Benefits</h2><ul><li>Collateral-free bank loans under CGTMSE</li><li>Protection against delayed payments (MSMED Act)</li><li>Preference in government tender procurement</li><li>Electricity bill concessions in many states</li></ul><blockquote>Udyam Registration is completely FREE and paperless — done via Aadhaar-based self-declaration.</blockquote><h2>How to Apply</h2><p>Visit udyamregistration.gov.in, enter your Aadhaar number, verify via OTP, fill in business details, and submit. Certificate is issued instantly.</p>',
        'tags'     => 'MSME,Udyam,registration,startup,government schemes',
    ],
];

foreach ($articles as $art) {
    $db->insert(
        "INSERT IGNORE INTO articles (user_id, title, slug, category, emoji, excerpt, content, tags)
         VALUES (?, ?, ?, ?, ?, ?, ?, ?)",
        [$adminId, $art['title'], $art['slug'], $art['category'], $art['emoji'], $art['excerpt'], $art['content'], $art['tags']]
    );
}
echo "✔ Sample articles inserted.\n";

// ── Sample contact submissions ────────────────────────────
$db->insert(
    "INSERT IGNORE INTO contact_submissions (full_name, email, phone, service, message)
     VALUES (?, ?, ?, ?, ?)",
    ['Vijay Tiwari', 'vijay@example.com', '9876512345', 'GST Registration & Compliance', 'We need help with GST registration for our new retail business.']
);
echo "✔ Sample contact submission inserted.\n";

echo "\nSeeding complete!\n";
