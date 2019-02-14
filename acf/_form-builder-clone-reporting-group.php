<?php
if( function_exists('acf_add_local_field_group') ):

acf_add_local_field_group(array(
	'key' => 'group_5bfbe09d3bf4f',
	'title' => 'Form Builder Clone: Reporting Group',
	'fields' => array(
		array(
			'key' => 'field_5bfbe09d3e240',
			'label' => 'Help Message',
			'name' => 'help',
			'type' => 'text',
			'instructions' => 'Displayed on error',
			'required' => 0,
			'conditional_logic' => 0,
			'wrapper' => array(
				'width' => '50',
				'class' => 'group-start',
				'id' => '',
			),
			'default_value' => '',
			'placeholder' => 'Eg. Email is not required but must be valid',
			'prepend' => '',
			'append' => '',
			'maxlength' => '',
		),
		array(
			'key' => 'field_5bfbe09d3e26c',
			'label' => 'Instructions',
			'name' => 'instructions',
			'type' => 'text',
			'instructions' => 'Always visible message',
			'required' => 0,
			'conditional_logic' => 0,
			'wrapper' => array(
				'width' => '50',
				'class' => 'group-end',
				'id' => '',
			),
			'default_value' => '',
			'placeholder' => '',
			'prepend' => '',
			'append' => '',
			'maxlength' => '',
		),
	),
	'location' => array(
		array(
			array(
				'param' => 'post_type',
				'operator' => '==',
				'value' => 'post',
			),
		),
	),
	'menu_order' => 0,
	'position' => 'normal',
	'style' => 'default',
	'label_placement' => 'top',
	'instruction_placement' => 'label',
	'hide_on_screen' => '',
	'active' => 0,
	'description' => '',
));

endif;