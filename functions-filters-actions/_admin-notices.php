<?php
/**
 * Check the GET request for new form error
 */
if (isset($_GET['wp_swift_form_builder_new_contact_form_error'])) {
    add_action( 'admin_notices', 'wp_swift_form_builder_new_contact_form_error' );
}
/**
 * Displays an error notice abaout new form failure
 */
function wp_swift_form_builder_new_contact_form_error() {
	$class = 'notice notice-error';
	$header	= '<b>WP Swift: Form Builder</b><br>';
	$message = __( 'An error has occurred while attempting to create a new form.', 'wp-swift-form-builder' );
	printf( '<div class="%1$s"><p>%2$s</p></div>', esc_attr( $class ), $header.esc_html( $message ) ); 
}

//
/**
 *  Displays a notice if the Advanced Custom Fields plugin is not active on Form Builder pages
 */
function wp_swift_form_builder_admin_notice_install_acf() {
	$show_error = false;
	if (( isset($_GET["page"]) && $_GET["page"] == "form_builder_templates" ) 
		|| ( isset($_GET["post_type"]) && $_GET["post_type"] == "wp_swift_form" )) {
		
		if (!function_exists( 'acf' )) {
			$show_error = true;
		}
	}

    if ( $show_error  ) : ?>
	    
	    <div class="error notice">
	    	<p><b><?php _e( 'WP Swift: Form Builder', 'wp-swift-form-builder' ); ?></b></p>
	        <p><?php _e( 'Please install <b>Advanced Custom Fields Pro</b>. It is required for this plugin to work properly! | <a href="http://www.advancedcustomfields.com/pro/" target="_blank">ACF Pro</a>', 'wp-swift-form-builder' ); ?></p>
	    </div>
	    <?php 	
   	endif;

}
add_action( 'admin_notices', 'wp_swift_form_builder_admin_notice_install_acf' );