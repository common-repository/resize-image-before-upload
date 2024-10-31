<?php
/**
 * AJAX handlers.
 *
 * @package Resize-Image-Before-Upload
 */

namespace Resize_Image_Before_Upload;

use Exception;

/**
 * Manages AJAX.
 */
final class AJAX {

    /**
     * Adds the menu and inits assets loading for it.
     */
    public function __construct() {
        $this->add_ajax_events();
    }

    /**
     * Loads AJAX handlers.
     */
    private function add_ajax_events() {
        $admin_ajax_events = [
            'save',
            'reset_settings',
        ];

        foreach ( $admin_ajax_events as $ajax_event ) {
            add_action( 'wp_ajax_ribu_' . $ajax_event, [ $this, $ajax_event ] );
        }
    }

    /**
     * Save plugin settings.
     *
     * @throws Exception Exception.
     */
    public function save() {
        /*
         * Nonce check.
         */
        check_ajax_referer( 'ribu-menu', 'nonceToken' );

        if ( array_key_exists( 'maxSize', $_REQUEST ) ) {
            $max_images_size_threshold = max( (int) sanitize_text_field( wp_unslash( $_REQUEST['maxSize'] ) ), 100 );
            Resize_Image_Before_Upload::$options->set( 'setting-max-images-size-threshold', $max_images_size_threshold );
        }

        if ( array_key_exists( 'useWpCompatVersion', $_REQUEST ) ) {
            $use_wp_compat_version = (string) ( '1' === sanitize_text_field( wp_unslash( $_REQUEST['useWpCompatVersion'] ) ) );
            Resize_Image_Before_Upload::$options->set( 'setting-use-wp-compat-version', $use_wp_compat_version );
        }

        if ( array_key_exists( 'keepEnabledOn', $_REQUEST ) ) {
            $keep_enabled_on = sanitize_text_field( wp_unslash( $_REQUEST['keepEnabledOn'] ) );
            Resize_Image_Before_Upload::$options->set( 'setting-keep-enabled-on', $keep_enabled_on );
        }

        wp_send_json_success();
    }

    /**
     * Resets all plugin settings.
     *
     * @throws Exception Exception.
     */
    public function reset_settings() {
        /*
         * Nonce check.
         */
        check_ajax_referer( 'ribu-menu', 'nonceToken' );

        // Delete all settings options.
        foreach ( Resize_Image_Before_Upload::$options->settings_options as $option ) {
            Resize_Image_Before_Upload::$options->delete( $option );
        }

        // Set them to defaults to trigger all event listeners.
        foreach ( Resize_Image_Before_Upload::$options->settings_options as $option ) {
            $option_data = Resize_Image_Before_Upload::$options->all_options[ $option ];
            if ( array_key_exists( 'default', $option_data ) ) {
                Resize_Image_Before_Upload::$options->set( $option, $option_data['default'] );
            }
        }

        wp_send_json_success();
    }
}
