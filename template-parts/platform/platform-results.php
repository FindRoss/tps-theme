<?php

// Get the parent page ID
$parent_page_id = wp_get_post_parent_id(get_the_ID());
// Get the taoxnomy page. 'streaming_platform'
$term = get_field('streaming_platform', $parent_page_id);

// Normalize to a real WP_Term object so get_field() can read the term-level 'icon' field.
$term_obj  = is_a($term, 'WP_Term') ? $term : get_term($term, 'streams');
$term_icon = ($term_obj && !is_wp_error($term_obj)) ? get_field('icon', $term_obj) : '';

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

<div class="tps-results">

  <div class="tps-results-controls">
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
      <span class="noselect" id="table-page-count">1 of <?php echo ceil($total_posts / 10); ?></span>
      <button class="btn tps-btn tps-btn__icon" id="table-page-next" aria-label="next page">
        <?php echo get_svg_icon('chevron-right'); ?>
      </button>
    </div>
  </div>

  <div id="table-results-wrapper" data-term="<?php echo esc_attr($term); ?>" data-icon="<?php echo esc_url($term_icon); ?>">
    <div id="loader-bar" class="loader-bar" style="display: none;"></div>
    <table class="tps-streams-table" id="episode-results" data-total-results="<?php echo $total_posts; ?>">
      <thead>
        <tr>
          <th class="tps-streams-table__col-thumbnail"></th>
          <th class="tps-streams-table__col-stream">Episode</th>
          <th class="tps-streams-table__col-type">Game Type</th>
          <th class="tps-streams-table__col-stakes">Stakes</th>
          <th class="tps-streams-table__col-date">Date</th>
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
              $permalink     = get_the_permalink();
            ?>
            <tr class="tps-streams-table__row">
              <td class="tps-streams-table__col-thumbnail">
                <?php if ($term_icon) : ?>
                  <a class="tps-streams-table__thumb" href="<?php echo esc_url($permalink); ?>" tabindex="-1" aria-hidden="true"><img src="<?php echo esc_url($term_icon); ?>" alt="" loading="lazy" /></a>
                <?php endif; ?>
              </td>
              <td class="tps-streams-table__col-stream">
                <a href="<?php echo esc_url($permalink); ?>"><?php echo esc_html($display_tile); ?></a>
                <div class="tps-streams-table__meta-mobile">
                  <?php echo esc_html($formattedDate); ?> · <?php echo esc_html($game_type); ?> · <?php echo esc_html($stakes_played); ?>
                </div>
              </td>
              <td class="tps-streams-table__col-type"><?php echo esc_html($game_type); ?></td>
              <td class="tps-streams-table__col-stakes"><?php echo esc_html($stakes_played); ?></td>
              <td class="tps-streams-table__col-date"><?php echo esc_html($formattedDate); ?></td>
            </tr>
          <?php
            endwhile;
            wp_reset_postdata();
          endif; ?>
      </tbody>
    </table>
  </div>

  <div class="tps-results-summary" id="table-showing-text">
    <?php if ($total_posts > 0) :
      $showing_end = min(10, $total_posts);
      echo esc_html("Showing 1–{$showing_end} of {$total_posts}");
    else :
      echo 'No results';
    endif; ?>
  </div>

</div><!-- .tps-results -->
