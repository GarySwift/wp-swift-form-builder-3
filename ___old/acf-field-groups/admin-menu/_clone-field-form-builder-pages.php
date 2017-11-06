<?php
if( function_exists('acf_add_local_field_group') ):

acf_add_local_field_group(array (
	'key' => 'group_59563408b12e0',
	'title' => 'Form Builder: Pages',
	'fields' => array (
		array (
			'key' => 'field_5956348d782bd',
			'label' => 'Instructions',
			'name' => '',
			'type' => 'message',
			'instructions' => '',
			'required' => 0,
			'conditional_logic' => 0,
			'wrapper' => array (
				'width' => '',
				'class' => '',
				'id' => '',
			),
			'message' => 'Please select a page if you wish to edit the default form handling',
			'new_lines' => 'wpautop',
			'esc_html' => 0,
		),
		array (
			'key' => 'field_59563421782bb',
			'label' => 'Contact Form Page',
			'name' => 'contact_form_page',
			'type' => 'post_object',
			'instructions' => '',
			'required' => 0,
			'conditional_logic' => 0,
			'wrapper' => array (
				'width' => '',
				'class' => '',
				'id' => '',
			),
			'post_type' => array (
				0 => 'page',
			),
			'taxonomy' => array (
			),
			'allow_null' => 1,
			'multiple' => 0,
			'return_format' => 'id',
			'ui' => 1,
		),
		array (
			'key' => 'field_595651c892275',
			'label' => 'Choose Form Page',
			'name' => 'choose_form_page',
			'type' => 'repeater',
			'instructions' => '',
			'required' => 0,
			'conditional_logic' => 0,
			'wrapper' => array (
				'width' => '',
				'class' => '',
				'id' => '',
			),
			'collapsed' => 'field_595651ec92276',
			'min' => 0,
			'max' => 0,
			'layout' => 'row',
			'button_label' => 'Add Page',
			'sub_fields' => array (
				array (
					'key' => 'field_595651ec92276',
					'label' => 'Page',
					'name' => 'page',
					'type' => 'post_object',
					'instructions' => '',
					'required' => 0,
					'conditional_logic' => 0,
					'wrapper' => array (
						'width' => '',
						'class' => '',
						'id' => '',
					),
					'post_type' => array (
						0 => 'page',
					),
					'taxonomy' => array (
					),
					'allow_null' => 0,
					'multiple' => 0,
					'return_format' => 'id',
					'ui' => 1,
				),
			),
		),
	),
	'location' => array (
		array (
			array (
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