<?php
/**
 * These are the settings for backend admin page. This includes a settings
 * tab and help tab. This page will show under the setttings menus 
 * unless the wp-swift-admin-menu plugin is activated where it will show
 * show under that menu instead.
 * 
 * @author 	 Gary Swift 
 * @since    1.0.0
 */
add_action( 'admin_menu', 'wp_swift_form_builder_add_admin_menu' );
add_action( 'admin_init', 'wp_swift_form_builder_settings_init' );

/*
 * This determines the location the settings page
 * It are listed under Settings unless the other plugin 'wp_swift_admin_menu' is activated
 */
function wp_swift_form_builder_add_admin_menu(  ) { 
	if ( get_option( 'wp_swift_admin_menu' ) ) {
        add_submenu_page( 'wp-swift-admin-menu', 'WP Swift: Form Builder', 'Form Builder', 'manage_options', 'wp_swift_form_builder', 'wp_swift_form_builder_options_page' );
    }
    else {
        add_options_page( 'WP Swift: Form Builder', 'Form Builder', 'manage_options', 'wp_swift_form_builder', 'wp_swift_form_builder_options_page' );
    }
}

function wp_swift_form_builder_settings_init(  ) { 

	/*
	 * Settings tab
	 */
	register_setting( 'settings_tab', 'wp_swift_form_builder_settings' );

	add_settings_section(
		'wp_swift_form_builder_settings_page_section', 
		__( 'Settings Page', 'wp-swift-form-builder' ), 
		'wp_swift_form_builder_settings_section_callback', 
		'settings_tab'
	);

	add_settings_field( 
		'wp_swift_form_builder_checkbox_load_assets', 
		__( 'Load Public Assets', 'wp-swift-form-builder' ), 
		'wp_swift_form_builder_checkbox_load_assets_render', 
		'settings_tab', 
		'wp_swift_form_builder_settings_page_section' 
	);

	/*
	 * Help tab
	 */
	register_setting( 'help_tab', 'wp_swift_form_builder_help' );

	add_settings_section(
		'wp_swift_form_builder_help_page_section', 
		__( 'Help Page', 'wp-swift-form-builder' ), 
		'wp_swift_form_builder_help_section_callback', 
		'help_tab'
	);

	add_settings_field( 
		'wp_swift_form_builder_help_shortcode', 
		__( 'Shortcodes', 'wp-swift-form-builder' ), 
		'wp_swift_form_builder_help_shortcode_render', 
		'help_tab', 
		'wp_swift_form_builder_help_page_section' 
	);

	/*
	 * Help tab
	 */
	register_setting( 'acf_tab', 'wp_swift_form_builder_acf' );	
}



function wp_swift_form_builder_checkbox_load_assets_render( ) { 

		$options = get_option( 'wp_swift_form_builder_settings' );
	?>
	<input type='text' name='wp_swift_form_builder_settings[wp_swift_form_builder_text_field_0]' value='<?php echo $options['wp_swift_form_builder_text_field_0']; ?>'>
	<?php
}

function wp_swift_form_builder_help_shortcode_render( ) { 
?>
<pre class="prettyprint custom">
// WordPress shortcode
[contact_form]
</pre>
<?php
}

function wp_swift_form_builder_settings_section_callback(  ) { 

	echo __( 'WordPress custom post type for FAQ.', 'wp-swift-form-builder' );

}

function wp_swift_form_builder_help_section_callback(  ) { 
	?><p>To render the <b>FAQ</b> onto a webpage there are two options: PHP code and WordPress <a href="https://codex.wordpress.org/Shortcode" target="_blank">Shortcodes</a>.</p><?php
}

function wp_swift_form_builder_options_page(  ) { 
?>

	<div class="wrap">

	<h1>WP Swift: FAQ CPT</h1>

	<?php $active_tab = isset( $_GET[ 'tab' ] ) ? $_GET[ 'tab' ] : 'settings_tab'; ?>

		<div id="icon-options-general" class="icon32"></div>
		<h2 class="nav-tab-wrapper">
		    <a href="?page=wp_swift_form_builder&tab=settings_tab" class="nav-tab <?php echo $active_tab == 'settings_tab' ? 'nav-tab-active' : ''; ?>">Settings</a>
		    <a href="?page=wp_swift_form_builder&tab=help_tab" class="nav-tab <?php echo $active_tab == 'help_tab' ? 'nav-tab-active' : ''; ?>">Help Page</a>
		    <a href="?page=wp_swift_form_builder&tab=acf_tab" class="nav-tab <?php echo $active_tab == 'acf_tab' ? 'nav-tab-active' : ''; ?>">ACF Page</a>
		</h2>

		<div class="metabox-holder has-right-sidebar">
			




		<?php if ($active_tab == 'settings_tab'): ?>
			<?php settings_page_sidebar(); ?>
			
			<div id="post-body">
				<div id="post-body-content">

					<div class="postbox">
						<!-- <h3><span>Metabox in Tab1</span></h3> -->
						<div class="inside">
							<!-- <p>Hi, I'm content visible in the first Tab!</p> -->
							<form action='options.php' method='post'>
								<?php 
									settings_fields( 'settings_tab' );
									do_settings_sections( 'settings_tab' );
									submit_button();
								?>
							</form>

						</div> <!-- .inside -->
					</div>

				</div> <!-- #post-body-content -->
			</div> <!-- #post-body -->	

		<?php elseif($active_tab == 'help_tab'):
			settings_fields( 'help_tab' );
			do_settings_sections( 'help_tab' );
		endif ?>
		</div> <!-- .metabox-holder -->
	</div> <!-- .wrap -->
<?php
}

function settings_page_sidebar() {
		# see http://www.satoripress.com/2011/10/wordpress/plugin-development/clean-2-column-page-layout-for-plugins-70/
		?>
		<div class="inner-sidebar">

			<div class="postbox">
				<h3><span>Sidebar Box</span></h3>
				<div class="inside">
					<p>Hi, I'm a persistent sidebar.<br/>Visible on all Tabs!</p>
				</div>
			</div>

		</div> <!-- .inner-sidebar -->
		<?php
	}