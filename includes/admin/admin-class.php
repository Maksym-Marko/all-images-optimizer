<?php

require_once('classes/php-image-resize/vendor/autoload.php' );


// Exit if accessed directly
if ( ! defined( 'ABSPATH' ) ) exit;

class MXAIO_Admin_Main
{

	// list of model names used in the plugin
	public $models_collection = [
		'MXAIO_Main_Page_Model'
	];

	/*
	* MXAIO_Admin_Main constructor
	*/
	public function __construct()
	{

	}

	/*
	* Additional classes
	*/
	public function mxaio_additional_classes()
	{

		// enqueue_scripts class
		mxaio_require_class_file_admin( 'enqueue-scripts.php' );

		MXAIO_Enqueue_Scripts::mxaio_register();
		
	}

	/*
	* Models Connection
	*/
	public function mxaio_models_collection()
	{

		// require model file
		foreach ( $this->models_collection as $model ) {
			
			mxaio_use_model( $model );

		}		

	}

	/**
	* registration ajax actions
	*/
	public function mxaio_registration_ajax_actions()
	{

		// ajax requests to main page
		MXAIO_Main_Page_Model::mxaio_wp_ajax();

	}

	/*
	* Routes collection
	*/
	public function mxaio_routes_collection()
	{
		// sub settings menu item
		MXAIO_Route::mxaio_get( 'MXAIO_Main_Page_Controller', 'settings_menu_item_action', 'NULL', [
			'menu_title' => 'Cut down uploads size',
			'page_title' => 'Cut down uploads size'
		], 'settings_menu_item', true );

	}

}

// Initialize
$initialize_admin_class = new MXAIO_Admin_Main();

// include classes
$initialize_admin_class->mxaio_additional_classes();

// include models
$initialize_admin_class->mxaio_models_collection();

// ajax requests
$initialize_admin_class->mxaio_registration_ajax_actions();

// include controllers
$initialize_admin_class->mxaio_routes_collection();