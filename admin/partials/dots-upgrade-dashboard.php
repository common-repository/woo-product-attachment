<?php
/**
 * Handles free plugin user dashboard
 * 
 * @package Woocommerce_Product_Attachment
 * @since   2.2.0
 */

// If this file is called directly, abort.
if (!defined('WPINC')) {
    die;
}

require_once( plugin_dir_path( __FILE__ ) . 'header/plugin-header.php' );

// Get product details from Freemius via API
$annual_plugin_price = '';
$monthly_plugin_price = '';
$plugin_details = array(
    'product_id' => 43545,
);

$api_url = add_query_arg(wp_rand(), '', WCPOA_STORE_URL . 'wp-json/dotstore-product-fs-data/v2/dotstore-product-fs-data');
$final_api_url = add_query_arg($plugin_details, $api_url);

if ( function_exists( 'vip_safe_wp_remote_get' ) ) {
    $api_response = vip_safe_wp_remote_get( $final_api_url, 3, 1, 20 );
} else {
    $api_response = wp_remote_get( $final_api_url ); // phpcs:ignore
}

if ( ( !is_wp_error($api_response)) && (200 === wp_remote_retrieve_response_code( $api_response ) ) ) {
	$api_response_body = wp_remote_retrieve_body($api_response);
	$plugin_pricing = json_decode( $api_response_body, true );

	if ( isset( $plugin_pricing ) && ! empty( $plugin_pricing ) ) {
		$first_element = reset( $plugin_pricing );
        if ( ! empty( $first_element['price_data'] ) ) {
            $first_price = reset( $first_element['price_data'] )['annual_price'];
        } else {
            $first_price = "0";
        }

        if( "0" !== $first_price ){
        	$annual_plugin_price = $first_price;
        	$monthly_plugin_price = round( intval( $first_price  ) / 12 );
        }
	}
}

// Set plugin key features content
$plugin_key_features = array(
    array(
        'title' => esc_html__( 'Bulk Attachment', 'woocommerce-product-attachment' ),
        'description' => esc_html__( 'Easily attach various general information, such as instructions, certificates, and user guides, to your products with the flexibility to add multiple bulk attachments.', 'woocommerce-product-attachment' ),
        'popup_image' => esc_url( WCPOA_PLUGIN_URL . 'admin/images/pro-features-img/feature-box-one-img.png' ),
        'popup_content' => array(
        	esc_html__( 'Easily add general information to products with multiple bulk attachments, empowering customers with complete details for confident purchases.', 'woocommerce-product-attachment' )
        ),
        'popup_examples' => array(
            esc_html__( 'Attach detailed user guides and manuals to help customers understand product features and functionalities.', 'woocommerce-product-attachment' ),
            esc_html__( 'Enhance product transparency by attaching relevant instructions and warranty details for informed purchases.', 'woocommerce-product-attachment' )
        )
    ),
    array(
        'title' => esc_html__( 'Attachment Import', 'woocommerce-product-attachment' ),
        'description' => esc_html__( 'Easily import product attachments in bulk. It provides a convenient bulk product attachment importer based on the products\' SKUs.', 'woocommerce-product-attachment' ),
        'popup_image' => esc_url( WCPOA_PLUGIN_URL . 'admin/images/pro-features-img/feature-box-two-img.png' ),
        'popup_content' => array(
        	esc_html__( 'Effortlessly import product attachments in bulk using our convenient bulk product attachment importer, making it easy to manage attachments for multiple products at once.', 'woocommerce-product-attachment' ),
        ),
        'popup_examples' => array(
            esc_html__( 'Import return and exchange policies for products with related SKUs for efficient documentation management.', 'woocommerce-product-attachment' ),
            esc_html__( 'Attach product datasheets in bulk, ensuring customers can access detailed technical information for informed purchases.', 'woocommerce-product-attachment' )
        )
    ),
    array(
        'title' => esc_html__( 'Attach External URL', 'woocommerce-product-attachment' ),
        'description' => esc_html__( 'Easily add external site URLs as attachments to your product pages. Attach references like PDF files, YouTube videos, and more for additional info.', 'woocommerce-product-attachment' ),
        'popup_image' => esc_url( WCPOA_PLUGIN_URL . 'admin/images/pro-features-img/feature-box-three-img.png' ),
        'popup_content' => array(
        	esc_html__( 'Enable the attachment of external site URLs to your product pages.', 'woocommerce-product-attachment' ),
        	esc_html__( 'You can add references such as PDF files, YouTube videos, and other external content to provide additional information to your customers.', 'woocommerce-product-attachment' ),
        ),
        'popup_examples' => array(
            esc_html__( 'Include a YouTube video demonstration to showcase product features and benefits.', 'woocommerce-product-attachment' ),
            esc_html__( 'Attach the external site\'s PDF file to help customers understand product usage better.', 'woocommerce-product-attachment' )
        )
    ),
    array(
        'title' => esc_html__( 'Video Attachments', 'woocommerce-product-attachment' ),
        'description' => esc_html__( 'Display YouTube video links as attachments in a new tab for a better user experience. Easily set all YouTube video links to open in a new tab.', 'woocommerce-product-attachment' ),
        'popup_image' => esc_url( WCPOA_PLUGIN_URL . 'admin/images/pro-features-img/feature-box-four-img.png' ),
        'popup_content' => array(
        	esc_html__( 'Improve the user experience by displaying YouTube video links as attachments in a new tab.', 'woocommerce-product-attachment' ),
        	esc_html__( 'With this feature, all YouTube videos will open in new tabs, ensuring a seamless browsing experience for your customers.', 'woocommerce-product-attachment' ),
        ),
        'popup_examples' => array(
            esc_html__( 'Attach a helpful style tips and care instructions video for customers.', 'woocommerce-product-attachment' ),
            esc_html__( 'Add a promotional video to showcase the product\'s unique features in a separate tab.', 'woocommerce-product-attachment' )
        )
    ),
    array(
        'title' => esc_html__( 'Custom Icons', 'woocommerce-product-attachment' ),
        'description' => esc_html__( 'Add a touch of personalization to your attachments by choosing custom icons. Select from a range of default icons or upload your custom icons.', 'woocommerce-product-attachment' ),
        'popup_image' => esc_url( WCPOA_PLUGIN_URL . 'admin/images/pro-features-img/feature-box-five-img.png' ),
        'popup_content' => array(
        	esc_html__( 'Personalize your attachments with custom icons. Choose from a variety of default icons or upload your own to add a unique touch to your product pages.', 'woocommerce-product-attachment' ),
        ),
        'popup_examples' => array(
            esc_html__( 'Use a shopping bag icon for product manuals to indicate downloadable documents.', 'woocommerce-product-attachment' ),
            esc_html__( 'Upload your brand logo as a custom icon to add a professional touch to all attachments.', 'woocommerce-product-attachment' )
        )
    ),
    array(
        'title' => esc_html__( 'User Attachments', 'woocommerce-product-attachment' ),
        'description' => esc_html__( 'Enable users to upload files during checkout, allowing them to add screenshots, pictures, or important documents like licenses or prescriptions.', 'woocommerce-product-attachment' ),
        'popup_image' => esc_url( WCPOA_PLUGIN_URL . 'admin/images/pro-features-img/feature-box-six-img.png' ),
        'popup_content' => array(
        	esc_html__( 'Empower your customers to upload files during checkout, making it convenient for them to include screenshots, images, or essential documents such as licenses and prescriptions.', 'woocommerce-product-attachment' ),
        ),
        'popup_examples' => array(
            esc_html__( 'Allow customers to upload images of custom design preferences when ordering personalized products.', 'woocommerce-product-attachment' ),
            esc_html__( 'Allow customers to attach proof of eligibility for discounts or special offers during checkout.', 'woocommerce-product-attachment' )
        )
    ),
    array(
        'title' => esc_html__( 'Order Attachments', 'woocommerce-product-attachment' ),
        'description' => esc_html__( 'Admin can upload attachments visible to customers in order emails. You can include crucial documents or discount offers upon purchase completion.', 'woocommerce-product-attachment' ),
        'popup_image' => esc_url( WCPOA_PLUGIN_URL . 'admin/images/pro-features-img/feature-box-seven-img.png' ),
        'popup_content' => array(
        	esc_html__( 'Enhance order communication by attaching important documents or exclusive offers to order completion emails.', 'woocommerce-product-attachment' ),
        	esc_html__( 'Admins can upload attachments that customers can access upon purchase completion.', 'woocommerce-product-attachment' ),
        ),
        'popup_examples' => array(
            esc_html__( 'Send a thank-you note and a discount coupon code as an attachment to encourage repeat purchases.', 'woocommerce-product-attachment' ),
            esc_html__( 'Include warranty information and product care instructions as attachments in order emails.', 'woocommerce-product-attachment' )
        )
    ),
    array(
        'title' => esc_html__( 'Customized Email Attachments', 'woocommerce-product-attachment' ),
        'description' => esc_html__( 'Effortlessly attach files to specific status-based emails, providing customers with streamlined visibility and relevant information based on their order status.', 'woocommerce-product-attachment' ),
        'popup_image' => esc_url( WCPOA_PLUGIN_URL . 'admin/images/pro-features-img/feature-box-eight-img.png' ),
        'popup_content' => array(
        	esc_html__( 'Enhance order transparency and simplify customer communication by providing relevant information, such as invoices or shipping details, based on their order status.', 'woocommerce-product-attachment' ),
        ),
        'popup_examples' => array(
            esc_html__( 'Attach order invoices to "Order Complete" emails, allowing customers to review and track their purchases conveniently.', 'woocommerce-product-attachment' ),
            esc_html__( 'Send order confirmation emails with attached product manuals to give customers essential information upon purchase.', 'woocommerce-product-attachment' )
        )
    ),
    array(
        'title' => esc_html__( 'Attachments In Download Tab', 'woocommerce-product-attachment' ),
        'description' => esc_html__( 'Enable the display of attachments in the download tab of your customers\' store accounts, providing easy access for multiple viewings.', 'woocommerce-product-attachment' ),
        'popup_image' => esc_url( WCPOA_PLUGIN_URL . 'admin/images/pro-features-img/feature-box-nine-img.png' ),
        'popup_content' => array(
        	esc_html__( 'Enhance customer convenience by displaying attachments in the download tab of their store accounts.', 'woocommerce-product-attachment' ),
        	esc_html__( 'Allow easy access to essential files, such as user manuals or product warranties.', 'woocommerce-product-attachment' ),
        ),
        'popup_examples' => array(
            esc_html__( 'Allow customers to access product user guides and warranties from the download tab for future reference.', 'woocommerce-product-attachment' ),
            esc_html__( 'Enable quick access to digital certificates and licenses for customers in the download tab.', 'woocommerce-product-attachment' )
        )
    ),
);
?>
<div class="dotstore-upgrade-dashboard">
	<div class="premium-benefits-section">
		<h2><?php esc_html_e( 'Upgrade to Unlock Premium Features', 'woocommerce-product-attachment' ); ?></h2>
		<p><?php esc_html_e( 'Check out the advanced features, simplify product management & reduce returns by upgrading to premium!', 'woocommerce-product-attachment' ); ?></p>
	</div>
	<div class="premium-plugin-details">
		<div class="premium-key-fetures">
			<h3><?php esc_html_e( 'Discover Our Top Key Features', 'woocommerce-product-attachment' ) ?></h3>
			<ul>
				<?php 
				if ( isset( $plugin_key_features ) && ! empty( $plugin_key_features ) ) {
					foreach( $plugin_key_features as $key_feature ) {
						?>
						<li>
							<h4><?php echo esc_html( $key_feature['title'] ); ?><span class="premium-feature-popup"></span></h4>
							<p><?php echo esc_html( $key_feature['description'] ); ?></p>
							<div class="feature-explanation-popup-main">
								<div class="feature-explanation-popup-outer">
									<div class="feature-explanation-popup-inner">
										<div class="feature-explanation-popup">
											<span class="dashicons dashicons-no-alt popup-close-btn" title="<?php esc_attr_e('Close', 'woocommerce-product-attachment'); ?>"></span>
											<div class="popup-body-content">
												<div class="feature-content">
													<h4><?php echo esc_html( $key_feature['title'] ); ?></h4>
													<?php 
													if ( isset( $key_feature['popup_content'] ) && ! empty( $key_feature['popup_content'] ) ) {
														foreach( $key_feature['popup_content'] as $feature_content ) {
															?>
															<p><?php echo esc_html( $feature_content ); ?></p>
															<?php
														}
													}
													?>
													<ul>
														<?php 
														if ( isset( $key_feature['popup_examples'] ) && ! empty( $key_feature['popup_examples'] ) ) {
															foreach( $key_feature['popup_examples'] as $feature_example ) {
																?>
																<li><?php echo esc_html( $feature_example ); ?></li>
																<?php
															}
														}
														?>
													</ul>
												</div>
												<div class="feature-image">
													<img src="<?php echo esc_url( $key_feature['popup_image'] ); ?>" alt="<?php echo esc_attr( $key_feature['title'] ); ?>">
												</div>
											</div>
										</div>		
									</div>
								</div>
							</div>
						</li>
						<?php
					}
				}
				?>
			</ul>
		</div>
		<div class="premium-plugin-buy">
			<div class="premium-buy-price-box">
				<div class="price-box-top">
					<div class="pricing-icon">
						<img src="<?php echo esc_url( WCPOA_PLUGIN_URL . 'admin/images/premium-upgrade-img/pricing-1.svg' ); ?>" alt="<?php esc_attr_e( 'Personal Plan', 'woocommerce-product-attachment' ); ?>">
					</div>
					<h4><?php esc_html_e( 'Personal', 'woocommerce-product-attachment' ) ?></h4>
				</div>
				<div class="price-box-middle">
					<?php
					if ( ! empty( $annual_plugin_price ) ) {
						?>
						<div class="monthly-price-wrap"><?php echo esc_html( '$' . $monthly_plugin_price ) ?><span class="seprater">/</span><span><?php esc_html_e( 'month', 'woocommerce-product-attachment' ) ?></span></div>
						<div class="yearly-price-wrap"><?php echo sprintf( esc_html__( 'Pay $%s today. Renews in 12 months.', 'woocommerce-product-attachment' ), esc_html( $annual_plugin_price ) ); ?></div>
						<?php	
					}
					?>
					<span class="for-site"><?php esc_html_e( '1 site', 'woocommerce-product-attachment' ) ?></span>
					<p class="price-desc"><?php esc_html_e( 'Great for website owners with a single WooCommerce Store', 'woocommerce-product-attachment' ) ?></p>
				</div>
				<div class="price-box-bottom">
					<a href="javascript:void(0);" class="upgrade-now"><?php esc_html_e( 'Get The Premium Version', 'woocommerce-product-attachment' ) ?></a>
					<p class="trusted-by"><?php esc_html_e( 'Trusted by 100,000+ store owners and WP experts!', 'woocommerce-product-attachment' ) ?></p>
				</div>
			</div>
			<div class="premium-satisfaction-guarantee premium-satisfaction-guarantee-2">
				<div class="money-back-img">
					<img src="<?php echo esc_url(WCPOA_PLUGIN_URL . 'admin/images/premium-upgrade-img/14-Days-Money-Back-Guarantee.png'); ?>" alt="<?php esc_attr_e('14-Day money-back guarantee', 'woocommerce-product-attachment'); ?>">
				</div>
				<div class="money-back-content">
					<h2><?php esc_html_e( '14-Day Satisfaction Guarantee', 'woocommerce-product-attachment' ) ?></h2>
					<p><?php esc_html_e( 'You are fully protected by our 100% Satisfaction Guarantee. If over the next 14 days you are unhappy with our plugin or have an issue that we are unable to resolve, we\'ll happily consider offering a 100% refund of your money.', 'woocommerce-product-attachment' ); ?></p>
				</div>
			</div>
			<div class="plugin-customer-review">
				<h3><?php esc_html_e( 'Excelent Plugin, Fantastic Support', 'woocommerce-product-attachment' ) ?></h3>
				<p><?php esc_html_e( 'I chose this plugin over others for its fantastic support. The team assisted me with customization and even provided free services upon request. Highly recommended!', 'woocommerce-product-attachment' ) ?></p>
				<span><?php esc_html_e( 'Ali Husnain Arshad', 'woocommerce-product-attachment' ) ?></span>
				<div class="customer-rating-bottom">
					<div class="customer-ratings">
						<span class="dashicons dashicons-star-filled"></span>
						<span class="dashicons dashicons-star-filled"></span>
						<span class="dashicons dashicons-star-filled"></span>
						<span class="dashicons dashicons-star-filled"></span>
						<span class="dashicons dashicons-star-filled"></span>
					</div>
					<div class="verified-customer">
						<span class="dashicons dashicons-yes-alt"></span>
						<?php esc_html_e( 'Verified Customer', 'woocommerce-product-attachment' ) ?>
					</div>
				</div>
			</div>
		</div>
	</div>
	<div class="upgrade-to-pro-faqs">
		<h2><?php esc_html_e( 'FAQs', 'woocommerce-product-attachment' ); ?></h2>
		<div class="upgrade-faqs-main">
			<div class="upgrade-faqs-list">
				<div class="upgrade-faqs-header">
					<h3><?php esc_html_e( 'Do you offer support for the plugin? What’s it like?', 'woocommerce-product-attachment' ); ?></h3>
				</div>
				<div class="upgrade-faqs-body">
					<p>
					<?php 
						echo sprintf(
						    esc_html__('Yes! You can read our %s or submit a %s. We are very responsive and strive to do our best to help you.', 'woocommerce-product-attachment'),
						    '<a href="' . esc_url('https://docs.thedotstore.com/collection/349-product-attachment') . '" target="_blank">' . esc_html__('knowledge base', 'woocommerce-product-attachment') . '</a>',
						    '<a href="' . esc_url('https://www.thedotstore.com/support-ticket/') . '" target="_blank">' . esc_html__('support ticket', 'woocommerce-product-attachment') . '</a>',
						);

					?>
					</p>
				</div>
			</div>
			<div class="upgrade-faqs-list">
				<div class="upgrade-faqs-header">
					<h3><?php esc_html_e( 'What payment methods do you accept?', 'woocommerce-product-attachment' ); ?></h3>
				</div>
				<div class="upgrade-faqs-body">
					<p><?php esc_html_e( 'You can pay with your credit card using Stripe checkout. Or your PayPal account.', 'woocommerce-product-attachment' ) ?></p>
				</div>
			</div>
			<div class="upgrade-faqs-list">
				<div class="upgrade-faqs-header">
					<h3><?php esc_html_e( 'What’s your refund policy?', 'woocommerce-product-attachment' ); ?></h3>
				</div>
				<div class="upgrade-faqs-body">
					<p><?php esc_html_e( 'We have a 14-day money-back guarantee.', 'woocommerce-product-attachment' ) ?></p>
				</div>
			</div>
			<div class="upgrade-faqs-list">
				<div class="upgrade-faqs-header">
					<h3><?php esc_html_e( 'I have more questions…', 'woocommerce-product-attachment' ); ?></h3>
				</div>
				<div class="upgrade-faqs-body">
					<p>
					<?php 
						echo sprintf(
						    esc_html__('No problem, we’re happy to help! Please reach out at %s.', 'woocommerce-product-attachment'),
						    '<a href="' . esc_url('mailto:hello@thedotstore.com') . '" target="_blank">' . esc_html('hello@thedotstore.com') . '</a>',
						);

					?>
					</p>
				</div>
			</div>
		</div>
	</div>
	<div class="upgrade-to-premium-btn">
		<a href="javascript:void(0);" target="_blank" class="upgrade-now"><?php esc_html_e( 'Get The Premium Version', 'woocommerce-product-attachment' ) ?><svg aria-hidden="true" focusable="false" data-prefix="fas" data-icon="crown" role="img" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 640 512" class="svg-inline--fa fa-crown fa-w-20 fa-3x" width="22" height="20"><path fill="#000" d="M528 448H112c-8.8 0-16 7.2-16 16v32c0 8.8 7.2 16 16 16h416c8.8 0 16-7.2 16-16v-32c0-8.8-7.2-16-16-16zm64-320c-26.5 0-48 21.5-48 48 0 7.1 1.6 13.7 4.4 19.8L476 239.2c-15.4 9.2-35.3 4-44.2-11.6L350.3 85C361 76.2 368 63 368 48c0-26.5-21.5-48-48-48s-48 21.5-48 48c0 15 7 28.2 17.7 37l-81.5 142.6c-8.9 15.6-28.9 20.8-44.2 11.6l-72.3-43.4c2.7-6 4.4-12.7 4.4-19.8 0-26.5-21.5-48-48-48S0 149.5 0 176s21.5 48 48 48c2.6 0 5.2-.4 7.7-.8L128 416h384l72.3-192.8c2.5.4 5.1.8 7.7.8 26.5 0 48-21.5 48-48s-21.5-48-48-48z" class=""></path></svg></a>
	</div>
</div>
<?php 
