<?php
if( function_exists('acf_add_local_field_group') ):
$site_name = get_bloginfo( 'name' );
$site_email = get_option('admin_email');
acf_add_local_field_group(array(
	'key' => 'group_5d1b0ee925576',
	'title' => 'Options Page: Marketing',
	'fields' => array(
		array(
			'key' => 'field_5d1b0ee928690',
			'label' => 'Marketing',
			'name' => 'marketing',
			'type' => 'select',
			'instructions' => 'The General Data Protection Regulation (GDPR) is a regulation in EU law on data protection and privacy for all individuals within the European Union. These forms must comply with GDPR.',
			'required' => 0,
			'conditional_logic' => 0,
			'wrapper' => array(
				'width' => '',
				'class' => '',
				'id' => '',
			),
			'choices' => array(
				'none' => 'None',
				'mailin' => 'Send in Blue',
				'mailchimp' => 'MailChimp',
			),
			'default_value' => array(
				0 => 'none',
			),
			'allow_null' => 0,
			'multiple' => 0,
			'ui' => 0,
			'return_format' => 'value',
			'ajax' => 0,
			'placeholder' => '',
		),
		array(
			'key' => 'field_5d1b0ee9286a0',
			'label' => 'GDPR Settings',
			'name' => 'gdpr_settings',
			'type' => 'group',
			'instructions' => '',
			'required' => 0,
			'conditional_logic' => array(
				array(
					array(
						'field' => 'field_5d1b0ee928690',
						'operator' => '!=',
						'value' => 'none',
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
					'key' => 'field_5d1b0ee92acba',
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
					'key' => 'field_5d1b0ee92ad08',
					'label' => 'Opt in',
					'name' => 'opt_in',
					'type' => 'repeater',
					'instructions' => 'This will determine what options you show to the end user.',
					'required' => 0,
					'conditional_logic' => array(
						array(
							array(
								'field' => 'field_5d1b0ee928690',
								'operator' => '==',
								'value' => 'mailin',
							),
						),
					),
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
							'key' => 'field_5d1b0ee92d961',
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
							'default_value' => 'I am happy to receive marketing information from '.$site_name.' by: (please tick all that apply)',
							'placeholder' => '',
							'maxlength' => '',
							'rows' => 2,
							'new_lines' => '',
						),
						array(
							'key' => 'field_5d1b0ee92d975',
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
							'key' => 'field_5d1b0ee92d983',
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
					'key' => 'field_5d1b0ee92ad18',
					'label' => 'MailChimp Opt In',
					'name' => 'mailchimp_opt_in',
					'type' => 'repeater',
					'instructions' => 'This will determine what options you show to the end user.',
					'required' => 0,
					'conditional_logic' => array(
						array(
							array(
								'field' => 'field_5d1b0ee928690',
								'operator' => '==',
								'value' => 'mailchimp',
							),
						),
					),
					'wrapper' => array(
						'width' => '',
						'class' => '',
						'id' => '',
					),
					'collapsed' => 'field_5ad46c39d7cb9',
					'min' => 1,
					'max' => 1,
					'layout' => 'table',
					'button_label' => 'Add Opt in',
					'sub_fields' => array(
						array(
							'key' => 'field_5d1b0ee933ce6',
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
							'default_value' => 'I am happy to receive marketing information from '.$site_name.' by: (please tick all that apply)',
							'placeholder' => '',
							'maxlength' => '',
							'rows' => 2,
							'new_lines' => '',
						),
						array(
							'key' => 'field_5d1b0ee933d7b',
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
								'direct_mail' => 'Direct Email',
								'customized_online_advertising' => 'Customized Online Advertising',
							),
							'allow_custom' => 0,
							'default_value' => array(
							),
							'layout' => 'vertical',
							'toggle' => 0,
							'return_format' => 'value',
							'save_custom' => 0,
						),
					),
				),
				array(
					'key' => 'field_5d1b0ee92ad24',
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
					'default_value' => 'You can opt out of receiving messages at any time by using the unsubscribe button on any of the messages you receive. You can withdraw your information at any time by emailing '.$site_email.'.

Marketing information refers to information on appointed reminders, news, products and services including competitions, promotions, offers, advertisements and prize draws.',
					'tabs' => 'visual',
					'toolbar' => 'basic',
					'media_upload' => 0,
					'delay' => 1,
				),
				array(
					'key' => 'field_5d1b1f8f75ff7',
					'label' => 'License Message',
					'name' => 'license_message',
					'type' => 'wysiwyg',
					'instructions' => 'Shown only if the consent is set to not opt-in for single methods',
					'required' => 0,
					'conditional_logic' => 0,
					'wrapper' => array(
						'width' => '',
						'class' => '',
						'id' => '',
					),
					'default_value' => 'By registering, you confirm that you agree to the processing of your personal data by '.$site_name.' as described in the Privacy Statement.',
					'tabs' => 'visual',
					'toolbar' => 'full',
					'media_upload' => 1,
					'delay' => 1,
				),				
			),
		),
	),
	'location' => array(
		array(
			array(
				'param' => 'options_page',
				'operator' => '==',
				'value' => 'acf-options-marketing',
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