<?php
/**
 * This is the summary for a DocBlock.
 *
 * This is the description for a DocBlock. This text may contain
 * multiple lines and even some _markdown_.
 *
 * Usage:
 * 
 * # Create the link like this:
 * 
 * $url = get_the_permalink( 11, false );
 * $edit_url = add_query_arg( 'edit-form', $post->ID, $url );
 *
 * # Retrieve the query var like this:
 * 
 * $edit_id = get_query_var('edit-form');
 * 
 * @author  		Gary Swift <gary@brightlight.ie>
 *
 * @since 			2018-07-04
 * 
 * @link 			https://codex.wordpress.org/Plugin_API/Filter_Reference/query_vars
 * @link 			https://codepen.io/the_ruther4d/post/custom-query-string-vars-in-wordpress
 */
function wp_swift_form_builder_custom_query_vars_filter($vars) {
  $vars[] .= 'edit-form';
  $vars[] .= 'add-form';
  $vars[] .= 'view-form';
  $vars[] .= 'switch';
  return $vars;
}
add_filter( 'query_vars', 'wp_swift_form_builder_custom_query_vars_filter' );