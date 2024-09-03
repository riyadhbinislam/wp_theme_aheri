<?php
/**
 * Register Meta Boxes
 * @package Aheri
 */

namespace AHERI\inc;
use AHERI\inc\traits\singleton;

class Meta_Boxes {
    use singleton;

    protected function __construct() {
        //load other classes.
        $this->setup_hooks();
    }

    protected function setup_hooks() {
        //actions and filters
        add_action( 'add_meta_boxes', [ $this, 'add_custom_meta_box' ] );
		add_action( 'save_post', [ $this, 'save_post_meta_data' ] );
    }


    public function add_custom_meta_box() {
        $screens = [ 'post' ];
        foreach ( $screens as $screen ) {
            add_meta_box(
                'hide_page_title',                 // Unique ID
                __('Hide Page Title', 'aheri'),      // Box title
                [$this, 'custom_meta_box_html'],  // Content callback, must be of type callable
                $screen,                            // Post type
                'side'  // contex
            );
        }
    }

    public function custom_meta_box_html( $post ) {
        $value = get_post_meta( $post->ID, '_hide_page_title', true );
        /**
         * Use Nonce for verification
         */
        wp_nonce_field( plugin_basename( __FILE__ ), 'hide_page_title_nonce' );
    ?>
        <label for="hide_page_title"><?php esc_html_e( 'Hide The Page Title' )?></label>
        <select name="hide_page_title" id="hide_page_title" class="postbox">
            <option value=""><?php esc_html_e( 'Select', 'aheri' )?></option>
            <option value="yes" <?php selected( $value, 'yes' ); ?>><?php esc_html_e( 'Yes', 'aheri' )?></option>
            <option value="no" <?php selected( $value, 'no' ); ?>><?php esc_html_e( 'No', 'aheri' )?></option>
        </select>
    <?php
    }

    public function save_post_meta_data( $post_id ) {
        /**
         * When  the post is saved or updated we get $_POST available
         * Check if the current user is authorized
         */
        if ( ! current_user_can( 'edit_post', $post_id ) ) {
            return;
            }
        /**
         * Check if the nonece value we recived is the same we created
         */
        if (! isset($_POST['hide_page_title_nonce']) ||
            ! wp_verify_nonce( $_POST['hide_page_title_nonce'], plugin_basename( __FILE__))) {
                return;
            }

        if ( array_key_exists( 'hide_page_title', $_POST ) ) {
            update_post_meta(
                $post_id,
                '_hide_page_title',
                $_POST['hide_page_title']
            );
        }
    }
}
