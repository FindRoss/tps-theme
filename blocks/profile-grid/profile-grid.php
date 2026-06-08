<?php 
/**
 * Testimonial Block template.
 *
 * @param array $block The block settings and attributes.
 */
 // Support custom "anchor" values.

$anchor = '';
if ( ! empty( $block['anchor'] ) ) {
  $anchor = 'id="' . esc_attr( $block['anchor'] ) . '" ';
}

$class_name = '';
if ( ! empty( $block['className'] ) ) {
    $class_name .= ' ' . $block['className'];
}

$profiles = get_field('profiles');

$query = new WP_Query(array(
  'post_type'      => 'player',
  // 'posts_per_page' => 3,
  'post__in'       => $profiles
));

if ($query->have_posts()) : 
  echo '<div class="pg-row ' . $class_name . '"' . $anchor . '>';
  
  while ($query->have_posts()) : $query->the_post();
    echo '<div class="pg-col">';
      require locate_template('template-parts/card/card-gyg.php');
    echo '</div>';
  endwhile; 

  echo '</div>';
  wp_reset_postdata();
endif;  