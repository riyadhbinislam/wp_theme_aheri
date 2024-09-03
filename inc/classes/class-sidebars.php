<?php
/**
 * Theme Sidebars
 * @package Aheri
 */

namespace AHERI\inc;
use AHERI\inc\traits\singleton;

class Sidebars {
    use singleton;

    protected function __construct() {
        //load other classes.
        $this->setup_hooks();
    }

    protected function setup_hooks() {
        //actions and filters
        add_action( 'widgets_init', [ $this, 'register_sidebars'] );
    }

    public function register_sidebars(){
        register_sidebar( array(
            'name'          => __( 'Main Sidebar', 'aheri' ),
            'id'            => 'sidebar-1',
            'description'   => __( 'Main Sidebar', 'aheri' ),
            'before_widget' => '<div id="%1$s" class="widget widget-main %2$s">',
            'after_widget'  => '</div>',
            'before_title'  => '<h3 class="widgettitle">',
            'after_title'   => '</h3>',
        ) );
        register_sidebar( array(
            'name'          => __( 'Footer Sidebar', 'aheri' ),
            'id'            => 'sidebar-2',
            'description'   => __( 'Footer Sidebar', 'aheri' ),
            'before_widget' => '<div id="%1$s" class="widget widget-footer cell column %2$s">',
            'after_widget'  => '</div>',
            'before_title'  => '<h3 class="widgettitle">',
            'after_title'   => '</h3>',
        ) );
    }
}
