# Hasil Pengujian Penerimaan

Tanggal: 13 Oktober 2026
Lingkungan: PHP 8.5.11 (CLI, ext `pdo_mysql`), MariaDB (XAMPP), server uji `php -S 127.0.0.1:8000`
Harness: `tests/acceptance.php` (permintaan HTTP nyata + cookie jar, token CSRF disertakan)

## Ringkasan

| Status | Jumlah |
|---|---|
| Lulus (PASS) | 18 |
| Gagal (FAIL) | 0 |
| **Total** | **18** |

Seluruh skenario pengujian (`AC-01` s.d. `AC-18`) lulus. Data uji dibersihkan setelah
pengujian; jumlah baris `books` kembali ke 12 (data contoh).

## Rincian

| ID | Skenario | Hasil |
|---|---|---|
| AC-01 | `index.php` tanpa login dialihkan ke `login.php` | PASS |
| AC-02 | Login kredensial benar -> session terbentuk, masuk daftar buku | PASS |
| AC-03 | Login kredensial salah -> pesan umum, tetap di login | PASS |
| AC-04 | Setelah logout, halaman terproteksi tidak dapat diakses | PASS |
| AC-05 | Submit form dengan field kosong -> ditolak + pesan per field | PASS |
| AC-06 | Tahun terbit bukan angka -> ditolak | PASS |
| AC-07 | Input `<script>alert(1)</script>` di-escape, tidak dieksekusi | PASS |
| AC-08 | Input `' OR 1=1 --` diperlakukan sebagai teks biasa | PASS |
| AC-09 | Menyimpan ISBN yang sudah ada -> ditolak | PASS |
| AC-10 | Tambah buku valid -> muncul di tabel | PASS |
| AC-11 | Edit terisi data lama, perubahan tersimpan | PASS |
| AC-12 | Hapus via POST + konfirmasi -> data hilang | PASS |
| AC-13 | Tanpa konfirmasi POST (GET) data tidak terhapus | PASS |
| AC-14 | `edit.php?id=99999` -> pesan error, kembali ke daftar | PASS |
| AC-15 | Akses pertama tampil Bahasa Indonesia | PASS |
| AC-16 | Ganti ke English konsisten antar halaman | PASS |
| AC-17 | `set_lang.php?lang=xx` diabaikan | PASS |
| AC-18 | Pesan validasi & notifikasi tampil Bahasa Inggris | PASS |

## Catatan

- `AC-13` diuji dari sisi server: permintaan `GET delete.php` tidak menghapus data
  (penghapusan hanya lewat POST). Dialog `confirm()` itu sendiri bersifat sisi klien.
- `AC-04` diuji sebagai: setelah logout, `index.php` mengembalikan redirect ke `login.php`.
- Anomali `curl_close()` (deprecated pada PHP 8.5) sudah dihilangkan dari harness.

## Cara menjalankan ulang

```
# 1. Pastikan MySQL aktif dan db_perpustakaan sudah diimpor (database/schema.sql)
# 2. Jalankan server aplikasi
php -S 127.0.0.1:8000
# 3. Jalankan pengujian
php tests/acceptance.php
```
