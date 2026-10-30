<?php
/**
 * Berkas bahasa - Bahasa Indonesia (id).
 * Setiap kunci harus ada di id.php dan en.php agar antarmuka tidak campur bahasa.
 */

return [
    // Umum
    'app_name'        => 'Sistem Katalog Perpustakaan',
    'app_tagline'     => 'Manajemen Katalog Buku',
    'language'        => 'Bahasa',
    'footer_text'     => 'Sistem Informasi Katalog Buku Perpustakaan',

    // Navigasi
    'nav_books'       => 'Daftar Buku',
    'nav_add'         => 'Tambah Buku',
    'logout'          => 'Keluar',
    'logged_in_as'    => 'Masuk sebagai',

    // Login
    'login_title'     => 'Masuk ke Sistem',
    'login_subtitle'  => 'Silakan masuk untuk mengelola katalog buku',
    'username'        => 'Nama Pengguna',
    'password'        => 'Kata Sandi',
    'btn_login'       => 'Masuk',

    // Daftar buku
    'book_list'       => 'Daftar Buku',
    'search_placeholder' => 'Cari judul, pengarang, atau ISBN...',
    'btn_search'      => 'Cari',
    'btn_reset'       => 'Reset',
    'h_no'            => 'No',
    'h_title'         => 'Judul',
    'h_author'        => 'Pengarang',
    'h_publisher'     => 'Penerbit',
    'h_year'          => 'Tahun',
    'h_isbn'          => 'ISBN',
    'h_category'      => 'Kategori',
    'h_stock'         => 'Stok',
    'h_action'        => 'Aksi',
    'btn_edit'        => 'Ubah',
    'btn_delete'      => 'Hapus',
    'empty_state'     => 'Belum ada data buku',
    'empty_search'    => 'Tidak ada buku yang cocok dengan pencarian Anda',
    'total_books'     => 'Total %d buku',
    'searching_for'   => 'Hasil pencarian untuk "%s"',
    'page_prev'       => 'Sebelumnya',
    'page_next'       => 'Berikutnya',
    'page_info'       => 'Halaman %1$d dari %2$d',

    // Form
    'add_book'        => 'Tambah Buku',
    'edit_book'       => 'Ubah Buku',
    'f_title'         => 'Judul Buku',
    'f_author'        => 'Pengarang',
    'f_publisher'     => 'Penerbit',
    'f_year'          => 'Tahun Terbit',
    'f_isbn'          => 'ISBN',
    'f_category'      => 'Kategori',
    'f_stock'         => 'Jumlah Stok',
    'select_category' => '-- Pilih Kategori --',
    'btn_save'        => 'Simpan',
    'btn_update'      => 'Perbarui',
    'btn_cancel'      => 'Batal',
    'legend_required' => 'Kolom bertanda * wajib diisi',

    // Kategori
    'cat_fiction'     => 'Fiksi',
    'cat_non_fiction' => 'Non-Fiksi',
    'cat_science'     => 'Sains',
    'cat_technology'  => 'Teknologi',
    'cat_history'     => 'Sejarah',
    'cat_other'       => 'Lainnya',

    // Notifikasi
    'msg_login_success'  => 'Berhasil masuk. Selamat datang!',
    'msg_logged_out'     => 'Anda telah keluar dari sistem.',
    'msg_login_required' => 'Silakan masuk terlebih dahulu.',
    'msg_saved'          => 'Data berhasil disimpan',
    'msg_updated'        => 'Data berhasil diperbarui',
    'msg_deleted'        => 'Data berhasil dihapus',
    'msg_not_found'      => 'Data buku tidak ditemukan',
    'msg_delete_post_error' => 'Penghapusan harus dilakukan melalui tombol konfirmasi.',
    'session_timeout'    => 'Sesi Anda berakhir karena tidak ada aktivitas. Silakan masuk kembali.',

    // Konfirmasi
    'confirm_delete'  => 'Yakin ingin menghapus data ini?',

    // Kesalahan
    'err_required'    => 'Kolom ini wajib diisi',
    'err_max_150'     => 'Maksimal 150 karakter',
    'err_max_100'     => 'Maksimal 100 karakter',
    'err_year_format' => 'Tahun terbit harus berupa 4 digit angka',
    'err_year_range'  => 'Tahun terbit harus antara %d dan %d',
    'err_isbn_format' => 'ISBN hanya boleh angka dan tanda hubung, 10-17 karakter',
    'err_isbn_unique' => 'ISBN sudah terdaftar',
    'err_category'    => 'Kategori tidak valid',
    'err_stock'       => 'Jumlah stok harus bilangan bulat 0 atau lebih',
    'err_login_empty' => 'Nama pengguna dan kata sandi wajib diisi',
    'err_login_invalid' => 'Username atau password salah',
];
