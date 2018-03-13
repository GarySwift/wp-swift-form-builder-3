<?php
/*
 * Include the WordPress Admin API interface settings for this plugin.
 * This will declare all menu pages, tabs and inputs etc but it does not
 * handle any business logic related to form functionality.
 */
// require_once 'form-builder-wordpress-admin-interface.php';
// require_once 'email-templates/wp-swift-email-templates.php';
/*
 * The main plugin class that will handle business logic related to form 
 * functionality.
 */
class WP_Swift_Form_Submission {

    /*
     * Initializes the plugin.
     */
    public function __construct( $title, $content, $attach = null ) {

        // insert the post
        $post_id = wp_insert_post(array (
            'post_type' => 'wp_swift_form_submit',
            'post_title' => $title,
            'post_content' => $content,
            'post_status' => 'publish',
            'comment_status' => 'closed',
            'ping_status' => 'closed',
        ));

        if ($post_id) {
            // insert post meta
            if (isset($attach["post_id"])) {
                add_post_meta($post_id, "_attach_post_id", $attach["post_id"]);
            }
            if (isset($attach["email"])) {
                add_post_meta($post_id, "_attach_email", $attach["email"]);
            }            
        }
    }
}