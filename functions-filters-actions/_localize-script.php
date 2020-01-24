<?php
function wp_swift_form_builder_get_localize_script($options) {
    $debug_options = get_option( 'wp_swift_form_builder_debug_settings' );
    $wp_swift_form_builder_debug_mode = false; 
    if (isset($debug_options['wp_swift_form_builder_debug_mode'])) $wp_swift_form_builder_debug_mode = true;  
    return array(
        // URL to wp-admin/admin-ajax.php to process the request
        'ajaxurl' => admin_url( 'admin-ajax.php' ),
        // generate a nonce with a unique ID so that you can check it later when an AJAX request is sent
        'security' => wp_create_nonce( 'form-builder-nonce' ),
        // debugging info
        'debug' => $wp_swift_form_builder_debug_mode,
        'datePicker' => array( 'format' => get_form_builder_date_format($options)),
        'encryptionSecret' => get_form_builder_encryption_secret($options),
        'marketing' => wp_swift_form_builder_get_marketing_script(),
    );     
}
/**
 * Create the ajax nonce and url
 */
function wp_swift_form_builder_localize_script($options = array()) {
    write_log('wp_swift_form_builder_localize_script()');
    // if (!$options) {
    //     $options = get_option( 'wp_swift_form_builder_settings' );
    // }
    // // write_log('$options: ');write_log($options);
    // if ( isset($options['wp_swift_form_builder_checkbox_javascript']) ) {
    //     // write_log($options);
    //     $form_builder_ajax = wp_swift_form_builder_get_localize_script($options);
    //     $use_theme_js = function_exists( 'foundationpress_scripts' );
    //     if ( $use_theme_js ) {
    //         $handle = "foundation";
    //     }  
    //     else {
            $handle = 'form-builder-ajax';
            $file = 'wp-swift-form-builder-public.js';
            $js_file = 'public/js/' . $file;
            $js_file_path = FORM_BUILDER_PLUGIN_PATH . $js_file;
            $js_version = filemtime( $js_file_path );
            $deps = array();
            // Register the script
            wp_register_script( $handle, FORM_BUILDER_PLUGIN_URL . $js_file, $deps, $js_version, true );
        // }      
        
        // Localize the script with new data
        wp_localize_script( $handle, 'FormBuilderAjax', $form_builder_ajax);

        // if (!$use_theme_js) {
            wp_enqueue_script( $handle ); 
        // }   
    // }
}
/**
 * Create the ajax nonce and url
 */
function wp_swift_form_builder_localize_script_2($options = array()) {
    // write_log('wp_swift_form_builder_localize_script()');
    if (!$options) {
        $options = get_option( 'wp_swift_form_builder_settings' );
    }
    // write_log('$options: ');write_log($options);
    if ( isset($options['wp_swift_form_builder_checkbox_javascript']) ) {
        // write_log($options);
        $form_builder_ajax = wp_swift_form_builder_get_localize_script($options);
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
# Do not use the action 'wp_enqueue_scripts' to localize the script. 
# We will call this function only when it is used in a page.
// add_action( 'wp_enqueue_scripts', 'wp_swift_form_builder_localize_script', 100 );