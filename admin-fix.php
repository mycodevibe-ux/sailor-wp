<?php
/**
 * 1-Click Instant Auto-Login & Permissions Repair
 */
require_once dirname(__DIR__, 3) . '/wp-load.php';

global $wpdb;

// 1. Fix URLs
update_option('siteurl', 'https://sailor-wp.onrender.com');
update_option('home', 'https://sailor-wp.onrender.com');

// 2. Fix Static Homepage
$home = get_page_by_path('home');
if ($home) {
    update_option('show_on_front', 'page');
    update_option('page_on_front', $home->ID);
}
$blog = get_page_by_path('blog');
if ($blog) {
    update_option('page_for_posts', $blog->ID);
}

// 3. Fix All User Capabilities & Levels
$prefix = $wpdb->prefix;
$wpdb->query("UPDATE {$wpdb->usermeta} SET meta_value = 'a:1:{s:13:\"administrator\";b:1;}' WHERE meta_key = '{$prefix}capabilities'");
$wpdb->query("UPDATE {$wpdb->usermeta} SET meta_value = '10' WHERE meta_key = '{$prefix}user_level'");

// 4. Populate default roles if missing
require_once ABSPATH . 'wp-admin/includes/schema.php';
populate_roles();

// 5. Authenticate user 1 and redirect to Dashboard
wp_clear_auth_cookie();
wp_set_current_user(1);
wp_set_auth_cookie(1, true, is_ssl());

wp_redirect(admin_url());
exit;
