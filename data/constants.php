<?php
/**
 * Defines plugin constants.
 *
 * @package Resize-Image-Before-Upload
 */

$plugin_file = realpath( __DIR__ . '/../resize-image-before-upload.php' );

/*
 * The URL to the plugin.
 */
define( 'RIBU_URL', plugin_dir_url( $plugin_file ) );

/*
 * The filesystem directory path to the plugin.
 */
define( 'RIBU_DIR', plugin_dir_path( $plugin_file ) );

/*
 * The version of the plugin.
 */
define( 'RIBU_VERSION', get_file_data( $plugin_file, [ 'Version' ] )[0] );

/*
 * The filename of the plugin including the path.
 */
define( 'RIBU_FILE', $plugin_file );

/*
 * Plugin basename.
 */
define( 'RIBU_BASENAME', plugin_basename( RIBU_FILE ) );
