<?php
function get_location_input_sections() {
	$wp_swift_form_builder_default_id = get_option( 'wp_swift_form_builder_default_id' );

	$location = array (
		array (
			'param' => 'post_type',
			'operator' => '==',
			'value' => 'wp_swift_form',
		),
		// array (
		// 	'param' => 'post',
		// 	'operator' => '!=',
		// 	'value' => '40',
		// ),
		// array (
		// 	'param' => 'post',
		// 	'operator' => '!=',
		// 	'value' => '1',
		// ),
	);
	if ($wp_swift_form_builder_default_id) {
		$location[] = array (
			'param' => 'post',
			'operator' => '!=',
			'value' => $wp_swift_form_builder_default_id,
		);
	}
	// );
	return array($location);
}
if( function_exists('acf_add_local_field_group') ):

acf_add_local_field_group(array (
	'key' => 'group_59cf729a5237d',
	'title' => 'Form Builder: Input Sections',
	'fields' => array (
		array (
			'key' => 'field_59cf72b083b6d',
			'label' => 'Sections',
			'name' => 'sections',
			'type' => 'repeater',
			'value' => NULL,
			'instructions' => '',
			'required' => 0,
			'conditional_logic' => 0,
			'wrapper' => array (
				'width' => '',
				'class' => '',
				'id' => '',
			),
			'collapsed' => 'field_59d2045c948d4',
			'min' => 1,
			'max' => 0,
			'layout' => 'row',
			'button_label' => 'Add Section',
			'sub_fields' => array (
				array (
					'key' => 'field_59cfa60dc0488',
					'label' => 'Inputs',
					'name' => '',
					'type' => 'tab',
					'instructions' => '',
					'required' => 0,
					'conditional_logic' => 0,
					'wrapper' => array (
						'width' => '',
						'class' => '',
						'id' => '',
					),
					'placement' => 'top',
					'endpoint' => 0,
				),
				array (
					'key' => 'field_59d2045c948d4',
					'label' => 'Form Inputs',
					'name' => 'form_inputs',
					'type' => 'clone',
					'value' => NULL,
					'instructions' => '',
					'required' => 0,
					'conditional_logic' => 0,
					'wrapper' => array (
						'width' => '',
						'class' => '',
						'id' => '',
					),
					'clone' => array (
						0 => 'group_57b6fd868aeca',
					),
					'display' => 'seamless',
					'layout' => 'block',
					'prefix_label' => 0,
					'prefix_name' => 0,
				),
				array (
					'key' => 'field_59cfa6a6c0489',
					'label' => 'Content',
					'name' => '',
					'type' => 'tab',
					'instructions' => '',
					'required' => 0,
					'conditional_logic' => 0,
					'wrapper' => array (
						'width' => '',
						'class' => '',
						'id' => '',
					),
					'placement' => 'top',
					'endpoint' => 0,
				),
				array (
					'key' => 'field_59cfa764c048a',
					'label' => 'Section Header',
					'name' => 'section_header',
					'type' => 'text',
					'value' => NULL,
					'instructions' => '',
					'required' => 0,
					'conditional_logic' => 0,
					'wrapper' => array (
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
				array (
					'key' => 'field_59cfa773c048b',
					'label' => 'Section Content',
					'name' => 'section_content',
					'type' => 'wysiwyg',
					'value' => NULL,
					'instructions' => '',
					'required' => 0,
					'conditional_logic' => 0,
					'wrapper' => array (
						'width' => '',
						'class' => '',
						'id' => '',
					),
					'default_value' => '',
					'tabs' => 'all',
					'toolbar' => 'full',
					'media_upload' => 0,
					'delay' => 0,
				),
			),
		),
	),
	'location' => get_location_input_sections(),
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