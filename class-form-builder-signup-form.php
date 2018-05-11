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
        write_log('@start submit_form_success');
        // write_log($post);
        $signups = isset($post["sign-up"]) ? $post["sign-up"] : array();
        
        $listid = array(5);
        $listid_unlink = null;
        if (isset($post["form-signup-options"]) && isset($post["form-signup-options-hidden"])) {
            $listid = $post["form-signup-options"];
            $listid_hidden = $post["form-signup-options-hidden"];
            $listid_unlink = array_diff_assoc($listid_hidden, $listid);       

            write_log($listid);
            write_log($listid_hidden);
            write_log($listid_unlink);
        }
        write_log($signups);
        write_log('@end submit_form_success');
        $response = wp_swift_do_signup(parent::get_form_data(), $signups, $listid, $listid_unlink);
        return $response;  
        // return array("html" => wp_swift_signup_error_html(),  "session" => null, "response" => null);        
    }


    public function submit_form_success_3($post, $ajax) {
        $reponse = '';
        $form_data = parent::get_form_data();
        $inputs = $form_data[0]["inputs"];
        $first_name = $inputs["form-first-name"]["clean"];
        $last_name = $inputs["form-last-name"]["clean"];
        // $username = $inputs["form-username"]["clean"];
        $email = $inputs["form-email"]["clean"];//strtolower()

        // require plugin_dir_path( __DIR__ ) . 'mailin-api-php/V2.0/Mailin.php';
        $mailin = new Mailin('https://api.sendinblue.com/v2.0', '', 5000);    
        
        //Optional parameter: Timeout in MS

        // $data = array( "id" => 2,
        //       "users" => array('example1@example.net','example2@example.net')
        //     );
        $data = array( "email" => $email,
            "attributes" => array( "FIRSTNAME" => $first_name, "LASTNAME" => $last_name, "DOUBLE_OPT-IN" => 1 ),
            "listid" => array(5),
            // "listid_unlink" => array(2,5)
        ); 

        $response = null;//$mailin->create_update_user($data);
        write_log( $response );

// echo "<pre>"; var_dump( $mailin->create_update_user($data) ); echo "</pre>";
        return array( 
            "html" => "Thank you for your details", 
            "session" => array( 
                "first_name" => $first_name,
                "last_name" => $last_name,
                "email" => $email,
            ),
            "response" => $response,
        );
    }    


    public function submit_form_success_2($post, $ajax) {
        // write_log($post);
        // write_log($ajax);
        $reponse = '';
        $form_data = parent::get_form_data();
        $inputs = $form_data[0]["inputs"];
        $first_name = $inputs["form-first-name"]["clean"];
        $last_name = $inputs["form-last-name"]["clean"];
        $username = $inputs["form-username"]["clean"];
        $email = $inputs["form-email"]["clean"];//strtolower()

       
        $username_exists = username_exists( $username );
        $email_exists = email_exists($email);

        if ( $username_exists ) {
            $reponse .= '<p>This username already exists!</p>';
        }
        if ( $email_exists ) {
            $reponse .= '<p>This email_exists already exists!</p>';
        } 

        if ( !$username_exists && !$email_exists ) {
            $user_id = $this->save_user($email, $username, $first_name, $last_name);
            $reponse .= '<p>Great. Please check your email for a link to see your downloads</p>';
        }       
        // if ( !$user_id and email_exists($user_email) == false ) {
        //     $random_password = wp_generate_password( $length=20, $include_standard_special_chars=false );
        //     $user_id = wp_create_user( $user_name, $random_password, $user_email );
        //     $user_id = wp_update_user( array( 'first_name' => $user_first_name, 'last_name' => $user_last_name ) );
        // } else {
        //     $random_password = __('User already exists.  Password inherited.');
        // }

        $args = array(
            'ID' => 1,
            'role' => 'administrator',
        );
        wp_update_user($args);        
        return $reponse;
    }    


    public function save_user($email, $username, $first_name, $last_name) {

        $password = wp_generate_password( 20, true );
        // $password = 'password';
        $user_id = wp_create_user ( $username, $password, $email );
        $display_name =  $first_name;

        $args = array(
            'ID' => $user_id,
            'role' => 'subscriber',
            'nickname' => $display_name,
            'first_name' => $first_name,
            'last_name' => $last_name,
            'user_nicename' => $display_name,
            'display_name' => $display_name,
            // 'user_registered' => $this->date(),
            'show_admin_bar_front' => 'false',
        );
        wp_update_user($args);
        write_log($args);
        // $user = new WP_User( $user_id );
        // update_user_meta( $user_id, 'gdpr', $this->lp_code );
        // wp_mail( $email_address, 'Welcome!', 'Your password is: ' . $password );
        return $user_id;
    }    
}

function wp_swift_do_signup($form_data, $signups, $listid = array(), $listid_unlink = null) {
    $inputs = $form_data[0]["inputs"];
    write_log($inputs);
    if ( class_exists('Mailin') &&  isset($inputs["form-first-name"]["clean"]) && isset($inputs["form-last-name"]["clean"])) {
        // write_log('1 wp_swift_do_signup');
        $data = array();
        $save = false;
            
        $session = array();
        $first_name = $inputs["form-first-name"]["clean"];
        $last_name = $inputs["form-last-name"]["clean"];

        $session = array ( 
            "first_name" => $first_name,
            "last_name" => $last_name,
        );

        $data = array( 
            "attributes" => array( "FIRSTNAME" => $first_name, "LASTNAME" => $last_name ),//, "DOUBLE_OPT-IN" => 1
            "listid" => $listid,
        );      

        // if () {
            
        // }
        // if () {

        // }
             
        // $data = array( 
        //     "email" => $email,
        //     "attributes" => array( "FIRSTNAME" => $first_name, "LASTNAME" => $last_name, "DOUBLE_OPT-IN" => $double_optin, "SMS" => "+199-73-9331169" ),
        //     "listid" => $listid,//,
        //     // "listid_unlink" => array(2,5)
        // ); 
// if (in_array("email", $signups) || in_array("sms", $signups)) {
        // write_log('2 wp_swift_do_signup');
        if ( isset( $inputs["form-email"]["clean"]) ) {
            $email = $inputs["form-email"]["clean"];//strtolower()
            $session["email"] = $email;
            if ( in_array("email", $signups) ) {
                $data["email"] = $email;
                $save = true;
            }
        }
        if ( isset( $inputs["form-phone"]["clean"]) ) {
            $phone = $inputs["form-phone"]["clean"];
            $session["phone"] = $phone;
            $phone = str_replace('+', '', $phone);
            $phone = str_replace('-', '', $phone);
            if ( in_array("sms", $signups) ) {
                $data["attributes"]["SMS"] = $phone;
                $save = true;
            }
        }
        // if ( in_array("email", $signups) && isset($email)) {
        //     $data["email"] = $email;
        //     $session["email"] = $email;
            
        // }        


        // write_log('3 wp_swift_do_signup');
        if (is_array( $listid_unlink )) {
            $data["listid_unlink"] = $listid_unlink;
        }

        if ($save) {
            write_log('save mailin.....');
            // $mailin = null;
            // $mailin_response = null;
            $mailin = new Mailin('https://api.sendinblue.com/v2.0', wp_swift_get_mailin_api(), 5000);//Optional parameter: Timeout in MS  
            write_log($data);
            $mailin_response = $mailin->create_update_user($data);

            $response = array("html" => wp_swift_signup_html(),  "session" => $session, "response" => $mailin_response);
            return $response;
        }
        else {
            $response = array("html" => wp_swift_nosignup_html(),  "session" => $session, "response" => null);
            return $response;
        }

    }//isset($inputs["form-first-name"]["clean"]) && isset($inputs["form-last-name"]["clean"])
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