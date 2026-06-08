<?php 
/**
 * Testimonial Block template.
 *
 * @param array $block The block settings and attributes.
 */

$taxToShow = get_field('tax_to_show');
if (get_field('number_of_posts')) {
  $numberOfPosts = get_field('number_of_posts');
} else {
  $numberOfPosts = 3;
}

$anchor = '';
if ( ! empty( $block['anchor'] ) ) {
  $anchor = 'id="' . esc_attr( $block['anchor'] ) . '" ';
}

$class_name = '';
if ( ! empty( $block['className'] ) ) {
    $class_name .= ' ' . $block['className'];
}


$query = new WP_Query(array(
  'post_type'      => 'post',
  'posts_per_page' => $numberOfPosts,
  'tax_query'      => array(
    array(
        'taxonomy' => 'streams', 
        'terms'    => $taxToShow,
    ),
  ),
));

if ($query->have_posts()) : 
  echo '<div class="' . $class_name . '"' . $anchor . '>';
  
  while ($query->have_posts()) : $query->the_post();
    echo '<div class="">'; ?>

       <div class="ny-card mt-3 pb-3">
        <a class="tps-card-link" href="<?php the_permalink(); ?>">
          <div class="ny-card__layout">
            <div class="ny-card__media">
              <img class="w-100 h-auto rounded tps-card-image" src="<?php the_post_thumbnail_url('small'); ?>"  alt="">
            </div>
            <div class="ny-card__content">
              <h3 class="ny-card__title tps-card__title"><?php the_title(); ?></h3>
            </div>
          </div>
        </a>
      </div>

    <?php 
    echo '</div>';
  endwhile; 

  echo '</div>';
  wp_reset_postdata();
endif;  