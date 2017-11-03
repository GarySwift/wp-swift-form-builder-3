<?php
        // $terms = get_terms([
        //     'taxonomy' => FORM_BUILDER_DEFAULT_TAXONOMY,
        //     'hide_empty' => false,
        // ]);
        // write_log(FORM_BUILDER_DEFAULT_TAXONOMY);    
        // write_log($terms);      
        
    // $slug = 'contact-form';
    // $term = 'Contact Form';
    // $taxonomy = 'wp_swift_form_category';

    // $terms = get_terms([
    //     'taxonomy' => $taxonomy,
    //     'hide_empty' => false,
    // ]);    

    // if (!term_exists( $term, $taxonomy )) {
    //     $wp_insert_term = wp_insert_term( $term, $taxonomy, array( 'slug' => $slug ) );
    //     write_log('wp_insert_term');   
    //     write_log($wp_insert_term);   
    // }
function wp_swift_delete_terms() {

    $taxonomy = 'wp_swift_form_category';
    $terms = get_terms([
        'taxonomy' => $taxonomy,
        'hide_empty' => false,
    ]);
    write_log('1'); 
    write_log($terms); 
    foreach ( $terms as $term ) {
           wp_delete_term( $term->term_id, $taxonomy );
    } 
    $terms = get_terms([
        'taxonomy' => $taxonomy,
        'hide_empty' => false,
    ]);
    write_log('2'); 
    write_log($terms); 


// if (!term_exists( FORM_BUILDER_DEFAULT_TERM, FORM_BUILDER_DEFAULT_TAXONOMY )) {
//         wp_insert_term( FORM_BUILDER_DEFAULT_TERM, FORM_BUILDER_DEFAULT_TAXONOMY, array( 'slug' => FORM_BUILDER_DEFAULT_SLUG ) );
//     } 
    write_log('FORM_BUILDER_DEFAULT_TERM = '.FORM_BUILDER_DEFAULT_TERM);
    write_log('FORM_BUILDER_DEFAULT_SLUG = '.FORM_BUILDER_DEFAULT_SLUG);
    write_log('FORM_BUILDER_DEFAULT_TAXONOMY = '.FORM_BUILDER_DEFAULT_TAXONOMY);    
    // wp_swift_form_builder_taxonomy_check();
}
// add_action("init", "wp_swift_delete_terms");