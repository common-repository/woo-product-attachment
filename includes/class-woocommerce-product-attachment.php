<?php

/**
 * The file that defines the core plugin class
 *
 * A class definition that includes attributes and functions used across both the
 * public-facing side of the site and the admin area.
 *
 * @link       http://www.multidots.com/
 * @since      1.0.0
 *
 * @package    Woocommerce_Product_Attachment
 * @subpackage Woocommerce_Product_Attachment/includes
 */
/**
 * The core plugin class.
 *
 * This is used to define internationalization, admin-specific hooks, and
 * public-facing site hooks.
 *
 * Also maintains the unique identifier of this plugin as well as the current
 * version of the plugin.
 *
 * @since      1.0.0
 * @package    Woocommerce_Product_Attachment
 * @subpackage Woocommerce_Product_Attachment/includes
 * @author     multidots <nirav.soni@multidots.com>
 */
class Woocommerce_Product_Attachment {
    /**
     * The loader that's responsible for maintaining and registering all hooks that power
     * the plugin.
     *
     * @since    1.0.0
     * @access   protected
     * @var      Woocommerce_Product_Attachment_Loader $loader Maintains and registers all hooks for the plugin.
     */
    protected $loader;

    /**
     * The unique identifier of this plugin.
     *
     * @since    1.0.0
     * @access   protected
     * @var      string $plugin_name The string used to uniquely identify this plugin.
     */
    protected $plugin_name;

    /**
     * The current version of the plugin.
     *
     * @since    1.0.0
     * @access   protected
     * @var      string $version The current version of the plugin.
     */
    protected $version;

    /**
     * Define the core functionality of the plugin.
     *
     * Set the plugin name and the plugin version that can be used throughout the plugin.
     * Load the dependencies, define the locale, and set the hooks for the admin area and
     * the public-facing side of the site.
     *
     * @since    1.0.0
     */
    public function __construct() {
        $this->plugin_name = 'woocommerce-product-attachment';
        $this->version = '2.1.6';
        $this->load_dependencies();
        $this->define_admin_hooks();
        $this->define_public_hooks();
        $prefix = ( is_network_admin() ? 'network_admin_' : '' );
        add_filter(
            "{$prefix}plugin_action_links_" . WCPOA_PLUGIN_BASENAME,
            array($this, 'plugin_action_links'),
            10,
            4
        );
        add_filter(
            'plugin_row_meta',
            array($this, 'plugin_row_meta_action_links'),
            20,
            4
        );
    }

    /**
     * Load the required dependencies for this plugin.
     *
     * Include the following files that make up the plugin:
     *
     * - Woocommerce_Product_Attachment_Loader. Orchestrates the hooks of the plugin.
     * - Woocommerce_Product_Attachment_i18n. Defines internationalization functionality.
     * - Woocommerce_Product_Attachment_Admin. Defines all hooks for the admin area.
     * - Woocommerce_Product_Attachment_Public. Defines all hooks for the public side of the site.
     *
     * Create an instance of the loader which will be used to register the hooks
     * with WordPress.
     *
     * @since    1.0.0
     * @access   private
     */
    private function load_dependencies() {
        /**
         * The class responsible for orchestrating the actions and filters of the
         * core plugin.
         */
        require_once plugin_dir_path( dirname( __FILE__ ) ) . 'includes/class-woocommerce-product-attachment-loader.php';
        /**
         * The class responsible for defining all actions that occur in the admin area.
         */
        require_once plugin_dir_path( dirname( __FILE__ ) ) . 'admin/class-woocommerce-product-attachment-admin.php';
        /**
         * The class responsible for defining all actions that occur in the public-facing
         * side of the site.
         */
        require_once plugin_dir_path( dirname( __FILE__ ) ) . 'public/class-woocommerce-product-attachment-public.php';
        $this->loader = new Woocommerce_Product_Attachment_Loader();
    }

    /**
     * Register all of the hooks related to the admin area functionality
     * of the plugin.
     *
     * @since    1.0.0
     * @access   private
     */
    private function define_admin_hooks() {
        $page = filter_input( INPUT_GET, 'page', FILTER_SANITIZE_FULL_SPECIAL_CHARS );
        $plugin_admin = new Woocommerce_Product_Attachment_Admin($this->get_plugin_name(), $this->get_version());
        $screen_id = 'dotstore-plugins_page_wcpoa_bulk_attachment';
        $this->loader->add_action( 'admin_enqueue_scripts', $plugin_admin, 'wcpoa_enqueue_styles' );
        $this->loader->add_action( 'admin_enqueue_scripts', $plugin_admin, 'wcpoa_enqueue_scripts' );
        if ( empty( $GLOBALS['admin_page_hooks']['dots_store'] ) ) {
            $this->loader->add_action( 'admin_menu', $plugin_admin, 'wcpoa_dot_store_menu' );
        }
        $this->loader->add_action( 'admin_init', $plugin_admin, 'wcpoa_welcome_plugin_screen_do_activation_redirect' );
        $this->loader->add_action( 'admin_menu', $plugin_admin, 'wcpoa_plugin_menu' );
        $this->loader->add_action( 'admin_head', $plugin_admin, 'wcpoa_dot_store_icon_css' );
        $this->loader->add_action( 'admin_wcpoa_setting_page', $plugin_admin, 'wcpoa_setting_page' );
        $this->loader->add_action(
            'add_meta_boxes',
            $plugin_admin,
            'wcpoa_add_meta_box',
            10,
            9
        );
        $this->loader->add_action( 'save_post', $plugin_admin, 'wcpoa_attachment_meta_data' );
        $this->loader->add_action( 'post_edit_form_tag', $plugin_admin, 'wcpoa_attachment_edit_form' );
        $this->loader->add_action( 'add_meta_boxes', $plugin_admin, 'wcpoa_order_add_meta_boxes' );
        $this->loader->add_action( 'admin_head', $plugin_admin, 'wcpoa_free_active_menu' );
        $this->loader->add_action( 'add_meta_boxes_' . $screen_id, $plugin_admin, 'wcpoa_add_my_meta_box' );
        $this->loader->add_action( 'load-' . $screen_id, $plugin_admin, 'wcpoa_add_my_meta_box' );
        $this->loader->add_action( 'admin_head', $plugin_admin, 'wcpoa_remove_admin_menus' );
        $this->loader->add_action( 'wp_ajax_wcpoa_plugin_setup_wizard_submit', $plugin_admin, 'wcpoa_plugin_setup_wizard_submit' );
        $this->loader->add_action( 'admin_init', $plugin_admin, 'wcpoa_send_wizard_data_after_plugin_activation' );
        // Footer review text
        if ( !empty( $page ) && (false !== strpos( $page, 'wcpoa' ) || false !== strpos( $page, 'woocommerce_product_attachment' )) ) {
            $this->loader->add_filter( 'admin_footer_text', $plugin_admin, 'wcpoa_admin_footer_review' );
            remove_filter( 'update_footer', 'core_update_footer' );
        }
    }

    /**
     * The name of the plugin used to uniquely identify it within the context of
     * WordPress and to define internationalization functionality.
     *
     * @since     1.0.0
     * @return    string    The name of the plugin.
     */
    public function get_plugin_name() {
        return $this->plugin_name;
    }

    /**
     * Retrieve the version number of the plugin.
     *
     * @since     1.0.0
     * @return    string    The version number of the plugin.
     */
    public function get_version() {
        return $this->version;
    }

    /**
     * Register all of the hooks related to the public-facing functionality
     * of the plugin.
     *
     * @since    1.0.0
     * @access   private
     */
    private function define_public_hooks() {
        $current_url = filter_input( INPUT_SERVER, 'REQUEST_URI', FILTER_SANITIZE_SPECIAL_CHARS );
        $plugin_public = new Woocommerce_Product_Attachment_Public($this->get_plugin_name(), $this->get_version());
        $wcpoa_attachments_show_in_email = get_option( 'wcpoa_attachments_show_in_email' );
        $wcpoa_att_btn_in_order_list = get_option( 'wcpoa_att_btn_in_order_list' );
        $wcpoa_att_in_my_acc = get_option( 'wcpoa_att_in_my_acc' );
        $wcpoa_att_in_thankyou = get_option( 'wcpoa_att_in_thankyou' );
        $this->loader->add_action( 'wp_enqueue_scripts', $plugin_public, 'wcpoa_enqueue_styles' );
        $this->loader->add_action( 'wp_enqueue_scripts', $plugin_public, 'wcpoa_enqueue_scripts' );
        $this->loader->add_filter( 'woocommerce_product_tabs', $plugin_public, 'wcpoa_new_product_tab' );
        $this->loader->add_action( 'woocommerce_account_wcpoa_attachment_tab_endpoint', $plugin_public, 'wcpoa_attachment_content' );
        $this->loader->add_action(
            'woocommerce_new_order_item',
            $plugin_public,
            'wcpoa_add_values_to_order_item_meta',
            1,
            3
        );
        $this->loader->add_action( 'init', $plugin_public, 'wcpoa_download_file' );
        if ( $wcpoa_attachments_show_in_email === 'yes' ) {
            $this->loader->add_action( 'woocommerce_email_header', $plugin_public, 'wcpoa_woocommerce_email_add_css_to_email_attachment' );
            $this->loader->add_filter(
                'woocommerce_email_after_order_table',
                $plugin_public,
                'wcpoa_woocommerce_email_order_attachment',
                20,
                4
            );
        }
        if ( $wcpoa_att_btn_in_order_list === 'wcpoa_att_btn_in_order_list_enable' ) {
            if ( $wcpoa_att_in_my_acc === 'wcpoa_att_in_my_acc_enable' ) {
                $this->loader->add_filter(
                    'woocommerce_account_orders_columns',
                    $plugin_public,
                    'wcpoa_woocommerce_add_account_orders_column',
                    10,
                    1
                );
                $this->loader->add_action( 'woocommerce_my_account_my_orders_column_wcpoa-attachment', $plugin_public, 'wcpoa_woocommerce_add_account_orders_column_rows' );
            }
        }
        if ( $wcpoa_att_in_my_acc === 'wcpoa_att_in_my_acc_enable' ) {
            $this->loader->add_action(
                'woocommerce_view_order',
                $plugin_public,
                'wcpoa_order_data_show_my_account',
                1,
                2
            );
        }
        if ( $wcpoa_att_in_thankyou === 'wcpoa_att_in_thankyou_enable' ) {
            $this->loader->add_action(
                'woocommerce_thankyou',
                $plugin_public,
                'wcpoa_order_data_show_on_thankyou',
                1,
                2
            );
        }
    }

    /**
     * Return the plugin action links.  This will only be called if the plugin
     * is active.
     *
     * @since 1.0.0
     * @param array $actions associative array of action names to anchor tags
     * @return array associative array of plugin action links
     */
    public function plugin_action_links(
        $actions,
        $plugin_file,
        $plugin_data,
        $context
    ) {
        $custom_actions = array(
            'configure' => sprintf( '<a href="%s">%s</a>', admin_url( 'admin.php?page=woocommerce_product_attachment' ), __( 'Settings', 'woocommerce-product-attachment' ) ),
            'docs'      => sprintf( '<a href="%s" target="_blank">%s</a>', esc_url( 'https://docs.thedotstore.com/category/353-premium-plugin-settings' ), __( 'Docs', 'woocommerce-product-attachment' ) ),
            'support'   => sprintf( '<a href="%s" target="_blank">%s</a>', esc_url( 'https://www.thedotstore.com/support/' ), __( 'Support', 'woocommerce-product-attachment' ) ),
        );
        // add the links to the front of the actions list
        return array_merge( $custom_actions, $actions );
    }

    /**
     * Add review stars in plugin row meta
     *
     * @since 1.0.0
     */
    public function plugin_row_meta_action_links(
        $plugin_meta,
        $plugin_file,
        $plugin_data,
        $status
    ) {
        if ( isset( $plugin_data['TextDomain'] ) && $plugin_data['TextDomain'] !== 'woocommerce-product-attachment' ) {
            return $plugin_meta;
        }
        $url = '';
        $url = esc_url( 'https://wordpress.org/plugins/woo-product-attachment/#reviews' );
        $plugin_meta[] = sprintf( '<a href="%s" target="_blank" style="color:#f5bb00;">%s</a>', $url, esc_html( '★★★★★' ) );
        return $plugin_meta;
    }

    /**
     * Run the loader to execute all of the hooks with WordPress.
     *
     * @since    1.0.0
     */
    public function run() {
        $this->loader->run();
    }

    /**
     * The reference to the class that orchestrates the hooks with the plugin.
     *
     * @since     1.0.0
     * @return    Woocommerce_Product_Attachment_Loader    Orchestrates the hooks of the plugin.
     */
    public function get_loader() {
        return $this->loader;
    }

}
