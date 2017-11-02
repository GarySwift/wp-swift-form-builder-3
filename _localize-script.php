<?php
/**
 * Create the ajax nonce and url
 */
 function wp_swift_form_builder_localize_script() {
    $js_version = filemtime( get_stylesheet_directory().'/dist/assets/js/app.js' );
	$form_builder_ajax = array(
        // URL to wp-admin/admin-ajax.php to process the request
        'ajaxurl' => admin_url( 'admin-ajax.php' ),
        // generate a nonce with a unique ID so that you can check it later when an AJAX request is sent
        'security' => wp_create_nonce( 'form-builder-nonce' ),
        // debugging info
        // 'updated' => date ("H:i:s - F d Y", $js_version),
    );   

    if ( function_exists( 'foundationpress_scripts' ) ) {
    	wp_localize_script( 'foundation', 'FormBuilderAjax', $form_builder_ajax);
    }
    else {
		$js_file = 'public/js/ajax.js';
		$js_file_path = plugin_dir_path( __FILE__ ).$js_file;
		$js_version = filemtime( $js_file_path ); 
    	wp_enqueue_script( 'form-builder-ajax', plugin_dir_url( __FILE__ ) . $js_file, array(), $js_version, true );
    	wp_localize_script( 'form-builder-ajax', 'FormBuilderAjax', $form_builder_ajax);
    }
    
}
add_action( 'wp_enqueue_scripts', 'wp_swift_form_builder_localize_script', 20 );