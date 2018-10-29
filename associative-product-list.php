<div class="dataTable-area">
    <?php

    // Display Product table on admin
    function wspewp_productTable() {
        global $i;
        $i = 1;
        $productDetails = get_product(get_the_ID());
        $childProducts = $productDetails->get_children();
        $product_id = $productDetails->get_id();
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
                    foreach ($childProducts as $key => $value) {
                        $productslist = get_post($value);
                        $productID = $productslist->ID;
                        $productName = $productslist->post_title;

                        $labelWidth = get_post_meta($product_id, "pe_label_field_" . $i . "_width", true);
                        $labelHeight = get_post_meta($product_id, "pe_label_field_" . $i . "_height", true);

                        $labelText = get_post_meta($product_id, "label_text_" . $i, true);
                        ?>

                        <tr id="<?php echo "tr_product_id_" . $key ?>">
                            <td class="postId"><?php echo $productID; ?></td>
                            <td><?php echo $productName; ?></td>
                            <td class="label-area-size">
                                <input type="text" class="label_width" data-lableid="<?php echo $i; ?>" name="<?php echo "pe_label_field_" . $i . "_width"; ?>" id="<?php echo "pe_label_field_" . $i . "_width"; ?>" value="<?php echo $labelWidth; ?>"> X 
                                <input type="text" class="label_height" data-lableid="<?php echo $i; ?>" name="<?php echo "pe_label_field_" . $i . "_height"; ?>" id="<?php echo "pe_label_field_" . $i . "_height"; ?>" value="<?php echo $labelHeight; ?>">
                            </td>
                            <td class="label-num">
                                <input type="text" class="label-field-link-to-num" name="<?php echo "label_text_" . $i ?>" id="label_field_link_to_num" value="<?php echo $labelText; ?>">
                            </td>
                        </tr>
                        <?php
                        update_post_meta($product_id, "linked_label_$productID", $i);
                        $i++;
                    } ?>
                </tbody>
            </table>
        </form>
        <?php
    } ?>
    <?php wspewp_productTable(); ?>
</div>