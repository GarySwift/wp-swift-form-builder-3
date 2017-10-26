<?php 
//require_once WP_CONTENT_DIR . '/plugins/wp-swift-form-builder-2/_build-form-array.php';
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
        else {
            if (!isset($settings["groupings"])) {
                $settings["groupings"] = true;
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
    if($type==='date' || $type==='date_time' || $type==='date_range') {
        $type='text';
    }

    if( get_sub_field('placeholder') ) {
        $placeholder = get_sub_field('placeholder');
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
            $inputs[$prefix.$id] = array("passed"=>false, "clean"=>"", "value"=>"", "section"=>$section, "required"=>$required, "type"=>$type, "data_type"=>$data_type,  "placeholder"=>$placeholder, "label"=>$label, "help"=>$help, "instructions" => $instructions, "grouping" => $grouping);
            break;
        case "textarea":
            $inputs[$prefix.$id] = array("passed"=>false, "clean"=>"", "value"=>"", "section"=>$section, "required"=>$required, "type"=>$type, "data_type"=>$data_type,  "placeholder"=>$placeholder, "label"=>$label, "help"=>$help, "instructions" => $instructions, "grouping" => $grouping);
            break; 
        case "select":
        case "multi_select":
        case "checkbox":
        case "radio":
            $inputs[$prefix.$id] = array("passed"=>false, "clean"=>"", "value"=>"", "section"=>$section, "required"=>$required, "type"=>$type, "data_type"=>$data_type, "label"=>$label, "options"=>$select_options, "selected_option"=>"", "help"=>$help, "instructions" => $instructions, "grouping" => $grouping);//,  "placeholder"=>$placeholder
            break;    
        case "file":
            $enctype = 'enctype="multipart/form-data"';
            $form_class = 'js-check-form-file';
            $inputs[$prefix.$id] = array("passed"=>false, "clean"=>"", "value"=>"", "section"=>$section, "required"=>$required, "type"=>$type, "data_type"=>$data_type,  "placeholder"=>$placeholder, "label"=>$label, "accept"=>"pdf", "help"=>$help, "instructions" => $instructions, "grouping" => $grouping);
            break;              
        case "date_range":
            $inputs[$prefix.$id.'-start'] = array("passed"=>false, "clean"=>"", "value"=>"", "section"=>$section, "required"=>$required, "type"=>$type, "data_type"=>$data_type, "label"=>"Date From", "help"=>$help, "instructions" => $instructions, "grouping" => $grouping, 'order'=>0, 'parent_label'=>$label);
            $inputs[$prefix.$id.'-end'] = array("passed"=>false, "clean"=>"", "value"=>"", "section"=>$section, "required"=>$required, "type"=>$type, "data_type"=>$data_type, "label"=>"Date To", "help"=>$help, "instructions" => $instructions, "grouping" => $grouping, 'order'=>1, 'parent_label'=>$label);
            break;                                               
    }
    return array("inputs" => $inputs, "settings" => $settings);       
}

function wp_swift_get_form_data($id) {

    // return wp_swift_form_data_loop($id);
    $upload_dir = wp_upload_dir();
    $file_path = $upload_dir["basedir"].FORM_BUILDER_DIR;
    $file_name = 'form-builder-'.$id.'.json';
    $file = $file_path.$file_name;
    // echo $file;
    if (file_exists($file)) {
        return json_decode(file_get_contents($file), true);
        // return json_decode(json_encode(file_get_contents($file)), true);
    }
    else {
        return wp_swift_form_data_loop($id);
        // return json_decode(json_encode((object) wp_swift_form_data_loop($id)), FALSE);
        // return json_decode( wp_swift_form_data_loop($id), true);
    }
    // file_put_contents($file, $form_data_json);
    // write_log ( "post_type is ".$post_type );
    // write_log ( "post_id is ".$post_id );
    // write_log ( $form_data );
    // write_log( $form_data_json);
    // write_log( wp_upload_dir() );
    // write_log( $file);    

    // $file_get_contents = file_get_contents($file);
    // write_log( json_decode($file_get_contents)); 

}
class Form_Data {
    public $settings = array();
    public $sections = array();
} 
function wp_swift_form_data_loop($id) {





    $form_data = array();//new Form_Data;//
    $settings = array();
    $sections = array();
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
            // $form_data->sections = (object) $sections;
            // $form_data->settings = (object) $settings;
        }

    endif;
    return $form_data; 
}  
// function get_section($form_data) {
// 	if (isset($form_data['section-count'])) {
// 		return $form_data['section-count'];
// 	}
// 	else {
// 		return 0;
// 	}
// }

// function get_page_inputs($page_id) {
// 	$form_data = array();
// 	if ( have_rows('form_inputs', $page_id) ) :

// 	    while( have_rows('form_inputs', $page_id) ) : the_row(); // Loop through the repeater for form inputs        
// 	         $form_data =  build_acf_form_array($form_data);  
// 	    endwhile;// End the AFC loop  

// 	endif;
// 	return $form_data;
// }