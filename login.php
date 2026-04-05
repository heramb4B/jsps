<?php
/**
 * login.php — Login & Register Page
 */

require_once __DIR__ . '/bootstrap.php';

// Already logged in → redirect to dashboard
if (isLoggedIn()) {
    redirect(isAdmin() ? '/admin/dashboard.php' : '/user/dashboard.php');
}

$pageTitle = 'Login | ' . APP_NAME;
$tab       = $_GET['tab'] ?? 'login';   // 'login' | 'register'
$errors    = [];
$success   = '';

// Generate CSRF
if (empty($_SESSION['csrf_token'])) {
    $_SESSION['csrf_token'] = bin2hex(random_bytes(32));
}

// ── Handle Login ───────────────────────────────────────────
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['action'])) {

    if ($_POST['csrf_token'] !== $_SESSION['csrf_token']) {
        $errors[] = 'Invalid request. Please refresh and try again.';
    } else {

        if ($_POST['action'] === 'login') {
            $email = sanitize($_POST['email']    ?? '');
            $pass  =          $_POST['password'] ?? '';

            if (empty($email))  $errors[] = 'Email is required.';
            if (empty($pass))   $errors[] = 'Password is required.';

            if (empty($errors)) {
                $db   = Database::getInstance();
                $user = $db->fetchOne(
                    "SELECT * FROM users WHERE email = ? AND is_active = 1",
                    [$email]
                );

                if ($user && password_verify($pass, $user['password_hash'])) {
                    // Regenerate session ID to prevent fixation
                    session_regenerate_id(true);
                    $_SESSION['user_id']    = $user['id'];
                    $_SESSION['user_fname'] = $user['first_name'];
                    $_SESSION['user_lname'] = $user['last_name'];
                    $_SESSION['user_email'] = $user['email'];
                    $_SESSION['user_role']  = $user['role'];

                    $redirect = $_SESSION['redirect_after_login'] ?? null;
                    unset($_SESSION['redirect_after_login']);

                    if ($redirect) {
                        header('Location: ' . $redirect); exit;
                    }
                    redirect($user['role'] === 'admin' ? '/admin/dashboard.php' : '/user/dashboard.php');
                } else {
                    $errors[] = 'Invalid email address or password.';
                }
            }

        } elseif ($_POST['action'] === 'register') {
            $tab    = 'register';
            $fname  = sanitize($_POST['first_name'] ?? '');
            $lname  = sanitize($_POST['last_name']  ?? '');
            $email  = sanitize($_POST['email']       ?? '');
            $pass   =          $_POST['password']    ?? '';
            $pass2  =          $_POST['confirm_password'] ?? '';

            if (empty($fname))  $errors[] = 'First name is required.';
            if (empty($lname))  $errors[] = 'Last name is required.';
            if (empty($email))  $errors[] = 'Email is required.';
            elseif (!filter_var($email, FILTER_VALIDATE_EMAIL)) $errors[] = 'Invalid email address.';
            if (strlen($pass) < 8)  $errors[] = 'Password must be at least 8 characters.';
            if ($pass !== $pass2)   $errors[] = 'Passwords do not match.';

            if (empty($errors)) {
                $db = Database::getInstance();
                if ($db->fetchOne("SELECT id FROM users WHERE email = ?", [$email])) {
                    $errors[] = 'This email address is already registered.';
                } else {
                    $hash = password_hash($pass, PASSWORD_BCRYPT, ['cost' => 12]);
                    $id   = $db->insert(
                        "INSERT INTO users (first_name, last_name, email, password_hash, role) VALUES (?, ?, ?, ?, 'user')",
                        [$fname, $lname, $email, $hash]
                    );
                    session_regenerate_id(true);
                    $_SESSION['user_id']    = $id;
                    $_SESSION['user_fname'] = $fname;
                    $_SESSION['user_lname'] = $lname;
                    $_SESSION['user_email'] = $email;
                    $_SESSION['user_role']  = 'user';
                    setFlash('success', 'Account created! Welcome, ' . $fname . '.');
                    redirect('/user/dashboard.php');
                }
            }
        }
    }
    // Regenerate CSRF after each attempt
    $_SESSION['csrf_token'] = bin2hex(random_bytes(32));
}

include __DIR__ . '/includes/head.php';
include __DIR__ . '/includes/navbar.php';
?>

<section class="auth-section">
    <div class="auth-container">
        <div class="auth-brand">
            <div class="logo-box auth-logo">JS<br>PS</div>
            <h2><?= APP_NAME ?></h2>
            <p><?= APP_TAGLINE ?></p>
        </div>

        <!-- Tabs -->
        <div class="auth-tabs">
            <button class="auth-tab <?= $tab === 'login'    ? 'active' : '' ?>" data-tab="login">Login</button>
            <button class="auth-tab <?= $tab === 'register' ? 'active' : '' ?>" data-tab="register">Register</button>
        </div>

        <?php if (!empty($errors)): ?>
            <div class="alert alert-danger">
                <?php foreach ($errors as $e): ?><div><?= e($e) ?></div><?php endforeach; ?>
            </div>
        <?php endif; ?>

        <!-- LOGIN FORM -->
        <div class="auth-form-wrap" id="formLogin" style="<?= $tab !== 'login' ? 'display:none' : '' ?>">
            <form method="POST" action="" novalidate>
                <input type="hidden" name="action" value="login">
                <input type="hidden" name="csrf_token" value="<?= e($_SESSION['csrf_token']) ?>">
                <div class="form-group">
                    <label for="loginEmail">Email Address</label>
                    <input type="email" id="loginEmail" name="email"
                           value="<?= e($_POST['email'] ?? '') ?>"
                           placeholder="your@email.com" required autocomplete="email">
                </div>
                <div class="form-group">
                    <label for="loginPass">Password</label>
                    <input type="password" id="loginPass" name="password"
                           placeholder="••••••••" required autocomplete="current-password">
                </div>
                <button type="submit" class="btn-submit">Login to Account</button>
            </form>
        </div>

        <!-- REGISTER FORM -->
        <div class="auth-form-wrap" id="formRegister" style="<?= $tab !== 'register' ? 'display:none' : '' ?>">
            <form method="POST" action="?tab=register" novalidate>
                <input type="hidden" name="action" value="register">
                <input type="hidden" name="csrf_token" value="<?= e($_SESSION['csrf_token']) ?>">
                <div class="form-row">
                    <div class="form-group">
                        <label for="regFname">First Name</label>
                        <input type="text" id="regFname" name="first_name"
                               value="<?= e($_POST['first_name'] ?? '') ?>"
                               placeholder="First name" required autocomplete="given-name">
                    </div>
                    <div class="form-group">
                        <label for="regLname">Last Name</label>
                        <input type="text" id="regLname" name="last_name"
                               value="<?= e($_POST['last_name'] ?? '') ?>"
                               placeholder="Last name" required autocomplete="family-name">
                    </div>
                </div>
                <div class="form-group">
                    <label for="regEmail">Email Address</label>
                    <input type="email" id="regEmail" name="email"
                           value="<?= e($_POST['email'] ?? '') ?>"
                           placeholder="your@email.com" required autocomplete="email">
                </div>
                <div class="form-group">
                    <label for="regPass">Password <span class="hint">(min. 8 characters)</span></label>
                    <input type="password" id="regPass" name="password"
                           placeholder="Min. 8 characters" required autocomplete="new-password">
                </div>
                <div class="form-group">
                    <label for="regPass2">Confirm Password</label>
                    <input type="password" id="regPass2" name="confirm_password"
                           placeholder="Repeat password" required autocomplete="new-password">
                </div>
                <button type="submit" class="btn-submit">Create Account</button>
            </form>
        </div>

        <p class="auth-back">
            <a href="<?= APP_URL ?>/">← Back to Home</a>
        </p>
    </div>
</section>

<?php include __DIR__ . '/includes/footer.php'; ?>
