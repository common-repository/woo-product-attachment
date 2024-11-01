<?php

// If this file is called directly, abort.
if ( !defined( 'WPINC' ) ) {
    die;
}
global $sitepress;
$wcpoa_product_tab_name = filter_input( INPUT_POST, 'wcpoa_product_tab_name', FILTER_SANITIZE_SPECIAL_CHARS );
$wcpoa_order_tab_name = filter_input( INPUT_POST, 'wcpoa_order_tab_name', FILTER_SANITIZE_SPECIAL_CHARS );
$wcpoa_admin_order_tab_name = filter_input( INPUT_POST, 'wcpoa_admin_order_tab_name', FILTER_SANITIZE_SPECIAL_CHARS );
$wcpoa_expired_date_label = filter_input( INPUT_POST, 'wcpoa_expired_date_label', FILTER_SANITIZE_SPECIAL_CHARS );
$wcpoa_default_tab_selected_flag = filter_input( INPUT_POST, 'wcpoa_default_tab_selected_flag', FILTER_SANITIZE_SPECIAL_CHARS );
$wcpoa_show_attachment_size_flag = filter_input( INPUT_POST, 'wcpoa_show_attachment_size_flag', FILTER_SANITIZE_SPECIAL_CHARS );
$wcpoa_att_download_restrict = filter_input(
    INPUT_POST,
    'wcpoa_att_download_restrict',
    FILTER_SANITIZE_SPECIAL_CHARS,
    FILTER_REQUIRE_ARRAY
);
$wcpoa_att_btn_in_order_list = filter_input( INPUT_POST, 'wcpoa_att_btn_in_order_list', FILTER_SANITIZE_SPECIAL_CHARS );
$wcpoa_attachments_show_in_email = filter_input( INPUT_POST, 'wcpoa_attachments_show_in_email', FILTER_SANITIZE_SPECIAL_CHARS );
$wcpoa_att_in_my_acc = filter_input( INPUT_POST, 'wcpoa_att_in_my_acc', FILTER_SANITIZE_SPECIAL_CHARS );
$wcpoa_att_in_thankyou = filter_input( INPUT_POST, 'wcpoa_att_in_thankyou', FILTER_SANITIZE_SPECIAL_CHARS );
$attachment_custom_style = filter_input( INPUT_POST, 'attachment_custom_style', FILTER_SANITIZE_SPECIAL_CHARS );
$wcpoa_product_download_type = filter_input( INPUT_POST, 'wcpoa_product_download_type', FILTER_SANITIZE_SPECIAL_CHARS );
$wcpoa_is_viewable = filter_input( INPUT_POST, 'wcpoa_is_viewable', FILTER_SANITIZE_SPECIAL_CHARS );
$wcpoa_product_tab = ( isset( $wcpoa_product_tab_name ) && !empty( $wcpoa_product_tab_name ) ? $wcpoa_product_tab_name : 'Attachment' );
$wcpoa_order_tab = ( isset( $wcpoa_order_tab_name ) && !empty( $wcpoa_order_tab_name ) ? $wcpoa_order_tab_name : 'Attachment' );
$wcpoa_admin_order_tab = ( isset( $wcpoa_admin_order_tab_name ) && !empty( $wcpoa_admin_order_tab_name ) ? $wcpoa_admin_order_tab_name : 'Attachment' );
$wcpoa_expired_date_label = ( isset( $wcpoa_expired_date_label ) && !empty( $wcpoa_expired_date_label ) ? $wcpoa_expired_date_label : '' );
$wcpoa_default_tab_selected_flag = ( isset( $wcpoa_default_tab_selected_flag ) && !empty( $wcpoa_default_tab_selected_flag ) ? $wcpoa_default_tab_selected_flag : '' );
$wcpoa_show_attachment_size_flag = ( isset( $wcpoa_show_attachment_size_flag ) && !empty( $wcpoa_show_attachment_size_flag ) ? $wcpoa_show_attachment_size_flag : '' );
$wcpoa_att_download_restrict_val = ( isset( $wcpoa_att_download_restrict ) && !empty( $wcpoa_att_download_restrict ) ? $wcpoa_att_download_restrict : '' );
$wcpoa_att_btn_in_order_list_val = ( isset( $wcpoa_att_btn_in_order_list ) && !empty( $wcpoa_att_btn_in_order_list ) ? $wcpoa_att_btn_in_order_list : '' );
$wcpoa_attachments_show_in_email = ( isset( $wcpoa_attachments_show_in_email ) && !empty( $wcpoa_attachments_show_in_email ) ? $wcpoa_attachments_show_in_email : '' );
$wcpoa_att_in_my_acc_val = ( isset( $wcpoa_att_in_my_acc ) && !empty( $wcpoa_att_in_my_acc ) ? $wcpoa_att_in_my_acc : '' );
$wcpoa_att_in_thankyou_val = ( isset( $wcpoa_att_in_thankyou ) && !empty( $wcpoa_att_in_thankyou ) ? $wcpoa_att_in_thankyou : '' );
$attachment_custom_style_val = ( isset( $attachment_custom_style ) && !empty( $attachment_custom_style ) ? $attachment_custom_style : '' );
$wcpoa_product_download_type_val = ( isset( $wcpoa_product_download_type ) && !empty( $wcpoa_product_download_type ) ? $wcpoa_product_download_type : 'download_by_btn' );
$wcpoa_is_viewable_val = ( isset( $wcpoa_is_viewable ) && !empty( $wcpoa_is_viewable ) ? $wcpoa_is_viewable : '' );
//save on database two tab value
$menu_page = filter_input( INPUT_GET, 'page', FILTER_SANITIZE_SPECIAL_CHARS );
$attachment_submit = filter_input( INPUT_POST, 'submit', FILTER_SANITIZE_SPECIAL_CHARS );
$wcpoa_save_settings_nonce = filter_input( INPUT_POST, 'wcpoa_save_global_settings_nonce', FILTER_SANITIZE_FULL_SPECIAL_CHARS );
if ( isset( $attachment_submit ) && isset( $menu_page ) && $menu_page === 'woocommerce_product_attachment' ) {
    if ( empty( $wcpoa_save_settings_nonce ) || !wp_verify_nonce( sanitize_text_field( $wcpoa_save_settings_nonce ), 'wcpoa_save_global_settings' ) ) {
        ?>
        <div id="message" class="wcpoa-notice notice notice-error is-dismissible">
            <p><?php 
        esc_html_e( 'There is an error with the security check.', 'woocommerce-product-attachment' );
        ?></p>
            <button type="button" class="notice-dismiss">
                <span class="screen-reader-text"><?php 
        esc_html_e( 'Dismiss this notice.', 'woocommerce-product-attachment' );
        ?></span>
            </button>
        </div>
        <?php 
        wp_die();
    } else {
        update_option( 'wcpoa_product_tab_name', $wcpoa_product_tab );
        update_option( 'wcpoa_order_tab_name', $wcpoa_order_tab );
        if ( !empty( $sitepress ) ) {
            do_action(
                'wpml_register_single_string',
                WCPOA_PLUGIN_TEXT_DOMAIN,
                sanitize_text_field( $wcpoa_product_tab ),
                sanitize_text_field( $wcpoa_product_tab )
            );
            do_action(
                'wpml_register_single_string',
                WCPOA_PLUGIN_TEXT_DOMAIN,
                sanitize_text_field( $wcpoa_order_tab ),
                sanitize_text_field( $wcpoa_order_tab )
            );
            do_action(
                'wpml_register_single_string',
                WCPOA_PLUGIN_TEXT_DOMAIN,
                sanitize_text_field( $wcpoa_admin_order_tab ),
                sanitize_text_field( $wcpoa_admin_order_tab )
            );
        }
        update_option( 'wcpoa_expired_date_label', $wcpoa_expired_date_label );
        update_option( 'wcpoa_default_tab_selected_flag', $wcpoa_default_tab_selected_flag );
        update_option( 'wcpoa_show_attachment_size_flag', $wcpoa_show_attachment_size_flag );
        update_option( 'wcpoa_attachments_show_in_email', $wcpoa_attachments_show_in_email );
        update_option( 'wcpoa_att_download_restrict', $wcpoa_att_download_restrict_val );
        update_option( 'wcpoa_att_btn_in_order_list', $wcpoa_att_btn_in_order_list_val );
        update_option( 'wcpoa_att_in_my_acc', $wcpoa_att_in_my_acc_val );
        update_option( 'wcpoa_att_in_thankyou', $wcpoa_att_in_thankyou_val );
        update_option( 'attachment_custom_style', $attachment_custom_style_val );
        update_option( 'wcpoa_product_download_type', $wcpoa_product_download_type_val );
        update_option( 'wcpoa_is_viewable', $wcpoa_is_viewable_val );
        ?>
        <div id="message" class="wcpoa-notice notice notice-success is-dismissible">
            <p><?php 
        esc_html_e( 'Attachment setting updated.', 'woocommerce-product-attachment' );
        ?></p>
            <button type="button" class="notice-dismiss">
                <span class="screen-reader-text"><?php 
        esc_html_e( 'Dismiss this notice.', 'woocommerce-product-attachment' );
        ?></span>
            </button>
        </div>
        <?php 
    }
}
//store value in variable
$wcpoa_product_tname = get_option( 'wcpoa_product_tab_name' );
$wcpoa_order_tname = get_option( 'wcpoa_order_tab_name' );
$wcpoa_admin_order_tname = get_option( 'wcpoa_admin_order_tab_name' );
$wcpoa_product_download_type = get_option( 'wcpoa_product_download_type' );
$wcpoa_is_viewable = get_option( 'wcpoa_is_viewable' );
$wcpoa_expired_date_tlabel = get_option( 'wcpoa_expired_date_label' );
$wcpoa_default_tab_selected_flag = get_option( 'wcpoa_default_tab_selected_flag' );
$wcpoa_show_attachment_size_flag = get_option( 'wcpoa_show_attachment_size_flag' );
$wcpoa_att_download_restrict = get_option( 'wcpoa_att_download_restrict' );
$wcpoa_att_btn_in_order_list = get_option( 'wcpoa_att_btn_in_order_list' );
$wcpoa_attachments_show_in_email = get_option( 'wcpoa_attachments_show_in_email' );
$wcpoa_att_in_my_acc = get_option( 'wcpoa_att_in_my_acc' );
$wcpoa_att_in_thankyou = get_option( 'wcpoa_att_in_thankyou' );
$attachment_custom_style = get_option( 'attachment_custom_style' );
?>
<div class="wcpoa-table-main">
    <form method="post" action="#" enctype="multipart/form-data">
        <?php 
wp_nonce_field( 'wcpoa_save_global_settings', 'wcpoa_save_global_settings_nonce' );
?>
        <div class="wcpoa-general-content">
            <div class="wcpoa-general-setting wcpoa-general-front-end-product">
                <div class="wcpoa-general-content-heading">
                    <h3><?php 
esc_html_e( 'FRONTEND: Product Page', 'woocommerce-product-attachment' );
?></h3>
                </div>
                <div class="wcpoa-general-input text-title">
                    <label
                        class="wcpoa-general-input-title"><?php 
esc_html_e( 'Frontend Product Page Tab Title', 'woocommerce-product-attachment' );
?></label>
                    <div class="wcpoa-general-input-value">
                        <input type="text" name="wcpoa_product_tab_name" placeholder="<?php 
echo esc_attr( 'Attachment' );
?>" value="<?php 
echo esc_attr( $wcpoa_product_tname );
?>">
                        <span class="wcpoa-description-tooltip-icon"></span>
                        <p class="wcpoa-description">
                            <?php 
/* translators: %s: Link to documentation */
echo sprintf( esc_html__( 'Customize Product Page Tab Title: Displayed on the front end. All attachments will showcase under this tab. Default tab name: Attachment. %s.', 'woocommerce-product-attachment' ), '<a href="' . esc_url( 'https://docs.thedotstore.com/article/369-how-to-add-frontend-product-page-tab-title-and-why-is-it-for' ) . '" target="_blank">' . esc_html__( 'Learn more', 'woocommerce-product-attachment' ) . '</a>' );
?>
                        </p>
                    </div>
                </div>
                <div class="wcpoa-general-input">
                    <label
                        class="wcpoa-general-input-title"><?php 
esc_html_e( 'User Role Based Display Attachment', 'woocommerce-product-attachment' );
?></label>
                    <div class="wcpoa-general-input-value">
                        <div>
                            <?php 
esc_html_e( 'Select user role, which you want to display an attachment. Leave unselected then apply to all.', 'woocommerce-product-attachment' );
?>
                            <span class="wcpoa-description-tooltip-icon"></span>
                            <p class="wcpoa-description">
                                <?php 
/* translators: %s: Link to documentation */
echo sprintf( esc_html__( 'Select to display product attachments to all or only to specific selected user roles. %s.', 'woocommerce-product-attachment' ), '<a href="' . esc_url( 'https://docs.thedotstore.com/article/369-how-to-add-frontend-product-page-tab-title-and-why-is-it-for' ) . '" target="_blank">' . esc_html__( 'Learn more', 'woocommerce-product-attachment' ) . '</a>' );
?>
                            </p>
                        </div>
                        <div class="wcpoa-name-chxbox wcpoa-user-role-base-attach">
                            <ul class="wcpoa-checkbox-list">
                                <li>
                                    <?php 
if ( empty( $wcpoa_att_download_restrict ) ) {
    $wcpoa_att_download_restrict = array();
}
?>
                                    <input name="wcpoa_att_download_restrict[]" id="wcpoa_att_download_guest" value="wcpoa_att_download_guest" type="checkbox" <?php 
if ( !is_null( $wcpoa_att_download_restrict ) && in_array( 'wcpoa_att_download_guest', $wcpoa_att_download_restrict, true ) ) {
    echo 'checked="checked"';
}
?>>
                                    <label for="wcpoa_att_download_guest"><?php 
esc_html_e( 'Guest / Not logged In', 'woocommerce-product-attachment' );
?></label>
                                </li>
                                <?php 
global $wp_roles;
foreach ( $wp_roles->roles as $key => $value ) {
    if ( empty( $wcpoa_att_download_restrict ) ) {
        $wcpoa_att_download_restrict = array();
    }
    $wcpoa_att_download_restric_key = "wcpoa_att_download_" . $key;
    ?>
                                    <li>
                                        <input name="wcpoa_att_download_restrict[]" id="<?php 
    echo esc_attr( $wcpoa_att_download_restric_key );
    ?>" value="wcpoa_att_download_<?php 
    echo esc_attr( $key );
    ?>" type="checkbox" <?php 
    if ( !is_null( $wcpoa_att_download_restrict ) && in_array( $wcpoa_att_download_restric_key, $wcpoa_att_download_restrict, true ) ) {
        echo 'checked="checked"';
    }
    ?>>
                                        <label for="<?php 
    echo esc_attr( $wcpoa_att_download_restric_key );
    ?>"><?php 
    esc_html_e( $value['name'], 'woocommerce-product-attachment' );
    ?></label>
                                    </li>
                                    <?php 
}
?>
                            </ul>
                        </div>
                    </div>
                </div>
            </div>
            <div class="wcpoa-general-setting wcpoa-general-order-attachment">
                <div class="wcpoa-general-content-heading">
                    <h3><?php 
esc_html_e( 'Order Attachment Setting', 'woocommerce-product-attachment' );
?></h3>
                </div>
                <div class="wcpoa-general-input text-title">
                    <label
                        class="wcpoa-general-input-title"><?php 
esc_html_e( 'Order Details Page Tab Title', 'woocommerce-product-attachment' );
?></label>
                    <div class="wcpoa-general-input-value">
                        <input type="text" name="wcpoa_order_tab_name" placeholder="<?php 
echo esc_attr( 'Attachment' );
?>" value="<?php 
echo esc_attr( $wcpoa_order_tname );
?>">
                        <span class="wcpoa-description-tooltip-icon"></span>
                        <p class="wcpoa-description">
                            <?php 
/* translators: %s: Link to documentation */
echo sprintf( esc_html__( 'Customize Order Page Tab Title: Displayed on the front end. All attachments will showcase under this tab. Default tab name: Attachment. %s.', 'woocommerce-product-attachment' ), '<a href="' . esc_url( 'https://docs.thedotstore.com/article/397-how-to-update-order-detail-page-attachment-title' ) . '" target="_blank">' . esc_html__( 'Learn more', 'woocommerce-product-attachment' ) . '</a>' );
?>
                        </p>
                    </div>
                </div>
                <div class="wcpoa-general-input">
                    <label
                        class="wcpoa-general-input-title"><?php 
esc_html_e( 'Show Attachments Button on Orders Listing Page', 'woocommerce-product-attachment' );
?></label>
                    <div class="wcpoa-general-input-value">
                        <div class="wcpoa-name-radio-box">
                            <input type="radio" name="wcpoa_att_btn_in_order_list" class="wcpoa_att_btn_in_order_list" id="wcpoa_att_btn_in_order_list_enable" value="wcpoa_att_btn_in_order_list_enable" <?php 
echo ( $wcpoa_att_btn_in_order_list === "wcpoa_att_btn_in_order_list_enable" ? 'checked' : '' );
?>>
                            <label for="wcpoa_att_btn_in_order_list_enable"><?php 
esc_html_e( 'Enable', 'woocommerce-product-attachment' );
?></label>
                            <input type="radio" name="wcpoa_att_btn_in_order_list" class="wcpoa_att_btn_in_order_list" id="wcpoa_att_btn_in_order_list_disable" value="wcpoa_att_btn_in_order_list_disable" <?php 
echo ( $wcpoa_att_btn_in_order_list === "wcpoa_att_btn_in_order_list_disable" ? 'checked' : '' );
?>>
                            <label for="wcpoa_att_btn_in_order_list_disable"><?php 
esc_html_e( 'Disable', 'woocommerce-product-attachment' );
?></label>
                            <span class="wcpoa-description-tooltip-icon"></span>
                            <p class="wcpoa-description">
                                <?php 
/* translators: %s: Link to documentation */
echo sprintf( esc_html__( 'Choose to enable or disable the attachments listing on the order details page. %s.', 'woocommerce-product-attachment' ), '<a href="' . esc_url( 'https://docs.thedotstore.com/article/370-order-attachment-settings' ) . '" target="_blank">' . esc_html__( 'Learn more', 'woocommerce-product-attachment' ) . '</a>' );
?>
                            </p>
                        </div>
                    </div>
                </div>
                <?php 
?>
                <div class="wcpoa-general-input">
                    <label class="wcpoa-general-input-title wcpoa-pro-feature"><?php 
esc_html_e( 'Show Attachments on Order Email', 'woocommerce-product-attachment' );
echo wp_kses( '<span class="wcpoa-pro-label"></span>', $this->allowed_html_tags() );
?></label>
                    <div class="wcpoa-general-input-value">
                        <div class="wcpoa-name-radio-box">
                            <input type="radio" class="wcpoa_attachments_show_in_email" id="wcpoa_att_show_in_email_yes" value="yes" disabled>
                            <label for="wcpoa_att_show_in_email_yes" class="wcpoa-pro-feature"><?php 
esc_html_e( 'Yes', 'woocommerce-product-attachment' );
?></label>
                            <input type="radio" class="wcpoa_attachments_show_in_email" id="wcpoa_att_show_in_email_no" value="no" disabled>
                            <label for="wcpoa_att_show_in_email_no" class="wcpoa-pro-feature"><?php 
esc_html_e( 'No', 'woocommerce-product-attachment' );
?></label>
                            <span class="wcpoa-description-tooltip-icon"></span>
                            <p class="wcpoa-description">
                                <?php 
/* translators: %s: Link to documentation */
echo sprintf( esc_html__( 'Select whether to display attachments in order emails or not. %s.', 'woocommerce-product-attachment' ), '<a href="' . esc_url( 'https://docs.thedotstore.com/article/370-order-attachment-settings' ) . '" target="_blank">' . esc_html__( 'Learn more', 'woocommerce-product-attachment' ) . '</a>' );
?>
                            </p>
                        </div>
                    </div>
                </div>
                <div class="wcpoa-general-input">
                    <label class="wcpoa-general-input-title wcpoa-pro-feature"><?php 
esc_html_e( 'Attachment List Position on Order Details Page', 'woocommerce-product-attachment' );
echo wp_kses( '<span class="wcpoa-pro-label"></span>', $this->allowed_html_tags() );
?></label>
                    <div class="wcpoa-general-input-value">
                        <div class="wcpoa-name-radio-box">
                            <input type="radio" name="wcpoa_att_btn_position" class="wcpoa_att_btn_position" id="wcpoa_att_btn_position_after" value="wcpoa_att_btn_position_after"  disabled>
                            <label for="wcpoa_att_btn_position_after" class="wcpoa-pro-feature"><?php 
esc_html_e( 'After Order', 'woocommerce-product-attachment' );
?></label>
                            <input type="radio" name="wcpoa_att_btn_position" class="wcpoa_att_btn_position" id="wcpoa_att_btn_position_before" value="wcpoa_att_btn_position_before" disabled>
                            <label for="wcpoa_att_btn_position_before" class="wcpoa-pro-feature"><?php 
esc_html_e( 'Before Order', 'woocommerce-product-attachment' );
?></label>
                            <span class="wcpoa-description-tooltip-icon"></span>
                            <p class="wcpoa-description">
                                <?php 
/* translators: %s: Link to documentation */
echo sprintf( esc_html__( 'Select a position for attachment list on order details page. %s.', 'woocommerce-product-attachment' ), '<a href="' . esc_url( 'https://docs.thedotstore.com/article/370-order-attachment-settings' ) . '" target="_blank">' . esc_html__( 'Learn more', 'woocommerce-product-attachment' ) . '</a>' );
?>
                            </p>
                        </div>
                    </div>
                </div>
                <div class="wcpoa-general-input">
                    <label
                        class="wcpoa-general-input-title wcpoa-pro-feature"><?php 
esc_html_e( 'Select to which status email the attachment as to be attached', 'woocommerce-product-attachment' );
echo wp_kses( '<span class="wcpoa-pro-label"></span>', $this->allowed_html_tags() );
?></label>
                    <div class="wcpoa-general-input-value">
                        <div>
                            <?php 
esc_html_e( 'Select order status for which the attachment(s) will be visible. Leave unselected to apply to all.', 'woocommerce-product-attachment' );
?>
                            <span class="wcpoa-description-tooltip-icon"></span>
                            <p class="wcpoa-description">
                                <?php 
/* translators: %s: Link to documentation */
echo sprintf( esc_html__( 'Display attachment on specific selected order status emails. %s.', 'woocommerce-product-attachment' ), '<a href="' . esc_url( 'https://docs.thedotstore.com/article/370-order-attachment-settings' ) . '" target="_blank">' . esc_html__( 'Learn more', 'woocommerce-product-attachment' ) . '</a>' );
?>
                            </p>
                        </div>
                        <div class="wcpoa-name-chxbox">
                            <ul class="wcpoa-checkbox-list">
                                <li>
                                    <input name="wcpoa_email_order_status[]" id="wcpoa_wc_order_completed" value="customer_completed_order" type="checkbox" disabled>
                                    <label for="wcpoa_wc_order_completed" class="wcpoa-pro-feature"><?php 
esc_html_e( 'Completed', 'woocommerce-product-attachment' );
?></label>
                                </li>
                                <li>
                                    <input name="wcpoa_email_order_status[]" id="wcpoa_wc_order_completed" value="customer_on_hold_order" type="checkbox" disabled>
                                    <label for="wcpoa_wc_order_on_hold" class="wcpoa-pro-feature"><?php 
esc_html_e( 'On Hold', 'woocommerce-product-attachment' );
?></label>
                                </li>
                                <li>
                                    <input name="wcpoa_email_order_status[]" id="wcpoa_wc_order_processing" value="customer_processing_order" type="checkbox" disabled>
                                    <label for="wcpoa_wc_order_processing" class="wcpoa-pro-feature"><?php 
esc_html_e( 'Processing', 'woocommerce-product-attachment' );
?></label>
                                </li>
                            </ul>
                        </div>
                    </div>
                </div>
                <?php 
?>
                <div class="wcpoa-general-input">
                    <label
                        class="wcpoa-general-input-title"><?php 
esc_html_e( 'Admin Order Details Page Tab Title', 'woocommerce-product-attachment' );
?></label>
                    <div class="wcpoa-general-input-value text-title">
                        <input type="text" name="wcpoa_admin_order_tab_name" placeholder="<?php 
echo esc_attr( 'Attachment' );
?>" value="<?php 
echo esc_attr( $wcpoa_admin_order_tname );
?>">
                        <span class="wcpoa-description-tooltip-icon"></span>
                        <p class="wcpoa-description">
                            <?php 
/* translators: %s: Link to documentation */
echo sprintf( esc_html__( 'Customize the Admin Order Details Page Tab Title: Displayed on the admin side. Default tab name: Attachment. %s.', 'woocommerce-product-attachment' ), '<a href="' . esc_url( 'https://docs.thedotstore.com/article/398-admin-order-page-product-attachment-title' ) . '" target="_blank">' . esc_html__( 'Learn more', 'woocommerce-product-attachment' ) . '</a>' );
?>
                        </p>
                    </div>
                </div>
                <?php 
?>
            </div>
            <div class="wcpoa-general-setting wcpoa-general-my-account">
                <div class="wcpoa-general-content-heading">
                    <h3><?php 
esc_html_e( 'My Account', 'woocommerce-product-attachment' );
?></h3>
                </div>
                <div class="wcpoa-general-input">
                    <label
                        class="wcpoa-general-input-title"><?php 
esc_html_e( 'Show Attachments in My Account Page', 'woocommerce-product-attachment' );
?></label>
                    <div class="wcpoa-general-input-value">
                        <div class="wcpoa-name-radio-box">
                            <input type="radio" name="wcpoa_att_in_my_acc" class="wcpoa_att_in_my_acc" id="wcpoa_att_in_my_acc_enable" value="wcpoa_att_in_my_acc_enable" <?php 
echo ( $wcpoa_att_in_my_acc === "wcpoa_att_in_my_acc_enable" ? 'checked' : '' );
?>>
                            <label for="wcpoa_att_in_my_acc_enable"><?php 
esc_html_e( 'Enable', 'woocommerce-product-attachment' );
?></label>
                            <input type="radio" name="wcpoa_att_in_my_acc" class="wcpoa_att_in_my_acc" id="wcpoa_att_in_my_acc_disable" value="wcpoa_att_in_my_acc_disable" <?php 
echo ( $wcpoa_att_in_my_acc === "wcpoa_att_in_my_acc_disable" ? 'checked' : '' );
?>>
                            <label for="wcpoa_att_in_my_acc_disable"><?php 
esc_html_e( 'Disable', 'woocommerce-product-attachment' );
?></label>
                            <span class="wcpoa-description-tooltip-icon"></span>
                            <p class="wcpoa-description">
                                <?php 
/* translators: %s: Link to documentation */
echo sprintf( esc_html__( 'Select whether to display attachments in My Account page or not. %s.', 'woocommerce-product-attachment' ), '<a href="' . esc_url( 'https://docs.thedotstore.com/article/371-how-to-show-attachment-in-my-account-page' ) . '" target="_blank">' . esc_html__( 'Learn more', 'woocommerce-product-attachment' ) . '</a>' );
?>
                            </p>
                        </div>
                    </div>
                </div>
                <div class="wcpoa-general-input">
                    <label
                        class="wcpoa-general-input-title"><?php 
esc_html_e( 'Show Attachments in Thank You Page', 'woocommerce-product-attachment' );
?></label>
                    <div class="wcpoa-general-input-value">
                        <div class="wcpoa-name-radio-box">
                            <input type="radio" name="wcpoa_att_in_thankyou" class="wcpoa_att_in_thankyou" id="wcpoa_att_in_thankyou_enable" value="wcpoa_att_in_thankyou_enable" <?php 
echo ( $wcpoa_att_in_thankyou === "wcpoa_att_in_thankyou_enable" ? 'checked' : '' );
?>>
                            <label for="wcpoa_att_in_thankyou_enable"><?php 
esc_html_e( 'Enable', 'woocommerce-product-attachment' );
?></label>
                            <input type="radio" name="wcpoa_att_in_thankyou" class="wcpoa_att_in_thankyou" id="wcpoa_att_in_thankyou_disable" value="wcpoa_att_in_thankyou_disable" <?php 
echo ( $wcpoa_att_in_thankyou === "wcpoa_att_in_thankyou_disable" ? 'checked' : '' );
?>>
                            <label for="wcpoa_att_in_thankyou_disable"><?php 
esc_html_e( 'Disable', 'woocommerce-product-attachment' );
?></label>
                            <span class="wcpoa-description-tooltip-icon"></span>
                            <p class="wcpoa-description">
                                <?php 
/* translators: %s: Link to documentation */
echo sprintf( esc_html__( 'Select whether to display attachments in Thank You page or not. %s.', 'woocommerce-product-attachment' ), '<a href="' . esc_url( 'https://docs.thedotstore.com/article/372-how-to-show-attachment-on-the-thank-you-page' ) . '" target="_blank">' . esc_html__( 'Learn more', 'woocommerce-product-attachment' ) . '</a>' );
?>
                            </p>
                        </div>
                    </div>
                </div>
                <?php 
?>
                <div class="wcpoa-general-input">
                    <label
                        class="wcpoa-general-input-title wcpoa-pro-feature"><?php 
esc_html_e( 'Show Attachments in Download Tab', 'woocommerce-product-attachment' );
echo wp_kses( '<span class="wcpoa-pro-label"></span>', $this->allowed_html_tags() );
?></label>
                    <div class="wcpoa-general-input-value">
                        <div class="wcpoa-name-radio-box">
                            <input type="radio" name="wcpoa_att_btn_in_order_down_tab" class="wcpoa_att_btn_in_order_down_tab" id="wcpoa_att_btn_in_order_down_tab_enable" value="wcpoa_att_btn_in_order_down_tab_enable"
                                    disabled>
                            <label for="wcpoa_att_btn_in_order_down_tab_enable" class="wcpoa-pro-feature"><?php 
esc_html_e( 'Enable', 'woocommerce-product-attachment' );
?></label>
                            <input type="radio" name="wcpoa_att_btn_in_order_down_tab" class="wcpoa_att_btn_in_order_down_tab" id="wcpoa_att_btn_in_order_down_tab_disable" value="wcpoa_att_btn_in_order_down_tab_disable"
                                disabled>
                            <label for="wcpoa_att_btn_in_order_down_tab_disable" class="wcpoa-pro-feature"><?php 
esc_html_e( 'Disable', 'woocommerce-product-attachment' );
?></label>
                            <span class="wcpoa-description-tooltip-icon"></span>
                            <p class="wcpoa-description">
                                <?php 
/* translators: %s: Link to documentation */
echo sprintf( esc_html__( 'Select whether to display attachments in Download tab or not. %s.', 'woocommerce-product-attachment' ), '<a href="' . esc_url( 'https://docs.thedotstore.com/article/373-how-to-show-attachment-in-downloads-tab' ) . '" target="_blank">' . esc_html__( 'Learn more', 'woocommerce-product-attachment' ) . '</a>' );
?>
                            </p>
                        </div>
                    </div>
                </div><?php 
?>
            </div>
            <div class="wcpoa-general-setting">
                <div class="wcpoa-general-content-heading">
                    <h3><?php 
esc_html_e( 'Attachment Setting', 'woocommerce-product-attachment' );
?></h3>
                </div>
                <div class="wcpoa-general-input">
                    <label
                        class="wcpoa-general-input-title"><?php 
esc_html_e( 'Show Attachments Expire Date', 'woocommerce-product-attachment' );
?></label>
                    <div class="wcpoa-general-input-value">
                        <div class="wcpoa-name-radio-box">
                            <input type="radio" name="wcpoa_expired_date_label" class="wcpoa_expired_date_label" id="wcpoa_expired_date_label_yes" value="yes" <?php 
echo ( $wcpoa_expired_date_tlabel === "yes" ? 'checked' : '' );
?>>
                            <label for="wcpoa_expired_date_label_yes"><?php 
esc_html_e( 'Yes', 'woocommerce-product-attachment' );
?></label>
                            <input type="radio" name="wcpoa_expired_date_label" class="wcpoa_expired_date_label" id="wcpoa_expired_date_label_no" value="no" <?php 
echo ( $wcpoa_expired_date_tlabel === "no" ? 'checked' : '' );
?>>
                            <label for="wcpoa_expired_date_label_no"><?php 
esc_html_e( 'No', 'woocommerce-product-attachment' );
?></label>
                            <span class="wcpoa-description-tooltip-icon"></span>
                            <p class="wcpoa-description">
                                <?php 
/* translators: %s: Link to documentation */
echo sprintf( esc_html__( 'Select whether to show attachment expiry date or not. %s.', 'woocommerce-product-attachment' ), '<a href="' . esc_url( 'https://docs.thedotstore.com/article/375-how-to-show-attachment-expiry-date-for-your-store-as-well-as-email' ) . '" target="_blank">' . esc_html__( 'Learn more', 'woocommerce-product-attachment' ) . '</a>' );
?>
                            </p>
                        </div>
                    </div>
                </div>
                <?php 
?>
                    <div class="wcpoa-general-input">
                        <label class="wcpoa-general-input-title wcpoa-pro-feature"><?php 
esc_html_e( 'Show Attachments File Icon / Download Button', 'woocommerce-product-attachment' );
echo wp_kses( '<span class="wcpoa-pro-label"></span>', $this->allowed_html_tags() );
?></label>
                        <div class="wcpoa-general-input-value">
                            <div class="wcpoa-name-radio-box">
                                <input type="radio" name="wcpoa_att_download_btn" class="wcpoa_att_download_btn" id="wcpoa_att_icon" value="wcpoa_att_icon" disabled>
                                <label for="wcpoa_att_icon" class="wcpoa-pro-feature"><?php 
esc_html_e( 'Upload Icon', 'woocommerce-product-attachment' );
?></label>
                                <input type="radio" name="wcpoa_att_download_btn" class="wcpoa_att_download_btn" id="wcpoa_att_btn" value="wcpoa_att_btn" disabled>
                                <label for="wcpoa_att_btn" class="wcpoa-pro-feature"><?php 
esc_html_e( 'Default Button', 'woocommerce-product-attachment' );
?></label>
                                <span class="wcpoa-description-tooltip-icon"></span>
                                <p class="wcpoa-description">
                                    <?php 
/* translators: %s: Link to documentation */
echo sprintf( esc_html__( 'If no file icon selected then it will show the default attachment type. %s.', 'woocommerce-product-attachment' ), '<a href="' . esc_url( 'https://docs.thedotstore.com/article/377-how-to-enable-icon-for-your-attachment-instead-of-the-download-button' ) . '" target="_blank">' . esc_html__( 'Learn more', 'woocommerce-product-attachment' ) . '</a>' );
?>
                                </p>
                            </div>
                        </div>
                    </div>
                <?php 
?>
                <div class="wcpoa-general-input">
                    <label class="wcpoa-general-input-title"><?php 
esc_html_e( 'Attachments Action', 'woocommerce-product-attachment' );
?></label>
                    <div class="wcpoa-general-input-value">
                        <div class="wcpoa-name-radio-box">
                            <input type="radio" name="wcpoa_is_viewable" class="wcpoa_is_viewable" id="wcpoa_is_viewable_no" value="no" <?php 
echo ( $wcpoa_is_viewable === "no" ? 'checked' : '' );
?>>
                            <label for="wcpoa_is_viewable_no"><?php 
esc_html_e( 'Download', 'woocommerce-product-attachment' );
?></label>
                            <input type="radio" name="wcpoa_is_viewable" class="wcpoa_is_viewable" id="wcpoa_is_viewable_yes" value="yes" <?php 
echo ( $wcpoa_is_viewable === "yes" ? 'checked' : '' );
?>>
                            <label for="wcpoa_is_viewable_yes"><?php 
esc_html_e( 'View', 'woocommerce-product-attachment' );
?></label>
                            <span class="wcpoa-description-tooltip-icon"></span>
                            <p class="wcpoa-description">
                                <?php 
/* translators: %s: Link to documentation */
echo sprintf( esc_html__( 'Set attachments action as download or view in browser. %s.', 'woocommerce-product-attachment' ), '<a href="' . esc_url( 'https://docs.thedotstore.com/article/589-how-to-set-attachment-action' ) . '" target="_blank">' . esc_html__( 'Learn more', 'woocommerce-product-attachment' ) . '</a>' );
?>
                            </p>
                        </div>

                    </div>
                </div>
                <div class="wcpoa-general-input">
                    <label
                        class="wcpoa-general-input-title"><?php 
esc_html_e( 'Download Attachment Option', 'woocommerce-product-attachment' );
?></label>
                    <div class="wcpoa-general-input-value">
                        <div class="wcpoa-name-select-box text-title">
                            <select id="wcpoa_product_download_type" name="wcpoa_product_download_type">
                                <option name="download_by_btn" value="download_by_btn" <?php 
echo ( $wcpoa_product_download_type === "download_by_btn" ? 'selected' : '' );
?>><?php 
esc_html_e( 'Download By Button', 'woocommerce-product-attachment' );
?></option>
                                <option name="download_by_link" value="download_by_link" <?php 
echo ( $wcpoa_product_download_type === "download_by_link" ? 'selected' : '' );
?>><?php 
esc_html_e( 'Download By Link', 'woocommerce-product-attachment' );
?></option>
                                <option name="download_by_both" value="download_by_both" <?php 
echo ( $wcpoa_product_download_type === "download_by_both" ? 'selected' : '' );
?>><?php 
esc_html_e( 'Both', 'woocommerce-product-attachment' );
?></option>
                            </select>
                            <span class="wcpoa-description-tooltip-icon"></span>
                            <p class="wcpoa-description">
                                <?php 
/* translators: %s: Link to documentation */
echo sprintf( esc_html__( 'Select an option/type to download the product attachments. %s.', 'woocommerce-product-attachment' ), '<a href="' . esc_url( 'https://docs.thedotstore.com/article/589-how-to-set-attachment-action' ) . '" target="_blank">' . esc_html__( 'Learn more', 'woocommerce-product-attachment' ) . '</a>' );
?> 
                            </p>
                        </div>
                    </div>
                </div>
            </div>
            <div class="wcpoa-general-setting">
                <div class="wcpoa-general-content-heading">
                    <h3><?php 
esc_html_e( 'Global Default Setting', 'woocommerce-product-attachment' );
?></h3>
                </div>

                <div class="wcpoa-general-input">
                    <label
                        class="wcpoa-general-input-title"><?php 
esc_html_e( 'Set product attachment tab default selected', 'woocommerce-product-attachment' );
?></label>
                    <div class="wcpoa-general-input-value">
                        <div class="wcpoa-name-radio-box">
                            <input type="radio" name="wcpoa_default_tab_selected_flag" class="wcpoa_default_tab_selected_flag" id="wcpoa_default_tab_yes" value="yes" <?php 
echo ( $wcpoa_default_tab_selected_flag === "yes" ? 'checked' : '' );
?>>
                            <label for="wcpoa_default_tab_yes"><?php 
esc_html_e( 'Yes', 'woocommerce-product-attachment' );
?></label>
                            <input type="radio" name="wcpoa_default_tab_selected_flag" class="wcpoa_default_tab_selected_flag" id="wcpoa_default_tab_no" value="no" <?php 
echo ( $wcpoa_default_tab_selected_flag === "no" ? 'checked' : '' );
?>>
                            <label for="wcpoa_default_tab_no"><?php 
esc_html_e( 'No', 'woocommerce-product-attachment' );
?></label>
                            <span class="wcpoa-description-tooltip-icon"></span>
                            <p class="wcpoa-description">
                                <?php 
/* translators: %s: Link to documentation */
echo sprintf( esc_html__( 'Enable the attachment tab to be selected by default in the first position on the product page. %s.', 'woocommerce-product-attachment' ), '<a href="' . esc_url( 'https://docs.thedotstore.com/article/393-how-to-display-attachment-tab-default-selected' ) . '" target="_blank">' . esc_html__( 'Learn more', 'woocommerce-product-attachment' ) . '</a>' );
?> 
                            </p>
                        </div>
                    </div>
                </div>

                <div class="wcpoa-general-input">
                    <label
                        class="wcpoa-general-input-title"><?php 
esc_html_e( 'Show attachments with size', 'woocommerce-product-attachment' );
?></label>
                    <div class="wcpoa-general-input-value">
                        <div class="wcpoa-name-radio-box">
                            <input type="radio" name="wcpoa_show_attachment_size_flag" class="wcpoa_show_attachment_size_flag" id="wcpoa_show_att_size_yes" value="yes" <?php 
echo ( $wcpoa_show_attachment_size_flag === "yes" ? 'checked' : '' );
?>>
                            <label for="wcpoa_show_att_size_yes"><?php 
esc_html_e( 'Yes', 'woocommerce-product-attachment' );
?></label>
                            <input type="radio" name="wcpoa_show_attachment_size_flag" class="wcpoa_show_attachment_size_flag" id="wcpoa_show_att_size_no" value="no" <?php 
echo ( $wcpoa_show_attachment_size_flag === "no" ? 'checked' : '' );
?>>
                            <label for="wcpoa_show_att_size_no"><?php 
esc_html_e( 'No', 'woocommerce-product-attachment' );
?></label>
                            <span class="wcpoa-description-tooltip-icon"></span>
                            <p class="wcpoa-description">
                                <?php 
/* translators: %s: Link to documentation */
echo sprintf( esc_html__( 'Select whether to display size of the attached file or not. %s.', 'woocommerce-product-attachment' ), '<a href="' . esc_url( 'https://docs.thedotstore.com/article/392-how-to-display-attachment-with-attachment-size' ) . '" target="_blank">' . esc_html__( 'Learn more', 'woocommerce-product-attachment' ) . '</a>' );
?> 
                            </p>
                        </div>
                    </div>
                </div>
                <?php 
?>
            </div>
            <?php 
?>
            <div class="wcpoa-general-setting">
                <div class="wcpoa-general-content-heading">
                    <h3><?php 
esc_html_e( 'Attachments Custom Styles', 'woocommerce-product-attachment' );
?></h3>
                </div>
                <div class="wcpoa-general-input">
                    <label
                        class="wcpoa-general-input-title"><?php 
esc_html_e( 'Add custom css', 'woocommerce-product-attachment' );
?></label>
                    <div class="wcpoa-general-input-value">
                        <textarea name="attachment_custom_style" id="attachment_custom_style" cols="80" rows="5" placeholder=".woocommerce-Tabs-panel--wcpoa_product_tab .wcpoa_attachment_name{}
        .woocommerce-Tabs-panel--wcpoa_product_tab .wcpoa_attachment{}
        .woocommerce-Tabs-panel--wcpoa_product_tab a.wcpoa_attachmentbtn {}"><?php 
echo esc_html( $attachment_custom_style );
?></textarea>
                        <span class="wcpoa-description-tooltip-icon"></span>
                        <p class="wcpoa-description">
                            <?php 
/* translators: %s: Link to documentation */
echo sprintf( esc_html__( 'Add your custom css for our product attachment section. %s.', 'woocommerce-product-attachment' ), '<a href="' . esc_url( 'https://docs.thedotstore.com/article/399-how-to-customize-style-the-attachment-section' ) . '" target="_blank">' . esc_html__( 'Learn more', 'woocommerce-product-attachment' ) . '</a>' );
?>
                        </p>
                    </div>
                </div>
            </div>
            <div class="wcpoa-setting-btn wcpoa-general-submit">
                <?php 
submit_button();
?>
            </div>
        </div>
    </form>
</div>
