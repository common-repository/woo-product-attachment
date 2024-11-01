<?php

// If this file is called directly, abort.
if ( !defined( 'WPINC' ) ) {
    die;
}
global 
    $product,
    $post,
    $i,
    $field
;
// vars
$div = array(
    'class'    => 'wcpoa-repeater',
    'data-min' => ( isset( $field['min'] ) ? $field['min'] : '' ),
    'data-max' => ( isset( $field['max'] ) ? $field['max'] : '' ),
);
// ensure value is an array
if ( empty( $field['value'] ) ) {
    $field['value'] = array();
    $div['class'] .= ' -empty';
}
// rows
$field['min'] = ( empty( $field['min'] ) ? 0 : $field['min'] );
$field['max'] = ( empty( $field['max'] ) ? 0 : $field['max'] );
// populate the empty row data (used for wcpoacloneindex and min setting)
$empty_row = array();
// If there are less values than min, populate the extra values
if ( $field['min'] ) {
    for ($i = 0; $i < $field['min']; $i++) {
        // continue if already have a value
        if ( array_key_exists( $i, $field['value'] ) ) {
            continue;
        }
        // populate values
        $field['value'][$i] = $empty_row;
    }
}
// If there are more values than man, remove some values
if ( $field['max'] ) {
    for ($i = 0; $i < count( $field['value'] ); $i++) {
        if ( $i >= $field['max'] ) {
            unset($field['value'][$i]);
        }
    }
}
// setup values for row clone
$field['value']['wcpoacloneindex'] = $empty_row;
// show columns
$show_order = false;
$show_add = true;
if ( $field['max'] ) {
    if ( (int) $field['max'] === 1 ) {
        $show_order = false;
    }
    if ( $field['max'] <= $field['min'] ) {
        $show_add = false;
    }
}
// field wrap
$before_fields = '';
$after_fields = '';
if ( 'row' === 'row' ) {
    $before_fields = '<td class="wcpoa-fields -left">';
    $after_fields = '</td>';
}
// layout
$div['class'] .= ' -' . 'row';
$plugin_url = WCPOA_PLUGIN_URL;
$get_post_id = filter_input( INPUT_GET, 'post', FILTER_SANITIZE_SPECIAL_CHARS );
if ( isset( $get_post_id ) && !empty( $get_post_id ) ) {
    $product_id = $get_post_id;
} else {
    $product_id = $post->ID;
}
$_product = wc_get_product( $product_id );
if ( !is_a( $_product, 'WC_Product' ) ) {
    return;
}
$wcpoa_attachment_ids = get_post_meta( $product_id, 'wcpoa_attachments_id', true );
$wcpoa_attachment_name = get_post_meta( $product_id, 'wcpoa_attachment_name', true );
$wcpoa_attach_type = get_post_meta( $product_id, 'wcpoa_attach_type', true );
$wcpoa_attachment_ext_url = get_post_meta( $product_id, 'wcpoa_attachment_ext_url', true );
$wcpoa_attachment_url = get_post_meta( $product_id, 'wcpoa_attachment_url', true );
$wcpoa_attachment_descriptions = get_post_meta( $product_id, 'wcpoa_attachment_description', true );
$wcpoa_product_open_window_flag = get_post_meta( $product_id, 'wcpoa_product_open_window_flag', true );
$wcpoa_product_page_enable = get_post_meta( $product_id, 'wcpoa_product_page_enable', true );
$wcpoa_product_logged_in_flag = get_post_meta( $product_id, 'wcpoa_product_logged_in_flag', true );
$wcpoa_product_variation = get_post_meta( $product_id, 'wcpoa_variation', true );
$wcpoa_order_status = array();
$wcpoa_pd_enable = get_post_meta( $product_id, 'wcpoa_expired_date_enable', true );
$wcpoa_expired_date = get_post_meta( $product_id, 'wcpoa_expired_date', true );
wp_nonce_field( plugin_basename( __FILE__ ), 'wcpoa_attachment_nonce' );
// Upgrade to pro popup
if ( !(wpap_fs()->is__premium_only() && wpap_fs()->can_use_premium_code()) ) {
    require_once WCPOA_PLUGIN_PATH . 'admin/partials/dots-upgrade-popup.php';
}
?>
<div class="wcpoa-field wcpoa-single-prod-attach wcpoa-field-repeater" data-name="attachments" data-type="repeater"
    data-key="attachments">
    <div class="wcpoa-label">
        <span><?php 
esc_html_e( 'Easily manage and create multiple attachments for this product. ', 'woocommerce-product-attachment' );
?></span><br>
    </div>

    <div class="wcpoa-input">
        <div <?php 
$this->wcpoa_esc_attr_e( $div );
?>>
            <table class="wcpoa-table wcpoa-prod-table">
                <tbody id="wcpoa-ui-tbody" class="wcpoa-ui-sortable">
                    <tr>
                        <th>
                            <div class="wcpoa-label top-heading">
                                <label for="attchment_order"><?php 
esc_html_e( 'No.', 'woocommerce-product-attachment' );
?></label>
                            </div>
                            <div class="wcpoa-label top-heading">
                                <label for="attchment_name"><?php 
esc_html_e( 'Name', 'woocommerce-product-attachment' );
?></label>
                            </div>
                            <div class="wcpoa-label top-heading not-mobile">
                                <label for="attchment_type"><?php 
esc_html_e( 'Type', 'woocommerce-product-attachment' );
?></label>
                            </div>
                            <div class="wcpoa-label top-heading not-mobile">
                                <label
                                    for="attchment_visibility"><?php 
esc_html_e( 'Show Product page', 'woocommerce-product-attachment' );
?></label>
                            </div>
                            <div class="wcpoa-label top-heading not-mobile">
                                <label for="attchment_remove"><?php 
esc_html_e( 'Expire', 'woocommerce-product-attachment' );
?></label>
                            </div>
                        </th>
                    </tr>
                    <?php 
if ( !empty( $wcpoa_attachment_ids ) && is_array( $wcpoa_attachment_ids ) ) {
    foreach ( $wcpoa_attachment_ids as $key => $wcpoa_attachments_id ) {
        if ( !empty( $wcpoa_attachments_id ) ) {
            $attachment_name = ( isset( $wcpoa_attachment_name[$key] ) && !empty( $wcpoa_attachment_name[$key] ) ? $wcpoa_attachment_name[$key] : '' );
            $wcpoa_attachment_file_id = ( isset( $wcpoa_attachment_url[$key] ) && !empty( $wcpoa_attachment_url[$key] ) ? $wcpoa_attachment_url[$key] : '' );
            $wcpoa_attach_type_single = ( isset( $wcpoa_attach_type[$key] ) && !empty( $wcpoa_attach_type[$key] ) ? $wcpoa_attach_type[$key] : 'file_upload' );
            $wcpoa_attachment_description = ( isset( $wcpoa_attachment_descriptions[$key] ) && !empty( $wcpoa_attachment_descriptions[$key] ) ? $wcpoa_attachment_descriptions[$key] : '' );
            $wcpoa_product_open_window_flag_val = ( isset( $wcpoa_product_open_window_flag[$key] ) && !empty( $wcpoa_product_open_window_flag[$key] ) ? $wcpoa_product_open_window_flag[$key] : '' );
            $wcpoa_product_p_enable = ( isset( $wcpoa_product_page_enable[$key] ) && !empty( $wcpoa_product_page_enable[$key] ) ? $wcpoa_product_page_enable[$key] : '' );
            $wcpoa_product_logged_in_flag_val = ( isset( $wcpoa_product_logged_in_flag[$key] ) && !empty( $wcpoa_product_logged_in_flag[$key] ) ? $wcpoa_product_logged_in_flag[$key] : '' );
            $wcpoa_product_date_enable = ( isset( $wcpoa_pd_enable[$key] ) && !empty( $wcpoa_pd_enable[$key] ) ? $wcpoa_pd_enable[$key] : '' );
            $wcpoa_expired_dates = ( isset( $wcpoa_expired_date[$key] ) && !empty( $wcpoa_expired_date[$key] ) ? $wcpoa_expired_date[$key] : '' );
            $wcpoa_order_status_value = get_post_meta( $product_id, 'wcpoa_order_status', true );
            if ( $wcpoa_order_status_value === 'wc-all' ) {
                $wcpoa_order_status = array();
            } else {
                $wcpoa_order_status = ( isset( $wcpoa_order_status_value[$wcpoa_attachments_id] ) && !empty( $wcpoa_order_status_value[$wcpoa_attachments_id] ) ? $wcpoa_order_status_value[$wcpoa_attachments_id] : array() );
            }
            //file upload
            // vars
            $uploader = 'uploader';
            // vars
            $o = array(
                'icon'     => '',
                'title'    => '',
                'url'      => '',
                'filesize' => '',
                'filename' => '',
            );
            $filediv = array(
                'class'         => 'wcpoa-file-uploader wcpoa-cf',
                'data-uploader' => $uploader,
            );
            // has value?
            if ( !empty( $wcpoa_attachment_file_id ) ) {
                $file = get_post( $wcpoa_attachment_file_id );
                if ( $file ) {
                    $o['icon'] = wp_mime_type_icon( $wcpoa_attachment_file_id );
                    $o['title'] = $file->post_title;
                    $o['filesize'] = size_format( filesize( get_attached_file( $wcpoa_attachment_file_id ) ) );
                    $o['url'] = wp_get_attachment_url( $wcpoa_attachment_file_id );
                    $explode = explode( '/', $o['url'] );
                    $o['filename'] = end( $explode );
                }
                // url exists
                if ( $o['url'] ) {
                    $filediv['class'] .= ' has-value';
                }
            }
            ?>
                                <tr class="wcpoa-row wcpoa-has-value -collapsed"
                                    data-id="<?php 
            echo esc_attr( $wcpoa_attachments_id );
            ?>"
                                    id="<?php 
            echo esc_attr( $wcpoa_attachments_id );
            ?>">

                                    <?php 
            echo wp_kses( $before_fields, $this->allowed_html_tags() );
            ?>
                                    <div class="wcpoa-field -collapsed-target group-title" data-name="_name" data-type="text"
                                        data-key="">
                                        <div class="wcpoa-label order">
                                            <span class="attchment_order"><?php 
            echo intval( $i ) + 1;
            $i++;
            ?></span>
                                        </div>
                                        <div class="wcpoa-label attachment_name">
                                            <label
                                                for="attchment_name"><?php 
            esc_html_e( $attachment_name, 'woocommerce-product-attachment' );
            ?></label>
                                            <ul class="attachment_action">
                                                <li><a class="edit_bulk_attach"
                                                        href="#"><?php 
            esc_html_e( 'Edit', 'woocommerce-product-attachment' );
            ?></a></li>
                                                <li><a class="-minus small wcpoa-js-tooltip" href="#" data-event="remove-row"
                                                        title="<?php 
            esc_attr_e( 'Remove', 'woocommerce-product-attachment' );
            ?>"><?php 
            esc_html_e( 'Delete', 'woocommerce-product-attachment' );
            ?></a>
                                                </li>
                                            </ul>
                                        </div>
                                        <div class="wcpoa-label not-mobile">
                                            <?php 
            if ( $wcpoa_attach_type_single === "file_upload" ) {
                $path_parts = pathinfo( $o['url'] );
                $ext = strtolower( $path_parts["extension"] );
                $file_upload_text = 'File Upload ( .' . $ext . ' )';
            }
            ?>
                                            <label
                                                for="attchment_type"><?php 
            ( $wcpoa_attach_type_single === "file_upload" ? esc_html_e( $file_upload_text, 'woocommerce-product-attachment' ) : esc_html_e( 'External URL', 'woocommerce-product-attachment' ) );
            ?></label>
                                        </div>
                                        <div class="wcpoa-label not-mobile">
                                            <label for="attchment_visibility">
                                                <?php 
            if ( "yes" === $wcpoa_product_p_enable ) {
                esc_html_e( 'Yes', 'woocommerce-product-attachment' );
            } else {
                esc_html_e( 'No', 'woocommerce-product-attachment' );
            }
            ?>
                                            </label>
                                        </div>
                                        <div class="wcpoa-label not-mobile">
                                            <label for="attchment_expire">
                                                <?php 
            if ( "no" === $wcpoa_product_date_enable ) {
                esc_html_e( 'No', 'woocommerce-product-attachment' );
            } elseif ( "yes" === $wcpoa_product_date_enable ) {
                esc_html_e( 'Specific Date', 'woocommerce-product-attachment' );
            } elseif ( "time_amount" === $wcpoa_product_date_enable ) {
                esc_html_e( 'Specific Time', 'woocommerce-product-attachment' );
            }
            ?>
                                            </label>
                                        </div>
                                    </div>
                                    <div class="wcpoa-field wcpoa-field-id" data-name="id" data-type="text" data-key="">
                                        <div class="wcpoa-label">
                                            <label for=""><?php 
            esc_html_e( 'Id', 'woocommerce-product-attachment' );
            ?> </label>
                                        </div>
                                        <div class="wcpoa-input">
                                            <div class="wcpoa-input-wrap">
                                                <input readonly="" class="wcpoa_attachments_id" name="wcpoa_attachments_id[]"
                                                    value="<?php 
            echo esc_attr( $wcpoa_attachments_id );
            ?>" placeholder=""
                                                    type="text">
                                                <span class="wcpoa-description-tooltip-icon"></span>
                                                <p class="wcpoa-description">
                                                    <?php 
            esc_html_e( 'Attachments Id used to identify each product attachment.This value is automatically generated.', 'woocommerce-product-attachment' );
            ?>
                                                </p>
                                            </div>
                                        </div>
                                    </div>

                                    <div class="wcpoa-field" data-name="_name" data-type="text" data-key="">
                                        <div class="wcpoa-label">
                                            <label for="attchment_name"><?php 
            esc_html_e( 'Name', 'woocommerce-product-attachment' );
            ?>
                                                <span class="wcpoa-required"> *</span></label>
                                        </div>
                                        <div class="wcpoa-input wcpoa-att-name-parent">
                                            <input class="wcpoa-attachment-name" type="text" name="wcpoa_attachment_name[]" placeholder="<?php 
            esc_attr_e( 'Attachment', 'woocommerce-product-attachment' );
            ?>" value="<?php 
            echo esc_attr( $attachment_name );
            ?>">
                                            <span class="wcpoa-description-tooltip-icon"></span>
                                            <p class="wcpoa-description">
                                                <?php 
            esc_html_e( 'Enter a name for the attachment. It will be displayed on the front end next to the download/view button.', 'woocommerce-product-attachment' );
            ?>
                                            </p>
                                        </div>
                                    </div>
                                    <div class="wcpoa-field wcpoa-field-textarea " data-name="description" data-type="textarea"
                                        data-key="" data-required="1">
                                        <div class="wcpoa-label">
                                            <label
                                                for="attchment_desc"><?php 
            esc_html_e( 'Description', 'woocommerce-product-attachment' );
            ?></label>
                                        </div>
                                        <div class="wcpoa-input">
                                            <textarea class="" name="wcpoa_attachment_description[]" placeholder="<?php 
            esc_attr_e( 'Enter a description', 'woocommerce-product-attachment' );
            ?>" rows="8"><?php 
            echo esc_html( $wcpoa_attachment_description );
            ?></textarea>
                                            <span class="wcpoa-description-tooltip-icon"></span>
                                            <p class="wcpoa-description">
                                                <?php 
            esc_html_e( 'You can type a short description of the attachment file. So customers will get details about the attachment file.', 'woocommerce-product-attachment' );
            ?>
                                            </p>
                                        </div>
                                    </div>
                                    <div class="wcpoa-field wcpoa-field-select">
                                        <div class="wcpoa-label">
                                            <label
                                                for="wcpoa_attach_type"><?php 
            esc_html_e( 'Attachment Type', 'woocommerce-product-attachment' );
            ?></label>
                                        </div>

                                        <div class="wcpoa-input wcpoa_attach_type">
                                            <?php 
            ?>
                                                    <select name="wcpoa_attach_type[]" class="wcpoa_attach_type_list" data-type=""
                                                        data-key="">
                                                        <option name="file_upload"
                                                            <?php 
            echo ( $wcpoa_attach_type_single === "file_upload" ? 'selected' : '' );
            ?>
                                                            value="file_upload"><?php 
            esc_html_e( 'File Upload', 'woocommerce-product-attachment' );
            ?>
                                                        </option>
                                                        <option value="external_ulr" class="wcpoa_pro_class">
                                                            <?php 
            esc_html_e( 'External URL 🔒', 'woocommerce-product-attachment' );
            ?></option>
                                                    </select>
                                            <?php 
            ?>
                                            <span class="wcpoa-description-tooltip-icon"></span>
                                            <p class="wcpoa-description">
                                                <?php 
            esc_html_e( 'Select the attachment type. Like Upload file / External URL', 'woocommerce-product-attachment' );
            ?>
                                            </p>
                                        </div>
                                    </div>
                                    <?php 
            $is_show = "";
            ?>
                                    <div style="display:<?php 
            echo esc_attr( $is_show );
            ?>"
                                        class="wcpoa-field file_upload wcpoa-field-file required" data-name="file" data-type="file"
                                        data-key="" data-required="1">
                                        <div class="wcpoa-label">
                                            <div class="wcpoa-label">
                                                <label
                                                    for="fee_settings_start_date"><?php 
            esc_html_e( 'Upload Attachment File', 'woocommerce-product-attachment' );
            ?>
                                                    <span class="wcpoa-required">*</span>
                                                </label>
                                            </div>
                                        </div>
                                        <div class="wcpoa-input" data-id="<?php 
            echo esc_attr( $wcpoa_attachments_id );
            ?>">
                                            <div <?php 
            $this->wcpoa_esc_attr_e( $filediv );
            ?>>
                                                <div class="wcpoa-error-message">
                                                    <p><?php 
            echo 'File value is required';
            ?></p>
                                                    <input name="wcpoa_attachment_file[]" data-validation="[NOTEMPTY]"
                                                        value="<?php 
            echo esc_attr( $wcpoa_attachment_file_id );
            ?>" data-name="id"
                                                        type="hidden" required="required">
                                                </div>
                                                <div class="show-if-value file-wrap wcpoa-soh">
                                                    <div class="file-icon">
                                                        <img data-name="icon" src="<?php 
            echo esc_url( $o['icon'] );
            ?>" alt="" />
                                                    </div>
                                                    <div class="file-info">
                                                        <p>
                                                            <strong data-name="title"><?php 
            echo esc_html( $o['title'] );
            ?></strong>
                                                        </p>
                                                        <p>
                                                            <strong><?php 
            esc_html_e( 'File name', 'woocommerce-product-attachment' );
            ?>
                                                                :</strong>
                                                            <a data-name="filename" href="<?php 
            echo esc_url( $o['url'] );
            ?>"
                                                                target="_blank"><?php 
            echo esc_html( $o['filename'] );
            ?></a>
                                                        </p>
                                                        <p>
                                                            <strong><?php 
            esc_html_e( 'File size', 'woocommerce-product-attachment' );
            ?>
                                                                :</strong>
                                                            <span
                                                                data-name="filesize"><?php 
            echo esc_html( $o['filesize'] );
            ?></span>
                                                        </p>

                                                        <ul class="wcpoa-hl wcpoa-soh-target">
                                                            <?php 
            if ( $uploader !== 'basic' ) {
                ?>
                                                            <li><a data-id="<?php 
                echo esc_attr( $wcpoa_attachments_id );
                ?>"
                                                                    class="wcpoa-icon -pencil dark" data-name="edit" href="#"></a>
                                                            </li>
                                                            <?php 
            }
            ?>
                                                            <li><a data-id="<?php 
            echo esc_attr( $wcpoa_attachments_id );
            ?>"
                                                                    class="wcpoa-icon -cancel dark" data-name="remove" href="#"></a>
                                                            </li>
                                                        </ul>
                                                    </div>
                                                </div>
                                                <div class="hide-if-value">
                                                    <?php 
            if ( $uploader === 'basic' ) {
                ?>
                                                    <?php 
                if ( $field['value'] && !is_numeric( $field['value'] ) ) {
                    ?>
                                                    <div class="wcpoa-error-message">
                                                        <p><?php 
                    echo esc_html( $field['value'] );
                    ?></p>
                                                    </div>
                                                    <?php 
                }
                ?>
                                                    <input type="file" name="<?php 
                echo esc_attr( $field['name'] );
                ?>"
                                                        id="<?php 
                echo esc_attr( $field['id'] );
                ?>" />
                                                    <?php 
            } else {
                ?>
                                                    <p style="margin:0;">
                                                        <?php 
                esc_html_e( 'No file selected', 'woocommerce-product-attachment' );
                ?>
                                                        <?php 
                echo wp_kses( $this->wcpoa_image_uploader_field( $wcpoa_attachments_id ), $this->allowed_html_tags() );
                ?>
                                                    </p>
                                                    <?php 
            }
            ?>

                                                </div>
                                                <p class="description">
                                                    <?php 
            esc_html_e( 'Select upload attachment File.', 'woocommerce-product-attachment' );
            ?></p>
                                            </div>
                                        </div>
                                    </div>
                                    <?php 
            ?>
                                    <div class="wcpoa-field">
                                        <div class="wcpoa-label">
                                            <label
                                                for="product_page_enable"><?php 
            esc_html_e( 'Open in new window', 'woocommerce-product-attachment' );
            ?></label>
                                        </div>
                                        <div class="wcpoa-input">
                                            <select id="wcpoa_product_open_window_flag" name="wcpoa_product_open_window_flag[]">
                                                <option name="no"
                                                    <?php 
            echo ( $wcpoa_product_open_window_flag_val === "no" ? 'selected' : '' );
            ?>
                                                    value="no"><?php 
            esc_html_e( 'No', 'woocommerce-product-attachment' );
            ?></option>
                                                <option name="yes"
                                                    <?php 
            echo ( $wcpoa_product_open_window_flag_val === "yes" ? 'selected' : '' );
            ?>
                                                    value="yes"><?php 
            esc_html_e( 'Yes', 'woocommerce-product-attachment' );
            ?></option>
                                            </select>
                                            <span class="wcpoa-description-tooltip-icon"></span>
                                            <p class="wcpoa-description">
                                                <?php 
            esc_html_e( 'Select Link Behavior: Specify whether you want the attachment link to open in a new window or the same window.', 'woocommerce-product-attachment' );
            ?>
                                            </p>
                                        </div>
                                    </div>

                                    <div class="wcpoa-field">
                                        <div class="wcpoa-label">
                                            <label
                                                for="product_page_enable"><?php 
            esc_html_e( 'Show on Product page', 'woocommerce-product-attachment' );
            ?></label>
                                        </div>
                                        <div class="wcpoa-input">
                                            <select id="wcpoa_product_page_enable" name="wcpoa_product_page_enable[]">
                                                <option name="yes"
                                                    <?php 
            echo ( $wcpoa_product_p_enable === "yes" ? 'selected' : '' );
            ?>
                                                    value="yes"><?php 
            esc_html_e( 'Yes', 'woocommerce-product-attachment' );
            ?></option>
                                                <option name="no"
                                                    <?php 
            echo ( $wcpoa_product_p_enable === "no" ? 'selected' : '' );
            ?> value="no">
                                                    <?php 
            esc_html_e( 'No', 'woocommerce-product-attachment' );
            ?></option>
                                            </select>
                                            <span class="wcpoa-description-tooltip-icon"></span>
                                            <p class="wcpoa-description">
                                                <?php 
            esc_html_e( 'Select whether you want to display the attachment on the product page or not.', 'woocommerce-product-attachment' );
            ?>
                                            </p>
                                        </div>
                                    </div>
                                    <div class="wcpoa-field">
                                        <div class="wcpoa-label">
                                            <label
                                                for="product_page_enable"><?php 
            esc_html_e( 'Show only for logged in users', 'woocommerce-product-attachment' );
            ?></label>
                                        </div>
                                        <div class="wcpoa-input">
                                            <select id="wcpoa_product_logged_in_flag" name="wcpoa_product_logged_in_flag[]">
                                                <option name="no"
                                                    <?php 
            echo ( $wcpoa_product_logged_in_flag_val === "no" ? 'selected' : '' );
            ?>
                                                    value="no"><?php 
            esc_html_e( 'No', 'woocommerce-product-attachment' );
            ?></option>
                                                <option name="yes"
                                                    <?php 
            echo ( $wcpoa_product_logged_in_flag_val === "yes" ? 'selected' : '' );
            ?>
                                                    value="yes"><?php 
            esc_html_e( 'Yes', 'woocommerce-product-attachment' );
            ?></option>
                                            </select>
                                            <span class="wcpoa-description-tooltip-icon"></span>
                                            <p class="wcpoa-description">
                                                <?php 
            esc_html_e( 'Select whether you want to display the attachment only for logged-in users or not.', 'woocommerce-product-attachment' );
            ?>
                                            </p>
                                        </div>
                                    </div>
                                    <?php 
            ?>
                                    <div class="wcpoa-field">
                                        <div class="wcpoa-label">
                                            <label for="attchment_order_status"><?php 
            esc_html_e( 'Order status', 'woocommerce-product-attachment' );
            ?></label>

                                        </div>
                                        <div class="wcpoa-input">
                                            <p class="description">
                                                <?php 
            esc_html_e( 'Select order status, where you want to showcase attachment. Leave unselected then apply to all.', 'woocommerce-product-attachment' );
            ?>
                                            </p>
                                            <ul class="wcpoa-checkbox-list">
                                                <li>
                                                    <input name="wcpoa_order_status[<?php 
            echo esc_attr( $wcpoa_attachments_id );
            ?>][]" id="wcpoa_wc_order_completed" value="wcpoa-wc-completed" type="checkbox" <?php 
            if ( !is_null( $wcpoa_order_status ) && in_array( 'wcpoa-wc-completed', $wcpoa_order_status, true ) ) {
                echo 'checked="checked"';
            }
            ?>>
                                                    <label for="wcpoa_wc_order_completed"><?php 
            esc_html_e( 'Completed', 'woocommerce-product-attachment' );
            ?></label>
                                                </li>
                                                <li>
                                                    <input name="wcpoa_order_status[<?php 
            echo esc_attr( $wcpoa_attachments_id );
            ?>][]" id="wcpoa_wc_order_on_hold" value="wcpoa-wc-on-hold" type="checkbox" <?php 
            if ( !is_null( $wcpoa_order_status ) && in_array( 'wcpoa-wc-on-hold', $wcpoa_order_status, true ) ) {
                echo 'checked="checked"';
            }
            ?>>
                                                    <label for="wcpoa_wc_order_on_hold"><?php 
            esc_html_e( 'On Hold', 'woocommerce-product-attachment' );
            ?></label>
                                                </li>
                                                <li>
                                                    <input name="wcpoa_order_status[<?php 
            echo esc_attr( $wcpoa_attachments_id );
            ?>][]" id="wcpoa_wc_order_pending" value="wcpoa-wc-pending" type="checkbox" <?php 
            if ( !is_null( $wcpoa_order_status ) && in_array( 'wcpoa-wc-pending', $wcpoa_order_status, true ) ) {
                echo 'checked="checked"';
            }
            ?>>
                                                    <label for="wcpoa_wc_order_pending"><?php 
            esc_html_e( 'Pending payment', 'woocommerce-product-attachment' );
            ?></label>
                                                </li>
                                                <li>
                                                    <input name="wcpoa_order_status[<?php 
            echo esc_attr( $wcpoa_attachments_id );
            ?>][]" id="wcpoa_wc_order_processing" value="wcpoa-wc-processing" type="checkbox" <?php 
            if ( !is_null( $wcpoa_order_status ) && in_array( 'wcpoa-wc-processing', $wcpoa_order_status, true ) ) {
                echo 'checked="checked"';
            }
            ?>>
                                                    <label for="wcpoa_wc_order_processing"><?php 
            esc_html_e( 'Processing', 'woocommerce-product-attachment' );
            ?></label>
                                                </li>
                                                <li>
                                                    <input name="wcpoa_order_status[<?php 
            echo esc_attr( $wcpoa_attachments_id );
            ?>][]" id="wcpoa_wc_order_cancelled" value="wcpoa-wc-cancelled" type="checkbox" <?php 
            if ( !is_null( $wcpoa_order_status ) && in_array( 'wcpoa-wc-cancelled', $wcpoa_order_status, true ) ) {
                echo 'checked="checked"';
            }
            ?>>
                                                    <label for="wcpoa_wc_order_cancelled"><?php 
            esc_html_e( 'Cancelled', 'woocommerce-product-attachment' );
            ?></label>
                                                </li>
                                                <li>
                                                    <input name="wcpoa_order_status[<?php 
            echo esc_attr( $wcpoa_attachments_id );
            ?>][]" id="wcpoa_wc_order_failed" value="wcpoa-wc-failed" type="checkbox" <?php 
            if ( !is_null( $wcpoa_order_status ) && in_array( 'wcpoa-wc-failed', $wcpoa_order_status, true ) ) {
                echo 'checked="checked"';
            }
            ?>>
                                                    <label for="wcpoa_wc_order_failed"><?php 
            esc_html_e( 'Failed', 'woocommerce-product-attachment' );
            ?></label>
                                                </li>
                                                <li>
                                                    <input name="wcpoa_order_status[<?php 
            echo esc_attr( $wcpoa_attachments_id );
            ?>][]" id="wcpoa_wc_order_refunded" value="wcpoa-wc-refunded" type="checkbox" <?php 
            if ( !is_null( $wcpoa_order_status ) && in_array( 'wcpoa-wc-refunded', $wcpoa_order_status, true ) ) {
                echo 'checked="checked"';
            }
            ?>>
                                                    <label for="wcpoa_wc_order_refunded"><?php 
            esc_html_e( 'Refunded', 'woocommerce-product-attachment' );
            ?></label>
                                                </li>
                                            </ul>

                                        </div>
                                    </div>

                                    <div class="wcpoa-field">
                                        <div class="wcpoa-label">
                                            <label for="wcpoa_expired_date_enable"><?php 
            esc_html_e( 'Set Expire date/time', 'woocommerce-product-attachment' );
            ?></label>
                                        </div>
                                        <div class="wcpoa-input enable_expire_date">
                                            <select name="wcpoa_expired_date_enable[]" class="enable_date_time" data-type="enable_date_<?php 
            echo esc_attr( $wcpoa_attachments_id );
            ?>" data-key="">
                                                <option name="no"
                                                    <?php 
            echo ( $wcpoa_product_date_enable === "no" ? 'selected' : '' );
            ?>
                                                    value="no" class=""><?php 
            esc_html_e( 'No', 'woocommerce-product-attachment' );
            ?></option>
                                                <option name="yes"
                                                    <?php 
            echo ( $wcpoa_product_date_enable === "yes" ? 'selected' : '' );
            ?>
                                                    value="yes"><?php 
            esc_html_e( 'Specific Date', 'woocommerce-product-attachment' );
            ?></option>
                                                <?php 
            ?>
                                                        <option value="time_amount" class="wcpoa_pro_class">
                                                            <?php 
            esc_html_e( 'Selected time period after purchase 🔒', 'woocommerce-product-attachment' );
            ?>
                                                        </option>
                                                <?php 
            ?>
                                            </select>
                                            <span class="wcpoa-description-tooltip-icon"></span>
                                            <p class="wcpoa-description">
                                                <?php 
            esc_html_e( 'Set a specific date and specific time to access the attachment.', 'woocommerce-product-attachment' );
            ?>
                                            </p>
                                        </div>
                                    </div>
                                    <?php 
            $is_date = ( $wcpoa_product_date_enable !== 'yes' ? 'none' : '' );
            ?>
                                    <div style="display:<?php 
            echo esc_attr( $is_date );
            ?>"
                                        class="wcpoa-field enable_date enable_date_<?php 
            echo esc_attr( $wcpoa_attachments_id );
            ?> wcpoa-field-date-picker"
                                        data-name="date" data-type="date_picker" data-key="" data-required="1" style=''>
                                        <div class="wcpoa-label">
                                            <label
                                                for="wcpoa_expired_date"><?php 
            esc_html_e( 'Specific Date', 'woocommerce-product-attachment' );
            ?></label>
                                        </div>
                                        <div class="wcpoa-input">
                                            <div class="wcpoa-date-picker wcpoa-input-wrap" data-date_format="yy/mm/dd">
                                                <input class="input wcpoa-php-date-picker" autocomplete="off" name="wcpoa_expired_date[]" placeholder="<?php 
            echo esc_attr( 'yy/mm/dd' );
            ?>" value="<?php 
            if ( $wcpoa_product_date_enable === "yes" ) {
                echo esc_attr( $wcpoa_expired_dates );
            }
            ?>"
                                                    type="text">
                                                <span class="wcpoa-description-tooltip-icon"></span>
                                                <p class="wcpoa-description">
                                                    <?php 
            esc_html_e( 'If an order is placed after the selected date, the attachments will be no longer visible for download. ( Date format: yy/mm/dd )', 'woocommerce-product-attachment' );
            ?>
                                                </p>
                                            </div>
                                        </div>
                                    </div>
                                    <?php 
            echo wp_kses( $after_fields, $this->allowed_html_tags() );
            ?>
                                </tr>
                                <?php 
        }
    }
}
foreach ( $field['value'] as $i => $row ) {
    $row_att = implode( " ", $row );
    $row_class = 'wcpoa-row trr hidden';
    if ( $i === 'wcpoacloneindex' ) {
        $row_class .= ' wcpoa-clone';
    }
    ?>
                            <tr class="<?php 
    echo esc_attr( $row_class );
    ?>" rowatt="<?php 
    echo esc_attr( $row_att );
    ?>"
                                data-id="<?php 
    echo esc_attr( $i );
    ?>">

                                <td class="wcpoa-fields -left">
                                    <div class="wcpoa-field -collapsed-target group-title" data-name="_name" data-type="text"
                                        data-key="">
                                        <div class="wcpoa-label order">
                                            <span class="attchment_order"><?php 
    echo intval( $i ) + 1;
    $i++;
    ?></span>
                                        </div>
                                        <div class="wcpoa-label attachment_name">
                                            <label
                                                for="attchment_name"><?php 
    esc_html_e( 'No Attachment Name', 'woocommerce-product-attachment' );
    ?></label>
                                            <ul class="attachment_action">
                                                <li><a class="edit_bulk_attach"
                                                        href="#"><?php 
    esc_html_e( 'Edit', 'woocommerce-product-attachment' );
    ?></a></li>
                                                <li><a class="-minus small wcpoa-js-tooltip" href="#" data-event="remove-row"
                                                        title="<?php 
    esc_attr_e( 'Remove', 'woocommerce-product-attachment' );
    ?>"><?php 
    esc_html_e( 'Delete', 'woocommerce-product-attachment' );
    ?></a>
                                                </li>
                                            </ul>
                                        </div>
                                        <div class="wcpoa-label not-mobile">
                                            <label for="attchment_type"><?php 
    esc_html_e( '-', 'woocommerce-product-attachment' );
    ?></label>
                                        </div>
                                        <div class="wcpoa-label not-mobile">
                                            <label
                                                for="attchment_visibility"><?php 
    esc_html_e( '-', 'woocommerce-product-attachment' );
    ?></label>
                                        </div>
                                        <div class="wcpoa-label not-mobile">
                                            <label
                                                for="attchment_visibility"><?php 
    esc_html_e( '-', 'woocommerce-product-attachment' );
    ?></label>
                                        </div>
                                    </div>
                                    <div class="wcpoa-field wcpoa-field-id wcpoa-field-58f4972436131" data-name="id"
                                        data-type="text" data-key="field_58f4972436131">
                                        <div class="wcpoa-label">
                                            <label for=""><?php 
    esc_html_e( 'Id', 'woocommerce-product-attachment' );
    ?> </label>
                                        </div>
                                        <div class="wcpoa-input">
                                            <div class="wcpoa-input-wrap">
                                                <input readonly="" class="wcpoa_attachments_id" name="wcpoa_attachments_id[]"
                                                    value="" placeholder="" type="text">
                                                <span class="wcpoa-description-tooltip-icon"></span>
                                                <p class="wcpoa-description">
                                                    <?php 
    esc_html_e( 'Attachments Id used to identify each product attachment.This value is automatically generated.', 'woocommerce-product-attachment' );
    ?>
                                                </p>
                                            </div>
                                        </div>
                                    </div>

                                    <div class="wcpoa-field">
                                        <div class="wcpoa-label">
                                            <label for="attchment_name"><?php 
    esc_html_e( 'Name', 'woocommerce-product-attachment' );
    ?>
                                                <span class="wcpoa-required"> *</span></label>
                                        </div>
                                        <div class="wcpoa-input">
                                            <input class="wcpoa-attachment-name" type="text" name="wcpoa_attachment_name[]" placeholder="<?php 
    esc_attr_e( 'Attachment', 'woocommerce-product-attachment' );
    ?>" value="">
                                            <span class="wcpoa-description-tooltip-icon"></span>
                                            <p class="wcpoa-description">
                                                <?php 
    esc_html_e( 'Enter a name for the attachment. It will be displayed on the front end next to the download/view button.', 'woocommerce-product-attachment' );
    ?>
                                            </p>
                                        </div>
                                    </div>
                                    <div class="wcpoa-field wcpoa-field-textarea " data-name="description" data-type="textarea"
                                        data-key="" data-required="1">
                                        <div class="wcpoa-label">
                                            <label for="attchment_desc"><?php 
    esc_html_e( 'Description', 'woocommerce-product-attachment' );
    ?></label>
                                        </div>
                                        <div class="wcpoa-input">
                                            <textarea class="" name="wcpoa_attachment_description[]" placeholder="<?php 
    esc_attr_e( 'Enter a description', 'woocommerce-product-attachment' );
    ?>" rows="8"></textarea>
                                            <span class="wcpoa-description-tooltip-icon"></span>
                                            <p class="wcpoa-description">
                                                <?php 
    esc_html_e( 'You can type a short description of the attachment file. So customers will get details about the attachment file.', 'woocommerce-product-attachment' );
    ?>
                                            </p>
                                        </div>
                                    </div>
                                    <div class="wcpoa-field">
                                        <div class="wcpoa-label">
                                            <label for="wcpoa_attach_type"><?php 
    esc_html_e( 'Attachment Type', 'woocommerce-product-attachment' );
    ?></label>
                                        </div>
                                        <div class="wcpoa-input wcpoa_attach_type">
                                            <?php 
    ?>
                                                    <select name="wcpoa_attach_type[]" class="wcpoa_attach_type_list" data-type=""
                                                        data-key="">
                                                        <option name="file_upload" value="file_upload">
                                                            <?php 
    esc_html_e( 'File Upload', 'woocommerce-product-attachment' );
    ?></option>
                                                        <option value="external_ulr" class="wcpoa_pro_class">
                                                            <?php 
    esc_html_e( 'External URL 🔒', 'woocommerce-product-attachment' );
    ?>
                                                        </option>
                                                    </select>
                                            <?php 
    ?>
                                            <span class="wcpoa-description-tooltip-icon"></span>
                                            <p class="wcpoa-description">
                                                <?php 
    esc_html_e( 'Select the attachment type. Like Upload file / External URL.', 'woocommerce-product-attachment' );
    ?>
                                            </p>
                                        </div>
                                    </div>
                                    <div class="wcpoa-field wcpoa-field-file file_upload" data-name="file" data-type="file"
                                        data-key="field_58f4974e36133" data-required="1">
                                        <div class="wcpoa-label">
                                            <div class="wcpoa-label">
                                                <label
                                                    for="fee_settings_start_date"><?php 
    esc_html_e( 'Upload Attachment File', 'woocommerce-product-attachment' );
    ?>
                                                    <span class="wcpoa-required">*</span>
                                                </label>
                                            </div>
                                        </div>
                                        <div class="wcpoa-input">
                                            <div class="wcpoa-file-uploader wcpoa-cf" data-uploader="uploader">
                                                <div class="wcpoa-error-message">
                                                    <p><?php 
    esc_html_e( 'File value is required.', 'woocommerce-product-attachment' );
    ?></p>
                                                    <input name="wcpoa_attachment_file[]" value="" data-name="id" type="hidden">
                                                </div>
                                                <div class="show-if-value file-wrap wcpoa-soh">
                                                    <div class="file-icon">
                                                        <img data-name="icon"
                                                            src="<?php 
    echo esc_url( $plugin_url ) . 'admin/images/default.png';
    ?>"
                                                            alt="" title="">
                                                    </div>
                                                    <div class="file-info">
                                                        <p>
                                                            <strong data-name="title"></strong>
                                                        </p>
                                                        <p>
                                                            <strong><?php 
    esc_html_e( 'File name:', 'woocommerce-product-attachment' );
    ?></strong>
                                                            <a data-name="filename" href="" target="_blank"></a>
                                                        </p>
                                                        <p>
                                                            <strong><?php 
    esc_html_e( 'File size:', 'woocommerce-product-attachment' );
    ?></strong>
                                                            <span data-name="filesize"></span>
                                                        </p>

                                                        <ul class="wcpoa-hl wcpoa-soh-target">
                                                            <li><a class="wcpoa-icon -pencil dark" data-name="edit"
                                                                    href="#"></a></li>
                                                            <li><a class="wcpoa-icon -cancel dark" data-name="remove"
                                                                    href="#"></a></li>
                                                        </ul>
                                                    </div>
                                                </div>
                                                <div class="hide-if-value">
                                                    <p style="margin:0;">
                                                        <?php 
    esc_html_e( 'No file selected ', 'woocommerce-product-attachment' );
    ?>
                                                        <?php 
    echo wp_kses( $this->wcpoa_image_uploader_field( 'test' ), $this->allowed_html_tags() );
    ?>
                                                    </p>
                                                </div>
                                                <p class="description">
                                                    <?php 
    esc_html_e( 'Select upload attachment File.', 'woocommerce-product-attachment' );
    ?>
                                                </p>
                                            </div>
                                        </div>
                                    </div>
                                    <?php 
    ?>
                                    <div class="wcpoa-field">
                                        <div class="wcpoa-label">
                                            <label
                                                for="product_page_enable"><?php 
    esc_html_e( 'Open in new window', 'woocommerce-product-attachment' );
    ?></label>
                                        </div>
                                        <div class="wcpoa-input">
                                            <select id="wcpoa_product_open_window_flag" name="wcpoa_product_open_window_flag[]">
                                                <option name="no" value="no" selected>
                                                    <?php 
    esc_html_e( 'No', 'woocommerce-product-attachment' );
    ?></option>
                                                <option name="yes" value="yes"><?php 
    esc_html_e( 'Yes', 'woocommerce-product-attachment' );
    ?>
                                                </option>
                                            </select>
                                            <span class="wcpoa-description-tooltip-icon"></span>
                                            <p class="wcpoa-description">
                                                <?php 
    esc_html_e( 'Select Link Behavior: Specify whether you want the attachment link to open in a new window or the same window.', 'woocommerce-product-attachment' );
    ?>
                                            </p>
                                        </div>
                                    </div>

                                    <div class="wcpoa-field">
                                        <div class="wcpoa-label">
                                            <label
                                                for="product_page_enable"><?php 
    esc_html_e( 'Show on Product page', 'woocommerce-product-attachment' );
    ?></label>
                                        </div>
                                        <div class="wcpoa-input">
                                            <select id="wcpoa_product_page_enable" name="wcpoa_product_page_enable[]">
                                                <option name="yes" value="yes" selected>
                                                    <?php 
    esc_html_e( 'Yes', 'woocommerce-product-attachment' );
    ?></option>
                                                <option name="no" value="no"><?php 
    esc_html_e( 'No', 'woocommerce-product-attachment' );
    ?>
                                                </option>
                                            </select>
                                            <span class="wcpoa-description-tooltip-icon"></span>
                                            <p class="wcpoa-description">
                                                <?php 
    esc_html_e( 'Select whether you want to display the attachment on the product page or not.', 'woocommerce-product-attachment' );
    ?>
                                            </p>
                                        </div>
                                    </div>

                                    <div class="wcpoa-field">
                                        <div class="wcpoa-label">
                                            <label
                                                for="product_page_enable"><?php 
    esc_html_e( 'Show only for logged in users', 'woocommerce-product-attachment' );
    ?></label>
                                        </div>
                                        <div class="wcpoa-input">
                                            <select id="wcpoa_product_logged_in_flag" name="wcpoa_product_logged_in_flag[]">
                                                <option name="no" value="no" selected>
                                                    <?php 
    esc_html_e( 'No', 'woocommerce-product-attachment' );
    ?></option>
                                                <option name="yes" value="yes"><?php 
    esc_html_e( 'Yes', 'woocommerce-product-attachment' );
    ?>
                                                </option>
                                            </select>
                                            <span class="wcpoa-description-tooltip-icon"></span>
                                            <p class="wcpoa-description">
                                                <?php 
    esc_html_e( 'Select whether you want to display the attachment only for logged-in users or not.', 'woocommerce-product-attachment' );
    ?>
                                            </p>
                                        </div>
                                    </div>
                                    <?php 
    ?>
                                    <div class="wcpoa-field">
                                        <div class="wcpoa-label">
                                            <label
                                                for="attchment_order_status"><?php 
    esc_html_e( 'Order status', 'woocommerce-product-attachment' );
    ?></label>
                                        </div>
                                        <div class="wcpoa-input">
                                            <p class="description">
                                                <?php 
    esc_html_e( 'Select order status, where you want to showcase attachment. Leave unselected then apply to all.', 'woocommerce-product-attachment' );
    ?>
                                            </p>
                                            <ul class="wcpoa-order-checkbox-list">
                                                <li>
                                                    <input name="wcpoa_order_status[]" id="wcpoa_wc_order_completed" value="wcpoa-wc-completed" type="checkbox">
                                                    <label for="wcpoa_wc_order_completed"><?php 
    esc_html_e( 'Completed', 'woocommerce-product-attachment' );
    ?></label>
                                                </li>
                                                <li>
                                                    <input name="wcpoa_order_status[]" id="wcpoa_wc_order_completed" value="wcpoa-wc-on-hold" type="checkbox">
                                                    <label for="wcpoa_wc_order_on_hold"><?php 
    esc_html_e( 'On Hold', 'woocommerce-product-attachment' );
    ?></label>
                                                </li>
                                                <li>
                                                    <input name="wcpoa_order_status[]" id="wcpoa_wc_order_pending" value="wcpoa-wc-pending" type="checkbox">
                                                    <label for="wcpoa_wc_order_pending"><?php 
    esc_html_e( 'Pending payment', 'woocommerce-product-attachment' );
    ?></label>
                                                </li>
                                                <li>
                                                    <input name="wcpoa_order_status[]" id="wcpoa_wc_order_processing" value="wcpoa-wc-processing" type="checkbox">
                                                    <label for="wcpoa_wc_order_processing"><?php 
    esc_html_e( 'Processing', 'woocommerce-product-attachment' );
    ?></label>
                                                </li>
                                                <li>
                                                    <input name="wcpoa_order_status[]" id="wcpoa_wc_order_cancelled" value="wcpoa-wc-cancelled" type="checkbox">
                                                    <label for="wcpoa_wc_order_cancelled"><?php 
    esc_html_e( 'Cancelled', 'woocommerce-product-attachment' );
    ?></label>
                                                </li>
                                                <li>
                                                    <input name="wcpoa_order_status[]" id="wcpoa_wc_order_failed" value="wcpoa-wc-failed" type="checkbox">
                                                    <label for="wcpoa_wc_order_failed"><?php 
    esc_html_e( 'Failed', 'woocommerce-product-attachment' );
    ?></label>
                                                </li>
                                                <li>
                                                    <input name="wcpoa_order_status[]" id="wcpoa_wc_order_refunded" value="wcpoa-wc-refunded" type="checkbox">
                                                    <label for="wcpoa_wc_order_refunded"><?php 
    esc_html_e( 'Refunded', 'woocommerce-product-attachment' );
    ?></label>
                                                </li>
                                            </ul>
                                        </div>
                                    </div>
                                    <div class="wcpoa-field wcpoa-field-select">
                                        <div class="wcpoa-label">
                                            <label for="wcpoa_expired_date_enable"><?php 
    esc_html_e( 'Set Expire date/time', 'woocommerce-product-attachment' );
    ?></label>
                                        </div>
                                        <div class="wcpoa-input enable_expire_date">
                                            <select name="wcpoa_expired_date_enable[]" class="enable_date_time" data-type=""
                                                data-key="">
                                                <option name="no" value="no" class="" selected="">
                                                    <?php 
    esc_html_e( 'No', 'woocommerce-product-attachment' );
    ?></option>
                                                <option name="yes" value="yes">
                                                    <?php 
    esc_html_e( 'Specific Date', 'woocommerce-product-attachment' );
    ?></option>
                                                <?php 
    ?>
                                                        <option value="time_amount" class="wcpoa_pro_class">
                                                            <?php 
    esc_html_e( 'Selected time period after purchase 🔒', 'woocommerce-product-attachment' );
    ?>
                                                        </option>
                                                <?php 
    ?>
                                            </select>
                                            <span class="wcpoa-description-tooltip-icon"></span>
                                            <p class="wcpoa-description">
                                                <?php 
    esc_html_e( 'Set a specific date and specific time to access the attachment.', 'woocommerce-product-attachment' );
    ?>
                                            </p>
                                        </div>
                                    </div>
                                    <div class="wcpoa-field enable_date" data-key="" data-required="1" style='display: none'>
                                        <div class="wcpoa-label">
                                            <label
                                                for="wcpoa_expired_date"><?php 
    esc_html_e( 'Specific Date', 'woocommerce-product-attachment' );
    ?></label>
                                        </div>
                                        <div class="wcpoa-input">
                                            <div class="wcpoa-input-wrap" data-date_format="yy/mm/dd">
                                                <input class="wcpoa-php-date-picker" value="" name="wcpoa_expired_date[]" placeholder="<?php 
    echo esc_attr( 'yy/mm/dd' );
    ?>" type="text" autocomplete="off">
                                                <span class="wcpoa-description-tooltip-icon"></span>
                                                <p class="wcpoa-description">
                                                    <?php 
    esc_html_e( 'If an order is placed after the selected date, the attachments will be no longer visible for download. ( Date format: yy/mm/dd )', 'woocommerce-product-attachment' );
    ?>
                                                </p>
                                            </div>
                                        </div>
                                    </div>
                                    <?php 
    ?>
                                </td>
                            </tr>
                    <?php 
}
?>
                </tbody>
            </table>
            <?php 
if ( $show_add ) {
    ?>
            <ul class="wcpoa-actions wcpoa-hl">
                <li>
                    <a class="wcpoa-button button button-primary"
                        data-event="add-row"><?php 
    esc_html_e( 'Add New Attachment', 'woocommerce-product-attachment' );
    ?></a>
                </li>
            </ul>
            <?php 
}
?>
            <div class="product_attachment_help">
                <a href="<?php 
echo esc_url( 'https://docs.thedotstore.com/article/378-bulk-attachment-for-woocommerce' );
?>"
                    target="_blank"><?php 
esc_html_e( 'View Documentation', 'woocommerce-product-attachment' );
?></a>
            </div>
        </div>
    </div>
</div>
<!--File validation-->
<!--End file validation-->
