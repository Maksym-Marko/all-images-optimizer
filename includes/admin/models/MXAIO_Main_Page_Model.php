<?php

use \Gumlet\ImageResize;

// Exit if accessed directly
if ( ! defined( 'ABSPATH' ) ) exit;

/**
* Main page Model
*/
class MXAIO_Main_Page_Model extends MXAIO_Model
{

	public static $list_of_files 			= [];

	public static $count_of_files 			= 0;

	public static $tmp_folder 				= MXAIO_PLUGIN_ABS_PATH . 'assets/mx_tmp/';

	/*
	* Observe function
	*/
	public static function mxaio_wp_ajax()
	{

		// prepate
		add_action( 'wp_ajax_mxaio_prepare_to_resize', [ 'MXAIO_Main_Page_Model', 'prepare_to_resize' ], 10, 1 );

		// resize
		add_action( 'wp_ajax_mxaio_image_resize', [ 'MXAIO_Main_Page_Model', 'resize_image' ], 10, 1 );

	}

	public static function resize_image()
	{

		// Checked POST nonce is not empty
		if( empty( $_POST['nonce'] ) ) wp_die();

		// Checked or nonce match
		if( wp_verify_nonce( $_POST['nonce'], 'mxaio_nonce_request' ) ) {

			if( isset( $_POST['image'] ) ) {				

				$quality = intval( $_POST['quality'] );

				$image_path = sanitize_text_field( $_POST['image']['image_path'] );

				$image_path = str_replace( "\\\\", "\\", $image_path );

				$file_name = sanitize_text_field( $_POST['image']['file_name'] );				

				self::mx_resizer( $image_path, $file_name, $quality );

			}

		}

		wp_die();

	}

	/*
	* Prepare for data updates
	*/
	public static function prepare_to_resize()
	{		
		
		// Checked POST nonce is not empty
		if( empty( $_POST['nonce'] ) ) wp_die();

		// Checked or nonce match
		if( wp_verify_nonce( $_POST['nonce'], 'mxaio_nonce_request' ) ) {

			$uploads_folder_path = wp_upload_dir();

			// D:\OpenServer\domains\all-images-optimizer.local/wp-content/uploads
			$uploads_basedir = $uploads_folder_path['basedir'];

			if( ! is_dir( self::$tmp_folder ) ) {

				mkdir( self::$tmp_folder, 0777, true );

			}

			self::mx_scan_dir( $uploads_basedir . '/' );	

			self::mx_get_images_to_resize();

		}

		wp_die();

	}

		public static function mx_scan_dir( $dir )
		{

			$current_dirs = scandir( $dir );			
			
			// each of all folders and files
			foreach ( $current_dirs as $key => $value ) {

				// exclude '.', '..'
				if( ! in_array( $value, [ '.', '..', '.htaccess' ] ) ) :

					// find fiels
					if( self::mx_is_file( $value ) ) :					

						// optimize file
						$image_path = $dir . $value;

						$originalSize = filesize( $image_path );

						$new_image = [
							'file_name' 	=> $value,
							'image_path' 	=> $image_path
						];

						array_push( self::$list_of_files, $new_image );

					// find directories
					else :

						if( is_dir( $dir . $value ) ) {

							self::mx_scan_dir( $dir . $value . '/' );

						}						

					endif;

				endif;

			}			

		}

		public static function mx_get_images_to_resize() {

			echo wp_json_encode( self::$list_of_files );

		}
	

	/*
	* This function checks the current item. Return true if an element is a file
	*/
	public static function mx_is_file( $obj )
	{

		$mime_types = [ 

			'.jpg',
			'.jpeg',
			'.png',
			'.webp'

		];

		foreach ( $mime_types as $mime_type ) {
			
			if( strpos( $obj, $mime_type ) ) :

				return true;

				break;

			endif;

		}

		return false;

	}


		public static function mx_resizer( $image_path, $image_name, $quality = 82 )
		{

			$res = [
				'image_path' 				=> $image_path,
				'image_name' 				=> $image_name,
				'error' 					=> '',
				'before_optimization'		=> 0,
				'after_optimization'		=> 0,
				'percent' 					=> 0
			];

			if (!isset($image_path) || !file_exists($image_path)) {

				$res['error'] = 'Invalid image path';

			} else {

				// Get the image and store the original size
				$image = $image_path;
				$originalSize = filesize($image);

				$resizer = new ImageResize($image);
				$resizer->quality_jpg = $quality;
				$resizer->quality_webp = $quality;
				$resizer->scale( 100 );

				$tmp_file = self::$tmp_folder . $image_name;

				$_save = $resizer->save( $image, null, null, null, false, $tmp_file);

				if( $_save == 'not optimized' ) {

					$res['error'] = 'Is not Optimized!';

					echo wp_json_encode( $res );

					return;

				}

				// Clear stat cache to get the optimized size
				clearstatcache();

				// Check the optimized size
				$optimizedSize = filesize($image);
				$percentChange = (1 - $optimizedSize / $originalSize) * 100;

				$res['before_optimization'] = $originalSize;

				$res['after_optimization'] = $optimizedSize;

				$percentChange = number_format( $percentChange, 2 );

				$res['percent'] = $percentChange;

			}

			echo wp_json_encode( $res );			

		}
	
}