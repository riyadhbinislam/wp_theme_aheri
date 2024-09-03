<?php
/**
 * Header Navigation Template
 *
 * @package AHERI
 */

$menu_class = \AHERI\inc\Menus::get_instance();
$header_menu_id = $menu_class->get_menu_id('aheri-header-menu');
$header_menus = wp_get_nav_menu_items($header_menu_id);

$top_menu_id = $menu_class->get_menu_id('aheri-top-menu');
$top_menus = wp_get_nav_menu_items($top_menu_id);

?>

<!-- Jumbotron -->
<div class="p-3 text-center bg-white border-bottom">
    <div class="container">
        <div class="row gy-3 d-flex ">
            <!-- Left elements -->
            <div class="col-lg-2 col-sm-4 col-4">
                <a class="navbar-brand logo_h" href="<?php echo home_url( '/' ) ;?>">
                    <?php
                        if ( isset( $aheri_redux_opt['header_logo']['url'] ) ) {
                            echo '<img src="' . esc_url( $aheri_redux_opt['header_logo']['url'] ) . '" alt="' . esc_attr( $aheri_redux_opt['header_logo_text'] ) . '">';
                        } elseif ( function_exists( 'the_custom_logo' ) ) {
                                the_custom_logo();
                            }else {
                                echo esc_html( $aheri_redux_opt['header_logo_text'] );
                        }
                    ?>
                </a>
            </div>
            <!-- Left elements -->

            <!-- Center elements -->
            <div class="col-lg-5 col-md-12 col-12">
                <form role="search" method="get" class="input-group float-center search-form" action="<?php echo esc_url( home_url( '/' ) ); ?>">
                    <div class="form-outline">
                        <input type="search" id="search" class="form-control border" name="s" value="<?php echo get_search_query(); ?>" />
                        <label class="form-label" for="search">Search</label>
                    </div>
                    <button type="submit" class="btn btn-primary shadow-0">
                        <i class="fas fa-search"></i>
                    </button>
                </form>
            </div>
            <!-- Center elements -->

            <!-- Right elements -->
            <div class="col-lg-5">
                <ul class="list-inline">
                    <?php if ( ! empty( $top_menus ) && is_array( $top_menus ) ) {
                        foreach ( $top_menus as $menu_item ) {
                            if ( ! $menu_item->menu_item_parent ) {
                                $child_menu_items = $menu_class->get_child_menu_items( $top_menus, $menu_item->ID );
                                $has_children = ! empty( $child_menu_items ) && is_array( $child_menu_items );

                                if ( ! $has_children ) { ?>
                                    <li class="list-inline-item border px-3 py-1">
                                        <a class="nav-link link-primary" href="<?php echo esc_url( $menu_item->url ); ?>"><?php echo esc_html( $menu_item->title ); ?></a>
                                    </li>
                                <?php } else { ?>
                                    <li class="nav-item dropdown">
                                        <a class="nav-link dropdown-toggle" href="<?php echo esc_url( $menu_item->url ); ?>" id="navbarDropdown" role="button" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                                            <?php echo esc_html( $menu_item->title ); ?>
                                        </a>
                                        <div class="dropdown-menu" aria-labelledby="navbarDropdown">
                                            <?php foreach ( $child_menu_items as $child_menu_item ) { ?>
                                                <a class="dropdown-item" href="<?php echo esc_url( $child_menu_item->url ); ?>"><?php echo esc_html( $child_menu_item->title ); ?></a>
                                            <?php } ?>
                                        </div>
                                    </li>
                                <?php }
                            }
                        }
                    } ?>

                    <!-- Cart Icon -->
                    <?php
                    $cart_count = 0;
                    if (isset($_SESSION['cart'])) {
                        $cart_count = array_sum(array_column($_SESSION['cart'], 'quantity'));
                    }
                    ?>
                    <li class="nav-item list-inline-item">
                        <a href="<?php echo esc_url(site_url('/cart')); ?>" class="nav-link">
                            <i class="fa fa-shopping-cart"></i>
                            <span class="badge bg-primary"><?php echo esc_html($cart_count); ?></span>
                        </a>
                    </li>

                    <!-- Order, Registration, and Signout -->
                    <?php
                        // Start the session if not already started
                        if (session_status() === PHP_SESSION_NONE) {
                            session_start();
                        }

                        // Get the current user ID
                        $userId = get_current_user_id();

                        // Generate or retrieve session ID for non-logged-in users
                        if (!$userId) {
                            if (!isset($_SESSION['session_id']) || empty($_SESSION['session_id'])) {
                                $_SESSION['session_id'] = session_id();
                            }
                            $session_id = $_SESSION['session_id'];
                        } else {
                            $session_id = null; // Session ID not needed for logged-in users
                        }

                        // Query to check if there are any orders for the logged-in user or session ID
                        global $wpdb;
                        $table_name = $wpdb->prefix . 'aheri_orders';

                        // Build the query based on whether the user is logged in or not
                        if ($userId) {
                            // Query for logged-in users
                            $query = $wpdb->prepare("
                                SELECT COUNT(*) FROM $table_name WHERE user_id = %d
                            ", $userId);
                        } else {
                            // Query for guest users (based on session_id)
                            $query = $wpdb->prepare("
                                SELECT COUNT(*) FROM $table_name WHERE session_id = %s
                            ", $session_id);
                        }

                        // Check if there are any orders
                        $order_count = $wpdb->get_var($query);
                        ?>

                        <!-- Display the navigation item if there are orders -->
                        <?php if ($order_count > 0) : ?>
                            <li class="nav-item list-inline-item border px-3 py-1">
                                <a class="nav-link link-primary" href="<?php echo esc_url(home_url('/order')); ?>"><?php _e('Order List', 'aheri'); ?></a>
                            </li>
                        <?php endif; ?>

                        <?php if ( ! is_user_logged_in() ) { ?>
                            <li class="nav-item list-inline-item border px-3 py-1">
                                <!-- Link to your custom login page -->
                                <a class="nav-link link-primary" href="<?php echo esc_url( site_url('/custom-login') ); ?>"><?php _e('Login', 'aheri'); ?></a>
                            </li>
                            <li class="nav-item list-inline-item border px-3 py-1">
                                <!-- Link to your registration page -->
                                <a class="nav-link link-primary" href="<?php echo esc_url( site_url('/registration') ); ?>"><?php _e('Register', 'aheri'); ?></a>
                            </li>
                        <?php } else { ?>
                            <li class="nav-item list-inline-item border px-3 py-1">
                                <!-- Link to sign out and return to homepage -->
                                <a class="nav-link link-primary" href="<?php echo esc_url( wp_logout_url( home_url() ) ); ?>"><?php _e('Signout', 'aheri'); ?></a>
                            </li>
                        <?php } ?>
                </ul>
            </div>
            <!-- Right elements -->
        </div>
    </div>
</div>
<!-- Jumbotron -->

<!-- Navbar -->
<nav class="navbar navbar-expand-lg navbar-light" style="background-color: #f5f5f5;">
    <div class="container justify-content-center justify-content-md-between">
        <button
            class="navbar-toggler border text-dark py-2"
            type="button"
            data-mdb-toggle="collapse"
            data-mdb-target="#navbarLeftAlignExample"
            aria-controls="navbarLeftAlignExample"
            aria-expanded="false"
            aria-label="Toggle navigation"
        >
            <i class="fas fa-bars"></i>
        </button>

        <div class="collapse navbar-collapse" id="navbarLeftAlignExample">
            <?php if ( ! empty( $header_menus ) && is_array( $header_menus ) ) { ?>
                <ul class="navbar-nav">
                    <?php foreach ( $header_menus as $menu_item ) {
                        if ( ! $menu_item->menu_item_parent ) {
                            $child_menu_items = $menu_class->get_child_menu_items( $header_menus, $menu_item->ID );
                            $has_children = ! empty( $child_menu_items ) && is_array( $child_menu_items );

                            if ( ! $has_children ) { ?>
                                <li class="nav-item">
                                    <a class="nav-link" href="<?php echo esc_url( $menu_item->url ); ?>"><?php echo esc_html( $menu_item->title ); ?></a>
                                </li>
                            <?php } else { ?>
                                <li class="nav-item dropdown">
                                    <a class="nav-link dropdown-toggle" href="<?php echo esc_url( $menu_item->url ); ?>" id="navbarDropdown" role="button" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                                        <?php echo esc_html( $menu_item->title ); ?>
                                    </a>
                                    <div class="dropdown-menu" aria-labelledby="navbarDropdown">
                                        <?php foreach ( $child_menu_items as $child_menu_item ) { ?>
                                            <a class="dropdown-item" href="<?php echo esc_url( $child_menu_item->url ); ?>"><?php echo esc_html( $child_menu_item->title ); ?></a>
                                        <?php } ?>
                                    </div>
                                </li>
                            <?php }
                        }
                    } ?>
                </ul>
            <?php } ?>
        </div>
    </div>
</nav>
<!-- Navbar -->