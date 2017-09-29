<?php
$contact_form_page = false;
$booking_form_page = false;
$location = array();



$form_pages = array();
if( class_exists('acf') ):
	if( have_rows('additional_forms', 'option') ):
	    while ( have_rows('additional_forms', 'option') ) : the_row();
	        $form_pages[] = get_sub_field('page');
	    endwhile;
	endif;
endif;

// echo "<pre>"; var_dump("Lorem ipsum dolor sit amet, consectetur adipisicing elit");var_dump($form_pages); echo "</pre>";

if( function_exists('acf_add_local_field_group') ):

	// if( get_field('contact_form_page', 'option') ) {
	//     $contact_form_page = get_field('contact_form_page', 'option');
	//     $location[] = form_builder_location_array( $contact_form_page );
	// }
	// if( get_field('booking_form_page', 'option') ) {
	//     $booking_form_page = get_field('booking_form_page', 'option');
	//     $location[] = form_builder_location_array( $booking_form_page );

	// }
	foreach ($form_pages as $page_id) {
		$location[] = form_builder_location_array( $page_id );
	}

endif;

// echo "<pre>"; var_dump("Lorem ipsum dolor sit amet, consectetur adipisicing elit");var_dump($location); echo "</pre>";

if( function_exists('acf_add_local_field_group') ):

	if ( count($form_pages) || $contact_form_page || $booking_form_page ):
 
	acf_add_local_field_group(array (
		'key' => 'group_57b6fd868aeca',
		'title' => 'Form Builder: Inputs',
		'fields' => array (
			array (
				'key' => 'field_57b6fd8692eb1',
				'label' => 'Form Inputs',
				'name' => 'form_inputs',
				'type' => 'repeater',
				'instructions' => '',
				'required' => 0,
				'conditional_logic' => 0,
				'wrapper' => array (
					'width' => '',
					'class' => '',
					'id' => '',
				),
				'collapsed' => 'field_57b6fd86d2e0b',
				'min' => 0,
				'max' => 0,
				'layout' => 'block',
				'button_label' => 'Add Input',
				'sub_fields' => array (
					array (
						'key' => 'field_57b6fd86d2e0b',
						'label' => 'Name',
						'name' => 'name',
						'type' => 'text',
						'instructions' => '',
						'required' => 1,
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
						'readonly' => 0,
						'disabled' => 0,
					),
					array (
						'key' => 'field_57b6fd86d2e29',
						'label' => 'Type',
						'name' => 'type',
						'type' => 'select',
						'instructions' => '',
						'required' => 1,
						'conditional_logic' => 0,
						'wrapper' => array (
							'width' => '',
							'class' => '',
							'id' => '',
						),
						'choices' => array (
							'text' => 'Text (Default)',
							'email' => 'Email',
							'number' => 'Number',
							'url' => 'URL',
							'input_combo' => 'Input Combo',
							'textarea' => 'Text Area (Multi-line Text)',
							'select' => 'Select',
							'multi_select' => 'Multiple Select',
							'checkbox' => 'Checkboxes',
							'radio' => 'Radio Buttons',
							'file' => 'File Upload',
							'date' => 'Date',
							'date_time' => 'Date & Time Combo',
							'date_range' => 'Date Range',
							'section' => 'Section Open',
							'section_close' => 'Section Close',
						),
						'default_value' => array (
							0 => 'text',
						),
						'allow_null' => 0,
						'multiple' => 0,
						'ui' => 0,
						'ajax' => 0,
						'return_format' => 'value',
						'placeholder' => '',
					),
					array (
						'key' => 'field_57da6d23398a8',
						'label' => 'Header',
						'name' => 'section_header',
						'type' => 'text',
						'instructions' => '',
						'required' => '',
						'conditional_logic' => array (
							array (
								array (
									'field' => 'field_57b6fd86d2e29',
									'operator' => '==',
									'value' => 'section',
								),
							),
						),
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
						'readonly' => 0,
						'disabled' => 0,
					),
					array (
						'key' => 'field_57b6fd86d2e49',
						'label' => 'Required',
						'name' => 'required',
						'type' => 'true_false',
						'instructions' => '',
						'required' => 0,
						'conditional_logic' => array (
							array (
								array (
									'field' => 'field_57b6fd86d2e29',
									'operator' => '!=',
									'value' => 'section',
								),
								array (
									'field' => 'field_57b6fd86d2e29',
									'operator' => '!=',
									'value' => 'section_close',
								),
							),
						),
						'wrapper' => array (
							'width' => '',
							'class' => '',
							'id' => '',
						),
						'message' => 'Force input from user',
						'default_value' => 1,
						'ui' => 0,
						'ui_on_text' => '',
						'ui_off_text' => '',
					),
					array (
						'key' => 'field_57b6fd86d2e5f',
						'label' => 'Placeholder',
						'name' => 'placeholder',
						'type' => 'text',
						'instructions' => 'Specifies a short hint for input',
						'required' => 0,
						'conditional_logic' => array (
							array (
								array (
									'field' => 'field_57b6fd86d2e29',
									'operator' => '!=',
									'value' => 'file',
								),
								array (
									'field' => 'field_57b6fd86d2e29',
									'operator' => '!=',
									'value' => 'select',
								),
								array (
									'field' => 'field_57b6fd86d2e29',
									'operator' => '!=',
									'value' => 'multi_select',
								),
								array (
									'field' => 'field_57b6fd86d2e29',
									'operator' => '!=',
									'value' => 'section',
								),
								array (
									'field' => 'field_57b6fd86d2e29',
									'operator' => '!=',
									'value' => 'section_close',
								),
								array (
									'field' => 'field_57b6fd86d2e29',
									'operator' => '!=',
									'value' => 'checkbox',
								),
								array (
									'field' => 'field_57b6fd86d2e29',
									'operator' => '!=',
									'value' => 'radio',
								),
							),
						),
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
						'key' => 'field_57b6fd86d2e72',
						'label' => 'Label',
						'name' => 'label',
						'type' => 'text',
						'instructions' => 'If different from name',
						'required' => 0,
						'conditional_logic' => array (
							array (
								array (
									'field' => 'field_57b6fd86d2e29',
									'operator' => '!=',
									'value' => 'section',
								),
								array (
									'field' => 'field_57b6fd86d2e29',
									'operator' => '!=',
									'value' => 'section_close',
								),
							),
						),
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
						'readonly' => 0,
						'disabled' => 0,
					),
					array (
						'key' => 'field_57b6fd86d2e84',
						'label' => 'Select Options',
						'name' => 'select_options',
						'type' => 'repeater',
						'instructions' => '',
						'required' => 0,
						'conditional_logic' => array (
							array (
								array (
									'field' => 'field_57b6fd86d2e29',
									'operator' => '==',
									'value' => 'select',
								),
								array (
									'field' => 'field_57b6fd86d2e9f',
									'operator' => '==',
									'value' => 'user',
								),
							),
							array (
								array (
									'field' => 'field_57b6fd86d2e29',
									'operator' => '==',
									'value' => 'multi_select',
								),
								array (
									'field' => 'field_57b6fd86d2e9f',
									'operator' => '==',
									'value' => 'user',
								),
							),
							array (
								array (
									'field' => 'field_57b6fd86d2e29',
									'operator' => '==',
									'value' => 'checkbox',
								),
								array (
									'field' => 'field_57b6fd86d2e9f',
									'operator' => '==',
									'value' => 'user',
								),
							),
							array (
								array (
									'field' => 'field_57b6fd86d2e29',
									'operator' => '==',
									'value' => 'radio',
								),
								array (
									'field' => 'field_57b6fd86d2e9f',
									'operator' => '==',
									'value' => 'user',
								),
							),
						),
						'wrapper' => array (
							'width' => '',
							'class' => '',
							'id' => '',
						),
						'collapsed' => '',
						'min' => 0,
						'max' => 0,
						'layout' => 'table',
						'button_label' => 'Add Option',
						'sub_fields' => array (
							array (
								'key' => 'field_57b6fd86e9a8c',
								'label' => 'Option',
								'name' => 'option',
								'type' => 'text',
								'instructions' => '',
								'required' => 1,
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
								'readonly' => 0,
								'disabled' => 0,
							),
							array (
								'key' => 'field_57b6fd86e9aa8',
								'label' => 'Option Value',
								'name' => 'option_value',
								'type' => 'text',
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
								'readonly' => 0,
								'disabled' => 0,
							),
						),
					),
					array (
						'key' => 'field_57b6fd86d2e9f',
						'label' => 'Select Type',
						'name' => 'select_type',
						'type' => 'radio',
						'instructions' => '',
						'required' => 0,
						'conditional_logic' => array (
							array (
								array (
									'field' => 'field_57b6fd86d2e29',
									'operator' => '==',
									'value' => 'select',
								),
							),
							array (
								array (
									'field' => 'field_57b6fd86d2e29',
									'operator' => '==',
									'value' => 'multi_select',
								),
							),
							array (
								array (
									'field' => 'field_57b6fd86d2e29',
									'operator' => '==',
									'value' => 'checkbox',
								),
							),
						),
						'wrapper' => array (
							'width' => '',
							'class' => '',
							'id' => '',
						),
						'choices' => array (
							'user' => 'Manually Input',
							'select' => 'Use Predefined Options',
						),
						'allow_null' => 0,
						'other_choice' => 0,
						'save_other_choice' => 0,
						'default_value' => 'user',
						'layout' => 'vertical',
						'return_format' => 'value',
					),
					array (
						'key' => 'field_57b6fd86d2eb9',
						'label' => 'Predefined Options',
						'name' => 'predefined_options',
						'type' => 'select',
						'instructions' => '',
						'required' => 0,
						'conditional_logic' => array (
							array (
								array (
									'field' => 'field_57b6fd86d2e29',
									'operator' => '==',
									'value' => 'select',
								),
								array (
									'field' => 'field_57b6fd86d2e9f',
									'operator' => '==',
									'value' => 'select',
								),
							),
							array (
								array (
									'field' => 'field_57b6fd86d2e29',
									'operator' => '==',
									'value' => 'multi_select',
								),
								array (
									'field' => 'field_57b6fd86d2e9f',
									'operator' => '==',
									'value' => 'select',
								),
							),
						),
						'wrapper' => array (
							'width' => '',
							'class' => '',
							'id' => '',
						),
						'choices' => array (
							'countries' => 'Countries',
						),
						'default_value' => array (
							'countries' => 'countries',
						),
						'allow_null' => 0,
						'multiple' => 0,
						'ui' => 0,
						'ajax' => 0,
						'placeholder' => '',
						'disabled' => 0,
						'readonly' => 0,
						'return_format' => 'value',
					),
					array (
						'key' => 'field_57b6fd86d2ecd',
						'label' => 'Help Message',
						'name' => 'help',
						'type' => 'text',
						'instructions' => 'If different from default',
						'required' => 0,
						'conditional_logic' => array (
							array (
								array (
									'field' => 'field_57b6fd86d2e29',
									'operator' => '!=',
									'value' => 'section',
								),
								array (
									'field' => 'field_57b6fd86d2e29',
									'operator' => '!=',
									'value' => 'section_close',
								),
							),
						),
						'wrapper' => array (
							'width' => '',
							'class' => '',
							'id' => '',
						),
						'default_value' => '',
						'placeholder' => 'Eg. Email is not required but must be valid',
						'prepend' => '',
						'append' => '',
						'maxlength' => '',
						'readonly' => 0,
						'disabled' => 0,
					),
					array (
						'key' => 'field_5953bee870551',
						'label' => 'Instructions',
						'name' => 'instructions',
						'type' => 'text',
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
						'key' => 'field_57b6ff3e6dde1',
						'label' => 'Section Content',
						'name' => 'section_content',
						'type' => 'wysiwyg',
						'instructions' => '',
						'required' => '',
						'conditional_logic' => array (
							array (
								array (
									'field' => 'field_57b6fd86d2e29',
									'operator' => '==',
									'value' => 'section',
								),
							),
						),
						'wrapper' => array (
							'width' => '',
							'class' => '',
							'id' => '',
						),
						'default_value' => '',
						'tabs' => 'visual',
						'toolbar' => 'basic',
						'media_upload' => 0,
						'delay' => 0,
					),
					array (
						'key' => 'field_5952522b90367',
						'label' => 'Input One',
						'name' => 'input_one',
						'type' => 'text',
						'instructions' => '',
						'required' => 0,
						'conditional_logic' => array (
							array (
								array (
									'field' => 'field_57b6fd86d2e29',
									'operator' => '==',
									'value' => 'input_combo',
								),
							),
						),
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
						'key' => 'field_595252d2505b2',
						'label' => 'Input Two',
						'name' => 'input_two',
						'type' => 'text',
						'instructions' => '',
						'required' => 0,
						'conditional_logic' => array (
							array (
								array (
									'field' => 'field_57b6fd86d2e29',
									'operator' => '==',
									'value' => 'input_combo',
								),
							),
						),
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
						'key' => 'field_595253c6f57b9',
						'label' => 'Combo Input Type',
						'name' => 'combo_input_type',
						'type' => 'select',
						'instructions' => '',
						'required' => 0,
						'conditional_logic' => array (
							array (
								array (
									'field' => 'field_57b6fd86d2e29',
									'operator' => '==',
									'value' => 'input_combo',
								),
							),
						),
						'wrapper' => array (
							'width' => '',
							'class' => '',
							'id' => '',
						),
						'choices' => array (
							'text' => 'Text (Default)',
							'email' => 'Email',
							'number' => 'Number',
							'url' => 'URL',
						),
						'default_value' => array (
							0 => 'text',
						),
						'allow_null' => 0,
						'multiple' => 0,
						'ui' => 0,
						'ajax' => 0,
						'return_format' => 'value',
						'placeholder' => '',
					),
				),
			),
		),
		'location' => $location,
		'menu_order' => 0,
		'position' => 'normal',
		'style' => 'default',
		'label_placement' => 'top',
		'instruction_placement' => 'label',
		'hide_on_screen' => '',
		'active' => 1,
		'description' => '',
	));

	endif;//@end if ($contact_form_page || $booking_form_page)
endif;//@end if( function_exists('acf_add_local_field_group') )