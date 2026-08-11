<?php

function tps_get_supported_event_post_types() {
  return array('triton_event', 'pokergo_event', 'pokerstars_event', 'wpt_event');
}

add_action('init', function() {
  if (!current_user_can('manage_options')) return;
  if (!isset($_GET['run_event_backfill'])) return; 

  foreach (tps_get_supported_event_post_types() as $post_type) {
    $count = tps_backfill_player_event_results($post_type);
    echo "{$post_type}: {$count} posts backfilled<br>"; 
  }

  exit;
});

function tps_get_series_taxonomy_for_post_type($post_type) {
  $map = array(
    'triton_event'     => 'triton_series',
    'pokergo_event'    => 'pokergo_series',
    'wpt_event'        => 'wpt_taxonomy',
    'pokerstars_event' => 'pokerstars_taxonomy'
  );
  return isset($map[$post_type]) ? $map[$post_type] : null;
}

add_action('save_post', 'tps_sync_player_event_results', 10, 2);
function tps_sync_player_event_results($post_id, $post) {
  $supported_post_types = tps_get_supported_event_post_types();

  if (!in_array($post->post_type, $supported_post_types)) {
    return;
  }

  global $wpdb;
  $table = $wpdb->prefix . 'player_event_results';
  $event_post_id = $post_id;
  $event_post_type = $post->post_type;

  $current_players = $wpdb->get_col(
    $wpdb->prepare("SELECT player_id FROM {$table} WHERE event_post_id = %d", $event_post_id)
  );
  $updated_player_ids = array();

  // Series term + its start_date
  $series_taxonomy = tps_get_series_taxonomy_for_post_type($event_post_type);
  $series_term_id = 0;
  $series_start_date = null;

  if ($series_taxonomy) {
    $series_terms = wp_get_post_terms($post_id, $series_taxonomy);
    if (!empty($series_terms) && !is_wp_error($series_terms)) {
      $series_term_id = $series_terms[0]->term_id;

      $start_date_raw = get_field('start_date', $series_terms[0]);
      if ($start_date_raw) {
        $date_object = DateTime::createFromFormat('d/m/Y', $start_date_raw);
        if ($date_object) {
          $series_start_date = $date_object->format('Y-m-d');
        }
      }
    }
  }

  $event_number_raw = get_field('event', $post_id);
  $event_number = ($event_number_raw !== '' && $event_number_raw !== false) ? (int) $event_number_raw : null;

  if (have_rows('results', $post_id)) {
    while (have_rows('results', $post_id)) {
      the_row();

      $player_id = get_sub_field('player');
      $winnings = get_sub_field('winnings');

      if (!$player_id) {
        continue;
      }

      $updated_player_ids[] = $player_id;

      $series_start_date_sql = ($series_start_date !== null) ? '%s' : 'NULL';
      $event_number_sql = ($event_number !== null) ? '%d' : 'NULL';

      $sql_template = "INSERT INTO {$table}
        (player_id, event_post_id, event_post_type, winnings, series_term_id, series_start_date, event_number)
        VALUES (%d, %d, %s, %f, %d, {$series_start_date_sql}, {$event_number_sql})
        ON DUPLICATE KEY UPDATE
          winnings = VALUES(winnings),
          event_post_type = VALUES(event_post_type),
          series_term_id = VALUES(series_term_id),
          series_start_date = VALUES(series_start_date),
          event_number = VALUES(event_number)";

      $args = array($player_id, $event_post_id, $event_post_type, (float) $winnings, $series_term_id);
      if ($series_start_date !== null) { $args[] = $series_start_date; }
      if ($event_number !== null) { $args[] = $event_number; }

      $result = $wpdb->query($wpdb->prepare($sql_template, $args));

      if ($result === false) {
        error_log('tps_sync_player_event_results insert error: ' . $wpdb->last_error);
      }
    }
  }

  $players_to_delete = array_diff($current_players, $updated_player_ids);
  if (!empty($players_to_delete)) {
    foreach ($players_to_delete as $player_id) {
      $wpdb->delete($table, array(
        'player_id'      => $player_id,
        'event_post_id'  => $event_post_id,
      ));
    }
  }
}

add_action('before_delete_post', 'tps_delete_player_event_results', 10, 1);
function tps_delete_player_event_results($post_id) {

  if (!in_array(get_post_type($post_id), tps_get_supported_event_post_types())) {
    return;
  } 

  global $wpdb;
  $table = $wpdb->prefix . 'player_event_results';
  $wpdb->delete($table, array('event_post_id' => $post_id));
}

function tps_backfill_player_event_results($post_type) {
  $posts = get_posts(array(
    'post_type'      => $post_type,
    'post_status'    => 'publish',
    'posts_per_page' => -1,
    'fields'         => 'ids',
  ));

  foreach ($posts as $post_id) {
    $post = get_post($post_id);
    tps_sync_player_event_results($post_id, $post);
  }

  return count($posts);
}