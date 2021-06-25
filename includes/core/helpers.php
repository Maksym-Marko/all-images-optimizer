<?php

// Exit if accessed directly
if ( ! defined( 'ABSPATH' ) ) exit;

/*
* Require class for admin panel
*/
function mxaio_require_class_file_admin( $file ) {

	require_once MXAIO_PLUGIN_ABS_PATH . 'includes/admin/classes/' . $file;

}


/*
* Require class for frontend panel
*/
function mxaio_require_class_file_frontend( $file ) {

	require_once MXAIO_PLUGIN_ABS_PATH . 'includes/frontend/classes/' . $file;

}

/*
* Require a Model
*/
function mxaio_use_model( $model ) {

	require_once MXAIO_PLUGIN_ABS_PATH . 'includes/admin/models/' . $model . '.php';

}

/*
* Debugging
*/
function mxaio_debug_to_file( $content ) {

	$content = mxaio_content_to_string( $content );

	$path = MXAIO_PLUGIN_ABS_PATH . 'mx-debug' ;

	if( ! file_exists( $path ) ) :

		mkdir( $path, 0777, true );

		file_put_contents( $path . '/mx-debug.txt', $content );

	else :

		file_put_contents( $path . '/mx-debug.txt', $content );

	endif;

}
	// pretty debug text to the file
	function mxaio_content_to_string( $content ) {

		ob_start();

		var_dump( $content );

		return ob_get_clean();

	}