<?php
/*
Plugin Name: Cut down uploads size
Plugin URI: https://github.com/Maxim-us/
Description: The “Cut down uploads size” plugin allows you to optimize all the images from your “uploads” folder. 
Author: Maksym Marko
Version: 1.2
Author URI: https://markomaksym.com.ua/
*/

// Exit if accessed directly
if ( ! defined( 'ABSPATH' ) ) exit;

/*
* Unique string - MXAIO
*/

/*
* Define MXAIO_PLUGIN_PATH
*
* E:\OpenServer\domains\my-domain.com\wp-content\plugins\all-images-optimizer\all-images-optimizer.php
*/
if ( ! defined( 'MXAIO_PLUGIN_PATH' ) ) {

	define( 'MXAIO_PLUGIN_PATH', __FILE__ );

}

/*
* Define MXAIO_PLUGIN_URL
*
* Return http://my-domain.com/wp-content/plugins/all-images-optimizer/
*/
if ( ! defined( 'MXAIO_PLUGIN_URL' ) ) {

	define( 'MXAIO_PLUGIN_URL', plugins_url( '/', __FILE__ ) );

}

/*
* Define MXAIO_PLUGN_BASE_NAME
*
* 	Return all-images-optimizer/all-images-optimizer.php
*/
if ( ! defined( 'MXAIO_PLUGN_BASE_NAME' ) ) {

	define( 'MXAIO_PLUGN_BASE_NAME', plugin_basename( __FILE__ ) );

}

/*
* Define MXAIO_TABLE_SLUG
*/
if ( ! defined( 'MXAIO_TABLE_SLUG' ) ) {

	define( 'MXAIO_TABLE_SLUG', 'mxaio_mx_table' );

}

/*
* Define MXAIO_PLUGIN_ABS_PATH
* 
* E:\OpenServer\domains\my-domain.com\wp-content\plugins\all-images-optimizer/
*/
if ( ! defined( 'MXAIO_PLUGIN_ABS_PATH' ) ) {

	define( 'MXAIO_PLUGIN_ABS_PATH', dirname( MXAIO_PLUGIN_PATH ) . '/' );

}

/*
* Define MXAIO_PLUGIN_VERSION
*/
if ( ! defined( 'MXAIO_PLUGIN_VERSION' ) ) {

	// version
	define( 'MXAIO_PLUGIN_VERSION', '1.2' ); // Must be replaced before production on for example '1.0'

}

/*
* Define MXAIO_MAIN_MENU_SLUG
*/
if ( ! defined( 'MXAIO_MAIN_MENU_SLUG' ) ) {

	// version
	define( 'MXAIO_MAIN_MENU_SLUG', 'mxaio-all-images-optimizer-menu' );

}

/**
 * activation|deactivation
 */
require_once plugin_dir_path( __FILE__ ) . 'install.php';

/*
* Registration hooks
*/
// Activation
register_activation_hook( __FILE__, [ 'MXAIO_Basis_Plugin_Class', 'activate' ] );

// Deactivation
register_deactivation_hook( __FILE__, [ 'MXAIO_Basis_Plugin_Class', 'deactivate' ] );


/*
* Include the main MXAIOAllImagesOptimizer class
*/
if ( ! class_exists( 'MXAIOAllImagesOptimizer' ) ) {

	require_once plugin_dir_path( __FILE__ ) . 'includes/final-class.php';

	/*
	* Translate plugin
	*/
	add_action( 'plugins_loaded', 'mxaio_translate' );

	function mxaio_translate()
	{

		load_plugin_textdomain( 'mxaio-domain', false, dirname( plugin_basename( __FILE__ ) ) . '/languages/' );

	}

}