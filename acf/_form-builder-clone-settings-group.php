<?php
if( function_exists('acf_add_local_field_group') ):

acf_add_local_field_group(array(
	'key' => 'group_5bfbf20f00155',
	'title' => 'Form Builder Clone: Settings Group',
	'fields' => array(
		array(
			'key' => 'field_5bfbf20f0645c',
			'label' => 'Required',
			'name' => 'required',
			'type' => 'true_false',
			'instructions' => 'Force input',
			'required' => 0,
			'conditional_logic' => 0,
			'wrapper' => array(
				'width' => '25',
				'class' => 'group-start',
				'id' => '',
			),
			'message' => '',
			'default_value' => 1,
			'ui' => 1,
			'ui_on_text' => '',
			'ui_off_text' => '',
		),
		array(
			'key' => 'field_5dc9622078ca3',
			'label' => 'Autofill',
			'name' => 'autofill',
			'type' => 'true_false',
			'instructions' => 'Save locally',
			'required' => 0,
			'conditional_logic' => 0,
			'wrapper' => array(
				'width' => '25',
				'class' => 'group-middle',
				'id' => '',
			),
			'message' => '',
			'default_value' => 0,
			'ui' => 1,
			'ui_on_text' => '',
			'ui_off_text' => '',
		),
		array(
			'key' => 'field_5bfbf249611f7',
			'label' => 'Grouping',
			'name' => 'grouping',
			'type' => 'select',
			'instructions' => 'Manage multiple inputs in a row',
			'required' => 0,
			'conditional_logic' => 0,
			'wrapper' => array(
				'width' => '50',
				'class' => 'group-end',
				'id' => '',
			),
			'choices' => array(
				'none' => 'None',
				'start' => 'Start Group',
				'end' => 'End Group',
			),
			'default_value' => array(
				0 => 'none',
			),
			'allow_null' => 0,
			'multiple' => 0,
			'ui' => 0,
			'ajax' => 0,
			'return_format' => 'value',
			'placeholder' => '',
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
	'active' => false,
	'description' => '',
));

endif;