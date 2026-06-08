<div class="mt-3">
  <a class="tps-card-link" href="<?php the_permalink()?>">
    <div class="tps-card tps-card--article-long">
      <div class="tps-card__media">
        <img src="<?php echo get_the_post_thumbnail_url(null, 'medium'); ?>" alt="<?php the_title(); ?>" width="600" height="400" />
      </div>
      <div class="tps-card__body">
        <h3 class="tps-card__title"><?php the_title(); ?></h3>
      </div>
    </div>
  </a>
</div>