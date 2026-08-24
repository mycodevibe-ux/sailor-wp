<?php
/**
 * Template Name: About Page
 * 100% Dynamic CMS Managed About Template
 */
get_header();
$page_id = get_the_ID();
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

      <?php if (has_post_thumbnail()) : ?>
        <div class="col-lg-5">
          <div class="about-img">
            <?php the_post_thumbnail('large', array('class' => 'img-fluid rounded')); ?>
          </div>
        </div>
        <div class="col-lg-7">
      <?php else : ?>
        <div class="col-lg-12">
      <?php endif; ?>

        <?php if (have_posts()) : while (have_posts()) : the_post(); ?>
          <h3 class="pt-0 pt-lg-2"><?php the_title(); ?></h3>


          <?php
          $mission = function_exists('get_field') ? get_field('mission_text', $page_id) : '';
          $vision  = function_exists('get_field') ? get_field('vision_text', $page_id) : '';
          ?>

          <!-- Tabs -->
          <ul class="nav nav-pills mb-3">
            <li><a class="nav-link active" data-bs-toggle="pill" href="#about-2-tab1">Overview</a></li>
            <?php if ($mission) : ?><li><a class="nav-link" data-bs-toggle="pill" href="#about-2-tab2">Our Mission</a></li><?php endif; ?>
            <?php if ($vision) : ?><li><a class="nav-link" data-bs-toggle="pill" href="#about-2-tab3">Our Vision</a></li><?php endif; ?>
          </ul>

          <div class="tab-content">
            <div class="tab-pane fade show active" id="about-2-tab1">
              <?php the_content(); ?>
            </div>
            <?php if ($mission) : ?>
              <div class="tab-pane fade" id="about-2-tab2">
                <?php echo wp_kses_post($mission); ?>
              </div>
            <?php endif; ?>
            <?php if ($vision) : ?>
              <div class="tab-pane fade" id="about-2-tab3">
                <?php echo wp_kses_post($vision); ?>
              </div>
            <?php endif; ?>
          </div>

        <?php endwhile; endif; ?>
      </div>

    </div>
  </div>
</section><!-- /About Section -->

<!-- Stats Section (Dynamic ACF Repeater: stats_list) -->
<?php
$stats_show = function_exists('get_field') ? get_field('stats_show', $page_id) : true;
$stats_list = function_exists('get_field') ? get_field('stats_list', $page_id) : false;

if ($stats_show && !empty($stats_list)) :
?>
<section id="stats" class="stats section light-background">
  <div class="container" data-aos="fade-up" data-aos-delay="100">
    <div class="row gy-4">
      <?php foreach ($stats_list as $stat) : ?>
        <div class="col-lg-3 col-md-6">
          <div class="stats-item text-center w-100 h-100">
            <span data-purecounter-start="0"
                  data-purecounter-end="<?php echo esc_attr($stat['number']); ?>"
                  data-purecounter-duration="1"
                  class="purecounter"></span>
            <p><?php echo esc_html($stat['label']); ?></p>
          </div>
        </div>
      <?php endforeach; ?>
    </div>
  </div>
</section>
<?php endif; ?><!-- /Stats Section -->

<!-- Team Section (Dynamic CPT: team) -->
<?php
$team_query = new WP_Query(array('post_type' => 'team', 'posts_per_page' => -1, 'post_status' => 'publish'));
if ($team_query->have_posts()) :
?>
<section id="team" class="team section">
  <div class="container section-title" data-aos="fade-up">
    <h2>Team</h2>
    <p>Our Hardworking Team<br></p>
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
        <div class="col-lg-6" data-aos="fade-up" data-aos-delay="<?php echo $delay; ?>">
          <div class="team-member d-flex align-items-start">
            <?php if ($img) : ?>
              <div class="pic"><img src="<?php echo esc_url($img); ?>" class="img-fluid rounded" alt="<?php the_title(); ?>"></div>
            <?php endif; ?>

            <div class="member-info">
              <h4><?php the_title(); ?></h4>
              <?php if ($role) : ?><span><?php echo esc_html($role); ?></span><?php endif; ?>
              <?php if (get_the_excerpt()) : ?><p><?php echo wp_trim_words(get_the_excerpt(), 15); ?></p><?php endif; ?>
              <div class="social">
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
<?php endif; ?><!-- /Team Section -->

<!-- Skills Section (Dynamic ACF Repeater: skills_list) -->
<?php
$skills_show = function_exists('get_field') ? get_field('skills_show', $page_id) : true;
$skills_list = function_exists('get_field') ? get_field('skills_list', $page_id) : false;

if ($skills_show && !empty($skills_list)) :
?>
<section id="skills" class="skills section">
  <div class="container section-title" data-aos="fade-up">
    <h2>Skills</h2>
    <p>Check Our Skills<br></p>
  </div>
  <div class="container" data-aos="fade-up" data-aos-delay="100">
    <div class="row skills-content skills-animation">
      <?php foreach ($skills_list as $skill) : ?>
        <div class="col-lg-6">
          <div class="progress">
            <span class="skill"><span><?php echo esc_html($skill['name']); ?></span> <i class="val"><?php echo esc_html($skill['percentage']); ?>%</i></span>
            <div class="progress-bar-wrap">
              <div class="progress-bar" role="progressbar" aria-valuenow="<?php echo esc_attr($skill['percentage']); ?>" aria-valuemin="0" aria-valuemax="100"></div>
            </div>
          </div>
        </div>
      <?php endforeach; ?>
    </div>
  </div>
</section>
<?php endif; ?><!-- /Skills Section -->

<?php get_footer(); ?>
