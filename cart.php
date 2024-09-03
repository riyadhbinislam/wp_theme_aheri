<?php
/**
 * Template Name: Cart Page
 * @package Aheri
 */

get_header();

use AHERI\inc\Cart;

// Instantiate the Cart class
$cart = Cart::get_instance();
?>

<!-- Cart Items -->

<section class="bg-light my-5">
  <div class="container">
    <div class="row">
      <!-- cart -->
      <div class="col-lg-9">
        <div class="card border shadow-0">
          <div class="m-4">
            <h4 class="card-title mb-4"><?php _e('Your shopping cart', 'aheri'); ?></h4>
<?php
            // Ensure cart session is initialized
                if (!isset($_SESSION['cart'])) {
                    $_SESSION['cart'] = array();
                }

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

                            // Get the product thumbnail URL
                            $thumbnail_url = get_the_post_thumbnail_url($product_id, 'thumbnail');

                            // Safely get 'type' and 'size' from the post meta
                            $type = get_post_meta($product_id, '_product_type', true);
                            $size = get_post_meta($product_id, '_product_size', true);

                            // Default values if meta fields are not set
                            $type = !empty($type) ? esc_html($type) : 'Unknown Type';
                            $size = !empty($size) ? esc_html($size) : 'Unknown Size';
                            ?>

                            <div class="row gy-3 mb-4">
                            <div class="col-lg-5">
                                <div class="me-lg-5">
                                <div class="d-flex">
                                    <img src="<?php echo esc_url($thumbnail_url); ?>" class="border rounded me-3" style="width: 96px; height: 96px;">
                                    <div>
                                    <a href="#" class="nav-link"><?php echo $product_title; ?></a>
                                    <p class="text-muted"><?php echo $type; ?>, <?php echo $size; ?></p>
                                    </div>
                                </div>
                                </div>
                            </div>
                            <div class="col-lg-2 col-sm-6 col-6 d-flex flex-row flex-lg-column flex-xl-row text-nowrap">
                                <span class="h6"><?php echo $quantity; ?>×</span>
                                <div>
                                  <span class="h6">$<?php echo number_format($price, 2); ?> / per item</span> <br>
                                  <small class="text-muted text-nowrap">$<?php echo number_format($total_price, 2); ?></small>
                                </div>
                            </div>
                            <div class="col-lg col-sm-6 d-flex justify-content-sm-center justify-content-md-start justify-content-lg-center justify-content-xl-end mb-2">
                                <div class="float-md-end">
                                <a href="#!" class="btn btn-light border px-2 icon-hover-primary"><i class="fas fa-heart fa-lg px-1 text-secondary"></i></a>
                                <a href="?remove_from_cart=<?php echo esc_attr($product_id); ?>" class="btn btn-light border text-danger icon-hover-danger"> Remove</a>
                                </div>
                            </div>
                            </div>

                            <?php
                        endif;
                    endforeach;

                else:
                    echo '<p class="text-center">' . __('Your cart is empty', 'aheri') . '</p>';
                endif;
?>
          </div>

          <div class="border-top pt-4 mx-4 mb-4">
            <p><i class="fas fa-truck text-muted fa-lg"></i> Free Delivery within 1-2 weeks</p>
            <p class="text-muted">
              Lorem ipsum dolor sit amet, consectetur adipisicing elit, sed do eiusmod tempor incididunt ut labore et dolore magna aliqua. Ut enim ad minim veniam, quis nostrud exercitation ullamco laboris nisi ut aliquip.
            </p>
          </div>
        </div>
      </div>
      <!-- cart -->
      <!-- summary -->
      <div class="col-lg-3">
        <div class="card mb-3 border shadow-0">
          <div class="card-body">
            <form>
              <div class="form-group">
                <label class="form-label">Have coupon?</label>
                <div class="input-group">
                  <input type="text" class="form-control border" name="coupon_code" placeholder="Coupon code">
                  <button class="btn btn-light border">Apply</button>
                </div>
              </div>
            </form>
          </div>
        </div>
        <div class="card shadow-0 border">
          <div class="card-body">
            <div class="d-flex justify-content-between">
              <p class="mb-2">Total price:</p>
              <p class="mb-2">
                  $<?php
                  // Assuming $total_cart_price is calculated elsewhere and is available here
                  if (!empty($total_cart_price) && is_numeric($total_cart_price)) {
                      echo number_format($total_cart_price, 2);
                  } else {
                      echo '0.00';
                  }
                  ?>
              </p>

            </div>
            <div class="d-flex justify-content-between">
              <p class="mb-2">Discount:</p>
              <p class="mb-2 text-success">-$60.00</p>
            </div>
            <div class="d-flex justify-content-between">
              <p class="mb-2">TAX:</p>
              <p class="mb-2">$14.00</p>
            </div>
            <hr>
            <div class="d-flex justify-content-between">
              <p class="mb-2">Total price:</p>
              <p class="mb-2 fw-bold">$
      <?php
      // Ensure $total_cart_price is defined and is a valid numeric value
      $total_cart_price = isset($total_cart_price) && is_numeric($total_cart_price) ? $total_cart_price : 0;

      // Calculate the final price with discount and additional charges
      $final_price = $total_cart_price - 60 + 14;

      // Format the final price to 2 decimal places
      echo number_format($final_price, 2);
      ?>
                </p>
            </div>

            <div class="mt-3">
              <a href="<?php echo esc_url(site_url('/checkout')); ?>" class="btn btn-success w-100 shadow-0 mb-2"><?php _e('Proceed to Checkout', 'aheri'); ?></a>
              <a href="<?php echo esc_url(site_url('/shop')); ?>" class="btn btn-light w-100 border mt-2">Back to shop</a>
            </div>
          </div>
        </div>
      </div>
      <!-- summary -->
    </div>
  </div>
</section>
    </div>
  </div>
</section>

<!-- Recommended -->

<section>
  <div class="container my-5">
    <header class="mb-4">
      <h3>Recommended items</h3>
    </header>

    <div class="row">
      <div class="col-lg-3 col-md-6 col-sm-6">
        <div class="card px-4 border shadow-0 mb-4 mb-lg-0">
          <div class="mask px-2" style="height: 50px;">
            <div class="d-flex justify-content-between">
              <h6><span class="badge bg-danger pt-1 mt-3 ms-2">New</span></h6>
              <a href="#"><i class="fas fa-heart text-primary fa-lg float-end pt-3 m-2"></i></a>
            </div>
          </div>
          <a href="#" class="">
            <img src="https://mdbootstrap.com/img/bootstrap-ecommerce/items/7.webp" class="card-img-top rounded-2">
          </a>
          <div class="card-body d-flex flex-column pt-3 border-top">
            <a href="#" class="nav-link">Gaming Headset with Mic</a>
            <div class="price-wrap mb-2">
              <strong class="">$18.95</strong>
              <del class="">$24.99</del>
            </div>
            <div class="card-footer d-flex align-items-end pt-3 px-0 pb-0 mt-auto">
              <a href="#" class="btn btn-outline-primary w-100">Add to cart</a>
            </div>
          </div>
        </div>
      </div>
      <div class="col-lg-3 col-md-6 col-sm-6">
        <div class="card px-4 border shadow-0 mb-4 mb-lg-0">
          <div class="mask px-2" style="height: 50px;">
            <a href="#"><i class="fas fa-heart text-primary fa-lg float-end pt-3 m-2"></i></a>
          </div>
          <a href="#" class="">
            <img src="https://mdbootstrap.com/img/bootstrap-ecommerce/items/5.webp" class="card-img-top rounded-2">
          </a>
          <div class="card-body d-flex flex-column pt-3 border-top">
            <a href="#" class="nav-link">Apple Watch Series 1 Sport </a>
            <div class="price-wrap mb-2">
              <strong class="">$120.00</strong>
            </div>
            <div class="card-footer d-flex align-items-end pt-3 px-0 pb-0 mt-auto">
              <a href="#" class="btn btn-outline-primary w-100">Add to cart</a>
            </div>
          </div>
        </div>
      </div>
      <div class="col-lg-3 col-md-6 col-sm-6">
        <div class="card px-4 border shadow-0">
          <div class="mask px-2" style="height: 50px;">
            <a href="#"><i class="fas fa-heart text-primary fa-lg float-end pt-3 m-2"></i></a>
          </div>
          <a href="#" class="">
            <img src="https://mdbootstrap.com/img/bootstrap-ecommerce/items/9.webp" class="card-img-top rounded-2">
          </a>
          <div class="card-body d-flex flex-column pt-3 border-top">
            <a href="#" class="nav-link">Men's Denim Jeans Shorts</a>
            <div class="price-wrap mb-2">
              <strong class="">$80.50</strong>
            </div>
            <div class="card-footer d-flex align-items-end pt-3 px-0 pb-0 mt-auto">
              <a href="#" class="btn btn-outline-primary w-100">Add to cart</a>
            </div>
          </div>
        </div>
      </div>
      <div class="col-lg-3 col-md-6 col-sm-6">
        <div class="card px-4 border shadow-0">
          <div class="mask px-2" style="height: 50px;">
            <a href="#"><i class="fas fa-heart text-primary fa-lg float-end pt-3 m-2"></i></a>
          </div>
          <a href="#" class="">
            <img src="https://mdbootstrap.com/img/bootstrap-ecommerce/items/10.webp" class="card-img-top rounded-2">
          </a>
          <div class="card-body d-flex flex-column pt-3 border-top">
            <a href="#" class="nav-link">Mens T-shirt Cotton Base Layer Slim fit </a>
            <div class="price-wrap mb-2">
              <strong class="">$13.90</strong>
            </div>
            <div class="card-footer d-flex align-items-end pt-3 px-0 pb-0 mt-auto">
              <a href="#" class="btn btn-outline-primary w-100">Add to cart</a>
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
