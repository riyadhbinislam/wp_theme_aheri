<?php
/**
 * Enqueue Theme Assets
 * @package Aheri
 */

namespace AHERI\inc;
use AHERI\inc\traits\singleton;

class Assets {
    use singleton;

    protected function __construct() {
        //load other classes.
        $this->setup_hooks();
    }

    protected function setup_hooks() {
        //actions and filters
        add_action('wp_enqueue_scripts', [ $this, 'register_styles' ]);
        add_action('wp_enqueue_scripts', [ $this, 'register_scripts' ]);
        add_action('admin_enqueue_scripts', [$this, 'enqueue_media']);
    }

    public function register_styles() {
        wp_enqueue_style('wp-style', get_stylesheet_uri(), array(), '1.0.1', 'all');
        wp_register_style( 'aheri_custom', AHERI_DIR_URI . '/assets/css/custom-style.css', array(), filemtime( AHERI_DIR_PATH . '/assets/css/custom-style.css' ), 'all' );
        wp_register_style( 'aheri_bootstrap', AHERI_DIR_URI . '/library/css/bootstrap.min.css', array(), false, 'all' );
        wp_register_style( 'aheri_mdb', AHERI_DIR_URI . '/library/css/mdb.min.css', array(), false, 'all' );

        wp_enqueue_style( 'dashicons' );
        wp_enqueue_style('aheri_custom');
        wp_enqueue_style('aheri_bootstrap');
        wp_enqueue_style('aheri_mdb');
        wp_enqueue_style('aheri_google_font', 'https://fonts.googleapis.com/css2?family=Open+Sans:ital@0;1&family=Roboto:wght@400;700&display=swap', false);
        wp_enqueue_style('aheri_fontawesome', 'https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.1.1/css/all.min.css', false);

    }

    public function register_scripts() {
        // Register the main script with jQuery as a dependency
        wp_register_script('aheri-custom-js', AHERI_DIR_URI . '/assets/js/custom.js', ['jquery'],  false, true );
        wp_register_script('aheri-custom-login', AHERI_DIR_URI . '/assets/js/custom-login.js', ['jquery'],  false, true );

        wp_localize_script('aheri-custom-js', 'aheri_ajax', array(
            'ajax_url' => admin_url('admin-ajax.php')
        ));
        wp_localize_script('aheri-custom-login', 'aheri_ajax', array(
            'ajax_url' => admin_url('admin-ajax.php')
        ));

        wp_register_script('aheri_main', AHERI_DIR_URI . '/library/js/main.js', ['jquery'],  false, true );
        wp_register_script('aheri_bootstrap_js', AHERI_DIR_URI . '/library/js/bootstrap.min.js', array(), false, true );

        // Enqueue the scripts
        wp_enqueue_script('jquery');
        wp_enqueue_script('aheri_main');
        wp_enqueue_script('aheri_bootstrap_js');
        wp_enqueue_script('aheri-custom-js');
        wp_enqueue_script('aheri-custom-login');
    }


    public function enqueue_media() {
        wp_enqueue_media(); // Enqueue the WordPress media uploader
    }
}
// Hook the enqueue function to the admin_enqueue_scripts action
