<?php
/**
 * Single Product
 *
 * @package AHERI
 */

get_header();

?>

<section class="py-5">
  <div class="container">
<?php
while ( have_posts() ) : the_post();

    $price = get_post_meta(get_the_ID(), '_product_price', true);
    $type = get_post_meta(get_the_ID(), '_product_type', true);
    $color = get_post_meta(get_the_ID(), '_product_color', true);
    $material = get_post_meta(get_the_ID(), '_product_material', true);
    $brand = get_post_meta(get_the_ID(), '_product_brand', true);
    $size = get_post_meta(get_the_ID(), '_product_size', true);
    $thumbnail_url = get_the_post_thumbnail_url(get_the_ID());
;?>
    <div class="row gx-5">
      <aside class="col-lg-6">
        <div class="border rounded-4 mb-3 d-flex justify-content-center">
            <a data-fslightbox="mygallery" class="rounded-4" target="_blank" data-type="image" href="<?php echo esc_url($thumbnail_url); ?>">
                <img style="max-width: 100%; max-height: 100vh; margin: auto;" class="rounded-4 fit" src="<?php echo esc_url($thumbnail_url); ?>" alt="<?php the_title_attribute(); ?>">
            </a>
        </div>

        <div class="d-flex justify-content-center mb-3">
          <a data-fslightbox="mygalley" class="border mx-1 rounded-2" target="_blank" data-type="image" href="https://mdbcdn.b-cdn.net/img/bootstrap-ecommerce/items/detail1/big1.webp">
            <img width="60" height="60" class="rounded-2" src="https://mdbcdn.b-cdn.net/img/bootstrap-ecommerce/items/detail1/big1.webp">
          </a>
          <a data-fslightbox="mygalley" class="border mx-1 rounded-2" target="_blank" data-type="image" href="https://mdbcdn.b-cdn.net/img/bootstrap-ecommerce/items/detail1/big2.webp">
            <img width="60" height="60" class="rounded-2" src="https://mdbcdn.b-cdn.net/img/bootstrap-ecommerce/items/detail1/big2.webp">
          </a>
          <a data-fslightbox="mygalley" class="border mx-1 rounded-2" target="_blank" data-type="image" href="https://mdbcdn.b-cdn.net/img/bootstrap-ecommerce/items/detail1/big3.webp">
            <img width="60" height="60" class="rounded-2" src="https://mdbcdn.b-cdn.net/img/bootstrap-ecommerce/items/detail1/big3.webp">
          </a>
          <a data-fslightbox="mygalley" class="border mx-1 rounded-2" target="_blank" data-type="image" href="https://mdbcdn.b-cdn.net/img/bootstrap-ecommerce/items/detail1/big4.webp">
            <img width="60" height="60" class="rounded-2" src="https://mdbcdn.b-cdn.net/img/bootstrap-ecommerce/items/detail1/big4.webp">
          </a>
          <a data-fslightbox="mygalley" class="border mx-1 rounded-2" target="_blank" data-type="image" href="https://mdbcdn.b-cdn.net/img/bootstrap-ecommerce/items/detail1/big.webp">
            <img width="60" height="60" class="rounded-2" src="https://mdbcdn.b-cdn.net/img/bootstrap-ecommerce/items/detail1/big.webp">
          </a>
        </div>
        <!-- thumbs-wrap.// -->
        <!-- gallery-wrap .end// -->
      </aside>
      <main class="col-lg-6">
        <div class="ps-lg-3">
           <h4 class="title text-dark"><?php the_title();?></h4>
          <div class="d-flex flex-row my-3">
            <div class="text-warning mb-1 me-2">
              <i class="fa fa-star"></i>
              <i class="fa fa-star"></i>
              <i class="fa fa-star"></i>
              <i class="fa fa-star"></i>
              <i class="fas fa-star-half-alt"></i>
              <span class="ms-1">
                4.5
              </span>
            </div>
            <span class="text-muted"><i class="fas fa-shopping-basket fa-sm mx-1"></i>154 orders</span>
            <span class="text-success ms-2">In stock</span>
          </div>

          <div class="mb-3">
            <span class="h5">$<?php echo esc_html($price);?></span>
            <span class="text-muted">/per box</span>
          </div>

          <p>
            <?php
                the_content();
            ?>
          </p>

          <div class="row">
            <dt class="col-3">Type:</dt>
            <dd class="col-9"><?php echo esc_html($type);?></dd>

            <dt class="col-3">Color:</dt>
            <dd class="col-9"><?php echo esc_html($color);?></dd>

            <dt class="col-3">Material:</dt>
            <dd class="col-9"><?php echo esc_html($material);?></dd>

            <dt class="col-3">Brand:</dt>
            <dd class="col-9"><?php echo esc_html($brand);?></dd>
          </div>

          <hr>

          <form method="post" action="" class="add-to-cart-form" enctype='multipart/form-data'>

            <input type="hidden" name="product_id" value="<?php echo get_the_ID(); ?>" />

            <div class="row mb-6 mr-3" >

              <div class="col-md-4 col-6">
                <label class="mb-2">Size</label>
                <select type="select" id="size" name="size"class="form-select border border-secondary" style="height: 35px; min-width:186px">
                  <option>Select Size</option>
                  <option value="Small">Small</option>
                  <option value="Medium">Medium</option>
                  <option value="Large">Large</option>
                </select>
              </div>

              <div class="col-md-4 col-6" style="margin-left: 30px !important;">
                <label class="mb-2 d-block ">Quantity</label>
                <input type="number" id="quantity" name="quantity"style="height: 35px; min-width:186px" class=" text-center input-number border rounded border-secondary" value="1" min="1" max="100" >
              </div>

            </div>

            <input class="btn btn-primary shadow-0" type="submit" value="<?php _e('Add to Cart', 'aheri'); ?>" />


          </form>

        </div>
      </main>
    </div>

<?php endwhile; ?>
  </div>
</section>

<?php
get_footer();
// wp_redirect(get_permalink());
exit;
?>