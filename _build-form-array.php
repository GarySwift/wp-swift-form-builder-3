<?php 
function build_acf_form_array($form_data) {
    global $post;
    $name =  get_sub_field('name');
    $id = sanitize_title_with_dashes( get_sub_field('name') );
    $type = get_sub_field('type');
    $data_type = get_sub_field('type');
    if($type==='date' || $type==='date_range' || $type==='input_combo') {
        $type='text';
    }
    $label = get_sub_field('label');
    $help = get_sub_field('help');
    $placeholder = get_sub_field('placeholder');
    $required = get_sub_field('required');
    $select_options='';
    $instructions = get_sub_field('instructions');

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

    if($required) {
        $required = 'required';
    }
    else {
        $required = '';
    }
    if(!$label) {
        $label = $name;
    }

    switch ($data_type) {           
        case "text":
        case "url":
        case "email":
        case "number":
            $form_data['form-'.$id] = array("passed"=>false, "clean"=>"", "value"=>"", "section"=>get_section($form_data), "required"=>$required, "type"=>$type, "data_type"=>$data_type,  "placeholder"=>$placeholder, "label"=>$label, "help"=>$help, "instructions" => $instructions);
            break;
        case "textarea":
            $form_data['form-'.$id] = array("passed"=>false, "clean"=>"", "value"=>"", "section"=>get_section($form_data), "required"=>$required, "type"=>$type, "data_type"=>$data_type,  "placeholder"=>$placeholder, "label"=>$label, "help"=>$help, "instructions" => $instructions);
            break; 
        case "select":
            $form_data['form-'.$id] = array("passed"=>false, "clean"=>"", "value"=>"", "section"=>get_section($form_data), "required"=>$required, "type"=>$type, "data_type"=>$data_type,  "placeholder"=>$placeholder, "label"=>$label, "options"=>$select_options, "selected_option"=>"", "help"=>$help, "instructions" => $instructions);
            break;
        case "multi_select":
        case "checkbox":
            $form_data['form-'.$id] = array("passed"=>false, "clean"=>"", "value"=>"", "section"=>get_section($form_data), "required"=>$required, "type"=>$type, "data_type"=>$data_type,  "placeholder"=>$placeholder, "label"=>$label, "options"=>$select_options, "selected_option"=>"", "help"=>$help, "instructions" => $instructions);
            break;    
        case "file":
            $enctype = 'enctype="multipart/form-data"';
            $form_class = 'js-check-form-file';
            $form_data['form-'.$id] = array("passed"=>false, "clean"=>"", "value"=>"", "section"=>get_section($form_data), "required"=>$required, "type"=>$type, "data_type"=>$data_type,  "placeholder"=>$placeholder, "label"=>$label, "accept"=>"pdf", "help"=>$help, "instructions" => $instructions);
            break;
        case "input_combo":

        	$input_one =  get_sub_field('input_one');
        	$input_two =  get_sub_field('input_two');
        	$id_one = sanitize_title_with_dashes( $input_one );
        	$id_two = sanitize_title_with_dashes( $input_two );
        	$combo_input_type =  get_sub_field('combo_input_type');
            $form_data['form-'.$id.'-'.$id_one] = array("passed"=>false, "clean"=>"", "value"=>"", "section"=>get_section($form_data), "required"=>$required, "type"=>$combo_input_type, "data_type"=>$data_type,  "placeholder"=>$placeholder, "label"=>$input_one, "help"=>$help, "instructions" => $instructions, 'order'=>0, 'parent_label'=>$label);
            $form_data['form-'.$id.'-'.$id_two] = array("passed"=>false, "clean"=>"", "value"=>"", "section"=>get_section($form_data), "required"=>$required, "type"=>$combo_input_type, "data_type"=>$data_type,  "placeholder"=>$placeholder, "label"=>$input_two, "help"=>$help, "instructions" => $instructions, 'order'=>1);
            break;                
        case "date_range":
            $form_data['form-'.$id.'-start'] = array("passed"=>false, "clean"=>"", "value"=>"", "section"=>get_section($form_data), "required"=>$required, "type"=>$type, "data_type"=>$data_type, "label"=>"Date From", "help"=>$help, "instructions" => $instructions, 'order'=>0, 'parent_label'=>$label);
            $form_data['form-'.$id.'-end'] = array("passed"=>false, "clean"=>"", "value"=>"", "section"=>get_section($form_data), "required"=>$required, "type"=>$type, "data_type"=>$data_type, "label"=>"Date To", "help"=>$help, "instructions" => $instructions, 'order'=>1, 'parent_label'=>$label);
            break; 
        case "section":
        	if (isset($form_data['section-count'])) {
        		$form_data['section-count']++;
        	}
        	else {
        		$form_data['section-count']=1;
        	}
            if( get_sub_field('section_header') ) {
                $section_header = get_sub_field('section_header');
            }
            else {
                $section_header='';
            }
            if( get_sub_field('section_content') ) {
                $section_content = get_sub_field('section_content');
            }
            else {
                $section_content='';
            }
            $form_data['form-'.$id] = array("passed"=>true, "section"=>get_section($form_data), "section_header"=>$section_header, "section_content"=>$section_content, "type"=>$type, "data_type"=>$type);
            break;  
        case "section_close":
            $form_data['form-'.$id] = array("passed"=>true, "section"=>get_section($form_data), "type"=>$type, "data_type"=>$type);
            break;                                                    
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