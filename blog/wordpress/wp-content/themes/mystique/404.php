<?php
/**
 * The template for displaying 404 pages (Not Found).
 *
 * @package WordPress
 * @subpackage Mystique
 */

get_header(); ?>

			<div id="content-container">
	 			<div id="content">
	 				<div class="error-page clear-block">
	 					<h1 class="archive-title"><span class="error"><?php _e( '404:', 'mystique' ); ?></span> <?php _e( 'The requested page was not found', 'mystique' ); ?></h1>
						<div class="entry">
							<p><?php _e( "Oops, looks like the page you're looking for has been moved or had its name changed. You may use the search box below to find what you're looking for, or start over from the", "mystique" ); ?> <?php printf( __( '<a href="%s">home page</a>.', 'mystique' ), home_url( '/' ) ); ?></p>
							<?php get_search_form(); ?>
						</div><!-- .entry -->
					</div><!-- .post -->
				</div><!-- #content -->
			</div><!-- #content-container -->

<?php get_sidebar(); ?>
<?php get_footer(); ?>