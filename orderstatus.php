<?php
/**
 * Template Name: Order Status Page
 * @package Aheri
 */

get_header();

// Start the session if not already started
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

// Function to get user details from the wp_users table by user ID
function get_user_details($user_id) {
    if ($user_id > 0) {
        $user_info = get_userdata($user_id);
        if ($user_info) {
            return array(
                'first_name' => $user_info->first_name,
                'last_name' => $user_info->last_name,
                'email' => $user_info->user_email,
                'address' => $user_info->address,
                'city' => $user_info->city,
                'postcode' => $user_info->postcode,
            );
        }
    }
    return array(
        'first_name' => '',
        'last_name' => '',
        'email' => '',
        'address' => '',
        'city' => '',
        'postcode' => '',
    );
}

// Function to get user details from the wp_aheri_orders table by order ID
function get_user_details_by_order($orderId) {
    global $wpdb;
    $table_name = $wpdb->prefix . 'aheri_orders';

    // Fetch user details based on order ID
    $order_data = $wpdb->get_row($wpdb->prepare("SELECT * FROM $table_name WHERE orderId = %d", $orderId), ARRAY_A);

    if ($order_data) {
        return array(
            'first_name' => $order_data['first_name'],
            'last_name' => $order_data['last_name'],
            'email' => $order_data['email'],
            'address' => $order_data['address'],
            'city' => $order_data['city'],
            'postcode' => $order_data['postcode'],
        );
    }

    return array(
        'first_name' => '',
        'last_name' => '',
        'email' => '',
        'address' => '',
        'city' => '',
        'postcode' => '',
    );
}

// Get the order ID from the query string
$orderId = isset($_GET['orderid']) ? intval($_GET['orderid']) : 0;

// Initialize user details
$userDetails = array(
    'first_name' => '',
    'last_name' => '',
    'email' => '',
    'address' => '',
    'city' => '',
    'postcode' => '',
);

if ($orderId > 0) {
    global $wpdb;
    $tbl_orders = $wpdb->prefix . 'aheri_orders';

    // Fetch the order details
    $orderDetails = $wpdb->get_results($wpdb->prepare("SELECT * FROM $tbl_orders WHERE orderId = %d", $orderId), ARRAY_A);

    if (!empty($orderDetails)) {
        // Get the user ID from the order
        $userId = isset($orderDetails[0]['user_id']) ? intval($orderDetails[0]['user_id']) : 0;

        if (is_user_logged_in() && $userId > 0) {
            // Fetch user details for logged-in user
            $userDetails = get_user_details($userId);
        } else {
            // Fetch user details from the order data
            $userDetails = get_user_details_by_order($orderId);
        }

        // Display order details
        echo '<section class="main-body">';
        echo '<div class="container">';
        echo '<h2>Order Details</h2>';

        foreach ($orderDetails as $order) {
            echo '<h3>Order ID: ' . esc_html($order['orderId']) . '</h3>';
            echo '<p>First Name: ' . esc_html($userDetails['first_name']) . '</p>';
            echo '<p>Last Name: ' . esc_html($userDetails['last_name']) . '</p>';
            echo '<p>Email: ' . esc_html($userDetails['email']) . '</p>';
            echo '<p>Address: ' . esc_html($userDetails['address']) . '</p>';
            echo '<p>City: ' . esc_html($userDetails['city']) . '</p>';
            echo '<p>Postcode: ' . esc_html($userDetails['postcode']) . '</p>';
            echo '<p>Payment Method: ' . esc_html($order['payment_method']) . '</p>';
            echo '<p>Order Date: ' . esc_html($order['order_date']) . '</p>';
            echo '<p>Total: $' . number_format(floatval($order['total']), 2) . '</p>';

            // Display order items
            $items = maybe_unserialize($order['items']);

            if (!empty($items)) {
                echo '<h4>Items:</h4>';
                echo '<ul>';

                foreach ($items as $product_id => $details) {
                    // Fetch product title from the product post type
                    $product = get_post($product_id);
                    $product_title = $product ? esc_html($product->post_title) : 'Unknown Product';
                    $price = isset($details['price']) ? floatval($details['price']) : 0;
                    $quantity = isset($details['quantity']) ? intval($details['quantity']) : 0;

                    echo '<li>';
                    echo esc_html($product_title) . ' - Quantity: ' . $quantity . ' - Price: $' . number_format($price, 2);
                    echo '</li>';
                }

                echo '</ul>';
            } else {
                echo '<p>No items found for this order.</p>';
            }

            echo '<hr>';
        }

        echo '</div>';
        echo '</section>';
    } else {
        echo '<p>No order details found for the provided Order ID.</p>';
    }
} else {
    echo '<p>Invalid Order ID.</p>';
}
?>

<?php
get_footer();
?>