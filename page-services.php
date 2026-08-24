<?php
/**
 * Template Name: Services Page
 * 100% Dynamic CMS Managed Services Template with rich fallbacks
 */
get_header();
$page_id   = get_the_ID();
$asset_uri = get_template_directory_uri() . '/assets';
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
<section id="services" class="services section">
  <div class="container">
    <div class="row gy-4">
      <?php
      $service_cpt_query = new WP_Query(array('post_type' => 'service', 'posts_per_page' => 12, 'post_status' => 'publish', 'orderby' => 'menu_order date', 'order' => 'ASC'));
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
              <h4 class="title"><?php the_title(); ?></h4>
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
              <h4 class="title"><?php echo esc_html($svc['title']); ?></h4>
              <p class="description"><?php echo esc_html($svc['desc']); ?></p>
            </div>
          </div>
        </div>
      <?php $delay += 100; endforeach; endif; ?>
    </div>
  </div>
</section>

<!-- Features Section -->
<section id="features" class="features section light-background">
  <div class="container section-title" data-aos="fade-up">
    <h2>Features</h2>
    <p>Check Our Features<br></p>
  </div>

  <div class="container">
    <div class="row">
      <div class="col-lg-3">
        <ul class="nav nav-tabs flex-column">
          <li class="nav-item"><a class="nav-link active show" data-bs-toggle="tab" href="#features-tab-1">Modi sit est</a></li>
          <li class="nav-item"><a class="nav-link" data-bs-toggle="tab" href="#features-tab-2">Unde praesentium</a></li>
          <li class="nav-item"><a class="nav-link" data-bs-toggle="tab" href="#features-tab-3">Pariatur explicabo</a></li>
          <li class="nav-item"><a class="nav-link" data-bs-toggle="tab" href="#features-tab-4">Nostrum qui quasi</a></li>
        </ul>
      </div>
      <div class="col-lg-9 mt-4 mt-lg-0">
        <div class="tab-content">
          <div class="tab-pane active show" id="features-tab-1">
            <div class="row">
              <div class="col-lg-8 details order-2 order-lg-1">
                <h3>Voluptatem dignissimos provident quasi corporis voluptates sit assumenda.</h3>
                <p class="fst-italic">Lorem ipsum dolor sit amet, consectetur adipiscing elit, sed do eiusmod tempor incididunt ut labore et dolore magna aliqua.</p>
                <p>Ullamco laboris nisi ut aliquip ex ea commodo consequat. Duis aute irure dolor in reprehenderit in voluptate velit esse cillum dolore eu fugiat nulla pariatur.</p>
              </div>
              <div class="col-lg-4 text-center order-1 order-lg-2">
                <img src="<?php echo esc_url($asset_uri . '/img/tabs/tab-1.png'); ?>" alt="Features" class="img-fluid">
              </div>
            </div>
          </div>
          <div class="tab-pane" id="features-tab-2">
            <div class="row">
              <div class="col-lg-8 details order-2 order-lg-1">
                <h3>Et blanditiis nemo veritatis excepturi</h3>
                <p class="fst-italic">Qui laudantium consequatur laborum sit qui ad sapiente dila parde sonata raqer a videna mareta paulona marka.</p>
                <p>Ea ipsum voluptatem consequatur quis est. Illum error ullam omnis quia et reiciendis sunt sunt est. Non dolore tempora ut et facilis.</p>
              </div>
              <div class="col-lg-4 text-center order-1 order-lg-2">
                <img src="<?php echo esc_url($asset_uri . '/img/tabs/tab-2.png'); ?>" alt="Features" class="img-fluid">
              </div>
            </div>
          </div>
          <div class="tab-pane" id="features-tab-3">
            <div class="row">
              <div class="col-lg-8 details order-2 order-lg-1">
                <h3>Impedit facilis occaecati odio neque aperiam sit</h3>
                <p class="fst-italic">Eos voluptatibus quo. Odio similique illum id quidem non enim fuga. Qui natus non sunt dicta dolor et.</p>
                <p>Nostrum quibusdam inventore voluptatem consequatur adipisci. Velit inventore voluptas id aut et temporibus incidunt.</p>
              </div>
              <div class="col-lg-4 text-center order-1 order-lg-2">
                <img src="<?php echo esc_url($asset_uri . '/img/tabs/tab-3.png'); ?>" alt="Features" class="img-fluid">
              </div>
            </div>
          </div>
          <div class="tab-pane" id="features-tab-4">
            <div class="row">
              <div class="col-lg-8 details order-2 order-lg-1">
                <h3>Fuga repudiandae temporibus voluptatem perferendis</h3>
                <p class="fst-italic">Totam aperiam accusamus. Repellat consequuntur iure voluptas iure porro quis delectus</p>
                <p>Eaque consequuntur consequuntur libero expedita in voluptas. Nostrum ipsam necessitatibus aliquam fugiat debitis quis velit.</p>
              </div>
              <div class="col-lg-4 text-center order-1 order-lg-2">
                <img src="<?php echo esc_url($asset_uri . '/img/tabs/tab-4.png'); ?>" alt="Features" class="img-fluid">
              </div>
            </div>
          </div>
        </div>
      </div>
    </div>
  </div>
</section>

<?php get_footer(); ?>
