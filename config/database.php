<?php
/**
 * Konfigurasi koneksi database (PDO).
 * Sesuaikan kredensial di bawah dengan lingkungan Anda (XAMPP/Laragon).
 */

$DB_CONFIG = [
    'host'    => '127.0.0.1',
    'port'    => 3306,
    'name'    => 'db_perpustakaan',
    'user'    => 'root',
    'pass'    => '',
    'charset' => 'utf8mb4',
];

if (!function_exists('db')) {
    /**
     * Mengembalikan satu instance PDO (singleton).
     */
    function db(): PDO
    {
        static $pdo = null;

        if ($pdo === null) {
            global $DB_CONFIG;

            $dsn = sprintf(
                'mysql:host=%s;port=%d;dbname=%s;charset=%s',
                $DB_CONFIG['host'],
                $DB_CONFIG['port'],
                $DB_CONFIG['name'],
                $DB_CONFIG['charset']
            );

            try {
                $pdo = new PDO($dsn, $DB_CONFIG['user'], $DB_CONFIG['pass'], [
                    PDO::ATTR_ERRMODE            => PDO::ERRMODE_EXCEPTION,
                    PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC,
                    PDO::ATTR_EMULATE_PREPARES   => false,
                ]);
            } catch (PDOException $e) {
                http_response_code(500);
                exit('Koneksi database gagal / Database connection failed: ' . $e->getMessage());
            }
        }

        return $pdo;
    }
}
