<?php

/**
 * Maps a platform child page's slug to the template part that renders it.
 * Shared across every pillar page routed through templates/platform.php
 * (via the platform_page_template filter in functions.php).
 */
function tps_get_platform_route_map() {
  return array(
    'news'      => 'template-parts/platform/platform-news.php',
    'players'   => 'template-parts/platform/platform-players.php',
    'streamers' => 'template-parts/platform/platform-players.php', // alias for 'players'
    'results'   => 'template-parts/platform/platform-results.php',
  );
}

/**
 * Pillar-specific route overrides layered on top of the shared map.
 */
function tps_get_platform_route_map_for_pillar($pillar_page_title) {
  $map = tps_get_platform_route_map();

  if ($pillar_page_title === 'Triton Poker') {
    $map['series'] = 'template-parts/platform/platform-triton-series.php';
  }

  return $map;
}

/**
 * Resolves which template part should render the current platform page.
 * Returns null when the caller should fall back to the generic the_content() wrapper.
 */
function tps_get_platform_template_part($current_page_id, $pillar_page_title) {
  $slug = strtolower(trim(get_post_field('post_name', $current_page_id)));
  $map  = tps_get_platform_route_map_for_pillar($pillar_page_title);

  if (isset($map[$slug])) {
    return $map[$slug];
  }

  if (!wp_get_post_parent_id($current_page_id)) {
    return 'template-parts/platform/platform-overview.php';
  }

  return null;
}
