<?php
function cptui_register_my_cpts_wp_swift_form_submit() {

	/**
	 * Post Type: Submissions.
	 */

	$labels = array(
		"name" => __( "Submissions", "" ),
		"singular_name" => __( "Submission", "" ),
		"all_items" => __( "Submissions", "" ),
	);

	$args = array(
		"label" => __( "Submissions", "" ),
		"labels" => $labels,
		"description" => "",
		"public" => false,
		"publicly_queryable" => false,
		"show_ui" => true,
		"show_in_rest" => false,
		"rest_base" => "",
		"has_archive" => false,
		"show_in_menu" => false,
		// "show_in_menu_string" => "edit.php?post_type=wp_swift_form",
		"exclude_from_search" => true,
		"capability_type" => "post",
		"map_meta_cap" => true,
		"hierarchical" => false,
		"rewrite" => false,
		"query_var" => false,
		"menu_icon" => "dashicons-email",
		"supports" => array( "title", "editor" ),
	);

	register_post_type( "wp_swift_form_submit", $args );
}

add_action( 'init', 'cptui_register_my_cpts_wp_swift_form_submit', 100);

/**
 * Add wp_swift_form_submit CPT as a submenu under 'edit.php?post_type=wp_swift_form'
 */
add_action('admin_menu', 'wp_swift_form_submit_admin_menu'); 
function wp_swift_form_submit_admin_menu() { 
	$posts = get_posts(array(
		'posts_per_page'	=> 1,
		'post_type'			=> 'wp_swift_form_submit',  
	));
	/*
	 * Don't show the 'Submissions' unless there are saved submission
	 */		 
	if( $posts ) {
		add_submenu_page('edit.php?post_type=wp_swift_form', 'Submissions', 'Submissions', 'manage_options', 'edit.php?post_type=wp_swift_form_submit');
	}
}

/**
 * Remove the slug metabox
 */
function wp_swift_form_submit_meta_boxes() {
    remove_meta_box( 'slugdiv', 'wp_swift_form_submit', 'normal' );
}
add_action( 'add_meta_boxes', 'wp_swift_form_submit_meta_boxes' );