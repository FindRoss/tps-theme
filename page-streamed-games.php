<?php 

get_header(); 

$streamed_games = array(3922, 10776, 4748, 18821, 13089, 10956, 9659, 10755, 3154, 10783);

$args = array(
  'post_type'      => 'page',
  'post__in'       => $streamed_games,
  'orderby'        => 'post__in', // Order by the order of IDs in $streamed_games
  'posts_per_page' => count($streamed_games), // Include all pages in the result
);

$query = new WP_Query($args); ?>

<section class="pt-4">
  <div class="container">  
    <h1><?php the_title(); ?></h1>
    <?php the_content(); ?>
  </div>
</section>

<section class="pb-5">
  <div class="container">

    <div class="row">
      <?php 
        if ($query->have_posts()) :
              while ($query->have_posts()) : $query->the_post(); ?>
                  
                  <div class="col-12 col-lg-4 mt-3 mt-lg-4">
                    <?php require locate_template('template-parts/card/card-two.php'); ?>
                  </div>
                  
              <?php
        endwhile;
        wp_reset_postdata();
      endif; ?>
    </div>
  </div>
</section>

<?php get_footer(); ?>