<?php
/**
 * JSPS Accounting Solutions Pvt. Ltd.
 * Application Configuration
 *
 * APP_URL is auto-detected — no manual editing needed for localhost.
 * Works with any folder name: jsps, jsps2, jsps3, etc.
 */

define('APP_NAME',    'JSPS Accounting Solutions Pvt. Ltd.');
define('APP_TAGLINE', 'Grow Together');
define('APP_VERSION', '1.0.0');

// ── Auto-detect base URL ───────────────────────────────────
(function () {
    $protocol  = (!empty($_SERVER['HTTPS']) && $_SERVER['HTTPS'] !== 'off') ? 'https' : 'http';
    $host      = $_SERVER['HTTP_HOST'] ?? 'localhost';
    $docRoot   = rtrim(str_replace('\\', '/', $_SERVER['DOCUMENT_ROOT'] ?? ''), '/');
    $scriptDir = rtrim(str_replace('\\', '/', dirname(dirname(__FILE__))), '/');
    $subFolder = str_replace($docRoot, '', $scriptDir);
    define('APP_URL', $protocol . '://' . $host . $subFolder);
})();

// ── Database ──────────────────────────────────────────────
define('DB_HOST',    'localhost');
define('DB_NAME',    'jsps_db');
define('DB_USER',    'root');
define('DB_PASS',    '');
define('DB_CHARSET', 'utf8mb4');

// ── Session ───────────────────────────────────────────────
define('SESSION_NAME',     'jsps_session');
define('SESSION_LIFETIME',  86400);

// ── Paths ─────────────────────────────────────────────────
define('ROOT_PATH',   dirname(__DIR__));
define('ASSETS_URL',  APP_URL . '/assets');
define('UPLOAD_PATH', ROOT_PATH . DIRECTORY_SEPARATOR . 'assets' . DIRECTORY_SEPARATOR . 'uploads');
define('UPLOAD_URL',  ASSETS_URL . '/uploads');

// ── Pagination ────────────────────────────────────────────
define('POSTS_PER_PAGE', 9);

// ── Email ─────────────────────────────────────────────────
define('MAIL_FROM',      'noreply@jspsaccounting.com');
define('MAIL_FROM_NAME',  APP_NAME);

// ── Environment ───────────────────────────────────────────
define('ENV', 'development');

if (ENV === 'development') {
    error_reporting(E_ALL);
    ini_set('display_errors', 1);
} else {
    error_reporting(0);
    ini_set('display_errors', 0);
}
