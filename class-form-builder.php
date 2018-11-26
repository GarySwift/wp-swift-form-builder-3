<?php
/*
 * Include the WordPress Admin API interface settings for this plugin.
 * This will declare all menu pages, tabs and inputs etc but it does not
 * handle any business logic related to form functionality.
 */
// require_once 'form-builder-wordpress-admin-interface.php';
// require_once 'email-templates/wp-swift-email-templates.php';
/*
 * The main plugin class that will handle business logic related to form 
 * functionality.
 */
class WP_Swift_Form_Builder_Parent {
    private $action='';
    private $form_data = null;
    // private $post_id = null;
    private $form_css_id = '';
    private $form_post_id = '';
    private $post_id = null;
    private $form_name = '';
    private $submit_button_id = '';
    private $submit_button_name = '';
    private $submit_button_text = '';
    private $css_framework = "zurb_foundation";
// private $show_mail_receipt = true;
    private $form_pristine = true;
    private $enctype = '';
    private $error_count = 0;
    private $tab_index = 100;
    // private $extra_error_msgs = array();
    // private $extra_msgs = array();
    private $list_form_errors_in_warning_panel = true;
    private $clear_after_submission = true;
    // private $Section_Layout_Addon = null;
    // private $default_input_keys_to_skip = array('submit-request-form', 'mail-receipt', 'form-file-upload', 'g-recaptcha-response');
    private $form_class ='form-builder';
    // private $success_msg = '';
    // private $option = '';

    private $form_error_messages = array();
    private $user_confirmation_email = "ask";
    private $show_edit_link = false;
    private $hidden = null;

    // private $form_action;
    private $form_type;

    private $gdpr_settings = null;
    private $recaptcha = null;
    /*
        function guide
        acf_build_form()


    */

    /*
     * Initializes the plugin.
     */
    public function __construct($form_id, $post_id, $hidden = array(), $type = 'request', $args = array(), $_post = null ) {// , $sections, $settings = false  //"option") {
        $form_data = wp_swift_get_form_data($form_id, $_post);
        // write_log(array('$_post', $_post));
        $this->post_id = $post_id;

        $this->form_type = $type;
        // echo '<pre>$this->form_action: '; var_dump($this->form_action); echo '</pre>';

        if (count($hidden)) {
            $this->hidden = $hidden;
        }

        // echo "<pre>"; var_dump($form_data); echo "</pre>";
        $this->form_post_id = $form_id;

        if( get_field('spam_prevention_type', $this->form_post_id ) ) {
            $spam_prevention_type = get_field('spam_prevention_type', $this->form_post_id );
            if ($spam_prevention_type === 'google') {

                $options = get_option( 'wp_swift_form_builder_settings' );
                $google_settings = $options['wp_swift_form_builder_google_recaptcha'];
                if ( $google_settings["site_key"] !== '' && $google_settings["secret_key"] !== '' ) {
                    $this->recaptcha = $google_settings;
                }
                if( get_field('recaptcha_settings', $this->form_post_id) ) {
                    $recaptcha_settings = get_field('recaptcha_settings', $this->form_post_id);
                    $this->recaptcha = array_merge( $this->recaptcha, $recaptcha_settings );
                }
                if( get_field('recaptcha_display_settings', $this->form_post_id) ) {
                    $recaptcha_display_settings = get_field('recaptcha_display_settings', $this->form_post_id);
                    $this->recaptcha = array_merge( $this->recaptcha, $recaptcha_display_settings );
                }                
            }
        }
        if( get_field('gdpr', $this->form_post_id ) ) {
            $this->gdpr_settings = get_field('gdpr_settings', $this->form_post_id);
        }        
        if (isset($form_data["sections"])) {
             $this->form_data = $form_data["sections"];
            // echo '<pre>1 $this->form_data: '; var_dump($this->form_data); echo '</pre>';
            // echo "<hr>";echo "<hr>";echo "<hr>";echo "<hr>";echo "<hr>";
            // $test = $this->increment_form_data( 2 );
            // echo '<pre>2 $this->form_data: '; var_dump($this->form_data); echo '</pre>';
        }
        if (isset($form_data["settings"])) {
            $settings = $form_data["settings"];
        }
        if(isset($args["form_name"])) {
            $form_name = sanitize_title_with_dashes($args["form_name"]);
            $this->form_name = $form_name;
        }
        else {
            $this->form_name = "request-form";
        }

        if (isset($args["form_id"])) {
            $this->form_css_id = $args["form_id"];
        }
        else {
            $this->form_css_id = $this->form_name;
        }
        
        if(isset($args["submit_button_name"])) {
            $this->submit_button_name = $args["submit_button_name"];
        }
        else {
            $this->submit_button_name = "submit-".$this->form_name;
        }
        // echo '<pre>$this->submit_button_name: '; var_dump($this->submit_button_name); echo '</pre>';

        if(isset($args["submit_button_id"])) {
            $this->submit_button_id = $args["submit_button_id"];
        }
        else {
            $this->submit_button_id = $this->submit_button_name;
        }

    if(isset($args["submit_button_text"])) {
        $this->submit_button_text = $args["submit_button_text"];
    }
    elseif(isset($settings["submit_button_text"])) {
        $this->submit_button_text = $settings["submit_button_text"];
    }  
    else {
        $this->submit_button_text = "Submit Form";
    }
    // echo '<pre>$this->submit_button_text: '; var_dump($this->submit_button_text); echo '</pre>';

        if (isset($args["clear_after_submission"])) {
            $this->clear_after_submission = $args["clear_after_submission"];
            $this->form_class .= ' js-do-not-clear';
        }

        if (isset($args["action"]) && $args["action"]!='') {
            $this->action = 'action="'.$args["action"].'"';
        }

        if (isset($args["list_form_errors_in_warning_panel"])) {
            $this->list_form_errors_in_warning_panel = $args["list_form_errors_in_warning_panel"];
        }

        if (isset($args["form_class"])) {
            $this->form_class .= $args["form_class"];
        }
        if (isset($settings["groupings"])) {
            $this->form_class .= " groupings";
        } 
        if (isset($settings["form_css_class"])) {
            $this->form_class .= $settings["form_css_class"];
        } 
        if(isset($settings["user_confirmation_email"])) {
            $this->user_confirmation_email = $settings["user_confirmation_email"];
        }
        if(isset($settings["show_edit_link"])) {
            $this->show_edit_link = true;
        }

        // else {
        //     $this->user_confirmation_email = "ask";
        // }                   
    }

    public function run() {
        if ($this->form_data) {
            return $this->get_form();
        }
        // else {
        //     return "<pre>Form not found</pre>";
        // }
    }

    /**
     * Form Processing
     */
    public function get_response($post) {
        $form_set = false;
        $html = $this::process_form($post, true);
        if ($this::get_form_data()) {
           $form_set = true;
        }
        if ($this->clear_after_submission) {
            
        }
        // write_log(array('$this->clear_after_submission', $this->clear_after_submission));
        $form_set = false;
        // $form_set = $this->clear_after_submission;
        $response = array(
            "form_set" => $form_set,
            "error_count" => $this::get_error_count(),
            "html" => $html,
        ); 
        if ($this->clear_after_submission) {
            $response["form_clear"] = 0;
        }        
        return $response;      
    }

    public function get_form() {
        ob_start();
        // if( isset( $_POST[$this->get_submit_button_name()] ) ) { //check if form was submitted
        //     $process_form = $this->process_form($_POST); 
        //     if (isset($process_form["html"])) {
        //         $html_response = $process_form["html"];
        //     }
        //     else {
        //         $html_response = $process_form;
        //     }     
        // }
        $this->front_end_form( $this->get_form_response() );
   
        $html = ob_get_contents();
        ob_end_clean();
        return $html; 
    }

    public function get_form_response() {
        $html_response = '';
        if( isset( $_POST[$this->get_submit_button_name()] ) ) { //check if form was submitted
            // echo '<pre>$_POST: '; var_dump($_POST); echo '</pre>';
            $process_form = $this->process_form($_POST); 
            if (isset($process_form["html"])) {
                $html_response = $process_form["html"];
            }
            else {
                $html_response = $process_form;
            }     
        }  
        return $html_response;      
    }

    /**
     * @function  set_form_data
     * Set the form data
     *
     * @param       $form_post_id   int     the id of the form CPT
     * @param       $form_inputs    array   the array of inputs
     * @param       $args           array   additional arguments
     */
    // public function set_form_data($form_post_id, $form_inputs=array(), $args=false) {
   
    // }
    //@end set_form_data()

  /*
     * Build the form
     */
    public function acf_build_form($submit_form_failure=true) {
        // if ($submit_form_failure) {
        //    $this->submit_form_failure();
        // }
        $this->front_end_form();
    }//@end acf_build_form()

    public function submit_form_success($post, $ajax) {

    }



    public function front_end_form( $html_response = null, $msg = null  ) {
        $framework='';
        $options = get_option( 'wp_swift_form_builder_settings' );
        if (isset($options['wp_swift_form_builder_select_css_framework'])) {
            $framework = $options['wp_swift_form_builder_select_css_framework'];
        }
        ?>
        <div class="<?php echo $this->get_form_class(); ?>"><!-- @start form-wrapper -->

            <?php if ($html_response): ?>
                <?php echo $html_response; ?>
            <?php endif ?>

            <?php if ($msg): ?>
                <?php echo $msg; ?>
            <?php endif ?>
            
            <!-- @start form -->
            <form method="post" <?php echo $this->action; ?> name="<?php echo $this->form_name; ?>" id="<?php echo $this->form_css_id; ?>" data-id="<?php echo $this->form_post_id ?>" data-post-id="<?php echo $this->post_id ?>" data-type="<?php echo $this->form_type ?>"<?php $this->form_data_types() ?> class="<?php echo $framework.' '; echo $this->get_form_class().' '; echo $this->form_name ?>" novalidate<?php echo $this->enctype;?>>

                <?php if ( isset($this->hidden) && count($this->hidden)):
                // echo "<pre>hidden: "; var_dump($this->hidden); echo "</pre>";
                    foreach ($this->hidden as $key => $hidden): ?>
                        <input type="hidden" data-type="hidden" id="<?php echo $key ?>" name="<?php echo $key ?>" value="<?php echo $hidden ?>">
                    <?php endforeach;
                endif;
                
                $this->front_end_form_input_loop();//$this->form_data, $this->tab_index, $this->form_pristine, $this->error_count
                // $this->before_submit_button_hook(); 
                // add_action( "wp_swift_formbuilder_before_submit_button_hook", $function_to_add, 10, 1 );
                do_action( 'wp_swift_formbuilder_before_submit_button_hook' );
                $this->gdpr_html(); ?>
                <div id="form-submission-wrapper"><?php 
                    $this->recaptcha_html(); ?>
                    <div id="form-submission"><?php 
                        $this->mail_receipt_html();
                        $this->button_html();
                    ?></div>
                </div><?php
                $this->gdpr_disclaimer();
                ?>
            </form><!-- @end form -->
        </div><!-- @end form-wrapper -->
        <?php 
        if ( $this->show_edit_link === true ) {
            edit_post_link( __( '(Edit Form)', 'wp-swift-form-builder' ), '<div class="edit-link">', '</div>', $this->form_post_id );
        }
        // edit_post_link( '(Edit Form)', '<p>', '</p>',$this->get_form_post_id() );           
    }// front_end_form()

    public function front_end_form_input_loop( $tab_index = false, $increment = false ) {
        if ( $tab_index ) {
            $this->tab_index = $tab_index;
        }

        // echo '<pre>$this->form_data: '; var_dump($this->form_data); echo '</pre>';

        foreach ($this->form_data as $key => $section) {

            $this->open_section_html( $section, $key );            

            foreach ($section["inputs"] as $id => $input) {

                if (isset($input['data_type'])) {
                    switch ($input['data_type']) {            
                        case "text":
                        case "url":
                        case "email":
                        case "number":
                        case "username":
                        case "password":
                        case "date":
                        case "date_range":
                            $input_html = $this->build_form_input($id, $input);
                            echo $this->wrap_input($id, $input, $input_html);
                            break;
                        case "textarea":
                            $input_html = $this->build_form_textarea($id, $input);
                            echo $this->wrap_input($id, $input, $input_html);
                            break; 
                        case "radio":
                            $input_html = $this->build_form_radio($id, $input);
                            echo $this->wrap_input($id, $input, $input_html);
                            break; 
                        case "checkbox":
                            $input_html = $this->build_form_checkbox($id, $input);
                            echo $this->wrap_input($id, $input, $input_html);
                            break; 
                        case "checkbox_single":
                            $input_html = $this->build_form_checkbox_single($id, $input);
                            echo $this->wrap_input($id, $input, $input_html);
                            break;               
                        case "multi_select":
                        case "select":
                            $input_html = $this->build_form_select($id, $input);
                            echo $this->wrap_input($id, $input, $input_html);
                            break; 
                        case "repeat_section":
                            echo $this->build_form_repeat_section($id, $input);
                            break;                                                                                    
                    }  
                }
                     
            }

            $this->close_section_html( $key );

        }
        return $this->tab_index;
    }

    public function process_form($post, $ajax=false) {

        if ( $this->recaptcha_check($post) && $this->get_form_data() ) {
            $this->validate_form($post);
            if ( $this->get_error_count() === 0 ) {
                return $this->submit_form_success($post, $ajax);
            }
            else {
                return $this->submit_form_failure($ajax);
            }
        }
        else {
            return $this->form_failure($ajax);
        }        
    }
   
/*
 *
 */
    public function validate_form($post) {
        // echo "<pre>post: "; var_dump($post); echo "</pre>";echo "<hr>";
        // The form is submitted by a user and so is no longer pristine
        $this->set_form_pristine(false);
        // check for repeat 
        // echo '<pre>$this->form_data: '; var_dump($this->form_data); echo '</pre>';echo "<hr>";
        $j = 0;
        foreach ( $this->form_data as &$section ) {
            $j++;
            // echo '<pre>$j: '; var_dump($j); echo '</pre>';
            foreach ( $section["inputs"] as $input_key => &$input ) {
                // echo '<pre>$input["data_type"]: '; var_dump($input["data_type"]); echo '</pre>';
                if ( $input["data_type"] === "repeat_section" ) {
                    if (isset($post[$input_key])) {
                        // echo '<pre>$post[$input_key]: '; var_dump($post[$input_key]); echo '</pre>';echo "<hr>";
                        // echo '<pre>$input: '; var_dump($input); echo '</pre>';
                        $input["count"] = (int) $post[$input_key];
                    }
                    // echo "<hr>";
                    // echo '<pre>$input: '; var_dump($input); echo '</pre>';
                    if (isset( $post[$input["prefix"].$input["id"]] )) {
                        $count = (int) $post[$input["prefix"].$input["id"]];

                        // echo "<hr>";
                        // echo '<pre>$post[$input["prefix"].$input["id"]]: '; var_dump($post[$input["prefix"].$input["id"]]); echo '</pre>';
                        // echo "<br>";
                        // echo '<pre>$section: '; var_dump($section); echo '</pre>';
                        // echo "<br>";
                        for ($i = 1; $i <= $count; $i++) {
                            foreach ($input["input_keys"] as $key => $input_key) {
                                $input_array = $input["input_arrays"][$input_key];

                                // echo '<pre>$input_array: '; var_dump($input_array); echo '</pre>';
                                $repeat_key = $input_key."-".$i;
                                // echo '<pre>$array: '; var_dump($array); echo '</pre>';
                                // echo "<pre>$repeat_key</pre>";
                                if (!isset($section["inputs"][$repeat_key])) {
                                    $section["inputs"][$repeat_key] = $input_array;
                                }
                            }
                        }
                            // foreach ($input["input_keys"] as $key => $input_key) {
                                
                            // }
                        // echo "<hr>";
                    }
                }
            }
        }
         // echo '<pre>$this->form_data: '; var_dump($this->form_data); echo '</pre>';echo "<hr>";
        foreach ( $this->form_data as &$section ) {
            foreach ( $section["inputs"] as $input_key => &$input ) {
                // write_log($input);
                // echo "<pre>input_key: $input_key</pre>";
                $set = isset($post[$input_key]);
                // echo '<pre>isset($post[$input_key]: '; var_dump($set); echo '</pre>';
// echo '<pre>$input: '; var_dump($input); echo '</pre>';
                // recaptcha_secret()($input['label'] .' '. $input["required"]);
                if (isset($post[$input_key]) && $input['data_type'] !== "repeat_section") {
                    // echo '<pre>$input_key: '; var_dump($input_key); echo '</pre>';

                    $input['value'] = $post[$input_key];
                    $input = $this->validate_input($input);
                    // echo "<hr>";
                }
                elseif(isset($post[$input_key."-hidden"])) {
                    $input['clean'] = 'No';
                    $input['passed'] = true;
                }
                else {
                    // echo "<pre>input: "; var_dump($input); echo "</pre>";
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
                // echo "<pre>"; echo $input["data_type"]." - ".$input["required"];echo "</pre>";

                // echo "<pre>";  echo "</pre>";
                if (isset($input['passed']) && !$input['passed']) {
                    // echo '<pre>$input: '; var_dump($input["data_type"]); echo '</pre>';
                    $this->increase_error_count();
                    if ($input['help'] !== '') {
                        // echo '<pre>$input: '; var_dump($input); echo '</pre>';
                        $this->form_error_messages[] = $input['help'];
                    }
                    else {

                        $this->form_error_messages[] = $input['label'] . ' is invalid';
                    }
                    // recaptcha_secret()($input['label'] . ' is invalid');
                } 
            }
        }  

        // $this->increase_error_count();
        // $this->form_error_messages[] = "Debugging!";           
    }

    /*
     * Check an individual form input field and sets the array with the findings 
     *
     * @param $input
     *
     * @return $input
     */
    public function validate_input($input) {
        // echo '<pre>$input: '; var_dump($input); echo '</pre>';echo "<hr>";
     
        if($input['required'] && $input['value']=='') {
            return $input;
        }
        elseif(!$input['required'] && $input['value']=='') {
            $input['passed'] = true;
            return $input;
        }

        if(!is_array($input['value'])) {
            $input['value'] = trim($input['value']);
        }

        if ( isset($input["validation"]) && is_array($input["validation"])) {
            $length = strlen($input['value']);
            // echo '<pre>$length: '; var_dump($length); echo '</pre>';
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
                // switch ($validation) {
                //     case "uppercase_alphanumeric":
                //         preg_match('/^[0-9A-Z]+$/', $input['value'], $matches, PREG_OFFSET_CAPTURE);
                //         if (count($matches) == 0) return $input;
                //         break;
                //     case "uppercase_alphanumeric":
                //         preg_match('/^[0-9A-Z]+$/', $input['value'], $matches, PREG_OFFSET_CAPTURE);
                //         if (count($matches) == 0) return $input;
                //         break;

                // }
                switch ($validation) {
                    case 'alphabetic':// Alphabetic
                        preg_match('/^[a-zA-Z]+$/', $input['value'], $matches, PREG_OFFSET_CAPTURE);
                        if (count($matches) == 0) return $input;
                    case 'alphanumeric':// Alphanumeric
                        preg_match('/^[0-9a-zA-Z]+$/', $input['value'], $matches, PREG_OFFSET_CAPTURE);
                        if (count($matches) == 0) return $input;   
                    case 'numeric': // Numeric
                        if ( !ctype_digit($input['value']) ) return $input;
                    case 'uppercase_alphabetic':// Uppercase Alphabetic
                        preg_match('/^[A-Z]+$/', $input['value'], $matches, PREG_OFFSET_CAPTURE);
                        if (count($matches) == 0) return $input;     
                    case 'uppercase_alphanumeric':// Uppercase Alphanumeric
                        preg_match('/^[0-9A-Z]+$/', $input['value'], $matches, PREG_OFFSET_CAPTURE);
                        if (count($matches) == 0) return $input;                                                      
                }                
            }
            // echo '<pre>$min: '; var_dump($min); echo '</pre>'; 
            // echo '<pre>$max: '; var_dump($max); echo '</pre>';          
            // echo '<pre>$input["validation"]: '; var_dump($input["validation"]); echo '</pre>';echo "<hr>";
        }
        // echo '<pre>$input["data_type"]: '; var_dump($input['data_type']); echo '</pre>';
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
                // echo '<pre>1 $input: '; var_dump($input); echo '</pre>';echo "<hr>";
                $input['selected_option'] = $input['value'];
                $input['clean'] = $input['value'];
                $input['passed'] = true;
                // return $input;
                 // echo '<pre>2 $input: '; var_dump($input); echo '</pre>';echo "<hr>";echo "<hr>";
                break;
            case "file":     
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
                // echo "<pre>options: "; var_dump($options); echo "</pre>";
                // recaptcha_secret()('options');
                // recaptcha_secret()($options);
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
                // 
                // $d = DateTime::createFromFormat($format, $input['value']);  
                // echo '<pre>$input[value]: '; var_dump($input['value']); echo '</pre>';    
                $date  = explode('/', $input['value']);
                $d = $date[0];
                $m = $date[1];
                $y = $date[2];
                // echo '<pre>$date: '; var_dump($date); echo '</pre>';
                # bool checkdate ( int $month , int $day , int $year )
                if (!checkdate($m, $d, $y)) {
                    return $input;
                }
                else {
                    $input['clean'] = $y.$m.$d;//date('Ymd', strtotime( $y.$m.$d ));
                    //$date[1] . ' ' . $date[0] . ' ' . $date[2] //$input['value']
                    // echo '<pre>$input[clean]: '; var_dump($input['clean']); echo '</pre>';
                }
                $input['passed'] = true;
                break;

  
        }
        // $input['passed'] = true;
        return $input;   
    }//@end validate_input 

    /*
     * Build form message
     */
    public function submit_form_failure($ajax) {
        ob_start();

        ?>

        <!-- @start #form-error-message -->
        <div id="form-error-message" class="form-message error<?php echo $ajax ? ' ajax':''; ?>">

            <h3 class="heading">Errors Found</h3>

            <div class="error-content">

                <p>We're sorry, there has been an error with the form input. Please rectify the <?php 
                    echo $this->get_error_count() === 1 ? ' error' : $this->get_error_count().' errors'; ?> below and resubmit.</p>
                <?php if ($this->list_form_errors_in_warning_panel) : ?>

                <ul>
                    <?php foreach ($this->form_error_messages as $message) : ?>

                    <li><?php echo $message; ?></li>    
                    <?php endforeach; ?>

                </ul>
                <?php endif ?>

            </div>

        </div>
        <!-- @end #form-error-message -->

        <?php
        $html = ob_get_contents();
        ob_end_clean();
        
        return $html;
    }//@end submit_form_failure()

    /*
     * Build form message
     */
    public function form_failure($ajax) {
        ob_start();

        ?>

        <!-- @start #form-error-message -->
        <div id="form-error-message" class="form-message error<?php echo $ajax ? ' ajax':''; ?>">

            <h3 class="heading">Form Error</h3>

            <div class="error-content">
                <p class="lead">We're sorry, there has been an error with the form input.</p>

                <?php if (count($this->form_error_messages)): ?>
                    <?php foreach ($this->form_error_messages as $key => $msg): ?>
                        <p><?php echo $msg ?></p>
                    <?php endforeach ?>
                <?php else: ?>
                       <p>We were unable to locate this form for processing.</p>             
                <?php endif ?>

            </div>

        </div>
        <!-- @end #form-error-message -->

        <?php
        $html = ob_get_contents();
        ob_end_clean();
        
        return $html;
    }//@end submit_form_failure()
    /******************************************************
     * @start Form Inputs
     ******************************************************/
    private function build_form_input($id, $input, $section='') {
        $has_error='';
        // echo '<pre>$this->clear_after_submission: '; var_dump($this->clear_after_submission); echo '</pre>';
        if(!$this->form_pristine) {
            if($this->clear_after_submission && $this->error_count===0) {
                // No errors found so clear the values
                $input['value']=''; 
            }
        }
        // data_type is the same as $data['type'] unless it is an invalid attributes type such as username
        // $data_type = $input['type'];
        // if ($input['type']=='username') {
        //     $input['type']='text';
        //     $data_type = 'username';
        // }
        if (isset($input['name'])) {
            $name = $input['name'];
        }
        else {
            $name = $id;
        }
        
        if (isset($input['id-index'])) {
            $id .= '-'.$input['id-index'];
        }

        
        // new
        $type = ' type="'.$input['type'].'"';
        $data_type = ' data-type="'.$input['data_type'].'"';
        $class = ' class="'.$this->get_form_input_class($input).'"';
        $id = ' id="'.$id.'"';
        $name = ' name="'.$name.'"';
        $tabindex = ' tabindex="'.$this->tab_index++.'"';
        $value = '';
        $min = '';
        $max = '';
        $validation = '';
        $disabled = '';

        if ( isset($input["value"]) && $input["value"] !== '') {
            $value = ' value="'.$input['value'].'"';
        }
        $placeholder = '';
        if ( isset($input["placeholder"]) && $input["placeholder"] !== '') {
            $placeholder = ' placeholder="'.$input['placeholder'].'"';
        }
        $section = '';
        if ( isset($input["section"]) && $input["section"] !== '') {
            $section = ' data-section="'.$input['section'].'"';
        }
        $required = '';
        if (isset($input['required']) && $input['required'] !== '') {
              $required = ' required';
        }
        if (isset($input["validation"])) {
            // echo '<pre>$input["validation"]'; var_dump($input["validation"]); echo '</pre>';
            // $validation = $input["validation"];
            $min = $input["validation"]["min"];
            $max = $input["validation"]["max"];
            $validation = $input["validation"]["validation"];
            // $max = $input["validation"]["max"];            
            if ($min) {
                $min = ' min="'.$min.'"';
            }
            if ($max) {
                $max = ' max="'.$max.'"';
            }
            if ($validation !== 'auto') {
                 $validation = ' data-validation="'.$validation.'"';          
            }            

        }
        if ($input["disabled"]) {
            $disabled = ' disabled';
        }
        $input_html = '<input'.$type.$data_type.$class.$id.$name.$tabindex.$value.$placeholder.$section.$required.$min.$max.$validation.$disabled.'>';
        return $input_html;       
    } 

    private function build_form_select($id, $data) {
        $readonly = '';
        $multiple = '';
        $css_class = '';
        if(!$this->form_pristine) {
            if($this->clear_after_submission && $this->error_count===0) {
                // No errors found so clear the selected value
                $data['selected_option']=''; 
            }
        }
        if (isset( $data['readonly'] ) && $data['readonly']) {
            $readonly = " disabled";
        }
        $allow_null = true;
        if (isset( $data['allow_null'] )) {
            $allow_null = $data['allow_null'];
        }
        if ( $data['data_type'] == 'multi_select') {
            $multiple = ' multiple';
            $css_class = ' js-select2-multiple';
        }
        ob_start();
        // echo '<pre>$css_class: '; var_dump($css_class); echo '</pre>';
        // echo '<pre>$data: '; var_dump($data); echo '</pre>';echo "<hr>";
        ?>

        <select class="<?php echo $this->get_form_input_class($data, $css_class); ?>" id="<?php echo $id; ?>" name="<?php echo $id; ?>" data-type="select" tabindex="<?php echo $this->tab_index++; ?>" <?php echo $data['required']; echo $multiple; echo $readonly; ?>>

            <?php if($allow_null && !$multiple ): ?>
                <option value="" class="placeholder">Please select an option...</option>
            <?php endif; ?>

            <?php if (isset($data['option_group']) && $data['option_group']):
                foreach ($data['options'] as $group_key => $optgroup):?>
                    <optgroup label="<?php echo $group_key; ?>">
                    <?php
                    foreach ($optgroup as $option):
                        
                        if($option['option_value'] === $data['selected_option']) { 
                            $selected=' selected'; 
                        } else { 
                            $selected=''; 
                        }?>
                        <option value="<?php echo $option['option_value']; ?>"<?php echo $selected; ?>><?php echo $option['option']; ?></option>
                    <?php 
                    endforeach;?>
                    </optgroup>
                <?php endforeach;
            else:
                foreach ($data['options'] as $option):
                    
                    if($option['option_value'] === $data['selected_option']) { 
                        $selected=' selected'; 
                    } else { 
                        $selected=''; 
                    }?>
                    <option value="<?php echo  $option['option_value']; ?>"<?php echo $selected; ?>><?php echo $option['option']; ?></option>
                <?php 
                endforeach;
                //Note: select closing tag (below) is indented to format correctly in browser                 
            endif; ?>

        </select>

        <?php
        $input_html = ob_get_contents();
        ob_end_clean();
        return $input_html;
    }

    private function build_form_textarea($id, $input) {
        $has_error='';
        if(!$this->form_pristine) {
            if($this->clear_after_submission && $this->error_count===0) {
                // No errors found so clear the values
                $input['value']=''; 
            }
        }     
        if (isset($input['name'])) {
            $name = $input['name'];
        }
        else {
            $name = $id;
        }
        $data_type = ' data-type="textarea"'; 
        $id = ' id="'.$id.'"';
        $name = ' name="'.$name.'"';
        $class = ' class="'.$this->get_form_input_class().'"';
        $tabindex = ' tabindex="'.$this->tab_index++.'"';
        $value = '';
        if ( isset($input["value"]) && $input["value"] !== '') {
            $value = $input['value'];
        }        
        $placeholder = '';
        if ( isset($input["placeholder"]) && $input["placeholder"] !== '') {
            $placeholder = ' placeholder="'.$input['placeholder'].'"';
        }        
        $section = '';
        if ( isset($input["section"]) && $input["section"] !== '') {
            $section = ' data-section="'.$input['section'].'"';
        }
        $required = '';
        if (isset($input['required']) && $input['required'] !== '') {
              $required = ' required';
        } 

        $rows = '';
        if ( isset($input["rows"]) && $input["rows"] !== '') {
            $rows = ' rows="'.$input['rows'].'"';
        }
        $maxlength = '';
        if ( isset($input["maxlength"]) && $input["maxlength"] !== '') {
            $maxlength = ' maxlength="'.$input['maxlength'].'"';
        }
        $input_html = '<textarea'.$data_type.$class.$id.$name.$tabindex.$placeholder.$section.$required.$rows.$maxlength.'>'.$value.'</textarea>';
        return $input_html;    

    }

    private function build_form_radio($id, $input) {
        if(!$this->form_pristine) {
            if($this->clear_after_submission && $this->error_count===0) {
                // No errors found so clear the selected value
                $input['selected_option']=''; 
            }
        }

        $count=0;  
        $checked='';
        ob_start();
        foreach ($input['options'] as $option): $count++;
            if ( $input['selected_option'] !== '' && ($input['selected_option'] === $option['option_value']) ){
                $checked=' checked';
            }
            ?>
                    
                    <label for="<?php echo $id.'-'.$count ?>" class="lbl-radio">
                        <input id="<?php echo $id.'-'.$count ?>" name="<?php echo $id ?>-radio" type="radio" data-type="radio" tabindex="<?php echo $this->tab_index++; ?>" value="<?php echo $option['option_value'] ?>"<?php echo $checked; ?>><?php echo $option['option'] ?>

                    </label>
            <?php 
        endforeach;
        $html = ob_get_contents();
        ob_end_clean();
        return $html;            
    }

    private function build_form_checkbox($id, $data) {
        if(!$this->form_pristine) {
            if($this->clear_after_submission && $this->error_count===0) {
                // No errors found so clear the checked values
                foreach ($data['options'] as $key => $option) {
                    $data['options'][$key]['checked'] = false;
                }
            }
        }

        $count=0;  
        $name_append = '';
        if (count($data['options']) > 1) {
            $name_append = '[]';
        }
        ob_start();

        // if ($id == "form-signup-options") {
        //     $checked=' checked';
        // }
        foreach ($data['options'] as $option): $count++;
            $checked='';
            //hack
            // if ($id == "form-signup-options") {
            //     $checked=' checked';
            // } 
            //@end hack       
            if ( $option['checked'] == true ){
                $checked=' checked';
            }
            // else {
            //     $checked='';
            // }
            if (isset($data['name'])) {
                $name = $data['name'].$name_append;
                if ($id == "form-signup-options") {
                    $hidden_name = $data['name'].'-hidden'.$name_append;
                }
                
            }
            else {
                $name = $id.''.$name_append;
                if ($id == "form-signup-options") {
                    $hidden_name = $id.'-hidden'.$name_append;
                }
                
            }

            ?>

                    <label for="<?php echo $id.'-'.$count ?>" class="lbl-checkbox">
                        <input id="<?php echo $id.'-'.$count ?>" name="<?php echo $name ?>" type="checkbox" data-type="checkbox" tabindex="<?php echo $this->tab_index++; ?>" value="<?php echo $option['option_value'] ?>"<?php echo $checked; ?>><?php echo $option['option'] ?>
                    
                    </label>
            <?php
      
          /*  if ($id == "form-signup-options")
                <input name="<?php echo $hidden_name ?>" type="text" data-type="hidden" value="<?php echo $option['option_value'] ?>">*/
            
        endforeach;
        $input_html = ob_get_contents();
        ob_end_clean();
        return $input_html;  
    }

    private function build_form_checkbox_single($id, $input) {
        $checked='';
        if ( $input["option"]["checked"]){
            $checked=' checked';
        }  
        $required = '';
        if (isset($input['required']) && $input['required'] !== '') {
              $required = ' required';
        }           
        ob_start();
        if ($input["option"]["key"]): ?>

                    <label for="<?php echo $id ?>" class="lbl-checkbox">
                        <input id="<?php echo $id ?>" name="<?php echo $id ?>" type="checkbox" class="js-single-checkbox"  data-type="checkbox" tabindex="<?php echo $this->tab_index++; ?>" value="1"<?php echo $checked; echo $required; ?>><?php echo $input["option"]["key"] ?>
                    
                    </label>
        <?php else: ?>

                    <input id="<?php echo $id.'-'.$count ?>" name="<?php echo $name ?>" class="js-single-checkbox" type="checkbox" data-type="checkbox" tabindex="<?php echo $this->tab_index++; ?>" value="1"<?php echo $checked; echo $required; ?>><?php echo $input["option"]["key"] ?>
        <?php endif;

        $html = ob_get_contents();
        ob_end_clean();
        
        return $html;

    }

    private function build_form_repeat_section($id, $input) {      
        ob_start();
   //      echo "<pre>id: $id</pre>";
   // echo '<pre>$input: '; var_dump($input); echo '</pre>';

        $button = $input["buttons"];
        $button_id = $input["id"];
        $add_button_text = $input["buttons"]["add_button"]["button_text"];
        $remove_button_text = $input["buttons"]["remove_button"]["button_text"];
        $add_button_disabled = $input["buttons"]["add_button"]["disabled"];
        // if ($add_button_disabled) {
        //     $add_button_disabled = '';
        // }
        // else {
        //     $add_button_disabled = ' disabled';
        // }        
        $remove_button_disabled = $input["buttons"]["remove_button"]["disabled"];
        // if ($remove_button_disabled) {
        //     $remove_button_disabled = '';
        // }
        // else {
        //     $remove_button_disabled = ' disabled';
        // }
        // $remove_button_text = str_replace("Add", "Remove", $add_button_text);
        $group_id = $input["id"] . '-add-remove-group';
        $min = $input["min"];
        $max = $input["max"];
        $count = $input['count'];
        $form_id = $input["form_id"];
        $input_keys = json_encode($input['input_keys']);
                // echo "<pre>"; var_dump( $input_keys); echo "</pre>";
        if ($count > $min) {
            // echo '<pre>$array: '; var_dump($input); echo '</pre>';
            
        }
        ?>


<!--                     <div class="form-label">
                        <label for="" class="control-label">Manage <?php echo str_replace('Add ', '',  $add_button_text ) ?>s</label>
                    </div> -->
                    <input type="hidden" 
                            id="<?php echo $id; ?>" 
                            name="<?php echo $id; ?>" 
                            data-type="repeat-section" 
                            value="<?php echo $count ?>"
                            min="<?php echo $max ?>" 
                            max="<?php echo $max ?>" 
                            readonly>
                            <div class="form-group right add-remove-group" id="<?php echo $group_id ?>">
                                <a href="#" 
                                class="button small success js-add-row" 
                                id="add-row-<?php echo $button_id ?>" 
                                data-remove-button="#remove-row-<?php echo $button_id ?>" 
                                data-count-input-id="#<?php echo $id ?>" 
                                data-action="add_row" 
                                data-group="<?php echo $button_id ?>" 
                                data-form-id="<?php echo $form_id ?>" 
                                data-keys='<?php echo $input_keys ?>' 
                                tabindex="<?php echo $this->tab_index++; ?>"<?php echo $add_button_disabled ?>><?php echo $add_button_text ?></a>
                                <a href="#" 
                                class="button small warning js-remove-row" 
                                id="remove-row-<?php echo $button_id ?>" 
                                data-add-button="#add-row-<?php echo $button_id ?>" 
                                tabindex="<?php echo $this->tab_index++; ?>"<?php echo $remove_button_disabled; ?>><?php echo $remove_button_text ?></a>
                            </div>



        <?php
        $html = ob_get_contents();
        ob_end_clean();
        
        return $html;
    }    
    /******************************************************
     * @end Form Inputs
     ******************************************************/

    /*
     * Use the same function to wrap all inputs
     */
    public function wrap_input($id, $input, $input_html, $section='') {
        // echo '<pre>$input: '; var_dump($input); echo '</pre>';

        if(!$this->form_pristine) {
            if($this->clear_after_submission && $this->error_count===0) {
                // No errors found so clear the values
                $input['value']=''; 
            }
        }
        if ($input["grouping"] && $input["grouping"] == "start"): ?>

            <!-- Start grouping -->
            <div class="<?php echo $this->get_css_form_grid_grouping(); ?>">         
        <?php endif ?>

            <!-- @start form element -->
            <div class="<?php echo $this->get_css_form_group($input) ?>" id="<?php echo $id; ?>-form-group">

                <!-- @start input anchor -->
                <a href="<?php echo $id; ?>-anchor"></a>
                <!-- @end input anchor -->

                <!-- @start input label -->
                <div class="form-label">

                    <div class="form-builder-feedback"><span class="feedback form-icon form-builder-x"></span><span class="feedback form-icon form-builder-check"></span><span class="form-icon form-builder-circle-o-notch"></span></div>
                    <?php 
                    if ($input['label']!=''): 
                    ?><label for="<?php echo $id; ?>" class="control-label <?php echo $input['required']; ?>"><?php echo $input['label']; ?> <span></span></label><?php 
                    endif; ?>

                </div>  
                <!-- @end input label -->
                
                <!-- @start input -->
                <div class="form-input">
                    
                    <?php 
                        echo $input_html; 

                        if ($input['help']) {
                             $help = $input['help'];
                        }
                        else {
                            $help = $input['label']. ' is required';
                            if ($input['type']=='email' || $input['type']=='url') {
                                $help .= ' and must be valid';
                            }  
                            $input['help'] = $help;
                        }   
                        //if ($input['required']): 
                        echo PHP_EOL; ?>
                    <div class="form-builder-error">
                        <small class="error" id="<?php echo $id; ?>-report"><?php echo $help; ?></small>
                    </div>
                    <?php 
                        //endif;
                        if (isset($input['instructions']) && $input['instructions']): echo PHP_EOL; ?>
                    <small class="instructions"><?php echo $input['instructions']; ?></small><?php 
                        endif;
                    ?>
                    

                </div>
                <!-- @end input -->

            </div>
            <!-- @end form element -->
        <?php if ($input["grouping"] && $input["grouping"] == "end"): ?>
             
            </div>
            <!-- end grouping -->    
        <?php endif;    
    }

    /*
     * 
     */
    public function get_css_form_grid_grouping() {
        return "form-grid-grouping _grid-x";
    }

    /*
     * 
     */
    public function get_css_form_group($input) {
        $has_error='';
        if(!$this->form_pristine && $input['passed']==false && $input["type"] !== "checkbox") {
            // This input has has error detected so add an error class to the surrounding div
            $has_error = ' has-error';
        } 
        $framework_style = '';
        if ( $this->css_framework === "zurb_foundation" ) {
            // $framework_style = ' cell large-auto small-6';
        }       
        return "form-group ".$input["css_class"].$has_error.$framework_style;
    }

    /*
     * Hookable function that
     */
    public function before_submit_button_hook() {

    }

    public function form_data_types() {
        if ($this->clear_after_submission) {
            echo ' data-clear="true"';
        }
    }
  
    /**
     * Get the submit button name 
     * This can be used to check if this POST object was set
     */
    public function get_submit_button_name() {
        return $this->submit_button_name;
    }  

    /**
     * Get the form wrapper class
     */
    public function get_form_class() {
        return $this->form_class;
    }      
    /*
     * Get form_post_id
     */
    public function get_form_post_id() {
        return $this->form_post_id;
    } 
    /*
     * Get form_post_id
     */
    public function get_post_id() {
        return $this->post_id;
    }      
    /*
     * Get form_pristine
     */
    public function get_form_pristine() {
        return $this->form_pristine;
    }
    /*
     * Set form_pristine
     */
    public function set_form_pristine($form_pristine) {
        $this->form_pristine = $form_pristine;
    }
    /*
     * Get error_count
     */
    public function get_error_count() {
        return $this->error_count;
    }
    /*
     * Increase error_count
     */
    public function increase_error_count() {
        $this->error_count++;
    }
    /*
     * Get extra_error_msgs
     */
    public function get_extra_error_msgs() {
        return $this->extra_error_msgs;
    }
    /*
     * Increase extra_error_msgs
     */
    public function add_extra_error_msgs($msg, $increase_count=true) {
        if ($increase_count) {
           $this->error_count++;
        }
        $this->extra_error_msgs[] = $msg;
    }

    /*
     * Get extra msgs
     */
    public function get_extra_msgs() {
        return $this->extra_msgs;
    }
    /*
     * Add new msg
     */
    public function add_extra_msg($msg) {
        $this->extra_msgs[] = $msg;
    }

    /*
     * Set success msg
     */
    public function set_success_msg($msg) {
        $this->success_msg = $msg;
    }

    /*
     * Set success msg
     */
    public function set_input_error($key, $msg) {
        $this->form_data[$key]["help"] = $msg;
        $this->form_data[$key]["passed"] = false;
        $this->increase_error_count();
    }

    /*
     * Get the CSS class for the input
     */
    private function get_form_input_class($input=false, $css_class='') {
        if (isset($input["css_class_input"])) {
            return "js-form-builder-control " . $input["css_class_input"] . $css_class;
        }
        return "js-form-builder-control" . $css_class;
    }

    /*
     * Set the CSS framework
     */
    public function set_css_framework($css_framework) {
        $this->css_framework = $css_framework;
    } 

    public function get_tab_index( $increment=true ) {
        if ($increment) {
            $this->tab_index++;
        }
        return $this->tab_index;
    }


    // public function get_show_mail_receipt() {
    //     return $this->show_mail_receipt;
    // }
    public function get_form_data( $sections = true) {
        if ($sections) {
            return $this->form_data;
        }
        else {
            $form_data = array();
            foreach ($this->form_data as $section) {
                foreach ($section["inputs"] as $key => $input) {
                    $form_data[$key] = $input;
                }
            }
            return $form_data;
        }
        
    }
   public function increment_form_data( $count ) {
        // return $this->form_data;
        // if ($sections) {
        //     return $this->form_data;
        // }
        // else {
        //     $form_data = array();
            foreach ($this->form_data as &$section) {
                foreach ($section["inputs"] as $key => $input) {
                    // $form_data[$key] = $input;
                    $section["inputs"][$key.'-'.$count] = $input;
                    unset( $section["inputs"][$key] );
                    // echo '<pre>$key: '; var_dump($key.'-'.$count); echo '</pre>';
                }
            }
        //     return $form_data;
        // }
        
    }    
    public function get_inputs() {
        return $this->form_data[0]["inputs"];        
    }     
    public function get_user_confirmation_email() {
        return $this->user_confirmation_email;
    }  
    public function recaptcha_site() {
        if (isset( $this->recaptcha["site_key"] )) {
            return $this->recaptcha["site_key"];
        } 
    } 
    public function recaptcha_secret() {
        if (isset( $this->recaptcha["secret_key"] )) {
            return $this->recaptcha["secret_key"];
        } 
    }

    public function recaptcha_theme() {
        if (isset( $this->recaptcha["theme"] )) {
            echo ' data-theme="'.$this->recaptcha["theme"].'"';
        } 
    }

    public function recaptcha_size() {
        if (isset( $this->recaptcha["size"] )) {
            echo ' data-size="'.$this->recaptcha["size"].'"';
        } 
    }

    public function recaptcha_group_class() {
        if (isset( $this->recaptcha["hide_on_load"] ) && $this->recaptcha["hide_on_load"] ) {
            echo ' hide init-hidden';
        } 
    }         
    public function recaptcha_html() {
        $html = '';
        if ( $this->recaptcha_site() ):
            ob_start();
            ?>

                <div class="form-group <?php $this->recaptcha_group_class(); ?>" id="captcha-wrapper">

                    <!-- @start input -->
                    <div class="form-input">
                        <div class="g-recaptcha" data-sitekey="<?php echo $this->recaptcha_site() ?>" <?php $this->recaptcha_theme(); $this->recaptcha_size(); ?> data-tabindex="<?php echo $this->get_tab_index(); ?>" data-size="normal"></div>

                    </div>
                    <!-- @end input -->

                </div>
            <?php
            $html = ob_get_contents();
            ob_end_clean();
        endif;//@nd if ($this->gdpr_settings)
        echo  $html;
    } 

    public function recaptcha_check($post) {
        $response = array(
            'status' => false,
            'msg' => '',
        );
        if ( !$this->recaptcha_secret() ){
            // recaptcha is not set so skip the check
            return true;
        }
        elseif ( $this->recaptcha_secret() && $post["g-recaptcha-response"] ){

            $g_response = $post["g-recaptcha-response"];

            $url = 'https://www.google.com/recaptcha/api/siteverify';
            $post_data = "secret=".$this->recaptcha_secret()."&response=".$g_response."&remoteip=".$_SERVER['REMOTE_ADDR'] ;
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
                $this->increase_error_count();
                $this->form_error_messages[] = "You are a bot! Go away!";    
                return false;         
            } 
            elseif ( $decgoogresp->success === true ) {
                return true;     
            }
        }
        elseif ( $this->recaptcha_secret() ){
            $this->increase_error_count();
            $this->form_error_messages[] = "This form is expecting a recaptcha code to validate but none was found!";               
            return false;
        }
              
    } 

    public function gdpr() {
        if ( $this->gdpr_settings ) {
            return true;
        }
    }

    public function gdpr_settings() {
        if ( $this->gdpr_settings ) {
            return $this->gdpr_settings;
        }
    }    

    public function gdpr_html() {

        if ( $this->gdpr_settings ): ?>
        <!-- @start .sign-up -->
        <div class="form-group sign-up">
            <div class="form-label"></div>
            <div class="form-input">
                <div class="checkbox">
                    <label id="sign-up-details"><?php echo $this->gdpr_settings["main_message"] ?></label>

                        <?php foreach ($this->gdpr_settings["opt_in"] as $key => $opt_in): ?>

                            <?php if ( is_array($opt_in["options"]) && ( in_array("email", $opt_in["options"]) || in_array("sms", $opt_in["options"]) ) ): ?>

                                <label for=""><?php echo $opt_in["message"] ?></label>

                                <?php if ( in_array("email", $opt_in["options"]) ): ?>
                                    <input type="checkbox" value="email" tabindex=<?php echo $this->get_tab_index(); ?> name="sign-up-<?php echo $key; ?>[]" id="sign-up-email" class="sign-up"><label for="sign-up-email">Email</label>
                                <?php endif ?>                            
                                <?php if ( in_array("sms", $opt_in["options"]) ): ?>
                                    <input type="checkbox" value="sms" tabindex=<?php echo $this->get_tab_index(); ?> name="sign-up-<?php echo $key; ?>[]" id="sign-up-sms" class="sign-up"><label for="sign-up-sms">SMS</label> 
                                <?php endif ?>  

                            <?php endif ?>
                            
                        <?php endforeach ?>
                    
                    

 <?php 
 /* 
                    <?php if ( $this->form_type === "contact" ): ?>
 
                         <label for="">I am happy to receive marketing information from this dealer by: (please tick all that apply)</label>
 
                         <input type="checkbox" value="email-dealer" tabindex=<?php echo $this->get_tab_index(); ?> name="sign-up[]" id="sign-up-email-dealer" class="sign-up"><label for="sign-up-email-dealer">Email</label>
                         <input type="checkbox" value="sms-dealer" tabindex=<?php echo $this->get_tab_index(); ?> name="sign-up[]" id="sign-up-sms-dealer" class="sign-up"><label for="sign-up-sms-dealer">SMS</label>                            
                         <?php endif ?> 
 */ 
 ?>
                     
                </div>
            </div>                  
        </div> 
        <!-- @end .sign-up -->                
        <?php endif;//@nd if ($this->gdpr_settings)
    }


    public function gdpr_disclaimer() {

        if ( $this->gdpr_settings && $this->gdpr_settings["disclaimer"] ): ?>

            <div class="form-group sign-up">
                <div class="policies">
                    <?php echo $this->gdpr_settings["disclaimer"] ?>
                </div>
            </div>

        <?php endif;//@nd if ($this->gdpr_settings)
    } 


    public function mail_receipt_html() {
        if ($this->user_confirmation_email === 'ask'): ?>

            <!-- @start .mail-receipt -->
            <div class="form-group mail-receipt">
                <div class="form-label"></div>
                <div class="form-input">
                    <div class="checkbox">
                      <input type="checkbox" value="" tabindex=<?php echo $this->get_tab_index(); ?> name="mail-receipt" id="mail-receipt" checked><label for="mail-receipt">Acknowledge me with a mail receipt</label>
                    </div>
                </div>                  
            </div> 
            <!-- @end .mail-receipt -->                
        <?php endif;         
    }    

    public function button_html() {
        ?>
        <!-- @start .button -->
        <div class="form-group button-group">

            <!-- @start input -->
            <div class="form-input">

                <button type="submit" name="<?php echo $this->submit_button_name; ?>" id="<?php echo $this->submit_button_id; ?>" class="button" tabindex="<?php echo $this->get_tab_index(); ?>"><?php echo $this->submit_button_text; ?></button>


            </div>
            <!-- @end input -->            
        </div>
        <!-- @end .button -->
        <?php           
    }  
    public function open_section_html( $content, $key = 0 ) {
        ?>

        <!-- @start section #form-section-<?php echo $key ?> -->
        <div id="form-section-<?php echo $key ?>">
        <?php
        if (isset($content["section_header"]) || isset($content["section_content"])): ?>

            <!-- @start .section-content -->
            <div class="form-group section-content">

                <!-- @start input -->
                <div class="form-input">
                    <?php if (isset($content["section_header"])): ?>

                    <h4><?php echo $content["section_header"]; ?></h4>

                    <?php 
                    endif;
                    if (isset($content["section_content"])): ?>

                    <div class="entry-content"><?php echo $content["section_content"]; ?></div>

                    <?php endif ?>

                </div>
                <!-- @end input -->            
            </div>
            <!-- @end .section-content --> 

        <?php endif;         
    }  

    public function close_section_html( $key ) {
        ?>

        </div>
        <!-- @end section #form-section-<?php echo $key ?> -->

        <?php   
    }                   
}