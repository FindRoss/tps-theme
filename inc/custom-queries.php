<?php 

function tps_custom_queries($query) {
  // Triton series query
  if (!is_admin() && is_tax('triton_series') && $query->is_main_query()) {
    $query->set('orderby', 'meta_value_num');
    $query->set('order', 'ASC');
    $query->set('meta_key', 'event');
  } 
}
add_action('pre_get_posts', 'tps_custom_queries');

