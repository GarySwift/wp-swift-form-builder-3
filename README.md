# WP Swift: FormBuilder

 * Plugin Name: WP Swift: FormBuilder
 * Plugin URI: 
 * Description: Placeholder description
 * Version: 1
 * Author: Gary Swift
 * Author URI: https://github.com/wp-swift-wordpress-plugins
 * License: GPL2


### Example using custom post type with multiple emails
`
<?php 
	$form_id = 144;
	$post_id = get_the_id();
	$title = get_the_title($post_id);

    $emails = get_field('email', $post->ID);
    $emails_array = explode(' ', $emails);
    $hidden = array( "title" => $title);

    if ( count($emails_array) ) {
        $hidden["office-email"] = $emails_array[0]; 
        wp_swift_formbuilder_run($form_id, $post_id, $hidden);
    }
?>
` 


