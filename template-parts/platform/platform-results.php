<?php 

$iconUp = '<svg xmlns="http://www.w3.org/2000/svg" height="16" width="10" viewBox="0 0 320 512"><path d="M182.6 137.4c-12.5-12.5-32.8-12.5-45.3 0l-128 128c-9.2 9.2-11.9 22.9-6.9 34.9s16.6 19.8 29.6 19.8H288c12.9 0 24.6-7.8 29.6-19.8s2.2-25.7-6.9-34.9l-128-128z"/></svg>';
$iconDown = '<svg xmlns="http://www.w3.org/2000/svg" height="16" width="10" viewBox="0 0 320 512"><path d="M137.4 374.6c12.5 12.5 32.8 12.5 45.3 0l128-128c9.2-9.2 11.9-22.9 6.9-34.9s-16.6-19.8-29.6-19.8L32 192c-12.9 0-24.6 7.8-29.6 19.8s-2.2 25.7 6.9 34.9l128 128z"/></svg>';

// Get the parent page ID
$parent_page_id = wp_get_post_parent_id(get_the_ID());
// Get the taoxnomy page. 'streaming_platform'
$term = get_field('streaming_platform', $parent_page_id);

// Custom query to retrieve data
$args = array(
  'post_type'      => 'cash_game', 
  'posts_per_page' => 10,
  'meta_key'       => 'date', 
  'orderby'        => 'meta_value_num',
  'order'          => 'DESC',
  'tax_query'      => array(
    array(
      'taxonomy' => 'streams',
      'terms' => $term
    ),
  )
);

$query = new WP_Query($args);

$total_posts = $query->found_posts;

?>
  <!-- input group -->
  <!-- <div class="input-group hcl-nav-input-group">
    <button class="btn btn-lg tps-btn tps-btn--big table-control-btn active" data-hcltable="episodes">Episodes</button>
    <button class="btn btn-lg tps-btn tps-btn--big table-control-btn" data-hcltable="players">Players</button>
  </div> -->

  <div class="form-controls hcl-results-form-controls row">

    <!-- Input group -->
    <div class="col-6 col-lg-3">
      <div class="noselect input-group tps-input-group tps-table-number-input">
        <button class="btn tps-btn tps-btn-outline" id="table-page-prev" aria-label="previous page" disabled>
          <?php echo get_svg_icon('chevron-left'); ?>
        </button>
        <span class="noselect flex-grow-1" id="table-page-count">1 of <?php echo ceil($total_posts / 10); ?></span>

        <button class="btn tps-btn tps-btn-outline" id="table-page-next" aria-label="next page">
          <?php echo get_svg_icon('chevron-right'); ?>
        </button>
      </div>
    </div>

    <!-- Input group -->
    <div class="col-6 col-lg-3">
      <div class="noselect input-group tps-input-group">
        <!-- <label class="noselect px-2" for="rows-per-page">Rows per page:</label> -->
        <select class="tps-select noselect" name="rows-per-page" id="rows-per-page" aria-label="rows per page select">
            <option value="10" selected>Rows</option>
            <option value="10">10</option>
            <option value="20">20</option>
            <option value="40">40</option>
            <option value="60">60</option>
            <!-- Add more options as needed -->
        </select>
      </div>
    </div>

    <!-- Input group -->
    <div class="col-12 col-lg-6 mt-2 mt-lg-0">
      <div class="input-group tps-input-group" id="hcl-search-player" style="visibility: hidden; display: none;">
        <input type="text" placeholder="Search for player">
      </div>
    </div>

  </div><!-- .form-controls --> 

  

  <div class="player-hcl-results mt-2" id="table-results-wrapper" data-term="<?php echo $term; ?>">
    <div id="loader-bar" class="loader-bar" style="display: none;"></div>
    <table class="table hcl-results-page-table table-results" id="episode-results" data-total-results="<?php echo $total_posts; ?>">
      <thead>
        <tr>
          <th data-tablecol="episode" data-coldir="DESC" class="th-control active">
            <div>
              <span>Episode</span> 
              <span class="table-col-icon" style="display: none;">
                <span class="table-col-icon-box"><?php echo $iconUp; ?></span>
                <span class="table-col-icon-box"><?php echo $iconDown; ?></span>
              </span>
            </div>
          </th>
          <th data-tablecol="date" data-coldir="DESC" class="th-control">
            <div>
              <span>Date</span>
              <span class="table-col-icon" style="display: none;">
                <span class="table-col-icon-box"><?php echo $iconUp; ?></span>
                <span class="table-col-icon-box"><?php echo $iconDown; ?></span>
              </span>
            </div>
          </th>
          <th data-tablecol="type" data-coldir="DESC" class="th-control">
            <div>
              <span>Game Type</span>
              <span class="table-col-icon" style="display: none;">
                <span class="table-col-icon-box"><?php echo $iconUp; ?></span>
                <span class="table-col-icon-box"><?php echo $iconDown; ?></span>
              </span>
            </div>
          </th>
          <th data-tablecol="stakes" data-coldir="DESC" class="th-control">
            <div>
              <span>Stakes</span>
              <span class="table-col-icon" style="display: none;">
                <span class="table-col-icon-box"><?php echo $iconUp; ?></span>
                <span class="table-col-icon-box"><?php echo $iconDown; ?></span>
              </span>
            </div>
          </th>
        </tr>
      </thead>
      <tbody id="table-body">
          <?php if ($query->have_posts()) :
              while ($query->have_posts()) : $query->the_post(); 
              $dateString    = get_field('date');
              $formattedDate = formatTableDate($dateString);
              $display_tile  = get_field('display_title');
              $game_type     = get_field('game_type');
              $stakes_played = get_field('stakes_played');
            ?>
            <tr>
              <td><a href="<?php the_permalink(); ?>"><?php echo $display_tile ?></a></td>
              <td><?php echo $formattedDate; ?></td>
              <td><?php echo $game_type; ?></td>
              <td><?php echo $stakes_played; ?></td>
            </tr>
          <?php
            endwhile;
            wp_reset_postdata();
          endif; ?>
      </tbody>
    </table>

      
  </div>

  