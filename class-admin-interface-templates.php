<?php
/*
 * Include the WordPress Admin API interface settings for this plugin.
 * This will declare all menu pages, tabs and inputs etc but it does not
 * handle any business logic related to form functionality.
 */
class WP_Swift_Form_Builder_Admin_Interface_Templates {

    /*
     * Initializes the plugin.
     */
    public function __construct() {
        /*
         * Inputs
         */
        add_action( 'admin_menu', array($this, 'wp_swift_form_builder_add_admin_menu'), 20 );
        add_action( 'admin_init', array($this, 'wp_swift_form_builder_settings_init') );
    }	

	/*
	 *
	 */
	public function wp_swift_form_builder_add_admin_menu(  ) { 

	    add_submenu_page( 'edit.php?post_type=wp_swift_form', 'Form Builder Templates', 'Templates', 'manage_options', 'form_builder_templates', array($this, 'wp_swift_form_builder_templates')  );
   

	}

	// public function wp_swift_form_builder_templates(  ) { 

	// }
	/*
	 *
	 */
	public function wp_swift_form_builder_settings_init(  ) { 


	    register_setting( 'form-builder-templates', 'wp_swift_form_builder_settings' );

	    add_settings_section(
	        'wp_swift_form_builder_plugin_page_section', 
	        __( 'Starter Template Forms', 'wp-swift-form-builder' ), 
	        array($this, 'wp_swift_form_builder_settings_section_callback'), 
	        'form-builder-templates'
	    );

// add_settings_field( 
// 			'wp_swift_form_builder_checkbox_debug_mode', 
// 			__( 'Debug Mode', 'wp-swift-form-builder' ), 
// 			array($this, 'wp_swift_form_builder_checkbox_debug_mode_render'),  
// 			'form-builder-templates', 
// 			'wp_swift_form_builder_plugin_page_section' 
// 	    );
	}

	/*
	 *
	 */
	public function wp_swift_form_builder_settings_section_callback(  ) { 
	    ?>

	    <p><?php echo __( 'Preset Form Builder templates are shown below. Click each button to create a form with the inputs shown.', 'wp-swift-form-builder' ); ?></p>
	    <p><?php echo __( 'Use these as starter points to see how forms are created. You can easily add/remove inputs to suit your needs.', 'wp-swift-form-builder' ); ?></p>
	    <?php
	}

		/*
	 *
	 */
	public function wp_swift_form_builder_checkbox_debug_mode_render(  ) { 

	    $options = get_option( 'wp_swift_form_builder_settings' );
	    ?><input type="checkbox" name="wp_swift_form_builder_settings[wp_swift_form_builder_checkbox_debug_mode]" value="1" <?php 
	    	if (isset($options['wp_swift_form_builder_checkbox_debug_mode'])) {
	         	checked( $options['wp_swift_form_builder_checkbox_debug_mode'], 1 );
	     	} 
	    ?>>
	    <small><b>Do not use on live sites!</b></small><br>
	    <small>You can set this to debug mode if you are a developer. This will skip default behaviour such as sending emails.</small><?php

	}
	/*
	 *
	 */
	public function wp_swift_form_builder_templates(  ) { 
	    ?>
	        <div id="form-builder-wrap" class="wrap">
	        <h2>Templates</h2>

	        	        <form action='options.php' method='post'>
	            
	            <?php
	            settings_fields( 'form-builder-templates' );
	            do_settings_sections( 'form-builder-templates' );
	            // submit_button();
	            ?>

	        </form>

	        

			<div class="wp-swift-form-builder-template">
				<div class="image"><img src="<?php echo plugin_dir_url( __FILE__ ) .'/admin/images/' ?>contact-form.svg" alt="Template Image" id="contact-form-svg-1" class="contact-form-svg"></div>
				<form action="<?php echo plugin_dir_url( __FILE__ ) . '_new-form-from-template.php'; ?>" method="post">
					<input type="text" name="wp_swift_form_builder_preset" value="1" hidden>
					<input type="submit" value="New Form from Template" class="new-form-from-template">
				</form>
			</div>

			<div class="wp-swift-form-builder-template">
				<div class="image"><img src="<?php echo plugin_dir_url( __FILE__ ) .'/admin/images/' ?>contact-form-2.svg" alt="Template Image" id="contact-form-svg-2" class="contact-form-svg"></div>
				<form action="<?php echo plugin_dir_url( __FILE__ ) . '_new-form-from-template.php'; ?>" method="post">
					<input type="text" name="wp_swift_form_builder_preset" value="2" hidden>
					<input type="submit" value="New Form from Template" class="new-form-from-template">
				</form>
			</div>

	        </div>
	    <?php 
	}
}
// Initialize the class
$form_builder_admin_interface_templates = new WP_Swift_Form_Builder_Admin_Interface_Templates();