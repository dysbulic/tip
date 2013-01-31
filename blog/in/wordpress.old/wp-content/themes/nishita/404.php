<?php
/**
 * The template for displaying 404 pages (Not Found).
 *
 * @package WordPress
 * @subpackage Nishita
 */

get_header(); ?>

<?php
/* Run a check to see if there are any active widgets
 * loaded into the Primary Sidebar. If widgets are present,
 * then we add a special class (page-no-sidebar) to a wrapper
 * div in order to reduce the width of page content and 
 * allow for a widgetized sidebar to be present.
 */
if ( ! is_active_sidebar( 'primary-sidebar' ) )
	echo '<div class="main page-no-sidebar">';
else
	echo '<div class="main">';
?>
		<div class="main-inner">
			<h2 class="page-title"><?php _e( 'Error 404 - Not Found', 'nishita' ); ?></h2>
			<div class="page-body">
				<p><?php _e( 'Sorry, the page you were looking for was not found.', 'nishita' ); ?></p>
				<p><?php _e( 'Search for the page or look through the Archives.', 'nishita' ); ?></p>
			</div><!-- .page-body -->
		</div><!-- .main-inner -->
	</div><!-- .main -->

<?php get_sidebar(); ?>
<?php get_footer(); ?>