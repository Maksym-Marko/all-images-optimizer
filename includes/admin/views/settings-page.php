<div class="mx-main-page-text-wrap">
	
	<h1><?php echo __( 'Cut down uploads size', 'mxaio-domain' ); ?></h1>

	<h2 class="mx_warning">
		Before you start optimization, please, make a backup of the “uploads” folder!
	</h2>

	<div id="mx_optimizer_app">

		<!-- progress bar -->
		<mx_progress_bar
			v-if="progress"
		></mx_progress_bar>

		<!-- errors -->
		<div 
			v-if="errors.length"
			class="mx_errors_container"
		>
			
			<p v-for="error in errors">
				{{ error }}
			</p>

		</div>

		<!-- checking form -->
		<mx_checking_form
			v-if="!uploads_is_checked"
			:uploads_is_checked="uploads_is_checked"
			:nonce="nonce"
			:ajaxurl="ajaxurl"
			@progress="setProgress"
			@images_data="setImagesData"
			@push_error="setErrors"
		></mx_checking_form>

		<!-- display data of progress -->
		<mx_data_of_progress
			:number_of_images="images_data.length"
			:optimized_imgs="optimized_imgs"
		></mx_data_of_progress>

		<!-- notifications -->
		<mx_notifications
			:notifications="notifications"
			v-if="optimized_imgs.length !== images_data.length"
		></mx_notifications>

		<!-- run optimization form -->
		<mx_run_optimization_form
			v-if="uploads_is_checked"
			:images_data="images_data"
			:nonce="nonce"
			:ajaxurl="ajaxurl"
			@optimized_img="addOptimizesImg"
			@progress="setProgress"
			@push_notification="setNotifications"
		></mx_run_optimization_form>

		<!-- potimization tracker -->
		<mx_potimization_tracker
			:optimized_imgs="optimized_imgs"
		></mx_potimization_tracker>

	</div>

</div>