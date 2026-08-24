<?php
/**
 * Template for Portfolio Archive & Portfolio Page (100% Dynamic CMS Managed)
 */
get_header();

$portfolio_page = get_page_by_path('portfolio');
$page_id = $portfolio_page ? $portfolio_page->ID : get_the_ID();

// Page settings from ACF
$custom_filters = function_exists('get_field') ? get_field('portfolio_filters', $page_id) : false;
$orderby_opt    = function_exists('get_field') ? get_field('portfolio_orderby', $page_id) : 'date_asc';

$orderby = 'date';
$order   = 'ASC';
if ($orderby_opt === 'date_desc') {
    $orderby = 'date';
    $order   = 'DESC';
} elseif ($orderby_opt === 'title') {
    $orderby = 'title';
    $order   = 'ASC';
} elseif ($orderby_opt === 'rand') {
    $orderby = 'rand';
    $order   = 'ASC';
}
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
        <?php
        if (!empty($custom_filters)) :
          foreach ($custom_filters as $flt) :
            $flt_name = $flt['name'];
            $flt_slug = $flt['slug'];
            if (!str_starts_with($flt_slug, '.')) {
                $flt_slug = '.' . $flt_slug;
            }
        ?>
          <li data-filter="<?php echo esc_attr($flt_slug); ?>"><?php echo esc_html($flt_name); ?></li>
        <?php
          endforeach;
        else :
          // Default 3 standard categories
        ?>
          <li data-filter=".filter-app">App</li>
          <li data-filter=".filter-product">Card</li>
          <li data-filter=".filter-branding">Web</li>
        <?php endif; ?>
      </ul><!-- End Portfolio Filters -->

      <div class="row gy-4 isotope-container" data-aos="fade-up" data-aos-delay="200">
        <?php
        $pf_query = new WP_Query(array(
            'post_type'      => 'portfolio',
            'posts_per_page' => -1,
            'post_status'    => 'publish',
            'orderby'        => $orderby,
            'order'          => $order,
        ));

        if ($pf_query->have_posts()) :
          while ($pf_query->have_posts()) : $pf_query->the_post();
            $cat_filter = function_exists('get_field') ? (get_field('portfolio_category_filter') ?: 'filter-app') : 'filter-app';
            $thumb_url  = get_the_post_thumbnail_url(get_the_ID(), 'medium_large');
            $gallery_id = str_replace('filter-', 'portfolio-gallery-', $cat_filter);
        ?>
          <div class="col-lg-4 col-md-6 portfolio-item isotope-item <?php echo esc_attr($cat_filter); ?>">
            <?php if ($thumb_url) : ?>
              <img src="<?php echo esc_url($thumb_url); ?>" class="img-fluid" alt="<?php the_title(); ?>">
            <?php endif; ?>
            <div class="portfolio-info">
              <h4><?php the_title(); ?></h4>
              <p><?php echo wp_trim_words(get_the_excerpt(), 5, '...'); ?></p>
              <?php if ($thumb_url) : ?>
                <a href="<?php echo esc_url($thumb_url); ?>"
                   title="<?php the_title(); ?>"
                   data-gallery="<?php echo esc_attr($gallery_id); ?>"
                   class="glightbox preview-link">
                  <i class="bi bi-zoom-in"></i>
                </a>
              <?php endif; ?>
              <a href="<?php the_permalink(); ?>" title="More Details" class="details-link">
                <i class="bi bi-link-45deg"></i>
              </a>
            </div>
          </div><!-- End Portfolio Item -->
        <?php
          endwhile;
          wp_reset_postdata();
        endif;
        ?>
      </div><!-- End Portfolio Container -->

    </div>
  </div>
</section><!-- /Portfolio Section -->

<?php get_footer(); ?>
