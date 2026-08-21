<?php 

function streamedGamesList($term_id = null, $post_id = null) {

  $games_args = array(
    'post_type'      => 'cash_game',
    'posts_per_page' => 8, 
    'meta_key'       => 'date',
    'orderby'        => 'meta_value',
    'order'          => 'DESC',
    'meta_type'      => 'DATE'
  ); 

  if ($term_id !== null) {
    $games_args['tax_query'] = array(
      array(
        'taxonomy' => 'streams',
        'field'    => 'term_id',
        'terms'    => $term_id
      )
    );
  }

  if ($post_id !== null) {
    $games_args['post__not_in'] = array($post_id);
  } 
 
  $games = new WP_Query($games_args);
  

  if ($games->have_posts()) : ?>

    <table class="tps-streams-table">
      <thead>
        <tr>
          <th class="tps-streams-table__col-thumbnail"></th>
          <th class="tps-streams-table__col-stream">Stream</th>
          <th class="tps-streams-table__col-type">Type</th>
          <th class="tps-streams-table__col-stakes">Stakes</th>
          <th class="tps-streams-table__col-date">Date</th>
        </tr>
      </thead>
      <tbody>

        <?php while ($games->have_posts()) : $games->the_post();

          // Should only ever be one term.
          $terms = get_the_terms(get_the_ID(), 'streams');

          if ($terms && !is_wp_error($terms)) {

            $term_name = $terms[0]->name;
            $term_icon = get_field('icon', $terms[0]);

            $display_title = get_field('display_title');
            $game_type     = get_field('game_type');
            $stakes_played = get_field('stakes_played');
            $date          = get_field('date');

            $formatted_date = formatTableDate($date);
            $permalink      = get_the_permalink();
            ?>

            <tr class="tps-streams-table__row">
              <td class="tps-streams-table__col-thumbnail">
                <?php if ($term_icon) : ?>
                  <a class="tps-streams-table__thumb" href="<?php echo esc_url($permalink); ?>" tabindex="-1" aria-hidden="true"><img src="<?php echo esc_url($term_icon); ?>" alt="" loading="lazy" /></a>
                <?php endif; ?>
              </td>
              <td class="tps-streams-table__col-stream">
                <a href="<?php echo esc_url($permalink); ?>"><?php echo esc_html($term_name); ?>: <?php echo esc_html($display_title); ?></a>
                <div class="tps-streams-table__meta-mobile">
                  <?php echo esc_html($formatted_date); ?> · <?php echo esc_html($game_type); ?> · <?php echo esc_html($stakes_played); ?>
                </div>
              </td>
              <td class="tps-streams-table__col-type"><?php echo esc_html($game_type); ?></td>
              <td class="tps-streams-table__col-stakes"><?php echo esc_html($stakes_played); ?></td>
              <td class="tps-streams-table__col-date"><?php echo esc_html($formatted_date); ?></td>
            </tr>

            <?php
          }

        endwhile; ?>
      </tbody>
    </table>
  <?php endif;
};
