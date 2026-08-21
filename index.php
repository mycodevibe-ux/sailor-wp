<?php
/**
 * Theme Index & Blog Archive (100% Matches Sailor html/blog.html design)
 */
get_header();
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

            <?php if (have_posts()) : while (have_posts()) : the_post(); ?>
              <div class="col-lg-12">
                <article>

                  <?php if (has_post_thumbnail()) : ?>
                    <div class="post-img">
                      <a href="<?php the_permalink(); ?>">
                        <img src="<?php the_post_thumbnail_url('large'); ?>" alt="<?php the_title(); ?>" class="img-fluid">
                      </a>
                    </div>
                  <?php endif; ?>

                  <h2 class="title">
                    <a href="<?php the_permalink(); ?>"><?php the_title(); ?></a>
                  </h2>

                  <div class="meta-top">
                    <ul>
                      <li class="d-flex align-items-center"><i class="bi bi-person"></i> <a href="<?php the_permalink(); ?>"><?php the_author(); ?></a></li>
                      <li class="d-flex align-items-center"><i class="bi bi-clock"></i> <a href="<?php the_permalink(); ?>"><time datetime="<?php echo get_the_date('c'); ?>"><?php echo get_the_date('M j, Y'); ?></time></a></li>
                    </ul>
                  </div>


                  <div class="content">
                    <p><?php echo wp_trim_words(get_the_excerpt(), 35); ?></p>
                    <div class="read-more">
                      <a href="<?php the_permalink(); ?>">Read More</a>
                    </div>
                  </div>

                </article>
              </div><!-- End post list item -->
            <?php endwhile; ?>

              <!-- Blog Pagination Section -->
              <div class="col-12 d-flex justify-content-center mt-4">
                <?php
                the_posts_pagination(array(
                    'mid_size'  => 2,
                    'prev_text' => '<i class="bi bi-chevron-left"></i>',
                    'next_text' => '<i class="bi bi-chevron-right"></i>',
                ));
                ?>
              </div>

            <?php else : ?>
              <div class="col-12"><p class="text-muted">No blog posts found.</p></div>
            <?php endif; ?>

          </div><!-- End blog posts list -->

        </div>

      </section><!-- /Blog Posts Section -->

    </div>

    <!-- Sidebar Column -->
    <div class="col-lg-4">
      <div class="widgets-container">

        <!-- Search Widget -->
        <div class="search-widget widget-item">
          <h3 class="widget-title">Search</h3>
          <form action="<?php echo esc_url(home_url('/')); ?>" method="get">
            <input type="text" name="s" placeholder="Search..." value="<?php echo get_search_query(); ?>">
            <button type="submit" title="Search"><i class="bi bi-search"></i></button>
          </form>
        </div><!--/Search Widget -->

        <!-- Categories Widget -->
        <div class="categories-widget widget-item">
          <h3 class="widget-title">Categories</h3>
          <ul class="mt-3">
            <?php
            $cats = get_categories(array('hide_empty' => true));
            if (!empty($cats)) :
              foreach ($cats as $cat) :
            ?>
              <li><a href="<?php echo esc_url(get_category_link($cat->term_id)); ?>"><?php echo esc_html($cat->name); ?> <span>(<?php echo $cat->count; ?>)</span></a></li>
            <?php endforeach; else : ?>
              <li><a href="#">General <span>(1)</span></a></li>
            <?php endif; ?>
          </ul>
        </div><!--/Categories Widget -->

        <!-- Recent Posts Widget -->
        <div class="recent-posts-widget widget-item">
          <h3 class="widget-title">Recent Posts</h3>
          <?php
          $recent_posts = new WP_Query(array('post_type' => 'post', 'posts_per_page' => 5, 'post_status' => 'publish'));
          if ($recent_posts->have_posts()) :
            while ($recent_posts->have_posts()) : $recent_posts->the_post();
          ?>
            <div class="post-item">
              <?php if (has_post_thumbnail()) : ?>
                <img src="<?php the_post_thumbnail_url('thumbnail'); ?>" alt="<?php the_title(); ?>" class="flex-shrink-0">
              <?php endif; ?>
              <div>
                <h4><a href="<?php the_permalink(); ?>"><?php echo wp_trim_words(get_the_title(), 7); ?></a></h4>
                <time datetime="<?php echo get_the_date('c'); ?>"><?php echo get_the_date('M j, Y'); ?></time>
              </div>
            </div>
          <?php endwhile; wp_reset_postdata(); endif; ?>
        </div><!--/Recent Posts Widget -->

        <!-- Tags Widget -->
        <div class="tags-widget widget-item">
          <h3 class="widget-title">Tags</h3>
          <ul>
            <?php
            $tags = get_tags(array('hide_empty' => false));
            if (!empty($tags)) :
              foreach ($tags as $t) :
            ?>
              <li><a href="<?php echo esc_url(get_tag_link($t->term_id)); ?>"><?php echo esc_html($t->name); ?></a></li>
            <?php endforeach; else : ?>
              <li><a href="#">App</a></li>
              <li><a href="#">IT</a></li>
              <li><a href="#">Business</a></li>
              <li><a href="#">Design</a></li>
            <?php endif; ?>
          </ul>
        </div><!--/Tags Widget -->

      </div>
    </div>

  </div>
</div>

<?php get_footer(); ?>