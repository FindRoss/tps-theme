<?php get_header(); ?>

<?php 
$paged = get_query_var( 'paged' ) ? get_query_var( 'paged' ) : 1;
$args = array(
  'post_type' => 'post',
  'paged'     => $paged,
); 

$query = new WP_Query( $args );
?>


<div class="page my-5">
  <div class="container">

    <h1><?php single_post_title(); ?></h1>

    <?php if ( $query->have_posts() ) :?>
      <div class="row">
      <?php while ( $query->have_posts() ) : $query->the_post(); ?>
        <div class="col-12 col-sm-6 col-md-4 mt-4">
          <?php require locate_template('template-parts/card/card-two.php'); ?>
        </div>
      <?php endwhile; ?>
      </div>
      
      <div class="custom-pagination py-3">
          <?php     
            echo paginate_links( array(
              'prev_text' => __( '<', 'textdomain' ),
              'next_text' => __( '>', 'textdomain' ),
              // 'total'     => $query->max_num_pages,
              'current'   => max( 1, get_query_var('paged') ),
              )
            );
          ?>
      </div><!-- .custom-pagination --> 

    <?php else : ?>
      <p>No posts found</p>
    <?php endif; ?>
    <?php wp_reset_postdata(); ?>
    
  </div><!-- .container -->
</div>


<?php get_footer(); ?>


