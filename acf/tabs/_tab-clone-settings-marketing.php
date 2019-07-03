<?php
if( function_exists('acf_add_local_field_group') ):

acf_add_local_field_group(array(
	'key' => 'group_5cff6a76b2519',
	'title' => 'Tab Clone Settings: Marketing',
	'fields' => array(
		array(
			'key' => 'field_5d1b0f1560a64',
			'label' => 'Marketing',
			'name' => 'marketing',
			'type' => 'true_false',
			'instructions' => '',
			'required' => 0,
			'conditional_logic' => 0,
			'wrapper' => array(
				'width' => '30',
				'class' => '',
				'id' => '',
			),
			'message' => '',
			'default_value' => 0,
			'ui' => 1,
			'ui_on_text' => '',
			'ui_off_text' => '',
		),
		array(
			'key' => 'field_5d1b0f3f60a65',
			'label' => 'Consent',
			'name' => 'consent',
			'type' => 'select',
			'instructions' => '',
			'required' => 0,
			'conditional_logic' => array(
				array(
					array(
						'field' => 'field_5d1b0f1560a64',
						'operator' => '==',
						'value' => '1',
					),
				),
			),
			'wrapper' => array(
				'width' => '70',
				'class' => '',
				'id' => '',
			),
			'choices' => array(
				'standard' => 'Clicking an opt-in checkbox for each contact method.',
				'tick_license' => 'Clicking a single opt-in checkbox for a license.',
				'license' => 'No opt-in. The user acknowledges they agree to licence terms.',
			),
			'default_value' => array(
				0 => 'standard',
			),
			'allow_null' => 0,
			'multiple' => 0,
			'ui' => 0,
			'return_format' => 'value',
			'ajax' => 0,
			'placeholder' => '',
		),
		array(
			'key' => 'field_5d1b16bbc1e89',
			'label' => '',
			'name' => '',
			'type' => 'message',
			'instructions' => '',
			'required' => 0,
			'conditional_logic' => array(
				array(
					array(
						'field' => 'field_5d1b0f1560a64',
						'operator' => '==',
						'value' => '1',
					),
				),
			),
			'wrapper' => array(
				'width' => '',
				'class' => '',
				'id' => '',
			),
			'message' => 'Be careful with consent. Make sure the license agreement covers all legal aspects of GDPR.',
			'new_lines' => 'wpautop',
			'esc_html' => 0,
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