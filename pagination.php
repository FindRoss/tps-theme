<div class="custom-pagination py-3">
  <?php     
  echo paginate_links( array(
    'prev_text' => __( '<', 'textdomain' ),
    'next_text' => __( '>', 'textdomain' ),
    'total'     => $query->max_num_pages,
    'current'   => max( 1, get_query_var('paged') ),
    )
  );
  ?>
</div><!-- .custom-pagination --> 