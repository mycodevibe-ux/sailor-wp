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
