<?php 
function wp_swift_get_form_data($id) {
    return wp_swift_form_data_loop($id);
    if (FORM_BUILDER_SAVE_TO_JSON) {

        $upload_dir = wp_upload_dir();
        $file_path = $upload_dir["basedir"].FORM_BUILDER_DIR;
        $file_name = 'form-builder-'.$id.'.json';
        $file = $file_path.$file_name;

        if (file_exists($file)) {
            return json_decode(file_get_contents($file), true);
        }
        else {
            return wp_swift_form_data_loop($id);
        }
    }
    else {

        $option_name = 'form-builder-'.$id;
        $form_data_option = get_option( $option_name );
        if ( $form_data_option ) {
            return json_decode($form_data_option, true);
        }
        else {
            return wp_swift_form_data_loop($id);;
        }
    }
}

function wp_swift_form_data_loop($id) {

    $form_data = array("sections" => false, "settings" => false );
    $settings = array();
    $sections = array();

    if (function_exists('get_field')) :
        if( get_field('hide_labels', $id) ) {
            $settings['hide_labels'] = true;
            $settings['form_css_class'] = ' hide-labels';
        }

        if( get_field('wrap_form', $id) ) {
            $settings['wrap_form'] = true;
            
            if ( isset($settings['form_css_class']) ) {
                $settings['form_css_class'] .= ' wrap';
            }
            else {
                $settings['form_css_class'] = ' wrap';
            }
        }

        if( get_field('submit_button_text', $id) ) {
            $settings['submit_button_text'] = get_field('submit_button_text', $id);
        }

        if ( have_rows('sections', $id) ) :

            $section_count = 0;
            $input_count = 0;

            while( have_rows('sections', $id) ) : the_row();
                $section = array();
                $inputs = array();

                if ( get_sub_field('section_header') ) {
                    $section["section_header"] = get_sub_field('section_header');
                }
                if ( get_sub_field('section_content') ) {
                    $section["section_content"] = get_sub_field('section_content');
                }            
                if ( have_rows('form_inputs') ) :
                    while( have_rows('form_inputs') ) : the_row();
                        $inputs_settings_array = build_acf_form_array($inputs, $settings, $section_count);  
                        $settings = $inputs_settings_array["settings"];
                        $inputs = $inputs_settings_array["inputs"];
                        $input_count++;
                    endwhile;
                endif;

                $section["inputs"] = $inputs;
                $sections[] = $section;
                $section_count++;
            endwhile;

            if ($input_count) {
                $form_data["sections"] = $sections;
                $form_data["settings"] = $settings;
            }

        endif;
    endif;

    return $form_data; 
}

function build_acf_form_array($inputs, $settings, $section=0) {
    global $post;
    $id = '';
    $type = 'text';
    $data_type = get_sub_field('type');

    $name = '';
    $label = '';
    $placeholder = '';
    $help = '';
    $instructions = '';
    $required = '';
    $grouping = false;
    $select_options='';
    $prefix = 'form-';
    $css_class = '';
    $css_class_input = '';
    $other = false;

    $rows = 2;
    $maxlength = 1000;

    if( get_sub_field('id') ) {
        $id_group = get_sub_field('id');
        if ($id_group["name"]) {
            $name = $id_group["name"];
            $id = sanitize_title_with_dashes( $name );
            if ($id_group["label"]) {
                $label = $id_group["label"];
            }
            else {
                $label = $name;
            }
        }
    }

    if( get_sub_field('settings') ) {
        $settings_group = get_sub_field('settings');
        $required = $settings_group["required"];
        $grouping = $settings_group["grouping"];
        if ($grouping == 'none') {
            $grouping = false;
        }
        else {
            if (!isset($settings["groupings"])) {
                $settings["groupings"] = true;
            }
        }
    }

    if( get_sub_field('reporting') ) {
        $reporting_group = get_sub_field('reporting');
        $instructions = $reporting_group["instructions"];
        $help = $reporting_group["help"];
        if ($help === '') {
            if ($required) {
                $help = $label.' is required';
                if ( $data_type === 'email' ||  $data_type === 'url') {
                    $help .= ' and must be valid';
                }
                elseif ( $data_type === 'date' ||  $data_type === 'date_range') {
                    $help .= ' and must be formatted to '.FORM_BUILDER_DATE_FORMAT;
                }
            }
            else {
                $help = $label.' is not valid';
            }
        }
    }
    
    if($required) {
        $required = 'required';
    }
    else {
        $required = '';
    }

    if( get_sub_field('type') ) {
        $type = get_sub_field('type');
        $data_type = get_sub_field('type');
    }

    if( $type === 'date' || $type === 'date_time' || $type === 'date_range' ) {
        $type='text';
    }

    if( get_sub_field('placeholder') ) {
        $placeholder = get_sub_field('placeholder');
    }
    
    if( get_sub_field('other') ) {
        $other = true;
        $css_class .= ' js-other-value-event';
    }

    if( $data_type === 'date' ) {
        $css_class .= ' js-date-picker';
    }

    if( $data_type === 'date_range' ) {
        $css_class .= ' js-date-picker-range';
        $css_class_input = 'js-date-picker-range';
        if (!isset($settings["groupings"])) {
            $settings["groupings"] = true;
        }        
    }

    if( $data_type === 'textarea' ) {
        $textarea_settings_group = get_sub_field('textarea_settings');
        if ($textarea_settings_group["rows"]) {
            $rows = $textarea_settings_group["rows"];
        }
        if ($textarea_settings_group["maxlength"]) {
            $maxlength = $textarea_settings_group["maxlength"];
        }        
    }   

    if( get_sub_field('select_options') ) {
        $select_options = get_sub_field('select_options');
        if ($data_type === 'checkbox' || $data_type === 'select' || $data_type === 'radio') {
            foreach ($select_options as $key => $value) {
                $value['checked'] = false;
                if ( $value['option_value'] === '') {
                    $select_options[$key]['option_value'] = sanitize_title_with_dashes( $value['option'] );
                }
                if ($data_type==='checkbox') {
                    $select_options[$key]['checked'] = false;
                }               
            }
            if ( $other && $data_type==='checkbox') {
                $select_options[] = array('option' => 'Other', 'option_value' => 'other', 'checked' => false);
            }
            elseif( $other ) {
                $select_options[] = array('option' => 'Other', 'option_value' => 'other');
            }
        }          
    }

    if( isset($settings['hide_labels']) ) {
        if ($placeholder === '') {
            $placeholder = $label;
        }
    }

    /*
     * Check for array key conflict and increment $id if found
     */
    if (array_key_exists($prefix.$id, $inputs)) {
        for ($i=1; $i < 20; $i++) { 
            if (!array_key_exists($prefix.$id.'-'.$i, $inputs)) {
                $id = $id.'-'.$i;
                break;
            }
        }
    }    

    switch ($data_type) {           
        case "text":
        case "url":
        case "email":
        case "number":
        case "date":
            $inputs[$prefix.$id] = array("passed"=>false, "clean"=>"", "value"=>"", "section"=>$section, "required"=>$required, "type"=>$type, "data_type"=>$data_type,  "placeholder"=>$placeholder, "label"=>$label, "help"=>$help, "instructions" => $instructions, "grouping" => $grouping, "css_class" => $css_class);
            break;
        case "textarea":
            $inputs[$prefix.$id] = array("passed"=>false, "clean"=>"", "value"=>"", "section"=>$section, "required"=>$required, "type"=>$type, "data_type"=>$data_type,  "placeholder"=>$placeholder, "label"=>$label, "help"=>$help, "instructions" => $instructions, "grouping" => $grouping, "css_class" => $css_class, "rows" => $rows, "maxlength" => $maxlength);

            break; 
        case "select":
        case "multi_select":
        case "checkbox":
        case "radio":
            $inputs[$prefix.$id] = array("passed"=>false, "clean"=>"", "value"=>"", "section"=>$section, "required"=>$required, "type"=>$type, "data_type"=>$data_type, "label"=>$label, "options"=>$select_options, "selected_option"=>"", "help"=>$help, "instructions" => $instructions, "grouping" => $grouping, "css_class" => $css_class);
            break; 
        case "checkbox_single":
             $inputs[$prefix.$id] = array("passed"=>false, "clean"=>"", "value"=>"", "section"=>$section, "required"=>$required, "type"=>"checkbox", "data_type"=>$data_type, "label"=>$label, "option"=>array("value" => 1, "key" => get_sub_field('checkbox_label'), 'checked' => false), "selected_option"=>"", "help"=>$help, "instructions" => $instructions, "grouping" => $grouping, "css_class" => $css_class);
            break;       
        case "file":
            $enctype = 'enctype="multipart/form-data"';
            $form_class = 'js-check-form-file';
            $inputs[$prefix.$id] = array("passed"=>false, "clean"=>"", "value"=>"", "section"=>$section, "required"=>$required, "type"=>$type, "data_type"=>$data_type,  "placeholder"=>$placeholder, "label"=>$label, "accept"=>"pdf", "help"=>$help, "instructions" => $instructions, "grouping" => $grouping, "css_class" => $css_class);
            break;              
        case "date_range":
            $inputs[$prefix.$id.'-start'] = array("passed"=>false, "clean"=>"", "value"=>"", "section"=>$section, "required"=>$required, "type"=>$type, "data_type"=>"date", "label"=>"Date From", "help"=>$help, "instructions" => $instructions, "grouping" => 'start', "css_class" => $css_class.' js-date-range', 'css_class_input' => $css_class_input, 'order'=>0, 'parent_label'=>$label);
            $inputs[$prefix.$id.'-end'] = array("passed"=>false, "clean"=>"", "value"=>"", "section"=>$section, "required"=>$required, "type"=>$type, "data_type"=>"date", "label"=>"Date To", "help"=>$help, "instructions" => $instructions, "grouping" => 'end', "css_class" => $css_class.' js-date-range', 'order'=>1, 'parent_label'=>$label);
            break;                                               
    }
    if( $other ) {
        $inputs[$prefix.$id.'-other'] = array("passed"=>false, "clean"=>"", "value"=>"", "section"=>$section, "required"=>'', "type"=>"text", "data_type"=>"text",  "placeholder"=>$placeholder, "label"=>"Other ".$label, "help"=>$help, "instructions" => $instructions, "grouping" => false, "css_class" => " js-other-value css-hide");
    }    
    return array("inputs" => $inputs, "settings" => $settings);       
}