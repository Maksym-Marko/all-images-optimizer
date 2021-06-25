<?php

// Exit if accessed directly
if ( ! defined( 'ABSPATH' ) ) exit;

class MXAIO_FrontEnd_Main
{

	/*
	* MXAIO_FrontEnd_Main constructor
	*/
	public function __construct()
	{

	}

	/*
	* Additional classes
	*/
	public function mxaio_additional_classes()
	{
		
	}

}

// Initialize
$initialize_admin_class = new MXAIO_FrontEnd_Main();

// include classes
$initialize_admin_class->mxaio_additional_classes();