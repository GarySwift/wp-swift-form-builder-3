<?php
/*
 * Add the admin menu link
 */
// require_once 'admin-menu.php';
/*
 * Add the ACF field group that will manaage the forms in the admin area.
 */
// require_once 'admin-menu-acf.php';

// function wp_swift_form_builder_admin_menu_slug() {
// 	// if( current_user_can('editor') || current_user_can('administrator') ) {
// 	// 	require plugin_dir_path( __FILE__ ) . '_admin-menu.php';
// 	// }
//     if ( function_exists('wp_swift_admin_menu_slug') ) {
//         return wp_swift_admin_menu_slug();
//     }
//     else {
//         return 'options-general.php';
//     }	
// }

function wp_swift_form_builder_admin_menu_check() {
	if( function_exists('acf_add_options_page') ) {
		add_action( 'admin_menu', 'wp_swift_form_builder_add_admin_menu_acf' );
	}
	else {
		add_action( 'admin_menu', 'wp_swift_form_builder_add_admin_menu' );
	}
}
// add_action( 'init', 'wp_swift_form_builder_admin_menu_check' );


# Register ACF field groups that will appear on the options pages
add_action( 'init', 'acf_add_local_field_group_contact_form' );
/*
 * The ACF field group for 'Contact Form'
 */ 
function acf_add_local_field_group_contact_form() {
	// echo "<pre>Lorem ipsum dolor sit amet, consectetur adipisicing elit. Hic fugit quaerat iste voluptatum! Quos dolore consequatur eius iste accusamus unde at mollitia necessitatibus odio voluptatum tempora neque, odit beatae dignissimos. </pre>";
    // include "acf-field-groups/contact-page/_acf-field-group-contact-form.php";
    // include "acf-field-groups/contact-page/_acf-field-group-form-inputs.php";
    // // include "acf-field-groups/_acf-field-group-options-page-settings.php";
    // include "acf-field-groups/contact-page/_acf-field-group-contact-page-input-settings.php";
    // require_once plugin_dir_path( __FILE__ ) . 'acf-field-groups/input-builder/shortcode.php';
    require_once     'acf-field-groups/input-builder/form-builder-inputs.php';
    require_once plugin_dir_path( __FILE__ ) . 'acf-field-groups/input-builder/form-builder-2-inputs-sections.php';
    // require_once plugin_dir_path( __FILE__ ) . 'acf-field-groups/default-contact-page/default-settings.php';
    // require_once plugin_dir_path( __FILE__ ) . 'acf-field-groups/contact-page/_acf-field-group-contact-form.php';
}