<?php
/**
 * Blog Archive Template (100% Matches Sailor html/blog.html design with rich demo articles)
 */
get_header();
$asset_uri = get_template_directory_uri() . '/assets';
?>

<!-- Page Title -->
<div class="page-title light-background">
  <div class="container d-lg-flex justify-content-between align-items-center">
    <h1 class="mb-2 mb-lg-0">Blog</h1>
    <nav class="breadcrumbs">
      <ol>
        <li><a href="<?php echo esc_url(home_url('/')); ?>">Home</a></li>
        <li class="current">Blog</li>
      </ol>
    </nav>
  </div>
</div><!-- End Page Title -->

<div class="container my-5">
  <div class="row">

    <div class="col-lg-8">

      <!-- Blog Posts Section -->
      <section id="blog-posts" class="blog-posts section p-0">
        <div class="container">
          <div class="row gy-4">

            <?php
            $default_posts = array(
                array(
                    'title'   => 'Dolorum optio tempore voluptas dignissimos cumque fuga qui quibusdam quia',
                    'author'  => 'John Doe',
                    'date'    => 'Jan 1, 2026',
                    'excerpt' => 'Similique neque nam consequuntur ad non maxime aliquam quas. Quibusdam animi praesentium. Aliquam et laboriosam eius aut nostrum quidem aliquid dicta. Et ad quas. Enim dolor sunt sit accusantium id.',
                    'img'     => $asset_uri . '/img/blog/blog-1.jpg'
                ),
                array(
                    'title'   => 'Nisi magni odit consequatur autem nulla dolorem',
                    'author'  => 'Julia Parker',
                    'date'    => 'Jun 5, 2026',
                    'excerpt' => 'Incidunt voluptate sit temporibus aperiam. Quia vitae aut sint ullam quis illum voluptatum et. Quo libero rerum voluptatem pariatur nam. Adipisci qui cupiditate.',
                    'img'     => $asset_uri . '/img/blog/blog-2.jpg'
                ),
                array(
                    'title'   => 'Possimus soluta ut id suscipit ea ut. In quo quia et soluta libero sit sint.',
                    'author'  => 'Maria Doe',
                    'date'    => 'Jul 15, 2026',
                    'excerpt' => 'Aut iste dolores quo vel quo sint. Vero voluptatem nihil sit et voluptatem sit error. Voluptatem eligendi aut sit qui aspernatur. Laboriosam animi ut et aspernatur.',
                    'img'     => $asset_uri . '/img/blog/blog-3.jpg'
                ),
                array(
                    'title'   => 'Non rem rerum nam cum quo minus. Dolor distinctio deleniti explicabo eius exercitationem.',
                    'author'  => 'Admin',
                    'date'    => 'Aug 20, 2026',
                    'excerpt' => 'Aspernatur rerum perferendis et sint. Voluptates cupiditate voluptas atque quae. Rem veritatis nemo consequatur est velit.',
                    'img'     => $asset_uri . '/img/blog/blog-4.jpg'
                )
            );

            if (have_posts()) :
              while (have_posts()) : the_post();
                $thumb_url = get_the_post_thumbnail_url(get_the_ID(), 'large');
                if (empty($thumb_url)) $thumb_url = $asset_uri . '/img/blog/blog-1.jpg';
            ?>
              <div class="col-lg-12">
                <article class="p-4 bg-white border rounded shadow-sm mb-4">
                  <div class="post-img mb-3">
                    <a href="<?php the_permalink(); ?>">
                      <img src="<?php echo esc_url($thumb_url); ?>" alt="<?php the_title(); ?>" class="img-fluid rounded w-100" style="max-height: 380px; object-fit: cover;">
                    </a>
                  </div>

                  <h2 class="title h3 mb-3">
                    <a href="<?php the_permalink(); ?>" class="text-dark text-decoration-none"><?php the_title(); ?></a>
                  </h2>

                  <div class="meta-top text-muted small d-flex gap-3 mb-3">
                    <span><i class="bi bi-person"></i> <?php the_author(); ?></span>
                    <span><i class="bi bi-clock"></i> <time datetime="<?php echo get_the_date('c'); ?>"><?php echo get_the_date('M j, Y'); ?></time></span>
                  </div>

                  <div class="content mb-3">
                    <p class="text-muted"><?php echo wp_trim_words(get_the_excerpt(), 35); ?></p>
                  </div>

                  <div class="read-more">
                    <a href="<?php the_permalink(); ?>" class="btn btn-outline-danger btn-sm">Read More</a>
                  </div>
                </article>
              </div>
            <?php endwhile; else :
              foreach ($default_posts as $dp) :
            ?>
              <div class="col-lg-12">
                <article class="p-4 bg-white border rounded shadow-sm mb-4">
                  <div class="post-img mb-3">
                    <img src="<?php echo esc_url($dp['img']); ?>" alt="<?php echo esc_attr($dp['title']); ?>" class="img-fluid rounded w-100" style="max-height: 380px; object-fit: cover;">
                  </div>

                  <h2 class="title h3 mb-3">
                    <a href="#" class="text-dark text-decoration-none"><?php echo esc_html($dp['title']); ?></a>
                  </h2>

                  <div class="meta-top text-muted small d-flex gap-3 mb-3">
                    <span><i class="bi bi-person"></i> <?php echo esc_html($dp['author']); ?></span>
                    <span><i class="bi bi-clock"></i> <time><?php echo esc_html($dp['date']); ?></time></span>
                  </div>

                  <div class="content mb-3">
                    <p class="text-muted"><?php echo esc_html($dp['excerpt']); ?></p>
                  </div>

                  <div class="read-more">
                    <a href="#" class="btn btn-outline-danger btn-sm">Read More</a>
                  </div>
                </article>
              </div>
            <?php endforeach; endif; ?>

          </div>
        </div>
      </section>

    </div>

    <!-- Sidebar Column -->
    <div class="col-lg-4">
      <div class="widgets-container p-4 bg-white border rounded shadow-sm">

        <!-- Search Widget -->
        <div class="search-widget widget-item mb-4">
          <h4 class="widget-title h5 mb-3 border-bottom pb-2">Search</h4>
          <form action="<?php echo esc_url(home_url('/')); ?>" method="get" class="d-flex">
            <input type="text" name="s" class="form-control me-2" placeholder="Search..." value="<?php echo get_search_query(); ?>">
            <button type="submit" class="btn btn-danger"><i class="bi bi-search"></i></button>
          </form>
        </div>

        <!-- Categories Widget -->
        <div class="categories-widget widget-item mb-4">
          <h4 class="widget-title h5 mb-3 border-bottom pb-2">Categories</h4>
          <ul class="list-unstyled">
            <li class="py-1"><a href="#" class="text-muted text-decoration-none">General (2)</a></li>
            <li class="py-1"><a href="#" class="text-muted text-decoration-none">Lifestyle (4)</a></li>
            <li class="py-1"><a href="#" class="text-muted text-decoration-none">Travel (3)</a></li>
            <li class="py-1"><a href="#" class="text-muted text-decoration-none">Design (5)</a></li>
          </ul>
        </div>

        <!-- Recent Posts Widget -->
        <div class="recent-posts-widget widget-item mb-4">
          <h4 class="widget-title h5 mb-3 border-bottom pb-2">Recent Posts</h4>
          <ul class="list-unstyled">
            <li class="mb-2"><a href="#" class="text-dark text-decoration-none fw-semibold">Dolorum optio tempore voluptas</a><br><small class="text-muted">Jan 1, 2026</small></li>
            <li class="mb-2"><a href="#" class="text-dark text-decoration-none fw-semibold">Nisi magni odit consequatur</a><br><small class="text-muted">Jun 5, 2026</small></li>
            <li class="mb-2"><a href="#" class="text-dark text-decoration-none fw-semibold">Possimus soluta ut id suscipit</a><br><small class="text-muted">Jul 15, 2026</small></li>
          </ul>
        </div>

        <!-- Tags Widget -->
        <div class="tags-widget widget-item">
          <h4 class="widget-title h5 mb-3 border-bottom pb-2">Tags</h4>
          <div class="d-flex flex-wrap gap-1">
            <span class="badge bg-light text-dark border p-2">App</span>
            <span class="badge bg-light text-dark border p-2">IT</span>
            <span class="badge bg-light text-dark border p-2">Business</span>
            <span class="badge bg-light text-dark border p-2">Design</span>
            <span class="badge bg-light text-dark border p-2">Office</span>
          </div>
        </div>

      </div>
    </div>

  </div>
</div>

<?php get_footer(); ?>
