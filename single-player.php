<?php get_header(); ?>

<?php 
  $profile_data = array();   

  // Profile
  $name          = get_field('name');
  if ($name) { $profile_data['Name'] = $name; }

  $nickname      = get_field('nickname');
  if ($nickname) { $profile_data['Nickname'] = $nickname; }

  $nationality   = get_field('nationality');
  if ($nationality) { $profile_data['Nationality'] = $nationality; }

  $date_of_birth = get_field('date_of_birth');
  if ($date_of_birth) {
    //explode the date to get month, day and year
    $birthDate = explode("/", $date_of_birth);
    //get age from date or birthdate
    $age = (date("md", date("U", mktime(0, 0, 0, $birthDate[0], $birthDate[1], $birthDate[2]))) > date("md")
      ? ((date("Y") - $birthDate[2]) - 1)
      : (date("Y") - $birthDate[2]));
      
      $profile_data['Age'] = $age;
  } 
  
  // Social
  $socials = array();
  $facebook      = get_field('facebook_url');
  if ($facebook) { $socials['Facebook'] = $facebook; }

  $instagram     = get_field('instagram_url');
  if ($instagram) { $socials['Instagram'] = $instagram; }

  $twitter       = get_field('twitter_url');
  if ($twitter) { $socials['Twitter'] = $twitter; }

  $youtube       = get_field('youtube_url');
  if ($youtube) { $socials['YouTube'] = $youtube; }

  $twitch        = get_field('twitch_url');
  if ($twitch) { $socials['Twitch'] = $twitch; }

  $hendonmob     = get_field('hendonmob_url'); 
  if ($hendonmob) { $socials['Hendonmob'] = $hendonmob; }

  if (!empty($socials)) {
    $profile_data['Socials'] = $socials;
  }

  // Streams
  $streams_on = get_the_terms(get_the_ID(), 'streams');
  if (!empty($streams_on)) {
    $profile_data['Streams on'] = $streams_on;
  }

  $profile_type = get_the_terms(get_the_ID(), 'profile_type');
  if (!empty($profile_type)) {
    $profile_data['Profile type'] = $profile_type;
  }

  $youtube_id = get_field('youtube_id');
?>

<div class="post mt-4 pb-5">
  <div class="container"> 
    <?php if ( have_posts() ) : while ( have_posts() ) : the_post(); ?>  

      <div class="row">
        <div class="col-12 col-lg-8">
          <h1><?php the_title(); ?></h1>
        
          <?php if (has_post_thumbnail()) {  ?>
            <img src="<?php echo get_the_post_thumbnail_url(get_the_ID(), 'medium'); ?>" alt="<?php the_title(); ?>" width="900" height="600" class="mt-3 rounded img-border">
          <?php }; ?>

          <!-- Table -->
          <?php if(!empty($profile_data)) { ?>
            <div class="profile-table__wrapper mt-4">
              <table class="profile-table">
                <tbody class="profile-table__body">
                  <?php foreach($profile_data as $title => $value) {
                    if($title == "Socials") { ?>
                      <tr>
                        <td><?php echo $title; ?></td>
                        <td>
                          <?php foreach($socials as $so_title => $so_link) : ?>
                            <div><a href="<?php echo $so_link; ?>" target="_blank"><?php echo $so_title ?> <i class="fa-solid fa-arrow-up-right-from-square"></i></a></div>
                          <?php endforeach; ?>
                        </td>
                      </tr>
                    <?php } else if ($title == "Streams on") { ?>
                      <tr>
                        <td>Streams on</td>
                        <td>
                          <?php foreach($value as $stream) { ?>
                            <?php
                              $main_page = get_field('main_page', $stream);

                              if (isset($main_page[0])) {
                                $main_page_id = $main_page[0];
                                $main_page_permalink = get_the_permalink($main_page[0]); ?>
                                <div><a href="<?php echo $main_page_permalink; ?>"><?php echo $stream->name; ?></a></div>
                                <?php } else { ?>
                                <div><?php echo $stream->name; ?></div>
                              <?php } 

                          }; ?>
                        </td>
                      </tr>
                    <?php } else if ($title == "Profile type") {  ?>
                      <tr>
                        <td>Type</td>
                        <td>
                          <?php foreach($value as $type) { ?>
                            <?php
                              $profile_link = get_term_link($type->term_id, 'profile_type');
                              $profile_term_name = substr($type->name, 0, -1);
                              if ($type->name == 'Coaches') {
                                $profile_term_name = 'Coach';
                              }
                            ?>
                            <!-- Use of Substr below removes the 's' or final character from 'players' and 'streamers' -->
                            <div><a href="<?php echo $profile_link; ?>"><?php echo $profile_term_name; ?></a></div>
                          <?php }; ?>
                        </td>
                      </tr>
                    <?php  } else { ?>
                      <tr>
                        <td><?php echo $title; ?></td>
                        <td><?php echo $value; ?></td>
                      </tr>
                    <?php }
                  } ?>
                </tbody>
              </table>
            </div>
          <?php }; ?>

          <div class="mt-4">
            <?php the_content(); ?>
          </div>
        </div><!-- .col -->

        <!-- SIDEBAR -->
        <div class="col-12 col-lg-4">
          <div class="sidebar-column d-flex flex-column">
            <?php require locate_template('sidebar.php') ?>
          </div>
        </div><!-- .col --> 
      </div><!-- .row --> 

      <?php endwhile; ?>
    <?php endif; ?>

    <?php $streams = array( 
        array(
          'heading'   => 'Hustler Casino Live Results',
          'post_type' => 'hcl_stream',
          'id'        => 61
        ),
        array(
          'heading'   => 'Bally Live Poker Results', 
          'post_type' => 'bally_stream',
          'id'        => 91
        ),
        array(
          'heading'   => 'Champions Poker Live Results', 
          'post_type' => 'cpl_stream',
          'id'        => 121
        ),
        array(
          'heading'   => 'Poker at the Lodge Results', 
          'post_type' => 'poker_lodge_stream', 
          'id'        => 79
        )
      );

      foreach ($streams as $stream) {
        $apps = profile_stream_apps($stream['id']); 

        if (!empty($apps)) : ?>

        <section class="mt-5">
          <?php generateSectionHeadingHTML(array(
              'title' => $stream['heading']
            )); 
    
          displayPlayerStreamResults($apps, $stream['id']);  ?>
        </section>
        <?php endif; 
      } ?>
    
      <!-- Player NEWS -->
      <?php $player_news_query = new WP_Query(array(
          'post_type'      => array('post', 'triton_event'),
          'posts_per_page' => 6,
          'meta_query'     => array(
            array(
                'key'     => 'player',
                'value'   => '"' . get_the_ID() . '"',
                'compare' => 'LIKE'
            )
          ) 
        ));
      ?>
      <?php if ($player_news_query->have_posts()) : ?>
        <div class="mt-5">
          <?php generateSectionHeadingHTML(array(
            'title' => get_the_title() . ' News'
          )); ?>
          <div class="row" id="player-news-row">
            <?php while ($player_news_query->have_posts()) : $player_news_query->the_post(); ?>
              
              <div class="col-12 col-sm-6 col-md-4 mt-3 player-news-col" data-post-id="<?php echo get_the_ID(); ?>">
                <?php require locate_template('template-parts/card/card-article.php') ?>
              </div>

            <?php endwhile; ?>
            <?php wp_reset_postdata(); ?>
          </div>
          <div class="d-flex justify-content-center d-none mt-4" id="loadMorePlayerNewsContainer">
            <button class="btn btn-lg btn-dark text-white" id="loadMorePlayerNews">More <?php the_title(); ?> News <i class="fa-solid fa-rotate-right"></i></button>
          </div>
        </div>
      <?php endif; ?>
   
  </div><!-- .container --> 
</div><!-- .post --> 

 <!-- if (false && $youtube_id) {
  <!-- <script type="text/javascript"> 
    function handleNumbers(num) {
      let finalNum = undefined;

      if (num < 999) return num;

      if (num > 999 && num < 999999) {
        console.log('why not here?')
        finalNum = Math.round(num / 1000) + 'K';
      }

      if (num > 999999) {
        finalNum = Math.round(num / 1000000) + 'M';
      }

      return finalNum;
    }

    fetch('https://youtube.googleapis.com/youtube/v3/channels?key=YOUTUBE_API_KEY>&id=<?php echo $youtube_id; ?>&part=statistics,snippet,id')
      .then(response => response.json())
      .then(data => {
        if (!data.items) return;

        const {
          subscriberCount,
          viewCount,
          videoCount
        } = data.items[0].statistics;

        const {
          publishedAt
        } = data.items[0].snippet;

        const id = data.items[0].id;

        const table = document.querySelector('.profile-table__body');

        let trSub = document.createElement('tr');
        let tdSubOne = document.createElement('td');
        let tdSubTwo = document.createElement('td');

        let tdSubOneContent = document.createTextNode('YouTube subscribers');
        let tdSubTwoContent = document.createTextNode(handleNumbers(Number(subscriberCount)));

        tdSubOne.appendChild(tdSubOneContent);
        tdSubTwo.appendChild(tdSubTwoContent);

        trSub.appendChild(tdSubOne);
        trSub.appendChild(tdSubTwo);

        table.appendChild(trSub);

        let trView = document.createElement('tr');
        let tdViewOne = document.createElement('td');
        let tdViewTwo = document.createElement('td');

        console.log('View Count: ', handleNumbers(Number(viewCount)));
        let tdViewOneContent = document.createTextNode('YouTube views');
        let tdViewTwoContent = document.createTextNode(handleNumbers(Number(viewCount)));

        tdViewOne.appendChild(tdViewOneContent);
        tdViewTwo.appendChild(tdViewTwoContent);

        trView.appendChild(tdViewOne);
        trView.appendChild(tdViewTwo);

        table.appendChild(trView);

        let trVid = document.createElement('tr');
        let tdVidOne = document.createElement('td');
        let tdVidTwo = document.createElement('td');

        let tdVidOneContent = document.createTextNode('YouTube videos');
        let tdVidTwoContent = document.createTextNode(handleNumbers(Number(videoCount)));

        tdVidOne.appendChild(tdVidOneContent);
        tdVidTwo.appendChild(tdVidTwoContent);

        trVid.appendChild(tdVidOne);
        trVid.appendChild(tdVidTwo);

        table.appendChild(trVid);
      });
  </script>
  } -->

<?php get_footer(); ?>