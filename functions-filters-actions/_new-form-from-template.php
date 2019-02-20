<?php
// Include the wp-load'er
include('../../../../wp-load.php');
require_once plugin_dir_path( __FILE__ ) . '_save-post-action.php';

if (isset($_POST['wp_swift_form_builder_preset'])) {

    $id = $_POST['wp_swift_form_builder_preset'];
    $file_path = plugin_dir_path( __DIR__ ). 'template-json/';
    $file_name = 'template-'.$id.'.json';
    $file = $file_path.$file_name;

    if (file_exists($file)) {
        $json = file_get_contents($file);
        $form_data_preset = json_decode($json, true);

        $args = array(
          'post_type' => 'wp_swift_form',
          'post_title'   => 'Contact Form',
          'post_status'   => 'publish',
        );

        $post_id = wp_insert_post($args);
        if(!is_wp_error($post_id)) {
            $update_post = array(
                'ID'           => $post_id,
                'post_title'   => 'Contact Form '.$post_id,
            );
            // Update the post title with post id
            wp_update_post( $update_post );            
            // Prepopulate the ACF field with input details (this is not processed form data)
            update_field( 'field_59cf72b083b6d', $form_data_preset, $post_id );
            // Process the form data into a Wordpress option
            wp_swift_form_builder_save_post($post_id);
            // Redirect
            header('Location: '.admin_url('post.php?post='.$post_id.'&action=edit', 'http'));
            exit;
        }
    }
}
header('Location: '.admin_url('?wp_swift_form_builder_new_contact_form_error'));
exit;