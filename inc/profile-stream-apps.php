<?php 

function profile_stream_apps($term_id = 61) {
  $hcl_apps = array(); 
  $post_ID = get_the_ID();

  $hcl_args = array(
    'post_type'      => 'cash_game',
    'posts_per_page' => -1,
    'meta_key'       => 'episode', // The ACF field name
    'orderby'        => 'meta_value_num', // Specify that the meta value should be treated as a number
    'order'          => 'DESC', // or 'DESC' depending on your sorting preference
    'tax_query'      => array(
      array(
        'taxonomy' => 'streams', // Replace with your custom taxonomy name
        'field'    => 'term_id', // Specify that you're using the term's ID
        'terms'    => $term_id,  // Replace with your specific term ID
        'operator' => 'IN',      // Other options: 'NOT IN', 'AND'
      ),
    )
  );

  $hcl_query = new WP_Query($hcl_args);

  if ($hcl_query->have_posts()) : 
    while ($hcl_query->have_posts()) : $hcl_query->the_post();       
      $hcl_results = get_field('results');

      if (is_array($hcl_results)) {
        foreach ($hcl_results as $r) {
          if ($r['player_link'] == $post_ID) { 
            $r['id']        = get_the_ID();
            $r['title']     = get_the_title($r['id']);
            $r['permalink'] = get_the_permalink($r['id']);
            $r['date']      = get_field('date', $r['id']);
            $hcl_apps[] = $r;
          }
        }; 
      };
    endwhile; 
  endif; 
  wp_reset_postdata(); 

  return $hcl_apps;
};