<?php
/**
 * Homepage Template
 *
 * @package AHERI
 */

?>
<?php get_header();?>

<!-- intro -->
<section class="pt-3">
    <div class="container">
      <div class="row gx-3">
        <main class="col-lg-9">
          <div class="card-banner p-5 rounded-5" style="height: 350px;
          background:
          <?php
            if (isset($aheri_redux_opt['hero_background_color'])) {
                echo esc_html($aheri_redux_opt['hero_background_color']);
            } else {
                echo "#f8f8f8";
            }
            ?>
          ">
            <div style="max-width: 500px;">
              <h2 class="text-white">
                <?php echo $aheri_redux_opt['hero_title']; ?>
              </h2>
              <p class="text-white"><?php echo $aheri_redux_opt['hero_sub_title']; ?></p>
              <a class="btn btn-light shadow-0 text-primary"href="<?php echo $aheri_redux_opt['hero_button_url']; ?>"><?php echo $aheri_redux_opt['hero_button_text']; ?></a>
            </div>
          </div>
        </main>
        <aside class="col-lg-3">
          <div class="card-banner h-100 rounded-5" style="background-color: <?php
                  if(isset($aheri_redux_opt['add_background_color'])){
                    echo $aheri_redux_opt['add_background_color'];
                  }else{
                    echo "#F87217";
                    }
                ?>">
            <div class="card-body text-center pb-5">

              <h5 class="pt-5 text-white">
                <?php
                  if(isset($aheri_redux_opt['add_title'])){
                    echo $aheri_redux_opt['add_title'];
                  }else{
                    echo esc_html(get_theme_mod('gift_heading', 'Amazing Gifts'));
                    }
                ?>
              </h5>
              <p class="text-white">
                <?php
                  if(isset($aheri_redux_opt['add_sub_title'])){
                    echo $aheri_redux_opt['add_sub_title'];
                  }else{
                    echo esc_html(get_theme_mod('gift_text', 'No matter how far along you are in your sophistication'));
                    }
                ?>
              </p>
              <a class="btn btn-outline-light" href="<?php
                  if(isset($aheri_redux_opt['add_button_url'])){
                    echo $aheri_redux_opt['add_button_url'];
                  }else{
                    echo esc_html(get_theme_mod('hero_button_transparent_url', '#'));
                  }
                ?>">

                <?php
                  if(isset($aheri_redux_opt['add_button_text'])){
                    echo $aheri_redux_opt['add_button_text'];
                  }else{
                    echo esc_html(get_theme_mod('hero_button_transparent', '#'));
                  }
                ?>

              </a>

            </div>
          </div>
        </aside>
      </div>
      <!-- row //end -->
    </div>
    <!-- container end.// -->
  </section>
  <!-- intro -->

  <!-- category -->
  <section>
    <div class="container pt-5">
      <nav class="row gy-4">
        <div class="col-md-12">
          <div class="d-flex cate-list">


          <?php
    // Fetch all product categories except 'Uncategorized'
    $product_categories = get_terms(array(
        'taxonomy' => 'category', // Replace 'category' with your custom taxonomy name if different
        'hide_empty' => false,
        'exclude' => array(1), // Exclude the 'Uncategorized' category, typically has ID 1
    ));

    if (!empty($product_categories) && !is_wp_error($product_categories)) {

        foreach ($product_categories as $category) {
            // Get the category link
            $category_link = get_term_link($category);

            echo '<div class="single-category">';

            // Display the category link with image and name
            echo '<a href="' . esc_url($category_link) . '">';

            // Get the category icon image URL
            $icon_url = get_term_meta($category->term_id, 'category_icon_image', true);

            // Display the category icon image if available
            if ($icon_url) {
                // Use `esc_url` to sanitize the URL
                echo '<img class="btn-outline-secondary p-1 mb-1 mx-auto ripple-surface-dark" src="' . esc_url($icon_url) . '" alt="' . esc_attr($category->name) . '" style="max-width: 60px; max-height: 60px;">';
            }

            // Display the category name
            echo '<p class="text-dark">' . esc_html($category->name) . '</p>';

            echo '</a>';
            echo '</div>';
        }

    } else {
        echo '<p>No categories found or categories are empty.</p>';
    }
?>

          </div>
        </div>
      </nav>
    </div>
  </section>
  <!-- category -->

  <!-- Products -->
  <section>
    <div class="container my-5">
      <header class="mb-4">
        <h3>New products</h3>
      </header>

      <div class="row">

<?php
// Custom Query to get the products
$query = new WP_Query(array(
    'post_type' => 'product',
    'posts_per_page' => '', // Adjust the number as needed
));

if($query->have_posts()):
    while($query->have_posts()):
        $query->the_post();

        // Get custom fields and thumbnail
        $price = get_post_meta(get_the_ID(), '_product_price', true);
        $type = get_post_meta(get_the_ID(), '_product_type', true);
        $color = get_post_meta(get_the_ID(), '_product_color', true);
        $material = get_post_meta(get_the_ID(), '_product_material', true);
        $brand = get_post_meta(get_the_ID(), '_product_brand', true);
        $size = get_post_meta(get_the_ID(), '_product_size', true);
        $thumbnail_url = get_the_post_thumbnail_url(get_the_ID());
?>
        <div class="col-lg-3 col-md-6 col-sm-6 d-flex">
          <div class="card w-100 my-2 shadow-2-strong">
            <a href="<?php the_permalink(); ?>" class="">
              <div class="mask" style="height: 50px;">
                <div class="d-flex justify-content-start align-items-start h-100 m-2">
                  <h6><span class="badge bg-danger pt-1">New</span></h6>
                </div>
              </div>
              <img style="aspect-ratio: 1 / 1" class="card-img-top rounded-2" src="<?php echo esc_url($thumbnail_url); ?>" alt="<?php the_title_attribute(); ?>">
            </a>
            <div class="card-body d-flex flex-column">
              <div class=" d-flex align-items-center pt-3 px-0 pb-0 justify-content-between">
                <form method="post" action="" class="add-to-cart-form">
                  <input type="hidden" name="product_id" value="<?php echo get_the_ID(); ?>" />
                  <input type="number" name="quantity" class="input-number d-none" value="1" min="1" max="100" >
                  <input class="btn btn-primary shadow-0" type="submit" value="<?php _e('Add to Cart', 'aheri'); ?>" />
                </form>
                <a href="#!" class="btn btn-light border px-2 pt-2 float-end icon-hover"><i class="fas fa-heart fa-lg px-1 text-secondary"></i></a>
              </div>

              <h5 class="card-title">$<?php echo esc_html($price);?></h5>
              <p class="card-text mb-0"><?php the_title(); ?></p>
              <p class="text-muted">
              <?php echo esc_html($brand);?>
              </p>
            </div>
          </div>
        </div>

<?php
    endwhile;
    wp_reset_postdata();
endif;
?>

      </div>
    </div>
  </section>
  <!-- Products -->

  <!-- Features -->
  <section>
    <div class="container">
      <div class="card p-4" style="background-color:<?php
        if (isset($aheri_redux_opt['banner_background_color'])) {
          echo esc_html($aheri_redux_opt['banner_background_color']);
        } else {
          echo "#fff";
        }
        ?>">

        <div class="row align-items-center">
          <div class="col">
            <h4 class="mb-0" style="color: <?php
                if (isset($aheri_redux_opt['banner_title_color'])) {
                  echo esc_html($aheri_redux_opt['banner_title_color']);
              } else {
                  echo "#fff";
              }

            ?>">
              <?php
              if (isset($aheri_redux_opt['banner_title'])) {
                  echo esc_html($aheri_redux_opt['banner_title']);
              } else {
                  echo "Best products ---------";
              }
              ?>
            </h4>
            <p class="mb-0" style="color:
            <?php
              if (isset($aheri_redux_opt['banner_sub_title_color'])) {
                echo esc_html($aheri_redux_opt['banner_sub_title_color']);
              } else {
                echo "#fff";
              }?>">

            <?php
              if (isset($aheri_redux_opt['banner_sub_title'])) {
                  echo esc_html($aheri_redux_opt['banner_sub_title']);
              } else {
                  echo "Trendy products and text--------";
              }
              ?>
            </p>
          </div>
          <div class="col-auto"><a class="btn btn-white text-primary shadow-0" href="#">Discover</a></div>
        </div>
      </div>
    </div>
  </section>
  <!-- Features -->

  <!-- Recommended -->
  <section>
    <div class="container my-5">
      <header class="mb-4">
        <h3>Recommended</h3>
      </header>

      <div class="row">
        <div class="col-lg-3 col-md-6 col-sm-6">
          <div class="card my-2 shadow-0">
            <a href="#" class="">
              <img src="https://mdbootstrap.com/img/bootstrap-ecommerce/items/9.webp" class="card-img-top rounded-2" style="aspect-ratio: 1 / 1"/>
            </a>
            <div class="card-body p-0 pt-3">
              <a href="#!" class="btn btn-primary shadow-0 me-1" style="">Add to cart</a>
              <a href="#!" class="btn btn-light border px-2 pt-2 float-end icon-hover"><i class="fas fa-heart fa-lg px-1 text-secondary"></i></a>
              <h5 class="card-title">$17.00</h5>
              <p class="card-text mb-0">Blue jeans shorts for men</p>
              <p class="text-muted">
                Sizes: S, M, XL
              </p>
            </div>
          </div>
        </div>
        <div class="col-lg-3 col-md-6 col-sm-6">
          <div class="card my-2 shadow-0">
            <a href="#" class="">
              <img src="https://mdbootstrap.com/img/bootstrap-ecommerce/items/10.webp" class="card-img-top rounded-2"style="aspect-ratio: 1 / 1" />
            </a>
            <div class="card-body p-0 pt-2">
              <a href="#!" class="btn btn-primary shadow-0 me-1" style="">Add to cart</a>
              <a href="#!" class="btn btn-light border px-2 pt-2 float-end icon-hover"><i class="fas fa-heart fa-lg px-1 text-secondary"></i></a>
              <h5 class="card-title">$9.50</h5>
              <p class="card-text mb-0">Slim fit T-shirt for men</p>
              <p class="text-muted">
                Sizes: S, M, XL
              </p>
            </div>
          </div>
        </div>
        <div class="col-lg-3 col-md-6 col-sm-6">
          <div class="card my-2 shadow-0">
            <a href="#" class="">
              <img src="https://mdbootstrap.com/img/bootstrap-ecommerce/items/11.webp" class="card-img-top rounded-2" style="aspect-ratio: 1 / 1"/>
            </a>
            <div class="card-body p-0 pt-2">
              <a href="#!" class="btn btn-primary shadow-0 me-1" style="">Add to cart</a>
              <a href="#!" class="btn btn-light border px-2 pt-2 float-end icon-hover"><i class="fas fa-heart fa-lg px-1 text-secondary"></i></a>
              <h5 class="card-title">$29.95</h5>
              <p class="card-text mb-0">Modern product name here</p>
              <p class="text-muted">
                Sizes: S, M, XL
              </p>
            </div>
          </div>
        </div>
        <div class="col-lg-3 col-md-6 col-sm-6">
          <div class="card my-2 shadow-0">
            <a href="#" class="">
              <img src="https://mdbootstrap.com/img/bootstrap-ecommerce/items/12.webp" class="card-img-top rounded-2" style="aspect-ratio: 1 / 1"/>
            </a>
            <div class="card-body p-0 pt-2">
              <a href="#!" class="btn btn-primary shadow-0 me-1" style="">Add to cart</a>
              <a href="#!" class="btn btn-light border px-2 pt-2 float-end icon-hover"><i class="fas fa-heart fa-lg px-1 text-secondary"></i></a>
              <h5 class="card-title">$29.95</h5>
              <p class="card-text mb-0">Modern product name here</p>
              <p class="text-muted">
                Material: Jeans
              </p>
            </div>
          </div>
        </div>
      </div>
    </div>
  </section>
  <!-- Recommended -->

  <?php get_footer();?>