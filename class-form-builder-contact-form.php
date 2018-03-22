<?php
/*
 * Declare a new class that extends the form builder
 * 
 * @class       WP_Swift_Form_Builder_Login_Form
 * @extends     WP_Swift_Form_Builder_Plugin
 *
 */
class WP_Swift_Form_Builder_Contact_Form extends WP_Swift_Form_Builder_Parent {
    private $post_id = null;
    private $to_email = null;
    private $forward_email = null;
    private $save_submission = null;
    
    /*
     * Initializes the plugin.
     */
    public function __construct( $form_id, $post_id = null, $args = array() ) {
        // if (isset($post_id)) {
        //     $this->post_id = $post_id;
        // }
        // if (count($args)) {
        //     if (isset($args["to_email"]) && filter_var($args["to_email"], FILTER_VALIDATE_EMAIL)) {
        //         $this->to_email = $args["to_email"];
        //         write_log('$this->to_email: '.$this->to_email);
        //     }
            // if (isset($args["forward_email"]) && filter_var($args["forward_email"], FILTER_VALIDATE_EMAIL)) {
            //     $this->forward_email = $args["forward_email"];
            // } 
            // if (isset($args["save_submission"])) {
            //     $this->save_submission = $args["save_submission"];
            // }                        
        // }

        // if( get_field('auto_response_subject', $form_id) ) {
        //         $this->save_submission = array(                        
        //             "title" => get_the_title($post_id),
        //             "attach" => array(
        //                 "email" => '$to',
        //                 "post_id" => get_the_ID($post_id),
        //             ),                  
        //         );
        //     }
        // echo "<pre>array: "; var_dump($this->save_submission); echo "</pre>";
        parent::__construct( $form_id, $post_id );
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
        /*
         * Variables
         */
        $send_email=true;//Debug variable. If false, emails will not be sent
        $form_post_id = parent::get_form_post_id();
        $post_id = parent::get_post_id();

        if( get_field('debugging_stop_email', 'option') ) {
            $send_email = false;
        }  
        $options = get_option( 'wp_swift_form_builder_settings' );
        if (isset($options['wp_swift_form_builder_checkbox_debug_mode']) && $options['wp_swift_form_builder_checkbox_debug_mode'] === '1') {
            $send_email=false;
        }
        $date = ' - '.date("Y-m-d H:i:s").' GMT';
        $post_id_or_acf_option= '';//We can specify if it is an option field or use a post_id (https://www.advancedcustomfields.com/add-ons/options-page/)
        $headers = array('Content-Type: text/html; charset=UTF-8');
        $use_callout = true;
        if ($ajax) {
            $use_callout = false;
        }
        if ($ajax)
            $class = 'ajax';
        else
            $class = 'standard';
        /*
         * These are the default form settings
         */
        // If a debug email is set in ACF, send the email there instead of the admin email
        
        // if (isset($this->to_email)) {
        //     $to = $this->to_email;
        // }
        // else {
            $to = get_option('admin_email');
        // }
        // Set reponse subject for email
        $response_subject = "New Enquiry".$date;
        // Set the response that is set back to the browser
        $browser_output_header = 'Hold Tight, We\'ll Get Back To You';
        // Start the reponse message for the email
        $response_message ='<h3>For the attention of '.get_option('blogname').' Admin</h3>';
        $response_message .= '<p>A website user has made the following enquiry.</p>';
        if ($post_id) {
            $response_message ='<h3>For the attention of '.get_the_title($post_id).'</h3>';
            $response_message .= '<p>A website user has made the following enquiry:</p>';
        }
        //Set auto_response_message
        $auto_response_message = '<p>Thank you very much for your enquiry. A representative will be contacting you shortly.</p>';
        if ($post_id) {
            $auto_response_message = '<p>Thank you very much for your enquiry. A <b>'.get_the_title($post_id).'</b> representative will be contacting you shortly.</p>';
        }
        // The auto-response subject
        $auto_response_subject='Auto-response (no-reply)';

        /*
         * Now, we can override the default settings if they are set
         */
        if (function_exists('get_field')) {
            

            if(get_field('email', $post_id) ) {
                $to = get_field('email', $post_id);
            }
            // If a to_email is set in ACF, send the email there instead of the admin email
            elseif (get_field('to_email', $form_post_id )) {
                $to = get_field('to_email', $form_post_id ); 
            }
            // Set reponse subject for email
            if (get_field('response_subject', $form_post_id )) {
                $response_subject = get_field('response_subject', $form_post_id ); 
            }
            // Start the reponse message for the email
            if ( !$post_id && get_field('response_message', $form_post_id ) ) {
                $response_message = get_field('response_message', $form_post_id );
            }
            //Set auto_response_message
            if ( !$post_id && get_field('auto_response_message', $form_post_id ) ) {
                $auto_response_message = get_field('auto_response_message', $form_post_id );
            }
            // Set the response that is set back to the browser
            if (get_field('browser_output_header', $form_post_id )) {
                $browser_output_header = get_field('browser_output_header', $form_post_id );
            } 
            // The auto-response subject
            if( get_field('auto_response_subject', $form_post_id) ) {
                $auto_response_subject = get_field('auto_response_subject', $form_post_id);
            }
            if( get_field('auto_response_subject', $form_post_id) ) {
                $this->save_submission = array(                        
                    "title" => get_the_title($post_id),
                    "attach" => array(
                        "email" => $to,
                        "post_id" => $post_id,
                    ),                  
                );
            }
            $forward_email = get_field('forward_email', $form_post_id);
            if ($forward_email) {
                $this->forward_email = $forward_email;
            }
        }
        // Debug override - send all mail to me
        // $to = "gary@brightlight.ie";
        $to = "eleanor@redco.ie";

        // Start making the string that will be sent in the email
        $email_string = $response_message;
        $key_value_table = $this->build_key_value_table();

        // Add the table of values to the string
        $email_string .= $key_value_table;
        $email_string .= $this->build_page_details();
        /*
         * Send the email to the admin/office
         */
        if ($send_email) {
            $status = wp_mail($to, $response_subject.$date, wp_swift_wrap_email($email_string), $headers);
            if (isset($this->forward_email)) {
                $status = wp_mail($this->forward_email, '[Fwd:] '.$response_subject.$date, wp_swift_wrap_email($email_string), $headers);
            }
        }
        else {
            write_log('$to: ' .$to);
            write_log('$this->forward_email: ' . $this->forward_email);
            error_log( "Debugging mode is on so no emails are being sent." );
        }

        if ( $this->save_submission ) {           
            $attach = null; 
            $save_submission_title = $response_subject . $date; 
            if (isset($this->save_submission["title"])) {
                 $save_submission_title = $this->save_submission["title"] . ' - ' . $save_submission_title;
            }
            if (isset($this->save_submission["attach"])) {
               $attach = $this->save_submission["attach"]; 
            }
            $submission = new WP_Swift_Form_Submission( $save_submission_title, $email_string, $attach ); 
        }
        /*
         * If the user has requested it, send an email acknowledgement
         */
        $user_output_footer = '';

        $user_email_string = $auto_response_message.'<p>A copy of your enquiry is shown below.</p>'.$key_value_table;

        $user_confirmation_email = parent::get_user_confirmation_email();

        $form_data = parent::get_form_data();
        $form_data = $form_data[0];

        if ( ($user_confirmation_email=== 'ask' && isset($post["mail-receipt"])) || $user_confirmation_email=== 'send' )  {
 
            if ($send_email) {

                if (isset($form_data["inputs"]['form-email']['clean'])) {
                    $status = wp_mail($form_data["inputs"]['form-email']['clean'], $auto_response_subject, wp_swift_wrap_email($user_email_string), $headers);// wrap_email($user_response_msg)
                }
                else {
                    write_log('email not found');
                }
            }
            else {
                $user_output_footer = "<pre>Debugging mode is on so no emails are being sent.</pre>";
            }
        
            $user_output_footer .= '<p>A confirmation email has been sent to you including these details.</p>';
        }

        /*
         * Return the html
         */              
        return $this->build_confirmation_output($class, $browser_output_header, $auto_response_message, $key_value_table, $user_output_footer);
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

    private function build_confirmation_output($class, $browser_output_header, $auto_response_message, $key_value_table, $user_output_footer) {

        ob_start(); ?>

            <div id="form-success-message" class="form-message <?php echo $class ?>"> 
               
                <h3><?php _e( $browser_output_header, 'wp-swift-form-builder' ); ?></h3>                    
                <div>
                    <?php _e( $auto_response_message, 'wp-swift-form-builder' ); ?>                       
                </div>
                <p><?php _e( 'A copy of your enquiry is shown below.', 'wp-swift-form-builder' ); ?></p>

                <?php echo $key_value_table; ?>

                <?php if ($user_output_footer): ?>
                    <p><?php _e( $user_output_footer, 'wp-swift-form-builder' ); ?></p>
                <?php endif ?>

            </div><!-- @end #form-success-message -->

        <?php
        // echo $this->build_page_details();
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
                                <h3><?php $this->table_cell_header($input_key) ?></h3>
                            </th>
                        </tr>
                            <?php 
                            else: 
                                if ($section_input['clean'] !== ''): ?>
                        <tr>
                            <th style="width:30%; text-align:left"><?php $this->table_cell_header($input_key) ?></th>
                            <td><?php 
                                    if ($section_input['type']=='select') {
                                        echo ucwords(str_replace('-', ' ',$section_input['clean']));
                                    } else {
                                        echo $section_input['clean'];
                                    }
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

    private function table_cell_header($input_key) {
        $header = ucwords(str_replace('-', ' ',substr($input_key, 5)));
        $header = str_replace(' Of ', ' of ', $header);
        echo $header;
    }

    private function build_confirmation_output_2($use_callout, $browser_output_header, $auto_response_message, $key_value_table, $user_output_footer) {

        $framework = '';
        $debugging_stop_email = false;//true;
        $options = get_option( 'wp_swift_form_builder_settings' );
        if (isset($options['wp_swift_form_builder_select_css_framework'])) {
            $framework = $options['wp_swift_form_builder_select_css_framework'];
        }
        if( get_field('debugging_stop_email', 'option') ) {
            $debugging_stop_email = true;
        } 

        $framework = "zurb_foundation";

        ob_start();
        if ($debugging_stop_email): ?>
            <pre>
                    <div><b>Debug Mode</b></div>
                <!-- <br> --><?php //var_dump($_POST); ?>
            </pre>
        <?php endif ?>
        <?php if ($use_callout):
                if ($framework === "zurb_foundation"): ?>
                    <div id="form-thank-you">
                        <!-- <div class="callout secondary" data-closable="slide-out-right">    --> 
                        <div id="form-success-panel">

                <?php elseif ($framework === "bootstrap"): ?>
                    <div class="panel panel-success" id="form-success-panel">
                        <div class="panel-heading">
                            <button type="button" class="close" data-target="#form-success-panel" data-dismiss="alert">
                                <span aria-hidden="true">&times;</span><span class="sr-only">Close</span>
                            </button>
                            <h3><?php echo $browser_output_header; ?></h3>
                            
                        </div>
                        <div class="panel-body">              
                <?php endif; ?>     
        <?php endif ?>
                <!-- <a href="#" >x</a> -->
                <button id="close-form-success-panel" aria-label="Dismiss alert" type="button">
                    <span aria-hidden="true">&times;</span>
                </button>      
                <?php if ($framework === "zurb_foundation"): ?>
                    <h3><?php echo $browser_output_header; ?></h3>
                <?php endif; ?>                             
                <p><?php echo $auto_response_message; ?></p>
                <p>A copy of your enquiry is shown below.</p>
                <?php echo $key_value_table; ?>
                <?php echo $user_output_footer; ?>

        <?php if ($use_callout):  
                if ($framework === "zurb_foundation"): ?>
               <!--          <button class="close-button" aria-label="Dismiss alert" type="button" data-close>
                            <span aria-hidden="true">&times;</span>
                        </button> -->
                    </div>        
                <?php elseif ($framework === "bootstrap"): ?>
                         </div>
                    </div>                
                <?php endif; ?>
        <?php endif;

        $html = ob_get_contents();
        ob_end_clean();
        return $html;
    }

    /*
     * Hookable function that
     */
    public function before_submit_button_hook() {

    }    
}
//     }
// }