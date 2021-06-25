<?php

// Exit if accessed directly
if ( ! defined( 'ABSPATH' ) ) exit;

class MXAIO_Main_Page_Controller extends MXAIO_Controller
{
	
	public function index()
	{

		$model_inst = new MXAIO_Main_Page_Model();

		$data = $model_inst->mxaio_get_row( NULL, 'product_id', 1 );

		return new MXAIO_View( 'main-page', $data );

	}

	public function submenu()
	{

		return new MXAIO_View( 'sub-page' );

	}

	public function hidemenu()
	{

		return new MXAIO_View( 'hidemenu-page' );

	}

	public function settings_menu_item_action()
	{

		return new MXAIO_View( 'settings-page' );

	}

}