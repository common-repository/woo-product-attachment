<?php

/**
 * The admin-specific functionality of the plugin.
 *
 * @link       http://www.multidots.com/
 * @since      1.0.0
 *
 * @package    Woocommerce_Product_Attachment
 * @subpackage Woocommerce_Product_Attachment/admin
 */
/**
 * The admin-specific functionality of the plugin.
 *
 * Defines the plugin name, version, and two examples hooks for how to
 * enqueue the admin-specific stylesheet and JavaScript.
 *
 * @package    Woocommerce_Product_Attachment
 * @subpackage Woocommerce_Product_Attachment/admin
 * @author     Multidots <inquiry@multidots.in>
 */
use Automattic\WooCommerce\Internal\DataStores\Orders\CustomOrdersTableController;
use Automattic\WooCommerce\Utilities\OrderUtil;
class Woocommerce_Product_Attachment_Admin {
    /**
     * The ID of this plugin.
     *
     * @since    1.0.0
     * @access   private
     * @var      string $plugin_name The ID of this plugin.
     */
    private $plugin_name;

    /**
     * The version of this plugin.
     *
     * @since    1.0.0
     * @access   private
     * @var      string $version The current version of this plugin.
     */
    private $version;

    /**
     * Initialize the class and set its properties.
     *
     * @since    1.0.0
     * @param      string $plugin_name The name of this plugin.
     * @param      string $version The version of this plugin.
     */
    public function __construct( $plugin_name, $version ) {
        $this->plugin_name = $plugin_name;
        $this->version = $version;
    }

    /**
     * Register the stylesheets for the admin area.
     *
     * @since    1.0.0
     */
    public function wcpoa_enqueue_styles() {
        /**
         * This function is provided for demonstration purposes only.
         *
         * An instance of this class should be passed to the run() function
         * defined in Woocommerce_Product_Attachment_Loader as all of the hooks are defined
         * in that particular class.
         *
         * The Woocommerce_Product_Attachment_Loader will then create the relationship
         * between the defined hooks and the functions defined in this
         * class.
         */
        $current_screen = get_current_screen();
        $post_type = $current_screen->post_type;
        $menu_page = filter_input( INPUT_GET, 'page', FILTER_SANITIZE_SPECIAL_CHARS );
        if ( isset( $menu_page ) && !empty( $menu_page ) && ($menu_page === "woocommerce_product_attachment" || $menu_page === "wcpoa_bulk_attachment" || $menu_page === "woocommerce_product_attachment-account") || !empty( $post_type ) && ($post_type === 'product' || $post_type === 'shop_order') ) {
            wp_enqueue_style( 'thickbox' );
            wp_enqueue_style(
                $this->plugin_name . '-font-awesome',
                plugin_dir_url( __FILE__ ) . 'css/font-awesome.min.css',
                array(),
                $this->version,
                'all'
            );
            wp_enqueue_style(
                $this->plugin_name . '-jquery-ui',
                plugin_dir_url( __FILE__ ) . 'css/jquery-ui.min.css',
                array(),
                $this->version,
                'all'
            );
            wp_enqueue_style(
                $this->plugin_name . '-main-jquery-ui',
                plugin_dir_url( __FILE__ ) . 'css/jquery-ui.css',
                array(),
                $this->version,
                'all'
            );
            wp_enqueue_style(
                $this->plugin_name . '-select2.min',
                plugin_dir_url( __FILE__ ) . 'css/select2.min.css',
                array(),
                $this->version,
                'all'
            );
            wp_enqueue_style(
                $this->plugin_name . '-wcpoa-main-style',
                plugin_dir_url( __FILE__ ) . 'css/style.css',
                array(),
                $this->version,
                'all'
            );
            wp_enqueue_style(
                $this->plugin_name,
                plugin_dir_url( __FILE__ ) . 'css/woocommerce-product-attachment-admin.css',
                array(),
                $this->version,
                'all'
            );
            wp_enqueue_style(
                $this->plugin_name . '-setup-wizard',
                plugin_dir_url( __FILE__ ) . 'css/plugin-setup-wizard.css',
                array(),
                $this->version,
                'all'
            );
            if ( isset( $post_type ) && ($post_type !== 'product' && $post_type !== 'shop_order') ) {
                wp_enqueue_style(
                    $this->plugin_name . '-plugin-new-style',
                    plugin_dir_url( __FILE__ ) . 'css/plugin-new-style.css',
                    array(),
                    $this->version,
                    'all'
                );
            }
            if ( !(wpap_fs()->is__premium_only() && wpap_fs()->can_use_premium_code()) ) {
                wp_enqueue_style(
                    $this->plugin_name . '-upgrade-dashboard',
                    plugin_dir_url( __FILE__ ) . 'css/upgrade-dashboard.css',
                    array(),
                    $this->version,
                    'all'
                );
            }
        }
    }

    /**
     * Register the JavaScript for the admin area.
     *
     * @since    1.0.0
     */
    public function wcpoa_enqueue_scripts() {
        /**
         * This function is provided for demonstration purposes only.
         *
         * An instance of this class should be passed to the run() function
         * defined in Woocommerce_Product_Attachment_Loader as all of the hooks are defined
         * in that particular class.
         *
         * The Woocommerce_Product_Attachment_Loader will then create the relationship
         * between the defined hooks and the functions defined in this
         * class.
         */
        $current_screen = get_current_screen();
        $post_type = $current_screen->post_type;
        $menu_page = filter_input( INPUT_GET, 'page', FILTER_SANITIZE_SPECIAL_CHARS );
        if ( isset( $menu_page ) && !empty( $menu_page ) && ($menu_page === "woocommerce_product_attachment" || $menu_page === "wcpoa_bulk_attachment" || $menu_page === "woocommerce_product_attachment-account") || !empty( $post_type ) && $post_type === 'product' ) {
            wp_enqueue_script( 'postbox' );
            wp_enqueue_script( 'jquery' );
            wp_enqueue_script( 'media-upload' );
            wp_enqueue_script( 'thickbox' );
            wp_enqueue_script( 'jquery-ui-core' );
            wp_enqueue_script( 'jquery-ui-datepicker' );
            wp_enqueue_media();
            wp_enqueue_script(
                $this->plugin_name . '-help-scout-beacon-js',
                plugin_dir_url( __FILE__ ) . 'js/help-scout-beacon.js',
                array('jquery'),
                $this->version,
                false
            );
            wp_enqueue_script(
                $this->plugin_name,
                plugin_dir_url( __FILE__ ) . 'js/woocommerce-product-attachment-admin.js',
                array('jquery'),
                $this->version,
                false
            );
            wp_enqueue_script(
                $this->plugin_name . '-select2_js',
                plugin_dir_url( __FILE__ ) . 'js/select2.full.min.js?ver=4.0.3',
                array('jquery'),
                '4.0.3',
                false
            );
            wp_enqueue_script(
                $this->plugin_name . '-datepicker',
                plugin_dir_url( __FILE__ ) . 'js/datepicker.min.js',
                array('jquery'),
                $this->version,
                false
            );
            wp_localize_script( $this->plugin_name, 'wcpoa_vars', array(
                'ajaxurl'                 => admin_url( 'admin-ajax.php' ),
                'wcpoa_nonce'             => wp_create_nonce( 'ajax_verification' ),
                'validation_msg'          => __( 'Please fill required fields in the WooCommerce Product Attachment section below.', 'woocommerce-product-attachment' ),
                'update_order'            => __( 'Update Order', 'woocommerce-product-attachment' ),
                'bulk_attachment_add'     => __( 'New bulk attachment successfully inserted.', 'woocommerce-product-attachment' ),
                'bulk_attachment_save'    => __( 'Bulk attachment successfully saved.', 'woocommerce-product-attachment' ),
                'bulk_attachment_edit'    => __( 'Bulk attachment successfully edited.', 'woocommerce-product-attachment' ),
                'bulk_attachment_delete'  => __( 'Bulk attachment deleted successfully.', 'woocommerce-product-attachment' ),
                'bulk_attachment_import'  => __( 'Bulk attachment imported successfully.', 'woocommerce-product-attachment' ),
                'bulk_attachment_order'   => __( 'Bulk attachment order changed successfully.', 'woocommerce-product-attachment' ),
                'dpb_api_url'             => WCPOA_STORE_URL,
                'setup_wizard_ajax_nonce' => wp_create_nonce( 'wizard_ajax_nonce' ),
                'select_product'          => __( 'Select a product', 'woocommerce-product-attachment' ),
                'select_category'         => __( 'Select a category', 'woocommerce-product-attachment' ),
                'select_tag'              => __( 'Select a tag', 'woocommerce-product-attachment' ),
                'select_attributes'       => __( 'Select an attribute', 'woocommerce-product-attachment' ),
                'is_plugin_page'          => ( isset( $post_type ) && ($post_type !== 'product' && $post_type !== 'shop_order') ? 'yes' : 'no' ),
            ) );
            // Freemius checkout popup library for upgrade
            if ( !(wpap_fs()->is__premium_only() && wpap_fs()->can_use_premium_code()) ) {
                wp_enqueue_script(
                    $this->plugin_name . 'freemius_pro',
                    'https://checkout.freemius.com/checkout.min.js',
                    array('jquery'),
                    $this->version,
                    true
                );
            }
        }
        if ( !empty( $post_type ) && $post_type === 'shop_order' ) {
        }
        if ( isset( $menu_page ) && !empty( $menu_page ) && $menu_page === "wcpoa_bulk_attachment" ) {
            wp_dequeue_script( 'wp-auth-check' );
        }
    }

    /**
     * Plugin activation redirection
     *
     * @since    1.0.0
     */
    public function wcpoa_welcome_plugin_screen_do_activation_redirect() {
        // if no activation redirect
        if ( !get_transient( '_welcome_screen_activation_redirect_data' ) ) {
            return;
        }
        // Delete the redirect transient
        delete_transient( '_welcome_screen_activation_redirect_data' );
        // if activating from network, or bulk
        $activate_multi = filter_input( INPUT_GET, 'activate-multi', FILTER_SANITIZE_SPECIAL_CHARS );
        if ( is_network_admin() || isset( $activate_multi ) ) {
            return;
        }
        // Redirect to extra cost welcome  page
        wp_safe_redirect( add_query_arg( array(
            'page' => 'woocommerce_product_attachment&tab=wcpoa-plugin-getting-started',
        ), admin_url( 'admin.php' ) ) );
        exit;
    }

    /**
     * Add plugin main menu
     *
     * @since    1.0.0
     */
    public function wcpoa_dot_store_menu() {
        global $GLOBALS;
        if ( empty( $GLOBALS['admin_page_hooks']['dots_store'] ) ) {
            add_menu_page(
                'Dotstore Plugins',
                'Dotstore Plugins',
                'null',
                'dots_store',
                array($this, 'dot_store_menu_page'),
                'dashicons-marker',
                25
            );
        }
        // Remove footer WP version
        $page = filter_input( INPUT_GET, 'page', FILTER_SANITIZE_FULL_SPECIAL_CHARS );
        if ( !empty( $page ) && (false !== strpos( $page, 'wcpoa' ) || false !== strpos( $page, 'woocommerce_product_attachment' )) ) {
            remove_filter( 'update_footer', 'core_update_footer' );
        }
    }

    /**
     * Add custom css for dotstore icon in admin area
     *
     * @since  1.1.3
     *
     */
    public function wcpoa_dot_store_icon_css() {
        echo '<style>
        .toplevel_page_dots_store .dashicons-marker::after{content:"";border:3px solid;position:absolute;top:14px;left:15px;border-radius:50%;opacity: 0.6;}
        li.toplevel_page_dots_store:hover .dashicons-marker::after,li.toplevel_page_dots_store.current .dashicons-marker::after{opacity: 1;}
        @media only screen and (max-width: 960px){
            .toplevel_page_dots_store .dashicons-marker::after{left:14px;}
        }
        </style>';
    }

    /**
     * WooCommerce Product Attachment menu add
     * 
     * @since    1.0.0
     */
    public function wcpoa_plugin_menu() {
        add_submenu_page(
            "dots_store",
            "Product Attachment",
            "Product Attachment",
            "manage_options",
            "woocommerce_product_attachment",
            array($this, "wcpoa_options_page")
        );
        add_submenu_page(
            "dots_store",
            'WooCommerce Product Bulk Attachment',
            'WooCommerce Product Bulk Attachment',
            'edit_posts',
            'wcpoa_bulk_attachment',
            array($this, "wcpoa_bulk_attachment")
        );
    }

    /**
     * Active menu class add
     * 
     * @since    1.0.0
     */
    public function wcpoa_free_active_menu() {
        $menu_page = filter_input( INPUT_GET, 'page', FILTER_SANITIZE_SPECIAL_CHARS );
        if ( isset( $menu_page ) && !empty( $menu_page ) && ($menu_page === "woocommerce_product_attachment" || $menu_page === "wcpoa_bulk_attachment" || $menu_page === "woocommerce_product_attachment-account") ) {
            ?>
            <script type="text/javascript">
            // add currunt menu class in main manu
            jQuery(window).load(function () {
                jQuery('a[href="admin.php?page=woocommerce_product_attachment"]').parents().addClass('current wp-has-current-submenu');
                jQuery('a[href="admin.php?page=woocommerce_product_attachment"]').addClass('current');
            });
            </script>
            <?php 
        }
    }

    /**
     * Remove Menu
     * 
     * @since    1.0.0
     */
    public function wcpoa_remove_admin_menus() {
        remove_submenu_page( 'dots_store', 'dots_store' );
        remove_submenu_page( 'dots_store', 'wcpoa_bulk_attachment' );
    }

    /**
     * Show admin footer review text.
     *
     * @since 1.3.0
     */
    public function wcpoa_admin_footer_review() {
        $url = '';
        $url = esc_url( 'https://wordpress.org/plugins/woo-product-attachment/#reviews' );
        echo sprintf( wp_kses( __( '<strong>We need your support</strong> to keep updating and improving the plugin. Please <a href="%1$s" target="_blank">help us by leaving a good review</a> :) Thanks!', 'woocommerce-product-attachment' ), array(
            'strong' => array(),
            'a'      => array(
                'href'   => array(),
                'target' => 'blank',
            ),
        ) ), esc_url( $url ) );
        return '';
    }

    /**
     * WooCommerce Product Attachment Option Page HTML
     *
     * @since    1.0.0
     */
    public function wcpoa_options_page() {
        require_once plugin_dir_path( __FILE__ ) . 'partials/header/plugin-header.php';
        $menu_tab = filter_input( INPUT_GET, 'tab', FILTER_SANITIZE_SPECIAL_CHARS );
        $wcpoa_attachment_tab = ( isset( $menu_tab ) && !empty( $menu_tab ) ? $menu_tab : '' );
        if ( !empty( $wcpoa_attachment_tab ) ) {
            if ( $wcpoa_attachment_tab === "wcpoa_plugin_setting_page" ) {
                self::wcpoa_setting_page();
            }
            if ( $wcpoa_attachment_tab === "wcpoa-plugin-getting-started" ) {
                self::wcpoa_plugin_get_started();
            }
            if ( $wcpoa_attachment_tab === "wcpoa-plugin-quick-info" ) {
                self::wcpoa_plugin_quick_info();
            }
            if ( $wcpoa_attachment_tab === "wcpoa-upgrade-dashboard" ) {
                self::wcpoa_plugin_upgrade_dashboard();
            }
        } else {
            self::wcpoa_setting_page();
        }
        ?>
        </div><!-- .wcpoa-section-left -->
        </div><!-- .dots-settings-inner-main -->
        </div><!-- .all-pad -->
        </div><!-- #dotsstoremain -->
        <?php 
    }

    /**
     * WooCommerce Product Attachment settings page
     *
     * @since    1.0.0
     */
    public function wcpoa_setting_page() {
        require_once plugin_dir_path( __FILE__ ) . 'partials/wcpoa-plugin-settings-page.php';
    }

    /**
     * Plugin Getting started
     * 
     * @since    1.0.0
     */
    function wcpoa_plugin_get_started() {
        require_once plugin_dir_path( __FILE__ ) . 'partials/wcpoa-plugin-get-started.php';
    }

    /**
     * Plugin Quick Information
     *
     * @since    1.0.0
     */
    function wcpoa_plugin_quick_info() {
        require_once plugin_dir_path( __FILE__ ) . 'partials/wcpoa-plugin-quick-info.php';
    }

    /**
     * Plugin Upgrade Dashoard
     *
     * @since    2.2.0
     */
    function wcpoa_plugin_upgrade_dashboard() {
        require_once plugin_dir_path( __FILE__ ) . 'partials/dots-upgrade-dashboard.php';
    }

    /**
     * Add attachment meta box
     *
     * @since    1.0.0
     * 
     * @param $post_type
     */
    public function wcpoa_add_meta_box( $post_type ) {
        global $post;
        if ( 'product' === $post_type ) {
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
            if ( !$_product->is_type( 'grouped' ) ) {
                add_meta_box(
                    'wcpoa_attachment',
                    __( 'WooCommerce Product Attachment', 'woocommerce-product-attachment' ),
                    array($this, 'wcpoa_attachment_product_page'),
                    $post_type,
                    'advanced',
                    'high'
                );
            }
        }
    }

    /**
     * Attachment attributes
     *
     * @since    1.0.0
     * 
     * @param $atts
     * @param $return
     */
    public function wcpoa_esc_attr( $atts, $return = true ) {
        // is string?
        if ( is_string( $atts ) ) {
            $atts = trim( $atts );
            return esc_attr( $atts );
        }
        // validate
        if ( empty( $atts ) ) {
            return '';
        }
        foreach ( $atts as $key => $value ) {
            return esc_html( $key ) . '="' . esc_attr( $value ) . '"';
        }
        return;
    }

    /**
     * Add metabox on product details page.
     * 
     * @since    1.0.0
     */
    public function wcpoa_attachment_product_page() {
        require_once plugin_dir_path( __FILE__ ) . 'partials/wcpoa-product-attachment-settings.php';
    }

    /**
     * Attachment attributes
     *
     * @since    1.0.0
     * 
     * @param $atts
     */
    public function wcpoa_esc_attr_e( $atts ) {
        echo wp_kses( $this->wcpoa_esc_attr( $atts ), $this->allowed_html_tags() );
    }

    /**
     * Save Meta for post types.
     *
     * @since 1.0.0
     * 
     * @param $product_id
     */
    public function wcpoa_attachment_meta_data( $product_id ) {
        if ( is_admin() ) {
            if ( !function_exists( 'get_current_screen' ) ) {
                //add this line
                return;
            }
            // add this line
            $screen = get_current_screen();
            if ( empty( $product_id ) || 'product' !== $screen->id ) {
                return;
            }
            // If this is an autosave, our form has not been submitted, so we don't want to do anything.
            if ( defined( 'DOING_AUTOSAVE' ) && DOING_AUTOSAVE ) {
                return;
            }
            $post_type = filter_input( INPUT_POST, 'post_type', FILTER_SANITIZE_SPECIAL_CHARS );
            // Check post type is product
            if ( isset( $post_type ) && 'product' === $post_type ) {
                $wcpoa_attachments_id = filter_input(
                    INPUT_POST,
                    'wcpoa_attachments_id',
                    FILTER_SANITIZE_SPECIAL_CHARS,
                    FILTER_REQUIRE_ARRAY
                );
                $wcpoa_attachments_id = ( !empty( $wcpoa_attachments_id ) && isset( $wcpoa_attachments_id ) ? $wcpoa_attachments_id : '' );
                update_post_meta( $product_id, 'wcpoa_attachments_id', $wcpoa_attachments_id );
                $wcpoa_attachment_name = filter_input(
                    INPUT_POST,
                    'wcpoa_attachment_name',
                    FILTER_SANITIZE_SPECIAL_CHARS,
                    FILTER_REQUIRE_ARRAY
                );
                $wcpoa_attachment_name = ( !empty( $wcpoa_attachment_name ) && isset( $wcpoa_attachment_name ) ? $wcpoa_attachment_name : '' );
                update_post_meta( $product_id, 'wcpoa_attachment_name', $wcpoa_attachment_name );
                $wcpoa_attach_type = filter_input(
                    INPUT_POST,
                    'wcpoa_attach_type',
                    FILTER_SANITIZE_SPECIAL_CHARS,
                    FILTER_REQUIRE_ARRAY
                );
                $wcpoa_attach_type = ( !empty( $wcpoa_attach_type ) && isset( $wcpoa_attach_type ) ? $wcpoa_attach_type : '' );
                update_post_meta( $product_id, 'wcpoa_attach_type', $wcpoa_attach_type );
                $wcpoa_attachment_file = filter_input(
                    INPUT_POST,
                    'wcpoa_attachment_file',
                    FILTER_SANITIZE_SPECIAL_CHARS,
                    FILTER_REQUIRE_ARRAY
                );
                $wcpoa_attachment_file = ( !empty( $wcpoa_attachment_file ) && isset( $wcpoa_attachment_file ) ? $wcpoa_attachment_file : '' );
                update_post_meta( $product_id, 'wcpoa_attachment_url', $wcpoa_attachment_file );
                $wcpoa_attachment_url = filter_input(
                    INPUT_POST,
                    'wcpoa_attachment_url',
                    FILTER_VALIDATE_URL,
                    FILTER_REQUIRE_ARRAY
                );
                $wcpoa_attachment_url = ( !empty( $wcpoa_attachment_url ) && isset( $wcpoa_attachment_url ) ? $wcpoa_attachment_url : '' );
                update_post_meta( $product_id, 'wcpoa_attachment_ext_url', $wcpoa_attachment_url );
                $wcpoa_attachment_description = filter_input(
                    INPUT_POST,
                    'wcpoa_attachment_description',
                    FILTER_SANITIZE_SPECIAL_CHARS,
                    FILTER_REQUIRE_ARRAY
                );
                $wcpoa_attachment_description = ( !empty( $wcpoa_attachment_description ) && isset( $wcpoa_attachment_description ) ? $wcpoa_attachment_description : '' );
                update_post_meta( $product_id, 'wcpoa_attachment_description', $wcpoa_attachment_description );
                $wcpoa_order_status = filter_input(
                    INPUT_POST,
                    'wcpoa_order_status',
                    FILTER_SANITIZE_SPECIAL_CHARS,
                    FILTER_REQUIRE_ARRAY
                );
                $wcpoa_order_status_all = ( !empty( $wcpoa_order_status ) ? $wcpoa_order_status : 'wc-all' );
                update_post_meta( $product_id, 'wcpoa_order_status', $wcpoa_order_status_all );
                $wcpoa_product_open_window_flag = filter_input(
                    INPUT_POST,
                    'wcpoa_product_open_window_flag',
                    FILTER_SANITIZE_SPECIAL_CHARS,
                    FILTER_REQUIRE_ARRAY
                );
                $wcpoa_product_open_window_flag = ( !empty( $wcpoa_product_open_window_flag ) && isset( $wcpoa_product_open_window_flag ) ? $wcpoa_product_open_window_flag : '' );
                update_post_meta( $product_id, 'wcpoa_product_open_window_flag', $wcpoa_product_open_window_flag );
                $wcpoa_product_page_enable = filter_input(
                    INPUT_POST,
                    'wcpoa_product_page_enable',
                    FILTER_SANITIZE_SPECIAL_CHARS,
                    FILTER_REQUIRE_ARRAY
                );
                $wcpoa_product_page_enable = ( !empty( $wcpoa_product_page_enable ) && isset( $wcpoa_product_page_enable ) ? $wcpoa_product_page_enable : '' );
                update_post_meta( $product_id, 'wcpoa_product_page_enable', $wcpoa_product_page_enable );
                $wcpoa_product_logged_in_flag = filter_input(
                    INPUT_POST,
                    'wcpoa_product_logged_in_flag',
                    FILTER_SANITIZE_SPECIAL_CHARS,
                    FILTER_REQUIRE_ARRAY
                );
                $wcpoa_product_logged_in_flag = ( !empty( $wcpoa_product_logged_in_flag ) && isset( $wcpoa_product_logged_in_flag ) ? $wcpoa_product_logged_in_flag : '' );
                update_post_meta( $product_id, 'wcpoa_product_logged_in_flag', $wcpoa_product_logged_in_flag );
                $wcpoa_product_att_icon_check = filter_input(
                    INPUT_POST,
                    'wcpoa_product_att_icon_check',
                    FILTER_SANITIZE_SPECIAL_CHARS,
                    FILTER_REQUIRE_ARRAY
                );
                $wcpoa_expired_date_enable = filter_input(
                    INPUT_POST,
                    'wcpoa_expired_date_enable',
                    FILTER_SANITIZE_SPECIAL_CHARS,
                    FILTER_REQUIRE_ARRAY
                );
                $wcpoa_expired_date_enable = ( !empty( $wcpoa_expired_date_enable ) && isset( $wcpoa_expired_date_enable ) ? $wcpoa_expired_date_enable : '' );
                update_post_meta( $product_id, 'wcpoa_expired_date_enable', $wcpoa_expired_date_enable );
                $wcpoa_expired_date = filter_input(
                    INPUT_POST,
                    'wcpoa_expired_date',
                    FILTER_SANITIZE_SPECIAL_CHARS,
                    FILTER_REQUIRE_ARRAY
                );
                $wcpoa_expired_date = ( !empty( $wcpoa_expired_date ) && isset( $wcpoa_expired_date ) ? $wcpoa_expired_date : '' );
                update_post_meta( $product_id, 'wcpoa_expired_date', $wcpoa_expired_date );
            }
        }
    }

    /**
     * Edit attachment form enctype
     *
     * @since 1.0.0
     */
    public function wcpoa_attachment_edit_form() {
        echo 'enctype="multipart/form-data" novalidate';
    }

    /**
     * Order wcpoa order meta fields.
     *
     * @since 1.0.0
     */
    public function wcpoa_order_add_meta_boxes() {
        if ( class_exists( 'Automattic\\WooCommerce\\Internal\\DataStores\\Orders\\CustomOrdersTableController' ) ) {
            $screen = ( wc_get_container()->get( CustomOrdersTableController::class )->custom_orders_table_usage_is_enabled() ? wc_get_page_screen_id( 'shop-order' ) : 'shop_order' );
        } else {
            $screen = 'shop_order';
        }
        $order_meta_title = get_option( 'wcpoa_admin_order_tab_name' );
        add_meta_box(
            'wcpoa_order_meta_fields',
            __( $order_meta_title, 'woocommerce-product-attachment' ),
            array($this, 'wcpoa_order_fields_data'),
            $screen,
            'normal',
            'low'
        );
    }

    /**
     * Order wcpoa order attachment meta fields.
     * 
     * @since 1.0.0
     */
    public function wcpoa_order_add_attachment_meta_boxes() {
        if ( class_exists( 'Automattic\\WooCommerce\\Internal\\DataStores\\Orders\\CustomOrdersTableController' ) ) {
            $screen = ( wc_get_container()->get( CustomOrdersTableController::class )->custom_orders_table_usage_is_enabled() ? wc_get_page_screen_id( 'shop-order' ) : 'shop_order' );
        } else {
            $screen = 'shop_order';
        }
        $order_meta_title = 'Add Attachments';
        add_meta_box(
            'wcpoa_order_attachment_meta_fields',
            __( $order_meta_title, 'woocommerce-product-attachment' ),
            array($this, 'wcpoa_order_attachment_data'),
            $screen,
            'side',
            'low'
        );
    }

    /**
     * User checkout page attachment listing widget
     * 
     * @since 1.0.0
     */
    public function wcpoa_checkout_attachment_meta_boxes() {
        if ( class_exists( 'Automattic\\WooCommerce\\Internal\\DataStores\\Orders\\CustomOrdersTableController' ) ) {
            $screen = ( wc_get_container()->get( CustomOrdersTableController::class )->custom_orders_table_usage_is_enabled() ? wc_get_page_screen_id( 'shop-order' ) : 'shop_order' );
        } else {
            $screen = 'shop_order';
        }
        $order_meta_title = 'User Attachments';
        add_meta_box(
            'wcpoa_checkout_attachment_meta_fields',
            __( $order_meta_title, 'woocommerce-product-attachment' ),
            array($this, 'wcpoa_checkout_attachment_data'),
            $screen,
            'side',
            'low'
        );
    }

    /**
     * Admin side: checkout attachment listing.
     * 
     * @since 1.0.0
     */
    public function wcpoa_checkout_attachment_data() {
        global $post;
        if ( isset( $post ) && !empty( $post ) ) {
            $order_id = $post->ID;
        } else {
            $order_id = filter_input( INPUT_GET, 'id', FILTER_SANITIZE_SPECIAL_CHARS );
        }
        $wcpoa_all_ids = get_post_meta( $order_id, '_wcpoa_checkout_attachment_ids', true );
        $wcpoa_meta_box = '';
        $wcpoa_meta_box .= '<div id="wcpoa_checkout_attach">';
        if ( isset( $wcpoa_all_ids ) && !empty( $wcpoa_all_ids ) ) {
            $id_array = explode( ",", $wcpoa_all_ids );
            foreach ( $id_array as $id ) {
                $media_name = get_the_title( $id );
                $media_upload_date = get_the_date( '', $id );
                $wcpoa_meta_box .= '<div>';
                $wcpoa_meta_box .= '<a href="' . wp_get_attachment_url( $id ) . '" target="_blank" class="wcpoa_image_text_wrap">';
                $wcpoa_meta_box .= wp_get_attachment_image( $id, 'thumbnail' );
                $wcpoa_meta_box .= '<h4>' . esc_html( $media_name ) . '</h4>';
                $wcpoa_meta_box .= '</a>';
                $wcpoa_meta_box .= '<p>' . esc_html( $media_upload_date ) . '</p><hr>';
                $wcpoa_meta_box .= '</div>';
            }
        }
        $wcpoa_meta_box .= '</div>';
        echo wp_kses( $wcpoa_meta_box, $this->allowed_html_tags() );
    }

    /**
     * Admin side: Add attachment on product status.
     * 
     * @since 1.0.0
     */
    public function wcpoa_order_attachment_data() {
        global $post;
        if ( isset( $post ) && !empty( $post ) ) {
            $order_id = $post->ID;
        } else {
            $order_id = filter_input( INPUT_GET, 'id', FILTER_SANITIZE_SPECIAL_CHARS );
        }
        $wcpoa_all_ids = get_post_meta( $order_id, '_wcpoa_order_attachments', true );
        $wcpoa_meta_box = '';
        $wcpoa_meta_box .= '<input type="hidden" name="wcpoa_media_ids" data-id="' . esc_attr( $order_id ) . '"  id="wcpoa_media_ids" value=' . esc_attr( $wcpoa_all_ids ) . '>';
        $wcpoa_meta_box .= '<div class="wcpoa-order-attach"><p>';
        $wcpoa_meta_box .= '<a href="#" id="wcpoa-order-upload-file" class="button button-primary">Add Attachment</a>';
        $wcpoa_meta_box .= '</p></div>';
        $wcpoa_meta_box .= '<div id="wcpoa_updated_attach">';
        if ( isset( $wcpoa_all_ids ) && !empty( $wcpoa_all_ids ) ) {
            $id_array = explode( ",", $wcpoa_all_ids );
            foreach ( $id_array as $id ) {
                $media_name = get_the_title( $id );
                $media_upload_date = get_the_date( '', $id );
                $wcpoa_meta_box .= '<div>';
                $wcpoa_meta_box .= '<a href="' . wp_get_attachment_url( $id ) . '" target="_blank" class="wcpoa_image_text_wrap">';
                $wcpoa_meta_box .= wp_get_attachment_image( $id, 'thumbnail' );
                $wcpoa_meta_box .= '<h4>' . esc_html( $media_name ) . '</h4>';
                $wcpoa_meta_box .= '</a>';
                $wcpoa_meta_box .= '<p>' . esc_html( $media_upload_date ) . ' - <a data-id="' . esc_attr( $id ) . '" class="wcpoa_remove_attach" href="#">Remove</a></p><hr>';
                $wcpoa_meta_box .= '</div>';
            }
        }
        $wcpoa_meta_box .= '</div>';
        echo wp_kses( $wcpoa_meta_box, $this->allowed_html_tags() );
    }

    /**
     * Admin order attachment save.
     * 
     * @since 1.0.0
     */
    public function wcpoa_order_update_attachment() {
        // Security check
        check_ajax_referer( 'order_ajax_verification', 'security' );
        // Save order attachments data
        $wcpoa_media_ids = filter_input( INPUT_POST, 'wcpoa_media_ids', FILTER_SANITIZE_SPECIAL_CHARS );
        $order_id = filter_input( INPUT_POST, 'order_id', FILTER_SANITIZE_SPECIAL_CHARS );
        $wcpoa_media_ids = ( !empty( $wcpoa_media_ids ) && isset( $wcpoa_media_ids ) ? $wcpoa_media_ids : '' );
        if ( !empty( $order_id ) ) {
            if ( class_exists( 'Automattic\\WooCommerce\\Utilities\\OrderUtil' ) ) {
                if ( OrderUtil::custom_orders_table_usage_is_enabled() ) {
                    // HPOS usage is enabled.
                    $order = wc_get_order( $order_id );
                    $order->update_meta_data( '_wcpoa_order_attachments', $wcpoa_media_ids );
                    $order->save();
                } else {
                    // Traditional CPT-based orders are in use.
                    update_post_meta( $order_id, '_wcpoa_order_attachments', $wcpoa_media_ids );
                }
            } else {
                // Traditional CPT-based orders are in use.
                update_post_meta( $order_id, '_wcpoa_order_attachments', $wcpoa_media_ids );
            }
            return true;
        }
    }

    /**
     * Admin side:Product attachments order data.
     *
     * @since 1.0.0
     */
    public function wcpoa_order_fields_data() {
        require_once plugin_dir_path( __FILE__ ) . 'partials/wcpoa-admin-order-attachments.php';
    }

    /**
     * Bulk Attachment
     * 
     * @since 1.0.0
     */
    public function wcpoa_bulk_attachment() {
        $submitwcpoabulkatt = filter_input( INPUT_POST, 'submitwcpoabulkatt', FILTER_SANITIZE_SPECIAL_CHARS );
        if ( isset( $submitwcpoabulkatt ) && !empty( $submitwcpoabulkatt ) ) {
            $this->wcpoa_bulk_attachment_data_save();
            ?>
            <div id="message" class="wcpoa-notice notice notice-success is-dismissible">
                <p></p>
                <button type="button" class="notice-dismiss">
                    <span class="screen-reader-text"><?php 
            esc_html_e( 'Dismiss this notice.', 'woocommerce-product-attachment' );
            ?></span>
                </button>
            </div> 
            <?php 
        }
        $screen = 'woocommerce_product_bulk_attachment_options';
        require_once plugin_dir_path( __FILE__ ) . "partials/header/plugin-header.php";
        ?>
        <div class="wrap wcpoa-bulk-attach-main">
            <form id="post" name="post" method="post" novalidate="novalidate" enctype="multipart/form-data">
                <input type="hidden" name="post_type" value="wcpoa_bulk_att">
                <?php 
        wp_nonce_field( 'some-action-nonce' );
        /* Used to save closed meta boxes and their order */
        wp_nonce_field( 'meta-box-order', 'meta-box-order-nonce', false );
        wp_nonce_field( 'closedpostboxes', 'closedpostboxesnonce', false );
        ?>

                <div id="poststuff">
                    <div class="wcpoa-table-main res-cl wcpoa-bulk-attach-left">
                        <h2><?php 
        esc_html_e( 'Product Bulk Attachments', 'woocommerce-product-attachment' );
        ?></h2>
                        <?php 
        require_once plugin_dir_path( __FILE__ ) . "partials/wcpoa-bulk-attachement-add.php";
        ?>
                    </div>
                    <div class="wcpoa-table-main res-cl wcpoa-bulk-attach-left">
                        <h2><?php 
        esc_html_e( 'Bulk Product Attachment Import', 'woocommerce-product-attachment' );
        ?>
                            <?php 
        if ( !(wpap_fs()->is__premium_only() && wpap_fs()->can_use_premium_code()) ) {
            ?><span class="wcpoa-pro-label wcpoa-pro-feature"></span><?php 
        }
        ?>
                        </h2>
                        <div class="bulk-product-attachment-wrap">
                            <div class="wcpoa-label wcpoa-bulk-att-desc">
                                <p><?php 
        esc_html_e( 'Import product attachments in bulk using product SKUs. Ensure your products have SKUs and upload a CSV file for import. Imported attachments will be added to the corresponding single-product pages based on SKUs. ', 'woocommerce-product-attachment' );
        ?></p>
                            </div>
                            <div class="wcpoa-oprations">
                                <label class="wcpoa-general-input-title"><strong><?php 
        esc_html_e( 'Import Attachments', 'woocommerce-product-attachment' );
        ?></strong></label>
                                <span class="wcpoa-description-tooltip-icon"></span>
                                <p class="wcpoa-description"><?php 
        echo sprintf( esc_html__( '%1$s to review the document guide and download a sample CSV file.', 'woocommerce-product-attachment' ), '<a href="' . esc_url( 'https://docs.thedotstore.com/article/690-bulk-product-attachment-import' ) . '" target="_blank">' . esc_html__( 'Click here', 'woocommerce-product-attachment' ) . '</a>' );
        ?></p>
                                <div class="wcpoa-general-input-value">
                                    <p><input type="file" id="wcpoa-import-file-attachment" name="wcpoa-import-file-attachment" /></p>
                                    <p style="margin-bottom:0"><input type="button" value="<?php 
        esc_attr_e( 'Import', 'woocommerce-product-attachment' );
        ?>" class="button button-primary button-large" id="wcpoa-bulk-product-import"></p>
                                </div>
                            </div>
                        </div>
                    </div>
                </div> <!-- #poststuff -->
            </form>
        </div><!-- .wrap -->
        </div><!-- .wcpoa-section-left -->
        </div><!-- .dots-settings-inner-main -->
        </div><!-- .all-pad -->
        </div><!-- #dotsstoremain -->
        <?php 
    }

    /**
     * Add attachment meta box
     * 
     * @since 1.0.0
     */
    public function wcpoa_add_my_meta_box() {
        $screen = 'woocommerce_product_bulk_attachment_options';
        add_meta_box(
            'wcpoasubmitdiv',
            __( 'Publish', 'woocommerce-product-attachment' ),
            array($this, 'wcpoa_bulk_submitdiv'),
            $screen,
            'side',
            'high'
        );
        add_meta_box(
            'wcpoa_bulk_att',
            __( 'WooCommerce Product Bulk Attachments', 'woocommerce-product-attachment' ),
            array($this, 'wcpoa_bulk_attachment_metabox'),
            $screen,
            'normal',
            'high'
        );
    }

    /**
     * Bulk attachment meta box
     * 
     * @since 1.0.0
     */
    public function wcpoa_bulk_attachment_metabox() {
        require_once plugin_dir_path( __FILE__ ) . "partials/wcpoa-bulk-attachement-add.php";
    }

    /**
     * Prints script in footer. This 'initialises' the meta boxes
     * 
     * @since 1.0.0
     */
    function wcpoa_print_script_in_footer() {
        ?>
        <script>
        jQuery(document).ready(function() {
            postboxes.add_postbox_toggles(pagenow);
        });
        </script>
        <?php 
    }

    /**
     * Bulk attachment submit div
     * 
     * @since 1.0.0
     * 
     * @param $post
     * @param $args
     */
    function wcpoa_bulk_submitdiv( $post, $args ) {
        ?>
        <div id="major-publishing-actions">
            <div id="publishing-action">
                <span class="spinner"></span>
                <input type="submit" accesskey="p" value="Publish" class="button button-primary button-large" id="publish"
                    name="submitwcpoabulkatt">
            </div>
            <div class="clear"></div>
        </div><?php 
    }

    /**
     * Save option for bulk attachment data save.
     *
     * @since 1.0.0
     */
    public function wcpoa_bulk_attachment_data_save() {
        $wcpoa_attachments_id = filter_input(
            INPUT_POST,
            'wcpoa_attachments_id',
            FILTER_SANITIZE_SPECIAL_CHARS,
            FILTER_REQUIRE_ARRAY
        );
        unset($wcpoa_attachments_id[count( $wcpoa_attachments_id ) - 1]);
        $wcpoa_attachments_id = ( !empty( $wcpoa_attachments_id ) ? $wcpoa_attachments_id : '' );
        $wcpoa_attachment_name = filter_input(
            INPUT_POST,
            'wcpoa_attachment_name',
            FILTER_SANITIZE_SPECIAL_CHARS,
            FILTER_REQUIRE_ARRAY
        );
        $wcpoa_attachment_name = ( !empty( $wcpoa_attachment_name ) ? $wcpoa_attachment_name : '' );
        $wcpoa_attach_view = filter_input(
            INPUT_POST,
            'wcpoa_attach_view',
            FILTER_SANITIZE_SPECIAL_CHARS,
            FILTER_REQUIRE_ARRAY
        );
        $wcpoa_attach_view = ( !empty( $wcpoa_attach_view ) ? $wcpoa_attach_view : '' );
        $wcpoa_attach_type = filter_input(
            INPUT_POST,
            'wcpoa_attach_type',
            FILTER_SANITIZE_SPECIAL_CHARS,
            FILTER_REQUIRE_ARRAY
        );
        $wcpoa_attach_type = ( !empty( $wcpoa_attach_type ) ? $wcpoa_attach_type : '' );
        $wcpoa_attachment_file = filter_input(
            INPUT_POST,
            'wcpoa_attachment_file',
            FILTER_SANITIZE_SPECIAL_CHARS,
            FILTER_REQUIRE_ARRAY
        );
        $wcpoa_attachment_file = ( !empty( $wcpoa_attachment_file ) ? $wcpoa_attachment_file : '' );
        $wcpoa_attachment_description = filter_input(
            INPUT_POST,
            'wcpoa_attachment_description',
            FILTER_SANITIZE_SPECIAL_CHARS,
            FILTER_REQUIRE_ARRAY
        );
        $wcpoa_attachment_description = ( !empty( $wcpoa_attachment_description ) ? $wcpoa_attachment_description : '' );
        $wcpoa_order_status = filter_input(
            INPUT_POST,
            'wcpoa_order_status',
            FILTER_SANITIZE_SPECIAL_CHARS,
            FILTER_REQUIRE_ARRAY
        );
        $wcpoa_order_status_all = ( !empty( $wcpoa_order_status ) ? $wcpoa_order_status : '' );
        $wcpoa_att_visibility = filter_input(
            INPUT_POST,
            'wcpoa_att_visibility',
            FILTER_SANITIZE_SPECIAL_CHARS,
            FILTER_REQUIRE_ARRAY
        );
        $wcpoa_att_visibility = ( !empty( $wcpoa_att_visibility ) ? $wcpoa_att_visibility : '' );
        $wcpoa_product_logged_in_flag = filter_input(
            INPUT_POST,
            'wcpoa_product_logged_in_flag',
            FILTER_SANITIZE_SPECIAL_CHARS,
            FILTER_REQUIRE_ARRAY
        );
        $wcpoa_product_logged_in_flag = ( !empty( $wcpoa_product_logged_in_flag ) ? $wcpoa_product_logged_in_flag : '' );
        $wcpoa_is_condition = filter_input(
            INPUT_POST,
            'wcpoa_is_condition',
            FILTER_SANITIZE_SPECIAL_CHARS,
            FILTER_REQUIRE_ARRAY
        );
        $wcpoa_is_condition = ( !empty( $wcpoa_is_condition ) ? $wcpoa_is_condition : '' );
        $wcpoa_expired_date_enable = filter_input(
            INPUT_POST,
            'wcpoa_expired_date_enable',
            FILTER_SANITIZE_SPECIAL_CHARS,
            FILTER_REQUIRE_ARRAY
        );
        $wcpoa_expired_date_enable = ( !empty( $wcpoa_expired_date_enable ) ? $wcpoa_expired_date_enable : '' );
        $wcpoa_product_open_window_flag = filter_input(
            INPUT_POST,
            'wcpoa_product_open_window_flag',
            FILTER_SANITIZE_SPECIAL_CHARS,
            FILTER_REQUIRE_ARRAY
        );
        $wcpoa_product_open_window_flag = ( !empty( $wcpoa_product_open_window_flag ) && isset( $wcpoa_product_open_window_flag ) ? $wcpoa_product_open_window_flag : '' );
        if ( $wcpoa_expired_date_enable ) {
            $wcpoa_attachment_time_amount = filter_input(
                INPUT_POST,
                'wcpoa_attachment_time_amount',
                FILTER_SANITIZE_SPECIAL_CHARS,
                FILTER_REQUIRE_ARRAY
            );
            $wcpoa_attachment_time_amount = ( !empty( $wcpoa_attachment_time_amount ) ? $wcpoa_attachment_time_amount : '' );
            $wcpoa_expired_date = filter_input(
                INPUT_POST,
                'wcpoa_expired_date',
                FILTER_SANITIZE_SPECIAL_CHARS,
                FILTER_REQUIRE_ARRAY
            );
            $wcpoa_expired_date = ( !empty( $wcpoa_expired_date ) ? $wcpoa_expired_date : '' );
        }
        $wcpoa_bulk_attachment_array = [];
        if ( !empty( $wcpoa_attachments_id ) && is_array( $wcpoa_attachments_id ) ) {
            $wcpoa_bulk_attachment_array = [];
            foreach ( $wcpoa_attachments_id as $wcpoa_bulk_key => $wcpoa_bulk_key_value ) {
                $wcpoa_bulk_attachment_array[$wcpoa_bulk_key_value]['wcpoa_attachments_id'] = $wcpoa_attachments_id[$wcpoa_bulk_key];
                $wcpoa_bulk_attachment_array[$wcpoa_bulk_key_value]['wcpoa_is_condition'] = $wcpoa_is_condition[$wcpoa_bulk_key];
                $wcpoa_bulk_attachment_array[$wcpoa_bulk_key_value]['wcpoa_attachment_name'] = $wcpoa_attachment_name[$wcpoa_bulk_key];
                $wcpoa_bulk_attachment_array[$wcpoa_bulk_key_value]['wcpoa_attach_view'] = $wcpoa_attach_view[$wcpoa_bulk_key];
                $wcpoa_bulk_attachment_array[$wcpoa_bulk_key_value]['wcpoa_attach_type'] = $wcpoa_attach_type[$wcpoa_bulk_key];
                $wcpoa_bulk_attachment_array[$wcpoa_bulk_key_value]['wcpoa_attachment_file'] = $wcpoa_attachment_file[$wcpoa_bulk_key];
                $wcpoa_bulk_attachment_array[$wcpoa_bulk_key_value]['wcpoa_attachment_description'] = $wcpoa_attachment_description[$wcpoa_bulk_key];
                if ( empty( $wcpoa_order_status_all[$wcpoa_bulk_key_value] ) ) {
                    $wcpoa_bulk_attachment_array[$wcpoa_bulk_key_value]['wcpoa_order_status'] = array();
                } else {
                    $wcpoa_bulk_attachment_array[$wcpoa_bulk_key_value]['wcpoa_order_status'] = $wcpoa_order_status_all[$wcpoa_bulk_key_value];
                }
                $wcpoa_attachment_time_amount = filter_input(
                    INPUT_POST,
                    'wcpoa_attachment_time_amount',
                    FILTER_SANITIZE_SPECIAL_CHARS,
                    FILTER_REQUIRE_ARRAY
                );
                $wcpoa_attachment_time_amount = ( !empty( $wcpoa_attachment_time_amount ) ? $wcpoa_attachment_time_amount : '' );
                $wcpoa_expired_date = filter_input(
                    INPUT_POST,
                    'wcpoa_expired_date',
                    FILTER_SANITIZE_SPECIAL_CHARS,
                    FILTER_REQUIRE_ARRAY
                );
                $wcpoa_expired_date = ( !empty( $wcpoa_expired_date ) ? $wcpoa_expired_date : '' );
                $wcpoa_bulk_attachment_array[$wcpoa_bulk_key_value]['wcpoa_att_visibility'] = $wcpoa_att_visibility[$wcpoa_bulk_key];
                $wcpoa_bulk_attachment_array[$wcpoa_bulk_key_value]['wcpoa_product_logged_in_flag'] = $wcpoa_product_logged_in_flag[$wcpoa_bulk_key];
                $wcpoa_bulk_attachment_array[$wcpoa_bulk_key_value]['wcpoa_product_open_window_flag'] = $wcpoa_product_open_window_flag[$wcpoa_bulk_key];
                $wcpoa_bulk_attachment_array[$wcpoa_bulk_key_value]['wcpoa_expired_date_enable'] = $wcpoa_expired_date_enable[$wcpoa_bulk_key];
                $wcpoa_bulk_attachment_array[$wcpoa_bulk_key_value]['wcpoa_expired_date'] = $wcpoa_expired_date[$wcpoa_bulk_key];
            }
        }
        update_option( 'wcpoa_bulk_attachment_data', $wcpoa_bulk_attachment_array );
    }

    /**
     * Upload image media library
     * 
     * @since 1.0.0
     * 
     * @param $postID
     * @param $url
     */
    private function wcpoa_upload_image_to_media_library( $postID, $url ) {
        require_once ABSPATH . "wp-admin/includes/image.php";
        require_once ABSPATH . "wp-admin/includes/file.php";
        require_once ABSPATH . "wp-admin/includes/media.php";
        // Set variables for storage
        // fix file filename for query strings
        preg_match( '/[^\\?]+\\.(jpg|jpe|jpeg|gif|png|webp|pdf|doc|odt|key|ppt|pptx|pps|ppsx|xls|mp3|m4a|ogg|wav|mp4|m4v|mov|wmv|avi|mpg|ogv|3gp|3g2|zip|gz)/i', $url, $matches );
        if ( !empty( $matches[0] ) && "" !== $matches[0] ) {
            $tmp = download_url( $url );
            $file_array = array();
            $file_array['name'] = basename( $matches[0] );
            $file_array['tmp_name'] = $tmp;
            // If error storing temporarily, unlink
            if ( is_wp_error( $tmp ) ) {
                $file_array['tmp_name'] = '';
            }
            // do the validation and storage stuff
            $id = media_handle_sideload( $file_array, $postID );
            // If error storing permanently, unlink
            if ( is_wp_error( $id ) ) {
                $id = "";
            }
        } else {
            $id = "";
        }
        return $id;
    }

    /**
     * Generate random strings
     * 
     * @since 1.0.0
     * 
     * @param $length
     */
    private function wcpoa_generate_random_string( $length = 14 ) {
        $characters = 'abcdefghijklmnopqrstuvwxyz0123456789';
        $charactersLength = strlen( $characters );
        $randomString = '';
        for ($i = 0; $i < $length; $i++) {
            $randomString .= $characters[rand( 0, $charactersLength - 1 )];
            //phpcs:ignore
        }
        return $randomString;
    }

    /**
     * Upload file button
     * 
     * @since 1.0.0
     * 
     * @param $attachment_id
     */
    public function wcpoa_image_uploader_field( $attachment_id = '' ) {
        $image = ' button">Upload File';
        return '<div>
                    <a href="#" data-id="' . esc_attr( $attachment_id ) . '" class="wcpoa_upload_image_button button-secondary' . $image . '</a>
                </div>';
    }

    /**
     * Get default site language
     *
     * @since 1.0.0
     * 
     * @return string $default_lang
     */
    public function wcpoa_get_default_langugae_with_sitpress() {
        global $sitepress;
        if ( !empty( $sitepress ) ) {
            $default_lang = $sitepress->get_current_language();
        } else {
            $default_lang = $this->wcpoa_get_current_site_language();
        }
        return $default_lang;
    }

    /**
     * Get current site langugae
     *
     * @return string $default_lang
     * 
     * @since 1.0.0
     */
    public function wcpoa_get_current_site_language() {
        $get_site_language = get_bloginfo( 'language' );
        if ( false !== strpos( $get_site_language, '-' ) ) {
            $get_site_language_explode = explode( '-', $get_site_language );
            $default_lang = $get_site_language_explode[0];
        } else {
            $default_lang = $get_site_language;
        }
        return $default_lang;
    }

    /**
     * Allow HTML tags
     * 
     * @since 1.0.0
     * 
     * @param $tags
     */
    public function allowed_html_tags( $tags = array() ) {
        $allowed_tags = array(
            'a'        => array(
                'href'    => array(),
                'title'   => array(),
                'data-id' => array(),
                'class'   => array(),
                'id'      => array(),
                'target'  => array(),
            ),
            'p'        => array(
                'href'  => array(),
                'title' => array(),
                'class' => array(),
            ),
            'span'     => array(
                'href'  => array(),
                'title' => array(),
                'class' => array(),
            ),
            'ul'       => array(
                'class' => array(),
            ),
            'img'      => array(
                'href'  => array(),
                'title' => array(),
                'class' => array(),
                'src'   => array(),
            ),
            'li'       => array(
                'class' => array(),
            ),
            'h1'       => array(
                'id'    => array(),
                'name'  => array(),
                'class' => array(),
            ),
            'h2'       => array(
                'id'    => array(),
                'name'  => array(),
                'class' => array(),
            ),
            'h3'       => array(
                'id'    => array(),
                'name'  => array(),
                'class' => array(),
            ),
            'h4'       => array(
                'id'    => array(),
                'name'  => array(),
                'class' => array(),
            ),
            'div'      => array(
                'class'     => array(),
                'id'        => array(),
                "data-max"  => array(),
                "data-min"  => array(),
                "stlye"     => array(),
                "data-name" => array(),
                "data-type" => array(),
                "data-key"  => array(),
            ),
            'select'   => array(
                'id'       => array(),
                'name'     => array(),
                'class'    => array(),
                'multiple' => array(),
                'style'    => array(),
            ),
            'input'    => array(
                'id'      => array(),
                'value'   => array(),
                'name'    => array(),
                'class'   => array(),
                'type'    => array(),
                'data-id' => array(),
            ),
            'textarea' => array(
                'id'    => array(),
                'name'  => array(),
                'class' => array(),
            ),
            'td'       => array(
                'id'    => array(),
                'name'  => array(),
                'class' => array(),
            ),
            'tr'       => array(
                'id'    => array(),
                'name'  => array(),
                'class' => array(),
            ),
            'tbody'    => array(
                'id'    => array(),
                'name'  => array(),
                'class' => array(),
            ),
            'table'    => array(
                'id'    => array(),
                'name'  => array(),
                'class' => array(),
            ),
            'option'   => array(
                'id'       => array(),
                'selected' => array(),
                'name'     => array(),
                'value'    => array(),
            ),
            'br'       => array(),
            'em'       => array(),
            'strong'   => array(),
            'label'    => array(
                'for' => array(),
            ),
        );
        if ( !empty( $tags ) ) {
            foreach ( $tags as $key => $value ) {
                $allowed_tags[$key] = $value;
            }
        }
        return $allowed_tags;
    }

    /**
     * Get dynamic promotional bar of plugin
     *
     * @param   String  $plugin_slug  slug of the plugin added in the site option
     * @since   2.2.0
     * 
     * @return  null
     */
    public function wcpoa_get_promotional_bar( $plugin_slug = '' ) {
        $promotional_bar_upi_url = WCPOA_STORE_URL . 'wp-json/dpb-promotional-banner/v2/dpb-promotional-banner?' . wp_rand();
        $promotional_banner_request = wp_remote_get( $promotional_bar_upi_url );
        //phpcs:ignore
        if ( empty( $promotional_banner_request->errors ) ) {
            $promotional_banner_request_body = $promotional_banner_request['body'];
            $promotional_banner_request_body = json_decode( $promotional_banner_request_body, true );
            echo '<div class="dynamicbar_wrapper">';
            if ( !empty( $promotional_banner_request_body ) && is_array( $promotional_banner_request_body ) ) {
                foreach ( $promotional_banner_request_body as $promotional_banner_request_body_data ) {
                    $promotional_banner_id = $promotional_banner_request_body_data['promotional_banner_id'];
                    $promotional_banner_cookie = $promotional_banner_request_body_data['promotional_banner_cookie'];
                    $promotional_banner_image = $promotional_banner_request_body_data['promotional_banner_image'];
                    $promotional_banner_description = $promotional_banner_request_body_data['promotional_banner_description'];
                    $promotional_banner_button_group = $promotional_banner_request_body_data['promotional_banner_button_group'];
                    $dpb_schedule_campaign_type = $promotional_banner_request_body_data['dpb_schedule_campaign_type'];
                    $promotional_banner_target_audience = $promotional_banner_request_body_data['promotional_banner_target_audience'];
                    if ( !empty( $promotional_banner_target_audience ) ) {
                        $plugin_keys = array();
                        if ( is_array( $promotional_banner_target_audience ) ) {
                            foreach ( $promotional_banner_target_audience as $list ) {
                                $plugin_keys[] = $list['value'];
                            }
                        } else {
                            $plugin_keys[] = $promotional_banner_target_audience['value'];
                        }
                        $display_banner_flag = false;
                        if ( in_array( 'all_customers', $plugin_keys, true ) || in_array( $plugin_slug, $plugin_keys, true ) ) {
                            $display_banner_flag = true;
                        }
                    }
                    if ( true === $display_banner_flag ) {
                        if ( 'default' === $dpb_schedule_campaign_type ) {
                            $banner_cookie_show = filter_input( INPUT_COOKIE, 'banner_show_' . $promotional_banner_cookie, FILTER_SANITIZE_FULL_SPECIAL_CHARS );
                            $banner_cookie_visible_once = filter_input( INPUT_COOKIE, 'banner_show_once_' . $promotional_banner_cookie, FILTER_SANITIZE_FULL_SPECIAL_CHARS );
                            $flag = false;
                            if ( empty( $banner_cookie_show ) && empty( $banner_cookie_visible_once ) ) {
                                setcookie( 'banner_show_' . $promotional_banner_cookie, 'yes', time() + 86400 * 7 );
                                //phpcs:ignore
                                setcookie( 'banner_show_once_' . $promotional_banner_cookie, 'yes' );
                                //phpcs:ignore
                                $flag = true;
                            }
                            $banner_cookie_show = filter_input( INPUT_COOKIE, 'banner_show_' . $promotional_banner_cookie, FILTER_SANITIZE_FULL_SPECIAL_CHARS );
                            if ( !empty( $banner_cookie_show ) || true === $flag ) {
                                $banner_cookie = filter_input( INPUT_COOKIE, 'banner_' . $promotional_banner_cookie, FILTER_SANITIZE_FULL_SPECIAL_CHARS );
                                $banner_cookie = ( isset( $banner_cookie ) ? $banner_cookie : '' );
                                if ( empty( $banner_cookie ) && 'yes' !== $banner_cookie ) {
                                    ?>
                                <div class="dpb-popup <?php 
                                    echo ( isset( $promotional_banner_cookie ) ? esc_html( $promotional_banner_cookie ) : 'default-banner' );
                                    ?>">
                                    <?php 
                                    if ( !empty( $promotional_banner_image ) ) {
                                        ?>
                                        <img src="<?php 
                                        echo esc_url( $promotional_banner_image );
                                        ?>"/>
                                        <?php 
                                    }
                                    ?>
                                    <div class="dpb-popup-meta">
                                        <p>
                                            <?php 
                                    echo wp_kses_post( str_replace( array('<p>', '</p>'), '', $promotional_banner_description ) );
                                    if ( !empty( $promotional_banner_button_group ) ) {
                                        foreach ( $promotional_banner_button_group as $promotional_banner_button_group_data ) {
                                            ?>
                                                    <a href="<?php 
                                            echo esc_url( $promotional_banner_button_group_data['promotional_banner_button_link'] );
                                            ?>" target="_blank"><?php 
                                            echo esc_html( $promotional_banner_button_group_data['promotional_banner_button_text'] );
                                            ?></a>
                                                    <?php 
                                        }
                                    }
                                    ?>
                                        </p>
                                    </div>
                                    <a href="javascript:void(0);" data-bar-id="<?php 
                                    echo esc_attr( $promotional_banner_id );
                                    ?>" data-popup-name="<?php 
                                    echo ( isset( $promotional_banner_cookie ) ? esc_attr( $promotional_banner_cookie ) : 'default-banner' );
                                    ?>" class="dpbpop-close"><svg xmlns="http://www.w3.org/2000/svg" width="10" height="10" viewBox="0 0 10 10"><path id="Icon_material-close" data-name="Icon material-close" d="M17.5,8.507,16.493,7.5,12.5,11.493,8.507,7.5,7.5,8.507,11.493,12.5,7.5,16.493,8.507,17.5,12.5,13.507,16.493,17.5,17.5,16.493,13.507,12.5Z" transform="translate(-7.5 -7.5)" fill="#acacac"/></svg></a>
                                </div>
                                <?php 
                                }
                            }
                        } else {
                            $banner_cookie_show = filter_input( INPUT_COOKIE, 'banner_show_' . $promotional_banner_cookie, FILTER_SANITIZE_FULL_SPECIAL_CHARS );
                            $banner_cookie_visible_once = filter_input( INPUT_COOKIE, 'banner_show_once_' . $promotional_banner_cookie, FILTER_SANITIZE_FULL_SPECIAL_CHARS );
                            $flag = false;
                            if ( empty( $banner_cookie_show ) && empty( $banner_cookie_visible_once ) ) {
                                setcookie( 'banner_show_' . $promotional_banner_cookie, 'yes' );
                                //phpcs:ignore
                                setcookie( 'banner_show_once_' . $promotional_banner_cookie, 'yes' );
                                //phpcs:ignore
                                $flag = true;
                            }
                            $banner_cookie_show = filter_input( INPUT_COOKIE, 'banner_show_' . $promotional_banner_cookie, FILTER_SANITIZE_FULL_SPECIAL_CHARS );
                            if ( !empty( $banner_cookie_show ) || true === $flag ) {
                                $banner_cookie = filter_input( INPUT_COOKIE, 'banner_' . $promotional_banner_cookie, FILTER_SANITIZE_FULL_SPECIAL_CHARS );
                                $banner_cookie = ( isset( $banner_cookie ) ? $banner_cookie : '' );
                                if ( empty( $banner_cookie ) && 'yes' !== $banner_cookie ) {
                                    ?>
                                <div class="dpb-popup <?php 
                                    echo ( isset( $promotional_banner_cookie ) ? esc_html( $promotional_banner_cookie ) : 'default-banner' );
                                    ?>">
                                    <?php 
                                    if ( !empty( $promotional_banner_image ) ) {
                                        ?>
                                            <img src="<?php 
                                        echo esc_url( $promotional_banner_image );
                                        ?>"/>
                                        <?php 
                                    }
                                    ?>
                                    <div class="dpb-popup-meta">
                                        <p>
                                            <?php 
                                    echo wp_kses_post( str_replace( array('<p>', '</p>'), '', $promotional_banner_description ) );
                                    if ( !empty( $promotional_banner_button_group ) ) {
                                        foreach ( $promotional_banner_button_group as $promotional_banner_button_group_data ) {
                                            ?>
                                                    <a href="<?php 
                                            echo esc_url( $promotional_banner_button_group_data['promotional_banner_button_link'] );
                                            ?>" target="_blank"><?php 
                                            echo esc_html( $promotional_banner_button_group_data['promotional_banner_button_text'] );
                                            ?></a>
                                                    <?php 
                                        }
                                    }
                                    ?>
                                        </p>
                                    </div>
                                    <a href="javascript:void(0);" data-bar-id="<?php 
                                    echo esc_attr( $promotional_banner_id );
                                    ?>" data-popup-name="<?php 
                                    echo ( isset( $promotional_banner_cookie ) ? esc_html( $promotional_banner_cookie ) : 'default-banner' );
                                    ?>" class="dpbpop-close"><svg xmlns="http://www.w3.org/2000/svg" width="10" height="10" viewBox="0 0 10 10"><path id="Icon_material-close" data-name="Icon material-close" d="M17.5,8.507,16.493,7.5,12.5,11.493,8.507,7.5,7.5,8.507,11.493,12.5,7.5,16.493,8.507,17.5,12.5,13.507,16.493,17.5,17.5,16.493,13.507,12.5Z" transform="translate(-7.5 -7.5)" fill="#acacac"/></svg></a>
                                </div>
                                <?php 
                                }
                            }
                        }
                    }
                }
            }
            echo '</div>';
        }
    }

    /**
     * Get and save plugin setup wizard data
     * 
     * @since 2.2.0
     * 
     */
    public function wcpoa_plugin_setup_wizard_submit() {
        // Security check
        check_ajax_referer( 'wizard_ajax_nonce', 'nonce' );
        $survey_list = filter_input( INPUT_GET, 'survey_list', FILTER_SANITIZE_FULL_SPECIAL_CHARS );
        if ( !empty( $survey_list ) && 'Select One' !== $survey_list ) {
            update_option( 'wcpoa_where_hear_about_us', $survey_list );
        }
        wp_die();
    }

    /**
     * Send setup wizard data to sendinblue
     * 
     * @since 2.2.0
     * 
     */
    public function wcpoa_send_wizard_data_after_plugin_activation() {
        $send_wizard_data = filter_input( INPUT_GET, 'send-wizard-data', FILTER_SANITIZE_FULL_SPECIAL_CHARS );
        if ( isset( $send_wizard_data ) && !empty( $send_wizard_data ) ) {
            if ( !get_option( 'wcpoa_data_submited_in_sendiblue' ) ) {
                $wcpoa_where_hear = get_option( 'wcpoa_where_hear_about_us', 'None' );
                $get_user = wpap_fs()->get_user();
                $data_insert_array = array();
                if ( isset( $get_user ) && !empty( $get_user ) ) {
                    $data_insert_array = array(
                        'user_email'              => $get_user->email,
                        'ACQUISITION_SURVEY_LIST' => $wcpoa_where_hear,
                    );
                }
                $feedback_api_url = WCPOA_STORE_URL . 'wp-json/dotstore-sendinblue-data/v2/dotstore-sendinblue-data?' . wp_rand();
                $query_url = $feedback_api_url . '&' . http_build_query( $data_insert_array );
                if ( function_exists( 'vip_safe_wp_remote_get' ) ) {
                    $response = vip_safe_wp_remote_get(
                        $query_url,
                        3,
                        1,
                        20
                    );
                } else {
                    $response = wp_remote_get( $query_url );
                    // phpcs:ignore
                }
                if ( !is_wp_error( $response ) && 200 === wp_remote_retrieve_response_code( $response ) ) {
                    update_option( 'wcpoa_data_submited_in_sendiblue', '1' );
                    delete_option( 'wcpoa_where_hear_about_us' );
                }
            }
        }
    }

}
