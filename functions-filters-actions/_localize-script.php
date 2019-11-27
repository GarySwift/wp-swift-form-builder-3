<?php
function wp_swift_form_builder_get_localize_script() {
    // $file = get_stylesheet_directory().'/dist/assets/js/app.js';
    // $js_version = 1.0;
    // if (file_exists($file)) {
    //     $js_version = filemtime( $file );
    // }
   
    return array(
        // URL to wp-admin/admin-ajax.php to process the request
        'ajaxurl' => admin_url( 'admin-ajax.php' ),
        // generate a nonce with a unique ID so that you can check it later when an AJAX request is sent
        'security' => wp_create_nonce( 'form-builder-nonce' ),
        // debugging info
        // 'updated' => date ("H:i:s - F d Y", $js_version),
        'debug' => FORM_BUILDER_DEBUG,
        'datePicker' => array( 'format' => get_form_builder_date_format()),
    );     
}
/**
 * Create the ajax nonce and url
 */
 function wp_swift_form_builder_localize_script() {

    $form_builder_ajax = wp_swift_form_builder_get_localize_script();
  
    // $form_builder_date_picker = array( 'format' => get_form_builder_date_format());
    if ( function_exists( 'foundationpress_scripts' ) ) {
        wp_register_script( 'foundation', get_stylesheet_directory_uri() . '/dist/assets/js/' . foundationpress_asset_path( 'app.js' ), array( 'jquery' ), '2.10.4', true );
    	wp_localize_script( 'foundation', 'FormBuilderAjax', $form_builder_ajax);
    }
    else {
        $file = 'ajax.js';//'wp-swift-form-builder-public.js';//
		$js_file = 'public/js/' . $file ;
		$js_file_path = plugin_dir_path( __DIR__ ).$js_file;
		$js_version = filemtime( $js_file_path ); 

        // Register the script
        wp_register_script( 'form-builder-ajax', plugin_dir_url( __DIR__ ) . $js_file );

        // Localize the script with new data
        wp_localize_script( 'form-builder-ajax', 'FormBuilderAjax', $form_builder_ajax);

        // Enqueued script with localized data.
        wp_enqueue_script( 'form-builder-ajax' ); 
    	// wp_enqueue_script( 'form-builder-ajax', plugin_dir_url( __DIR__ ) . $js_file, array(), $js_version, true );
    	// wp_localize_script( 'form-builder-ajax', 'FormBuilderAjax', $form_builder_ajax);
    } 
}
// add_action( 'wp_enqueue_scripts', 'wp_swift_form_builder_localize_script', 100 );