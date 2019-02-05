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
class WP_Swift_Form_Builder_Helper {
    public $form_data = [];
    public $settings = null;
    private $args;
    private $form_post_id = '';
    private $post_id = null;
    private $form_name = '';

    private $submit_button_id = '';
    private $submit_button_name = '';
    private $submit_button_text = '';

    private $form_pristine = true;
    private $error_count = 0;
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
    // private $uploads_dir = '';
    private $attachments = array();
    /*
        function guide
        acf_build_form()


    */
    private $recaptcha = null;    
    /*
     * Initializes the plugin.
     */
    public function __construct($form_id, $post_id, $hidden = array(), $type = 'request', $args = array(), $_post = null ) {// , $sections, $settings = false  //"option") {

         /**
         * Get the array that will store all input settings and values
         */
        $form_data = wp_swift_get_form_data($form_id, $_post);
        if (isset($form_data["sections"])) {
            $this->form_data = $form_data["sections"];
        }
        if (isset($form_data["settings"])) {
            $this->settings = $form_data["settings"];
        }        
        $this->post_id = $post_id;
        $this->form_post_id = $form_id; 
        $this->form_type = $type;
        if (count($hidden)) {
            $this->hidden = $hidden;
        }
        // $this->uploads_dir = ABSPATH.'uploads';

        if (function_exists("get_field")) {
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
        }

        if(isset($this->settings["user_confirmation_email"])) {
            $this->user_confirmation_email = $this->settings["user_confirmation_email"];
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
            $this->submit_button_text = $this->settings["submit_button_text"];
        }  
        else {
            $this->submit_button_text = "Submit Form";
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
     * Get the submit button id
     */
    public function get_submit_button_id() {
        return $this->submit_button_id;
    }    

    /**
     * Get the submit button text
     */
    public function get_submit_button_text() {
        return $this->submit_button_text;
    }   

    public function get_form_response($validate, $button_name) {
        $html_response = '';
        if( isset( $_POST[$button_name] ) ) { //check if form was submitted
            // echo '<pre>$_POST: '; var_dump($_POST); echo '</pre>';
            $process_form = $validate->process_form($_POST);
            if (isset($process_form["html"])) {
                $html_response = $process_form["html"];
            }
            else {
                $html_response = $process_form;
            }     
        }  
        return $html_response;      
    }

    //     public function process_form($validate, $post, $ajax=false) {




    //     // if ( $this->get_form_data() && $this->validate->recaptcha_check($this, $post) ) {
    //         // $this->validate_form($post);
    //         echo '<pre>$this->error_count: '; var_dump($this->error_count); echo '</pre>';
    //         echo '<pre>$form_pristine: '; var_dump($this->form_pristine); echo '</pre>';
    //         echo '<pre>$this->html_builder->get_form_pristine() '; var_dump($this->get_form_pristine()); echo '</pre>';
    //         // $this->form_data = $this->validate->run($this, $this->html_builder, $post, $ajax);
    //         $this->helper = $validate->validate_form($this->helper, $post, $ajax);
    //         echo '<pre>$form_pristine: '; var_dump($this->form_pristine); echo '</pre>';
    //         // echo '<pre>$this->html_builder->get_form_pristine() '; var_dump($this->get_form_pristine()); echo '</pre>';
    //         // echo '<pre>$this->error_count: '; var_dump($this->error_count); echo '</pre>';
    //         // echo "<hr>";
    //         // if ( $this->helper->get_error_count() === 0 ) {
    //         //     return $this->submit_form_success($post, $ajax);
    //         // }
    //         // else {
    //         //     return $this->submit_form_failure($ajax);
    //         // }
    //     // }
    //     // else {
    //     //     return $this->form_failure($ajax);
    //     // }        
    // }

    
   
    
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
    // public function get_submit_button_name() {
    //     return $this->submit_button_name;
    // }  

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
    // 
    // 
    public function get_settings() {
        return $this->settings;   
    }
    public function get_args() {
        return $this->args;   
    }
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
    public function set_form_data( $form_data ) {
        $this->form_data = $form_data;
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

    public function get_attachments() {
        return $this->attachments;
    }  
    public function set_attachments($attachments) {
        return $this->attachments = $attachments;
    } 
    public function add_attachment($attachment) {
        return $this->attachments[] = $attachment;
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

    // public function recaptcha_theme() {
    //     if (isset( $this->recaptcha["theme"] )) {
    //         echo ' data-theme="'.$this->recaptcha["theme"].'"';
    //     } 
    // }

    // public function recaptcha_size() {
    //     if (isset( $this->recaptcha["size"] )) {
    //         echo ' data-size="'.$this->recaptcha["size"].'"';
    //     } 
    // }

    // public function recaptcha_group_class() {
    //     if (isset( $this->recaptcha["hide_on_load"] ) && $this->recaptcha["hide_on_load"] ) {
    //         echo ' hide init-hidden';
    //     } 
    // }         

    public function gdpr() {
        if ( $this->gdpr_settings ) {
            return true;
        }
    }

    public function get_gdpr_settings() {
        if ( $this->gdpr_settings ) {
            return $this->gdpr_settings;
        }
    } 
    public function add_form_error_message($message) {
        $this->form_error_messages[] = $message;
    } 
    public function get_form_error_messages() {
        return $this->form_error_messages;
    } 
    public function get_list_form_errors_in_warning_panel() {
        return $this->list_form_errors_in_warning_panel;
    } 
    public function get_clear_after_submission() {
        return $this->clear_after_submission;
    } 
    public function get_form_type() {
        return $this->form_type;
    } 
    // public function get_uploads_path() {       
    //     return $this->uploads_dir; // Root folder + uploads 
    // }

}