<?php
/*
 * The main plugin class that will handle form validation and attachment processing.
 */
class WP_Swift_Form_Builder_Validate {
    public function validate_form($helper, $post, $ajax) {
        $form_data = $helper->get_form_data();

        $this->spam_prevention($helper, $post);
        if ( !empty($form_data)  ) {
            
            // The form is submitted by a user and so is no longer pristine
            $helper->set_form_pristine(false);
            // Check for repeating section 
            $j = 0;
            foreach ( $form_data as &$section ) {
                $j++;
                foreach ( $section["inputs"] as $input_key => &$input ) {
                    if ( $input["data_type"] === "repeat_section" ) {
                        if (isset($post[$input_key])) {
                            $input["count"] = (int) $post[$input_key];
                        }
                        if (isset( $post[$input["prefix"].$input["id"]] )) {
                            $count = (int) $post[$input["prefix"].$input["id"]];
                            for ($i = 1; $i <= $count; $i++) {
                                foreach ($input["input_keys"] as $key => $input_key) {
                                    $input_array = $input["input_arrays"][$input_key];
                                    $repeat_key = $input_key."-".$i;
                                    if (!isset($section["inputs"][$repeat_key])) {
                                        $section["inputs"][$repeat_key] = $input_array;
                                    }
                                }
                            }
                        }
                    }
                }
            }
            foreach ( $form_data as &$section ) {
                foreach ( $section["inputs"] as $input_key => &$input ) {

                    $set = isset($post[$input_key]);

                    if (isset($post[$input_key]) && $input['data_type'] !== "repeat_section") {
                        $input['value'] = $post[$input_key];
                        $input = $this->validate_input($input, $input_key);
                    }
                    elseif (isset($_FILES[$input_key])) {
                        $input = $this->validate_input($input, $input_key, $helper);
                    }
                    elseif(isset($post[$input_key."-hidden"])) {
                        $input['clean'] = 'No';
                        $input['passed'] = true;
                    }
                    else {
                        if ($input["data_type"] === "checkbox_single" && $input["required"] === "") {
                            $input['clean'] = 'No';
                            $input['passed'] = true;
                        }
                        elseif ( $input["data_type"] === "text" && !$input["required"] ) {
                            // Catch for unrequired (and disabled) text elements that are not sent in POST object
                            $input['passed'] = true;                   
                        }                    
                    }

                    
                    // if (isset($input['passed']) && !$input['passed'] && $input["type"] === "text" && !$input["required"] ) {
                    //     $input['passed'] = true;
                    // }
                    if (isset($input['passed']) && !$input['passed'] && !$input["required"] ) { //&& ($input["type"] === "text" || $input["type"] === "number" )  
                        $input['passed'] = true;
                    }

                    if (isset($input['passed']) && !$input['passed']) {
                        $helper->increase_error_count();
                        if ($input['help'] !== '') {
                            $helper->add_form_error_message( $input['help'] );
                        }
                        else {
                            $helper->add_form_error_message( $input['label'] . ' is invalid' );
                        }
                    } 
                }
            }
            $helper->set_form_data( $form_data );              
        }

        return $helper;     
    }

    /*
     * Check an individual form input field and sets the array with the findings 
     *
     * @param $input
     *
     * @return $input
     */
    public function validate_input($input, $key, $helper=null) {

        if ($input["data_type"] !== 'file') {
            if($input['required'] && $input['value']=='') {
                return $input;
            }
            elseif(!$input['required'] && $input['value']=='') {
                $input['passed'] = true;
                return $input;
            }            
        }

        if(!is_array($input['value'])) {
            $input['value'] = trim($input['value']);
        }

        /**
         * Special validation
         *
         * alphabetic, alphanumeric, numeric, uppercase_alphabetic, uppercase_alphanumeric
         */
        if ( isset($input["validation"]) && is_array($input["validation"])) {
            $length = strlen($input['value']);
            if (isset( $input["validation"]["min"])) {
                $min = (int) $input["validation"]["min"];
                if ( $length < $min ) {
                    // $input["help"] = "This must be at least $min characters";
                    return $input;
                }
            }
            if (isset( $input["validation"]["max"])) {
                $max = (int) $input["validation"]["max"];
                if ( $length > $max ) {
                    // $input["help"] = "This cannot be more than $max characters";
                    return $input;
                }
            } 
            if (isset( $input["validation"]["validation"])) {
                $validation = $input["validation"]["validation"];
                switch ($validation) {
                    case 'alphabetic':// Alphabetic
                        preg_match('/^[a-zA-Z]+$/', $input['value'], $matches, PREG_OFFSET_CAPTURE);
                        if (count($matches) == 0) return $input;
                        break;
                    case 'alphanumeric':// Alphanumeric
                        preg_match('/^[0-9a-zA-Z]+$/', $input['value'], $matches, PREG_OFFSET_CAPTURE);
                        if (count($matches) == 0) return $input;
                        break;   
                    case 'numeric': // Numeric
                        if ( !ctype_digit($input['value']) ) return $input;
                        break;
                    case 'uppercase_alphabetic':// Uppercase Alphabetic
                        preg_match('/^[A-Z]+$/', $input['value'], $matches, PREG_OFFSET_CAPTURE);
                        if (count($matches) == 0) return $input;
                        break;     
                    case 'uppercase_alphanumeric':// Uppercase Alphanumeric
                        preg_match('/^[0-9A-Z]+$/', $input['value'], $matches, PREG_OFFSET_CAPTURE);
                        if (count($matches) == 0) return $input;
                        break;                                                      
                }                
            }
        }

        /**
         * Default validation based on input type
         */
        switch ($input['data_type']) {
            case "text":
            case "textarea":
                $input['clean'] = sanitize_text_field( $input['value'] );
                $input['passed'] = true;
                break;
            case "username":
                $username_strlen = strlen ( $input['value']  );
                if ($username_strlen<4 || $username_strlen>30) {
                    return $input;
                }
                $input['clean'] = sanitize_user( $input['value'], $strict=true ); 
                $input['passed'] = true;
                break;
            case "email":
                if ( !is_email( $input['value'] ) ) { 
                    return $input; 
                }
                else {
                    $input['clean'] = sanitize_email( $input['value'] );  
                }
                $input['passed'] = true;
                break;
            case "number":
                if ( !is_numeric( $input['value'] ) ) { 
                    return $input; 
                }
                else {
                    $input['clean'] = $input['value'];  
                }
                $input['passed'] = true;
                break;        
            case "url":
                if (filter_var($input['value'], FILTER_VALIDATE_URL) === false) {
                    return $input;
                }
                else {
                    $input['clean'] = $input['value'];
                }
                break;
            case "select2":
            case "select":
                $input['selected_option'] = $input['value'];
                $input['clean'] = $input['value'];
                $input['passed'] = true;
                break;
            case "file": 
                $input = $this->process_file($_FILES, $input, $key, $helper);  
                break; 
            case "hidden":
                if (isset($input['nonce'])) {
                    $retrieved_nonce = $value;
                    if (!wp_verify_nonce($retrieved_nonce, 'search_nonce' ) ) {
                        die( 'Failed security check' );
                        return $input;  
                    }
                }
                if (isset($input['expected'])) {
                    if ($input['expected'] != $value ) {
                        return $input;  
                    }
                }
                $input['passed'] = true;             
                break; 
            case "password":
                    break; 
            case "checkbox":
                $options = $input["options"];
                $clean = '';
                foreach ($options as $option_key => $option) {
                    if ( in_array($option["option_value"], $input['value'])) {
                        $options[$option_key]["checked"] = true;
                        $clean .= $option["option"].', ';
                    }
                }
                $clean = rtrim( $clean, ', ');
                $input["options"] = $options;
                $input['clean'] = $clean;
                $input['passed'] = true;
                break;                        
            case "checkbox_single":
                $input["option"]["checked"] = 1;
                $input['clean'] = "Yes";//"Yes <small>(".$input["option"]["key"].")</small>";
                $input['passed'] = true;
                break; 
            case "date":
                // $d = DateTime::createFromFormat($format, $input['value']);  
                $date  = explode('/', $input['value']);
                $d = $date[0];
                $m = $date[1];
                $y = $date[2];
                # bool checkdate ( int $month , int $day , int $year )
                if (!checkdate($m, $d, $y)) {
                    return $input;
                }
                else {
                    $input['clean'] = $y.$m.$d;//date('Ymd', strtotime( $y.$m.$d ));
                    //$date[1] . ' ' . $date[0] . ' ' . $date[2] //$input['value']
                }
                $input['passed'] = true;
                break;

  
        }
        return $input;   
    }//@end validate_input 

    private function process_file($files, $input, $key, $helper) {
        $attachments = array();
        $uploads_path = ABSPATH.$input["save_location"];
        $uploads_path_exists = false;

        // Create folder if none exists
        if(!is_dir($uploads_path))
            $uploads_path_exists = mkdir($uploads_path, 0700);
        else
            $uploads_path_exists = true;

        // Move file from tmp location to the preferred uploads location
        if($uploads_path_exists) {
            $old_name = $files[$key]["name"];
            $time = time();
            $new_name = $time . '_' . $old_name;
            $new_name_with_path = $uploads_path. "/" . $new_name;
            if(move_uploaded_file( $files[$key]["tmp_name"], $new_name_with_path )) {
                $form_data[$key]['passed'] = true;
                $attach_file=true;
                $input["clean"] = $old_name;
                $input["value"] = $new_name_with_path;
                $input['passed'] = true;
                $helper->add_attachment( $new_name_with_path );
            }
        }
        return $input;
    }

    public function spam_prevention($helper, $post) {
        return $this->recaptcha_check($helper, $post);
    }

    public function recaptcha_check($helper, $post) {
        $response = array(
            'status' => false,
            'msg' => '',
        );
        if ( !$helper->recaptcha_secret() ){
            // recaptcha is not set so skip the check
            return true;
        }
        elseif ( $helper->recaptcha_secret() && $post["g-recaptcha-response"] ){

            $g_response = $post["g-recaptcha-response"];

            $url = 'https://www.google.com/recaptcha/api/siteverify';
            $post_data = "secret=".$helper->recaptcha_secret()."&response=".$g_response."&remoteip=".$_SERVER['REMOTE_ADDR'] ;
            $ch = curl_init();  
            curl_setopt($ch, CURLOPT_URL, $url);
            curl_setopt($ch, CURLOPT_POST, true);
            curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
            curl_setopt($ch, CURLOPT_HTTPHEADER, array('Content-Type: application/x-www-form-urlencoded; charset=utf-8', 'Content-Length: ' . strlen($post_data)));
            curl_setopt($ch, CURLOPT_POSTFIELDS, $post_data); 
            $googresp = curl_exec($ch);       
            $decgoogresp = json_decode($googresp);
            curl_close($ch);

            if ( $decgoogresp->success === false ) {
                $helper->increase_error_count();
                $helper->add_form_error_message("You are a bot! Go away!");    
                return false;         
            } 
            elseif ( $decgoogresp->success === true ) {
                return true;     
            }
        }
        elseif ( $helper->recaptcha_secret() ){
            $helper->increase_error_count();
            $helper->add_form_error_message("This form is expecting a recaptcha code to validate but none was found!");               
            return false;
        }    
    }                  
}