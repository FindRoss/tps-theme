<?php 
// Custom search fetch endpoint 
function custom_search_endpoint() {
  register_rest_route('custom/v1', '/search/', array(
      'methods'  => 'GET',
      'callback' => 'custom_search_callback',
  ));
}

add_action('rest_api_init', 'custom_search_endpoint');
// Search callback
function custom_search_callback($data) {
  $results = array();
  $search_term = sanitize_text_field($data['search']);

  $args = array(
    's'              => $search_term,
    'post_type'      => array( 'player' ),
    'posts_per_page' => 18,
  );

  $query = new WP_Query($args);

  // Check if there are posts
  if ($query->have_posts()) {
    
    while ($query->have_posts()) {
        $query->the_post();

        // Get the title of the current post
        $post_title = get_the_title();

        // Check if the title contains the search term
        if (stripos($post_title, $search_term) !== false) {
            // Add the post to the matching posts array
            $results[] = array(
              'title' => get_the_title(),
              'link'  => get_the_permalink(),
              'image' => get_the_post_thumbnail_url(get_the_ID(), 'thumbnail')
            );
        }
    }
    wp_reset_postdata();
  } 

  return $results;
};