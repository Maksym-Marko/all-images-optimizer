<?php

// Exit if accessed directly
if ( ! defined( 'ABSPATH' ) ) exit;

// create table class
require_once MXAIO_PLUGIN_ABS_PATH . 'includes/core/create-table.php';

class MXAIO_Basis_Plugin_Class
{

	private static $table_slug = MXAIO_TABLE_SLUG;

	public static function activate()
	{


	}

	public static function deactivate()
	{

		// Rewrite rules
		// flush_rewrite_rules();

	}

	/*
	* This function sets the option in the table for CPT rewrite rules
	*/
	public static function create_option_for_activation()
	{

		// add_option( 'mxaio_flush_rewrite_rules', 'go_flush_rewrite_rules' );

	}

}