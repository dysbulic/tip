<?php
/**
 * The template for displaying Archive pages.
 *
 * Used to display archive-type pages if nothing more specific matches a query.
 *
 * @package WordPress
 * @subpackage Choco
 */
get_header(); ?>
<?php
	/* Queue the first post, that way we know
	* what date we're dealing with (if that is the case).
	*
	* We reset this later so we can run the loop
	* properly with a call to rewind_posts().
	*/
	if ( have_posts() )
		the_post();
?>

					<h1 class="pagetitle">
						<?php if ( is_day() ) : ?>
							<?php printf( __( 'Daily Archives: <span>%s</span>', 'choco' ), get_the_date() ); ?>
						<?php elseif ( is_month() ) : ?>
							<?php printf( __( 'Monthly Archives: <span>%s</span>', 'choco' ), get_the_date( 'F Y' ) ); ?>
						<?php elseif ( is_year() ) : ?>
							<?php printf( __( 'Yearly Archives: <span>%s</span>', 'choco' ), get_the_date( 'Y' ) ); ?>
						<?php else : ?>
							<?php _e( 'Blog Archives', 'choco' ); ?>
						<?php endif; ?>
					</h1>
					
					<div class="list-page">
						<?php
						/* Since we called the_post() above, we need to
						* rewind the loop back to the beginning that way
						* we can run the loop properly, in full.
						*/
						rewind_posts();
						
						/* Run the loop for the archives page to output the posts.
						* If you want to overload this in a child theme then include a file
						* called loop-archive.php and that will be used instead.
						*/
						get_template_part( 'loop', 'archive' );
						?>
					</div><!-- #list-page -->

<?php get_sidebar(); ?>
<?php get_footer(); ?>