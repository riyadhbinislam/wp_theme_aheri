<?php
/**
 * class Menus
 * @package Aheri
 */

namespace AHERI\inc;
use AHERI\inc\traits\singleton;

class Menus {
    use singleton;

    protected function __construct() {
        //load other classes.
        $this->setup_hooks();
    }

    protected function setup_hooks() {
        //actions and filters
        add_action('init', [ $this, 'register_menus' ]);
    }

    public function register_menus() {
        register_nav_menus([
              'aheri-header-menu' => esc_html__('Header Menu',  'aheri'),
              'aheri-top-menu' => esc_html__('Top Menu',  'aheri'),
              'aheri-footer-menu' => esc_html__('Footer Menu',  'aheri'),
            ]);
    }

    public function get_menu_id( $location ) {
        // Get all the locations.
        $locations = get_nav_menu_locations();

        // Check if the location exists in the array
        if ( isset( $locations[ $location ] ) ) {
            // Get Object id by location
            return $locations[ $location ];
        }

        // Return an empty string if the location does not exist
        return '';
    }


    public function get_child_menu_items( $menu_array, $parent_id ){
        $child_menus = [];

        if(! empty($menu_array) && is_array($menu_array)){
            foreach ($menu_array as $menu){
                if (intval( $menu->menu_item_parent ) === $parent_id ){
                    array_push( $child_menus, $menu );
                }
            }
        }

        return $child_menus;
    }
}
