<?php
/**
 * Generated by the WordPress Meta Box generator
 * at http://jeremyhixon.com/tool/wordpress-meta-box-generator/
 */

function form_shortcode_get_meta( $value ) {
    global $post;

    $field = get_post_meta( $post->ID, $value, true );
    if ( ! empty( $field ) ) {
        return is_array( $field ) ? stripslashes_deep( $field ) : stripslashes( wp_kses_decode_entities( $field ) );
    } else {
        return false;
    }
}

function form_shortcode_add_meta_box() {
    add_meta_box(
        'form_shortcode-form-shortcode',
        __( 'Form Usage', 'form_shortcode' ),
        'form_shortcode_html',
        'wp_swift_form',
        'side',
        'high'
    );
}
add_action( 'add_meta_boxes', 'form_shortcode_add_meta_box' );

function form_shortcode_html( $post) {
	// $id = get_the_id();
?>
<h4>Shortcode</h4>
<p>Copy the shortcode below and paste into the page editor.</p>
<input id="shortcode-input" type="text" value='[form id="<?php echo $post->ID ?>"]' readonly>
<a href="#" id="shortcode-input-copy" data-tooltip="Copy to Clipboard" class="tooltips"><img src="<?php echo plugin_dir_url( __FILE__ ) . 'admin/images/icon-copy.svg' ?>" alt="icon-copy" class="icon-copy"><span>Copy to Clipboard</span></a>
<div id="shortcode-input-copy" class="hidden"><small>Shortcode Copied</small></div>
<h4>PHP Function</h4>
<p>Use this function in your theme.</p>
<pre>wp_swift_get_form(<?php echo $post->ID ?>);</pre>
<?php 
// onclick="this.focus();this.select()" onfocus="this.focus();this.select();"
}