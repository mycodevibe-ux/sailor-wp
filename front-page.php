<?php
/**
 * front-page.php - Complete, Beautiful, Fully Populated Sailor Homepage
 * Renders rich dynamic CMS sections with full demo fallbacks & image support
 */
get_header();
$asset_uri = get_template_directory_uri() . '/assets';
$front_id  = get_the_ID();
?>

<!-- ============================================================
     1. HERO SECTION (Carousel)
============================================================ -->
<?php
$hero_show   = function_exists('get_field') ? get_field('hero_show', $front_id) : true;
$hero_slides = function_exists('get_field') ? get_field('hero_slides', $front_id) : false;

if (empty($hero_slides) || !is_array($hero_slides) || count($hero_slides) === 0) {
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
?>
<section id="hero" class="hero section dark-background">
  <div id="hero-carousel" class="carousel slide carousel-fade" data-bs-ride="carousel" data-bs-interval="5000">

    <?php
    $i = 0;
    foreach ($hero_slides as $slide) :
      $active   = ($i === 0) ? 'active' : '';
      $img_url  = !empty($slide['image']['url']) ? $slide['image']['url'] : ($asset_uri . '/img/hero-carousel/hero-carousel-' . (($i % 3) + 1) . '.jpg');
      $title    = !empty($slide['title']) ? $slide['title'] : 'Welcome to Sailor';
      $desc     = !empty($slide['description']) ? $slide['description'] : 'Lorem ipsum dolor sit amet, consectetur adipiscing elit, sed do eiusmod tempor incididunt ut labore et dolore magna aliqua.';
      $btn_text = !empty($slide['button_text']) ? $slide['button_text'] : 'Get Started';
      $btn_link = !empty($slide['button_link']) ? $slide['button_link'] : '#about';
    ?>
      <div class="carousel-item <?php echo $active; ?>">
        <img src="<?php echo esc_url($img_url); ?>" alt="<?php echo esc_attr($title); ?>">
        <div class="carousel-container">
          <h2><?php echo esc_html($title); ?><br></h2>
          <p><?php echo esc_html($desc); ?></p>
          <a href="<?php echo esc_url($btn_link); ?>" class="btn-get-started"><?php echo esc_html($btn_text); ?></a>
        </div>
      </div>
    <?php $i++; endforeach; ?>

    <a class="carousel-control-prev" href="#hero-carousel" role="button" data-bs-slide="prev">
      <span class="carousel-control-prev-icon bi bi-chevron-left" aria-hidden="true"></span>
    </a>
    <a class="carousel-control-next" href="#hero-carousel" role="button" data-bs-slide="next">
      <span class="carousel-control-next-icon bi bi-chevron-right" aria-hidden="true"></span>
    </a>
    <ol class="carousel-indicators"></ol>

  </div>
</section><!-- /Hero Section -->

<!-- ============================================================
     2. ABOUT SECTION
============================================================ -->
<?php
$about_subtitle = function_exists('get_field') ? get_field('about_section_subtitle', $front_id) : '';
$about_title    = function_exists('get_field') ? get_field('about_section_title', $front_id) : '';
$about_left     = function_exists('get_field') ? get_field('about_left_text', $front_id) : '';
$about_right    = function_exists('get_field') ? get_field('about_right_text', $front_id) : '';

if (empty($about_title) || strlen($about_title) < 4) $about_title = 'About Us';
if (empty($about_subtitle)) $about_subtitle = 'About';
if (empty($about_left) || strlen($about_left) < 10) {
    $about_left = '<p>Lorem ipsum dolor sit amet, consectetur adipiscing elit, sed do eiusmod tempor incididunt ut labore et dolore magna aliqua.</p><ul><li><i class="bi bi-check2-all"></i> <span>Ullamco laboris nisi ut aliquip ex ea commodo consequat.</span></li><li><i class="bi bi-check2-all"></i> <span>Duis aute irure dolor in reprehenderit in voluptate velit.</span></li><li><i class="bi bi-check2-all"></i> <span>Ullamco laboris nisi ut aliquip ex ea commodo consequat.</span></li></ul>';
}
if (empty($about_right) || strlen($about_right) < 10) {
    $about_right = '<p>Ullamco laboris nisi ut aliquip ex ea commodo consequat. Duis aute irure dolor in reprehenderit in voluptate velit esse cillum dolore eu fugiat nulla pariatur. Excepteur sint occaecat cupidatat non proident, sunt in culpa qui officia deserunt mollit anim id est laborum.</p>';
}
?>
<section id="about" class="about section">
  <div class="container section-title" data-aos="fade-up">
    <h2><?php echo esc_html($about_subtitle); ?></h2>
    <p><?php echo esc_html($about_title); ?><br></p>
  </div>

  <div class="container">
    <div class="row gy-4">
      <div class="col-lg-6 content" data-aos="fade-up" data-aos-delay="100">
        <?php echo wp_kses_post($about_left); ?>
      </div>

      <div class="col-lg-6" data-aos="fade-up" data-aos-delay="200">
        <?php echo wp_kses_post($about_right); ?>
        <a href="<?php echo esc_url(home_url('/about/')); ?>" class="read-more">
          <span>Read More</span><i class="bi bi-arrow-right"></i>
        </a>
      </div>
    </div>
  </div>
</section><!-- /About Section -->

<!-- ============================================================
     3. CLIENTS LOGOS SECTION
============================================================ -->
<section id="clients" class="clients section light-background">
  <div class="container" data-aos="fade-up">
    <div class="row gy-4">
      <?php for ($c = 1; $c <= 6; $c++) : ?>
        <div class="col-xl-2 col-md-3 col-6 client-logo">
          <img src="<?php echo esc_url($asset_uri . '/img/clients/client-' . $c . '.png'); ?>" class="img-fluid" alt="Client <?php echo $c; ?>">
        </div>
      <?php endfor; ?>
    </div>
  </div>
</section><!-- /Clients Section -->

<!-- ============================================================
     4. SERVICES SECTION
============================================================ -->
<?php
$service_cpt_query = new WP_Query(array('post_type' => 'service', 'posts_per_page' => 6, 'post_status' => 'publish', 'orderby' => 'menu_order date', 'order' => 'ASC'));
?>
<section id="services" class="services section">
  <div class="container section-title" data-aos="fade-up">
    <h2>Services</h2>
    <p>Check Our Services<br></p>
  </div>

  <div class="container">
    <div class="row gy-4">
      <?php
      $default_services = array(
          array('icon' => 'bi bi-briefcase', 'title' => 'Dolor Sitema', 'desc' => 'Minim veniam, quis nostrud exercitation ullamco laboris nisi ut aliquip ex ea commodo consequat tarad limino ata'),
          array('icon' => 'bi bi-card-checklist', 'title' => 'Sed ut perspiciatis', 'desc' => 'Duis aute irure dolor in reprehenderit in voluptate velit esse cillum dolore eu fugiat nulla pariatur'),
          array('icon' => 'bi bi-bar-chart', 'title' => 'Magni Dolores', 'desc' => 'Excepteur sint occaecat cupidatat non proident, sunt in culpa qui officia deserunt mollit anim id est laborum'),
          array('icon' => 'bi bi-binoculars', 'title' => 'Nemo Enim', 'desc' => 'At vero eos et accusamus et iusto odio dignissimos ducimus qui blanditiis praesentium voluptatum deleniti atque'),
          array('icon' => 'bi bi-brightness-high', 'title' => 'Eiusmod Tempor', 'desc' => 'Et harum quidem rerum facilis est et expedita distinctio. Nam libero tempore, cum soluta nobis est eligendi'),
          array('icon' => 'bi bi-calendar-week', 'title' => 'Ullamco Laboris', 'desc' => 'Quis autem vel eum iure reprehenderit qui in ea voluptate velit esse quam nihil molestiae consequatur')
      );

      $delay = 100;
      if ($service_cpt_query->have_posts()) :
        while ($service_cpt_query->have_posts()) : $service_cpt_query->the_post();
          $icon_sel = function_exists('get_field') ? get_field('service_icon') : 'bi bi-briefcase';
          $icon_class = $icon_sel ?: 'bi bi-briefcase';
      ?>
        <div class="col-md-6" data-aos="fade-up" data-aos-delay="<?php echo $delay; ?>">
          <div class="service-item d-flex position-relative h-100">
            <i class="<?php echo esc_attr($icon_class); ?> icon flex-shrink-0"></i>
            <div>
              <h4 class="title"><a href="<?php echo esc_url(home_url('/services/')); ?>" class="stretched-link"><?php the_title(); ?></a></h4>
              <p class="description"><?php echo get_the_content() ? wp_strip_all_tags(get_the_content()) : get_the_excerpt(); ?></p>
            </div>
          </div>
        </div>
      <?php $delay += 100; endwhile; wp_reset_postdata(); else :
        foreach ($default_services as $svc) :
      ?>
        <div class="col-md-6" data-aos="fade-up" data-aos-delay="<?php echo $delay; ?>">
          <div class="service-item d-flex position-relative h-100">
            <i class="<?php echo esc_attr($svc['icon']); ?> icon flex-shrink-0"></i>
            <div>
              <h4 class="title"><a href="<?php echo esc_url(home_url('/services/')); ?>" class="stretched-link"><?php echo esc_html($svc['title']); ?></a></h4>
              <p class="description"><?php echo esc_html($svc['desc']); ?></p>
            </div>
          </div>
        </div>
      <?php $delay += 100; endforeach; endif; ?>
    </div>
  </div>
</section><!-- /Services Section -->

<!-- ============================================================
     5. CALL TO ACTION SECTION
============================================================ -->
<section id="call-to-action" class="call-to-action section dark-background">
  <div class="container">
    <div class="row justify-content-center" data-aos="zoom-in" data-aos-delay="100">
      <div class="col-xl-10">
        <div class="text-center">
          <h3>Call To Action</h3>
          <p>Duis aute irure dolor in reprehenderit in voluptate velit esse cillum dolore eu fugiat nulla pariatur. Excepteur sint occaecat cupidatat non proident, sunt in culpa qui officia deserunt mollit anim id est laborum.</p>
          <a class="cta-btn" href="<?php echo esc_url(home_url('/contact/')); ?>">Call To Action</a>
        </div>
      </div>
    </div>
  </div>
</section><!-- /Call To Action -->

<!-- ============================================================
     6. PORTFOLIO SECTION
============================================================ -->
<?php
$portfolio_query = new WP_Query(array('post_type' => 'portfolio', 'posts_per_page' => 6, 'post_status' => 'publish'));
?>
<section id="portfolio" class="portfolio section">
  <div class="container section-title" data-aos="fade-up">
    <h2>Portfolio</h2>
    <p>Check Our Latest Work<br></p>
  </div>

  <div class="container">
    <div class="isotope-layout" data-default-filter="*" data-layout="masonry" data-sort="original-order">
      
      <ul class="portfolio-filters isotope-filters" data-aos="fade-up" data-aos-delay="100">
        <li data-filter="*" class="filter-active">All</li>
        <li data-filter=".filter-app">App</li>
        <li data-filter=".filter-product">Card</li>
        <li data-filter=".filter-branding">Web</li>
      </ul>

      <div class="row gy-4 isotope-container" data-aos="fade-up" data-aos-delay="200">
        <?php
        $default_ports = array(
            array('title' => 'App 1', 'cat' => 'filter-app', 'img' => $asset_uri . '/img/portfolio/portfolio-1.jpg'),
            array('title' => 'Product 1', 'cat' => 'filter-product', 'img' => $asset_uri . '/img/portfolio/portfolio-2.jpg'),
            array('title' => 'Branding 1', 'cat' => 'filter-branding', 'img' => $asset_uri . '/img/portfolio/portfolio-3.jpg'),
            array('title' => 'App 2', 'cat' => 'filter-app', 'img' => $asset_uri . '/img/portfolio/portfolio-4.jpg'),
            array('title' => 'Product 2', 'cat' => 'filter-product', 'img' => $asset_uri . '/img/portfolio/portfolio-5.jpg'),
            array('title' => 'Branding 2', 'cat' => 'filter-branding', 'img' => $asset_uri . '/img/portfolio/portfolio-6.jpg'),
        );

        if ($portfolio_query->have_posts()) :
          while ($portfolio_query->have_posts()) : $portfolio_query->the_post();
            $cat_filter = function_exists('get_field') ? (get_field('portfolio_category_filter') ?: 'filter-app') : 'filter-app';
            $thumb_url  = get_the_post_thumbnail_url(get_the_ID(), 'medium_large');
            if (empty($thumb_url)) $thumb_url = $asset_uri . '/img/portfolio/portfolio-1.jpg';
        ?>
          <div class="col-lg-4 col-md-6 portfolio-item isotope-item <?php echo esc_attr($cat_filter); ?>">
            <img src="<?php echo esc_url($thumb_url); ?>" class="img-fluid" alt="<?php the_title(); ?>">
            <div class="portfolio-info">
              <h4><?php the_title(); ?></h4>
              <p><?php echo wp_trim_words(get_the_excerpt(), 6); ?></p>
              <a href="<?php echo esc_url($thumb_url); ?>" title="<?php the_title(); ?>" data-gallery="portfolio-gallery" class="glightbox preview-link"><i class="bi bi-zoom-in"></i></a>
              <a href="<?php the_permalink(); ?>" title="More Details" class="details-link"><i class="bi bi-link-45deg"></i></a>
            </div>
          </div>
        <?php endwhile; wp_reset_postdata(); else :
          foreach ($default_ports as $p) :
        ?>
          <div class="col-lg-4 col-md-6 portfolio-item isotope-item <?php echo esc_attr($p['cat']); ?>">
            <img src="<?php echo esc_url($p['img']); ?>" class="img-fluid" alt="<?php echo esc_attr($p['title']); ?>">
            <div class="portfolio-info">
              <h4><?php echo esc_html($p['title']); ?></h4>
              <p>Lorem ipsum dolor sit amet</p>
              <a href="<?php echo esc_url($p['img']); ?>" title="<?php echo esc_attr($p['title']); ?>" data-gallery="portfolio-gallery" class="glightbox preview-link"><i class="bi bi-zoom-in"></i></a>
              <a href="<?php echo esc_url(home_url('/portfolio/')); ?>" title="More Details" class="details-link"><i class="bi bi-link-45deg"></i></a>
            </div>
          </div>
        <?php endforeach; endif; ?>
      </div>
    </div>
  </div>
</section><!-- /Portfolio Section -->

<!-- ============================================================
     7. TEAM SECTION
============================================================ -->
<?php
$team_query = new WP_Query(array('post_type' => 'team', 'posts_per_page' => 4, 'post_status' => 'publish'));
?>
<section id="team" class="team section">
  <div class="container section-title" data-aos="fade-up">
    <h2>Team</h2>
    <p>Our Hardworking Team<br></p>
  </div>

  <div class="container">
    <div class="row gy-4">
      <?php
      $default_team = array(
          array('name' => 'Walter White', 'role' => 'Chief Executive Officer', 'img' => $asset_uri . '/img/team/team-1.jpg'),
          array('name' => 'Sarah Jhonson', 'role' => 'Product Manager', 'img' => $asset_uri . '/img/team/team-2.jpg'),
          array('name' => 'William Anderson', 'role' => 'CTO', 'img' => $asset_uri . '/img/team/team-3.jpg'),
          array('name' => 'Amanda Jepson', 'role' => 'Accountant', 'img' => $asset_uri . '/img/team/team-4.jpg'),
      );

      $delay = 100;
      if ($team_query->have_posts()) :
        while ($team_query->have_posts()) : $team_query->the_post();
          $role = function_exists('get_field') ? get_field('team_role') : '';
          $img  = get_the_post_thumbnail_url(get_the_ID(), 'medium');
          if (empty($img)) $img = $asset_uri . '/img/team/team-1.jpg';
      ?>
        <div class="col-xl-3 col-md-6" data-aos="fade-up" data-aos-delay="<?php echo $delay; ?>">
          <div class="member">
            <div class="member-img">
              <img src="<?php echo esc_url($img); ?>" class="img-fluid" alt="<?php the_title(); ?>">
              <div class="social">
                <a href="#"><i class="bi bi-twitter-x"></i></a>
                <a href="#"><i class="bi bi-facebook"></i></a>
                <a href="#"><i class="bi bi-instagram"></i></a>
                <a href="#"><i class="bi bi-linkedin"></i></a>
              </div>
            </div>
            <div class="member-info">
              <h4><?php the_title(); ?></h4>
              <span><?php echo esc_html($role ?: 'Team Member'); ?></span>
            </div>
          </div>
        </div>
      <?php $delay += 100; endwhile; wp_reset_postdata(); else :
        foreach ($default_team as $tm) :
      ?>
        <div class="col-xl-3 col-md-6" data-aos="fade-up" data-aos-delay="<?php echo $delay; ?>">
          <div class="member">
            <div class="member-img">
              <img src="<?php echo esc_url($tm['img']); ?>" class="img-fluid" alt="<?php echo esc_attr($tm['name']); ?>">
              <div class="social">
                <a href="#"><i class="bi bi-twitter-x"></i></a>
                <a href="#"><i class="bi bi-facebook"></i></a>
                <a href="#"><i class="bi bi-instagram"></i></a>
                <a href="#"><i class="bi bi-linkedin"></i></a>
              </div>
            </div>
            <div class="member-info">
              <h4><?php echo esc_html($tm['name']); ?></h4>
              <span><?php echo esc_html($tm['role']); ?></span>
            </div>
          </div>
        </div>
      <?php $delay += 100; endforeach; endif; ?>
    </div>
  </div>
</section><!-- /Team Section -->

<?php get_footer(); ?>
