<?php
/**
 * class Product
 * @package Aheri
 */

namespace AHERI\inc;
use AHERI\inc\traits\singleton;

class Product {
    use singleton;

    protected function __construct() {
        $this->setup_hooks();
    }

    protected function setup_hooks() {
        // Actions and filters
        add_action('init', [$this, 'custom_product']);
        //add_action('init', [$this,'register_product_taxonomies']);
        add_action('add_meta_boxes', [$this, 'add_custom_meta_boxes']);
        add_action('save_post', [$this, 'save_custom_meta_boxes']);

        // For Icon Field
        add_action('product_cat_add_form_fields', [$this, 'aheri_add_category_icon_field']);
        add_action('product_cat_edit_form_fields', [$this, 'aheri_edit_category_icon_field'], 10, 2);
        add_action('created_product_cat', [$this, 'aheri_save_category_icon']);
        add_action('edited_product_cat', [$this, 'aheri_save_category_icon']);
        add_action('get_product_cat', [$this, 'aheri_get_category_icon']);

        add_action('category_add_form_fields', [$this, 'aheri_add_category_icon_field']);
        add_action('category_edit_form_fields', [$this, 'aheri_edit_category_icon_field'], 10, 2);
        add_action('created_category', [$this, 'aheri_save_category_icon']);
        add_action('edited_category', [$this, 'aheri_save_category_icon']);
        add_filter('manage_edit-category_columns', [$this, 'add_category_icon_column']);
        add_filter('manage_category_custom_column', [$this, 'display_category_icon_column'], 10, 3);

        add_action('wp_ajax_save_category_icon', [$this, 'aheri_save_category_icon']);
        add_action('wp_ajax_nopriv_save_category_icon', [$this, 'aheri_save_category_icon']);

        add_theme_support('post-thumbnails');
    }

    public function custom_product() {
        $labels = [
            'name'                  => _x('Products', 'Post Type General Name', 'aheri'),
            'singular_name'         => _x('Product', 'Post Type Singular Name', 'aheri'),
            'menu_name'             => _x('Products', 'Admin Menu text', 'aheri'),
            'name_admin_bar'        => _x('Product', 'Add New on Toolbar', 'aheri'),
            'archives'              => __('Product Archives', 'aheri'),
            'attributes'            => __('Product Attributes', 'aheri'),
            'parent_item_colon'     => __('Parent Product:', 'aheri'),
            'all_items'             => __('All Products', 'aheri'),
            'add_new_item'          => __('Add New Product', 'aheri'),
            'add_new'               => __('Add New', 'aheri'),
            'new_item'              => __('New Product', 'aheri'),
            'edit_item'             => __('Edit Product', 'aheri'),
            'update_item'           => __('Update Product', 'aheri'),
            'view_item'             => __('View Product', 'aheri'),
            'view_items'            => __('View Products', 'aheri'),
            'search_items'          => __('Search Product', 'aheri'),
            'not_found'             => __('Not found', 'aheri'),
            'not_found_in_trash'    => __('Not found in Trash', 'aheri'),
            'featured_image'        => __('Featured Image', 'aheri'),
            'set_featured_image'    => __('Set featured image', 'aheri'),
            'remove_featured_image' => __('Remove featured image', 'aheri'),
            'use_featured_image'    => __('Use as featured image', 'aheri'),
            'insert_into_item'      => __('Insert into Product', 'aheri'),
            'uploaded_to_this_item' => __('Uploaded to this Product', 'aheri'),
            'items_list'            => __('Products list', 'aheri'),
            'items_list_navigation' => __('Products list navigation', 'aheri'),
            'filter_items_list'     => __('Filter Products list', 'aheri'),
        ];

        $args = [
            'label'                 => __('Product', 'aheri'),
            'description'           => __('Lorem ipsum dolor sit amet.', 'aheri'),
            'labels'                => $labels,
            'menu_icon'             => 'dashicons-pinterest',
            'supports'              => ['title', 'editor', 'thumbnail', 'revisions', 'page-attributes', 'post-formats'],
            'taxonomies'            => ['category', 'post_tag'],
            'hierarchical'          => true,
            'public'                => true,
            'show_ui'               => true,
            'show_in_menu'          => true,
            'menu_position'         => 5,
            'show_in_admin_bar'     => true,
            'show_in_nav_menus'     => true,
            'can_export'            => true,
            'has_archive'           => true,
            'exclude_from_search'   => false,
            'publicly_queryable'    => true,
            'capability_type'       => 'post',
            'show_in_rest'          => true,
            'rewrite'               => ['slug' => 'products'],
        ];

        register_post_type('product', $args);
    }


    /**--------------- Category Icon or Image Related function --------------------* */
    public function aheri_add_category_icon_field() {
        ?>
        <div class="form-field">
            <label for="category_icon_image"><?php _e('Category Icon (Image or SVG)', 'aheri'); ?></label>
            <input type="text" name="category_icon_image" id="category_icon_image" value="" class="category-icon-url" />
            <button class="upload-category-icon button"><?php _e('Upload/Add Image', 'aheri'); ?></button>
            <p class="description"><?php _e('Upload an image or SVG for the category icon.', 'aheri'); ?></p>
            <div id="category_icon_preview"></div>
        </div>
        <script>
            jQuery(document).ready(function($) {
                var mediaUploader;

                $(document).on('click', '.upload-category-icon', function(e) {
                    e.preventDefault();

                    if (mediaUploader) {
                        mediaUploader.open();
                        return;
                    }

                    mediaUploader = wp.media({
                        title: '<?php _e('Choose Icon', 'aheri'); ?>',
                        button: {
                            text: '<?php _e('Choose Icon', 'aheri'); ?>'
                        },
                        multiple: false
                    });

                    mediaUploader.on('select', function() {
                        var attachment = mediaUploader.state().get('selection').first().toJSON();
                        $('#category_icon_image').val(attachment.url);
                        $('#category_icon_preview').html('<img src="' + attachment.url + '" style="max-width: 100px; max-height: 100px;">');
                    });

                    mediaUploader.open();
                });
            });
        </script>
        <?php
    }

    public function aheri_edit_category_icon_field($term) {
        $icon_url = get_term_meta($term->term_id, 'category_icon_image', true);
        ?>
        <tr class="form-field">
            <th scope="row" valign="top">
                <label for="category_icon_image"><?php _e('Category Icon (Image or SVG)', 'aheri'); ?></label>
            </th>
            <td>
                <input type="text" name="category_icon_image" id="category_icon_image" value="<?php echo esc_url($icon_url); ?>" class="category-icon-url" />
                <button class="upload-category-icon button"><?php _e('Upload/Add Image', 'aheri'); ?></button>
                <p class="description"><?php _e('Upload an image or SVG for the category icon.', 'aheri'); ?></p>
                <div id="category_icon_preview">
                    <?php if ($icon_url): ?>
                        <img src="<?php echo esc_url($icon_url); ?>" style="max-width: 100px; max-height: 100px;">
                    <?php endif; ?>
                </div>
            </td>
        </tr>
        <script>
            jQuery(document).ready(function($) {
                var mediaUploader;

                $(document).on('click', '.upload-category-icon', function(e) {
                    e.preventDefault();

                    if (mediaUploader) {
                        mediaUploader.open();
                        return;
                    }

                    mediaUploader = wp.media({
                        title: '<?php _e('Choose Icon', 'aheri'); ?>',
                        button: {
                            text: '<?php _e('Choose Icon', 'aheri'); ?>'
                        },
                        multiple: false
                    });

                    mediaUploader.on('select', function() {
                        var attachment = mediaUploader.state().get('selection').first().toJSON();
                        $('#category_icon_image').val(attachment.url);
                        $('#category_icon_preview').html('<img src="' + attachment.url + '" style="max-width: 100px; max-height: 100px;">');
                    });

                    mediaUploader.open();
                });
            });
        </script>
        <?php
    }
    public function aheri_save_category_icon($term_id) {
        if (!isset($_POST['category_icon_image'])) {
            return;
        }

        $icon_url = sanitize_text_field($_POST['category_icon_image']);
        update_term_meta($term_id, 'category_icon_image', $icon_url);
    }

    public function aheri_get_category_icon($term_id) {
        $icon_url = get_term_meta($term_id, 'category_icon_image', true);
        if ($icon_url) {
            return '<span class="category-icon"><img src="' . esc_url($icon_url) . '" alt="' . esc_attr__('Category Icon', 'aheri') . '" style="width: 32px; height: 32px;"></span>';
        }
        return '';
    }

    public function add_category_icon_column($columns) {
        $columns['category_icon'] = __('Icon', 'aheri');
        return $columns;
    }

    public function display_category_icon_column($content, $column_name, $term_id) {
        if ($column_name == 'category_icon') {
            $icon_url = get_term_meta($term_id, 'category_icon_image', true);
            $content = $icon_url ? '<img src="' . esc_url($icon_url) . '" style="max-width: 30px; max-height: 30px;">' : __('No Icon', 'aheri');
        }
        return $content;
    }


    /**--------------- Category Icon or Image Related function --------------------* */

    // Add the Meta Boxes
    public function add_custom_meta_boxes() {
        add_meta_box(
            'product_meta_box',
            __('Product Details', 'aheri'),
            [$this, 'render_meta_box_content'],
            'product',
            'advanced',
            'high'
        );
    }

    // Render the Meta Box content
    public function render_meta_box_content($post) {
        wp_nonce_field('save_product_meta_box_data', 'product_meta_box_nonce');
        $type = get_post_meta($post->ID, '_product_type', true);
        $color = get_post_meta($post->ID, '_product_color', true);
        $material = get_post_meta($post->ID, '_product_material', true);
        $brand = get_post_meta($post->ID, '_product_brand', true);
        $size = get_post_meta($post->ID, '_product_size', true);

        ?>
        <p>
            <label for="product_type"><?php _e('Type:', 'aheri'); ?></label>
            <input type="text" id="product_type" name="product_type" value="<?php echo esc_attr($type); ?>" />
        </p>
        <p>
            <label for="product_color"><?php _e('Color:', 'aheri'); ?></label>
            <input type="text" id="product_color" name="product_color" value="<?php echo esc_attr($color); ?>" />
        </p>
        <p>
            <label for="product_material"><?php _e('Material:', 'aheri'); ?></label>
            <input type="text" id="product_material" name="product_material" value="<?php echo esc_attr($material); ?>" />
        </p>
        <p>
            <label for="product_brand"><?php _e('Brand:', 'aheri'); ?></label>
            <input type="text" id="product_brand" name="product_brand" value="<?php echo esc_attr($brand); ?>" />
        </p>
        <p>
            <label for="product_size"><?php _e('Size:', 'aheri'); ?></label>
            <input type="text" id="product_size" name="product_size" value="<?php echo esc_attr($size); ?>" />
        </p>
        <?php
    }

    // Save the Meta Box data
    public function save_custom_meta_boxes($post_id) {
        if (!isset($_POST['product_meta_box_nonce'])) {
            return;
        }

        if (!wp_verify_nonce($_POST['product_meta_box_nonce'], 'save_product_meta_box_data')) {
            return;
        }

        if (defined('DOING_AUTOSAVE') && DOING_AUTOSAVE) {
            return;
        }

        if (!current_user_can('edit_post', $post_id)) {
            return;
        }

        $fields = ['product_type', 'product_color', 'product_material', 'product_brand', 'product_size'];

        foreach ($fields as $field) {
            if (isset($_POST[$field])) {
                $value = sanitize_text_field($_POST[$field]);
                update_post_meta($post_id, "_{$field}", $value);
            } else {
                delete_post_meta($post_id, "_{$field}");
            }
        }
    }

}
