<?php
/**
 * Assets for main screen
 *
 * Loads assets (JS, CSS), adds data for them.
 *
 * @package Resize-Image-Before-Upload
 */

namespace Resize_Image_Before_Upload\Assets\Menu\Screens;

use Resize_Image_Before_Upload\Admin_Menu;

/**
 * Assets class.
 */
final class Main {

    /**
     * Inits.
     */
    public static function init() {
        $class = __CLASS__;
        add_action(
            'admin_enqueue_scripts',
            function () use ( $class ) {
                new $class();
            }
        );
    }

    /**
     * Constructor.
     */
    public function __construct() {
        $this->styles();
        $this->scripts();
    }

    /**
     * Loads styles.
     */
    private function styles() {
        wp_register_style(
            'ribu-admin-style',
            RIBU_URL . 'assets-build/admin/index.css',
            [],
            RIBU_VERSION
        );

        wp_enqueue_style(
            'ribu-admin-main-screen-style',
            RIBU_URL . 'assets-build/admin/screens/main.css',
            [ 'ribu-admin-style', 'tmm-wp-plugins-core-admin-style' ],
            RIBU_VERSION
        );
    }

    /**
     * Loads scripts.
     */
    private function scripts() {
        wp_register_script(
            'ribu-admin-script',
            RIBU_URL . 'assets-build/admin/index.js',
            [],
            RIBU_VERSION,
            true
        );

        wp_enqueue_script(
            'ribu-admin-main-screen-script',
            RIBU_URL . 'assets-build/admin/screens/main.js',
            [ 'ribu-admin-script', 'tmm-wp-plugins-core-admin-script' ],
            RIBU_VERSION,
            true
        );

        wp_localize_script(
            'ribu-admin-main-screen-script',
            'ribu',
            [
                'nonceToken' => wp_create_nonce( 'ribu-menu' ),
                'txt'        => Admin_Menu::get_texts(),
            ]
        );
    }
}
