<?php
/**
 * Get the date format
 */
function get_form_builder_date_format($options = null) {
    if (!$options) {
        $options = get_option( 'wp_swift_form_builder_settings' );
    }
	if (!empty($options['wp_swift_form_builder_date_format'])) {
		return $options['wp_swift_form_builder_date_format'];
	}
	else {
		return FORM_BUILDER_DATE_FORMAT;
	}
}