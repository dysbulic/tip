<?php
/**
 * @package WordPress
 * @subpackage Fjords
 */
?>
<?php get_header(); ?>

<h2><?php _e( 'Not Found', 'fjords' ); ?></h2>

<p><?php _e( 'Sorry, but the page you requested cannot be found.', 'fjords' ); ?></p>

<h3><?php _e( 'Search', 'fjords' ); ?></h3>

<?php get_search_form(); ?>

<?php get_sidebar(); ?>

<?php get_footer(); ?>