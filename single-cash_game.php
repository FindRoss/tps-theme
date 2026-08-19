<?php get_header(); ?>

<?php 
  $details = array();

  $date           = get_field('date'); 
  $episode        = get_field('episode');
  $game_type      = get_field('game_type');
  $stakes_played  = get_field('stakes_played');
  $youtube_stream = get_field('youtube_stream');

  $terms     = get_the_terms(get_the_ID(), 'streams');
  $term      = $terms[0]; 
  $term_name = $term->name;
  $term_id   = $term->term_id;

  $main_page_arr = get_field('main_page', $term);
  $main_page = $main_page_arr[0];
  $main_page_link = get_the_permalink($main_page);
  $main_page_title = get_the_title($main_page);

  if ($term) {
    $details['Stream'] = '<a href="' . $main_page_link . '">' . $term_name . '</a>';
  }
  if ($episode) {
    $details['Episode'] = $episode;
  }
  if ($game_type) {
    $details['Game Type'] = $game_type;
  }
  if ($stakes_played) {
    $details['Stakes Played'] = $stakes_played;
  }
  if ($date) {
    $details['Date'] = formatTableDate($date);
  }
  if ($youtube_stream) {
    // $details['Watch'] = '<a target="_blank" rel="nofollow" href="' . $youtube_stream . '">Watch ' . get_svg_icon('external-link') . '</a>';
  }

  $person_profile = 'https://toppokerstreamers.com/wp-content/uploads/2024/08/anonymous-person-profile__100x100-jpg.webp';

  require locate_template('template-parts/platform/platform-nav.php');
?>

  <main class="page-main">
    <div class="container">
      <?php if ( have_posts() ) : while ( have_posts() ) : the_post(); ?>
        
        <div class="row mt-4">
          <div class="col-12 col-lg-8">

            <!-- HEADER -->
            <h1><?php the_title(); ?></h1>

            <!-- IMAGE -->
            <?php if (has_post_thumbnail()) { 
              $alt_text = get_the_title(); 
              $image_id = get_post_thumbnail_id();
              $image_alt = get_post_meta($image_id, '_wp_attachment_image_alt', TRUE);
              if ($image_alt) {
                $alt_text = $image_alt;
              }
            ?>
              <img src="<?php echo get_the_post_thumbnail_url(null, 'large'); ?>" alt="<?php echo $alt_text; ?>" class="tps-featured-img rounded mt-3 w-100 h-auto" width="900" height="600">
            <?php }; ?>
            
            <!-- CONTENT -->
            <div class="large-description mt-4">
              <?php the_content(); ?>
            </div>
          
            <!-- DETAILS -->
            <?php if (!empty($details)) {  ?>
              <div class="mt-5">
                <h3>Details</h3>
                <table class="tps-table-striped">
                  <tbody>
                    <?php foreach ($details as $key => $value) { ?>
                      <tr>
                        <td><?php echo $key; ?></td>
                        <td><?php echo $value; ?></td>
                      </tr>
                    <?php }; ?>
                  </tbody>
                </table>
              </div>
            <?php }; ?>

            <!-- TABLE -->
            <?php if ( have_rows('results') ): ?>
              <div class="mt-5">
                <h3>Payouts</h3>
                <table class="tps-table-results">   
                  <tbody>
                    <?php while( have_rows('results') ) : the_row();

                      // Load sub field value.
                      $player   = get_sub_field('player_link');
                      $winnings = get_sub_field('tournament_winnings');
                      // number_format adds ',' and tunrs it into thousands
                      $winnings_formatted =  number_format(abs($winnings)); 
                    

                      $player_output = '<a class="class="tps-table-results__player-link" href="' . get_the_permalink($player) . '"><img class="rounded-circle" src="' . $person_profile . '" width="75" height="75" alt="' . get_the_title($player) . '">' . get_the_title($player) . '</a>';
                      // If this profile has a post thumbnail then show it! 
                      if (has_post_thumbnail($player)) { 
                        $player_output = '<a class="class="tps-table-results__player-link" href="' . get_the_permalink($player) . '"><img class="rounded-circle" src="' . get_the_post_thumbnail_url($player, "thumbnail") . '" width="75" height="75" alt="' . get_the_title($player) . '">' . get_the_title($player) . '</a>';
                      } 
                    ?>
  
                      <tr>
                        <td class="tps-table-results__position"><?php echo tranformNumbers(get_row_index()); ?></td>
                        <td><?php echo $player_output; ?></td>
                        <td class="text-end <?php echo ($winnings < 0) ? 'text-danger' : 'text-success'; ?>"> <?php echo ($winnings < 0) ? '-' : ' '; ?>$<?php echo $winnings_formatted; ?></td>
                      </tr>
                  
                    <?php endwhile; ?>
                  </tbody>
                </table>
              </div>
            <?php endif; ?>

            <!-- YOUTUBE EMBED -->
            <?php if ($youtube_stream) : ?>
              <!-- <h3 class="h6 text-uppercase text-muted fw-bold">Watch on YouTube</h3> -->
              <div class="mt-4 rounded overflow-hidden">
                <div class="youtube-embed-wrapper" id="video"></div>
              </div>
            <?php endif; ?>

          </div><!-- .col --> 

          <!-- Sidebar -->
          <div class="col-12 col-lg-4">
            <?php require locate_template('sidebar.php') ?>
          </div><!-- .col --> 

        </div><!-- .row --> 

      <?php endwhile; endif; ?>
    </div><!-- .container --> 
  </main>

  <section class="mt-5">
    <div class="container">
      <?php generateSectionHeadingHTML(array(
         'title' => "Latest {$term_name} Streams",
      )); ?>
      <?php streamedGamesList($term_id, get_the_ID()); ?>
    </div>
  </section>


  <?php if ($youtube_stream) : ?>
  <!-- <script> 
    const vidWrapper = document.querySelector('#video'); 

    function getId(url) {
      const regExp = /^.*(youtu.be\/|v\/|u\/\w\/|embed\/|watch\?v=|&v=)([^#&?]*).*/;
      const match = url.match(regExp);

        return (match && match[2].length === 11)
          ? match[2]
          : null;
      }
          
    // const videoId = getId('https://www.youtube.com/watch?v=vJWELUAlZZs');
    const videoId = getId('<?php echo $youtube_stream; ?>');
    const iframeMarkup = '<iframe width="560" height="315" src="https://www.youtube.com/embed/' 
        + videoId + '" frameborder="0" allowfullscreen></iframe>';
    
    vidWrapper.innerHTML = iframeMarkup; 
  </script> -->
  <?php endif; ?>


<?php get_footer(); ?>