<?php
/**
 * Harness pengujian penerimaan: AC-01 .. AC-18.
 *
 * Prasyarat:
 *   - Server aplikasi aktif:  php -S 127.0.0.1:8000
 *   - Database db_perpustakaan sudah diimpor dari database/schema.sql
 *
 * Jalankan:  php tests/acceptance.php
 */

declare(strict_types=1);

require_once __DIR__ . '/../config/database.php';

const BASE      = 'http://127.0.0.1:8000';
define('COOKIE_JAR', sys_get_temp_dir() . DIRECTORY_SEPARATOR . 'katalog_acceptance_cookies.txt');

/* ------------------------------------------------------------------ HTTP */

function http(string $method, string $path, ?array $post = null, ?string $referer = null): array
{
    $url = str_starts_with($path, 'http') ? $path : BASE . '/' . ltrim($path, '/');
    $ch  = curl_init($url);

    $opts = [
        CURLOPT_RETURNTRANSFER => true,
        CURLOPT_HEADER         => true,
        CURLOPT_COOKIEJAR      => COOKIE_JAR,
        CURLOPT_COOKIEFILE     => COOKIE_JAR,
        CURLOPT_FOLLOWLOCATION => false,
        CURLOPT_TIMEOUT        => 15,
    ];
    if ($referer !== null) {
        $opts[CURLOPT_REFERER] = $referer;
    }
    if ($method === 'POST') {
        $opts[CURLOPT_POST]       = true;
        $opts[CURLOPT_POSTFIELDS] = http_build_query($post ?? []);
    }

    curl_setopt_array($ch, $opts);
    $resp = curl_exec($ch);

    if ($resp === false) {
        return ['status' => 0, 'body' => '', 'location' => null, 'error' => curl_error($ch)];
    }

    $headerSize = curl_getinfo($ch, CURLINFO_HEADER_SIZE);
    $status     = (int) curl_getinfo($ch, CURLINFO_HTTP_CODE);

    $headers = substr($resp, 0, $headerSize);
    $body    = substr($resp, $headerSize);
    $location = preg_match('/^Location:\s*(.+?)\r?$/mi', $headers, $m) ? trim($m[1]) : null;

    return ['status' => $status, 'body' => $body, 'location' => $location, 'error' => null];
}

function reset_session(): void
{
    if (is_file(COOKIE_JAR)) {
        @unlink(COOKIE_JAR);
    }
}

function csrf(string $html): ?string
{
    return preg_match('/name="csrf_token" value="([^"]+)"/', $html, $m) ? $m[1] : null;
}

function valid_book(array $overrides = []): array
{
    return array_merge([
        'title'     => 'TEST-Judul Buku',
        'author'    => 'TEST-Pengarang',
        'publisher' => 'TEST-Penerbit',
        'year'      => '2020',
        'isbn'      => '9990000000',
        'category'  => 'fiction',
        'stock'     => '3',
    ], $overrides);
}

/* ------------------------------------------------------------------ Runner */

$results = [];
$counter = ['pass' => 0, 'fail' => 0];

function check(string $id, string $desc, bool $pass, string $detail = ''): void
{
    global $results, $counter;
    $counter[$pass ? 'pass' : 'fail']++;
    $results[] = [$id, $desc, $pass, $detail];
    printf("[%s] %-6s %s%s\n", $pass ? 'PASS' : 'FAIL', $id, $desc, $pass ? '' : "  => {$detail}");
}

/* ------------------------------------------------------------- Mulai uji */

echo "== Pengujian Penerimaan Katalog Perpustakaan ==\n\n";

/* --- Otentikasi ---------------------------------------------------- */

reset_session();
$r = http('GET', 'index.php');
check('AC-01', 'index.php tanpa login dialihkan ke login.php',
    $r['status'] === 302 && stripos((string) $r['location'], 'login.php') !== false,
    "status={$r['status']} loc={$r['location']}");

reset_session();
$r = http('GET', 'login.php');
check('AC-15', 'Akses pertama tampil Bahasa Indonesia',
    $r['status'] === 200 && str_contains($r['body'], 'Masuk ke Sistem'),
    "status={$r['status']}");

reset_session();
$r = http('GET', 'login.php');
$r = http('POST', 'login.php', ['csrf_token' => csrf($r['body']), 'username' => 'admin', 'password' => 'salah']);
check('AC-03', 'Login salah -> pesan umum, tetap di login',
    $r['status'] === 200 && str_contains($r['body'], 'Username atau password salah'),
    "status={$r['status']}");

reset_session();
$r = http('GET', 'login.php');
$r = http('POST', 'login.php', ['csrf_token' => csrf($r['body']), 'username' => 'admin', 'password' => 'admin123']);
$idx = http('GET', 'index.php');
check('AC-02', 'Login benar -> session terbentuk, masuk daftar buku',
    $r['status'] === 302 && stripos((string) $r['location'], 'index.php') !== false
        && $idx['status'] === 200 && str_contains($idx['body'], 'Daftar Buku'),
    "login={$r['status']} index={$idx['status']}");

/* --- Validasi & Sanitasi ------------------------------------------ */

$create = http('GET', 'create.php');
$token  = csrf($create['body']);

$r = http('POST', 'create.php', ['csrf_token' => $token] + valid_book(['title' => '', 'author' => '', 'year' => '', 'isbn' => '', 'category' => '', 'stock' => '']));
check('AC-05', 'Submit field kosong ditolak + pesan error',
    $r['status'] === 200 && str_contains($r['body'], 'Kolom ini wajib diisi'),
    "status={$r['status']}");

$r = http('POST', 'create.php', ['csrf_token' => $token] + valid_book(['year' => 'abcd', 'isbn' => '9990000006']));
check('AC-06', 'Tahun terbit bukan angka ditolak',
    $r['status'] === 200 && str_contains($r['body'], 'Tahun terbit harus'),
    "status={$r['status']}");

$r = http('POST', 'create.php', ['csrf_token' => $token] + valid_book(['isbn' => '978-0553380163']));
check('AC-09', 'ISBN duplikat ditolak',
    $r['status'] === 200 && str_contains($r['body'], 'ISBN sudah terdaftar'),
    "status={$r['status']}");

$r = http('POST', 'create.php', ['csrf_token' => $token] + valid_book(['title' => '<script>alert(1)</script>', 'isbn' => '9990000007']));
$idx = http('GET', 'index.php');
$hasRaw = str_contains($idx['body'], '<script>alert(1)</script>');
$hasEsc = str_contains($idx['body'], '&lt;script&gt;alert(1)&lt;/script&gt;');
check('AC-07', 'XSS pada judul di-escape, tidak dieksekusi',
    $r['status'] === 302 && $hasEsc && !$hasRaw,
    "escaped={$hasEsc} raw={$hasRaw}");

$r = http('POST', 'create.php', ['csrf_token' => $token] + valid_book(['author' => "' OR 1=1 --", 'isbn' => '9990000008']));
$idx = http('GET', 'index.php');
check('AC-08', 'SQLi diperlakukan sebagai teks biasa',
    $r['status'] === 302 && $idx['status'] === 200 && str_contains($idx['body'], 'OR 1=1'),
    "status={$r['status']} index={$idx['status']}");

/* --- CRUD ---------------------------------------------------------- */

$r = http('POST', 'create.php', ['csrf_token' => $token] + valid_book(['title' => 'TEST-Buku Baru', 'isbn' => '9990000010']));
$newId = (int) db()->query("SELECT id FROM books WHERE isbn = '9990000010'")->fetchColumn();
$idx = http('GET', 'index.php');
check('AC-10', 'Tambah buku valid -> muncul di daftar',
    $r['status'] === 302 && $newId > 0 && str_contains($idx['body'], 'TEST-Buku Baru'),
    "status={$r['status']} id={$newId}");

$edit   = http('GET', 'edit.php?id=' . $newId);
$hasOld = str_contains($edit['body'], 'value="TEST-Buku Baru"');
$r = http('POST', 'edit.php?id=' . $newId,
    ['csrf_token' => csrf($edit['body'])] + valid_book(['title' => 'TEST-Buku Diubah', 'isbn' => '9990000010']));
$idx = http('GET', 'index.php');
check('AC-11', 'Edit terisi data lama lalu tersimpan',
    $edit['status'] === 200 && $hasOld && $r['status'] === 302 && str_contains($idx['body'], 'TEST-Buku Diubah'),
    "edit={$edit['status']} prefil={$hasOld} post={$r['status']}");

$r = http('GET', 'delete.php?id=' . $newId);
$stillExists = (bool) db()->query("SELECT id FROM books WHERE id = {$newId}")->fetchColumn();
check('AC-13', 'Tanpa konfirmasi POST data tidak terhapus',
    $stillExists, 'data terhapus padahal hanya GET');

$r = http('GET', 'edit.php?id=99999');
check('AC-14', 'edit.php?id tidak ada -> kembali ke daftar',
    $r['status'] === 302 && stripos((string) $r['location'], 'index.php') !== false,
    "status={$r['status']} loc={$r['location']}");

$r = http('POST', 'delete.php', ['csrf_token' => $token, 'id' => $newId]);
$stillExists = (bool) db()->query("SELECT id FROM books WHERE id = {$newId}")->fetchColumn();
check('AC-12', 'Hapus POST + konfirmasi -> data hilang',
    $r['status'] === 302 && !$stillExists,
    "status={$r['status']} exists=" . ($stillExists ? 'yes' : 'no'));

/* --- Dual Bahasa --------------------------------------------------- */

http('GET', 'set_lang.php?lang=en', null, BASE . '/index.php');
$idx = http('GET', 'index.php');
check('AC-16', 'Ganti ke English konsisten antar halaman',
    $idx['status'] === 200 && str_contains($idx['body'], 'Book List'),
    "status={$idx['status']}");

$r = http('POST', 'create.php', ['csrf_token' => $token] + valid_book(['title' => '', 'isbn' => '']));
check('AC-18', 'Pesan validasi tampil Bahasa Inggris',
    $r['status'] === 200 && str_contains($r['body'], 'This field is required'),
    "status={$r['status']}");

http('GET', 'set_lang.php?lang=xx', null, BASE . '/index.php');
$idx = http('GET', 'index.php');
check('AC-17', 'set_lang.php?lang=xx diabaikan',
    str_contains($idx['body'], 'Book List'),
    'bahasa berubah dari nilai tak dikenal');

/* --- Logout -------------------------------------------------------- */

http('GET', 'logout.php');
$idx = http('GET', 'index.php');
check('AC-04', 'Setelah logout halaman terproteksi tidak dapat diakses',
    $idx['status'] === 302 && stripos((string) $idx['location'], 'login.php') !== false,
    "status={$idx['status']} loc={$idx['location']}");

/* ------------------------------------------------------------ Bersihkan */

db()->exec("DELETE FROM books WHERE isbn IN ('9990000006','9990000007','9990000008','9990000010','9990000009')");

/* -------------------------------------------------------------- Ringkas */

echo "\n== Ringkasan ==\n";
printf("Lulus: %d | Gagal: %d | Total: %d\n", $counter['pass'], $counter['fail'], $counter['pass'] + $counter['fail']);

exit($counter['fail'] === 0 ? 0 : 1);
