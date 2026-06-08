 <!-- .card --> 
<?php if (get_post_type() === 'post') { 
  $cat = get_the_category(); 
}; ?>
<div class="white-card py-3 mt-3">
  <a class="tps-card-link" href="<?php the_permalink()?>">
    <div class="">
      <div class="">
        <h3 class="tps-card__title mb-0"><?php the_title(); ?></h5>
        <div class="white-card__meta">
          <?php echo get_the_date('M d'); ?> 
          <?php if (get_post_type() === 'post') {
            echo ' | ' . $cat[0]->name;
          } ?>
        </div>
      </div>
    </div>
  </a>
</div>