<?php
/**
 * Get the date format
 */
function get_form_builder_encryption_secret($options = array()) {
	if (!$options) {
		$options = get_option( 'wp_swift_form_builder_settings' );
	}
	if (!empty($options['wp_swift_form_builder_encryption_secret'])) {
		return $options['wp_swift_form_builder_encryption_secret'];
	}
	else {
		return 'form-builder-encryption-secret';
	}
}