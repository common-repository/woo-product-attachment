<?php

// If this file is called directly, abort.
if ( !defined( 'WPINC' ) ) {
    die;
}
global $post;
if ( isset( $post ) && !empty( $post ) ) {
    $order_id = $post->ID;
} else {
    $order_id = filter_input( INPUT_GET, 'id', FILTER_SANITIZE_SPECIAL_CHARS );
}
$wcpoa_order = wc_get_order( $order_id );
if ( empty( $wcpoa_order ) ) {
    return;
}
$order_statuses = wc_get_order_statuses();
$items = $wcpoa_order->get_items( array('line_item') );
$wcpoa_att_values_key = array();
$current_date = gmdate( "Y/m/d" );
$wcpoa_today_date = strtotime( $current_date );
$wcpoa_today_date_time = current_time( 'Y/m/d H:i:s' );
// Fetch order data and get order time
$wc_order = new WC_Order($order_id);
$order_data = $wc_order->get_data();
$order_time = '';
if ( isset( $order_data['date_created'] ) && !empty( $order_data['date_created'] ) ) {
    $order_datetime = new DateTime($order_data['date_created']->date( 'Y/m/d H:i:s' ));
    $order_time = $order_datetime->format( 'Y/m/d H:i:s' );
}
$wcpoa_att_values_product_key = array();
$wcpoa_all_att_values_product_key = array();
$get_permalink_structure = get_permalink( $order_id );
if ( strpos( $get_permalink_structure, "?" ) ) {
    $wcpoa_attachment_url_arg = '&';
} else {
    $wcpoa_attachment_url_arg = '?';
}
if ( !empty( $items ) && is_array( $items ) ) {
    foreach ( $items as $item_id => $item ) {
        //single product page attachment
        $wcpoa_order_attachment_items = wc_get_order_item_meta( $item_id, 'wcpoa_order_attachment_order_arr', true );
        if ( !empty( $wcpoa_order_attachment_items ) ) {
            $wcpoa_attachment_ids = ( isset( $wcpoa_order_attachment_items['wcpoa_attachment_ids'] ) && !empty( $wcpoa_order_attachment_items['wcpoa_attachment_ids'] ) ? $wcpoa_order_attachment_items['wcpoa_attachment_ids'] : '' );
            $wcpoa_attachment_name = ( isset( $wcpoa_order_attachment_items['wcpoa_attachment_name'] ) && !empty( $wcpoa_order_attachment_items['wcpoa_attachment_name'] ) ? $wcpoa_order_attachment_items['wcpoa_attachment_name'] : '' );
            $wcpoa_attachment_url = ( isset( $wcpoa_order_attachment_items['wcpoa_attachment_url'] ) && !empty( $wcpoa_order_attachment_items['wcpoa_attachment_url'] ) ? $wcpoa_order_attachment_items['wcpoa_attachment_url'] : '' );
            $wcpoa_expire_date_enable = ( isset( $wcpoa_order_attachment_items['wcpoa_expired_date_enable'] ) && !empty( $wcpoa_order_attachment_items['wcpoa_expired_date_enable'] ) ? $wcpoa_order_attachment_items['wcpoa_expired_date_enable'] : '' );
            $wcpoa_expired_date = ( isset( $wcpoa_order_attachment_items['wcpoa_expired_date'] ) && !empty( $wcpoa_order_attachment_items['wcpoa_expired_date'] ) ? $wcpoa_order_attachment_items['wcpoa_expired_date'] : '' );
            $wcpoa_attach_type = ( isset( $wcpoa_order_attachment_items['wcpoa_attach_type'] ) && !empty( $wcpoa_order_attachment_items['wcpoa_attach_type'] ) ? $wcpoa_order_attachment_items['wcpoa_attach_type'] : '' );
            $wcpoa_order_status = ( isset( $wcpoa_order_attachment_items['wcpoa_order_status'] ) && !empty( $wcpoa_order_attachment_items['wcpoa_order_status'] ) ? $wcpoa_order_attachment_items['wcpoa_order_status'] : '' );
            $wcpoa_order_attachment_expired = ( isset( $wcpoa_order_attachment_items['wcpoa_order_attachment_expired'] ) && !empty( $wcpoa_order_attachment_items['wcpoa_order_attachment_expired'] ) ? $wcpoa_order_attachment_items['wcpoa_order_attachment_expired'] : '' );
            $attached_variations = array();
            $wcpoa_order_product_variation = "";
            $selected_variation_id = '';
            if ( !empty( $selected_variation_id ) && is_array( $attached_variations ) && in_array( (int) $selected_variation_id, convert_array_to_int( $attached_variations ), true ) ) {
            } else {
                foreach ( (array) $wcpoa_attachment_ids as $key => $wcpoa_attachments_id ) {
                    if ( !empty( $wcpoa_attachments_id ) || $wcpoa_attachments_id !== '' ) {
                        if ( !in_array( $wcpoa_attachments_id, $wcpoa_att_values_key, true ) ) {
                            if ( !empty( $wcpoa_attachment_ids ) ) {
                                $wcpoa_att_values_key[] = $wcpoa_attachments_id;
                                $attachment_name = ( isset( $wcpoa_attachment_name[$key] ) && !empty( $wcpoa_attachment_name[$key] ) ? $wcpoa_attachment_name[$key] : '' );
                                $wcpoa_expired_date_enable = ( isset( $wcpoa_expire_date_enable[$key] ) && !empty( $wcpoa_expire_date_enable[$key] ) ? $wcpoa_expire_date_enable[$key] : '' );
                                $wcpoa_attachment_file = ( isset( $wcpoa_attachment_url[$key] ) && !empty( $wcpoa_attachment_url[$key] ) ? $wcpoa_attachment_url[$key] : '' );
                                $wcpoa_order_status_val = ( isset( $wcpoa_order_status[$wcpoa_attachments_id] ) && !empty( $wcpoa_order_status[$wcpoa_attachments_id] ) && $wcpoa_order_status[$wcpoa_attachments_id] ? $wcpoa_order_status[$wcpoa_attachments_id] : array() );
                                $wcpoa_order_status_new = str_replace( 'wcpoa-', '', $wcpoa_order_status_val );
                                $wcpoa_expired_dates = ( isset( $wcpoa_order_attachment_expired[$key] ) && !empty( $wcpoa_order_attachment_expired[$key] ) ? $wcpoa_order_attachment_expired[$key] : '' );
                                $attachment_id = $wcpoa_attachment_file;
                                // ID of attachment
                                echo '<table class="wcpoa_order">';
                                echo '<tbody>';
                                $wcpoa_attachment_expired_date = strtotime( $wcpoa_expired_dates );
                                $attachment_order_name = '<h3 class="wcpoa_attachment_name">' . $attachment_name . '</h3>';
                                $wcpoa_file_url_btn = '<a class="wcpoa_attachmentbtn" href="' . get_permalink( $order_id ) . $wcpoa_attachment_url_arg . 'attachment_id=' . $attachment_id . '&download_file=' . $wcpoa_attachments_id . '" rel="nofollow">Download</a>';
                                $wcpoa_file_url_btn = '<a class="wcpoa_attachmentbtn" href="' . get_permalink( $order_id ) . $wcpoa_attachment_url_arg . 'attachment_id=' . $attachment_id . '&download_file=' . $wcpoa_attachments_id . '" rel="nofollow">Download</a>';
                                $wcpoa_file_expired_url_btn = '<a class="wcpoa_order_attachment_expire" rel="nofollow">Download</a>';
                                $wcpoa_expired_date_text = '<p class="order_att_expire_date">' . __( 'This Attachment Expired.', 'woocommerce-product-attachment' ) . '</p>';
                                $wcpoa_never_expired_date_text = '<p class="order_att_expire_date">' . __( 'This Attachment Is Never Expire.', 'woocommerce-product-attachment' ) . '</p>';
                                $wcpoa_expire_date_text = '<p class="order_att_expire_date">' . __( 'This Attachment Expiry Date Is : ', 'woocommerce-product-attachment' ) . $wcpoa_expired_dates . '</p>';
                                if ( !empty( $wcpoa_attachment_expired_date ) && $wcpoa_expired_date_enable === 'yes' ) {
                                    if ( $wcpoa_today_date > $wcpoa_attachment_expired_date ) {
                                        echo wp_kses( $attachment_order_name, $this->allowed_html_tags() );
                                        echo wp_kses( $wcpoa_file_expired_url_btn, $this->allowed_html_tags() );
                                        echo wp_kses( $wcpoa_expired_date_text, $this->allowed_html_tags() );
                                    } else {
                                        echo wp_kses( $attachment_order_name, $this->allowed_html_tags() );
                                        echo wp_kses( $wcpoa_file_url_btn, $this->allowed_html_tags() );
                                        echo wp_kses( $wcpoa_expire_date_text, $this->allowed_html_tags() );
                                    }
                                } else {
                                    echo wp_kses( $attachment_order_name, $this->allowed_html_tags() );
                                    echo wp_kses( $wcpoa_file_url_btn, $this->allowed_html_tags() );
                                    echo wp_kses( $wcpoa_never_expired_date_text, $this->allowed_html_tags() );
                                }
                                echo '<div class="wcpoa-order-status">';
                                foreach ( $order_statuses as $wcpoa_order_status_key => $wcpoa_order_status_value ) {
                                    if ( in_array( $wcpoa_order_status_key, $wcpoa_order_status_new, true ) ) {
                                        $order_status_available = '<h4><span class="dashicons dashicons-yes"></span>' . $wcpoa_order_status_value . '</h4>';
                                        echo wp_kses( $order_status_available, $this->allowed_html_tags() );
                                    } elseif ( empty( $wcpoa_order_status_new ) ) {
                                        $order_status_available = '<h4><span class="dashicons dashicons-yes"></span>' . $wcpoa_order_status_value . '</h4>';
                                        echo wp_kses( $order_status_available, $this->allowed_html_tags() );
                                    } else {
                                        $order_status_available = '<h4><span class="dashicons dashicons-no"></span>' . $wcpoa_order_status_value . '</h4>';
                                        echo wp_kses( $order_status_available, $this->allowed_html_tags() );
                                    }
                                }
                                echo '</div>';
                                echo '</tbody>';
                                echo '</table>';
                            }
                        }
                    }
                }
            }
        }
    }
}
//Bulk Attachment
$wcpoa_bulk_att_data = get_option( 'wcpoa_bulk_attachment_data' );
if ( !empty( $items ) && is_array( $items ) ) {
    $wcpoa_bulk_att_match = 'no';
    $wcpoa_bulk_att_key = array();
    foreach ( $items as $key => $item_value ) {
        $_product = wc_get_product( $item_value['product_id'] );
        if ( !is_a( $_product, 'WC_Product' ) ) {
            return;
        }
        // for all product
        if ( !empty( $wcpoa_bulk_att_data ) && is_array( $wcpoa_bulk_att_data ) ) {
            foreach ( $wcpoa_bulk_att_data as $att_new_key => $wcpoa_bulk_att_values ) {
                if ( !in_array( $att_new_key, $wcpoa_bulk_att_key, true ) ) {
                    if ( !array_key_exists( 'wcpoa_attach_view', $wcpoa_bulk_att_values ) || "enable" === $wcpoa_bulk_att_values['wcpoa_attach_view'] ) {
                        if ( 'no' === $wcpoa_bulk_att_values['wcpoa_is_condition'] ) {
                            if ( !in_array( $att_new_key, $wcpoa_all_att_values_product_key, true ) ) {
                                $wcpoa_all_att_values_product_key[] = $att_new_key;
                                $wcpoa_attachments_bulk_id = ( !empty( $wcpoa_bulk_att_values['wcpoa_attachments_id'] ) ? $wcpoa_bulk_att_values['wcpoa_attachments_id'] : '' );
                                $wcpoa_bulk_attachments_name = ( isset( $wcpoa_bulk_att_values['wcpoa_attachment_name'] ) && !empty( $wcpoa_bulk_att_values['wcpoa_attachment_name'] ) ? $wcpoa_bulk_att_values['wcpoa_attachment_name'] : '' );
                                $wcpoa_bulk_attachment_file = ( isset( $wcpoa_bulk_att_values['wcpoa_attachment_file'] ) && !empty( $wcpoa_bulk_att_values['wcpoa_attachment_file'] ) ? $wcpoa_bulk_att_values['wcpoa_attachment_file'] : '' );
                                $wcpoa_bulk_att_expired_date_enable = ( isset( $wcpoa_bulk_att_values['wcpoa_expired_date_enable'] ) && !empty( $wcpoa_bulk_att_values['wcpoa_expired_date_enable'] ) ? $wcpoa_bulk_att_values['wcpoa_expired_date_enable'] : '' );
                                $wcpoa_bulk_att_expired_dates = ( isset( $wcpoa_bulk_att_values['wcpoa_expired_date'] ) && !empty( $wcpoa_bulk_att_values['wcpoa_expired_date'] ) ? $wcpoa_bulk_att_values['wcpoa_expired_date'] : '' );
                                $wcpoa_attachment_expired_date = strtotime( $wcpoa_bulk_att_expired_dates );
                                $wcpoa_order_bulk_status = ( isset( $wcpoa_bulk_att_values['wcpoa_order_status'] ) && !empty( $wcpoa_bulk_att_values['wcpoa_order_status'] ) ? $wcpoa_bulk_att_values['wcpoa_order_status'] : '' );
                                $wcpoa_attachments_name = '<h3 class="wcpoa_attachment_name">' . esc_html__( $wcpoa_bulk_attachments_name, 'woocommerce-product-attachment' ) . '</h3>';
                                $wcpoa_bulk_file_url_btn = '<a class="wcpoa_attachmentbtn" href="' . get_permalink( $order_id ) . $wcpoa_attachment_url_arg . 'attachment_id=' . $wcpoa_bulk_attachment_file . '&download_file=' . $wcpoa_attachments_bulk_id . '">Download</a>';
                                $wcpoa_bulk_file_expired_url_btn = '<a class="wcpoa_order_attachment_expire" rel="nofollow"> Download </a>';
                                $wcpoa_bulk_expired_date_text = '<p class="order_att_expire_date">' . __( 'This Attachment Expired.', 'woocommerce-product-attachment' ) . '</p>';
                                $wcpoa_bulk_never_expired_date_text = '<p class="order_att_expire_date">' . __( 'This Attachment Never Expires.', 'woocommerce-product-attachment' ) . '</p>';
                                $wcpoa_bulk_duration = '';
                                $wcpoa_bulk_attachment_expired_time = '';
                                if ( !empty( $wcpoa_bulk_attachment_time_amount ) && $wcpoa_bulk_att_expired_date_enable === "time_amount" ) {
                                    $wcpoa_bulk_duration = '+' . $wcpoa_bulk_att_time_amount_concate;
                                    $wcpoa_bulk_attachment_expired_time = gmdate( 'Y/m/d H:i:s', strtotime( $wcpoa_bulk_duration, strtotime( $order_time ) ) );
                                    $wcpoa_bulk_expire_date_text = '<p class="order_att_expire_date">' . __( 'This Attachment Expiry Date Is : ', 'woocommerce-product-attachment' ) . $wcpoa_bulk_attachment_expired_time . '</p>';
                                } else {
                                    $wcpoa_bulk_expire_date_text = '<p class="order_att_expire_date">' . __( 'This Attachment Expiry Date Is : ', 'woocommerce-product-attachment' ) . $wcpoa_bulk_att_expired_dates . '</p>';
                                }
                                $wcpoa_order_status_bulknew = str_replace( 'wcpoa-wc-', '', $wcpoa_order_bulk_status );
                                $wcpoa_order_status_bulknew_val = ( !empty( $wcpoa_order_status_bulknew ) ? $wcpoa_order_status_bulknew : array() );
                                if ( !empty( $wcpoa_attachment_expired_date ) && $wcpoa_bulk_att_expired_date_enable === 'yes' ) {
                                    if ( $wcpoa_today_date > $wcpoa_attachment_expired_date ) {
                                        echo wp_kses( $wcpoa_attachments_name, $this->allowed_html_tags() );
                                        echo wp_kses( $wcpoa_bulk_file_expired_url_btn, $this->allowed_html_tags() );
                                        echo wp_kses( $wcpoa_bulk_expired_date_text, $this->allowed_html_tags() );
                                        $wcpoa_bulk_att_match = 'yes';
                                        $wcpoa_bulk_att_key[] = $att_new_key;
                                    } else {
                                        echo wp_kses( $wcpoa_attachments_name, $this->allowed_html_tags() );
                                        echo wp_kses( $wcpoa_bulk_file_url_btn, $this->allowed_html_tags() );
                                        echo wp_kses( $wcpoa_bulk_expire_date_text, $this->allowed_html_tags() );
                                        $wcpoa_bulk_att_match = 'yes';
                                        $wcpoa_bulk_att_key[] = $att_new_key;
                                    }
                                } else {
                                    echo wp_kses( $wcpoa_attachments_name, $this->allowed_html_tags() );
                                    echo wp_kses( $wcpoa_bulk_file_url_btn, $this->allowed_html_tags() );
                                    echo wp_kses( $wcpoa_bulk_never_expired_date_text, $this->allowed_html_tags() );
                                    $wcpoa_bulk_att_match = 'yes';
                                    $wcpoa_bulk_att_key[] = $att_new_key;
                                }
                                if ( isset( $order_statuses ) && is_array( $order_statuses ) ) {
                                    echo '<div class="wcpoa-order-status">';
                                    foreach ( $order_statuses as $wcpoa_order_status_key => $wcpoa_order_status_bulkvalue ) {
                                        $wcpoa_order_status_key_new = str_replace( 'wc-', '', $wcpoa_order_status_key );
                                        if ( in_array( $wcpoa_order_status_key_new, $wcpoa_order_status_bulknew_val, true ) ) {
                                            $bulkorder_status_available = '<h4><span class="dashicons dashicons-yes"></span>' . $wcpoa_order_status_bulkvalue . '</h4>';
                                            echo wp_kses( $bulkorder_status_available, $this->allowed_html_tags() );
                                        } elseif ( empty( $wcpoa_order_status_bulknew_val ) ) {
                                            $bulkorder_status_available = '<h4><span class="dashicons dashicons-yes"></span>' . $wcpoa_order_status_bulkvalue . '</h4>';
                                            echo wp_kses( $bulkorder_status_available, $this->allowed_html_tags() );
                                        } else {
                                            $bulkorder_status_available = '<h4><span class="dashicons dashicons-no"></span>' . $wcpoa_order_status_bulkvalue . '</h4>';
                                            echo wp_kses( $bulkorder_status_available, $this->allowed_html_tags() );
                                        }
                                    }
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