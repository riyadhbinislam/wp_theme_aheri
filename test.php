<?php
/**
 * Template Name: Order Complete Page
 * @package Aheri
 */

get_header();
?>

<section class="bg-light my-5">
    <div class="container">
        <div class="row">
            <div class="col-lg-12">
                <div class="card border shadow-0">
                    <div class="card-body text-center">
                        <?php
                        // Retrieve order details from session or database
                        if (isset($_SESSION['order'])):
                            $order = $_SESSION['order'];
                            $total_cart_price = 0;

                            echo '<h4 class="mb-4">' . __('Order Summary', 'aheri') . '</h4>';

                            foreach ($order['items'] as $product_id => $details):
                                $product = get_post($product_id);
                                if ($product):
                                    $product_title = esc_html($product->post_title);
                                    $quantity = isset($details['quantity']) ? intval($details['quantity']) : 0;
                                    $price = isset($details['price']) ? floatval($details['price']) : 0;
                                    $total_price = $quantity * $price;
                                    $total_cart_price += $total_price;
                                    ?>

                                    <div class="d-flex justify-content-between mb-2">
                                        <p><?php echo $product_title; ?> × <?php echo $quantity; ?></p>
                                        <p>$<?php echo number_format($total_price, 2); ?></p>
                                    </div>

                                    <?php
                                endif;
                            endforeach;

                            // Assuming discount and tax are stored in the order
                            $discount = isset($order['discount']) ? floatval($order['discount']) : 0;
                            $tax = isset($order['tax']) ? floatval($order['tax']) : 0;
                            $final_price = $total_cart_price - $discount + $tax;
                            ?>

                            <hr>

                            <div class="d-flex justify-content-between mb-2">
                                <p><?php _e('Subtotal:', 'aheri'); ?></p>
                                <p>$<?php echo number_format($total_cart_price, 2); ?></p>
                            </div>

                            <div class="d-flex justify-content-between mb-2">
                                <p><?php _e('Discount:', 'aheri'); ?></p>
                                <p class="text-success">- $<?php echo number_format($discount, 2); ?></p>
                            </div>

                            <div class="d-flex justify-content-between mb-2">
                                <p><?php _e('TAX:', 'aheri'); ?></p>
                                <p>$<?php echo number_format($tax, 2); ?></p>
                            </div>

                            <hr>

                            <div class="d-flex justify-content-between fw-bold mb-4">
                                <p><?php _e('Total Price:', 'aheri'); ?></p>
                                <p>$<?php echo number_format($final_price, 2); ?></p>
                            </div>

                        <?php else: ?>
                            <p><?php _e('Order details are not available.', 'aheri'); ?></p>
                        <?php endif; ?>

                        <?php
                        // Clear the cart
                        if (isset($_SESSION['cart'])) {
                            unset($_SESSION['cart']);
                        }
                        ?>

                        <a href="<?php echo esc_url(home_url('/')); ?>" class="btn btn-primary"><?php _e('Return to Home', 'aheri'); ?></a>
                    </div>
                </div>
            </div>
        </div>
    </div>
</section>

<?php
get_footer();

?>




