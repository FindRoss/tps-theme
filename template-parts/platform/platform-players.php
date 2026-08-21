<?php

$parent_page_id = wp_get_post_parent_id(get_the_ID());
// Get the taxonomy page. 'streaming_platform'
$term = get_field('streaming_platform', $parent_page_id);

// Retrieve first 10 results from custom database table
global $wpdb;

$results = $wpdb->get_results($wpdb->prepare(
    "SELECT player_id,
            COUNT(*) as appearances,
            SUM(winnings) as total_winnings
     FROM {$wpdb->prefix}player_cashgame_results
     WHERE streams_term_id = %d
     GROUP BY player_id
     ORDER BY appearances DESC
     LIMIT 10",
    $term
));


$total_results = $wpdb->get_var($wpdb->prepare(
    "SELECT COUNT(DISTINCT player_id)
     FROM {$wpdb->prefix}player_cashgame_results
     WHERE streams_term_id = %d",
    $term
));

?>

<div class="tps-results">

  <div class="tps-results-controls">
    <div class="tps-results-controls__search">
      <input type="text" class="tps-players-search" placeholder="Search players" id="player-search">
    </div>

    <div class="tps-results-controls__right">
      <div class="tps-results-controls__sort">
        <span class="tps-results-controls__label">Sort</span>
        <select class="tps-select noselect" id="sort-by" aria-label="sort by">
          <option value="appearances:DESC" selected>Most appearances</option>
          <option value="total_winnings:DESC">Highest profit</option>
          <option value="total_winnings:ASC">Lowest profit</option>
          <option value="player_name:ASC">Name A&ndash;Z</option>
          <option value="player_name:DESC">Name Z&ndash;A</option>
        </select>
      </div>

      <div class="tps-results-controls__rows">
        <span class="tps-results-controls__label">Rows</span>
        <select class="tps-select noselect" name="rows-per-page" id="rows-per-page" aria-label="rows per page select">
          <option value="10" selected>10</option>
          <option value="25">25</option>
          <option value="50">50</option>
        </select>
      </div>

      <div class="tps-results-controls__pagination">
        <button class="btn tps-btn tps-btn__icon" id="table-page-prev" aria-label="previous page" disabled>
          <?php echo get_svg_icon('chevron-left'); ?>
        </button>
        <span class="noselect" id="table-page-count">1 of <?php echo ceil($total_results / 10); ?></span>
        <button class="btn tps-btn tps-btn__icon" id="table-page-next" aria-label="next page">
          <?php echo get_svg_icon('chevron-right'); ?>
        </button>
      </div>
    </div>
  </div>

  <div id="table-results-wrapper" data-term="<?php echo esc_attr($term); ?>">
    <div id="loader-bar" class="loader-bar" style="display: none;"></div>
    <table class="tps-players-table" id="player-results" data-total-results="<?php echo $total_results; ?>">
      <thead>
        <tr>
          <th class="tps-players-table__col-rank">#</th>
          <th class="tps-players-table__col-player">Player</th>
          <th class="tps-players-table__col-appearances">Appearances</th>
          <th class="tps-players-table__col-profit">Profit/Loss</th>
        </tr>
      </thead>
      <tbody id="table-body">
        <?php if (!empty($results)) :
          foreach ($results as $i => $row) :
            // Fetch player post title
            $player_post = get_post($row->player_id);
            $player_name = $player_post ? $player_post->post_title : 'Unknown Player';
            $winnings    = floatval($row->total_winnings);
            $text_color  = $winnings >= 0 ? 'text-success' : 'text-danger';
            $amount      = ($winnings < 0 ? '-' : '') . '$' . number_format(abs($winnings), 0);
        ?>
          <tr class="tps-players-table__row">
            <td class="tps-players-table__col-rank"><?php echo esc_html($i + 1); ?></td>
            <td class="tps-players-table__col-player">
              <?php $player_link = get_permalink($row->player_id); ?>
              <div class="tps-players-table__player-cell">
                <a class="tps-players-table__avatar" href="<?php echo esc_url($player_link); ?>" tabindex="-1" aria-hidden="true">
                  <img src="<?php echo esc_url(get_the_post_thumbnail_url($row->player_id)); ?>" alt="" loading="lazy" />
                </a>
                <div class="tps-players-table__player-info">
                  <a href="<?php echo esc_url($player_link); ?>"><?php echo esc_html($player_name); ?></a>
                  <div class="tps-players-table__meta-mobile">
                    <?php echo esc_html($row->appearances); ?> appearances · <span class="<?php echo esc_attr($text_color); ?>"><?php echo esc_html($amount); ?></span>
                  </div>
                </div>
              </div>
            </td>
            <td class="tps-players-table__col-appearances"><?php echo esc_html($row->appearances); ?></td>
            <td class="tps-players-table__col-profit"><span class="<?php echo esc_attr($text_color); ?>"><?php echo esc_html($amount); ?></span></td>
          </tr>
        <?php endforeach; else : ?>
          <tr><td colspan="4">No results found.</td></tr>
        <?php endif; ?>
      </tbody>
    </table>
  </div>

  <div class="tps-results-summary" id="table-showing-text">
    <?php if ($total_results > 0) :
      $showing_end = min(10, $total_results);
      echo esc_html("Showing 1–{$showing_end} of {$total_results}");
    else :
      echo 'No results';
    endif; ?>
  </div>

</div><!-- .tps-results -->
