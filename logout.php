<?php
/**
 * Logout (FR-S05): hapus session lalu arahkan ke halaman login.
 */

require_once __DIR__ . '/includes/auth.php';

$_SESSION = [];
session_unset();
session_destroy();

// Buka session baru hanya untuk menampilkan notifikasi keluar.
session_start();
session_regenerate_id(true);
set_flash('success', 'msg_logged_out');

redirect('login.php');
