<?php
if( function_exists('acf_add_local_field_group') ):

acf_add_local_field_group(array(
	'key' => 'group_59cf729a5237d',
	'title' => 'Form Builder: Input Sections',
	'fields' => array(
		array(
			'key' => 'field_5afd52dc39f96',
			'label' => '',
			'name' => '',
			'type' => 'message',
			'instructions' => '',
			'required' => 0,
			'conditional_logic' => 0,
			'wrapper' => array(
				'width' => '',
				'class' => '',
				'id' => '',
			),
			'message' => 'Form builder is still in beta so please refer to the developer before changing advanced features of this form.',
			'new_lines' => 'wpautop',
			'esc_html' => 0,
		),
		array(
			'key' => 'field_59ec8f68aa374',
			'label' => 'Inputs',
			'name' => '',
			'type' => 'tab',
			'instructions' => '',
			'required' => 0,
			'conditional_logic' => 0,
			'wrapper' => array(
				'width' => '',
				'class' => '',
				'id' => '',
			),
			'placement' => 'top',
			'endpoint' => 0,
		),
		array(
			'key' => 'field_59cf72b083b6d',
			'label' => 'Sections',
			'name' => 'sections',
			'type' => 'repeater',
			'instructions' => '',
			'required' => 0,
			'conditional_logic' => 0,
			'wrapper' => array(
				'width' => '',
				'class' => '',
				'id' => 'form-builder-input-sections',
			),
			'collapsed' => 'field_59d2045c948d4',
			'min' => 1,
			'max' => 0,
			'layout' => 'row',
			'button_label' => 'Add Section',
			'sub_fields' => array(
				array(
					'key' => 'field_59cfa60dc0488',
					'label' => 'Inputs',
					'name' => '',
					'type' => 'tab',
					'instructions' => '',
					'required' => 0,
					'conditional_logic' => 0,
					'wrapper' => array(
						'width' => '',
						'class' => '',
						'id' => '',
					),
					'placement' => 'top',
					'endpoint' => 0,
				),
				array(
					'key' => 'field_59d2045c948d4',
					'label' => 'Form Inputs',
					'name' => 'form_inputs',
					'type' => 'clone',
					'instructions' => '',
					'required' => 0,
					'conditional_logic' => 0,
					'wrapper' => array(
						'width' => '',
						'class' => '',
						'id' => '',
					),
					'clone' => array(
						0 => 'group_57b6fd868aeca',
					),
					'display' => 'seamless',
					'layout' => 'block',
					'prefix_label' => 0,
					'prefix_name' => 0,
				),
				array(
					'key' => 'field_59cfa6a6c0489',
					'label' => 'Content',
					'name' => '',
					'type' => 'tab',
					'instructions' => '',
					'required' => 0,
					'conditional_logic' => 0,
					'wrapper' => array(
						'width' => '',
						'class' => '',
						'id' => '',
					),
					'placement' => 'top',
					'endpoint' => 0,
				),
				array(
					'key' => 'field_59cfa764c048a',
					'label' => 'Section Header',
					'name' => 'section_header',
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
				array(
					'key' => 'field_59cfa773c048b',
					'label' => 'Section Content',
					'name' => 'section_content',
					'type' => 'wysiwyg',
					'instructions' => '',
					'required' => 0,
					'conditional_logic' => 0,
					'wrapper' => array(
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
		array(
			'key' => 'field_59ec8f96aa375',
			'label' => 'General Settings',
			'name' => '',
			'type' => 'tab',
			'instructions' => '',
			'required' => 0,
			'conditional_logic' => 0,
			'wrapper' => array(
				'width' => '',
				'class' => '',
				'id' => '',
			),
			'placement' => 'top',
			'endpoint' => 0,
		),
		array(
			'key' => 'field_59ec8fa5aa376',
			'label' => 'Hide Labels',
			'name' => 'hide_labels',
			'type' => 'true_false',
			'instructions' => '',
			'required' => 0,
			'conditional_logic' => 0,
			'wrapper' => array(
				'width' => '',
				'class' => '',
				'id' => '',
			),
			'message' => 'Placeholders will be automatically added if labels are hidden.',
			'default_value' => 0,
			'ui' => 1,
			'ui_on_text' => '',
			'ui_off_text' => '',
		),
		array(
			'key' => 'field_59ec9007aa377',
			'label' => 'Wrap Form',
			'name' => 'wrap_form',
			'type' => 'true_false',
			'instructions' => '',
			'required' => 0,
			'conditional_logic' => 0,
			'wrapper' => array(
				'width' => '',
				'class' => '',
				'id' => '',
			),
			'message' => 'Adds a border around the form and removes border from error messages.',
			'default_value' => 0,
			'ui' => 1,
			'ui_on_text' => '',
			'ui_off_text' => '',
		),
		array(
			'key' => 'field_5a02c5255508c',
			'label' => 'Submit Button Text',
			'name' => 'submit_button_text',
			'type' => 'text',
			'instructions' => 'Leave blank to use default.',
			'required' => 0,
			'conditional_logic' => 0,
			'wrapper' => array(
				'width' => '',
				'class' => '',
				'id' => '',
			),
			'default_value' => '',
			'placeholder' => 'Submit Form',
			'prepend' => '',
			'append' => '',
			'maxlength' => 30,
		),
		array(
			'key' => 'field_5a0c9ab591cc9',
			'label' => 'User Confirmation Email',
			'name' => 'user_confirmation_email',
			'type' => 'select',
			'instructions' => 'How confirmations email are handled.',
			'required' => 0,
			'conditional_logic' => 0,
			'wrapper' => array(
				'width' => '',
				'class' => '',
				'id' => '',
			),
			'choices' => array(
				'ask' => 'Always Ask User',
				'send' => 'Always Send',
				'never' => 'Never Send',
			),
			'default_value' => array(
				0 => 'ask',
			),
			'allow_null' => 1,
			'multiple' => 0,
			'ui' => 0,
			'ajax' => 0,
			'return_format' => 'value',
			'placeholder' => '',
		),
		array(
			'key' => 'field_5a0ca25ef7116',
			'label' => 'Show Page in Email',
			'name' => 'show_page_in_email',
			'type' => 'true_false',
			'instructions' => '',
			'required' => 0,
			'conditional_logic' => array(
				array(
					array(
						'field' => 'field_5a0c9ab591cc9',
						'operator' => '==',
						'value' => 'ask',
					),
				),
				array(
					array(
						'field' => 'field_5a0c9ab591cc9',
						'operator' => '==',
						'value' => 'send',
					),
				),
			),
			'wrapper' => array(
				'width' => '',
				'class' => '',
				'id' => '',
			),
			'message' => 'Show page name and link in email. (Useful if form appears on multiple pages.)',
			'default_value' => 0,
			'ui' => 1,
			'ui_on_text' => '',
			'ui_off_text' => '',
		),
		array(
			'key' => 'field_5a0c9ce346991',
			'label' => 'Show Edit Link',
			'name' => 'show_edit_link',
			'type' => 'true_false',
			'instructions' => '',
			'required' => 0,
			'conditional_logic' => 0,
			'wrapper' => array(
				'width' => '',
				'class' => '',
				'id' => '',
			),
			'message' => 'Shows an <b>Edit Form</b> link to users with form editing capabilities.',
			'default_value' => 0,
			'ui' => 1,
			'ui_on_text' => '',
			'ui_off_text' => '',
		),
		array(
			'key' => 'field_5aa7d7df2f286',
			'label' => 'Save Submission',
			'name' => 'save_submission',
			'type' => 'true_false',
			'instructions' => '',
			'required' => 0,
			'conditional_logic' => 0,
			'wrapper' => array(
				'width' => '',
				'class' => '',
				'id' => '',
			),
			'message' => 'Save a permanent copy of each form submission in the WordPress backend.',
			'default_value' => 0,
			'ui' => 1,
			'ui_on_text' => '',
			'ui_off_text' => '',
		),
		array(
			'key' => 'field_5af44f85be7cb',
			'label' => 'Spam Prevention',
			'name' => '',
			'type' => 'tab',
			'instructions' => '',
			'required' => 0,
			'conditional_logic' => 0,
			'wrapper' => array(
				'width' => '',
				'class' => '',
				'id' => '',
			),
			'placement' => 'top',
			'endpoint' => 0,
		),
		array(
			'key' => 'field_5ad4674d8339f',
			'label' => 'Spam Prevention',
			'name' => 'spam_prevention_type',
			'type' => 'radio',
			'instructions' => '',
			'required' => 0,
			'conditional_logic' => 0,
			'wrapper' => array(
				'width' => '',
				'class' => '',
				'id' => '',
			),
			'choices' => array(
				'none' => 'None',
				'google' => 'Google reCAPTCHA',
			),
			'allow_null' => 0,
			'other_choice' => 0,
			'save_other_choice' => 0,
			'default_value' => 'none',
			'layout' => 'horizontal',
			'return_format' => 'value',
		),
		array(
			'key' => 'field_5af450cd6baed',
			'label' => 'reCAPTCHA Settings',
			'name' => 'recaptcha_settings',
			'type' => 'group',
			'instructions' => '',
			'required' => 0,
			'conditional_logic' => array(
				array(
					array(
						'field' => 'field_5ad4674d8339f',
						'operator' => '==',
						'value' => 'google',
					),
				),
			),
			'wrapper' => array(
				'width' => '',
				'class' => '',
				'id' => '',
			),
			'layout' => 'table',
			'sub_fields' => array(
				array(
					'key' => 'field_5af450ec6baee',
					'label' => 'Theme',
					'name' => 'theme',
					'type' => 'select',
					'instructions' => '',
					'required' => 0,
					'conditional_logic' => 0,
					'wrapper' => array(
						'width' => '',
						'class' => '',
						'id' => '',
					),
					'choices' => array(
						'dark' => 'Dark',
						'light' => 'Light',
					),
					'default_value' => array(
						0 => 'light',
					),
					'allow_null' => 0,
					'multiple' => 0,
					'ui' => 0,
					'ajax' => 0,
					'return_format' => 'value',
					'placeholder' => '',
				),
				array(
					'key' => 'field_5af4512b6baef',
					'label' => 'Size',
					'name' => 'size',
					'type' => 'select',
					'instructions' => '',
					'required' => 0,
					'conditional_logic' => 0,
					'wrapper' => array(
						'width' => '',
						'class' => '',
						'id' => '',
					),
					'choices' => array(
						'compact' => 'Compact',
						'normal' => 'Normal',
					),
					'default_value' => array(
						0 => 'normal',
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
		array(
			'key' => 'field_5af455951b950',
			'label' => 'Display Settings',
			'name' => 'recaptcha_display_settings',
			'type' => 'group',
			'instructions' => '',
			'required' => 0,
			'conditional_logic' => array(
				array(
					array(
						'field' => 'field_5ad4674d8339f',
						'operator' => '==',
						'value' => 'google',
					),
				),
			),
			'wrapper' => array(
				'width' => '',
				'class' => '',
				'id' => '',
			),
			'layout' => 'block',
			'sub_fields' => array(
				array(
					'key' => 'field_5af455ba1b951',
					'label' => 'Hide On Load',
					'name' => 'hide_on_load',
					'type' => 'true_false',
					'instructions' => '',
					'required' => 0,
					'conditional_logic' => 0,
					'wrapper' => array(
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
		),
		array(
			'key' => 'field_5ad46a79d7cb4',
			'label' => 'GDPR',
			'name' => '',
			'type' => 'tab',
			'instructions' => '',
			'required' => 0,
			'conditional_logic' => 0,
			'wrapper' => array(
				'width' => '',
				'class' => '',
				'id' => '',
			),
			'placement' => 'top',
			'endpoint' => 0,
		),
		array(
			'key' => 'field_5ad46b1bd7cb5',
			'label' => 'GDPR',
			'name' => 'gdpr',
			'type' => 'true_false',
			'instructions' => 'The General Data Protection Regulation (GDPR) is a regulation in EU law on data protection and privacy for all individuals within the European Union.',
			'required' => 0,
			'conditional_logic' => 0,
			'wrapper' => array(
				'width' => '',
				'class' => '',
				'id' => '',
			),
			'message' => 'Show GDPR sign up options.',
			'default_value' => 0,
			'ui' => 1,
			'ui_on_text' => '',
			'ui_off_text' => '',
		),
		array(
			'key' => 'field_5ad46b6ad7cb6',
			'label' => 'GDPR Settings',
			'name' => 'gdpr_settings',
			'type' => 'group',
			'instructions' => '',
			'required' => 0,
			'conditional_logic' => array(
				array(
					array(
						'field' => 'field_5ad46b1bd7cb5',
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
			'layout' => 'block',
			'sub_fields' => array(
				array(
					'key' => 'field_5ad46b93d7cb7',
					'label' => 'Main message',
					'name' => 'main_message',
					'type' => 'textarea',
					'instructions' => '',
					'required' => 0,
					'conditional_logic' => 0,
					'wrapper' => array(
						'width' => '',
						'class' => '',
						'id' => '',
					),
					'default_value' => 'We would love to keep in touch with you, if you’re happy for us to do that please let us know below:',
					'placeholder' => '',
					'maxlength' => '',
					'rows' => 2,
					'new_lines' => '',
				),
				array(
					'key' => 'field_5ad46bddd7cb8',
					'label' => 'Opt in',
					'name' => 'opt_in',
					'type' => 'repeater',
					'instructions' => 'This will determine what options you show to the end user.',
					'required' => 0,
					'conditional_logic' => 0,
					'wrapper' => array(
						'width' => '',
						'class' => '',
						'id' => '',
					),
					'collapsed' => 'field_5ad46c39d7cb9',
					'min' => 1,
					'max' => 3,
					'layout' => 'table',
					'button_label' => 'Add Opt in',
					'sub_fields' => array(
						array(
							'key' => 'field_5ad46c39d7cb9',
							'label' => 'Message',
							'name' => 'message',
							'type' => 'textarea',
							'instructions' => 'Edit this so it is GDPR compliant',
							'required' => 0,
							'conditional_logic' => 0,
							'wrapper' => array(
								'width' => '50',
								'class' => '',
								'id' => '',
							),
							'default_value' => 'I am happy to receive marketing information from [YOUR_SITE] by: (please tick all that apply)',
							'placeholder' => '',
							'maxlength' => '',
							'rows' => 2,
							'new_lines' => '',
						),
						array(
							'key' => 'field_5ad46c84d7cba',
							'label' => 'Options',
							'name' => 'options',
							'type' => 'checkbox',
							'instructions' => 'What we show user',
							'required' => 0,
							'conditional_logic' => 0,
							'wrapper' => array(
								'width' => '25',
								'class' => '',
								'id' => '',
							),
							'choices' => array(
								'email' => 'Email',
								'sms' => 'SMS',
							),
							'allow_custom' => 0,
							'save_custom' => 0,
							'default_value' => array(
							),
							'layout' => 'horizontal',
							'toggle' => 0,
							'return_format' => 'value',
						),
						array(
							'key' => 'field_5afc36b540767',
							'label' => 'List IDs',
							'name' => 'list_ids',
							'type' => 'number',
							'instructions' => 'SendInBlue Group ID',
							'required' => 0,
							'conditional_logic' => 0,
							'wrapper' => array(
								'width' => '25',
								'class' => '',
								'id' => '',
							),
							'default_value' => '',
							'placeholder' => '',
							'prepend' => '',
							'append' => '',
							'min' => '',
							'max' => '',
							'step' => '',
						),
					),
				),
				array(
					'key' => 'field_5ad471d8af991',
					'label' => 'Disclaimer',
					'name' => 'disclaimer',
					'type' => 'wysiwyg',
					'instructions' => 'An additional message explaining to the user what their rights are.',
					'required' => 0,
					'conditional_logic' => 0,
					'wrapper' => array(
						'width' => '',
						'class' => '',
						'id' => '',
					),
					'default_value' => 'You can opt out of receiving messages at any time by using the unsubscribe button on any of the messages you receive. You can withdraw your information at any time by emailing [YOUR_EMAIL].

Marketing information refers to information on appointed reminders, news, products and services including competitions, promotions, offers, advertisements and prize draws.',
					'tabs' => 'visual',
					'toolbar' => 'basic',
					'media_upload' => 0,
					'delay' => 1,
				),
			),
		),
	),
	'location' => array(
		array(
			array(
				'param' => 'post_type',
				'operator' => '==',
				'value' => 'wp_swift_form',
			),
		),
	),
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