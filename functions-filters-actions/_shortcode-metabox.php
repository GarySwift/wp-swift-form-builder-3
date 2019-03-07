<?php
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
?>
    <h4>Shortcode</h4>

    <p>Copy the shortcode below and paste into the page editor.</p>

    <input id="shortcode-input" class="js-click-to-copy-input" type="text" value='[form id="<?php echo $post->ID ?>"]' readonly>

    <a href="#" data-copy-id="shortcode-input" class="js-click-to-copy-link tooltips">
        <img src="<?php echo FORM_BUILDER_PLUGIN_URL . 'admin/images/icon-copy.svg' ?>" alt="icon-copy" class="icon-copy">
        <span>Copy to Clipboard</span>
    </a>

    <h4>PHP Function</h4>

    <p>Use this function in your theme.</p>

    <pre class="js-click-to-copy-text">wp_swift_formbuilder_run(<?php echo $post->ID ?>);</pre>
<?php
}