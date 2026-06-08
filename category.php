<?php get_header(); ?>

<?php 
$paged     = get_query_var( 'paged' ) ? get_query_var( 'paged' ) : 1;
$category  = get_category( get_query_var( 'cat' ) );
$cat_id    = $category->cat_ID;
$cat_title = $category->name;
$query     = new WP_Query(array('cat' => $cat_id, 'paged' => $paged,));
?>

<section class="mt-4">
  <div class="container">  
    <h1><?php echo $cat_title; ?></h1>
    <?php $category_description = category_description(); ?>
    <div style="max-width: 42rem;"><?php if (!empty($category_description)) echo $category_description; ?></div>
  </div>
</section>

<div class="container">

  <div class="row">
    <?php while ($query->have_posts()) : $query->the_post(); ?>

      <div class="col-12 col-sm-6 col-lg-4 mt-3">
        <?php require locate_template('template-parts/card/card-two.php'); ?>
      </div>

    <?php endwhile; ?>
  </div>

    <div class="custom-pagination py-3">
    <?php     
    echo paginate_links( array(
      'prev_text' => __( '<', 'textdomain' ),
      'next_text' => __( '>', 'textdomain' ),
      'total' => $query->max_num_pages,
      'current' => max( 1, get_query_var('paged') ),
      )
    );
    ?>
    </div><!-- .custom-pagination --> 
    

</div>

<?php get_footer(); ?>