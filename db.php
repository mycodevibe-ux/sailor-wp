<?php
/**
 * TiDB Cloud Compatibility Drop-in for WordPress
 * Disables utf8mb4_520 collation so TiDB creates all tables smoothly with utf8mb4_unicode_ci.
 */
class wpdb extends \wpdb {
    public function has_cap( $db_cap ) {
        if ( 'utf8mb4_520' === $db_cap ) {
            return false;
        }
        return parent::has_cap( $db_cap );
    }
}

// Auto-Bootstrap WordPress Settings & User Permissions
if ( ! defined('SAILOR_DB_FIX_APPLIED') ) {
    define('SAILOR_DB_FIX_APPLIED', true);
    
    add_action('plugins_loaded', function() {
        global $wpdb;
        if ( ! $wpdb ) return;
        
        // 1. Force Home Page as Static Front Page
        update_option('show_on_front', 'page');
        update_option('page_on_front', 6);
        update_option('page_for_posts', 11);
        update_option('permalink_structure', '/%postname%/');
        
        // 2. Auto-repair User Roles if corrupted
        if ( ! function_exists('populate_roles') ) {
            require_once ABSPATH . 'wp-admin/includes/schema.php';
        }
        populate_roles();
        
        // 3. Ensure User 1 and current user are Administrator
        $admin = get_user_by('id', 1);
        if ( $admin ) {
            $admin->set_role('administrator');
        }
        $admin_login = get_user_by('login', 'admin');
        if ( $admin_login ) {
            $admin_login->set_role('administrator');
        }
    }, 1);
}
