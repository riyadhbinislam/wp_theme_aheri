<?php
/**
 * Template Name: Order Complete Page
 * @package Aheri
 */

get_header();

// Get the current user ID
$userId = get_current_user_id();

// Start a session to get the session ID
if (!session_id()) {
    session_start();
}

// Get the current session ID
$sessionId = session_id();

// Query to fetch order details
global $wpdb;

if ($userId) {
    // If user is logged in, fetch orders by user ID
    $query = $wpdb->prepare("
        SELECT * FROM {$wpdb->prefix}aheri_orders WHERE user_id = %d
    ", $userId);
} else {
    // If user is not logged in, fetch orders by session ID
    $query = $wpdb->prepare("
        SELECT * FROM {$wpdb->prefix}aheri_orders WHERE session_id = %s
    ", $sessionId);
}

$getOrder = $wpdb->get_results($query, ARRAY_A);

// Initialize an array to store orders grouped by their IDs
$orderGroups = [];

// Group orders by their unique order group ID
if ($getOrder) {
    foreach ($getOrder as $result) {
        $orderId = $result['orderId'];
        $orderGroups[$orderId][] = $result;
    }
}

// Sort the order groups by their keys (Order Group ID)
ksort($orderGroups);

?>

<section class="main-body">
    <div class="container">
        <h2>Orders List</h2>
        <h3 style="margin-bottom: 30px;">Below, you'll find a comprehensive list of all your orders.</h3>

        <!-- Display order details grouped by order group IDs -->
        <?php if ($orderGroups) : ?>
            <?php foreach ($orderGroups as $orderId => $orders) : ?>
                <div class="order-group">
                    <a class="view-order-btn" href="<?php echo esc_url(home_url('/orderstatus?orderid=' . $orderId)); ?>">
                        View Order No - <?php echo esc_html($orderId); ?>
                    </a>
                </div>
            <?php endforeach; ?>
        <?php else : ?>
            <p>No orders found.</p>
        <?php endif; ?>
    </div>
</section>

<?php

get_footer();