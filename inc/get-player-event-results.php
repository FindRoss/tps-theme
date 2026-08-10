<?php 

function get_player_event_results($player_id, $event_post_type) {
  static $cache = array();
  $cache_key = $player_id . '_' . $event_post_type;

  if (array_key_exists($cache_key, $cache)) {
    return $cache[$cache_key];
  }

  global $wpdb;
  $table = $wpdb->prefix . 'player_event_results';

  $rows = $wpdb->get_results(
    $wpdb->prepare(
      "SELECT event_post_id, winnings, series_start_date, event_number
       FROM {$table}
       WHERE player_id = %d
         AND event_post_type = %s
       ORDER BY series_start_date DESC, event_number ASC",
      $player_id,
      $event_post_type
    ),
    ARRAY_A
  );

  if (empty($rows)) return $cache[$cache_key] = array();

  $apps = array();
  foreach ($rows as $row) {
    $event_post_id = (int) $row['event_post_id'];

    $date = '';
    if ($row['series_start_date'] !== null) {
      $date_object = date_create_from_format('Y-m-d', $row['series_start_date']);
      if ($date_object) {
        $date = $date_object->format('d/m/Y');
      }
    }

    $apps[] = array(
      'player_link'         => $player_id,
      'tournament_winnings' => (float) $row['winnings'],
      'id'                  => $event_post_id,
      'title'               => get_the_title($event_post_id),
      'permalink'           => get_permalink($event_post_id),
      'date'                => $date,
    );
  }
  return $cache[$cache_key] = $apps;
}

function get_player_event_types($player_id) {
  global $wpdb;
  $table = $wpdb->prefix . 'player_event_results';

  $post_types = $wpdb->get_col(
    $wpdb->prepare("SELECT DISTINCT event_post_type FROM {$table} WHERE player_id = %d", $player_id)
  );

  if (empty($post_types)) return array();

  $events = array();
  foreach ($post_types as $post_type) {
    $events[] = array(
      'heading'   => tps_get_event_heading_for_post_type($post_type),
      'post_type' => $post_type,
    );
  }

  usort($events, function($a, $b) {
    return strcasecmp($a['heading'], $b['heading']);
  });

  return $events;
}

function tps_get_event_heading_for_post_type($post_type) {
  $map = array(
    'triton_event'     => 'Triton Results',
    'pokergo_event'    => 'PokerGO Results',
    'wpt_event'        => 'WPT Results',
    'pokerstars_event' => 'PokerStars Results',
  );
  return isset($map[$post_type]) ? $map[$post_type] : ucfirst($post_type) . ' Results';
}