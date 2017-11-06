<?php
/*
 * Check if the default taxonomy exists and create it if not
 * This is run on plugin activation and on the save_post hook 'wp_swift_form_builder_save_post'
 */
function wp_swift_form_builder_taxonomy_check() {
    if (!taxonomy_exists( FORM_BUILDER_DEFAULT_TAXONOMY )) {
        cptui_register_my_taxes_wp_swift_form_category();
        if (!term_exists( FORM_BUILDER_DEFAULT_TERM, FORM_BUILDER_DEFAULT_TAXONOMY )) {
            wp_insert_term( FORM_BUILDER_DEFAULT_TERM, FORM_BUILDER_DEFAULT_TAXONOMY, array( 'slug' => FORM_BUILDER_DEFAULT_SLUG ) );
        }    
    }
}