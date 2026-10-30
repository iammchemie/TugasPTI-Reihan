<?php
/**
 * Language file - English (en).
 * Every key must exist in both id.php and en.php to keep the UI consistent.
 */

return [
    // General
    'app_name'        => 'Library Catalog System',
    'app_tagline'     => 'Book Catalog Management',
    'language'        => 'Language',
    'footer_text'     => 'Library Book Catalog Information System',

    // Navigation
    'nav_books'       => 'Book List',
    'nav_add'         => 'Add Book',
    'logout'          => 'Logout',
    'logged_in_as'    => 'Signed in as',

    // Login
    'login_title'     => 'Sign In',
    'login_subtitle'  => 'Please sign in to manage the book catalog',
    'username'        => 'Username',
    'password'        => 'Password',
    'btn_login'       => 'Sign In',

    // Book list
    'book_list'       => 'Book List',
    'search_placeholder' => 'Search title, author, or ISBN...',
    'btn_search'      => 'Search',
    'btn_reset'       => 'Reset',
    'h_no'            => 'No',
    'h_title'         => 'Title',
    'h_author'        => 'Author',
    'h_publisher'     => 'Publisher',
    'h_year'          => 'Year',
    'h_isbn'          => 'ISBN',
    'h_category'      => 'Category',
    'h_stock'         => 'Stock',
    'h_action'        => 'Action',
    'btn_edit'        => 'Edit',
    'btn_delete'      => 'Delete',
    'empty_state'     => 'No books yet',
    'empty_search'    => 'No books match your search',
    'total_books'     => '%d books total',
    'searching_for'   => 'Search results for "%s"',
    'page_prev'       => 'Previous',
    'page_next'       => 'Next',
    'page_info'       => 'Page %1$d of %2$d',

    // Form
    'add_book'        => 'Add Book',
    'edit_book'       => 'Edit Book',
    'f_title'         => 'Book Title',
    'f_author'        => 'Author',
    'f_publisher'     => 'Publisher',
    'f_year'          => 'Publication Year',
    'f_isbn'          => 'ISBN',
    'f_category'      => 'Category',
    'f_stock'         => 'Stock Quantity',
    'select_category' => '-- Select Category --',
    'btn_save'        => 'Save',
    'btn_update'      => 'Update',
    'btn_cancel'      => 'Cancel',
    'legend_required' => 'Fields marked * are required',

    // Categories
    'cat_fiction'     => 'Fiction',
    'cat_non_fiction' => 'Non-Fiction',
    'cat_science'     => 'Science',
    'cat_technology'  => 'Technology',
    'cat_history'     => 'History',
    'cat_other'       => 'Other',

    // Notifications
    'msg_login_success'  => 'Signed in successfully. Welcome!',
    'msg_logged_out'     => 'You have been signed out.',
    'msg_login_required' => 'Please sign in first.',
    'msg_saved'          => 'Data saved successfully',
    'msg_updated'        => 'Data updated successfully',
    'msg_deleted'        => 'Data deleted successfully',
    'msg_not_found'      => 'Book data not found',
    'msg_delete_post_error' => 'Deletion must be done via the confirmation button.',
    'session_timeout'    => 'Your session expired due to inactivity. Please sign in again.',

    // Confirmation
    'confirm_delete'  => 'Are you sure you want to delete this?',

    // Errors
    'err_required'    => 'This field is required',
    'err_max_150'     => 'Maximum 150 characters',
    'err_max_100'     => 'Maximum 100 characters',
    'err_year_format' => 'Publication year must be a 4-digit number',
    'err_year_range'  => 'Publication year must be between %d and %d',
    'err_isbn_format' => 'ISBN may only contain digits and hyphens, 10-17 characters',
    'err_isbn_unique' => 'This ISBN is already registered',
    'err_category'    => 'Invalid category',
    'err_stock'       => 'Stock must be an integer of 0 or more',
    'err_login_empty' => 'Username and password are required',
    'err_login_invalid' => 'Incorrect username or password',
];
