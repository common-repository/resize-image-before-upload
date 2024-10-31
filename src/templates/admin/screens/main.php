<?php
/**
 * Template for admin menu
 *
 * @package Resize-Image-Before-Upload
 */

use Resize_Image_Before_Upload\Resize_Image_Before_Upload;
use WP_Plugins_Core\Setting_Fields;

?>

<div class="wrap">
    <h1 class="ribu-header">
        <?php esc_html_e( 'Resize Image Before Upload', 'ribu' ); ?>
    </h1>

    <form>
        <div class="ribu-container ribu-align-items-flex-start">
            <div class="wp-plugins-core-tabs-container">
                <div class="wp-plugins-core-tab-content" data-tab-name="general">
                    <h2 class="wp-plugins-core-tab-heading"><?php esc_html_e( 'General Settings', 'ribu' ); ?></h2>

                    <?php

                    $settings = [
                        [
                            'title' => __( 'General Settings', 'ribu' ),
                            'type'  => 'title',
                            'desc'  => '',
                            'id'    => 'settings',
                        ],

                        [
                            'title' => __( 'Max size (pixels)', 'ribu' ),
                            'id'    => 'max-size',
                            'desc'  => __( 'The images bigger than this threshold are scaled down to it.', 'ribu' ),
                            'value' => Resize_Image_Before_Upload::$options->get( 'setting-max-images-size-threshold' ),
                            'type'  => 'number',
                            'css'   => 'width: 65px;',
                        ],
                        [
                            'title' => __( 'Use WP-compat (old) assets uploading script', 'ribu' ),
                            'id'    => 'use-wp-compat-version',
                            'desc'  => __( "Use version 2.1.9 (guaranteed better compatibility) instead of 2.3.9 (uses a better scaling algorithm). Both versions have been proven to work in all test cases, but in some edge cases the WP compatibility version may work more reliably.<br><br>If you're the sole contributor uploading images to your site and the latest version (2.3.9) meets your needs, feel free to use it. The improved scaling algorithm could offer noticeable enhancements. If any issue arises, you can effortlessly switch back to the WP compatible version (2.1.9).", 'ribu' ),
                            'value' => '1' === Resize_Image_Before_Upload::$options->get( 'setting-use-wp-compat-version' ) ? 'yes' : 'no',
                            'type'  => 'checkbox',
                        ],
                        [
                            'title'   => __( 'Keep enabled on', 'ribu' ),
                            'desc'    => __( 'Most visual page editors (Elementor, WPBakery etc) are consider as front-end (not admin part) when editing a page.', 'ribu' ),
                            'id'      => 'keep-enabled-on',
                            'type'    => 'select',
                            'options' => [
                                'everywhere' => __( 'Everywhere', 'ribu' ),
                                'frontend'   => __( 'Only front-end', 'ribu' ),
                                'admin'      => __( 'Only admin panel', 'ribu' ),
                            ],
                            'value'   => Resize_Image_Before_Upload::$options->get( 'setting-keep-enabled-on' ),
                        ],
                        [
                            'type' => 'sectionend',
                            'id'   => 'settings',
                        ],
                    ];

                    Setting_Fields::output_fields( $settings );
                    ?>
                </div>
            </div>
        </div>
        <hr>
        <div class="ribu-container">
            <div class="ribu-column mt-m20 mb-20">
                <?php submit_button( __( 'Save changes', 'ribu' ), [ 'primary', 'ribu-submit' ] ); ?>
            </div>
        </div>
        <div class="ribu-container">
            <div class="ribu-row ribu-justify-center">
                <a class="ribu-link ribu-reset-settings-link mt-15"><?php esc_html_e( 'Reset All Settings', 'ribu' ); ?></a>
            </div>
        </div>
    </form>
</div>
