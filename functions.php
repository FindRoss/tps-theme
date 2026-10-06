<?php 

add_action( 'wp_enqueue_scripts', 'themebs_enqueue_styles');
function themebs_enqueue_styles() {
  wp_enqueue_style( 'bootstrap', get_template_directory_uri() . '/assets/css/bootstrap.min.css' );
  wp_enqueue_style('main-styles', get_theme_file_uri('/build/style-index.css'), array(), wp_get_theme()->get('Version'));
}

add_action( 'wp_enqueue_scripts', 'themebs_enqueue_scripts');
function themebs_enqueue_scripts() {
  wp_enqueue_script('bootstrap5', 'https://cdn.jsdelivr.net/npm/bootstrap@5.2.0/dist/js/bootstrap.min.js', [], '5.2.0', true);
	wp_enqueue_script('popper', 'https://cdn.jsdelivr.net/npm/@popperjs/core@2.11.5/dist/umd/popper.min.js', [], '2.11.5', true);
  wp_enqueue_script('global-script', get_template_directory_uri() . '/assets/js/global.js', [], '1.4', true);
  wp_enqueue_script('tps-index', get_template_directory_uri() . '/build/index.js', [], '1.1', true);

  // Single-Player JS
  if (is_singular( 'player' )) {
    wp_enqueue_script('player-script', get_template_directory_uri() . '/assets/js/player.js', [], '1.8', true);

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
    $localize_data = array(
        'root'    => esc_url_raw(rest_url()),
        'post_id' => $post_id,
        'posts'   => $posts,
    );

    foreach (get_player_stream_terms($post_id) as $stream) {
      $localize_data[$stream['id']] = profile_stream_apps($stream['id']);
    }

    foreach (get_player_event_types($post_id) as $event) {
      $localize_data[$event['post_type']] = get_player_event_results($post_id, $event['post_type']);
    }

    wp_localize_script('player-script', 'wpApiSettings', $localize_data);
  }

  // Search JS
  if (is_search()) {
    wp_enqueue_script('custom-search', get_template_directory_uri() . '/assets/js/search.js', [], '1.75', true);
  
    // Pass the WordPress REST API base URL to your JavaScript
    wp_localize_script('custom-search', 'wpApiSettings', array(
        'root' => esc_url_raw(rest_url()),
        'nonce' => wp_create_nonce('wp_rest'),
    ));
  };

  // Table handler utility (used by both results and players)
  wp_enqueue_script('table-handler', get_template_directory_uri() . '/assets/js/table-handler.js', [], '1.0', true);

  // Platform Results JS
  if (is_page() && get_post_field('post_name', get_the_ID()) === 'results') {
    wp_enqueue_script('platform-results', get_template_directory_uri() . '/assets/js/platform-results.js', ['table-handler'], '1.0', true);
    wp_localize_script('platform-results', 'platformResultsData', array(
      'root' => esc_url_raw(rest_url()),
    ));
  }

  // Platform Players JS
  if (is_page() && get_post_field('post_name', get_the_ID()) === 'players') {
    wp_enqueue_script('platform-players', get_template_directory_uri() . '/assets/js/platform-players.js', ['table-handler'], '1.0', true);
    wp_localize_script('platform-players', 'platformPlayersData', array(
      'root' => esc_url_raw(rest_url()),
    ));
  }

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
 * Adjust the default wordpress queries
 */
require get_theme_file_path('/inc/custom-queries.php');

/**
 * get_player_cashgame_results()
 */
require get_theme_file_path('/inc/get-player-cashgame-results.php');

/**
 * get_player_event_results()
 */
require get_theme_file_path('/inc/get-player-event-results.php');

/**
 * Player Stream Results
 */
require get_theme_file_path('/inc/display-player-cashgame-results.php');

/**
 * Player Event Results
 */
require get_theme_file_path('/inc/display-player-event-results.php');

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
 * tps_sync_player_cashgame_results + tps_delete_player_cashgame_results
 */
require get_theme_file_path('/inc/player-cashgame-sync.php');

/**
 * tps_sync_player_event_results + tps_delete_player_event_results
 */
require get_theme_file_path('/inc/player-event-sync.php');

/**
 * tps_get_platform_template_part
 */
require get_theme_file_path('/inc/platform-page-router.php');

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
      // Unparseable date — blank cell rather than showing raw text to visitors
      return '';
    }
  }
}; 