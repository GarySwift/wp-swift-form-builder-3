<?php
/**
 * Fire on the initialization of WordPress.
 */
function new_form_cpt() {
    // Do stuff. Say we will echo "Fired on the WordPress initialization".
    // echo 'Do stuff. Say we will echo "Fired on the WordPress initialization".';
	$args = array(
	  'post_type' => 'wp_swift_form',
	  'post_title'   => 'Contact Form (Default)',
	  'post_status'   => 'publish',
	);
	// add_option( 'wp_swift_form_builder_default_id', '101' );
	// delete_option( 'wp_swift_form_builder_default_id' ); 
	// update_option( 'wp_swift_form_builder_default_id', '101');
	$wp_swift_form_builder_default_id = get_option( 'wp_swift_form_builder_default_id' );
	// echo "<pre>wp_swift_form_builder_default_id: "; var_dump($wp_swift_form_builder_default_id); echo "</pre>";

	// Create a new default form if there isn't one
	if (isset($wp_swift_form_builder_default_id) && !$wp_swift_form_builder_default_id) {
		$post_id = wp_insert_post($args);
		if(!is_wp_error($post_id)) {
			// Save the default ID as an option
		  	add_option( 'wp_swift_form_builder_default_id', $post_id );
		}
		// else{
		//   //there was an error in the post insertion, 
		//   // echo $post_id->get_error_message();
		// }
	}
}
// add_action( 'init', 'new_form_cpt', 10, 3 );