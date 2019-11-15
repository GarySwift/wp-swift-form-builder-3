<?php
/*
 * Include the WordPress Admin API interface settings for this plugin.
 * This will declare all menu pages, tabs and inputs etc but it does not
 * handle any business logic related to form functionality.
 */
class WP_Swift_Form_Builder_Admin_Interface_Debug_Settings {
	private $settings = 'form-builder-debug-settings';

    /*
     * Initializes the plugin.
     */
    public function __construct() {
        /*
         * Inputs
         */
        add_action( 'admin_menu', array($this, 'wp_swift_form_builder_add_admin_menu'), 20 );
        add_action( 'admin_init', array($this, 'wp_swift_form_builder_debug_settings_init') );
    }	

	/*
	 *
	 */
	public function wp_swift_form_builder_add_admin_menu(  ) { 

	    add_submenu_page( 'edit.php?post_type=wp_swift_form', 'Form Builder Debug Settings', 'Debug', 'manage_options', 'form_builder_debug', array($this, 'wp_swift_form_builder_options_page')  );

	}

	/*
	 *
	 */
	public function wp_swift_form_builder_debug_settings_init(  ) { 


	    register_setting( $this->settings, 'wp_swift_form_builder_debug_settings' );

	    add_settings_section(
	        'wp_swift_form_builder_plugin_page_section', 
	        __( 'Set your debug preferences for the Form Builder here.', 'wp-swift-form-builder' ), 
	        array($this, 'wp_swift_form_builder_debug_settings_section_callback'), 
	        $this->settings
	    );


	    add_settings_field( 
			'wp_swift_form_builder_debug_mode', 
			__( 'Console Feedback', 'wp-swift-form-builder' ), 
			array($this, 'wp_swift_form_builder_debug_mode_render'),  
			$this->settings, 
			'wp_swift_form_builder_plugin_page_section' 
	    );

	    add_settings_field( 
			'wp_swift_form_builder_email_debug_mode', 
			__( 'Prevent Email Sending', 'wp-swift-form-builder' ), 
			array($this, 'wp_swift_form_builder_email_debug_mode_render'),  
			$this->settings, 
			'wp_swift_form_builder_plugin_page_section' 
	    );


	    add_settings_field( 
			'wp_swift_form_builder_marketing_debug_mode', 
			__( 'Disable Marketing', 'wp-swift-form-builder' ), 
			array($this, 'wp_swift_form_builder_marketing_debug_mode_render'),  
			$this->settings, 
			'wp_swift_form_builder_plugin_page_section' 
	    );

	}

	/*
	 *
	 */
	public function wp_swift_form_builder_marketing_debug_mode_render(  ) { 

		
	    $options = get_option( 'wp_swift_form_builder_debug_settings' );

	    ?>
	    <input type='checkbox' name='wp_swift_form_builder_debug_settings[wp_swift_form_builder_marketing_debug_mode]' <?php 
	     if (isset($options['wp_swift_form_builder_marketing_debug_mode'])) {
	         checked( $options['wp_swift_form_builder_marketing_debug_mode'], 1 );
	     } 
	    ?> value='1'>
	    <small>This will prevent marketing sign-ups.</small>
	    <?php

	}

	/*
	 *
	 */
	public function wp_swift_form_builder_debug_mode_render(  ) { 

		
	    $options = get_option( 'wp_swift_form_builder_debug_settings' );

	    ?>
	    <input type='checkbox' name='wp_swift_form_builder_debug_settings[wp_swift_form_builder_debug_mode]' <?php 
	     if (isset($options['wp_swift_form_builder_debug_mode'])) {
	         checked( $options['wp_swift_form_builder_debug_mode'], 1 );
	     } 
	    ?> value='1'>
	    <small>This will allow JavaScript to log certain information about the process.</small>
	    <?php

	}

	/*
	 *
	 */
	public function wp_swift_form_builder_email_debug_mode_render(  ) { 

		
	    $options = get_option( 'wp_swift_form_builder_debug_settings' );

	    ?>
	    <input type='checkbox' name='wp_swift_form_builder_debug_settings[wp_swift_form_builder_email_debug_mode]' <?php 
	     if (isset($options['wp_swift_form_builder_email_debug_mode'])) {
	         checked( $options['wp_swift_form_builder_email_debug_mode'], 1 );
	     } 
	    ?> value='1'>
	    <small>This will prevent emails form being sent and output status information.</small>
	    <?php

	}
	/*
	 *
	 */
	public function wp_swift_form_builder_debug_settings_section_callback(  ) { 

	    echo __( 'You can set this to debug mode if you are a developer. This will skip default behaviour such as sending emails.', 'wp-swift-form-builder' );

	    echo __( '<br><br><small><b>Do not use on live sites!</b></small>', 'wp-swift-form-builder' );

	}

	/*
	 *
	 */
	public function wp_swift_form_builder_options_page(  ) { 
	    ?>
	        <div id="form-builder-wrap" class="wrap">
		        <h2>WP Swift: Form Builder</h2>

		        <form action='options.php' method='post'>
		            
		            <?php
		            settings_fields( $this->settings );
		            do_settings_sections( $this->settings );
		            echo __( '<small><b>Do not use on live sites!</b></small>', 'wp-swift-form-builder' );
		            submit_button('Set Debug Options');
		            ?>

		        </form>
	        </div>
	    <?php 
	}
}
// Initialize the class
$form_builder_admin_interface_debug_settings = new WP_Swift_Form_Builder_Admin_Interface_Debug_Settings();