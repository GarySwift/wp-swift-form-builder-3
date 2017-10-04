<?php
function cptui_register_my_cpts_wp_swift_form() {

	/**
	 * Post Type: Forms.
	 */

	$labels = array(
		"name" => __( "Forms", "" ),
		"singular_name" => __( "Form", "" ),
	);

	$args = array(
		"label" => __( "Forms", "" ),
		"labels" => $labels,
		"description" => "",
		"public" => false,
		"publicly_queryable" => false,
		"show_ui" => true,
		"show_in_rest" => false,
		"rest_base" => "",
		"has_archive" => false,
		"show_in_menu" => true,
		"exclude_from_search" => false,
		"capability_type" => "post",
		"map_meta_cap" => true,
		"hierarchical" => false,
		"rewrite" => array( "slug" => "wp_swift_form", "with_front" => false ),
		"query_var" => false,
		"menu_icon" => "dashicons-welcome-write-blog",
		"supports" => array( "title" ),
	);

	register_post_type( "wp_swift_form", $args );
}

add_action( 'init', 'cptui_register_my_cpts_wp_swift_form' );