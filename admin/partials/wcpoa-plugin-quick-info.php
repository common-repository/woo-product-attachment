<?php

// If this file is called directly, abort.
if ( !defined( 'WPINC' ) ) {
    die;
}
$plugin_name = WCPOA_PLUGIN_NAME;
$plugin_version = WCPOA_PLUGIN_VERSION;
?>

<div class="wcpoa-table-main res-cl wcpoa-details-table">
    <h2><?php 
esc_html_e( 'Introduction', 'woocommerce-product-attachment' );
?></h2>
    <table class="wcpoa-tableouter table-outer">
        <tbody>
            <tr>
                <td class="fr-1"><?php 
esc_html_e( 'Product Type', 'woocommerce-product-attachment' );
?></td>
                <td class="fr-2"><?php 
esc_html_e( 'WordPress Plugin', 'woocommerce-product-attachment' );
?></td>
            </tr>
            <tr>
                <td class="fr-1"><?php 
esc_html_e( 'Product Name', 'woocommerce-product-attachment' );
?></td>
                <td class="fr-2"><?php 
echo esc_html( $plugin_name );
?></td>
            </tr>
            <tr>
                <td class="fr-1"><?php 
esc_html_e( 'Installed Version', 'woocommerce-product-attachment' );
?></td>
                <td class="fr-2"><?php 
echo esc_html( $plugin_version );
?></td>
            </tr>
            <tr>
                <td class="fr-1"><?php 
esc_html_e( 'License & Terms of use', 'woocommerce-product-attachment' );
?></td>
                <td class="fr-2"><a href="<?php 
echo esc_url( 'https://www.thedotstore.com/terms-and-conditions/' );
?>"><?php 
esc_html_e( 'Click here', 'woocommerce-product-attachment' );
?></a> <?php 
esc_html_e( 'to view license and terms of use.', 'woocommerce-product-attachment' );
?></td>
            </tr>
            <tr>
                <td class="fr-1"><?php 
esc_html_e( 'Help & Support', 'woocommerce-product-attachment' );
?></td>
                <td class="fr-2">
                    <ul class="help-support">
                        <li><a target="_blank" href="<?php 
echo esc_url( site_url( 'wp-admin/admin.php?page=woocommerce_product_attachment&tab=wcpoa-plugin-getting-started' ) );
?>"><?php 
esc_html_e( 'Quick Start Guide', 'woocommerce-product-attachment' );
?></a></li>
                        <li><a target="_blank" href="<?php 
echo esc_url( 'https://docs.thedotstore.com/collection/349-product-attachment-for-woocommerce' );
?>"><?php 
esc_html_e( 'Documentation', 'woocommerce-product-attachment' );
?></a>
                        </li>
                        <li><a target="_blank" href="<?php 
echo esc_url( 'https://www.thedotstore.com/support/' );
?>"><?php 
esc_html_e( 'Support Forum', 'woocommerce-product-attachment' );
?></a></li>
                    </ul>
                </td>
            </tr>
            <tr>
                <td class="fr-1"><?php 
esc_html_e( 'Localization', 'woocommerce-product-attachment' );
?></td>
                <td class="fr-2"><?php 
esc_html_e( 'English, Spanish', 'woocommerce-product-attachment' );
?></td>
            </tr>
            <?php 
$pro_only = '<span class="wcpoa-pro-label"></span>';
?>
            <tr>
                <td class="fr-1"><label class="wcpoa-pro-feature"><?php 
esc_html_e( 'Custom template shortcode', 'woocommerce-product-attachment' );
echo wp_kses( $pro_only, $this->allowed_html_tags() );
?></label></td>
                <td class="fr-2">
                    <?php 
esc_html_e( '[display_attachments product_id=\'18\' hide_title=\'false\' hide_size_label=\'false\' hide_description=\'false\']', 'woocommerce-product-attachment' );
?>
                    <div style="margin-top:10px;"><strong><?php 
esc_html_e( 'Note:', 'woocommerce-product-attachment' );
?></strong> <?php 
esc_html_e( 'Empty product id will take default product.', 'woocommerce-product-attachment' );
?></div>
                </td>
            </tr>
            <tr>
                <td class="fr-1"><label class="wcpoa-pro-feature"><?php 
esc_html_e( 'Search attachments shortcode', 'woocommerce-product-attachment' );
echo wp_kses( $pro_only, $this->allowed_html_tags() );
?></label></td>
                <td class="fr-2">
                    <?php 
esc_html_e( '[attachments_search placeholder="Search by product, category, attachment name" noresult="No matching data found."]', 'woocommerce-product-attachment' );
?>
                </td>
            </tr>
        </tbody>
    </table>
</div>
