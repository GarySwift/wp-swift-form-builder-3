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
    private $action='';
    private $form_data = null;
    // private $post_id = null;
    private $form_css_id = '';
    private $form_post_id = '';
    private $form_name = '';
    private $submit_button_id = '';
    private $submit_button_name = '';
    private $submit_button_text = '';
    private $css_framework = "zurb_foundation";
// private $show_mail_receipt = true;
    private $form_pristine = true;
    private $enctype = '';
    private $error_count = 0;
    private $tab_index = 100;
    // private $extra_error_msgs = array();
    // private $extra_msgs = array();
    private $list_form_errors_in_warning_panel = true;
    private $clear_after_submission = true;
    // private $Section_Layout_Addon = null;
    // private $default_input_keys_to_skip = array('submit-request-form', 'mail-receipt', 'form-file-upload', 'g-recaptcha-response');
    private $form_class ='form-builder';
    // private $success_msg = '';
    // private $option = '';

    private $form_error_messages = array();
    private $user_confirmation_email = "ask";
    /*
        function guide
        acf_build_form()


    */

    /*
     * Initializes the plugin.
     */
    public function __construct($form_id) {// , $sections, $settings = false  //"option") {
        $form_data = wp_swift_get_form_data($form_id);
        // echo "<pre>"; var_dump($form_data); echo "</pre>";
        $this->form_post_id = $form_id;
        if (isset($form_data["sections"])) {
             $this->form_data = $form_data["sections"];
        }
        if (isset($form_data["settings"])) {
            $settings = $form_data["settings"];
        }
        if(isset($args["form_name"])) {
            $form_name = sanitize_title_with_dashes($args["form_name"]);
            $this->form_name = $form_name;
        }
        else {
            $this->form_name = "request-form";
        }

        if (isset($args["form_id"])) {
            $this->form_css_id = $args["form_id"];
        }
        else {
            $this->form_css_id = $this->form_name;
        }
        
        if(isset($args["submit_button_name"])) {
            $this->submit_button_name = $args["submit_button_name"];
        }
        else {
            $this->submit_button_name = "submit-".$this->form_name;
        }

        if(isset($args["submit_button_id"])) {
            $this->submit_button_id = $args["submit_button_id"];
        }
        else {
            $this->submit_button_id = $this->submit_button_name;
        }

    if(isset($settings["submit_button_text"])) {
        $this->submit_button_text = $settings["submit_button_text"];
    }
    else {
        $this->submit_button_text = "Submit Form";
    }

        if (isset($args["clear_after_submission"])) {
            $this->clear_after_submission = $args["clear_after_submission"];
        }

        if (isset($args["action"]) && $args["action"]!='') {
            $this->action = 'action="'.$args["action"].'"';
        }

        if (isset($args["list_form_errors_in_warning_panel"])) {
            $this->list_form_errors_in_warning_panel = $args["list_form_errors_in_warning_panel"];
        }

        if (isset($args["form_class"])) {
            $this->form_class .= $args["form_class"];
        }
        if (isset($settings["groupings"])) {
            $this->form_class .= " groupings";
        } 
        if (isset($settings["form_css_class"])) {
            $this->form_class .= $settings["form_css_class"];
        } 
        if(isset($settings["user_confirmation_email"])) {
            $this->user_confirmation_email = $settings["user_confirmation_email"];
        }
        // else {
        //     $this->user_confirmation_email = "ask";
        // }                   
    }

    public function run() {
        if ($this->form_data) {
            return $this->get_form();
        }
        // else {
        //     return "<pre>Form not found</pre>";
        // }
    }

    public function get_form() {
        ob_start();
        ?>
        <div class="<?php echo $this->form_class; ?>">
        <?php
        // if ($form_builder != null ) {
            if( isset( $_POST[$this->get_submit_button_name()] ) ) { //check if form was submitted
                echo $this->process_form($_POST); 
            }
            $this->front_end_form();
        // }
        ?>
        </div>
        <?php
        $html = ob_get_contents();
        ob_end_clean();
        return $html; 
    }

    // private function get_form_class() {

    // }
    /*
     * @function  set_form_data
     * Set the form data
     *
     * @param       $form_post_id   int     the id of the form CPT
     * @param       $form_inputs    array   the array of inputs
     * @param       $args           array   additional arguments
     */
    // public function set_form_data($form_post_id, $form_inputs=array(), $args=false) {
   
    // }
    //@end set_form_data()

  /*
     * Build the form
     */
    public function acf_build_form($submit_form_failure=true) {
        // if ($submit_form_failure) {
        //    $this->submit_form_failure();
        // }
        $this->front_end_form();
    }//@end acf_build_form()

    public function submit_form_success($post, $ajax) {

    }



    public function front_end_form() {
        $framework='';
        $options = get_option( 'wp_swift_form_builder_settings' );
        if (isset($options['wp_swift_form_builder_select_css_framework'])) {
            $framework = $options['wp_swift_form_builder_select_css_framework'];
        }
        ?>
 
        <!-- @start form -->
        <form method="post" <?php echo $this->action; ?> name="<?php echo $this->form_name; ?>" id="<?php echo $this->form_css_id; ?>" data-id="<?php echo $this->form_post_id ?>" class="<?php echo $framework.' '; echo $this->form_class.' '; echo $this->form_name ?>" novalidate<?php echo $this->enctype; ?>>
            <?php
            
            $this->front_end_form_input_loop($this->form_data, $this->tab_index, $this->form_pristine, $this->error_count);

            $this->before_submit_button_hook(); 

            if ($this->user_confirmation_email === 'ask'): ?>

            <!-- @start .mail-receipt -->
            <div class="form-group mail-receipt">
                <div class="form-label"></div>
                <div class="form-input">
                    <div class="checkbox">
                      <input type="checkbox" value="" tabindex=<?php echo $this->get_tab_index(); ?> name="mail-receipt" id="mail-receipt" checked><label for="mail-receipt">Acknowledge me with a mail receipt</label>
                    </div>
                </div>                  
            </div> 
            <!-- @end .mail-receipt -->                
            <?php endif ?>

            <!-- @start .button -->
            <div class="form-group button-group">
                <button type="submit" name="<?php echo $this->submit_button_name; ?>" id="<?php echo $this->submit_button_id; ?>" class="button large expanded" tabindex="<?php echo $this->tab_index++; ?>"><?php echo $this->submit_button_text; ?></button>
            </div>
            <!-- @end .button -->

        </form><!-- @end form -->

        <?php 
        // edit_post_link( '(Edit Form)', '<p>', '</p>',$this->get_form_post_id() );           
    }// front_end_form()

    public function front_end_form_input_loop() {

        foreach ($this->form_data as $key => $section) {
            foreach ($section["inputs"] as $id => $input) {

                if (isset($input['data_type'])) {
                    switch ($input['data_type']) {            
                        case "text":
                        case "url":
                        case "email":
                        case "number":
                        case "username":
                        case "password":
                        case "date":
                        case "date_range":
                            $input_html = $this->build_form_input($id, $input);
                            echo $this->wrap_input($id, $input, $input_html);
                            break;
                        case "textarea":
                            $input_html = $this->build_form_textarea($id, $input);
                            echo $this->wrap_input($id, $input, $input_html);
                            break; 
                        case "radio":
                            $input_html = $this->build_form_radio($id, $input);
                            echo $this->wrap_input($id, $input, $input_html);
                            break; 
                        case "checkbox":
                            $input_html = $this->build_form_checkbox($id, $input);
                            echo $this->wrap_input($id, $input, $input_html);
                            break; 
                        case "checkbox_single":
                            $input_html = $this->build_form_checkbox_single($id, $input);
                            echo $this->wrap_input($id, $input, $input_html);
                            break;               
                        case "multi_select":
                        case "select":
                            $input_html = $this->build_form_select($id, $input, '');
                            $this->wrap_input($id, $input, $input_html);
                            break;                                                        
                    }  
                }
                     
            }
        }

    }

    public function process_form($post, $ajax=false) {
        if ( $this->get_form_data() ) {
            $this->validate_form($post);
            if ( $this->get_error_count() === 0 ) {
                return $this->submit_form_success($post, $ajax);
            }
            else {
                return $this->submit_form_failure($ajax);
            }
        }
        else {
            return $this->form_failure($ajax);
        }        
    }
/*
 *
 */
    public function validate_form($post) {

        // The form is submitted by a user and so is no longer pristine
        $this->set_form_pristine(false);
        foreach ($this->form_data as &$section) {
            foreach ($section["inputs"] as $input_key => &$input) {
                if (isset($post[$input_key])) {
                    $input['value'] = $post[$input_key];
                    $input = $this->validate_input($input);
                }
                else {
                    if ($input["data_type"] === "checkbox_single" && $input["required"] === "") {
                        $input['clean'] = 'No';
                        $input['passed'] = true;
                    }                    
                }

                // echo "<pre>"; echo $input["data_type"]." - ".$input["required"];echo "</pre>";

                // echo "<pre>";  echo "</pre>";
                if (!$input['passed']) {
                    $this->increase_error_count();
                    if ($input['help'] == '') {
                        $this->form_error_messages[] = $input['help'];
                    }
                    else {
                        $this->form_error_messages[] = $input['label'] . ' is invalid';
                    }
                } 
            }
        }              
    }

    /*
     * Check an individual form input field and sets the array with the findings 
     *
     * @param $input
     *
     * @return $input
     */
    public function validate_input($input) {
     
        if($input['required'] && $input['value']=='') {
            return $input;
        }
        elseif(!$input['required'] && $input['value']=='') {
            $input['passed'] = true;
            return $input;
        }

        if(!is_array($input['value'])) {
            $input['value'] = trim($input['value']);
        }

        switch ($input['data_type']) {
            case "text":
            case "textarea":
                $input['clean'] = sanitize_text_field( $input['value'] );
                break;
            case "username":
                $username_strlen = strlen ( $input['value']  );
                if ($username_strlen<4 || $username_strlen>30) {
                    return $input;
                }
                $input['clean'] = sanitize_user( $input['value'], $strict=true ); 
                break;
            case "email":
                if ( !is_email( $input['value'] ) ) { 
                    return $input; 
                }
                else {
                    $input['clean'] = sanitize_email( $input['value'] );  
                }
                break;
            case "number":
                if ( !is_numeric( $input['value'] ) ) { 
                    return $input; 
                }
                else {
                    $input['clean'] = $input['value'];  
                }
                break;        
            case "url":
                if (filter_var($input['value'], FILTER_VALIDATE_URL) === false) {
                    return $input;
                }
                else {
                    $input['clean'] = $input['value'];
                }
                break;
            case "select2":
            case "select":
                $input['selected_option'] = $input['value'];
                $input['clean'] = $input['value'];
                break;
            case "file":     
                break; 
            case "hidden":
                    if (isset($input['nonce'])) {
                        $retrieved_nonce = $value;
                        if (!wp_verify_nonce($retrieved_nonce, 'search_nonce' ) ) {
                            die( 'Failed security check' );
                            return $input;  
                        }
                    }
                    if (isset($input['expected'])) {
                        if ($input['expected'] != $value ) {
                            return $input;  
                        }
                    }             
                break; 
            case "password":
                    break; 
            case "checkbox":
                    $options = $input["options"];
                    $clean = '';
                    foreach ($options as $option_key => $option) {
                        if ( in_array($option["option_value"], $value)) {
                            $options[$option_key]["checked"] = true;
                            $clean .= $option["option"].', ';
                        }
                    }
                    $clean = rtrim( $clean, ', ');
                    $input["options"] = $options;
                    $input['clean'] = $clean;
                    break;                        
            case "checkbox_single":
                    $input["option"]["checked"] = 1;
                    $input['clean'] = "Yes";//"Yes <small>(".$input["option"]["key"].")</small>";
                    break;        
        }
        $input['passed'] = true;
        return $input;   
    }//@end validate_input 

    /*
     * Build form message
     */
    public function submit_form_failure($ajax) {
        ob_start();

        ?>

        <!-- @start #form-error-message -->
        <div id="form-error-message" class="form-message error<?php echo $ajax ? ' ajax':''; ?>">

            <h3 class="heading">Errors Found</h3>

            <div class="error-content">

                <p>We're sorry, there has been an error with the form input. Please rectify the <?php 
                    echo $this->get_error_count() === 1 ? ' error' : $this->get_error_count().' errors'; ?> below and resubmit.</p>
                <?php if ($this->list_form_errors_in_warning_panel) : ?>

                <ul>
                    <?php foreach ($this->form_error_messages as $message) : ?>

                    <li><?php echo $message; ?></li>    
                    <?php endforeach; ?>

                </ul>
                <?php endif ?>

            </div>

        </div>
        <!-- @end #form-error-message -->

        <?php
        $html = ob_get_contents();
        ob_end_clean();
        
        return $html;
    }//@end submit_form_failure()

    /*
     * Build form message
     */
    public function form_failure($ajax) {
        ob_start();

        ?>

        <!-- @start #form-error-message -->
        <div id="form-error-message" class="form-message error<?php echo $ajax ? ' ajax':''; ?>">

            <h3 class="heading">Form Error</h3>

            <div class="error-content">
                <p class="lead">We're sorry, there has been an error with the form input.</p>
                <p>We were unable to locate this form for processing.</p>

            </div>

        </div>
        <!-- @end #form-error-message -->

        <?php
        $html = ob_get_contents();
        ob_end_clean();
        
        return $html;
    }//@end submit_form_failure()
    /******************************************************
     * @start Form Inputs
     ******************************************************/
    private function build_form_input($id, $input, $section='') {
        $has_error='';
        if(!$this->form_pristine) {
            if($this->clear_after_submission && $this->error_count===0) {
                // No errors found so clear the values
                $input['value']=''; 
            }
        }
        // data_type is the same as $data['type'] unless it is an invalid attributes type such as username
        // $data_type = $input['type'];
        // if ($input['type']=='username') {
        //     $input['type']='text';
        //     $data_type = 'username';
        // }
        if (isset($input['name'])) {
            $name = $input['name'];
        }
        else {
            $name = $id;
        }
        
        if (isset($input['id-index'])) {
            $id .= '-'.$input['id-index'];
        }

        
        // new
        $type = ' type="'.$input['type'].'"';
        $data_type = ' data-type="'.$input['data_type'].'"';
        $class = ' class="'.$this->get_form_input_class($input).'"';
        $id = ' id="'.$id.'"';
        $name = ' name="'.$name.'"';
        $tabindex = ' tabindex="'.$this->tab_index++.'"';
        $value = '';
        if ( isset($input["value"]) && $input["value"] !== '') {
            $value = ' value="'.$input['value'].'"';
        }
        $placeholder = '';
        if ( isset($input["placeholder"]) && $input["placeholder"] !== '') {
            $placeholder = ' placeholder="'.$input['placeholder'].'"';
        }
        $section = '';
        if ( isset($input["section"])  & $input["section"] !== '') {
            $section = ' data-section="'.$input['section'].'"';
        }
        $required = '';
        if (isset($input['required']) && $input['required'] !== '') {
              $required = ' required';
        }
        $input_html = '<input'.$type.$data_type.$class.$id.$name.$tabindex.$value.$placeholder.$section.$required.'>';
        return $input_html;       
    } 

    private function build_form_select($id, $data, $multiple='') {

        if(!$this->form_pristine) {
            if($this->clear_after_submission && $this->error_count===0) {
                // No errors found so clear the selected value
                $data['selected_option']=''; 
            }
        }
        ob_start();
        ?>

        <select class="<?php echo $this->get_form_input_class(); ?>" id="<?php echo $id; ?>" name="<?php echo $id; ?>" data-type="select" tabindex="<?php echo $this->tab_index++; ?>" <?php echo $data['required']; echo $multiple; ?>>

            <?php if(!$multiple): ?>
                <option value="" class="placeholder">Please select an option...</option>
            <?php endif;
            foreach ($data['options'] as $option):
                
                    if($option['option_value'] === $data['selected_option']) { 
                        $selected='selected'; 
                    } else { 
                        $selected=''; 
                    }?>
                <option value="<?php echo  $option['option_value']; ?>" <?php echo $selected; ?>><?php echo $option['option']; ?></option>
            <?php 
            endforeach;
            //Note: select closing tag (below) is indented to format correctly in browser 
            ?>

        </select>

        <?php
        $input_html = ob_get_contents();
        ob_end_clean();
        return $input_html;
    }

    private function build_form_textarea($id, $input) {
        $has_error='';
        if(!$this->form_pristine) {
            if($this->clear_after_submission && $this->error_count===0) {
                // No errors found so clear the values
                $input['value']=''; 
            }
        }     
        if (isset($input['name'])) {
            $name = $input['name'];
        }
        else {
            $name = $id;
        }
        $data_type = ' data-type="textarea"'; 
        $id = ' id="'.$id.'"';
        $name = ' name="'.$name.'"';
        $class = ' class="'.$this->get_form_input_class().'"';
        $tabindex = ' tabindex="'.$this->tab_index++.'"';
        $value = '';
        if ( isset($input["value"]) && $input["value"] !== '') {
            $value = $input['value'];
        }        
        $placeholder = '';
        if ( isset($input["placeholder"]) && $input["placeholder"] !== '') {
            $placeholder = ' placeholder="'.$input['placeholder'].'"';
        }        
        $section = '';
        if ( isset($input["section"])  & $input["section"] !== '') {
            $section = ' data-section="'.$input['section'].'"';
        }
        $required = '';
        if (isset($input['required']) && $input['required'] !== '') {
              $required = ' required';
        } 

        $rows = '';
        if ( isset($input["rows"])  & $input["rows"] !== '') {
            $rows = ' rows="'.$input['rows'].'"';
        }
        $maxlength = '';
        if ( isset($input["maxlength"])  & $input["maxlength"] !== '') {
            $maxlength = ' maxlength="'.$input['maxlength'].'"';
        }
        $input_html = '<textarea'.$data_type.$class.$id.$name.$tabindex.$placeholder.$section.$required.$rows.$maxlength.'>'.$value.'</textarea>';
        return $input_html;    

    }

    private function build_form_radio($id, $input) {
        if(!$this->form_pristine) {
            if($this->clear_after_submission && $this->error_count===0) {
                // No errors found so clear the selected value
                $input['selected_option']=''; 
            }
        }

        $count=0;  
        $checked='';
        ob_start();
        foreach ($input['options'] as $option): $count++;
            if ( $input['selected_option'] !== '' && ($input['selected_option'] === $option['option_value']) ){
                $checked=' checked';
            }
            ?>
                    
                    <label for="<?php echo $id.'-'.$count ?>" class="lbl-radio">
                        <input id="<?php echo $id.'-'.$count ?>" name="<?php echo $id ?>-radio" type="radio" data-type="radio" tabindex="<?php echo $this->tab_index++; ?>" value="<?php echo $option['option_value'] ?>"<?php echo $checked; ?>><?php echo $option['option'] ?>

                    </label>
            <?php 
        endforeach;
        $html = ob_get_contents();
        ob_end_clean();
        return $html;            
    }

    private function build_form_checkbox($id, $data) {
        if(!$this->form_pristine) {
            if($this->clear_after_submission && $this->error_count===0) {
                // No errors found so clear the checked values
                foreach ($data['options'] as $key => $option) {
                    $data['options'][$key]['checked'] = false;
                }
            }
        }

        $count=0;  
        $name_append = '';
        if (count($data['options']) > 1) {
            $name_append = '[]';
        }
        ob_start();
        foreach ($data['options'] as $option): $count++;
            $checked='';

            if ( $option['checked'] == true ){
                $checked=' checked';
            }
            if (isset($data['name'])) {
                $name = $data['name'].$name_append;
            }
            else {
                $name = $id.''.$name_append;
            }
            ?>

                    <label for="<?php echo $id.'-'.$count ?>" class="lbl-checkbox">
                        <input id="<?php echo $id.'-'.$count ?>" name="<?php echo $name ?>" type="checkbox" data-type="checkbox" tabindex="<?php echo $this->tab_index++; ?>" value="<?php echo $option['option_value'] ?>"<?php echo $checked; ?>><?php echo $option['option'] ?>
                    
                    </label>
            <?php 
           
        endforeach;
        $input_html = ob_get_contents();
        ob_end_clean();
        return $input_html;  
    }

    private function build_form_checkbox_single($id, $input) {
        $checked='';
        if ( $input["option"]["checked"]){
            $checked=' checked';
        }  
        $required = '';
        if (isset($input['required']) && $input['required'] !== '') {
              $required = ' required';
        }           
        ob_start();
        // echo "<pre>"; var_dump($input); echo "</pre>";
        if ($input["option"]["key"]): ?>

                    <label for="<?php echo $id ?>" class="lbl-checkbox">
                        <input id="<?php echo $id ?>" name="<?php echo $id ?>" type="checkbox" class="js-single-checkbox"  data-type="checkbox" tabindex="<?php echo $this->tab_index++; ?>" value="1"<?php echo $checked; echo $required; ?>><?php echo $input["option"]["key"] ?>
                    
                    </label>
        <?php else: ?>

                    <input id="<?php echo $id.'-'.$count ?>" name="<?php echo $name ?>" class="js-single-checkbox" type="checkbox" data-type="checkbox" tabindex="<?php echo $this->tab_index++; ?>" value="1"<?php echo $checked; echo $required; ?>><?php echo $input["option"]["key"] ?>
        <?php endif;

        $html = ob_get_contents();
        ob_end_clean();
        
        return $html;

    }
    /******************************************************
     * @end Form Inputs
     ******************************************************/

    /*
     * Us the same fucntion to wrap all inputs
     */
    public function wrap_input($id, $input, $input_html, $section='') {

        if(!$this->form_pristine) {
            if($this->clear_after_submission && $this->error_count===0) {
                // No errors found so clear the values
                $input['value']=''; 
            }
        }
        if ($input["grouping"] && $input["grouping"] == "start"): ?>

            <!-- Start grouping -->
            <div class="<?php echo $this->get_css_form_grid_grouping(); ?>">         
        <?php endif ?>

            <!-- @start form element -->
            <div class="<?php echo $this->get_css_form_group($input) ?>" id="<?php echo $id; ?>-form-group">

                <!-- @start input anchor -->
                <a href="<?php echo $id; ?>-anchor"></a>
                <!-- @end input anchor -->

                <!-- @start input label -->
                <div class="form-label">

                    <div class="form-builder-feedback"><span class="feedback icon-x"></span><span class="feedback icon-check"></span><span class="icon-circle-o-notch"></span></div>
                    <?php 
                    if ($input['label']!=''): 
                    ?><label for="<?php echo $id; ?>" class="control-label <?php echo $input['required']; ?>"><?php echo $input['label']; ?> <span></span></label><?php 
                    endif; ?>

                </div>  
                <!-- @end input label -->
                
                <!-- @start input -->
                <div class="form-input">
                    
                    <?php 
                        echo $input_html; 

                        if ($input['help']) {
                             $help = $input['help'];
                        }
                        else {
                            $help = $input['label']. ' is required';
                            if ($input['type']=='email' || $input['type']=='url') {
                                $help .= ' and must be valid';
                            }  
                            $input['help'] = $help;
                        }   
                        if ($input['required']): echo PHP_EOL; ?>
                    <small class="error"><?php echo $help; ?></small><?php 
                        endif;
                        if (isset($input['instructions']) && $input['instructions']): echo PHP_EOL; ?>
                    <small class="instructions"><?php echo $input['instructions']; ?></small><?php 
                        endif;
                    ?>
                    

                </div>
                <!-- @end input -->

            </div>
            <!-- @end form element -->
        <?php if ($input["grouping"] && $input["grouping"] == "end"): ?>
             
            </div>
            <!-- end grouping -->    
        <?php endif;    
    }

    /*
     * 
     */
    public function get_css_form_grid_grouping() {
        return "form-grid-grouping grid-x";
    }

    /*
     * 
     */
    public function get_css_form_group($input) {
        $has_error='';
        if(!$this->form_pristine && $input['passed']==false && $input["type"] !== "checkbox") {
            // This input has has error detected so add an error class to the surrounding div
            $has_error = ' has-error';
        }        
        return " form-group".$input["css_class"].$has_error." cell large-auto small-6";
    }

    /*
     * Hookable function that
     */
    public function before_submit_button_hook() {

    }

  
     /*
     * Get the submit button name 
     * This can be used to check if this POST object was
     */
    public function get_submit_button_name() {
        return $this->submit_button_name;
    }  
    /*
     * Get form_post_id
     */
    public function get_form_post_id() {
        return $this->form_post_id;
    }  
    /*
     * Get form_pristine
     */
    public function get_form_pristine() {
        return $this->form_pristine;
    }
    /*
     * Set form_pristine
     */
    public function set_form_pristine($form_pristine) {
        $this->form_pristine = $form_pristine;
    }
    /*
     * Get error_count
     */
    public function get_error_count() {
        return $this->error_count;
    }
    /*
     * Increase error_count
     */
    public function increase_error_count() {
        $this->error_count++;
    }
    /*
     * Get extra_error_msgs
     */
    public function get_extra_error_msgs() {
        return $this->extra_error_msgs;
    }
    /*
     * Increase extra_error_msgs
     */
    public function add_extra_error_msgs($msg, $increase_count=true) {
        if ($increase_count) {
           $this->error_count++;
        }
        $this->extra_error_msgs[] = $msg;
    }

    /*
     * Get extra msgs
     */
    public function get_extra_msgs() {
        return $this->extra_msgs;
    }
    /*
     * Add new msg
     */
    public function add_extra_msg($msg) {
        $this->extra_msgs[] = $msg;
    }

    /*
     * Set success msg
     */
    public function set_success_msg($msg) {
        $this->success_msg = $msg;
    }

    /*
     * Set success msg
     */
    public function set_input_error($key, $msg) {
        $this->form_data[$key]["help"] = $msg;
        $this->form_data[$key]["passed"] = false;
        $this->increase_error_count();
    }

    /*
     * Get the CSS class for the input
     */
    private function get_form_input_class($input=false) {
        if (isset($input["css_class_input"])) {
            return "js-form-builder-control " . $input["css_class_input"];
        }
        return "js-form-builder-control";// form-control form-builder-control 
    }

    /*
     * Set the CSS framework
     */
    public function set_css_framework($css_framework) {
        $this->css_framework = $css_framework;
    } 

    public function get_tab_index( $increment=true ) {
        if ($increment) {
            $this->tab_index++;
        }
        return $this->tab_index;
    }

    // public function get_show_mail_receipt() {
    //     return $this->show_mail_receipt;
    // }
    public function get_form_data() {
        return $this->form_data;
    } 
    public function get_user_confirmation_email() {
        return $this->user_confirmation_email;
    }            
}