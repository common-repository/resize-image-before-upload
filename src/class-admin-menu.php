<?php
/**
 * Admin Menus
 *
 * Adds admin menus.
 *
 * @package Resize-Image-Before-Upload
 */

namespace Resize_Image_Before_Upload;

/**
 * Class Admin_Menu.
 */
final class Admin_Menu {

    /**
     * Adds the menu and inits assets loading for it.
     */
    public function __construct() {
        add_action( 'admin_menu', [ $this, 'init_menu' ] );
    }

    /**
     * Adds the menu and inits assets loading for it.
     */
    public function init_menu() {
        $menu_slug = add_submenu_page(
            'upload.php',
            __( 'Resize Before Upload', 'ribu' ),
            __( 'Resize Before Upload', 'ribu' ),
            'manage_options',
            'ribu',
            function () {
                require_once RIBU_DIR . 'src/templates/admin/screens/main.php';
            }
        );

        add_action(
            'load-' . $menu_slug,
            [ 'Resize_Image_Before_Upload\Assets\Menu\Screens\Main', 'init' ]
        );
    }

    /**
     * Returns an array of texts for admin scripts.
     */
    public static function get_texts() {
        return [
            'error'                  => __( 'Error', 'ribu' ),
            'resetAllSettingsNotice' => __( 'Are you sure you want to reset all plugin settings?', 'ribu' ),
            'yesResetAllSettings'    => __( 'Yes, reset all plugin settings!', 'ribu' ),
            'no'                     => __( 'No', 'ribu' ),
        ];
    }
}
