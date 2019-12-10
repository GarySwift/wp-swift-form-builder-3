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
        'encryptionSecret' => get_form_builder_encryption_secret()
    );     
}
/**
 * Create the ajax nonce and url
 */
function wp_swift_form_builder_localize_script() {
    $options = get_option( 'wp_swift_form_builder_settings' );
    if ( isset($options['wp_swift_form_builder_checkbox_javascript']) ) {
        $form_builder_ajax = wp_swift_form_builder_get_localize_script();
        $use_theme_js = function_exists( 'foundationpress_scripts' );
        if ( $use_theme_js ) {
            $handle = "foundation";
        }  
        else {
            $handle = 'form-builder-ajax';
            $file = 'ajax.js';
            $js_file = 'public/js/' . $file;
            $js_file_path = FORM_BUILDER_PLUGIN_PATH . $js_file;
            $js_version = filemtime( $js_file_path );
            $deps = array();
            // Register the script
            wp_register_script( $handle, FORM_BUILDER_PLUGIN_URL . $js_file, $deps, $js_version, true );
        }      
        
        // Localize the script with new data
        wp_localize_script( $handle, 'FormBuilderAjax', $form_builder_ajax);

        if (!$use_theme_js) {
            wp_enqueue_script( $handle ); 
        }   
    }
}
add_action( 'wp_enqueue_scripts', 'wp_swift_form_builder_localize_script', 100 );