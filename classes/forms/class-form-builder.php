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
    public function __construct( $form_id, $post_id, $hidden = array(), $type = 'request', $args = array(), $_post = null ) {// , $sections, $settings = false  //"option") {
        /**
         * Initializes the helper, validate abd html_builder
         */  
        $this->helper = new WP_Swift_Form_Builder_Helper( $form_id, $post_id, $hidden = array(), $type, $args = array(), $_post = null );
        $this->validate = new WP_Swift_Form_Builder_Validate();  
        $this->html_builder = new WP_Swift_Form_Builder_Html( $this->helper->get_tab_index() );      
    }

    /**
     * Start form process and output html
     */
    public function run() {
        return $this->get_form();
    }

    /**
     * Build the form html (including submit information (success/error details) if relevant)
     *
     * @return  $string     Return the html to output from a shortcode or a function
     */
    public function get_form() {
        ob_start();
        $form_response = $this->process_form_non_ajax($_POST);// Get success/error details
        $this->html_builder->front_end_form( $this->helper, $form_response);
        $html = ob_get_contents();
        ob_end_clean();
        return $html; 
    }

    private function exists(){
        global $wpdb;
        $querystr = "SELECT wp_posts.ID, wp_posts.post_type
        FROM wp_posts WHERE wp_posts.ID = $form_id 
        AND wp_posts.post_type = 'wp_swift_form'
        AND ((wp_posts.post_status = 'publish'))";
        $products_wpdb =  $wpdb->get_results( $querystr );
        return isset($products_wpdb[0]->ID);
    }

    /**
     * Form Processing
     *
     * This handles the ajax request 
     */
    public function get_response($post) {
        $form_set = true;
        $form_response = $this::process_form($post, true);
        $html = $form_response["html"];

        // if ($this->helper->get_form_data()) {
        //    $form_set = true;
        // }
        // $form_set = true;
        // $form_set = $this->clear_after_submission;
        $response = array(
            "form_set" => $form_set,
            "error_count" => $this->helper->get_error_count(),
            "html" => $html,
            "process_form_response" => $form_response,
            "displaying_results" => $this->helper->get_displaying_results()
        ); 
        if (isset($form_response["session"])) {
            $response["session"] = $form_response["session"];
        }
        // if ($this->helper->get_clear_after_submission()) {
        //     $response["form_clear"] = 0;
        // }        
        return $response;      
    }

    public function process_form_non_ajax($post) { 
        $form_response = '';     
        // Check if form was submitted
        if( isset( $post[$this->helper->get_submit_button_name()] ) ) {
            $process_form = $this->process_form($post);// This will do validation and return a user message
            // if (isset($process_form["html"])) {
            //     $form_response = $process_form["html"];
            // }
            // else {
            //     $form_response = $process_form;
            // }
            $form_response = $process_form;
            // if (isset($process_form["html"])) {
                     
            // }     
        }
        return $form_response;        
    }
    public function process_form($post, $ajax=false) {
        $this->helper = $this->validate->validate_form($this->helper, $post, $ajax);
        if ( $this->helper->get_error_count() === 0 ) {
            // $response = $this->submit_form_success($post, $ajax);
            return $this->submit_form_success($post, $ajax);
        }
        else {
            return array("html" => $this->html_builder->submit_form_failure($this->helper, $ajax));
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
        return $this->helper->get_user_confirmation_email();
    }
    /*
     * Get show_page_in_email
     */
    public function get_show_page_in_email() {
        return $this->helper->get_show_page_in_email();
    }      
    /*
     * Get gdpr_settings
     */
    public function get_gdpr_settings() {
        return $this->helper->get_gdpr_settings();
    } 

    /*
     * Get marketing
     */
    public function get_marketing() {
        return $this->helper->get_marketing();
    } 
    /*
     * Get attachments
     */
    public function get_attachments() {
        return $this->helper->get_attachments();
    }

    public function helper() {
        return $this->helper;
    } 

    // public function validate() {
    //     return $this->validate;
    // }

    public function html() {
        return $this->html_builder;
    }       

    public function signup_api($post, $send_marketing = true, $at_least_one_option_required = false) {     
        return $this->html()->signup_api($post, $this->get_form_data(), $this->helper()->get_marketing(), $this->helper()->get_gdpr_settings(), $send_marketing, $at_least_one_option_required);
    }              
    // public function set_attachments($attachments) {
    //     // $this->attachments = $attachments;
    //     $this->helper->set_attachments($attachments);
    // } 
    // public function add_attachment($attachment) {
    //     // $this->attachments[] = $attachment;
    //     $this->helper->add_attachment($attachment);
    // }            
}