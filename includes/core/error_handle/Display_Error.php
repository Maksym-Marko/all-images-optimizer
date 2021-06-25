<?php

// Exit if accessed directly
if ( ! defined( 'ABSPATH' ) ) exit;

/*
* Error Handle calss
*/
class MXAIO_Display_Error
{

	/**
	* Error notice
	*/
	public $mxaio_error_notice = '';

	public function __construct( $mxaio_error_notice )
	{

		$this->mxaio_error_notice = $mxaio_error_notice;

	}

	public function mxaio_show_error()
	{
		add_action( 'admin_notices', function() { ?>

			<div class="notice notice-error is-dismissible">

			    <p><?php echo $this->mxaio_error_notice; ?></p>
			    
			</div>
		    
		<?php } );
	}

}