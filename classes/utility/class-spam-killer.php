<?php
/*
 * Utility Class
 */
class WP_Swift_Form_Builder_Encryptor {
    static function encrypt($value) {
        $encryptedValue = base64_encode($value);
        return $encryptedValue; 
    }
    static function decrypt($value) {
        $decryptedValue = base64_decode($value);
        return $decryptedValue; 
    }
}
/*
 * Spam Prevention
 */
class WP_Swift_Form_Builder_Spam_Killer {
    // private $error_count = 0;
    // private $form_error_messages = array();
    private $recaptcha = null;
    private $honeypot = true;
    private $time_control = true;
    private $min_time_to_fill_form = 2;// define the minimum time required to fill the form to 6 seconds
    private $debug = false;
    /*
     * Initializes the plugin.
     */
    public function __construct() {
        // write_log('WP_Swift_Form_Builder_Spam_Killer');
        $options = get_option( 'wp_swift_form_builder_settings' );
        $google_settings = $options['wp_swift_form_builder_google_recaptcha'];
        if ( $google_settings["site_key"] !== '' && $google_settings["secret_key"] !== '' ) {
            $this->recaptcha = $google_settings;
            // echo '<pre>$this->recaptcha: '; var_dump($this->recaptcha); echo '</pre>';
        }
    }

    public function spam_html($helper, $tab_index) {

        $this->time_control_html();

        $this->honeypot_html();

        $this->recaptcha_html($helper, $tab_index);
    }

    /*
     * Get error_count
     */
    public function get_error_count($error_count) {
        return $this->error_count + $error_count;
    }

    /*
     * Get form_error_messages
     */
    public function get_form_error_messages($form_error_messages) {
        return $this->form_error_messages + $form_error_messages;
    }

    /*
     * Increase error_count
     */
    private function increase_error_count() {
        $this->error_count++;
    }

    private function recaptcha_site() {
        if (isset( $this->recaptcha["site_key"] )) {
            return $this->recaptcha["site_key"];
        } 
    } 
    private function recaptcha_secret() {
        if (isset( $this->recaptcha["secret_key"] )) {
            return $this->recaptcha["secret_key"];
        } 
    }

    private function recaptcha_theme() {
        if (isset( $this->recaptcha["theme"] )) {
            echo ' data-theme="'.$this->recaptcha["theme"].'"';
        } 
    }

    private function recaptcha_size() {
        if (isset( $this->recaptcha["size"] )) {
            echo ' data-size="'.$this->recaptcha["size"].'"';
        } 
    }

    private function recaptcha_group_class() {
        if (isset( $this->recaptcha["hide_on_load"] ) && $this->recaptcha["hide_on_load"] ) {
            echo ' hide init-hidden';
        } 
    }         
    private function recaptcha_html($helper, $tab_index) {
        $html = '';
        if ( $this->recaptcha_site() ):
            ob_start();
            ?>

                <div class="form-group form-group-extra captcha-wrapper<?php $helper->recaptcha_group_class(); ?>" id="captcha-wrapper-<?php echo $helper->get_form_post_id(); ?>">

                    <!-- @start input -->
                    <div class="form-input">
                        <div class="g-recaptcha" data-sitekey="<?php echo $helper->recaptcha_site() ?>" <?php $helper->recaptcha_theme(); $helper->recaptcha_size(); ?> data-tabindex="<?php echo $tab_index; ?>" data-size="normal"></div>

                    </div>
                    <!-- @end input -->

                </div>
            <?php
            $html = ob_get_contents();
            ob_end_clean();
        endif;//@nd if $this->recaptcha_site()
        echo  $html;
    }

    public function recaptcha_check($helper, $post) {
        $response = array(
            'status' => false,
            'msg' => '',
        );
        if ( !$helper->recaptcha_secret() ){
            // recaptcha is not set so skip the check
            return true;
        }
        elseif ( $helper->recaptcha_secret() && $post["g-recaptcha-response"] ){

            $g_response = $post["g-recaptcha-response"];

            $url = 'https://www.google.com/recaptcha/api/siteverify';
            $post_data = "secret=".$helper->recaptcha_secret()."&response=".$g_response."&remoteip=".$_SERVER['REMOTE_ADDR'] ;
            $ch = curl_init();  
            curl_setopt($ch, CURLOPT_URL, $url);
            curl_setopt($ch, CURLOPT_POST, true);
            curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
            curl_setopt($ch, CURLOPT_HTTPHEADER, array('Content-Type: application/x-www-form-urlencoded; charset=utf-8', 'Content-Length: ' . strlen($post_data)));
            curl_setopt($ch, CURLOPT_POSTFIELDS, $post_data); 
            $googresp = curl_exec($ch);       
            $decgoogresp = json_decode($googresp);
            curl_close($ch);

            if ( $decgoogresp->success === false ) {
                $helper->increase_error_count();
                $helper->add_form_error_message("You are a bot! Go away!"); 
                write_log('recaptcha_check failed. ref: $decgoogresp->success ');
                return false;         
            } 
            elseif ( $decgoogresp->success === true ) {
                return true;     
            }
        }
        elseif ( $helper->recaptcha_secret() ){
            $helper->increase_error_count();
            $helper->add_form_error_message("This form is expecting a recaptcha code to validate but none was found!");   
            write_log('recaptcha_check failed. ref: This form is expecting a recaptcha code to validate but none was found!');            
            return false;
        }    
    }

    private function honeypot_html() {
        if ( !$this->honeypot ) return;
        if ( $this->debug ) $height = 'auto'; else $height = '0';
?><div id="form-control-fields" style="height: <?php echo $height ?>; overflow: hidden;">
    <div class="form-group control-field">
    <div class="form-label">
        <label for="control-field-1" class="control-field-label">Do not fill the following field.<br>(These are control fields detecting bots.)</label>
    </div>
    <div class="form-input">
       <input class="" name="control-field-1" id="control-field-1" type="text" autocomplete="off" />
    </div>                  
</div> 

<div class="form-group control-field">
    <div class="form-label">
        <!-- <label for="control-field-2" class="control-field-label">Leave blank</label> -->
    </div>
    <div class="form-input">
       <input class="" name="control-field-2" id="control-field-2" type="text" autocomplete="off" />
    </div>                  
</div> 

<div class="form-group control-field">
    <div class="form-label">
        <!-- <label for="control-field-3" class="control-field-label"></label> -->
    </div>
    <div class="form-input">
       <input class="" name="control-field-3" id="control-field-3" type="text" autocomplete="off" />
    </div>                  
</div> 

<div class="form-group control-field">
    <div class="form-label">
        <label for="control-field-4" class="control-field-label">Options</label>
    </div>
    <div class="form-input">
        <select class="control-field" id="control-field-4" name="control-field-4" autocomplete="off">
            <option value="">Please select an option...</option>
            <option value="1">1</option>
            <option value="2">2</option>
            <option value="3">3</option>
            <option value="4">4</option>
        </select>
    </div>
</div>

<div class="form-group control-field">
    <div class="form-label">
        <!-- <label for="control-field-5" class="control-field-label"></label> -->
    </div>
    <div class="form-input">
       <input class="" name="control-field-5" id="control-field-5" type="text" />
    </div>                  
</div>
</div><?php
    }  

    private function honeypot_check($helper, $post) {
        if ( !$this->honeypot ) return true;
        $control_field_filled_in = false;
        for ($i=1; $i < 6; $i++) { 
            if( isset($post['control-field-'.$i]) && $post['control-field-'.$i] !== '' ) {
                $control_field_filled_in = true;
            }
        }
        if ($control_field_filled_in) {
            $helper->increase_error_count();// Count as 1 error
            $helper->add_form_error_message('Your enquiry has been submited and rejected because our system does not believe you are human.'.
            '<br><p>Why does this happen? It is an anti-spam measure used to detect bogus form fillers. We are sorry but something has gone wrong if you are actually human and seeing this message.</p>'.'<p><b>Spam Error Code: 2</b></p>');
            return false;
        } 
        return true;       
    } 

    private function time_control_html() {
        if ($this->time_control):
            $readonly = '';
            if ( $this->debug ) {$type = 'text'; $readonly = ' readonly'; } else $type = 'hidden';
            $form_time_control_field_value = WP_Swift_Form_Builder_Encryptor::encrypt(time()); ?>
            <input type="<?php echo $type ?>"<?php echo $readonly ?> name="form-time-control-field" id="form-time-control-field" value="<?php echo $form_time_control_field_value; ?>" />
        <?php endif;
    } 

    private function time_control_check($helper, $post) {
        if ( !$this->time_control ) return true;

        $encrypted_loaded_form_time = $post['form-time-control-field'];
        $loaded_form_time = WP_Swift_Form_Builder_Encryptor::decrypt($encrypted_loaded_form_time); // decrypt it

        if (is_numeric($loaded_form_time)) {
            $form_filled_in_seconds = time() - $loaded_form_time;
        } else {
            // This is a spam robot. Take action!
            $helper->increase_error_count();
            $control_field_msg = '<p>Your enquiry has been submited and rejected because our system does not believe you are human.</p>';
            $control_field_msg .= '<p><b>Spam Error Code: 3a</b></p>';
            $helper->add_form_error_message($control_field_msg);
            return false;            
        }

        if(!isset($encrypted_loaded_form_time) || $form_filled_in_seconds < $this->min_time_to_fill_form) {
            // This is a spam robot. Take action!
            $helper->increase_error_count();
            $control_field_msg = '<p>Your enquiry has been submited and rejected because our system does not believe you are human.</p>';
            $control_field_msg .= '<p>Why does this happen?</p>';
            $control_field_msg .= '<p>The form was submitted  in <b>' . $form_filled_in_seconds . ' seconds</b>. This is quicker than a human might do it. We are sorry if we got this wrong - maybe you could try again.</p>'; 
            $control_field_msg .= '<p><b>Spam Error Code: 3b</b></p>';
            // $this->form_error_messages[] = $control_field_msg;
            $helper->add_form_error_message($control_field_msg);
            return false;
        } 
        return true; 
    } 

    public function spam_prevention($helper, $post) {
        if ($this->recaptcha_check($helper, $post) && $this->honeypot_check($helper, $post) && $this->time_control_check($helper, $post)) {
            return true;
        }
        return false;
    }   

}