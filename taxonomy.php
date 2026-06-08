<?php get_header(); ?>

  <?php 
    /* 
    *
    * Currently used for  
    * pokergo_series, triton_series (events)
    * triton_cash_category, pokergo_cash_category (cash_game)
    *
    */ 

    $term = get_queried_object();
    $term_id = $term->term_id;
    $term_taxonomy = $term->taxonomy;
    $term_parent = $term->parent;

    $term_children = get_term_children($term_id, $term_taxonomy);
  

    // Set the name of the host to use in the title.
    $game_host = ''; 
    if ($term_taxonomy == 'triton_series' || $term_taxonomy == 'triton_cash_category') {
       $game_host = 'Triton'; 
    } else if ($term_taxonomy == 'pokergo_series' || $term_taxonomy == 'pokergo_cash_category') {
      $game_host = 'PokerGO'; 
    } else if ($term_taxonomy == 'wpt_taxonomy') {
      $game_host = 'World Poker Tour';
    };

    $landscapeImage = get_field('landscape_image', $term);
    $location       = get_field('location', $term);
    $year           = get_field('year', $term);
  
    // Get the dates
    $startDate = get_field('start_date', $term);
    $endDate   = get_field('end_date', $term);

    if ($startDate AND $endDate) { 
        // Create DateTime objects
        $startDateObj = DateTime::createFromFormat('d/m/Y', $startDate);
        $endDateObj   = DateTime::createFromFormat('d/m/Y', $endDate);
        
        // Get the UNIX timestamp
        $startDateTimestamp = $startDateObj->getTimestamp();
        $endDateTimestamp   = $endDateObj->getTimestamp();
        
        // Start Dates
        $startMonth = date("F", $startDateTimestamp);
        $startDisplayDate = date("jS F", $startDateTimestamp);
        
        // End Dates
        $endMonth = date("F", $endDateTimestamp);
        $endDisplayDate = date("jS F Y", $endDateTimestamp);
        
        // Only show the month twice if I really need to
        if ($startMonth == $endMonth) {
          $startDisplayDate = date("jS", $startDateTimestamp);
      }
    }
  ?>  

  <!-- Temp navigation  -->
  <!-- require locate_template('template-parts/platform/temp-nav-triton.php'); -->
  

  <main class="page-main">  

    <div class="container">

      <?php if ($landscapeImage) : ?>

      <!-- Image -->
      <div class="series-header">
        <div class="series-header__layout">
          <div class="series-header__content">
            <h1 class="series-header__title"><?php echo $game_host . ' ' . $location . ' ' . $year; ?></h1>
            <div class="series-header__date">
              <?php if ($startDate AND $endDate) { 
                echo $startDisplayDate . ' - ' . $endDisplayDate;
              }; ?>
            </div>
          </div>
          <?php if ($landscapeImage) { ?>
          <div class="series-header__media">
            <img class="series-header__image" src="<?php echo $landscapeImage['url']; ?>" alt="<?php echo $landscapeImage['alt']; ?>">
          </div>
          <?php }; ?>
        </div>
      </div>

      <?php else : ?>
        <h1><?php echo $game_host; ?> <?php echo single_term_title(); ?></h1>
      <?php endif; ?>

      <!-- Description -->
      <?php if (term_description())  echo term_description(); ?>
      

      <!-- Default loop for term taoxnomy pages that do not have children -->
      <?php if (empty($term_children)) : ?>
        
        <?php if (have_posts()) : ?>
          <div class="row">
            <?php while (have_posts()) : the_post(); ?>
              <?php $event = get_field('event'); ?>
            
              <div class="col-12 col-md-4 d-flex flex-column">  

                <div class="series-event-card--wrapper d-flex flex-column flex-grow-1 pt-3 mt-3">
                  <a class="tps-card-link d-flex flex-column flex-grow-1" href="<?php the_permalink(); ?>">
                    <div class="tps-card d-flex flex-column flex-grow-1 rounded">

                      <!-- Image? -->
                      <?php if (has_post_thumbnail()): ?>
                        <img src="<?php echo get_the_post_thumbnail_url(null, 'medium'); ?>" width="450" height="300" class="w-100 h-auto" alt="<?php the_title(); ?>">
                      <?php endif; ?>

                      <div class="tps-card__body series-event-card__body p-3">
                        <?php if ($event) : ?><span class="series-event-card__event">Event <?php echo $event; ?></span><?php endif; ?>
                        <h2 class="tps-card__title series-event-card__title h4"><?php the_title(); ?></h2>
                      </div>

                    </div>
                  </a>
                </div>

              </div><!-- .col --> 
            <?php endwhile; ?>
          </div><!-- .row --> 
        <?php endif; ?>
      
      <?php elseif (!empty($term_children)) : ?>

      <div class="row">
      
        <?php foreach ($term_children as $child_id) {
          // Get the term object for each child term ID
          $child_term = get_term($child_id, $term_taxonomy);
          // Check if the term exists
          if (!is_wp_error($child_term)) {

            ob_start(); ?>

              <div class="col-12 col-md-4 d-flex flex-column">  

            

                  <div class="series-event-card--wrapper d-flex flex-column flex-grow-1 pt-3 mt-3">
                    <a class="tps-card-link d-flex flex-column flex-grow-1" href="<?php echo get_term_link($child_term->term_id, $term_taxonomy); ?>">
                      <div class="tps-card d-flex flex-column flex-grow-1 rounded">

                        <div class="tps-card__body series-event-card__body p-3">
                          <h2 class="tps-card__title series-event-card__title h4"><?php echo $child_term->name; ?></h2>
                        </div>

                      </div>
                    </a>
                  </div>

                </div><!-- .col --> 
              

            <?php
            // Get the buffered content and clean the buffer
            $child_content = ob_get_clean();

            // Output the cleaned content if needed
            echo $child_content;
            
            }
          } ?>

        </div><!-- .row -->
      <?php endif; ?>

    </div><!-- .container --> 

  </main>

<?php get_footer(); ?>