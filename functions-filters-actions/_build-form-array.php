<?php 
function wp_swift_get_form_data($id, $_post = null) {
    return wp_swift_form_data_loop($id, $_post);
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
            return wp_swift_form_data_loop($id);
        }
    }
}

function wp_swift_form_data_loop($id, $_post = null) {

    $form_data = array("sections" => false, "settings" => false );
    $settings = array();
    $sections = array();
    $edit_id = get_query_var('edit-form');
    $switch_to_blog = get_query_var('switch');

    if (function_exists('get_field')) :

        $labels = get_field('labels', $id);
        if( $labels ) {
            if ($labels == 'hide_labels') {
                $settings['hide_labels'] = true;
                $settings['form_css_class'] = ' hide-labels';                
            }
            else {
                $settings['form_css_class'] = ' labels-'.$labels;
            }

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

        if( get_field('user_confirmation_email', $id) ) {
            $settings['user_confirmation_email'] = get_field('user_confirmation_email', $id);
        }

        if( $show_page_in_email = get_field('show_page_in_email', $id) ) {         
            $settings['show_page_in_email'] = $show_page_in_email;
        }

        if( get_field('show_edit_link', $id) ) {
            $settings['show_edit_link'] = get_field('show_edit_link', $id);
        }
        

        if( $css = get_field('css', $id) ) {
            $settings['css'] = $css;
        } 

        if( $tab_index = get_field('tab_index', $id) ) {
            $settings['tab_index'] = $tab_index;
        }        

        if( $next_button_in_sections = get_field('next_button_in_sections', $id) ) {
            $settings['next_button_in_sections'] = $next_button_in_sections;
        }

        if( $show_section_stage_guide = get_field('show_section_stage_guide', $id) ) {
            $settings['show_section_stage_guide'] = $show_section_stage_guide;
        }

        if( $transparent_inputs = get_field('transparent_inputs', $id) ) {
            $settings['transparent_inputs'] = $transparent_inputs;
        }        

        if( $colour_theme = get_field('colour_theme', $id) ) {
            $settings['colour_theme'] = ' form-builder-theme-'.$colour_theme;
        }

        if (get_field('ajax', $id)) {
            $ajax = get_field('ajax', $id);
        }
        else {
            // Support legacy forms where this field is not set.
            $ajax = true;
        }
        if ($ajax) $settings['ajax'] = 1; else $settings['ajax'] = 0;
 


        // todo - move the get_field requests into _build-form-array.php
        // if (function_exists("get_field")) {
        if( $spam_prevention_type = get_field('spam_prevention_type', $id ) ) {
            // $spam_prevention_type = get_field('spam_prevention_type', $id );
            if ($spam_prevention_type === 'google') {

                $options = get_option( 'wp_swift_form_builder_settings' );
                $google_settings = $options['wp_swift_form_builder_google_recaptcha'];
                if ( $google_settings["site_key"] !== '' && $google_settings["secret_key"] !== '' ) {
                    // $this->recaptcha = $google_settings;
                    $settings['recaptcha'] = $google_settings;
                    //echo '<pre>1 $this->recaptcha: '; var_dump($this->recaptcha); echo '</pre>';
                }
                if( $recaptcha_settings = get_field('recaptcha_settings', $id) ) {
                    // $recaptcha_settings = get_field('recaptcha_settings', $id);
                    // $this->recaptcha = array_merge( $this->recaptcha, $recaptcha_settings );
                    $settings['recaptcha'] = array_merge( $settings['recaptcha'], $recaptcha_settings );
                    //echo '<pre>2 $this->recaptcha: '; var_dump($this->recaptcha); echo '</pre>';
                }
                if( $recaptcha_display_settings = get_field('recaptcha_display_settings', $id) ) {
                    // $recaptcha_display_settings = get_field('recaptcha_display_settings', $id);
                    // $this->recaptcha = array_merge( $this->recaptcha, $recaptcha_display_settings );
                    $settings['recaptcha'] = array_merge( $settings['recaptcha'], $recaptcha_display_settings );
                    //echo '<pre>3 $this->recaptcha: '; var_dump($this->recaptcha); echo '</pre>';
                }                
            }
        }
        // $gdpr = get_field('gdpr', $id );
        // // echo '<pre>$gdpr: '; var_dump($gdpr); echo '</pre>';
        // if( $marketing = get_field('marketing', $id ) !== 'none' ) {
        //     $this->gdpr_settings = get_field('gdpr_settings', $id);
        //     // echo '<pre>$this->gdpr_settings: '; var_dump($this->gdpr_settings); echo '</pre>';
        // }              
        // }
        //@end todo
        //
        // if( $marketing = get_field('marketing', $id) ) {
        //     if ($marketing !== 'none' ) {
        //         $settings['marketing'] = $marketing;
        //         $settings['gdpr_settings'] = get_field('gdpr_settings', $id);
        //     }
        // } 
        $marketing = get_field('marketing', $id);
        if($marketing === true ) {              
            $marketing_type = get_field('marketing', 'option');
            if ($marketing_type !== 'none' ) {
                $consent = get_field('consent', $id);
                $settings['marketing'] = $marketing_type;
                $settings['consent'] = $consent;
                if( $gdpr_settings = get_field('gdpr_settings', 'option') ) {
                    $settings['gdpr_settings'] = $gdpr_settings;
                }
            }
        }
        
        // todo: get other marketing/GDPR fields into here
        
        $settings['displaying_results'] = array ( 'results_modal' => true, 'dom_element_to_inject' => '', 'dom_element_to_remove' => '', );
        if( $displaying_results = get_field('displaying_results', $id) ) {
            $settings['displaying_results'] = $displaying_results;
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
                if ( $section_image = get_sub_field('section_image') ) {
                    $section["section_image"] = $section_image;
                }  
                if ( $section_css = get_sub_field('section_css') ) {
                    $section["section_css"] = $section_css;
                }                                            
                if ( have_rows('form_inputs') ) :
                    while( have_rows('form_inputs') ) : the_row();
                        $row_layout = get_row_layout();
                        // echo '<pre>$row_layout: '; var_dump($row_layout); echo '</pre>';
                        $inputs_settings_array = build_acf_form_array($row_layout, $inputs, $settings, $section_count, $edit_id, $switch_to_blog, $_post);  
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

function build_acf_form_array($row_layout, $inputs, $settings, $section=0, $edit_id = false, $switch_to_blog = false, $_post = null) {

    global $post;
    $id = '';
    $type = 'text';
    $data_type = $row_layout;
    $name = '';
    $label = '';
    $placeholder = '';
    $help = '';
    $instructions = '';
    $required = '';
    $grouping = false;
    $select_options= array();
    $selected_option = null;
    $select_type = null;
    $option_group = false;
    $allow_null = true;
    $prefix = 'form-';
    $css_class = '';
    $css_class_input = '';
    $other = false;
    $readonly = false;
    $rows = 2;
    $maxlength = 1000;
    $value = '';
    $validation = null;
    $disabled = false;
    $options_layout = '';
    $name =  get_sub_field('form_input_name');
    $label =  get_sub_field('label');

    if( $name ) {
        if ( !$label ) {
            $label = $name;
        } 
            // $id_group = get_sub_field('name');
        // echo '<pre>$id_group: '; var_dump($id_group); echo '</pre>';
        // if ($id_group["name"]) {
        //     $name = $id_group["name"];
            $id = sanitize_title_with_dashes( $name );
            // if ($id_group["label"]) {
            //     $label = $id_group["label"];
            // }
            // else {
            //     $label = $name;
            // }

            if ($edit_id) {
                // echo '<pre>$switch_to_blog: '; var_dump($switch_to_blog); echo '</pre>';
                if ($switch_to_blog) switch_to_blog( $switch_to_blog );
                
                // echo '<pre>$id: '; var_dump($id); echo '</pre>';
                $field_group = str_replace('-', '_', $id);
                // echo '<pre>$field_group: '; var_dump($field_group); echo '</pre>';
                $value = get_field( $field_group, $edit_id);
                // echo '<pre>$value: '; var_dump($value); echo '</pre>';echo "<hr>";
                // echo "<hr>";
                if ($switch_to_blog) restore_current_blog();
            }            
        // }
    }
    $css_class = get_sub_field('group_css');
    $css_class_input = get_sub_field('input_css');
    // if( get_sub_field('css_class') ) {
    //     $css_class = $css_class_input = get_sub_field('css_class');
    // }
    // if( get_sub_field('css_class') ) {
    //     $css_class = $css_class_input = get_sub_field('css_class');
    // }    
    $disabled = false;
    if( get_sub_field('disabled') ) {
        $disabled = true;
    }    

    $required = get_sub_field('required');
    // echo '<pre>$required: '; var_dump($required); echo '</pre>';
    $grouping = get_sub_field('grouping');
    // if( get_sub_field('settings') ) {
    //     $settings_group = get_sub_field('settings');
    //     $required = $settings_group["required"];
    //     $grouping = $settings_group["grouping"];
        if ($grouping == 'none') {
            $grouping = false;
        }
        else {
            if (!isset($settings["groupings"])) {
                $settings["groupings"] = true;
            }
        }
    // }
    $help = get_sub_field('help');
    $instructions = get_sub_field('instructions');
    // if( get_sub_field('reporting') ) {
    //     $reporting_group = get_sub_field('reporting');
    //     $instructions = $reporting_group["instructions"];
    //     $help = $reporting_group["help"];
        if ($help === '') {
            if ($required) {
                $help = $label.' is required';
                if ( $data_type === 'email' ||  $data_type === 'url') {
                    $help .= ' and must be valid';
                }
                elseif ( $data_type === 'date' ||  $data_type === 'date_range') {
                    $help .= ' and must be formatted to '.get_form_builder_date_format();
                }
            }
            else {
                $help = $label.' is not valid';
            }
        }
    // }
    
    if($required) {
        $required = 'required';
    }
    else {
        $required = '';
    }

// if( get_sub_field('type') ) {
//     $type = get_sub_field('type');
//     $data_type = get_sub_field('type');
// }

    if ($data_type === 'text') {
        if( get_sub_field('validation') ) {
            $validation_group = get_sub_field('validation');
            // echo '<pre>$validation_group: '; var_dump($validation_group); echo '</pre>';
            $min = $validation_group["min"];
            $max = $validation_group["max"];
            // $type_field = $validation_group["type_field"];
            if ($min || $max) {
                 $validation =  $validation_group;
                 // echo '<pre>$validation: '; var_dump($validation); echo '</pre>';
            }
            // echo $validation;
            // echo '<pre>$validation: '; var_dump($validation); echo '</pre>';
        }
    }

    if( $data_type === 'date' || $data_type === 'date_time' || $data_type === 'date_range' ) {
        $type = 'text';
    }
    else {
        $type = $data_type;
    }

    if( get_sub_field('placeholder') ) {
        $placeholder = get_sub_field('placeholder');
    }
    
    if( get_sub_field('other') ) {
        $other = true;
        $css_class .= ' js-other-value-event';
        // echo $id;echo "<hr>";
    }

    $date_ranges = '';
    if( $data_type === 'date' || $data_type === 'date_range' ) {
        $date_ranges = get_sub_field('date_ranges');
    }

    if( $data_type === 'date' ) {
        $css_class .= 'js-date-picker ' . $date_ranges;
    }

    if( $data_type === 'file' ) {
        $css_class .= 'file-upload';
        $save_location = get_sub_field('save_location');
    }
    if( $data_type === 'date_range' ) {
         
        // echo '<pre>$date_ranges: '; var_dump($date_ranges); echo '</pre>';       
        $css_class .= ' js-date-picker-range ' . $date_ranges;
        $css_class_input = 'js-date-picker-range';
        if (!isset($settings["groupings"])) {
            $settings["groupings"] = true;
        }        
    }

    // if( $data_type === 'date' ) {
    //     $date_ranges = get_sub_field('date_ranges');
    //     // echo '<pre>$date_ranges: '; var_dump($date_ranges); echo '</pre>';
    //     if( $date_ranges ) {
    //         $css_class .= ' ' . $date_ranges;
    //     }
    // }

    if( $data_type === 'textarea' ) {
        $textarea_settings_group = get_sub_field('textarea_settings');
        if ($textarea_settings_group["rows"]) {
            $rows = $textarea_settings_group["rows"];
        }
        if ($textarea_settings_group["maxlength"]) {
            $maxlength = $textarea_settings_group["maxlength"];
        }        
    }   
    $select_options = get_sub_field('options');
    if( $select_options ) {
        // $select_options = get_sub_field('select_options');
        // echo '<pre>$data_type: '; var_dump($data_type); echo '</pre>';
        if ($data_type === 'checkbox' || $data_type === 'select' || $data_type === 'radio') {       
            foreach ($select_options as $key => $option) {
                $option['checked'] = false;
                if ( $option['option_value'] === '') {
                    $select_options[$key]['option_value'] = sanitize_title_with_dashes( $option['option'] );
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
    if ($data_type === 'checkbox' || $data_type === 'select' || $data_type === 'radio') {
        $options_layout = get_sub_field('options_layout');
        $css_class .= ' ' . $options_layout;

    }

    $select_type = get_sub_field('select_type');
    $multiple_select = get_sub_field('multiple_select');
    
    if ($multiple_select) {
        $data_type = 'multi_select';
        $stylised_ui = get_sub_field('stylised_ui');
        // if ($stylised_ui) $css_class .= ' js-select2-multiple';

    }
    //     $data_type = 'multi_select';
    //     echo "<hr>";
    //     echo '<pre>2 $data_type: '; var_dump($data_type); echo '</pre>';
    //     echo '<pre>$multiple_select: '; var_dump($multiple_select); echo '</pre>';echo '<pre>$id: '; var_dump($id); echo '</pre>';

    // }
    
    if( ( $data_type == 'select' || $data_type == 'multi_select' || $data_type == 'checkbox' ) && $select_type ) {
        
        // echo "<hr>";
        // echo '<h1>HELP</h1>';
        // echo '<pre>$data_type: '; var_dump($data_type); echo '</pre>';
        // echo '<pre>$name: '; var_dump($name); echo '</pre>';
        // echo '<pre>$select_type: '; var_dump($select_type); echo '</pre>';
        // echo '<h1>HELP</h1>';
        /*
         * The user has selected a predefined option
         */
        //|| $select_type === 'multi_select' || $select_type === 'checkbox'
        if ( $select_type === 'select' ) {

            /*
             * $option_group determines if options have an <optgroup> tag
             */
            $option_group = get_sub_field("option_group");
            // echo '<pre>$option_group: '; var_dump($option_group); echo '</pre>';

            $predefined_options = get_sub_field('predefined_options');
            // echo '<pre>$predefined_options: '; var_dump($predefined_options); echo '</pre>';
            if( $predefined_options ) {
                // echo '<pre>$data_type: '; var_dump($data_type); echo '</pre>';
                $func = "wp_swift_form_builder_get_array_".$predefined_options;//The function name
                // echo '<pre>$func: '; var_dump($func); echo '</pre>'; 
                // echo "<hr>";
                if (function_exists($func)) {
                    $response = $func();//Call the function to get the predefined options array
                    // echo '<pre>$response: '; var_dump($response); echo '</pre>';echo "<hr>";echo "<hr>";
                    $array = $response["array"];
                    if (isset($response["option_group"])) $option_group = $response["option_group"];
                    // if( $data_type === 'select' || $data_type === 'multi_select' || $data_type === 'checkbox') {

                        if ($option_group) {

                            $options_group = array();
                           
                            // echo '<pre>$array: '; var_dump($array); echo '</pre>';
                            // Parse the array
                            foreach ($array as $group_key => $group) {
                                 $select_options = array();
                                foreach ($group as $value_key => $option_value) {
                                    $select_options[] = array(
                                        'option' => $option_value,
                                        'option_value' => $value_key,
                                    );       
                                } 
                                $options_group[$group_key] = $select_options;

                            }

                          // echo '<pre>$options_group: '; var_dump($options_group); echo '</pre>';
                          $select_options = $options_group;

                        }
                        else {

                            $select_options = array();
                            // Parse the array
                            foreach ($array as $key => $option_value) {

                                $option = array(
                                    'option' => $option_value,
                                    'option_value' => $key,
                                );
                                if ($data_type === 'checkbox') {
                                    $option["checked"] = false;
                                }
                                $select_options[] = $option;       
                            }  

                            if (isset( $response["selected_option"] )) {
                                $selected_option = $response["selected_option"];     
                            }  

                        }
                    // }
                    // elseif ($data_type === 'multi_select') {


                    // }
                    
                    if (!$selected_option && isset($value)) {
                        $selected_option = $value;
                    } 

                }            
            } 
        }
    }



    /**
     * We will use a custom function outside this plugin to get this input settings.
     * 
     * It is up to the developer to handle this.
     */
    if( $data_type === 'custom' ) {     
        if( get_sub_field('custom') ) {
            $custom = get_sub_field('custom');
            // echo '<pre>$custom: '; var_dump($custom); echo '</pre>';
            if (function_exists($custom)) {
                # Call the function to get the predefined options array and settings
                $custom_settings = $custom( $edit_id, $_post );
                if (is_array($custom_settings)) {
                    $type = $custom_settings["type"];
                    $data_type = $custom_settings["data_type"];
                    if (isset(  $custom_settings["select_options"] )) {
                        $select_options = $custom_settings["select_options"];
                    }
                    if (isset( $custom_settings["readonly"] )) {
                        $readonly = $custom_settings["readonly"];
                    }
                    if (isset( $custom_settings["selected_option"] )) {
                        $selected_option = $custom_settings["selected_option"];
                        // echo '<pre>$selected_option: '; var_dump($selected_option); echo '</pre>';
                    }
                    if (isset( $custom_settings["allow_null"] )) {
                        $allow_null = $custom_settings["allow_null"];
                    }                                    
                    if (isset($custom_settings["form_data"])) {
                        $nested_form_data = $custom_settings["form_data"];
                        $test = array();
                        foreach ($nested_form_data as $key => $nested_input) {
                            $inputs[$key] = $nested_input;
                        }
                        // echo '<pre>$test: '; var_dump($test); echo '</pre>';
                    }                    
                }
                else {
                    return array("inputs" => $inputs, "settings" => $settings);
                }
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
        case "true_false":
            $inputs[$prefix.$id] = array("passed"=>false, "clean"=>$value, "value"=>$value, "section"=>$section, "required"=>$required, "type"=>$type, "data_type"=>$data_type,  "placeholder"=>$placeholder, "label"=>$label, "help"=>$help, "instructions" => $instructions, "grouping" => $grouping, "css_class" => $css_class, 'css_class_input' => $css_class_input, "validation" => $validation, 'disabled' => $disabled);
            break;
        case "textarea":
            $inputs[$prefix.$id] = array("passed"=>false, "clean"=>$value, "value"=>$value, "section"=>$section, "required"=>$required, "type"=>$type, "data_type"=>$data_type,  "placeholder"=>$placeholder, "label"=>$label, "help"=>$help, "instructions" => $instructions, "grouping" => $grouping, "css_class" => $css_class, "rows" => $rows, "maxlength" => $maxlength);

            break; 
        case "select":
        case "multi_select":
        case "checkbox":
        case "radio":
            $inputs[$prefix.$id] = array("passed"=>false, "clean"=>$value, "value"=>$value, "section"=>$section, "required"=>$required, "type"=>$type, "data_type"=>$data_type, "label"=>$label, "options"=>$select_options, "selected_option"=>$selected_option, "option_group"=>$option_group, "allow_null" => $allow_null, "help"=>$help, "instructions" => $instructions, "grouping" => $grouping, "css_class" => $css_class, "readonly" => $readonly, 'disabled' => $disabled);
            break; 
        case "checkbox_single":
             $inputs[$prefix.$id] = array("passed"=>false, "clean"=>$value, "value"=>$value, "section"=>$section, "required"=>$required, "type"=>"checkbox", "data_type"=>$data_type, "label"=>$label, "option"=>array("value" => 1, "key" => get_sub_field('checkbox_label'), 'checked' => false), "selected_option"=>"", "help"=>$help, "instructions" => $instructions, "grouping" => $grouping, "css_class" => $css_class);
            break;       
        case "file":
            // $enctype = 'enctype="multipart/form-data"';
            // echo '<pre>$enctype: '; var_dump($enctype); echo '</pre>';
            $settings["enctype"] = ' enctype="multipart/form-data"';
            $form_class = 'js-check-form-file';
            $save_location = get_sub_field('save_location');
            if (!$save_location) $save_location = 'uploads';
            $css_class_input .= 'js-file-upload';
            $inputs[$prefix.$id] = array("passed"=>false, "clean"=>$value, "value"=>$value, "section"=>$section, "required"=>$required, "type"=>$type, "data_type"=>$data_type,  "placeholder"=>$placeholder, "label"=>$label, "accept"=>"pdf", "help"=>$help, "instructions" => $instructions, "grouping" => $grouping, "css_class" => $css_class, "css_class_input" => $css_class_input, 'disabled' => $disabled, "save_location" => $save_location);
            break;              
        case "date_range":
            $inputs[$prefix.$id.'-start'] = array("passed"=>false, "clean"=>$value, "value"=>$value, "section"=>$section, "required"=>$required, "type"=>$type, "data_type"=>"date", "label"=>"Date From", "help"=>$help, "instructions" => $instructions, "grouping" => 'start', "css_class" => $css_class.' js-date-range', 'css_class_input' => $css_class_input, 'order'=>0, 'parent_label'=>$label, 'disabled' => $disabled);
            $inputs[$prefix.$id.'-end'] = array("passed"=>false, "clean"=>$value, "value"=>$value, "section"=>$section, "required"=>$required, "type"=>$type, "data_type"=>"date", "label"=>"Date To", "help"=>$help, "instructions" => $instructions, "grouping" => 'end', "css_class" => $css_class.' js-date-range', 'order'=>1, 'parent_label'=>$label, 'disabled' => $disabled);
            break;  
        case "repeat_section":
            $repeat_section = wp_swift_get_repeating_section_form_data( $prefix, $id, get_sub_field('repeating_section'), $inputs, $edit_id, $switch_to_blog, $_post );
            $inputs = $repeat_section["inputs"];
            $inputs[$prefix.$id] = $repeat_section["repeat_section"];
            // }

            break;                                             
    }
    if( $other ) {
        $inputs[$prefix.$id.'-other'] = array("passed"=>false, "clean"=>$value, "value"=>$value, "section"=>$section, "required"=>'', "type"=>"text", "data_type"=>"text",  "placeholder"=>$placeholder, "label"=>"Other ".$label, "help"=>$help, "instructions" => $instructions, "grouping" => false, "css_class" => " js-other-value hide", 'disabled' => true);
    }    
    return array("inputs" => $inputs, "settings" => $settings);       
}

function wp_swift_get_repeating_section_form_data( $prefix, $id, $repeating_section, $inputs, $edit_id, $switch_to_blog, $_post = null ) {
    // $parts_list = array(
    //     'key' => 'field_5b3e14b10c913',
    //     'label' => 'Parts List',
    //     'name' => 'parts_list'
    // );


    $type = "repeat_section";
    $data_type = "repeat_section";    
    $form_id = $repeating_section["form_id"];
    $min = 0;
    if ($edit_id) {
        // $min = 3;
        // $field_group = str_replace('-', '_', $id);

        if ($switch_to_blog) switch_to_blog( $switch_to_blog );

        // echo '<pre>$id: '; var_dump($id); echo '</pre>';
        $field_group = str_replace('-', '_', $id);
        // echo '<pre>$field_group: '; var_dump($field_group); echo '</pre>';
        $repeater_values = get_field( $field_group, $edit_id);
        if (is_array($repeater_values)) {
            $min = count($repeater_values);
        }
        if ($switch_to_blog) restore_current_blog();        
    }
    else {
        $min = $repeating_section["min"];
        // If the $_post array has a count set for repeating rows.
        if (isset($_post[$prefix.$id])) {
            $count_sections_in_post = (int) $_post[$prefix.$id];
            if (is_int($count_sections_in_post)) {
                $min = $count_sections_in_post;
            }
            
            // echo '<pre>$count_sections_in_post: '; var_dump($count_sections_in_post); echo '</pre>';
            // echo '<pre>$prefix: '; var_dump($prefix); echo '</pre>';
            // echo '<pre>$id: '; var_dump($id); echo '</pre>';
            // echo "<hr>";
            // echo '<pre>$repeating_section: '; var_dump($repeating_section); echo '</pre>';
            // echo '<pre>$_POST: '; var_dump($_POST); echo '</pre>';
        }           
    }
    $max = $repeating_section["max"];
    if ($min > $max) $min = $max;
        

    $button_text = $repeating_section["button_text"];
    $input_keys = array();
    $input_arrays = array();
    $buttons = array();

    if( $form_id ) {

        $form = new WP_Swift_Form_Builder_Parent( $form_id, null );
        $form_data = $form->get_form_data( $sections = false );

        // echo '<pre>$inputs: '; var_dump($inputs); echo '</pre>';
        // echo "<hr>";

        // $j = 0;
       
            for ($i = 0; $i < $min && $i < $max; $i++) {
                // echo "<pre>i: $i</pre>";
                // echo '<pre>$i: '; var_dump($i); echo '</pre>';
                // echo "<pre>$i</pre>";
                // echo '<pre>$repeater_values[$i]: '; var_dump($repeater_values[$i]); echo '</pre>';
                if ( isset($repeater_values) ) {
                    $repeater_value = $repeater_values[$i]["part"];
                }
                
                // echo '<pre>$repeater_value: '; var_dump($repeater_value); echo '</pre>';
                // $test = $repeater_values[$i];
                // echo '<pre>$test: '; var_dump($test); echo '</pre>';
                foreach ($form_data as $key => $input) {
                    // echo '<pre>$key: '; var_dump($key); echo '</pre>';
                    $subfield_group = str_replace('form-', '', $key);
                    $subfield = str_replace('-', '_', $subfield_group);
                    // echo '<pre>$subfield: '; var_dump($subfield); echo '</pre>';
                    // $j++;
                    // echo '<pre> $j: '; var_dump($j); echo '</pre>';
                    // echo "<pre>$j</pre>";
                    // echo '<pre>  $input["clean"]: '; var_dump($input["clean"]); echo '</pre>';
                    // $rand = rand(0, 9);
                    if ( isset($repeater_values) ) {
                        if (isset($repeater_value[$subfield])) {
                            $input["value"] = $repeater_value[$subfield];
                        }
                    }
                    
                    // $input["clean"] = $rand;
                    // echo '<pre>$input: '; var_dump($input); echo '</pre>';
                    // echo "<hr>";
                    // echo '<pre>  $input["clean"]: '; var_dump($input["clean"]); echo '</pre>';echo "<hr>";
                    $inputs[$key.'-'.($i+1)] = $input;
                }              
            }
        // }

        // echo '<pre>$inputs: '; var_dump($inputs); echo '</pre>';
        foreach ($form_data as $key => $input) {
            $input_keys[] = $key;
            $input_arrays[$key] = $input;
        } 

        $add_button_text = $button_text;
        $remove_button_text = str_replace("Add", "Remove", $add_button_text);
        $add_button_disabled = '';
         // $remove_button_disabled = '';
        $remove_button_disabled = ' disabled';

        if ( $min === $max ) {
            $remove_button_disabled = '';
            $add_button_disabled = ' disabled';
        } 
        elseif ( $min > 0 && $min < $max ) {
            $add_button_disabled = '';
            $remove_button_disabled = '';      
        }
        elseif ( $min === 0) {
            $add_button_disabled = '';
            $remove_button_disabled = ' disabled';      
        }               

        // if ( $min > $max ) {
        //     $remove_button_disabled = '';
        // }
        // if ( $min > 0 ) {
        //     $add_button_disabled = ' disabled';
        // }

    // echo '<pre>$min: '; var_dump($min); echo '</pre>';
    // echo '<pre>$max: '; var_dump($max); echo '</pre>';
    // echo "<hr>";
    // echo '<pre>$add_button_disabled: '; var_dump($add_button_disabled); echo '</pre>';
    // echo '<pre>$remove_button_disabled: '; var_dump($remove_button_disabled); echo '</pre>';

        $buttons = array(
            "add_button" => array(
                 "button_text" => $add_button_text, 
                 "disabled" => $add_button_disabled,
            ),
            "remove_button" => array(
                 "button_text" => $remove_button_text, 
                 "disabled" => $remove_button_disabled,
            ),
        );
    }

    return array(
        "inputs" => $inputs,
        "repeat_section" => array(
            "data_type" => $data_type,
            "repeat_section" => true,
            "count" => $min, 
            "form_id" => $form_id,
            "prefix" => $prefix, 
            "id" => $id,            
            "min" => $min,
            "max" => $max,
            "buttons" => $buttons,
            "input_arrays" => $input_arrays,
            "input_keys" => $input_keys,
        ),
    );
}