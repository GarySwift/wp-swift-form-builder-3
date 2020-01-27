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
		$section_acf_key = 'field_5cff77477c15c';
		$form_type_acf_key = 'field_5cff741fba730';
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

									$data = array();
									$form_id = $_POST["form-id"];
									$sections = array();
									$form_data_preset = array();

									$title = array(
										"form-title" => array( 
											"key" => "post_title",
											"value" => get_the_title( $form_id ),//"Test Title",// 
										),
									);

									$form_data_preset[] = $title;							

									$form_data_preset[] = array(
										"sections" => array( 
											"key" => "field_5cff77477c15c",
											"value" =>  get_field('sections', $form_id, true),
										),
									);
			
									$form_data_preset[] = array(
										"form_type" => array( 
											"key" => "field_5cff741fba730",
											"value" =>  get_field('form_type', $form_id, true),
										),
									);								

									$general = array();
									$general["labels"] = array(
										"key" => "field_5c896d0ca5b65",
										"value" => get_field('labels', $form_id, true),
									);
									$general["wrap_form"]  = array(
										"key" => "field_5c80ea6c2fb10",
										"value" => get_field('wrap_form', $form_id, true),
									);

									$general["transparent_inputs"] = array(
										"key" => "field_5c81398d14242",
										"value" => get_field('transparent_inputs', $form_id, true),
									);

									$submit_button_text = get_field('submit_button_text', $form_id, true);
									
									$general["submit_button_text"] = array(
										"key" => "field_5c80ea6c2fb1c",
										"value" => addslashes(get_field('submit_button_text', $form_id, true)),
										"format" => "addslashes",
										"unformat" => "stripslashes",
									);

									$general["user_confirmation_email"] = array(
										"key" => "field_5c80ea6c2fb28",
										"value" => get_field('user_confirmation_email', $form_id, true),
									);

									$general["show_page_in_email"] = array(
										"key" => "field_5c80ea6c2fb33",
										"value" => get_field('show_page_in_email', $form_id, true),
									);		

									$general["show_edit_link"] = array(
										"key" => "field_5c80ea6c2fb3f",
										"value" => get_field('show_edit_link', $form_id, true),
									);

									$general["tab_index"] = array(
										"key" => "field_5c80ea6c2fb4a",
										"value" => get_field('tab_index', $form_id, true),
									);	

									$general["colour_theme"] = array(
										"key" => "field_5c8ab19817df1",
										"value" => get_field('colour_theme', $form_id, true),
									);

									$general["css"] = array(
										"key" => "field_5c8ab10c17dee",
										"value" => get_field('css', $form_id, true),
									);		

									$general["displaying_results"] = array(
										"key" => "field_5d0b8b8a46e9e",
										"value" => get_field('displaying_results', $form_id, true),
									);	

									$general["next_button_in_sections"] = array(
										"key" => "field_5c80ea9626f3d",
										"value" => get_field('next_button_in_sections', $form_id, true),
									);	
									
									$general["show_section_stage_guide"] = array(
										"key" => "field_5c8b8293904b0",
										"value" => get_field('show_section_stage_guide', $form_id, true),
									);								

									$form_data_preset[] = $general;

									$form_data_preset[] = array(
										"spam_prevention_type" => array( 
											"key" => "field_5cff71d409587",
											"value" =>  get_field('spam_prevention_type', $form_id, true),
										),
									);

									$form_data_preset[] = array(
										"recaptcha_display_settings" => array( 
											"key" => "field_5cff71d40959d",
											"value" =>  get_field('recaptcha_display_settings', $form_id, true),
										),
									);

									$form_data_preset[] = array(
										"recaptcha_settings" => array( 
											"key" => "field_5cff71d409592",
											"value" =>  get_field('recaptcha_settings', $form_id, true),
										),
									);

									$form_data_preset[] = array(
										"marketing" => array( 
											"key" => "field_5d1b0f1560a64",
											"value" =>  get_field('marketing', $form_id, true),
										),
									);

									$form_data_preset[] = array(
										"consent" => array( 
											"key" => "field_5d1b0f3f60a65",
											"value" =>  get_field('consent', $form_id, true),
										),
									);


									$json = json_encode($form_data_preset, JSON_HEX_QUOT | JSON_HEX_APOS);	

									$form_data_preset = json_decode($json, true);	
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
									$form_data = $_POST["import-form"];
									$json = str_replace('\"', '"', $form_data);
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

											$sections = null;
											$form_type = null;

									        $post_id = wp_insert_post($args);
									        $post_title = false;	
									        if(!is_wp_error($post_id)) {
		         

									            // Prepopulate the ACF field with input details (this is not processed form data)

								        		foreach ($form_data_preset as $section) {

													foreach ( $section as $key => $field) {

										        		if ($key == "form-title") {
	
										        			$count = 0;
										        			if (get_page_by_title($field["value"], OBJECT, 'wp_swift_form')) {
										        				// Title exists
										        				$count++;
										        				
										        				while (!$post_title) {
										        					
										        					$title = $field["value"] . ' ('.($count) .')';

										        			    	if (!get_page_by_title($title, OBJECT, 'wp_swift_form')) {
										        			    		$post_title = $title;
										        			    	}

																    if($count > 100) $post_title = 'Imported Form '.$post_id;
																    
																    $count++;
																}
										        			}
										        			else {
										        				// Title does not exist
										        				$post_title = $field["value"];
										        			}

										        		}
											        	elseif (isset($field["unformat"])) {
											        		switch ($field["unformat"]) {
											        			case "stripslashes":
											        				update_field( $field["key"], stripslashes ( $field["value"] ), $post_id );
											        				break;
											        		}
											        	} else {
											        		update_field( $field["key"], $field["value"], $post_id );
											        	}
											        }

								        		}

								        		if ($post_title) {
								        		
									        		$update_post = array(
										                'ID'           => $post_id,
										                'post_title'   => $post_title,
										            );

										            // Update the post title with post id
										            wp_update_post( $update_post );  								        			
									        	
									        	} 

									            // Process the form data into a Wordpress option
									            wp_swift_form_builder_save_post($post_id);
									        }


																		 ?>
										 <p><?php echo __( 'Form Imported!' ); ?></p>
										 <p><a href="<?php echo admin_url('post.php?post='.$post_id.'&action=edit', 'http') ?>">Edit Form</a></p>
									<?php else: ?>
										<?php echo '<pre>$form_data_preset: '; var_dump($form_data_preset); echo '</pre>'; ?>
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