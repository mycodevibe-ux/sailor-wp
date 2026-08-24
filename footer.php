  </main>

  <footer id="footer" class="footer dark-background">

    <div class="container footer-top">
      <div class="row gy-4">
        
        <!-- Column 1: About & Contact -->
        <div class="col-lg-4 col-md-6 footer-about">
          <a href="<?php echo esc_url(home_url('/')); ?>" class="logo d-flex align-items-center">
            <span class="sitename"><?php bloginfo('name'); ?></span>
          </a>
          <div class="footer-contact pt-3">
            <?php
            $address = function_exists('get_field') ? get_field('address', 'option') : '';
            $phone   = function_exists('get_field') ? get_field('phone', 'option') : '';
            $email   = function_exists('get_field') ? get_field('email', 'option') : '';
            ?>
            <p><?php echo nl2br(esc_html($address ?: 'A108 Adam Street, New York, NY 535022')); ?></p>
            <p class="mt-3"><strong>Phone:</strong> <span><?php echo esc_html($phone ?: '+1 5589 55488 55'); ?></span></p>
            <p><strong>Email:</strong> <span><?php echo esc_html($email ?: get_option('admin_email')); ?></span></p>
          </div>
          
          <div class="social-links d-flex mt-4">
            <?php
            $twitter   = function_exists('get_field') ? get_field('twitter_link', 'option') : '';
            $facebook  = function_exists('get_field') ? get_field('facebook_link', 'option') : '';
            $instagram = function_exists('get_field') ? get_field('instagram_link', 'option') : '';
            $linkedin  = function_exists('get_field') ? get_field('linkedin_link', 'option') : '';
            ?>
            <?php if ($twitter) : ?><a href="<?php echo esc_url($twitter); ?>" target="_blank" rel="noopener"><i class="bi bi-twitter-x"></i></a><?php endif; ?>
            <?php if ($facebook) : ?><a href="<?php echo esc_url($facebook); ?>" target="_blank" rel="noopener"><i class="bi bi-facebook"></i></a><?php endif; ?>
            <?php if ($instagram) : ?><a href="<?php echo esc_url($instagram); ?>" target="_blank" rel="noopener"><i class="bi bi-instagram"></i></a><?php endif; ?>
            <?php if ($linkedin) : ?><a href="<?php echo esc_url($linkedin); ?>" target="_blank" rel="noopener"><i class="bi bi-linkedin"></i></a><?php endif; ?>
          </div>
        </div>

        <!-- Column 2: Useful Links (WordPress Menu: footer_useful) -->
        <div class="col-lg-2 col-md-3 footer-links">
          <h4><?php echo esc_html(function_exists('get_field') ? (get_field('footer_col2_title', 'option') ?: 'Useful Links') : 'Useful Links'); ?></h4>
          <?php
          if (has_nav_menu('footer_useful')) {
              wp_nav_menu(array(
                  'theme_location' => 'footer_useful',
                  'container'      => false,
                  'menu_class'     => '',
                  'fallback_cb'    => false,
              ));
          } else {
              $useful_pages = get_pages(array('number' => 5, 'sort_column' => 'menu_order, post_title'));
              echo '<ul>';
              foreach ($useful_pages as $p) {
                  echo '<li><a href="' . esc_url(get_permalink($p->ID)) . '">' . esc_html($p->post_title) . '</a></li>';
              }
              echo '</ul>';
          }
          ?>
        </div>

        <!-- Column 3: Services Links (WordPress Menu: footer_services or Dynamic Services) -->
        <div class="col-lg-2 col-md-3 footer-links">
          <h4><?php echo esc_html(function_exists('get_field') ? (get_field('footer_col3_title', 'option') ?: 'Our Services') : 'Our Services'); ?></h4>
          <?php
          if (has_nav_menu('footer_services')) {
              wp_nav_menu(array(
                  'theme_location' => 'footer_services',
                  'container'      => false,
                  'menu_class'     => '',
                  'fallback_cb'    => false,
              ));
          } else {
              $front_page_id = get_option('page_on_front');
              $services = ($front_page_id && function_exists('get_field')) ? get_field('services_list', $front_page_id) : false;
              
              echo '<ul>';
              if (!empty($services)) {
                  $count = 0;
                  foreach ($services as $svc) {
                      if ($count >= 5) break;
                      $link = !empty($svc['link']) ? $svc['link'] : home_url('/services');
                      echo '<li><a href="' . esc_url($link) . '">' . esc_html($svc['title']) . '</a></li>';
                      $count++;
                  }
              } else {
                  $services_page = get_page_by_path('services');
                  $services_url  = $services_page ? get_permalink($services_page->ID) : home_url('/services');
                  $default_svcs = array('Web Development', 'UI/UX Design', 'Digital Marketing', 'SEO Optimization', 'Brand Strategy');
                  foreach ($default_svcs as $s) {
                      echo '<li><a href="' . esc_url($services_url) . '">' . esc_html($s) . '</a></li>';
                  }
              }
              echo '</ul>';
          }
          ?>
        </div>

        <!-- Column 4: Newsletter -->
        <div class="col-lg-4 col-md-12 footer-newsletter">
          <h4><?php echo esc_html(function_exists('get_field') ? (get_field('footer_newsletter_title', 'option') ?: 'Our Newsletter') : 'Our Newsletter'); ?></h4>
          <p><?php echo esc_html(function_exists('get_field') ? (get_field('footer_newsletter_desc', 'option') ?: 'Subscribe to our newsletter and receive the latest news about our products and services!') : 'Subscribe to our newsletter and receive the latest news about our products and services!'); ?></p>
          <form action="<?php echo esc_url(admin_url('admin-ajax.php')); ?>" method="post" class="php-email-form">
            <div class="newsletter-form">
              <input type="email" name="email" placeholder="Your Email" required>
              <input type="submit" value="Subscribe">
            </div>
            <div class="loading" style="display:none;">Loading</div>
            <div class="error-message" style="display:none;"></div>
            <div class="sent-message" style="display:none;">Your subscription request has been sent. Thank you!</div>
          </form>
        </div>

      </div>
    </div>

    <!-- Copyright Bar -->
    <div class="container copyright text-center mt-4">
      <?php
      $raw_copyright = function_exists('get_field') ? get_field('footer_copyright_text', 'option') : '';
      if (empty($raw_copyright)) {
          $raw_copyright = '© Copyright [sitename]. All Rights Reserved';
      }
      $copyright = str_replace(
          array('[sitename]', '[year]'),
          array('<strong class="px-1 sitename">' . esc_html(get_bloginfo('name')) . '</strong>', date('Y')),
          esc_html($raw_copyright)
      );
      ?>
      <p><?php echo wp_kses_post($copyright); ?></p>
    </div>

  </footer>

  <!-- Scroll Top -->
  <a href="#" id="scroll-top" class="scroll-top d-flex align-items-center justify-content-center"><i class="bi bi-arrow-up-short"></i></a>

  <?php wp_footer(); ?>

</body>
</html>
