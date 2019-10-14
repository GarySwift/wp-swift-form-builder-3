<?php
/*
 * The main plugin class that will handle form validation and attachment processing.
 */
class WP_Swift_Form_Builder_Validate {
    // private $spam_killer;

    /*
     * Initializes the class.
     */
    public function __construct( ) {
        // $this->spam_killer = new WP_Swift_Form_Builder_Spam_Killer();
    }

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
                        //@todo: Pass $input_key so errors can be passed to client
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
         // write_log($input);write_log('');

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
        // write_log($input['data_type']);

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
                $input['clean'] = sanitize_user( $input['value'], $strict = true );
                $input['passed'] = true;
                break;
            case "email":
                if ( !is_email( $input['value'] ) ) { 
                    return $input; 
                }
                // $fake_email_domains = array('@mailinator.net');
                // foreach ( $fake_email_domains as $fake_email_domain) {
                //     if (strpos(strtolower($input['value']), $fake_email_domain) !== false) {
                //         $input["help"] = $input['value'] . ' looks fake or invalid, please enter a real email address.';
                //         write_log($input);
                //         return $input; 
                //     }                    
                // }
                $input['clean'] = sanitize_email( $input['value'] );
                $input['passed'] = true;
                break;
            case "number":
                if ( !is_numeric( $input['value'] ) ) { 
                    return $input; 
                }
                $input['clean'] = $input['value']; 
                $input['passed'] = true;
                break;        
            case "url":
                if (filter_var($input['value'], FILTER_VALIDATE_URL) === false) {
                    return $input;
                }
                $input['clean'] = $input['value'];
                $input['passed'] = true;
                break;
            case "select2":
            case "select":
                $input['selected_option'] = $input['value'];
                $input['clean'] = $input['value'];
                $input['passed'] = true;
                break;
            case "multi_select":
                // todo
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
            case "radio":
                $options = $input["options"];
                foreach ($options as $option_key => $option) {
                    $value = $input['value'];
                    if (in_array($value, $option)) {
                        $options[$option_key]["checked"] = true;
                        $clean = $option["option"];
                        $input['clean'] = $clean;
                        $input['passed'] = true;
                        break;
                    }
                }
                return $input;
                break;                                        
            case "checkbox_single":
                $input["option"]["checked"] = 1;
                $input['clean'] = "Yes";//"Yes <small>(".$input["option"]["key"].")</small>";
                $input['passed'] = true;
                break; 
            case "date":
                $date_format = get_form_builder_date_format();// 'dd/mm/yyyy' or 'mm/dd/yyyy'
                $date  = explode('/', $input['value']);// split string into array
                $y = $date[2];// year
                switch ($date_format) {
                    case 'dd/mm/yyyy':
                        $d = $date[0];// day
                        $m = $date[1];// month
                        $date_format_output = 'd M Y';
                        break;
                    case 'mm/dd/yyyy':
                        $m = $date[0];// month
                        $d = $date[1];// day
                        $date_format_output = 'M D Y';
                        break;
                }
                # bool checkdate ( int $month , int $day , int $year )
                if (!checkdate($m, $d, $y)) {
                    return $input;
                }
                else {
                    $input['clean'] = date($date_format_output, strtotime( $y.$m.$d ));
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
        // return $this->recaptcha_check($helper, $post);
        return  $helper->spam_killer->spam_prevention($helper, $post);
    }                  
}