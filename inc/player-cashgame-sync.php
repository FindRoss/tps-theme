<?php

add_action('save_post', 'tps_sync_player_cashgame_results', 10, 2);
function tps_sync_player_cashgame_results($post_id, $post) {
  error_log('tps_sync_player_cashgame_results ran for post ' . $post_id);

  if ($post->post_type !== 'cash_game') {
    return;
  }

  global $wpdb;
  $table = $wpdb->prefix . 'player_cashgame_results';
  $cash_game_id = $post_id;

  $current_players = $wpdb->get_col(
    $wpdb->prepare("SELECT player_id FROM {$table} WHERE cash_game_id = %d", $cash_game_id)
  );

  $updated_player_ids = array();

  $streams_terms = wp_get_post_terms($post_id, 'streams');
  $streams_term_id = (!empty($streams_terms) && !is_wp_error($streams_terms)) ? $streams_terms[0]->term_id : 0;

  $game_type = get_field('game_type', $post_id);
  $stakes_played = get_field('stakes_played', $post_id);

  $date_raw = get_field('date', $post_id);
  $game_date = '0000-00-00';
  if ($date_raw) {
    $date_object = DateTime::createFromFormat('d/m/Y', $date_raw);
    if ($date_object) {
      $game_date = $date_object->format('Y-m-d');
    }
  }

  if (have_rows('results', $post_id)) {
    while (have_rows('results', $post_id)) {
      the_row();

      $player_id = get_sub_field('player_link');
      $player_name = get_field('name', $player_id);
      $player_image = !empty(get_the_post_thumbnail_url($player_id, 'thumbnail'))
        ? get_the_post_thumbnail_url($player_id, 'thumbnail')
        : 'https://toppokerstreamers.com/wp-content/uploads/2024/08/anonymous-person-profile__100x100-jpg.webp';
      $tournament_winnings = get_sub_field('tournament_winnings');

      $updated_player_ids[] = $player_id;

      $result = $wpdb->query(
        $wpdb->prepare(
          "INSERT INTO {$table} (player_id, player_name, player_image, cash_game_id, winnings, streams_term_id, game_type, stakes_played, game_date) 
           VALUES (%d, %s, %s, %d, %f, %d, %s, %s, %s)
           ON DUPLICATE KEY UPDATE 
           player_name = VALUES(player_name), player_image = VALUES(player_image), winnings = VALUES(winnings), game_type = VALUES(game_type), stakes_played = VALUES(stakes_played), game_date = VALUES(game_date), streams_term_id = VALUES(streams_term_id)",
          $player_id,
          $player_name,
          $player_image,
          $cash_game_id,
          (float) $tournament_winnings,
          $streams_term_id,
          $game_type,
          $stakes_played,
          $game_date
        )
      );

      if ($result === false) {
        error_log('tps_sync_player_cashgame_results insert error: ' . $wpdb->last_error);
      }
    }
  }

  $players_to_delete = array_diff($current_players, $updated_player_ids);
  if (!empty($players_to_delete)) {
    foreach ($players_to_delete as $player_id) {
      $wpdb->delete($table, array(
        'player_id'     => $player_id,
        'cash_game_id'  => $cash_game_id,
      ));
    }
  }
}

add_action('before_delete_post', 'tps_delete_player_cashgame_results', 10, 1);
function tps_delete_player_cashgame_results($post_id) {
  if (get_post_type($post_id) !== 'cash_game') {
    return;
  }

  global $wpdb;
  $table = $wpdb->prefix . 'player_cashgame_results';
  $wpdb->delete($table, array('cash_game_id' => $post_id));
}