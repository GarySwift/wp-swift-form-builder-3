<?php
function wp_swift_form_builder_get_form_input($form_data, $key, $section_index = 0) {
    if (isset( $form_data[$section_index]["inputs"][$key]["clean"] )) {
        return $form_data[$section_index]["inputs"][$key]["clean"];
    }
    return '';
} 