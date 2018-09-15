<?php
/*
 *  Plugin Name: Product Explode Plugin
 *  Descrption: This plugin allows you to explode the image of your Group product.
 *  Version: 1.0
 *  Author: Wama Software
 *  Author URI: http://www.wamasoftware.com/
 */

//global $product;

add_action('init', 'ajax_load_scripts');

function ajax_load_scripts()
{
    wp_enqueue_script('jQuery', plugin_dir_url(__FILE__) . 'js/script.js', array());
    wp_enqueue_style('style', plugin_dir_url(__FILE__) . 'css/style.css', array());
}

add_filter('woocommerce_product_data_tabs', 'add_my_custom_product_explode_tab', 99, 1);

// Adding Product Explode tab on products menu in Admin
function add_my_custom_product_explode_tab($product_data_tabs)
{
    $product_data_tabs['product-explode'] = array(
        'label' => __('Product Explode', 'my_text_domain'),
        'target' => 'my_custom_product_data',
        'class' => array('show_if_grouped'),
    );

    return $product_data_tabs;
}

add_action('woocommerce_product_data_panels', 'add_my_custom_product_explode_fields');

function add_my_custom_product_explode_fields()
{
    global $woocommerce, $post;
    $product = get_product(get_the_ID());
    $children = $product->get_children();
    $product_id = $product->get_id();
    ?>

    <!--Displaying product image on product explode tab-->
    <div id="my_custom_product_data" class="panel woocommerce_options_panel">
        <?php
        $product_meta = get_post_meta($post->ID);
        $product_explode_img_url = wp_get_attachment_url($product_meta['_thumbnail_id'][0]);
        ?>
        <div id="test" class="exploded-area">
            <img src="<?php echo $product_explode_img_url ?>">

            <?php
            for ($i = 1; $i <= count($children); $i++) {
                $label_width = get_post_meta($product_id, "pe_label_field_" . $i . "_width", true);
                $label_height = get_post_meta($product_id, "pe_label_field_" . $i . "_height", true);

                $label_top_pos = get_post_meta($product_id, "position_top_" . $i, true);
                $label_left_pos = get_post_meta($product_id, "position_left_" . $i, true);
                $label_text = get_post_meta($product_id, "label_text_" . $i, true);
                ?>
                <div id="<?php echo "draggable_" . $i ?>" data-lableid="<?php echo $i; ?>" class="ui-widget-content draggable draggable-label" style="left: <?php echo $label_left_pos . 'px' ?>; top: <?php echo $label_top_pos . 'px' ?>; width: <?php echo $label_width . 'px' ?>;height: <?php echo $label_height . 'px' ?>;">
                    <input type="hidden" name="position_top_<?php echo $i; ?>" value="<?php echo $label_top_pos; ?>" class="top position_top_<?php echo $i; ?>">
                    <span name="label_text_<?php echo $i; ?>" value="<?php echo $label_text; ?>" class="label-text label_text_<?php echo $i; ?>"><?php echo $label_text; ?></span><?php echo $i; ?>
                    <input type="hidden" name="position_left_<?php echo $i; ?>" value="<?php echo $label_left_pos; ?>" class="left position_left_<?php echo $i; ?>">
                </div>
        <?php } ?>
        </div>
        <?php require_once(ABSPATH . 'wp-content/plugins/prod_ex_plgn/associative-product-list.php');
        ?>
    </div>
    <?php
}

add_action('save_post', 'associative_post_meta', 10, 3);

function associative_post_meta($post_ID, $post, $update)
{
    for ($i = 1; $i <= count($_REQUEST['grouped_products']); $i++) {
        update_post_meta($post_ID, "pe_label_field_" . $i . "_width", $_POST['pe_label_field_' . $i . '_width']);
        update_post_meta($post_ID, "pe_label_field_" . $i . "_height", $_POST['pe_label_field_' . $i . '_height']);

        update_post_meta($post_ID, "position_top_" . $i, $_POST['position_top_' . $i]);
        update_post_meta($post_ID, "position_left_" . $i, $_POST['position_left_' . $i]);

        update_post_meta($post_ID, "label_text_" . $i, $_POST['label_text_' . $i]);
    }
}

function myplugin_plugin_path()
{
    // gets the absolute path to this plugin directory

    return untrailingslashit(plugin_dir_path(__FILE__));
}

//add_filter('template_include', 'single_prod_template', 99);

//function single_prod_template($template)
//{
//    $plugin_path = myplugin_plugin_path() . '/woocommerce/single-product.php';
//
//    if (is_page('product')) {
//        $new_template = locate_template(array($plugin_path));
//    }
//    return $plugin_path;
//}

add_action('woocommerce_after_main_content', 'display_product');

function display_product()
{
    global $wpdb, $product, $woocommerce, $post, $i;

    $product = get_product(get_the_ID());
    $children = $product->get_children();
    $product_id = $product->get_id();
    
    if(!empty($children)){
      ?>
        <form class="cart grouped_form" action="<?php echo esc_url(apply_filters('woocommerce_add_to_cart_form_action', $product->get_permalink())); ?>" method="post"enctype="multiple/form-data">

        <!--    Displaying Image    -->
        <div id="my_custom_product_data" class="panel woocommerce_options_panel">
            <?php
            $product_meta = get_post_meta($post->ID);
            $product_explode_img_url = wp_get_attachment_url($product_meta['_thumbnail_id'][0]);
            ?> 
            <img src="<?php echo $product_explode_img_url ?>"> 

            <?php
            for ($i = 1; $i <= count($children); $i++) {

                $label_width = get_post_meta($product_id, "pe_label_field_" . $i . "_width", true);
                $label_height = get_post_meta($product_id, "pe_label_field_" . $i . "_height", true);

                $label_top_pos = get_post_meta($product_id, "position_top_" . $i, true);
                $label_left_pos = get_post_meta($product_id, "position_left_" . $i, true);

                $label_text = get_post_meta($product_id, "label_text_" . $i, true);
                ?>
                <div data-lableid="<?php echo $i; ?>" id="<?php echo "draggable_" . $i ?>" class="ui-widget-content draggable draggable-label" style="left: <?php echo $label_left_pos . 'px' ?>; top: <?php echo $label_top_pos . 'px' ?>; width: <?php echo $label_width . 'px' ?>;height: <?php echo $label_height . 'px' ?>;">
                    <input type="hidden" name="position_top_<?php echo $i; ?>" value="<?php echo $label_top_pos; ?>" class="position_top_<?php echo $i; ?>">
                    <span name="label_text_<?php echo $i; ?>" value="<?php echo $label_text; ?>" class="label-text label_text_<?php echo $i; ?>"><?php echo $label_text; ?></span><?php echo $i; ?>
                    <input type="hidden" name="position_left_<?php echo $i; ?>" value="<?php echo $label_left_pos; ?>" class="position_left_<?php echo $i; ?>">
                </div>
                <?php
            }
            associative_post_meta($post_ID, $post, $update);
            $i = 1;
            ?>
        </div>

        <!--    Displaying Products Table-->
        <div class="product_meta display" id="ass_pr_list_table">
            <table id="ass_prod_list">
                <thead>
                    <tr>
                        <th>Sr. No.</th>
                        <th>Product Id</th>
                        <th>SKU (Item No.)</th>
                        <th>Product Name</th>
                        <th>Price</th>
                        <th>Qty</th>
                        <th>Action</th>
                    </tr>
                </thead>

                <tbody>
                    <?php
                    foreach ($children as $key => $value) {
                        $productslist = get_post($value);
                        $pr = new WC_Product($value);
                        $post_id = $productslist->ID;
                        $post_name = $productslist->post_title;
                        $prod_price = $pr->get_price_html();
                        $prod_sku = $pr->get_sku();
                        ?>
                        <tr class="tr_ass_pr_list" id="tr_ass_pr_list_id_<?php echo $i; ?>" name="tr_ass_pr_list_id_<?php echo $i; ?>">
                            <td><?php echo $i; ?></td>
                            <td><?php echo $post_id; ?></td>
                            <td><?php echo $prod_sku; ?></td>
                            <td><?php echo $post_name; ?></td>
                            <td><?php echo $prod_price; ?></td>
                            <td>
                                <input type="number" id="quantity_5b3dac65c4092" class="input-text qty text" step="1" min="0" max="100" name="quantity[<?php echo $post_id; ?>]" value="0" title="Qty" size="4" pattern="[0-9]*" inputmode="numeric" aria-labelledby="">
                            </td>
                            <td>
                                <button type="submit" class="single_add_to_cart_button button alt"><?php echo esc_html($product->single_add_to_cart_text()); ?></button>
                            </td>
                        </tr>
        <?php
        $i++;
    }
    ?>
                </tbody>
            </table>
            <input type="hidden" name="add-to-cart" value="<?php echo $product_id; ?>"/>
        </div>
    </form> 
    
    <?php     
    }
    ?>
    <script src="http://ajax.googleapis.com/ajax/libs/jquery/1.11.2/jquery.min.js"></script>
    <?php
}

add_filter('woocommerce_locate_template', 'myplugin_woocommerce_locate_template', 10, 3);

function myplugin_woocommerce_locate_template($template, $template_name, $template_path)
{
    global $woocommerce;

    $_template = $template;

    if (!$template_path)
        $template_path = $woocommerce->template_url;

    $plugin_path = myplugin_plugin_path() . '/woocommerce/';

    // Look within passed path within the theme - this is priority
    $template = locate_template(
            array(
                $template_path . $template_name,
                $template_name
            )
    );

    // Modification: Get the template from this plugin, if it exists
    if (!$template && file_exists($plugin_path . $template_name))
        $template = $plugin_path . $template_name;

    // Use default template
    if (!$template)
        $template = $_template;


    // Return what we found
    return $template;
}
?>