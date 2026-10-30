<?php
/**
 * Bootstrap session + proteksi halaman.
 * Wajib di-include paling atas pada setiap halaman sebelum ada output.
 */

require_once __DIR__ . '/../config/database.php';
require_once __DIR__ . '/functions.php';

// Cookie session: HttpOnly + SameSite=Lax (FR non-fungsional keamanan).
if (session_status() !== PHP_SESSION_ACTIVE) {
    session_set_cookie_params([
        'lifetime' => 0,
        'path'     => '/',
        'httponly' => true,
        'samesite' => 'Lax',
    ]);
    session_start();
}

// FR-S08: session timeout otomatis setelah 30 menit tidak aktif.
const SESSION_IDLE_TIMEOUT = 1800;

if (isset($_SESSION['user_id'], $_SESSION['last_activity'])
    && (time() - (int) $_SESSION['last_activity']) > SESSION_IDLE_TIMEOUT) {
    $_SESSION = [];
    session_destroy();
    session_start();
    session_regenerate_id(true);
    set_flash('warning', 'session_timeout');
}

// Perbarui penanda aktivitas terakhir.
$_SESSION['last_activity'] = time();

if (!function_exists('is_logged_in')) {
    function is_logged_in(): bool
    {
        return isset($_SESSION['user_id']);
    }
}

if (!function_exists('require_login')) {
    /**
     * FR-S04: halaman CRUD hanya untuk pengguna yang sudah login.
     */
    function require_login(): void
    {
        if (!is_logged_in()) {
            set_flash('warning', 'msg_login_required');
            redirect('login.php');
        }
    }
}

if (!function_exists('require_guest')) {
    /**
     * FR-S06: pengguna yang sudah login tidak perlu membuka halaman login.
     */
    function require_guest(): void
    {
        if (is_logged_in()) {
            redirect('index.php');
        }
    }
}
