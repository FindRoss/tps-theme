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

/**
 * Display labels for known platform child-page slugs, used for the
 * breadcrumb/tab bar tab labels. Falls back to the child page's own title
 * for any slug not listed here.
 */
function tps_get_platform_tab_labels() {
  return array(
    'players'   => 'Players',
    'streamers' => 'Players', // alias, matches tps_get_platform_route_map()
    'news'      => 'News',
    'results'   => 'Results',
    'series'    => 'Series',
  );
}

/**
 * Builds the data needed to render the shared platform breadcrumb + tab bar
 * (template-parts/platform/platform-bar.php), given a pillar page and the
 * page currently being viewed. Used by both templates/platform.php (the
 * pillar page and its Players/News/Results/etc children) and
 * single-cash_game.php (an individual stream, which isn't itself a child
 * of the pillar page in the post_parent sense, but still shows this nav).
 */
function tps_build_platform_bar_data($pillar_page_id, $current_page_id) {
  $current_page_url = get_permalink($current_page_id);
  $pillar_page_url   = get_the_permalink($pillar_page_id);
  $pillar_page_title = get_the_title($pillar_page_id);
  $pillar_page_image = get_the_post_thumbnail_url($pillar_page_id, 'medium');

  $children = get_pages(array(
    'child_of'    => $pillar_page_id,
    'sort_column' => 'menu_order',
    'sort_order'  => 'ASC',
  ));

  $tab_labels = tps_get_platform_tab_labels();

  $tabs = array(
    array(
      'label'     => 'Overview',
      'url'       => $pillar_page_url,
      'is_active' => ($current_page_url == $pillar_page_url),
    ),
  );

  $results_url = '';
  foreach ($children as $child) {
    $slug      = get_post_field('post_name', $child->ID);
    $permalink = get_permalink($child->ID);
    $label     = isset($tab_labels[$slug]) ? $tab_labels[$slug] : get_the_title($child->ID);

    $tabs[] = array(
      'label'     => $label,
      'url'       => $permalink,
      'is_active' => ($current_page_url == $permalink),
    );

    if ($slug === 'results') $results_url = $permalink;
  }

  return array(
    'pillar_page_url'    => $pillar_page_url,
    'pillar_page_title'  => $pillar_page_title,
    'pillar_page_image'  => $pillar_page_image,
    'tabs'                => $tabs,
    'results_url'         => $results_url,
    'streamed_games_url'  => site_url('/streamed-games/'),
  );
}
