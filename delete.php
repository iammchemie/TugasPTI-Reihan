<?php
/**
 * Hapus buku - Delete (FR-D05, FR-D07).
 * Hanya menerima metode POST, dilindungi token CSRF.
 */

require_once __DIR__ . '/config/database.php';
require_once __DIR__ . '/includes/functions.php';
require_once __DIR__ . '/includes/auth.php';

require_login();

// FR-D07: penghapusan lewat GET ditolak (mencegah penghapusan tidak sengaja).
if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    set_flash('error', 'msg_delete_post_error');
    redirect('index.php');
}

require_csrf();

$id = (int) ($_POST['id'] ?? 0);

$stmt = db()->prepare('DELETE FROM books WHERE id = :id');
$stmt->execute([':id' => $id]);

if ($stmt->rowCount() > 0) {
    set_flash('success', 'msg_deleted');
} else {
    set_flash('error', 'msg_not_found');
}

redirect('index.php');
