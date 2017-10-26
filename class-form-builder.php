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
    public $action='';
    // public $form_settings = null;
    public $form_inputs = array();
    private $post_id = null;
    private $form_id = '';
    private $form_post_id = '';
    private $form_name = '';
    private $submit_button_id = '';
    private $submit_button_name = '';
    private $submit_button_text = '';
    private $css_framework = "zurb_foundation";
    // private $show_mail_receipt = false;
    private $form_pristine = true;
    private $enctype = '';
    private $error_count = 0;
    private $tab_index = 1;
    private $extra_error_msgs = array();
    private $extra_msgs = array();
    private $list_form_errors_in_warning_panel = true;
    private $clear_after_submission = true;
    private $Section_Layout_Addon = null;
    private $default_input_keys_to_skip = array('submit-request-form', 'mail-receipt', 'form-file-upload', 'g-recaptcha-response');
    private $form_class ='form-builder';
    private $success_msg = '';
    private $option = '';

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
    // if (class_exists('WP_Swift_Form_Builder_Contact_Form') && isset($form_data["sections"])) {
        $this->form_inputs = $form_data["sections"];
        $settings = $form_data["settings"];

        // $this->set_form_data($form_post_id, $form_inputs, $args);
        $this->form_post_id = $form_id;
        // $this->form_inputs = $sections;
        // $settings = $form_inputs["settings"];
        // echo "<pre>"; var_dump($settings); echo "</pre>"; 
        if(isset($args["form_name"])) {
            $form_name = sanitize_title_with_dashes($args["form_name"]);
            $this->form_name = $form_name;
        }
        else {
            $this->form_name = "request-form";
        }

        if (isset($args["form_id"])) {
            $this->form_id = $args["form_id"];
        }
        else {
            $this->form_id = $this->form_name;
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

        if(isset($args["button_text"])) {
            $this->submit_button_text = $args["button_text"];
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
    }

    public function run() {
        // echo 'Lorem ipsum dolor sit amet, consectetur adipisicing elit. Modi debitis voluptate libero est, similique corporis dolor repudiandae et nemo in asperiores. Fugit cum optio officia nesciunt nam, facilis dicta. Soluta!';//
        return $this->get_form();
    }

    public function get_form() {
        ob_start();
        ?>
        <div class="<?php echo $this->form_class; ?>">
        <?php
        // if ($form_builder != null ) {
            if(isset($_POST[ $this->get_submit_button_name() ])) { //check if form was submitted
                echo $this->process_form($_POST); 
            }
            $this->acf_build_form();
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
    public function set_form_data($form_post_id, $form_inputs=array(), $args=false) {
   
    }//@end set_form_data()

  /*
     * Build the form
     */
    public function acf_build_form($acf_build_form_message=true) {
        // if ($acf_build_form_message) {
        //    $this->acf_build_form_message();
        // }
        $this->front_end_form();
    }//@end acf_build_form()

    /*
     * Build form message
     */
    public function acf_build_form_message() {
        if ($this->get_error_count()>0): ?>

            <!-- @start .form-message -->
            <div id="form-error-message" class="form-message error">
                <h3 class="heading">Errors Found</h3>

                <div class="error-content">

                    <p>We're sorry, there has been an error with the form input. Please rectify the <?php 
                        echo $this->get_error_count() === 1 ? ' error' : $this->get_error_count().' errors'; ?> below and resubmit.</p>
                    <ul><?php 

                        if ($this->list_form_errors_in_warning_panel) {
                            foreach ($this->form_inputs as $section_key => $section) {

                                foreach ($section["inputs"] as $input_key => $section_input) {
                                    if (isset($section_input["passed"]) && !$section_input["passed"] && $section_input["type"] != "checkbox") {
                                        if ($section_input["help"]): 
                                        ?>
                                            <li><?php echo $section_input["help"] ?></li>
                                        <?php else: 
                                            $help = $section_input['label'].' is required';
                                            if ($section_input['type']=='email' || $section_input['type']=='url') {
                                                $help .= ' and must be valid';
                                            }
                                            ?>
                                            <li><?php echo $help ?></li>
                                        <?php 
                                        endif;
                                    }
                                }
     
                            }
                        }
                        if (count($this->extra_error_msgs)) {
                            foreach ($this->extra_error_msgs as $key => $msg) {
                            ?>
                                <li><?php echo $msg ?></li>
                            <?php 
                            }
                        } 
                     ?></ul>
                </div>

            </div>
            <!-- @end .form-message -->

        <?php
        endif;
    }//@end acf_build_form_message()

    public function front_end_form() {
        $framework='zurb';
        $options = get_option( 'wp_swift_form_builder_settings' );
        if (isset($options['wp_swift_form_builder_select_css_framework'])) {
            $framework = $options['wp_swift_form_builder_select_css_framework'];
        }
        ?>

        <!-- @start form -->
        <form method="post" <?php echo $this->action; ?> name="<?php echo $this->form_name; ?>" id="<?php echo $this->form_id; ?>" data-id="<?php echo $this->form_post_id ?>" class="<?php echo $framework.' '; echo $this->form_class.' '; echo $this->form_name ?>" novalidate<?php echo $this->enctype; ?>>
            <?php
            
            $this->front_end_form_input_loop($this->form_inputs, $this->tab_index, $this->form_pristine, $this->error_count);

            $this->before_submit_button_hook(); 

            ?>

            <!-- @start .button -->
            <div class="form-group button-group">
                <button type="submit" name="<?php echo $this->submit_button_name; ?>" id="<?php echo $this->submit_button_id; ?>" class="button large expanded" tabindex="<?php echo $this->tab_index++; ?>"><?php echo $this->submit_button_text; ?></button>
            </div>
            <!-- @end .button -->

        </form><!-- @end form -->

        <?php             
    }// front_end_form()

    public function front_end_form_input_loop() {

        foreach ($this->form_inputs as $key => $section) {
            foreach ($section["inputs"] as $id => $input) {

                if (isset($input['data_type'])) {
                    switch ($input['data_type']) {            
                        case "text":
                        case "url":
                        case "email":
                        case "number":
                        case "username":
                        case "password":
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

    public function get_tab_index( $increment=true ) {
        if ($increment) {
            $this->tab_index++;
        }
        return $this->tab_index;
    }

    // public function get_show_mail_receipt() {
    //     return $this->show_mail_receipt;
    // }
    public function get_form_inputs() {
        return $this->form_inputs;
    }

    public function get_form_inputs_value($key) {
        if (isset($this->form_inputs[$key])) {
            return $this->form_inputs[$key];
        }
        else {
            return false;
        }
    }
    public function validate_form($post, $ajax, $input_keys_to_skip=array()) {
        // $this->default_input_keys_to_skip = array('submit-request-form', 'mail-receipt', 'form-file-upload', 'g-recaptcha-response');
        // $this->default_input_keys_to_skip = array_merge($this->default_input_keys_to_skip, $input_keys_to_skip);

        // The form is submitted by a user and so is no longer pristine
        $this->set_form_pristine(false);
        //Loop through the POST and validate. Store the values in $form_data
        foreach ($post as $key => $value) {
            // echo "key <pre>"; var_dump($key); echo "</pre>";
            // if (!in_array($key, $this->default_input_keys_to_skip)) { //Skip the button,  mail-receipt checkbox, g-recaptcha-response etc
            //     $check_if_submit = substr($key, 0, 7);
            //     // Get the substring of the key and make sure it is not a submit button
            //     if ($check_if_submit!='submit-') {

            //         $this->check_input($key, $value);//Validate input    
            //     }
                
            // }
            $this->check_input($key, $value);//Validate input    
        }
        // echo "<br>";echo "<br>";echo "<br>";echo "<br>";echo "<br>";echo "<br>";echo "<br>";
        // echo "<hr>";echo "<hr>";echo "<hr>";echo "<hr>";echo "<hr>";echo "<hr>";echo "<hr>";
        // echo "<br>";echo "<br>";echo "<br>";echo "<br>";echo "<br>";echo "<br>";echo "<br>";

        // foreach ($this->form_inputs as $section_key => $section) {
        //     // echo "<pre>"; var_dump($section); echo "</pre>";
        //     // echo "<hr>";
        //     foreach ($section["inputs"] as $input_key => $input) {
        //         echo "<pre>"; var_dump($input); echo "</pre>";
        //     }
        // }

    }

    public function process_form($post, $ajax=false) {
        echo "<div class='callout secondary'>"; 
        echo '<h5>public function process_form()</h5>';
        echo '<p>This the the default form handling for the <code>WP Swift: Form Builer</code> plugin. You will need to write your own function to handle this POST request.</p>';
        echo 'var_dump($_POST)<br><br>';
        echo "<pre>";var_dump($post);echo "</pre>";
        echo "</div>";
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
        $this->form_inputs[$key]["help"] = $msg;
        $this->form_inputs[$key]["passed"] = false;
        $this->increase_error_count();
    }

    /*
     * Get the CSS class for the input
     */
    private function get_form_input_class() {
        return "js-form-builder-control";// form-control form-builder-control 
    }

    /*
     * Set the CSS framework
     */
    public function set_css_framework($css_framework) {
        $this->css_framework = $css_framework;
    }

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
        $data_type = $input['type'];
        if ($input['type']=='username') {
            $input['type']='text';
            $data_type = 'username';
        }
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
        $data_type = ' data-type="'.$data_type.'"';
        $class = ' class="'.$this->get_form_input_class().'"';
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

        <select class="js-form-control" id="<?php echo $id; ?>" name="<?php echo $id; ?>" tabindex="<?php echo $this->tab_index++; ?>" <?php echo $data['required']; echo $multiple; ?>>

            <?php if(!$multiple): ?>
                <option value="">Please select an option...</option>
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

        $input_html = '<textarea rows="2"'.$data_type.$class.$id.$name.$tabindex.$placeholder.$section.$required.'>'.$value.'</textarea>';
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
    /******************************************************
     * @ens Form Inputs
     ******************************************************/

    /*
     * Us the same fucntion to wrap all inputs
     */
    public function wrap_input($id, $input, $input_html, $section='') {
        $has_error='';
        if(!$this->form_pristine && $input['passed']==false && $input["type"] !== "checkbox") {
            // This input has has error detected so add an error class to the surrounding div
            $has_error = ' has-error';
        }

        if(!$this->form_pristine) {
            if($this->clear_after_submission && $this->error_count===0) {
                // No errors found so clear the values
                $input['value']=''; 
            }
        }
        if ($input["grouping"] && $input["grouping"] == "start"): ?>

            <!-- Start grouping -->
            <div class="form-grid-grouping">         
        <?php endif ?>

            <!-- @start form element -->
            <div class="form-group<?php echo $has_error; ?>" id="<?php echo $id; ?>-form-group">

                <!-- @start input anchor -->
                <a href="<?php echo $id; ?>-anchor"></a>
                <!-- @end input anchor -->

                <!-- @start input label -->
                <div class="form-label">
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
                <!-- @start input -->

            </div>
            <!-- @end form element -->
        <?php if ($input["grouping"] && $input["grouping"] == "end"): ?>
             
            </div>
            <!-- end grouping -->    
        <?php endif;    
    }

    /*
     * Hookable function that
     */
    public function before_submit_button_hook() {

    }

    /*
     * Check an individual form input field and sets the array with the findings 
     *
     * @param $key      an array key that matches the form input name (POST key)
     * @param $value    the value of the form input
     *
     * @return null
     */
    public function check_input($key, $value){
        // echo "<pre>key: "; var_dump($key); echo "</pre>";
        // echo "<pre>value: "; var_dump($value); echo "</pre>";
        // echo "<hr>";
        // include('_check-input.php');
        /*
         * Check an individual form input field and sets the array with the findings 
         *
         * @param $key      an array key that matches the form input name (POST key)
         * @param $value    the value of the form input
         */
        // public function check_input($key, $value){

        $input = null;
        foreach ($this->form_inputs as $section_key => $section) {
            foreach ($section["inputs"] as $input_key => $section_input) {
                if ( $input_key === $key ) {
                    $input = &$this->form_inputs[$section_key]["inputs"][$key];
                    $input['value'] = $value;
                    break;
                }

            }
        }

        if ($input) {
     
            if($input['required'] && $input['value']=='') {
                $this->increase_error_count();
                return;
            }

            else if(!$input['required'] && $input['value']=='') {
                $input['clean'] = $input['value'];
                $input['passed'] = true;
                return;
            }

            if(!is_array($input['value'])) {
                $input['value'] = trim($input['value']);
               
            }

            switch ($input['type']) {
                case "text":
                case "textarea":
                    $input['clean'] = sanitize_text_field( $input['value'] );
                    break;
                case "username":
                    $username_strlen = strlen ( $input['value']  );
                    if ($username_strlen<4 || $username_strlen>30) {
                        $this->increase_error_count();
                        return $input;
                    }
                    $input['clean'] = sanitize_user( $input['value'], $strict=true ); 
                    break;
                case "email":
                    if ( !is_email( $input['value'] ) ) { 
                        $this->increase_error_count();
                        return $input; 
                    }
                    else {
                        $input['clean'] = sanitize_email( $input['value'] );  
                    }
                    break;
                case "number":
                    if ( !is_numeric( $input['value'] ) ) { 
                        $this->increase_error_count();
                        return $input; 
                    }
                    else {
                        $input['clean'] = $input['value'];  
                    }
                    break;        
                case "url":
                    if (filter_var($input['value'], FILTER_VALIDATE_URL) === false) {
                        $this->increase_error_count();
                        return $input;
                    }
                    else {
                        $input['clean'] = $input['value'];
                    }
                    break;
                case "select2":
                case "select":
                    $input['selected_option'] = $value;
                    $input['clean'] = $value;
                    break;
                case "file":     
                    break; 
                case "hidden":
                        if (isset($input['nonce'])) {
                            $retrieved_nonce = $value;
                            if (!wp_verify_nonce($retrieved_nonce, 'search_nonce' ) ) {
                                $this->increase_error_count();
                                die( 'Failed security check' );
                                return;  
                            }
                        }
                        if (isset($input['expected'])) {
                            if ($input['expected'] != $value ) {
                                $this->increase_error_count();
                                return;  
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
            }
            // esc_attr() - Escaping for HTML attributes. Encodes the <, >, &, ” and ‘ (less than, greater than, ampersand, double quote and single quote) characters. Will never double encode entities.
            $input['passed'] = true;   
        }

        // echo "<pre>"; var_dump($input); echo "</pre>";
        // echo "<hr>";echo "<hr>";
        return;         
    }//@end check_input 


    // private function get_form_data($id) {
    //     $form_data = array();
    //     $settings = array();
    //     $sections = array();
    //     if ( have_rows('sections', $id) ) :

    //         $section_count = 0;
    //         $input_count = 0;

    //         while( have_rows('sections', $id) ) : the_row();
    //             $section = array();
    //             $inputs = array();

    //             if ( get_sub_field('section_header') ) {
    //                 $section["section_header"] = get_sub_field('section_header');
    //             }
    //             if ( get_sub_field('section_content') ) {
    //                 $section["section_content"] = get_sub_field('section_content');
    //             }            
    //             if ( have_rows('form_inputs') ) :
    //                 while( have_rows('form_inputs') ) : the_row();
    //                     $inputs_settings_array = $this->build_form_array($inputs, $settings, $section_count);  
    //                     $settings = $inputs_settings_array["settings"];
    //                     $inputs = $inputs_settings_array["inputs"];
    //                     $input_count++;
    //                 endwhile;
    //             endif;

    //             $section["inputs"] = $inputs;
    //             $sections[] = $section;
    //             $section_count++;
    //         endwhile;

    //         if ($input_count) {
    //             $form_data["sections"] = $sections;
    //             $form_data["settings"] = $settings;
    //         }

    //     endif;
    //     return $form_data; 
    // }  
    // private function build_form_array($inputs, $settings, $section=0) {
    //     global $post;
    //     $id = '';
    //     $type = 'text';
    //     $data_type = get_sub_field('type');
    //     $name = '';
    //     $label = '';
    //     $placeholder = '';
    //     $help = '';
    //     $instructions = '';
    //     $required = '';
    //     $grouping = false;
    //     $select_options='';
    //     $prefix = 'form-';

    //     if( get_sub_field('id') ) {
    //         $id_group = get_sub_field('id');
    //         if ($id_group["name"]) {
    //             $name = $id_group["name"];
    //             $id = sanitize_title_with_dashes( $name );
    //             if ($id_group["label"]) {
    //                 $label = $id_group["label"];
    //             }
    //             else {
    //                 $label = $name;
    //             }
    //         }
    //     }

    //     if( get_sub_field('reporting') ) {
    //         $reporting_group = get_sub_field('reporting');
    //         $help = $reporting_group["help"];
    //         $instructions = $reporting_group["instructions"];
    //     }

    //     if( get_sub_field('settings') ) {
    //         $settings_group = get_sub_field('settings');
    //         $required = $settings_group["required"];
    //         $grouping = $settings_group["grouping"];
    //         if ($grouping == 'none') {
    //             $grouping = false;
    //         }
    //         else {
    //             if (!isset($settings["groupings"])) {
    //                 $settings["groupings"] = true;
    //             }
    //         }
    //     }
        
    //     if($required) {
    //         $required = 'required';
    //     }
    //     else {
    //         $required = '';
    //     }

    //     if( get_sub_field('type') ) {
    //         $type = get_sub_field('type');
    //         $data_type = get_sub_field('type');
    //     }
    //     if($type==='date' || $type==='date_time' || $type==='date_range') {
    //         $type='text';
    //     }

    //     if( get_sub_field('placeholder') ) {
    //         $placeholder = get_sub_field('placeholder');
    //     }

    //     if( get_sub_field('select_options') ) {
    //         $select_options = get_sub_field('select_options');
    //         if ($data_type === 'checkbox' || $data_type === 'select' || $data_type === 'radio') {
    //             foreach ($select_options as $key => $value) {
    //                 $value['checked'] = false;
    //                 if ( $value['option_value'] === '') {
    //                     $select_options[$key]['option_value'] = sanitize_title_with_dashes( $value['option'] );
    //                 }
    //                 if ($data_type==='checkbox') {
    //                     $select_options[$key]['checked'] = false;
    //                 }               
    //             }
    //         }          
    //     }

    //     /*
    //      * Check for array key conflict and increment $id if found
    //      */
    //     if (array_key_exists($prefix.$id, $inputs)) {
    //         for ($i=1; $i < 20; $i++) { 
    //             if (!array_key_exists($prefix.$id.'-'.$i, $inputs)) {
    //                 $id = $id.'-'.$i;
    //                 break;
    //             }
    //         }
    //     }    

    //     switch ($data_type) {           
    //         case "text":
    //         case "url":
    //         case "email":
    //         case "number":
    //             $inputs[$prefix.$id] = array("passed"=>false, "clean"=>"", "value"=>"", "section"=>$section, "required"=>$required, "type"=>$type, "data_type"=>$data_type,  "placeholder"=>$placeholder, "label"=>$label, "help"=>$help, "instructions" => $instructions, "grouping" => $grouping);
    //             break;
    //         case "textarea":
    //             $inputs[$prefix.$id] = array("passed"=>false, "clean"=>"", "value"=>"", "section"=>$section, "required"=>$required, "type"=>$type, "data_type"=>$data_type,  "placeholder"=>$placeholder, "label"=>$label, "help"=>$help, "instructions" => $instructions, "grouping" => $grouping);
    //             break; 
    //         case "select":
    //         case "multi_select":
    //         case "checkbox":
    //         case "radio":
    //             $inputs[$prefix.$id] = array("passed"=>false, "clean"=>"", "value"=>"", "section"=>$section, "required"=>$required, "type"=>$type, "data_type"=>$data_type, "label"=>$label, "options"=>$select_options, "selected_option"=>"", "help"=>$help, "instructions" => $instructions, "grouping" => $grouping);//,  "placeholder"=>$placeholder
    //             break;    
    //         case "file":
    //             $enctype = 'enctype="multipart/form-data"';
    //             $form_class = 'js-check-form-file';
    //             $inputs[$prefix.$id] = array("passed"=>false, "clean"=>"", "value"=>"", "section"=>$section, "required"=>$required, "type"=>$type, "data_type"=>$data_type,  "placeholder"=>$placeholder, "label"=>$label, "accept"=>"pdf", "help"=>$help, "instructions" => $instructions, "grouping" => $grouping);
    //             break;              
    //         case "date_range":
    //             $inputs[$prefix.$id.'-start'] = array("passed"=>false, "clean"=>"", "value"=>"", "section"=>$section, "required"=>$required, "type"=>$type, "data_type"=>$data_type, "label"=>"Date From", "help"=>$help, "instructions" => $instructions, "grouping" => $grouping, 'order'=>0, 'parent_label'=>$label);
    //             $inputs[$prefix.$id.'-end'] = array("passed"=>false, "clean"=>"", "value"=>"", "section"=>$section, "required"=>$required, "type"=>$type, "data_type"=>$data_type, "label"=>"Date To", "help"=>$help, "instructions" => $instructions, "grouping" => $grouping, 'order'=>1, 'parent_label'=>$label);
    //             break;                                               
    //     }
    //     return array("inputs" => $inputs, "settings" => $settings);       
    // }        
}