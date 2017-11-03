<?php






function wp_swift_init_test() {
    // $slug = 'contact';
    // $term = 'Contact Form';
    $taxonomy = 'wp_swift_form_category';
    // if (!term_exists( $term, $taxonomy )) {
    //     wp_insert_term( $term, $taxonomy, array( 'slug' => $slug ) );
    //     $terms = get_terms([
    //         'taxonomy' => $taxonomy,
    //         'hide_empty' => false,
    //     ]);
    //     write_log($terms);
    // }
       // wp_insert_term( $term, $taxonomy, array( 'slug' => $slug ) );
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
}
// add_action("init", "wp_swift_init_test");
// function wp_swift_form_builder_new_to_publish($post_id) {
//      write_log('1 wp_swift_form_builder_new_to_publish');
//     // Return if this isn't a 'wp_swift_form' post
//     if ( "wp_swift_form" != get_post_type($post_id) ) return; 
//     wp_set_post_terms( $post_id, FORM_BUILDER_DEFAULT_TERM, FORM_BUILDER_DEFAULT_TAXONOMY );
//     write_log('2 wp_swift_form_builder_new_to_publish');
// }
// add_action( 'new_to_publish', 'wp_swift_form_builder_new_to_publish' );


// function wp_swift_form_builder_pending_to_publish($post_id) {
//      write_log('1 wp_swift_form_builder_pending_to_publish');
//     // Return if this isn't a 'wp_swift_form' post
//     if ( "wp_swift_form" != get_post_type($post_id) ) return; 
//     wp_set_post_terms( $post_id, FORM_BUILDER_DEFAULT_TERM, FORM_BUILDER_DEFAULT_TAXONOMY );
//     write_log('2 wp_swift_form_builder_pending_to_publish');
// }
// add_action( 'pending_to_publish', 'wp_swift_form_builder_pending_to_publish' );

// function wp_swift_form_builder_draft_to_publish($post_id) {
//      write_log('1 wp_swift_form_builder_draft_to_publish');
//     // Return if this isn't a 'wp_swift_form' post
//     if ( "wp_swift_form" != get_post_type($post_id) ) return; 
//     wp_set_post_terms( $post_id, FORM_BUILDER_DEFAULT_TERM, FORM_BUILDER_DEFAULT_TAXONOMY );
//     write_log('2 wp_swift_form_builder_draft_to_publish');
// }
// add_action( 'draft_to_publish', 'wp_swift_form_builder_draft_to_publish' );