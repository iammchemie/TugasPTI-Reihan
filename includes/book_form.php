<?php
/**
 * Partial formulir buku (dipakai oleh create.php dan edit.php).
 *
 * Variabel yang diharapkan:
 * @var array  $errors   Error per-field dari validate_book().
 * @var array  $old      Nilai input untuk repopulasi (FR-F05).
 * @var string $action   URL tujuan submit.
 * @var string $submitKey Kunci terjemahan tombol simpan (btn_save / btn_update).
 */

$fields = [
    'title'     => ['f_title', 150],
    'author'    => ['f_author', 100],
    'publisher' => ['f_publisher', 100],
];
?>
<form method="post" action="<?= e($action) ?>" class="card form">
    <?= csrf_field() ?>
    <p class="hint"><?= e(t('legend_required')) ?></p>

    <?php foreach ($fields as $name => [$labelKey, $max]): ?>
        <div class="form-row">
            <label for="<?= e($name) ?>"><?= e(t($labelKey)) ?> *</label>
            <input type="text" id="<?= e($name) ?>" name="<?= e($name) ?>"
                   maxlength="<?= (int) $max ?>" value="<?= e(old($old, $name)) ?>"
                   class="input<?= isset($errors[$name]) ? ' input-error' : '' ?>">
            <?php if (isset($errors[$name])): ?>
                <span class="error"><?= e(tr_error($errors[$name])) ?></span>
            <?php endif; ?>
        </div>
    <?php endforeach; ?>

    <div class="form-grid">
        <div class="form-row">
            <label for="year"><?= e(t('f_year')) ?> *</label>
            <input type="text" id="year" name="year" inputmode="numeric" maxlength="4"
                   value="<?= e(old($old, 'year')) ?>"
                   class="input<?= isset($errors['year']) ? ' input-error' : '' ?>">
            <?php if (isset($errors['year'])): ?>
                <span class="error"><?= e(tr_error($errors['year'])) ?></span>
            <?php endif; ?>
        </div>

        <div class="form-row">
            <label for="isbn"><?= e(t('f_isbn')) ?> *</label>
            <input type="text" id="isbn" name="isbn" maxlength="17"
                   value="<?= e(old($old, 'isbn')) ?>"
                   class="input<?= isset($errors['isbn']) ? ' input-error' : '' ?>">
            <?php if (isset($errors['isbn'])): ?>
                <span class="error"><?= e(tr_error($errors['isbn'])) ?></span>
            <?php endif; ?>
        </div>
    </div>

    <div class="form-grid">
        <div class="form-row">
            <label for="category"><?= e(t('f_category')) ?> *</label>
            <select id="category" name="category"
                    class="input<?= isset($errors['category']) ? ' input-error' : '' ?>">
                <option value=""><?= e(t('select_category')) ?></option>
                <?php foreach (categories() as $cat): ?>
                    <option value="<?= e($cat) ?>" <?= is_selected($old, 'category', $cat) ?>>
                        <?= e(t('cat_' . $cat)) ?>
                    </option>
                <?php endforeach; ?>
            </select>
            <?php if (isset($errors['category'])): ?>
                <span class="error"><?= e(tr_error($errors['category'])) ?></span>
            <?php endif; ?>
        </div>

        <div class="form-row">
            <label for="stock"><?= e(t('f_stock')) ?> *</label>
            <input type="text" id="stock" name="stock" inputmode="numeric"
                   value="<?= e(old($old, 'stock')) ?>"
                   class="input<?= isset($errors['stock']) ? ' input-error' : '' ?>">
            <?php if (isset($errors['stock'])): ?>
                <span class="error"><?= e(tr_error($errors['stock'])) ?></span>
            <?php endif; ?>
        </div>
    </div>

    <div class="form-actions">
        <button type="submit" class="btn btn-primary"><?= e(t($submitKey)) ?></button>
        <a href="index.php" class="btn btn-outline"><?= e(t('btn_cancel')) ?></a>
    </div>
</form>
