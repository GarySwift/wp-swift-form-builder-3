<?php
/**
 * Display a custom taxonomy dropdown in admin
 * @author Mike Hemberger
 * @link http://thestizmedia.com/custom-post-type-filter-admin-custom-taxonomy/
 */
// add_action('restrict_manage_posts', 'wp_swift_filter_post_type_by_taxonomy_dealer_cat');
// function wp_swift_filter_post_type_by_taxonomy_dealer_cat() {
// 	global $typenow;
// 	$post_type = 'dealer'; // change to your post type
// 	$taxonomy  = 'dealer_cat'; // change to your taxonomy
// 	if ($typenow == $post_type) {
// 		$selected      = isset($_GET[$taxonomy]) ? $_GET[$taxonomy] : '';
// 		$info_taxonomy = get_taxonomy($taxonomy);
// 		wp_dropdown_categories(array(
// 			'show_option_all' => __("Show All {$info_taxonomy->label}"),
// 			'taxonomy'        => $taxonomy,
// 			'name'            => $taxonomy,
// 			'orderby'         => 'name',
// 			'selected'        => $selected,
// 			'show_count'      => true,
// 			'hide_empty'      => true,
// 		));
// 	};
// }
/**
 * Filter posts by taxonomy in admin
 * @author  Mike Hemberger
 * @link http://thestizmedia.com/custom-post-type-filter-admin-custom-taxonomy/
 */
// add_filter('parse_query', 'wp_swift_convert_id_to_term_in_query_dealer_cat');
// function wp_swift_convert_id_to_term_in_query_dealer_cat($query) {
// 	global $pagenow;
// 	$post_type = 'dealer'; // change to your post type
// 	$taxonomy  = 'dealer_cat'; // change to your taxonomy
// 	$q_vars    = &$query->query_vars;
// 	if ( $pagenow == 'edit.php' && isset($q_vars['post_type']) && $q_vars['post_type'] == $post_type && isset($q_vars[$taxonomy]) && is_numeric($q_vars[$taxonomy]) && $q_vars[$taxonomy] != 0 ) {
// 		$term = get_term_by('id', $q_vars[$taxonomy], $taxonomy);
// 		$q_vars[$taxonomy] = $term->slug;
// 	}
// }


/**
 * Display a custom taxonomy dropdown in admin
 * @author Mike Hemberger
 * @link http://thestizmedia.com/custom-post-type-filter-admin-custom-taxonomy/
 */
// add_action('restrict_manage_posts', 'wp_swift_filter_post_type_by_taxonomy_region');
// function wp_swift_filter_post_type_by_taxonomy_region() {
// 	global $typenow;
// 	$post_type = 'dealer'; // change to your post type
// 	$taxonomy  = 'region'; // change to your taxonomy
// 	if ($typenow == $post_type) {
// 		$selected      = isset($_GET[$taxonomy]) ? $_GET[$taxonomy] : '';
// 		$info_taxonomy = get_taxonomy($taxonomy);
// 		wp_dropdown_categories(array(
// 			'show_option_all' => __("Show All {$info_taxonomy->label}"),
// 			'taxonomy'        => $taxonomy,
// 			'name'            => $taxonomy,
// 			'orderby'         => 'name',
// 			'selected'        => $selected,
// 			'show_count'      => true,
// 			'hide_empty'      => true,
// 		));
// 	};
// }
/**
 * Filter posts by taxonomy in admin
 * @author  Mike Hemberger
 * @link http://thestizmedia.com/custom-post-type-filter-admin-custom-taxonomy/
 */
// add_filter('parse_query', 'wp_swift_convert_id_to_term_in_query_region');
// function wp_swift_convert_id_to_term_in_query_region($query) {
// 	global $pagenow;
// 	$post_type = 'dealer'; // change to your post type
// 	$taxonomy  = 'region'; // change to your taxonomy
// 	$q_vars    = &$query->query_vars;
// 	if ( $pagenow == 'edit.php' && isset($q_vars['post_type']) && $q_vars['post_type'] == $post_type && isset($q_vars[$taxonomy]) && is_numeric($q_vars[$taxonomy]) && $q_vars[$taxonomy] != 0 ) {
// 		$term = get_term_by('id', $q_vars[$taxonomy], $taxonomy);
// 		$q_vars[$taxonomy] = $term->slug;
// 	}
// }