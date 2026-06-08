<?php get_header(); ?>

<?php 

$category = get_the_category(); 

// Get the post author ID
$author_id = get_post_field('post_author', get_the_ID());
// Get the author display name
$author_name = get_the_author_meta('display_name', $author_id);
// Get the author archive link
$author_link = get_author_posts_url($author_id);
?>

<div class="post mb-5">
  <div class="container"> 

    <?php if ( have_posts() ) : while ( have_posts() ) : the_post(); ?>
    
    <div class="row mt-4">
      <div class="col-12 col-lg-8">

        <!-- Header -->
        <h1><?php the_title(); ?></h1>
        <span class="post-meta">
          <span>Written by <a href="<?php echo esc_url($author_link) ?>"><?php echo esc_html($author_name) ?></a></span>
          <span class="post-meta__separator">|</span>
          <span>Published <?php echo get_the_date(); ?></span>
        </span>
        <?php if (has_post_thumbnail()) { ?> 
          <img src="<?php echo get_the_post_thumbnail_url(null, 'large'); ?>" alt="<?php the_title(); ?>" class="tps-featured-img rounded mt-3 w-100 h-auto" width="900" height="600">
        <?php }; ?>
        
        <!-- Content -->
        <div class="mt-4">
          <?php the_content(); ?>
        </div>

        <!-- Categories -->
        <div class="mt-4">
          <div class="d-flex flex-wrap align-items-center">
            <?php foreach($category as $cat) : ?>
              <a class="btn btn-dark btn-sm text-light rounded-pill mt-1 fs-6 me-2" href="/category/<?php echo $cat->slug; ?>/"><?php echo $cat->name; ?></a>
            <?php endforeach; ?>
          </div>
        </div>
  
   

    <?php endwhile; ?>
    <?php endif; ?>
    <?php wp_reset_postdata(); ?>

    <?php 

    $players = get_field('player'); 

    if ($players) : 

    $player_query = new WP_Query(array(
      'post_type' => array( 'player' ),
      'post__in'  => $players,
    )); 
    
    $player_articles_query = new WP_Query(array(
      'post_type'      => 'post',
      'post__not_in'   => array(get_the_ID()),
      'posts_per_page' => 4,
      'meta_query'     => array(
        array(
            'key' => 'player', // name of custom field
            'value' => '"' . $players[0] . '"', // matches exactly "123", not just 123. This prevents a match for "1234"
            'compare' => 'LIKE'
        )
      )
    )); ?>

        <!-- Profiles tagged -->
        <?php if ($player_query->have_posts()) : ?>
          <div class="mt-5">
            <?php generateSectionHeadingHTML(array(
              'title' => 'Profiles mentioned'
            )); ?>
       
            <div class="row">
              <?php while ($player_query->have_posts()) : $player_query->the_post(); ?>
                <div class="col-12 col-lg-6 mt-3">
                  <?php require locate_template('template-parts/card/card-streamer.php') ?>
                </div>
              <?php endwhile; ?>
            </div>
          </div>
        <?php endif; ?>
        <?php wp_reset_postdata(); ?>


        <!-- More profile news -->
        <?php if ($player_articles_query->have_posts()) : ?>
          <div class="mt-5">
            <?php generateSectionHeadingHTML(array(
              'title' => get_the_title($players[0]) . ' News'
            )); ?>
       
            <div class="white-cards-col">
              <?php while ($player_articles_query->have_posts()) : $player_articles_query->the_post(); ?>
                
               <?php require locate_template('template-parts/card/card-new-york.php'); ?>

              <?php endwhile; ?>
            </div>
          </div>
          <?php endif; ?>
          <?php wp_reset_postdata(); ?>
         <?php endif; ?>
      
      </div><!-- .col --> 

      <div class="col-12 col-lg-4">
        <!-- Sidebar -->
        <?php require locate_template('sidebar.php') ?>
      </div>

    </div><!-- .row -->

    <div class="row">
      <!-- More category news -->
      <?php $cat_query = new WP_Query(array('cat' => $category[0]->cat_ID, 'post__not_in' => array(get_the_ID()), 'posts_per_page' => 6)); ?>


        <?php if ($cat_query->have_posts()) : ?>
        <div class="mt-5">

         <?php generateSectionHeadingHTML(array(
            'title' => 'More ' . $category[0]->name,
            'link'  => site_url() . '/category/' . $category[0]->slug . '/'
          )); ?>
        
          <div class="row">
            <?php while ($cat_query->have_posts()) : $cat_query->the_post(); ?>
              <div class="col-12 col-sm-6 col-lg-4 mt-3">
                <?php require locate_template('template-parts/card/card-two.php') ?>
              </div>
            <?php endwhile; ?>
          </div>
        </div>
      <?php endif; ?>
      <?php wp_reset_postdata(); ?>
    </div><!-- .row -->

  </div><!-- .container --> 
</div><!-- .post --> 

<?php get_footer(); ?>