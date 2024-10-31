<?php
/**
 * Class to handle plugin options.
 *
 * @package Resize-Image-Before-Upload
 */

namespace Resize_Image_Before_Upload;

use Exception;

/**
 * Manages Options using the WordPress options API.
 */
final class Options {

    /**
     * All options.
     *
     * Values:
     *  'default':   Default option value.           If not set, equals false.
     *  'autoload':  Whether to autoload the option. If not set, equals true.
     *
     * @var array
     */
    public $all_options = [];

    /**
     * Settings options.
     *
     * @var array
     */
    public $settings_options = [];

    /**
     * Constructor.
     */
    public function __construct() {
        $this->all_options = require RIBU_DIR . 'data/options.php';

        $this->define_settings_options();
    }

    /**
     * Defines settings options.
     */
    private function define_settings_options() {
        foreach ( $this->all_options as $option => $option_data ) {
            if ( preg_match( '@^resize-image-before-upload-setting-@', $option ) ) {
                $this->settings_options[] = $option;
            }
        }
    }

    /**
     * Validates option name, and if it does not exist, throws an exception.
     *
     * @param string $option_name Name of the option to validate.
     *
     * @throws Exception Exception.
     */
    private function validation_option( $option_name ) {
        if ( ! array_key_exists( $option_name, $this->all_options ) ) {
            throw new Exception( Resize_Image_Before_Upload::$name . ': ' . esc_html__( 'Unknown option name:', 'tmm-wp-plugins-core' ) . ' ' . esc_html( $option_name ) );
        }
    }

    /**
     * Gets the option value. Returns the default value if the value does not exist.
     *
     * @param string $option_name Name of the option to get.
     *
     * @return mixed Option value.
     *
     * @throws Exception Exception.
     */
    public function get( $option_name ) {
        try {
            $this->validation_option( $option_name );
        } catch ( Exception $e ) {
            $option_name = Resize_Image_Before_Upload::$slug . '-' . $option_name;
            $this->validation_option( $option_name );
        }

        $option_data = $this->all_options[ $option_name ];

        return get_option( $option_name, array_key_exists( 'default', $option_data ) ? $option_data['default'] : false );
    }

    /**
     * Sets the option. Update the value if the option for the given name already exists.
     *
     * @param string $option_name Name of the option to set.
     * @param mixed  $value       Value to set for the option.
     *
     * @throws Exception Exception.
     */
    public function set( $option_name, $value ) {
        try {
            $this->validation_option( $option_name );
        } catch ( Exception $e ) {
            $option_name = Resize_Image_Before_Upload::$slug . '-' . $option_name;
            $this->validation_option( $option_name );
        }

        $option_data = $this->all_options[ $option_name ];

        update_option( $option_name, $value, array_key_exists( 'autoload', $option_data ) ? $option_data['autoload'] : null );
    }

    /**
     * Deletes the option value.
     *
     * @param string $option_name Name of the option to delete.
     *
     * @throws Exception Exception.
     */
    public function delete( $option_name ) {
        try {
            $this->validation_option( $option_name );
        } catch ( Exception $e ) {
            $option_name = Resize_Image_Before_Upload::$slug . '-' . $option_name;
            $this->validation_option( $option_name );
        }

        delete_option( $option_name );
    }
}
