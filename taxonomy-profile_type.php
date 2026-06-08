<?php get_header(); ?>

<?php 
$taxonomy_name = 'profile_type';
$term = get_queried_object();
$term_id = $term->term_id;

// Nav link items
$taxonomy_terms = get_terms(array(
    'taxonomy'   => 'profile_type',
    'orderby'    => 'count',
    'order'      => 'DESC',
    'hide_empty' => true,
  )
);

foreach($taxonomy_terms as $term) {
  $nav_link_items[] = array(
    'title' => $term->name,
    'link'  => get_term_link($term->term_id, $taxonomy_name)
  );
};

// First, get IDs of players tagged in the 'players' taxonomy term
$pre_args = get_posts(array(
  'post_type' => 'player',
  'fields'    => 'ids',
  'nopaging'  => true,
  'tax_query' => array(
    array (
      'taxonomy' => $taxonomy_name,
      'field'    => 'term_id',
      'terms'    => $term_id,
    )
  )
));

// WP QUERY
$paged = get_query_var('paged') ? get_query_var('paged') : 1;
$args = array( 
  'post_type' => 'player',
  'paged'     => $paged,
  'orderby'   => 'post_views',
  'order'     => 'desc',
  'post__in'  => $pre_args,
);
$query = new WP_Query($args); 
?>


<section>
  <div class="container">  
    
    <!-- Nav Pills  -->
    <div class="nav-pill-container">
      <div class="nav-pill-wrapper">
        <?php foreach($nav_link_items as $item) { 
          $current_page_url = strtok(home_url($_SERVER['REQUEST_URI']), '?');
 
          $is_active = false; 
          if ($current_page_url === $item['link']) $is_active = true;
          
          ?>
          <!-- Item -->
          <div class="nav-pill-item">
            <a href="<?php echo $item['link']; ?>" class="btn btn-dark nav-pill rounded-pill <?php echo $is_active ? ' is-active' : ''; ?>"><?php echo $item['title']; ?></a>
          </div>
        <?php } ?>
      </div><!-- .nav-pill-wrapper -->
    </div><!-- .nav-pill-container --> 

    <div class="d-flex justify-content-center">
      <h1 class="display-5 my-0 py-3 py-md-5"><?php echo single_term_title(); ?></h1>
    </div>
  </div>
</section>

<div class="page mb-4">
  <div class="container">

    <?php if ( $query->have_posts() ) :?>
      <div class="row">  
      
        <?php while ( $query->have_posts() ) : $query->the_post(); ?>
          <div class="col-6 col-md-4 mt-3">
            <?php require locate_template('template-parts/card/card-gyg.php'); ?>
          </div>
        <?php endwhile; ?>
      </div><!-- .row --> 
      <?php wp_reset_postdata(); ?>
      
      <?php require locate_template('pagination.php') ?>
    <?php endif; ?>
      
  


    <!-- THE CONTENT -->
    <?php $content = get_the_content(); ?>
    <?php if (!empty($content)) : ?>
      <div class="row mt-4">
        <div class="col-12">
          <!-- echo $content; -->
        </div>
      </div>
    <?php endif; ?>
    
  </div><!-- .container -->
</div>


<?php get_footer(); ?>
