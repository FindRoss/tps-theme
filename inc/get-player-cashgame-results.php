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
      'heading' => $term->name . ' Cash Game Results', 
      'id'      => (int) $term_id,
      'name'    => $term->name, 
    ); 
  }

  usort($streams, fn($a, $b) => strcasecmp($a['name'], $b['name'])); 
  
  return $streams; 
}