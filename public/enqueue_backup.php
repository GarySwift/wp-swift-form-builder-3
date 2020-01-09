<?php
	/**
	 * Register the stylesheets for the public-facing side of the site.
	 *
	 * @since    1.0.0
	 */
	public function _enqueue_styles() {

		// $options = get_option( 'wp_swift_form_builder_settings' );
		// if ( !isset($options['wp_swift_form_builder_checkbox_css']) ) {
  //       	$this->enqueue_styles_no_check();
  //       }
		// $datepicker_css_file = 'node_modules/foundation-datepicker/css/foundation-datepicker.min.css';
		// if (file_exists(plugin_dir_path( __DIR__ ) . $datepicker_css_file)) {
		// 	$datepicker_css_version = filemtime(plugin_dir_path( __DIR__ ) . $datepicker_css_file);
		// 	wp_enqueue_style( $this->plugin_name.'-datepicker-css', plugin_dir_url( __DIR__ ) . $datepicker_css_file, array(), $datepicker_css_version, 'all' );			
		// }

		// $select2_css_file = 'node_modules/select2/dist/css/select2.min.css';
		// if (file_exists(plugin_dir_path( __DIR__ ) . $select2_css_file)) {
		// 	$select2_css_version = filemtime(plugin_dir_path( __DIR__ ) . $select2_css_file);
		// 	wp_enqueue_style( $this->plugin_name.'-select2-css', plugin_dir_url( __DIR__ ) . $select2_css_file, array(), $select2_css_version, 'all' );			
		// }

	}
	/**
	 * Register the JavaScript for the public-facing side of the site.
	 *
	 * @since    1.0.0
	 */
	public function enqueue_scripts() {

		// $options = get_option( 'wp_swift_form_builder_settings' );
  //       if ( !isset($options['wp_swift_form_builder_checkbox_javascript']) ) {
		// 	$this->enqueue_scripts_no_check();			
		// }


		// $datepicker_js_file = 'node_modules/foundation-datepicker/js/foundation-datepicker.min.js';
		// if (file_exists(plugin_dir_path( __DIR__ ) . $datepicker_js_file)) {
		// 	$datepicker_js_version = filemtime(plugin_dir_path( __DIR__ ) . $datepicker_js_file);
		// 	wp_enqueue_script( $this->plugin_name.'-datepicker-js', plugin_dir_url( __DIR__ ) . $datepicker_js_file, array( 'jquery' ), $datepicker_js_version, true );			

		// }

		// $select2_js_file = 'node_modules/select2/dist/js/select2.min.js';
		// if (file_exists(plugin_dir_path( __DIR__ ) . $select2_js_file)) {
		// 	$select2_js_version = filemtime(plugin_dir_path( __DIR__ ) . $select2_js_file);
		// 	wp_enqueue_script( $this->plugin_name.'-select2-js', plugin_dir_url( __DIR__ ) . $select2_js_file, array( 'jquery' ), $select2_js_version, true );			

		// }			
		// wp_enqueue_script( 'g-recaptcha', 'https://www.google.com/recaptcha/api.js', '', '' );		
	}
	/**
	 * Register the JavaScript for the public-facing side of the site.
	 *
	 * @since    1.0.0
	 */
	public function wp_swift_form_builder_register_styles_and_scripts() {
		$css_file = 'css/wp-swift-form-builder-public.css';
		$css_version = filemtime(plugin_dir_path( __FILE__ ) . $css_file);
		wp_register_style( 'wp-swift-form-builder-css', plugin_dir_url( __FILE__ ) . $css_file, array(), $css_version, 'all' );

		$js_file = 'js/wp-swift-form-builder-public.js';
		$js_version = filemtime(plugin_dir_path( __FILE__ ) . $js_file);
		wp_register_script( 'wp-swift-form-builder-js', plugin_dir_url( __FILE__ ) . $js_file, array( 'jquery' ), $js_version, true );

		// wp_register_script( 'g-recaptcha', 'https://www.google.com/recaptcha/api.js', '', '' );

		$datepicker_css_file = 'node_modules/foundation-datepicker/css/foundation-datepicker.min.css';
		$datepicker_css_version = filemtime(plugin_dir_path( __DIR__ ) . $datepicker_css_file);
		wp_register_style( 'wp-swift-form-builder-datepicker-css', plugin_dir_url( __DIR__ ) . $datepicker_css_file, array(), $datepicker_css_version, 'all' );

		$datepicker_js_file = 'node_modules/foundation-datepicker/js/foundation-datepicker.min.js';
		$datepicker_js_version = filemtime(plugin_dir_path( __DIR__ ) . $datepicker_js_file);
		wp_register_script( 'wp-swift-form-builder-datepicker-js', plugin_dir_url( __DIR__ ) . $datepicker_js_file, array( 'jquery' ), $datepicker_js_version, true );

		// wp_enqueue_style( 'wp-swift-form-builder-css' );
		// wp_enqueue_script( 'wp-swift-form-builder-js' );
		// wp_enqueue_script( 'g-recaptcha' );
		// wp_enqueue_style( 'wp-swift-form-builder-datepicker-css' );
		// wp_enqueue_script( 'wp-swift-form-builder-datepicker-js' );	
		// $this->wp_swift_form_builder_enqueue_styles_and_scripts();	
	}

	public function enqueue_styles_and_script_without_check() {
		$options = get_option( 'wp_swift_form_builder_settings' );
    	/**
    	 * Hack for placing stylesheet in footer
    	 *
    	 * All styles should be placed in header. So WordPress doesn't have 
    	 * a parameter for doing this in the wp_enqueue_style function, because 
    	 * traditionally all styles were added in the head. 
    	 *
    	 * Here we use the 'get_footer' hook to add it in the footer
    	 */
    	// add_action( 'get_footer', array( $this, 'enqueue_styles_no_check' ) );	
    	if ( !isset($options['wp_swift_form_builder_checkbox_css']) ) {
	    	/**
	    	 * Update - Enqueue the style as normal in the body before the form
	    	 */
	    	wp_swift_form_builder_enqueue_styles_no_check();	
	    	/**
	    	 * Enqueue the JavasScript as normal
	    	 */
	    	wp_swift_form_builder_enqueue_scripts_no_check();
    	}	
	}

	




	public function wp_swift_form_builder_enqueue_styles_and_scripts() {
		// $css_file = 'css/wp-swift-form-builder-public.css';
		// $css_version = filemtime(plugin_dir_path( __FILE__ ) . $css_file);
		// wp_register_style( 'wp-swift-form-builder-css', plugin_dir_url( __FILE__ ) . $css_file, array(), $css_version, 'all' );

		// $js_file = 'js/wp-swift-form-builder-public.js';
		// $js_version = filemtime(plugin_dir_path( __FILE__ ) . $js_file);
		// wp_register_script( 'wp-swift-form-builder-js', plugin_dir_url( __FILE__ ) . $js_file, array( 'jquery' ), $js_version, true );

		// wp_register_script( 'g-recaptcha', 'https://www.google.com/recaptcha/api.js', '', '' );

		// $datepicker_css_file = 'node_modules/foundation-datepicker/css/foundation-datepicker.min.css';
		// $datepicker_css_version = filemtime(plugin_dir_path( __DIR__ ) . $datepicker_css_file);
		// wp_register_style( 'wp-swift-form-builder-datepicker-css', plugin_dir_url( __DIR__ ) . $datepicker_css_file, array(), $datepicker_css_version, 'all' );

		// $datepicker_js_file = 'node_modules/foundation-datepicker/js/foundation-datepicker.min.js';
		// $datepicker_js_version = filemtime(plugin_dir_path( __DIR__ ) . $datepicker_js_file);
		// wp_register_script( 'wp-swift-form-builder-datepicker-js', plugin_dir_url( __DIR__ ) . $datepicker_js_file, array( 'jquery' ), $datepicker_js_version, true );

	}	

	if ( isset( $parent[‘key’] ) && isset( $parent[‘ID’] ) ) {

// Check local fields first.
if( acf_have_local_fields($parent[‘key’]) ) {
$raw_fields = acf_get_local_fields( $parent[‘key’] );
foreach( $raw_fields as $raw_field ) {
$fields[] = acf_get_field( $raw_field[‘key’] );
}

// Then check database.
} else {
if ( isset( $parent[‘ID’] ) ) {
$raw_fields = acf_get_raw_fields( $parent[‘ID’] );
foreach ( $raw_fields as $raw_field ) {
$fields[] = acf_get_field( $raw_field[‘ID’] );
}
}
}
}