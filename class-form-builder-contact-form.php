<?php
/*
 * Check if WP_Swift_Form_Builder_Plugin exists.
 */
// include_once( ABSPATH . 'wp-admin/includes/plugin.php' );

// if ( is_plugin_active( 'wp-swift-form-builder/form-builder.php' ) )  {

//     include_once( plugin_dir_path( __DIR__ ) . 'wp-swift-form-builder/form-builder.php' );

//     if(class_exists('WP_Swift_Form_Builder_Plugin')) {

/*
 * Declare a new class that extends the form builder
 * 
 * @class       WP_Swift_Form_Builder_Login_Form
 * @extends     WP_Swift_Form_Builder_Plugin
 *
 */
class WP_Swift_Form_Builder_Contact_Form extends WP_Swift_Form_Builder_Parent {

    private $attributes = null;
    private $option = '';

    /*
     * Initializes the plugin.
     */
    public function __construct( $form_data, $args ) { 
        // $args = $this->get_form_args();
        parent::__construct( $form_data, $args );
    }    

    /*
     * Process the form
     * 
     * Use the parent class to run the default validation on the form
     * If default is passed, we let the child do additional checks required by this form such as existing email
     * 
     * Eg. The parent will check if the email exists, is valid etc but the child only knows if it needs to check for duplicates
     * The parent does not know what to with a successful form, it just validates default settings
     * The parent will handle errors if default errors have been found
     */
    public function process_form() {
        parent::validate_form();
        if ($this->get_error_count()==0) {
            echo $this->process_form_after_default_passed();
        }
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
    public function process_form_after_default_passed() {
        /*
         * Variables
         */
        $send_email=false;//Debug variable. If false, emails will not be sent
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

        /*
         * These are the default form settings
         */
        // If a debug email is set in ACF, send the email there instead of the admin email
        $to = get_option('admin_email');
        // Set reponse subject for email
        $response_subject = "New Enquiry".$date;
        // Start the reponse message for the email
        $response_message = '<p>A website user has made the following enquiry.</p>';
        //Set auto_response_message
        $auto_response_message = 'Thank you very much for your enquiry. A representative will be contacting you shortly.';
        // Set the response that is set back to the browser
        $browser_output_header = 'Hold Tight, We\'ll Get Back To You';
        // The auto-response subject
        $auto_response_subject='Auto-response (no-reply)';

        /*
         * Now, we can override the default settings if they are set
         */
        if (function_exists('get_field')) {
            // If a to_email is set in ACF, send the email there instead of the admin email
            if (get_field('to_email', $this->option)) {
                $to = get_field('to_email', $this->option); 
            }
            // Set reponse subject for email
            if (get_field('response_subject', $this->option)) {
                $response_subject = get_field('response_subject', $this->option); 
            }
            // Start the reponse message for the email
            if (get_field('response_message', $this->option)) {
                $response_message = get_field('response_message', $this->option);
            }
            //Set auto_response_message
            if (get_field('auto_response_message', $this->option)) {
                $auto_response_message = get_field('auto_response_message', $this->option);
            }
            // Set the response that is set back to the browser
            if (get_field('browser_output_header', $this->option)) {
                $browser_output_header = get_field('browser_output_header', $this->option);
            } 
            // The auto-response subject
            if( get_field('auto_response_subject') ) {
                $auto_response_subject = get_field('auto_response_subject');
            }
        }

        // Start making the string that will be sent in the email
        $email_string = $response_message;
        $key_value_table = $this->build_key_value_table();
        // Add the table of values to the string
        $email_string .= $key_value_table;

        /*
         * Send the email to the admin/office
         */
        if ($send_email) {
            $status = wp_mail($to, $response_subject.' - '.date("D j M Y, H:i"). ' GMT', wp_swift_wrap_email($email_string), $headers);//wrap_email($email_string)
        }
        /*
         * If the user has requested it, send an email acknowledgement
         */
        $user_output_footer = '';
        if($this->get_show_mail_receipt()) {
            $user_email_string = $auto_response_message.'<p>A copy of your enquiry is shown below.</p>'.$key_value_table;
            if ($send_email) {
                if (isset($this->form_inputs['form-email']['clean'])) {
                    $status = wp_mail($this->form_inputs['form-email']['clean'], $auto_response_subject, wp_swift_wrap_email($user_email_string), $headers);// wrap_email($user_response_msg)
                }
                
            }
        }
        $user_output_footer = '<p>A confirmation email has been sent to you including these details.</p>';

        /*
         * Return the html
         */              
        return $this->build_confirmation_output($use_callout=true, $browser_output_header, $auto_response_message, $key_value_table, $user_output_footer);
    } 


    private function build_key_value_table() {

        ob_start(); 
        ?><table class="form-feedback" id="print-table" style="width:100%">
                <tbody>
                    <?php foreach ($this->form_inputs as $key => $value): ?>
                        <?php if (isset($value['data_type'])): $type = $value['data_type'];?>
                            <tr>
   
                                <?php if ($type=='section'): ?>
                                    <th colspan="2" style="width:100%; text-align:center">
                                        <h3><?php $this->table_cell_header($key) ?></h3>
                                    </th>
                                <?php else: ?>
                                    <?php if ($value['clean'] !== ''): ?>
                                        <th style="width:30%; text-align:left"><?php $this->table_cell_header($key) ?></th>
                                        <td><?php 
                                            if ($value['type']=='select') {
                                                echo ucwords(str_replace('-', ' ',$value['clean']));
                                            } else {
                                                echo $value['clean'];
                                            }
                                        ?></td>                                      
                                    <?php endif ?>                                 
                                <?php endif ?>
                            </tr>

                        <?php endif ?>
                       
                    <?php endforeach ?>   
                </tbody>
            </table>
            <?php
        $html = ob_get_contents();
        ob_end_clean();
        return $html;
    }

    private function table_cell_header($key) {
        $header = ucwords(str_replace('-', ' ',substr($key, 5)));
        $header = str_replace(' Of ', ' of ', $header);
        echo $header;
    }

    private function build_confirmation_output($use_callout=true, $browser_output_header, $auto_response_message, $key_value_table, $user_output_footer) {

        $framework = '';
        $debugging_stop_email = true;
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
                        <div class="callout secondary" data-closable="slide-out-right">            
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

                <?php if ($framework === "zurb_foundation"): ?>
                    <h3><?php echo $browser_output_header; ?></h3>
                <?php endif; ?>                             
                <p><?php echo $auto_response_message; ?></p>
                <p>A copy of your enquiry is shown below.</p>
                <?php echo $key_value_table; ?>
                <?php echo $user_output_footer; ?>

        <?php if ($use_callout):  
                if ($framework === "zurb_foundation"): ?>
                            <button class="close-button" aria-label="Dismiss alert" type="button" data-close>
                            <span aria-hidden="true">&times;</span>
                        </button>
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
}
//     }
// }