<?php
/**
 * Plugin Name: Resize Image Before Upload
 * Description: A WordPress plugin for client-side uploaded images resize (to do it client-side instead of backend-side and minimize the backend performance hit).
 * Version:     1.0.4
 * Text Domain: ribu
 * Author:      TMM Technology
 * Author URI:  https://tmm.ventures/
 * Requires PHP: 7.4
 *
 * @package Resize-Image-Before-Upload
 */

namespace Resize_Image_Before_Upload;

/**
 * Main Resize_Image_Before_Upload class.
 */
final class Resize_Image_Before_Upload {

    /**
     * AJAX class.
     *
     * @var AJAX
     */
    public static $ajax;

    /**
     * Options.
     *
     * @var Options
     */
    public static $options;

    /**
     * Plugin slug.
     *
     * @var string
     */
    public static $slug;

    /**
     * Plugin version.
     *
     * @var string
     */
    public static $version;

    /**
     * Plugin name.
     *
     * @var string
     */
    public static $name;

    /**
     * Constructor.
     */
    public function __construct() {
        $this->define_constants();
        $this->import_initial_files();

        add_action(
            'plugins_loaded',
            function() {
                new \WP_Plugins_Core\WP_Plugins_Core( $this );

                // Load translations.
                $this->load_plugin_textdomain();

                $this->import_plugin_files();

                // Options.
                self::$options = new Options();

                // Adds plugin settings links to plugins admin screen.
                add_filter( 'plugin_action_links_' . RIBU_BASENAME, [ $this, 'plugin_action_links' ] );

                add_action(
                    'init',
                    function () {

                        // Assets.
                        new Assets\Assets();

                        // Menu.
                        new Admin_Menu();

                        // AJAX Handler.
                        self::$ajax = new AJAX();
                    }
                );
            }
        );
    }

    /**
     * Defines constants.
     */
    private function define_constants() {
        require_once __DIR__ . '/data/constants.php';

        /**
         * Plugin name.
         */
        self::$name = get_file_data( RIBU_FILE, [ 'Plugin Name' ] )[0];

        /**
         * Plugin slug.
         */
        $dir_parts  = explode( '/', RIBU_DIR );
        self::$slug = $dir_parts[ array_search( 'plugins', $dir_parts, true ) + 1 ];

        /**
         * Plugin version.
         */
        self::$version = RIBU_VERSION;
    }

    /**
     * Imports initial plugin files:
     *
     * - WP plugins core.
     */
    private function import_initial_files() {
        $files = [
            'vendor/autoload_packages',
            'vendor/tmmtech/wp-plugins-core/wp-plugins-core',
            'vendor/woocommerce/action-scheduler/action-scheduler',
        ];
        foreach ( $files as $file ) {
            require_once RIBU_DIR . $file . '.php';
        }
    }

    /**
     * Imports plugin files.
     */
    private function import_plugin_files() {
        $src_files = [
            'assets/class-assets',
            'assets/screens/class-assets-main-screen',
            'class-admin-menu',
            'class-options',
            'class-ajax',
        ];
        foreach ( $src_files as $file ) {
            require_once RIBU_DIR . 'src/' . $file . '.php';
        }
    }

    /**
     * Loads text-domain.
     */
    private function load_plugin_textdomain() {
        load_plugin_textdomain(
            'ribu',
            false,
            dirname( RIBU_BASENAME ) . '/languages'
        );
    }

    /**
     * Show settings link on the plugin screen.
     *
     * @param mixed $links Plugin Action links.
     *
     * @return array
     */
    public function plugin_action_links( $links ) {
        $action_links = array(
            'settings' => '<a href="' . admin_url( 'upload.php?page=ribu' ) . '" aria-label="' .
                esc_attr__( 'Settings', 'ribu' ) . '">' . esc_html__( 'Settings', 'ribu' ) . '</a>',
        );
        return array_merge( $action_links, $links );
    }
}
new Resize_Image_Before_Upload();
