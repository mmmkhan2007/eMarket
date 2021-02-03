/* =-=-=-= Copyright © 2018 eMarket =-=-=-= 
 |    GNU GENERAL PUBLIC LICENSE v.3.0    |
 |  https://github.com/musicman3/eMarket  |
 =-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-= */
/**
 * DiscountSale
 *
 * @package DiscountSales
 * @author eMarket
 * 
 */
class DiscountSale {
    /**
     * Outputting data to context menu
     * @param discounts_interface {Array} (input data)
     * @return output {Object} (output data)
     *
     */
    static context(discounts_interface) {

        var discounts = new Object();
        for (var key in discounts_interface[3]) {
            var discount_name = key.split('_');
            if (discount_name[0] === 'sale') {
                discounts[discount_name[1]] = discounts_interface[3][key];
            }
        }

        var discounts_options = '';
        for (key in discounts) {
            discounts_options = discounts_options + '<option value="' + key + '">' + discounts[key] + '</option>';
        }

        var discount_dafault = '';
        for (var key in discounts_interface[4]) {
            var default_id = key.split('_');
            if (default_id[0] === 'sale') {
                discount_dafault = default_id[1];
            }
        }

        var lang = discounts_interface[0];
        var parent_id = discounts_interface[1];
        var idsx_real_parent_id = discounts_interface[2];

        var output = {

            text: '<span class="bi-star"> ' + lang['modules_discount_sale_admin_button_sale'] + '</span>',
            disabled: discounts.length === 0 || $('div#ajax_data').data('jsondataproduct')['name'] === undefined && $('div#ajax_data').data('jsondatacategory')['name'] === undefined,
            subMenu: [
                {
                    text: '<span><select name="context-menu-input-sale">' + discounts_options + '</select></span>'
                },
                {
                    text: '<span class="bi-star-fill"> ' + lang['modules_discount_sale_admin_button_sale_on'] + '</span>',
                    action: function () {
                        var selected_id = $('select[name="context-menu-input-sale"] option:selected').val();
                        jQuery.ajaxSetup({async: false});
                        var idArray = [];
                        $(".option").each(function (i) {
                            if (!$(this).children().hasClass('inactive'))
                                idArray[i] = this.id;
                        });
                        jQuery.post(window.location.href,
                                {idsx_sale_on_id: idArray,
                                    idsx_real_parent_id: idsx_real_parent_id,
                                    sale: selected_id,
                                    idsx_sale_on_key: 'On'});
                        jQuery.get(window.location.href,
                                {parent_down: parent_id},
                                AjaxSuccess);
                    },
                    disabled: $('div#ajax_data').data('jsondataproduct')['name'] === undefined && $('div#ajax_data').data('jsondatacategory')['name'] === undefined

                },
                {
                    text: '<span class="bi-star"> ' + lang['modules_discount_sale_admin_button_sale_off'] + '</span>',
                    action: function () {
                        $('#confirm').modal('show');
                        $('#confirm_title').html(lang['modules_discount_sale_admin_attention']);
                        $('#confirm_body').html(lang['modules_discount_sale_admin_confirm_delete_sale']);
                        var selected_id = $('select[name="context-menu-input-sale"] option:selected').val();
                        confirmation.onclick = function () {
                            $('#confirm').modal('hide');

                            jQuery.ajaxSetup({async: false});
                            var idArray = [];
                            $(".option").each(function (i) {
                                if (!$(this).children().hasClass('inactive'))
                                    idArray[i] = this.id;
                            });
                            jQuery.post(window.location.href,
                                    {idsx_sale_off_id: idArray,
                                        idsx_real_parent_id: idsx_real_parent_id,
                                        sale: selected_id,
                                        idsx_sale_off_key: 'Off'});
                            jQuery.get(window.location.href,
                                    {parent_down: parent_id},
                                    AjaxSuccess);
                        };
                    },
                    disabled: $('div#ajax_data').data('jsondataproduct')['name'] === undefined && $('div#ajax_data').data('jsondatacategory')['name'] === undefined

                },
                {
                    text: '<span class="bi-lightning-fill"> ' + lang['modules_discount_sale_admin_button_sale_off_all'] + '</span>',
                    action: function () {
                        $('#confirm').modal('show');
                        $('#confirm_title').html(lang['modules_discount_sale_admin_attention']);
                        $('#confirm_body').html(lang['modules_discount_sale_admin_confirm_delete_sales']);

                        confirmation.onclick = function () {
                            $('#confirm').modal('hide');
                            var selected_id = $('select[name="context-menu-input-sale"] option:selected').val();
                            jQuery.ajaxSetup({async: false});
                            var idArray = [];
                            $(".option").each(function (i) {
                                if (!$(this).children().hasClass('inactive'))
                                    idArray[i] = this.id;
                            });
                            jQuery.post(window.location.href,
                                    {idsx_sale_off_all_id: idArray,
                                        idsx_real_parent_id: idsx_real_parent_id,
                                        idsx_sale_off_all_key: 'OffAll'});
                            jQuery.get(window.location.href,
                                    {parent_down: parent_id},
                                    AjaxSuccess);
                        };
                    },
                    disabled: $('div#ajax_data').data('jsondataproduct')['name'] === undefined && $('div#ajax_data').data('jsondatacategory')['name'] === undefined
                }
            ]
        };

        return output;
    }
}