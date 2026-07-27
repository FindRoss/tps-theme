<?php 

function profile_stream_apps($term_id = 61) {
  $stream_apps = array(); 
  $post_ID = get_the_ID();

  $stream_args = array(
    'post_type'      => 'cash_game',
    'posts_per_page' => -1,
    'meta_key'       => 'episode', 
    'orderby'        => 'meta_value_num', 
    'order'          => 'DESC', 
    'tax_query'      => array(
      array(
        'taxonomy' => 'streams', 
        'field'    => 'term_id', 
        'terms'    => $term_id,  
        'operator' => 'IN',      
      ),
    )
  );

  $stream_query = new WP_Query($stream_args);

  if ($stream_query->have_posts()) : 
    while ($stream_query->have_posts()) : $stream_query->the_post();       
      $stream_results = get_field('results');

      if (is_array($stream_results)) {
        foreach ($stream_results as $r) {
          if ($r['player_link'] == $post_ID) { 
            $r['id']        = get_the_ID();
            $r['title']     = get_the_title($r['id']);
            $r['permalink'] = get_the_permalink($r['id']);
            $r['date']      = get_field('date', $r['id']);
            $stream_apps[] = $r;
          }
        }; 
      };
    endwhile; 
  endif; 
  wp_reset_postdata(); 

  return $stream_apps;
};