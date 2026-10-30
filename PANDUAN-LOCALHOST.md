# Panduan Menjalankan di Localhost

Proyek: **Sistem Informasi Katalog Buku Perpustakaan** (PHP murni + MySQL)  
Lokasi: `D:\katalog-perpustakaan`  
URL setelah setup: **http://localhost/katalog-perpustakaan/login.php**

Akun demo: `admin` / `admin123`

## 1) Siapkan database
Impor skema (otomatis buat database + tabel + data awal):

**Via CLI (Laragon MySQL):**
```powershell
cmd /c "C:\laragon\bin\mysql\mysql-8.4.3-winx64\bin\mysql.exe -u root --default-character-set=utf8mb4 < ""D:\katalog-perpustakaan\database\schema.sql"""
```

**Via phpMyAdmin/Adminer:** Import file `database/schema.sql`.

## 2) Akses lewat Laragon (disarankan)
Opsi A: buat junction dari `D:\` ke `C:\laragon\www`
```powershell
New-Item -ItemType Junction -Path 'C:\laragon\www\katalog-perpustakaan' -Target 'D:\katalog-perpustakaan'
```
Lalu buka **http://localhost/katalog-perpustakaan/login.php**.

Opsi B: copy/pindahkan folder ke `C:\laragon\www\katalog-perpustakaan` langsung.

## 3) Akses lewat PHP built-in server
```powershell
C:\laragon\bin\php\php-8.5.11-nts-Win32-vs17-x64\php.exe -c C:\laragon\bin\php\php-8.5.11-nts-Win32-vs17-x64\php.ini -S localhost:8000 -t D:\katalog-perpustakaan
```
Buka **http://localhost:8000/login.php**. Hentikan dengan `Ctrl+C`.

> Tanpa `-c php.ini`, ekstensi `pdo_mysql` bisa tidak aktif.

## 4) Konfigurasi koneksi
Atur di `config/database.php` (bawaan Laragon sudah benar):
```php
'host' => '127.0.0.1', 'port' => 3306, 'name' => 'db_perpustakaan', 'user' => 'root', 'pass' => ''
```

## Troubleshooting
| Gejala | Solusi |
|---|---|
| `Unknown database` | Impor `database/schema.sql` |
| `could not find driver` | Aktifkan `extension=pdo_mysql` di php.ini atau pakai Laragon |
| `403` di `/phpmyadmin` | Gunakan `/adminer` atau pasang phpMyAdmin |
| Port 80 bentrok | Ganti port Apache di Laragon atau pakai built-in server |
| Perubahan tidak terlihat | `Ctrl+F5` |
