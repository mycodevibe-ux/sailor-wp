<?php
/**
 * Template for Portfolio CPT single project page
 */
get_header();
$asset_uri = get_template_directory_uri() . '/assets';
?>

<!-- Page Title -->
<div class="page-title light-background">
  <div class="container d-lg-flex justify-content-between align-items-center">
    <h1 class="mb-2 mb-lg-0"><?php the_title(); ?></h1>
    <nav class="breadcrumbs">
      <ol>
        <li><a href="<?php echo esc_url(home_url('/')); ?>">Home</a></li>
        <li><a href="<?php echo esc_url(home_url('/portfolio')); ?>">Portfolio</a></li>
        <li class="current"><?php the_title(); ?></li>
      </ol>
    </nav>
  </div>
</div>

<!-- Portfolio Details Section -->
<section class="portfolio-details section">
  <div class="container" data-aos="fade-up" data-aos-delay="100">
    <?php if (have_posts()) : while (have_posts()) : the_post(); ?>
    <div class="row gy-4">

      <!-- Portfolio Gallery / Main Image -->
      <div class="col-lg-8">
        <?php
        $gallery = function_exists('get_field') ? get_field('portfolio_gallery') : array();
        if ($gallery) :
        ?>
          <div class="portfolio-details-slider swiper init-swiper">
            <script type="application/json" class="swiper-config">
              { "loop": true, "speed": 600, "autoplay": { "delay": 5000 },
                "slidesPerView": "auto", "pagination": { "el": ".swiper-pagination", "type": "bullets", "clickable": true } }
            </script>
            <div class="swiper-wrapper align-items-center">
              <?php foreach ($gallery as $img) : ?>
                <div class="swiper-slide">
                  <img src="<?php echo esc_url($img['url']); ?>" alt="<?php echo esc_attr($img['alt']); ?>">
                </div>
              <?php endforeach; ?>
            </div>
            <div class="swiper-pagination"></div>
          </div>
        <?php elseif (has_post_thumbnail()) : ?>
          <img src="<?php the_post_thumbnail_url('large'); ?>" class="img-fluid rounded" alt="<?php the_title(); ?>">
        <?php endif; ?>

      </div>

      <!-- Portfolio Info Sidebar -->
      <div class="col-lg-4">
        <div class="portfolio-info" data-aos="fade-up" data-aos-delay="200">
          <h3>Project information</h3>
          <ul>
            <li><strong>Category:</strong>
              <?php
              $terms = get_the_terms(get_the_ID(), 'portfolio_category');
              if ($terms && !is_wp_error($terms)) {
                echo esc_html(implode(', ', wp_list_pluck($terms, 'name')));
              } elseif (function_exists('get_field') && get_field('portfolio_category_filter')) {
                echo esc_html(str_replace('filter-', '', get_field('portfolio_category_filter')));
              } else {
                echo 'App';
              }
              ?>
            </li>
            <?php if (function_exists('get_field') && get_field('portfolio_client')) : ?>
              <li><strong>Client:</strong> <?php echo esc_html(get_field('portfolio_client')); ?></li>
            <?php endif; ?>
            <?php if (function_exists('get_field') && get_field('portfolio_date')) : ?>
              <li><strong>Project date:</strong> <?php echo esc_html(get_field('portfolio_date')); ?></li>
            <?php endif; ?>
            <?php if (function_exists('get_field') && get_field('portfolio_url')) : ?>
              <li><strong>Project URL:</strong>
                <a href="<?php echo esc_url(get_field('portfolio_url')); ?>" target="_blank">
                  <?php echo esc_url(get_field('portfolio_url')); ?>
                </a>
              </li>
            <?php endif; ?>
          </ul>
        </div>

        <div class="portfolio-description" data-aos="fade-up" data-aos-delay="300">
          <h2><?php the_title(); ?></h2>
          <?php the_content(); ?>
        </div>
      </div>

    </div>
    <?php endwhile; endif; ?>
  </div>
</section><!-- /Portfolio Details Section -->

<?php get_footer(); ?>
