<?php
// add_action( 'foundationpress_layout_end', array($this, 'foundationpress_layout_end_content')  );
add_action( 'foundationpress_layout_end', 'foundationpress_layout_end_content');
/**
 * Load in the html for the footer bar
 *
 * @since    1.0.0
 */
function foundationpress_layout_end_content() {
?>
<div class="reveal" id="form-builder-reveal" data-reveal>
  <div id="form-builder-reveal-content"></div>
  <button class="close-button" data-close aria-label="Close modal" type="button">
    <span aria-hidden="true">&times;</span>
  </button>
</div>
<?php
}	