<?php

acf_add_options_sub_page(array(
    'page_title'    => 'Theme Footer Settings',
    'menu_title'    => 'Footer',
    'parent_slug'   => 'theme-general-settings',
));

// add_submenu_page( 'edit.php?post_type=wp_swift_form', 'Form Builder Marketing', 'Marketing', 'manage_options', 'form_builder_marketing', array($this, 'wp_swift_form_builder_marketing')  );

if( function_exists('acf_add_local_field_group') ):

acf_add_local_field_group(array(
	'key' => 'group_5d1616e948896',
	'title' => 'Marketing',
	'fields' => array(
		array(
			'key' => 'field_5d1616e99fa8d',
			'label' => 'Form',
			'name' => 'form',
			'type' => 'post_object',
			'instructions' => 'The form used in the modal.',
			'required' => 0,
			'conditional_logic' => 0,
			'wrapper' => array(
				'width' => '',
				'class' => '',
				'id' => '',
			),
			'post_type' => array(
				0 => 'wp_swift_form',
			),
			'taxonomy' => '',
			'allow_null' => 0,
			'multiple' => 0,
			'return_format' => 'id',
			'ui' => 1,
		),
	),
	'location' => array(
		array(
			array(
				'param' => 'options_page',
				'operator' => '==',
				'value' => 'acf-options-header',
			),
		),
	),
	'menu_order' => 0,
	'position' => 'normal',
	'style' => 'default',
	'label_placement' => 'top',
	'instruction_placement' => 'label',
	'hide_on_screen' => '',
	'active' => true,
	'description' => '',
));

endif;
// if( function_exists('acf_add_local_field_group') ):

// acf_add_local_field_group(array(
// 	'key' => 'group_5d07a16d6737f',
// 	'title' => 'Feature Selector',
// 	'fields' => array(
// 		array(
// 			'key' => 'field_5d07a17e34a2d',
// 			'label' => 'Form',
// 			'name' => 'form',
// 			'type' => 'post_object',
// 			'instructions' => '',
// 			'required' => 0,
// 			'conditional_logic' => 0,
// 			'wrapper' => array(
// 				'width' => '',
// 				'class' => '',
// 				'id' => '',
// 			),
// 			'post_type' => array(
// 				0 => 'wp_swift_form',
// 			),
// 			'taxonomy' => '',
// 			'allow_null' => 0,
// 			'multiple' => 0,
// 			'return_format' => 'id',
// 			'ui' => 1,
// 		),
// 		array(
// 			'key' => 'field_5d14cd1b4f2dd',
// 			'label' => 'Page Link',
// 			'name' => 'page_link',
// 			'type' => 'post_object',
// 			'instructions' => '',
// 			'required' => 0,
// 			'conditional_logic' => 0,
// 			'wrapper' => array(
// 				'width' => '',
// 				'class' => '',
// 				'id' => '',
// 			),
// 			'post_type' => array(
// 				0 => 'page',
// 			),
// 			'taxonomy' => '',
// 			'allow_null' => 0,
// 			'multiple' => 0,
// 			'return_format' => 'id',
// 			'ui' => 1,
// 		),
// 	),
// 	'location' => array(
// 		array(
// 			array(
// 				'param' => 'page_template',
// 				'operator' => '==',
// 				'value' => 'templates/page-sign-up.php',
// 			),
// 		),
// 	),
// 	'menu_order' => 0,
// 	'position' => 'normal',
// 	'style' => 'default',
// 	'label_placement' => 'top',
// 	'instruction_placement' => 'label',
// 	'hide_on_screen' => '',
// 	'active' => true,
// 	'description' => '',
// ));

// endif;