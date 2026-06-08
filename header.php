<!DOCTYPE html>
<!--[if lt IE 7]><html class="no-js lt-ie9 lt-ie8 lt-ie7" lang="en"> <![endif]-->
<!--[if IE 7]><html class="no-js lt-ie9 lt-ie8" lang="en"> <![endif]-->
<!--[if IE 8]><html class="no-js lt-ie9" lang="en"> <![endif]-->
<!--[if gt IE 8]><!--> <html class="no-js" <?php language_attributes(); ?>> <!--<![endif]-->
  <head>
    <script>
        if (localStorage.getItem('lightTheme') === 'true') {
          document.querySelector('html').classList.add('light-theme');
        };
    </script>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge,chrome=1">
    <title><?php wp_title('&laquo;', true, 'right'); ?></title>
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <link rel="pingback" href="<?php bloginfo('pingback_url'); ?>" />
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.2/css/all.min.css">
    <!-- Google tag (gtag.js) -->
    <?php if ( !is_user_logged_in() ) : ?> 
      <script async src="https://www.googletagmanager.com/gtag/js?id=G-CQX7Z411ZK"></script>
      <script>
        window.dataLayer = window.dataLayer || [];
        function gtag(){dataLayer.push(arguments);}
        gtag('js', new Date());

        gtag('config', 'G-CQX7Z411ZK');
      </script>
    <?php endif; ?>
    <?php wp_head(); ?>
</head>
<body <?php body_class(); ?>>

<header class="" style="background-color: #000; color: #fff;">
  <div class="container-xxl overflow-hidden"> 
    
    <nav class="navbar navbar-expand-xl navbar-dark flex-wrap py-1">
      <div class="col-4 col-sm-6 col-md-8 col-xl-4 d-flex align-items-center" style="min-height: 75px;">
        <!-- Mobile menu --> 
        <div class="d-xl-none">  
          <button class="navbar-toggler btn-sm me-2" type="button" data-bs-toggle="collapse" data-bs-target="#navbarNavAltMarkup" aria-controls="navbarNavAltMarkup" aria-expanded="false" aria-label="Toggle navigation">
            <span class="navbar-toggler-icon"></span>
          </button>
        </div>
        <!-- Logo -->
        <div class="d-flex justify-content-start align-items-center flex-fill">
          <a class="navbar-brand d-flex" href="/">
          
            <picture>

              <source 
              srcset="https://toppokerstreamers.com/wp-content/uploads/2024/10/top-poker-streamers-players-logo__65x65-png.avif" 
              media="(max-width: 767px)">
              
              <source 
              srcset="https://toppokerstreamers.com/wp-content/uploads/2024/12/TPS_logo-white-reupload__640x80-png.avif" 
              media="(min-width: 768px)">
              

              <!-- Fallback Image -->
              <img 
              class="logo" 
              src="https://toppokerstreamers.com/wp-content/uploads/2024/10/top-poker-streamers-players-logo__65x65-png.avif" 
              alt="Top Poker Streamers logo">
            </picture>

          </a>
        </div> 
      </div><!-- .col --> 

      <div class="col-xl-5 d-none d-xl-block">
        <!-- new menu -->
        <div class="d-none d-xl-block position-relative w-100">
          <?php 
            wp_nav_menu( array(
              'theme_location'  => 'primary',
              'depth'           => 2,
              'container'       => 'div',
              'container_class' => '',
              'container_id'    => '',
              'menu_class'      => 'navbar-nav desktop-menu',
              'fallback_cb'     => 'WP_Bootstrap_Navwalker::fallback',
              'walker'          => new WP_Bootstrap_Navwalker(),
            )); 
          ?>
        </div>
      </div>
   
      <!-- Search and Theme -->
      <div class="col-8 col-sm-6 col-md-4 col-xl-3 d-flex justify-content-end align-items-center">
        
        <!-- Search --> 
        <div class="me-2">
          <form class="form-inline my-2 my-xl-0 w-100" role="search" method="get" id="searchform" action="<?php echo esc_url( home_url( '/' ) ); ?>">
            <div class="input-group" id="searchForm">
              <input class="form-control mr-sm-2 rounded-start" type="search" placeholder="Search" aria-label="Search input" value="<?php the_search_query(); ?>" name="s" id="searchInput" type="text">
              <button id="searchsubmit" class="btn btn-dark text-white" value="Search" type="submit" id="searchBtn" aria-label="Search button">
                <svg class="search-svg" xmlns="http://www.w3.org/2000/svg" height="16" width="16" viewBox="0 0 512 512"><path d="M416 208c0 45.9-14.9 88.3-40 122.7L502.6 457.4c12.5 12.5 12.5 32.8 0 45.3s-32.8 12.5-45.3 0L330.7 376c-34.4 25.2-76.8 40-122.7 40C93.1 416 0 322.9 0 208S93.1 0 208 0S416 93.1 416 208zM208 352a144 144 0 1 0 0-288 144 144 0 1 0 0 288z"/></svg>
              </button>
            </div>
          </form>
        </div>
      
        <!-- Theme Dark/Light button -->
        <div>
          <button class="btn btn-dark" id="modeBtn" type="button" aria-label="theme">
            <script>
            const moonIcon = '<svg class="theme-svg moon" xmlns="http://www.w3.org/2000/svg" height="16" width="12" viewBox="0 0 384 512"><path d="M223.5 32C100 32 0 132.3 0 256S100 480 223.5 480c60.6 0 115.5-24.2 155.8-63.4c5-4.9 6.3-12.5 3.1-18.7s-10.1-9.7-17-8.5c-9.8 1.7-19.8 2.6-30.1 2.6c-96.9 0-175.5-78.8-175.5-176c0-65.8 36-123.1 89.3-153.3c6.1-3.5 9.2-10.5 7.7-17.3s-7.3-11.9-14.3-12.5c-6.3-.5-12.6-.8-19-.8z"/></svg>'; 
            const sunIcon = '<svg class="theme-svg sun" xmlns="http://www.w3.org/2000/svg" height="16" width="16" viewBox="0 0 512 512"><path d="M361.5 1.2c5 2.1 8.6 6.6 9.6 11.9L391 121l107.9 19.8c5.3 1 9.8 4.6 11.9 9.6s1.5 10.7-1.6 15.2L446.9 256l62.3 90.3c3.1 4.5 3.7 10.2 1.6 15.2s-6.6 8.6-11.9 9.6L391 391 371.1 498.9c-1 5.3-4.6 9.8-9.6 11.9s-10.7 1.5-15.2-1.6L256 446.9l-90.3 62.3c-4.5 3.1-10.2 3.7-15.2 1.6s-8.6-6.6-9.6-11.9L121 391 13.1 371.1c-5.3-1-9.8-4.6-11.9-9.6s-1.5-10.7 1.6-15.2L65.1 256 2.8 165.7c-3.1-4.5-3.7-10.2-1.6-15.2s6.6-8.6 11.9-9.6L121 121 140.9 13.1c1-5.3 4.6-9.8 9.6-11.9s10.7-1.5 15.2 1.6L256 65.1 346.3 2.8c4.5-3.1 10.2-3.7 15.2-1.6zM160 256a96 96 0 1 1 192 0 96 96 0 1 1 -192 0zm224 0a128 128 0 1 0 -256 0 128 128 0 1 0 256 0z"/></svg>';
            
            if (localStorage.getItem('lightTheme') === 'true') {
              document.write(moonIcon);
            } else {
              document.write(sunIcon);
            };
            </script> 
          </button>
        </div>

      </div><!-- .col--> 

      <!-- <div class="col-12 px-0 border-xl-top-none border-top border-bottom d-block d-xl-none"> -->
      <div class="col-12 px-0 d-block d-xl-none">
          <?php wp_nav_menu( array(
            'theme_location'  => 'primary',
            'depth'           => 2,
            'container'       => 'div',
            'container_class' => 'collapse navbar-collapse',
            'container_id'    => 'navbarNavAltMarkup',
            'menu_class'      => 'navbar-nav cus-navbar',
            'fallback_cb'     => 'WP_Bootstrap_Navwalker::fallback',
            'walker'          => new WP_Bootstrap_Navwalker(),
          )); ?>
      </div><!-- .col --> 
    </nav>

  </div><!-- .container --> 
</header><!-- .bg --> 
