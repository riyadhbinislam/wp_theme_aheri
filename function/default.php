<?php

function aheri_add_to_cart() {
    if (isset($_POST['product_id']) && isset($_POST['quantity'])) {
        $product_id = intval($_POST['product_id']);
        $quantity = intval($_POST['quantity']);

        $cart = AHERI\inc\Cart::get_instance();
        $result = $cart->add_to_cart($product_id, $quantity);

        if ($result['success']) {
            wp_send_json_success($result['message']);
        } else {
            wp_send_json_error($result['message']);
        }
    } else {
        wp_send_json_error('Invalid request.');
    }
}

add_action('wp_ajax_aheri_add_to_cart', 'aheri_add_to_cart');
add_action('wp_ajax_nopriv_aheri_add_to_cart', 'aheri_add_to_cart');


function aheri_get_cart_count() {
    // Get Cart instance
    $cart_count = 0;
    if (isset($_SESSION['cart'])) {
        $cart_count = array_sum(array_column($_SESSION['cart'], 'quantity'));
    }

    wp_send_json_success(['cart_count' => $cart_count]);
}
add_action('wp_ajax_aheri_get_cart_count', 'aheri_get_cart_count');
add_action('wp_ajax_nopriv_aheri_get_cart_count', 'aheri_get_cart_count');

