<?php
/**
 * @package WordPress
 * @subpackage Blogum
 */
?>

<?php get_header(); ?>

<div id="content" role="main">
	<div class="error404 clear">
		<div class="error404-meta">404</div>
		<div class="error404-text">
			<p><?php _e( 'The requested page could not be located on this blog. We recommend using the navigation bar or search form above to get back on track.', 'blogum' ); ?></p>
			<?php printf( __( '<a href="%s" title="&laquo; Return to the Front Page" class="error404-back">Return to the Front Page</a>', 'blogum' ), get_home_url( '/' ) ); ?>
		</div><!-- .error404-text -->
	</div><!-- .error404 -->
</div><!-- #content -->

<?php get_sidebar(); ?>

<?php get_footer(); ?>