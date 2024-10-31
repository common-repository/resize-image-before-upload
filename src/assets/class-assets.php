<?php
/**
 * Assets
 *
 * Loads assets (JS, CSS), adds data for them.
 *
 * @package Cache-Warmer
 */

namespace Resize_Image_Before_Upload\Assets;

use Resize_Image_Before_Upload\Resize_Image_Before_Upload;

/**
 * Assets class.
 */
final class Assets {

    /**
     * Whether the scripts were supplemented.
     *
     * @var bool
     */
    public static $scripts_were_supplemented = false;

    /**
     * Constructor.
     */
    public function __construct() {
        add_action(
            'admin_enqueue_scripts',
            [ $this, 'modify_default_scripts' ],
            0
        );

        add_action(
            'wp_enqueue_scripts',
            [ $this, 'modify_default_scripts' ],
            0
        );

        add_action( 'wp_default_scripts', [ $this, 'modify_default_scripts' ], 11 );
    }

    /**
     * Modify default scripts.
     *
     * @param \WP_Scripts|null $scripts Scripts.
     */
    public function modify_default_scripts( $scripts = null ) {
        if ( self::$scripts_were_supplemented ) {
            return;
        }

        $keep_enabled_on = Resize_Image_Before_Upload::$options->get( 'setting-keep-enabled-on' );

        if (
            'everywhere' === $keep_enabled_on ||
            'admin' === $keep_enabled_on && doing_action( 'admin_enqueue_scripts' ) ||
            'frontend' === $keep_enabled_on && doing_action( 'wp_enqueue_scripts' )
        ) {
            if ( wp_script_is( 'moxiejs', 'registered' ) && wp_script_is( 'plupload', 'registered' ) ) {
                self::$scripts_were_supplemented = true;

                if ( $scripts instanceof \WP_Scripts ) {
                    $scripts->remove( 'moxiejs' );
                    $scripts->remove( 'plupload' );
                } else {
                    wp_deregister_script( 'moxiejs' );
                    wp_deregister_script( 'plupload' );
                }

                if ( Resize_Image_Before_Upload::$options->get( 'setting-use-wp-compat-version' ) ) {
                    $moxie_script = [
                        'path' => RIBU_URL . 'libs/plupload-2.1.9/js/moxie.min.js',
                        'deps' => [],
                        'ver'  => '1.3.5',
                    ];

                    $plupload_script = [
                        'path' => RIBU_URL . 'libs/plupload-2.1.9/js/plupload.min.js',
                        'deps' => [ 'moxiejs' ],
                        'ver'  => '2.1.9',
                    ];
                } else {
                    $moxie_script = [
                        'path' => RIBU_URL . 'libs/plupload-2.3.9/js/moxie.min.js',
                        'deps' => [],
                        'ver'  => '1.5.7',
                    ];

                    $plupload_script = [
                        'path' => RIBU_URL . 'libs/plupload-2.3.9/js/plupload.min.js',
                        'deps' => [ 'moxiejs' ],
                        'ver'  => '2.3.9',
                    ];
                }

                if ( $scripts instanceof \WP_Scripts ) {
                    $scripts->add( 'moxiejs', $moxie_script['path'], $moxie_script['deps'], $moxie_script['ver'] );
                    $scripts->add( 'plupload', $plupload_script['path'], $plupload_script['deps'], $plupload_script['ver'] );
                } else {
                    wp_enqueue_script( 'moxiejs', $moxie_script['path'], $moxie_script['deps'], $moxie_script['ver'] ); // @codingStandardsIgnoreLine
                    wp_enqueue_script( 'plupload', $plupload_script['path'], $plupload_script['deps'], $plupload_script['ver'] ); // @codingStandardsIgnoreLine
                }

                if ( $scripts instanceof \WP_Scripts ) {
                    $scripts->localize(
                        'plupload',
                        'ribu',
                        [
                            'maxSize' => Resize_Image_Before_Upload::$options->get( 'setting-max-images-size-threshold' ),
                        ]
                    );
                } else {
                    wp_localize_script(
                        'plupload',
                        'ribu',
                        [
                            'maxSize' => Resize_Image_Before_Upload::$options->get( 'setting-max-images-size-threshold' ),
                        ]
                    );
                }
            }
        }
    }
}
