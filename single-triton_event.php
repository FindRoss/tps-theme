<?php get_header(); ?>

  <?php 
    // Creat an array to hold the custom field values
    $details = array();

    $seriesTerms = get_the_terms(get_the_ID(), 'triton_series');
    if ($seriesTerms) {
      $seriesName        = $seriesTerms[0]->name;
      $seriesLocation    = get_field('location', $seriesTerms[0]);
      $seriesYear        = get_field('year', $seriesTerms[0]);
      $seriesLink        = get_term_link($seriesTerms[0]);
      $details['Series'] = '<a href="' . $seriesLink . '">' . $seriesLocation . ' ' . $seriesYear . '</a>';
    }

    $event = get_field('event');
    if ($event) {
      $details['Event'] = 'Event ' . $event; 
    } 

    $buyIn = get_field('buy_in');
    if ($buyIn) {
      $details['Buy In'] = '$' . $buyIn;
    }

    $playersPaid = get_field('players_paid'); 
    if ($playersPaid) {
      $details['Players Paid'] = $playersPaid;
    }

    $person_profile = 'https://toppokerstreamers.com/wp-content/uploads/2024/08/anonymous-person-profile__100x100-jpg.webp';
  ?>

  <!-- Temp navigation  -->
  <!-- require locate_template('template-parts/platform/temp-nav-triton.php');  -->
  

  <main class="page-main">

    <div class="container">
      <?php if ( have_posts() ) : while ( have_posts() ) : the_post(); ?>


        <div class="row mt-4">
          <div class="col-12 col-lg-8">

            <!-- HEADER -->
            <h1>Event <?php echo $event ?>: <?php the_title(); ?></h1>

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

            <!-- TABLE -->
            <?php if( have_rows('results') ): ?>
              <div class="mt-5">
                <h3>Payouts</h3>
                <table class="tps-table-results">   
                  <tbody>
                    <?php while( have_rows('results') ) : the_row();

                      // Load sub field value.
                      $player   = get_sub_field('player');
                      $winnings = get_sub_field('winnings');

                      $winnings_formatted = number_format(intval($winnings));
                      
                      $player_output = '<a class="class="tps-table-results__player-link" href="' . get_the_permalink($player) . '"><img class="rounded-circle" src="' . $person_profile . '" width="75" height="75" alt="' . get_the_title($player) . '">' . get_the_title($player) . '</a>'; 
                      if (has_post_thumbnail($player)) { 
                        $player_output = '<a class="class="tps-table-results__player-link" href="' . get_the_permalink($player) . '"><img class="rounded-circle" src="' . get_the_post_thumbnail_url($player, "thumbnail") . '" width="75" height="75" alt="' . get_the_title($player) . '">' . get_the_title($player) . '</a>';
                      } 
                    ?>
                    
                      <tr>
                        <td><?php echo tranformNumbers(get_row_index()); ?></td>
                        <td><?php echo $player_output; ?></td>
                        <td class="text-end">$<?php echo $winnings_formatted; ?></td>
                      </tr>
                  
                    <?php endwhile; ?>
                  </tbody>
                </table>
              </div>
            <?php endif; ?>

          </div>
        </div>

      <?php endwhile; endif; ?>
    </div><!-- container --> 
  </main>
<?php get_footer(); ?>