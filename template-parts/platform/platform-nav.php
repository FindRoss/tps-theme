<?php

  // I already hav from single-cash_game.php

  // $term_id;
  $pillar_page_id    = $main_page; 
  $pillar_page_url   = $main_page_link;
  $pillar_page_title = get_the_title($pillar_page_id);
  $pillar_page_image = get_the_post_thumbnail_url($pillar_page_id, 'medium');

  // echo '<div> ID: ' . $pillar_page_id . '</div>';
  // echo '<div> URL: ' . $pillar_page_url . '</div>';
  // echo '<div> Title: ' . $pillar_page_title . '</div>';
  // echo '<div> Image: ' . $pillar_page_image . '</div>';


 // Get all children of the parent page
  $parent_page_children = get_pages(array(
    'child_of' => $pillar_page_id,
    'sort_column'  => 'menu_order', 
    'sort_order'    => 'ASC', 
  ));



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

  <div class="platform-menu-wrapper">
    <div class="container-xxl">
      
      <div class="platform-menu">

        <!-- Parent -->
        <span class="platform-menu__item platform-menu__parent">
          <a class="platform-menu__link  platform-menu__parent-link" href="<?php echo $pillar_page_url; ?>">
            <img src="<?php echo $pillar_page_image; ?>" width="48" height="32" class="rounded"/><?php echo $pillar_page_title; ?>
          </a>
        </span>

        <!-- Children -->
        <div class="platform-menu__children d-none d-sm-flex">
          <?php 
            foreach($platform_nav_items as $item) { ?>

              <span class="platform-menu__item">
                <a class="platform-menu__link platform-menu__child-link" href="<?php echo $item['permalink']; ?>"><?php echo $item['title']; ?></a>
              </span>

            <?php } ?>
        </div><!-- .platform-menu__children -->
        
        <div class="tps-dropdown d-block d-sm-none">
          <button class="tps-dropdown__button">
            <?php require locate_template('assets/svgs/dots.svg'); ?>
          </button>
          <ul class="tps-dropdown__menu">
            <?php foreach($platform_nav_items as $item) { ?>

              <div class="tps-dropdown__menu-item">
                <a href="<?php echo $item['permalink']; ?>"><?php echo $item['title']; ?></a>
              </div>

            <?php } ?>
          </ul>
        </div>

      </div><!-- .platform-menu --> 

    </div><!-- .container -->  
  </div><!-- .platform-menu-wrapper -->