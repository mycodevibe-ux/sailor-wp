<?php
/**
 * Template Name: Services Page
 * 100% Dynamic CMS Managed Services Template
 */
get_header();
$page_id       = get_the_ID();
$front_page_id = get_option('page_on_front');

// Check Services List on this page or front page
$services_list = function_exists('get_field') ? (get_field('services_list', $page_id) ?: get_field('services_list', $front_page_id)) : false;
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

<!-- Services Section -->
<?php
$service_cpt_query = new WP_Query(array('post_type' => 'service', 'posts_per_page' => -1, 'post_status' => 'publish', 'orderby' => 'menu_order date', 'order' => 'ASC'));

if ($service_cpt_query->have_posts() || !empty($services_list)) :
?>
<section id="services" class="services section">
  <div class="container">
    <div class="row gy-4">
      <?php
      $delay = 100;
      if ($service_cpt_query->have_posts()) :
        while ($service_cpt_query->have_posts()) : $service_cpt_query->the_post();
          $icon_sel    = function_exists('get_field') ? get_field('service_icon') : 'bi bi-briefcase';
          $icon_custom = function_exists('get_field') ? get_field('service_icon_custom') : '';
          $icon_class  = ($icon_sel === 'custom' && !empty($icon_custom)) ? $icon_custom : ($icon_sel ?: 'bi bi-briefcase');
          $link_url    = function_exists('get_field') ? get_field('service_link') : '';
      ?>

        <div class="col-md-6" data-aos="fade-up" data-aos-delay="<?php echo $delay; ?>">
          <div class="service-item d-flex position-relative h-100">
            <i class="<?php echo esc_attr($icon_class); ?> icon flex-shrink-0"></i>
            <div>
              <h4 class="title">
                <?php if ($link_url) : ?>
                  <a href="<?php echo esc_url($link_url); ?>" class="stretched-link">
                    <?php the_title(); ?>
                  </a>
                <?php else : ?>
                  <?php the_title(); ?>
                <?php endif; ?>
              </h4>
              <?php if (get_the_content() || get_the_excerpt()) : ?>
                <p class="description"><?php echo get_the_content() ? wp_strip_all_tags(get_the_content()) : get_the_excerpt(); ?></p>
              <?php endif; ?>
            </div>
          </div>
        </div>
      <?php
        $delay += 100;
        endwhile;
        wp_reset_postdata();
      elseif (!empty($services_list)) :
        foreach ($services_list as $svc) :
          $icon_class = !empty($svc['icon']) ? $svc['icon'] : 'bi bi-briefcase';
          $link_url   = !empty($svc['link']) ? $svc['link'] : '';
      ?>
        <div class="col-md-6" data-aos="fade-up" data-aos-delay="<?php echo $delay; ?>">
          <div class="service-item d-flex position-relative h-100">
            <i class="<?php echo esc_attr($icon_class); ?> icon flex-shrink-0"></i>
            <div>
              <h4 class="title">
                <?php if ($link_url) : ?>
                  <a href="<?php echo esc_url($link_url); ?>" class="stretched-link">
                    <?php echo esc_html($svc['title']); ?>
                  </a>
                <?php else : ?>
                  <?php echo esc_html($svc['title']); ?>
                <?php endif; ?>
              </h4>
              <?php if (!empty($svc['description'])) : ?>
                <p class="description"><?php echo esc_html($svc['description']); ?></p>
              <?php endif; ?>
            </div>
          </div>
        </div>
      <?php $delay += 100; endforeach; endif; ?>
    </div>
  </div>
</section>
<?php endif; ?><!-- /Services Section -->


<!-- Features Section (Dynamic ACF Repeater: features_tabs) -->
<?php
$feat_show     = function_exists('get_field') ? get_field('features_show', $page_id) : true;
$feat_subtitle = function_exists('get_field') ? get_field('features_subtitle', $page_id) : '';
$feat_title    = function_exists('get_field') ? get_field('features_title', $page_id) : '';
$features_tabs = function_exists('get_field') ? get_field('features_tabs', $page_id) : false;

if ($feat_show && !empty($features_tabs)) :
?>
<section id="features" class="features section">
  <?php if ($feat_subtitle || $feat_title) : ?>
    <div class="container section-title" data-aos="fade-up">
      <?php if ($feat_subtitle) : ?><h2><?php echo esc_html($feat_subtitle); ?></h2><?php endif; ?>
      <?php if ($feat_title) : ?><p><?php echo esc_html($feat_title); ?><br></p><?php endif; ?>
    </div>
  <?php endif; ?>

  <div class="container" data-aos="fade-up" data-aos-delay="100">
    <div class="row">
      <div class="col-lg-3">
        <ul class="nav nav-tabs flex-column">
          <?php
          $t = 1;
          foreach ($features_tabs as $tab) :
            $active = ($t === 1) ? 'active show' : '';
          ?>
            <li class="nav-item">
              <a class="nav-link <?php echo $active; ?>" data-bs-toggle="tab" href="#features-tab-<?php echo $t; ?>">
                <?php echo esc_html($tab['tab_label']); ?>
              </a>
            </li>
          <?php $t++; endforeach; ?>
        </ul>
      </div>

      <div class="col-lg-9 mt-4 mt-lg-0">
        <div class="tab-content">
          <?php
          $t = 1;
          foreach ($features_tabs as $tab) :
            $active  = ($t === 1) ? 'active show' : '';
            $img_url = !empty($tab['image']['url']) ? $tab['image']['url'] : '';
          ?>
            <div class="tab-pane <?php echo $active; ?>" id="features-tab-<?php echo $t; ?>">
              <div class="row">
                <div class="<?php echo $img_url ? 'col-lg-8' : 'col-12'; ?> order-2 order-lg-1 mt-3 mt-lg-0">
                  <?php if (!empty($tab['heading'])) : ?><h3><?php echo esc_html($tab['heading']); ?></h3><?php endif; ?>
                  <?php if (!empty($tab['body'])) : ?><?php echo wp_kses_post($tab['body']); ?><?php endif; ?>
                </div>
                <?php if ($img_url) : ?>
                  <div class="col-lg-4 order-1 order-lg-2 text-center">
                    <img src="<?php echo esc_url($img_url); ?>" alt="<?php echo esc_attr($tab['tab_label']); ?>" class="img-fluid">
                  </div>
                <?php endif; ?>
              </div>
            </div>
          <?php $t++; endforeach; ?>
        </div>
      </div>
    </div>
  </div>
</section>
<?php endif; ?><!-- /Features Section -->

<?php get_footer(); ?>
