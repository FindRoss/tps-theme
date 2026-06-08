<div class="platform-menu-wrapper">
  <div class="container-xxl">
    <div class="platform-menu">

      <!-- Parent -->
      <span class="platform-menu__item platform-menu__parent">
        <a class="platform-menu__link  platform-menu__parent-link" href="<?php echo site_url() . '/hustler-casino-live/'; ?>">
          <img src="https://toppokerstreamers.com/wp-content/uploads/2023/08/hustler-casino-live-small__900x600-600x400.jpg" alt="Hustler Casino Live logo" width="48" height="32" class="rounded"/>
          Hustler Casino Live
        </a>
      </span>

      <!-- Children -->
      <div class="platform-menu__children d-none d-sm-flex">         

        <span class="platform-menu__item">
          <a class="platform-menu__link platform-menu__child-link" href="<?php echo site_url() . '/triton-poker/players/'; ?>">Players</a>
        </span>
        <span class="platform-menu__item">
          <a class="platform-menu__link platform-menu__child-link" href="<?php echo site_url() . '/triton-poker/news/'; ?>">News</a>
        </span>
        <span class="platform-menu__item">
          <a class="platform-menu__link platform-menu__child-link"  href="<?php echo site_url() . '/triton-poker/results/'; ?>">Results</a>
        </span>

      </div><!-- .platform-menu__children -->
      
      <div class="tps-dropdown d-block d-sm-none">
        <button class="tps-dropdown__button">
          <?php require locate_template('assets/svgs/dots.svg'); ?>
        </button>
        <ul class="tps-dropdown__menu">

          <div class="tps-dropdown__menu-item">
            <a class="" href="<?php echo site_url() . '/triton-poker/players/'; ?>">Players</a>
          </div>
          <div class="tps-dropdown__menu-item">
            <a class="" href="<?php echo site_url() . '/triton-poker/news/'; ?>">News</a>
          </div>
          <div class="tps-dropdown__menu-item">
            <a class="" href="<?php echo site_url() . '/triton-poker/results/'; ?>">Results</a>
          </div>

        </ul>
      </div>

    </div><!-- .platform-menu --> 
  </div><!-- .container -->  
</div><!-- .platform-menu-wrapper --> 
