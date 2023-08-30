// potimization tracker
Vue.component( 'mx_potimization_tracker', {

	props: {
		optimized_imgs: {
			type: Array
		}
	},

	template: `
		<div
			v-if="optimized_imgs.length"
			id="mx_result_stack"
		>

			<div 
				v-for="image in optimized_imgs"
				class="mx_stack_item"
				v-html="mxStackItem( image )"
			></div>

		</div>
	`,
	methods: {

		mxStackItem( image_data ) {

			if( image_data.error !== '' ) {

				return "File: " + image_data.image_path + "<br>" +
				image_data.error

			} else {

				return "File: " + image_data.image_path + "<br>" +
					"Before Optimization size: " + image_data.before_optimization + "<br>" +
					"After Optimization size: " + image_data.after_optimization + "<br>" +
					"The image is now " + image_data.percent + "% smaller"
				

			}			

		}

	}

} )

// run optimization form
Vue.component( 'mx_notifications', {

	props: {
		notifications: {
			type: Array
		}
	},

	template: `
	<div 
		v-if="notifications.length"
		class="mx_notifications"
	>

		<p v-for="notification in notifications">
			{{ notification.notification }}
		</p>

	</div>
	`

} )

// run optimization form
Vue.component( 'mx_run_optimization_form', {

	props: {
		images_data: {
			required: true,
			type: Array
		},
		nonce: {
			required: true,
			type: String
		},
		ajaxurl: {
			required: true,
			type: String
		}
	},

	template: `
	<form 
		id="mx_run_optimizer"		
	>

		<p 
			v-if="show_button"
			class="mx_quality_selector"
		>
			<label for="mx_image_quality">Choose the JPG Image Quality</label>
			<select
				v-model="mx_image_quality"
			>
				<option
					v-for="index in 99" :key="index"
					v-if="index>0"
				>{{ 100-index }}</option>
			</select>
		</p>

		<button
			v-if="show_button"
			type="submit"
			class="mx_button mx_run_optimizer_btn"
			@click.prevent="runOptimization"
		>Run Optimizer</button>

	</form>
	`,
	data() {
		return {
			show_button: true,
			mx_image_quality: 82,
			interval_soul: null,
			set_interval: true,
			img_index: 0,
			optimized_images: []
		}
	},
	methods: {

		runOptimization() {

			if( confirm( 'Do you really want to optimize all the images?' ) ) {

				this.$emit( 'progress', true )

				this.show_button = false

				let _this = this

				this.interval_soul = setInterval( function() {

					let start_counting = Date.now()

					if( _this.optimized_images.length !== _this.images_data.length ) {

						if( _this.set_interval ) {

							_this.set_interval = false

							let data = {

								'action': 'mxaio_image_resize',
								'nonce': _this.nonce,
								'image': _this.images_data[_this.img_index],
								'quality': _this.mx_image_quality

							}

							jQuery.ajax( {

								url: _this.ajaxurl,
								type: 'POST',
								data: data,
								success: function( response ) {

									if( _this.isJSON( response ) ) {

										let img_data = JSON.parse( response )

										_this.$emit( 'optimized_img', img_data )

										_this.optimized_images.push( img_data )

										_this.img_index = _this.optimized_images.length

										_this.set_interval = true

										// 
										let _time = Date.now() - start_counting 

										console.log( _time )

									}
									
								},

								error: function( response ) {

									// console.log( 'error' + response );

								}

							} )

						}										

					} else {

						clearInterval( _this.interval_soul )

					}

				}, 300 )				

			}

		},

		isJSON( str ) {
			try {
		        JSON.parse(str);
		    } catch (e) {
		        return false;
		    }
		    return true;
		}

	},

	watch: {

		mx_image_quality() {

			if( this.mx_image_quality < 80 ) {

				this.$emit( 'push_notification', 
					{ 
						id: 1,
						notification: 'Pay attention! Image optimization quality below 80 can affect image resolution!'
					}
				)

			} else {

				this.$emit( 'push_notification', 
					{ 
						id: 1,
						notification: false
					}
				)

			}

		}

	}

} )

// display data of progress
Vue.component( 'mx_data_of_progress', {

	props: {
		number_of_images: {
			type: Number,
			required: true
		},
		optimized_imgs: {
			type: Array,
			required: true
		}
	},

	template: `
		<div class="mx_result_data">
			Number of images: <span id="mx_n_i_s">{{ number_of_images }}</span> <br />
			Number of checked images: <span id="mx_n_i">{{ optimized_imgs.length }}</span> <br>
			Average percent of optimization: <span id="mx_av_perc">{{ av_percent }}</span>%
		</div>
	`,
	data() {
		return {
			av_percent: '0.00',
			computed_av_percent: '0.00'
		}
	},
	methods: {

		get_av_percent() {

			let _this = this

			_this.computed_av_percent = 0

			let optimized_imgs_length = this.optimized_imgs.length

			this.optimized_imgs.forEach( function( v, i ) {

				_this.computed_av_percent = parseFloat( _this.computed_av_percent ) + parseFloat( v.percent )

				_this.av_percent = _this.computed_av_percent > 0 ? parseFloat( _this.computed_av_percent / optimized_imgs_length ) : 0

				_this.av_percent = parseFloat( _this.av_percent ).toFixed( 2 )

			} )

		}

	},

	watch: {

		optimized_imgs: {
            handler: function( _obj ) {

               this.get_av_percent()

            },
            deep: true
        }		

	}

} )

// progress bar
Vue.component( 'mx_progress_bar', {

	template: `
		<div class="mx_progress_bar">
			Progress...
		</div>
	`

} )

// checkin form
Vue.component( 'mx_checking_form', {

	props: {
		uploads_is_checked: {
			type: Boolean
		},
		nonce: {
			required: true,
			type: String
		},
		ajaxurl: {
			required: true,
			type: String
		}
	},

	template: `
		<form			
			id="mx_check_images"
		>

			<button
				type="submit"
				class="mx_button"
				@click.prevent="checkUploadsFolder" 
			>Checkout uploads folder</button> 
			
		</form>
	`,
	data() {

		return {
		}

	},
	methods: {

		checkUploadsFolder() {

			let _this = this

			let data = {

				'action': 'mxaio_prepare_to_resize',
				'nonce': _this.nonce

			}

			this.$emit( 'progress', true )

			jQuery.ajax( {

				url: _this.ajaxurl,
				type: 'POST',
				data: data,
				success: function( response ) {

					_this.$emit( 'progress', false )

					if( _this.isJSON( response ) ) {

						let images_data = JSON.parse( response );

						_this.$emit( 'images_data', images_data )

					} else {

						_this.$emit( 'push_error', 'Data receiving is failed!' )

					}				
					
				},

				error: function(response) {

					// console.log('error');

				}

			} )

		},

		isJSON( str ) {
			try {
		        JSON.parse(str);
		    } catch (e) {
		        return false;
		    }
		    return true;
		}

	}

} )


if( document.getElementById( 'mx_optimizer_app' ) ) {

	let app = new Vue( {
		el: '#mx_optimizer_app',
		data: {
			nonce: mxaio_admin_localize.nonce,
			ajaxurl: mxaio_admin_localize.ajaxurl,
			uploads_is_checked: false,
			progress: false,
			images_data: [],
			errors: [],
			notifications: [],
			optimized_imgs: []
		},
		methods: {

			addOptimizesImg( _img ) {

				this.optimized_imgs.push( _img )				

			},

			setProgress( _boolean ) {

				this.progress = _boolean

			},

			setImagesData( _array ) {

				if( Array.isArray( _array ) )  {

					this.images_data = _array

					this.uploads_is_checked = true

					this.errors = []

				} else {

					this.errors.push( 'Data checking failed!' )

				}

			},

			setErrors( _error ) {

				this.errors.push( _error )				

			},

			setNotifications( _notif_obj ) {

				if( _notif_obj.notification ) {

					if( ! this.isNotification( _notif_obj ) ) {

						this.notifications.push( 
							{
								id: _notif_obj.id,
								notification: _notif_obj.notification
							}
						)

					}							
					
				} else {

					let _this = this

					this.notifications.forEach( function( v, i ) {

						if( v.id === _notif_obj.id ) {

							_this.notifications.splice( i, 1 )

						}

					} )

				}				

			},

			isNotification( _notif_obj ) {

				let _this = this		

				for( let i = 0; i < this.notifications.length; i++ ) {

					if( _notif_obj.id === _this.notifications[i]['id'] ) {

						return true

						break

					}					

				}

				return false				

			}

		},
		watch: {

			images_data() {

				if( this.images_data.length > 0 ) {

					this.notifications.push( 
						{
							id: 0,
							notification: 'Please, make sure you\'ve made the backup of your "uploads" folder. After optimization running, you CAN NOT restore the data!'
						}
					)

				}

			},

			optimized_imgs() {

				if( this.images_data.length === this.optimized_imgs.length ) {

					this.progress = false

				}

			}

		}

	} )

}