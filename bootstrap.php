<?php
/**
 * Bootstrap — loaded at the top of every entry-point file.
 * Handles: config, autoloading, session, helpers.
 */

require_once __DIR__ . DIRECTORY_SEPARATOR . 'config'   . DIRECTORY_SEPARATOR . 'config.php';
require_once __DIR__ . DIRECTORY_SEPARATOR . 'database' . DIRECTORY_SEPARATOR . 'Database.php';

// ── Session ───────────────────────────────────────────────
session_name(SESSION_NAME);
session_set_cookie_params([
    'lifetime' => SESSION_LIFETIME,
    'path'     => '/',
    'secure'   => (APP_URL !== 'http://localhost' && strpos(APP_URL, 'https') === 0),
    'httponly' => true,
    'samesite' => 'Lax',
]);
session_start();

// ── Helpers ───────────────────────────────────────────────

function e(string $value): string
{
    return htmlspecialchars($value, ENT_QUOTES | ENT_SUBSTITUTE, 'UTF-8');
}

function isLoggedIn(): bool
{
    return isset($_SESSION['user_id']);
}

function isAdmin(): bool
{
    return isLoggedIn() && ($_SESSION['user_role'] ?? '') === 'admin';
}

function currentUser(): array
{
    return [
        'id'         => $_SESSION['user_id']    ?? null,
        'first_name' => $_SESSION['user_fname'] ?? '',
        'last_name'  => $_SESSION['user_lname'] ?? '',
        'email'      => $_SESSION['user_email'] ?? '',
        'role'       => $_SESSION['user_role']  ?? '',
    ];
}

function requireLogin(string $redirectTo = '/login.php'): void
{
    if (!isLoggedIn()) {
        $_SESSION['redirect_after_login'] = $_SERVER['REQUEST_URI'];
        header('Location: ' . APP_URL . $redirectTo);
        exit;
    }
}

function requireAdmin(): void
{
    requireLogin();
    if (!isAdmin()) {
        header('Location: ' . APP_URL . '/user/dashboard.php');
        exit;
    }
}

function redirect(string $path): void
{
    header('Location: ' . APP_URL . $path);
    exit;
}

function setFlash(string $type, string $message): void
{
    $_SESSION['flash'] = ['type' => $type, 'message' => $message];
}

function getFlash(): ?array
{
    if (isset($_SESSION['flash'])) {
        $flash = $_SESSION['flash'];
        unset($_SESSION['flash']);
        return $flash;
    }
    return null;
}

function slugify(string $text): string
{
    $text = strtolower(trim($text));
    $text = preg_replace('/[^a-z0-9\s-]/', '', $text);
    $text = preg_replace('/[\s-]+/', '-', $text);
    return trim($text, '-');
}

function paginate(int $total, int $perPage, int $current): array
{
    $totalPages = (int) ceil($total / $perPage);
    return [
        'total'       => $total,
        'per_page'    => $perPage,
        'current'     => $current,
        'total_pages' => $totalPages,
        'offset'      => ($current - 1) * $perPage,
        'has_prev'    => $current > 1,
        'has_next'    => $current < $totalPages,
    ];
}

function sanitize(string $input): string
{
    return trim(strip_tags($input));
}

function timeAgo(string $datetime): string
{
    $diff = time() - strtotime($datetime);
    if ($diff < 60)       return 'just now';
    if ($diff < 3600)     return floor($diff / 60) . 'm ago';
    if ($diff < 86400)    return floor($diff / 3600) . 'h ago';
    if ($diff < 2592000)  return floor($diff / 86400) . 'd ago';
    return date('d M Y', strtotime($datetime));
}
