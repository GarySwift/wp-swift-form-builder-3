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
    private $helper;
    private $validate;
    private $html_builder;

    /**
     * Initializes the plugin.
     */
    public function __construct($form_id, $post_id, $hidden = array(), $type = 'request', $args = array(), $_post = null ) {// , $sections, $settings = false  //"option") {
        /**
         * Initializes the helper, validate abd html_builder
         */        
        $this->helper = new WP_Swift_Form_Builder_Helper($form_id, $post_id);
        $this->validate = new WP_Swift_Form_Builder_Validate();           
        $this->html_builder = new WP_Swift_Form_Builder_Html($this->helper);              
    }

    public function run() {
        // if ($this->form_data) {
            // return $this->get_form();
        //     return $this->html_builder->run( $this->helper );
        // }
        // return $this->html_builder->get_form( $this->helper, $this->validate );
        // else {
        //     return "<pre>Form not found</pre>";
        // }
        return $this->get_form();
    }

    public function get_form() {
        $form_response = $this->process_form_non_ajax($_POST);
        ob_start();
        // Build the form html (including submit information if relevant)
        $this->html_builder->front_end_form( $this->helper, $form_response);
        $html = ob_get_contents();
        ob_end_clean();
        // Return the html to output from a shortcode or a function
        return $html; 
    }

    /**
     * Form Processing
     *
     * This handles the ajax request 
     */
    public function get_response($post) {
        $form_set = false;
        $html = $this::process_form($post, true);
        if ($this->helper->get_form_data()) {
           $form_set = true;
        }
        if ($this->clear_after_submission) {
            
        }
        $form_set = false;
        // $form_set = $this->clear_after_submission;
        $response = array(
            "form_set" => $form_set,
            "error_count" => $this->helper->get_error_count(),
            "html" => $html,
        ); 
        if ($this->clear_after_submission) {
            $response["form_clear"] = 0;
        }        
        return $response;      
    }

    public function process_form_non_ajax($post) { 
        // echo '<pre>1 $helper->get_attachments(): '; var_dump($this->helper->get_attachments()); echo '</pre>';
        $form_response = '';     
        // Check if form was submitted
        if( isset( $post[$this->html_builder->get_submit_button_name()] ) ) { 
            $process_form = $this->process_form($post);// This will do validation and return a user message
            if (isset($process_form["html"])) {
                $form_response = $process_form["html"];
            }
            else {
                $form_response = $process_form;
            }     
        }
         // echo '<pre>2 $helper->get_attachments(): '; var_dump($this->helper->get_attachments()); echo '</pre>';
        return $form_response;        
    }
    public function process_form($post, $ajax=false) {

        // if ( $this->get_form_data() && $this->validate->recaptcha_check($this, $post) ) {
            // $this->helper = $this->validate->validate_form($this->helper, $post, $ajax);
            // if ( $this->helper->get_error_count() === 0 ) {
            //     return $this->submit_form_success($post, $ajax);
            // }
            // else {
            //     return $this->html_builder->submit_form_failure($this->helper, $ajax);
            // }
        // }
        // else {
        //     return $this->form_failure($ajax);
        // } 
        $this->helper = $this->validate->validate_form($this->helper, $post, $ajax);
        if ( $this->helper->get_error_count() === 0 ) {
            return $this->submit_form_success($post, $ajax);
        }
        else {
            return $this->html_builder->submit_form_failure($this->helper, $ajax);
        }       
    }
    public function submit_form_success($post, $ajax) {}  


    public function get_form_data( $sections = true) {
       return $this->helper->get_form_data( $sections );
    }   
    /*
     * Get error_count
     */
    public function get_error_count() {
        return $this->helper->get_error_count();
    }
    /*
     * Get post_id
     */
    public function get_post_id() {
        return $this->helper->get_post_id();
    } 
    /*
     * Get form_post_id
     */
    public function get_form_post_id() {
        return $this->helper->get_form_post_id();
    }     
    /*
     * Get user_confirmation_email
     */
    public function get_user_confirmation_email() {
        return$this->helper->get_user_confirmation_email();
    }  
    /*
     * Get gdpr_settings
     */
    public function gdpr_settings() {
        return $this->helper->gdpr_settings();
    } 
    /*
     * Get attachments
     */
    public function get_attachments() {
        return $this->helper->get_attachments();
    }             
}