<?php
// If this file is called directly, abort.
if (!defined('WPINC')) {
    die;
}

// User role accessibility
$user = wp_get_current_user();

$wcpoa_att_download_restrict_flag = 0;
$wcpoa_att_download_restrict = get_option('wcpoa_att_download_restrict');
if ($wcpoa_att_download_restrict === 'wcpoa_att_download_loggedin') {
    if (is_user_logged_in()) {
        $wcpoa_att_download_restrict_flag = 1;
    } else {
        $wcpoa_att_download_restrict_flag = 0;
    }
} elseif ($wcpoa_att_download_restrict === 'wcpoa_att_download_guest') {
    $wcpoa_att_download_restrict_flag = 1;
}


$wcpoa_att_download_visible_user = $user->roles;
$prefixed_wcpoa_att_download_visible_user = preg_filter('/^/', 'wcpoa_att_download_', $wcpoa_att_download_visible_user);

if( empty($wcpoa_att_download_restrict)){
    $wcpoa_att_download_restrict_flag = 1; // apply for all users
} elseif (in_array('wcpoa_att_download_guest', $wcpoa_att_download_restrict,true) && !is_user_logged_in()){
    $wcpoa_att_download_restrict_flag = 1;  // apply for guest users
} elseif (array_intersect($prefixed_wcpoa_att_download_visible_user, $wcpoa_att_download_restrict)) {
    $wcpoa_att_download_restrict_flag = 1;  // apply for admin user roles which is set by admin side
}
    
if((int)$wcpoa_att_download_restrict_flag === 1 && $wcpoa_att_in_my_acc === "wcpoa_att_in_my_acc_enable"){
    $wcpoa_attachments_id_bulk = array();
    $attached_variations = array();
    if (!empty($items) && is_array($items)):
        foreach ($items as $item_id => $item) {
            $_product = wc_get_product( $item['product_id'] );
            if ( ! is_a( $_product, 'WC_Product' ) ) {
                return;
            }
            $wcpoa_order_attachment_items = wc_get_order_item_meta($item_id, 'wcpoa_order_attachment_order_arr', true);
            $wcpoa_order_product_variation_id = $item['variation_id'];
               
            if (!empty($wcpoa_order_attachment_items)) {
                $wcpoa_attachment_ids = isset($wcpoa_order_attachment_items['wcpoa_attachment_ids']) && !empty($wcpoa_order_attachment_items['wcpoa_attachment_ids']) ? $wcpoa_order_attachment_items['wcpoa_attachment_ids'] : '';
                $wcpoa_attachment_name = isset($wcpoa_order_attachment_items['wcpoa_attachment_name']) && !empty($wcpoa_order_attachment_items['wcpoa_attachment_name']) ? $wcpoa_order_attachment_items['wcpoa_attachment_name'] : '';
                $wcpoa_attachment_url = isset($wcpoa_order_attachment_items['wcpoa_attachment_url']) && !empty($wcpoa_order_attachment_items['wcpoa_attachment_url']) ? $wcpoa_order_attachment_items['wcpoa_attachment_url'] : '';
                $wcpoa_attach_type = isset($wcpoa_order_attachment_items['wcpoa_attach_type']) && !empty($wcpoa_order_attachment_items['wcpoa_attach_type']) ? $wcpoa_order_attachment_items['wcpoa_attach_type'] : '';
                $wcpoa_order_status = isset($wcpoa_order_attachment_items['wcpoa_order_status']) && !empty($wcpoa_order_attachment_items['wcpoa_order_status']) ? $wcpoa_order_attachment_items['wcpoa_order_status'] : '';
                $wcpoa_expired_date_enable = isset($wcpoa_order_attachment_items['wcpoa_expired_date_enable']) && !empty($wcpoa_order_attachment_items['wcpoa_expired_date_enable']) ? $wcpoa_order_attachment_items['wcpoa_expired_date_enable'] : '';
                $wcpoa_order_attachment_expired = isset($wcpoa_order_attachment_items['wcpoa_order_attachment_expired']) && !empty($wcpoa_order_attachment_items['wcpoa_order_attachment_expired']) ? $wcpoa_order_attachment_items['wcpoa_order_attachment_expired'] : '';
                $wcpoa_attachment_ext_url = isset($wcpoa_order_attachment_items['wcpoa_attachment_ext_url']) && !empty($wcpoa_order_attachment_items['wcpoa_attachment_ext_url']) ? $wcpoa_order_attachment_items['wcpoa_attachment_ext_url'] : '';
                $wcpoa_order_product_variation_value = isset($wcpoa_order_attachment_items['wcpoa_order_product_variation']) && !empty($wcpoa_order_attachment_items['wcpoa_order_product_variation']) ? $wcpoa_order_attachment_items['wcpoa_order_product_variation'] : array();
                $wcpoa_order_attachment_time_amount = isset($wcpoa_order_attachment_items['wcpoa_order_attachment_time_amount']) && !empty($wcpoa_order_attachment_items['wcpoa_order_attachment_time_amount']) ? $wcpoa_order_attachment_items['wcpoa_order_attachment_time_amount'] : '';
                $wcpoa_order_attachment_time_type = isset($wcpoa_order_attachment_items['wcpoa_order_attachment_time_type']) && !empty($wcpoa_order_attachment_items['wcpoa_order_attachment_time_type']) ? $wcpoa_order_attachment_items['wcpoa_order_attachment_time_type'] : '';

                if (!empty($wcpoa_order_product_variation_value) && is_array($wcpoa_order_product_variation_value)):
                    foreach ($wcpoa_order_product_variation_value as $var_list) {
                        if (!empty($var_list) && is_array($var_list))
                            foreach ($var_list as $var_id)
                                $attached_variations[] = $var_id;
                    }
                endif;

                $wcpoa_file_url_btn = '';
                $selected_variation_id = $item->get_variation_id();

                if (!empty($selected_variation_id) && is_array($attached_variations) && in_array((int)$selected_variation_id, convert_array_to_int($attached_variations),true)) {
                    if (!empty($wcpoa_attachment_ids) && is_array($wcpoa_attachment_ids)) {
                        //End Woo Product Attachment Order Tab   
                        foreach ($wcpoa_attachment_ids as $key => $wcpoa_attachments_id) {
                            if (!empty($wcpoa_attachments_id) || $wcpoa_attachments_id !== '') {
                                if (!in_array($wcpoa_attachments_id, $wcpoa_att_values_key,true)) {
                                    $attachment_name = isset($wcpoa_attachment_name[$key]) && !empty($wcpoa_attachment_name[$key]) ? $wcpoa_attachment_name[$key] : '';
                                    $wcpoa_order_product_variation = isset($wcpoa_order_product_variation_value[$wcpoa_attachments_id]) && !empty($wcpoa_order_product_variation_value[$wcpoa_attachments_id]) ? $wcpoa_order_product_variation_value[$wcpoa_attachments_id] : array();

                                    if (is_array($wcpoa_order_product_variation) && in_array((int)$selected_variation_id, convert_array_to_int($wcpoa_order_product_variation),true)) {

                                        $wcpoa_att_values_key[] = $wcpoa_attachments_id;
                                        $wcpoa_attachment_type = isset($wcpoa_attach_type[$key]) && !empty($wcpoa_attach_type[$key]) ? $wcpoa_attach_type[$key] : 'file_upload';
                                        $wcpoa_attachment_external_url = isset($wcpoa_attachment_ext_url[$key]) && !empty($wcpoa_attachment_ext_url[$key]) ? $wcpoa_attachment_ext_url[$key] : '';
                                        $wcpoa_attachment_file = isset($wcpoa_attachment_url[$key]) && !empty($wcpoa_attachment_url[$key]) ? $wcpoa_attachment_url[$key] : '';

                                        $wcpoa_order_status_val = isset($wcpoa_order_status[$wcpoa_attachments_id]) && !empty($wcpoa_order_status[$wcpoa_attachments_id]) && $wcpoa_order_status[$wcpoa_attachments_id] ? $wcpoa_order_status[$wcpoa_attachments_id] : array();
                                        $wcpoa_order_status_new = str_replace('wcpoa-wc-', '', $wcpoa_order_status_val);
                                        $wcpoa_expired_date_is_enable = isset($wcpoa_expired_date_enable[$key]) && !empty($wcpoa_expired_date_enable[$key]) ? $wcpoa_expired_date_enable[$key] : '';
                                        $wcpoa_order_attachment_expired_date = isset($wcpoa_order_attachment_expired[$key]) && !empty($wcpoa_order_attachment_expired[$key]) ? $wcpoa_order_attachment_expired[$key] : '';
                                        $wcpoa_order_attachment_exp_time_amount = isset($wcpoa_order_attachment_time_amount[$key]) && !empty($wcpoa_order_attachment_time_amount[$key]) ? $wcpoa_order_attachment_time_amount[$key] : '';
                                        $wcpoa_order_attachment_exp_time_type = isset($wcpoa_order_attachment_time_type[$key]) && !empty($wcpoa_order_attachment_time_type[$key]) ? $wcpoa_order_attachment_time_type[$key] : '';
                                        $wcpoa_order_attachment_time_amount_concate = $wcpoa_order_attachment_exp_time_amount." ".$wcpoa_order_attachment_exp_time_type; 
                                    $wcpoa_attachment_time_amount_concate_single = strtotime($wcpoa_order_attachment_time_amount_concate);

                                        $wcpoa_attachment_time_amount = isset($wcpoa_bulk_att_values['wcpoa_attachment_time_amount']) && !empty($wcpoa_bulk_att_values['wcpoa_attachment_time_amount']) ? $wcpoa_bulk_att_values['wcpoa_attachment_time_amount'] : '';
                                        $wcpoa_attachment_time_type = isset($wcpoa_bulk_att_values['wcpoa_attachment_time_type']) && !empty($wcpoa_bulk_att_values['wcpoa_attachment_time_type']) ? $wcpoa_bulk_att_values['wcpoa_attachment_time_type'] : '';
                                        $wcpoa_time_amount_concate = $wcpoa_attachment_time_amount." ".$wcpoa_attachment_time_type;


                                        $attachment_id = $wcpoa_attachment_file; // ID of attachment

                                        $wcpoa_attachment_expired_date = strtotime($wcpoa_order_attachment_expired_date);
                                        
                                        if($wcpoa_attachment_type === "file_upload"){
                                            $wcpoa_file_url_btn = get_permalink() . $wcpoa_attachment_url_arg . 'attachment_id=' . $attachment_id . '&download_file=' . $wcpoa_attachments_id . '&wcpoa_attachment_order_id=' . $items_order_id;
                                        }elseif($wcpoa_attachment_type === "external_ulr"){
                                            $wcpoa_file_url_btn = $wcpoa_attachment_external_url;
                                        }

                                        if (!empty($wcpoa_order_status_new)) {
                                            if (!empty($wcpoa_attachment_expired_date) && $wcpoa_expired_date_is_enable === "yes")
                                            {
                                                if ($wcpoa_today_date > $wcpoa_attachment_expired_date) {
                                                    if (in_array($items_order_status, $wcpoa_order_status_new,true)) {
                                                        if (!empty($wcpoa_order_product_variation_id) && in_array((int)$wcpoa_order_product_variation_id, convert_array_to_int($wcpoa_order_product_variation),true)) {
                                                            $wcpoa_attachment_down_tab = array();
                                                            $wcpoa_attachment_down_tab['is_managed_by_wcam'] = true;
                                                            $wcpoa_attachment_down_tab['is_order_attachment'] = true;
                                                            $wcpoa_attachment_down_tab['download_url'] =  '#wcpoa_expire';
                                                            $wcpoa_attachment_down_tab['customer-has-to-be-approved'] = true;
                                                            $wcpoa_attachment_down_tab['download_id'] = '';
                                                            $wcpoa_attachment_down_tab['download_name'] = __( 'Expired', 'woocommerce-product-attachment' );
                                                            $wcpoa_attachment_down_tab['name'] = '';
                                                            $wcpoa_attachment_down_tab['product_name'] = sprintf(__( 'Order #%s', 'woocommerce-product-attachment' ),$order_id);
                                                            $wcpoa_attachment_down_tab['order_id'] = '';
                                                            $wcpoa_attachment_down_tab['product_id'] = "";
                                                            $wcpoa_attachment_down_tab['product_url'] = $wcpoa_attachmen_order_url;
                                                            $wcpoa_attachment_down_tab['downloads_remaining'] = "";
                                                            $wcpoa_attachment_down_tab['order_key'] = "";
                                                            $wcpoa_attachment_down_tab['access_expires'] = null;
                                                            $wcpoa_attachment_down_tab['file'] = array();
                                                            $wcpoa_attachment_down_tab['file']['name'] = "";
                                                            $wcpoa_attachment_down_tab['access_expires'] = $wcpoa_order_attachment_expired_date;
                                                            $downloads[] = $wcpoa_attachment_down_tab;

                                                        }else{
                                                            $wcpoa_attachment_down_tab = array();
                                                            $wcpoa_attachment_down_tab['is_managed_by_wcam'] = true;
                                                            $wcpoa_attachment_down_tab['is_order_attachment'] = true;
                                                            $wcpoa_attachment_down_tab['download_url'] =  '#wcpoa_expire';
                                                            $wcpoa_attachment_down_tab['customer-has-to-be-approved'] = true;
                                                            $wcpoa_attachment_down_tab['download_id'] = '';
                                                            $wcpoa_attachment_down_tab['download_name'] = __( 'Expired', 'woocommerce-product-attachment' );
                                                            $wcpoa_attachment_down_tab['name'] = '';
                                                            $wcpoa_attachment_down_tab['product_name'] = sprintf(__( 'Order #%s', 'woocommerce-product-attachment' ),$order_id);
                                                            $wcpoa_attachment_down_tab['order_id'] = '';
                                                            $wcpoa_attachment_down_tab['product_id'] = "";
                                                            $wcpoa_attachment_down_tab['product_url'] = $wcpoa_attachmen_order_url;
                                                            $wcpoa_attachment_down_tab['downloads_remaining'] = "";
                                                            $wcpoa_attachment_down_tab['order_key'] = "";
                                                            $wcpoa_attachment_down_tab['access_expires'] = null;
                                                            $wcpoa_attachment_down_tab['file'] = array();
                                                            $wcpoa_attachment_down_tab['file']['name'] = "";
                                                            $wcpoa_attachment_down_tab['access_expires'] = $wcpoa_order_attachment_expired_date;
                                                            $downloads[] = $wcpoa_attachment_down_tab;
                                                        }
                                                    }
                                                } else {
                                                    if (in_array($items_order_status, $wcpoa_order_status_new,true)) {
                                                        if (!empty($wcpoa_order_product_variation_id) && in_array((int)$wcpoa_order_product_variation_id, convert_array_to_int($wcpoa_order_product_variation),true)) {
                                                            $wcpoa_attachment_down_tab = array();
                                                            $wcpoa_attachment_down_tab['is_managed_by_wcam'] = true;
                                                            $wcpoa_attachment_down_tab['is_order_attachment'] = true;
                                                            $wcpoa_attachment_down_tab['download_url'] =  $wcpoa_file_url_btn;
                                                            $wcpoa_attachment_down_tab['customer-has-to-be-approved'] = true;
                                                            $wcpoa_attachment_down_tab['download_id'] = '';
                                                            $wcpoa_attachment_down_tab['download_name'] = $attachment_name;
                                                            $wcpoa_attachment_down_tab['name'] = '';
                                                            $wcpoa_attachment_down_tab['product_name'] = sprintf(__( 'Order #%s', 'woocommerce-product-attachment' ),$order_id);
                                                            $wcpoa_attachment_down_tab['order_id'] = '';
                                                            $wcpoa_attachment_down_tab['product_id'] = "";
                                                            $wcpoa_attachment_down_tab['product_url'] = $wcpoa_attachmen_order_url;
                                                            $wcpoa_attachment_down_tab['downloads_remaining'] = "";
                                                            $wcpoa_attachment_down_tab['order_key'] = "";
                                                            $wcpoa_attachment_down_tab['access_expires'] = null;
                                                            $wcpoa_attachment_down_tab['file'] = array();
                                                            $wcpoa_attachment_down_tab['file']['name'] = "";
                                                            $wcpoa_attachment_down_tab['access_expires'] = $wcpoa_order_attachment_expired_date;
                                                            $downloads[] = $wcpoa_attachment_down_tab; 
                                                        } else {
                                                            $wcpoa_attachment_down_tab = array();
                                                            $wcpoa_attachment_down_tab['is_managed_by_wcam'] = true;
                                                            $wcpoa_attachment_down_tab['is_order_attachment'] = true;
                                                            $wcpoa_attachment_down_tab['download_url'] =  $wcpoa_file_url_btn;
                                                            $wcpoa_attachment_down_tab['customer-has-to-be-approved'] = true;
                                                            $wcpoa_attachment_down_tab['download_id'] = '';
                                                            $wcpoa_attachment_down_tab['download_name'] = $attachment_name;
                                                            $wcpoa_attachment_down_tab['name'] = '';
                                                            $wcpoa_attachment_down_tab['product_name'] = sprintf(__( 'Order #%s', 'woocommerce-product-attachment' ),$order_id);
                                                            $wcpoa_attachment_down_tab['order_id'] = '';
                                                            $wcpoa_attachment_down_tab['product_id'] = "";
                                                            $wcpoa_attachment_down_tab['product_url'] = $wcpoa_attachmen_order_url;
                                                            $wcpoa_attachment_down_tab['downloads_remaining'] = "";
                                                            $wcpoa_attachment_down_tab['order_key'] = "";
                                                            $wcpoa_attachment_down_tab['access_expires'] = null;
                                                            $wcpoa_attachment_down_tab['file'] = array();
                                                            $wcpoa_attachment_down_tab['file']['name'] = "";
                                                            $wcpoa_attachment_down_tab['access_expires'] = $wcpoa_order_attachment_expired_date;
                                                            $downloads[] = $wcpoa_attachment_down_tab;
                                                        }
                                                    }
                                                }
                                            } elseif (!empty($wcpoa_attachment_time_amount_concate_single) && $wcpoa_expired_date_is_enable === "time_amount") {

	                                            $wcpoa_single_duration = '+'.$wcpoa_order_attachment_time_amount_concate;
	                                            $wcpoa_attachment_expired_time = gmdate('Y/m/d H:i:s', strtotime($wcpoa_single_duration, strtotime($order_time)));

	                                            if ($wcpoa_today_date_time > $wcpoa_attachment_expired_time) {
	                                                if (in_array($items_order_status, $wcpoa_order_status_new,true)) {
	                                                    if (!empty($wcpoa_order_product_variation_id) && in_array((int)$wcpoa_order_product_variation_id, convert_array_to_int($wcpoa_order_product_variation),true)) {
	                                                        $wcpoa_attachment_down_tab = array();
	                                                        $wcpoa_attachment_down_tab['is_managed_by_wcam'] = true;
	                                                        $wcpoa_attachment_down_tab['is_order_attachment'] = true;
	                                                        $wcpoa_attachment_down_tab['download_url'] =  '#wcpoa_expire';
	                                                        $wcpoa_attachment_down_tab['customer-has-to-be-approved'] = true;
	                                                        $wcpoa_attachment_down_tab['download_id'] = '';
	                                                        $wcpoa_attachment_down_tab['download_name'] = __( 'Expired', 'woocommerce-product-attachment' );
	                                                        $wcpoa_attachment_down_tab['name'] = '';
	                                                        $wcpoa_attachment_down_tab['product_name'] = sprintf(__( 'Order #%s', 'woocommerce-product-attachment' ),$order_id);
	                                                        $wcpoa_attachment_down_tab['order_id'] = '';
	                                                        $wcpoa_attachment_down_tab['product_id'] = "";
	                                                        $wcpoa_attachment_down_tab['product_url'] = $wcpoa_attachmen_order_url;
	                                                        $wcpoa_attachment_down_tab['downloads_remaining'] = "";
	                                                        $wcpoa_attachment_down_tab['order_key'] = "";
	                                                        $wcpoa_attachment_down_tab['access_expires'] = null;
	                                                        $wcpoa_attachment_down_tab['file'] = array();
	                                                        $wcpoa_attachment_down_tab['file']['name'] = "";
	                                                        $wcpoa_attachment_down_tab['access_expires'] = $wcpoa_attachment_expired_time;
	                                                        $downloads[] = $wcpoa_attachment_down_tab; 
	                                                    } else {
	                                                        $wcpoa_attachment_down_tab = array();
	                                                        $wcpoa_attachment_down_tab['is_managed_by_wcam'] = true;
	                                                        $wcpoa_attachment_down_tab['is_order_attachment'] = true;
	                                                        $wcpoa_attachment_down_tab['download_url'] =  '#wcpoa_expire';
	                                                        $wcpoa_attachment_down_tab['customer-has-to-be-approved'] = true;
	                                                        $wcpoa_attachment_down_tab['download_id'] = '';
	                                                        $wcpoa_attachment_down_tab['download_name'] = __( 'Expired', 'woocommerce-product-attachment' );
	                                                        $wcpoa_attachment_down_tab['name'] = '';
	                                                        $wcpoa_attachment_down_tab['product_name'] = sprintf(__( 'Order #%s', 'woocommerce-product-attachment' ),$order_id);
	                                                        $wcpoa_attachment_down_tab['order_id'] = '';
	                                                        $wcpoa_attachment_down_tab['product_id'] = "";
	                                                        $wcpoa_attachment_down_tab['product_url'] = $wcpoa_attachmen_order_url;
	                                                        $wcpoa_attachment_down_tab['downloads_remaining'] = "";
	                                                        $wcpoa_attachment_down_tab['order_key'] = "";
	                                                        $wcpoa_attachment_down_tab['access_expires'] = null;
	                                                        $wcpoa_attachment_down_tab['file'] = array();
	                                                        $wcpoa_attachment_down_tab['file']['name'] = "";
	                                                        $wcpoa_attachment_down_tab['access_expires'] = $wcpoa_attachment_expired_time;
	                                                        $downloads[] = $wcpoa_attachment_down_tab; 
	                                                    }
	                                                }
	                                            }else{
	                                                if (in_array($items_order_status, $wcpoa_order_status_new,true)) {
	                                                    if (!empty($wcpoa_order_product_variation_id) && in_array((int)$wcpoa_order_product_variation_id, convert_array_to_int($wcpoa_order_product_variation),true)) {
	                                                        $wcpoa_attachment_down_tab = array();
	                                                        $wcpoa_attachment_down_tab['is_managed_by_wcam'] = true;
	                                                        $wcpoa_attachment_down_tab['is_order_attachment'] = true;
	                                                        $wcpoa_attachment_down_tab['download_url'] =  $wcpoa_file_url_btn;
	                                                        $wcpoa_attachment_down_tab['customer-has-to-be-approved'] = true;
	                                                        $wcpoa_attachment_down_tab['download_id'] = '';
	                                                        $wcpoa_attachment_down_tab['download_name'] = $attachment_name;
	                                                        $wcpoa_attachment_down_tab['name'] = '';
	                                                        $wcpoa_attachment_down_tab['product_name'] = sprintf(__( 'Order #%s', 'woocommerce-product-attachment' ),$order_id);
	                                                        $wcpoa_attachment_down_tab['order_id'] = '';
	                                                        $wcpoa_attachment_down_tab['product_id'] = "";
	                                                        $wcpoa_attachment_down_tab['product_url'] = $wcpoa_attachmen_order_url;
	                                                        $wcpoa_attachment_down_tab['downloads_remaining'] = "";
	                                                        $wcpoa_attachment_down_tab['order_key'] = "";
	                                                        $wcpoa_attachment_down_tab['access_expires'] = null;
	                                                        $wcpoa_attachment_down_tab['file'] = array();
	                                                        $wcpoa_attachment_down_tab['file']['name'] = "";
	                                                        $wcpoa_attachment_down_tab['access_expires'] = "";
	                                                        $downloads[] = $wcpoa_attachment_down_tab; 
	                                                    } else {
	                                                        $wcpoa_attachment_down_tab = array();
	                                                        $wcpoa_attachment_down_tab['is_managed_by_wcam'] = true;
	                                                        $wcpoa_attachment_down_tab['is_order_attachment'] = true;
	                                                        $wcpoa_attachment_down_tab['download_url'] =  $wcpoa_file_url_btn;
	                                                        $wcpoa_attachment_down_tab['customer-has-to-be-approved'] = true;
	                                                        $wcpoa_attachment_down_tab['download_id'] = '';
	                                                        $wcpoa_attachment_down_tab['download_name'] = $attachment_name;
	                                                        $wcpoa_attachment_down_tab['name'] = '';
	                                                        $wcpoa_attachment_down_tab['product_name'] = sprintf(__( 'Order #%s', 'woocommerce-product-attachment' ),$order_id);
	                                                        $wcpoa_attachment_down_tab['order_id'] = '';
	                                                        $wcpoa_attachment_down_tab['product_id'] = "";
	                                                        $wcpoa_attachment_down_tab['product_url'] = $wcpoa_attachmen_order_url;
	                                                        $wcpoa_attachment_down_tab['downloads_remaining'] = "";
	                                                        $wcpoa_attachment_down_tab['order_key'] = "";
	                                                        $wcpoa_attachment_down_tab['access_expires'] = null;
	                                                        $wcpoa_attachment_down_tab['file'] = array();
	                                                        $wcpoa_attachment_down_tab['file']['name'] = "";
	                                                        $wcpoa_attachment_down_tab['access_expires'] = "";
	                                                        $downloads[] = $wcpoa_attachment_down_tab; 
	                                                    }
	                                                }
	                                            }   
	                                        } else {
                                                if (in_array($items_order_status, $wcpoa_order_status_new,true)) {
                                                    if (!empty($wcpoa_order_product_variation_id) && in_array((int)$wcpoa_order_product_variation_id, convert_array_to_int($wcpoa_order_product_variation),true)) {
                                                        $wcpoa_attachment_down_tab = array();
                                                        $wcpoa_attachment_down_tab['is_managed_by_wcam'] = true;
                                                        $wcpoa_attachment_down_tab['is_order_attachment'] = true;
                                                        $wcpoa_attachment_down_tab['download_url'] =  $wcpoa_file_url_btn;
                                                        $wcpoa_attachment_down_tab['customer-has-to-be-approved'] = true;
                                                        $wcpoa_attachment_down_tab['download_id'] = '';
                                                        $wcpoa_attachment_down_tab['download_name'] = $attachment_name;
                                                        $wcpoa_attachment_down_tab['name'] = '';
                                                        $wcpoa_attachment_down_tab['product_name'] = sprintf(__( 'Order #%s', 'woocommerce-product-attachment' ),$order_id);
                                                        $wcpoa_attachment_down_tab['order_id'] = '';
                                                        $wcpoa_attachment_down_tab['product_id'] = "";
                                                        $wcpoa_attachment_down_tab['product_url'] = $wcpoa_attachmen_order_url;
                                                        $wcpoa_attachment_down_tab['downloads_remaining'] = "";
                                                        $wcpoa_attachment_down_tab['order_key'] = "";
                                                        $wcpoa_attachment_down_tab['access_expires'] = null;
                                                        $wcpoa_attachment_down_tab['file'] = array();
                                                        $wcpoa_attachment_down_tab['file']['name'] = "";
                                                        $wcpoa_attachment_down_tab['access_expires'] = "";
                                                        $downloads[] = $wcpoa_attachment_down_tab; 
                                                    } else {
                                                        $wcpoa_attachment_down_tab = array();
                                                        $wcpoa_attachment_down_tab['is_managed_by_wcam'] = true;
                                                        $wcpoa_attachment_down_tab['is_order_attachment'] = true;
                                                        $wcpoa_attachment_down_tab['download_url'] =  $wcpoa_file_url_btn;
                                                        $wcpoa_attachment_down_tab['customer-has-to-be-approved'] = true;
                                                        $wcpoa_attachment_down_tab['download_id'] = '';
                                                        $wcpoa_attachment_down_tab['download_name'] = $attachment_name;
                                                        $wcpoa_attachment_down_tab['name'] = '';
                                                        $wcpoa_attachment_down_tab['product_name'] = sprintf(__( 'Order #%s', 'woocommerce-product-attachment' ),$order_id);
                                                        $wcpoa_attachment_down_tab['order_id'] = '';
                                                        $wcpoa_attachment_down_tab['product_id'] = "";
                                                        $wcpoa_attachment_down_tab['product_url'] = $wcpoa_attachmen_order_url;
                                                        $wcpoa_attachment_down_tab['downloads_remaining'] = "";
                                                        $wcpoa_attachment_down_tab['order_key'] = "";
                                                        $wcpoa_attachment_down_tab['access_expires'] = null;
                                                        $wcpoa_attachment_down_tab['file'] = array();
                                                        $wcpoa_attachment_down_tab['file']['name'] = "";
                                                        $wcpoa_attachment_down_tab['access_expires'] = "";
                                                        $downloads[] = $wcpoa_attachment_down_tab; 
                                                    }
                                                }
                                            }
                                        } else {
                                            if (!empty($wcpoa_attachment_expired_date) && $wcpoa_expired_date_is_enable === "yes") {
                                                if ($wcpoa_today_date > $wcpoa_attachment_expired_date) {

                                                    $wcpoa_attachment_down_tab = array();
                                                    $wcpoa_attachment_down_tab['is_managed_by_wcam'] = true;
                                                    $wcpoa_attachment_down_tab['is_order_attachment'] = true;
                                                    $wcpoa_attachment_down_tab['download_url'] =  '#wcpoa_expire';
                                                    $wcpoa_attachment_down_tab['customer-has-to-be-approved'] = true;
                                                    $wcpoa_attachment_down_tab['download_id'] = '';
                                                    $wcpoa_attachment_down_tab['download_name'] = __( 'Expired', 'woocommerce-product-attachment' );
                                                    $wcpoa_attachment_down_tab['name'] = '';
                                                    $wcpoa_attachment_down_tab['product_name'] = sprintf(__( 'Order #%s', 'woocommerce-product-attachment' ),$order_id);
                                                    $wcpoa_attachment_down_tab['order_id'] = '';
                                                    $wcpoa_attachment_down_tab['product_id'] = "";
                                                    $wcpoa_attachment_down_tab['product_url'] = $wcpoa_attachmen_order_url;
                                                    $wcpoa_attachment_down_tab['downloads_remaining'] = "";
                                                    $wcpoa_attachment_down_tab['order_key'] = "";
                                                    $wcpoa_attachment_down_tab['access_expires'] = null;
                                                    $wcpoa_attachment_down_tab['file'] = array();
                                                    $wcpoa_attachment_down_tab['file']['name'] = "";
                                                    $wcpoa_attachment_down_tab['access_expires'] = $wcpoa_order_attachment_expired_date;
                                                    $downloads[] = $wcpoa_attachment_down_tab;

                                                }else{

                                                    $wcpoa_attachment_down_tab = array();
                                                    $wcpoa_attachment_down_tab['is_managed_by_wcam'] = true;
                                                    $wcpoa_attachment_down_tab['is_order_attachment'] = true;
                                                    $wcpoa_attachment_down_tab['download_url'] =  $wcpoa_file_url_btn;
                                                    $wcpoa_attachment_down_tab['customer-has-to-be-approved'] = true;
                                                    $wcpoa_attachment_down_tab['download_id'] = '';
                                                    $wcpoa_attachment_down_tab['download_name'] = $attachment_name;
                                                    $wcpoa_attachment_down_tab['name'] = '';
                                                    $wcpoa_attachment_down_tab['product_name'] = sprintf(__( 'Order #%s', 'woocommerce-product-attachment' ),$order_id);
                                                    $wcpoa_attachment_down_tab['order_id'] = '';
                                                    $wcpoa_attachment_down_tab['product_id'] = "";
                                                    $wcpoa_attachment_down_tab['product_url'] = $wcpoa_attachmen_order_url;
                                                    $wcpoa_attachment_down_tab['downloads_remaining'] = "";
                                                    $wcpoa_attachment_down_tab['order_key'] = "";
                                                    $wcpoa_attachment_down_tab['access_expires'] = null;
                                                    $wcpoa_attachment_down_tab['file'] = array();
                                                    $wcpoa_attachment_down_tab['file']['name'] = "";
                                                    $wcpoa_attachment_down_tab['access_expires'] = $wcpoa_order_attachment_expired_date;
                                                    $downloads[] = $wcpoa_attachment_down_tab;

                                                }
                                            } elseif (!empty($wcpoa_attachment_time_amount_concate_single) && $wcpoa_expired_date_is_enable === "time_amount") {
                                                $wcpoa_single_duration = '+'.$wcpoa_order_attachment_time_amount_concate;
                                                $wcpoa_attachment_expired_time = gmdate('Y/m/d H:i:s', strtotime($wcpoa_single_duration, strtotime($order_time)));
                                                if ($wcpoa_today_date_time > $wcpoa_attachment_expired_time) {
                                                    $wcpoa_attachment_down_tab = array();
                                                    $wcpoa_attachment_down_tab['is_managed_by_wcam'] = true;
                                                    $wcpoa_attachment_down_tab['is_order_attachment'] = true;
                                                    $wcpoa_attachment_down_tab['download_url'] =  '#wcpoa_expire';
                                                    $wcpoa_attachment_down_tab['customer-has-to-be-approved'] = true;
                                                    $wcpoa_attachment_down_tab['download_id'] = '';
                                                    $wcpoa_attachment_down_tab['download_name'] = __( 'Expired', 'woocommerce-product-attachment' );
                                                    $wcpoa_attachment_down_tab['name'] = '';
                                                    $wcpoa_attachment_down_tab['product_name'] = sprintf(__( 'Order #%s', 'woocommerce-product-attachment' ),$order_id);
                                                    $wcpoa_attachment_down_tab['order_id'] = '';
                                                    $wcpoa_attachment_down_tab['product_id'] = "";
                                                    $wcpoa_attachment_down_tab['product_url'] = $wcpoa_attachmen_order_url;
                                                    $wcpoa_attachment_down_tab['downloads_remaining'] = "";
                                                    $wcpoa_attachment_down_tab['order_key'] = "";
                                                    $wcpoa_attachment_down_tab['access_expires'] = null;
                                                    $wcpoa_attachment_down_tab['file'] = array();
                                                    $wcpoa_attachment_down_tab['file']['name'] = "";
                                                    $wcpoa_attachment_down_tab['access_expires'] = $wcpoa_attachment_expired_time;
                                                    $downloads[] = $wcpoa_attachment_down_tab;
                                                } else{
                                                    $wcpoa_attachment_down_tab = array();
                                                    $wcpoa_attachment_down_tab['is_managed_by_wcam'] = true;
                                                    $wcpoa_attachment_down_tab['is_order_attachment'] = true;
                                                    $wcpoa_attachment_down_tab['download_url'] =  $wcpoa_file_url_btn;
                                                    $wcpoa_attachment_down_tab['customer-has-to-be-approved'] = true;
                                                    $wcpoa_attachment_down_tab['download_id'] = '';
                                                    $wcpoa_attachment_down_tab['download_name'] = $attachment_name;
                                                    $wcpoa_attachment_down_tab['name'] = '';
                                                    $wcpoa_attachment_down_tab['product_name'] = sprintf(__( 'Order #%s', 'woocommerce-product-attachment' ),$order_id);
                                                    $wcpoa_attachment_down_tab['order_id'] = '';
                                                    $wcpoa_attachment_down_tab['product_id'] = "";
                                                    $wcpoa_attachment_down_tab['product_url'] = $wcpoa_attachmen_order_url;
                                                    $wcpoa_attachment_down_tab['downloads_remaining'] = "";
                                                    $wcpoa_attachment_down_tab['order_key'] = "";
                                                    $wcpoa_attachment_down_tab['access_expires'] = null;
                                                    $wcpoa_attachment_down_tab['file'] = array();
                                                    $wcpoa_attachment_down_tab['file']['name'] = "";
                                                    $wcpoa_attachment_down_tab['access_expires'] = $wcpoa_attachment_expired_time;
                                                    $downloads[] = $wcpoa_attachment_down_tab;
                                                } 
                                            } else {
                                                $wcpoa_attachment_down_tab = array();
                                                $wcpoa_attachment_down_tab['is_managed_by_wcam'] = true;
                                                $wcpoa_attachment_down_tab['is_order_attachment'] = true;
                                                $wcpoa_attachment_down_tab['download_url'] =  $wcpoa_file_url_btn;
                                                $wcpoa_attachment_down_tab['customer-has-to-be-approved'] = true;
                                                $wcpoa_attachment_down_tab['download_id'] = '';
                                                $wcpoa_attachment_down_tab['download_name'] = $attachment_name;
                                                $wcpoa_attachment_down_tab['name'] = '';
                                                $wcpoa_attachment_down_tab['product_name'] = sprintf(__( 'Order #%s', 'woocommerce-product-attachment' ),$order_id);
                                                $wcpoa_attachment_down_tab['order_id'] = '';
                                                $wcpoa_attachment_down_tab['product_id'] = "";
                                                $wcpoa_attachment_down_tab['product_url'] = $wcpoa_attachmen_order_url;
                                                $wcpoa_attachment_down_tab['downloads_remaining'] = "";
                                                $wcpoa_attachment_down_tab['order_key'] = "";
                                                $wcpoa_attachment_down_tab['access_expires'] = null;
                                                $wcpoa_attachment_down_tab['file'] = array();
                                                $wcpoa_attachment_down_tab['file']['name'] = "";
                                                $wcpoa_attachment_down_tab['access_expires'] = "";
                                                $downloads[] = $wcpoa_attachment_down_tab; 
                                            }
                                        }
                                    }
                                }
                            }
                        }
                    }       
                } else {
                    if (!empty($wcpoa_attachment_ids) && is_array($wcpoa_attachment_ids)) {
                        //End Woo Product Attachment Order Tab
                        foreach ($wcpoa_attachment_ids as $key => $wcpoa_attachments_id) {
                            if (!empty($wcpoa_attachments_id) || $wcpoa_attachments_id !== '') {
                                if (!in_array($wcpoa_attachments_id, $wcpoa_att_values_key,true)) {
                                    $wcpoa_att_values_key[] = $wcpoa_attachments_id;
                                    $attachment_name = isset($wcpoa_attachment_name[$key]) && !empty($wcpoa_attachment_name[$key]) ? $wcpoa_attachment_name[$key] : '';
                                    $wcpoa_attachment_type = isset($wcpoa_attach_type[$key]) && !empty($wcpoa_attach_type[$key]) ? $wcpoa_attach_type[$key] : 'file_upload';
                                    $wcpoa_attachment_file = isset($wcpoa_attachment_url[$key]) && !empty($wcpoa_attachment_url[$key]) ? $wcpoa_attachment_url[$key] : '';
                                    $wcpoa_order_status_val = isset($wcpoa_order_status[$wcpoa_attachments_id]) && !empty($wcpoa_order_status[$wcpoa_attachments_id]) && $wcpoa_order_status[$wcpoa_attachments_id] ? $wcpoa_order_status[$wcpoa_attachments_id] : array();
                                    $wcpoa_order_status_new = str_replace('wcpoa-wc-', '', $wcpoa_order_status_val);
                                    $wcpoa_expired_date_is_enable = isset($wcpoa_expired_date_enable[$key]) && !empty($wcpoa_expired_date_enable[$key]) ? $wcpoa_expired_date_enable[$key] : '';
                                    $wcpoa_order_attachment_expired_date = isset($wcpoa_order_attachment_expired[$key]) && !empty($wcpoa_order_attachment_expired[$key]) ? $wcpoa_order_attachment_expired[$key] : '';
                                    $wcpoa_attachment_external_url = isset($wcpoa_attachment_ext_url[$key]) && !empty($wcpoa_attachment_ext_url[$key]) ? $wcpoa_attachment_ext_url[$key] : '';
                                    $wcpoa_order_attachment_exp_time_amount = isset($wcpoa_order_attachment_time_amount[$key]) && !empty($wcpoa_order_attachment_time_amount[$key]) ? $wcpoa_order_attachment_time_amount[$key] : '';
                                    $wcpoa_order_attachment_exp_time_type = isset($wcpoa_order_attachment_time_type[$key]) && !empty($wcpoa_order_attachment_time_type[$key]) ? $wcpoa_order_attachment_time_type[$key] : '';
                                    $wcpoa_order_attachment_time_amount_concate = $wcpoa_order_attachment_exp_time_amount." ".$wcpoa_order_attachment_exp_time_type; 
                                    $wcpoa_attachment_time_amount_concate_single = strtotime($wcpoa_order_attachment_time_amount_concate);

                                    $attachment_id = $wcpoa_attachment_file; // ID of attachment

                                    $wcpoa_attachment_expired_date = strtotime($wcpoa_order_attachment_expired_date);
                                    
                                    if($wcpoa_attachment_type === "file_upload"){
                                        $wcpoa_file_url_btn = get_permalink() . $wcpoa_attachment_url_arg . 'attachment_id=' . $attachment_id . '&download_file=' . $wcpoa_attachments_id . '&wcpoa_attachment_order_id=' . $items_order_id;
                                    }elseif($wcpoa_attachment_type === "external_ulr"){
                                        $wcpoa_file_url_btn = $wcpoa_attachment_external_url;
                                    }

                                    $wcpoa_order_product_variation = isset($wcpoa_order_product_variation_value[$wcpoa_attachments_id]) && !empty($wcpoa_order_product_variation_value[$wcpoa_attachments_id]) ? $wcpoa_order_product_variation_value[$wcpoa_attachments_id] : array();

                                    if (!empty($wcpoa_order_status_new)) {
                                        if (!empty($wcpoa_attachment_expired_date) && $wcpoa_expired_date_is_enable === "yes") {
                                            if ($wcpoa_today_date > $wcpoa_attachment_expired_date) {
                                                if (in_array($items_order_status, $wcpoa_order_status_new,true)) {
                                                    if (!empty($wcpoa_order_product_variation_id) && in_array((int)$wcpoa_order_product_variation_id, convert_array_to_int($wcpoa_order_product_variation),true)) {
                                                        $wcpoa_attachment_down_tab = array();
                                                        $wcpoa_attachment_down_tab['is_managed_by_wcam'] = true;
                                                        $wcpoa_attachment_down_tab['is_order_attachment'] = true;
                                                        $wcpoa_attachment_down_tab['download_url'] =  '#wcpoa_expire';
                                                        $wcpoa_attachment_down_tab['customer-has-to-be-approved'] = true;
                                                        $wcpoa_attachment_down_tab['download_id'] = '';
                                                        $wcpoa_attachment_down_tab['download_name'] = __( 'Expired', 'woocommerce-product-attachment' );
                                                        $wcpoa_attachment_down_tab['name'] = '';
                                                        $wcpoa_attachment_down_tab['product_name'] = sprintf(__( 'Order #%s', 'woocommerce-product-attachment' ),$order_id);
                                                        $wcpoa_attachment_down_tab['order_id'] = '';
                                                        $wcpoa_attachment_down_tab['product_id'] = "";
                                                        $wcpoa_attachment_down_tab['product_url'] = $wcpoa_attachmen_order_url;
                                                        $wcpoa_attachment_down_tab['downloads_remaining'] = "";
                                                        $wcpoa_attachment_down_tab['order_key'] = "";
                                                        $wcpoa_attachment_down_tab['access_expires'] = null;
                                                        $wcpoa_attachment_down_tab['file'] = array();
                                                        $wcpoa_attachment_down_tab['file']['name'] = "";
                                                        $wcpoa_attachment_down_tab['access_expires'] = $wcpoa_order_attachment_expired_date;
                                                        $downloads[] = $wcpoa_attachment_down_tab; 
                                                    } else {
                                                        $wcpoa_attachment_down_tab = array();
                                                        $wcpoa_attachment_down_tab['is_managed_by_wcam'] = true;
                                                        $wcpoa_attachment_down_tab['is_order_attachment'] = true;
                                                        $wcpoa_attachment_down_tab['download_url'] =  '#wcpoa_expire';
                                                        $wcpoa_attachment_down_tab['customer-has-to-be-approved'] = true;
                                                        $wcpoa_attachment_down_tab['download_id'] = '';
                                                        $wcpoa_attachment_down_tab['download_name'] = __( 'Expired', 'woocommerce-product-attachment' );
                                                        $wcpoa_attachment_down_tab['name'] = '';
                                                        $wcpoa_attachment_down_tab['product_name'] = sprintf(__( 'Order #%s', 'woocommerce-product-attachment' ),$order_id);
                                                        $wcpoa_attachment_down_tab['order_id'] = '';
                                                        $wcpoa_attachment_down_tab['product_id'] = "";
                                                        $wcpoa_attachment_down_tab['product_url'] = $wcpoa_attachmen_order_url;
                                                        $wcpoa_attachment_down_tab['downloads_remaining'] = "";
                                                        $wcpoa_attachment_down_tab['order_key'] = "";
                                                        $wcpoa_attachment_down_tab['access_expires'] = null;
                                                        $wcpoa_attachment_down_tab['file'] = array();
                                                        $wcpoa_attachment_down_tab['file']['name'] = "";
                                                        $wcpoa_attachment_down_tab['access_expires'] = $wcpoa_order_attachment_expired_date;
                                                        $downloads[] = $wcpoa_attachment_down_tab; 
                                                    }
                                                }
                                            }else{
                                                if (in_array($items_order_status, $wcpoa_order_status_new,true)) {

                                                    if (!empty($wcpoa_order_product_variation_id) && in_array((int)$wcpoa_order_product_variation_id, convert_array_to_int($wcpoa_order_product_variation),true)) {
                                                        $wcpoa_attachment_down_tab = array();
                                                        $wcpoa_attachment_down_tab['is_managed_by_wcam'] = true;
                                                        $wcpoa_attachment_down_tab['is_order_attachment'] = true;
                                                        $wcpoa_attachment_down_tab['download_url'] =  $wcpoa_file_url_btn;
                                                        $wcpoa_attachment_down_tab['customer-has-to-be-approved'] = true;
                                                        $wcpoa_attachment_down_tab['download_id'] = '';
                                                        $wcpoa_attachment_down_tab['download_name'] = $attachment_name;
                                                        $wcpoa_attachment_down_tab['name'] = '';
                                                        $wcpoa_attachment_down_tab['product_name'] = sprintf(__( 'Order #%s', 'woocommerce-product-attachment' ),$order_id);
                                                        $wcpoa_attachment_down_tab['order_id'] = '';
                                                        $wcpoa_attachment_down_tab['product_id'] = "";
                                                        $wcpoa_attachment_down_tab['product_url'] = $wcpoa_attachmen_order_url;
                                                        $wcpoa_attachment_down_tab['downloads_remaining'] = "";
                                                        $wcpoa_attachment_down_tab['order_key'] = "";
                                                        $wcpoa_attachment_down_tab['access_expires'] = null;
                                                        $wcpoa_attachment_down_tab['file'] = array();
                                                        $wcpoa_attachment_down_tab['file']['name'] = "";
                                                        $wcpoa_attachment_down_tab['access_expires'] = "";
                                                        $downloads[] = $wcpoa_attachment_down_tab; 
                                                    } else {
                                                        $wcpoa_attachment_down_tab = array();
                                                        $wcpoa_attachment_down_tab['is_managed_by_wcam'] = true;
                                                        $wcpoa_attachment_down_tab['is_order_attachment'] = true;
                                                        $wcpoa_attachment_down_tab['download_url'] =  $wcpoa_file_url_btn;
                                                        $wcpoa_attachment_down_tab['customer-has-to-be-approved'] = true;
                                                        $wcpoa_attachment_down_tab['download_id'] = '';
                                                        $wcpoa_attachment_down_tab['download_name'] = $attachment_name;
                                                        $wcpoa_attachment_down_tab['name'] = '';
                                                        $wcpoa_attachment_down_tab['product_name'] = sprintf(__( 'Order #%s', 'woocommerce-product-attachment' ),$order_id);
                                                        $wcpoa_attachment_down_tab['order_id'] = '';
                                                        $wcpoa_attachment_down_tab['product_id'] = "";
                                                        $wcpoa_attachment_down_tab['product_url'] = $wcpoa_attachmen_order_url;
                                                        $wcpoa_attachment_down_tab['downloads_remaining'] = "";
                                                        $wcpoa_attachment_down_tab['order_key'] = "";
                                                        $wcpoa_attachment_down_tab['access_expires'] = null;
                                                        $wcpoa_attachment_down_tab['file'] = array();
                                                        $wcpoa_attachment_down_tab['file']['name'] = "";
                                                        $wcpoa_attachment_down_tab['access_expires'] = "";
                                                        $downloads[] = $wcpoa_attachment_down_tab; 
                                                    }   
                                                }
                                            }
                                        } elseif (!empty($wcpoa_attachment_time_amount_concate_single) && $wcpoa_expired_date_is_enable === "time_amount") {

                                            $wcpoa_single_duration = '+'.$wcpoa_order_attachment_time_amount_concate;
                                            $wcpoa_attachment_expired_time = gmdate('Y/m/d H:i:s', strtotime($wcpoa_single_duration, strtotime($order_time)));

                                            if ($wcpoa_today_date_time > $wcpoa_attachment_expired_time) {
                                                if (in_array($items_order_status, $wcpoa_order_status_new,true)) {
                                                    if (!empty($wcpoa_order_product_variation_id) && in_array((int)$wcpoa_order_product_variation_id, convert_array_to_int($wcpoa_order_product_variation),true)) {
                                                        $wcpoa_attachment_down_tab = array();
                                                        $wcpoa_attachment_down_tab['is_managed_by_wcam'] = true;
                                                        $wcpoa_attachment_down_tab['is_order_attachment'] = true;
                                                        $wcpoa_attachment_down_tab['download_url'] =  '#wcpoa_expire';
                                                        $wcpoa_attachment_down_tab['customer-has-to-be-approved'] = true;
                                                        $wcpoa_attachment_down_tab['download_id'] = '';
                                                        $wcpoa_attachment_down_tab['download_name'] = __( 'Expired', 'woocommerce-product-attachment' );
                                                        $wcpoa_attachment_down_tab['name'] = '';
                                                        $wcpoa_attachment_down_tab['product_name'] = sprintf(__( 'Order #%s', 'woocommerce-product-attachment' ),$order_id);
                                                        $wcpoa_attachment_down_tab['order_id'] = '';
                                                        $wcpoa_attachment_down_tab['product_id'] = "";
                                                        $wcpoa_attachment_down_tab['product_url'] = $wcpoa_attachmen_order_url;
                                                        $wcpoa_attachment_down_tab['downloads_remaining'] = "";
                                                        $wcpoa_attachment_down_tab['order_key'] = "";
                                                        $wcpoa_attachment_down_tab['access_expires'] = null;
                                                        $wcpoa_attachment_down_tab['file'] = array();
                                                        $wcpoa_attachment_down_tab['file']['name'] = "";
                                                        $wcpoa_attachment_down_tab['access_expires'] = $wcpoa_attachment_expired_time;
                                                        $downloads[] = $wcpoa_attachment_down_tab; 
                                                    } else {
                                                        $wcpoa_attachment_down_tab = array();
                                                        $wcpoa_attachment_down_tab['is_managed_by_wcam'] = true;
                                                        $wcpoa_attachment_down_tab['is_order_attachment'] = true;
                                                        $wcpoa_attachment_down_tab['download_url'] =  '#wcpoa_expire';
                                                        $wcpoa_attachment_down_tab['customer-has-to-be-approved'] = true;
                                                        $wcpoa_attachment_down_tab['download_id'] = '';
                                                        $wcpoa_attachment_down_tab['download_name'] = __( 'Expired', 'woocommerce-product-attachment' );
                                                        $wcpoa_attachment_down_tab['name'] = '';
                                                        $wcpoa_attachment_down_tab['product_name'] = sprintf(__( 'Order #%s', 'woocommerce-product-attachment' ),$order_id);
                                                        $wcpoa_attachment_down_tab['order_id'] = '';
                                                        $wcpoa_attachment_down_tab['product_id'] = "";
                                                        $wcpoa_attachment_down_tab['product_url'] = $wcpoa_attachmen_order_url;
                                                        $wcpoa_attachment_down_tab['downloads_remaining'] = "";
                                                        $wcpoa_attachment_down_tab['order_key'] = "";
                                                        $wcpoa_attachment_down_tab['access_expires'] = null;
                                                        $wcpoa_attachment_down_tab['file'] = array();
                                                        $wcpoa_attachment_down_tab['file']['name'] = "";
                                                        $wcpoa_attachment_down_tab['access_expires'] = $wcpoa_attachment_expired_time;
                                                        $downloads[] = $wcpoa_attachment_down_tab; 
                                                    }
                                                }
                                            }else{
                                                if (in_array($items_order_status, $wcpoa_order_status_new,true)) {
                                                    if (!empty($wcpoa_order_product_variation_id) && in_array((int)$wcpoa_order_product_variation_id, convert_array_to_int($wcpoa_order_product_variation),true)) {
                                                        $wcpoa_attachment_down_tab = array();
                                                        $wcpoa_attachment_down_tab['is_managed_by_wcam'] = true;
                                                        $wcpoa_attachment_down_tab['is_order_attachment'] = true;
                                                        $wcpoa_attachment_down_tab['download_url'] =  $wcpoa_file_url_btn;
                                                        $wcpoa_attachment_down_tab['customer-has-to-be-approved'] = true;
                                                        $wcpoa_attachment_down_tab['download_id'] = '';
                                                        $wcpoa_attachment_down_tab['download_name'] = $attachment_name;
                                                        $wcpoa_attachment_down_tab['name'] = '';
                                                        $wcpoa_attachment_down_tab['product_name'] = sprintf(__( 'Order #%s', 'woocommerce-product-attachment' ),$order_id);
                                                        $wcpoa_attachment_down_tab['order_id'] = '';
                                                        $wcpoa_attachment_down_tab['product_id'] = "";
                                                        $wcpoa_attachment_down_tab['product_url'] = $wcpoa_attachmen_order_url;
                                                        $wcpoa_attachment_down_tab['downloads_remaining'] = "";
                                                        $wcpoa_attachment_down_tab['order_key'] = "";
                                                        $wcpoa_attachment_down_tab['access_expires'] = null;
                                                        $wcpoa_attachment_down_tab['file'] = array();
                                                        $wcpoa_attachment_down_tab['file']['name'] = "";
                                                        $wcpoa_attachment_down_tab['access_expires'] = "";
                                                        $downloads[] = $wcpoa_attachment_down_tab; 
                                                    } else {
                                                        $wcpoa_attachment_down_tab = array();
                                                        $wcpoa_attachment_down_tab['is_managed_by_wcam'] = true;
                                                        $wcpoa_attachment_down_tab['is_order_attachment'] = true;
                                                        $wcpoa_attachment_down_tab['download_url'] =  $wcpoa_file_url_btn;
                                                        $wcpoa_attachment_down_tab['customer-has-to-be-approved'] = true;
                                                        $wcpoa_attachment_down_tab['download_id'] = '';
                                                        $wcpoa_attachment_down_tab['download_name'] = $attachment_name;
                                                        $wcpoa_attachment_down_tab['name'] = '';
                                                        $wcpoa_attachment_down_tab['product_name'] = sprintf(__( 'Order #%s', 'woocommerce-product-attachment' ),$order_id);
                                                        $wcpoa_attachment_down_tab['order_id'] = '';
                                                        $wcpoa_attachment_down_tab['product_id'] = "";
                                                        $wcpoa_attachment_down_tab['product_url'] = $wcpoa_attachmen_order_url;
                                                        $wcpoa_attachment_down_tab['downloads_remaining'] = "";
                                                        $wcpoa_attachment_down_tab['order_key'] = "";
                                                        $wcpoa_attachment_down_tab['access_expires'] = null;
                                                        $wcpoa_attachment_down_tab['file'] = array();
                                                        $wcpoa_attachment_down_tab['file']['name'] = "";
                                                        $wcpoa_attachment_down_tab['access_expires'] = "";
                                                        $downloads[] = $wcpoa_attachment_down_tab; 
                                                    }
                                                }
                                            }   
                                        } else {
                                            if (in_array($items_order_status, $wcpoa_order_status_new,true)) {
                                                if (!empty($wcpoa_order_product_variation_id) && in_array((int)$wcpoa_order_product_variation_id, convert_array_to_int($wcpoa_order_product_variation),true)) {
                                                    $wcpoa_attachment_down_tab = array();
                                                    $wcpoa_attachment_down_tab['is_managed_by_wcam'] = true;
                                                    $wcpoa_attachment_down_tab['is_order_attachment'] = true;
                                                    $wcpoa_attachment_down_tab['download_url'] =  $wcpoa_file_url_btn;
                                                    $wcpoa_attachment_down_tab['customer-has-to-be-approved'] = true;
                                                    $wcpoa_attachment_down_tab['download_id'] = '';
                                                    $wcpoa_attachment_down_tab['download_name'] = $attachment_name;
                                                    $wcpoa_attachment_down_tab['name'] = '';
                                                    $wcpoa_attachment_down_tab['product_name'] = sprintf(__( 'Order #%s', 'woocommerce-product-attachment' ),$order_id);
                                                    $wcpoa_attachment_down_tab['order_id'] = '';
                                                    $wcpoa_attachment_down_tab['product_id'] = "";
                                                    $wcpoa_attachment_down_tab['product_url'] = $wcpoa_attachmen_order_url;
                                                    $wcpoa_attachment_down_tab['downloads_remaining'] = "";
                                                    $wcpoa_attachment_down_tab['order_key'] = "";
                                                    $wcpoa_attachment_down_tab['access_expires'] = null;
                                                    $wcpoa_attachment_down_tab['file'] = array();
                                                    $wcpoa_attachment_down_tab['file']['name'] = "";
                                                    $wcpoa_attachment_down_tab['access_expires'] = "";
                                                    $downloads[] = $wcpoa_attachment_down_tab; 
                                                } else {
                                                    $wcpoa_attachment_down_tab = array();
                                                    $wcpoa_attachment_down_tab['is_managed_by_wcam'] = true;
                                                    $wcpoa_attachment_down_tab['is_order_attachment'] = true;
                                                    $wcpoa_attachment_down_tab['download_url'] =  $wcpoa_file_url_btn;
                                                    $wcpoa_attachment_down_tab['customer-has-to-be-approved'] = true;
                                                    $wcpoa_attachment_down_tab['download_id'] = '';
                                                    $wcpoa_attachment_down_tab['download_name'] = $attachment_name;
                                                    $wcpoa_attachment_down_tab['name'] = '';
                                                    $wcpoa_attachment_down_tab['product_name'] = sprintf(__( 'Order #%s', 'woocommerce-product-attachment' ),$order_id);
                                                    $wcpoa_attachment_down_tab['order_id'] = '';
                                                    $wcpoa_attachment_down_tab['product_id'] = "";
                                                    $wcpoa_attachment_down_tab['product_url'] = $wcpoa_attachmen_order_url;
                                                    $wcpoa_attachment_down_tab['downloads_remaining'] = "";
                                                    $wcpoa_attachment_down_tab['order_key'] = "";
                                                    $wcpoa_attachment_down_tab['access_expires'] = null;
                                                    $wcpoa_attachment_down_tab['file'] = array();
                                                    $wcpoa_attachment_down_tab['file']['name'] = "";
                                                    $wcpoa_attachment_down_tab['access_expires'] = "";
                                                    $downloads[] = $wcpoa_attachment_down_tab; 
                                                }
                                            }   
                                        }
                                    } else {
                                        if (!empty($wcpoa_attachment_expired_date) && $wcpoa_expired_date_is_enable === "yes") {
                                            if ($wcpoa_today_date > $wcpoa_attachment_expired_date) {
                                                $wcpoa_attachment_down_tab = array();
                                                $wcpoa_attachment_down_tab['is_managed_by_wcam'] = true;
                                                $wcpoa_attachment_down_tab['is_order_attachment'] = true;
                                                $wcpoa_attachment_down_tab['download_url'] =  '#wcpoa_expire';
                                                $wcpoa_attachment_down_tab['customer-has-to-be-approved'] = true;
                                                $wcpoa_attachment_down_tab['download_id'] = '';
                                                $wcpoa_attachment_down_tab['download_name'] = __( 'Expired', 'woocommerce-product-attachment' );
                                                $wcpoa_attachment_down_tab['name'] = '';
                                                $wcpoa_attachment_down_tab['product_name'] = sprintf(__( 'Order #%s', 'woocommerce-product-attachment' ),$order_id);
                                                $wcpoa_attachment_down_tab['order_id'] = '';
                                                $wcpoa_attachment_down_tab['product_id'] = "";
                                                $wcpoa_attachment_down_tab['product_url'] = $wcpoa_attachmen_order_url;
                                                $wcpoa_attachment_down_tab['downloads_remaining'] = "";
                                                $wcpoa_attachment_down_tab['order_key'] = "";
                                                $wcpoa_attachment_down_tab['access_expires'] = null;
                                                $wcpoa_attachment_down_tab['file'] = array();
                                                $wcpoa_attachment_down_tab['file']['name'] = "";
                                                $wcpoa_attachment_down_tab['access_expires'] = $wcpoa_order_attachment_expired_date;
                                                $downloads[] = $wcpoa_attachment_down_tab;
                                            }else{
                                                $wcpoa_attachment_down_tab = array();
                                                $wcpoa_attachment_down_tab['is_managed_by_wcam'] = true;
                                                $wcpoa_attachment_down_tab['is_order_attachment'] = true;
                                                $wcpoa_attachment_down_tab['download_url'] =  $wcpoa_file_url_btn;
                                                $wcpoa_attachment_down_tab['customer-has-to-be-approved'] = true;
                                                $wcpoa_attachment_down_tab['download_id'] = '';
                                                $wcpoa_attachment_down_tab['download_name'] = $attachment_name;
                                                $wcpoa_attachment_down_tab['name'] = '';
                                                $wcpoa_attachment_down_tab['product_name'] = sprintf(__( 'Order #%s', 'woocommerce-product-attachment' ),$order_id);
                                                $wcpoa_attachment_down_tab['order_id'] = '';
                                                $wcpoa_attachment_down_tab['product_id'] = "";
                                                $wcpoa_attachment_down_tab['product_url'] = $wcpoa_attachmen_order_url;
                                                $wcpoa_attachment_down_tab['downloads_remaining'] = "";
                                                $wcpoa_attachment_down_tab['order_key'] = "";
                                                $wcpoa_attachment_down_tab['access_expires'] = null;
                                                $wcpoa_attachment_down_tab['file'] = array();
                                                $wcpoa_attachment_down_tab['file']['name'] = "";
                                                $wcpoa_attachment_down_tab['access_expires'] = $wcpoa_order_attachment_expired_date;
                                                $downloads[] = $wcpoa_attachment_down_tab;
                                            }
                                        }elseif (!empty($wcpoa_attachment_time_amount_concate_single) && $wcpoa_expired_date_is_enable === "time_amount") {
                                            $wcpoa_single_duration = '+'.$wcpoa_order_attachment_time_amount_concate;
                                            $wcpoa_attachment_expired_time = gmdate('Y/m/d H:i:s', strtotime($wcpoa_single_duration, strtotime($order_time)));
                                            if ($wcpoa_today_date_time > $wcpoa_attachment_expired_time) {
                                                $wcpoa_attachment_down_tab = array();
                                                $wcpoa_attachment_down_tab['is_managed_by_wcam'] = true;
                                                $wcpoa_attachment_down_tab['is_order_attachment'] = true;
                                                $wcpoa_attachment_down_tab['download_url'] =  '#wcpoa_expire';
                                                $wcpoa_attachment_down_tab['customer-has-to-be-approved'] = true;
                                                $wcpoa_attachment_down_tab['download_id'] = '';
                                                $wcpoa_attachment_down_tab['download_name'] = __( 'Expired', 'woocommerce-product-attachment' );
                                                $wcpoa_attachment_down_tab['name'] = '';
                                                $wcpoa_attachment_down_tab['product_name'] = sprintf(__( 'Order #%s', 'woocommerce-product-attachment' ),$order_id);
                                                $wcpoa_attachment_down_tab['order_id'] = '';
                                                $wcpoa_attachment_down_tab['product_id'] = "";
                                                $wcpoa_attachment_down_tab['product_url'] = $wcpoa_attachmen_order_url;
                                                $wcpoa_attachment_down_tab['downloads_remaining'] = "";
                                                $wcpoa_attachment_down_tab['order_key'] = "";
                                                $wcpoa_attachment_down_tab['access_expires'] = null;
                                                $wcpoa_attachment_down_tab['file'] = array();
                                                $wcpoa_attachment_down_tab['file']['name'] = "";
                                                $wcpoa_attachment_down_tab['access_expires'] = $wcpoa_attachment_expired_time;
                                                $downloads[] = $wcpoa_attachment_down_tab;
                                            }else{
                                                $wcpoa_attachment_down_tab = array();
                                                $wcpoa_attachment_down_tab['is_managed_by_wcam'] = true;
                                                $wcpoa_attachment_down_tab['is_order_attachment'] = true;
                                                $wcpoa_attachment_down_tab['download_url'] =  $wcpoa_file_url_btn;
                                                $wcpoa_attachment_down_tab['customer-has-to-be-approved'] = true;
                                                $wcpoa_attachment_down_tab['download_id'] = '';
                                                $wcpoa_attachment_down_tab['download_name'] = $attachment_name;
                                                $wcpoa_attachment_down_tab['name'] = '';
                                                $wcpoa_attachment_down_tab['product_name'] = sprintf(__( 'Order #%s', 'woocommerce-product-attachment' ),$order_id);
                                                $wcpoa_attachment_down_tab['order_id'] = '';
                                                $wcpoa_attachment_down_tab['product_id'] = "";
                                                $wcpoa_attachment_down_tab['product_url'] = $wcpoa_attachmen_order_url;
                                                $wcpoa_attachment_down_tab['downloads_remaining'] = "";
                                                $wcpoa_attachment_down_tab['order_key'] = "";
                                                $wcpoa_attachment_down_tab['access_expires'] = null;
                                                $wcpoa_attachment_down_tab['file'] = array();
                                                $wcpoa_attachment_down_tab['file']['name'] = "";
                                                $wcpoa_attachment_down_tab['access_expires'] = $wcpoa_attachment_expired_time;
                                                $downloads[] = $wcpoa_attachment_down_tab;
                                            } 
                                        } else {
                                            $wcpoa_attachment_down_tab = array();
                                            $wcpoa_attachment_down_tab['is_managed_by_wcam'] = true;
                                            $wcpoa_attachment_down_tab['is_order_attachment'] = true;
                                            $wcpoa_attachment_down_tab['download_url'] =  $wcpoa_file_url_btn;
                                            $wcpoa_attachment_down_tab['customer-has-to-be-approved'] = true;
                                            $wcpoa_attachment_down_tab['download_id'] = '';
                                            $wcpoa_attachment_down_tab['download_name'] = $attachment_name;
                                            $wcpoa_attachment_down_tab['name'] = '';
                                            $wcpoa_attachment_down_tab['product_name'] = sprintf(__( 'Order #%s', 'woocommerce-product-attachment' ),$order_id);
                                            $wcpoa_attachment_down_tab['order_id'] = '';
                                            $wcpoa_attachment_down_tab['product_id'] = "";
                                            $wcpoa_attachment_down_tab['product_url'] = $wcpoa_attachmen_order_url;
                                            $wcpoa_attachment_down_tab['downloads_remaining'] = "";
                                            $wcpoa_attachment_down_tab['order_key'] = "";
                                            $wcpoa_attachment_down_tab['access_expires'] = null;
                                            $wcpoa_attachment_down_tab['file'] = array();
                                            $wcpoa_attachment_down_tab['file']['name'] = "";
                                            $wcpoa_attachment_down_tab['access_expires'] = "";
                                            $downloads[] = $wcpoa_attachment_down_tab; 
                                        }
                                    }
                                }
                            }
                        }
                    }
                }
            }

            // Get the Bulk Attachments
            if (!empty($items)) {
                $product_terms = get_the_terms($item['product_id'], 'product_cat'); //Product Category Get
                if (!empty($wcpoa_bulk_att_data)) {
                    foreach ($wcpoa_bulk_att_data as $att_new_key => $wcpoa_bulk_att_values) {
                        if( (!array_key_exists('wcpoa_attach_view', $wcpoa_bulk_att_values)) || "enable" === $wcpoa_bulk_att_values['wcpoa_attach_view'] ){
                            if (!in_array($att_new_key, $wcpoa_bulk_att_product_key,true)) {

                                $wcpoa_bulk_applied_tag = !empty($wcpoa_bulk_att_values['wcpoa_tag_list']) ? $wcpoa_bulk_att_values['wcpoa_tag_list'] : array();
                                $wcpoa_bulk_applied_attributes = !empty($wcpoa_bulk_att_values['wcpoa_attributes_list']) ? $wcpoa_bulk_att_values['wcpoa_attributes_list'] : array();

                                $tag_terms = get_the_terms($item['product_id'], 'product_tag'); //Product tag Get
                                $product_tag_ids = array();
                                if(!empty($tag_terms)){
                                    foreach ($tag_terms as $tag_term) {
                                        $product_tag_ids[] = $tag_term->term_id;
                                    }                                    
                                }

                                $attribute_taxonomies = $_product->get_attributes();
                                $product_attribute_ids = array();
                                $product_attribute_name = array();
                                if(!empty($attribute_taxonomies) && is_array($attribute_taxonomies)){
                                    foreach ( $attribute_taxonomies as $attribute ) {
                                        $product_attribute_name[] = $attribute->get_name();
                                    }
                                }
                                if(!empty($product_attribute_name) && is_array($product_attribute_name)){
                                    foreach ($product_attribute_name as $key => $attribute_name) {
                                        if (strpos($attribute_name,'pa_') === false) {
                                            unset($product_attribute_name[$key]);
                                        }
                                    }

                                    $attributes_terms = array();
                                    foreach ($product_attribute_name as $attribute_name) {
                                        $attributes_terms[] = get_the_terms($item['product_id'], $attribute_name); //Product Attributes Get
                                    }

                                    if(!empty($attributes_terms) && is_array($attributes_terms)){
                                        foreach ($attributes_terms as $attributes_term) {
                                            foreach($attributes_term as $attribute_term) {
                                                $product_attribute_ids[] = $attribute_term->term_id;
                                            }
                                        }                                    
                                    }
                                }

                                $product_cats_id = array();
                                if (!empty($product_terms)) {
                                    foreach ($product_terms as $product_term) {
                                        $product_cats_id[] = $product_term->term_id;
                                    }
                                }
                                $wcpoa_bulk_att_visibility = isset($wcpoa_bulk_att_values['wcpoa_att_visibility']) && !empty($wcpoa_bulk_att_values['wcpoa_att_visibility']) ? $wcpoa_bulk_att_values['wcpoa_att_visibility'] : '';
                                $wcpoa_is_condition=isset($wcpoa_bulk_att_values['wcpoa_is_condition']) && !empty($wcpoa_bulk_att_values['wcpoa_is_condition']) ? $wcpoa_bulk_att_values['wcpoa_is_condition'] : '';

                                $wcpoa_bulk_applied_cat = !empty($wcpoa_bulk_att_values['wcpoa_category_list']) ? $wcpoa_bulk_att_values['wcpoa_category_list'] : array();

                                $wcpoa_bulk_apply_cat = !empty($wcpoa_bulk_att_values['wcpoa_apply_cat']) ? $wcpoa_bulk_att_values['wcpoa_apply_cat'] : array();

                                $child_terms = array();
                                if($wcpoa_bulk_apply_cat!=='wcpoa_cat_selected_only'){
                                    foreach($wcpoa_bulk_applied_cat as $value){
                                        $child_terms=get_term_children( $value, 'product_cat' ); 
                                    }
                                    $wcpoa_bulk_applied_cat=array_merge($wcpoa_bulk_applied_cat,$child_terms);
                                }
                    
                                $wcpoa_assignment = !empty($wcpoa_bulk_att_values['wcpoa_assignment']) ? $wcpoa_bulk_att_values['wcpoa_assignment'] : array();
                                
                                $wcpoa_bulk_applied_product = !empty($wcpoa_bulk_att_values['wcpoa_product_list']) ? $wcpoa_bulk_att_values['wcpoa_product_list'] : array();

                                $wcpoa_attachments_bulk_id = !empty($wcpoa_bulk_att_values['wcpoa_attachments_id']) ? $wcpoa_bulk_att_values['wcpoa_attachments_id'] : '';
                                $wcpoa_bulk_attachments_name = isset($wcpoa_bulk_att_values['wcpoa_attachment_name']) && !empty($wcpoa_bulk_att_values['wcpoa_attachment_name']) ? $wcpoa_bulk_att_values['wcpoa_attachment_name'] : '';

                                $wcpoa_bulk_attachment_type = isset($wcpoa_bulk_att_values['wcpoa_attach_type']) && !empty($wcpoa_bulk_att_values['wcpoa_attach_type']) ? $wcpoa_bulk_att_values['wcpoa_attach_type'] : '';

                                if($wcpoa_bulk_attachment_type === "file_upload"){
                                    $wcpoa_bulk_attachment_file = isset($wcpoa_bulk_att_values['wcpoa_attachment_file']) && !empty($wcpoa_bulk_att_values['wcpoa_attachment_file']) ? $wcpoa_bulk_att_values['wcpoa_attachment_file'] : '';
                                }elseif($wcpoa_bulk_attachment_type === "external_ulr"){
                                    $wcpoa_bulk_attachment_url = isset($wcpoa_bulk_att_values['wcpoa_attachment_url']) && !empty($wcpoa_bulk_att_values['wcpoa_attachment_url']) ? $wcpoa_bulk_att_values['wcpoa_attachment_url'] : '';
                                }
                                $wcpoa_expired_date_enable = isset($wcpoa_bulk_att_values['wcpoa_expired_date_enable']) && !empty($wcpoa_bulk_att_values['wcpoa_expired_date_enable']) ? $wcpoa_bulk_att_values['wcpoa_expired_date_enable'] : '';

                                $wcpoa_expired_dates = isset($wcpoa_bulk_att_values['wcpoa_expired_date']) && !empty($wcpoa_bulk_att_values['wcpoa_expired_date']) ? $wcpoa_bulk_att_values['wcpoa_expired_date'] : '';
                                $wcpoa_attachment_time_amount = isset($wcpoa_bulk_att_values['wcpoa_attachment_time_amount']) && !empty($wcpoa_bulk_att_values['wcpoa_attachment_time_amount']) ? $wcpoa_bulk_att_values['wcpoa_attachment_time_amount'] : '';
                                $wcpoa_attachment_time_type = isset($wcpoa_bulk_att_values['wcpoa_attachment_time_type']) && !empty($wcpoa_bulk_att_values['wcpoa_attachment_time_type']) ? $wcpoa_bulk_att_values['wcpoa_attachment_time_type'] : '';
                                $wcpoa_time_amount_concate = $wcpoa_attachment_time_amount." ".$wcpoa_attachment_time_type; 

                                $wcpoa_order_status = isset($wcpoa_bulk_att_values['wcpoa_order_status']) && !empty($wcpoa_bulk_att_values['wcpoa_order_status']) ? $wcpoa_bulk_att_values['wcpoa_order_status'] : '';

                                if($wcpoa_bulk_attachment_type === "file_upload"){
                                    $wcpoa_bulk_file_url_btn = get_permalink() . $wcpoa_attachment_url_arg . 'attachment_id=' . $wcpoa_bulk_attachment_file . '&download_file=' . $wcpoa_attachments_bulk_id . '&wcpoa_attachment_order_id=' . $items_order_id;
                                }elseif($wcpoa_bulk_attachment_type === "external_ulr"){
                                    $wcpoa_bulk_file_url_btn = $wcpoa_bulk_attachment_url;
                                }
                                $wcpoa_attachment_expired_date = strtotime($wcpoa_expired_dates);
                                $wcpoa_attachment_time_amount = strtotime($wcpoa_time_amount_concate);
                                $wcpoa_order_status_val = str_replace('wcpoa-wc-', '', $wcpoa_order_status);
                                $wcpoa_order_status_new = !empty($wcpoa_order_status_val) ? $wcpoa_order_status_val : array();
                                $wcpoa_bulk_att_values_key[] = $att_new_key;
                                if ($wcpoa_bulk_att_visibility === 'order_details_page' || $wcpoa_bulk_att_visibility === 'wcpoa_all') {
                                    if( empty($wcpoa_order_status_new) || in_array($items_order_status, $wcpoa_order_status_new,true)){
                                        if (
                                            ($wcpoa_is_condition!=='yes')
                                            ||
                                            (
                                                (trim($wcpoa_assignment) === 'exclude'  && ((empty($wcpoa_bulk_applied_cat) || empty(array_intersect($product_cats_id, $wcpoa_bulk_applied_cat))) && 
                                                (empty($wcpoa_bulk_applied_tag) || empty(array_intersect($product_tag_ids, $wcpoa_bulk_applied_tag))) && 
                                                (empty($wcpoa_bulk_applied_attributes) || empty(array_intersect($product_attribute_ids, $wcpoa_bulk_applied_attributes))) && 
                                                (empty($wcpoa_bulk_applied_product) || !in_array((int)$item['product_id'], convert_array_to_int($wcpoa_bulk_applied_product),true)))
                                                )
                                                || 
                                                (trim($wcpoa_assignment) !== 'exclude' && (
                                                    ( !empty(array_intersect($product_tag_ids, $wcpoa_bulk_applied_tag))) || 
                                                    ( !empty(array_intersect($product_cats_id, $wcpoa_bulk_applied_cat))) || 
                                                    ( !empty(array_intersect($product_attribute_ids, $wcpoa_bulk_applied_attributes))) || 
                                                    (in_array((int)$item['product_id'], convert_array_to_int($wcpoa_bulk_applied_product),true)))
                                                )
                                            )
                                        ) 
                                            {
                                            $wcpoa_att_id = $wcpoa_bulk_att_values['wcpoa_attachments_id'];
                                            if(empty($wcpoa_attachments_id_bulk) || !in_array($wcpoa_att_id, $wcpoa_attachments_id_bulk,true)){
                                                $wcpoa_attachments_id_bulk[] = $wcpoa_bulk_att_values['wcpoa_attachments_id'];
                                                if (!empty($wcpoa_attachment_expired_date) && $wcpoa_expired_date_enable === 'yes') {
                                                    
                                                    if ($wcpoa_today_date > $wcpoa_attachment_expired_date) {
                                                        $wcpoa_attachment_down_tab = array();
                                                        $wcpoa_attachment_down_tab['is_managed_by_wcam'] = true;
                                                        $wcpoa_attachment_down_tab['is_order_attachment'] = true;
                                                        $wcpoa_attachment_down_tab['download_url'] =  '#wcpoa_expire';
                                                        $wcpoa_attachment_down_tab['customer-has-to-be-approved'] = true;
                                                        $wcpoa_attachment_down_tab['download_id'] = '';
                                                        $wcpoa_attachment_down_tab['download_name'] =  __( 'Expired', 'woocommerce-product-attachment' );
                                                        $wcpoa_attachment_down_tab['name'] = '';
                                                        $wcpoa_attachment_down_tab['product_name'] = sprintf(__( 'Order #%s', 'woocommerce-product-attachment' ),$order_id);
                                                        $wcpoa_attachment_down_tab['order_id'] = '';
                                                        $wcpoa_attachment_down_tab['product_id'] = "";
                                                        $wcpoa_attachment_down_tab['product_url'] = $wcpoa_attachmen_order_url;
                                                        $wcpoa_attachment_down_tab['downloads_remaining'] = "";
                                                        $wcpoa_attachment_down_tab['order_key'] = "";
                                                        $wcpoa_attachment_down_tab['access_expires'] = null;
                                                        $wcpoa_attachment_down_tab['file'] = array();
                                                        $wcpoa_attachment_down_tab['file']['name'] = "";
                                                        $wcpoa_attachment_down_tab['access_expires'] = $wcpoa_expired_dates;
                                                        $downloads[] = $wcpoa_attachment_down_tab;
                                                    }else{
                                                        $wcpoa_attachment_down_tab = array();
                                                        $wcpoa_attachment_down_tab['is_managed_by_wcam'] = true;
                                                        $wcpoa_attachment_down_tab['is_order_attachment'] = true;
                                                        $wcpoa_attachment_down_tab['download_url'] =  $wcpoa_bulk_file_url_btn;
                                                        $wcpoa_attachment_down_tab['customer-has-to-be-approved'] = true;
                                                        $wcpoa_attachment_down_tab['download_id'] = '';
                                                        $wcpoa_attachment_down_tab['download_name'] = $wcpoa_bulk_attachments_name;
                                                        $wcpoa_attachment_down_tab['name'] = '';
                                                        $wcpoa_attachment_down_tab['product_name'] = sprintf(__( 'Order #%s', 'woocommerce-product-attachment' ),$order_id);
                                                        $wcpoa_attachment_down_tab['order_id'] = '';
                                                        $wcpoa_attachment_down_tab['product_id'] = "";
                                                        $wcpoa_attachment_down_tab['product_url'] = $wcpoa_attachmen_order_url;
                                                        $wcpoa_attachment_down_tab['downloads_remaining'] = "";
                                                        $wcpoa_attachment_down_tab['order_key'] = "";
                                                        $wcpoa_attachment_down_tab['access_expires'] = null;
                                                        $wcpoa_attachment_down_tab['file'] = array();
                                                        $wcpoa_attachment_down_tab['file']['name'] = "";
                                                        $wcpoa_attachment_down_tab['access_expires'] = $wcpoa_expired_dates;
                                                        $downloads[] = $wcpoa_attachment_down_tab;
                                                    }
                                                    
                                                } elseif (!empty($wcpoa_attachment_time_amount) && $wcpoa_expired_date_enable === 'time_amount') { 
                                                    $wcpoa_bulk_duration = '+'.$wcpoa_time_amount_concate;
                                                    $wcpoa_attachment_expired_time = gmdate('Y/m/d H:i:s', strtotime($wcpoa_bulk_duration, strtotime($order_time)));

                                                    if ($wcpoa_today_date_time > $wcpoa_attachment_expired_time) {
                                                        $wcpoa_attachment_down_tab = array();
                                                        $wcpoa_attachment_down_tab['is_managed_by_wcam'] = true;
                                                        $wcpoa_attachment_down_tab['is_order_attachment'] = true;
                                                        $wcpoa_attachment_down_tab['download_url'] =  '#wcpoa_expire';
                                                        $wcpoa_attachment_down_tab['customer-has-to-be-approved'] = true;
                                                        $wcpoa_attachment_down_tab['download_id'] = '';
                                                        $wcpoa_attachment_down_tab['download_name'] = __( 'Expired', 'woocommerce-product-attachment' );
                                                        $wcpoa_attachment_down_tab['name'] = '';
                                                        $wcpoa_attachment_down_tab['product_name'] = sprintf(__( 'Order #%s', 'woocommerce-product-attachment' ),$order_id);
                                                        $wcpoa_attachment_down_tab['order_id'] = '';
                                                        $wcpoa_attachment_down_tab['product_id'] = "";
                                                        $wcpoa_attachment_down_tab['product_url'] = $wcpoa_attachmen_order_url;
                                                        $wcpoa_attachment_down_tab['downloads_remaining'] = "";
                                                        $wcpoa_attachment_down_tab['order_key'] = "";
                                                        $wcpoa_attachment_down_tab['access_expires'] = null;
                                                        $wcpoa_attachment_down_tab['file'] = array();
                                                        $wcpoa_attachment_down_tab['file']['name'] = "";
                                                        $wcpoa_attachment_down_tab['access_expires'] = $wcpoa_attachment_expired_time;
                                                        $downloads[] = $wcpoa_attachment_down_tab;

                                                    } else{
                                                        $wcpoa_attachment_down_tab = array();
                                                        $wcpoa_attachment_down_tab['is_managed_by_wcam'] = true;
                                                        $wcpoa_attachment_down_tab['is_order_attachment'] = true;
                                                        $wcpoa_attachment_down_tab['download_url'] =  $wcpoa_bulk_file_url_btn;
                                                        $wcpoa_attachment_down_tab['customer-has-to-be-approved'] = true;
                                                        $wcpoa_attachment_down_tab['download_id'] = '';
                                                        $wcpoa_attachment_down_tab['download_name'] = $wcpoa_bulk_attachments_name;
                                                        $wcpoa_attachment_down_tab['name'] = '';
                                                        $wcpoa_attachment_down_tab['product_name'] = sprintf(__( 'Order #%s', 'woocommerce-product-attachment' ),$order_id);
                                                        $wcpoa_attachment_down_tab['order_id'] = '';
                                                        $wcpoa_attachment_down_tab['product_id'] = "";
                                                        $wcpoa_attachment_down_tab['product_url'] = $wcpoa_attachmen_order_url;
                                                        $wcpoa_attachment_down_tab['downloads_remaining'] = "";
                                                        $wcpoa_attachment_down_tab['order_key'] = "";
                                                        $wcpoa_attachment_down_tab['access_expires'] = null;
                                                        $wcpoa_attachment_down_tab['file'] = array();
                                                        $wcpoa_attachment_down_tab['file']['name'] = "";
                                                        $wcpoa_attachment_down_tab['access_expires'] = $wcpoa_attachment_expired_time;
                                                        $downloads[] = $wcpoa_attachment_down_tab;
                                                    }
                                                } else {
                                                    $wcpoa_attachment_down_tab = array();
                                                    $wcpoa_attachment_down_tab['is_managed_by_wcam'] = true;
                                                    $wcpoa_attachment_down_tab['is_order_attachment'] = true;
                                                    $wcpoa_attachment_down_tab['download_url'] =  $wcpoa_bulk_file_url_btn;
                                                    $wcpoa_attachment_down_tab['customer-has-to-be-approved'] = true;
                                                    $wcpoa_attachment_down_tab['download_id'] = '';
                                                    $wcpoa_attachment_down_tab['download_name'] = $wcpoa_bulk_attachments_name;
                                                    $wcpoa_attachment_down_tab['name'] = '';
                                                    $wcpoa_attachment_down_tab['product_name'] = sprintf(__( 'Order #%s', 'woocommerce-product-attachment' ),$order_id);
                                                    $wcpoa_attachment_down_tab['order_id'] = '';
                                                    $wcpoa_attachment_down_tab['product_id'] = "";
                                                    $wcpoa_attachment_down_tab['product_url'] = $wcpoa_attachmen_order_url;
                                                    $wcpoa_attachment_down_tab['downloads_remaining'] = "";
                                                    $wcpoa_attachment_down_tab['order_key'] = "";
                                                    $wcpoa_attachment_down_tab['access_expires'] = null;
                                                    $wcpoa_attachment_down_tab['file'] = array();
                                                    $wcpoa_attachment_down_tab['file']['name'] = "";
                                                    $wcpoa_attachment_down_tab['access_expires'] = "";
                                                    $downloads[] = $wcpoa_attachment_down_tab;
                                                }
                                            }       
                                        } 
                                    } 
                                }
                            }
                        }
                    }
                }
            }
        }
    endif;

    // Get the Checkout Page Attachments 
    $wcpoa_checkout_all_ids = get_post_meta( $items_order_id, '_wcpoa_checkout_attachment_ids', true );
    if( !empty( $wcpoa_checkout_all_ids ) && "" !== $wcpoa_checkout_all_ids ){
        $id_checkout_array = explode( ",", $wcpoa_checkout_all_ids );
        foreach ($id_checkout_array as $wcpoa_checkout_id){
            $wcpoa_file_url_btn = get_permalink() .'?post_type=shop_order&p=' . $items_order_id . '&attachment_id=' . $wcpoa_checkout_id . '&wcpoa_attachment_order_id='.$items_order_id;

            $attachment_name = get_the_title( $wcpoa_checkout_id );

            $wcpoa_attachment_down_tab = array();
            $wcpoa_attachment_down_tab['is_managed_by_wcam'] = true;
            $wcpoa_attachment_down_tab['is_order_attachment'] = true;
            $wcpoa_attachment_down_tab['download_url'] =  $wcpoa_file_url_btn;
            $wcpoa_attachment_down_tab['customer-has-to-be-approved'] = true;
            $wcpoa_attachment_down_tab['download_id'] = '';
            $wcpoa_attachment_down_tab['download_name'] = $attachment_name;
            $wcpoa_attachment_down_tab['name'] = '';
            $wcpoa_attachment_down_tab['product_name'] = sprintf(__( 'Order #%s', 'woocommerce-product-attachment' ),$order_id);
            $wcpoa_attachment_down_tab['order_id'] = '';
            $wcpoa_attachment_down_tab['product_id'] = "";
            $wcpoa_attachment_down_tab['product_url'] = $wcpoa_attachmen_order_url;
            $wcpoa_attachment_down_tab['downloads_remaining'] = "";
            $wcpoa_attachment_down_tab['order_key'] = "";
            $wcpoa_attachment_down_tab['access_expires'] = null;
            $wcpoa_attachment_down_tab['file'] = array();
            $wcpoa_attachment_down_tab['file']['name'] = "";
            $wcpoa_attachment_down_tab['access_expires'] = "";
            $downloads[] = $wcpoa_attachment_down_tab;
        }
    }

    // Get the Order Page Attachments
    $wcpoa_all_ids = get_post_meta( $items_order_id, '_wcpoa_order_attachments', true );
    if( !empty( $wcpoa_all_ids ) && "" !== $wcpoa_all_ids ){
        $id_array = explode( ",", $wcpoa_all_ids );
        foreach ($id_array as $wcpoa_id){
            $wcpoa_file_url_btn = get_permalink() .'?post_type=shop_order&p=' . $items_order_id . '&attachment_id=' . $wcpoa_id . '&wcpoa_attachment_order_id='.$items_order_id;

            $attachment_name = get_the_title( $wcpoa_id );

            $wcpoa_attachment_down_tab = array();
            $wcpoa_attachment_down_tab['is_managed_by_wcam'] = true;
            $wcpoa_attachment_down_tab['is_order_attachment'] = true;
            $wcpoa_attachment_down_tab['download_url'] =  $wcpoa_file_url_btn;
            $wcpoa_attachment_down_tab['customer-has-to-be-approved'] = true;
            $wcpoa_attachment_down_tab['download_id'] = '';
            $wcpoa_attachment_down_tab['download_name'] = $attachment_name;
            $wcpoa_attachment_down_tab['name'] = '';
            $wcpoa_attachment_down_tab['product_name'] = sprintf(__( 'Order #%s', 'woocommerce-product-attachment' ),$order_id);
            $wcpoa_attachment_down_tab['order_id'] = '';
            $wcpoa_attachment_down_tab['product_id'] = "";
            $wcpoa_attachment_down_tab['product_url'] = $wcpoa_attachmen_order_url;
            $wcpoa_attachment_down_tab['downloads_remaining'] = "";
            $wcpoa_attachment_down_tab['order_key'] = "";
            $wcpoa_attachment_down_tab['access_expires'] = null;
            $wcpoa_attachment_down_tab['file'] = array();
            $wcpoa_attachment_down_tab['file']['name'] = "";
            $wcpoa_attachment_down_tab['access_expires'] = "";
            $downloads[] = $wcpoa_attachment_down_tab;
        }
    }
}