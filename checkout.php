<?php
/**
 * Template Name: Checkout Page
 * @package Aheri
 */

get_header();

use AHERI\inc\Cart;

// Instantiate the Cart class
$cart = Cart::get_instance();
?>

<?php
// Start the session if not already started
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

// Ensure cart session is initialized as an array
if (!isset($_SESSION['cart']) || !is_array($_SESSION['cart'])) {
    $_SESSION['cart'] = array();
}

// Capture form data (if necessary)
$order = array();
$order['items'] = $_SESSION['cart'];
$order['first_name'] = isset($_POST['first_name']) ? sanitize_text_field($_POST['first_name']) : '';
$order['last_name'] = isset($_POST['last_name']) ? sanitize_text_field($_POST['last_name']) : '';
$order['email'] = isset($_POST['email']) ? sanitize_email($_POST['email']) : '';
$order['phone'] = isset($_POST['phone']) ? sanitize_text_field($_POST['phone']) : '';
$order['address'] = isset($_POST['address']) ? sanitize_text_field($_POST['address']) : '';
$order['city'] = isset($_POST['city']) ? sanitize_text_field($_POST['city']) : '';
$order['postcode'] = isset($_POST['postcode']) ? sanitize_text_field($_POST['postcode']) : '';
$order['payment_method'] = isset($_POST['payment_method']) ? sanitize_text_field($_POST['payment_method']) : '';
$order['shipping'] = isset($_POST['shipping']) ? sanitize_text_field($_POST['shipping']) : '';

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

// Store order in session
$_SESSION['order'] = $order;
?>
<section class="bg-light py-5">
  <div class="container">
    <div class="row">
      <div class="col-xl-8 col-lg-8 mb-4">
     <?php if ( ! is_user_logged_in() ) {
        ?>
        <div class="card mb-4 border shadow-0">
          <div class="p-4 d-flex justify-content-between">
            <div class="">
              <h5>Have an account?</h5>
              <p class="mb-0 text-wrap ">Lorem ipsum dolor sit amet, consectetur adipisicing elit</p>
            </div>
            <div class="d-flex align-items-center justify-content-center flex-column flex-md-row">
              <a href="/registration/" class="btn btn-outline-primary me-0 me-md-2 mb-2 mb-md-0 w-100">Registration</a>
              <a href="/custom-login" class="btn btn-primary shadow-0 text-nowrap w-100">Sign in</a>
            </div>
          </div>
        </div>
    <?php }?>
<section class="bg-light my-5">
        <div class="container">
            <div class="row">
                <!-- Checkout -->
            <div class="card shadow-0 border">
            <div class="p-4">
                <h4 class="card-title mb-4"><?php _e('Billing Details', 'aheri'); ?></h4>

<?php if ( ! is_user_logged_in() ) {?>
                <h5 class="card-title mb-3">Guest checkout</h5>
                <form action="<?php echo esc_url(site_url('/process')); ?>" method="POST">
                <div class="row">
                    <div class="col-md-6">
                        <div class="mb-3">
                            <label for="first_name"><?php _e('First Name', 'aheri'); ?></label>
                            <input type="text" name="first_name" id="first_name" class="form-control" required>
                        </div>
                    </div>
                    <div class="col-md-6">
                        <div class="mb-3">
                            <label for="last_name"><?php _e('Last Name', 'aheri'); ?></label>
                            <input type="text" name="last_name" id="last_name" class="form-control" required>
                        </div>
                    </div>
                </div>
                <div class="row">
                    <div class="col-md-6">
                        <div class="mb-3">
                            <label for="email"><?php _e('Email Address', 'aheri'); ?></label>
                            <input type="email" name="email" id="email" class="form-control" required>
                        </div>
                    </div>
                    <div class="col-md-6">
                        <div class="mb-3">
                            <label for="tel"><?php _e('Phone Number', 'aheri'); ?></label>
                            <input type="tel" name="phone" id="phone" class="form-control" required>
                        </div>
                    </div>
                </div>

                <div class="form-check">
                <input class="form-check-input" type="checkbox" value="" id="flexCheckDefault" />
                <label class="form-check-label" for="flexCheckDefault">Keep me up to date on news</label>
                </div>

                <hr class="my-4" />

                <h5 class="card-title mb-3">Shipping info</h5>

                <div class="row mb-3">
                <div class="col-lg-4 mb-3">
                    <!-- Default checked radio -->
                    <div class="form-check h-100 border rounded-3">
                    <div class="p-3">
                        <input class="form-check-input" type="radio" name="shipping" id="flexRadioDefault1" checked />
                        <label class="form-check-label" for="flexRadioDefault1">
                        Express delivery <br />
                        <small class="text-muted">3-4 days via Fedex </small>
                        </label>
                    </div>
                    </div>
                </div>
                <div class="col-lg-4 mb-3">
                    <!-- Default radio -->
                    <div class="form-check h-100 border rounded-3">
                    <div class="p-3">
                        <input class="form-check-input" type="radio" name="shipping" id="flexRadioDefault2" />
                        <label class="form-check-label" for="flexRadioDefault2">
                        Post office <br />
                        <small class="text-muted">20-30 days via post </small>
                        </label>
                    </div>
                    </div>
                </div>
                <div class="col-lg-4 mb-3">
                    <!-- Default radio -->
                    <div class="form-check h-100 border rounded-3">
                    <div class="p-3">
                        <input class="form-check-input" type="radio" name="shipping" id="flexRadioDefault3" />
                        <label class="form-check-label" for="flexRadioDefault3">
                        Self pick-up <br />
                        <small class="text-muted">Come to our shop </small>
                        </label>
                    </div>
                    </div>
                </div>
                </div>

                <div class="row">
                        <div class="mb-3 col-sm-4">
                            <label for="address"><?php _e('Address', 'aheri'); ?></label>
                            <input type="text" name="address" id="address" class="form-control" required>
                        </div>
                        <div class="mb-3 col-sm-4">
                            <label for="city"><?php _e('City', 'aheri'); ?></label>
                            <input type="text" name="city" id="city" class="form-control" required>
                        </div>
                        <div class="mb-3 col-sm-4">
                            <label for="postcode"><?php _e('Postcode', 'aheri'); ?></label>
                            <input type="text" name="postcode" id="postcode" class="form-control" required>
                        </div>
                </div>

                <div class="form-check mb-3">
                <input class="form-check-input" type="checkbox" value="" id="flexCheckDefault1" />
                <label class="form-check-label" for="flexCheckDefault1">Save this address</label>
                </div>

                <div class="mb-3">
                <p class="mb-0">Message to seller</p>
                <div class="form-outline">
                    <textarea class="form-control border" id="textAreaExample1" rows="2"></textarea>
                </div>
                </div>
                <!-- Payment Method -->
                <div class="mb-3">
                    <h5><?php _e('Payment Method', 'aheri'); ?></h5>
                    <div class="form-check">
                        <input class="form-check-input" type="radio" name="payment_method" id="payment_method_cod" value="cod" checked>
                        <label class="form-check-label" for="payment_method_cod">
                            <?php _e('Cash on Delivery', 'aheri'); ?>
                        </label>
                    </div>
                    <div class="form-check">
                        <input class="form-check-input" type="radio" name="payment_method" id="payment_method_card" value="card">
                        <label class="form-check-label" for="payment_method_card">
                            <?php _e('Credit Card', 'aheri'); ?>
                        </label>
                    </div>
                </div>

                <button type="submit" class="btn btn-primary w-100"><?php _e('Place Order', 'aheri'); ?></button>
<?php }else{?>

    <form action="<?php echo esc_url(site_url('/process')); ?>" method="POST">
                <h5 class="card-title mb-3">Shipping info</h5>

                <div class="row mb-3">
                <div class="col-lg-4 mb-3">
                    <!-- Default checked radio -->
                    <div class="form-check h-100 border rounded-3">
                    <div class="p-3">
                        <input class="form-check-input" type="radio" name="shipping" id="flexRadioDefault1" checked />
                        <label class="form-check-label" for="flexRadioDefault1">
                        Express delivery <br />
                        <small class="text-muted">3-4 days via Fedex </small>
                        </label>
                    </div>
                    </div>
                </div>
                <div class="col-lg-4 mb-3">
                    <!-- Default radio -->
                    <div class="form-check h-100 border rounded-3">
                    <div class="p-3">
                        <input class="form-check-input" type="radio" name="shipping" id="flexRadioDefault2" />
                        <label class="form-check-label" for="flexRadioDefault2">
                        Post office <br />
                        <small class="text-muted">20-30 days via post </small>
                        </label>
                    </div>
                    </div>
                </div>
                <div class="col-lg-4 mb-3">
                    <!-- Default radio -->
                    <div class="form-check h-100 border rounded-3">
                    <div class="p-3">
                        <input class="form-check-input" type="radio" name="shipping" id="flexRadioDefault3" />
                        <label class="form-check-label" for="flexRadioDefault3">
                        Self pick-up <br />
                        <small class="text-muted">Come to our shop </small>
                        </label>
                    </div>
                    </div>
                </div>
                </div>

                <!-- <div class="row">
                        <div class="mb-3 col-sm-4">
                            <label for="address"><?php //_e('Address', 'aheri'); ?></label>
                            <input type="text" name="address" id="address" class="form-control" required>
                        </div>
                        <div class="mb-3 col-sm-4">
                            <label for="city"><?php //_e('City', 'aheri'); ?></label>
                            <input type="text" name="city" id="city" class="form-control" required>
                        </div>
                        <div class="mb-3 col-sm-4">
                            <label for="postcode"><?php //_e('Postcode', 'aheri'); ?></label>
                            <input type="text" name="postcode" id="postcode" class="form-control" required>
                        </div>
                </div> -->

                <!-- <div class="form-check mb-3">
                    <input class="form-check-input" type="checkbox" value="" id="flexCheckDefault1" />
                    <label class="form-check-label" for="flexCheckDefault1">Save this address</label>
                </div> -->

                <div class="mb-3">
                <p class="mb-0">Message to seller</p>
                <div class="form-outline">
                    <textarea class="form-control border" id="textAreaExample1" rows="2"></textarea>
                </div>
                </div>
                <!-- Payment Method -->
                <div class="mb-3">
                    <h5><?php _e('Payment Method', 'aheri'); ?></h5>
                    <div class="form-check">
                        <input class="form-check-input" type="radio" name="payment_method" id="payment_method_cod" value="cod" checked>
                        <label class="form-check-label" for="payment_method_cod">
                            <?php _e('Cash on Delivery', 'aheri'); ?>
                        </label>
                    </div>
                    <div class="form-check">
                        <input class="form-check-input" type="radio" name="payment_method" id="payment_method_card" value="card">
                        <label class="form-check-label" for="payment_method_card">
                            <?php _e('Credit Card', 'aheri'); ?>
                        </label>
                    </div>
                </div>
                <button type="submit" class="btn btn-primary w-100"><?php _e('Place Order', 'aheri'); ?></button>

<?php } ?>

            </div>
            </div>
            </div>
        </div>

      </div>
      <div class="col-xl-4 col-lg-4 d-flex justify-content-center justify-content-lg-end">
      <section class="bg-light my-5">
        <div class="card shadow-0 border">
        <div class="p-4">
        <div class="ms-lg-4 mt-4 mt-lg-0" style="max-width: 320px;">
          <h4 class="mb-3">Summary</h4>

          <?php
            // Check if cart has items
            if (!empty($_SESSION['cart'])):
                $total_cart_price = 0;

                foreach ($_SESSION['cart'] as $product_id => $details):
                    $product = get_post($product_id);
                    if ($product):
                        $product_title = esc_html($product->post_title);
                        $quantity = isset($details['quantity']) ? intval($details['quantity']) : 0;
                        $price = isset($details['price']) ? floatval($details['price']) : 0;
                        $total_price = $quantity * $price;
                        $total_cart_price += $total_price;
                        ?>

                        <div class="d-flex justify-content-between">
                            <p><?php echo $product_title; ?> × <?php echo $quantity; ?></p>
                            <p>$<?php echo number_format($total_price, 2); ?></p>
                        </div>

                        <?php
                    endif;
                endforeach;

                // Assuming discount and tax are the same as in the cart page
                $discount = 60.00;
                $tax = 14.00;
                $final_price = $total_cart_price - $discount + $tax;
            ?>


            <div class="d-flex justify-content-between">
                <p class="mb-2 font-weight-bold"><strong><?php _e('Total price:', 'aheri'); ?></strong></p>
                <p class="mb-2">$<?php echo number_format($total_cart_price, 2); ?></p>
            </div>

            <div class="d-flex justify-content-between">
                <p class="mb-2 font-weight-bold"><strong><?php _e('Discount:', 'aheri'); ?></strong></p>
                <p class="mb-2 text-danger">- $<?php echo number_format($discount, 2); ?></p>
            </div>

            <div class="d-flex justify-content-between">
                <p class="mb-2 font-weight-bold"><strong><?php _e('TAX:', 'aheri'); ?>/Shipping cost:</strong></p>
                <p class="mb-2">+ $<?php echo number_format($tax, 2); ?></p>
            </div>

            <hr />

            <div class="d-flex justify-content-between">
                <p class="mb-2 font-weight-bold"><strong><?php _e('Total price:', 'aheri'); ?>:</strong></p>
                <p class="mb-2 fw-bold">$<?php echo number_format($final_price, 2); ?></p>
            </div>

            <?php else: ?>
            <p class="text-center"><?php _e('Your cart is empty', 'aheri'); ?></p>
            <?php endif; ?>

        </div>
     </section>
        </div>
      </div>
        </div>
      </div>
    </div>
  </div>
</section>

<?php
get_footer();
?>
