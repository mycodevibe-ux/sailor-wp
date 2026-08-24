<?php
/**
 * Template Name: About Page
 * 100% Dynamic CMS Managed About Template with rich fallbacks
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

<!-- About Section -->
<section id="about-2" class="about-2 section">
  <div class="container" data-aos="fade-up">
    <div class="row g-4 g-lg-5" data-aos="fade-up" data-aos-delay="200">

      <div class="col-lg-5">
        <div class="about-img">
          <?php if (has_post_thumbnail()) : ?>
            <?php the_post_thumbnail('large', array('class' => 'img-fluid rounded')); ?>
          <?php else : ?>
            <img src="<?php echo esc_url($asset_uri . '/img/about-portrait.jpg'); ?>" class="img-fluid rounded" alt="About Sailor">
          <?php endif; ?>
        </div>
      </div>

      <div class="col-lg-7">
        <h3 class="pt-0 pt-lg-2">Learn More About What We Do</h3>
        
        <!-- Tabs -->
        <ul class="nav nav-pills mb-3">
          <li><a class="nav-link active" data-bs-toggle="pill" href="#about-2-tab1">Overview</a></li>
          <li><a class="nav-link" data-bs-toggle="pill" href="#about-2-tab2">Our Mission</a></li>
          <li><a class="nav-link" data-bs-toggle="pill" href="#about-2-tab3">Our Vision</a></li>
        </ul>

        <div class="tab-content">
          <div class="tab-pane fade show active" id="about-2-tab1">
            <p>Consequuntur inventore voluptates consequatur aut vel et. Eos doloribus expedita. Sapiente atque consequatur minima nihil quae aspernatur quo suscipit voluptatem.</p>
            <div class="d-flex align-items-center mt-4">
              <i class="bi bi-check2"></i>
              <h4>Repudiandae rerum velit modi et officia quasi facilis</h4>
            </div>
            <p>Laborum omnis voluptates voluptas qui sit aliquam blanditiis. Sapiente minima commodi dolorum non eveniet magni quaerat nemo et.</p>
            <div class="d-flex align-items-center mt-4">
              <i class="bi bi-check2"></i>
              <h4>Incidunt non veritatis illum ea ut nisi</h4>
            </div>
            <p>Non quod totam minus repellendus autem sint velit. Rerum debitis facere soluta tenetur. Iure molestiae assumenda sunt qui inventore eligendi voluptates nisi at.</p>
          </div>

          <div class="tab-pane fade" id="about-2-tab2">
            <p>Doloribus et provident et nihil accusamus enim. Quia quo omnis voluptatem ratione eos voluptatem alias. Et dolorem non explicabo quas.</p>
          </div>

          <div class="tab-pane fade" id="about-2-tab3">
            <p>Voluptas et ea et est minima. Omnis et expedita repellendus autem sint velit. Rerum debitis facere soluta tenetur.</p>
          </div>
        </div>

      </div>

    </div>
  </div>
</section>

<!-- Team Section -->
<?php
$team_query = new WP_Query(array('post_type' => 'team', 'posts_per_page' => 4, 'post_status' => 'publish'));
?>
<section id="team" class="team section light-background">
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
</section>

<?php get_footer(); ?>
