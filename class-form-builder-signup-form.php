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
        $response = array(
            "form_set" => $form_set,
            "error_count" => parent::get_error_count(),
            "html" => $process_form["html"],
            "session" => $process_form["session"],
            "response" => $process_form["response"],
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

        if (isset($post["sign-up"])) {
            $response = wp_swift_do_signup(parent::get_form_data(), $post["sign-up"], array(5));
            write_log($response);  
            return $response;    
        }
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
        $mailin = new Mailin('https://api.sendinblue.com/v2.0', '7k0yHG1javQ93zS2', 5000);    
        
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

    if (in_array("email", $signups) || in_array("sms", $signups)) {
        $inputs = $form_data[0]["inputs"];
        $data = array();
        if (isset($inputs["form-first-name"]["clean"]) && isset($inputs["form-last-name"]["clean"])) {
            $session = array();
            $first_name = $inputs["form-first-name"]["clean"];
            $last_name = $inputs["form-last-name"]["clean"];

            $session = array ( 
                "first_name" => $first_name,
                "last_name" => $last_name,
            );

            $data = array( 
                "attributes" => array( "FIRSTNAME" => $first_name, "LASTNAME" => $last_name ),
                "listid" => $listid,
            );      

            if (isset( $inputs["form-email"]["clean"])) {
                $email = $inputs["form-email"]["clean"];//strtolower()
            }
            if (isset( $inputs["form-phone"]["clean"])) {
                $phone = $inputs["form-phone"]["clean"];
                $phone = str_replace('+', '', $phone);
                $phone = str_replace('-', '', $phone);
            }
         
            // $data = array( 
            //     "email" => $email,
            //     "attributes" => array( "FIRSTNAME" => $first_name, "LASTNAME" => $last_name, "DOUBLE_OPT-IN" => $double_optin, "SMS" => "+199-73-9331169" ),
            //     "listid" => $listid,//,
            //     // "listid_unlink" => array(2,5)
            // ); 

            if (isset($email)) {
                $data["email"] = $email;
                $session["email"] = $email;
                $save = true;
            }

            if (isset($phone)) {
                $data["attributes"]["SMS"] = $phone;
                $session["phone"] = $phone;
                $save = true;
            }

            if (is_array($listid_unlink )) {
                $data["listid_unlink"] = $listid_unlink;
            }

            if ($save) {
                write_log('save mailin.....');
                $mailin = new Mailin('https://api.sendinblue.com/v2.0', '7k0yHG1javQ93zS2', 5000);//Optional parameter: Timeout in MS  
                write_log($data);
                $mailin_response =  $mailin->create_update_user($data);

                $response = array("html" => wp_swift_signup_html(),  "session" => $session, "response" => $mailin_response);

                return $response;
            }

        }//isset($inputs["form-first-name"]["clean"]) && isset($inputs["form-last-name"]["clean"])        
    }//in_array("email", $signups) || in_array("sms", $signups)
    return;
}

function wp_swift_signup_html() {
    ob_start();
    ?>
        <h3>You are Good to Go!</h3>
        <p>Thank you for your datails. Your files are available below.</p>
    <?php
    $html = ob_get_contents();
    ob_end_clean();
    return $html;
}