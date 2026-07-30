<?php 

function get_player_event_results($player_id, $event_post_type) {
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

  if (empty($rows)) return array();

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
  return $apps;
}