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
    <div class="container-fluid container-xl position-relative d-flex align-items-center">

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
            // Dynamic Pages Fallback
            $pages = get_pages(array('sort_column' => 'menu_order, post_title', 'number' => 7));
            echo '<ul>';
            foreach ($pages as $p) {

                $is_contact = (strtolower(trim($p->post_title)) === 'contact');
                $classes = array();
                if (is_page($p->ID)) $classes[] = 'active';
                if ($is_contact) $classes[] = 'btn-getstarted';
                $class_attr = !empty($classes) ? ' class="' . implode(' ', $classes) . '"' : '';
                echo '<li><a href="' . esc_url(get_permalink($p->ID)) . '"' . $class_attr . '>' . esc_html($p->post_title) . '</a></li>';
            }
            echo '</ul>';
        }
        ?>

        <i class="mobile-nav-toggle d-xl-none bi bi-list"></i>
      </nav>

    </div>

  </header>

  <main class="main">