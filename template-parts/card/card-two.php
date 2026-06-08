 <!-- .card --> 
<?php if (get_post_type() === 'post') { 
  $cat = get_the_category(); 
}; ?>

<div class="h-100">
  <a class="tps-card-link h-100" href="<?php the_permalink()?>">
    <div class="tps-card h-100 rounded">
      <?php if (has_post_thumbnail()): ?>
        <img src="<?php echo get_the_post_thumbnail_url(null, 'medium'); ?>" width="900" height="600" class="w-100 h-auto" alt="<?php the_title(); ?>">
      <?php endif; ?>
      <div class="card-body">
        <h3 class="tps-card__title"><?php the_title(); ?></h5>
        <?php if (!has_post_thumbnail()): ?>
          <div class="mt-2">
            <?php echo get_the_excerpt(); ?>
          </div>
        <?php endif; ?>
        <?php if (get_post_type() === 'post') { ?>
          <div class="tps-card-meta">
            <?php 
              echo get_the_date('M d');
              if (get_post_type() === 'post') {
                echo ' | ' . $cat[0]->name;
              }; ?>
          </div>
        <?php } ?>
      </div>
    </div>
  </a>
</div>