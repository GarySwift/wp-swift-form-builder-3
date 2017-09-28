<?php
/**
 * The admin menu settings.
 *
 * @author 	 Gary Swift 
 * @since    1.0.0
 */


function wp_swift_form_builder_add_admin_menu() {

	$menu_slug = wp_swift_form_builder_admin_menu_slug();

	if ( function_exists('wp_swift_admin_menu_slug') ) {
        add_submenu_page( $menu_slug, 'WP Swift: Form Builder', 'Form Builder', 'manage_options', 'wp-swift-form-builder', 'wp_swift_form_builder_options_page' );
    }
    else {
        add_options_page( 'WP Swift: Form Builder', 'Form Builder', 'manage_options', 'wp-swift-form-builder', 'wp_swift_form_builder_options_page' );
    }		
}



function wp_swift_form_builder_options_page( ) { 
	?>

	<div class="wrap">
		<h1>WP Swift: Form Builder</h1>
	</div>

	<?php
}
