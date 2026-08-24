<?php
/**
 * front-page.php - 100% Dynamic CMS Managed Homepage
 * All content rendered exclusively from CMS (ACF & WordPress Post Types)
 */
get_header();
$asset_uri = get_template_directory_uri() . '/assets';
$front_id  = get_the_ID();
?>

<!-- ============================================================
     1. HERO SECTION (Dynamic ACF Repeater)
============================================================ -->
<?php
$hero_show   = function_exists('get_field') ? get_field('hero_show', $front_id) : true;
if ($hero_show === null || $hero_show === '') $hero_show = true;
$hero_slides = function_exists('get_field') ? get_field('hero_slides', $front_id) : false;

// Robust Fallback slides if ACF repeater is empty
if (empty($hero_slides)) {
    $hero_slides = array(
        array(
            'image'       => array('url' => $asset_uri . '/img/hero-carousel/hero-carousel-1.jpg'),
            'title'       => 'Welcome to Sailor',
            'description' => 'Ut velit est quam dolor ad a aliquid qui aliquid. Sequi ea ut et est quaerat sequi nihil ut aliquam. Occaecati alias dolorem mollitia ut. Similique ea voluptatem.',
            'button_text' => 'Get Started',
            'button_link' => '#about'
        ),
        array(
            'image'       => array('url' => $asset_uri . '/img/hero-carousel/hero-carousel-2.jpg'),
            'title'       => 'At vero eos et accusamus',
            'description' => 'Nam libero tempore, cum soluta nobis est eligendi optio cumque nihil impedit quo minus id quod maxime placeat facere possimus, omnis voluptas assumenda est.',
            'button_text' => 'Our Services',
            'button_link' => '#services'
        ),
        array(
            'image'       => array('url' => $asset_uri . '/img/hero-carousel/hero-carousel-3.jpg'),
            'title'       => 'Temporibus autem quibusdam',
            'description' => 'Beatae vitae dicta sunt explicabo. Nemo enim ipsam voluptatem quia voluptas sit aspernatur aut odit aut fugit, sed quia consequuntur magni dolores eos.',
            'button_text' => 'Contact Us',
            'button_link' => '#contact'
        ),
    );
}

if ($hero_show && !empty($hero_slides)) :
?>
<section id="hero" class="hero section dark-background">
  <div id="hero-carousel" class="carousel slide carousel-fade" data-bs-ride="carousel" data-bs-interval="5000">

    <?php
    $i = 0;
    foreach ($hero_slides as $slide) :
      $active   = ($i === 0) ? 'active' : '';
      $img_url  = !empty($slide['image']['url']) ? $slide['image']['url'] : '';
      $title    = !empty($slide['title']) ? $slide['title'] : '';
      $desc     = !empty($slide['description']) ? $slide['description'] : '';
      $btn_text = !empty($slide['button_text']) ? $slide['button_text'] : '';
      $btn_link = !empty($slide['button_link']) ? $slide['button_link'] : '';
    ?>
      <div class="carousel-item <?php echo $active; ?>">
        <?php if ($img_url) : ?>
          <img src="<?php echo esc_url($img_url); ?>" alt="<?php echo esc_attr($title); ?>">
        <?php endif; ?>
        <div class="carousel-container">
          <?php if ($title) : ?><h2><?php echo esc_html($title); ?><br></h2><?php endif; ?>
          <?php if ($desc) : ?><p><?php echo esc_html($desc); ?></p><?php endif; ?>
          <?php if ($btn_link && $btn_text) : ?>
            <a href="<?php echo esc_url($btn_link); ?>" class="btn-get-started">
              <?php echo esc_html($btn_text); ?>
            </a>
          <?php endif; ?>
        </div>
      </div>
    <?php $i++; endforeach; ?>

    <?php if (count($hero_slides) > 1) : ?>
      <a class="carousel-control-prev" href="#hero-carousel" role="button" data-bs-slide="prev">
        <span class="carousel-control-prev-icon bi bi-chevron-left" aria-hidden="true"></span>
      </a>
      <a class="carousel-control-next" href="#hero-carousel" role="button" data-bs-slide="next">
        <span class="carousel-control-next-icon bi bi-chevron-right" aria-hidden="true"></span>
      </a>
      <ol class="carousel-indicators"></ol>
    <?php endif; ?>

  </div>
</section>
<?php endif; ?><!-- /Hero Section -->

<!-- ============================================================
     2. ABOUT SECTION (Dynamic ACF Fields)
============================================================ -->
<?php
$about_show     = function_exists('get_field') ? get_field('about_show', $front_id) : true;
$about_subtitle = function_exists('get_field') ? get_field('about_section_subtitle', $front_id) : '';
$about_title    = function_exists('get_field') ? get_field('about_section_title', $front_id) : '';
$about_left     = function_exists('get_field') ? get_field('about_left_text', $front_id) : '';
$about_right    = function_exists('get_field') ? get_field('about_right_text', $front_id) : '';
// About fallbacks
if (empty($about_title) && empty($about_left)) {
    $about_subtitle = 'About Us';
    $about_title    = 'Learn More About What We Do';
    $about_left     = '<p>Voluptatem dignissimos provident quasi corporis voluptates sit assumenda. Lorem ipsum dolor sit amet, consectetur adipiscing elit, sed do eiusmod tempor incididunt ut labore et dolore magna aliqua.</p><ul><li><i class="bi bi-check2-all"></i> <span>Ullamco laboris nisi ut aliquip ex ea commodo consequat.</span></li><li><i class="bi bi-check2-all"></i> <span>Duis aute irure dolor in reprehenderit in voluptate velit.</span></li><li><i class="bi bi-check2-all"></i> <span>Ullamco laboris nisi ut aliquip ex ea commodo consequat.</span></li></ul>';
    $about_right    = '<p>Ullamco laboris nisi ut aliquip ex ea commodo consequat. Duis aute irure dolor in reprehenderit in voluptate velit esse cillum dolore eu fugiat nulla pariatur. Excepteur sint occaecat cupidatat non proident, sunt in culpa qui officia deserunt mollit anim id est laborum.</p>';
    $about_btn_text = 'Read More';
    $about_btn_link = home_url('/about/');
}

if ($about_show && ($about_title || $about_left || $about_right)) :
?>
<section id="about" class="about section">
  <?php if ($about_subtitle || $about_title) : ?>
    <div class="container section-title" data-aos="fade-up">
      <?php if ($about_subtitle) : ?><h2><?php echo esc_html($about_subtitle); ?></h2><?php endif; ?>
      <?php if ($about_title) : ?><p><?php echo esc_html($about_title); ?><br></p><?php endif; ?>
    </div>
  <?php endif; ?>

  <div class="container">
    <div class="row gy-4">
      <div class="col-lg-6 content" data-aos="fade-up" data-aos-delay="100">
        <?php if (!empty($about_left)) : ?>
          <?php echo wp_kses_post($about_left); ?>
        <?php endif; ?>
      </div>

      <div class="col-lg-6" data-aos="fade-up" data-aos-delay="200">
        <?php if (!empty($about_right)) : ?>
          <?php echo wp_kses_post($about_right); ?>
        <?php endif; ?>

        <?php if (!empty($about_btn_link) && !empty($about_btn_text)) : ?>
          <a href="<?php echo esc_url($about_btn_link); ?>" class="read-more">
            <span><?php echo esc_html($about_btn_text); ?></span><i class="bi bi-arrow-right"></i>
          </a>
        <?php endif; ?>
      </div>
    </div>
  </div>
</section>
<?php endif; ?><!-- /About Section -->

<!-- ============================================================
     3. CLIENTS LOGOS SECTION (Dynamic ACF Repeater)
============================================================ -->
<?php
$clients_show = function_exists('get_field') ? get_field('clients_show', $front_id) : true;
if ($clients_show === null || $clients_show === '') $clients_show = true;
$clients_list = function_exists('get_field') ? get_field('clients_list', $front_id) : false;

if (empty($clients_list)) {
    $clients_list = array(
        array('image' => array('url' => $asset_uri . '/img/clients/client-1.png'), 'name' => 'Client 1'),
        array('image' => array('url' => $asset_uri . '/img/clients/client-2.png'), 'name' => 'Client 2'),
        array('image' => array('url' => $asset_uri . '/img/clients/client-3.png'), 'name' => 'Client 3'),
        array('image' => array('url' => $asset_uri . '/img/clients/client-4.png'), 'name' => 'Client 4'),
        array('image' => array('url' => $asset_uri . '/img/clients/client-5.png'), 'name' => 'Client 5'),
        array('image' => array('url' => $asset_uri . '/img/clients/client-6.png'), 'name' => 'Client 6'),
    );
}

if ($clients_show && !empty($clients_list)) :
?>
<section id="clients" class="clients section light-background">
  <div class="container" data-aos="fade-up">
    <div class="row gy-4">
      <?php
      foreach ($clients_list as $client) :
        $c_logo = !empty($client['logo']['url']) ? $client['logo']['url'] : '';
        $c_name = !empty($client['name']) ? $client['name'] : 'Client';
        $c_url  = !empty($client['url']) ? $client['url'] : '';
        if ($c_logo) :
      ?>
        <div class="col-xl-2 col-md-3 col-6 client-logo">
          <?php if ($c_url) : ?><a href="<?php echo esc_url($c_url); ?>" target="_blank" rel="noopener"><?php endif; ?>
            <img src="<?php echo esc_url($c_logo); ?>" class="img-fluid" alt="<?php echo esc_attr($c_name); ?>">
          <?php if ($c_url) : ?></a><?php endif; ?>
        </div>
      <?php endif; endforeach; ?>
    </div>
  </div>
</section>
<?php endif; ?><!-- /Clients Section -->

<!-- ============================================================
     4. SERVICES SECTION (Dynamic ACF Repeater)
============================================================ -->
<?php
$services_show     = function_exists('get_field') ? get_field('services_show', $front_id) : true;
$services_subtitle = function_exists('get_field') ? get_field('services_section_subtitle', $front_id) : 'Services';
$services_title    = function_exists('get_field') ? get_field('services_section_title', $front_id) : 'Check Our Services';
$service_cpt_query = new WP_Query(array('post_type' => 'service', 'posts_per_page' => 6, 'post_status' => 'publish', 'orderby' => 'menu_order date', 'order' => 'ASC'));
$services_list     = function_exists('get_field') ? get_field('services_list', $front_id) : false;

if ($services_show && ($service_cpt_query->have_posts() || !empty($services_list))) :
?>
<section id="services" class="services section">
  <?php if ($services_subtitle || $services_title) : ?>
    <div class="container section-title" data-aos="fade-up">
      <?php if ($services_subtitle) : ?><h2><?php echo esc_html($services_subtitle); ?></h2><?php endif; ?>
      <?php if ($services_title) : ?><p><?php echo esc_html($services_title); ?><br></p><?php endif; ?>
    </div>
  <?php endif; ?>

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


<!-- ============================================================
     5. CALL TO ACTION (CTA) SECTION (Dynamic ACF Fields)
============================================================ -->
<?php
$cta_show     = function_exists('get_field') ? get_field('cta_show', $front_id) : true;
$cta_title    = function_exists('get_field') ? get_field('cta_title', $front_id) : '';
$cta_desc     = function_exists('get_field') ? get_field('cta_description', $front_id) : '';
$cta_btn_text = function_exists('get_field') ? get_field('cta_btn_text', $front_id) : '';
$cta_btn_link = function_exists('get_field') ? get_field('cta_btn_link', $front_id) : '';

if ($cta_show && ($cta_title || $cta_desc)) :
?>
<section id="call-to-action" class="call-to-action section dark-background">
  <div class="container">
    <div class="row justify-content-center" data-aos="zoom-in" data-aos-delay="100">
      <div class="col-xl-10">
        <div class="text-center">
          <?php if ($cta_title) : ?><h3><?php echo esc_html($cta_title); ?></h3><?php endif; ?>
          <?php if ($cta_desc) : ?><p><?php echo wp_kses_post($cta_desc); ?></p><?php endif; ?>
          <?php if ($cta_btn_link && $cta_btn_text) : ?>
            <a class="cta-btn" href="<?php echo esc_url($cta_btn_link); ?>">
              <?php echo esc_html($cta_btn_text); ?>
            </a>
          <?php endif; ?>
        </div>
      </div>
    </div>
  </div>
</section>
<?php endif; ?><!-- /Call To Action -->

<!-- ============================================================
     6. PORTFOLIO SECTION (Dynamic CPT: portfolio)
============================================================ -->
<?php
$port_show     = function_exists('get_field') ? get_field('portfolio_show', $front_id) : true;
$port_subtitle = function_exists('get_field') ? (get_field('portfolio_section_subtitle', $front_id) ?: 'Portfolio') : 'Portfolio';
$port_title    = function_exists('get_field') ? (get_field('portfolio_section_title', $front_id) ?: 'Check Our Latest Work') : 'Check Our Latest Work';
$port_count    = function_exists('get_field') ? (int)(get_field('portfolio_count', $front_id) ?: 6) : 6;

if ($port_show !== false && $port_show !== '0' && $port_show !== 0) :
  $portfolio_query = new WP_Query(array(
      'post_type'      => 'portfolio',
      'posts_per_page' => $port_count,
      'post_status'    => 'publish',
  ));

  if ($portfolio_query->have_posts()) :
?>
<section id="portfolio" class="portfolio section">
  <div class="container section-title" data-aos="fade-up">
    <h2><?php echo esc_html($port_subtitle); ?></h2>
    <p><?php echo esc_html($port_title); ?><br></p>
  </div>

  <div class="container">
    <div class="isotope-layout" data-default-filter="*" data-layout="masonry" data-sort="original-order">
      
      <!-- Filter Categories -->
      <ul class="portfolio-filters isotope-filters" data-aos="fade-up" data-aos-delay="100">
        <li data-filter="*" class="filter-active">All</li>
        <li data-filter=".filter-app">App</li>
        <li data-filter=".filter-product">Card</li>
        <li data-filter=".filter-branding">Web</li>
      </ul>

      <div class="row gy-4 isotope-container" data-aos="fade-up" data-aos-delay="200">
        <?php
        while ($portfolio_query->have_posts()) : $portfolio_query->the_post();
          $cat_filter = function_exists('get_field') ? (get_field('portfolio_category_filter') ?: 'filter-app') : 'filter-app';
          $thumb_url  = get_the_post_thumbnail_url(get_the_ID(), 'medium_large');
        ?>
          <div class="col-lg-4 col-md-6 portfolio-item isotope-item <?php echo esc_attr($cat_filter); ?>">
            <?php if ($thumb_url) : ?>
              <img src="<?php echo esc_url($thumb_url); ?>" class="img-fluid" alt="<?php the_title(); ?>">
            <?php endif; ?>
            <div class="portfolio-info">
              <h4><?php the_title(); ?></h4>
              <p><?php echo wp_trim_words(get_the_excerpt(), 6); ?></p>
              <?php if ($thumb_url) : ?>
                <a href="<?php echo esc_url($thumb_url); ?>" title="<?php the_title(); ?>" data-gallery="portfolio-gallery" class="glightbox preview-link"><i class="bi bi-zoom-in"></i></a>
              <?php endif; ?>
              <a href="<?php the_permalink(); ?>" title="More Details" class="details-link"><i class="bi bi-link-45deg"></i></a>
            </div>
          </div>
        <?php endwhile; wp_reset_postdata(); ?>
      </div>
    </div>
  </div>
</section>
<?php endif; endif; ?><!-- /Portfolio Section -->

<!-- ============================================================
     7. TEAM SECTION (Dynamic CPT: team)
============================================================ -->
<?php
$team_show     = function_exists('get_field') ? get_field('team_show', $front_id) : true;
$team_subtitle = function_exists('get_field') ? (get_field('team_section_subtitle', $front_id) ?: 'Team') : 'Team';
$team_title    = function_exists('get_field') ? (get_field('team_section_title', $front_id) ?: 'Our Hardworking Team') : 'Our Hardworking Team';
$team_count    = function_exists('get_field') ? (int)(get_field('team_count', $front_id) ?: 4) : 4;

if ($team_show !== false && $team_show !== '0' && $team_show !== 0) :
  $team_query = new WP_Query(array(
      'post_type'      => 'team',
      'posts_per_page' => $team_count,
      'post_status'    => 'publish',
  ));

  if ($team_query->have_posts()) :
?>
<section id="team" class="team section">
  <div class="container section-title" data-aos="fade-up">
    <h2><?php echo esc_html($team_subtitle); ?></h2>
    <p><?php echo esc_html($team_title); ?><br></p>
  </div>

  <div class="container">
    <div class="row gy-4">
      <?php
      $delay = 100;
      while ($team_query->have_posts()) : $team_query->the_post();
        $role = function_exists('get_field') ? get_field('team_role') : '';
        $tw   = function_exists('get_field') ? get_field('team_twitter') : '';
        $fb   = function_exists('get_field') ? get_field('team_facebook') : '';
        $ig   = function_exists('get_field') ? get_field('team_instagram') : '';
        $li   = function_exists('get_field') ? get_field('team_linkedin') : '';
        $img  = get_the_post_thumbnail_url(get_the_ID(), 'medium');
        if (empty($img)) {
            $img = function_exists('sailor_get_avatar_placeholder') ? sailor_get_avatar_placeholder() : '';
        }
      ?>
        <div class="col-xl-3 col-md-6" data-aos="fade-up" data-aos-delay="<?php echo $delay; ?>">
          <div class="member">
            <div class="member-img">
              <?php if ($img) : ?>
                <img src="<?php echo esc_url($img); ?>" class="img-fluid" alt="<?php the_title(); ?>">
              <?php endif; ?>
              <div class="social">
                <?php if ($tw) : ?><a href="<?php echo esc_url($tw); ?>" target="_blank" rel="noopener"><i class="bi bi-twitter-x"></i></a><?php endif; ?>
                <?php if ($fb) : ?><a href="<?php echo esc_url($fb); ?>" target="_blank" rel="noopener"><i class="bi bi-facebook"></i></a><?php endif; ?>
                <?php if ($ig) : ?><a href="<?php echo esc_url($ig); ?>" target="_blank" rel="noopener"><i class="bi bi-instagram"></i></a><?php endif; ?>
                <?php if ($li) : ?><a href="<?php echo esc_url($li); ?>" target="_blank" rel="noopener"><i class="bi bi-linkedin"></i></a><?php endif; ?>
              </div>
            </div>

            <div class="member-info">
              <h4><?php the_title(); ?></h4>
              <?php if ($role) : ?><span><?php echo esc_html($role); ?></span><?php endif; ?>
              <div class="social-inline">
                <?php if ($tw) : ?><a href="<?php echo esc_url($tw); ?>" target="_blank" rel="noopener"><i class="bi bi-twitter-x"></i></a><?php endif; ?>
                <?php if ($fb) : ?><a href="<?php echo esc_url($fb); ?>" target="_blank" rel="noopener"><i class="bi bi-facebook"></i></a><?php endif; ?>
                <?php if ($ig) : ?><a href="<?php echo esc_url($ig); ?>" target="_blank" rel="noopener"><i class="bi bi-instagram"></i></a><?php endif; ?>
                <?php if ($li) : ?><a href="<?php echo esc_url($li); ?>" target="_blank" rel="noopener"><i class="bi bi-linkedin"></i></a><?php endif; ?>
              </div>
            </div>
          </div>
        </div>

      <?php $delay += 100; endwhile; wp_reset_postdata(); ?>
    </div>
  </div>
</section>
<?php endif; endif; ?><!-- /Team Section -->

<!-- ============================================================
     8. RECENT BLOG POSTS SECTION (100% Dynamic)
============================================================ -->
<?php
$blog_query = new WP_Query(array(
    'post_type'      => 'post',
    'posts_per_page' => 3,
    'post_status'    => 'publish',
));

if ($blog_query->have_posts()) :
?>
<section id="recent-posts" class="recent-posts section light-background">
  <div class="container section-title" data-aos="fade-up">
    <h2>Blog</h2>
    <p>Recent Blog Posts<br></p>
  </div>

  <div class="container">
    <div class="row gy-4">
      <?php
      $delay = 100;
      while ($blog_query->have_posts()) : $blog_query->the_post();
        $thumb = get_the_post_thumbnail_url(get_the_ID(), 'medium_large');
      ?>
        <div class="col-lg-4" data-aos="fade-up" data-aos-delay="<?php echo $delay; ?>">
          <article class="p-3 bg-white border rounded shadow-sm h-100 d-flex flex-column">
            <?php if ($thumb) : ?>
              <div class="post-img mb-3">
                <a href="<?php the_permalink(); ?>">
                  <img src="<?php echo esc_url($thumb); ?>" alt="<?php the_title(); ?>" class="img-fluid rounded w-100" style="height: 220px; object-fit: cover;">
                </a>
              </div>
            <?php endif; ?>

            <div class="meta-top text-muted small d-flex gap-2 mb-2">
              <span><i class="bi bi-clock"></i> <time datetime="<?php echo get_the_date('c'); ?>"><?php echo get_the_date(); ?></time></span>
              <span>•</span>
              <span><i class="bi bi-person"></i> <?php the_author(); ?></span>
            </div>

            <h3 class="title h5 mb-2">
              <a href="<?php the_permalink(); ?>" class="text-dark text-decoration-none">
                <?php the_title(); ?>
              </a>
            </h3>

            <p class="text-muted small mb-3 flex-grow-1">
              <?php echo wp_trim_words(get_the_excerpt(), 15); ?>
            </p>

            <a href="<?php the_permalink(); ?>" class="btn btn-outline-danger btn-sm align-self-start">Read More</a>
          </article>
        </div>
      <?php $delay += 100; endwhile; wp_reset_postdata(); ?>
    </div>

    <div class="text-center mt-4">
      <a href="<?php echo esc_url(get_permalink(get_option('page_for_posts')) ?: home_url('/blog/')); ?>" class="btn btn-danger px-4 py-2">
        View All Blog Posts
      </a>
    </div>
  </div>
</section>
<?php endif; ?><!-- /Recent Blog Posts -->

<?php get_footer(); ?>

