<?php
/**
 * summary
 */
class WP_Swift_Form_Builder_Marketing
{
    /**
     * summary
     */
    public function __construct()
    {
        
    }

    public function signup_api( $post, $form_data, $marketing, $auto_consent, $gdpr_settings, $send_marketing, $at_least_one_option_required = false ) {
        // write_log('signup_api() $auto_consent: ');write_log($auto_consent);
        // write_log('$post: ');write_log($post);
        if (!isset($post["marketing-sign-up"])) 
            return null;    
        // $marketing =  parent::get_marketing();
        // $gdpr_settings = parent::get_gdpr_settings();
        $opt_ins = null;

        if ( $marketing == 'mailin' && isset($gdpr_settings["opt_in"]) ) {
            $opt_ins = $gdpr_settings["opt_in"];
        }  
        elseif ( $marketing == 'mailchimp' && isset($gdpr_settings["mailchimp_opt_in"]) ) {
            $opt_ins = $gdpr_settings["mailchimp_opt_in"];
            // write_log('1 $gdpr_settings: ');write_log($gdpr_settings);
        }         

        $html = '';
        $list_ids = array();
        $list_id_array = array();
        $response_msg = '';
        
        if ($opt_ins) {
            ob_start();
            foreach ($opt_ins as $key => $opt_in): 
                $email = "No";
                $sms = "No";
                $direct_mail = "No";
                $customized_online_advertising = "No";

                // write_log($key.' $opt_in: ');write_log($opt_in);
                if ( isset($post["sign-up-$key"]) ) {   
                        
                    $signups = $post["sign-up-$key"];        

                    if ( in_array("email", $signups) ) {
                        $email = "Yes";
                    }
                    if ( in_array("sms", $signups) ) {
                        $sms = "Yes";
                    } 
                    if ( in_array("direct_mail", $signups) ) {
                        $direct_mail = "Yes";
                    } 
                    if ( in_array("customized_online_advertising", $signups) ) {
                        $customized_online_advertising = "Yes";
                    } 
                                                          
                    if ($auto_consent || $email === "Yes" || $sms === "Yes" || $direct_mail === "Yes" || $customized_online_advertising === "Yes") {

                        // if ( $opt_in['list_ids'] ) {
                            $list_id_array_default = $this->get_default_group();
                            if (!$list_id_array_default) {
                                $list_ids = $opt_in['list_ids'];
                                $list_id_temp_array = explode(',', $list_ids);
                                foreach ($list_id_temp_array as $id) {
                                    // $int_id = (int) trim($id);
                                    // if ( is_int( $int_id ) && $int_id > 0 ){
                                    //     $list_id_array[] =  $int_id;
                                    // }
                                    $list_id_array[] = trim($id);//$int_id;
                                }
                                // write_log('$send_marketing: ');write_log($send_marketing);
                                // write_log('count($list_id_array): ');write_log(count($list_id_array));
                                // write_log('$list_id_array: ');write_log($list_id_array);                                
                            }
                            else {
                                $list_id_array = $list_id_array_default;
                            }

                            // write_log('$send_marketing: ');write_log($send_marketing);
                            // write_log('count($list_id_array): ');write_log(count($list_id_array));
                            if ( $send_marketing && count($list_id_array) ) {
                                $signup_response = $this->do_signup( $marketing, $form_data, $signups, $auto_consent, $list_id_array );  
                                // write_log('HTML >> $signup_response: ');write_log($signup_response);
                            }                          
                        // }
                        // $signup_response = $this->do_signup( parent::get_form_data(), $signups, $list_id_array );            
                    }                  
                }
                ?>

                <p><?php echo $opt_in["message"] ?></p>
				<?php //write_log('DEBUG $opt_in: ');write_log($opt_in); write_log('$auto_consent: ');write_log($auto_consent); ?>
                <?php if( $auto_consent || in_array("email", $opt_in["options"]) ) echo '<p>Email: '.$email.'</p>'; ?>

                <?php if( is_array($opt_in["options"]) && in_array("sms", $opt_in["options"]) ) echo '<p>SMS: '.$sms.'</p>'; ?>

                <?php if( $auto_consent || in_array("direct_mail", $opt_in["options"]) ) echo '<p>Direct Mail: '.$direct_mail.'</p>'; ?>

                <?php if( $auto_consent || in_array("customized_online_advertising", $opt_in["options"]) ) echo '<p>Customized Online Advertising: '.$customized_online_advertising.'</p>'; ?>

                <?php if (isset($signup_response["html"])): ?>
                    <?php echo $signup_response["html"] ?>
                <?php endif ?>

                <?php if ( !$send_marketing ): ?>
                    <pre>Marketing debugging is on so user details were not saved.</pre>
                <?php endif ?>

            <?php endforeach;

            $html = ob_get_contents();
            ob_end_clean();
        }
        
        // write_log('parent signup_api() $html: ');write_log($html);
        // write_log('$response: ');write_log($response);
        // write_log('$signup_response: ');write_log($signup_response);
        if (isset($signup_response["response_header"])) {
            $html = $signup_response["response_header"] . $html;//'<h2>'.$response_header.'</h2>';
        }
        $response = array("html" => $html);
        if (isset($signup_response)) {
            $response = array_merge($response, $signup_response);
        }
        elseif($at_least_one_option_required) {
            $response["error"] = true;
            $response["msg"] = "Please select at least one marketing option!"; 
        }
        return $response;
    }

	public function do_signup($marketing, $form_data, $signups, $auto_consent = false, $list_id_array = array(), $list_id_array_unlink = null) {   
	    switch ($marketing) {
	        case "mailin":
	            return $this->do_signup_sendinblue($form_data, $signups, $auto_consent, $list_id_array, $list_id_array_unlink);
	            break;
	        case "mailchimp":
	            return $this->do_signup_mailchimp($form_data, $signups, $auto_consent, $list_id_array, $list_id_array_unlink);;
	            break;        
	    }
	    return;  
	}

	public function do_signup_mailchimp($form_data, $signups, $auto_consent, $list_id_array = array(), $list_id_array_unlink = null) {  
	    /**
	     * $signup_status
	     *
	     * "pending" means users recieve an email
	     * "subscribed" means they are directly added
	     */    
	    $signup_status = "subscribed";    
	    $response = null;
	    $session = array();// This will send back the user data to store in local storage 
	    $data = array();// This will be the user data we send to SendInBlue
	    $save = false;// We will only save if SMS or Email is selected    
	    $api_key = $this->get_marketing_api();// The user API key
	    $get_user_data_if_registered = true;
	    $first_name = $this->get_form_input($form_data, "form-first-name" );
	    $last_name = $this->get_form_input($form_data, "form-last-name" );
	    $contact_name = $first_name . ' ' . $last_name;
	    // write_log('$first_name: ');write_log($first_name);
	    // write_log('$last_name: ');write_log($last_name);
	    if (!$first_name) {
	        $contact_name = $this->get_form_input($form_data, "form-contact-name" );// potential issue here if name field is not the the same
	        $contact_name_array = explode(' ', $contact_name, 2);// Split contact name in two
	        $first_name = $contact_name_array[0];
	        $last_name = '';
	        if (isset($contact_name_array[1]))
	            $last_name = $contact_name_array[1];        
	    }

	    $email = $this->get_form_input($form_data, "form-email" );
	    $email = strtolower($email);
	    $phone = $this->get_form_input($form_data, "form-phone" ); 
	    if (!$phone) $phone = $this->get_form_input($form_data, "form-company-phone" );
	     

	    // The data to send to the API
	    $post_data = array(
	        "email_address" => $email, 
	        "status" => $signup_status,
	        "merge_fields" => array(
	            "FNAME" => $first_name,
	            "LNAME" => $last_name,
	            "PHONE" => $phone,
	        ),
	    );

	    $post_data["marketing_permissions"] = $this->set_mailchimp_marketing_permissions($signups, $auto_consent);

	    $company = $this->get_form_input($form_data, "form-company-name" );
	    if ($company) {
	        $post_data["merge_fields"]["COMPANY"] = $company;
	    }
	    $job_title = $this->get_form_input($form_data, "form-company-position" );
	    if ($job_title) {
	        $post_data["merge_fields"]["JOBTITLE"] = $job_title;
	    }
	    $country = $this->get_form_input($form_data, "form-country" );
	    $state = '';
	    if ($country) {
	        $country_states = array(
	            'united-states' => 'form-state',
	            'australia' => 'form-australia-state',
	            'canada' => 'form-canada-state',
	            'china' => 'form-china-state',
	            'india' => 'form-india-state',
	            'japan' => 'form-japan-state',
	        );
	        if (array_key_exists($country, $country_states)) {
	            $state = $this->get_form_input($form_data, $country_states[$country] );
	        }
	        if (function_exists("wp_taoglas_country_from_value")) {
	            $post_data["merge_fields"]["COUNTRY"] = wp_taoglas_country_from_value($country);
	        }
	        else {
	        	$post_data["merge_fields"]["COUNTRY"] = $country;
	        }
	        
	        // write_log('$country: ');write_log($country);
	    }
	    if ($state) {
	        $post_data["merge_fields"]["STATE"] = $state;
	    }
	    // write_log('$post_data: ');write_log($post_data);

	    // $session_data = array(
	    //     "firstName" => $first_name,
	    //     "lastName" => $last_name,
	    //     "email" => $email,
	    //     "phone" => $phone,
	    //     "jobTitle" => $job_title,
	    //     "company" => $company,
	    //     "country" => $country, 
	    //     "state" => $state,       
	    // ); 
	    $session_data = array(
	        "form-first-name" => array('type' => 'input', 'val' => $first_name),
	        "form-last-name" => array('type' => 'input', 'val' => $last_name),
	        "form-contact-name" => array('type' => 'input', 'val' => $first_name . ' ' .$last_name),
	        "form-email" => array('type' => 'input', 'val' => $email),
	        "form-phone" => array('type' => 'input', 'val' => $phone),
	        "form-company-phone" => array('type' => 'input', 'val' => $phone),
	        "form-company-position" => array('type' => 'input', 'val' => $job_title),
	        "form-company-name" => array('type' => 'input', 'val' => $company),
	        "form-country" => array('type' => 'select', 'val' => $country), 
	        "form-state" => array('type' => 'input', 'val' => $state, 'hidden' => true)      
	    );    
	    // write_log('$api_key: ');write_log($api_key);    
	    $data_center = substr($api_key,strpos($api_key,'-')+1);
	    // write_log('$data_center: ');write_log($data_center);
	    # This loop will run once ($list_id_array has a single array element at the moment)
	    // write_log('DEBUG: $list_id_array: ');write_log($list_id_array);
	    // $list_id_array = array();
	    foreach ($list_id_array as $list_id) {
	        # Setup cURL
	        $url = 'https://'.$data_center.'.api.mailchimp.com/3.0/lists/'.$list_id.'/members/';
	        // write_log('$url: ');write_log($url);
	        $ch = curl_init($url);
	        curl_setopt_array($ch, array(
	            CURLOPT_POST => TRUE,
	            CURLOPT_RETURNTRANSFER => TRUE,
	            CURLOPT_HTTPHEADER => array(
	                'Authorization: apikey '.$api_key,
	                'Content-Type: application/json'
	            ),
	            CURLOPT_POSTFIELDS => json_encode($post_data)
	        ));   
	        $api_response = curl_exec($ch);# Send the request
	        $api_response = json_decode($api_response, true);# Decode the response
	        // write_log('');write_log('$api_response: ');write_log($api_response);write_log('');
	        # End cURL
	        
	        if (isset($api_response["status"])) {
	            $response_msg = null;
	            $session["email"] = $email;
	            if ($api_response["status"] == "subscribed") {               
	                $response_header = "<h4>Thank You. You have been added to our Mailing List!</h4>";
	                $response_header .= "<p class='lead'>Please check your email for confirmation link.</p>";
	                // $response_header = "<p class='lead'>Please check your email for confirmation link.</p>";
	                $session_data["subscribed"] = true;   
	                $response["session"] = $session_data;  
	                $response["response_header"] = $response_header;
	            }            
	            elseif ($api_response["status"] == "pending") {
	                $response_msg = "Please check your email and click the link in order to complete your subscription.";
	            
	            }
	            elseif ($api_response["status"] == "400" && $api_response["title"] == "Member Exists") {
	                $response_msg = "Our records show that this email is already registered!";
	                // $session_data["subscribed"] = true;   
	                // $response["session"] = $session_data;
	                $get_user_data_if_registered = true;
	                if ($get_user_data_if_registered) {
	                    # WARNING!!! 
	                    # 
	                    # Be carefull doing this - it is sensitive data and we do not want to
	                    # expose user data to somebody impersonating someone by using their email.
	                    # 
	                    # However, this is how we get an already existing user via their email. 
	                    # This returns all of the Mailchimp saved user data.
	                    $api_user_response = $this->get_mailchimp_user_data($data_center, $list_id, $api_key, $email);
	                    // write_log('$api_user_response: ');write_log($api_user_response);
	                    if (isset($api_user_response["status"])) {
	                        $status = $api_user_response["status"];
	                        // write_log('this');write_log('$status: ');write_log($status);
	                        if ($status == "subscribed") {
	                            $session_data["subscribed"] = true;   
	                            $response["session"] = $session_data;                            
	                        }
	                        else {
	                            # This email is already registered but user has unsubscribed
	                            # This curl curl request will send a PATCH request to update their status
	                            $post_data["status"] = "pending";// Users must verify with an email
	                            $api_response_patch_request = $this->patch_mailchimp_user_data($data_center, $list_id, $api_key, $email, $post_data);
	                            // write_log('');write_log('$api_response_patch_request: ');write_log($api_response_patch_request);write_log('');
	                            if (isset($api_response_patch_request["status"])) {
	                                $status = $api_response_patch_request["status"];
	                                if ($status == "subscribed") {
	                                    $session_data["subscribed"] = true;   
	                                    $response["session"] = $session_data;                            
	                                }                                
	                            }                            
	                        }
	                    }
	                }        
	            }
	            elseif ($api_response["status"] == "400" && $api_response["title"] == "Invalid Resource") {
	                // write_log('>>> $api_response["status"] == "400" && $api_response["title"] == "Invalid Resource"');
	                // $response_msg = $api_response["detail"]; 
	                $response["error"] = true;
	                // $response["msg"] = $api_response["detail"]; 
	                $response_msg = $this->signup_error_html() 
	                . '<br>' . "Status 400: " . $api_response["title"]
	                . '<br>' . $api_response["detail"];                 

	            }            
	            elseif ($api_response["status"] == "400" && $api_response["title"] == "Forgotten Email Not Subscribed") {
	                // This situation should only occur when users have been deleted.
	                // Mailchimp does not allow deleted users to subscribe so we allow the user 
	                // download the files anyway.
	                $response_msg = "Our records show that this email was registered but the user has unsubscribed.";
	                $session_data["subscribed"] = true;   
	                $response["session"] = $session_data;        
	            }            
	            elseif ($api_response["status"] == "400") {
	                $response_msg = $this->signup_error_html() 
	                . '<br>' . "Status 400: " . $api_response["title"]
	                . '<br>' . $api_response["detail"]; 
	            }
	            else {
	                $response_msg = $api_response["status"];
	            }            
	            if ( $response_msg ) {
	                $response["html"] = '<p><b>'.$response_msg.'</b></p>';
	            }
	            // if ( $response_header ) {
	            //     $response["response_header"] = $response_header;
	            // }            
	        }           
	    }
	    return $response;
	}


	public function get_mailchimp_user_data($data_center, $list_id, $api_key, $email) {
	    # Setup cURL
	    $url = 'https://' . $data_center . '.api.mailchimp.com/3.0/lists/' . $list_id . '/members/' . md5($email);  
	    $ch = curl_init($url);
	    curl_setopt_array($ch, array(
	        CURLOPT_RETURNTRANSFER => TRUE,
	        CURLOPT_HTTPHEADER => array(
	            'Authorization: apikey '.$api_key,
	            'Content-Type: application/json'
	        ),
	    ));   
	    $api_response = curl_exec($ch);# Send the request
	    $api_response = json_decode($api_response, true);# Decode the response              
	    # End cURL
	    return $api_response;
	}

	public function patch_mailchimp_user_data($data_center, $list_id, $api_key, $email, $post_data) {
	    # Setup cURL
	    $ch = curl_init($url);
	    curl_setopt_array($ch, array(
	        CURLOPT_CUSTOMREQUEST => 'PATCH',
	        CURLOPT_RETURNTRANSFER => TRUE,
	        CURLOPT_HTTPHEADER => array(
	            'Authorization: apikey '.$api_key,
	            'Content-Type: application/json'
	        ),
	        CURLOPT_POSTFIELDS => json_encode($post_data)
	    )); 
	    $api_response = curl_exec($ch);# Send the request
	    $api_response = json_decode($api_response, true);# Decode the response              
	    # End cURL
	    return $api_response;
	}
	public function do_signup_sendinblue($form_data, $signups, $auto_consent, $list_id_array = array(), $list_id_array_unlink = null) {   
	    $sendinblue_account_type == 1;//
	    $session = array();// This will send back the user data to store in local storage 
	    $data = array();// This will be the user data we send to SendInBlue
	    $save = false;// We will only save if SMS or Email is selected    
	    $mailin = null;// This will be instantiated into a MailIn object
	    $mailin_response = null;// This will be the response form the API call 
	    $mailin_api_url = 'https://api.sendinblue.com/v2.0';// sendinblue url
	    $mailin_api_key = $this->get_marketing_api();// The user API key
	    $mailin_timeout = 5000;// Optional parameter: Timeout in MS    
	    $first_name = $this->get_form_input($form_data, "form-first-name" );
	    $last_name = $this->get_form_input($form_data, "form-last-name" );
	    $email = $this->get_form_input($form_data, "form-email" );
	    $phone = $this->get_form_input($form_data, "form-phone" );

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
	            $response = array("html" => $this->signup_html(),  "session" => $session, "response" => $mailin_response);
	            return $response;
	        }
	        else {
	            $response = array("html" => $this->nosignup_html(),  "session" => $session, "response" => null);
	            return $response;
	        }

	    }
	    $response = array("html" => $this->signup_error_html(),  "session" => $session, "response" => null);
	    return $response;
	}

	public function set_mailchimp_marketing_permissions($signups, $auto_consent = false) {   
	    $marketing_permissions = array();
	    $options = get_option( 'wp_swift_form_builder_settings' );

	    if (isset($options['wp_swift_form_builder_marketing_api_ids']['email']) && $options['wp_swift_form_builder_marketing_api_ids']['email'] != '') {
	        $set = $auto_consent;
	        if (in_array('email', $signups)) {
	            $set = true;
	        }
	        $email = array( 
	            "marketing_permission_id" => $options['wp_swift_form_builder_marketing_api_ids']['email'],
	            "enabled" => $set
	        ); 
	        $marketing_permissions[] = $email;         
	    }  

	    if (isset($options['wp_swift_form_builder_marketing_api_ids']['direct_mail']) && $options['wp_swift_form_builder_marketing_api_ids']['direct_mail'] != '') {
	        $set = $auto_consent;
	        if (in_array('direct_mail', $signups)) {
	            $set = true;
	        }
	        $direct_mail = array( 
	            "marketing_permission_id" => $options['wp_swift_form_builder_marketing_api_ids']['direct_mail'],
	            "enabled" => $set
	        ); 
	        $marketing_permissions[] = $direct_mail;         
	    } 

	    if (isset($options['wp_swift_form_builder_marketing_api_ids']['customized_online_advertising']) && $options['wp_swift_form_builder_marketing_api_ids']['customized_online_advertising'] != '') {
	        $set = $auto_consent;
	        if (in_array('customized_online_advertising', $signups)) {
	            $set = true;
	        }
	        $customized_online_advertising = array( 
	            "marketing_permission_id" => $options['wp_swift_form_builder_marketing_api_ids']['customized_online_advertising'],
	            "enabled" => $set
	        ); 
	        $marketing_permissions[] = $customized_online_advertising;         
	    } 
	    return $marketing_permissions;           
	}

	public function signup_html() {
	    ob_start();
	    ?>
	        <h3>You're good to go!</h3>
	        <p>Thanks for updating your preferences, your files are available below.</p>
	    <?php
	    $html = ob_get_contents();
	    ob_end_clean();
	    return $html;
	}

	public function nosignup_html() {
	    ob_start();
	    ?>
	        <h3>Thank You</h3>
	        <p>Thanks for completing our marketing preferences form, if you would like to opt-in in the future just visit us again. The brochure library is now available for download.</p>
	    <?php
	    $html = ob_get_contents();
	    ob_end_clean();
	    return $html;
	}
	public function signup_error_html() {
	    ob_start();
	    ?>
	        <h3>We're Sorry</h3>
	        <p>There was an error with the signup. Please contact site admin.</p>
	    <?php
	    $html = ob_get_contents();
	    ob_end_clean();
	    return $html;
	}

	public function get_marketing_api() {
	    $options = get_option( 'wp_swift_form_builder_settings' );
	    if (isset($options['wp_swift_form_builder_marketing_api']) && $options['wp_swift_form_builder_marketing_api'] != '') {
	        return $options['wp_swift_form_builder_marketing_api'];
	    } 
	}

	public function get_default_group() {
	    $options = get_option( 'wp_swift_form_builder_settings' );
	    if (isset($options['wp_swift_form_builder_marketing_api_group']) && $options['wp_swift_form_builder_marketing_api_group'] != '') {
	        return array($options['wp_swift_form_builder_marketing_api_group']);
	    } 
	}

	public function get_form_input($form_data, $key) {
	    if (isset( $form_data[0]["inputs"][$key]["clean"] )) {
	        return $form_data[0]["inputs"][$key]["clean"];
	    }
	    return '';
	}         
}