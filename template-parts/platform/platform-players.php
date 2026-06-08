<?php 

$iconUp = '<svg xmlns="http://www.w3.org/2000/svg" height="16" width="10" viewBox="0 0 320 512"><path d="M182.6 137.4c-12.5-12.5-32.8-12.5-45.3 0l-128 128c-9.2 9.2-11.9 22.9-6.9 34.9s16.6 19.8 29.6 19.8H288c12.9 0 24.6-7.8 29.6-19.8s2.2-25.7-6.9-34.9l-128-128z"/></svg>';
$iconDown = '<svg xmlns="http://www.w3.org/2000/svg" height="16" width="10" viewBox="0 0 320 512"><path d="M137.4 374.6c12.5 12.5 32.8 12.5 45.3 0l128-128c9.2-9.2 11.9-22.9 6.9-34.9s-16.6-19.8-29.6-19.8L32 192c-12.9 0-24.6 7.8-29.6 19.8s-2.2 25.7 6.9 34.9l128 128z"/></svg>';


// Get the parent page ID
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
<div class="form-controls hcl-results-form-controls row">

    <!-- Input group -->
    <div class="col-6 col-lg-3">
      <div class="noselect input-group tps-input-group tps-table-number-input">
        <button class="btn tps-btn tps-btn-outline" id="table-page-prev" aria-label="previous page" disabled>
          <?php echo get_svg_icon('chevron-left'); ?>
        </button>
        <span class="noselect px-2 flex-grow-1" id="table-page-count">1 of <?php echo ceil($total_results / 10); ?></span>
        <button class="btn tps-btn tps-btn-outline" id="table-page-next" aria-label="next page">
          <?php echo get_svg_icon('chevron-right'); ?>
        </button>
      </div>
    </div>

    <!-- Input group -->
    <div class="col-6 col-lg-3">
      <div class="noselect input-group tps-input-group">
        <select class="tps-select noselect" name="rows-per-page" id="rows-per-page" aria-label="rows per page select">
            <option value="10" selected>Rows</option>
            <option value="10">10</option>
            <option value="20">20</option>
            <option value="40">40</option>
            <option value="60">60</option>
        </select>
      </div>
    </div>

    <!-- Input group -->
    <div class="col-12 col-lg-6 mt-2 mt-lg-0">
      <div class="input-group tps-input-group" id="hcl-search-player" style="visibility: inherit; display: block;">
        <input type="text" placeholder="Search players" id="player-search">
      </div>
    </div>

</div><!-- .form-controls -->

<div class="player-hcl-results mt-2" id="table-results-wrapper" data-term="<?php echo $term; ?>">
  <div id="loader-bar" class="loader-bar" style="display: none;"></div>
  <table class="table hcl-results-page-table table-results" id="player-results" data-total-results="<?php echo $total_results; ?>">
    <thead>
      <tr>
        <th>
          <div><span>Player</span></div>
        </th>
        <th>
          <div><span>Appearances</span></div>
        </th>
        <th>
          <div><span>Profit/Loss</span></div>
        </th>
      </tr>
    </thead>
    <tbody id="table-body">
      <?php if (!empty($results)) :
        foreach ($results as $row) : 
          // Fetch player post title
          $player_post = get_post($row->player_id);
          $player_name = $player_post ? $player_post->post_title : 'Unknown Player'; 
      ?>
        <tr>
          <td>
            <a href="<?php echo get_permalink($row->player_id); ?>" class="d-flex align-items-center hcl-title-link">
              <img src="<?php echo get_the_post_thumbnail_url($row->player_id); ?>" width="75" height="75" class="me-2">
              <?php echo esc_html($player_name); ?>
            </a>
          </td>
          <td><?php echo esc_html($row->appearances); ?></td>
          <td>
            <div class="fw-bold <?php echo $row->total_winnings >= 0 ? 'text-success' : 'text-danger'; ?>">
              <?php echo ($row->total_winnings < 0 ? '-' : ''); ?>$<?php echo number_format(abs($row->total_winnings), 0); ?>
            </div>
          </td>
        </tr>
      <?php endforeach; else : ?>
        <tr><td colspan="3">No results found.</td></tr>
      <?php endif; ?>
    </tbody>
  </table>
</div>
