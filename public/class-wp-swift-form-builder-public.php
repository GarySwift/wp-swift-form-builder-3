<?php

/**
 * The public-facing functionality of the plugin.
 *
 * @link       https://github.com/wp-swift-wordpress-plugins
 * @since      1.0.0
 *
 * @package    Wp_Swift_Form_Builder
 * @subpackage Wp_Swift_Form_Builder/public
 */

/**
 * The public-facing functionality of the plugin.
 *
 * Defines the plugin name, version, and two examples hooks for how to
 * enqueue the admin-specific stylesheet and JavaScript.
 *
 * @package    Wp_Swift_Form_Builder
 * @subpackage Wp_Swift_Form_Builder/public
 * @author     Gary Swift <garyswiftmail@gmail.com>
 */
class Wp_Swift_Form_Builder_Public {

	/**
	 * The ID of this plugin.
	 *
	 * @since    1.0.0
	 * @access   private
	 * @var      string    $plugin_name    The ID of this plugin.
	 */
	private $plugin_name;

	/**
	 * The version of this plugin.
	 *
	 * @since    1.0.0
	 * @access   private
	 * @var      string    $version    The current version of this plugin.
	 */
	private $version;

	/**
	 * Initialize the class and set its properties.
	 *
	 * @since    1.0.0
	 * @param      string    $plugin_name       The name of the plugin.
	 * @param      string    $version    The version of this plugin.
	 */
	public function __construct( $plugin_name, $version ) {

		$this->plugin_name = $plugin_name;
		$this->version = $version;

    	/**
    	 * Add reveal modal which shows submission response
    	 */
    	// add_action( 'wp_footer', 'wp_swift_form_builder_modal_reveal', 1);

    	/**
    	 * Add the shortcode
    	 */
        add_shortcode( 'form', array( $this, 'render_form' ) ); 
        
	}

    /**
     * A shortcode for rendering the forms.
     *
     * @param  array   $attributes  Shortcode attributes.
     * @param  string  $content     The text content for shortcode. Not used.
     *
     * @return string  The shortcode output
     */
    public function render_form( $atts = array(), $content = null ) {
    	wp_swift_form_builder_enqueue_styles_and_scripts();
    	
        $a = shortcode_atts( array(
            'id' => false,
	        'to-email' => null,
	        'forward-email' => null,            
        ), $atts );

        $args = array();
		$form_id = $a['id'];
		$to_email = $a['to-email'];
		$forward_email = $a['forward-email'];
		
		if ($to_email) {
			$args["to_email"] = $to_email;
		}
		if ($forward_email) {
			$args["forward_email"] = $forward_email;
		}
		$post_id = get_the_id();

		$type = get_field('form_types', $form_id);
		if( $type == 'signup' ) {
	    	$form_builder = new WP_Swift_Form_Builder_Signup_Form( $form_id, $post_id, $args );
		}
		else {
			$form_builder = new WP_Swift_Form_Builder_Contact_Form( $form_id, $post_id, $args );
		}

        return $form_builder->run();
    }



		
}
#@end class Wp_Swift_Form_Builder_Public

/**
 * Handler for registering the JavaScript and stylesheets for the public-facing side of the site.
 *
 * This is called by the shortcode and also the form function for use in template pages.
 *
 * @since    1.0.0
 */
function wp_swift_form_builder_enqueue_styles_and_scripts() {
	// return;// @todo Reove this and the add_action hook
	$options = get_option( 'wp_swift_form_builder_settings' );
	if ( !isset($options['wp_swift_form_builder_checkbox_javascript']) ) {
    	# Register the JavaScript for the public-facing side of the site.
    	wp_swift_form_builder_enqueue_scripts_no_check($options);
	}
	else {
		wp_swift_form_builder_localize_script($options);
	}
	if ( !isset($options['wp_swift_form_builder_checkbox_css']) ) {
    	# Register the stylesheets for the public-facing side of the site.
    	wp_swift_form_builder_enqueue_styles_no_check();	
	}	
}
/**
 * Register the JavaScript for the public-facing side of the site.
 *
 * @since    1.0.0
 */
function wp_swift_form_builder_enqueue_scripts_no_check($options) {

	$file = 'public/js/wp-swift-form-builder-public.js';
	$version = filemtime( FORM_BUILDER_PLUGIN_PATH . $file) ;
	$form_builder_ajax = wp_swift_form_builder_get_localize_script($options);

	// Register the script
    wp_register_script( FORM_BUILDER_PLUGIN_NAME, FORM_BUILDER_PLUGIN_URL . $file );

    // Localize the script with new data
    wp_localize_script( FORM_BUILDER_PLUGIN_NAME, 'FormBuilderAjax', $form_builder_ajax, array( 'jquery' ), $version, true);

    // Enqueued script with localized data.
    wp_enqueue_script( FORM_BUILDER_PLUGIN_NAME );

}

/**
 * Register the stylesheets for the public-facing side of the site.
 *
 * @since    1.0.0
 */
function wp_swift_form_builder_enqueue_styles_no_check() {

	$file = 'public/css/wp-swift-form-builder-public.css';
	$version = filemtime( FORM_BUILDER_PLUGIN_PATH . $file );
	wp_enqueue_style( FORM_BUILDER_PLUGIN_NAME, FORM_BUILDER_PLUGIN_URL . $file, array(), $version, 'all' );

}

function wp_swift_get_contact_form($form_id, $post_id = null, $hidden = array()) {

    return new WP_Swift_Form_Builder_Contact_Form( $form_id, $post_id, $hidden ); 

}

function wp_swift_get_signup_form($form_id, $post_id = null, $hidden = array(), $type = 'signup') {

    $form_builder = new WP_Swift_Form_Builder_Signup_Form( $form_id, $post_id, $hidden, $type );
    echo $form_builder->run();   

}
/**
 * This is the function used in theme files
 *
 * @author  		Gary Swift <gary@brightlight.ie>
 *
 * @since 			1.0
 * 
 * @link 			http://docs.phpdoc.org/references/phpdoc/basic-syntax.html
 *
 * @param int    	$form_id  	The post ID of the form.
 * @param int 		$post_id 	The post ID of the page that the form is being used on.
 * @param array 	$hidden 	An array of fields that will be used to generate hidden
 *                        		form inputs fields. For example, use the array key 'to_email'
 *                        		to override any settings in the back-end. Shortcode attributes
 *                        		will be used in the same way. It is necessary to use hidden 
 *                        		inputs fields due to the Ajax callback. If this information 
 *                        		is sensitive then you should write a custom callback function.
 */
function wp_swift_formbuilder_run($form_id, $post_id = null, $hidden = array()) {

	$type = get_field('form_type', $form_id);
	if( $type == 'signup' ) {
    	$form_builder = new WP_Swift_Form_Builder_Signup_Form( $form_id, $post_id, $hidden );
	}
	else {
		$form_builder = new WP_Swift_Form_Builder_Contact_Form( $form_id, $post_id, $hidden );
	}

	wp_swift_form_builder_enqueue_styles_and_scripts();

    echo $form_builder->run();  

}