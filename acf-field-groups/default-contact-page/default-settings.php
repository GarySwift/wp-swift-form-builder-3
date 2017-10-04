<?php
function get_location_default_contact_form() {
	$wp_swift_form_builder_default_id = get_option( 'wp_swift_form_builder_default_id' );
	
	$location = array (
		array (
			'param' => 'post_type',
			'operator' => '==',
			'value' => 'wp_swift_form',
		),
	);

	if ($wp_swift_form_builder_default_id) {
		$location[] = array (
			'param' => 'post',
			'operator' => '==',
			'value' => $wp_swift_form_builder_default_id,
		);
		return array($location);
	}
	else {
		return array();
	}
}
if( function_exists('acf_add_local_field_group') ):

acf_add_local_field_group(array (
	'key' => 'group_595771b58d12c',
	'title' => 'Form Builder: Default Contact Form',
	'fields' => array (
		array (
			'key' => 'field_5957740876bba',
			'label' => 'Form Notes',
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
			'message' => 'This page has been assigned as the default contact page. The default form includes first and last name inputs, along with an email input and a question textarea.',
			'new_lines' => '',
			'esc_html' => 0,
		),
		array (
			'key' => 'field_59577633fb276',
			'label' => 'Form Adjustments',
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
			'message' => 'The following adjustments are available on the default contact page.',
			'new_lines' => '',
			'esc_html' => 0,
		),
		array (
			'key' => 'field_595771c51e07a',
			'label' => 'Combine Name Fields',
			'name' => 'combine_name_fields',
			'type' => 'true_false',
			'instructions' => '',
			'required' => 0,
			'conditional_logic' => 0,
			'wrapper' => array (
				'width' => '',
				'class' => '',
				'id' => '',
			),
			'message' => '',
			'default_value' => 0,
			'ui' => 1,
			'ui_on_text' => '',
			'ui_off_text' => '',
		),
		array (
			'key' => 'field_595773241e07b',
			'label' => 'Show Telephone Input',
			'name' => 'show_telephone_input',
			'type' => 'true_false',
			'instructions' => '',
			'required' => 0,
			'conditional_logic' => 0,
			'wrapper' => array (
				'width' => '',
				'class' => '',
				'id' => '',
			),
			'message' => '',
			'default_value' => 0,
			'ui' => 1,
			'ui_on_text' => '',
			'ui_off_text' => '',
		),
		array (
			'key' => 'field_5957739c1e07c',
			'label' => 'Show Company Input',
			'name' => 'show_company_input',
			'type' => 'true_false',
			'instructions' => '',
			'required' => 0,
			'conditional_logic' => 0,
			'wrapper' => array (
				'width' => '',
				'class' => '',
				'id' => '',
			),
			'message' => '',
			'default_value' => 0,
			'ui' => 1,
			'ui_on_text' => '',
			'ui_off_text' => '',
		),
	),
	'location' => get_location_default_contact_form(),
	'menu_order' => 0,
	'position' => 'normal',
	'style' => 'default',
	'label_placement' => 'top',
	'instruction_placement' => 'label',
	'hide_on_screen' => '',
	'active' => 1,
	'description' => '',
));

endif;