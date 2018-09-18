<div class="dataTable-area">
<!--    <link rel="stylesheet" type="text/css" href="https://cdn.datatables.net/1.10.19/css/jquery.dataTables.min.css">-->
<!--    <script type="text/javascript" charset="utf8" src="https://cdn.datatables.net/1.10.19/js/jquery.dataTables.min.js"></script>-->

    <?php

    // Display Product table on admin
    function product_explode_table()
    {
        global $wpdb, $product, $i;
        $i = 1;
        $product = get_product(get_the_ID());
        $children = $product->get_children();
        $product_id = $product->get_id();
        ?>
        <form id="product_form_id" method="post" action="">
            <table border="1" id="product_table_id" class="display">
                <thead>
                    <tr>
                        <th>Product Id</th>
                        <th>Product Name</th>
                        <th>Clickable Area</th>
                        <th>Text</th>
                    </tr>
                </thead>

                <tbody>
                    <?php
                    foreach ($children as $key => $value) {
                        $productslist = get_post($value);
                        $post_id = $productslist->ID;
                        $post_name = $productslist->post_title;

                        $label_width = get_post_meta($product_id, "pe_label_field_" . $i . "_width", true);
                        $label_height = get_post_meta($product_id, "pe_label_field_" . $i . "_height", true);

                        $label_text = get_post_meta($product_id, "label_text_" . $i, true);
                        ?>

                        <tr id="<?php echo "tr_product_id_" . $key ?>">
                            <td class="postId"><?php echo $post_id; ?></td>
                            <td><?php echo $post_name; ?></td>
                            <td class="label-area-size">
                                <input type="text" class="label_width" data-lableid="<?php echo $i; ?>" name="<?php echo "pe_label_field_" . $i . "_width"; ?>" id="<?php echo "pe_label_field_" . $i . "_width"; ?>" value="<?php echo $label_width; ?>"> X 
                                <input type="text" class="label_height" data-lableid="<?php echo $i; ?>" name="<?php echo "pe_label_field_" . $i . "_height"; ?>" id="<?php echo "pe_label_field_" . $i . "_height"; ?>" value="<?php echo $label_height; ?>">
                            </td>
                            <td class="label-num">
                                <input type="text" class="label-field-link-to-num" name="<?php echo "label_text_" . $i ?>" id="label_field_link_to_num" value="<?php echo $label_text; ?>">
                            </td>
                        </tr>
                        <?php
                        update_post_meta($product_id, "linked_label_$post_id", $i);
                        $i++;
                    }
                    ?>
                </tbody>
            </table>
        </form>
        <?php
    }
    ?>
    <?php product_explode_table(); ?>
</div>
