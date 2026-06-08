  <?php require locate_template('modal.php'); ?>
    <footer> 
      <div>
        <div class="container py-5">
         
          <div class="row">
            <div class="col-6 col-md mt-4">
              <h5 class="h6">Social</h5>
              <div class="d-flex flex-column">
                <!-- YouTube -->
                <div class="mt-2 mt-md-0 me-md-3">
                  <a href="https://www.youtube.com/@TopPokerStreamers" target="_blank" rel="nofollow"><span class="mb-2 d-flex align-items-center"><span class="me-1"><i class="fa-brands fa-youtube fa-2x"></i></span><span>YouTube</span></span></a>
                </div>
                <!-- Twitter -->
                <div class="mt-2 mt-md-0 me-md-3">
                  <a href="https://twitter.com/PokerStreamers" target="_blank" rel="nofollow"><span class="mb-2 d-flex align-items-center"><span class="me-1"><i class="fa-brands fa-square-x-twitter fa-2x"></i></i></span><span>X / Twitter</span></span></a>
                </div>
                <!-- Instagram -->
                <div class="mt-2 mt-md-0 me-md-3">
                  <a href="https://www.instagram.com/toppokerstreamers/" target="_blank" rel="nofollow"><span class="mb-2 d-flex align-items-center"><span class="me-1"><i class="fa-brands fa-instagram fa-2x"></i></span><span>Instagram</span></span></a>
                </div>
              </div><!-- .d-flex -->
            </div><!-- .col --> 

          
            
           
              <?php $footer_menu_one = wp_nav_menu( array(
                'echo'            => false, 
                'menu'            => 'footer-one',
                'depth'           => 1,
                'container'       => 'div',
                'container_class' => 'footer-list__container',
                'container_id'    => '',
                'menu_class'      => '',
                'fallback_cb'     => 'WP_Bootstrap_Navwalker::fallback',
                'walker'          => new WP_Bootstrap_Navwalker(),
                )); 
                
                if (!empty($footer_menu_one)) {
                  echo '<div class="col-6 col-md mt-4">';
                  echo '<h5 class="h6">Players</h5>'; 
                  echo $footer_menu_one; 
                  echo '</div>';
                } ?>
              
            
              <?php $footer_menu_two = wp_nav_menu( array(
                'echo'            => false, 
                'menu'            => 'footer-two',
                'depth'           => 1,
                'container'       => 'div',
                'container_class' => 'footer-list__container',
                'container_id'    => '',
                'menu_class'      => '',
                'fallback_cb'     => 'WP_Bootstrap_Navwalker::fallback',
                'walker'          => new WP_Bootstrap_Navwalker(),
                )); 
                
                if (!empty($footer_menu_two)) {
                  echo '<div class="col-6 col-md mt-4">';
                  echo '<h5 class="h6">Profiles</h5>'; 
                  echo $footer_menu_two; 
                  echo '</div>';
                } ?>


   
              <?php $footer_menu_three = wp_nav_menu( array(
                'echo'            => false, 
                'menu'            => 'footer-three',
                'depth'           => 1,
                'container'       => 'div',
                'container_class' => 'footer-list__container',
                'container_id'    => '',
                'menu_class'      => '',
                'fallback_cb'     => 'WP_Bootstrap_Navwalker::fallback',
                'walker'          => new WP_Bootstrap_Navwalker(),
                )); 
                
                if (!empty($footer_menu_three)) {
                  echo '<div class="col-6 col-md mt-4">';
                  echo '<h5 class="h6">Streamed Games</h5>'; 
                  echo $footer_menu_three; 
                  echo '</div>';
                } ?>
        

            
              <?php $footer_menu_four = wp_nav_menu( array(
                'echo'            => false, 
                'menu'            => 'footer-four',
                'depth'           => 1,
                'container'       => 'div',
                'container_class' => 'footer-list__container',
                'container_id'    => '',
                'menu_class'      => '',
                'fallback_cb'     => 'WP_Bootstrap_Navwalker::fallback',
                'walker'          => new WP_Bootstrap_Navwalker(),
                )); 
                
                if (!empty($footer_menu_four)) {
                  echo '<div class="col-6 col-md mt-4">';
                  echo '<h5 class="h6">Site</h5>'; 
                  echo $footer_menu_four; 
                  echo '</div>';
                } ?>
    
            
          </div><!-- .row --> 
        </div><!-- .container --> 
      </div><!-- .color --->
      <div class="footer-bottom"> 
        <div class="container">
          <div class="row">
            <div class="col-12 d-flex align-items-center py-3" style="font-size: 0.85em;">
              <img width="22" height="22" class="mr-2" src="https://toppokerstreamers.com/wp-content/uploads/2023/03/cropped-TPS_Icon__512x512.png" alt="top poker streamers">
              <div class="ms-2 me-1 chaser-footer-title">Top Poker Streamers</div>
              <div class="">&copy; <?php echo date('Y'); ?></div>
            </div><!-- .col --> 
          </div><!-- .row --> 
        </div><!-- .container --> 
      </div><!-- .color --->
    </footer>
    <?php wp_footer(); ?>
  </body>
</html>


