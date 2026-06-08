<?php 

// triton_event : Triton Events
// hcl_stream : Hustler Casino Live Streams
// bally_stream : Bally Live Poker Streams
// poker_lodge_stream : Poker at the Lodge Streams

$anchor = '';
if ( ! empty( $block['anchor'] ) ) {
  $anchor = 'id="' . esc_attr( $block['anchor'] ) . '" ';
}

$class_name = '';
if ( ! empty( $block['className'] ) ) {
    $class_name .= ' ' . $block['className'];
}


// $triton_series = get_the_terms(get_the_ID(), 'triton_series');
$foobar = get_terms(array(
  'taxonomy' => 'triton_series',
  'hide_empty' => true,
  'meta_query' => array(
    array(
      'key'     => 'start_date', // ACF field key
      'type'    => 'DATETIME',
      'compare' => 'EXISTS',
    ),
  ),
  'orderby'    => 'meta_value',
  'order'      => 'DESC',
));


// This is the term I need. 
// The 'triton_series' term that is the most modern
// $foobar[0]

$query = new WP_Query(array(
  'post_type'      => 'triton_event',
  'posts_per_page' => 3,
  'orderby'        => 'meta_value_num',
  'order'          => 'ASC', 
  'meta_key'       => 'event'
 ));

// Then loop through this term and output the result - that is the most recent results

//  $query->set('orderby', 'meta_value_num');
//  $query->set('order', 'ASC');
//  $query->set('meta_key', 'event');


if (isset($query) AND $query->have_posts()) : 
  while ($query->have_posts()) : $query->the_post();
    echo '<div class="mb-2">';  ?>

      <div class="result-card mt-3">
        <a class="tps-card-link" href="<?php the_permalink(); ?>">
          <div class="result-card__layout">
            <span class="result-card__number">#<?php echo get_field('event', get_the_ID()); ?></span>
            <h3 class="h2 tps-card__title"><?php the_title(); ?></h3>
          </div>
        </a>
      </div>


    <?php echo '</div>';
  endwhile; 
  wp_reset_postdata();
endif;  
