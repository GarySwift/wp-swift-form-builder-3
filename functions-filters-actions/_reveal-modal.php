<?php
/**
 * Load in the html for the modal reveal
 *
 * Don't add this on every page - the shortcode function can do this
 *
 * Usage: add_action( 'wp_footer', 'wp_swift_form_builder_modal_reveal', 1);
 * 
 * @since    1.0.0
 */
function wp_swift_form_builder_modal_reveal() {
?>
<div id="form-builder-reveal" class="fb-modal">
  <?php /* Modal content */ ?>
  <div class="fb-modal-content large">
    <span class="fb-modal-close hide">&times;</span>
    <div id="form-builder-reveal-content"></div>
  </div>
</div>
<?php
}