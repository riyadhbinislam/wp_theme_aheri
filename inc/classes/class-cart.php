<?php
/**
 * class Cart
 * @package Aheri
 */

namespace AHERI\inc;
use AHERI\inc\traits\singleton;

class Cart {
    use singleton;

    protected function __construct() {
        // Initialize session
        $this->cart_session();

        // Load other classes and setup hooks
        $this->setup_hooks();
    }

    protected function setup_hooks() {
        // Actions and filters
        add_action('wp', [$this, 'handle_cart_actions']);
        add_action('wp', [$this, 'process_checkout']);
        add_action('after_setup_theme',[$this, 'aheri_create_order_table']);
        add_action('admin_init',[$this, 'aheri_update_order_table']);
    }

    // Initialize session if not already started
    public function cart_session() {
        if (!session_id()) {
            session_start();
        }
    }

    // Add product to cart
    public function add_to_cart($product_id, $quantity) {
        if (!isset($_SESSION['cart'])) {
            $_SESSION['cart'] = [];
        }

        // If the product is already in the cart
        if (isset($_SESSION['cart'][$product_id])) {
            return [
                'success' => false,
                'message' => 'Product is already in the cart.'
            ];
        } else {
            $_SESSION['cart'][$product_id] = [
                'quantity' => $quantity,
                'price' => get_post_meta($product_id, '_product_price', true)
            ];
            return [
                'success' => true,
                'message' => 'Product added to cart.'
            ];
        }
    }


    // Handle removal of items
    public function handle_cart_actions() {
        if (isset($_GET['remove_from_cart'])) {
            $product_id = intval($_GET['remove_from_cart']);
            $this->remove_from_cart($product_id);
            wp_redirect(remove_query_arg('remove_from_cart'));
            exit;
        }
    }

    // Remove product from cart
    public function remove_from_cart($product_id) {
        if (isset($_SESSION['cart'][$product_id])) {
            unset($_SESSION['cart'][$product_id]);
        }
    }

    // Process checkout
    public function process_checkout() {
        if (isset($_POST['checkout'])) {
            // Create order data
            $order_data = [
                'items' => isset($_SESSION['cart']) ? $_SESSION['cart'] : [],
                'first_name' => sanitize_text_field($_POST['first_name']),
                'last_name' => sanitize_text_field($_POST['last_name']),
                'email' => sanitize_email($_POST['email']),
                'address' => sanitize_text_field($_POST['address']),
                'city' => sanitize_text_field($_POST['city']),
                'postcode' => sanitize_text_field($_POST['postcode']),
                'payment_method' => sanitize_text_field($_POST['payment_method']),
                'discount' => floatval($_POST['discount']),
                'tax' => floatval($_POST['tax']),
                'total' => floatval($_POST['total']),
            ];

            // Save the order with a new group ID
            $this->save_order_with_group($order_data);

            // Clear the cart after processing
            unset($_SESSION['cart']);

            // Redirect to order complete page
            wp_redirect(home_url('/order-complete')); // Ensure this URL matches your order complete page URL
            exit;
        }
    }

    function aheri_create_order_table() {
        global $wpdb;
        $table_name = $wpdb->prefix . 'aheri_orders'; // Table name

        $charset_collate = $wpdb->get_charset_collate();

        $sql = "CREATE TABLE $table_name (
            id mediumint(9) NOT NULL AUTO_INCREMENT,
            orderId mediumint(9) NOT NULL,
            order_grp_id int(11) NOT NULL,
            user_id bigint(20) UNSIGNED NOT NULL,
            first_name varchar(100) NOT NULL,
            last_name varchar(100) NOT NULL,
            email varchar(100) NOT NULL,
            address text NOT NULL,
            city varchar(100) NOT NULL,
            postcode varchar(20) NOT NULL,
            payment_method varchar(50) NOT NULL,
            items text NOT NULL,
            discount float NOT NULL,
            tax float NOT NULL,
            total float NOT NULL,
            order_date datetime DEFAULT CURRENT_TIMESTAMP NOT NULL,
            PRIMARY KEY  (id)
        ) $charset_collate;";

        require_once( ABSPATH . 'wp-admin/includes/upgrade.php' );
        dbDelta( $sql );
    }

    function aheri_update_order_table() {
        global $wpdb;
        $table_name = $wpdb->prefix . 'aheri_orders'; // Table name

        $charset_collate = $wpdb->get_charset_collate();

        // SQL query to add the new columns
        $sql = "ALTER TABLE $table_name
                ADD COLUMN product_title varchar(255) NOT NULL,
                ADD COLUMN status varchar(50) NOT NULL DEFAULT 'Received',
                ADD COLUMN session_id varchar(255) NULL;"; // Add session_id column

        require_once(ABSPATH . 'wp-admin/includes/upgrade.php');
        dbDelta($sql);
    }




}
