<?php
/*************************************************************/
/*   ACF Friendly Block Titles                              */
/***********************************************************/
function form_builder_acf_layout_title($title, $field, $layout, $i) {
	if($value = get_sub_field('form_input_name')) {
		return $value . ' <sup>(' . $title . ')</sup>';
	}
	return $title;
}
add_filter('acf/fields/flexible_content/layout_title', 'form_builder_acf_layout_title', 10, 4);