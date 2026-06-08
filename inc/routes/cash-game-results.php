<?php 
add_action('rest_api_init', 'gameShowResults');

function gameShowResults() {
    register_rest_route('custom/v1', 'gameshows', array(
        'methods' => WP_REST_SERVER::READABLE,
        'callback' => 'gameShowResultsFunc'
    ));
}

function gameShowResultsFunc($data) {
    $rows = isset($data['rows']) ? intval(sanitize_text_field($data['rows'])) : 10;
    $page = isset($data['page']) ? intval(sanitize_text_field($data['page'])) : 1;
    $term = isset($data['term']) ? intval(sanitize_text_field($data['term'])) : 61;

    // Create a unique cache key based on page, rows, and term
    // $cache_key = 'gameshow_results_' . $page . '_' . $rows . '_' . $term;
    // $results = get_transient($cache_key);

    // // If cached data is not found, query the database
    // if (false === $results) {

    $results = array(); 
    $mainQuery = new WP_Query(array(
        'post_type'      => 'cash_game',
        'posts_per_page' => $rows,
        'paged'          => $page,
        'meta_key'       => 'date',
        'orderby'        => 'meta_value_num',
        'order'          => 'DESC',
        'tax_query' => array(
            array(
                'taxonomy' => 'streams',
                'terms'    => $term
            ),
        ),
    ));

    // Get the total number of gameshows
    $total_players = $mainQuery->found_posts;

    // Calculate total pages
    $total_pages = ceil($total_players / $rows);

    // Loop through the query results and build the response array
    while ($mainQuery->have_posts()) {
        $mainQuery->the_post();
        $results[] = array(
            'link'   => get_the_permalink(),
            'title'  => get_field('display_title'),
            'date'   => get_field('date'),
            'type'   => get_field('game_type'),
            'stakes' => get_field('stakes_played'),
        );
    }

        // Cache the results for 12 hours
        // set_transient($cache_key, $results, 12 * HOUR_IN_SECONDS);
    // }

    // Prepare the response structure
    if (!empty($results)) {
        return array(
            'total_players' => $total_players,
            'total_pages'   => $total_pages,
            'results'       => $results,
        );
    } else {
        return new WP_Error('no_players', 'No players found for this streams term', ['status' => 404]);
    }
}
