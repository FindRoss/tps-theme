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

// String - post type
$postType = get_field('stream_to_display');
// Number
$numberToDisplay = get_field('number_to_display');


$query = new WP_Query(array(
  'post_type'      => $postType,
  'posts_per_page' => $numberToDisplay ? $numberToDisplay : 3,
));

if ($query->have_posts()) : 
  echo '<div class="pb-3">';
  while ($query->have_posts()) : $query->the_post();
    echo '<div class="mb-2">';   
       
      require locate_template('template-parts/card/card-episode.php');  
  
    echo '</div>';
  endwhile; 
  echo '</div>';
  wp_reset_postdata();
endif;  
