<?php
function cptui_register_my_taxes_wp_swift_form_category() {

	/**
	 * Taxonomy: Category.
	 */

	$labels = array(
		"name" => __( "Category", "" ),
		"singular_name" => __( "Category", "" ),
	);

	$args = array(
		"label" => __( "Category", "" ),
		"labels" => $labels,
		"public" => false,
		"hierarchical" => false,
		"label" => "Category",
		"show_ui" => false,
		"show_in_menu" => false,
		"show_in_nav_menus" => false,
		"query_var" => false,
		"rewrite" => array( 'slug' => 'wp_swift_form_category', 'with_front' => false, ),
		"show_admin_column" => true,
		"show_in_rest" => true,
		"rest_base" => "",
		"show_in_quick_edit" => false,
	);
	register_taxonomy( "wp_swift_form_category", array( "wp_swift_form" ), $args );
}

add_action( 'init', 'cptui_register_my_taxes_wp_swift_form_category' );
