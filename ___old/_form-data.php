<?php 
/*
 * Get the booking form settings array
 *
 * @return array    form data array
 */
function get_contact_form_data() {
    $options = get_option( 'wp_swift_form_builder_contact_form_settings' );
    $form_first_and_last_name = false;
    $form_phone = false;
    if (isset($options['wp_swift_form_builder_contact_form_checkbox_first_last_name'])) {
        $form_first_and_last_name = true;
    }
    if (isset($options['wp_swift_form_builder_contact_form_checkbox_phone'])) {
        $form_phone = true;
    }

    $combine_name_fields = false;
    $show_telephone_input = false;
    $show_company_input = false;
    $form_data = array();

    if( class_exists('acf') ) {
        if( get_field('contact_form_page', 'option') ) {
            $contact_form_page = get_field('contact_form_page', 'option');
            $location[] = form_builder_location_array( $contact_form_page );
            if( get_field('combine_name_fields', $contact_form_page) ) {
                $combine_name_fields = get_field('combine_name_fields', $contact_form_page);
            }
            if( get_field('show_telephone_input', $contact_form_page) ) {
                $show_telephone_input = get_field('show_telephone_input', $contact_form_page);
            }
            if( get_field('show_company_input', $contact_form_page) ) {
                $show_company_input = get_field('show_company_input', $contact_form_page);
            }
        }
    }

    if (!$combine_name_fields) {
        $form_data['form-first-name'] = array (
            'passed' => false,
            'clean' => '',
            'value' => '',
            'section' => 0,
            'required' => 'required',
            'type' => 'text',
            'data_type' => 'text',
            'placeholder' => '',
            'label' => 'First Name',
            'help' => '',
          );
        $form_data['form-last-name'] = array (
            'passed' => false,
            'clean' => '',
            'value' => '',
            'section' => 0,
            'required' => 'required',
            'type' => 'text',
            'data_type' => 'text',
            'placeholder' => '',
            'label' => 'Last Name',
            'help' => '',
          );
    }
    else {
        $form_data['form-name'] = array (
            'passed' => false,
            'clean' => '',
            'value' => '',
            'section' => 0,
            'required' => 'required',
            'type' => 'text',
            'data_type' => 'text',
            'placeholder' => '',
            'label' => 'Name',
            'help' => '',
          );
    }

    $form_data['form-email'] = array (
        'passed' => false,
        'clean' => '',
        'value' => '',
        'section' => 0,
        'required' => 'required',
        'type' => 'email',
        'data_type' => 'email',
        'placeholder' => '',
        'label' => 'Email',
        'help' => '',
    );


    
    if ($show_telephone_input) {
        $form_data['form-phone'] = array (
            'passed' => false,
            'clean' => '',
            'value' => '',
            'section' => 0,
            'required' => '',
            'type' => 'text',
            'data_type' => 'text',
            'placeholder' => '',
            'label' => 'Telephone',
            'help' => '',
        );
    }

    if ($show_company_input) {
        $form_data['form-company'] = array (
            'passed' => false,
            'clean' => '',
            'value' => '',
            'section' => 0,
            'required' => '',
            'type' => 'text',
            'data_type' => 'text',
            'placeholder' => '',
            'label' => 'Company',
            'help' => '',
        );
    }

    $form_data['form-question'] =array (
        'passed' => false,
        'clean' => '',
        'value' => '',
        'section' => 0,
        'required' => 'required',
        'type' => 'textarea',
        'data_type' => 'textarea',
        'placeholder' => '',
        'label' => 'Question',
        'help' => '',
    );

    return $form_data;
}