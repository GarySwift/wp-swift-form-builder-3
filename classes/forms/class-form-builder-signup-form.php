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
    }    

    public function get_response($post) {
        $form_set = false;
        $process_form = parent::process_form($post, true);

        if (parent::get_form_data()) {
           $form_set = true;
        }
        if (isset($process_form["html"])) {
            $html = $process_form["html"];
        }
        else {
            $html = $process_form;
        }
        $response = array(
            "form_set" => $form_set,
            "error_count" => parent::get_error_count(),
            "html" => $html,
            "session" => isset($process_form["session"]) ? $process_form["session"] : null,
            "response" => isset($process_form["response"]) ? $process_form["response"] : null,

        );
        write_log($response); 
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
        $signups = isset($post["sign-up"]) ? $post["sign-up"] : array();
        $listid = array(5);
        $listid_unlink = null;
        if (isset($post["form-signup-options"]) && isset($post["form-signup-options-hidden"])) {
            $listid = $post["form-signup-options"];
            $listid_hidden = $post["form-signup-options-hidden"];
            $listid_unlink = array_diff_assoc($listid_hidden, $listid);       
        }
        $response = wp_swift_do_signup(parent::get_form_data(), $signups, $listid, $listid_unlink);
        return $response;  
    }
}

function wp_swift_do_signup($form_data, $signups, $list_id_array = array(), $list_id_array_unlink = null) {   
    $sendinblue_account_type == 1;//
    $session = array();// This will send back the user data to store in local storage 
    $data = array();// This will be the user data we send to SendInBlue
    $save = false;// We will only save if SMS or Email is selected    
    $mailin = null;// This will be instantiated into a MailIn object
    $mailin_response = null;// This will be the response form the API call 
    $mailin_api_url = 'https://api.sendinblue.com/v2.0';// sendinblue url
    $mailin_api_key = wp_swift_get_mailin_api();// The user API key
    $mailin_timeout = 5000;// Optional parameter: Timeout in MS    
    $first_name = get_form_input($form_data, "form-first-name" );
    $last_name = get_form_input($form_data, "form-last-name" );
    $email = get_form_input($form_data, "form-email" );
    $phone = get_form_input($form_data, "form-phone" );

    if ( class_exists('Mailin') && $first_name && $last_name ) {
        $session = array ( 
            "first_name" => $first_name,
            "last_name" => $last_name,
        );

        /**
         * SendinBlue uses different name fields for different accounts
         * so if is not possible to have a universal API. This is crazy
         * and I have contacted support about this but nothing can be 
         * done at the moment.
         *
         * We need use this approach for the moment.
         */
        if ($sendinblue_account_type == 1) {
            $first_name_key = "NAME";
            $last_name_key = "SURNAME";
        }
        elseif ($sendinblue_account_type == 2) {
            $first_name_key = "FIRSTNAME";
            $last_name_key = "LASTNAME";
        }

        $data = array( 
            "attributes" => array( $first_name_key => $first_name, $last_name_key => $last_name ),//, "DOUBLE_OPT-IN" => 1
            "listid" => $list_id_array,
        );      

        if ( $email ) {
            $session["email"] = $email;
            if ( in_array("email", $signups) ) {
                $data["email"] = $email;
                $save = true;
            }
        }
        if ( $phone ) {
            // $phone = str_replace(' ', '', $phone);
            $session["phone"] = $phone;
            if ( in_array("sms", $signups) ) {
                $data["attributes"]["SMS"] = $phone;
                $save = true;
            }
        }
        if (is_array( $list_id_array_unlink )) {
            $data["listid_unlink"] = $list_id_array_unlink;
        }
        if ($save) {
            $mailin = new Mailin( $mailin_api_url, $mailin_api_key, $mailin_timeout );
            $mailin_response = $mailin->create_update_user( $data );
            $response = array("html" => wp_swift_signup_html(),  "session" => $session, "response" => $mailin_response);
            return $response;
        }
        else {
            $response = array("html" => wp_swift_nosignup_html(),  "session" => $session, "response" => null);
            return $response;
        }

    }
    $response = array("html" => wp_swift_signup_error_html(),  "session" => $session, "response" => null);
    return $response;
}

function wp_swift_signup_html() {
    ob_start();
    ?>
        <h3>You're good to go!</h3>
        <p>Thanks for updating your preferences, your files are available below.</p>
    <?php
    $html = ob_get_contents();
    ob_end_clean();
    return $html;
}

function wp_swift_nosignup_html() {
    ob_start();
    ?>
        <h3>Thank You</h3>
        <p>Thanks for completing our marketing preferences form, if you would like to opt-in in the future just visit us again. The brochure library is now available for download.</p>
    <?php
    $html = ob_get_contents();
    ob_end_clean();
    return $html;
}
function wp_swift_signup_error_html() {
    ob_start();
    ?>
        <h3>We're Sorry</h3>
        <p>There was an error with the signup. Please contact site admin.</p>
    <?php
    $html = ob_get_contents();
    ob_end_clean();
    return $html;
}

function wp_swift_get_mailin_api() {
    $options = get_option( 'wp_swift_form_builder_settings' );
    if (isset($options['wp_swift_form_builder_marketing_api']) && $options['wp_swift_form_builder_marketing_api'] != '') {
        return $options['wp_swift_form_builder_marketing_api'];
    } 
}

function get_form_input($form_data, $key) {
    if (isset( $form_data[0]["inputs"][$key]["clean"] )) {
        return $form_data[0]["inputs"][$key]["clean"];
    }
}