<?php get_header(); ?>

<div class="page my-5">
  <div class="container">

    <h1><?php the_title(); ?></h1>
    
    <div>
      <?php the_content(); ?>
    </div>
   
    <?php $members = get_field('members'); ?>
    
    <?php if ($members) { ?>
      <h2 class="mt-4">Meet the team</h2>
      <div class="row member-row">

        <?php foreach( $members as $member ) {
          $name        = $member['name'];
          $role        = $member['role'];
          $description = $member['description']; 
          $picture     = $member['picture'];
          $picture_url = $picture['url'];
          $articles    = $member['articles']; 
          $twitch      = $member['twitch_link'];
          $website     = $member['website']; 
          $x           = $member['x']; 
          ?>

          <div class="col-12 member-col">
            <div class="card member-card border-0 shadow-sm d-flex">
              <div class="member-card__img-wrapper">
                <img class="rounded" src="<?php echo $picture_url; ?>" alt="<?php echo $name . $role . 'at TopPokerStreamers'; ?>">
              </div>
              <div class="card-body">
                <h3 class="h4 card-title"><?php echo $name; ?></h3>
                <h4 class="h6 card-subtitle mb-2"><?php echo $role; ?></h4>
                <div class="card-text"><?php echo $description; ?></div>
                <!-- If author articles -->
                <?php if ($articles) { ?>
                  <a href="<?php echo $articles; ?>">Read articles written by <?php echo $name; ?></a>
                <?php }; ?>

                <?php
                  $channels = [
                    'website' => $website,
                    'twitch'  => $twitch,
                    'x'       => $x
                  ];

                  $icons = [
                    'website' => 'fa-solid fa-globe',
                    'twitch'  => 'fa-brands fa-twitch',
                    'x'       => 'fa-brands fa-square-x-twitter'
                  ];

                  $nonEmptyChannels = array_filter($channels);

                  if (!empty($nonEmptyChannels)) { ?>
                  <div class="mt-3">
                    <?php foreach ($nonEmptyChannels as $key => $value) { ?>
                      <div class="d-inline-block me-1">
                        <a href="<?php echo $value; ?>" target="_blank"><i class="<?php echo $icons[$key]; ?> fa-2xl"></i></a>
                      </div>
                    <?php } ?>
                  </div>
                <?php }; ?>
  
              </div>
            </div>
          </div>
      
        <?php }; ?>
      </div><!-- .row --> 
    <?php }; ?>
    


    
  </div><!-- .container -->
</div>


<?php get_footer(); ?>


