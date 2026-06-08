<?php 
  $person_profile = 'https://toppokerstreamers.com/wp-content/uploads/2024/08/anonymous-person-profile__100x100-jpg.webp';
?>

<div class="h-100">
  <a class="tps-card-link h-100" href="<?php the_permalink()?>">
    <div class="tps-card p-2 p-lg-3 rounded h-100">
      <div class="tps-card__profile-media d-flex justify-content-center">
        <?php if (has_post_thumbnail()): ?>
          <img src="<?php echo get_the_post_thumbnail_url(null, 'thumbnail'); ?>" alt="<?php the_title(); ?>" class="rounded-circle" width="100" height="100" />
        <?php else : ?>
          <img src="<?php echo $person_profile; ?>" alt="<?php the_title(); ?>" class="rounded-circle" width="100" height="100" />
        <?php endif; ?>
      </div>
      <div class="pt-3">
        <h3 class="h6 text-center tps-card__title m-0 text-break"><?php the_title(); ?></h3>
      </div>
    </div>
  </a>
</div>