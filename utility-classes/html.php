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
class WP_Swift_Form_Builder_Html {
    private $tab_index = 100;

    public function front_end_form($helper, $html_response = null, $msg = null ) {
        ?>
        <div class="<?php echo $helper->get_form_class(); ?>"><!-- @start form-wrapper -->

            <?php if ($html_response): ?>
                <?php echo $html_response; ?>
            <?php endif ?>

            <?php if ($msg): ?>
                <?php echo $msg; ?>
            <?php endif ?>
            
            <!-- @start form -->
            <form method="post"<?php echo $helper->get_action(); ?> name="<?php echo $helper->get_form_name(); ?>" id="<?php echo $helper->get_form_css_id(); ?>" data-id="<?php echo $helper->get_form_post_id(); ?>" data-post-id="<?php echo $helper->get_post_id(); ?>" data-type="<?php echo $helper->get_form_type() ?>"<?php $helper->get_form_data_types() ?> class="<?php echo $helper->get_form_class() . ' ' . $helper->get_form_name(); ?>" novalidate<?php echo $helper->get_enctype(); ?>>

                <?php if ( isset($this->hidden) && count($this->hidden)):
                    foreach ($this->hidden as $key => $hidden): ?>
                        <input type="hidden" data-type="hidden" id="<?php echo $key ?>" name="<?php echo $key ?>" value="<?php echo $hidden ?>">
                    <?php endforeach;
                endif;
                
                $this->front_end_form_input_loop($helper);

                do_action( 'wp_swift_formbuilder_before_submit_button_hook' );
                $this->gdpr_html($helper); ?>
                <div id="form-submission-wrapper"><?php 
                    $this->recaptcha_html($helper); ?>
                    <div id="form-submission"><?php 
                        $this->mail_receipt_html($helper);
                        $this->button_html($helper);
                    ?></div>
                </div><?php
                $this->gdpr_disclaimer($helper);
                ?>
            </form><!-- @end form -->
        </div><!-- @end form-wrapper -->
        <?php 
        if ( $helper->get_show_edit_link() === true ) {
            edit_post_link( __( '(Edit Form)', 'wp-swift-form-builder' ), '<div class="edit-link">', '</div>', $helper->get_form_post_id() );
        }
    }// front_end_form()

    public function front_end_form_input_loop( $helper, $tab_index = false, $increment = false ) {
        if ( $tab_index ) {
            $this->tab_index = $tab_index;
        }

        foreach ($helper->get_form_data() as $key => $section) {

            $this->open_section_html( $section, $key );            

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
                            $input_html = $this->build_form_input($helper, $id, $input);
                            echo $this->wrap_input($helper, $id, $input, $input_html);
                            break;
                        case "textarea":
                            $input_html = $this->build_form_textarea($helper, $id, $input);
                            echo $this->wrap_input($helper, $id, $input, $input_html);
                            break; 
                        case "radio":
                            $input_html = $this->build_form_radio($helper, $id, $input);
                            echo $this->wrap_input($helper, $id, $input, $input_html);
                            break; 
                        case "checkbox":
                            $input_html = $this->build_form_checkbox($helper, $id, $input);
                            echo $this->wrap_input($helper, $id, $input, $input_html);
                            break; 
                        case "checkbox_single":
                            $input_html = $this->build_form_checkbox_single($helper, $id, $input);
                            echo $this->wrap_input($helper, $id, $input, $input_html);
                            break;               
                        case "multi_select":
                        case "select":
                            $input_html = $this->build_form_select($helper, $id, $input);
                            echo $this->wrap_input($helper, $id, $input, $input_html);
                            break; 
                        case "repeat_section":
                            echo $this->build_form_repeat_section($helper, $id, $input);
                            break;
                        case "file":
                            $input_html = $this->build_form_file_upload($helper, $id, $input);
                            echo $this->wrap_input($helper, $id, $input, $input_html);
                            break;                                                                                                                
                    }  
                }
                     
            }

            $this->close_section_html( $key );

        }
        return $this->tab_index;
    }

    /*
     * Build form message
     */
    public function submit_form_failure($helper, $ajax) {
        ob_start();

        ?>

        <!-- @start #form-error-message -->
        <div id="form-error-message" class="form-message error<?php echo $ajax ? ' ajax':''; ?>">

            <h3 class="heading">Errors Found</h3>

            <div class="error-content">

                <p>We're sorry, there has been an error with the form input. Please rectify the <?php 
                    echo $helper->get_error_count() === 1 ? ' error' : $helper->get_error_count().' errors'; ?> below and resubmit.</p>
                <?php if ($helper->get_list_form_errors_in_warning_panel()) : ?>

                <ul>
                    <?php foreach ($helper->get_form_error_messages() as $message) : ?>

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

    private function clear_input($helper) {
        if(!$helper->get_form_pristine()) {
            if($helper->get_clear_after_submission() && $helper->get_error_count() === 0) {
                // No errors found so clear the values
                $input['value']=''; 
                return true;
            }
        }     
        return false;   
    }
    /******************************************************
     * @start Form Inputs
     ******************************************************/
    private function build_form_input($helper, $id, $input, $section='') {
        // $has_error='';
        if ($this->clear_input($helper)) {
            $input['value'] = '';
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
        $min = '';
        $max = '';
        $validation = '';
        $disabled = '';

        if ( isset($input["value"]) && $input["value"] !== '') {
            $value = ' value="'.$input['value'].'"';
        }
        $placeholder = '';
        if ( isset($input["placeholder"]) && $input["placeholder"] !== '') {
            $placeholder = ' placeholder="'.$input['placeholder'].'"';
        }
        $section = '';
        if ( isset($input["section"]) && $input["section"] !== '') {
            $section = ' data-section="'.$input['section'].'"';
        }
        $required = '';
        if (isset($input['required']) && $input['required'] !== '') {
              $required = ' required';
        }
        if (isset($input["validation"])) {
            // $validation = $input["validation"];
            $min = $input["validation"]["min"];
            $max = $input["validation"]["max"];
            $validation = $input["validation"]["validation"];
            // $max = $input["validation"]["max"];            
            if ($min) {
                $min = ' min="'.$min.'"';
            }
            if ($max) {
                $max = ' max="'.$max.'"';
            }
            if ($validation !== 'auto') {
                 $validation = ' data-validation="'.$validation.'"';          
            }            

        }
        if ($input["disabled"]) {
            $disabled = ' disabled';
        }
        $input_html = '<input'.$type.$data_type.$class.$id.$name.$tabindex.$value.$placeholder.$section.$required.$min.$max.$validation.$disabled.'>';
        return $input_html;       
    } 

    private function build_form_file_upload($helper, $id, $input, $section='') {
        $input_html = $this->build_form_input($helper, $id, $input);
        $input_html = '<div class="dummy-input">'.$input_html.'</div>';
        return $input_html;       
    } 

    private function build_form_select($helper, $id, $data) {
        $readonly = '';
        $multiple = '';
        $css_class = '';
        $disabled = '';
        $name_append_array = '';
        if ($data["disabled"]) {
            $disabled = ' disabled';
        }        
        if ($this->clear_input($helper)) {
            $data['selected_option'] = ''; 
        }
        if (isset( $data['readonly'] ) && $data['readonly']) {
            $readonly = " disabled";
        }
        $allow_null = true;
        if (isset( $data['allow_null'] )) {
            $allow_null = $data['allow_null'];
        }
        if ( $data['data_type'] == 'multi_select') {
            $multiple = ' multiple';
            $css_class = ' js-select2-multiple';
            $name_append_array = '[]';
        }

        ob_start();
        ?>

        <select class="<?php echo $this->get_form_input_class($data, $css_class); ?>" id="<?php echo $id; ?>" name="<?php echo $id; echo $name_append_array; ?>" data-type="select" tabindex="<?php echo $this->tab_index++; ?>" <?php echo $data['required']; echo $multiple; echo $readonly; echo $disabled ?>>

            <?php if($allow_null && !$multiple ): ?>
                <option value="" class="placeholder">Please select an option...</option>
            <?php endif; ?>

            <?php if (isset($data['option_group']) && $data['option_group']):
                foreach ($data['options'] as $group_key => $optgroup):?>
                    <optgroup label="<?php echo $group_key; ?>">
                    <?php
                    foreach ($optgroup as $option):
                        
                        if($option['option_value'] === $data['selected_option']) { 
                            $selected=' selected'; 
                        } else { 
                            $selected=''; 
                        }?>
                        <option value="<?php echo $option['option_value']; ?>"<?php echo $selected; ?>><?php echo $option['option']; ?></option>
                    <?php 
                    endforeach;?>
                    </optgroup>
                <?php endforeach;
            else:
                foreach ($data['options'] as $option):
                    
                    if($option['option_value'] === $data['selected_option']) { 
                        $selected=' selected'; 
                    } else { 
                        $selected=''; 
                    }?>
                    <option value="<?php echo  $option['option_value']; ?>"<?php echo $selected; ?>><?php echo $option['option']; ?></option>
                <?php 
                endforeach;
                //Note: select closing tag (below) is indented to format correctly in browser                 
            endif; ?>

        </select>

        <?php
        $input_html = ob_get_contents();
        ob_end_clean();
        return $input_html;
    }

    private function build_form_textarea($helper, $id, $input) {
        $has_error='';
        if ($this->clear_input($helper)) {
            $input['value'] = '';
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
        $class = ' class="'.$this->get_form_input_class($input).'"';
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
        if ( isset($input["section"]) && $input["section"] !== '') {
            $section = ' data-section="'.$input['section'].'"';
        }
        $required = '';
        if (isset($input['required']) && $input['required'] !== '') {
              $required = ' required';
        } 

        $rows = '';
        if ( isset($input["rows"]) && $input["rows"] !== '') {
            $rows = ' rows="'.$input['rows'].'"';
        }
        $maxlength = '';
        if ( isset($input["maxlength"]) && $input["maxlength"] !== '') {
            $maxlength = ' maxlength="'.$input['maxlength'].'"';
        }
        $input_html = '<textarea'.$data_type.$class.$id.$name.$tabindex.$placeholder.$section.$required.$rows.$maxlength.'>'.$value.'</textarea>';
        return $input_html;    

    }

    private function build_form_radio($helper, $id, $input) {
        if ($this->clear_input($helper)) {
           $input['selected_option'] = ''; 
        }  
        $count=0;  
        $checked='';
        $data_id = ' data-id="'.$id.'"';
        ob_start();
        foreach ($input['options'] as $option): $count++;
            if ( $input['selected_option'] !== '' && ($input['selected_option'] === $option['option_value']) ){
                $checked=' checked';
            }
            $css_class = '';
            // if ($option['option_value'] === 'other' && strrpos( $input['css_class'], "js-other-value-event") ) {
            //     // echo '<pre>'; var_dump( strrpos( $input['css_class'], "js-other-value-event") ); echo '</pre>';;echo "<hr>";
            //     $css_class = ' class="radio js-other-value-event"';
            // }
            if ($input['css_class']) $css_class = ' class="'.trim($input['css_class']).'"';
            ?>
                    
                    <label for="<?php echo $id.'-'.$count ?>" class="lbl-radio">
                        <?php /* name="<?php echo $id ?>-radio" */ ?>
                        <input id="<?php echo $id.'-'.$count ?>" name="<?php echo $id ?>" type="radio" data-type="radio" tabindex="<?php echo $this->tab_index++; ?>" value="<?php echo $option['option_value'] ?>"<?php echo $data_id; echo $css_class; echo $checked; ?>><?php echo $option['option'] ?>

                    </label>
            <?php 
        endforeach;
        $html = ob_get_contents();
        ob_end_clean();
        return $html;            
    }

    private function build_form_checkbox($helper, $id, $data) {
        if ($this->clear_input($helper)) {
            // No errors found so clear the checked values
            foreach ($data['options'] as $key => $option) {
                $data['options'][$key]['checked'] = false;
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
                if ($id == "form-signup-options") {
                    $hidden_name = $data['name'].'-hidden'.$name_append;
                }
                
            }
            else {
                $name = $id.''.$name_append;
                if ($id == "form-signup-options") {
                    $hidden_name = $id.'-hidden'.$name_append;
                }
                
            }

            ?>

                    <label for="<?php echo $id.'-'.$count ?>" class="lbl-checkbox">
                        <input id="<?php echo $id.'-'.$count ?>" name="<?php echo $name ?>" type="checkbox" data-type="checkbox" tabindex="<?php echo $this->tab_index++; ?>" value="<?php echo $option['option_value'] ?>"<?php echo $checked; ?>><?php echo $option['option'] ?>
                    
                    </label>
            <?php
      
          /*  if ($id == "form-signup-options")
                <input name="<?php echo $hidden_name ?>" type="text" data-type="hidden" value="<?php echo $option['option_value'] ?>">*/
            
        endforeach;
        $input_html = ob_get_contents();
        ob_end_clean();
        return $input_html;  
    }

    private function build_form_checkbox_single($helper, $id, $input) {
        $checked='';
        if ( $input["option"]["checked"]){
            $checked=' checked';
        }  
        $required = '';
        if (isset($input['required']) && $input['required'] !== '') {
              $required = ' required';
        }           
        ob_start();
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

    private function build_form_repeat_section($id, $input) {      
        ob_start();
        $button = $input["buttons"];
        $button_id = $input["id"];
        $add_button_text = $input["buttons"]["add_button"]["button_text"];
        $remove_button_text = $input["buttons"]["remove_button"]["button_text"];
        $add_button_disabled = $input["buttons"]["add_button"]["disabled"];
        // if ($add_button_disabled) {
        //     $add_button_disabled = '';
        // }
        // else {
        //     $add_button_disabled = ' disabled';
        // }        
        $remove_button_disabled = $input["buttons"]["remove_button"]["disabled"];
        // if ($remove_button_disabled) {
        //     $remove_button_disabled = '';
        // }
        // else {
        //     $remove_button_disabled = ' disabled';
        // }
        // $remove_button_text = str_replace("Add", "Remove", $add_button_text);
        $group_id = $input["id"] . '-add-remove-group';
        $min = $input["min"];
        $max = $input["max"];
        $count = $input['count'];
        $form_id = $input["form_id"];
        $input_keys = json_encode($input['input_keys']);
        if ($count > $min) {
        }
        ?>

            <input type="hidden" 
                id="<?php echo $id; ?>" 
                name="<?php echo $id; ?>" 
                data-type="repeat-section" 
                value="<?php echo $count ?>"
                min="<?php echo $max ?>" 
                max="<?php echo $max ?>" 
                readonly>
                <div class="form-group right add-remove-group" id="<?php echo $group_id ?>">
                    <a href="#" 
                    class="button small success js-add-row" 
                    id="add-row-<?php echo $button_id ?>" 
                    data-remove-button="#remove-row-<?php echo $button_id ?>" 
                    data-count-input-id="#<?php echo $id ?>" 
                    data-action="add_row" 
                    data-group="<?php echo $button_id ?>" 
                    data-form-id="<?php echo $form_id ?>" 
                    data-keys='<?php echo $input_keys ?>' 
                    tabindex="<?php echo $this->tab_index++; ?>"<?php echo $add_button_disabled ?>><?php echo $add_button_text ?></a>
                    <a href="#" 
                    class="button small warning js-remove-row" 
                    id="remove-row-<?php echo $button_id ?>" 
                    data-add-button="#add-row-<?php echo $button_id ?>" 
                    tabindex="<?php echo $this->tab_index++; ?>"<?php echo $remove_button_disabled; ?>><?php echo $remove_button_text ?></a>
                </div>
        <?php
        $html = ob_get_contents();
        ob_end_clean();
        
        return $html;
    }    
    /******************************************************
     * @end Form Inputs
     ******************************************************/

    /*
     * Use the same function to wrap all inputs
     */
    public function wrap_input($helper, $id, $input, $input_html, $section='') {

        if ($input["grouping"] && $input["grouping"] == "start"): ?>

            <!-- Start grouping -->
            <div class="<?php echo $this->get_css_form_grid_grouping(); ?>">         
        <?php endif ?>

            <!-- @start form element -->
            <div class="<?php echo $this->get_css_form_group($helper, $input) ?>" id="<?php echo $id; ?>-form-group">

                <!-- @start input anchor -->
                <a href="<?php echo $id; ?>-anchor"></a>
                <!-- @end input anchor -->

                <!-- @start input label -->
                <div class="form-label">

                    <div class="form-builder-feedback"><span class="feedback form-icon form-builder-x"></span><span class="feedback form-icon form-builder-check"></span><span class="form-icon form-builder-circle-o-notch"></span></div>
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
                        //if ($input['required']): 
                        echo PHP_EOL; ?>
                    <div class="form-builder-error">
                        <small class="error" id="<?php echo $id; ?>-report"><?php echo $help; ?></small>
                    </div>
                    <?php 
                        //endif;
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
        return "form-grid-grouping";
    }

    /*
     * to do
     */
    public function get_css_form_group($helper, $input) {
        // todo
        $has_error='';
        if(!$helper->get_form_pristine() && $input['passed']==false && $input["type"] !== "checkbox") {
            // This input has an error detected so add an error class to the surrounding div
            $has_error = ' has-error';
        }     
        return "form-group ".$input["css_class"].$has_error;
    }
    
    /*
     * Get the CSS class for the input
     */
    private function get_form_input_class($input=false, $css_class='') {
        if (isset($input["css_class_input"])) {
            return "js-form-builder-control " . $input["css_class_input"] . $css_class;
        }
        return "js-form-builder-control" . $css_class;
    }

    public function get_tab_index( $increment=true ) {
        if ($increment) {
            $this->tab_index++;
        }
        return $this->tab_index;
    }
  
    public function recaptcha_theme($helper) {
        if (isset( $helper->recaptcha["theme"] )) {
            echo ' data-theme="'.$helper->recaptcha["theme"].'"';
        } 
    }

    public function recaptcha_size($helper) {
        if (isset( $helper->recaptcha["size"] )) {
            echo ' data-size="'.$helper->recaptcha["size"].'"';
        } 
    }

    public function recaptcha_group_class($helper) {
        if (isset( $helper->recaptcha["hide_on_load"] ) && $helper->recaptcha["hide_on_load"] ) {
            echo ' hide init-hidden';
        } 
    }         

    public function recaptcha_html($helper) {
        $html = '';
        if ( $helper->recaptcha_site() ):
            ob_start();
            ?>

                <div class="form-group <?php $this->recaptcha_group_class($helper); ?>" id="captcha-wrapper">

                    <!-- @start input -->
                    <div class="form-input">
                        <div class="g-recaptcha" data-sitekey="<?php echo $helper->recaptcha_site() ?>" <?php $this->recaptcha_theme($helper); $this->recaptcha_size($helper); ?> data-tabindex="<?php echo $this->get_tab_index(); ?>" data-size="normal"></div>

                    </div>
                    <!-- @end input -->

                </div>
            <?php
            $html = ob_get_contents();
            ob_end_clean();
        endif;//@nd if ($helper->recaptcha_site())
        echo  $html;
    }  

    public function gdpr_html($helper) {
        $gdpr_settings = $helper->get_gdpr_settings();
        if ( isset($gdpr_settings["opt_in"]) ): ?>

        <!-- @start .sign-up -->
        <div class="form-group sign-up">
            <div class="form-label"></div>
            <div class="form-input">
                <div class="checkbox">
                    <label id="sign-up-details"><?php echo $gdpr_settings["main_message"] ?></label>

                        <?php foreach ($gdpr_settings["opt_in"] as $key => $opt_in): ?>

                            <?php if ( is_array($opt_in["options"]) && ( in_array("email", $opt_in["options"]) || in_array("sms", $opt_in["options"]) ) ): ?>

                                <label for=""><?php echo $opt_in["message"] ?></label>

                                <?php if ( in_array("email", $opt_in["options"]) ): ?>
                                    <input type="checkbox" value="email" tabindex=<?php echo $this->get_tab_index(); ?> name="sign-up-<?php echo $key; ?>[]" id="sign-up-email" class="sign-up"><label for="sign-up-email">Email</label>
                                <?php endif ?>                            
                                <?php if ( in_array("sms", $opt_in["options"]) ): ?>
                                    <input type="checkbox" value="sms" tabindex=<?php echo $this->get_tab_index(); ?> name="sign-up-<?php echo $key; ?>[]" id="sign-up-sms" class="sign-up"><label for="sign-up-sms">SMS</label> 
                                <?php endif ?>  

                            <?php endif ?>
                            
                        <?php endforeach ?>
                     
                </div>
            </div>                  
        </div> 
        <!-- @end .sign-up -->           

        <?php endif;//@nd if ($this->gdpr_settings)
    }


    public function gdpr_disclaimer($helper) {
        $gdpr_settings = $helper->get_gdpr_settings();
        if ( isset($gdpr_settings["disclaimer"]) ): ?>

            <div class="form-group sign-up">
                <div class="policies">
                    <?php echo $gdpr_settings["disclaimer"] ?>
                </div>
            </div>

        <?php endif;//@nd if ($this->gdpr_settings)
    } 


    public function mail_receipt_html($helper) {
        if ($helper->get_user_confirmation_email() === 'ask'): ?>

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

        <?php endif;         
    }    

    //todo - does submit_button_name come from helper
    public function button_html($helper) {
        ?>
        <!-- @start .button -->
        <div class="form-group button-group">

            <!-- @start input -->
            <div class="form-input">

                <button type="submit" name="<?php echo $helper->get_submit_button_name(); ?>" id="<?php echo $helper->get_submit_button_id(); ?>" class="button" tabindex="<?php echo $this->get_tab_index(); ?>"><?php echo $helper->get_submit_button_text(); ?></button>


            </div>
            <!-- @end input -->            
        </div>
        <!-- @end .button -->
        <?php           
    }  
    public function open_section_html( $content, $key = 0 ) {
        ?>

        <!-- @start section #form-section-<?php echo $key ?> -->
        <div id="form-section-<?php echo $key ?>">
        <?php
        if (isset($content["section_header"]) || isset($content["section_content"])): ?>

            <!-- @start .section-content -->
            <div class="form-group section-content">

                <!-- @start input -->
                <div class="form-input">
                    <?php if (isset($content["section_header"])): ?>

                    <h4><?php echo $content["section_header"]; ?></h4>

                    <?php 
                    endif;
                    if (isset($content["section_content"])): ?>

                    <div class="entry-content"><?php echo $content["section_content"]; ?></div>

                    <?php endif ?>

                </div>
                <!-- @end input -->            
            </div>
            <!-- @end .section-content --> 

        <?php endif;         
    }  

    public function close_section_html( $key ) {
        ?>

        </div>
        <!-- @end section #form-section-<?php echo $key ?> -->

        <?php   
    }                   
}