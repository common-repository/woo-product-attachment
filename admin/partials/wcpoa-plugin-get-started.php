<?php

// If this file is called directly, abort.
if ( !defined( 'WPINC' ) ) {
    die;
}
$plugin_url = esc_url( WCPOA_PLUGIN_URL );
$getting_Started_01 = WCPOA_PLUGIN_URL . 'admin/images/Getting_Started_01.png';
$getting_Started_02 = WCPOA_PLUGIN_URL . 'admin/images/Getting_Started_02.png';
$getting_Started_03 = WCPOA_PLUGIN_URL . 'admin/images/Getting_Started_03.png';
$getting_Started_04 = WCPOA_PLUGIN_URL . 'admin/images/Getting_Started_04.png';
$getting_Started_05 = WCPOA_PLUGIN_URL . 'admin/images/Getting_Started_05.png';
?>
<div class="wcpoa-table-main res-cl wcpoa-details-table">
    <h2><?php 
esc_html_e( 'Getting Started', 'woocommerce-product-attachment' );
?></h2>
    <table class="wcpoa-tableouter table-outer">
        <tbody>
            <tr>
                <td class="fr-2">
                    <p class="block textgetting"><?php 
esc_html_e( 'Enhance your customer experience of product pages with downloadable files, such as technical descriptions, certificates, and licenses, user guides, and manuals, etc. A plugin will help you to attach/ upload any kind of files (doc, jpg, videos, pdf) for a customer orders.', 'woocommerce-product-attachment' );
?></p>
                    <div class="quick-tour-video-guide">
                        <div class="quick-tour-video">
                            <iframe src="<?php 
echo esc_url( 'https://www.youtube.com/embed/A8eGbS4PrPQ' );
?>" title="<?php 
esc_attr_e( 'Quick Tour', 'woocommerce-product-attachment' );
?>" frameborder="0" allow="accelerometer; autoplay; clipboard-write; encrypted-media; gyroscope; picture-in-picture" allowfullscreen></iframe>
                        </div>
                    </div>
                    <p class="block textgetting">
                        <strong>Step 1: </strong><?php 
esc_html_e( 'Setting Page', 'woocommerce-product-attachment' );
?>
                        <span class="gettingstarted"><img style="border: 2px solid #e9e9e9;margin-top: 3%;" src="<?php 
echo esc_url( $getting_Started_01 );
?>"></span>
                    </p>
                    <p class="block textgetting">
                        <strong>Step 2: </strong><?php 
esc_html_e( 'Bulk Product Attachment', 'woocommerce-product-attachment' );
?>
                        <span class="gettingstarted"><img style="border: 2px solid #e9e9e9;margin-top: 3%;" src="<?php 
echo esc_url( $getting_Started_02 );
?>"></span>
                    </p>
                    <p class="block textgetting">
                        <strong>Step 3: </strong><?php 
esc_html_e( 'Single Product Attachment Admin Side', 'woocommerce-product-attachment' );
?>
                        <span class="gettingstarted"><img style="border: 2px solid #e9e9e9;margin-top: 3%;" src="<?php 
echo esc_url( $getting_Started_03 );
?>"></span>
                    </p>
                    <p class="block textgetting">
                        <strong>Step 4: </strong><?php 
esc_html_e( 'Single Product Attachment Front Side', 'woocommerce-product-attachment' );
?>
                        <span class="gettingstarted"><img style="border: 2px solid #e9e9e9;margin-top: 3%;" src="<?php 
echo esc_url( $getting_Started_04 );
?>"></span>
                    </p>
                    <p class="block textgetting">
                        <strong>Step 5: </strong><?php 
esc_html_e( 'Product Attachment Admin Side WooCommerce Order Page', 'woocommerce-product-attachment' );
?>
                        <span class="gettingstarted"><img style="border: 2px solid #e9e9e9;margin-top: 3%;" src="<?php 
echo esc_url( $getting_Started_05 );
?>"></span>
                    </p>

                </td>
            </tr>
        </tbody>
    </table>
</div>