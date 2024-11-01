<?php

/**
 * Fired during plugin activation
 *
 * @link       http://www.multidots.com/
 * @since      1.0.0
 *
 * @package    Woocommerce_Product_Attachment
 * @subpackage Woocommerce_Product_Attachment/includes
 */

/**
 * Fired during plugin activation.
 *
 * This class defines all code necessary to run during the plugin's activation.
 *
 * @since      1.0.0
 * @package    Woocommerce_Product_Attachment
 * @subpackage Woocommerce_Product_Attachment/includes
 * @author     multidots <nirav.soni@multidots.com>
 */
class Woocommerce_Product_Attachment_Activator {

    /**
     * Short Description. (use period)
     *
     * Long Description.
     *
     * @since    1.0.0
     */ 
    
    public static function activate() {
        set_transient( '_welcome_screen_activation_redirect_data', true, 30 );

        if(!get_option('wcpoa_product_tab_name')){
            add_option('wcpoa_product_tab_name', 'Attachment');
        }
        if(!get_option('wcpoa_order_tab_name')){
            add_option('wcpoa_order_tab_name', 'Attachment');
        }
        if(!get_option('wcpoa_admin_order_tab_name')){
            add_option('wcpoa_admin_order_tab_name', 'Attachment');
        }
        if(!get_option('wcpoa_att_btn_in_order_list')){
            add_option('wcpoa_att_btn_in_order_list', 'wcpoa_att_btn_in_order_list_enable');
        }
        if(!get_option('wcpoa_attachments_show_in_email')){
            add_option('wcpoa_attachments_show_in_email', 'yes');
        }
        if(!get_option('wcpoa_att_btn_position')){
            add_option('wcpoa_att_btn_position', 'wcpoa_att_btn_position_after');
        }
        if(!get_option('wcpoa_att_in_my_acc')){
            add_option('wcpoa_att_in_my_acc', 'wcpoa_att_in_my_acc_enable');
        }
        if(!get_option('wcpoa_att_in_thankyou')){
            add_option('wcpoa_att_in_thankyou', 'wcpoa_att_in_thankyou_enable');
        }
        if(!get_option('wcpoa_attachments_action_on_click')){
            add_option('wcpoa_attachments_action_on_click', 'download');
        }
        if(!get_option('wcpoa_att_download_btn')){
            add_option('wcpoa_att_download_btn', 'wcpoa_att_btn');
        }
        if(!get_option('wcpoa_att_btn_in_order_down_tab')){
            add_option('wcpoa_att_btn_in_order_down_tab', 'wcpoa_att_btn_in_order_down_tab_enable');
        }
        if(!get_option('wcpoa_expired_date_label')){
            add_option('wcpoa_expired_date_label', 'yes');
        }
        if(!get_option('wcpoa_product_download_type')){
            add_option('wcpoa_product_download_type', 'download_by_btn');
        }


        if (!in_array('woocommerce/woocommerce.php', apply_filters('active_plugins', get_option('active_plugins')),true) && !is_plugin_active_for_network('woocommerce/woocommerce.php')) {
            wp_die("<strong> WooCommerce Product Attachment</strong> Plugin requires <strong>WooCommerce</strong> <a href='" . esc_url(get_admin_url(null, 'plugins.php')) . "'>Plugins page</a>.");
        }
    }
}
