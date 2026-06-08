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

    <div class="cash-game-table-wrapper">
      <table class="table cash-game-table">
        <thead>
          <tr>
            <th></th>
            <th>Stream</th>
            <th>Type</th>
            <th>Stakes</th>
            <th>Date</th>
          </tr>
        </thead>
        <tbody>
    
          <?php while ($games->have_posts()) : $games->the_post();

            // Should only ever be one term.
            $terms = get_the_terms(get_the_ID(), 'streams');

            if ($terms && !is_wp_error($terms)) {

              // Get the term icon
              $term_icon = get_field('icon', $terms[0]);
              $term_name = $terms[0]->name;

              // $main_page      = get_field('main_page', $terms[0]);
              // $main_page_link = get_the_permalink($main_page[0]);
              // Dont need this:  $main_page_img = get_the_post_thumbnail_url($main_page[0], 'platform');

              $display_title = get_field('display_title');
              $game_type     = get_field('game_type');
              $stakes_played = get_field('stakes_played');
              $date          = get_field('date');
              $display_date  = "Jan 24th"; 

              // Not working
              $formatted_date = formatTableDate($date);
              
              // Start output buffering
              ob_start(); ?>

                <tr>   
                  <td><div><img src="<?php echo $term_icon; ?>" alt="<?php echo $terms[0]->name; ?>" width="50" height="50"></div></td>
                  <td><div><a href="<?php the_permalink(); ?>"><?php echo $term_name; ?>: <?php echo $display_title; ?></a></div></td>
                  <td><div><?php echo $game_type; ?></div></td>
                  <td><div><?php echo $stakes_played; ?></div></td>
                  <td><div><?php echo $formatted_date; ?></div></td>
                </tr>

              <?php
              // Get the buffered content and clean the buffer
              $content = ob_get_clean();

              // Output the cleaned content if needed
              echo $content;
            }

          endwhile;  ?>
        </tbody>
      </table>
    </div>
  <?php endif;
};
