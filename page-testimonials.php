<?php
/**
 * Template Name: Testimonials Page
 * 100% Dynamic CMS Managed Testimonials Template
 */
get_header();
$page_id = get_the_ID();
$testimonials_query = new WP_Query(array('post_type' => 'testimonial', 'posts_per_page' => -1, 'post_status' => 'publish'));
?>

<!-- Page Title -->
<div class="page-title light-background">
  <div class="container d-lg-flex justify-content-between align-items-center">
    <h1 class="mb-2 mb-lg-0"><?php the_title(); ?></h1>
    <nav class="breadcrumbs">
      <ol>
        <li><a href="<?php echo esc_url(home_url('/')); ?>">Home</a></li>
        <li class="current"><?php the_title(); ?></li>
      </ol>
    </nav>
  </div>
</div>

<!-- Testimonials Section -->
<?php if ($testimonials_query->have_posts()) : ?>
<section id="testimonials" class="testimonials section">
  <div class="container">
    <div class="row gy-4">
      <?php
      $delay = 100;
      while ($testimonials_query->have_posts()) : $testimonials_query->the_post();
        $designation = function_exists('get_field') ? get_field('testimonial_designation') : '';
        $stars       = function_exists('get_field') ? (int)(get_field('testimonial_stars') ?: 5) : 5;
        $photo       = get_the_post_thumbnail_url(get_the_ID(), 'thumbnail');
        if (empty($photo)) {
            $photo = function_exists('sailor_get_avatar_placeholder') ? sailor_get_avatar_placeholder() : '';
        }
      ?>
        <div class="col-lg-6" data-aos="fade-up" data-aos-delay="<?php echo $delay; ?>">
          <div class="testimonial-item">
            <?php if ($photo) : ?>
              <img src="<?php echo esc_url($photo); ?>" class="testimonial-img" alt="<?php the_title(); ?>">
            <?php endif; ?>
            <h3><?php the_title(); ?></h3>

            <?php if ($designation) : ?><h4><?php echo esc_html($designation); ?></h4><?php endif; ?>
            <div class="stars">
              <?php for ($s = 1; $s <= $stars; $s++) : ?>
                <i class="bi bi-star-fill"></i>
              <?php endfor; ?>
            </div>
            <p>
              <i class="bi bi-quote quote-icon-left"></i>
              <span><?php echo get_the_content(); ?></span>
              <i class="bi bi-quote quote-icon-right"></i>
            </p>
          </div>
        </div>
      <?php $delay += 100; endwhile; wp_reset_postdata(); ?>
    </div>
  </div>
</section>
<?php endif; ?><!-- /Testimonials Section -->

<?php get_footer(); ?>
