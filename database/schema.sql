-- =====================================================================
--  Skema & Data Awal - db_perpustakaan
--  Sistem Informasi Katalog Buku Perpustakaan
--
--  Impor melalui phpMyAdmin atau:
--    mysql -u root -p < database/schema.sql
--
--  Akun demo:  username = admin   |   password = admin123
-- =====================================================================

CREATE DATABASE IF NOT EXISTS db_perpustakaan
  CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci;

USE db_perpustakaan;

-- Urutan drop dijaga agar aman saat impor ulang
DROP TABLE IF EXISTS books;
DROP TABLE IF EXISTS users;

-- ---------------------------------------------------------------------
-- Tabel users
-- ---------------------------------------------------------------------
CREATE TABLE users (
  id         INT AUTO_INCREMENT PRIMARY KEY,
  username   VARCHAR(50)  NOT NULL UNIQUE,
  password   VARCHAR(255) NOT NULL,
  created_at TIMESTAMP    DEFAULT CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- ---------------------------------------------------------------------
-- Tabel books
-- ---------------------------------------------------------------------
CREATE TABLE books (
  id         INT AUTO_INCREMENT PRIMARY KEY,
  title      VARCHAR(150) NOT NULL,
  author     VARCHAR(100) NOT NULL,
  publisher  VARCHAR(100) NOT NULL,
  year       SMALLINT     NOT NULL,
  isbn       VARCHAR(17)  NOT NULL UNIQUE,
  category   VARCHAR(50)  NOT NULL,
  stock      INT          NOT NULL DEFAULT 0,
  created_at TIMESTAMP    DEFAULT CURRENT_TIMESTAMP,
  updated_at TIMESTAMP    DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- ---------------------------------------------------------------------
-- Akun admin awal (password: admin123, hash bcrypt via password_hash()).
-- Ganti password setelah login pertama pada lingkungan nyata.
-- ---------------------------------------------------------------------
INSERT INTO users (username, password) VALUES
  ('admin', '$2y$12$i3I5HqmaLIvGtPkg9AfYguosiNqdb6lRTtwluOP5sV4zuDzKQSsr6');

-- ---------------------------------------------------------------------
-- Data contoh buku (kategori memakai kunci kanonik untuk dual bahasa).
-- ---------------------------------------------------------------------
INSERT INTO books (title, author, publisher, year, isbn, category, stock) VALUES
  ('Bumi Manusia',                 'Pramoedya Ananta Toer', 'Hasta Mitra',         1980, '978-979-97312-3-3', 'fiction',     5),
  ('Laskar Pelangi',               'Andrea Hirata',         'Bentang Pustaka',     2005, '978-979-3062-79-2', 'fiction',     8),
  ('Sapiens',                      'Yuval Noah Harari',     'Kepustakaan Populer', 2017, '978-602-424-621-8', 'history',     3),
  ('A Brief History of Time',      'Stephen Hawking',       'Bantam Books',        1988, '978-0553380163',    'science',     4),
  ('Clean Code',                   'Robert C. Martin',      'Prentice Hall',       2008, '978-0132350884',    'technology',  6),
  ('The Pragmatic Programmer',     'Andrew Hunt',           'Addison-Wesley',      1999, '978-0201616224',    'technology',  2),
  ('Filsafat Ilmu',                'Jujun S. Suriasumantri','Pustaka Sinar Harapan',2009,'978-979-416-041-5', 'non_fiction', 3),
  ('Negeri 5 Menara',              'Ahmad Fuadi',           'Gramedia',            2009, '978-979-22-4862-3', 'fiction',     7),
  ('Cosmos',                       'Carl Sagan',            'Random House',        1980, '978-0394502947',    'science',     2),
  ('Sejarah Indonesia Modern',     'M.C. Ricklefs',         'Serambi',             2008, '979-1112-15-1',     'history',     4),
  ('Algoritma dan Pemrograman',    'Rinaldi Munir',         'Informatika',         2016, '978-602-1514-01-9', 'technology',  9),
  ('Atomic Habits',                'James Clear',           'Avery',               2018, '978-0735211292',    'non_fiction', 5);
