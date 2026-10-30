# Rangkuman Alur Program

**Sistem Informasi Katalog Buku Perpustakaan** — PHP murni + MySQL, tanpa framework, tanpa Composer.
Arsitektur: *page-based* (setiap file `.php` = satu halaman/aksi) dengan `includes/` sebagai pustaka bersama.

---

## 1. Peta file

| Lapisan | File | Tugas |
|---|---|---|
| Konfigurasi | `config/database.php` | Fungsi `db()` → satu koneksi PDO (singleton) |
| Pustaka | `includes/functions.php` | `e()`, `redirect()`, CSRF, flash, `validate_book()` |
| Pustaka | `includes/auth.php` | Mulai session, timeout, `require_login()` / `require_guest()` |
| Pustaka | `includes/lang.php` | Muat bahasa dari session, `t()` penerjemah |
| Tampilan | `includes/header.php`, `footer.php`, `book_form.php` | Layout + form bersama create/edit |
| Halaman | `login.php`, `logout.php`, `index.php`, `create.php`, `edit.php`, `delete.php`, `set_lang.php` | Titik masuk HTTP |
| Data | `database/schema.sql` | Tabel `users`, `books` + data awal |

Tidak ada routing. URL = nama file: `/katalog-perpustakaan/index.php`, `create.php`, dst.

---

## 2. Alur satu request

```
Browser → halaman .php
   ├─ require config/database.php
   ├─ require includes/functions.php
   ├─ require includes/auth.php  (session + timeout)
   ├─ require_login() / require_guest()
   ├─ require includes/lang.php
   ├─ logika (query PDO / validasi)
   ├─ require header.php  (flash + nav)
   ├─ HTML + e() 
   └─ require footer.php
```

---

## 3. Login & session

- Cookie `HttpOnly`, `SameSite=Lax`; `session_regenerate_id(true)` setelah login berhasil.
- Idle timeout 30 menit; jika expired → session dihancurkan, flash `session_timeout`.
- Login salah menampilkan pesan umum (tidak bocor username/password).
- Hanya POST dengan CSRF token yang bisa melakukan aksi sensitif.

---

## 4. CRUD

| Aksi | Alur |
|---|---|
| **Read** `index.php` | Pencarian `q` (LIKE) + paginasi 10/halaman, `ORDER BY id DESC`, `LIMIT/OFFSET` terikat `INT`. |
| **Create** `create.php` | CSRF → `validate_book()` → jika valid `INSERT` → flash → redirect. |
| **Update** `edit.php` | Ambil data by id, render form terisi; POST divalidasi dengan `$ignoreId` agar ISBN tidak bentrok dengan dirinya sendiri → `UPDATE`. |
| **Delete** `delete.php` | Tolak metode selain POST, cek CSRF, `DELETE WHERE id=?`, cek `rowCount()` untuk pesan hasil. |

Semua operasi modifikasi wajib `POST` + token CSRF. Konfirmasi `confirm()` ada di form hapus.

---

## 5. Validasi kunci

`title/author/publisher` wajib & batas panjang; `year` wajib format 4 digit & 1900–tahun sekarang;
`isbn` wajib pola `0-9-` panjang 10–17 & unik; `category` harus dari whitelist; `stock` wajib bilangan bulat >= 0.
Semua error berupa **kunci terjemahan** dan nilai ditampilkan lewat `e()`.

---

## 6. Keamanan

- PDO prepared statements, `bindValue` untuk LIMIT/OFFSET
- `htmlspecialchars` di semua output (`e()`)
- CSRF double-submit (session) + `hash_equals`
- `HttpOnly` session cookie, regenerasi session id
- Proteksi halaman, session timeout
- Password `password_hash`/`password_verify`

---

## 7. Database

```sql
users(id PK, username UNIQUE, password, created_at)
books(id PK, title, author, publisher, year SMALLINT, isbn UNIQUE, category, stock, created_at, updated_at)
```

Akun demo: `admin` / `admin123`.
