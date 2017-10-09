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

        # Shortcode for rendering the new user registration form
        add_shortcode( 'contact-form', array( $this, 'render_contact_form' ) );

        add_shortcode( 'form', array( $this, 'render_form' ) );

        add_filter( 'the_content', array($this, 'wp_swift_render_contact_form_after_content') );

        # Handle POST request form login form
        // add_action( 'init', array( $this, 'process_form' ) );

        # Register the acf_add_options_sub_page    
        // add_action( 'admin_menu', array($this, 'wp_swift_contact_form_admin_menu') );

        # Register ACF field groups that will appear on the options pages
        // add_action( 'init', array($this, 'acf_add_local_field_group_contact_form') );

        # Register the inputs
        // add_action( 'admin_init', array($this, 'wp_swift_form_builder_contact_form_settings_init') );   
	}

    /**
     * A shortcode for rendering the contact form.
     *
     * @param  array   $attributes  Shortcode attributes.
     * @param  string  $content     The text content for shortcode. Not used.
     *
     * @return string  The shortcode output
     */
    public function render_contact_form( $attributes = array(), $content = null ) {
        $form_position = 'after_content';
        if( get_field('form_position') ) {
            $form_position = get_field('form_position');
        }
        if( $form_position === 'shortcode' ) {

            $form_builder = wp_swift_get_contact_form($attributes);
            // Render the login form using an external template
            return $this->get_template_html( 'contact-form', $attributes, $form_builder );
        }
        else {
            return '[contact-form]';
        }


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
        $form_id = $a['id'];
        $form_data = wp_swift_get_form_data($form_id);
        $form_builder = wp_swift_get_form_builder($form_id, $form_data);
        $html = wp_swift_set_form($form_builder);
        return $html;
    }

    /**
     * Renders the contents of the given template to a string and returns it.
     *
     * @param string $template_name The name of the template to render (without .php)
     * @param array  $attributes    The PHP variables for the template
     *
     * @return string               The contents of the template.
     */
    private function get_template_html( $template_name, $attributes = null, $form_builder, $content = null ) {
        if ( ! $attributes ) {
            $attributes = array();
        }
     
        ob_start();
     
        do_action( 'personalize_login_before_' . $template_name );
     
        require( 'templates/' . $template_name . '.php');
     
        do_action( 'personalize_login_after_' . $template_name );
     
        $html = ob_get_contents();
        ob_end_clean();
     
        return $html;
    } 

	/**
	 * Register the stylesheets for the public-facing side of the site.
	 *
	 * @since    1.0.0
	 */
	public function enqueue_styles() {

		/**
		 * This function is provided for demonstration purposes only.
		 *
		 * An instance of this class should be passed to the run() function
		 * defined in Wp_Swift_Form_Builder_Loader as all of the hooks are defined
		 * in that particular class.
		 *
		 * The Wp_Swift_Form_Builder_Loader will then create the relationship
		 * between the defined hooks and the functions defined in this
		 * class.
		 */

		wp_enqueue_style( $this->plugin_name, plugin_dir_url( __FILE__ ) . 'css/wp-swift-form-builder-public.css', array(), $this->version, 'all' );

	}

	/**
	 * Register the JavaScript for the public-facing side of the site.
	 *
	 * @since    1.0.0
	 */
	public function enqueue_scripts() {

		/**
		 * This function is provided for demonstration purposes only.
		 *
		 * An instance of this class should be passed to the run() function
		 * defined in Wp_Swift_Form_Builder_Loader as all of the hooks are defined
		 * in that particular class.
		 *
		 * The Wp_Swift_Form_Builder_Loader will then create the relationship
		 * between the defined hooks and the functions defined in this
		 * class.
		 */

		// wp_enqueue_script( $this->plugin_name, plugin_dir_url( __FILE__ ) . 'js/wp-swift-form-builder-public.js', array( 'jquery' ), $this->version, false );

	}

    /*
     * Render the default contact form using the 'the_content' filter
     * 
     */
    public function wp_swift_render_contact_form_after_content( $content ) {
        $form_position = 'after_content';
        if( get_field('form_position') ) {
            $form_position = get_field('form_position');
        }
        if( $form_position === 'after_content' ) :
            $form = '';
            $form_builder = null;

            if( class_exists('acf') ) {
              // This page ID
              $this_page_id = get_the_ID();
              // The preset default contact page ID (Saved via ACF options page)
              $contact_page_id = get_field('contact_form_page', 'option');

              // Form IDs added with the repeater on the same options page
              $form_pages = array();

              if( have_rows('additional_forms', 'option') ):
                  while ( have_rows('additional_forms', 'option') ) : the_row();
                      $form_pages[] = get_sub_field('page');
                  endwhile;
              endif;


              if ($this_page_id === $contact_page_id) {
                $form_builder = wp_swift_get_contact_form();
              }
              elseif (in_array($this_page_id, $form_pages )) {
                $form_position = get_field('form_position', $this_page_id);
                if( $form_position !== 'shortcode' ) {
                  $form_builder = wp_swift_get_generic_form($this_page_id);
                }
              }


              if ($form_builder !== null ) {
                ob_start();
                    ?><div class="contact-form-container"><?php 
                  if ($form_builder != null ) {
                          if(isset($_POST[ $form_builder->get_submit_button_name() ])){ //check if form was submitted
                              $form_builder->process_form(); 
                          }
                        $form_builder->acf_build_form();
                    } 
                ?></div><?php
                    $form = ob_get_contents();
                    ob_end_clean();
                }

            }
            return $content.$form;      
        endif;

        return $content;
    }

}

function wp_swift_get_form_data($id) {
    $form_data = array();
    $inputs = array();
    // $sections = array();
    if ( have_rows('sections', $id) ) :

        $section_count = 0;

        while( have_rows('sections', $id) ) : the_row();
    
            // $section = array();
            // $section["section_header"] = the_sub_field('section_header');
            // $section["section_content"] = the_sub_field('section_content');
            if ( have_rows('form_inputs') ) :
            
                while( have_rows('form_inputs') ) : the_row();

                    $inputs = build_acf_form_array($inputs, $section_count);  
                   
                endwhile;
            
            endif;
            // $sections[] = $section;
            $section_count++;
        endwhile;
    
    endif; 
    // $form_data['sections'] = $sections;
    // $form_data['inputs'] = $inputs;
    return $inputs;   
}
/*
 * @end Wp_Swift_Form_Builder_Public
 */
function wp_swift_form_builder($id) {
/* 
    $form_builder = wp_swift_get_contact_form();
  if ($form_builder !== null ) {
    ob_start();
        ?><div class="contact-form-container"><?php 
      if ($form_builder != null ) {
              if(isset($_POST[ $form_builder->get_submit_button_name() ])){ //check if form was submitted
                  $form_builder->process_form(); 
              }
            $form_builder->acf_build_form();
        } 
    ?></div><?php
        $form = ob_get_contents();
        ob_end_clean();
    } 
    return $form;    
*/ 
$form_data = wp_swift_get_form_data($id);
$form_builder = wp_swift_get_form_builder($form_data);
$html = wp_swift_set_form($form_builder);
return $html;
}
/*
 * Check if the page has inputs set using ACF field groups
 * This is used the form builder part 
 */
function get_page_inputs($page_id) {
	$form_data = array();
	if ( have_rows('form_inputs', $page_id) ) :

	    while( have_rows('form_inputs', $page_id) ) : the_row(); // Loop through the repeater for form inputs        
	         $form_data =  build_acf_form_array($form_data);  
	    endwhile;// End the AFC loop  

	endif;
	return $form_data;
}

if (!function_exists('wp_swift_get_contact_form')) {
	function wp_swift_get_contact_form( $attributes=array() ) {
	    $form_builder = null;
	    if (class_exists('WP_Swift_Form_Builder_Contact_Form')) {
	        $form_builder = new WP_Swift_Form_Builder_Contact_Form( get_contact_form_data(), array("show_mail_receipt"=>true, "option" => "") );    
	    }
	    return $form_builder;        
	}
}

if (!function_exists('wp_swift_get_generic_form')) {
	function wp_swift_get_generic_form( $attributes=array() ) {
	    $form_builder = null;
	    if (class_exists('WP_Swift_Form_Builder_Contact_Form')) {
	        $form_builder = new WP_Swift_Form_Builder_Contact_Form( get_page_inputs(get_the_id()), array("show_mail_receipt"=>true, "option" => "") );
	    }
	    return $form_builder;        
	}
}

if (!function_exists('form_builder_location_array')) {
	function form_builder_location_array($id, $param = 'page') {
	    return array ( array (
	            'param' => $param,
	            'operator' => '==',
	            'value' => $id,
	        ),
	    );
	}
}

function wp_swift_get_form_builder($form_post_id, $form_data) {
    if (class_exists('WP_Swift_Form_Builder_Contact_Form')) {
        return new WP_Swift_Form_Builder_Contact_Form( $form_post_id, $form_data, array("show_mail_receipt"=>true, "option" => "") ); 
    }
}

function wp_swift_set_form($form_builder) {
    ob_start();
    if ($form_builder != null ) {
        if(isset($_POST[ $form_builder->get_submit_button_name() ])){ //check if form was submitted
            // $form_builder->process_form(); 
            // var_dump($_POST);
            echo "<pre>"; var_dump($_POST); echo "</pre>";
        }
        $form_builder->acf_build_form();
    }
    $html = ob_get_contents();
    ob_end_clean();
    return $html; 
}
    
/*
 * Get the booking form settings array
 *
 * @return array    form data array
 */
function get_contact_form_data() {
    $options = get_option( 'wp_swift_form_builder_contact_form_settings' );
    $form_first_and_last_name = false;
    $form_phone = false;
    if (isset($options['wp_swift_form_builder_contact_form_checkbox_first_last_name'])) {
        $form_first_and_last_name = true;
    }
    if (isset($options['wp_swift_form_builder_contact_form_checkbox_phone'])) {
        $form_phone = true;
    }

    $combine_name_fields = false;
    $show_telephone_input = false;
    $show_company_input = false;
    $form_data = array();

    if( class_exists('acf') ) {
        if( get_field('contact_form_page', 'option') ) {
            $contact_form_page = get_field('contact_form_page', 'option');
            $location[] = form_builder_location_array( $contact_form_page );
            if( get_field('combine_name_fields', $contact_form_page) ) {
                $combine_name_fields = get_field('combine_name_fields', $contact_form_page);
            }
            if( get_field('show_telephone_input', $contact_form_page) ) {
                $show_telephone_input = get_field('show_telephone_input', $contact_form_page);
            }
            if( get_field('show_company_input', $contact_form_page) ) {
                $show_company_input = get_field('show_company_input', $contact_form_page);
            }
        }
    }

    if (!$combine_name_fields) {
        $form_data['form-first-name'] = array (
            'passed' => false,
            'clean' => '',
            'value' => '',
            'section' => 0,
            'required' => 'required',
            'type' => 'text',
            'data_type' => 'text',
            'placeholder' => '',
            'label' => 'First Name',
            'help' => '',
          );
        $form_data['form-last-name'] = array (
            'passed' => false,
            'clean' => '',
            'value' => '',
            'section' => 0,
            'required' => 'required',
            'type' => 'text',
            'data_type' => 'text',
            'placeholder' => '',
            'label' => 'Last Name',
            'help' => '',
          );
    }
    else {
        $form_data['form-name'] = array (
            'passed' => false,
            'clean' => '',
            'value' => '',
            'section' => 0,
            'required' => 'required',
            'type' => 'text',
            'data_type' => 'text',
            'placeholder' => '',
            'label' => 'Name',
            'help' => '',
          );
    }

    $form_data['form-email'] = array (
        'passed' => false,
        'clean' => '',
        'value' => '',
        'section' => 0,
        'required' => 'required',
        'type' => 'email',
        'data_type' => 'email',
        'placeholder' => '',
        'label' => 'Email',
        'help' => '',
    );


    
    if ($show_telephone_input) {
        $form_data['form-phone'] = array (
            'passed' => false,
            'clean' => '',
            'value' => '',
            'section' => 0,
            'required' => '',
            'type' => 'text',
            'data_type' => 'text',
            'placeholder' => '',
            'label' => 'Telephone',
            'help' => '',
        );
    }

    if ($show_company_input) {
        $form_data['form-company'] = array (
            'passed' => false,
            'clean' => '',
            'value' => '',
            'section' => 0,
            'required' => '',
            'type' => 'text',
            'data_type' => 'text',
            'placeholder' => '',
            'label' => 'Company',
            'help' => '',
        );
    }

    $form_data['form-question'] =array (
        'passed' => false,
        'clean' => '',
        'value' => '',
        'section' => 0,
        'required' => 'required',
        'type' => 'textarea',
        'data_type' => 'textarea',
        'placeholder' => '',
        'label' => 'Question',
        'help' => '',
    );

    return $form_data;
}

/*
 * Get the booking form settings array
 *
 * @return array    form data array
 */
function get_booking_form_data() {
    $form_data = array (
      'section-count' => 5,
      'form-contact-details' => 
      array (
        'passed' => true,
        'section' => 1,
        'section_header' => 'Contact Details',
        'section_content' => '',
        'type' => 'section',
        'data_type' => 'section',
      ),
      'form-group-name' => 
      array (
        'passed' => false,
        'clean' => '',
        'value' => '',
        'section' => 1,
        'required' => 'required',
        'type' => 'text',
        'data_type' => 'text',
        'placeholder' => '',
        'label' => 'Group Name',
        'help' => '',
      ),
      'form-group-leaders-name' => 
      array (
        'passed' => false,
        'clean' => '',
        'value' => '',
        'section' => 1,
        'required' => 'required',
        'type' => 'text',
        'data_type' => 'text',
        'placeholder' => '',
        'label' => 'Group Leaders Name',
        'help' => '',
      ),
      'form-postal-address' => 
      array (
        'passed' => false,
        'clean' => '',
        'value' => '',
        'section' => 1,
        'required' => 'required',
        'type' => 'textarea',
        'data_type' => 'textarea',
        'placeholder' => '',
        'label' => 'Postal Address',
        'help' => '',
      ),
      'form-phone-number' => 
      array (
        'passed' => false,
        'clean' => '',
        'value' => '',
        'section' => 1,
        'required' => 'required',
        'type' => 'text',
        'data_type' => 'text',
        'placeholder' => '',
        'label' => 'Phone Number',
        'help' => '',
      ),
      'form-email' => 
      array (
        'passed' => false,
        'clean' => '',
        'value' => '',
        'section' => 1,
        'required' => 'required',
        'type' => 'email',
        'data_type' => 'email',
        'placeholder' => '',
        'label' => 'Email',
        'help' => '',
      ),
      'form-group-details' => 
      array (
        'passed' => true,
        'section' => 2,
        'section_header' => 'Group Details',
        'section_content' => '',
        'type' => 'section',
        'data_type' => 'section',
      ),
      'form-leaders-male' => 
      array (
        'passed' => false,
        'clean' => '',
        'value' => '',
        'section' => 2,
        'required' => '',
        'type' => 'number',
        'data_type' => 'input_combo',
        'placeholder' => '',
        'label' => 'Male',
        'help' => '',
        'order' => 0,
        'parent_label' => 'Number of Leaders',
      ),
      'form-leaders-female' => 
      array (
        'passed' => false,
        'clean' => '',
        'value' => '',
        'section' => 2,
        'required' => '',
        'type' => 'number',
        'data_type' => 'input_combo',
        'placeholder' => '',
        'label' => 'Female',
        'help' => '',
        'order' => 1,
      ),
      'form-youths-male' => 
      array (
        'passed' => false,
        'clean' => '',
        'value' => '',
        'section' => 2,
        'required' => '',
        'type' => 'number',
        'data_type' => 'input_combo',
        'placeholder' => '',
        'label' => 'Male',
        'help' => '',
        'order' => 0,
        'parent_label' => 'Number of Youths',
      ),
      'form-youths-female' => 
      array (
        'passed' => false,
        'clean' => '',
        'value' => '',
        'section' => 2,
        'required' => '',
        'type' => 'number',
        'data_type' => 'input_combo',
        'placeholder' => '',
        'label' => 'Female',
        'help' => '',
        'order' => 1,
      ),
      'form-youth-category' => 
      array (
        'passed' => false,
        'clean' => '',
        'value' => '',
        'section' => 2,
        'required' => 'required',
        'type' => 'checkbox',
        'data_type' => 'checkbox',
        'placeholder' => '',
        'label' => 'Youth Category',
        'options' => 
        array (
          0 => 
          array (
            'option' => 'Beavers',
            'option_value' => 'beavers',
            'checked' => false
          ),
          1 => 
          array (
            'option' => 'Cubs/Macaoimh',
            'option_value' => 'cubs',
            'checked' => false
          ),
          2 => 
          array (
            'option' => 'Scouts',
            'option_value' => 'scouts',
            'checked' => false
          ),
          3 => 
          array (
            'option' => 'Venture Scouts',
            'option_value' => 'venture',
            'checked' => false
          ),
          4 => 
          array (
            'option' => 'Other',
            'option_value' => 'other',
            'checked' => false
          ),
        ),
        'selected_option' => '',
        'help' => '',
      ),
      'form-other-youth-categories' => 
      array (
        'passed' => false,
        'clean' => '',
        'value' => '',
        'section' => 2,
        'required' => '',
        'type' => 'text',
        'data_type' => 'text',
        'placeholder' => 'Please specify if other',
        'label' => 'Other',
        'help' => '',
      ),
      'form-dates' => 
      array (
        'passed' => true,
        'section' => 3,
        'section_header' => 'Dates',
        'section_content' => '',
        'type' => 'section',
        'data_type' => 'section',
      ),
      'form-dates-of-stay-start' => 
      array (
        'passed' => false,
        'clean' => '',
        'value' => '',
        'section' => 3,
        'required' => '',
        'type' => 'text',
        'data_type' => 'date_range',
        'label' => 'Date From',
        'help' => '',
        'order' => 0,
        'parent_label' => 'Dates of Stay',
      ),
      'form-dates-of-stay-end' => 
      array (
        'passed' => false,
        'clean' => '',
        'value' => '',
        'section' => 3,
        'required' => '',
        'type' => 'text',
        'data_type' => 'date_range',
        'label' => 'Date To',
        'help' => '',
        'order' => 1,
        'parent_label' => 'Dates of Stay',
      ),
      'form-accommodation' => 
      array (
        'passed' => true,
        'section' => 4,
        'section_header' => 'Accommodation',
        'section_content' => '',
        'type' => 'section',
        'data_type' => 'section',
      ),
      'form-type-of-stay' => 
      array (
        'passed' => false,
        'clean' => '',
        'value' => '',
        'section' => 4,
        'required' => 'required',
        'type' => 'select',
        'data_type' => 'select',
        'placeholder' => '',
        'label' => 'Type of Stay',
        'options' => 
        array (
          0 => 
          array (
            'option' => 'Full Board',
            'option_value' => 'full-board',
            // 'checked' => false
          ),
          1 => 
          array (
            'option' => 'Self Catering',
            'option_value' => 'self-catering',
            // 'checked' => false
          ),
          2 => 
          array (
            'option' => 'Camping',
            'option_value' => 'camping',
            // 'checked' => false
          ),
          3 => 
          array (
            'option' => 'Camping with Use of Self Catering Kitchen',
            'option_value' => 'camping-with-use-of-self-catering-kitchen',
            // 'checked' => false
          ),
          4 => 
          array (
            'option' => 'Camping with Meals',
            'option_value' => 'camping-with-meals',
            // 'checked' => false
          ),
          5 => 
          array (
            'option' => 'Other',
            'option_value' => 'other-types-stay',
            // 'checked' => false
          ),
        ),
        'selected_option' => '',
        'help' => '',
      ),
      'form-other-types-of-stay' => 
      array (
        'passed' => false,
        'clean' => '',
        'value' => '',
        'section' => 4,
        'required' => '',
        'type' => 'text',
        'data_type' => 'text',
        'placeholder' => '',
        'label' => 'Other Types of Stay',
        'help' => '',
      ),
      'form-additional-information-section' => 
      array (
        'passed' => true,
        'section' => 5,
        'section_header' => 'Additional Information',
        'section_content' => '',
        'type' => 'section',
        'data_type' => 'section',
      ),
      'form-special-dietary-requirements' => 
      array (
        'passed' => false,
        'clean' => '',
        'value' => '',
        'section' => 5,
        'required' => '',
        'type' => 'textarea',
        'data_type' => 'textarea',
        'placeholder' => '',
        'label' => 'Special Dietary Requirements',
        'help' => '',
      ),
      'form-allergies' => 
      array (
        'passed' => false,
        'clean' => '',
        'value' => '',
        'section' => 5,
        'required' => '',
        'type' => 'textarea',
        'data_type' => 'textarea',
        'placeholder' => '',
        'label' => 'Allergies',
        'help' => '',
      ),
      'form-other-special-requests' => 
      array (
        'passed' => false,
        'clean' => '',
        'value' => '',
        'section' => 5,
        'required' => '',
        'type' => 'textarea',
        'data_type' => 'textarea',
        'placeholder' => '',
        'label' => 'Other Special Requests',
        'help' => '',
      ),
    );
    // $form_data = get_book_inputs(152);
    return $form_data;
}