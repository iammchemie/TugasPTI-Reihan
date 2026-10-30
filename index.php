<?php
/**
 * Daftar buku - Read (FR-D03) + pencarian (FR-D09) + paginasi (FR-D10).
 */

require_once __DIR__ . '/config/database.php';
require_once __DIR__ . '/includes/functions.php';
require_once __DIR__ . '/includes/auth.php';

require_login();

require_once __DIR__ . '/includes/lang.php';

$pageTitle = 'book_list';

$q       = trim((string) ($_GET['q'] ?? ''));
$perPage = 10;
$pageNum = max(1, (int) ($_GET['page'] ?? 1));

$where  = '';
$params = [];
if ($q !== '') {
    $where  = 'WHERE title LIKE :q1 OR author LIKE :q2 OR isbn LIKE :q3';
    $like   = '%' . $q . '%';
    $params = [':q1' => $like, ':q2' => $like, ':q3' => $like];
}

// Hitung total untuk paginasi.
$countStmt = db()->prepare('SELECT COUNT(*) FROM books ' . $where);
$countStmt->execute($params);
$total = (int) $countStmt->fetchColumn();

$totalPages = max(1, (int) ceil($total / $perPage));
if ($pageNum > $totalPages) {
    $pageNum = $totalPages;
}
$offset = ($pageNum - 1) * $perPage;

// Ambil data halaman ini (prepared statement + LIMIT/OFFSET terikat integer).
$stmt = db()->prepare(
    'SELECT id, title, author, publisher, year, isbn, category, stock
       FROM books ' . $where . '
       ORDER BY id DESC
       LIMIT :limit OFFSET :offset'
);
foreach ($params as $key => $value) {
    $stmt->bindValue($key, $value);
}
$stmt->bindValue(':limit', $perPage, PDO::PARAM_INT);
$stmt->bindValue(':offset', $offset, PDO::PARAM_INT);
$stmt->execute();
$books = $stmt->fetchAll();

$pageUrl = function (int $p) use ($q): string {
    $args = ['page' => $p];
    if ($q !== '') {
        $args['q'] = $q;
    }

    return 'index.php?' . http_build_query($args);
};

require __DIR__ . '/includes/header.php';
?>
<div class="page-head">
    <h1><?= e(t('book_list')) ?></h1>
    <a href="create.php" class="btn btn-primary"><?= e(t('nav_add')) ?></a>
</div>

<div class="toolbar">
    <form method="get" action="index.php" class="search-form">
        <input type="text" name="q" class="input" value="<?= e($q) ?>"
               placeholder="<?= e(t('search_placeholder')) ?>">
        <button type="submit" class="btn btn-primary"><?= e(t('btn_search')) ?></button>
        <?php if ($q !== ''): ?>
            <a href="index.php" class="btn btn-outline"><?= e(t('btn_reset')) ?></a>
        <?php endif; ?>
    </form>
</div>

<p class="result-meta">
    <?= e(t('total_books', $total)) ?>
    <?php if ($q !== ''): ?> &middot; <?= e(t('searching_for', $q)) ?><?php endif; ?>
</p>

<div class="table-wrap">
    <?php if (empty($books)): ?>
        <div class="empty">
            <?= e($q !== '' ? t('empty_search') : t('empty_state')) ?>
        </div>
    <?php else: ?>
        <table>
            <thead>
                <tr>
                    <th><?= e(t('h_no')) ?></th>
                    <th><?= e(t('h_title')) ?></th>
                    <th><?= e(t('h_author')) ?></th>
                    <th><?= e(t('h_publisher')) ?></th>
                    <th><?= e(t('h_year')) ?></th>
                    <th><?= e(t('h_isbn')) ?></th>
                    <th><?= e(t('h_category')) ?></th>
                    <th><?= e(t('h_stock')) ?></th>
                    <th><?= e(t('h_action')) ?></th>
                </tr>
            </thead>
            <tbody>
                <?php foreach ($books as $i => $book): ?>
                    <tr>
                        <td><?= $offset + $i + 1 ?></td>
                        <td><?= e($book['title']) ?></td>
                        <td><?= e($book['author']) ?></td>
                        <td><?= e($book['publisher']) ?></td>
                        <td><?= e($book['year']) ?></td>
                        <td><?= e($book['isbn']) ?></td>
                        <td><?= e(t('cat_' . $book['category'])) ?></td>
                        <td><?= (int) $book['stock'] ?></td>
                        <td>
                            <div class="cell-actions">
                                <a href="edit.php?id=<?= (int) $book['id'] ?>" class="btn btn-outline btn-sm">
                                    <?= e(t('btn_edit')) ?>
                                </a>
                                <form method="post" action="delete.php" class="inline-form"
                                      onsubmit="return confirm('<?= e(t('confirm_delete')) ?>');">
                                    <?= csrf_field() ?>
                                    <input type="hidden" name="id" value="<?= (int) $book['id'] ?>">
                                    <button type="submit" class="btn btn-danger btn-sm">
                                        <?= e(t('btn_delete')) ?>
                                    </button>
                                </form>
                            </div>
                        </td>
                    </tr>
                <?php endforeach; ?>
            </tbody>
        </table>
    <?php endif; ?>
</div>

<?php if ($totalPages > 1): ?>
    <div class="pagination">
        <?php if ($pageNum > 1): ?>
            <a href="<?= e($pageUrl($pageNum - 1)) ?>" class="btn btn-outline btn-sm">
                &laquo; <?= e(t('page_prev')) ?>
            </a>
        <?php else: ?>
            <span class="btn btn-outline btn-sm disabled">&laquo; <?= e(t('page_prev')) ?></span>
        <?php endif; ?>

        <span class="page-info"><?= e(t('page_info', $pageNum, $totalPages)) ?></span>

        <?php if ($pageNum < $totalPages): ?>
            <a href="<?= e($pageUrl($pageNum + 1)) ?>" class="btn btn-outline btn-sm">
                <?= e(t('page_next')) ?> &raquo;
            </a>
        <?php else: ?>
            <span class="btn btn-outline btn-sm disabled"><?= e(t('page_next')) ?> &raquo;</span>
        <?php endif; ?>
    </div>
<?php endif; ?>
<?php require __DIR__ . '/includes/footer.php'; ?>
