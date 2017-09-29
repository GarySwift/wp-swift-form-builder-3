<?php
if( function_exists('acf_add_local_field_group') ):

acf_add_local_field_group(array (
	'key' => 'group_599c872f1839d',
	'title' => 'Form Builder Clone Field: Help Tab',
	'fields' => array (
		array (
			'key' => 'field_599c8821753eb',
			'label' => 'Shortcodes',
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
			'message' => '[contact_form]',
			'new_lines' => 'br',
			'esc_html' => 0,
		),
		array (
			'key' => 'field_599c8841753ec',
			'label' => 'PHP Functions',
			'name' => '',
			'type' => 'message',
			'instructions' => '&lt;?php
echo wp_swift_form_builder();',
			'required' => 0,
			'conditional_logic' => 0,
			'wrapper' => array (
				'width' => '',
				'class' => '',
				'id' => '',
			),
			'message' => '&lt;?php
echo wp_swift_form_builder();',
			'new_lines' => 'br',
			'esc_html' => 0,
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