<?php
/**
 * save_post hook that adds default taxonomy and saves the processed ACF form data into FormBuilder data
 */
function wp_swift_form_builder_save_post($post_id) {
    return;
    // Return if this isn't a 'wp_swift_form' post
    if ( "wp_swift_form" != get_post_type($post_id) ) return; 

    $form_data = wp_swift_form_data_loop($post_id); 
    // Check if default taxonomy exists
    // wp_swift_form_builder_taxonomy_check();
    // Add default taxonomy
    // wp_set_post_terms( $post_id, FORM_BUILDER_DEFAULT_TERM, FORM_BUILDER_DEFAULT_TAXONOMY );

    if (FORM_BUILDER_SAVE_TO_JSON) {
    	/*
    	 * Save $form_data to a json file 
    	 */
        $form_data_json = json_encode($form_data, JSON_PRETTY_PRINT);
        $upload_dir = wp_upload_dir();
        $file_path = $upload_dir["basedir"].FORM_BUILDER_DIR;
        $file_name = 'form-builder-'.$post_id.'.json';
        $file = $file_path.$file_name;

        if (!file_exists($file_path)) {
            mkdir($file_path, 0777, true);
        }
        file_put_contents($file, $form_data_json);
    }
    else {
    	/*
    	 * Save $form_data to a WordPress option
    	 */    	
        $form_data_json = json_encode($form_data);
        $option_name = 'form-builder-'.$post_id;
        if ( !get_option( $option_name ) ) {
            add_option( $option_name, $form_data_json );
        }
        else {
            update_option( $option_name, $form_data_json );
        }
    }
}
add_action( 'save_post', 'wp_swift_form_builder_save_post' );