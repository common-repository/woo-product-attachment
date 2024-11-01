<?php

// If this file is called directly, abort.
if (!defined('WPINC')) {
    die;
}
global $product, $post, $i, $field;

// rows
$field['min'] = empty($field['min']) ? 0 : $field['min'];
$field['max'] = empty($field['max']) ? 0 : $field['max'];

// vars
$div = array(
    'class' => 'wcpoa-repeater',
    'data-min' => $field['min'],
    'data-max' => $field['max']
);

// ensure value is an array
if (empty($field['value'])) {

    $field['value'] = array();

    $div['class'] .= ' -empty';
}

// populate the empty row data (used for wcpoacloneindex and min setting)
$empty_row = array();

// If there are less values than min, populate the extra values
if ($field['min']) {

    for ($i = 0; $i < $field['min']; $i++) {

        // continue if already have a value
        if (array_key_exists($i, $field['value'])) {
            continue;
        }
        // populate values
        $field['value'][$i] = $empty_row;
    }
}

// If there are more values than man, remove some values
if ($field['max']) {

    for ($i = 0; $i < count($field['value']); $i++) {

        if ($i >= $field['max']) {

            unset($field['value'][$i]);
        }
    }
}

// setup values for row clone
$field['value']['wcpoacloneindex'] = $empty_row;
// show columns
$show_add = true;
$show_remove = false;

if ($field['max']) {

    if ((int)$field['max'] <= (int)$field['min']) {

        $show_remove = false;
        $show_add = false;
    }
}

// field wrap
$el = 'td';
$before_fields = '';
$after_fields = '';

if ('row' === 'row') {

    $el = 'div';
    $before_fields = '<td class="wcpoa-fields -left">';
    $after_fields = '</td>';
}

// layout
$div['class'] .= ' -' . 'row';
$plugin_url = WCPOA_PLUGIN_URL;

wp_nonce_field(plugin_basename(__FILE__), 'wcpoa_attachment_nonce');

$wcpoa_bulk_att_data = get_option('wcpoa_bulk_attachment_data');

?>
<div class="wcpoa-field wcpoa-field-repeater" data-name="attachments" data-type="repeater" data-key="attachments">
    <input type="hidden" value="wcpoa_bulk_attachment" id="wcpoa-plug-page">
    <div class="wcpoa-label wcpoa-bulk-att-desc">
        <div>
            <?php esc_html_e( 'Easily manage and create bulk product attachments. Define the attachment\'s scope by specifying applicable products, categories, tags, and attributes.', 'woocommerce-product-attachment' ) ?>
            <span class="wcpoa-description-tooltip-icon"></span>
            <p class="wcpoa-description">
                <?php
                /* translators: %s: Link to documentation */
                echo sprintf(
                    esc_html__('%s to review the document guide.', 'woocommerce-product-attachment'),
                    '<a href="' . esc_url('https://docs.thedotstore.com/article/378-bulk-attachment-for-woocommerce') . '" target="_blank">' . esc_html__('Click here', 'woocommerce-product-attachment') . '</a>'
                );
                ?>
            </p>
        </div>
    </div>
    <div class="wcpoa-oprations wcpoa-pro-operations">
        <div class="wcpoa-op-right">
            <a class="button button-secondary"><?php esc_html_e('Import', 'woocommerce-product-attachment') ?><span class="wcpoa-pro-label"></span></a>
            <a class="button button-secondary"><?php esc_html_e('Export', 'woocommerce-product-attachment') ?><span class="wcpoa-pro-label"></span></a>
        </div>
    </div>
    <div <?php $this->wcpoa_esc_attr_e($div); ?>>
        <table class="wcpoa-table wcpoa_bulk_table">
            <tbody class="wcpoa-ui-sortable" id='wcpoa-ui-tbody'>
                <tr>
                    <th>
                        <div class="wcpoa-label top-heading">
                            <label for="attchment_order"><?php esc_html_e('No.', 'woocommerce-product-attachment') ?></label>
                        </div>
                        <div class="wcpoa-label top-heading">
                            <label for="attchment_name"><?php esc_html_e('Name', 'woocommerce-product-attachment') ?></label>
                        </div>
                        <div class="wcpoa-label top-heading not-mobile">
                            <label for="attchment_type"><?php esc_html_e('Type', 'woocommerce-product-attachment') ?></label>
                        </div>
                        <div class="wcpoa-label top-heading not-mobile">
                            <label for="attchment_visibility"><?php esc_html_e('Visibility', 'woocommerce-product-attachment') ?></label>
                        </div>
                        <div class="wcpoa-label top-heading not-mobile">
                            <label for="attchment_remove"><?php esc_html_e('Expire', 'woocommerce-product-attachment') ?></label>
                        </div>
                    </th>
                </tr>
                <?php
                $wcpoa_attachments_id = "";
                if($wcpoa_bulk_att_data) {
                    $i = 0;
                    foreach ($wcpoa_bulk_att_data as $key => $wcpoa_bulk_att_data_value) {
                        if (!empty($wcpoa_bulk_att_data_value)) {
                            $wcpoa_attachments_id = $key; //attachement id  

                            $attachment_name = isset($wcpoa_bulk_att_data[$key]['wcpoa_attachment_name']) && !empty($wcpoa_bulk_att_data[$key]['wcpoa_attachment_name']) ? $wcpoa_bulk_att_data[$key]['wcpoa_attachment_name'] : '';
                            $wcpoa_attach_view = isset($wcpoa_bulk_att_data[$key]['wcpoa_attach_view']) && !empty($wcpoa_bulk_att_data[$key]['wcpoa_attach_view']) ? $wcpoa_bulk_att_data[$key]['wcpoa_attach_view'] : '';
                            $wcpoa_attach_type = isset($wcpoa_bulk_att_data[$key]['wcpoa_attach_type']) && !empty($wcpoa_bulk_att_data[$key]['wcpoa_attach_type']) ? $wcpoa_bulk_att_data[$key]['wcpoa_attach_type'] : '';
                            $wcpoa_attachment_url = isset($wcpoa_bulk_att_data[$key]['wcpoa_attachment_url']) && !empty($wcpoa_bulk_att_data[$key]['wcpoa_attachment_url']) ? $wcpoa_bulk_att_data[$key]['wcpoa_attachment_url'] : '';
                            $wcpoa_attachment_file_id = isset($wcpoa_bulk_att_data[$key]['wcpoa_attachment_file']) && !empty($wcpoa_bulk_att_data[$key]['wcpoa_attachment_file']) ? $wcpoa_bulk_att_data[$key]['wcpoa_attachment_file'] : '';
                            $wcpoa_attachment_descriptions = isset($wcpoa_bulk_att_data[$key]['wcpoa_attachment_description']) && !empty($wcpoa_bulk_att_data[$key]['wcpoa_attachment_description']) ? $wcpoa_bulk_att_data[$key]['wcpoa_attachment_description'] : '';
                            $wcpoa_is_condition = isset($wcpoa_bulk_att_data[$key]['wcpoa_is_condition']) && !empty($wcpoa_bulk_att_data[$key]['wcpoa_is_condition']) ? $wcpoa_bulk_att_data[$key]['wcpoa_is_condition'] : '';
                            $wcpoa_visibility = isset($wcpoa_bulk_att_data[$key]['wcpoa_att_visibility']) && !empty($wcpoa_bulk_att_data[$key]['wcpoa_att_visibility']) ? $wcpoa_bulk_att_data[$key]['wcpoa_att_visibility'] : '';
                            $wcpoa_product_date_enable = isset($wcpoa_bulk_att_data[$key]['wcpoa_expired_date_enable']) && !empty($wcpoa_bulk_att_data[$key]['wcpoa_expired_date_enable']) ? $wcpoa_bulk_att_data[$key]['wcpoa_expired_date_enable'] : '';
                            $wcpoa_expired_dates = isset($wcpoa_bulk_att_data[$key]['wcpoa_expired_date']) && !empty($wcpoa_bulk_att_data[$key]['wcpoa_expired_date']) ? $wcpoa_bulk_att_data[$key]['wcpoa_expired_date'] : '';    
                            $wcpoa_product_logged_in_flag_val = isset($wcpoa_bulk_att_data[$key]['wcpoa_product_logged_in_flag']) && !empty($wcpoa_bulk_att_data[$key]['wcpoa_product_logged_in_flag']) ? $wcpoa_bulk_att_data[$key]['wcpoa_product_logged_in_flag'] : '';
                            $wcpoa_product_open_window_flag_val = isset($wcpoa_bulk_att_data[$key]['wcpoa_product_open_window_flag']) && !empty($wcpoa_bulk_att_data[$key]['wcpoa_product_open_window_flag']) ? $wcpoa_bulk_att_data[$key]['wcpoa_product_open_window_flag'] : '';
                            $wcpoa_att_time_amount = isset($wcpoa_bulk_att_data[$key]['wcpoa_attachment_time_amount']) && !empty($wcpoa_bulk_att_data[$key]['wcpoa_attachment_time_amount']) ? $wcpoa_bulk_att_data[$key]['wcpoa_attachment_time_amount'] : '';     

                            $wcpoa_order_status_value = isset($wcpoa_bulk_att_data_value['wcpoa_order_status']) && !empty($wcpoa_bulk_att_data_value['wcpoa_order_status']) ? $wcpoa_bulk_att_data_value['wcpoa_order_status'] : '';
                            if( empty( $wcpoa_order_status_value )) {
                                $wcpoa_order_status = array();
                            } else {
                                $wcpoa_order_status = $wcpoa_order_status_value;
                            }

                            //file upload
                            $uploader = 'uploader';

                            // vars
                            $o = array(
                                'icon' => '',
                                'title' => '',
                                'url' => '',
                                'filesize' => '',
                                'filename' => '',
                            );

                            $filediv = array(
                                'class' => 'wcpoa-file-uploader wcpoa-cf',
                                'data-uploader' => $uploader
                            );

                            // has value?
                            if (!empty($wcpoa_attachment_file_id)) {
                                $file = get_post($wcpoa_attachment_file_id);
                                if ($file) {
                                    $o['icon'] = wp_mime_type_icon($wcpoa_attachment_file_id);
                                    $o['title'] = $file->post_title;
                                    $o['filesize'] = size_format(filesize(get_attached_file($wcpoa_attachment_file_id)));
                                    $o['url'] = wp_get_attachment_url($wcpoa_attachment_file_id);

                                    $explode = explode('/', $o['url']);
                                    $o['filename'] = end($explode);
                                }
                                if ($o['url']) {

                                    $filediv['class'] .= ' has-value';
                                }
                            }
                            ?>

                            <tr  class="wcpoa-row wcpoa-has-value -collapsed" id="<?php echo esc_attr($wcpoa_attachments_id) ?>" data-id="<?php echo esc_attr($wcpoa_attachments_id) ?>">
                                <?php echo wp_kses($before_fields,$this->allowed_html_tags()); ?>
                                <div class="wcpoa-field -collapsed-target group-title" data-name="_name" data-type="text" data-key="">
                                    <div class="wcpoa-label">
                                        <span class="attchment_order"><?php echo intval($i+1); $i++; ?></span>
                                    </div>
                                    <div class="wcpoa-label attachment_name">
                                        <label for="attchment_name"><?php esc_html_e($attachment_name ,'woocommerce-product-attachment'); ?></label>
                                        <ul class="attachment_action">
                                            <li><a class="edit_bulk_attach"href="#"><?php esc_html_e('Edit' ,'woocommerce-product-attachment'); ?></a></li>
                                            <li><a class="-minus small wcpoa-js-tooltip" href="#" data-event="remove-row" title="<?php esc_attr_e('Remove','woocommerce-product-attachment'); ?>"><?php esc_html_e('Delete' ,'woocommerce-product-attachment'); ?></a></li>
                                        </ul>
                                    </div>
                                    <div class="wcpoa-label not-mobile">
                                        <?php
                                            if($wcpoa_attach_type === "file_upload"){
                                                $path_parts = pathinfo($o['url']);
                                                $ext = strtolower($path_parts["extension"]);
                                                $file_upload_text = 'File Upload ( .'.$ext.' )';
                                            }
                                        ?>
                                        <label for="attchment_type"><?php ($wcpoa_attach_type === "file_upload") ? esc_html_e($file_upload_text, 'woocommerce-product-attachment') : esc_html_e('External URL', 'woocommerce-product-attachment');  ?></label>
                                    </div>
                                    <div class="wcpoa-label not-mobile">
                                        <label for="attchment_visibility">
                                            <?php
                                                if("order_details_page" === $wcpoa_visibility){
                                                    esc_html_e('Order Details Page','woocommerce-product-attachment');
                                                }elseif("product_details_page" === $wcpoa_visibility){
                                                    esc_html_e('Product Details Page','woocommerce-product-attachment');
                                                }else{
                                                    esc_html_e('Order/ Product Details Page','woocommerce-product-attachment');
                                                }
                                            ?>
                                        </label>
                                    </div>
                                    <div class="wcpoa-label not-mobile">
                                        <label for="attchment_expire">
                                            <?php
                                                if("no" === $wcpoa_product_date_enable){
                                                    esc_html_e('No','woocommerce-product-attachment');
                                                }elseif("yes" === $wcpoa_product_date_enable){
                                                    esc_html_e('Specific Date','woocommerce-product-attachment');
                                                }elseif("time_amount" === $wcpoa_product_date_enable){
                                                    esc_html_e('Specific Time','woocommerce-product-attachment');
                                                }
                                            ?>
                                        </label>
                                    </div>
                                </div>
                                <div class="wcpoa-field wcpoa-field-text wcpoa-field-id" data-name="id" data-type="text" data-key="">
                                    <div class="wcpoa-label">
                                        <label for=""><?php esc_html_e('Id','woocommerce-product-attachment') ?> </label>
                                    </div>
                                    <div class="wcpoa-input">
                                        <div class="wcpoa-input-wrap">
                                            <input readonly="" class="wcpoa_attachments_id" name="wcpoa_attachments_id[]" value="<?php echo esc_attr($wcpoa_attachments_id); ?>" placeholder="" type="text">
                                            <p class="wcpoa-description"><?php esc_html_e('Attachments Id used to identify each product attachment.This value is automatically generated.','woocommerce-product-attachment') ?></p>
                                        </div>
                                    </div>
                                </div>
                                <div class="wcpoa-field" data-name="_name" data-type="text" data-key="">
                                    <div class="wcpoa-label">
                                        <label for="attchment_name"><?php esc_html_e('Name','woocommerce-product-attachment'); ?><span class="wcpoa-required"> *</span></label>
                                    </div>
                                    <div class="wcpoa-input wcpoa-att-name-parent">
                                        <input class="wcpoa-attachment-name"  type="text" name="wcpoa_attachment_name[]" placeholder="<?php esc_attr_e('Attachment', 'woocommerce-product-attachment'); ?>" value="<?php echo esc_attr($attachment_name); ?>">
                                        <span class="wcpoa-description-tooltip-icon"></span>
                                        <p class="wcpoa-description"><?php esc_html_e('Enter a name for the attachment. It will be displayed on the front end next to the download/view button.', 'woocommerce-product-attachment') ?></p>
                                    </div>
                                </div>
                                <div class="wcpoa-field wcpoa-field-select">
                                    <div class="wcpoa-label"> 
                                        <label for="wcpoa_attach_view"><?php esc_html_e('Status','woocommerce-product-attachment'); ?></label>
                                    </div>

                                    <div class="wcpoa-input wcpoa_attach_view">
                                        <select name="wcpoa_attach_view[]" class="wcpoa_attach_view" data-type="" data-key="">
                                            <option name="enable" <?php echo ($wcpoa_attach_view === "enable") ? 'selected' : '';  ?> value="enable"><?php esc_html_e('Enable', 'woocommerce-product-attachment') ?></option>
                                            <option name="disable" <?php echo ($wcpoa_attach_view === "disable") ? 'selected' : '';  ?> value="disable" class=""><?php esc_html_e('Disable', 'woocommerce-product-attachment') ?></option>
                                        </select>
                                        <span class="wcpoa-description-tooltip-icon"></span>
                                        <p class="wcpoa-description"><?php esc_html_e('Enable or Disable attachment using this option (This attachment will be visible to customers only if it is enabled).','woocommerce-product-attachment'); ?></p>
                                    </div> 
                                </div>
                                <div class="wcpoa-field wcpoa-field-textarea " data-name="description" data-type="textarea" data-key="" data-required="1">
                                    <div class="wcpoa-label">
                                        <label for="attchment_desc"><?php esc_html_e('Description','woocommerce-product-attachment'); ?></label>
                                        
                                    </div>
                                    <div class="wcpoa-input">
                                        <textarea class="" name="wcpoa_attachment_description[]" placeholder="<?php esc_attr_e('Enter a description', 'woocommerce-product-attachment'); ?>" rows="8"><?php echo esc_html($wcpoa_attachment_descriptions); ?></textarea>
                                        <span class="wcpoa-description-tooltip-icon"></span>
                                        <p class="wcpoa-description"><?php esc_html_e('You can type a short description of the attachment file. So customers will get details about the attachment file.', 'woocommerce-product-attachment') ?></p>
                                    </div>
                                </div>
                                <div class="wcpoa-field wcpoa-field-select">
                                    <div class="wcpoa-label"> 
                                        <label for="wcpoa_attach_type"><?php esc_html_e('Attachment Type','woocommerce-product-attachment'); ?></label>
                                    </div>

                                    <div class="wcpoa-input wcpoa_attach_type">
                                        <select name="wcpoa_attach_type[]" class="wcpoa_attach_type_list" data-type="" data-key="">
                                            <option name="file_upload" <?php echo ($wcpoa_attach_type === "file_upload") ? 'selected' : '';  ?> value="file_upload"><?php esc_html_e('File Upload', 'woocommerce-product-attachment') ?></option>
                                            <option value="external_ulr"><?php esc_html_e('External URL 🔒', 'woocommerce-product-attachment') ?></option>
                                        </select>
                                        <span class="wcpoa-description-tooltip-icon"></span>
                                        <p class="wcpoa-description"><?php esc_html_e('Select the attachment type. Like Upload file / External URL','woocommerce-product-attachment'); ?></p>
                                    </div> 
                                </div>

                                <div class="wcpoa-field file_upload wcpoa-field-file required" data-name="file" data-type="file" data-key="" data-required="1">
                                    <div class="wcpoa-label">
                                        <label for=""><?php esc_html_e('Upload Attachment File','woocommerce-product-attachment'); ?>
                                            <span class="wcpoa-required"> *</span></label>
                                    </div>
                                    <div class="wcpoa-input">
                                        <div <?php $this->wcpoa_esc_attr_e($filediv); ?> data-id="<?php echo esc_attr($wcpoa_attachments_id) ?>">
                                            <div class="wcpoa-error-message"><p><?php echo 'File value is required'; ?></p>
                                                <input name="wcpoa_attachment_file[]" value="<?php echo esc_attr($wcpoa_attachment_file_id) ?>" data-name="id" type="hidden" required="required">
                                            </div>    
                                            <div class="show-if-value file-wrap wcpoa-soh">
                                                <div class="file-icon">
                                                    <img data-name="icon" src="<?php echo esc_url($o['icon']); ?>" alt=""/>
                                                </div>
                                                <div class="file-info">
                                                    <p>
                                                        <strong data-name="title"><?php echo esc_html($o['title']); ?></strong>
                                                    </p>
                                                    <p>
                                                        <strong><?php esc_html_e('File name', 'woocommerce-product-attachment'); ?>:</strong>
                                                        <a data-name="filename" href="<?php echo esc_url($o['url']); ?>" target="_blank"><?php echo esc_html($o['filename']); ?></a>
                                                    </p>
                                                    <p>
                                                        <strong><?php esc_html_e('File size', 'woocommerce-product-attachment'); ?>:</strong>
                                                        <span data-name="filesize"><?php echo esc_html($o['filesize']); ?></span>
                                                    </p>

                                                    <ul class="wcpoa-hl wcpoa-soh-target">
                                                        <?php if ($uploader !== 'basic'): ?>
                                                            <li><a class="wcpoa-icon -pencil dark" data-id="<?php echo esc_attr($wcpoa_attachments_id) ?>" data-name="edit" href="#"></a></li>
                                                        <?php endif; ?>
                                                        <li><a class="wcpoa-icon -cancel dark" data-id="<?php echo esc_attr($wcpoa_attachments_id) ?>" data-name="remove" href="#"></a></li>
                                                    </ul>
                                                </div>
                                            </div>
                                            <div class="hide-if-value">
                                                <?php echo wp_kses($this->wcpoa_image_uploader_field($wcpoa_attachments_id),$this->allowed_html_tags()); ?>

                                            </div>
                                            <p  class="wcpoa-description"><?php esc_html_e('Select upload attachment File.', 'woocommerce-product-attachment') ?></p>
                                        </div>
                                    </div>
                                </div>

                                <div class="wcpoa-field">
                                    <div class="wcpoa-label">
                                        <label for="product_page_enable"><?php esc_html_e('Open in new window', 'woocommerce-product-attachment'); ?></label>
                                    </div>
                                    <div class="wcpoa-input">
                                        <select id="wcpoa_product_open_window_flag"
                                                name="wcpoa_product_open_window_flag[]">
                                            <option name="no" <?php echo ($wcpoa_product_open_window_flag_val === "no") ? 'selected' : ''; ?>
                                                    value="no"><?php esc_html_e('No', 'woocommerce-product-attachment') ?></option>
                                            <option name="yes" <?php echo ($wcpoa_product_open_window_flag_val === "yes") ? 'selected' : ''; ?>
                                                    value="yes"><?php esc_html_e('Yes', 'woocommerce-product-attachment') ?></option>
                                        </select>
                                        <span class="wcpoa-description-tooltip-icon"></span>
                                        <p class="wcpoa-description"><?php esc_html_e('Select Link Behavior: Specify whether you want the attachment link to open in a new window or the same window.', 'woocommerce-product-attachment') ?></p> 
                                    </div>
                                </div>
                                <!-- nirav code start --> 
                                <div class="wcpoa-field wcpoa-field-select" data-type="select">
                                    <div class="wcpoa-label">
                                        <label for="wcpoa_sel_product"><?php esc_html_e('Attachment For', 'woocommerce-product-attachment'); ?></label>
                                    </div>
                                    <div class="wcpoa-input">
                                        <select name="wcpoa_is_condition[]" class="is_condition_select" >
                                            <option value="no" <?php echo $wcpoa_is_condition==='no'?"selected":'' ?>><?php esc_html_e('All Product', 'woocommerce-product-attachment'); ?></option>
                                            <option value="yes"><?php esc_html_e('Specific Product, Category, Tags, Attributes 🔒', 'woocommerce-product-attachment'); ?><span class="wcpoa-pro-label wcpoa-pro-feature"></span></option>
                                        </select>
                                        <span class="wcpoa-description-tooltip-icon"></span>
                                        <p class="wcpoa-description"><?php esc_html_e('You can either assign this attachment to all products or assign it to specific products, categories, tags, or attributes as required.', 'woocommerce-product-attachment') ?></p>
                                    </div>
                                </div> 
                                <div class="wcpoa-field">
                                    <div class="wcpoa-label">
                                    <label for="product_page_enable">
                                        <?php esc_html_e('Attachment Visibility Pages','woocommerce-product-attachment'); ?></label>
                                    </div>
                                    <div class="wcpoa-input">
                                        <select id="wcpoa_product_page_enable" name="wcpoa_att_visibility[]">
                                            <option name="order_details_page" <?php echo ($wcpoa_visibility === "order_details_page") ? 'selected' : '';  ?> value="order_details_page"><?php esc_html_e('Order Details Page','woocommerce-product-attachment') ?></option>
                                            <option name="product_details_page" <?php echo ($wcpoa_visibility === "product_details_page") ? 'selected' : ''; ?> value="product_details_page"><?php esc_html_e('Product Details Page','woocommerce-product-attachment') ?></option>
                                            <option name="wcpoa_all" <?php echo ($wcpoa_visibility === "wcpoa_all") ? 'selected' : '';  ?> value="wcpoa_all"><?php esc_html_e('Both','woocommerce-product-attachment') ?></option>          
                                        </select>
                                        <span class="wcpoa-description-tooltip-icon"></span>
                                        <p class="wcpoa-description"><?php esc_html_e('Select where you want to showcase the attachment - Product Page, Order Page, or Both.','woocommerce-product-attachment'); ?></p>
                                    </div>
                                </div>
                                <div class="wcpoa-field">
                                    <div class="wcpoa-label">
                                        <label for="product_page_enable"><?php esc_html_e('Show only for logged in users', 'woocommerce-product-attachment'); ?></label>
                                    </div>
                                    <div class="wcpoa-input">
                                        <select id="wcpoa_product_logged_in_flag"
                                                name="wcpoa_product_logged_in_flag[]">
                                            <option name="no" <?php echo ($wcpoa_product_logged_in_flag_val === "no") ? 'selected' : ''; ?>
                                                    value="no"><?php esc_html_e('No', 'woocommerce-product-attachment') ?></option>
                                            <option name="yes" <?php echo ($wcpoa_product_logged_in_flag_val === "yes") ? 'selected' : ''; ?>
                                                    value="yes"><?php esc_html_e('Yes', 'woocommerce-product-attachment') ?></option>
                                        </select>
                                        <span class="wcpoa-description-tooltip-icon"></span>
                                        <p class="wcpoa-description"><?php esc_html_e('Select whether you want to display the attachment only for logged-in users or not.', 'woocommerce-product-attachment') ?></p> 
                                    </div>
                                </div>

                                <div class="wcpoa-field">
                                    <div class="wcpoa-label">
                                        <label for="attchment_order_status">
                                            <?php esc_html_e('Order Status','woocommerce-product-attachment'); ?></label>
                                    </div>
                                    <div class="wcpoa-input">
                                        <p class="description"><?php esc_html_e('Select order status, where you want to showcase attachment. Leave unselected then apply to all.','woocommerce-product-attachment'); ?></p>
                                        <ul class="wcpoa-checkbox-list">
                                            <li>
                                                <input name="wcpoa_order_status[<?php echo esc_attr($wcpoa_attachments_id); ?>][]" id="wcpoa_wc_order_completed" value="wcpoa-wc-completed" type="checkbox" <?php if (!is_null($wcpoa_order_status) && in_array('wcpoa-wc-completed', $wcpoa_order_status,true)) echo 'checked="checked"'; ?>>
                                                <label for="wcpoa_wc_order_completed"><?php esc_html_e('Completed', 'woocommerce-product-attachment'); ?></label>
                                            </li>
                                            <li>
                                                <input name="wcpoa_order_status[<?php echo esc_attr($wcpoa_attachments_id); ?>][]" id="wcpoa_wc_order_on_hold" value="wcpoa-wc-on-hold" type="checkbox" <?php if (!is_null($wcpoa_order_status) && in_array('wcpoa-wc-on-hold', $wcpoa_order_status,true)) echo 'checked="checked"'; ?>>
                                                <label for="wcpoa_wc_order_on_hold"><?php esc_html_e('On Hold', 'woocommerce-product-attachment'); ?></label>
                                            </li>
                                            <li>
                                                <input name="wcpoa_order_status[<?php echo esc_attr($wcpoa_attachments_id); ?>][]" id="wcpoa_wc_order_pending" value="wcpoa-wc-pending" type="checkbox" <?php if (!is_null($wcpoa_order_status) && in_array('wcpoa-wc-pending', $wcpoa_order_status,true)) echo 'checked="checked"'; ?>>
                                                <label for="wcpoa_wc_order_pending"><?php esc_html_e('Pending payment', 'woocommerce-product-attachment'); ?></label>
                                            </li>
                                            <li>
                                                <input name="wcpoa_order_status[<?php echo esc_attr($wcpoa_attachments_id); ?>][]" id="wcpoa_wc_order_processing" value="wcpoa-wc-processing" type="checkbox" <?php if (!is_null($wcpoa_order_status) && in_array('wcpoa-wc-processing', $wcpoa_order_status,true)) echo 'checked="checked"'; ?>>
                                                <label for="wcpoa_wc_order_processing"><?php esc_html_e('Processing', 'woocommerce-product-attachment'); ?></label>
                                            </li>
                                            <li>
                                                <input name="wcpoa_order_status[<?php echo esc_attr($wcpoa_attachments_id); ?>][]" id="wcpoa_wc_order_cancelled" value="wcpoa-wc-cancelled" type="checkbox" <?php if (!is_null($wcpoa_order_status) && in_array('wcpoa-wc-cancelled', $wcpoa_order_status,true)) echo 'checked="checked"'; ?>>
                                                <label for="wcpoa_wc_order_cancelled"><?php esc_html_e('Cancelled', 'woocommerce-product-attachment'); ?></label>
                                            </li>
                                            <li>
                                                <input name="wcpoa_order_status[<?php echo esc_attr($wcpoa_attachments_id); ?>][]" id="wcpoa_wc_order_failed" value="wcpoa-wc-failed" type="checkbox" <?php if (!is_null($wcpoa_order_status) && in_array('wcpoa-wc-failed', $wcpoa_order_status,true)) echo 'checked="checked"'; ?>>
                                                <label for="wcpoa_wc_order_failed"><?php esc_html_e('Failed', 'woocommerce-product-attachment'); ?></label>
                                            </li>
                                            <li>
                                                <input name="wcpoa_order_status[<?php echo esc_attr($wcpoa_attachments_id); ?>][]" id="wcpoa_wc_order_refunded" value="wcpoa-wc-refunded" type="checkbox" <?php if (!is_null($wcpoa_order_status) && in_array('wcpoa-wc-refunded', $wcpoa_order_status,true)) echo 'checked="checked"'; ?>>
                                                <label for="wcpoa_wc_order_refunded"><?php esc_html_e('Refunded', 'woocommerce-product-attachment'); ?></label>
                                            </li>
                                        </ul>
                                    </div>
                                </div>

                                <div class="wcpoa-field">
                                    <div class="wcpoa-label">
                                        <label for="wcpoa_expired_date_enable"><?php esc_html_e('Set Expire date/time','woocommerce-product-attachment'); ?></label>
                                    </div>
                                    <div class="wcpoa-input enable_expire_date">
                                        <select name="wcpoa_expired_date_enable[]" class="enable_date_time" data-type="enable_date_<?php echo esc_attr($wcpoa_attachments_id); ?>" data-key="">          
                                            <option name="no" <?php  echo ($wcpoa_product_date_enable === "no") ? 'selected' : '';  ?> value="no" class=""><?php esc_html_e('No','woocommerce-product-attachment') ?></option>
                                            <option name="yes" <?php  echo ($wcpoa_product_date_enable === "yes") ? 'selected' : ''; ?> value="yes"><?php esc_html_e('Specific Date','woocommerce-product-attachment') ?></option>
                                            <option value="time_amount"><?php esc_html_e('Selected time period after purchase 🔒', 'woocommerce-product-attachment'); ?></option>
                                        </select>
                                        <span class="wcpoa-description-tooltip-icon"></span>
                                        <p class="wcpoa-description"><?php esc_html_e('Set a specific date and specific time to access the attachment.','woocommerce-product-attachment'); ?></p>
                                    </div>
                                </div>
                                    <?php $is_date=$wcpoa_product_date_enable!=='yes'?'none':''; ?>   
                                    <div style="display:<?php  echo esc_attr($is_date)  ?>"class="wcpoa-field enable_date enable_date_<?php echo esc_attr($wcpoa_attachments_id); ?> wcpoa-field-date-picker" data-name="date" data-type="date_picker" data-key="" data-required="1" style=''>
                                    <div class="wcpoa-label">
                                        <label for="wcpoa_expired_date"><?php esc_html_e('Specific Date','woocommerce-product-attachment'); ?></label>
                                    </div>
                                    <div class="wcpoa-input">
                                        <div class="wcpoa-date-picker wcpoa-input-wrap" data-date_format="yy/mm/dd">
                                            
                                            <input name="wcpoa_expired_date[]" class="input wcpoa-php-date-picker" placeholder="<?php echo esc_attr('yy/mm/dd'); ?>" autocomplete="off" value="<?php echo esc_attr(($wcpoa_product_date_enable === "yes") ? $wcpoa_expired_dates : ''); ?>" type="text">
                                            <span class="wcpoa-description-tooltip-icon"></span>
                                        <p class="wcpoa-description"><?php esc_html_e('If an order is placed after the selected date, the attachments will be no longer visible for download. ( Date format: yy/mm/dd )','woocommerce-product-attachment') ?></p>
                                        </div>
                                    </div>

                                </div>
                                    <?php $is_time=$wcpoa_product_date_enable!=='time_amount'?'none':''; ?> <div class="wcpoa-field enable_time" style='display:<?php  echo esc_attr($is_time)  ?>'>
                                    <div class="wcpoa-label">
                                        <label for="attchment_time_amount"><?php esc_html_e('Time Period','woocommerce-product-attachment'); ?></label>
                                    </div>
                                    <div class="wcpoa-input">
                                        <input class="wcpoa-attachment-_time-amount" type="number" name="wcpoa_attachment_time_amount[]" placeholder="<?php echo esc_attr('2'); ?>" value="<?php echo esc_attr($wcpoa_att_time_amount); ?>" >
                                        <span class="wcpoa-description-tooltip-icon"></span>
                                        <p class="wcpoa-description"><?php esc_html_e('Set time period, After that Attachment will not accessible.','woocommerce-product-attachment') ?></p>
                                    </div>
                                </div>
                                <?php echo wp_kses($after_fields,$this->allowed_html_tags()); ?>
                                <?php if ($show_remove): ?>
                                    <td class="wcpoa-row-handle remove">
                                        <a class="wcpoa-icon -plus small wcpoa-js-tooltip" href="#" data-event="add-row" title="<?php esc_attr_e('Add row','woocommerce-product-attachment'); ?>"></a>
                                        <a class="wcpoa-icon -minus small wcpoa-js-tooltip" href="#" data-event="remove-row" title="<?php esc_attr_e('Remove row','woocommerce-product-attachment'); ?>"></a>
                                    </td>
                                <?php endif; ?>

                            </tr>
                        <?php
                        }
                    }   
                }else{ ?>
                    <tr class="wcpoa-not-found-bulk-attach">
                        <td><?php esc_html_e('No Attachment Found !', 'woocommerce-product-attachment') ?></td>
                    </tr>
                <?php }  ?>
                <tr  class="hidden trr wcpoa-row wcpoa-has-value" data-id="">

                    <?php echo wp_kses($before_fields,$this->allowed_html_tags()); ?>
                    <div class="wcpoa-field -collapsed-target group-title" data-name="_name" data-type="text" data-key="">
                        <div class="wcpoa-label order">
                            <span class="attchment_order"><?php echo intval($i+1); $i++; ?></span>
                        </div>
                        <div class="wcpoa-label attachment_name">
                            <label for="attchment_name"><?php esc_html_e('No Attachment Name' ,'woocommerce-product-attachment'); ?></label>
                            <ul class="attachment_action">
                                <li><a class="edit_bulk_attach"href="#"><?php esc_html_e('Edit' ,'woocommerce-product-attachment'); ?></a></li>
                                <li><a class="-minus small wcpoa-js-tooltip" href="#" data-event="remove-row" title="<?php esc_attr_e('Remove','woocommerce-product-attachment'); ?>"><?php esc_html_e('Delete' ,'woocommerce-product-attachment'); ?></a></li>
                            </ul>
                        </div>
                        <div class="wcpoa-label not-mobile">
                            <label for="attchment_type"><?php esc_html_e('-', 'woocommerce-product-attachment');  ?></label>
                        </div>
                        <div class="wcpoa-label not-mobile">
                            <label for="attchment_visibility"><?php esc_html_e('-', 'woocommerce-product-attachment');  ?></label>
                        </div>
                        <div class="wcpoa-label not-mobile">
                            <label for="attchment_visibility"><?php esc_html_e('-', 'woocommerce-product-attachment');  ?></label>
                        </div>
                    </div>
                    <div class="wcpoa-field wcpoa-field-text wcpoa-field-id" data-name="id" data-type="text" data-key="">
                        <div class="wcpoa-label">
                            <label for=""><?php esc_html_e('Id','woocommerce-product-attachment') ?> </label>
                        </div>
                        <div class="wcpoa-input">
                            <div class="wcpoa-input-wrap">
                                <input readonly="" class="wcpoa_attachments_id" name="wcpoa_attachments_id[]" value="" placeholder="" type="text">
                                <span class="wcpoa-description-tooltip-icon"></span>
                                <p class="wcpoa-description"><?php esc_html_e('Attachments Id used to identify each product attachment.This value is automatically generated.','woocommerce-product-attachment') ?></p>
                            </div>
                        </div>
                    </div>
                    <div class="wcpoa-field" data-name="_name" data-type="text" data-key="">
                        <div class="wcpoa-label">
                            <label for="attchment_name"><?php esc_html_e('Name','woocommerce-product-attachment'); ?><span class="wcpoa-required"> *</span></label>
                        </div>
                        <div class="wcpoa-input wcpoa-att-name-parent">
                            <input class="wcpoa-attachment-name" type="text" name="wcpoa_attachment_name[]" placeholder="<?php esc_attr_e('Attachment', 'woocommerce-product-attachment'); ?>" value="" >
                            <span class="wcpoa-description-tooltip-icon"></span>
                            <p class="wcpoa-description"><?php esc_html_e('Enter a name for the attachment. It will be displayed on the front end next to the download/view button.', 'woocommerce-product-attachment') ?></p>
                        </div>
                    </div>
                    <div class="wcpoa-field wcpoa-field-select">
                        <div class="wcpoa-label"> 
                            <label for="wcpoa_attach_view"><?php esc_html_e('Status','woocommerce-product-attachment'); ?></label>
                        </div>

                        <div class="wcpoa-input wcpoa_attach_view">
                            <select name="wcpoa_attach_view[]" class="wcpoa_attach_view" data-type="" data-key="">
                                <option name="enable"  value="enable"><?php esc_html_e('Enable', 'woocommerce-product-attachment') ?></option>
                                <option name="disable"  value="disable" class=""><?php esc_html_e('Disable', 'woocommerce-product-attachment') ?></option>
                            </select>
                            <span class="wcpoa-description-tooltip-icon"></span>
                            <p class="wcpoa-description"><?php esc_html_e('Enable or Disable attachment using this option (This attachment will be visible to customers only if it is enabled).','woocommerce-product-attachment'); ?></p>
                        </div> 
                    </div>
                    <div class="wcpoa-field wcpoa-field-textarea " data-name="description" data-type="textarea" data-key="" data-required="1">
                        <div class="wcpoa-label">
                            <label for="attchment_desc"><?php esc_html_e('Description','woocommerce-product-attachment'); ?></label>
                        </div>
                        <div class="wcpoa-input">
                            <textarea class="" name="wcpoa_attachment_description[]" placeholder="<?php esc_attr_e('Enter a description', 'woocommerce-product-attachment'); ?>" rows="8"></textarea>
                            <span class="wcpoa-description-tooltip-icon"></span>
                            <p class="wcpoa-description"><?php esc_html_e('You can type a short description of the attachment file. So customers will get details about the attachment file.', 'woocommerce-product-attachment') ?></p>
                        </div>
                    </div>
                    <div class="wcpoa-field wcpoa-field-select">
                        <div class="wcpoa-label">
                            <label for="wcpoa_attach_type"><?php esc_html_e('Attachment Type','woocommerce-product-attachment'); ?></label>
                        </div>

                        <div class="wcpoa-input wcpoa_attach_type">
                            <select name="wcpoa_attach_type[]" class="wcpoa_attach_type_list" data-type="" data-key="">
                                <option name="file_upload"  value="file_upload"><?php esc_html_e('File Upload', 'woocommerce-product-attachment') ?></option>
                                <option value="external_ulr"><?php esc_html_e('External URL 🔒', 'woocommerce-product-attachment') ?></option>
                            </select>
                            <span class="wcpoa-description-tooltip-icon"></span>
                            <p class="wcpoa-description"><?php esc_html_e('Select the attachment type. Like Upload file / External URL','woocommerce-product-attachment'); ?></p>
                        </div>
                    </div>
                    <div class="wcpoa-field file_upload wcpoa-field-file required" data-name="file" data-type="file" data-key="" data-required="1">
                        <div class="wcpoa-label">
                            <label for=""><?php esc_html_e('Upload Attachment File','woocommerce-product-attachment'); ?>
                                <span class="wcpoa-required"> *</span></label>
                        </div>
                        <div class="wcpoa-input">

                            <div class="wcpoa-file-uploader wcpoa-cf">
                                <div class="wcpoa-error-message"><p><?php echo 'File value is required'; ?></p>
                                    <input name="wcpoa_attachment_file[]"  data-name="id" type="hidden" required="required">
                                </div>    
                                <div class="show-if-value file-wrap wcpoa-soh">
                                    <div class="file-icon">
                                        <img data-name="icon" src="<?php echo esc_url($plugin_url).'admin/images/default.png';?>" alt="" title="">
                                    </div>
                                    <div class="file-info">
                                        <p>
                                            <strong data-name="title"></strong>
                                        </p>
                                        <p>
                                            <strong><?php esc_html_e('File name', 'woocommerce-product-attachment'); ?>:</strong>
                                            <a data-name="filename" href="" target="_blank"></a>
                                        </p>
                                        <p>
                                            <strong><?php esc_html_e('File size', 'woocommerce-product-attachment'); ?>:</strong>
                                            <span data-name="filesize"></span>
                                        </p>

                                        <ul class="wcpoa-hl wcpoa-soh-target">
                                            <li><a class="wcpoa-icon -pencil dark" data-name="edit" href="#"></a></li>
                                            <li><a class="wcpoa-icon -cancel dark" data-name="remove" href="#"></a></li>
                                        </ul>
                                    </div>
                                </div>
                                <div class="hide-if-value">
                                    <?php echo wp_kses($this->wcpoa_image_uploader_field('test'),$this->allowed_html_tags()); ?>

                                </div>
                                <p  class="description"><?php esc_html_e('Select upload attachment File.', 'woocommerce-product-attachment') ?></p>
                            </div>
                        </div>
                    </div>
                    <div class="wcpoa-field">
                        <div class="wcpoa-label">
                            <label for="product_page_enable"><?php esc_html_e('Open in new window', 'woocommerce-product-attachment'); ?></label>
                        </div>
                        <div class="wcpoa-input">
                            <select id="wcpoa_product_open_window_flag"
                                    name="wcpoa_product_open_window_flag[]">
                                    <option name="no" value="no" selected><?php esc_html_e('No', 'woocommerce-product-attachment') ?></option>
                                    <option name="yes" value="yes"><?php esc_html_e('Yes', 'woocommerce-product-attachment') ?></option>
                            </select>
                            <span class="wcpoa-description-tooltip-icon"></span>
                            <p class="wcpoa-description"><?php esc_html_e('Select Link Behavior: Specify whether you want the attachment link to open in a new window or the same window.', 'woocommerce-product-attachment') ?></p> 
                        </div>
                    </div>
                    <!-- nirav code start --> 
                    <div class="wcpoa-field wcpoa-field-select" data-type="select">
                        <div class="wcpoa-label">
                            <label for="wcpoa_sel_product"><?php esc_html_e('Attachment For ', 'woocommerce-product-attachment'); ?></label>
                        </div>
                        <div class="wcpoa-input">
                            <select name="wcpoa_is_condition[]" class="is_condition_select" >
                                <option value="no" ><?php esc_html_e('All Product ', 'woocommerce-product-attachment'); ?></option>
                                <option value="yes"><?php esc_html_e('Specific Product, Category, Tags, Attributes 🔒', 'woocommerce-product-attachment'); ?><span class="wcpoa-pro-label wcpoa-pro-feature"></span></option>
                            </select>
                            <span class="wcpoa-description-tooltip-icon"></span>
                            <p class="wcpoa-description"><?php esc_html_e('Assign attachment for All products. Also, you can assign attachment for specific Product, Specific Category or a specific tags', 'woocommerce-product-attachment') ?></p>
                        </div>
                    </div> 

                    <div class="wcpoa-field">
                        <div class="wcpoa-label">
                        <label for="product_page_enable">
                            <?php esc_html_e('Attachment Visibility Pages','woocommerce-product-attachment'); ?></label>
                        </div>
                        <div class="wcpoa-input">
                            <select id="wcpoa_product_page_enable" name="wcpoa_att_visibility[]">
                                <option name="order_details_page"  value="order_details_page"><?php esc_html_e('Order Details Page','woocommerce-product-attachment') ?></option>
                                <option name="product_details_page"  value="product_details_page"><?php esc_html_e('Product Details Page','woocommerce-product-attachment') ?></option>
                                <option name="wcpoa_all"  value="wcpoa_all"><?php esc_html_e('Both','woocommerce-product-attachment') ?></option>                                          
                            </select>
                            <span class="wcpoa-description-tooltip-icon"></span>
                            <p class="wcpoa-description"><?php esc_html_e('Select where you want to showcase the attachment - Product Page, Order Page, or Both.','woocommerce-product-attachment'); ?></p>
                        </div>
                    </div>
                    <div class="wcpoa-field">
                        <div class="wcpoa-label">
                            <label for="product_page_enable"><?php esc_html_e('Show only for logged in users', 'woocommerce-product-attachment'); ?></label>
                        </div>
                        <div class="wcpoa-input">
                            <select id="wcpoa_product_logged_in_flag"
                                    name="wcpoa_product_logged_in_flag[]">
                                <option name="no" value="no"><?php esc_html_e('No','woocommerce-product-attachment') ?></option>
                                <option name="yes" value="yes"><?php esc_html_e('Yes','woocommerce-product-attachment') ?></option>
                            </select>
                            <span class="wcpoa-description-tooltip-icon"></span>
                            <p class="wcpoa-description"><?php esc_html_e('Select whether you want to display the attachment only for logged-in users or not.', 'woocommerce-product-attachment') ?></p> 
                        </div>
                    </div>
                    <div class="wcpoa-field">
                        <div class="wcpoa-label">
                            <label for="attchment_order_status">
                                <?php esc_html_e('Order status','woocommerce-product-attachment'); ?></label>
                        </div>
                        <div class="wcpoa-input">
                            <p class="description"><?php esc_html_e('Select order status, where you want to showcase attachment. Leave unselected then apply to all.','woocommerce-product-attachment'); ?></p>
                            <ul class="wcpoa-checkbox-list wcpoa-order-checkbox-list">
                                <li>
                                    <input name="wcpoa_order_status[][]" id="wcpoa_wc_order_completed" value="wcpoa-wc-completed" type="checkbox">
                                    <label for="wcpoa_wc_order_completed"><?php esc_html_e('Completed', 'woocommerce-product-attachment'); ?></label>
                                </li>
                                <li>
                                    <input name="wcpoa_order_status[][]" id="wcpoa_wc_order_on_hold" value="wcpoa-wc-on-hold" type="checkbox">
                                    <label for="wcpoa_wc_order_on_hold"><?php esc_html_e('On Hold', 'woocommerce-product-attachment'); ?></label>
                                </li>
                                <li>
                                    <input name="wcpoa_order_status[][]" id="wcpoa_wc_order_pending" value="wcpoa-wc-pending" type="checkbox">
                                    <label for="wcpoa_wc_order_pending"><?php esc_html_e('Pending payment', 'woocommerce-product-attachment'); ?></label>
                                </li>
                                <li>
                                    <input name="wcpoa_order_status[][]" id="wcpoa_wc_order_processing" value="wcpoa-wc-processing" type="checkbox">
                                    <label for="wcpoa_wc_order_processing"><?php esc_html_e('Processing', 'woocommerce-product-attachment'); ?></label>
                                </li>
                                <li>
                                    <input name="wcpoa_order_status[][]" id="wcpoa_wc_order_cancelled" value="wcpoa-wc-cancelled" type="checkbox">
                                    <label for="wcpoa_wc_order_cancelled"><?php esc_html_e('Cancelled', 'woocommerce-product-attachment'); ?></label>
                                </li>
                                <li>
                                    <input name="wcpoa_order_status[][]" id="wcpoa_wc_order_failed" value="wcpoa-wc-failed" type="checkbox">
                                    <label for="wcpoa_wc_order_failed"><?php esc_html_e('Failed', 'woocommerce-product-attachment'); ?></label>
                                </li>
                                <li>
                                    <input name="wcpoa_order_status[][]" id="wcpoa_wc_order_refunded" value="wcpoa-wc-refunded" type="checkbox">
                                    <label for="wcpoa_wc_order_refunded"><?php esc_html_e('Refunded', 'woocommerce-product-attachment'); ?></label>
                                </li>
                            </ul>
                        </div>
                    </div>

                    <div class="wcpoa-field">
                        <div class="wcpoa-label">
                            <label for="wcpoa_expired_date_enable"><?php esc_html_e('Set Expire date/time','woocommerce-product-attachment'); ?></label>
                        </div>
                        <div class="wcpoa-input enable_expire_date">
                            <select name="wcpoa_expired_date_enable[]" class="enable_date_time" data-type="enable_date_<?php echo esc_attr($wcpoa_attachments_id); ?>" data-key="">                       
                                <option name="no" value="no" class=""><?php esc_html_e('No','woocommerce-product-attachment') ?></option>
                                <option name="yes"  value="yes"><?php esc_html_e('Specific Date','woocommerce-product-attachment') ?></option>
                                <option value="time_amount"><?php esc_html_e('Selected time period after purchase 🔒','woocommerce-product-attachment') ?></option>
                            </select>
                            <span class="wcpoa-description-tooltip-icon"></span>
                            <p class="wcpoa-description"><?php esc_html_e('Set a specific date and specific time to access the attachment.','woocommerce-product-attachment'); ?></p>

                        </div>
                    </div>

                    <div style="display: none;" class="wcpoa-field enable_date enable_date_<?php echo esc_attr($wcpoa_attachments_id); ?> wcpoa-field-date-picker" data-name="date" data-type="date_picker" data-key="" data-required="1" style=''>
                        <div class="wcpoa-label">
                            <label for="wcpoa_expired_date"><?php esc_html_e('Specific Date','woocommerce-product-attachment'); ?></label>
                        </div>
                        <div class="wcpoa-input">
                            <div class="wcpoa-date-picker wcpoa-input-wrap" data-date_format="yy/mm/dd">
                                <input class="input wcpoa-php-date-picker" name="wcpoa_expired_date[]" placeholder="<?php echo esc_attr('yy/mm/dd'); ?>" type="text" autocomplete="off">
                                <span class="wcpoa-description-tooltip-icon"></span>
                                <p class="wcpoa-description"><?php esc_html_e('If an order is placed after the selected date, the attachments will be no longer visible for download. ( Date format: yy/mm/dd )','woocommerce-product-attachment') ?></p>
                            </div>
                        </div>

                    </div>
                    <?php echo wp_kses($after_fields,$this->allowed_html_tags()); ?>
                    <?php if ($show_remove): ?>
                        <td class="wcpoa-row-handle remove">
                            <a class="wcpoa-icon -plus small wcpoa-js-tooltip" href="#" data-event="add-row" title="<?php esc_attr_e('Add row','woocommerce-product-attachment'); ?>"></a>
                            <a class="wcpoa-icon -minus small wcpoa-js-tooltip" href="#" data-event="remove-row" title="<?php esc_attr_e('Remove row','woocommerce-product-attachment'); ?>"></a>
                        </td>
                    <?php endif; ?>

                </tr>
            </tbody>
        </table>
        <?php if ($show_add): ?>

            <ul class="wcpoa-actions wcpoa-hl">
                <li id="publishing-action">
                    <!--<span class="spinner"></span>-->
                    <input type="submit" accesskey="p" value="Save Changes"
                           class="button button-primary button-large" id="publish" name="submitwcpoabulkatt">
                </li>
                <?php 
                if($wcpoa_bulk_att_data) { ?>
                    <li class="wcpoa-add-bulk-attach wcpoa-in-pro-button">
                        <a class="button button-secondary"><?php esc_html_e('Add Unlimited Attachment', 'woocommerce-product-attachment') ?><span class="wcpoa-pro-label"></span></a>
                    </li>
                <?php    
                }else{ ?>
                    <li class="wcpoa-add-bulk-attach">
                        <a class="wcpoa-button button button-secondary"data-event="add-row"><?php esc_html_e('Add New Attachment', 'woocommerce-product-attachment') ?></a>
                    </li>
                <?php    
                }
                ?>
            </ul>
        <?php endif; ?>
    </div>
    </div>
<!--File validation-->

<!--End file validation-->
