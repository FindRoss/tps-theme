<?php 

function my_acf_post_object_query( $args, $field, $post_id ) {
    if ( isset( $args['s'] ) ) {
        global $wpdb;

        // Get all player post IDs that match the search term in the title for both published and draft posts
        $post_ids = $wpdb->get_col( $wpdb->prepare(
            "SELECT ID FROM $wpdb->posts WHERE post_title LIKE %s AND post_type = %s AND (post_status = 'publish' OR post_status = 'draft')",
            '%' . $wpdb->esc_like( $args['s'] ) . '%',
            'player' // Custom post type is 'player'
        ));

        // If there are results, limit the search to those posts
        if ( !empty( $post_ids ) ) {
            $args['post__in'] = $post_ids;
        } else {
            // If no results, return no matches
            $args['post__in'] = array( 0 );
        }

        // Remove default search query
        unset( $args['s'] );
    }

    return $args;
};

add_filter( 'acf/fields/post_object/query', 'my_acf_post_object_query', 10, 3 );
