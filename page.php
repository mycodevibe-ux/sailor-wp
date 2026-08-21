<?php get_header(); ?>

<!-- Page Title Section -->
<div class="page-title dark-background" data-aos="fade">
  <div class="container position-relative">
    <h1><?php the_title(); ?></h1>
  </div>
</div>

<section class="section py-5">
  <div class="container">
    <?php if (have_posts()) : while (have_posts()) : the_post(); ?>
      <div class="entry-content">
        <?php the_content(); ?>
      </div>
    <?php endwhile; endif; ?>
  </div>
</section>

<?php get_footer(); ?>