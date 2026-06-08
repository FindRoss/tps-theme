<?php get_header(); ?>

<?php 
$author_id = get_the_author_meta('ID'); 
// $paged = get_query_var( 'paged' ) ? get_query_var( 'paged' ) : 1;
// $args = array(
//   'post_type'      => array('post', 'player'),
//   'paged'          => $paged,
//   'author'         => $author_id,
//   'post_status'    => 'publish',
//   'posts_per_page' => 12, // Adjust as needed
// ); 





// $query = new WP_Query( $args );

// Get the author's description
$author_description = get_the_author_meta('description', $author_id);
?>

<div class="page my-5">
  <div class="container">

    <div class="row">
      <div class="col-12">
        <div class="author-intro p-3 shadow-sm rounded">
          <h1><?php echo get_the_author_meta('display_name'); ?></h1>
          <?php 
          // Check if the description exists and is not empty
          if (!empty($author_description)) {
              echo '<div class="author-description">' . $author_description . '</div>';
          }; ?>
        </div>
      </div>
    </div>

    <div class="row">
      <?php if(have_posts()) : ?>
        <?php while(have_posts()) : the_post(); ?>
          <div class="col-12 col-md-6 col-lg-4 mt-3">
            <?php require locate_template('template-parts/card/card-two.php'); ?>
          </div>
        <?php endwhile; ?>
      <?php else : ?>
          <p>No posts found</p>
      <?php endif; ?>
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

   
    <?php wp_reset_postdata(); ?>
    
  </div><!-- .container -->
</div>


<?php get_footer(); ?>


