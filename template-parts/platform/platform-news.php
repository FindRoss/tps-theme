<?php 
  // Get the parent page ID
  $parent_page_id = wp_get_post_parent_id(get_the_ID());
  // Get the custom field that stores the taxonomy for this platform
  $parent_page_platform = get_field('streaming_platform', $parent_page_id);

  $paged = get_query_var( 'paged' ) ? get_query_var( 'paged' ) : 1;

  $query = new WP_Query(array (
    'post_type' => array( 'post' ),
    'paged'     => $paged,
    'tax_query' => array(
      array(
        'taxonomy' => 'streams',
        'terms' => $parent_page_platform
      )
    )
  ));

  ?>
        
        <?php if ($query->have_posts()) : ?>
          <div class="row">
            <?php while ($query->have_posts()) : $query->the_post(); ?>
              <div class="col-12 col-sm-6 col-md-4 mt-3">
                <?php require locate_template('template-parts/card/card-two.php'); ?>
              </div>
            <?php endwhile; ?>
          </div>

          <div class="custom-pagination py-3">
          <?php     
          echo paginate_links( array(
            'prev_text' => __( '<', 'textdomain' ),
            'next_text' => __( '>', 'textdomain' ),
            'total'     => $query->max_num_pages,
            'current'   => max( 1, get_query_var('paged') ),
            )
          );
          ?>
          </div><!-- .custom-pagination --> 

        <?php else : ?>
          No posts found
        <?php endif; ?>

   
