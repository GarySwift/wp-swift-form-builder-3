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
    wp_swift_form_builder_taxonomy_check();
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
 * FormBuilder constants and required files
 *
 * @author 	 Gary Swift 
 * @since    1.0.0
 */

/**
 * Constant vars
 */
define('FORM_BUILDER_DIR', '/form-builder/');
define('FORM_BUILDER_SAVE_TO_JSON', false);
define('FORM_BUILDER_DEFAULT_TERM', 'Contact Form');
define('FORM_BUILDER_DEFAULT_SLUG', 'contact-form');
define('FORM_BUILDER_DEFAULT_TAXONOMY', 'wp_swift_form_category');
define('FORM_BUILDER_DATE_FORMAT', 'dd-mm-yyyy');

/**
 * The FormBuilder class that handles all form logic
 */
require_once plugin_dir_path( __FILE__ ) . 'class-form-builder.php';

/**
 * A FormBuilder child class that handles contact forms
 */
require_once plugin_dir_path( __FILE__ ) . 'class-form-builder-contact-form.php';
require_once plugin_dir_path( __FILE__ ) . 'class-form-builder-signup-form.php';

/**
 * A FormBuilder child class that handles contact forms
 */
require_once plugin_dir_path( __FILE__ ) . 'class-form-submission-cpt.php';

/*
 * Form Custom Post Type and Taxonomies
 */
require_once plugin_dir_path( __FILE__ ) . 'cpt/wp_swift_form.php';
require_once plugin_dir_path( __FILE__ ) . 'cpt/wp_swift_form_category.php';
require_once plugin_dir_path( __FILE__ ) . 'cpt/wp_swift_form_submit.php';

/**
 * The ACF field groups
 */ 
require_once plugin_dir_path( __FILE__ ) . 'acf-field-groups/form-builder-inputs.php';
// require_once plugin_dir_path( __FILE__ ) . 'acf-field-groups/form-builder-input-sections.php';
require_once plugin_dir_path( __FILE__ ) . 'acf-field-groups/_acf-field-group-contact-form.php';

/**
 * The classes that handles the admin interface
 */
require_once plugin_dir_path( __FILE__ ) . 'class-admin-interface-templates.php';
require_once plugin_dir_path( __FILE__ ) . 'class-admin-interface-settings.php';

/**
 * Function that wraps email message in a html template
 */
require_once plugin_dir_path( __FILE__ ) . '/email-templates/wp-swift-email-templates.php';

/**
 * Create the ajax nonce and url
 */
require_once plugin_dir_path( __FILE__ ) . '_localize-script.php';

/**
 * Check if the default taxonomy exists and create it if not
 */
require_once plugin_dir_path( __FILE__ ) . '_taxonomy-check.php';

/**
 * Handle the ajax form submit
 */
require_once plugin_dir_path( __FILE__ ) . '_ajax-form-callback.php';

/**
 * save_post hook that adds default taxonomy and saves the processed ACF form data into FormBuilder data
 */
require_once plugin_dir_path( __FILE__ ) . '_save-post-action.php';

/**
 * All admin notices including a GET request for 'wp_swift_form_builder_new_contact_form_error'
 */
require_once plugin_dir_path( __FILE__ ) . '_admin-notices.php';

/**
 * Process ACF data into FormBuilder data
 */
require_once plugin_dir_path( __FILE__ ) . '_build-form-array.php';

/**
 * A metabox showing form usage which includes shortcode and php function
 */
require_once plugin_dir_path( __FILE__ ) . '_shortcode-metabox.php';

/**
 * Add the FoundationPress reveal modal which shows submission response
 */
require_once plugin_dir_path( __FILE__ ) . '_reveal-modal.php';


require_once plugin_dir_path( __FILE__ ) . 'debug/write-log.php';

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
