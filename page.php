<?php get_header(); ?>

<?php if ( have_posts() ) : while ( have_posts() ) : the_post(); ?>
<div class="page pb-5 flex flex-column flex-grow">
  
  <section class="py-5">
    <div class="container">  
      <h1><?php the_title(); ?></h1>
    </div>
  </section>

  <div class="container">
    <div class="row justify-content-center">
      <div class="col-12">
        <div class="mt-4"><?php the_content(); ?></div>
      </div>
    </div>
  </div><!-- .container -->
</div><!-- .page -->

<?php endwhile; endif; ?>
<?php wp_reset_postdata(); ?>


<?php get_footer(); ?>