<?php get_header(); ?>

<?php 
$cats_to_display = get_field('categories_to_display');
$players_to_display = array(); 
$streamers_to_display = array(); 

$used_articles = array();
?>

<div class="page flex flex-column flex-grow pb-5">
    
  <section>
    <div class="container-xxl">
      <div class="row">
        <!-- Main -->
        <div class="col-12 col-lg-8">

          <!-- FEATURED NEWS --> 
          <?php 
          $featured_news = get_field('featured_news');
          $featured_news_query = new WP_Query(array(
            'post_type'      => 'post', 
            'post__in'       => $featured_news,
            'posts_per_page' => 2, 
            'cat'            => 7 
          )); ?>
        
          <?php if ($featured_news_query->have_posts()) : ?>
            <div class="row">
              <?php while ($featured_news_query->have_posts()) : $featured_news_query->the_post(); ?>
                <div class="col-12 col-md-6 mt-3">
                  <?php require locate_template('template-parts/card/card-two.php'); ?>
                  <?php array_push($used_articles, get_the_ID()); ?>
                </div><!-- .col --> 
              <?php endwhile; ?>
              <?php wp_reset_postdata();  ?>
            </div><!-- .row --> 
          <?php endif; ?>
        </div><!-- .col -->

        <!-- Sidebar -->
        <div class="col-12 col-lg-4">
          <a href="https://go.wpnaffiliates.com/visit/?bta=236787&brand=americascardroom&afp=tps" target="_blank" rel="sponsored" class="ad-link">
            <img src="https://toppokerstreamers.com/wp-content/uploads/2026/06/ACR_Poker_Welcome_Bonus_Banner_300_x_300.avif" class="rounded ad-img w-100 h-auto" alt="ACR Poker welcome bonus banner"/>
          </a>
        </div>
     
      </div><!-- .row --> 
    </div><!-- .container --> 
  </section>

  <section class="mt-5">
    <div class="container-xxl">
      <?php generateSectionHeadingHTML(array(
        'title' => 'Latest Cash Game Streams',
      )); ?>
      <?php streamedGamesList(); ?>
    </div>
  </section>

  <!-- Profile types -->
  <section class="mt-5">
    <div class="container-xxl">
    
    <?php generateSectionHeadingHTML(array(
          'title' => 'Profiles',
          'link' => site_url() . '/profiles/',
    )); ?>
              
      <div class="row">
        <?php 
          // https://toppokerstreamers.com/wp-content/uploads/2023/09/Andy-Stacks-poker-jpg.webp
          $profileCards = array(
            array (
              'title' => 'Players',
              'link'  => site_url() . '/type/players/', 
              'image' => 'https://toppokerstreamers.com/wp-content/uploads/2023/09/Sashimi-Poker-HCL-1-jpg.webp'
            ),
            array(
              'title' => 'Streamers',
              'link'  => site_url() . '/type/streamers/',
              'image' => 'https://toppokerstreamers.com/wp-content/uploads/2023/04/Parker-Tonkaaaap-Talbott-jpg.webp'
            ),
            array(
              'title' => 'Vloggers',
              'link'  => site_url() . '/type/vloggers/',
              'image' => 'https://toppokerstreamers.com/wp-content/uploads/2023/04/Ethan-Rampage-Yau-jpg.webp'
            ),
            array(
              'title' => 'Commentators',
              'link'  => site_url() . '/type/commentators/',
              'image' => 'https://toppokerstreamers.com/wp-content/uploads/2023/05/Bart-Hanson.png'
            )
          );
          
          foreach ($profileCards as $card) { ?>

            <div class="col-12 col-sm-6 col-lg-3 mt-3"> 
              <a class="gyg-card tps-card-link" href="<?php echo $card['link']; ?>">
                <div class="gyg-card__img-container" style="background-image: url('<?php echo $card['image']; ?>');">
                  <h3 class="gyg-card__title"><?php echo $card['title']; ?></h3>
                </div>
              </a>
            </div>

          <?php } ?>


      </div>
    </div>
  </section>



<!-- NEWS ARTICLES --> 
  <?php $article_query = new WP_Query(array(
    'posts_per_page' => 6, 
    'post__not_in'   => $used_articles, 
    'post_type'      => 'post'
  )); ?>

  <!-- Args -->
  <?php if ($article_query->have_posts()) :  ?>

    <section class="mt-5">
      <div class="container-xxl">
        
        <?php generateSectionHeadingHTML(array(
          'title' => 'News',
          'link'  => site_url() . '/category/news/'
        )); ?>
          
        <div class="row">
          <?php while ($article_query->have_posts()) : $article_query->the_post(); ?>
            <div class="col-12 col-md-4 mt-4">
              <?php require locate_template('template-parts/card/card-two.php'); ?>
            </div><!-- .col --> 
          <?php endwhile; ?>
        </div><!-- .row --> 

      </div><!-- .container -->
    </section>

  <?php endif; ?>
  <?php wp_reset_postdata();  ?>

<!-- HUSTLER CASINO LIVE RESULTS -->
<section class="mt-5 overflow-hidden">
  <div class="container-xxl">
    
    <?php generateSectionHeadingHTML(array(
    'title' => 'Hustler Casino Live'
    )); ?>

    <?php generateBBCCardHTML(array(
      'title' => 'Huster Casino Live',
      'description' => 'Hustler Casino Live is a raw footage, live-stream poker show where some of the biggest names in poker compete alongside regulars and special guests.',
      'link' => site_url() . '/hustler-casino-live/',
      'image' => 'https://toppokerstreamers.com/wp-content/uploads/2024/04/hustler-casino-live-scene__800x450-jpg.webp'
    )); ?>


   
  </div>
</section>

<!-- Streamed Games --> 
<section class="mt-5 pb-4">
  <div class="container-xxl">

  <?php generateSectionHeadingHTML(array(
      'title' => 'Streamed Games',
      'link'  => site_url() . '/streamed-games/'
    )); ?>

  <?php 
    $streamed_games_query = new WP_Query(array(
        'post_type' => 'page',
        'orderby'   => 'post__in',
        'post__in'  => array(4748, 10776, 9659, 3922, 10956, 13089, 3154, 10755),
    ));

    if ($streamed_games_query->have_posts()) : ?>
        <div class="row">
          
          
          <?php while ($streamed_games_query->have_posts()) : $streamed_games_query->the_post(); ?>
          
            <div class="col-12 col-lg-6">
              <!-- .card ny-card --> 
              <div class="ny-card mt-3 pt-3">
                <a class="tps-card-link" href="<?php the_permalink(); ?>">
                  <div class="ny-card__layout">
                    <div class="ny-card__media">
                      <img class="w-100 h-auto rounded tps-card-image" src="<?php the_post_thumbnail_url('small'); ?>"  alt="">
                    </div>
                    <div class="ny-card__content d-flex flex-column justify-content-center">
                      <h3 class="ny-card__title tps-card__title"><?php the_title(); ?></h3>
                        <p class="ny-card__excerpt"><?php echo get_the_excerpt(); ?></p>
                    </div>
                  </div>
                </a>
              </div>
            </div><!-- .col -->


        <?php endwhile;
        wp_reset_postdata(); ?>
      </div><!-- .row -->
    <?php endif; ?>

  </div>
</section>

<!-- Most Viewed --> 
<section>
  <div class="container-xxl">

    <?php generateSectionHeadingHTML(array(
      'title' => 'Profiles',
      'link'  => site_url() . '/profiles/'
    )); ?>

    <?php 
      $mostViewedPlayers = pvc_get_most_viewed_posts(array(
        'order'	    => 'desc',
        'post_type' => 'player',
        'posts_per_page' => 8
      ));

      $featuredProfiles = get_field('featured_profiles');

      $featuredProfilesArgs = array(
        'post_type' => 'player',
        'posts_per_page' => 4
      );

      if ($featuredProfiles) {
        $featuredProfilesArgs['post__in'] = $featuredProfiles;
      };

      $featuredProfilesQuery = new WP_Query($featuredProfilesArgs);
    ?>

    <?php if ($featuredProfilesQuery->have_posts()) : ?>
      <div class="row">
        <?php while ($featuredProfilesQuery->have_posts()) : $featuredProfilesQuery->the_post(); 
          // Access the post data
          $title = get_the_title(get_the_ID());
          $image = get_the_post_thumbnail_url(get_the_ID());
          $link  = get_permalink(get_the_ID());
          ?>

            <div class="col-12 col-sm-6 col-lg-3 mt-3">
              <a class="gyg-card tps-card-link" href="<?php echo $link; ?>">
                <div class="gyg-card__img-container" style="background-image: url('<?php echo $image ?>');">
                  <h3 class="gyg-card__title"><?php echo $title; ?></h3>
                </div>
              </a>
            </div><!-- .col --> 

          <?php endwhile; ?>
          <?php wp_reset_postdata(); ?>
        </div>
      <?php endif; ?>
    
  </div>
</section>


</div><!-- .page --> 
<?php get_footer(); ?>




