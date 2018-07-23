<?php
if (!function_exists("wp_swift_submit_request_form_callback")) {
    /*
     * The ajax call back
     */
    function wp_swift_submit_request_form_callback() {
        check_ajax_referer( 'form-builder-nonce', 'security' );
        $post = array();
        $form_set = false;
        $form_id = intval( $_POST['id'] );
        $post_id = intval( $_POST['post'] );
        // echo $form_id;
        // die();

    // write_log($_POST);
        if (isset($_POST['form'])) {
            $post = wp_swift_convert_json_to_post_array( $_POST['form'] );
        }
        if (isset($_POST['type']) && $_POST['type'] == "signup") {
            write_log("WP_Swift_Form_Builder_Signup_Form");
            $form_builder = new WP_Swift_Form_Builder_Signup_Form( $form_id, $post_id );
        }
        // elseif (isset($_POST['type']) && $_POST['type'] == "warranty") {
        //     // write_log("WP_Swift_Form_Builder_Warranty_Form");
        //     $form_builder = new WP_Swift_Form_Builder_Warranty_Form( $form_id, $post_id );
        // }    
        else {
            $form_builder = new WP_Swift_Form_Builder_Contact_Form( $form_id, $post_id );
        }
        
        // $html = $form_builder->process_form($post, true);

        // if ($form_builder->get_form_data()) {
        //    $form_set = true;
        // }
        // $response = array(
        //     "form_set" => $form_set,
        //     "error_count" => $form_builder->get_error_count(),
        //     "html" => $html,
        // );
        echo json_encode( $form_builder->get_response($post) );
        die();
    }    
}
add_action( 'wp_ajax_wp_swift_submit_request_form', 'wp_swift_submit_request_form_callback' );
add_action( 'wp_ajax_nopriv_wp_swift_submit_request_form', 'wp_swift_submit_request_form_callback' );

/*
 * Form data is inside a Json object in $_POST['form'].
 * We need to loop through json this to convert it into an array
 * that has the same structure as regular $_POST form data
 * so we can use the same validation function
 */
function wp_swift_convert_json_to_post_array($form) {
	$post = array();
	foreach ($form as $input) {
            
        $name = $input["name"];

        $input_is_an_array = false;

        // Check if this input should be in an array
        if (strpos($name, '[]') !== false) {
            // Strip out square brackets from array name
            $name = str_replace('[]', '', $name);
            $input_is_an_array = true;
        }
        
        if (isset($input["value"])) {
            $value = $input["value"];

            if (!$input_is_an_array) {
                // Regular input
                $post[$name] = $value;
            }
            else {
                // Array input
                if (!isset( $post[$name])) {
                    // Empty so create a new array
                    $post[$name] = array($value);
                }
                else {
                    // Push into existing array
                    $post[$name][] = $value;
                }
            }
        }
    }//@end foreach	
    return $post;
}



/*
 * The ajax call back
 */
function wp_swift_add_row_callback() {
    check_ajax_referer( 'form-builder-nonce', 'security' );
    // $post = array();
    // $form_set = false;
    $form_id = intval( $_POST['formId'] );
    $tabindex = intval( $_POST['tabindex'] );
    $count = intval( $_POST['count'] );
    $count++;
    //$form_id, $post_id, $hidden = array(), $type = 'request', $increment_id = false
    $form = new WP_Swift_Form_Builder_Parent( $form_id, null, array(), 'request', $tabindex );
    $form->increment_form_data( $count );
    // $form_data = $form->get_form_data( $sections = false );
    ob_start();
    ?>
        <div id="row-<?php echo $count ?>" class="">
        <?php $tabindex = $form->front_end_form_input_loop($tabindex); ?>
        </div>
    <?php    
    $html = ob_get_contents();
    ob_end_clean();
    
    $response = array();
    
    // $tabindex = $form->get_tab_index( $increment = false );
    
    $response["tabindex"] = $tabindex;
    $response["count"] = $count;
    $response["html"] = $html;//"<p>Test</p>";//
    echo json_encode( $response );
    die();
}    

add_action( 'wp_ajax_wp_swift_add_row', 'wp_swift_add_row_callback' );
add_action( 'wp_ajax_nopriv_wp_swift_add_row', 'wp_swift_add_row_callback' );