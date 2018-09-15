/* 
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */


//<script src="http://ajax.googleapis.com/ajax/libs/jquery/1.11.2/jquery.min.js"></script>

jQuery(document).ready(function () {
    jQuery(function () {
        jQuery('.draggable').draggable({
            start: function (e, ui) {
                ui.helper.addClass("dragging");
            },
            stop: function (e, ui) {
                var offset = ui.position;
                var top = offset.top;
                var left = offset.left;

                jQuery(this).closest('.draggable-label').find('.top').val(top);
                jQuery(this).closest('.draggable-label').find('.left').val(left);

                ui.helper.removeClass("dragging");
            }
        });
    });

    jQuery("#product_table_id tbody").on("keyup", ".label_width", function () {
        if (jQuery(this).val()) {
            var labelid = jQuery(this).data('lableid');
            jQuery("#my_custom_product_data").find('#draggable_' + labelid).css('width', jQuery(this).val());
        }
    });

    jQuery("#product_table_id tbody").on("keyup", ".label_height", function () {
        if (jQuery(this).val()) {
            var labelid = jQuery(this).data('lableid');
            jQuery("#my_custom_product_data").find('#draggable_' + labelid).css('height', jQuery(this).val());
        }
    });

    jQuery("#product_table_id tbody").on("change", ".label-field-link-to-num", function () {
        if (jQuery(this).val()) {
            jQuery("#my_custom_product_data").find('span.label-text').attr('value', jQuery(this).val());
        }

    });

    jQuery('div').click(function () {
        jQuery('#product_table_id').DataTable();
    });
});

jQuery(document).ready(function () {
    jQuery('.draggable-label').on('click', function () {
        var rowid = jQuery(this).data('lableid');
        jQuery('.tr_ass_pr_list').siblings().removeClass("highlight");
        jQuery.scrollTo(jQuery('#tr_ass_pr_list_id_' + rowid).addClass("highlight"), 1000);
    });
});

