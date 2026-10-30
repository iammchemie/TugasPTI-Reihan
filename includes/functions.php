<?php
/**
 * Fungsi bantu: escape output, sanitasi, validasi, CSRF, dan flash message.
 */

require_once __DIR__ . '/../config/database.php';

if (!function_exists('e')) {
    /**
     * FR-F06: escape output untuk mencegah XSS.
     */
    function e($value): string
    {
        return htmlspecialchars((string) $value, ENT_QUOTES, 'UTF-8');
    }
}

if (!function_exists('redirect')) {
    function redirect(string $url): void
    {
        header('Location: ' . $url);
        exit;
    }
}

if (!function_exists('categories')) {
    /**
     * Daftar kategori kanonik (kunci disimpan di DB, label diterjemahkan saat tampil).
     */
    function categories(): array
    {
        return ['fiction', 'non_fiction', 'science', 'technology', 'history', 'other'];
    }
}

/* ---------------------------------------------------------------------
 * CSRF - double submit token di session.
 * ------------------------------------------------------------------ */

if (!function_exists('csrf_token')) {
    function csrf_token(): string
    {
        if (empty($_SESSION['csrf_token'])) {
            $_SESSION['csrf_token'] = bin2hex(random_bytes(32));
        }

        return $_SESSION['csrf_token'];
    }
}

if (!function_exists('csrf_field')) {
    function csrf_field(): string
    {
        return '<input type="hidden" name="csrf_token" value="' . e(csrf_token()) . '">';
    }
}

if (!function_exists('verify_csrf')) {
    function verify_csrf(): bool
    {
        return isset($_POST['csrf_token'], $_SESSION['csrf_token'])
            && is_string($_POST['csrf_token'])
            && hash_equals($_SESSION['csrf_token'], $_POST['csrf_token']);
    }
}

if (!function_exists('require_csrf')) {
    function require_csrf(): void
    {
        if (!verify_csrf()) {
            http_response_code(419);
            exit('Token CSRF tidak valid / Invalid CSRF token');
        }
    }
}

/* ---------------------------------------------------------------------
 * Flash message (notifikasi lintas redirect).
 * ------------------------------------------------------------------ */

if (!function_exists('set_flash')) {
    function set_flash(string $type, string $message_key): void
    {
        $_SESSION['flash'][] = ['type' => $type, 'message' => $message_key];
    }
}

if (!function_exists('get_flashes')) {
    function get_flashes(): array
    {
        $flashes = $_SESSION['flash'] ?? [];
        unset($_SESSION['flash']);

        return $flashes;
    }
}

/* ---------------------------------------------------------------------
 * Validasi formulir buku.
 * ------------------------------------------------------------------ */

if (!function_exists('validate_book')) {
    /**
     * Memvalidasi input buku.
     *
     * @return array{0: array<string,mixed>, 1: array<string,mixed>} [$errors, $data]
     */
    function validate_book(array $input, ?int $ignoreId = null): array
    {
        $errors      = [];
        $data        = [];
        $currentYear = (int) date('Y');

        // Judul Buku - wajib, maks 150 karakter
        $title = trim((string) ($input['title'] ?? ''));
        if ($title === '') {
            $errors['title'] = 'err_required';
        } elseif (mb_strlen($title) > 150) {
            $errors['title'] = 'err_max_150';
        }
        $data['title'] = $title;

        // Pengarang - wajib, maks 100 karakter
        $author = trim((string) ($input['author'] ?? ''));
        if ($author === '') {
            $errors['author'] = 'err_required';
        } elseif (mb_strlen($author) > 100) {
            $errors['author'] = 'err_max_100';
        }
        $data['author'] = $author;

        // Penerbit - wajib, maks 100 karakter
        $publisher = trim((string) ($input['publisher'] ?? ''));
        if ($publisher === '') {
            $errors['publisher'] = 'err_required';
        } elseif (mb_strlen($publisher) > 100) {
            $errors['publisher'] = 'err_max_100';
        }
        $data['publisher'] = $publisher;

        // Tahun Terbit - wajib, 4 digit, 1900..tahun berjalan
        $yearRaw       = trim((string) ($input['year'] ?? ''));
        $data['year']  = $yearRaw;
        if ($yearRaw === '') {
            $errors['year'] = 'err_required';
        } elseif (!preg_match('/^\d{4}$/', $yearRaw)) {
            $errors['year'] = 'err_year_format';
        } else {
            $year = filter_var($yearRaw, FILTER_VALIDATE_INT);
            if ($year === false) {
                $errors['year'] = 'err_year_format';
            } elseif ($year < 1900 || $year > $currentYear) {
                $errors['year'] = ['err_year_range', 1900, $currentYear];
            } else {
                $data['year'] = $year;
            }
        }

        // ISBN - wajib, angka & tanda hubung, 10-17 karakter, unik
        $isbn      = trim((string) ($input['isbn'] ?? ''));
        $data['isbn'] = $isbn;
        if ($isbn === '') {
            $errors['isbn'] = 'err_required';
        } elseif (!preg_match('/^[0-9-]{10,17}$/', $isbn)) {
            $errors['isbn'] = 'err_isbn_format';
        } else {
            $sql    = 'SELECT id FROM books WHERE isbn = :isbn';
            $params = [':isbn' => $isbn];
            if ($ignoreId !== null) {
                $sql .= ' AND id <> :id';
                $params[':id'] = $ignoreId;
            }
            $sql .= ' LIMIT 1';

            $stmt = db()->prepare($sql);
            $stmt->execute($params);
            if ($stmt->fetch()) {
                $errors['isbn'] = 'err_isbn_unique';
            }
        }

        // Kategori - wajib, harus dari whitelist
        $category       = trim((string) ($input['category'] ?? ''));
        $data['category'] = $category;
        if ($category === '') {
            $errors['category'] = 'err_required';
        } elseif (!in_array($category, categories(), true)) {
            $errors['category'] = 'err_category';
        }

        // Jumlah Stok - wajib, bilangan bulat >= 0
        $stockRaw      = trim((string) ($input['stock'] ?? ''));
        $data['stock'] = $stockRaw;
        if ($stockRaw === '') {
            $errors['stock'] = 'err_required';
        } elseif (!preg_match('/^\d+$/', $stockRaw)) {
            $errors['stock'] = 'err_stock';
        } else {
            $data['stock'] = (int) $stockRaw;
        }

        return [$errors, $data];
    }
}

/* ---------------------------------------------------------------------
 * Bantuan tampilan formulir.
 * ------------------------------------------------------------------ */

if (!function_exists('old')) {
    function old(array $data, string $key): string
    {
        return isset($data[$key]) ? (string) $data[$key] : '';
    }
}

if (!function_exists('is_selected')) {
    function is_selected(array $data, string $key, string $value): string
    {
        return (isset($data[$key]) && (string) $data[$key] === $value) ? 'selected' : '';
    }
}
