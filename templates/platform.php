  <?php 
  /* 
  * Template Name: Platform
  */ 
  // Parent pages
  // Hustler Casino Live Results - 3922
  // Bally Live Poker Streams - 10776
  // Poker at the Lodge - 10956

  // Get the current page ID
  $current_page_id = get_the_ID();
  $current_page_url = get_permalink(get_the_ID());

  // Get the parent page ID
  $parent_page_id = wp_get_post_parent_id($current_page_id);

  // Get the parent page ID whether I am on that page already or not
  $pillar_page_id = 0;
  if ($parent_page_id) $pillar_page_id = $parent_page_id;
  else $pillar_page_id = $current_page_id;

  // Get all children of the parent page
  $parent_page_children = get_pages(array(
    'child_of' => $pillar_page_id,
    'sort_column'  => 'menu_order', 
    'sort_order'    => 'ASC', 
  ));

  $pillar_page_image = get_the_post_thumbnail_url($pillar_page_id, 'medium'); 
  $pillar_page_url = get_the_permalink($pillar_page_id);
  $pillar_page_title = get_the_title($pillar_page_id);

  $platform_nav_items = array();
  foreach($parent_page_children as $child) {
    $title = get_post_field('post_name', $child->ID);
    $platform_nav_items[] = array(
      'title'     => $title,
      'id'        => $child->ID,
      'permalink' => get_permalink($child->ID)
    );
  };
?>

<?php get_header(); ?>

  <div class="platform-menu-wrapper">
    <div class="container-xxl">
      
      <div class="platform-menu">

        <?php 
          $active_status = '';
          if ($current_page_url == $pillar_page_url) $active_status = 'active'; ?>

        <!-- Parent -->
        <span class="platform-menu__item platform-menu__parent">
          <a class="platform-menu__link  platform-menu__parent-link <?php echo $active_status; ?>" href="<?php echo $pillar_page_url; ?>">
            <img src="<?php echo $pillar_page_image; ?>" width="48" height="32" class="rounded"/><?php echo $pillar_page_title; ?>
          </a>
        </span>

        <!-- Children -->
        <div class="platform-menu__children d-none d-sm-flex">
          <?php 
            foreach($platform_nav_items as $item) { 
            $active_status = '';
            if ($current_page_url == $item['permalink']) $active_status = 'active'; ?>

            <span class="platform-menu__item">
              <a class="platform-menu__link platform-menu__child-link <?php echo $active_status; ?>" href="<?php echo $item['permalink']; ?>"><?php echo $item['title']; ?></a>
            </span>

            <?php } ?>
        </div><!-- .platform-menu__children -->
        
        <div class="tps-dropdown d-block d-sm-none">
          <button class="tps-dropdown__button">
            <?php require locate_template('assets/svgs/dots.svg'); ?>
          </button>
          <ul class="tps-dropdown__menu">
            <?php 
              foreach($platform_nav_items as $item) { 
              $active_status = '';
              if ($current_page_url == $item['permalink']) $active_status = 'active'; ?>

              <div class="tps-dropdown__menu-item">
                <a class="<?php echo $active_status; ?>" href="<?php echo $item['permalink']; ?>"><?php echo $item['title']; ?></a>
              </div>


            <?php } ?>
          </ul>
        </div>

      </div><!-- .platform-menu --> 

    </div><!-- .container -->  
  </div><!-- .platform-menu-wrapper --> 

  <section class="mt-4">
    <div class="container">  
      <h1 class="h3 m-0"><?php the_title(); ?></h1>
    </div>
  </section>

  <section class="mt-4 pb-5">
    <div class="container">

      <?php 
        
        // Remove hyphens and convert to Camel Case with spaces
        $current_post_slug_name = get_post_field('post_name', $current_page_id);
        $modified_post_slug_name = ucwords(str_replace('-', ' ', $current_post_slug_name));

        if ($current_post_slug_name === 'news') {
          require locate_template('template-parts/platform/platform-news.php');
        } else if ($current_post_slug_name === 'players' || $current_post_slug_name === 'streamers') {
          require locate_template('template-parts/platform/platform-players.php');
        } else if ($current_post_slug_name === 'results') {
          require locate_template('template-parts/platform/platform-results.php');
        } else if (($pillar_page_title === 'Triton Poker' && $current_post_slug_name === 'series')) {
          require locate_template('template-parts/platform/platform-triton-series.php');
        } else if (!wp_get_post_parent_id($current_page_id)) { 
          require locate_template('template-parts/platform/platform-overview.php');
        } else {
          echo '<div class="platform-row">';
            the_content();
          echo '</div>';
        };
      ?>
    </div>
  </section>

  




<?php get_footer(); ?>  