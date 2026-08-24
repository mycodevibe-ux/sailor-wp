<?php
/**
 * archive.php - Clean Archive Template for Sailor Theme
 */
if (is_post_type_archive('portfolio') || get_query_var('post_type') === 'portfolio') {
    require __DIR__ . '/archive-portfolio.php';
} else {
    require __DIR__ . '/home.php';
}