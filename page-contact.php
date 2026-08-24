<?php
/**
 * Template Name: Contact Page
 * 100% Dynamic CMS Managed Contact Template with ACF Form Builder
 */
get_header();

$page_id = get_the_ID();

// Map Settings
$map_show = function_exists('get_field') ? get_field('contact_map_show', $page_id) : true;
$map_url  = function_exists('get_field') ? (get_field('contact_map_url', $page_id) ?: sailor_option('maps_embed_url', '')) : sailor_option('maps_embed_url', '');

// Contact Info
$address  = function_exists('get_field') ? (get_field('contact_address', $page_id) ?: sailor_option('address', "A108 Adam Street\nNew York, NY 535022")) : sailor_option('address', "A108 Adam Street\nNew York, NY 535022");
$phone    = function_exists('get_field') ? (get_field('contact_phone', $page_id) ?: sailor_option('phone', '+1 5589 55488 55')) : sailor_option('phone', '+1 5589 55488 55');
$email    = function_exists('get_field') ? (get_field('contact_email', $page_id) ?: sailor_option('email', get_option('admin_email'))) : sailor_option('email', get_option('admin_email'));

// Form Settings
$form_fields = function_exists('get_field') ? get_field('contact_form_fields', $page_id) : false;
$btn_text    = function_exists('get_field') ? (get_field('contact_btn_text', $page_id) ?: 'Send Message') : 'Send Message';
$success_msg = function_exists('get_field') ? (get_field('contact_success_msg', $page_id) ?: 'Your message has been sent. Thank you!') : 'Your message has been sent. Thank you!';
$shortcode   = function_exists('get_field') ? get_field('contact_form_shortcode', $page_id) : '';
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

<!-- Contact Section -->
<section id="contact" class="contact section">
  <div class="container" data-aos="fade-up" data-aos-delay="100">

    <!-- Google Maps Embed -->
    <?php if ($map_show !== false && $map_show !== '0' && $map_show !== 0 && !empty($map_url)) : ?>
      <div class="mb-4" data-aos="fade-up" data-aos-delay="200">
        <iframe style="border:0; width: 100%; height: 270px;"
          src="<?php echo esc_url($map_url); ?>"
          frameborder="0" allowfullscreen="" loading="lazy"
          referrerpolicy="no-referrer-when-downgrade"></iframe>
      </div>
    <?php endif; ?>

    <div class="row gy-4">

      <!-- Contact Info Sidebar -->
      <div class="col-lg-4">
        <?php if ($address) : ?>
          <div class="info-item d-flex" data-aos="fade-up" data-aos-delay="300">
            <i class="bi bi-geo-alt flex-shrink-0"></i>
            <div>
              <h3>Address</h3>
              <p><?php echo nl2br(esc_html($address)); ?></p>
            </div>
          </div>
        <?php endif; ?>

        <?php if ($phone) : ?>
          <div class="info-item d-flex" data-aos="fade-up" data-aos-delay="400">
            <i class="bi bi-telephone flex-shrink-0"></i>
            <div>
              <h3>Call Us</h3>
              <p><?php echo esc_html($phone); ?></p>
            </div>
          </div>
        <?php endif; ?>

        <?php if ($email) : ?>
          <div class="info-item d-flex" data-aos="fade-up" data-aos-delay="500">
            <i class="bi bi-envelope flex-shrink-0"></i>
            <div>
              <h3>Email Us</h3>
              <p><?php echo esc_html($email); ?></p>
            </div>
          </div>
        <?php endif; ?>
      </div>

      <!-- Contact Form Column -->
      <div class="col-lg-8">
        <?php if (!empty($shortcode)) : ?>
          <div class="custom-contact-form" data-aos="fade-up" data-aos-delay="200">
            <?php echo do_shortcode($shortcode); ?>
          </div>
        <?php else : ?>
          <form id="sailor-contact-form" class="php-email-form" data-aos="fade-up" data-aos-delay="200">
            <?php wp_nonce_field('sailor_contact_nonce', 'contact_nonce'); ?>
            <input type="hidden" name="action" value="sailor_contact">
            
            <div class="row gy-4">

              <?php
              if (!empty($form_fields)) :
                foreach ($form_fields as $field) :
                  $f_type  = $field['type'] ?: 'text';
                  $f_width = $field['width'] ?: 'col-md-6';
                  $f_label = $field['label'] ?: '';
                  $f_ph    = $field['placeholder'] ?: $f_label;
                  $f_name  = !empty($field['name']) ? sanitize_key($field['name']) : sanitize_key($f_label ?: 'field_' . rand(100, 999));
                  $f_req   = !empty($field['required']) ? 'required' : '';
              ?>
                <div class="<?php echo esc_attr($f_width); ?>">
                  <?php if ($f_label) : ?>
                    <label class="form-label small fw-bold text-muted"><?php echo esc_html($f_label); ?><?php if ($f_req) echo ' <span class="text-danger">*</span>'; ?></label>
                  <?php endif; ?>

                  <?php if ($f_type === 'textarea') : ?>
                    <textarea class="form-control" name="<?php echo esc_attr($f_name); ?>" rows="6" placeholder="<?php echo esc_attr($f_ph); ?>" <?php echo $f_req; ?>></textarea>
                  <?php else : ?>
                    <input type="<?php echo esc_attr($f_type); ?>" name="<?php echo esc_attr($f_name); ?>" class="form-control" placeholder="<?php echo esc_attr($f_ph); ?>" <?php echo $f_req; ?>>
                  <?php endif; ?>
                </div>
              <?php
                endforeach;
              else :
                // Default 4 standard inputs
              ?>
                <div class="col-md-6">
                  <input type="text" name="name" class="form-control" placeholder="Your Name" required>
                </div>
                <div class="col-md-6">
                  <input type="email" name="email" class="form-control" placeholder="Your Email" required>
                </div>
                <div class="col-md-12">
                  <input type="text" name="subject" class="form-control" placeholder="Subject" required>
                </div>
                <div class="col-md-12">
                  <textarea class="form-control" name="message" rows="6" placeholder="Message" required></textarea>
                </div>
              <?php endif; ?>

              <div class="col-md-12 text-center">
                <div class="loading">Loading</div>
                <div class="error-message"></div>
                <div class="sent-message"><?php echo esc_html($success_msg); ?></div>
                <button type="submit"><?php echo esc_html($btn_text); ?></button>
              </div>

            </div>
          </form>

          <script>
          document.addEventListener('DOMContentLoaded', function() {
            const form = document.getElementById('sailor-contact-form');
            if (!form) return;
            form.addEventListener('submit', function(e) {
              e.preventDefault();
              const loading = form.querySelector('.loading');
              const errMsg  = form.querySelector('.error-message');
              const sentMsg = form.querySelector('.sent-message');

              loading.style.display = 'block';
              errMsg.style.display  = 'none';
              sentMsg.style.display = 'none';

              const formData = new FormData(form);
              formData.append('action', 'sailor_contact');
              formData.append('nonce', '<?php echo wp_create_nonce("sailor_contact_nonce"); ?>');

              fetch('<?php echo admin_url("admin-ajax.php"); ?>', {
                method: 'POST',
                body: formData
              })
              .then(r => r.json())
              .then(data => {
                loading.style.display = 'none';
                if (data.success) {
                  sentMsg.style.display = 'block';
                  form.reset();
                } else {
                  errMsg.textContent   = data.data.message || 'An error occurred.';
                  errMsg.style.display = 'block';
                }
              })
              .catch(() => {
                loading.style.display = 'none';
                errMsg.textContent    = 'Connection error. Please try again.';
                errMsg.style.display  = 'block';
              });
            });
          });
          </script>
        <?php endif; ?>
      </div>

    </div>
  </div>
</section><!-- /Contact Section -->

<?php get_footer(); ?>
