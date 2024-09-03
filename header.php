<?php global $aheri_redux_opt; ?>
<html lang="<?php language_attributes() ;?>">
<head>
    <meta charset="<?php bloginfo( 'charset' ) ?>" class="no-js">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="icon" href="<?php echo $aheri_redux_opt['header_fav_logo']['url']; ?>" type="image/png">
    <title><?php echo $aheri_redux_opt['header_title_text']; ?></title>
        <?php wp_head(); ?>

</head>
<div id="notification-container" style="position: fixed; top: 100px; right: 10px; z-index: 9999;"></div>
<body <?php body_class();?>>
<?php if(function_exists('wp_body_open')){wp_body_open();} ?>



<main id="main" class="site-main" role="main">

<!--Main Navigation-->
<header id="header_area" class="<?php echo get_theme_mod('omni_menu_position');?>">
    <?php include('templates/header/nav.php');?>
</header>



