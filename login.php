<?php
/**
 * Halaman login (FR-S01, FR-S02, FR-S03, FR-S06, FR-S07).
 */

require_once __DIR__ . '/config/database.php';
require_once __DIR__ . '/includes/functions.php';
require_once __DIR__ . '/includes/auth.php';
require_once __DIR__ . '/includes/lang.php';

$pageTitle = 'login_title';
require_guest();

$errors   = [];
$username = '';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    require_csrf();

    $username = trim((string) ($_POST['username'] ?? ''));
    $password = (string) ($_POST['password'] ?? '');

    if ($username === '' || $password === '') {
        $errors['general'] = 'err_login_empty';
    } else {
        $stmt = db()->prepare('SELECT id, username, password FROM users WHERE username = :username LIMIT 1');
        $stmt->execute([':username' => $username]);
        $user = $stmt->fetch();

        if ($user && password_verify($password, $user['password'])) {
            // FR-S03: regenerasi id session untuk mencegah session fixation.
            session_regenerate_id(true);
            $_SESSION['user_id']       = (int) $user['id'];
            $_SESSION['username']      = $user['username'];
            $_SESSION['last_activity'] = time();

            set_flash('success', 'msg_login_success');
            redirect('index.php');
        }

        // FR-S07: pesan umum, tanpa membocorkan field mana yang salah.
        $errors['general'] = 'err_login_invalid';
    }
}

require __DIR__ . '/includes/header.php';
?>
<div class="auth-wrap">
    <div class="card auth-card">
        <h1><?= e(t('login_title')) ?></h1>
        <p class="auth-subtitle"><?= e(t('login_subtitle')) ?></p>

        <?php if (isset($errors['general'])): ?>
            <div class="flash flash-error"><?= e(tr_error($errors['general'])) ?></div>
        <?php endif; ?>

        <form method="post" action="login.php">
            <?= csrf_field() ?>

            <div class="form-row">
                <label for="username"><?= e(t('username')) ?></label>
                <input type="text" id="username" name="username" class="input"
                       value="<?= e($username) ?>" autofocus>
            </div>

            <div class="form-row">
                <label for="password"><?= e(t('password')) ?></label>
                <input type="password" id="password" name="password" class="input">
            </div>

            <div class="form-actions">
                <button type="submit" class="btn btn-primary btn-block"><?= e(t('btn_login')) ?></button>
            </div>
        </form>
    </div>
</div>
<?php require __DIR__ . '/includes/footer.php'; ?>
