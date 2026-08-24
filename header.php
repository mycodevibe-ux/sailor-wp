<!DOCTYPE html>
<html <?php language_attributes(); ?>>

<head>
  <meta charset="<?php bloginfo('charset'); ?>">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <link rel="profile" href="https://gmpg.org/xfn/11">
  <?php wp_head(); ?>
</head>

<body <?php body_class('index-page'); ?>>
<?php wp_body_open(); ?>

  <header id="header" class="header d-flex align-items-center sticky-top">
    <div class="container-fluid container-xl position-relative d-flex align-items-center justify-content-between">

      <a href="<?php echo esc_url(home_url('/')); ?>" class="logo d-flex align-items-center me-auto">
        <?php if (function_exists('has_custom_logo') && has_custom_logo()) : ?>
          <?php the_custom_logo(); ?>
        <?php else : ?>
          <h1 class="sitename"><?php bloginfo('name'); ?></h1>
        <?php endif; ?>
      </a>

      <nav id="navmenu" class="navmenu">
        <?php
        if (has_nav_menu('primary')) {
            wp_nav_menu(array(
                'theme_location' => 'primary',
                'container'      => false,
                'menu_class'     => '',
                'fallback_cb'    => false,
                'walker'         => class_exists('Sailor_Nav_Walker') ? new Sailor_Nav_Walker() : '',
            ));
        } else {
            // Perfect Ordered Fallback Menu matching Sailor Template
            $menu_items = array(
                array('title' => 'Home',         'url' => home_url('/'),             'slug' => 'home'),
                array('title' => 'About',        'url' => home_url('/about/'),        'slug' => 'about'),
                array('title' => 'Services',     'url' => home_url('/services/'),     'slug' => 'services'),
                array('title' => 'Portfolio',    'url' => home_url('/portfolio/'),    'slug' => 'portfolio'),
                array('title' => 'Testimonials', 'url' => home_url('/testimonials/'), 'slug' => 'testimonials'),
                array('title' => 'Blog',         'url' => home_url('/blog/'),         'slug' => 'blog'),
                array('title' => 'Contact',      'url' => home_url('/contact/'),      'slug' => 'contact'),
            );

            echo '<ul>';
            foreach ($menu_items as $item) {
                $is_active = (is_front_page() && $item['slug'] === 'home') || (is_page($item['slug']));
                $active_class = $is_active ? ' class="active"' : '';
                echo '<li><a href="' . esc_url($item['url']) . '"' . $active_class . '>' . esc_html($item['title']) . '</a></li>';
            }
            echo '</ul>';
        }
        ?>

        <i class="mobile-nav-toggle d-xl-none bi bi-list"></i>
      </nav>

      <a class="btn-getstarted d-none d-md-inline-block" href="<?php echo esc_url(home_url('/about/')); ?>">Get Started</a>

    </div>

  </header>

  <main class="main">