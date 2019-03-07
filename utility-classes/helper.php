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
    private $form_pristine = true;
    private $error_count = 0;
    private $form_error_messages = array();
    private $extra_msgs = array();
    private $form_type; 

    // private $args;
    private $form_post_id = '';
    private $post_id = null;
    private $form_name = '';
    private $form_css_id = '';
    private $form_class = 'form-builder';//  // css class

    private $clear_after_submission = true;
    private $list_form_errors_in_warning_panel = true;
    private $user_confirmation_email = "ask";
    private $action = '';
    private $enctype = '';
    private $show_edit_link = false;
    private $submit_button_id = '';
    private $submit_button_name = '';
    private $submit_button_text = '';




    private $hidden = null;//Hidden input fields



    private $recaptcha = array();
    private $gdpr_settings = null;
    private $attachments = array();

    /*
     * Initializes the plugin.
     */
    public function __construct( $form_id, $post_id, $hidden = array(), $type = 'request', $args = array(), $_post = null ) {

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
        $this->form_type = $type;       
        $this->post_id = $post_id;
        $this->form_post_id = $form_id; 


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






        if (isset($args["form_class"])) {
            $this->form_class .= $args["form_class"];
        }
        if (isset($this->settings["groupings"])) {
            $this->form_class .= " groupings";
        } 
        if (isset($this->settings["form_css_class"])) {
            $this->form_class .= $this->settings["form_css_class"];
        } 
        if (isset($args["clear_after_submission"])) {
            $this->clear_after_submission = $args["clear_after_submission"];
            $this->form_class .= ' js-do-not-clear';
        }

        if (isset($args["list_form_errors_in_warning_panel"])) {
            $this->list_form_errors_in_warning_panel = $args["list_form_errors_in_warning_panel"];
        }
        if(isset($this->settings["user_confirmation_email"])) {
            $this->user_confirmation_email = $this->settings["user_confirmation_email"];
        }
        if (isset($args["action"]) && $args["action"]!='') {
            $this->action = ' action="'.$args["action"].'"';// If the form is to sent to a specific page
        }
        if (isset($this->settings["enctype"])) {
            $this->enctype = $this->settings["enctype"];
        }
        else {
            $this->form_class .= ' ajax';
        }
        if(isset($this->settings["show_edit_link"])) {
            $this->show_edit_link = true;
        }
        if(isset($args["submit_button_name"])) {
            $this->submit_button_name = $args["submit_button_name"];
        }
        else {
            $this->submit_button_name = "submit-".$this->form_name;
        }

        if(isset($args["submit_button_id"])) {
            $this->submit_button_id = $args["submit_button_id"];
        }
        else {
            $this->submit_button_id = $this->submit_button_name;
        }

        if(isset($args["submit_button_text"])) {
            $this->submit_button_text = $args["submit_button_text"];
        }
        elseif(isset($this->settings["submit_button_text"])) {
            $this->submit_button_text = $this->settings["submit_button_text"];
        }  
        else {
            $this->submit_button_text = "Submit Form";
        }



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

        if (count($hidden)) {
            $this->hidden = $hidden;
        }
    }

    /**
     * Get $form_data
     *
     */
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

    public function get_settings() {
        return $this->settings;   
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

    public function get_form_type() {
        return $this->form_type;
    }

    public function add_form_error_message($message) {
        $this->form_error_messages[] = $message;
    } 
    public function get_form_error_messages() {
        return $this->form_error_messages;
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
     * Add new extra msg
     */
    public function add_extra_msg($msg) {
        $this->extra_msgs[] = $msg;
    }    
    /*************************************************/ 
    /*
     * Get form_post_id
     */
    public function get_form_post_id() {
        return $this->form_post_id;
    } 

    /*
     * Get the post ID of the page where this form is used
     */
    public function get_post_id() {
        return $this->post_id;
    }

    /**
     * Get the form css name
     */
    public function get_form_name() {
        return $this->form_name;
    }


    /**
     * Get the form css id
     */
    public function get_form_css_id() {
        return $this->form_css_id;
    }    

    /**
     * Get the form wrapper class
     */
    public function get_form_class() {
        return $this->form_class;
    }        

    public function get_clear_after_submission() {
        return $this->clear_after_submission;
    } 

    public function get_list_form_errors_in_warning_panel() {
        return $this->list_form_errors_in_warning_panel;
    }

    public function get_user_confirmation_email() {
        return $this->user_confirmation_email;
    } 

    /**
     * Get the form action
     *
     * Specify if this form should be submitted to a specific page.
     * This can be used in WordPress forms such as login, password reset etc.
     * It is not used in standard forms.
     */
    public function get_action() {
        return $this->action;
    }

    public function get_enctype() {
        return $this->enctype;
    }   

    public function get_show_edit_link() {
        return $this->show_edit_link;
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

    public function get_form_data_types() {
        if ($this->clear_after_submission) {
            echo ' data-clear="true"';
        }
    }
    public function get_attachments() {
        return $this->attachments;
    }  
    public function set_attachments($attachments) {
        $this->attachments = $attachments;
    } 
    public function add_attachment($attachment) {
        $this->attachments[] = $attachment;
    }    
}