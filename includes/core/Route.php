<?php

// Exit if accessed directly
if ( ! defined( 'ABSPATH' ) ) exit;

// require Route-Registrar.php
require_once MXAIO_PLUGIN_ABS_PATH . 'includes/core/Route-Registrar.php';

/*
* Routes class
*/
class MXAIO_Route
{

	public function __construct()
	{
		// ...
	}
	
	public static function mxaio_get( ...$args )
	{

		return new MXAIO_Route_Registrar( ...$args );

	}
	
}