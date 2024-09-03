<?php
/**
 * Bootstraps the Theme.
 *
 * @package AHERI
 */

namespace AHERI\inc;

use AHERI\inc\traits\singleton;

class AHERI_THEME {
    use singleton;

    protected function __construct() {
        // Load other classes.
        Assets::get_instance();
        Menus::get_instance();
        Meta_Boxes::get_instance();
        Sidebars::get_instance();
        Product::get_instance();
        Cart::get_instance();


        $this->setup_hooks();
    }

    protected function setup_hooks() {
        // Actions and filters
        add_action('after_setup_theme', [$this, 'setup_theme']);
        add_action('customize_register', [$this, 'aheri_hero_register']);
        add_action('admin_head', [$this,'aheri_enqueue_admin_styles']);
        add_action('init', [$this, 'custom_add_user_role']);
        add_action('init', [$this, 'redirect_to_custom_register']);
        add_action('init', [$this, 'add_custom_user_fields_to_wp_users']);
        add_action('wp_ajax_custom_login', [$this, 'custom_login']);
        add_action('wp_ajax_nopriv_custom_login', [$this, 'custom_login']);

    }

    public function setup_theme() {
        // Theme Title
        add_theme_support('title-tag');

        // Custom Logo
        add_theme_support('custom-logo', [
            'header-text'          => ['site-title', 'site-description'],
            'flex-height'          => true,
            'flex-width'           => true,
            'unlink-homepage-logo' => true,
        ]);

        // Custom Background
        add_theme_support('custom-background', [
            'default-color'  => 'fff',
            'default-image'  => '',
            'default-repeat' => 'no-repeat',
        ]);

        // Thumbnail Image Support / Featured Photo on
        add_theme_support('post-thumbnails', ['page', 'post', 'custom_product']);
        add_image_size('post-thumbnails', 800, 450, true);
        add_image_size('featured-thumbnail', 800, 450, true);

        //Refresh particulr section when adding or updating , For better previwing when changes are made
        add_theme_support('customize-selective-refresh-widgets');

        //add defult post link and RSS to head
        add_theme_support('automatic-feed-links');

        //turn on some html5 fetures, switch the defult html structure
        add_theme_support('html5', [
            'comment-list',
            'comment-form',
            'search-form',
            'gallery',
            'caption',
            'style',
            'script',
        ]);

        //allows to link a custom style to the tiny mice editor
        add_editor_style();

        //
        add_theme_support('wp-block-styles');

        //For alignment of a image
        add_theme_support('align-wide');

        add_theme_support('editor-styles');

        remove_theme_support('core-block-patterns');

        global $content_width;
        if (!isset($content_width)) {
            $content_width = 1210;
        }
    }

    function aheri_enqueue_admin_styles() {
        echo '<style>
            .category_icon i:before {
                font-family: "dashicons" !important;
                font-style: normal !important;
                font-size: initial !important;
            }
        </style>';
    }


    public function aheri_hero_register($wp_customize) {
        // Add a section for the Hero area
        $wp_customize->add_section('hero_section', [
            'title'    => __('Hero Section', 'aheri'),
            'priority' => 30,
        ]);

        // Add setting for Hero Background
        $wp_customize->add_setting('hero_bg', [
            'default'           => '',
            'sanitize_callback' => 'esc_url_raw',
        ]);

        $wp_customize->add_control(new \WP_Customize_Image_Control($wp_customize, 'hero_bg', [
            'label'    => __('Hero Background', 'aheri'),
            'section'  => 'hero_section',
            'settings' => 'hero_bg',
        ]));

         // Add setting for H1 Heading
         $wp_customize->add_setting('h1_heading', [
            'default'           => __('', 'aheri'),
            'sanitize_callback' => 'sanitize_text_field',
        ]);

        $wp_customize->add_control('h1_heading', [
            'label'   => __('H1 Heading', 'aheri'),
            'section' => 'hero_section',
            'type'    => 'text',
        ]);

        // Add setting for Gift Text
         $wp_customize->add_setting('gift_heading', [
            'default'           => __('', 'aheri'),
            'sanitize_callback' => 'sanitize_text_field',
        ]);

        $wp_customize->add_control('gift_heading', [
            'label'   => __('Gift Heading', 'aheri'),
            'section' => 'hero_section',
            'type'    => 'text',
        ]);

        // Add setting for Hero Text
        $wp_customize->add_setting('gift_text', [
            'default'           => __('', 'aheri'),
            'sanitize_callback' => 'sanitize_text_field',
        ]);

        $wp_customize->add_control('gift_text', [
            'label'   => __('Gift Text', 'aheri'),
            'section' => 'hero_section',
            'type'    => 'text',
        ]);
        // Add setting for Hero Text
        $wp_customize->add_setting('hero_text', [
            'default'           => __('', 'aheri'),
            'sanitize_callback' => 'sanitize_text_field',
        ]);

        $wp_customize->add_control('hero_text', [
            'label'   => __('Hero Text', 'aheri'),
            'section' => 'hero_section',
            'type'    => 'text',
        ]);

        // Add setting for Hero Button1 Text
        $wp_customize->add_setting('hero_button_coloured', [
            'default'           => __('View More', 'aheri'),
            'sanitize_callback' => 'sanitize_text_field',
        ]);

        $wp_customize->add_control('hero_button_coloured', [
            'label'   => __('Hero Button Coloured Text', 'aheri'),
            'section' => 'hero_section',
            'type'    => 'text',
        ]);

        // Add setting for Hero Button1 URL
        $wp_customize->add_setting('hero_button_coloured_url', [
            'default'           => '#',
            'sanitize_callback' => 'esc_url_raw',
        ]);

        $wp_customize->add_control('hero_button_coloured_url', [
            'label'   => __('Hero Button Coloured URL', 'aheri'),
            'section' => 'hero_section',
            'type'    => 'url',
        ]);

        // Add setting for Hero Button2 Text
        $wp_customize->add_setting('hero_button_transparent', [
            'default'           => __('', 'aheri'),
            'sanitize_callback' => 'sanitize_text_field',
        ]);

        $wp_customize->add_control('hero_button_transparent', [
            'label'   => __('Hero Button Transparent Text', 'aheri'),
            'section' => 'hero_section',
            'type'    => 'text',
        ]);

        // Add setting for Hero Button2 URL
        $wp_customize->add_setting('hero_button_transparent_url', [
            'default'           => '#',
            'sanitize_callback' => 'esc_url_raw',
        ]);

        $wp_customize->add_control('hero_button_transparent_url', [
            'label'   => __('Hero Button Transparent URL', 'aheri'),
            'section' => 'hero_section',
            'type'    => 'url',
        ]);
    }

    function custom_add_user_role() {
          /* Create Author Pro User Role */
        add_role(
            'aheri_customer', //  System name of the role.
            __( 'Customer'  ), // Display name of the role.
            array(
                'read'  => true,
                'delete_posts'  => false,
                'delete_published_posts' => false,
                'edit_posts'   => false,
                'publish_posts' => false,
                'edit_published_posts'   => false,
                'upload_files'  => false,
                'moderate_comments'=> false, // This user will be able to moderate the comments.
            )
        );
    }

    function redirect_to_custom_register() {
        if ( 'wp-login.php' == $GLOBALS['pagenow'] && isset($_GET['action']) && $_GET['action'] === 'register' ) {
            wp_redirect(site_url('/register'));
            exit();
        }
    }

   // Add this to your theme's functions.php or a custom plugin

    function custom_login() {
    // Process login
        $username = sanitize_text_field($_POST['username']);
        $password = sanitize_text_field($_POST['password']);

        $creds = array(
            'user_login'    => $username,
            'user_password' => $password,
            'remember'      => isset($_POST['remember']) ? true : false,
        );

        $user = wp_signon($creds, false);

        if ( is_wp_error($user) ) {
            wp_send_json_error(array('message' => $user->get_error_message()));
        } else {
            wp_send_json_success(array('redirect' => home_url())); // Redirect to home page
        }
    }



    function add_custom_user_fields_to_wp_users() {
        global $wpdb;
        $table_name = $wpdb->prefix . 'users';

        // Columns to add
        $columns_to_add = array(
            'first_name' => 'VARCHAR(255)',
            'last_name'  => 'VARCHAR(255)',
            'address'    => 'VARCHAR(255)',
            'city'       => 'VARCHAR(100)',
            'postcode'   => 'VARCHAR(20)',
            'phone'      => 'VARCHAR(20)'
        );

        // Get existing columns
        $existing_columns = $wpdb->get_col("DESCRIBE $table_name");

        foreach ($columns_to_add as $column => $type) {
            // Check if the column already exists
            if (!in_array($column, $existing_columns)) {
                // Column does not exist, add it
                $wpdb->query("ALTER TABLE $table_name ADD COLUMN $column $type AFTER user_email");
            }
        }
    }



}

// Initialize the theme instance
AHERI_THEME::get_instance();


