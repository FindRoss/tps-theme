<?php 
// Custom search fetch endpoint 
function search_two_endpoint() {
  register_rest_route('custom/v2', '/search/', array(
      'methods'  => 'GET',
      'callback' => 'search_two_callback',
  ));
}

add_action('rest_api_init', 'search_two_endpoint');
// Search callback
function search_two_callback($data) {
  $results = array();
  $search_term = sanitize_text_field($data['term']);
  $post_type = $data['type'];
  
  if ($post_type == 'all') {
    $post_type = array('player', 'post', 'cash_game', 'pokergo_event', 'triton_event');
  } else if ($post_type == 'tournament') { 
    $post_type = array('pokergo_event', 'triton_event');
  };
  
  $page = !empty($data['page']) ? absint($data['page']) : 1;
  $args = array(
    's'              => $search_term,
    'post_type'      => $post_type,
    'posts_per_page' => 20,
    'paged'          => $page
  );

  $query = new WP_Query($args);

  // Check if there are posts
  if ($query->have_posts()) {
    
    while ($query->have_posts()) {
        $query->the_post();

        // Get the title of the current post
        // $post_title = get_the_title();

        // Check if the title contains the search term
        // if (stripos($post_title, $search_term) !== false) {
            // Add the post to the matching posts array
            $results[] = array(
              'title'    => get_the_title(),
              'link'     => get_the_permalink(),
              'postType' => get_post_type(),
              'image'    => get_the_post_thumbnail_url(get_the_ID(), 'thumbnail'),
              'excerpt'  => get_the_excerpt()
            );
        // }
    }
    wp_reset_postdata();
  } 

  // Include pagination info
  return array(
    'results' => $results,
    'currentPage' => $page,
    'totalPages' => $query->max_num_pages, // Total number of pages.
    'totalPosts' => $query->found_posts,   // Total number of posts.
  );
};