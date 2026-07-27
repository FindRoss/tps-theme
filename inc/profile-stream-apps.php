<?php 

function get_player_cashgame_results($player_id, $term_id) {
  global $wpdb; 
  $table = $wpdb->prefix . 'player_cashgame_results';

  $rows = $wpdb->get_results(
    $wpdb->prepare(
      "SELECT cash_game_id, winnings, game_date
      FROM {$table}
      WHERE player_id = %d
        AND streams_term_id = %d
        AND game_date <> '0000-00-00'
      ORDER BY game_date DESC, cash_game_id DESC", 
      $player_id,
      $term_id
    ),
    ARRAY_A
  );

  if (empty($rows)) return array(); 

  $apps = array(); 
  foreach ($rows as $row) {
    $cash_game_id = (int) $row['cash_game_id']; 
    $game_date_obj = date_create_from_format('Y-m-d', $row['game_date']); 
    if (!$game_date_obj) continue;

    $apps[] = array(
      'player_link'         => $player_id,
      'tournament_winnings' => (float) $row['winnings'],
      'id'                  => $cash_game_id,
      'title'               => get_the_title($cash_game_id),
      'permalink'           => get_permalink($cash_game_id),
      'date'                => $game_date_obj->format('d/m/Y'),
    );
  }

  return $apps; 
}

function profile_stream_apps($term_id = 61) {
  static $cache = array(); 
  $player_id = get_the_ID();
  $cache_key = $player_id . '_' . $term_id;

  if (array_key_exists($cache_key, $cache)) {
    return $cache[$cache_key];
  }

  return $cache[$cache_key] = get_player_cashgame_results($player_id, $term_id); 
}

// function profile_stream_apps($term_id = 61) {
//   $stream_apps = array(); 
//   $post_ID = get_the_ID();

//   $stream_args = array(
//     'post_type'      => 'cash_game',
//     'posts_per_page' => -1,
//     'meta_key'       => 'episode', 
//     'orderby'        => 'meta_value_num', 
//     'order'          => 'DESC', 
//     'tax_query'      => array(
//       array(
//         'taxonomy' => 'streams', 
//         'field'    => 'term_id', 
//         'terms'    => $term_id,  
//         'operator' => 'IN',      
//       ),
//     )
//   );

//   $stream_query = new WP_Query($stream_args);

//   if ($stream_query->have_posts()) : 
//     while ($stream_query->have_posts()) : $stream_query->the_post();       
//       $stream_results = get_field('results');

//       if (is_array($stream_results)) {
//         foreach ($stream_results as $r) {
//           if ($r['player_link'] == $post_ID) { 
//             $r['id']        = get_the_ID();
//             $r['title']     = get_the_title($r['id']);
//             $r['permalink'] = get_the_permalink($r['id']);
//             $r['date']      = get_field('date', $r['id']);
//             $stream_apps[] = $r;
//           }
//         }; 
//       };
//     endwhile; 
//   endif; 
//   wp_reset_postdata(); 

//   return $stream_apps;
// };

function get_player_stream_terms($player_id) {
  global $wpdb; 
  $table = $wpdb->prefix . 'player_cashgame_results';

  $term_ids = $wpdb->get_col(
    $wpdb->prepare("SELECT DISTINCT streams_term_id FROM {$table} WHERE player_id = %d", $player_id)
  );

  if (empty($term_ids)) return array();

  $streams = array(); 
  foreach($term_ids as $term_id) {
    $term = get_term((int) $term_id, 'streams'); 
    if (!$term || is_wp_error($term)) continue; 

    $streams[] = array(
      'heading' => $term->name . ' Results', 
      'id'      => (int) $term_id,
      'name'    => $term->name, 
    ); 
  }

  usort($streams, fn($a, $b) => strcasecmp($a['name'], $b['name'])); 
  
  return $streams; 
}