<?php 

add_action( 'wp_enqueue_scripts', 'themebs_enqueue_styles');
function themebs_enqueue_styles() {
  wp_enqueue_style( 'bootstrap', get_template_directory_uri() . '/assets/css/bootstrap.min.css' );
  wp_enqueue_style('main-styles', get_theme_file_uri('/build/style-index.css'), array(), wp_get_theme()->get('Version'));
}

add_action( 'wp_enqueue_scripts', 'themebs_enqueue_scripts');
function themebs_enqueue_scripts() {
  wp_deregister_script( 'jquery' );

  wp_enqueue_script('jquery-3.4.1', 'https://code.jquery.com/jquery-3.4.1.min.js', array(), '3.4.1', true);
  wp_enqueue_script('bootstrap5', 'https://cdn.jsdelivr.net/npm/bootstrap@5.2.0/dist/js/bootstrap.min.js', [], '5.2.0', true);
	wp_enqueue_script('popper', 'https://cdn.jsdelivr.net/npm/@popperjs/core@2.11.5/dist/umd/popper.min.js', [], '2.11.5', true);
  wp_enqueue_script('global-script', get_template_directory_uri() . '/assets/js/global.js', [], '1.4', true);
  wp_enqueue_script('tps-index', get_template_directory_uri() . '/build/index.js', [], '1.1', true);

  // Single-Player JS
  if (is_singular( 'player' )) {
    wp_enqueue_script('player-script', get_template_directory_uri() . '/assets/js/player.js', [], '1.7', true);

    // Get the current post ID
    $post_id = get_the_ID();

    // Find all posts that are tagged with this player ID. 
    $args = array(
      'post_type'      => 'post',
      'posts_per_page' => -1,     
      'meta_query'     => array(
          array(
              'key'     => 'player', 
              'value'   => $post_id,               
              'compare' => 'LIKE' 
          ),
      ),
      'fields'         => 'ids',
    );
    $posts = get_posts($args);

    // Pass the WordPress REST API base URL to your JavaScript
    wp_localize_script('player-script', 'wpApiSettings', array(
        'root'    => esc_url_raw(rest_url()),
        'post_id' => $post_id,
        'posts'   => $posts,
        'apps'    => profile_stream_apps(),
        61        => profile_stream_apps(61),
        91        => profile_stream_apps(91),
        121       => profile_stream_apps(121),
        79        => profile_stream_apps(79)
    ));
  }

  // Search JS
  // if (is_search()) {
  //   wp_enqueue_script('custom-search', get_template_directory_uri() . '/assets/js/search.js', [], '1.75', true);
  
  //   // Pass the WordPress REST API base URL to your JavaScript
  //   wp_localize_script('custom-search', 'wpApiSettings', array(
  //       'root' => esc_url_raw(rest_url()),
  //       'nonce' => wp_create_nonce('wp_rest'),
  //   ));
  // }

  // Combine your SVG icons and generalized data
  wp_localize_script('tps-index', 'wpData', [
      'svgIcons' => [
          'close'  => get_svg_icon('close'),
          'search' => get_svg_icon('search'),
      ],
      'siteUrl' => get_site_url(), // Generalized data
  ]);
  
};

/**
 * Register Blocks
 */
function tps_register_acf_blocks() {
  register_block_type( __DIR__ . '/blocks/profile-grid' );
  register_block_type( __DIR__ . '/blocks/post-grid' );
  register_block_type( __DIR__ . '/blocks/button' );
  register_block_type( __DIR__ . '/blocks/stream-results' );
  register_block_type( __DIR__ . '/blocks/triton-results' );
}
add_action( 'init', 'tps_register_acf_blocks' );


/**
 * ACF Post Object search improvlement
 */
require get_theme_file_path('/inc/acf-post-object-search-filter.php');

/**
 * Function to create a section heading
 */
require get_theme_file_path('/inc/section-heading.php');


/**
 * Function to create a section heading
 */
require get_theme_file_path('/inc/bbc-card.php');

/**
 * Adjust the default wordpress queries
 */
require get_theme_file_path('/inc/custom-queries.php');

/**
 * Profile stream apps
 */
require get_theme_file_path('/inc/profile-stream-apps.php');

/**
 * Player Stream Results
 */
require get_theme_file_path('/inc/display-player-stream-results.php');

/**
 * Streamed Games List
 */
require get_theme_file_path('/inc/streamed-games-list.php');

/**
 * Custom Search API route 
 */
require get_theme_file_path('/inc/routes/search.php');

/**
 * Custom Search Two API route 
 */
require get_theme_file_path('/inc/routes/search-two.php');

/**
 * Game Show Results Route
 */
require get_theme_file_path('/inc/routes/cash-game-results.php');

/**
 * Player Results Route
 */
require get_theme_file_path('/inc/routes/player-results.php');

/**
 * Player Results Route
 */
require get_theme_file_path('/assets/svg-icons.php');


/**
 * Add support for excerpts on Pages
 */
add_action('init', 'my_add_excerpts_to_pages');
function my_add_excerpts_to_pages() {
    add_post_type_support('page', 'excerpt');
}


/**
 * Serve children of platform page the correct template file. 
 */
add_filter('template_include', 'platform_page_template');
function platform_page_template($template) {
  global $post;

  // Get the current page ID
  $current_page_id = get_the_ID();

  // Get the parent page ID
  $parent_page_id = wp_get_post_parent_id($current_page_id);

  // Check if there is a parent page
  if ($parent_page_id && !is_search()) {
 
    $parent_page_template = get_page_template_slug($parent_page_id);

    if ($parent_page_template === 'templates/platform.php') {

      $custom_template = locate_template('templates/platform.php');
      
      if (!empty($custom_template)) {
        return $custom_template;
      }  
    }
  } 
  return $template;
};


// Stop linking my media file wordpress!
add_action( 'after_setup_theme', function() {
  update_option( 'image_default_link_type', 'none' );
});


add_action( 'after_setup_theme', 'register_navwalker' );
function register_navwalker(){
	require_once get_template_directory() . '/class-wp-bootstrap-navwalker.php';
}

// MENUS
register_nav_menus( array(
  'primary'      => __( 'Primary Nav', 'bs-theme' ),
  'sidebar-nav'  => __( 'Sidebar Nav', 'bs-theme' ),
  'footer-one'   => __( 'Footer Menu One', 'bs-theme' ),
  'footer-two'   => __( 'Footer Menu Two', 'bs-theme' ),
  'footer-three' => __( 'Footer Menu Three', 'bs-theme' ),
  'footer-four'  => __( 'Footer Menu Four', 'bs-theme' )
));

/* Custom length for the_excerpt */
add_filter( 'excerpt_length', 'custom_excerpt_length', 999 );
function custom_excerpt_length( $length ) {
	return 16;
}


/* Custom image sizes */
function wpse_setup_theme() {
  add_theme_support( 'post-thumbnails' );
  add_image_size( 'small-thumb', 100, 100, true );
  add_image_size( 'platform' , 180, 120, true);
  add_image_size( 'square-thumb', 200, 200, true );
  add_image_size( 'landscape', 800, 450, true );
}
add_action( 'after_setup_theme', 'wpse_setup_theme' );

function cards_shortcode( $atts ) {
  // Set default attribute values
  $attributes = shortcode_atts( array(
    'hand' => 'default'
  ), $atts); 

  // Define constants for card icons
  $spade_icon   = 'https://toppokerstreamers.com/wp-content/uploads/2023/10/suit-spade.png';
  $heart_icon   = 'https://toppokerstreamers.com/wp-content/uploads/2023/10/suit-heart.png';
  $diamond_icon = 'https://toppokerstreamers.com/wp-content/uploads/2023/10/suit-diamond.png';
  $club_icon    = 'https://toppokerstreamers.com/wp-content/uploads/2023/10/suit-club.png';

  // Split the input string into an array of cards
  $cards_input = explode (",", $attributes['hand']);
  $processed_cards = array(); 

  foreach($cards_input as $card) {
    $card = trim($card);

    // Extract the rank and suit
    $rank = substr($card, 0, 2); // Extract two characters for the rank
    $suit = substr($card, -1); // Extract the suit
    
    // If the first character of $rank is '1', it means the rank is '10'
    if ($rank[0] === '1') {
        $rank = '10';
    } else {
        $rank = $rank[0]; // For other ranks (2-9), take the first character
    }

    // Create an associative array representing the card
    $card_data = array(
      'rank' => $rank,
      'suit' => $suit
    );

    // Add the card data to the processed cards array
    array_push($processed_cards, $card_data);
  };

  // Create the output
  $output = '<span class="playing-card-hand">'; 
  foreach($processed_cards as $card) {

    $suit = $card['suit'];
    $card_icon = '';
    $card_alt = '';

    
    switch($suit) {
      case 's':
        $card_icon = $spade_icon;
        $card_alt = cardLetterToWord($card['rank']) . ' of Spades';
        break;
      case 'c':
        $card_icon = $club_icon;
        $card_alt = cardLetterToWord($card['rank']) . ' of Clubs';
        break;
      case 'd':
        $card_icon = $diamond_icon;
        $card_alt = cardLetterToWord($card['rank']) . ' of Diamonds';
        break;
      case 'h':
        $card_icon = $heart_icon;
        $card_alt = cardLetterToWord($card['rank']) . ' of Hearts';
        break;
    };

    $output .= '<span class="playing-card">' . strtoupper($card['rank']) . '<img src="' . $card_icon .  '" alt="' . $card_alt . '"></span>';
  };  

  $output .= '</span>';

  return $output;
}; 

// Register the shortcode
add_shortcode( 'cards', 'cards_shortcode' );

function cardLetterToWord($data) {
  if (is_numeric($data)) {
    return $data;
  } else if ($data === 'a') {
    return 'Ace'; 
  } else if ($data === 'k') {
    return 'King';
  } else if ($data === 'q') {
    return 'Queen';
  } else if ($data === 'j') {
    return 'Joker';
  };
 };


// Update the default query on the Author.php page.
function author_cpt_filter($query) {
  if ( !is_admin() && $query->is_main_query() ) {
    if ($query->is_author()) {
      $query->set('post_type', array('post', 'player'));
      $query->set('post_per_page', 12);
    }
  }
}
add_action('pre_get_posts','author_cpt_filter');

function modify_taxonomy_query($query) {
  
  // Check we are on an 'event series' term page
  if ($query->is_main_query() && (is_tax('pokergo_series') || is_tax('triton_series') || is_tax('wpt_taxonomy'))) {
    // Set the ACF field key to order by ACF field key
    $query->set('meta_key', 'event');
    $query->set('orderby', 'meta_value_num'); // Use 'meta_value' for strings, 'meta_value_num' for numbers
    $query->set('order', 'ASC'); // Set to 'ASC' or 'DESC' as needed
  }

  // Check we are on a 'stream category' page
  if ($query->is_main_query() && (is_tax('triton_cash_category') || is_tax('pokergo_cash_category'))) {
    // Set the ACF field key to order by ACF field key
    $query->set('meta_key', 'episode');
    $query->set('orderby', 'meta_value_num'); // Use 'meta_value' for strings, 'meta_value_num' for numbers
    $query->set('order', 'ASC'); // Set to 'ASC' or 'DESC' as needed
   }
}
add_action('pre_get_posts', 'modify_taxonomy_query');


// findImg - currently used on card-profile.php
function findImg($term_id) {
  switch ($term_id) {
      case 61:
        return 'https://top-poker-streamers.local/wp-content/uploads/2023/11/hustler-casino-live-circle__100x100-jpg.webp'; 
        break;
      case 55:
          return 'https://top-poker-streamers.local/wp-content/uploads/2023/11/poker-go-circle__100x100-jpg.webp'; 
          break;
      case 65: 
          return 'https://top-poker-streamers.local/wp-content/uploads/2023/11/triton-circle__100x100-jpg.webp';
          break;
      case 51: 
          return 'https://top-poker-streamers.local/wp-content/uploads/2023/11/twitch-circle__100x100-jpg.webp';
          break; 
      case 52:  
          return 'https://top-poker-streamers.local/wp-content/uploads/2023/11/youtube-circle__100x100-jpg.webp'; 
          break;
      default:
          return;
    };
  };


// Single-player.php 
// Check for players who have featued on HCL Streams

//// SEARCH
// Search endpoint fetch
function custom_hcl_apps_endpoint() {
  register_rest_route('custom/v1', '/hcl-apps/', array(
      'methods'  => 'GET',
      'callback' => 'profile_stream_apps',
  ));
}
add_action('rest_api_init', 'custom_hcl_apps_endpoint');


function get_hcl_results_by_episode($postType = 'hcl_stream') {
  $results = array();
  $hcl_args = array(
    'post_type'      => $postType,
    'posts_per_page' => -1,
    'meta_key'       => 'episode', 
    'orderby'        => 'meta_value_num',
    'order'          => 'DESC',
  );

  $hcl_query = new WP_Query($hcl_args);

  if ($hcl_query->have_posts()) : 
    while ($hcl_query->have_posts()) : $hcl_query->the_post();       
       
        // Add the item to the array
        $results[] = array(
          'link'        => get_the_permalink(),
          'episode'     => intval(get_field('episode')),
          'date'        => get_field('date'),
          'type'        => get_field('game_type'),
          'stakes'      => get_field('stakes_played'),
          'youtube_vid' => get_field('youtube_stream')
        );

     endwhile; 
     wp_reset_postdata(); 
    endif; 

  return $results;
};

function get_hcl_results_by_player($postType = 'hcl_stream') {
  $results = array(); // Initialize $results as an indexed array
  $hcl_args = array(
    'post_type'      => $postType,
    'posts_per_page' => -1,
    'meta_key'       => 'episode', // The ACF field name
    'orderby'        => 'meta_value_num', // Specify that the meta value should be treated as a number
    'order'          => 'DESC', // or 'DESC' depending on your sorting preference
  );

  $hcl_query = new WP_Query($hcl_args);

  if ($hcl_query->have_posts()) : 
    while ($hcl_query->have_posts()) : $hcl_query->the_post();       
      $hcl_results = get_field('results');
 
      foreach ($hcl_results as $r) {

        $player = $r['player_link'];
        $winnings = $r['tournament_winnings'];
        $winnings_int = (int)$winnings;

        // Check if the item already exists in the array
        $found = false;
        foreach ($results as $result) {
          if ($result['id'] === $player) {
            $found = true;
            break;
          }
        }

        // If not found, add the player to the array
        if (!$found) {
          $results[] = array(
            'id'          => $player,
            'title'       => get_the_title($player),
            'link'        => get_the_permalink($player),
            'thumbnail'   => get_the_post_thumbnail_url($player, 'thumbnail'),
            'winnings'    => 0, 
            'appearances' => 0 
          );
        }

        // Add all winnings to players
        $key = array_search($player, array_column($results, 'id'));
        $results[$key]['winnings'] += $winnings_int;

        // Increment appearance count for the player
        $results[$key]['appearances']++;
      }; 

    endwhile; 
    wp_reset_postdata(); 
  endif; 

  return $results;
};

// Get profile_type terms
function get_profiles_by_taxonomy($tax, $num_posts) {
  $collection = array();
  $used_posts = array();

  // Profile_type terms
  $profile_type_terms = get_terms(array(
      'taxonomy'   => $tax,
      'orderby'    => 'count',
      'order'      => 'DESC',
      'hide_empty' => true,
    )
  );

  foreach($profile_type_terms as $term) {

    $term_id = $term->term_id;
    // Get profiles
    $args = array(
      'post_type'      => 'player',
      'posts_per_page' => $num_posts,
      'post__not_in'   => $used_posts,
      'orderby'        => 'post_views',
      'order'          => 'DESC',
      'tax_query'      => array(
        array(
            'taxonomy' => $tax, 
            'field'    => 'term_id',
            'terms'    => $term_id,
        ),
      ),
    );

    $posts = new WP_Query($args);

    // Check if there are posts
    if ($posts->have_posts()) {
      while ($posts->have_posts()) {
          $posts->the_post();
          // Add post ID to used_posts array
          $used_posts[] = get_the_ID();
      }
    // Reset post data
    wp_reset_postdata();
    }

    $collection[$term_id] = array(
      'title'       => $term->name,
      'description' => $term->description,
      'permalink'   => get_term_link($term_id, $tax),
      'profiles'    => $posts,
    );
  };

  return $collection;
};

function cut_off_after_three_paragraphs($content) {
  // Split the content into paragraphs
  $paragraphs = explode("\n", $content);

  // Take the first three paragraphs
  $cut_off_content = array_slice($paragraphs, 0, 3);

  // Join paragraphs back into text for the first part
  $text_set1 = implode("\n", $paragraphs);

  // Join paragraphs back into text for the second part
  $text_set2 = implode("\n", $cut_off_content);

  // Return an array with two sets of text
  return array($text_set1, $text_set2);
};


// Carousel
 function carousel_card($card_title, $card_link, $card_img) { 

  $output = '<div class="player-col player-col-4 mt-2">';
  $output .= '<div class="h-100">';
  $output .= '<a class="tps-card-link" href="' . esc_url($card_link) . '">';
  $output .= '<div class="tps-card p-2 p-lg-3 rounded h-100">';
  $output .= '<div class="tps-card__media d-flex justify-content-center">';
  $output .= '<img src="' . esc_url($card_img) . '" alt="' . esc_html($card_title) . '" class="rounded-circle" width="100" height="100">';
  $output .= '</div>';
  $output .= '<div class="pt-3">';
  $output .= '<h3 class="h6 text-center tps-card__title m-0 text-break">' . esc_html($card_title) . '</h3>';
  $output .= '</div>';
  $output .= '</div>';
  $output .= '</a>';
  $output .= '</div>';
  $output .= '</div>';

  return $output;
}; 


function carousel($arguments) { 
  $inner_carousel = '';
  $output = '';

  $inputQuery = new WP_Query($arguments);
  $posts = $inputQuery->posts;
  $post_count = $inputQuery->post_count; 
  $uuid = wp_generate_uuid4(); 

  $svgNext = '<svg xmlns="http://www.w3.org/2000/svg" height="16" width="14" viewBox="0 0 448 512"><!--!Font Awesome Free 6.5.1 by @fontawesome - https://fontawesome.com License - https://fontawesome.com/license/free Copyright 2023 Fonticons, Inc.--><path d="M438.6 278.6c12.5-12.5 12.5-32.8 0-45.3l-160-160c-12.5-12.5-32.8-12.5-45.3 0s-12.5 32.8 0 45.3L338.8 224 32 224c-17.7 0-32 14.3-32 32s14.3 32 32 32l306.7 0L233.4 393.4c-12.5 12.5-12.5 32.8 0 45.3s32.8 12.5 45.3 0l160-160z"/></svg>';
  $svgPrev = '<svg xmlns="http://www.w3.org/2000/svg" height="16" width="14" viewBox="0 0 448 512"><!--!Font Awesome Free 6.5.1 by @fontawesome - https://fontawesome.com License - https://fontawesome.com/license/free Copyright 2023 Fonticons, Inc.--><path d="M9.4 233.4c-12.5 12.5-12.5 32.8 0 45.3l160 160c12.5 12.5 32.8 12.5 45.3 0s12.5-32.8 0-45.3L109.2 288 416 288c17.7 0 32-14.3 32-32s-14.3-32-32-32l-306.7 0L214.6 118.6c12.5-12.5 12.5-32.8 0-45.3s-32.8-12.5-45.3 0l-160 160z"/></svg>';
  
  $panel_arrays = array_chunk($posts, 4);

  foreach ($panel_arrays as $index => $chucked) {
    

    $inner_carousel .= '<div class="carousel-item' . ($index == 0 ? ' active' : '') . '">';
    $inner_carousel .= '<div class="player-row flex-wrap-reverse gap-2">';

    foreach ($chucked as $card) {
      
      $title = $card->post_title;
      $permalink = get_the_permalink($card->ID);
      $thumbnail = get_the_post_thumbnail_url($card->ID, 'thumbnail');
      $inner_carousel .= carousel_card($title, $permalink, $thumbnail);
    };

    $inner_carousel .= '</div>';
    $inner_carousel .= '</div>';
  }

  ?>

  <?php
  $output .= '<div id="carousel-' . $uuid . '" class="carousel slide tps-card-carousel">';
  $output .= '  <div class="carousel-indicators tps-card-carousel__indicators">';
  $output .= '    <button type="button" data-bs-target="#carousel-' . $uuid . '" data-bs-slide-to="0" class="indicator active" aria-current="true" aria-label="Slide 1"></button>';
  $output .= '    <button type="button" data-bs-target="#carousel-' . $uuid . '" data-bs-slide-to="1" class="indicator" aria-label="Slide 2"></button>';
  $output .= '    <button type="button" data-bs-target="#carousel-' . $uuid . '" data-bs-slide-to="2" class="indicator" aria-label="Slide 3"></button>';
  $output .= '  </div>';

  $output .= '  <div class="carousel-inner">';
  $output .= '    ' . $inner_carousel;
  $output .= '  </div>';

  $output .= '  <button class="carousel-control-prev  tps-carousel-control d-none" type="button" data-bs-target="#carousel-' . $uuid . '" data-bs-slide="prev">';
  $output .= '    <span class="tps-carousel__arrow" aria-hidden="true">'. $svgPrev . '</span>';
  $output .= '    <span class="visually-hidden">Previous</span>';
  $output .= '  </button>';

  $output .= '  <button class="carousel-control-next tps-carousel-control" type="button" data-bs-target="#carousel-' . $uuid . '" data-bs-slide="next">';
  $output .= '    <span class="tps-carousel__arrow" aria-hidden="true">'. $svgNext . '</span>';
  $output .= '    <span class="visually-hidden">Next</span>';
  $output .= '  </button>';
  $output .= '</div>';

  echo $output;

}; 

/**
 * Transform numbers so 1 becomes 1st, 2 becomes 2nd
 * 
 * For results tables
 */

 function tranformNumbers($number) {
    // Special case for 11, 12, and 13
    if ($number % 100 >= 11 && $number % 100 <= 13) {
        return $number . 'th';
    }

    // General case
    switch ($number % 10) {
        case 1: return $number . 'st';
        case 2: return $number . 'nd';
        case 3: return $number . 'rd';
        default: return $number . 'th';
    }
}

// Format the date for all cash games
function formatTableDate($date) {

  if ($date) {
    // Create a DateTime object from the string
    $dateTime = DateTime::createFromFormat('d/m/Y', $date);
    
    // Check if the conversion was successful
    if ($dateTime) {
      $formattedDate = $dateTime->format('j M Y');
      
      return $formattedDate;
    } else {
      // Handle invalid input date
      return "Invalid date format";
    }
  }
}; 


// Temp

// function update_player_appearance_count($post_id) {
//     // Check if this is a 'cash_game' post
//     if (get_post_type($post_id) !== 'cash_game') {
//         return;
//     }

//     // Get all players in the repeater field for this cash game
//     if (have_rows('results', $post_id)) {
//         $player_counts = array();
        
//         // Loop through each player in the repeater
//         while (have_rows('results', $post_id)) {
//             the_row();
//             $player_id = get_sub_field('player_link');

//             if ($player_id) {
//                 // Track player appearances in an array
//                 if (!isset($player_counts[$player_id])) {
//                     $player_counts[$player_id] = 1;
//                 } else {
//                     $player_counts[$player_id]++;
//                 }
//             }
//         }

//         // Update each player's appearance count
//         foreach ($player_counts as $player_id => $count) {
//             update_post_meta($player_id, 'appearance_count', $count);
//         }
//     }
// }
// add_action('save_post_cash_game', 'update_player_appearance_count');
