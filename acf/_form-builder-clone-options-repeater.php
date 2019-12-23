<?php
if( function_exists('acf_add_local_field_group') ):

acf_add_local_field_group(array(
	'key' => 'group_5dff88e1106d5',
	'title' => 'Form Builder Clone: Options Repeater',
	'fields' => array(
		array(
			'key' => 'field_5dff88e115079',
			'label' => 'Options',
			'name' => 'options',
			'type' => 'repeater',
			'instructions' => '',
			'required' => 0,
			'conditional_logic' => array(
				array(
					array(
						'field' => 'field_5dff88e11505e',
						'operator' => '==',
						'value' => 'user',
					),
				),
			),
			'wrapper' => array(
				'width' => '',
				'class' => 'full-width-settings',
				'id' => '',
			),
			'collapsed' => 'field_5bfc1a79b48f9',
			'min' => 0,
			'max' => 0,
			'layout' => 'table',
			'button_label' => 'Add Option',
			'sub_fields' => array(
				array(
					'key' => 'field_5dff88e119827',
					'label' => 'Option',
					'name' => 'option',
					'type' => 'text',
					'instructions' => '',
					'required' => 1,
					'conditional_logic' => 0,
					'wrapper' => array(
						'width' => '',
						'class' => '',
						'id' => '',
					),
					'default_value' => '',
					'placeholder' => '',
					'prepend' => '',
					'append' => '',
					'maxlength' => '',
				),
				array(
					'key' => 'field_5dffbb7de6b6e',
					'label' => 'Option Value',
					'name' => 'option_value',
					'type' => 'text',
					'instructions' => '',
					'required' => 0,
					'conditional_logic' => 0,
					'wrapper' => array(
						'width' => '',
						'class' => '',
						'id' => '',
					),
					'default_value' => '',
					'placeholder' => '',
					'prepend' => '',
					'append' => '',
					'maxlength' => '',
				),
			),
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