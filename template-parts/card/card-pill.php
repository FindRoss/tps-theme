<div class="mt-2 flex-grow-1">
  <a class="tps-card-link d-block h-100" href="<?php the_permalink()?>">
    <div class="tps-card d-flex h-100 rounded">
      <div class="tps-card__media d-flex justify-content-center align-items-center" style="flex-basis: 32%">
        <img class="border rounded-circle" src="<?php echo get_the_post_thumbnail_url(null, 'thumbnail'); ?>" alt="">
      </div>
      <div class="ps-1 pe-2 d-flex flex-column justify-content-center" style="flex-basis: 68%">
        <span class="text-uppercase tps-card__cat"><?php echo get_post_type();?></span>
        <h4 class="h6 tps-card__title" style="font-weight: 300;"><?php the_title(); ?></h4>
      </div>
    </div>
  </a>
</div>