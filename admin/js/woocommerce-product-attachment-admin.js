(function ($) {
    'use strict';

    var count=0;
    var bulkActionsPerformed = 'no';
    function select2object(ajaxtype){
        return {
         minimumInputLength: 3,
         placeholder: wcpoa_vars['select_' + ajaxtype],
          ajax: {
            url: ajaxurl,
            dataType: 'json',
            data: function (params) {
              var query = {
                action: ajaxtype+'_ajax',
                security: wcpoa_vars.wcpoa_nonce,
                search: params.term
              };

              // Query parameters will be ?search=[term]&page=[page]
              return query;
            }

          }
        };
    }
    jQuery(document).ready(function() {
        var plugin_page = jQuery('#wcpoa-plug-page').val();
        if( plugin_page === 'wcpoa_bulk_attachment' ){
            let bulk_attachment_action_msg = localStorage.getItem('wcpoa-bulk-save-action-msg');
            if( ( null !== bulk_attachment_action_msg || undefined !== bulk_attachment_action_msg ) ) {
                jQuery('.wcpoa-notice p').text(bulk_attachment_action_msg);
            }
        }

        var i=0;
        fileupload();
        delete_media();
        jQuery('#wcpoa-ui-tbody tr:not(:last) .productlist').select2(select2object('product'));
        jQuery('#wcpoa-ui-tbody tr:not(:last) .catlist').select2(select2object('category'));
        jQuery('#wcpoa-ui-tbody tr:not(:last) .taglist').select2(select2object('tag'));
        jQuery('#wcpoa-ui-tbody tr:not(:last) .attributes_list').select2(select2object('attributes'));
        customValidation();
        jQuery(document).on( 'click', '.wcpoa-notice .notice-dismiss', function() {
            jQuery('.wcpoa-notice').hide();
        });
        jQuery('body').on('click', '.wcpoa-button,.wcpoa-icon.-plus', function (e) {
            e.preventDefault();
            var trr= jQuery('.trr');
            jQuery('.wcpoa-not-found-bulk-attach').hide();
            jQuery('.wcpoa-add-bulk-attach').remove();
            localStorage.setItem('wcpoa-bulk-save-action-msg', wcpoa_vars.bulk_attachment_add);
            bulkActionsPerformed = 'yes';
            createAttachment(trr);
            count++;
            i++;
        });
        jQuery('.wcpoa_att_download_btn').click(function(){
            var buttontype=jQuery(this).val();
            if(buttontype === 'wcpoa_att_btn'){
                jQuery('.wcpoa_att_icon_file_selected').addClass('hide');
            } else{
               jQuery('.wcpoa_att_icon_file_selected').removeClass('hide'); 
            }
        });
        jQuery('body').on('change','.is_condition_select',function(){
            if(jQuery(this).val() === 'yes'){
                jQuery(this).parent().parent().parent().find('.is_condition').removeClass('hide');
            } else{
                jQuery(this).parent().parent().parent().find('.is_condition').addClass('hide');
            }
        });
        jQuery('body').on('focus','.wcpoa-php-date-picker', function(){
            jQuery(this).datepicker({ dateFormat: 'yy/mm/dd', minDate : 0 });
        });
        jQuery('body').on('change','.enable_date_time',function(){
            var att=jQuery(this).parent().parent().parent();
            if(jQuery(this).val() === 'yes'){
                jQuery(att).find('.enable_time').hide();    
                jQuery(att).find('.enable_date').show();
            } else if(jQuery(this).val() === 'time_amount'){
                jQuery(att).find('.enable_time').show();    
                jQuery(att).find('.enable_date').hide();
            } else{
                jQuery(att).find('.enable_time').hide();    
                jQuery(att).find('.enable_date').hide();
            }
        });
        jQuery('body').on('click','.attachment_action .-minus',function(){
            var element=jQuery(this).parent().parent().parent().parent().parent().parent();
            delete_row(element);
        });
        jQuery('body').on('click', '.attachment_name label', function(e){
            e.preventDefault();
            localStorage.setItem('wcpoa-bulk-save-action-msg', wcpoa_vars.bulk_attachment_edit);
            bulkActionsPerformed = 'yes';
            jQuery(this).parent().parent().parent().parent().toggleClass('-collapsed');
        });
        jQuery('body').on('click', '.edit_bulk_attach', function(e){
            e.preventDefault();
            localStorage.setItem('wcpoa-bulk-save-action-msg', wcpoa_vars.bulk_attachment_edit);
            bulkActionsPerformed = 'yes';
            jQuery(this).parent().parent().parent().parent().parent().parent().toggleClass('-collapsed');
        });

        jQuery( '.group-title,.wcpoa-icon.-collapse' ).hover(function() {
            jQuery( this ).find('.attachment_action').css('visibility', 'visible');
        },function(){
            jQuery( this ).find('.attachment_action').css('visibility', 'hidden');
        });

        /* description toggle */
        jQuery( '.wcpoa-description-tooltip-icon' ).click( function( e) {
            e.preventDefault();
            jQuery( this ).next( 'p.wcpoa-description' ).toggle();
        } );

        if ( bulkActionsPerformed === 'no' ) {
            localStorage.setItem('wcpoa-bulk-save-action-msg', wcpoa_vars.bulk_attachment_save);
        }
        
        /** Upgrade Dashboard Script START */
        // Dashboard features popup script
        $(document).on('click', '.dotstore-upgrade-dashboard .premium-key-fetures .premium-feature-popup', function (event) {
            let $trigger = $('.feature-explanation-popup, .feature-explanation-popup *');
            if(!$trigger.is(event.target) && $trigger.has(event.target).length === 0){
                $('.feature-explanation-popup-main').not($(this).find('.feature-explanation-popup-main')).hide();
                $(this).parents('li').find('.feature-explanation-popup-main').show();
                $('body').addClass('feature-explanation-popup-visible');
            }
        });
        $(document).on('click', '.dotstore-upgrade-dashboard .popup-close-btn', function () {
            $(this).parents('.feature-explanation-popup-main').hide();
            $('body').removeClass('feature-explanation-popup-visible');
        });
        /** Upgrade Dashboard Script End */

        /** Plugin Setup Wizard Script START */
        // Hide & show wizard steps based on the url params 
        var urlParams = new URLSearchParams(window.location.search);
        if (urlParams.has('require_license')) {
            $('.ds-plugin-setup-wizard-main .tab-panel').hide();
            $( '.ds-plugin-setup-wizard-main #step5' ).show();
        } else {
            $( '.ds-plugin-setup-wizard-main #step1' ).show();
        }
        
        // Plugin setup wizard steps script
        $(document).on('click', '.ds-plugin-setup-wizard-main .tab-panel .btn-primary:not(.ds-wizard-complete)', function () {
            var curruntStep = $(this).closest('.tab-panel').attr('id');
            var nextStep = 'step' + ( parseInt( curruntStep.slice(4,5) ) + 1 );

            if( 'step5' !== curruntStep ) {
                //Youtube videos stop on next step
                $('iframe[src*="https://www.youtube.com/embed/"]').each(function(){
                    $(this).attr('src', $(this).attr('src'));
                    return false;
                });

                // Hide/show wizard steps
                $( '#' + curruntStep ).hide();
                $( '#' + nextStep ).show();   
            }
        });

        // Get allow for marketing or not
        if ( $( '.ds-plugin-setup-wizard-main .ds_count_me_in' ).is( ':checked' ) ) {
            $('#fs_marketing_optin input[name="allow-marketing"][value="true"]').prop('checked', true);
        } else {
            $('#fs_marketing_optin input[name="allow-marketing"][value="false"]').prop('checked', true);
        }

        // Get allow for marketing or not on change     
        $(document).on( 'change', '.ds-plugin-setup-wizard-main .ds_count_me_in', function() {
            if ( this.checked ) {
                $('#fs_marketing_optin input[name="allow-marketing"][value="true"]').prop('checked', true);
            } else {
                $('#fs_marketing_optin input[name="allow-marketing"][value="false"]').prop('checked', true);
            }
        });

        // Complete setup wizard
        $(document).on( 'click', '.ds-plugin-setup-wizard-main .tab-panel .ds-wizard-complete', function() {
            if ( $( '.ds-plugin-setup-wizard-main .ds_count_me_in' ).is( ':checked' ) ) {
                $( '.fs-actions button'  ).trigger('click');
            } else {
                $('.fs-actions #skip_activation')[0].click();
            }
        });

        // Send setup wizard data on Ajax callback
        $(document).on( 'click', '.ds-plugin-setup-wizard-main .fs-actions button', function() {
            var wizardData = {
                'action': 'wcpoa_plugin_setup_wizard_submit',
                'survey_list': $('.ds-plugin-setup-wizard-main .ds-wizard-where-hear-select').val(),
                'nonce': wcpoa_vars.setup_wizard_ajax_nonce
            };

            $.ajax({
                url: wcpoa_vars.ajaxurl,
                data: wizardData,
                success: function ( success ) {
                    console.log(success);
                }
            });
        });
        /** Plugin Setup Wizard Script End */

        /** Dynamic Promotional Bar START */
        $(document).on('click', '.dpbpop-close', function () {
            var popupName       = $(this).attr('data-popup-name');
            setCookie( 'banner_' + popupName, 'yes', 60 * 24 * 7);
            $('.' + popupName).hide();
        });

        $(document).on('click', '.dpb-popup .dpb-popup-meta a', function () {
            var promotional_id = $(this).parents().find('.dpbpop-close').attr('data-bar-id');

            //Create a new Student object using the values from the textfields
            var apiData = {
                'bar_id' : promotional_id
            };

            $.ajax({
                type: 'POST',
                url: wcpoa_vars.dpb_api_url + 'wp-content/plugins/dots-dynamic-promotional-banner/bar-response.php',
                data: JSON.stringify(apiData),// now data come in this function
                dataType: 'json',
                cors: true,
                contentType:'application/json',
                
                success: function (data) {
                    console.log(data);
                },
                error: function () {
                }
             });
        });
        /** Dynamic Promotional Bar END */

        // Script for updagrade to pro modal
        $(document).on('click', '#dotsstoremain .wcpoa-pro-feature, .wcpoa-table-main .wcpoa-in-pro-button a, .wcpoa-table-main .wcpoa-pro-operations .button', function(){
            $('body').addClass('wcpoa-modal-visible');
        });

        $(document).on('click', '.upgrade-to-pro-modal-main .modal-close-btn', function(){
            $('body').removeClass('wcpoa-modal-visible');
        });


        /** Script for Freemius upgrade popup */
        $(document).on('click', '.dots-header .dots-upgrade-btn, .dotstore-upgrade-dashboard .upgrade-now', function(e){
            e.preventDefault();
            upgradeToProFreemius( '' );
        });
        $(document).on('click', '.upgrade-to-pro-modal-main .upgrade-now', function(e){
            e.preventDefault();
            $('body').removeClass('wcpoa-modal-visible');
            let couponCode = $('.upgrade-to-pro-discount-code').val();
            upgradeToProFreemius( couponCode );
        });

        premiumSelectShowPopup( '.wcpoa-bulk-attach-main .is_condition_select', 'yes' );
        premiumSelectShowPopup( '.wcpoa_attach_type_list', 'external_ulr' );
        premiumSelectShowPopup( '.wcpoa-field .enable_date_time', 'time_amount' );

        // Script for Beacon configuration
        var helpBeaconCookie = getCookie( 'wcpoa-help-beacon-hide' );
        if ( ! helpBeaconCookie && 'yes' === wcpoa_vars.is_plugin_page ) {
            Beacon('init', 'afe1c188-3c3b-4c5f-9dbd-87329301c920');
            Beacon('config', {
                display: {
                    style: 'icon',
                    iconImage: 'message',
                }
            });

            // Add plugin articles IDs to display in beacon
            Beacon('suggest', ['5fd06cb1388c5a0089e64e65', '5fcf7aa9471dc000c9affde3', '605b42a40ad05379b10d03ac', '5fcf7e05388c5a0089e649a2', '5fd062fe12909d16116e0130']);

            // Add custom close icon form beacon
            setTimeout(function() {
                if ( $( '.hsds-beacon .BeaconFabButtonFrame' ).length > 0 ) {
                    let newElement = document.createElement('span');
                    newElement.classList.add('dashicons', 'dashicons-no-alt', 'dots-beacon-close');
                    let container = document.getElementsByClassName('BeaconFabButtonFrame');
                    container[0].appendChild( newElement );
                }
            }, 3000);

            // Hide beacon
            $(document).on('click', '.dots-beacon-close', function(){
                Beacon('destroy');
                setCookie( 'wcpoa-help-beacon-hide' , 'true', 24 * 60 );
            });
        }
    });
    
    /** Script for Freemius upgrade popup */
    function upgradeToProFreemius( couponCode ) {
        let handler;
        handler = FS.Checkout.configure({
            plugin_id: '3473',
            plan_id: '5539',
            public_key:'pk_9edf804dccd14eabfd00ff503acaf',
            image: 'https://www.thedotstore.com/wp-content/uploads/sites/1417/2023/09/WooCommerce-Product-Attachment-Banner-1-New.png',
            coupon: couponCode,
        });
        handler.open({
            name: 'WooCommerce Product Attachment',
            subtitle: 'WooCommerce Product Attachment',
            licenses: jQuery('input[name="licence"]:checked').val(),
            purchaseCompleted: function( response ) {                            
                console.log (response);
            },
            success: function (response) {
                console.log (response);
            }
        });
    }

    // Script for Display upgrade pro popup on select premium option
    function premiumSelectShowPopup( option, value ) {
        // Script for hide row on change
        $(document).on('change', option, function(){
            if( value === $(this).val() ) {
                $(this).find(':selected').prop('selected', false);
                $('body').addClass('wcpoa-modal-visible');
            }
        });
    }

    // Set cookies
    function setCookie(name, value, minutes) {
        var expires = '';
        if (minutes) {
            var date = new Date();
            date.setTime(date.getTime() + (minutes * 60 * 1000));
            expires = '; expires=' + date.toUTCString();
        }
        document.cookie = name + '=' + (value || '') + expires + '; path=/';
    }

    // Get cookies
    function getCookie(name) {
        let nameEQ = name + '=';
        let ca = document.cookie.split(';');
        for (let i = 0; i < ca.length; i++) {
            let c = ca[i].trim();
            if (c.indexOf(nameEQ) === 0) {
                return c.substring(nameEQ.length, c.length);
            }
        }
        return null;
    }


    function customValidation(){
        jQuery('#post').submit(function(e){
            var isValid = true;

            jQuery('input.wcpoa-attachment-name').each(function() {
                if(!jQuery(this).parent().parent().parent().parent().hasClass('hidden')){
                    if(jQuery(this).val() === '' && jQuery(this).val().length < 1) {
                        jQuery(this).addClass('error');
                        isValid = false;
                    } else {
                       jQuery(this).removeClass('error');
                    }

                }
            });  
            jQuery('.wcpoa_attach_type_list').each(function(){
                if(!jQuery(this).parent().parent().parent().parent().hasClass('hidden')){
                    if(jQuery(this).val() === 'file_upload'){
                         if(jQuery(this).parent().parent().parent().find('.wcpoa-file-uploader input').val() === ''){
                                 isValid = false;
                                 jQuery(this).parent().parent().parent().find('.wcpoa-file-uploader input').addClass('error');
                                 jQuery(this).parent().parent().parent().find('.wcpoa-file-uploader .wcpoa-error-message').show();
                         } else {
                                 jQuery(this).parent().parent().parent().find('.wcpoa-file-uploader .wcpoa-error-message').hide();                                jQuery().hide();
                         } 
                    } else{
                        if(jQuery(this).parent().parent().parent().find('.wcpoa-attachment-url').val() === ''){
                                 isValid = false;
                                 jQuery(this).parent().parent().parent().find('.wcpoa-attachment-url').addClass('error');
                         } else {
                                jQuery(this).parent().parent().parent().find('.wcpoa-attachment-url').removeClass('error');
                         }     
                    }
                    
                }
            });

            jQuery('.enable_date_time').each(function(){
                if(!jQuery(this).parent().parent().parent().parent().hasClass('hidden')){
                    if(jQuery(this).val() === 'yes'){
                         if(jQuery(this).parent().parent().parent().find('input.wcpoa-php-date-picker').val() === ''){
                                 isValid = false;
                                 jQuery(this).parent().parent().parent().find('input.wcpoa-php-date-picker').addClass('error');
                            }  else{
                                 jQuery(this).parent().parent().parent().find('input.wcpoa-php-date-picker').removeClass('error');                              
                         } 
                    } else if(jQuery(this).val() === 'time_amount'){
                        if(jQuery(this).parent().parent().parent().find('input.wcpoa-attachment-_time-amount').val() === ''){
                                 isValid = false;
                                 jQuery(this).parent().parent().parent().find('input.wcpoa-attachment-_time-amount').addClass('error');
                         }  else{
                                jQuery(this).parent().parent().parent().find('input.wcpoa-attachment-_time-amount').removeClass('error');
                         }     
                    }
                    
                }
            });

            if(!isValid) {
                e.preventDefault();
                alert(wcpoa_vars.validation_msg);
            }
        });
    }

    function makeid(length) {
       var result           = '';
       var characters       = 'abcdefghijklmnopqrstuvwxyz0123456789';
       var charactersLength = characters.length;
       for ( var i = 0; i < length; i++ ) {
          result += characters.charAt(Math.floor(Math.random() * charactersLength));
       }
       return result;
    }

    function createAttachment(element){
        var cln = element[0].cloneNode(true);
        // Append the cloned <li> element to <ul> with id="myList1"
        var tbody=document.getElementById('wcpoa-ui-tbody');
        tbody.appendChild(cln);
        var last_attachment_id=makeid(13);
        jQuery('#wcpoa-ui-tbody tr:nth-last-child(2)').find('.wcpoa_attachments_id').val(last_attachment_id);
        jQuery('#wcpoa-ui-tbody tr:nth-last-child(2)').removeClass('hidden');
        jQuery('#wcpoa-ui-tbody tr:nth-last-child(2)').removeClass('trr');
        jQuery('#wcpoa-ui-tbody tr:nth-last-child(2)').attr('id',last_attachment_id);
        jQuery('#wcpoa-ui-tbody tr:nth-last-child(2)').attr('data-id',last_attachment_id);
        jQuery('#wcpoa-ui-tbody tr:nth-last-child(2) .wcpoa_upload_image_button').attr('data-id',last_attachment_id);
        jQuery('#wcpoa-ui-tbody tr:nth-last-child(2) .wcpoa_upload_image_button').attr('data-id',last_attachment_id);
        jQuery('#wcpoa-ui-tbody tr:nth-last-child(2) .wcpoa-icon.-pencil').attr('data-id',last_attachment_id);
        jQuery('#wcpoa-ui-tbody tr:nth-last-child(2) .wcpoa-icon.-cancel').attr('data-id',last_attachment_id);
        jQuery('#wcpoa-ui-tbody tr:nth-last-child(2) .productlist').attr('name','wcpoa_product_list['+last_attachment_id+'][]').select2(select2object('product'));
        jQuery('#wcpoa-ui-tbody tr:nth-last-child(2) .catlist').attr('name','wcpoa_category_list['+last_attachment_id+'][]').select2(select2object('category'));
        jQuery('#wcpoa-ui-tbody tr:nth-last-child(2) .taglist').attr('name','wcpoa_tag_list['+last_attachment_id+'][]').select2(select2object('tag'));
        jQuery('#wcpoa-ui-tbody tr:nth-last-child(2) .attributes_list').attr('name','wcpoa_attributes_list['+last_attachment_id+'][]').select2(select2object('attributes'));
        jQuery('#wcpoa-ui-tbody tr:nth-last-child(2) .wcpoa_product_variation input').attr('name','wcpoa_variation['+last_attachment_id+'][]');
        jQuery('#wcpoa-ui-tbody tr:nth-last-child(2) .wcpoa-order-checkbox-list input').attr('name','wcpoa_order_status['+last_attachment_id+'][]');

        var lasttr = jQuery('#wcpoa-ui-tbody tr:nth-last-child(3) .order span').get(0);
        if(lasttr !== undefined){
            var index = parseInt(lasttr.innerHTML)+1;
            var ono = jQuery('#wcpoa-ui-tbody tr:nth-last-child(2) .order span').get(0);
            ono.innerHTML = '';
            setText(ono,index);
            var ono_new = jQuery('#wcpoa-ui-tbody tr:nth-last-child(2) .group-title .order .attchment_order').get(0);
            if(ono_new !== undefined){
                ono_new.innerHTML = '';
                setText(ono_new,index);                
            }
        }
        customValidation();
        return;      
    }

    function setText(element,text){
        var text_node = document.createTextNode(text);
        element.appendChild(text_node);        
    }
    function delete_row(element){
        var con = confirm('Are you sure you want to delete.');
        if(con){
            element.remove();
            localStorage.setItem('wcpoa-bulk-save-action-msg', wcpoa_vars.bulk_attachment_delete);
            bulkActionsPerformed = 'yes';
        }
    }
    function delete_media(){
        jQuery('body').on('click', '.wcpoa-icon.-cancel', function(e){
            e.preventDefault();
            var element = jQuery('#'+jQuery(this).attr('data-id'));
            var con = confirm('Are you sure you want to delete.');
            if(con){
                element.find('.wcpoa-file-uploader input').val('');
                element.find('.wcpoa-file-uploader').removeClass('has-value');

            }
        });
    }

    function fileupload(){
        jQuery('body').on('click', '.wcpoa_upload_image_button, .file-info .wcpoa-icon.-pencil', function(e){
            e.preventDefault();
                var attachment_div=jQuery('#'+jQuery(this).attr('data-id')).find('.wcpoa-file-uploader');
                
                var custom_uploader = wp.media({
                    title: 'Insert file',
                    button: {
                        text: 'Use this file' // button label text
                    },
                    multiple: false
                }).on('select', function() { // it also has "open" and "close" events 
                    jQuery(attachment_div).addClass('has-value');
                    var attachment = custom_uploader.state().get('selection').first().toJSON();
                    jQuery(attachment_div).find('.wcpoa-error-message input').val(attachment.id);
                    jQuery(attachment_div).find('.file-info p:nth-child(1) strong').text(attachment.title);
                    jQuery(attachment_div).find('.file-info a').text(attachment.filename);
                    jQuery(attachment_div).find('.file-info span').text(attachment.size);
                })
                .open();
        });
        jQuery('body').on('click', '.misha_remove_image_button', function(){
            jQuery(this).hide().prev().val('').prev().addClass('button').html('Upload file');
            return false;
        });
    }
})(jQuery);
