<?php
if( function_exists('acf_add_local_field_group') ):

acf_add_local_field_group(array(
	'key' => 'group_5c6b0b129bb02',
	'title' => 'Form Builder Clone: Options Layout',
	'fields' => array(
		array(
			'key' => 'field_5c6b0b3c49483',
			'label' => 'Options Layout',
			'name' => 'options_layout',
			'type' => 'select',
			'instructions' => '',
			'required' => 0,
			'conditional_logic' => 0,
			'wrapper' => array(
				'width' => '50',
				'class' => 'full-width-settings',
				'id' => '',
			),
			'choices' => array(
				'fb-options-inline' => 'Inline',
				'fb-options-block' => 'Block',
				'fb-options-grid-2' => 'Grid (2 columns)',
				'fb-options-grid-3' => 'Grid (3 columns)',
				'fb-options-grid-4' => 'Grid (4 columns)',
			),
			'default_value' => array(
				0 => 'fb-options-inline',
			),
			'allow_null' => 0,
			'multiple' => 0,
			'ui' => 0,
			'return_format' => 'value',
			'ajax' => 0,
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
	'active' => 0,
	'description' => '',
));

endif;