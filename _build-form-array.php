<?php 
//require_once WP_CONTENT_DIR . '/plugins/wp-swift-form-builder-2/_build-form-array.php';
function build_acf_form_array($form_data, $section=0) {
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

    if( get_sub_field('reporting') ) {
        $reporting_group = get_sub_field('reporting');
        $help = $reporting_group["help"];
        $instructions = $reporting_group["instructions"];
    }

    if( get_sub_field('settings') ) {
        $settings_group = get_sub_field('settings');
        $required = $settings_group["required"];
        $grouping = $settings_group["grouping"];
        if ($grouping == 'none') {
            $grouping = false;
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
    if($type==='date' || $type==='date_time' || $type==='date_range') {
        $type='text';
    }

    if( get_sub_field('placeholder') ) {
        $placeholder = get_sub_field('placeholder');
    }

    if( get_sub_field('select_options') ) {
        $select_options = get_sub_field('select_options');
        if ($data_type==='checkbox' || $data_type==='select') {
            foreach ($select_options as $key => $value) {
                $value['checked'] = false;
                if ( $value['option_value'] === '') {
                    $select_options[$key]['option_value'] = sanitize_title_with_dashes( $value['option'] );
                }
                if ($data_type==='checkbox') {
                    $select_options[$key]['checked'] = false;
                }               
            }
        }          
    }

    /*
     * Check for array key conflict and increment $id if found
     */
    if (array_key_exists('form-'.$id, $form_data)) {
        for ($i=1; $i < 20; $i++) { 
            if (!array_key_exists('form-'.$id.'-'.$i, $form_data)) {
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
            $form_data['form-'.$id] = array("passed"=>false, "clean"=>"", "value"=>"", "section"=>$section, "required"=>$required, "type"=>$type, "data_type"=>$data_type,  "placeholder"=>$placeholder, "label"=>$label, "help"=>$help, "instructions" => $instructions, "grouping" => $grouping);
            break;
        case "textarea":
            $form_data['form-'.$id] = array("passed"=>false, "clean"=>"", "value"=>"", "section"=>$section, "required"=>$required, "type"=>$type, "data_type"=>$data_type,  "placeholder"=>$placeholder, "label"=>$label, "help"=>$help, "instructions" => $instructions, "grouping" => $grouping);
            break; 
        case "select":
            $form_data['form-'.$id] = array("passed"=>false, "clean"=>"", "value"=>"", "section"=>$section, "required"=>$required, "type"=>$type, "data_type"=>$data_type,  "placeholder"=>$placeholder, "label"=>$label, "options"=>$select_options, "selected_option"=>"", "help"=>$help, "instructions" => $instructions, "grouping" => $grouping);
            break;
        case "multi_select":
        case "checkbox":
            $form_data['form-'.$id] = array("passed"=>false, "clean"=>"", "value"=>"", "section"=>$section, "required"=>$required, "type"=>$type, "data_type"=>$data_type,  "placeholder"=>$placeholder, "label"=>$label, "options"=>$select_options, "selected_option"=>"", "help"=>$help, "instructions" => $instructions, "grouping" => $grouping);
            break;    
        case "file":
            $enctype = 'enctype="multipart/form-data"';
            $form_class = 'js-check-form-file';
            $form_data['form-'.$id] = array("passed"=>false, "clean"=>"", "value"=>"", "section"=>$section, "required"=>$required, "type"=>$type, "data_type"=>$data_type,  "placeholder"=>$placeholder, "label"=>$label, "accept"=>"pdf", "help"=>$help, "instructions" => $instructions, "grouping" => $grouping);
            break;
        // case "input_combo":

        // 	$input_one =  get_sub_field('input_one');
        // 	$input_two =  get_sub_field('input_two');
        // 	$id_one = sanitize_title_with_dashes( $input_one );
        // 	$id_two = sanitize_title_with_dashes( $input_two );
        // 	$combo_input_type =  get_sub_field('combo_input_type');
        //     $form_data['form-'.$id.'-'.$id_one] = array("passed"=>false, "clean"=>"", "value"=>"", "section"=>$section, "required"=>$required, "type"=>$combo_input_type, "data_type"=>$data_type,  "placeholder"=>$placeholder, "label"=>$input_one, "help"=>$help, "instructions" => $instructions, "grouping" => $grouping, 'order'=>0, 'parent_label'=>$label);
        //     $form_data['form-'.$id.'-'.$id_two] = array("passed"=>false, "clean"=>"", "value"=>"", "section"=>$section, "required"=>$required, "type"=>$combo_input_type, "data_type"=>$data_type,  "placeholder"=>$placeholder, "label"=>$input_two, "help"=>$help, "instructions" => $instructions, "grouping" => $grouping, 'order'=>1);
        //     break;                
        case "date_range":
            $form_data['form-'.$id.'-start'] = array("passed"=>false, "clean"=>"", "value"=>"", "section"=>$section, "required"=>$required, "type"=>$type, "data_type"=>$data_type, "label"=>"Date From", "help"=>$help, "instructions" => $instructions, "grouping" => $grouping, 'order'=>0, 'parent_label'=>$label);
            $form_data['form-'.$id.'-end'] = array("passed"=>false, "clean"=>"", "value"=>"", "section"=>$section, "required"=>$required, "type"=>$type, "data_type"=>$data_type, "label"=>"Date To", "help"=>$help, "instructions" => $instructions, "grouping" => $grouping, 'order'=>1, 'parent_label'=>$label);
            break; 
        // case "section":
        // 	if (isset($form_data['section-count'])) {
        // 		$form_data['section-count']++;
        // 	}
        // 	else {
        // 		$form_data['section-count']=1;
        // 	}
        //     if( get_sub_field('section_header') ) {
        //         $section_header = get_sub_field('section_header');
        //     }
        //     else {
        //         $section_header='';
        //     }
        //     if( get_sub_field('section_content') ) {
        //         $section_content = get_sub_field('section_content');
        //     }
        //     else {
        //         $section_content='';
        //     }
        //     $form_data['form-'.$id] = array("passed"=>true, "section"=>$section, "section_header"=>$section_header, "section_content"=>$section_content, "type"=>$type, "data_type"=>$type);
        //     break;  
        // case "section_close":
        //     $form_data['form-'.$id] = array("passed"=>true, "section"=>$section, "type"=>$type, "data_type"=>$type);
        //     break;                                                    
    }
    return $form_data;       
}

function get_section($form_data) {
	if (isset($form_data['section-count'])) {
		return $form_data['section-count'];
	}
	else {
		return 0;
	}
}

// function get_page_inputs($page_id) {
// 	$form_data = array();
// 	if ( have_rows('form_inputs', $page_id) ) :

// 	    while( have_rows('form_inputs', $page_id) ) : the_row(); // Loop through the repeater for form inputs        
// 	         $form_data =  build_acf_form_array($form_data);  
// 	    endwhile;// End the AFC loop  

// 	endif;
// 	return $form_data;
// }