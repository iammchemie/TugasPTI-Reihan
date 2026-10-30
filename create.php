<?php
/**
 * Tambah buku - Create (FR-D02) dengan validasi & sanitasi.
 */

require_once __DIR__ . '/config/database.php';
require_once __DIR__ . '/includes/functions.php';
require_once __DIR__ . '/includes/auth.php';

require_login();

require_once __DIR__ . '/includes/lang.php';

$pageTitle = 'add_book';

$errors = [];
$old    = [
    'title'     => '',
    'author'    => '',
    'publisher' => '',
    'year'      => '',
    'isbn'      => '',
    'category'  => '',
    'stock'     => '',
];

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    require_csrf();

    [$errors, $old] = validate_book($_POST);

    if (empty($errors)) {
        $stmt = db()->prepare(
            'INSERT INTO books (title, author, publisher, year, isbn, category, stock)
             VALUES (:title, :author, :publisher, :year, :isbn, :category, :stock)'
        );
        $stmt->execute([
            ':title'     => $old['title'],
            ':author'    => $old['author'],
            ':publisher' => $old['publisher'],
            ':year'      => $old['year'],
            ':isbn'      => $old['isbn'],
            ':category'  => $old['category'],
            ':stock'     => $old['stock'],
        ]);

        set_flash('success', 'msg_saved');
        redirect('index.php');
    }
}

$action    = 'create.php';
$submitKey = 'btn_save';

require __DIR__ . '/includes/header.php';
?>
<div class="page-head">
    <h1><?= e(t('add_book')) ?></h1>
</div>
<?php require __DIR__ . '/includes/book_form.php'; ?>
<?php require __DIR__ . '/includes/footer.php'; ?>
