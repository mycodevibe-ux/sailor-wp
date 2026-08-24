<?php
/**
 * Template Name: Testimonials Page
 * 100% Dynamic CMS Managed Testimonials Template with rich fallbacks
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

<!-- Testimonials Section -->
<section id="testimonials" class="testimonials section">
  <div class="container">
    <div class="row gy-4">
      <?php
      $testimonials_query = new WP_Query(array('post_type' => 'testimonial', 'posts_per_page' => 8, 'post_status' => 'publish'));
      $default_testimonials = array(
          array('name' => 'Saul Goodman', 'role' => 'Ceo & Founder', 'desc' => 'Proin iaculis purus consequat sem cure digni ssim donec porttitora entum suscipit rhoncus. Accusantium quam, ultricies eget id, aliquam eget nibh et. Maecen aliquam, risus at semper.', 'img' => $asset_uri . '/img/testimonials/testimonials-1.jpg'),
          array('name' => 'Sara Wilsson', 'role' => 'Designer', 'desc' => 'Export tempor illum tamen malis malis eram quae irure esse labore quem cillum quid cillum eram malis quorum velit fore eram velit sunt aliqua noster fugiat irure amet legam anim culpa.', 'img' => $asset_uri . '/img/testimonials/testimonials-2.jpg'),
          array('name' => 'Jena Karlis', 'role' => 'Store Owner', 'desc' => 'Enim nisi quem export duis labore cillum quae magna enim sint quorum nulla quem veniam duis minim tempor labore quem eram duis noster aute amet eram fore quis sint minim.', 'img' => $asset_uri . '/img/testimonials/testimonials-3.jpg'),
          array('name' => 'Matt Brandon', 'role' => 'Freelancer', 'desc' => 'Fugiat enim eram quae cillum dolore dolor amet nulla culpa multos export minim fugiat minim velit minim dolor enim duis veniam ipsum anim magna sunt elit fore quem dolore labore illum veniam.', 'img' => $asset_uri . '/img/testimonials/testimonials-4.jpg'),
          array('name' => 'John Larson', 'role' => 'Entrepreneur', 'desc' => 'Quis quorum aliqua sint quem legam fore sunt eram irure aliqua veniam tempor noster veniam enim culpa labore duis sunt culpa nulla illum cillum fugiat legam esse veniam culpa fore nisi cillum quid.', 'img' => $asset_uri . '/img/testimonials/testimonials-5.jpg'),
          array('name' => 'Emily Harison', 'role' => 'Accountant', 'desc' => 'Eius ipsam praesentium dolor quaerat inventore rerum odio. Quos laudantium adipisci eius. Accusamus qui iste cupiditate sed temporibus est aspernatur. Sequi officiis ea et quia quidem.', 'img' => $asset_uri . '/img/testimonials/testimonials-6.jpg'),
      );

      $delay = 100;
      if ($testimonials_query->have_posts()) :
        while ($testimonials_query->have_posts()) : $testimonials_query->the_post();
          $designation = function_exists('get_field') ? get_field('testimonial_designation') : 'Client';
          $photo       = get_the_post_thumbnail_url(get_the_ID(), 'thumbnail');
          if (empty($photo)) $photo = $asset_uri . '/img/testimonials/testimonials-1.jpg';
      ?>
        <div class="col-lg-6" data-aos="fade-up" data-aos-delay="<?php echo $delay; ?>">
          <div class="testimonial-item">
            <img src="<?php echo esc_url($photo); ?>" class="testimonial-img" alt="<?php the_title(); ?>">
            <h3><?php the_title(); ?></h3>
            <h4><?php echo esc_html($designation ?: 'Client'); ?></h4>
            <div class="stars"><i class="bi bi-star-fill"></i><i class="bi bi-star-fill"></i><i class="bi bi-star-fill"></i><i class="bi bi-star-fill"></i><i class="bi bi-star-fill"></i></div>
            <p>
              <i class="bi bi-quote quote-icon-left"></i>
              <span><?php echo get_the_content() ? wp_strip_all_tags(get_the_content()) : 'Lorem ipsum dolor sit amet, consectetur adipiscing elit.'; ?></span>
              <i class="bi bi-quote quote-icon-right"></i>
            </p>
          </div>
        </div>
      <?php $delay += 100; endwhile; wp_reset_postdata(); else :
        foreach ($default_testimonials as $t) :
      ?>
        <div class="col-lg-6" data-aos="fade-up" data-aos-delay="<?php echo $delay; ?>">
          <div class="testimonial-item">
            <img src="<?php echo esc_url($t['img']); ?>" class="testimonial-img" alt="<?php echo esc_attr($t['name']); ?>">
            <h3><?php echo esc_html($t['name']); ?></h3>
            <h4><?php echo esc_html($t['role']); ?></h4>
            <div class="stars"><i class="bi bi-star-fill"></i><i class="bi bi-star-fill"></i><i class="bi bi-star-fill"></i><i class="bi bi-star-fill"></i><i class="bi bi-star-fill"></i></div>
            <p>
              <i class="bi bi-quote quote-icon-left"></i>
              <span><?php echo esc_html($t['desc']); ?></span>
              <i class="bi bi-quote quote-icon-right"></i>
            </p>
          </div>
        </div>
      <?php $delay += 100; endforeach; endif; ?>
    </div>
  </div>
</section>

<?php get_footer(); ?>
