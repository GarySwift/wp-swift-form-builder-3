<?php

/**
 * The plugin bootstrap file
 *
 * This file is read by WordPress to generate the plugin information in the plugin
 * admin area. This file also includes all of the dependencies used by the plugin,
 * registers the activation and deactivation functions, and defines a function
 * that starts the plugin.
 *
 * @link              https://github.com/wp-swift-wordpress-plugins
 * @since             1.0.0
 * @package           Wp_Swift_Form_Builder
 *
 * @wordpress-plugin
 * Plugin Name:       WP Swift: Form Builder 2
 * Plugin URI:        https://github.com/wp-swift-wordpress-plugins/wp-swift-form-builder-2
 * Description:       This is a short description of what the plugin does. It's displayed in the WordPress admin area.
 * Version:           1.0.0
 * Author:            Gary Swift
 * Author URI:        https://github.com/wp-swift-wordpress-plugins
 * License:           GPL-2.0+
 * License URI:       http://www.gnu.org/licenses/gpl-2.0.txt
 * Text Domain:       wp-swift-form-builder
 * Domain Path:       /languages
 */

// If this file is called directly, abort.
if ( ! defined( 'WPINC' ) ) {
	die;
}

/**
 * The code that runs during plugin activation.
 * This action is documented in includes/class-wp-swift-form-builder-activator.php
 */
function activate_wp_swift_form_builder() {
	require_once plugin_dir_path( __FILE__ ) . 'includes/class-wp-swift-form-builder-activator.php';
	Wp_Swift_Form_Builder_Activator::activate();
}

/**
 * The code that runs during plugin deactivation.
 * This action is documented in includes/class-wp-swift-form-builder-deactivator.php
 */
function deactivate_wp_swift_form_builder() {
	require_once plugin_dir_path( __FILE__ ) . 'includes/class-wp-swift-form-builder-deactivator.php';
	Wp_Swift_Form_Builder_Deactivator::deactivate();
}

register_activation_hook( __FILE__, 'activate_wp_swift_form_builder' );
register_deactivation_hook( __FILE__, 'deactivate_wp_swift_form_builder' );

/**
 * The core plugin class that is used to define internationalization,
 * admin-specific hooks, and public-facing site hooks.
 */
require plugin_dir_path( __FILE__ ) . 'includes/class-wp-swift-form-builder.php';

/**
 * The FormBuilder class that handles all form logic
 */
require_once plugin_dir_path( __FILE__ ) . 'class-form-builder.php';

/**
 * A FormBuilder child class that handles contact forms
 */
require_once plugin_dir_path( __FILE__ ) . 'class-form-builder-contact-form.php';

/*
*/
require_once plugin_dir_path( __FILE__ ) . '/email-templates/wp-swift-email-templates.php';

/**
 * The Admin menu settings.
 *
 * @author 	 Gary Swift 
 * @since    1.0.0
 */
// function wp_swift_form_builder_admin_menu() {
// 	if( current_user_can('editor') || current_user_can('administrator') ) {
// 		require plugin_dir_path( __FILE__ ) . 'admin-menu.php';
// 	}
// }
// add_action( 'init', 'wp_swift_form_builder_admin_menu' );

/*
 * Add the admin menu link
 */
require_once 'admin-menu.php';
/*
 * Add the ACF field group that will manaage the forms in the admin area.
 */
require_once 'admin-menu-acf.php';


require_once 'admin-notices.php';

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
add_action( 'init', 'wp_swift_form_builder_admin_menu_check' );


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
    require_once plugin_dir_path( __FILE__ ) . 'acf-field-groups/input-builder/form-builder-inputs.php';
    require_once plugin_dir_path( __FILE__ ) . 'acf-field-groups/input-builder/form-builder-2-inputs-sections.php';
}

require_once 'class-form-builder.php';
require_once 'class-form-builder-contact-form.php';
require_once '_build-form-array.php';


/*
 * Form Custom Post Type
 */
require_once plugin_dir_path( __FILE__ ) . 'cpt/wp_swift_form.php';

// Initialize the class
// $wp_swift_contact_form_plugin = new WP_Swift_Form_Builder_Contact_Form();


// require_once '_form-data.php';
// function wp_swift_get_contact_form( $attributes=array() ) {
    // $form_builder = null;
    // // if (class_exists('WP_Swift_Form_Builder_Contact_Form')) {
    //     $form_builder = new WP_Swift_Form_Builder_Contact_Form( get_contact_form_data(), array("show_mail_receipt"=>true, "option" => "") );    
    // }
    // return $form_builder;        
// }


/**
 * Begins execution of the plugin.
 *
 * Since everything within the plugin is registered via hooks,
 * then kicking off the plugin from this point in the file does
 * not affect the page life cycle.
 *
 * @since    1.0.0
 */
function run_wp_swift_form_builder() {

	$plugin = new Wp_Swift_Form_Builder();
	$plugin->run();

}
run_wp_swift_form_builder();