<?php
/*
 * Declare a new class that extends the form builder
 * 
 * @class       WP_Swift_Form_Builder_Login_Form
 * @extends     WP_Swift_Form_Builder_Plugin
 *
 */
class WP_Swift_Form_Builder_Signup_Form extends WP_Swift_Form_Builder_Parent {
    
    /*
     * Initializes the plugin.
     */
    public function __construct( $form_id, $post_id = null, $hidden = array(), $type = 'signup' ) {//
        parent::__construct( $form_id, $post_id, $hidden, $type );
        // write_log('__construct WP_Swift_Form_Builder_Signup_Form: ');
        // echo "<pre>"; var_dump('WP_Swift_Form_Builder_Signup_Form'); echo "</pre>";
        // echo '<pre>parent::helper()->get_auto_consent(): '; var_dump(parent::helper()->get_auto_consent()); echo '</pre>';
    }    

    public function get_response($post) {
        $form_set = false;
        $process_form = parent::process_form($post, true);
        $ref = false;
        if (isset($_POST['ref'])) $ref = $_POST['ref'];
        
        if (isset($process_form["error"])) {
            parent::helper()->increase_error_count();
            parent::helper()->add_form_error_message( $process_form["msg"] );           
            return array(
                "html" => parent::html()->submit_form_failure(parent::helper(), true),
                // "error_fields" => array("form-email")
            );
        }
        if (parent::get_form_data()) {
           $form_set = true;
        }
        $response = array(
            "type" => "signup",
            "form_set" => $form_set,
            "error_count" => parent::get_error_count(),
            "displaying_results" => parent::helper()->get_displaying_results(),
        );
        $response = array_merge($response, $process_form);
        if ($ref && isset($process_form["session"])) {
            if (strpos($ref, '?') !== false) {
                $ref .= '&download=1';
            }
            else {
                $ref .= '?download=1';
            }        
            $response["location"] = $ref;
        }
        return $response;      
    }

    /*
     * Form Processing
     */

    /*
     * Default has passed so the child will continue processing
     *
     * The form has been validated so we can send the emails.
     * This will get the to and from email recipients, build the html message and send the emails.
     * It then returns a html string that tells the user what has happened.
     *
     * @param class $Form_Builder   The name of the template to render (without .php)
     * @param array  $post          The global $_POST variable cast as a the local $post variable
     *
     * @return string               The html success message
     */
    public function submit_form_success($post, $ajax) {
        $response = array("html" => "Unknown error");
        $signups = isset($post["sign-up"]) ? $post["sign-up"] : array();
        $listid = null;
        $listid_unlink = null;
        $response = parent::signup_api($post, $send_marketing = true, $at_least_one_option_required = false);      
        return $response;  
    }
}