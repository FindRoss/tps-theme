  <?php $ep = get_field('episode', get_the_ID()); ?>
  
      
  <div class="result-card mt-3">
    <a class="tps-card-link" href="<?php the_permalink(); ?>">
      <div class="result-card__layout">
        <span class="result-card__number">#<?php echo $ep; ?></span>
        <h3 class="result-card__content tps-card__title"><?php the_content(); ?></h3>
      </div>
    </a>
  </div>