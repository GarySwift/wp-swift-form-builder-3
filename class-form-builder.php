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
    private $form_name = '';
    private $submit_button_id = '';
    private $submit_button_name = '';
    private $submit_button_text = '';
    private $css_framework = "zurb_foundation";
    private $show_mail_receipt = false;
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
     * Initializes the plugin.
     */
    public function __construct($form_data=false, $form_builder_args=false) { //"option") {
        
        $this->set_form_data($form_data, $form_builder_args);
        // add_action( 'wp_enqueue_scripts', array( $this, 'wp_swift_form_builder_css_file') );
        // add_action( 'wp_enqueue_scripts', array($this, 'enqueue_javascript') );
        /*
         * Inputs
         */
        // add_action( 'admin_menu', array($this, 'wp_swift_form_builder_add_admin_menu'), 20 );
        // add_action( 'admin_init', array($this, 'wp_swift_form_builder_settings_init') );
        if (isset($attributes["section-layout"])) {
            $section_layout_string = $attributes["section-layout"];
            if ( class_exists($section_layout_string) ) {
                $this->Section_Layout_Addon = new $section_layout_string();
            }
        }
    }

    /*
     * Set the form data
     */
    // $this->set_form_data($form_data, $form_builder_args);
    // public function set_form_data($form_inputs="form_inputs", $post_id, $args=false, $attributes= false, $option=false) {
    public function set_form_data($form_inputs=array(), $args=false) {
        // include('_set-form-data.php');
        
        /*
         * @function  set_form_data
         * Set the form data
         *
         * @param       $form_inputs        string or array
         * @param       $post_id            the post id
         * @param       $form_inputs        string or array
         */
        // public function set_form_data($form_inputs="form_inputs", $post_id, $args=false, $attributes= false, $option=false)


        // $this->form_settings = array();
        // $this->post_id = $post_id;
        // $this->form_settings["form_pristine"] = true;
        // $this->form_settings["form_num_error_found"] = 0;
        $this->error_count = 0;
        // $this->form_settings["enctype"] = "";
        // $this->form_settings["form_class"] = "";
        $this->form_settings["option"]=$args["option"];
        $this->option = $args["option"];
        if (isset($args["show_mail_receipt"]) && $args["show_mail_receipt"] === true) {
            $this->show_mail_receipt = true;
        }
        // if( function_exists('acf')) {
        //     if (get_sub_field('form_name', $post_id)) {
        //          $this->form_settings["form-name"] = sanitize_title_with_dashes( get_sub_field('form_name') );
        //     }
        //     // elseif(isset($args["form_name"])) {
        //     //     $this->form_settings["form-name"] = sanitize_title_with_dashes($args["form_name"]);
        //     // }
        //     // else {
        //     //      $this->form_settings["form-name"] = "request-form";
        //     // }
        //     if (get_sub_field('button_text', $post_id)) {
        //           $this->form_settings["submit-button-text"] = get_sub_field('button_text');
        //     }
        //     // elseif(isset($args["button_text"])) {
        //     //     $this->form_settings["submit-button-text"] = $args["button_text"];
        //     // }
        //     // else {
        //     //       $this->form_settings["submit-button-text"] = "Submit Form";
        //     // }
        // }

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
            // echo "<pre>";var_dump($args["clear_after_submission"]);echo "</pre>";
            $this->clear_after_submission = $args["clear_after_submission"];
            // echo "<pre>";var_dump($this->clear_after_submission);echo "</pre>";
        }

        if (isset($args["action"]) && $args["action"]!='') {
            $this->action = 'action="'.$args["action"].'"';
        }
        if (isset($args["list_form_errors_in_warning_panel"])) {
            $this->list_form_errors_in_warning_panel = $args["list_form_errors_in_warning_panel"];
        }
        if (isset($args["form_class"])) {
            $this->form_class = $args["form_class"];
        }
        // $this->form_settings["form-name"] = "request-form";
        // $this->form_settings["submit-button-text"] = "Submit Form";
        // $this->form_settings["submit-button-name"] = "submit-".$this->form_settings["form-name"];


        $this->form_settings["error_class"] = "";
        $this->form_settings["ajax"] = false;
        $form_data = array();
                
        if (is_array($form_inputs)) {
            $this->form_settings["form_data"] = $form_inputs;
            $this->form_inputs = $form_inputs;
        }
        else if (is_string($form_inputs)) {
            if( function_exists('acf')) {
                 // Construct the array that makes the form
                if ( have_rows($form_inputs, $option) ) {

                    $this->form_settings = array();
                    $this->form_settings["form_pristine"] = true;
                    $this->form_settings["form_num_error_found"] = 0;
                    $this->form_settings["enctype"] = "";
                    $this->form_settings["form_class"] = "";
                    $this->form_settings["option"]=$option;
                    if (get_sub_field('form_name')) {
                         $this->form_settings["form-name"] = sanitize_title_with_dashes( get_sub_field('form_name') );
                    }
                    else {
                         $this->form_settings["form-name"] = "request-form";
                    }
                    if (get_sub_field('button_text')) {
                          $this->form_settings["submit-button-text"] = get_sub_field('button_text');
                    }
                    else {
                          $this->form_settings["submit-button-text"] = "Submit Form";
                    }

                    $this->form_settings["submit-button-name"] = "submit-".$this->form_settings["form-name"];
                    $this->form_settings["error_class"] = "";
                    $this->form_settings["ajax"] = false;
                    $form_data = array();

                    while( have_rows($form_inputs, $option) ) : the_row(); // Loop through the repeater for form inputs

                        $name =  get_sub_field('name');
                        $id = sanitize_title_with_dashes( get_sub_field('name') );
                        $type = get_sub_field('type');
                        $label = get_sub_field('label');
                        $help = get_sub_field('help');
                        $placeholder = get_sub_field('placeholder');
                        $required = get_sub_field('required');
                        $select_options='';

                        // If the user has manually added options with the repeater
                        if( get_sub_field('select_options') ) {
                            $select_options = get_sub_field('select_options');

                            if(get_sub_field('select_type') === 'user') {                    
                                for ($i = 0; $i < count($select_options); ++$i) {
                                    if($select_options[$i]['option_value']=='') {
                                        $select_options[$i]['option_value'] = sanitize_title_with_dashes( $select_options[$i]['option'] );
                                    }
                                }   
                            }
                        }

                        // If the user has elected to select predefined options - only countries available at the moment
                        if(get_sub_field('select_type') === 'select') {
                            $countries = getCountries(); // Returns an array of countries
                            $i=0;
                            // Push each entry into $select_options in a usable way
                            foreach ($countries as $key => $value) {
                                ++$i;
                                $select_options[$i]['option_value']  = sanitize_title_with_dashes($key);
                                $select_options[$i]['option'] = $value;//$key;
                            }                     
                        }

                        if($required) {
                            $required = 'required';
                        }
                        else {
                            $required = '';
                        }
                        if(!$label) {
                            $label = $name;
                        }

                        switch ($type) {
                            case "text":
                            case "url":
                            case "email":
                            case "number":
                            case "password":
                                $form_data['form-'.$id] = array("passed"=>false, "clean"=>"", "value"=>"", "section"=>1, "required"=>$required, "type"=>$type,  "placeholder"=>$placeholder, "label"=>$label, "help"=>$help);
                                break;
                            case "textarea":
                                $form_data['form-'.$id] = array("passed"=>false, "clean"=>"", "value"=>"", "section"=>1, "required"=>$required, "type"=>$type,  "placeholder"=>$placeholder, "label"=>$label, "help"=>$help);
                                break; 
                            case "checkbox":
                            case "select":
                                $form_data['form-'.$id] = array("passed"=>false, "clean"=>"", "value"=>"", "section"=>1, "required"=>$required, "type"=>$type,  "placeholder"=>$placeholder, "label"=>$label, "options"=>$select_options, "selected_option"=>"", "help"=>$help);
                                break;
                            case "multi_select":
                                $form_data['form-'.$id] = array("passed"=>false, "clean"=>"", "value"=>"", "section"=>1, "required"=>$required, "type"=>$type,  "placeholder"=>$placeholder, "label"=>$label, "options"=>$select_options, "selected_option"=>"", "help"=>$help);
                                break;    
                           case "file":
                                $this->form_settings["enctype"] = ' enctype="multipart/form-data"';
                                $this->form_settings["form_class"] = 'js-check-form-file';
                                $form_data['form-'.$id] = array("passed"=>false, "clean"=>"", "value"=>"", "section"=>1, "required"=>$required, "type"=>$type,  "placeholder"=>$placeholder, "label"=>$label, "accept"=>"pdf", "help"=>$help);
                                break;            
                        }           
                            
                    endwhile;// End the AFC loop  
                    $this->form_settings["form_data"] = $form_data;
                }
            }
        }
        // return $this->form_settings;            
    }//@end set_form_data()


    /*
     * Build the form
     */
    public function acf_build_form() {
        // include('_acf-build-form.php');
        /*
         * acf_build_form()
         */
        if ($this->get_error_count()>0):
        $framework = $this->css_framework;
        $options = get_option( 'wp_swift_form_builder_settings' );
        if (isset($options['wp_swift_form_builder_select_css_framework'])) {
            $framework = $options['wp_swift_form_builder_select_css_framework'];
        }
        if ($framework === "zurb_foundation") :
            ?>
            <div class="callout warning">
                        <h3>Errors Found</h3>
            <?php
        elseif ($framework === "bootstrap"):
            ?>
            <div class="panel panel-danger">
              <div class="panel-heading"><h3>Errors Found</h3></div>
              <div class="panel-body">
        <?php else:?>
            <h3>Errors Found</h3><?php  
        endif;



        ?>


            <!-- <div class="callout warning"> -->

                <p>We're sorry, there has been an error with the form input. Please rectify the <?php echo $this->get_error_count() ?> errors below and resubmit.</p>
                <ul><?php 
                    if ($this->list_form_errors_in_warning_panel) {
                       foreach ($this->form_inputs as $key => $data) {

                            if (isset($data["passed"]) && !$data["passed"] && $data["type"] != "checkbox") {
                                if ($data["help"]): 
                                ?>
                                    <li><?php echo $data["help"] ?></li>
                                <?php else: 
                                    $help = $data['label'].' is required';
                                    if ($data['type']=='email' || $data['type']=='url') {
                                        $help .= ' and must be valid';
                                    }
                                    ?>
                                    <li><?php echo $help ?></li>
                                <?php 
                                endif;
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
            <!-- </div> -->

        <?php 

        if ($framework === "zurb_foundation") :
            ?>
            </div>
            <?php
        elseif ($framework === "bootstrap"):
            ?>
          </div>
        </div>
            <?php
        endif;
        // endif;
        // $this->success_msg = "Testing";

        elseif($this->get_error_count()===0):
        ?>
            <?php if ($this->success_msg !== ''): ?>
                <div class="callout warning">
                    <h3>Form Saved</h3>
                    <?php echo $this->success_msg; ?>
                </div>
            <?php endif ?>
        <?php
        endif;

        if (count($this->extra_msgs ) > 0 && $this->get_error_count()===0): ?>
            <div class="callout warning">
                <h3>Notifications</h3>
                <ul><?php 
                    if (count($this->extra_msgs)) {
                        foreach ($this->extra_msgs as $key => $msg) {
                        ?>
                            <li><?php echo $msg ?></li>
                        <?php 
                        }
                    } 
                 ?></ul>
            </div>
        <?php 
        endif;

        $this->front_end_form();
    }//@end acf_build_form()

    public function front_end_form() {
        $framework='zurb';
        $options = get_option( 'wp_swift_form_builder_settings' );
        if (isset($options['wp_swift_form_builder_select_css_framework'])) {
            $framework = $options['wp_swift_form_builder_select_css_framework'];
        } 

        // ob_start();
        ?>

        <!-- @start form -->
        <form method="post" <?php echo $this->action; ?> name="<?php echo $this->form_name; ?>" id="<?php echo $this->form_id; ?>" class="<?php echo $framework.' '; echo $this->form_class.' '; echo $this->form_name ?>" novalidate<?php echo $this->enctype; ?>>
            <?php
            $this->front_end_form_input_loop($this->form_inputs, $this->tab_index, $this->form_pristine, $this->error_count);
            if ($this->show_mail_receipt): ?>

            <!-- @start .mail-receipt -->
            <div class="form-group mail-receipt">
                <div class="form-label"></div>
                <div class="form-input">
                    <div class="checkbox">
                      <input type="checkbox" value="" tabindex=<?php echo $this->tab_index; ?> name="mail-receipt" id="mail-receipt"><label for="mail-receipt">Acknowledge me with a mail receipt</label>
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

        // $html = ob_get_contents();
        // ob_end_clean();
        
        // echo $html;                
    }// front_end_form()

    public function front_end_form_input_loop() {
        $i=0;
        foreach ($this->form_inputs as $id => $input):
            $i++;
            if (isset($input['data_type'])) {
                switch ($input['data_type']) {
                    case "section": 
                        if ($this->Section_Layout_Addon) {
                            $this->Section_Layout_Addon->section_open($input['section_header'], $input['section_content']);
                        }
                        else {
                            $this->section_open($input['section_header'], $input['section_content']);
                        }
                        break; 
                    case "section_close":
                        if ($this->Section_Layout_Addon) {
                            $this->Section_Layout_Addon->section_close();
                        }
                        else {
                            $this->section_close();
                        } 
                        break;               
                    case "text":
                    case "url":
                    case "email":
                    case "number":
                    case "username": // Wordpress username
                    // case "input_combo":
                    case "password":
                        $input_html = $this->bld_form_input($id, $input);
                        echo $this->wrap_input($id, $input, $input_html);
                        break;
                    case "input_combo":
                    // echo "<pre>"; var_dump($input); echo "</pre><hr>";
                        $input_html = $this->bld_form_input($id, $input);
                        $form_group = $this->wrap_input($id, $input, $input_html);
                        // echo $form_group;
                        echo $this->bld_combo_form_input($id, $input, $form_group);
         
                        // $input_html = $this->bld_form_input($id, $input);
                        // $this->wrap_input($id, $input, $input_html);
                        break;  
                    // case "date_range":
                    //     $this->bld_combo_form_input($id, $input);
                    //     break;         
                    case "hidden":
                        $this->bld_form_hidden_input($id, $input);
                        break;
                    case "textarea":
                        $input_html = $this->bld_form_textarea($id, $input);
                        echo $this->wrap_input($id, $input, $input_html);
                        break; 
                    case "radio":
                        $this->build_form_radio($id, $input);
                    case "checkbox":
                        $this->build_form_checkbox($id, $input);
                        break; 
                    case "multi_select":
                    case "select":
                        $input_html = $this->bld_form_select($id, $input, '');
                        // $this->bld_form_select($id, $input, '');
                        $this->wrap_input($id, $input, $input_html);
                        break;
                    default:
                        // echo "<pre>Unkown type: ".$input['data_type']."</pre><hr>";
                        // echo "<pre>";var_dump($input);echo "</pre>";
                        break;                                                             
                }  
            }
                 
        endforeach;
    }

    /*
     *
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
        // $grid_grouping_class = '';
        // if (!$input["grouping"]) {
        //     $grid_grouping_class = ' form-not-grid-grouping';
        // }
        // elseif ($input["grouping"] == "start") {

        // }
        //         elseif ($input["grouping"] == "start") {

        // }
        // ob_start();

        //echo "<pre>"; var_dump($input["grouping"]); echo "</pre>";
        ?>

        <?php if ($input["grouping"] && $input["grouping"] == "start"): ?>

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

            </div><!-- @end form element -->

        <?php if ($input["grouping"] && $input["grouping"] == "end"): ?>
             
            </div>
            <!-- end grouping -->    
        <?php endif;            
    
        // $form_group = ob_get_contents();
        // ob_end_clean();
        
        // return $form_group;        
    }

    /*************************************************************************/
    /*************************************************************************/
    /*************************************************************************/

    public function get_show_mail_receipt() {
        return $this->show_mail_receipt;
    }
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
    public function validate_form($input_keys_to_skip=array()) {
        $this->default_input_keys_to_skip = array('submit-request-form', 'mail-receipt', 'form-file-upload', 'g-recaptcha-response');
        $this->default_input_keys_to_skip = array_merge($this->default_input_keys_to_skip, $input_keys_to_skip);

        // The form is submitted by a user and so is no longer pristine
        $this->set_form_pristine(false);
        //Loop through the POST and validate. Store the values in $form_data
        foreach ($_POST as $key => $value) {
            // echo "key <pre>"; var_dump($key); echo "</pre>";
            if (!in_array($key, $this->default_input_keys_to_skip)) { //Skip the button,  mail-receipt checkbox, g-recaptcha-response etc
                $check_if_submit = substr($key, 0, 7);
                // Get the substring of the key and make sure it is not a submit button
                if ($check_if_submit!='submit-') {

                    $this->check_input($key, $value);//Validate input    
                }
                
            }
        }
    }

    public function process_form() {
        echo "<div class='callout secondary'>"; 
        echo '<h5>public function process_form()</h5>';
        echo '<p>This the the default form handling for the <code>WP Swift: Form Builer</code> plugin. You will need to write your own function to handle this POST request.</p>';
        echo 'var_dump($_POST)<br><br>';
        echo "<pre>";var_dump($_POST);echo "</pre>";
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
        return $this->error_count;//form_num_error_found
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

    // public function enqueue_javascript () {
    //     $options = get_option( 'wp_swift_form_builder_settings' );
    //     // echo "<pre>wp-swift-form-builder</pre>";
        
    //     if (isset($options['wp_swift_form_builder_checkbox_javascript'])==false) {
    //        wp_enqueue_script( $handle='wp-swift-form-builder', $src=plugins_url( '/assets/javascript/wp-swift-form-builder.js', __FILE__ ), $deps=null, $ver=null, $in_footer=true );
    //     }
    // }

    /*
     * Add the css file
     */
    // function wp_swift_form_builder_css_file() {
    //     $options = get_option( 'wp_swift_form_builder_settings' );
    //     // echo "<pre>2 wp-swift-form-builder-style</pre>";
    //     // echo "<pre>"; var_dump($options); echo "</pre>";
    //     // echo "<pre>"; var_dump(!isset($options['wp_swift_form_builder_checkbox_css'])); echo "</pre>";
    //     // echo "<pre>"; var_dump(isset($options['wp_swift_form_builder_checkbox_css'])); echo "</pre>";
    //     if (isset($options['wp_swift_form_builder_checkbox_css'])==false) {
    //         wp_enqueue_style('wp-swift-form-builder-style', plugins_url( 'assets/css/wp-swift-form-builder.css', __FILE__ ) );
    //     }

    // }
    
    /*
     * Build the HTML before the form input
     */
    public function before_form_input($id, $data) {
        $data = $this->form_element_open($id, $data);
        $this->form_element_anchor($id);
        $this->form_element_label($id, $data);
        $this->form_element_form_input_open();
        return $data;
    }
    /*
     * Build the HTML after the form input
     */
    public function after_form_input($id, $data) {
        $data = $this->form_element_help($data);
        $this->form_element_form_input_close();
        $this->form_element_close($id, $data);
        return $data;
    }

    public function bld_form_input($id, $input, $section='') {
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

    public function bld_form_select($id, $data, $multiple='') {

        if(!$this->form_pristine) {
            if($this->clear_after_submission && $this->error_count===0) {
                // No errors found so clear the selected value
                $data['selected_option']=''; 
            }
        }
        // ob_start();
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
        // $input_html = ob_get_contents();
        // ob_end_clean();
        // return $input_html;
    }
    public function bld_form_textarea($id, $input) {
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

    public function bld_combo_form_input($id, $data, $form_group, $section='') {
        // ob_start();

        if (isset($data['order']) && $data['order'] == 0):
            ?>

            <!-- @start .form-builder-combo-row -->
            <div class="row form-builder-combo-row">
            
            <div class="small-12 medium-6 large-6 columns columns-1">
            <div class="input-1">
                <?php  echo $form_group; ?>

            </div><!-- @end .input-1 -->
            </div><!-- @end .columns-1.columns -->
            
        <?php elseif (isset($data['order']) && $data['order'] == 1): ?>
            
            <div class="small-12 medium-6 large-6 columns columns-2">
            <div class="input-2">
                <?php echo $form_group; ?>

            </div><!-- @end .input-2 -->            
            </div><!-- @end .columns-2.columns -->
            
            </div>
            <!-- @end .form-builder-combo-row -->
        <?php endif;
  
        // $html_combo_row = ob_get_contents();
        // ob_end_clean();
        
        // return $html_combo_row;
    } 

    public function bld_form_hidden_input($id, $data, $tabIndex=0, $section='') {
        // echo "<pre>"; var_dump($data); echo "</pre>";
        // $has_error='';
        // echo "<pre>this->form_pristine: "; var_dump($this->form_pristine); echo "</pre>";
        // echo "<pre>this->clear_after_submission "; var_dump($this->clear_after_submission); echo "</pre>";
        // echo "<pre>this->error_count "; var_dump($this->error_count); echo "</pre>";
        // if(!$this->form_pristine) {
        //     if($this->clear_after_submission && $this->error_count===0) {
        //         // No errors found so clear the values
        //         $data['value']=''; 
        //     }
        // }
        // data_type is the same as $data['type'] unless it is an invalid attributes type such as username
        // $data_type = $data['type'];
        // if ($data['type']=='username') {
        //     $data['type']='text';
        //     $data_type = 'username';
        // }
        // $data = $this->before_form_input($id, $data);
        if (isset($data['data-type'])) {
            $data_type = $data['data-type'];
        }
        else {
            $data_type = $data['type'];
        }
        if (isset($data['name'])) {
            $name = $data['name'];
        }
        else {
            $name = $id;
        }
        
        if (isset($data['id-index'])) {
            $id .= '-'.$data['id-index'];
        }
        ?><input 
            type="hidden" 
            data-type="<?php echo $data_type; ?>" 
            class="hidden" 
            id="<?php echo $id; ?>" 
            name="<?php echo $name; ?>" 
            value="<?php echo $data['value']; ?>"
            <?php if ( isset($data["section"])): ?> data-section="<?php echo $data["section"] ?>" <?php endif ?>
            <?php echo $data['required']; ?>   
        ><?php 
        // $data = $this->after_form_input($id, $data);
    } 
    private function form_element_open($id, $data) {
            $has_error='';

            if(!$this->form_pristine && $data['passed']==false && $data["type"] !== "checkbox") {
                // This input has has error detected so add an error class to the surrounding div
                $has_error = 'has-error';
            }
            // echo "<pre>has_error: "; var_dump($has_error); echo "</pre>";
            // echo "<pre>"; var_dump($data); echo "</pre>";
            if(!$this->form_pristine) {
                if($this->clear_after_submission && $this->error_count===0) {
                    // No errors found so clear the values
                    $data['value']=''; 
                }
            }
            // $has_error = 'has-error';
        ?><!-- @start form element -->
        <div class="row form-group form-builder <?php echo $has_error; ?>" 
        id="<?php echo $id; ?>-form-group"><?php 
        return $data;
    }
    private function form_element_anchor($id) {
        ?><a href="<?php echo $id; ?>-anchor"></a><?php
    }
    private function form_element_label($id, $data) {
        // echo "Required: <pre>".$data['required']."</pre><hr>";
        ?><div class="<?php echo $this->get_form_label_div_class() ?> form-label">
            <?php if ($data['label']!=''): ?>
                <label for="<?php echo $id; ?>" class="control-label <?php echo $data['required']; ?>"><?php echo $data['label']; ?> <span></span></label>
            <?php endif ?>
        </div><?php     
    }
    private function form_element_close() {
        ?></div><!-- @end form element --><?php 
    }
    private function form_element_form_input_open() {
        ?><div class="<?php echo $this->get_form_input_div_class() ?> form-input"><?php /*small-12 medium-9 large-9 columns*/
    }
    private function form_element_form_input_close() {
        ?></div><?php 
    }
    private function form_element_help($data) {
        if ($data['help']) {
             $help = $data['help'];
        }
        else {
            $help = $data['label']. ' is required';
            if ($data['type']=='email' || $data['type']=='url') {
                $help .= ' and must be valid';
            }  
            $data['help'] = $help;
        }   
        if ($data['required']): 
            ?><small class="error"><?php echo $help; ?></small><?php 
        endif;
        if (isset($data['instructions']) && $data['instructions']): 
            ?><small class="instructions"><?php echo $data['instructions']; ?></small><?php 
        endif;
        return $data;
    }
    /*
     * Get the CSS class for div wrapping the label
     */
    private function get_form_label_div_class() {
        $framework = $this->css_framework;
        $options = get_option( 'wp_swift_form_builder_settings' );
        if (isset($options['wp_swift_form_builder_select_css_framework'])) {
            $framework = $options['wp_swift_form_builder_select_css_framework'];
        }

        if ($framework === "zurb_foundation") {
            return $framework."";//" small-12 medium-12 large-12 columns "
        }
        elseif ($framework === "bootstrap") {
            return "col-xs-12 col-sm-12 col-md-12 col-lg-12 ";
        }    
        else {
            return "";
        }
    }
    /*
     * Get the CSS class for div wrapping the label
     */
    private function get_form_input_div_class() {
        $framework = $this->css_framework;
        $options = get_option( 'wp_swift_form_builder_settings' );
        if (isset($options['wp_swift_form_builder_select_css_framework'])) {
            $framework = $options['wp_swift_form_builder_select_css_framework'];
        }
           
        if ($framework === "zurb_foundation") {
            return "";//" small-12 medium-12 large-12 columns "
        }
        elseif ($framework === "bootstrap") {
            return "col-xs-12 col-sm-12 col-md-12 col-lg-12 ";
        }     
        else {
            return "";
        }
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




    function build_form_radio($id, $input) {
        if(!$this->form_pristine) {
            if($this->clear_after_submission && $this->error_count===0) {
                // No errors found so clear the selected value
                $input['selected_option']=''; 
            }
        }

        $this->before_form_input($id, $input);
        $count=0;  
        $checked='';
          ?><?php 
            foreach ($input['options'] as $option): $count++;
                if ( ($input['selected_option']=='' && $count==1) || ($input['selected_option']==$option['option_value'])){
                    $checked=' checked';
                }
                ?><input id="<?php echo $id.'-'.$count ?>" name="<?php echo $id ?>-radio" type="radio" tabindex="<?php echo $this->tab_index++; ?>" value="<?php echo $option['option_value'] ?>"<?php echo $checked; ?>>
                <label for="<?php echo $id.'-'.$count ?>"><?php echo $option['option'] ?></label><?php 
            endforeach; ?><?php
        $this->after_form_input($id, $input);
    }
    function build_form_checkbox($id, $data) {
        if(!$this->form_pristine) {
            if($this->clear_after_submission && $this->error_count===0) {
                // No errors found so clear the checked values
                foreach ($data['options'] as $key => $option) {
                    $data['options'][$key]['checked'] = false;
                }
            }
        }

        $data = $this->before_form_input($id, $data);
        $count=0;  
        
        $name_append = '';
        if (count($data['options']) > 1) {
            $name_append = '[]';
        }
     
        foreach ($data['options'] as $option): $count++;
            $checked='';
            // echo "<br><pre>";var_dump($option);echo "</pre>";
            if ( $option['checked'] == true ){
                $checked=' checked';
            }
            if (isset($data['name'])) {
                $name = $data['name'].$name_append;
            }
            else {
                $name = $id.''.$name_append;
                // $name = $id.'-checkbox'.$name_append;
            }
            ?><label for="<?php echo $id.'-'.$count ?>" class="lbl-checkbox"><input id="<?php echo $id.'-'.$count ?>" name="<?php echo $name ?>" type="checkbox" tabindex="<?php echo $this->tab_index++; ?>" value="<?php echo $option['option_value'] ?>"<?php echo $checked; ?>>
            <?php echo $option['option'] ?></label><?php 
        endforeach;
        $data = $this->after_form_input($id, $data);
    }

    public function section_open($section_header, $section_content) {
 
        if ($section_header): ?>

            <!-- @start section-header -->
            <header class="form-section-header">
                <h4><?php echo $section_header ?></h4>
            </header>
            <!-- @end section-header  -->
        <?php endif;

        if ($section_content): ?>

            <!-- @start section-content -->
            <div class="form-section-content">
                <div class="form-section-content-inner">
                    <p><?php echo $section_content ?></p>
                </div>
            </div>
            <!-- @end section-content -->
        <?php endif;  
    }

    public function section_close() {
    ?>

        <!-- @end section -->
        <?php       
    } 

    public function html_section_open_side_by_side ($section_header, $section_content) {
      ?>
          <div class="row form-section">
           <div class="small-12 medium-6 large-6 columns large-push-6">
               <div class="search-info">
                   <h3 class="search-header-info"><?php echo $section_header ?></h3>
                   <div class="entry-content"><?php echo $section_content ?></div>
               </div>
           </div>
           <div class="small-12 medium-6 large-6 columns large-pull-6">   
      <?php
        return '';
    }

    public function html_section_close_side_by_side () {
        $html = '</div>';
        $html .= '</div>'; 
        return $html;
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
        // include('_check-input.php');
        /*
         * Check an individual form input field and sets the array with the findings 
         *
         * @param $key      an array key that matches the form input name (POST key)
         * @param $value    the value of the form input
         */
        // public function check_input($key, $value){

        $this->form_inputs[$key]['value'] = $value;

        if($this->form_inputs[$key]['required'] && $this->form_inputs[$key]['value']=='') {
            $this->increase_error_count();
            return;
        }

        else if(!$this->form_inputs[$key]['required'] && $this->form_inputs[$key]['value']=='') {
            $this->form_inputs[$key]['clean'] = $this->form_inputs[$key]['value'];
            $this->form_inputs[$key]['passed'] = true;
            return;
        }

        if(!is_array($this->form_inputs[$key]['value'])) {
            $this->form_inputs[$key]['value'] = trim($this->form_inputs[$key]['value']);
           
        }

        switch ($this->form_inputs[$key]['type']) {
            case "text":
            case "textarea":
                $this->form_inputs[$key]['clean'] = sanitize_text_field( $this->form_inputs[$key]['value'] );
                break;
            case "username":
                $username_strlen = strlen ( $this->form_inputs[$key]['value']  );
                if ($username_strlen<4 || $username_strlen>30) {
                    $this->increase_error_count();
                    return $this->form_inputs[$key];
                }
                $this->form_inputs[$key]['clean'] = sanitize_user( $this->form_inputs[$key]['value'], $strict=true ); 
                break;
            case "email":
                if ( !is_email( $this->form_inputs[$key]['value'] ) ) { 
                    $this->increase_error_count();
                    return $this->form_inputs[$key]; 
                }
                else {
                    $this->form_inputs[$key]['clean'] = sanitize_email( $this->form_inputs[$key]['value'] );  
                }
                break;
            case "number":
                if ( !is_numeric( $this->form_inputs[$key]['value'] ) ) { 
                    $this->increase_error_count();
                    return $this->form_inputs[$key]; 
                }
                else {
                    $this->form_inputs[$key]['clean'] = $this->form_inputs[$key]['value'];  
                }
                break;        
            case "url":
                if (filter_var($this->form_inputs[$key]['value'], FILTER_VALIDATE_URL) === false) {
                    $this->increase_error_count();
                    return $this->form_inputs[$key];
                }
                else {
                    $this->form_inputs[$key]['clean'] = $this->form_inputs[$key]['value'];
                }
                break;
            case "select2":
            case "select":
                $this->form_inputs[$key]['selected_option'] = $value;
                $this->form_inputs[$key]['clean'] = $value;
                break;
            case "file":     
                break; 
            case "hidden":
                    if (isset($this->form_inputs[$key]['nonce'])) {
                        $retrieved_nonce = $value;
                        if (!wp_verify_nonce($retrieved_nonce, 'search_nonce' ) ) {
                            $this->increase_error_count();
                            die( 'Failed security check' );
                            return;  
                        }
                    }
                    if (isset($this->form_inputs[$key]['expected'])) {
                        if ($this->form_inputs[$key]['expected'] != $value ) {
                            $this->increase_error_count();
                            return;  
                        }
                    }             
                break; 
            case "password":
                    break; 
            case "checkbox":
                    $options = $this->form_inputs[$key]["options"];
                    $clean = '';
                    foreach ($options as $option_key => $option) {
                        if ( in_array($option["option_value"], $value)) {
                            $options[$option_key]["checked"] = true;
                            $clean .= $option["option"].', ';
                        }
                    }
                    $clean = rtrim( $clean, ', ');
                    $this->form_inputs[$key]["options"] = $options;
                    $this->form_inputs[$key]['clean'] = $clean;
                    break;                          
        }
        // esc_attr() - Escaping for HTML attributes. Encodes the <, >, &, ” and ‘ (less than, greater than, ampersand, double quote and single quote) characters. Will never double encode entities.
        $this->form_inputs[$key]['passed'] = true;        
    }//@end check_input



     
}
// Initialize the plugin
// $form_builder_plugin = new WP_Swift_Form_Builder_Plugin();