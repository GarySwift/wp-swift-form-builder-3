<?php
// require_once 'acf-field-groups/admin-menu/_clone-field-form-builder-pages.php';
// require_once 'acf-field-groups/admin-menu/_clone-field-settings.php';
// require_once 'acf-field-groups/admin-menu/_clone-field-help.php';
// require_once 'acf-field-groups/admin-menu/form-builder-admin-menu.php';

/**
 * The ACF admin menu settings.
 *
 * @author 	 Gary Swift 
 * @since    1.0.0
 */


function wp_swift_form_builder_add_admin_menu_acf() {

	// $menu_slug = wp_swift_form_builder_admin_menu_slug();

	if ( function_exists('wp_swift_admin_menu_slug') ) {
		$menu_slug = wp_swift_admin_menu_slug();
	}
    else {
        $menu_slug = 'options-general.php';
    }

if( function_exists('acf_add_options_page') ) {
 
	$option_page = acf_add_options_page(array(
		'page_title' 	=> 'Theme General Settings',
		'menu_title' 	=> 'Theme Settings',
		'menu_slug' 	=> 'theme-general-settings',
		'capability' 	=> 'edit_posts',
		'redirect' 	=> false
	));
 
}    		
	// if( function_exists('acf_add_options_page') ) {
	 
		$form_builder_menu_array = array(
	        'title' => 'Form Builder',
	        'slug' => 'form-builder-settings',
	        'parent' => $menu_slug,
	    );
	    acf_add_options_sub_page( $form_builder_menu_array );

	    $contact_form_availability = get_field('contact_form_availability', 'option');

	    if ($contact_form_availability === 'global') {
			$form_builder_menu_array = array(
		        'title' => 'Contact Form',
		        'slug' => 'form-builder-contact-form-settings',
		        'parent' => $menu_slug,
		    );
		    acf_add_options_sub_page( $form_builder_menu_array );	
	    }
// if( get_field('contact_form_availability', 'option') ):
	


// endif;	    
	// }
	// else {

	// 	if ( function_exists('wp_swift_admin_menu_slug') ) {
	//         add_submenu_page( $menu_slug, 'WP Swift: Form Builder', 'Form Builder', 'manage_options', 'wp-swift-form-builder', 'wp_swift_form_builder_options_page' );
	//     }
	//     else {
	//         add_options_page( 'WP Swift: Form Builder', 'Form Builder', 'manage_options', 'wp-swift-form-builder', 'wp_swift_form_builder_options_page' );
	//     }		
	// }
}