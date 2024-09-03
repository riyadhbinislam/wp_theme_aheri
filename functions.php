<?php
/**
 * Functions and definitions
 *
 * @package Aheri
 */

// Define constants for directory paths and URIs
if ( ! defined( 'AHERI_DIR_URI' ) ) {
    define( 'AHERI_DIR_URI', get_template_directory_uri() );
}

if ( ! defined( 'AHERI_DIR_PATH' ) ) {
    define( 'AHERI_DIR_PATH', get_template_directory() );
}

// Autoloader function
require_once AHERI_DIR_PATH . '/inc/helpers/autoloader.php';
require_once AHERI_DIR_PATH . '/inc/helpers/template-tags.php';
require_once AHERI_DIR_PATH . '/aheri-theme-option/redux-core/framework.php';
require_once AHERI_DIR_PATH . '/aheri-theme-option/sample/config.php';

// Initialize the theme
AHERI\inc\AHERI_THEME::get_instance();

include "function/default.php";