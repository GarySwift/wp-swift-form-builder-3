<?php
if( function_exists('acf_add_local_field_group') ):

acf_add_local_field_group(array(
	'key' => 'group_5bfbdda24b2a3',
	'title' => 'Form Builder Clone: ID Group',
	'fields' => array(
		array(
			'key' => 'field_5bfbde4c9c69c',
			'label' => 'Name',
			'name' => 'form_input_name',
			'type' => 'text',
			'instructions' => 'Input identifier',
			'required' => 1,
			'conditional_logic' => 0,
			'wrapper' => array(
				'width' => '50',
				'class' => 'group-start',
				'id' => '',
			),
			'default_value' => '',
			'placeholder' => 'Eg. First Name',
			'prepend' => '',
			'append' => '',
			'maxlength' => '',
		),
		array(
			'key' => 'field_5bfbde229c69b',
			'label' => 'Label',
			'name' => 'label',
			'type' => 'text',
			'instructions' => 'If different from name',
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