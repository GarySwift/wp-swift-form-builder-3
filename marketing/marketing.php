<?php
/**
 * WordPress Marketing
 *
 * 'wp_swift_marketing_modal_reveal' puts the reveal holder in the footer
 *
 * * Markdown style lists function too
 * * Just try this out once
 *
 * The section after the description contains the tags; which provide
 * structured meta-data concerning the given element.
 *
 * @author          Gary Swift <gary@brightlight.ie>
 *
 * @since           2019 06 20
 */

require_once 'acf/_sign-up-form-selector.php';

define("FORM_BUILDER_MARKETING", true);
define("FORM_BUILDER_MARKETING_DEBUG", false);
define("FORM_BUILDER_MARKETING_MODAL", false);
define("FORM_BUILDER_MARKETING_MARKETING_REDIRECT", true);
/**
 * Add reveal modal which shows submission response
 */
if (FORM_BUILDER_MARKETING_MODAL) 
    add_action( 'wp_footer', 'wp_swift_marketing_modal_reveal', 1);
/**
 * Load in the html for the modal reveal
 *
 * Don't add this on every page - the shortcode function can do this
 *
 * Usage: add_action( 'wp_footer', 'wp_swift_form_builder_modal_reveal', 1);
 * 
 * @since    1.0.0
 */
function wp_swift_marketing_modal_reveal() {
?>
<div class="reveal medium" id="marketing-reveal" data-reveal>
  <div id="marketing-reveal-content"></div>
  <button class="close-button" data-close aria-label="Close modal" type="button">
    <span aria-hidden="true">&times;</span>
  </button>
</div>
<?php
}

/**
 * WordPress Marketing Ajax Feature
 */
if (FORM_BUILDER_MARKETING) {
    # wp_ajax action hooks
    add_action( 'wp_ajax_wp_swift_from_builder_marketing', 'wp_swift_from_builder_marketing_callback' );
    add_action( 'wp_ajax_nopriv_wp_swift_from_builder_marketing', 'wp_swift_from_builder_marketing_callback' );

    # enqueue_script
    add_action( 'wp_enqueue_scripts', 'wp_swift_from_builder_marketing_localize_script', 100 );
}

/**
 * The ajax callback function
 */
function wp_swift_from_builder_marketing_callback() {
    check_ajax_referer( "marketing-nonce", 'security' );
    $localhost_form_id = 23907;// 21693;//[form id="23907"]
    $dev_form_id = 21777;
    $form_id= is_localhost() ? $localhost_form_id  : $dev_form_id;
    // $form_id = $localhost_form_id;
    ob_start();
    wp_swift_formbuilder_run($form_id);
    $modal = ob_get_contents();
    ob_end_clean();
    $response = array(
        "msg" => "MarketingAjax server success",
        "modal" => $modal,
    );
    echo json_encode($response);
    die();
}

/**
 * Create the ajax nonce and url
 */
function wp_swift_from_builder_marketing_localize_script() {
    global $wpdb; 
    $page_slug = 'sign-up';
    $post_type = 'page';
    $page = $wpdb->get_var( $wpdb->prepare( "SELECT ID FROM $wpdb->posts WHERE post_name = %s AND post_type= %s AND post_status = 'publish'", $page_slug, $post_type ) );
    if ($page) {
        $js_version = 1.0;
        if ( function_exists( 'foundationpress_scripts' ) ) {
            $js_file = "/dist/assets/js/app.js";
            $file = get_stylesheet_directory_uri() . $js_file;
            $js_file_path = get_template_directory() . $js_file;
        }
        else {
            $js_file = "./script.js";
            $file = plugin_dir_url( __FILE__ ) . $js_file;
            $js_file_path = plugin_dir_path( __FILE__ ) . $js_file;
        }
        $js_version = filemtime( $js_file_path );
        $time = 60000;// 1 minutue in miliseconds
        // $time = 5000;// 5 seconds      
        $marketing_ajax = array(
            // URL to wp-admin/admin-ajax.php to process the request
            'ajaxurl' => admin_url( 'admin-ajax.php' ),
            // generate a nonce with a unique ID so that you can check it later when an AJAX request is sent
            'security' => wp_create_nonce( "marketing-nonce" ),
            // callback function
            'action' => 'wp_swift_from_builder_marketing',
            // debugging info
            // 'updated' => date ("H:i:s - F d Y", $js_version),
            // 'post_id' => get_the_id(),

            'modal' => FORM_BUILDER_MARKETING_MODAL,
            'showAlertBeforeRedirect' => false,
            'signupDeclined' => "form-builder-signup-declined",
            'time' => $time,// time before modal shows
            'debugClearSignupDeclined' => false,
            'autoHideModal' => 6000,

            'redirect' => FORM_BUILDER_MARKETING_MARKETING_REDIRECT,
            'signUpURL' => get_the_permalink( $page ),
            // debugging info
            'debug' => FORM_BUILDER_MARKETING_DEBUG,
            'debugClearCacheAuto' => false,
            'debugTimeoutInterval' => 30000,
            'debugClearUserData' => false,        
        );
        if ( function_exists( 'foundationpress_scripts' ) ) {
            // wp_deregister_script('foundation'); 
            // wp_register_script( 'foundation', get_stylesheet_directory_uri() . '/dist/assets/js/' . foundationpress_asset_path( 'app.js' ), array( 'jquery' ), $js_version, true );
            //  wp_enqueue_script('foundation');
            // write_log(get_stylesheet_directory_uri() . '/dist/assets/js/' . foundationpress_asset_path( 'app.js' ));
            // Enqueue Foundation scripts
            // wp_enqueue_script( 'foundation', get_stylesheet_directory_uri() . '/dist/assets/js/' . foundationpress_asset_path( 'app.js' ), array( 'jquery' ), $js_version, true );
            wp_localize_script( 'foundation', 'MarketingAjax', $marketing_ajax);
        }
        else {
            wp_enqueue_script( "marketing-ajax", $file, array(), $js_version, true );
            wp_localize_script( "marketing-ajax", "MarketingAjax", $marketing_ajax);
        }         
    }
}