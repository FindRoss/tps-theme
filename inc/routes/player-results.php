<?php 

add_action('rest_api_init', function () {
    register_rest_route('custom/v1', 'player-results', array(
        'methods' => 'GET',
        'callback' => 'get_player_results_by_streams_term',
        'permission_callback' => '__return_true',
    ));
});

function get_player_results_by_streams_term($data) {
    global $wpdb;

    // Get streams_term_id, page number, and number of items per page from the query string
    $term = isset($_GET['term']) ? intval($_GET['term']) : 0;
    $page = isset($_GET['page']) ? intval($_GET['page']) : 1;
    $rows = isset($_GET['rows']) ? intval($_GET['rows']) : 10; // default 10 per page
    $search = isset($_GET['search']) ? sanitize_text_field($_GET['search']) : '';
    $sort = isset($_GET['sort']) ? sanitize_text_field($_GET['sort']) : 'appearances';
    $dir = isset($_GET['dir']) && strtoupper($_GET['dir']) === 'ASC' ? 'ASC' : 'DESC'; 

    // Calculate offset for pagination
    $offset = ($page - 1) * $rows;

    // Query to get total players count for this streams term
    $total_players_query = "SELECT COUNT(DISTINCT player_id) 
                            FROM {$wpdb->prefix}player_cashgame_results 
                            WHERE streams_term_id = %d";
    if (!empty($search)) {
        $total_players_query .= " AND player_name LIKE %s";
        $total_players = $wpdb->get_var($wpdb->prepare($total_players_query, $term, '%' . $wpdb->esc_like($search) . '%'));
    } else {
        $total_players = $wpdb->get_var($wpdb->prepare($total_players_query, $term));
    }

    // Query to get appearances, winnings, and image for each player, ordered by appearances
    $results_query = "SELECT player_id, 
                             player_name,
                             COUNT(*) as appearances, 
                             SUM(winnings) as total_winnings,
                             (SELECT player_image FROM {$wpdb->prefix}player_cashgame_results AS img WHERE img.player_id = p.player_id LIMIT 1) as player_image
                      FROM {$wpdb->prefix}player_cashgame_results AS p
                      WHERE streams_term_id = %d";
    if (!empty($search)) {
        $results_query .= " AND player_name LIKE %s";
    }
    $results_query .= " GROUP BY player_id, player_name
                        ORDER BY " . sanitize_sql_orderby($sort . ' ' . $dir) . "
                        LIMIT %d OFFSET %d";

    if (!empty($search)) {
        $results = $wpdb->get_results($wpdb->prepare($results_query, $term, '%' . $wpdb->esc_like($search) . '%', $rows, $offset));
    } else {
        $results = $wpdb->get_results($wpdb->prepare($results_query, $term, $rows, $offset));
    }

    foreach ($results as $row) {
        $row->link = get_permalink($row->player_id);
    }

    // Calculate total pages
    $total_pages = ceil($total_players / $rows);

    if (!empty($results)) {
        return array(
            'total_players' => $total_players,
            'total_pages' => $total_pages,
            'results' => $results,
        );
    } else {
        return new WP_Error('no_players', 'No players found for this streams term', ['status' => 404]);
    }
}
