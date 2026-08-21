  <?php 
  /* 
  * Template Name: Platform
  */ 
  
  $current_page_id = get_the_ID();
  $parent_page_id = wp_get_post_parent_id($current_page_id);

  // Get the parent page ID whether I am on that page already or not
  $pillar_page_id = 0;
  if ($parent_page_id) $pillar_page_id = $parent_page_id;
  else $pillar_page_id = $current_page_id;

  $pillar_page_title = get_the_title($pillar_page_id);

  $platform_bar_data = tps_build_platform_bar_data($pillar_page_id, $current_page_id);
  $platform_results_url = $platform_bar_data['results_url']; // used by platform-overview.php
?>

<?php get_header(); ?>

  <?php require locate_template('template-parts/platform/platform-bar.php'); ?>

  <section class="platform-page-body">
    <div class="platform-page-body__inner">

      <h1 class="platform-h1"><?php the_title(); ?></h1>

      <?php
        $platform_template_part = tps_get_platform_template_part($current_page_id, $pillar_page_title);

        if ($platform_template_part) {
          require locate_template($platform_template_part);
        } else {
          echo '<div class="platform-row">';
            the_content();
          echo '</div>';
        }
      ?>
    </div>
  </section>

<?php get_footer(); ?>  