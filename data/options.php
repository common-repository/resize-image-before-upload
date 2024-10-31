<?php
/**
 * Defines plugin options.
 *
 * Format:
 *  'default':   Default option value.            If not set, equals false.
 *  'autoload':  Whether to autoload the option.  If not set, equals true.
 *
 * @package Resize-Image-Before-Upload
 */

return [

    /*
     * Max images size to resize to.
     *
     * The images bigger than this threshold are scaled down to it.
     */
    'resize-image-before-upload-setting-max-images-size-threshold' => [
        'default'  => 2560, // Default threshold from WordPress.
        'autoload' => false,
    ],

    /*
     * Use WP-compat script version instead of the new script version.
     *
     * The images bigger than this threshold are scaled down to it.
     */
    'resize-image-before-upload-setting-use-wp-compat-version' => [
        'default'  => '1',
        'autoload' => false,
    ],

    /*
     * Keep enabled on.
     */
    'resize-image-before-upload-setting-keep-enabled-on' => [
        'default'  => 'everywhere',
        'autoload' => false,
    ],
];
