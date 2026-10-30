<?php
/**
 * Ubah buku - Update (FR-D04) dengan data awal terisi dari database.
 */

require_once __DIR__ . '/config/database.php';
require_once __DIR__ . '/includes/functions.php';
require_once __DIR__ . '/includes/auth.php';

require_login();

require_once __DIR__ . '/includes/lang.php';

$id = (int) ($_GET['id'] ?? 0);

$stmt = db()->prepare('SELECT * FROM books WHERE id = :id LIMIT 1');
$stmt->execute([':id' => $id]);
$book = $stmt->fetch();

// FR-D kondisi khusus: id tidak ditemukan -> beri pesan lalu kembali ke daftar.
if (!$book) {
    set_flash('error', 'msg_not_found');
    redirect('index.php');
}

$pageTitle = 'edit_book';

$errors = [];
$old    = $book;

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    require_csrf();

    [$errors, $old] = validate_book($_POST, $id);

    if (empty($errors)) {
        $update = db()->prepare(
            'UPDATE books
                SET title = :title, author = :author, publisher = :publisher,
                    year = :year, isbn = :isbn, category = :category, stock = :stock
              WHERE id = :id'
        );
        $update->execute([
            ':title'     => $old['title'],
            ':author'    => $old['author'],
            ':publisher' => $old['publisher'],
            ':year'      => $old['year'],
            ':isbn'      => $old['isbn'],
            ':category'  => $old['category'],
            ':stock'     => $old['stock'],
            ':id'        => $id,
        ]);

        set_flash('success', 'msg_updated');
        redirect('index.php');
    }
}

$action    = 'edit.php?id=' . $id;
$submitKey = 'btn_update';

require __DIR__ . '/includes/header.php';
?>
<div class="page-head">
    <h1><?= e(t('edit_book')) ?></h1>
</div>
<?php require __DIR__ . '/includes/book_form.php'; ?>
<?php require __DIR__ . '/includes/footer.php'; ?>
