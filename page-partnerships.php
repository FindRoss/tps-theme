<?php 


get_header(); 

// Hustler - 10982


$args = array(
  'post_type'      => 'page',
  'post_parent'    => 10980,
  'orderby'        => 'menu_order', 
  'posts_per_page' => -1
);

$query = new WP_Query($args); ?>

<section class="pt-4">
  <div class="container">  
    <div class="row">
      <div class="col-12 col-lg-7">
        <h1><?php the_title(); ?></h1>
        <?php the_content(); ?>
      </div>
    </div>
  </div>
</section>

<section class="py-4">
  <div class="container">

    <div class="row">
      <?php 
        if ($query->have_posts()) {
              while ($query->have_posts()) {
                  $query->the_post(); ?>
                  
                  <div class="col-12 col-lg-4 mt-3 mt-lg-4">
                    <?php require locate_template('template-parts/card/card-two.php'); ?>
                  </div>
                  
              <?php }

              // Restore original post data
              wp_reset_postdata();
          } 
  ?>
      

    </div>
  </div>
</section>

<?php get_footer(); ?>