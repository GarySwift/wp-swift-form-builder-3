<?php
/*
 * Declare a new class that extends the form builder
 * 
 * @class       WP_Swift_Form_Builder_Login_Form
 * @extends     WP_Swift_Form_Builder_Plugin
 *
 */
class WP_Swift_Form_Builder_Contact_Form extends WP_Swift_Form_Builder_Parent {  
   
    /*
     * Variables
     */    
    private $headers = array('Content-Type: text/html; charset=UTF-8');
    private $post_id = null;
    private $to_email = null;
    private $forward_email = null;
    private $save_submission = null;
    private $date;
    private $send_email = false;//Debug variable. If false, emails will not be sent
    private $send_marketing = true;
    private $title;
    private $response_subject;
    private $browser_output_header;
    private $response_message;
    private $auto_response_message;
    private $auto_response_subject;
    private $to = array();
    


    /*
     * Initializes the plugin.
     */
    public function __construct( $form_id, $post_id = null, $hidden = array(), $type = 'contact' ) {//$args = array()
        parent::__construct( $form_id, $post_id, $hidden, $type );
    }    
    
    /*
     * Form Processing
     */
    public function get_response($post) {
        $form_set = false;
        $html = parent::process_form($post, true);
        if (parent::get_form_data()) {
           $form_set = true;
        }
        $response = array(
            "form_set" => $form_set,
            "error_count" => parent::get_error_count(),
            "html" => $html,
        ); 
        return $response;      
    }

    private function before_send_email() {
        $form_post_id = parent::get_form_post_id();
        $post_id = parent::get_post_id();        
        $this->date = ' - ' . date("Y-m-d H:i:s") . ' GMT';
        /*
         * These are the default form settings
         */
        $blogname = get_option('blogname');
        // $post_title = get_the_title($post_id);
        $title = $blogname;

        if ( isset($post["title"]) && !empty($post["title"]) ) {
            $title = $post["title"];
        }
                                
        // Set reponse subject for email
        $this->response_subject = "New Enquiry".$this->date;
        // Set the response that is set back to the browser
        $this->browser_output_header = 'Hold Tight, We\'ll Get Back To You';
        // Start the reponse message for the email
        $this->response_message ='<h3>For the attention of '.$title.' Admin</h3>';
        $this->response_message .= '<p>A website user has made the following enquiry.</p>';   


        //Set auto_response_message
        $this->auto_response_message = '<p>Thank you very much for your enquiry. A <b>'.$title.'</b> representative will be contacting you shortly.</p>';
        // The auto-response subject
        $this->auto_response_subject='Auto-response (no-reply)';   

        $this->to = get_option('admin_email');//array()

        $options = get_option( 'wp_swift_form_builder_settings' );
        if (isset($options['wp_swift_form_builder_checkbox_debug_mode']) && $options['wp_swift_form_builder_checkbox_debug_mode'] === '1') {
            $this->send_email=false;
        }


        /*
         * Now, we can override the default settings if they are set
         */
        if (function_exists('get_field')) {
            
            if( get_field('debugging_stop_email', 'option') ) {
                $this->send_email = false;
            } 

            if(get_field('email', $post_id) ) {

                $emails = get_field('email', $post_id);
                $emails_array = explode(' ', $emails);
                    
                if ( count($emails_array) ) {

                    $this->to = array();
                    foreach ($emails_array as $key => $email) {
                        if (filter_var($email, FILTER_VALIDATE_EMAIL)) {
                            $this->to[] = $email;
                        }
                    }
                }
            }
            elseif (get_field('to_email', $form_post_id )) {
                // If a to_email is set in ACF, send the email there instead of the admin email
                $this->to = get_field('to_email', $form_post_id ); 
            }
            // Set reponse subject for email
            if (get_field('response_subject', $form_post_id )) {
                $this->response_subject = get_field('response_subject', $form_post_id ); 
            }
            // Start the reponse message for the email
            if ( !$post_id && get_field('response_message', $form_post_id ) ) {
                $this->response_message = get_field('response_message', $form_post_id );
            }
            //Set auto_response_message
            if ( !$post_id && get_field('auto_response_message', $form_post_id ) ) {
                $this->auto_response_message = get_field('auto_response_message', $form_post_id );
            }
            // Set the response that is set back to the browser
            if (get_field('browser_output_header', $form_post_id )) {
                $this->browser_output_header = get_field('browser_output_header', $form_post_id );
            } 
            // The auto-response subject
            if( get_field('auto_response_subject', $form_post_id) ) {
                $this->auto_response_subject = get_field('auto_response_subject', $form_post_id);
            }
            if( get_field('save_submission', $form_post_id) ) {
                $this->save_submission = array(                        
                    "title" => $title,
                    "attach" => array(
                        "email" => json_encode($this->to),
                        "post_id" => $post_id,
                    ),                  
                );
            }
            $forward_email = get_field('forwarding_emails', $form_post_id);
            if ( !empty($forward_email)) {
                $this->forward_email = $forward_email;
            }

            $to_email_callback = get_field('to_email_callback', $form_post_id);
            if ( !empty($to_email_callback) && function_exists( $to_email_callback )) {
                $to_email = $to_email_callback();
                if ( is_email( $to_email ) ) {
                    $this->to = $to_email;
                }
            }
        }                          
    }
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
        $this->before_send_email();

        // Start making the string that will be sent in the email
        $email_string = $this->response_message;
        $key_value_table = $this->build_key_value_table();
        $email_string .= $key_value_table;
        $email_string .= $this->build_page_details();
        $signup = $this->do_signup_api( $post );
        $email_string .= $this->do_signup_third_party_wrap($signup);
        $attachments = parent::get_attachments();
        $debug_info = '';
        // echo '<pre>3 $attachments = parent::get_attachments(): '; var_dump($attachments); echo '</pre>';
        // echo wp_swift_wrap_email($email_string);
        /*
         * Send the email to the admin/office
         */
        if ($this->send_email) {
            // foreach ($this->to as $key => $to_email) {
                if (empty($attachments)) {
                    $status = wp_mail($this->to, $this->response_subject.$this->date, wp_swift_wrap_email($email_string), $this->headers);
                }
                else {
                    $status = wp_mail($this->to, $this->response_subject.$this->date, wp_swift_wrap_email($email_string), $this->headers, $attachments);
                }
                
            // }

            if (isset($this->forward_email)) {
                foreach ($this->forward_email as $key => $forward_email) {
                    $status = wp_mail($forward_email, '[Fwd:] '.$this->response_subject.$this->date, wp_swift_wrap_email($email_string), $this->headers);
                }
            }         
        }
        else {
            $debug_info .= "<pre>Debugging mode is on so no emails are being sent.</pre>";
            $debug_info .= "<br><p>Emails will be sent to here: </p>";
            if (count($attachments)) {
            }
            // $attachments = 
            // echo '<pre>3 $helper->get_attachments(): '; var_dump($parent->helper->get_attachments()); echo '</pre>';
            if (function_exists('write_log')) {
                write_log('$this->to:');write_log($this->to);
                write_log('$this->forward_email: ' . $this->forward_email);
                error_log( "Debugging mode is on so no emails are being sent." );
            }
            // foreach ($this->to as $key => $to_email) {
                $debug_info .= $this->to.'<br>';
            // }

            if (isset($this->forward_email)) {
                foreach ($this->forward_email as $key => $forward_email) {
                    $debug_info .= '[Fwd:] '.$forward_email.'<br>';
                }
            }              
        }

        /*
         * Save submission as CPT
         */
        if ( $this->save_submission ) {           
            $attach = null; 
            $save_submission_title = $this->response_subject . $this->date; 
            if (isset($this->save_submission["title"])) {
                 $save_submission_title = $this->save_submission["title"] . ' - ' . $save_submission_title;
            }
            if (isset($this->save_submission["attach"])) {
               $attach = $this->save_submission["attach"]; 
            }
            $submission = new WP_Swift_Form_Submission( $save_submission_title, $email_string, $attach ); 
        }

        /*
         * If the user has requested it, send an email acknowledgment
         */
        
        $user_output_footer = '';

        $user_email_string = $this->auto_response_message.'<p>A copy of your enquiry is shown below.</p>'.$key_value_table.$this->do_signup_customer_wrap($signup);

        $user_confirmation_email = parent::get_user_confirmation_email();

        $form_data = parent::get_form_data();

        $form_data = $form_data[0];

        if ( ($user_confirmation_email=== 'ask' && isset($post["mail-receipt"])) || $user_confirmation_email=== 'send' )  {
 
            if ($this->send_email) {

                if (isset($form_data["inputs"]['form-email']['clean'])) {
                    $status = wp_mail($form_data["inputs"]['form-email']['clean'], $this->auto_response_subject, wp_swift_wrap_email($user_email_string), $this->headers);
                }
            }
        
            $user_output_footer .= '<p>A confirmation email has been sent to you including these details.</p>';
        }

        if ( !$this->send_email ) {
            $user_output_footer .= $debug_info;           
        }

        /*
         * Return the html
         */              
        return $this->build_confirmation_output($ajax, $this->browser_output_header, $this->auto_response_message, $key_value_table, $user_output_footer, $signup);
    } 

    private function do_signup_third_party_wrap($html) { 
        if ($html) {
            ob_start();
            ?>
                <h4>Marketing Information</h4>
                <p>This customer has opted-in to receive marketing information through the following methods </p>
                <?php echo $html ?>
                <p>Please only contact the customer for MARKETING RELATED activity through the methods they have specified (opted in) too. If no methods are specified you cannot contact the customer for Marketing messages, only contact them in relation to this query and nothing else.</p> 
            <?php
            $html = ob_get_contents();
            ob_end_clean();
            
            return $html;
        }
    } 
    private function do_signup_customer_wrap($html) {  
        if ($html) {
            ob_start();
            ?>
                <h4>Marketing Information</h4>
                <?php echo $html ?>
            <?php
            $html = ob_get_contents();
            ob_end_clean();
            
            return $html;
        }
    }     

    private function do_signup_api( $post ) {
        $gdpr_settings = parent::get_gdpr_settings();
        $html = '';
        $list_ids = array();
        $list_id_array = array();

        if ($gdpr_settings) {
            ob_start();
            foreach ($gdpr_settings["opt_in"] as $key => $opt_in): 
                $email = "No";
                $sms = "No";
                if ( isset($post["sign-up-$key"]) ) {   
                        
                    $signups = $post["sign-up-$key"];        

                    if ( in_array("email", $signups) ) {
                        $email = "Yes";
                    }
                    if ( in_array("sms", $signups) ) {
                        $sms = "Yes";
                    }  
                    if ($email === "Yes" || $sms === "Yes") {
                        
                        if ( $opt_in['list_ids'] ) {
                            $list_ids = $opt_in['list_ids'];
                            $list_id_temp_array = explode(',', $list_ids);
                            foreach ($list_id_temp_array as $id) {
                                $int_id = (int) trim($id);
                                if ( is_int( $int_id ) && $int_id > 0 ){
                                    $list_id_array[] =  $int_id;
                                }
                            }
                            if ( $this->send_marketing && count($list_id_array) ) {
                                $response = wp_swift_do_signup( parent::get_form_data(), $signups, $list_id_array );  
                            }                          
                        }            
                    }                  
                }
                ?>

                <p><?php echo $opt_in["message"] ?></p>

                <p>Email: <?php echo $email; ?>, SMS: <?php echo $sms; ?></p>

                <?php if ( !$this->send_marketing ): ?>
                    <pre>Marketing debugging is on so user details were not saved.</pre>
                <?php endif ?>

            <?php endforeach;

            $html = ob_get_contents();
            ob_end_clean();
        }

        return $html;
    }

    private function build_page_details() {
        $html = '';
        $post_id = parent::get_post_id();
        if ( $post_id ):
            $url = get_the_permalink( $post_id );
            ob_start(); ?>
                
                <div id="page-details"> 

                    <div><small>Sent from page:</small></div>
                    <p><a href="<?php echo $url ?>" target="_blank"><b><?php echo get_the_title( $post_id ); ?></b> - <?php echo $url ?></a></p>

                </div><!-- @end #page-details -->

            <?php

            $html = ob_get_contents();
            ob_end_clean();
        endif;
        return $html;
    }

    private function build_confirmation_output($ajax, $browser_output_header, $auto_response_message, $key_value_table, $user_output_footer, $signup = '') {
        $class = 'standard';
        if ($ajax) {
            $class = 'ajax';
        }  
        ob_start(); ?>

            <div id="form-success-message" class="form-message <?php echo $class ?>"> 
               
                <h3><?php _e( $browser_output_header, 'wp-swift-form-builder' ); ?></h3>                    
                <div>
                    <?php _e( $auto_response_message, 'wp-swift-form-builder' ); ?>                       
                </div>
                <p><?php _e( 'A copy of your enquiry is shown below.', 'wp-swift-form-builder' ); ?></p>

                <?php echo $key_value_table; ?>

                <?php echo $this->do_signup_customer_wrap($signup) ?>

                <?php if ($user_output_footer): ?>
                    <p><?php _e( $user_output_footer, 'wp-swift-form-builder' ); ?></p>
                <?php endif ?>

            </div><!-- @end #form-success-message -->

        <?php
        $html = ob_get_contents();
        ob_end_clean();
        return $html;
    }

    private function build_key_value_table() {
        ob_start(); 
        ?><table class="form-feedback" id="print-table" style="width:100%">
                    <tbody><?php 
                        
                    foreach (parent::get_form_data() as $section_key => $section): 
                        foreach ($section["inputs"] as $input_key => $section_input):
                
                        if (isset($section_input['data_type'])): $type = $section_input['data_type']; ?>

                        <?php 
                            if ($type=='section'): ?>
                        <tr>
                            <th colspan="2" style="width:100%; text-align:center">
                                <h3><?php $this->table_cell_header($input_key, $section_input) ?></h3>
                            </th>
                        </tr>
                            <?php 
                            else: 
                                if ($section_input['clean'] !== ''): ?>
                        <tr>
                            <th style="width:30%; text-align:left"><?php $this->table_cell_header($input_key, $section_input) ?></th>
                            <td><?php if ( $section_input['data_type'] == 'checkbox' ): ?>
                                <table>
                                    <?php foreach ($section_input['options'] as $option): ?>
                                        <?php if ($option["checked"]): ?>
                                            <tr>
                                                <td><?php echo $option["option"]; ?></td>
                                            </tr>
                                        <?php endif ?>
                                    <?php endforeach ?>
                                </table>
                            <?php elseif ($section_input['type']=='select'): ?>
                                <?php echo $section_input['clean'] ?>
                            <?php else: ?>
                                 <?php echo $section_input['clean'] ?>
                            <?php endif;
                            ?></td>
                        </tr>    
                        <?php   endif;//@end if ($section_input['clean'] !== '')                               
                            endif;//@end if ($type=='section')
                        ?><?php endif;

                        endforeach;//@end foreach ($section["inputs"] as $input_key => $section_input):
                    endforeach;//@end foreach ($this->form_inputs as $section_key => $section):
                    ?>   
                    </tbody>
                </table><!-- @end table -->
        <?php
        $html = ob_get_contents();
        ob_end_clean();
        return $html;
    }

    private function table_cell_header($input_key, $input) {
        echo $input["label"];
    }   
}