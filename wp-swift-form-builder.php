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
 * Plugin Name:       WP Swift: Form Builder 3
 * Plugin URI:        https://github.com/GarySwift/wp-swift-form-builder-3
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
    // wp_swift_form_builder_taxonomy_check();
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
 * Options
 */

$wp_swift_form_builder_debug_mode = false;
$wp_swift_form_builder_email_debug_mode = false;
$wp_swift_form_builder_marketing_debug_mode = false;
$debug_options = get_option( 'wp_swift_form_builder_debug_settings' );
// if (isset($debug_options['wp_swift_form_builder_debug_mode']))
// 	$wp_swift_form_builder_debug_mode = true;
// if (isset($debug_options['wp_swift_form_builder_email_debug_mode']))
// 	$wp_swift_form_builder_email_debug_mode = true;
// if (isset($debug_options['wp_swift_form_builder_marketing_debug_mode']))
// 	$wp_swift_form_builder_marketing_debug_mode = true;

/**
 * Constant vars
 */
define('FORM_BUILDER_DIR', '/form-builder/');
define('FORM_BUILDER_SAVE_TO_JSON', false);
define('FORM_BUILDER_DEBUG', $wp_swift_form_builder_debug_mode);
define('FORM_BUILDER_DEBUG_EMAIL', $wp_swift_form_builder_email_debug_mode);
define('FORM_BUILDER_DEBUG_MARKETING', $wp_swift_form_builder_marketing_debug_mode);
define('FORM_BUILDER_DEFAULT_TERM', 'Contact Form');
define('FORM_BUILDER_DEFAULT_SLUG', 'contact-form');
// define('FORM_BUILDER_DEFAULT_TAXONOMY', 'wp_swift_form_category');
define('FORM_BUILDER_DATE_FORMAT', 'dd/mm/yyyy');// or 'mm/dd/yyyy' (Can be set in settings)
define('FORM_BUILDER_PLUGIN_PATH', plugin_dir_path( __FILE__ ));
define('FORM_BUILDER_PLUGIN_URL', plugin_dir_url( __FILE__ ));
/**
 * The FormBuilder class that handles all form logic
 */
require_once FORM_BUILDER_PLUGIN_PATH . 'classes/forms/class-form-builder.php';
// validate
require_once FORM_BUILDER_PLUGIN_PATH . 'classes/utility/validate.php';
require_once FORM_BUILDER_PLUGIN_PATH . 'classes/utility/html.php';
require_once FORM_BUILDER_PLUGIN_PATH . 'classes/utility/helper.php';
require_once FORM_BUILDER_PLUGIN_PATH . 'classes/utility/marketing.php';
require_once FORM_BUILDER_PLUGIN_PATH . 'classes/utility/class-spam-killer.php';
/**
 * A FormBuilder child class that handles contact forms
 */
require_once FORM_BUILDER_PLUGIN_PATH . 'classes/forms/class-form-builder-contact-form.php';
require_once FORM_BUILDER_PLUGIN_PATH . 'classes/forms/class-form-builder-signup-form.php';

/**
 * A FormBuilder child class that handles contact forms
 */
// require_once FORM_BUILDER_PLUGIN_PATH . 'classes/utility/class-form-submission-cpt.php';

/*
 * Form Custom Post Type and Taxonomies
 */
require_once FORM_BUILDER_PLUGIN_PATH . 'cpt/wp_swift_form.php';
// require_once FORM_BUILDER_PLUGIN_PATH . 'cpt/wp_swift_form_category.php';
// require_once FORM_BUILDER_PLUGIN_PATH . 'cpt/wp_swift_form_submit.php';

/**
 * The ACF field groups
 */
require_once FORM_BUILDER_PLUGIN_PATH . 'acf/all.php';
// require_once FORM_BUILDER_PLUGIN_PATH . 'acf-field-groups/_acf-field-group-contact-form.php';

/**
 * The classes that handles the admin interface
 */
if( function_exists('acf_add_options_page') ) {
 
	// add sub page
	acf_add_options_sub_page(array(
		'page_title' 	=> 'Marketing Settings',
		'menu_title' 	=> 'Marketing',
		'parent_slug' 	=> 'edit.php?post_type=wp_swift_form',
	));

}
require_once FORM_BUILDER_PLUGIN_PATH . 'classes/interface/class-admin-interface-templates.php';
require_once FORM_BUILDER_PLUGIN_PATH . 'classes/interface/class-admin-interface-tools.php';
require_once FORM_BUILDER_PLUGIN_PATH . 'classes/interface/class-admin-interface-settings.php';
require_once FORM_BUILDER_PLUGIN_PATH . 'classes/interface/class-admin-interface-debug-settings.php';

/**
 * Function that wraps email message in a html template
 */
require_once FORM_BUILDER_PLUGIN_PATH . 'email-templates/wp-swift-email-templates.php';

/**
 * Create the ajax nonce and url
 */
require_once FORM_BUILDER_PLUGIN_PATH . 'functions-filters-actions/_localize-script.php';

/**
 * Check if the default taxonomy exists and create it if not
 */
// require_once FORM_BUILDER_PLUGIN_PATH . 'functions-filters-actions/_taxonomy-check.php';

/**
 * Handle the ajax form submit
 */
require_once FORM_BUILDER_PLUGIN_PATH . 'functions-filters-actions/_ajax-form-callback.php';

/**
 * save_post hook that adds default taxonomy and saves the processed ACF form data into FormBuilder data
 */
require_once FORM_BUILDER_PLUGIN_PATH . 'functions-filters-actions/_save-post-action.php';

/**
 * All admin notices including a GET request for 'wp_swift_form_builder_new_contact_form_error'
 */
require_once FORM_BUILDER_PLUGIN_PATH . 'functions-filters-actions/_admin-notices.php';

/**
 * Process ACF data into FormBuilder data
 */
require_once FORM_BUILDER_PLUGIN_PATH . 'functions-filters-actions/_build-form-array.php';

/**
 * A metabox showing form usage which includes shortcode and php function
 */
require_once FORM_BUILDER_PLUGIN_PATH . 'functions-filters-actions/_shortcode-metabox.php';

/**
 * Add custom query vars
 */
// require_once FORM_BUILDER_PLUGIN_PATH . 'functions-filters-actions/_custom-query-vars.php';

require_once FORM_BUILDER_PLUGIN_PATH . 'functions-filters-actions/_acf-filters.php';

require_once FORM_BUILDER_PLUGIN_PATH . 'functions-filters-actions/_get-date-format.php';

/**
 * Add the FoundationPress reveal modal which shows submission response
 */
require_once FORM_BUILDER_PLUGIN_PATH . 'functions-filters-actions/_reveal-modal.php';

require_once FORM_BUILDER_PLUGIN_PATH . 'functions-filters-actions/_write-log.php';
require_once FORM_BUILDER_PLUGIN_PATH . 'functions-filters-actions/_get-form-input.php';

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

function wp_swift_formbuilder_exist($form_id) {
	$form = get_post($form_id);
	if (!empty($form) && $form->post_type = 'wp_swift_form') {
		return true;
	}
	return false;
}

run_wp_swift_form_builder();