<?php

// Exit if accessed directly
if ( ! defined( 'ABSPATH' ) ) exit;

class MXAIO_Enqueue_Scripts
{

	/*
	* MXAIO_Enqueue_Scripts
	*/
	public function __construct()
	{

	}

	/*
	* Registration of styles and scripts
	*/
	public static function mxaio_register()
	{

		// register scripts and styles
		add_action( 'admin_enqueue_scripts', [ 'MXAIO_Enqueue_Scripts', 'mxaio_enqueue' ] );

	}

		public static function mxaio_enqueue()
		{

			wp_enqueue_style( 'mxaio_font_awesome', MXAIO_PLUGIN_URL . 'assets/font-awesome-4.6.3/css/font-awesome.min.css' );

			wp_enqueue_style( 'mxaio_admin_style', MXAIO_PLUGIN_URL . 'includes/admin/assets/css/style.css', [ 'mxaio_font_awesome' ], MXAIO_PLUGIN_VERSION, 'all' );

			// include Vue.js
				// dev version
				// wp_enqueue_script( 'mx_ddp_vue_js', MXAIO_PLUGIN_URL . 'includes/admin/assets/add/vue_js/vue.dev.js', [], MXAIO_PLUGIN_VERSION, true );

				// production version
				wp_enqueue_script( 'mx_ddp_vue_js', MXAIO_PLUGIN_URL . 'includes/admin/assets/add/vue_js/vue.production.js', [], MXAIO_PLUGIN_VERSION, true );

			wp_enqueue_script( 'mxaio_admin_script', MXAIO_PLUGIN_URL . 'includes/admin/assets/js/script.js', ['jquery', 'mx_ddp_vue_js'], MXAIO_PLUGIN_VERSION, true );


			wp_localize_script( 'mxaio_admin_script', 'mxaio_admin_localize', [

				'ajaxurl' 			=> admin_url( 'admin-ajax.php' ),
				'nonce' 			=> wp_create_nonce( 'mxaio_nonce_request' )

			] );

		}

}