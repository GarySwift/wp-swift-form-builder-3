<?php
/*
 * Include the WordPress Admin API interface settings for this plugin.
 * This will declare all menu pages, tabs and inputs etc but it does not
 * handle any business logic related to form functionality.
 */
class WP_Swift_Form_Builder_Admin_Interface_Tools {

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

	    add_submenu_page( 'edit.php?post_type=wp_swift_form', 'Form Builder Tools', 'Tools', 'manage_options', 'form_builder_tools', array($this, 'wp_swift_form_builder_tools')  );
	}

	/*
	 *
	 */
	public function wp_swift_form_builder_settings_init(  ) { 

	    register_setting( 'form-builder-tools', 'wp_swift_form_builder_settings' );
	    add_settings_section(
	        'wp_swift_form_builder_plugin_page_section', 
	        __( 'FormBuilder Tools', 'wp-swift-form-builder' ), 
	        array($this, 'wp_swift_form_builder_settings_section_callback'), 
	        'form-builder-tools'
	    );
	}

	/*
	 *
	 */
	public function wp_swift_form_builder_tools(  ) { 
	    ?>
	        <div id="form-builder-wrap" class="wrap">
		        <h2>FormBuilder Tools</h2>
		        <br>

				<div id="export-import-sections">
					
					<div id="export-section" class="export-import-section">

						<div id="" class="postbox">
							<h2 class="hndle postbox-header"><span>Export</span></h2>
							<div class="inside">
								<p><?php echo __( 'Select the form you would like to export and and use the download button to export to a .json file which you can then import to another WordPress installation.', 'wp-swift-form-builder' ); ?></p>
								<?php 
								if ( isset($_POST["form-id"]) ):
									$form_id = $_POST["form-id"];
									$form_data_preset = get_field('sections', $form_id, true);
									$json = json_encode($form_data_preset);
									?>

									<textarea class="copy-area" onclick="this.focus();this.select();document.execCommand('copy')" onfocus="this.focus();this.select();document.execCommand('copy')" readonly><?php echo $json ?></textarea>

									<?php 
								else:

									$posts = get_posts(array(
									 	'posts_per_page'	=> -1,
									 	'post_type'			=> 'wp_swift_form',
		 
									));
								 
								 	if( $posts ): ?>
									 	<form action="<?php echo admin_url( 'edit.php?post_type=wp_swift_form&page=form_builder_tools' ); ?>" method="post">
									 	
										 	<ul>
										 		
											 	<?php foreach( $posts as $post ): ?>

											 		<li>
											 			<label for="form-<?php echo $post->ID; ?>">
											 				<input type="radio" value="<?php echo $post->ID; ?>" name="form-id" id="form-<?php echo $post->ID; ?>"><?php echo $post->post_title; ?>
											 			</label>
											 		</li>
											 	
											 	<?php endforeach; ?>
										 	
										 	</ul>

										 	<?php submit_button( "Export Form" ); ?>
										</form>
									 	<?php 
								 	endif;

								endif; ?>

							</div>
						</div>

					</div>
					<div id="import-section" class="export-import-section">
						
						<div id="" class="postbox">
							<h2 class="hndle postbox-header"><span>Import</span></h2>
							<div class="inside">
							
								<?php 
								if ( isset($_POST["import-form"]) ):
									$sections = $_POST["import-form"];
									$json = str_replace('\"', '"', $sections);
									$form_data_preset = json_decode($json, true);
									if (is_array($form_data_preset)): ?>

										<p><?php echo __( 'Please be patient! This could take a moment.' ); ?></p>

										<textarea class="paste-area" name="import-form"><?php var_export($form_data_preset) ?></textarea>
										
										<?php

									        $args = array(
									          'post_type' => 'wp_swift_form',
									          'post_title'   => 'Imported Form',
									          'post_status'   => 'publish',
									        );

									        $post_id = wp_insert_post($args);
									        if(!is_wp_error($post_id)) {
									            $update_post = array(
									                'ID'           => $post_id,
									                'post_title'   => 'Imported Form '.$post_id,
									            );
									            // Update the post title with post id
									            wp_update_post( $update_post );            
									            // Prepopulate the ACF field with input details (this is not processed form data)
									            update_field( 'field_59cf72b083b6d', $form_data_preset, $post_id );
									            // Process the form data into a Wordpress option
									            wp_swift_form_builder_save_post($post_id);
									        }
																		 ?>
										 <p><?php echo __( 'Form Imported!' ); ?></p>
										 <p><a href="<?php echo admin_url('post.php?post='.$post_id.'&action=edit', 'http') ?>">Edit Form</a></p>
									<?php else: ?>
										<p><?php echo __( 'Unable to read file!' ); ?></p>
									<?php endif;								
								else:

								 
								 	?>
								 	<p><?php echo __( 'Paste the expoted json into here and cclick the <b>Import Form</b> button.' ); ?></p>

									<form action="<?php echo admin_url( 'edit.php?post_type=wp_swift_form&page=form_builder_tools' ); ?>" method="post">

										<textarea class="paste-area" name="import-form"></textarea>
										<?php submit_button( "Import Form" ); ?>

									</form>
									<?php 

								endif; ?>

							</div>
						</div>

					</div>
				</div>

	        </div>
	    <?php 
	}
}
// Initialize the class
$form_builder_admin_interface_tools = new WP_Swift_Form_Builder_Admin_Interface_Tools();