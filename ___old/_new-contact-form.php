<?php
/*
 * Puts a 'New Contact Form' link under New Form in the admin menu
 */
// add_action('admin_menu', 'wp_swift_form_builder_new_contact_form'); 
function wp_swift_form_builder_new_contact_form() { 
    add_submenu_page('edit.php?post_type=wp_swift_form', 'New Contact Form', 'New Contact Form', 'manage_options', 'edit.php?post_type=wp_swift_form&wp_swift_form_builder_preset_test_2=1'); 
}
function wp_swift_form_preset() {
    
    if (isset($_GET['wp_swift_form_builder_preset_test_2'])) {
        write_log( $_GET['wp_swift_form_builder_preset_test_2'] );

        $args = array(
          'post_type' => 'wp_swift_form',
          'post_title'   => 'Contact Form (Default)',
          'post_status'   => 'publish',
        );
        write_log($args);

        $post_id = wp_insert_post($args);
        if(!is_wp_error($post_id)) {
            // Prepopulate the ACF field with input details (this is not processed form data)
            update_field( 'field_59cf72b083b6d', wp_swift_get_form_data_preset(), $post_id );
            wp_swift_form_builder_save_post($post_id);
            // wp_redirect(admin_url('post.php?post='.$post_id.'&action=edit', 'http'), 301);
            header('Location: '.admin_url('post.php?post='.$post_id.'&action=edit', 'http'));
            exit;
        }
        else {
            add_action( 'admin_notices', 'wp_swift_form_builder_new_contact_form_error' );
        }
        add_action( 'admin_notices', 'wp_swift_form_builder_new_contact_form_error' );
    }
}

// add_action("init", "wp_swift_form_preset");
function wp_swift_get_form_data_preset() {
    return array (
      0 => 
      array (
        'form_inputs' => 
        array (
          0 => 
          array (
            'id' => 
            array (
              'name' => 'First Name',
              'label' => '',
            ),
            'reporting' => 
            array (
              'help' => '',
              'instructions' => '',
            ),
            'settings' => 
            array (
              'required' => true,
              'grouping' => 'start',
            ),
            'type' => 'text',
            'placeholder' => '',
            'select_options' => false,
            'other' => false,
            'select_type' => 'user',
            'predefined_options' => 
            array (
              0 => 'countries',
            ),
          ),
          1 => 
          array (
            'id' => 
            array (
              'name' => 'Second Name',
              'label' => '',
            ),
            'reporting' => 
            array (
              'help' => '',
              'instructions' => '',
            ),
            'settings' => 
            array (
              'required' => true,
              'grouping' => 'end',
            ),
            'type' => 'text',
            'placeholder' => '',
            'select_options' => false,
            'other' => false,
            'select_type' => 'user',
            'predefined_options' => 
            array (
              0 => 'countries',
            ),
          ),
          2 => 
          array (
            'id' => 
            array (
              'name' => 'Email',
              'label' => '',
            ),
            'reporting' => 
            array (
              'help' => '',
              'instructions' => '',
            ),
            'settings' => 
            array (
              'required' => true,
              'grouping' => 'start',
            ),
            'type' => 'email',
            'placeholder' => '',
            'select_options' => false,
            'other' => false,
            'select_type' => 'user',
            'predefined_options' => 
            array (
              0 => 'countries',
            ),
          ),
          3 => 
          array (
            'id' => 
            array (
              'name' => 'Phone',
              'label' => '',
            ),
            'reporting' => 
            array (
              'help' => '',
              'instructions' => '',
            ),
            'settings' => 
            array (
              'required' => false,
              'grouping' => 'end',
            ),
            'type' => 'text',
            'placeholder' => '',
            'select_options' => false,
            'other' => false,
            'select_type' => 'user',
            'predefined_options' => 
            array (
              0 => 'countries',
            ),
          ),
          4 => 
          array (
            'id' => 
            array (
              'name' => 'Question',
              'label' => '',
            ),
            'reporting' => 
            array (
              'help' => '',
              'instructions' => '',
            ),
            'settings' => 
            array (
              'required' => true,
              'grouping' => 'none',
            ),
            'type' => 'textarea',
            'placeholder' => '',
            'select_options' => false,
            'other' => false,
            'select_type' => 'user',
            'predefined_options' => 
            array (
              0 => 'countries',
            ),
          ),
        ),
        'section_header' => '',
        'section_content' => '',
      ),
    );    
}