<?php
/**
 * Ganti bahasa (FR-L02, FR-L06, FR-L07).
 * Nilai bahasa divalidasi terhadap whitelist; setelah itu kembali ke halaman asal.
 */

require_once __DIR__ . '/includes/auth.php';

$allowed = ['id', 'en'];
$lang    = (string) ($_GET['lang'] ?? '');

if (in_array($lang, $allowed, true)) {
    $_SESSION['lang'] = $lang;
}

// Tentukan halaman tujuan: pakai referer bila masih satu host, jika tidak ke beranda.
$target = is_logged_in() ? 'index.php' : 'login.php';
$ref    = $_SERVER['HTTP_REFERER'] ?? '';

if ($ref !== '') {
    $parts    = parse_url($ref);
    $host     = $_SERVER['HTTP_HOST'] ?? '';
    $sameHost = !isset($parts['host']) || $parts['host'] === $host;

    if ($sameHost && !empty($parts['path'])) {
        $target = basename($parts['path']);
        if (!empty($parts['query'])) {
            $target .= '?' . $parts['query'];
        }
    }
}

redirect($target);
