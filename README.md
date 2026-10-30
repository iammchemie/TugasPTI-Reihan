# Sistem Informasi Katalog Buku Perpustakaan

Aplikasi web **PHP murni + MySQL** untuk mengelola katalog buku perpustakaan, dilengkapi
otentikasi berbasis session dan antarmuka **dua bahasa** (Indonesia/Inggris).

## Fitur

- Login/logout dengan password ter-hash (`password_hash` / `password_verify`).
- Proteksi seluruh halaman CRUD via session + regenerasi session id.
- CRUD buku lengkap: daftar, tambah, ubah, hapus (konfirmasi + hanya POST).
- Validasi & sanitasi input sisi server (`trim`, `filter_var`, `htmlspecialchars`).
- Pencarian judul/pengarang/ISBN dan paginasi (10 data per halaman).
- Prepared statement (PDO) di seluruh query, token CSRF, cookie session `HttpOnly`.
- Pengalih bahasa ID/EN di semua halaman; pilihan disimpan di session.

## Kebutuhan

- PHP 8.0+ dengan ekstensi `pdo_mysql` (XAMPP/Laragon sudah menyediakannya).
- MySQL 5.7+ / MariaDB 10.4+.

## Instalasi

1. **Letakkan berkas** di document root web server (mis. `C:\xampp\htdocs\katalog-perpustakaan`).
2. **Buat database & impor skema:**
   - Via phpMyAdmin: buat database `db_perpustakaan` (jika belum ada), lalu impor `database/schema.sql`.
   - Via terminal:
     ```
     mysql -u root -p < database/schema.sql
     ```
     Skrip `schema.sql` otomatis membuat database, dua tabel, akun admin, dan 12 data contoh.
3. **Konfigurasi koneksi** (opsional) di `config/database.php`:
   ```php
   'host' => '127.0.0.1',
   'name' => 'db_perpustakaan',
   'user' => 'root',
   'pass' => '',
   ```
4. **Jalankan** melalui Apache (`http://localhost/katalog-perpustakaan/`) atau server bawaan PHP:
   ```
   php -S localhost:8000
   ```
   lalu buka `http://localhost:8000/login.php`.

## Akun Demo

| Username | Password |
|---|---|
| `admin` | `admin123` |

> Password di `schema.sql` disimpan sebagai hash bcrypt.

## Struktur Folder

```
config/database.php     Koneksi database (PDO)
includes/auth.php       Session, timeout, proteksi halaman
includes/functions.php  Sanitasi, validasi, CSRF, flash
includes/lang.php       Pemuat berkas bahasa
includes/header.php     Header + navigasi + language switcher
includes/footer.php     Footer
includes/book_form.php  Partial formulir buku (create/edit)
lang/id.php, lang/en.php  Teks antarmuka dua bahasa
assets/style.css        Gaya antarmuka
database/schema.sql     Skema + data awal
index.php               Daftar buku (Read + cari + paginasi)
create.php              Tambah buku (Create)
edit.php                Ubah buku (Update)
delete.php              Hapus buku (Delete, POST)
login.php               Login
logout.php              Logout
set_lang.php            Ganti bahasa
```

## Pengujian

Dengan server PHP aktif (`php -S localhost:8000`), jalankan:

```
php tests/acceptance.php
```
