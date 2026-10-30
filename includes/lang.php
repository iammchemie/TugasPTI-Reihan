<?php
/**
 * Pemuat berkas bahasa.
 * Bahasa aktif disimpan di $_SESSION['lang'] dan divalidasi terhadap whitelist.
 */

$allowed_langs = ['id', 'en'];

if (!isset($_SESSION['lang']) || !in_array($_SESSION['lang'], $allowed_langs, true)) {
    // FR-L05: bahasa default adalah Bahasa Indonesia.
    $_SESSION['lang'] = 'id';
}

$lang = require __DIR__ . '/../lang/' . $_SESSION['lang'] . '.php';

if (!function_exists('t')) {
    /**
     * Menerjemahkan sebuah kunci. Argumen tambahan dipakai untuk sprintf/vsprintf.
     */
    function t(string $key, ...$args): string
    {
        global $lang;
        $text = $lang[$key] ?? $key;

        return $args ? vsprintf($text, $args) : $text;
    }
}

if (!function_exists('tr_error')) {
    /**
     * Menerjemahkan nilai error validasi (string kunci atau array [kunci, ...args]).
     */
    function tr_error($err): string
    {
        if (is_array($err)) {
            return t($err[0], ...array_slice($err, 1));
        }

        return t((string) $err);
    }
}
