<?php

// If this file is called directly, abort.
if ( !defined( 'WPINC' ) ) {
    die;
}
global $sitepress;
$wc_order = new WC_Order($order_id);
if ( !is_a( $wc_order, 'WC_Order' ) ) {
    return;
}
$order_data = $wc_order->get_data();
$order_time = $order_data['date_created']->date( 'Y/m/d H:i:s' );
$items = $wc_order->get_items( array('line_item') );
$items_order_status = $wc_order->get_status();
$items_order_id = $wc_order->get_id();
$wcpoa_order_tab_name = get_option( 'wcpoa_order_tab_name' );
//wcpoa order tab option name
$wcpoa_expired_date_tlabel = get_option( 'wcpoa_expired_date_label' );
$wcpoa_show_attachment_size_flag = get_option( 'wcpoa_show_attachment_size_flag' );
// get attachment download type
$wcpoa_download_type = get_option( 'wcpoa_product_download_type' );
$get_permalink_structure = get_permalink( $order_id );
if ( strpos( $get_permalink_structure, "?" ) ) {
    $wcpoa_attachment_url_arg = '&';
} else {
    $wcpoa_attachment_url_arg = '?';
}
$current_date = gmdate( "Y/m/d" );
$wcpoa_today_date = strtotime( $current_date );
$wcpoa_today_date_time = current_time( 'Y/m/d H:i:s' );
$wcpoa_att_values_key = array();
$tab_title_match = 'no';
$wcpoa_bulk_att_data = get_option( 'wcpoa_bulk_attachment_data' );
$wcpoa_bulk_att_values_key = array();
$wcpoa_bulk_att_product_key = array();
// intialize admin object
$admin_object = new Woocommerce_Product_Attachment_Admin('', '');
if ( !empty( $items ) && is_array( $items ) ) {
    foreach ( $items as $item_id => $item ) {
        $wcpoa_order_attachment_items = wc_get_order_item_meta( $item_id, 'wcpoa_order_attachment_order_arr', true );
    }
}
$wcpoa_bulk_att_product_key = array();
//Bulk Attachment
if ( !empty( $items ) ) {
    foreach ( $items as $item_id => $item ) {
        if ( !empty( $wcpoa_bulk_att_data ) ) {
            foreach ( $wcpoa_bulk_att_data as $att_new_key => $wcpoa_bulk_att_values ) {
                if ( !array_key_exists( 'wcpoa_attach_view', $wcpoa_bulk_att_values ) || "enable" === $wcpoa_bulk_att_values['wcpoa_attach_view'] ) {
                    if ( !in_array( $att_new_key, $wcpoa_bulk_att_product_key, true ) ) {
                        $wcpoa_is_condition = ( isset( $wcpoa_bulk_att_values['wcpoa_is_condition'] ) && !empty( $wcpoa_bulk_att_values['wcpoa_is_condition'] ) ? $wcpoa_bulk_att_values['wcpoa_is_condition'] : '' );
                        $wcpoa_order_status = ( isset( $wcpoa_bulk_att_values['wcpoa_order_status'] ) && !empty( $wcpoa_bulk_att_values['wcpoa_order_status'] ) ? $wcpoa_bulk_att_values['wcpoa_order_status'] : '' );
                        $wcpoa_attachments_bulk_id = ( !empty( $wcpoa_bulk_att_values['wcpoa_attachments_id'] ) ? $wcpoa_bulk_att_values['wcpoa_attachments_id'] : '' );
                        $wcpoa_order_status_val = str_replace( 'wcpoa-wc-', '', $wcpoa_order_status );
                        $wcpoa_order_status_new[] = ( !empty( $wcpoa_order_status_val ) ? $wcpoa_order_status_val : array() );
                        $wcpoa_bulk_att_values_key[] = $att_new_key;
                        if ( empty( $wcpoa_order_status_new ) || in_array( $items_order_status, $wcpoa_order_status_new, true ) ) {
                            if ( $wcpoa_is_condition === 'no' ) {
                                $wcpoa_att_id = $wcpoa_bulk_att_values['wcpoa_attachments_id'];
                                if ( empty( $wcpoa_attachments_id_bulk ) || !in_array( $wcpoa_att_id, $wcpoa_attachments_id_bulk, true ) ) {
                                    $tab_title_match = 'yes';
                                }
                            }
                        }
                    }
                }
            }
        }
    }
}
$wcpoa_checkout_all_ids = get_post_meta( $items_order_id, '_wcpoa_checkout_attachment_ids', true );
echo '<section class="woocommerce-attachment-details">';
if ( $tab_title_match === 'yes' || !empty( $wcpoa_checkout_all_ids ) || !empty( $wcpoa_order_attachment_items ) ) {
    if ( !empty( $sitepress ) ) {
        $default_lang = $admin_object->wcpoa_get_default_langugae_with_sitpress();
        $wcpoa_order_tab_name_lang = apply_filters(
            'wpml_translate_single_string',
            $wcpoa_order_tab_name,
            'woocommerce-product-attachment',
            $wcpoa_order_tab_name,
            $default_lang
        );
    } else {
        $wcpoa_order_tab_name_lang = $wcpoa_order_tab_name;
    }
    echo '<h2 class="woocommerce-order-details__title">' . esc_html( $wcpoa_order_tab_name_lang ) . '</h2>';
}
$wcpoa_attachments_id_bulk = array();
$attached_variations = array();
if ( !empty( $items ) && is_array( $items ) ) {
    foreach ( $items as $item_id => $item ) {
        $wcpoa_order_attachment_items = wc_get_order_item_meta( $item_id, 'wcpoa_order_attachment_order_arr', true );
        $attachment_order_name = '';
        $wcpoa_file_url_btn = '';
        if ( !empty( $wcpoa_order_attachment_items ) ) {
            $wcpoa_attachment_ids = ( isset( $wcpoa_order_attachment_items['wcpoa_attachment_ids'] ) && !empty( $wcpoa_order_attachment_items['wcpoa_attachment_ids'] ) ? $wcpoa_order_attachment_items['wcpoa_attachment_ids'] : '' );
            $wcpoa_attachment_name = ( isset( $wcpoa_order_attachment_items['wcpoa_attachment_name'] ) && !empty( $wcpoa_order_attachment_items['wcpoa_attachment_name'] ) ? $wcpoa_order_attachment_items['wcpoa_attachment_name'] : '' );
            $wcpoa_attachment_description = ( isset( $wcpoa_order_attachment_items['wcpoa_att_order_description'] ) && !empty( $wcpoa_order_attachment_items['wcpoa_att_order_description'] ) ? $wcpoa_order_attachment_items['wcpoa_att_order_description'] : '' );
            $wcpoa_attachment_url = ( isset( $wcpoa_order_attachment_items['wcpoa_attachment_url'] ) && !empty( $wcpoa_order_attachment_items['wcpoa_attachment_url'] ) ? $wcpoa_order_attachment_items['wcpoa_attachment_url'] : '' );
            $wcpoa_attach_type = ( isset( $wcpoa_order_attachment_items['wcpoa_attach_type'] ) && !empty( $wcpoa_order_attachment_items['wcpoa_attach_type'] ) ? $wcpoa_order_attachment_items['wcpoa_attach_type'] : '' );
            $wcpoa_product_open_window_flag = ( isset( $wcpoa_order_attachment_items['wcpoa_product_open_window_flag'] ) && !empty( $wcpoa_order_attachment_items['wcpoa_product_open_window_flag'] ) ? $wcpoa_order_attachment_items['wcpoa_product_open_window_flag'] : '' );
            $wcpoa_att_order_status = ( isset( $wcpoa_order_attachment_items['wcpoa_order_status'] ) && !empty( $wcpoa_order_attachment_items['wcpoa_order_status'] ) ? $wcpoa_order_attachment_items['wcpoa_order_status'] : '' );
            $wcpoa_expire_date_enable = ( isset( $wcpoa_order_attachment_items['wcpoa_expired_date_enable'] ) && !empty( $wcpoa_order_attachment_items['wcpoa_expired_date_enable'] ) ? $wcpoa_order_attachment_items['wcpoa_expired_date_enable'] : '' );
            $wcpoa_order_attachment_expired = ( isset( $wcpoa_order_attachment_items['wcpoa_order_attachment_expired'] ) && !empty( $wcpoa_order_attachment_items['wcpoa_order_attachment_expired'] ) ? $wcpoa_order_attachment_items['wcpoa_order_attachment_expired'] : '' );
            $selected_variation_id = "";
            $attached_variations = array();
            if ( !empty( $selected_variation_id ) && is_array( $attached_variations ) && in_array( (int) $selected_variation_id, convert_array_to_int( $attached_variations ), true ) ) {
            } else {
                if ( !empty( $wcpoa_attachment_ids ) && is_array( $wcpoa_attachment_ids ) ) {
                    //End Woo Product Attachment Order Tab
                    foreach ( $wcpoa_attachment_ids as $key => $wcpoa_attachments_id ) {
                        if ( !empty( $wcpoa_attachments_id ) || $wcpoa_attachments_id !== '' ) {
                            if ( !in_array( $wcpoa_attachments_id, $wcpoa_att_values_key, true ) ) {
                                $wcpoa_att_values_key[] = $wcpoa_attachments_id;
                                $attachment_name = ( isset( $wcpoa_attachment_name[$key] ) && !empty( $wcpoa_attachment_name[$key] ) ? $wcpoa_attachment_name[$key] : '' );
                                $wcpoa_order_status_val = ( isset( $wcpoa_att_order_status[$wcpoa_attachments_id] ) && !empty( $wcpoa_att_order_status[$wcpoa_attachments_id] ) && $wcpoa_att_order_status[$wcpoa_attachments_id] ? $wcpoa_att_order_status[$wcpoa_attachments_id] : array() );
                                $wcpoa_order_status_new = str_replace( 'wcpoa-wc-', '', $wcpoa_order_status_val );
                                $wcpoa_attachment_type = ( isset( $wcpoa_attach_type[$key] ) && !empty( $wcpoa_attach_type[$key] ) ? $wcpoa_attach_type[$key] : 'file_upload' );
                                $wcpoa_product_open_window_flag_val = ( isset( $wcpoa_product_open_window_flag[$key] ) && !empty( $wcpoa_product_open_window_flag[$key] ) ? $wcpoa_product_open_window_flag[$key] : '' );
                                $wcpoa_attachment_file = ( isset( $wcpoa_attachment_url[$key] ) && !empty( $wcpoa_attachment_url[$key] ) ? $wcpoa_attachment_url[$key] : '' );
                                $wcpoa_attachment_descriptions = ( isset( $wcpoa_attachment_description[$key] ) && !empty( $wcpoa_attachment_description[$key] ) ? $wcpoa_attachment_description[$key] : '' );
                                $wcpoa_expired_date_enable = ( isset( $wcpoa_expire_date_enable[$key] ) && !empty( $wcpoa_expire_date_enable[$key] ) ? $wcpoa_expire_date_enable[$key] : '' );
                                $wcpoa_order_attachment_expired_date = ( isset( $wcpoa_order_attachment_expired[$key] ) && !empty( $wcpoa_order_attachment_expired[$key] ) ? $wcpoa_order_attachment_expired[$key] : '' );
                                $wcpoa_attachment_time_amount_concate_single = "";
                                $attachment_id = $wcpoa_attachment_file;
                                // ID of attachment
                                $wcpoa_attachments_icon = '';
                                $wcpoa_attachment_expired_date = strtotime( $wcpoa_order_attachment_expired_date );
                                $wcpoa_att_btn = '';
                                $wcpoa_att_ex_btn = '';
                                $wcpoa_att_btn = $this->wcpoa_get_attachment_icons_or_button( $attachment_id );
                                if ( isset( $wcpoa_show_attachment_size_flag ) && 'yes' === $wcpoa_show_attachment_size_flag ) {
                                    $attachment_size = size_format( filesize( get_attached_file( $attachment_id ) ) );
                                    if ( isset( $attachment_size ) && '' !== $attachment_size && false !== $attachment_size ) {
                                        $wcpoa_att_btn = $wcpoa_att_btn . '<span class="attachment_size">(' . $attachment_size . ')</span>';
                                    }
                                }
                                $download_target = ( $wcpoa_product_open_window_flag_val === 'yes' ? 'target="_blank"' : '' );
                                if ( isset( $wcpoa_product_open_window_flag_val ) ) {
                                    $is_download = $download_target;
                                }
                                if ( !empty( $wcpoa_download_type ) && 'download_by_link' === $wcpoa_download_type || 'download_by_both' === $wcpoa_download_type ) {
                                    $attachment_order_name = '<h4 class="wcpoa_attachment_name"><a class="wcpoa_title_with_link" href="' . get_permalink( $order_id ) . $wcpoa_attachment_url_arg . 'attachment_id=' . $attachment_id . '&download_file=' . $wcpoa_attachments_id . '&wcpoa_attachment_order_id=' . $items_order_id . '" rel="nofollow" ' . $is_download . '>' . __( $attachment_name, 'woocommerce-product-attachment' ) . '</a></h4>';
                                } else {
                                    $attachment_order_name = '<h4 class="wcpoa_attachment_name">' . __( $attachment_name, 'woocommerce-product-attachment' ) . '</h4>';
                                }
                                $wcpoa_file_url_btn = '<a class="wcpoa_attachmentbtn" href="' . get_permalink( $order_id ) . $wcpoa_attachment_url_arg . 'attachment_id=' . $attachment_id . '&download_file=' . $wcpoa_attachments_id . '&wcpoa_attachment_order_id=' . $items_order_id . '" rel="nofollow" ' . $is_download . '>' . $wcpoa_att_btn . '</a>';
                                if ( !empty( $wcpoa_download_type ) && 'download_by_link' === $wcpoa_download_type || 'download_by_both' === $wcpoa_download_type ) {
                                    $wcpoa_expired_attachments_name = '<h4 class="wcpoa_attachment_name"><a class="wcpoa_title_with_link wcpoa_expired_title_with_link" rel="nofollow"> ' . __( $attachment_name, 'woocommerce-product-attachment' ) . ' </a></h4>';
                                }
                                $wcpoa_att_ex_btn = $this->wcpoa_get_attachment_icons_or_button( $attachment_id, true );
                                $wcpoa_file_expired_url_btn = '<a class="wcpoa_order_attachment_expire" rel="nofollow">' . $wcpoa_att_ex_btn . '</a>';
                                $wcpoa_order_attachment_descriptions = '';
                                if ( isset( $wcpoa_attachment_descriptions ) && !empty( $wcpoa_attachment_descriptions ) ) {
                                    $wcpoa_order_attachment_descriptions = '<p class="wcpoa_attachment_desc">' . __( $wcpoa_attachment_descriptions, 'woocommerce-product-attachment' ) . '</p>';
                                }
                                if ( $wcpoa_expired_date_tlabel === 'no' ) {
                                    $wcpoa_expire_date_text = '';
                                    $wcpoa_expired_date_text = '';
                                } else {
                                    $wcpoa_expire_date_text = '<p class="order_att_expire_date"><span>*</span>' . __( 'This Attachment Expiry Date Is : ', 'woocommerce-product-attachment' ) . $wcpoa_order_attachment_expired_date . '</p>';
                                    $wcpoa_expired_date_text = '<p class="order_att_expire_date"><span>*</span>' . __( 'This Attachment Expired', 'woocommerce-product-attachment' ) . '</p>';
                                    if ( !empty( $wcpoa_attachment_time_amount_concate_single ) && $wcpoa_expired_date_enable === "time_amount" ) {
                                        $wcpoa_attachment_duration = '+' . $wcpoa_order_attachment_time_amount_concate;
                                        $wcpoa_attachment_expired_time = gmdate( 'Y/m/d H:i:s', strtotime( $wcpoa_attachment_duration, strtotime( $order_time ) ) );
                                        $wcpoa_expire_date_text = '<p class="order_att_expire_date"><span>*</span>' . __( 'This Attachment Expiry Date Is : ', 'woocommerce-product-attachment' ) . $wcpoa_attachment_expired_time . '</p>';
                                    }
                                }
                                if ( empty( $wcpoa_order_status_new ) || in_array( $items_order_status, $wcpoa_order_status_new, true ) ) {
                                    if ( !empty( $wcpoa_attachment_expired_date ) && $wcpoa_expired_date_enable === "yes" ) {
                                        if ( $wcpoa_today_date > $wcpoa_attachment_expired_date ) {
                                            echo '<div class="wcpoa_attachment">';
                                            if ( !empty( $wcpoa_download_type ) && 'download_by_link' === $wcpoa_download_type || 'download_by_both' === $wcpoa_download_type ) {
                                                echo wp_kses( $wcpoa_expired_attachments_name, $this->allowed_html_tags() );
                                            } else {
                                                echo wp_kses( $attachment_order_name, $this->allowed_html_tags() );
                                            }
                                            if ( !empty( $wcpoa_download_type ) && 'download_by_btn' === $wcpoa_download_type || 'download_by_both' === $wcpoa_download_type ) {
                                                echo wp_kses( $wcpoa_file_expired_url_btn, $this->allowed_html_tags() );
                                            }
                                            echo wp_kses( $wcpoa_order_attachment_descriptions, $this->allowed_html_tags() );
                                            echo wp_kses( $wcpoa_expired_date_text, $this->allowed_html_tags() );
                                            $tab_title_match = 'yes';
                                            echo '</div>';
                                        } else {
                                            echo '<div class="wcpoa_attachment">';
                                            echo wp_kses( $attachment_order_name, $this->allowed_html_tags() );
                                            if ( !empty( $wcpoa_download_type ) && 'download_by_btn' === $wcpoa_download_type || 'download_by_both' === $wcpoa_download_type ) {
                                                echo wp_kses( $wcpoa_file_url_btn, $this->allowed_html_tags() );
                                            }
                                            echo wp_kses( $wcpoa_order_attachment_descriptions, $this->allowed_html_tags() );
                                            echo wp_kses( $wcpoa_expire_date_text, $this->allowed_html_tags() );
                                            $tab_title_match = 'yes';
                                            echo '</div>';
                                        }
                                    } elseif ( !empty( $wcpoa_order_attachment_exp_time_amount ) && $wcpoa_expired_date_enable === "time_amount" ) {
                                    } else {
                                        echo '<div class="wcpoa_attachment">';
                                        echo wp_kses( $attachment_order_name, $this->allowed_html_tags() );
                                        if ( !empty( $wcpoa_download_type ) && 'download_by_btn' === $wcpoa_download_type || 'download_by_both' === $wcpoa_download_type ) {
                                            echo wp_kses( $wcpoa_file_url_btn, $this->allowed_html_tags() );
                                        }
                                        echo wp_kses( $wcpoa_order_attachment_descriptions, $this->allowed_html_tags() );
                                        $tab_title_match = 'yes';
                                        echo '</div>';
                                    }
                                }
                            }
                        }
                    }
                }
            }
        }
        //Bulk Attachment
        if ( !empty( $items ) ) {
            $_product = wc_get_product( $item['product_id'] );
            if ( !is_a( $_product, 'WC_Product' ) ) {
                return;
            }
            if ( !empty( $wcpoa_bulk_att_data ) ) {
                foreach ( $wcpoa_bulk_att_data as $att_new_key => $wcpoa_bulk_att_values ) {
                    if ( !array_key_exists( 'wcpoa_attach_view', $wcpoa_bulk_att_values ) || "enable" === $wcpoa_bulk_att_values['wcpoa_attach_view'] ) {
                        if ( !in_array( $att_new_key, $wcpoa_bulk_att_product_key, true ) ) {
                            $wcpoa_order_status = ( isset( $wcpoa_bulk_att_values['wcpoa_order_status'] ) && !empty( $wcpoa_bulk_att_values['wcpoa_order_status'] ) ? $wcpoa_bulk_att_values['wcpoa_order_status'] : '' );
                            $wcpoa_attachments_bulk_id = ( !empty( $wcpoa_bulk_att_values['wcpoa_attachments_id'] ) ? $wcpoa_bulk_att_values['wcpoa_attachments_id'] : '' );
                            $wcpoa_order_status_val = str_replace( 'wcpoa-wc-', '', $wcpoa_order_status );
                            $wcpoa_order_status_new = ( !empty( $wcpoa_order_status_val ) ? $wcpoa_order_status_val : array() );
                            $wcpoa_is_condition = ( isset( $wcpoa_bulk_att_values['wcpoa_is_condition'] ) && !empty( $wcpoa_bulk_att_values['wcpoa_is_condition'] ) ? $wcpoa_bulk_att_values['wcpoa_is_condition'] : '' );
                            $wcpoa_bulk_att_visibility = ( isset( $wcpoa_bulk_att_values['wcpoa_att_visibility'] ) && !empty( $wcpoa_bulk_att_values['wcpoa_att_visibility'] ) ? $wcpoa_bulk_att_values['wcpoa_att_visibility'] : '' );
                            $wcpoa_bulk_attachments_name = ( isset( $wcpoa_bulk_att_values['wcpoa_attachment_name'] ) && !empty( $wcpoa_bulk_att_values['wcpoa_attachment_name'] ) ? $wcpoa_bulk_att_values['wcpoa_attachment_name'] : '' );
                            $wcpoa_bulk_attachment_type = ( isset( $wcpoa_bulk_att_values['wcpoa_attach_type'] ) && !empty( $wcpoa_bulk_att_values['wcpoa_attach_type'] ) ? $wcpoa_bulk_att_values['wcpoa_attach_type'] : '' );
                            $wcpoa_bulk_attachment_file = ( isset( $wcpoa_bulk_att_values['wcpoa_attachment_file'] ) && !empty( $wcpoa_bulk_att_values['wcpoa_attachment_file'] ) ? $wcpoa_bulk_att_values['wcpoa_attachment_file'] : '' );
                            $wcpoa_product_open_window_flag = ( isset( $wcpoa_bulk_att_values['wcpoa_product_open_window_flag'] ) && !empty( $wcpoa_bulk_att_values['wcpoa_product_open_window_flag'] ) ? $wcpoa_bulk_att_values['wcpoa_product_open_window_flag'] : '' );
                            $wcpoa_attachment_descriptions = ( isset( $wcpoa_bulk_att_values['wcpoa_attachment_description'] ) && !empty( $wcpoa_bulk_att_values['wcpoa_attachment_description'] ) ? $wcpoa_bulk_att_values['wcpoa_attachment_description'] : '' );
                            $wcpoa_expired_date_enable = ( isset( $wcpoa_bulk_att_values['wcpoa_expired_date_enable'] ) && !empty( $wcpoa_bulk_att_values['wcpoa_expired_date_enable'] ) ? $wcpoa_bulk_att_values['wcpoa_expired_date_enable'] : '' );
                            $wcpoa_expired_dates = ( isset( $wcpoa_bulk_att_values['wcpoa_expired_date'] ) && !empty( $wcpoa_bulk_att_values['wcpoa_expired_date'] ) ? $wcpoa_bulk_att_values['wcpoa_expired_date'] : '' );
                            $attachment_id = '';
                            if ( isset( $wcpoa_bulk_attachment_file ) ) {
                                $attachment_id = $wcpoa_bulk_attachment_file;
                            }
                            $wcpoa_att_btn = '';
                            $wcpoa_att_ex_btn = '';
                            $wcpoa_att_btn = $this->wcpoa_get_attachment_icons_or_button( $attachment_id );
                            if ( isset( $wcpoa_show_attachment_size_flag ) && 'yes' === $wcpoa_show_attachment_size_flag ) {
                                $attachment_size = size_format( filesize( get_attached_file( $attachment_id ) ) );
                                if ( isset( $attachment_size ) && '' !== $attachment_size && false !== $attachment_size ) {
                                    $wcpoa_att_btn = $wcpoa_att_btn . '<span class="attachment_size">(' . $attachment_size . ')</span>';
                                }
                            }
                            $download_target = ( $wcpoa_product_open_window_flag === 'yes' ? 'target="_blank"' : '' );
                            if ( isset( $wcpoa_product_open_window_flag ) ) {
                                $is_download = $download_target;
                            }
                            if ( !empty( $wcpoa_download_type ) && 'download_by_link' === $wcpoa_download_type || 'download_by_both' === $wcpoa_download_type ) {
                                $wcpoa_attachments_name = '<h4 class="wcpoa_attachment_name"><a class="wcpoa_title_with_link" href="' . get_permalink( $order_id ) . $wcpoa_attachment_url_arg . 'attachment_id=' . $wcpoa_bulk_attachment_file . '&download_file=' . $wcpoa_attachments_bulk_id . '&wcpoa_attachment_order_id=' . $items_order_id . '" rel="nofollow" ' . $is_download . '>' . esc_html__( $wcpoa_bulk_attachments_name, 'woocommerce-product-attachment' ) . '</a></h4>';
                            } else {
                                $wcpoa_attachments_name = '<h4 class="wcpoa_attachment_name">' . esc_html__( $wcpoa_bulk_attachments_name, 'woocommerce-product-attachment' ) . '</h4>';
                            }
                            $wcpoa_bulk_file_url_btn = '<a class="wcpoa_attachmentbtn" href="' . get_permalink( $order_id ) . $wcpoa_attachment_url_arg . 'attachment_id=' . $wcpoa_bulk_attachment_file . '&download_file=' . $wcpoa_attachments_bulk_id . '&wcpoa_attachment_order_id=' . $items_order_id . '" rel="nofollow" ' . $is_download . '>' . $wcpoa_att_btn . '</a>';
                            if ( !empty( $wcpoa_download_type ) && 'download_by_link' === $wcpoa_download_type || 'download_by_both' === $wcpoa_download_type ) {
                                $wcpoa_expired_attachments_name = '<h4 class="wcpoa_attachment_name"><a class="wcpoa_title_with_link wcpoa_expired_title_with_link" rel="nofollow"> ' . esc_html__( $wcpoa_bulk_attachments_name, 'woocommerce-product-attachment' ) . ' </a></h4>';
                            }
                            $wcpoa_att_ex_btn = $this->wcpoa_get_attachment_icons_or_button( $attachment_id, true );
                            $wcpoa_bulk_file_expired_url_btn = '<a class="wcpoa_order_attachment_expire" rel="nofollow"> ' . $wcpoa_att_ex_btn . ' </a>';
                            if ( isset( $wcpoa_attachment_descriptions ) && !empty( $wcpoa_attachment_descriptions ) ) {
                                $wcpoa_attachment_descriptions = '<p class="wcpoa_attachment_desc">' . __( $wcpoa_attachment_descriptions, 'woocommerce-product-attachment' ) . '</p>';
                            }
                            if ( $wcpoa_expired_date_tlabel === 'no' ) {
                                $wcpoa_bulk_expired_date_text = '';
                                $wcpoa_bulk_expire_date_text = '';
                            } else {
                                $wcpoa_bulk_expired_date_text = '<p class="order_att_expire_date"><span>*</span>' . __( 'This Attachment Expired.', 'woocommerce-product-attachment' ) . '</p>';
                                $wcpoa_bulk_expire_date_text = '<p class="order_att_expire_date"><span>*</span>' . __( 'This Attachment Expiry Date Is : ', 'woocommerce-product-attachment' ) . $wcpoa_expired_dates . '</p>';
                            }
                            $wcpoa_attachment_expired_date = strtotime( $wcpoa_expired_dates );
                            $wcpoa_bulk_att_values_key[] = $att_new_key;
                            if ( $wcpoa_bulk_att_visibility === 'order_details_page' || $wcpoa_bulk_att_visibility === 'wcpoa_all' ) {
                                if ( empty( $wcpoa_order_status_new ) || in_array( $items_order_status, $wcpoa_order_status_new, true ) ) {
                                    if ( $wcpoa_is_condition === 'no' ) {
                                        $wcpoa_att_id = $wcpoa_bulk_att_values['wcpoa_attachments_id'];
                                        if ( empty( $wcpoa_attachments_id_bulk ) || !in_array( $wcpoa_att_id, $wcpoa_attachments_id_bulk, true ) ) {
                                            $wcpoa_attachments_id_bulk[] = $wcpoa_bulk_att_values['wcpoa_attachments_id'];
                                            if ( !empty( $wcpoa_attachment_expired_date ) && $wcpoa_expired_date_enable === 'yes' ) {
                                                if ( $wcpoa_today_date > $wcpoa_attachment_expired_date ) {
                                                    echo '<div class="wcpoa_attachment">';
                                                    if ( !empty( $wcpoa_download_type ) && 'download_by_link' === $wcpoa_download_type || 'download_by_both' === $wcpoa_download_type ) {
                                                        echo wp_kses( $wcpoa_expired_attachments_name, $this->allowed_html_tags() );
                                                    } else {
                                                        echo wp_kses( $wcpoa_attachments_name, $this->allowed_html_tags() );
                                                    }
                                                    if ( !empty( $wcpoa_download_type ) && 'download_by_btn' === $wcpoa_download_type || 'download_by_both' === $wcpoa_download_type ) {
                                                        echo wp_kses( $wcpoa_bulk_file_expired_url_btn, $this->allowed_html_tags() );
                                                    }
                                                    echo wp_kses( $wcpoa_attachment_descriptions, $this->allowed_html_tags() );
                                                    echo wp_kses( $wcpoa_bulk_expired_date_text, $this->allowed_html_tags() );
                                                    $tab_title_match = 'yes';
                                                    echo '</div>';
                                                } else {
                                                    echo '<div class="wcpoa_attachment">';
                                                    echo wp_kses( $wcpoa_attachments_name, $this->allowed_html_tags() );
                                                    if ( !empty( $wcpoa_download_type ) && 'download_by_btn' === $wcpoa_download_type || 'download_by_both' === $wcpoa_download_type ) {
                                                        echo wp_kses( $wcpoa_bulk_file_url_btn, $this->allowed_html_tags() );
                                                    }
                                                    echo wp_kses( $wcpoa_attachment_descriptions, $this->allowed_html_tags() );
                                                    echo wp_kses( $wcpoa_bulk_expire_date_text, $this->allowed_html_tags() );
                                                    $tab_title_match = 'yes';
                                                    echo '</div>';
                                                }
                                            } else {
                                                echo '<div class="wcpoa_attachment">';
                                                echo wp_kses( $wcpoa_attachments_name, $this->allowed_html_tags() );
                                                if ( !empty( $wcpoa_download_type ) && 'download_by_btn' === $wcpoa_download_type || 'download_by_both' === $wcpoa_download_type ) {
                                                    echo wp_kses( $wcpoa_bulk_file_url_btn, $this->allowed_html_tags() );
                                                }
                                                echo wp_kses( $wcpoa_attachment_descriptions, $this->allowed_html_tags() );
                                                $tab_title_match = 'yes';
                                                echo '</div>';
                                            }
                                        }
                                    }
                                }
                            } else {
                                if ( !is_wc_endpoint_url( 'order-received' ) && !is_wc_endpoint_url( 'view-order' ) ) {
                                    if ( empty( $wcpoa_order_status_new ) || in_array( $items_order_status, $wcpoa_order_status_new, true ) ) {
                                        if ( $wcpoa_is_condition === 'no' ) {
                                            $wcpoa_att_id = $wcpoa_bulk_att_values['wcpoa_attachments_id'];
                                            if ( empty( $wcpoa_attachments_id_bulk ) || !in_array( $wcpoa_att_id, $wcpoa_attachments_id_bulk, true ) ) {
                                                $wcpoa_attachments_id_bulk[] = $wcpoa_bulk_att_values['wcpoa_attachments_id'];
                                                if ( !empty( $wcpoa_attachment_expired_date ) && $wcpoa_expired_date_enable === 'yes' ) {
                                                    if ( $wcpoa_today_date > $wcpoa_attachment_expired_date ) {
                                                        echo '<div class="wcpoa_attachment">';
                                                        if ( !empty( $wcpoa_download_type ) && 'download_by_link' === $wcpoa_download_type || 'download_by_both' === $wcpoa_download_type ) {
                                                            echo wp_kses( $wcpoa_expired_attachments_name, $this->allowed_html_tags() );
                                                        } else {
                                                            echo wp_kses( $wcpoa_attachments_name, $this->allowed_html_tags() );
                                                        }
                                                        if ( !empty( $wcpoa_download_type ) && 'download_by_btn' === $wcpoa_download_type || 'download_by_both' === $wcpoa_download_type ) {
                                                            echo wp_kses( $wcpoa_bulk_file_expired_url_btn, $this->allowed_html_tags() );
                                                        }
                                                        echo wp_kses( $wcpoa_attachment_descriptions, $this->allowed_html_tags() );
                                                        echo wp_kses( $wcpoa_bulk_expired_date_text, $this->allowed_html_tags() );
                                                        $tab_title_match = 'yes';
                                                        echo '</div>';
                                                    } else {
                                                        echo '<div class="wcpoa_attachment">';
                                                        echo wp_kses( $wcpoa_attachments_name, $this->allowed_html_tags() );
                                                        if ( !empty( $wcpoa_download_type ) && 'download_by_btn' === $wcpoa_download_type || 'download_by_both' === $wcpoa_download_type ) {
                                                            echo wp_kses( $wcpoa_bulk_file_url_btn, $this->allowed_html_tags() );
                                                        }
                                                        echo wp_kses( $wcpoa_attachment_descriptions, $this->allowed_html_tags() );
                                                        echo wp_kses( $wcpoa_bulk_expire_date_text, $this->allowed_html_tags() );
                                                        $tab_title_match = 'yes';
                                                        echo '</div>';
                                                    }
                                                } else {
                                                    echo '<div class="wcpoa_attachment">';
                                                    echo wp_kses( $wcpoa_attachments_name, $this->allowed_html_tags() );
                                                    if ( !empty( $wcpoa_download_type ) && 'download_by_btn' === $wcpoa_download_type || 'download_by_both' === $wcpoa_download_type ) {
                                                        echo wp_kses( $wcpoa_bulk_file_url_btn, $this->allowed_html_tags() );
                                                    }
                                                    echo wp_kses( $wcpoa_attachment_descriptions, $this->allowed_html_tags() );
                                                    $tab_title_match = 'yes';
                                                    echo '</div>';
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
    }
}
echo '</section>';