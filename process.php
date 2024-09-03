<?php
/**
 * Template Name: Process Page
 * @package Aheri
 */

get_header();

// Start the session if not already started
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

// Get the current user ID
$userId = get_current_user_id();

// Generate or retrieve session ID
if (!$userId) {
    // If session_id is not set in session, create one
    if (!isset($_SESSION['session_id']) || empty($_SESSION['session_id'])) {
        $_SESSION['session_id'] = session_id();
    }
    $session_id = $_SESSION['session_id'];
} else {
    $session_id = null; // Session ID not needed for logged-in users
}

// Check if cart data exists in session
if (isset($_SESSION['cart']) && !empty($_SESSION['cart'])) {
    // Capture form data
    $order = array();
    $order['items'] = $_SESSION['cart'];
    $order['first_name'] = isset($_POST['first_name']) ? sanitize_text_field($_POST['first_name']) : '';
    $order['last_name'] = isset($_POST['last_name']) ? sanitize_text_field($_POST['last_name']) : '';
    $order['email'] = isset($_POST['email']) ? sanitize_email($_POST['email']) : '';
    $order['address'] = isset($_POST['address']) ? sanitize_text_field($_POST['address']) : '';
    $order['city'] = isset($_POST['city']) ? sanitize_text_field($_POST['city']) : '';
    $order['postcode'] = isset($_POST['postcode']) ? sanitize_text_field($_POST['postcode']) : '';
    $order['payment_method'] = isset($_POST['payment_method']) ? sanitize_text_field($_POST['payment_method']) : '';

    // Calculate totals (example)
    $total_cart_price = 0;
    foreach ($order['items'] as $product_id => $details) {
        $price = isset($details['price']) ? floatval($details['price']) : 0;
        $quantity = isset($details['quantity']) ? intval($details['quantity']) : 0;
        $total_cart_price += $price * $quantity;
    }

    // Add any discounts or taxes if applicable
    $order['discount'] = 60.00; // Example discount
    $order['tax'] = 14.00; // Example tax
    $order['total'] = $total_cart_price - $order['discount'] + $order['tax'];

    // Generate unique random integer for orderId and order_grp_id
    global $wpdb;
    do {
        $orderId = mt_rand(100000, 999999);
        $order_grp_id = mt_rand(100000, 999999);

        // Check if these IDs already exist in the database
        $existing_order = $wpdb->get_var($wpdb->prepare("SELECT COUNT(*) FROM {$wpdb->prefix}aheri_orders WHERE orderId = %d OR order_grp_id = %d", $orderId, $order_grp_id));
    } while ($existing_order > 0);

    $order['orderId'] = $orderId;
    $order['order_grp_id'] = $order_grp_id;
    $order['session_id'] = $session_id;

    // Store order in session
    $_SESSION['order'] = $order;
} else {
    // If cart is empty or not set, handle the error
    $_SESSION['order'] = null;
    echo '<p>' . __('Your cart is empty. Please add items to your cart before proceeding.', 'aheri') . '</p>';
}

?>

<section class="bg-light my-5">
    <div class="container">
        <div class="row">
            <div class="col-lg-12">
                <div class="card border shadow-0">
                    <div class="card-body text-center">
                        <h2 class="card-title mb-4"><?php _e('Thank You for Your Order!', 'aheri'); ?></h2>
                        <?php if ($_SESSION['order']): ?>
                            <p class="mb-4"><?php _e('Your order has been successfully placed. You will receive an email confirmation shortly.', 'aheri'); ?></p>

                            <?php
                                // Save order to the database
                                global $wpdb;
                                $table_name = $wpdb->prefix . 'aheri_orders';

                                $wpdb->insert(
                                    $table_name,
                                    array(
                                        'orderId'        => $orderId,
                                        'order_grp_id'   => $order_grp_id,
                                        'user_id'        => $userId,
                                        'session_id'     => $session_id,
                                        'first_name'     => $_SESSION['order']['first_name'],
                                        'last_name'      => $_SESSION['order']['last_name'],
                                        'email'          => $_SESSION['order']['email'],
                                        'address'        => $_SESSION['order']['address'],
                                        'city'           => $_SESSION['order']['city'],
                                        'postcode'       => $_SESSION['order']['postcode'],
                                        'payment_method' => $_SESSION['order']['payment_method'],
                                        'items'          => maybe_serialize($_SESSION['order']['items']),
                                        'discount'       => $_SESSION['order']['discount'],
                                        'tax'            => $_SESSION['order']['tax'],
                                        'total'          => $_SESSION['order']['total'],
                                        'order_date'     => current_time('mysql')
                                    )
                                );

                                // Clear the cart after saving the order
                                unset($_SESSION['cart']);
                                unset($_SESSION['order']);
                            ?>
                        <?php endif; ?>
                        <a href="<?php echo esc_url(home_url('/order')); ?>" class="btn btn-primary"><?php _e('Order Complete', 'aheri'); ?></a>
                    </div>
                </div>
            </div>
        </div>
    </div>
</section>

<?php
get_footer();
?>