<?php
/**
 * Template for Portfolio Archive & Portfolio Page (100% Dynamic CMS Managed with rich fallbacks)
 */
get_header();
$asset_uri = get_template_directory_uri() . '/assets';

$portfolio_page = get_page_by_path('portfolio');
$page_id = $portfolio_page ? $portfolio_page->ID : get_the_ID();
?>

<!-- Page Title -->
<div class="page-title light-background">
  <div class="container d-lg-flex justify-content-between align-items-center">
    <h1 class="mb-2 mb-lg-0">Portfolio</h1>
    <nav class="breadcrumbs">
      <ol>
        <li><a href="<?php echo esc_url(home_url('/')); ?>">Home</a></li>
        <li class="current">Portfolio</li>
      </ol>
    </nav>
  </div>
</div><!-- End Page Title -->

<!-- Portfolio Section -->
<section id="portfolio" class="portfolio section">
  <div class="container">
    <div class="isotope-layout" data-default-filter="*" data-layout="masonry" data-sort="original-order">

      <!-- Dynamic Filter Categories -->
      <ul class="portfolio-filters isotope-filters" data-aos="fade-up" data-aos-delay="100">
        <li data-filter="*" class="filter-active">All</li>
        <li data-filter=".filter-app">App</li>
        <li data-filter=".filter-product">Card</li>
        <li data-filter=".filter-branding">Web</li>
      </ul><!-- End Portfolio Filters -->

      <div class="row gy-4 isotope-container" data-aos="fade-up" data-aos-delay="200">
        <?php
        $pf_query = new WP_Query(array(
            'post_type'      => 'portfolio',
            'posts_per_page' => -1,
            'post_status'    => 'publish',
            'orderby'        => 'date',
            'order'          => 'DESC',
        ));

        $default_ports = array(
            array('title' => 'App 1', 'cat' => 'filter-app', 'img' => $asset_uri . '/img/portfolio/portfolio-1.jpg', 'desc' => 'Lorem ipsum, dolor sit amet consectetur'),
            array('title' => 'Product 1', 'cat' => 'filter-product', 'img' => $asset_uri . '/img/portfolio/portfolio-2.jpg', 'desc' => 'Lorem ipsum, dolor sit amet consectetur'),
            array('title' => 'Branding 1', 'cat' => 'filter-branding', 'img' => $asset_uri . '/img/portfolio/portfolio-3.jpg', 'desc' => 'Lorem ipsum, dolor sit amet consectetur'),
            array('title' => 'App 2', 'cat' => 'filter-app', 'img' => $asset_uri . '/img/portfolio/portfolio-4.jpg', 'desc' => 'Lorem ipsum, dolor sit amet consectetur'),
            array('title' => 'Product 2', 'cat' => 'filter-product', 'img' => $asset_uri . '/img/portfolio/portfolio-5.jpg', 'desc' => 'Lorem ipsum, dolor sit amet consectetur'),
            array('title' => 'Branding 2', 'cat' => 'filter-branding', 'img' => $asset_uri . '/img/portfolio/portfolio-6.jpg', 'desc' => 'Lorem ipsum, dolor sit amet consectetur'),
            array('title' => 'App 3', 'cat' => 'filter-app', 'img' => $asset_uri . '/img/portfolio/portfolio-7.jpg', 'desc' => 'Lorem ipsum, dolor sit amet consectetur'),
            array('title' => 'Product 3', 'cat' => 'filter-product', 'img' => $asset_uri . '/img/portfolio/portfolio-8.jpg', 'desc' => 'Lorem ipsum, dolor sit amet consectetur'),
            array('title' => 'Branding 3', 'cat' => 'filter-branding', 'img' => $asset_uri . '/img/portfolio/portfolio-9.jpg', 'desc' => 'Lorem ipsum, dolor sit amet consectetur'),
        );

        $p_idx = 1;
        if ($pf_query->have_posts()) :
          while ($pf_query->have_posts()) : $pf_query->the_post();
            $cat_filter = function_exists('get_field') ? (get_field('portfolio_category_filter') ?: 'filter-app') : 'filter-app';
            $thumb_url  = get_the_post_thumbnail_url(get_the_ID(), 'medium_large');
            if (empty($thumb_url)) {
                $thumb_url = $asset_uri . '/img/portfolio/portfolio-' . (($p_idx % 9) + 1) . '.jpg';
            }
            $gallery_id = str_replace('filter-', 'portfolio-gallery-', $cat_filter);
        ?>
          <div class="col-lg-4 col-md-6 portfolio-item isotope-item <?php echo esc_attr($cat_filter); ?>">
            <img src="<?php echo esc_url($thumb_url); ?>" class="img-fluid" alt="<?php the_title(); ?>">
            <div class="portfolio-info">
              <h4><?php the_title(); ?></h4>
              <p><?php echo wp_trim_words(get_the_excerpt(), 5, '...'); ?></p>
              <a href="<?php echo esc_url($thumb_url); ?>"
                 title="<?php the_title(); ?>"
                 data-gallery="<?php echo esc_attr($gallery_id); ?>"
                 class="glightbox preview-link">
                <i class="bi bi-zoom-in"></i>
              </a>
              <a href="<?php the_permalink(); ?>" title="More Details" class="details-link"><i class="bi bi-link-45deg"></i></a>
            </div>
          </div>
        <?php
          $p_idx++;
          endwhile;
          wp_reset_postdata();
        else :
          foreach ($default_ports as $dp) :
        ?>
          <div class="col-lg-4 col-md-6 portfolio-item isotope-item <?php echo esc_attr($dp['cat']); ?>">
            <img src="<?php echo esc_url($dp['img']); ?>" class="img-fluid" alt="<?php echo esc_attr($dp['title']); ?>">
            <div class="portfolio-info">
              <h4><?php echo esc_html($dp['title']); ?></h4>
              <p><?php echo esc_html($dp['desc']); ?></p>
              <a href="<?php echo esc_url($dp['img']); ?>"
                 title="<?php echo esc_attr($dp['title']); ?>"
                 data-gallery="portfolio-gallery"
                 class="glightbox preview-link">
                <i class="bi bi-zoom-in"></i>
              </a>
              <a href="<?php echo esc_url(home_url('/portfolio/')); ?>" title="More Details" class="details-link"><i class="bi bi-link-45deg"></i></a>
            </div>
          </div>
        <?php endforeach; endif; ?>

      </div><!-- End Portfolio Container -->

    </div>
  </div>
</section><!-- /Portfolio Section -->

<?php get_footer(); ?>
