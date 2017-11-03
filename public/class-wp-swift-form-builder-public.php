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
        $a = shortcode_atts( array(
            'id' => false,
        ), $atts );

    // if( get_field('sections', $a['id']) ) {
    //     $sections = get_field('sections', $a['id']);
    //     // echo "<pre>"; var_export($sections); echo "</pre>";
    //     echo "<pre>"; 
    //     	echo json_encode($sections);
    //     echo "</pre>";
    // }
    // else {
    //     write_log('no sections');
    // }

        $form_builder = new WP_Swift_Form_Builder_Contact_Form( $a['id'] );
        return $form_builder->run();
    }

	/**
	 * Register the stylesheets for the public-facing side of the site.
	 *
	 * @since    1.0.0
	 */
	public function enqueue_styles() {

		$options = get_option( 'wp_swift_form_builder_settings' );
        if ( !isset($options['wp_swift_form_builder_checkbox_css']) ) {
			$file = 'css/wp-swift-form-builder-public.css';
			$version = filemtime(plugin_dir_path( __FILE__ ) . $file);
			wp_enqueue_style( $this->plugin_name, plugin_dir_url( __FILE__ ) . $file, array(), $version, 'all' );
        }

	}

	/**
	 * Register the JavaScript for the public-facing side of the site.
	 *
	 * @since    1.0.0
	 */
	public function enqueue_scripts() {

		$options = get_option( 'wp_swift_form_builder_settings' );
        if ( !isset($options['wp_swift_form_builder_checkbox_javascript']) ) {
			$file = 'js/wp-swift-form-builder-public.js';
			$version = filemtime(plugin_dir_path( __FILE__ ) . $file);
			wp_enqueue_script( $this->plugin_name, plugin_dir_url( __FILE__ ) . $file, array( 'jquery' ), $version, false );
		}

	}
}//@end class Wp_Swift_Form_Builder_Public


/*
 * @end Wp_Swift_Form_Builder_Public
 */

function wp_swift_get_contact_form($form_id) {
    // $form_data = wp_swift_get_form_data($form_id);
    // // if (class_exists('WP_Swift_Form_Builder_Contact_Form') && isset($form_data["sections"])) {
    //     $sections = $form_data["sections"];
    //     $settings = $form_data["settings"];
        return new WP_Swift_Form_Builder_Contact_Form( $form_id ); 
    // }
}

function wp_swift_get_form($form_id) {
    // $form_builder = wp_swift_get_form_builder($form_id);
    // if ($form_builder !== null) {
    //    return $form_builder->get_form();
    // }
    $form_builder = new WP_Swift_Form_Builder_Contact_Form( $form_id );
    // $form_builder = wp_swift_get_contact_form(234);
    echo $form_builder->run();    
}