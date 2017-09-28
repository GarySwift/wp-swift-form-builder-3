<?php
// Displays a notice if the Advanced Custom Fields plugin is not active.
function wp_swift_form_builder_admin_notice_install_acf() {
    if ( isset($_GET["page"]) && $_GET["page"] == "wp-swift-form-builder"  ) : ?>
	    <?php if (!function_exists( 'acf' )): ?>
	    <div class="error notice">
	        <p><?php _e( 'Please install <b>Advanced Custom Fields Pro</b>. It is required for this plugin to work properly! | <a href="http://www.advancedcustomfields.com/pro/" target="_blank">ACF Pro</a>', 'wp-swift-form-builder' ); ?></p>
	        <small><i><?php _e( 'Option page will not show until this is installed', 'wp-swift-form-builder' ); ?></i></small>
	    </div>
	    <?php endif;    	
   	endif;

}
add_action( 'admin_notices', 'wp_swift_form_builder_admin_notice_install_acf' );


// Displays a notice if the Advanced Custom Fields plugin is not active.
function wp_swift_form_builder_admin_notice_install_wp_swift_admin_menu() {
    if ( isset($_GET["page"]) && ($_GET["page"] == "wp-swift-form-builder" || $_GET["page"] == "form-builder-settings")  ) : ?>
	    <?php if (!function_exists( 'wp_swift_admin_menu_slug' )): ?>
	    <div class="notice notice-warning">
	        <p><?php _e( 'Please install <b>WP Swift: Admin Menu</b>. It is not required but we do recommend it. | <a href="https://github.com/wp-swift-wordpress-plugins/wp-swift-admin-menu" target="_blank">WP Swift: Admin Menu</a>', 'wp-swift-admin-menu' ); ?></p>
	        <small><i><?php _e( 'This will place all <b>WP Swift</b> plugins under the same menu', 'wp-swift-form-builder' ); ?></i></small>
	    </div>
	    <?php endif;    	
   	endif;

}
add_action( 'admin_notices', 'wp_swift_form_builder_admin_notice_install_wp_swift_admin_menu' );